<?php
/**
 * Header Template - Student Task Manager
 * Praktikum 3 Komputasi Awan - Allfridho Prima Anjaynata Saragi (102062400045)
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

$db = get_db();
$dbInfo = $db->getInfo();
$pageTitle = isset($pageTitle) ? $pageTitle . ' - ' . APP_NAME : APP_NAME . ' (Azure Cloud)';
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="Aplikasi CRUD Student Task Manager untuk Praktikum 3 Komputasi Awan di Microsoft Azure App Service.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=<?= APP_VERSION ?>">
</head>
<body>

    <!-- Sticky Navigation Bar -->
    <header class="navbar">
        <div class="container nav-container">
            <a href="index.php" class="brand">
                <div class="brand-logo">STM</div>
                <div class="brand-text">
                    <h1><?= e(APP_NAME) ?></h1>
                    <span class="tagline">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z"/></svg>
                        Azure App Service (PHP <?= phpversion() ?>)
                    </span>
                </div>
            </a>

            <div class="nav-status">
                <?php if ($dbInfo['connected']): ?>
                    <span class="status-chip" title="Terkoneksi ke <?= e($dbInfo['driver']) ?> (<?= e($dbInfo['host']) ?>)">
                        <span class="status-dot"></span>
                        DB: <?= e(strtoupper($dbInfo['driver'])) ?> Connected
                    </span>
                <?php else: ?>
                    <span class="status-chip" style="border-color: rgba(239, 68, 68, 0.4);" title="<?= e($dbInfo['error']) ?>">
                        <span class="status-dot danger"></span>
                        DB Offline
                    </span>
                <?php endif; ?>

                <a href="create.php" class="btn btn-primary btn-sm">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Tugas
                </a>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="container" style="flex: 1; padding-top: 15px; padding-bottom: 30px;">
        <!-- Global Flash Alerts -->
        <?php if ($flash): ?>
            <div class="alert alert-<?= e($flash['type']) ?>" role="alert">
                <span><?= e($flash['message']) ?></span>
                <button type="button" class="alert-close" aria-label="Tutup">&times;</button>
            </div>
        <?php endif; ?>

        <?php if (!$dbInfo['connected']): ?>
            <div class="alert alert-danger" role="alert">
                <div>
                    <strong>Peringatan Database:</strong> Tidak dapat terhubung ke server database. 
                    <span style="font-size: 0.78rem; opacity: 0.85;">(<?= e($dbInfo['error']) ?>)</span>
                </div>
            </div>
        <?php endif; ?>
