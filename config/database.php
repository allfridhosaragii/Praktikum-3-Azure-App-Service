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
    private $dbPath;
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
            'path'      => $this->dbPath,
            'error'     => $this->errorMessage
        ];
    }

    private function resolveSqlitePath() {
        $customPath = getenv('DB_PATH');
        if (!empty($customPath)) {
            $dir = dirname($customPath);
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            return $customPath;
        }

        // Deteksi lingkungan Microsoft Azure App Service Linux
        $isAzure = getenv('WEBSITE_SITE_NAME') !== false || is_dir('/home/site');

        if ($isAzure) {
            // Prioritas 1: /home/data/student-task-manager/ (Persisten di luar wwwroot)
            $primaryDir = '/home/data/student-task-manager';
            if (!is_dir($primaryDir)) {
                @mkdir($primaryDir, 0755, true);
            }

            // Prioritas 2 (Fallback): /home/site/data/ (Juga persisten di luar wwwroot)
            $targetDir = (is_dir($primaryDir) && is_writable($primaryDir)) ? $primaryDir : '/home/site/data';
            if (!is_dir($targetDir)) {
                @mkdir($targetDir, 0755, true);
            }

            $newDbPath = $targetDir . '/tasks.db';
            $oldDbPath = dirname(__DIR__) . '/database/tasks.db';

            // Migrasi Aman: Jika database baru belum ada, salin dari database lama di wwwroot
            if (!file_exists($newDbPath) && file_exists($oldDbPath)) {
                if (@copy($oldDbPath, $newDbPath)) {
                    error_log("[Database Security Migration] Database berhasil dimigrasikan dari $oldDbPath ke $newDbPath");
                }
            }

            // Validasi Keamanan: Jika database baru di /home/data/ sudah aktif dan valid,
            // bersihkan berkas lama di wwwroot/database/ agar tidak terekspos ke HTTP publik!
            if (file_exists($newDbPath) && filesize($newDbPath) > 0 && file_exists($oldDbPath)) {
                // Hapus berkas lama dari wwwroot demi kepatuhan audit keamanan
                @unlink($oldDbPath);
                error_log("[Database Security Hardening] Berkas lama di wwwroot/database/tasks.db berhasil dihapus untuk mencegah akses publik");
            }

            return $newDbPath;
        }

        // Lingkungan Pengembangan Lokal (Windows / Dev)
        $localDir = dirname(__DIR__) . '/database';
        if (!is_dir($localDir)) {
            @mkdir($localDir, 0755, true);
        }
        return $localDir . '/tasks.db';
    }

    private function connect() {
        $dbDriver = getenv('DB_DRIVER') ?: 'sqlite';
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
                // SQLite PDO dengan path terisolasi persisten di luar wwwroot
                $this->driver = 'sqlite';
                $this->dbPath = $this->resolveSqlitePath();
                $this->host = 'persistent_storage';
                $this->database = basename($this->dbPath);

                $this->pdo = new PDO("sqlite:" . $this->dbPath, null, null, $options);
                $this->initSqliteSchema();
            } elseif ($dbDriver === 'pgsql') {
                $dsn = "pgsql:host={$dbHost};port={$dbPort};dbname={$dbName}";
                if ($dbSsl) {
                    $dsn .= ";sslmode=require";
                }
                $this->pdo = new PDO($dsn, $dbUser, $dbPass, $options);
            } else {
                // MySQL / MariaDB via PDO (Jika dikonfigurasi melalui external service)
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
        // 1. Inisialisasi skema tabel tasks jika belum ada
        $sql = "CREATE TABLE IF NOT EXISTS tasks (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT,
            course TEXT NOT NULL,
            deadline TEXT NOT NULL,
            priority TEXT NOT NULL DEFAULT 'medium' CHECK (priority IN ('low', 'medium', 'high')),
            status TEXT NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'in_progress', 'completed')),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        CREATE TABLE IF NOT EXISTS app_meta (
            key TEXT PRIMARY KEY,
            value TEXT
        );
        CREATE INDEX IF NOT EXISTS idx_tasks_status ON tasks(status);
        CREATE INDEX IF NOT EXISTS idx_tasks_priority ON tasks(priority);
        CREATE INDEX IF NOT EXISTS idx_tasks_deadline ON tasks(deadline);";
        $this->pdo->exec($sql);

        // 2. Pemisahan Seeding: Cek metadata sistem 'initial_seed_done'
        // Seeding HANYA dilakukan sekali seumur hidup saat inisialisasi basis data awal.
        // Jika pengguna menghapus seluruh tugas (COUNT = 0), data TIDAK AKAN diisi ulang secara otomatis.
        $stmtMeta = $this->pdo->query("SELECT value FROM app_meta WHERE key = 'initial_seed_done'");
        $metaRow = $stmtMeta->fetch();

        if (!$metaRow) {
            // Cek apakah tabel benar-benar baru
            $stmtCount = $this->pdo->query("SELECT COUNT(*) as total FROM tasks");
            $countRow = $stmtCount->fetch();

            if ($countRow && (int)$countRow['total'] === 0) {
                $seedSql = "INSERT INTO tasks (title, description, course, deadline, priority, status) VALUES
                ('Tugas Praktikum 3 Komputasi Awan (Azure App Service)', 'Membangun aplikasi web PHP native CRUD dengan integrasi database dan deployment otomatis via GitHub Actions.', 'Komputasi Awan', '2026-11-02', 'high', 'in_progress'),
                ('Perancangan Arsitektur Cloud PaaS & SaaS', 'Studi komparasi performa dan skalabilitas arsitektur App Service Plan F1 dengan Virtual Machine IaaS.', 'Komputasi Awan', '2026-11-10', 'medium', 'pending'),
                ('Setup Lingkungan Cloud Azure for Students', 'Aktivasi akun Azure for Students dengan alokasi kredit $100 dan konfigurasi GitHub Deployment Center.', 'Sistem Operasi', '2026-10-25', 'high', 'completed');";
                $this->pdo->exec($seedSql);
            }

            // Tandai bahwa seeding inisial telah selesai agar tidak berulang
            $stmtInsertMeta = $this->pdo->prepare("INSERT INTO app_meta (key, value) VALUES ('initial_seed_done', '1')");
            $stmtInsertMeta->execute();
        }
    }
}

function get_db() {
    return Database::getInstance();
}

