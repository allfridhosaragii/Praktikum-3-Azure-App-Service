-- Data Awal Akademik Student Task Manager
-- Mahasiswa: Allfridho Prima Anjaynata Saragi (102062400045)

USE student_task_manager;

INSERT INTO tasks (title, description, course, deadline, priority, status) VALUES
('Tugas Praktikum 3 Komputasi Awan (Azure App Service)', 'Membangun aplikasi web PHP native CRUD dengan integrasi database dan deployment otomatis via GitHub Actions.', 'Komputasi Awan', '2026-11-02', 'high', 'in_progress'),
('Perancangan Arsitektur Cloud PaaS & SaaS', 'Studi komparasi performa dan skalabilitas arsitektur App Service Plan F1 dengan Virtual Machine IaaS.', 'Komputasi Awan', '2026-11-10', 'medium', 'pending'),
('Setup Lingkungan Cloud Azure for Students', 'Aktivasi akun Azure for Students dengan alokasi kredit $100 dan konfigurasi GitHub Deployment Center.', 'Sistem Operasi', '2026-10-25', 'high', 'completed');
