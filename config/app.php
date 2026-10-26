<?php
/**
 * Konfigurasi Utama Aplikasi Student Task Manager
 * Praktikum 3 Komputasi Awan - Deploy Web App melalui Azure App Service
 * Mahasiswa: Allfridho Prima Anjaynata Saragi (102062400045)
 */

if (ob_get_level() === 0) {
    ob_start();
}

if (!defined('APP_NAME')) {
    define('APP_NAME', 'Student Task Manager');
}

if (!defined('APP_ENV')) {
    define('APP_ENV', getenv('APP_ENV') ?: 'production');
}

if (!defined('APP_VERSION')) {
    define('APP_VERSION', '3.0.2-CI-CD');
}

// Pengaturan Timezone Indonesia (WIB)
date_default_timezone_set('Asia/Jakarta');

// Start PHP Session jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

