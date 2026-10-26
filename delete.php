<?php
/**
 * Operasi DELETE - Hapus Data Tugas
 * Mengharuskan metode HTTP POST & Verifikasi CSRF Token
 * Praktikum 3 Komputasi Awan - Deploy Web App melalui Azure App Service
 * Mahasiswa: Allfridho Prima Anjaynata Saragi (NIM: 102062400045)
 */

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// 1. Tolak Request Non-POST Secara Tegas (Audit Keamanan)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    set_flash('danger', 'Akses Ditolak (HTTP 405): Penghapusan data hanya diizinkan melalui metode POST berotentikasi CSRF.');
    header('Location: index.php');
    exit;
}

// 2. Verifikasi Token CSRF
$token = $_POST['csrf_token'] ?? '';
if (!verify_csrf_token($token)) {
    set_flash('danger', 'Token CSRF tidak valid atau sesi telah kedaluwarsa.');
    header('Location: index.php');
    exit;
}

// 3. Validasi ID Tugas
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    set_flash('danger', 'ID tugas yang akan dihapus tidak valid.');
    header('Location: index.php');
    exit;
}

// 4. Eksekusi Hapus Data via Prepared Statement
$db = get_db();
$pdo = $db->getConnection();
if (!$pdo) {
    set_flash('danger', 'Koneksi database offline. Data tidak dapat dihapus.');
    header('Location: index.php');
    exit;
}

try {
    // Ambil judul untuk konfirmasi pesan
    $stmtFind = $pdo->prepare("SELECT title FROM tasks WHERE id = :id LIMIT 1");
    $stmtFind->execute([':id' => $id]);
    $task = $stmtFind->fetch();

    if (!$task) {
        set_flash('warning', 'Data tugas #' . $id . ' tidak ditemukan di database.');
        header('Location: index.php');
        exit;
    }

    $stmtDelete = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
    $stmtDelete->execute([':id' => $id]);

    set_flash('success', 'Tugas #' . $id . ' ("' . $task['title'] . '") berhasil dihapus permanen dari database!');
} catch (PDOException $e) {
    error_log("[Delete Error] " . $e->getMessage());
    set_flash('danger', 'Gagal menghapus data dari database: ' . $e->getMessage());
}

header('Location: index.php');
exit;
