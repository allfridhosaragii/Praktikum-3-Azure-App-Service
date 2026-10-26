<?php
/**
 * Abstraksi Koneksi Database via PHP PDO
 * Kompatibel dengan Azure App Service (App Settings / Environment Variables)
 * Praktikum 3 Komputasi Awan - Allfridho Prima Anjaynata Saragi (102062400045)
 */

require_once __DIR__ . '/app.php';

// Fungsi helper membaca file .env lokal jika tersedia
function load_env_file($path) {
    if (!file_exists($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $name = trim($parts[0]);
            $val = trim($parts[1], " \t\n\r\0\x0B\"'");
            if (getenv($name) === false) {
                putenv("{$name}={$val}");
                $_ENV[$name] = $val;
                $_SERVER[$name] = $val;
            }
        }
    }
}

// Muat .env lokal jika ada (hanya di lingkungan dev lokal)
load_env_file(dirname(__DIR__) . '/.env');

class Database {
    private static $instance = null;
    private $pdo;
    private $driver;
    private $host;
    private $database;
    private $connected = false;
    private $errorMessage = null;

    private function __construct() {
        $this->connect();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function isConnected() {
        return $this->connected;
    }

    public function getErrorMessage() {
        return $this->errorMessage;
    }

    public function getInfo() {
        return [
            'connected' => $this->connected,
            'driver'    => $this->driver,
            'host'      => $this->host,
            'database'  => $this->database,
            'error'     => $this->errorMessage
        ];
    }

    private function connect() {
        $dbDriver = getenv('DB_DRIVER') ?: 'mysql';
        $dbHost   = getenv('DB_HOST') ?: '';
        $dbPort   = getenv('DB_PORT') ?: ($dbDriver === 'pgsql' ? '5432' : '3306');
        $dbName   = getenv('DB_NAME') ?: 'student_task_manager';
        $dbUser   = getenv('DB_USER') ?: 'root';
        $dbPass   = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '';
        $dbSsl    = filter_var(getenv('DB_SSL'), FILTER_VALIDATE_BOOLEAN);

        $this->driver   = $dbDriver;
        $this->host     = $dbHost ?: 'localhost';
        $this->database = $dbName;

        try {
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            if ($dbDriver === 'sqlite' || (empty($dbHost) && !getenv('DB_USER') && !getenv('DB_HOST'))) {
                // Fallback SQLite lokal yang sepenuhnya mandiri jika belum ada MySQL host
                $this->driver = 'sqlite';
                $dbPath = dirname(__DIR__) . '/database/tasks.db';
                $this->host = 'local_file';
                $this->database = basename($dbPath);
                $this->pdo = new PDO("sqlite:" . $dbPath, null, null, $options);
                $this->initSqliteSchema();
            } elseif ($dbDriver === 'pgsql') {
                $dsn = "pgsql:host={$dbHost};port={$dbPort};dbname={$dbName}";
                if ($dbSsl) {
                    $dsn .= ";sslmode=require";
                }
                $this->pdo = new PDO($dsn, $dbUser, $dbPass, $options);
            } else {
                // Default: MySQL / MariaDB via PDO
                $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
                if ($dbSsl) {
                    $options[PDO::MYSQL_ATTR_SSL_CA] = true;
                    $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
                }
                $this->pdo = new PDO($dsn, $dbUser, $dbPass, $options);
            }

            $this->connected = true;
        } catch (PDOException $e) {
            $this->connected = false;
            $this->errorMessage = $e->getMessage();
            error_log("[Database Connection Error] " . $e->getMessage());
        }
    }

    private function initSqliteSchema() {
        $sql = "CREATE TABLE IF NOT EXISTS tasks (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT,
            course TEXT NOT NULL,
            deadline TEXT NOT NULL,
            priority TEXT NOT NULL DEFAULT 'medium',
            status TEXT NOT NULL DEFAULT 'pending',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        CREATE INDEX IF NOT EXISTS idx_tasks_status ON tasks(status);
        CREATE INDEX IF NOT EXISTS idx_tasks_priority ON tasks(priority);
        CREATE INDEX IF NOT EXISTS idx_tasks_deadline ON tasks(deadline);";
        $this->pdo->exec($sql);

        // Cek data awal
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM tasks");
        $row = $stmt->fetch();
        if ($row && (int)$row['total'] === 0) {
            $seedSql = "INSERT INTO tasks (title, description, course, deadline, priority, status) VALUES
            ('Tugas Praktikum 3 Komputasi Awan (Azure App Service)', 'Membangun aplikasi web PHP native CRUD dengan integrasi database dan deployment otomatis via GitHub Actions.', 'Komputasi Awan', '2026-11-02', 'high', 'in_progress'),
            ('Perancangan Arsitektur Cloud PaaS & SaaS', 'Studi komparasi performa dan skalabilitas arsitektur App Service Plan F1 dengan Virtual Machine IaaS.', 'Komputasi Awan', '2026-11-10', 'medium', 'pending'),
            ('Setup Lingkungan Cloud Azure for Students', 'Aktivasi akun Azure for Students dengan alokasi kredit $100 dan konfigurasi GitHub Deployment Center.', 'Sistem Operasi', '2026-10-25', 'high', 'completed');";
            $this->pdo->exec($seedSql);
        }
    }
}

function get_db() {
    return Database::getInstance();
}
