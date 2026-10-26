<?php
/**
 * Fungsi Pembantu & Keamanan Aplikasi Student Task Manager
 * Praktikum 3 Komputasi Awan - Allfridho Prima Anjaynata Saragi (102062400045)
 */

require_once __DIR__ . '/../config/app.php';

/**
 * Sanitasi & Escaping Output HTML untuk Mencegah Stored/Reflected XSS
 */
function e($string) {
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitasi Input String Umum
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return trim((string)$data);
}

/**
 * Pembuatan & Pengambilan Token CSRF Kriptografis
 */
function get_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validasi Token CSRF Menggunakan Algoritma Timing-Safe
 */
function verify_csrf_token($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Pengaturan Pesan Notifikasi (Flash Message)
 */
function set_flash($type, $message) {
    $_SESSION['flash'] = [
        'type'    => $type, // 'success', 'danger', 'info', 'warning'
        'message' => $message
    ];
}

/**
 * Pengambilan dan Pembersihan Pesan Notifikasi
 */
function get_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Format Tanggal Indonesia yang Rapi
 */
function format_date($dateStr) {
    if (empty($dateStr)) return '-';
    $timestamp = strtotime($dateStr);
    if (!$timestamp) return e($dateStr);
    
    $bulanIndo = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
        7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
    ];
    $d = date('d', $timestamp);
    $m = (int)date('m', $timestamp);
    $y = date('Y', $timestamp);
    
    return "{$d} {$bulanIndo[$m]} {$y}";
}

/**
 * Komponen Badge Prioritas Berbasis CSS
 */
function badge_priority($priority) {
    $p = strtolower(trim((string)$priority));
    switch ($p) {
        case 'high':
            return '<span class="badge badge-priority-high"><i class="icon-alert"></i> Tinggi</span>';
        case 'medium':
            return '<span class="badge badge-priority-medium"><i class="icon-circle"></i> Sedang</span>';
        case 'low':
            return '<span class="badge badge-priority-low"><i class="icon-check"></i> Rendah</span>';
        default:
            return '<span class="badge badge-secondary">' . e($priority) . '</span>';
    }
}

/**
 * Komponen Badge Status Berbasis CSS
 */
function badge_status($status) {
    $s = strtolower(trim((string)$status));
    switch ($s) {
        case 'completed':
            return '<span class="badge badge-status-completed"><i class="icon-completed"></i> Selesai</span>';
        case 'in_progress':
            return '<span class="badge badge-status-in-progress"><i class="icon-progress"></i> Dikerjakan</span>';
        case 'pending':
            return '<span class="badge badge-status-pending"><i class="icon-pending"></i> Belum Selesai</span>';
        default:
            return '<span class="badge badge-secondary">' . e($status) . '</span>';
    }
}

/**
 * Validasi Server-Side Input Tugas
 */
function validate_task_input($data) {
    $errors = [];

    $title = trim($data['title'] ?? '');
    $course = trim($data['course'] ?? '');
    $deadline = trim($data['deadline'] ?? '');
    $priority = trim($data['priority'] ?? 'medium');
    $status = trim($data['status'] ?? 'pending');

    if (empty($title)) {
        $errors['title'] = 'Judul tugas wajib diisi.';
    } elseif (mb_strlen($title) > 255) {
        $errors['title'] = 'Judul tugas maksimal 255 karakter.';
    }

    if (empty($course)) {
        $errors['course'] = 'Mata kuliah wajib diisi.';
    } elseif (mb_strlen($course) > 150) {
        $errors['course'] = 'Mata kuliah maksimal 150 karakter.';
    }

    if (empty($deadline)) {
        $errors['deadline'] = 'Deadline tugas wajib ditentukan.';
    } else {
        $d = DateTime::createFromFormat('Y-m-d', $deadline);
        if (!$d || $d->format('Y-m-d') !== $deadline) {
            $errors['deadline'] = 'Format tanggal deadline tidak valid (harus YYYY-MM-DD).';
        }
    }

    if (!in_array($priority, ['low', 'medium', 'high'], true)) {
        $errors['priority'] = 'Tingkat prioritas harus salah satu dari: low, medium, high.';
    }

    if (!in_array($status, ['pending', 'in_progress', 'completed'], true)) {
        $errors['status'] = 'Status tugas harus salah satu dari: pending, in_progress, completed.';
    }

    return $errors;
}
