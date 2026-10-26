-- ==============================================================================
-- Skema Basis Data SQLite - Student Task Manager (Azure Edition)
-- Mata Kuliah: Komputasi Awan (Praktikum 3)
-- Mahasiswa: Allfridho Prima Anjaynata Saragi (NIM: 102062400045)
-- Engine: SQLite 3 via PHP PDO (PDO_SQLITE)
-- Storage: Persistent Cloud Volume (/home/data/student-task-manager/tasks.db)
-- ==============================================================================

-- 1. Tabel Utama Tugas Akademik
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

-- 2. Metadata Sistem untuk Pemisahan Seeding Inisial
CREATE TABLE IF NOT EXISTS app_meta (
    key TEXT PRIMARY KEY,
    value TEXT
);

-- 3. Indeks Performa Kueri
CREATE INDEX IF NOT EXISTS idx_tasks_status ON tasks(status);
CREATE INDEX IF NOT EXISTS idx_tasks_priority ON tasks(priority);
CREATE INDEX IF NOT EXISTS idx_tasks_deadline ON tasks(deadline);
