-- Skema Tabel Database Student Task Manager
-- Praktikum 3 Komputasi Awan (Deploy Web App melalui Azure App Service)
-- Mahasiswa: Allfridho Prima Anjaynata Saragi (NIM: 102062400045)

CREATE DATABASE IF NOT EXISTS student_task_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE student_task_manager;

CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    course VARCHAR(150) NOT NULL,
    deadline DATE NOT NULL,
    priority ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'medium',
    status ENUM('pending', 'in_progress', 'completed') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tasks_status (status),
    INDEX idx_tasks_priority (priority),
    INDEX idx_tasks_deadline (deadline)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
