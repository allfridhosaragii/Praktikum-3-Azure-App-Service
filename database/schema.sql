-- ==============================================================================
-- Skema Basis Data Student Task Manager (Praktikum 3 Komputasi Awan)
-- Mahasiswa: Allfridho Prima Anjaynata Saragi (NIM: 102062400045)
-- Dosen Pengampu: M. Nizar P. Ma'ady, S.Kom., M.IM., M.Kom. & Rizqi Ramadhan, S.T., M.Kom.
-- ==============================================================================
-- CATATAN ARSITEKTUR BASIS DATA:
-- Aplikasi secara aktif berjalan menggunakan SQLite 3 via PHP PDO (PDO_SQLITE)
-- yang disimpan pada volume persisten di luar web root:
-- /home/data/student-task-manager/tasks.db (Bebas Biaya / $0.00).
-- Di bawah ini disediakan DDL SQLite (Aktif) dan DDL MySQL (Alternatif Opsional).
-- ==============================================================================

-- BAGIAN A: SKEMA AKTIF - SQLITE (Digunakan pada Azure App Service)
CREATE TABLE IF NOT EXISTS tasks (
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
CREATE INDEX IF NOT EXISTS idx_tasks_deadline ON tasks(deadline);

-- ==============================================================================
-- BAGIAN B: SKEMA ALTERNATIF - MYSQL / MARIADB (Jika menggunakan Azure Database for MySQL)
-- ==============================================================================
-- CREATE DATABASE IF NOT EXISTS student_task_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE student_task_manager;
-- 
-- CREATE TABLE IF NOT EXISTS tasks_mysql (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     title VARCHAR(255) NOT NULL,
--     description TEXT NULL,
--     course VARCHAR(150) NOT NULL,
--     deadline DATE NOT NULL,
--     priority ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'medium',
--     status ENUM('pending', 'in_progress', 'completed') NOT NULL DEFAULT 'pending',
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
--     INDEX idx_tasks_status (status),
--     INDEX idx_tasks_priority (priority),
--     INDEX idx_tasks_deadline (deadline)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
