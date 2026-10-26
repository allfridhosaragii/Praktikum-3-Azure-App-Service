<?php
/**
 * Halaman Utama (Dashboard & Read Tasks)
 * Praktikum 3 Komputasi Awan - Deploy Web App melalui Azure App Service
 * Mahasiswa: Allfridho Prima Anjaynata Saragi (NIM: 102062400045)
 */

$pageTitle = 'Dashboard Tugas';
require_once __DIR__ . '/includes/header.php';

$pdo = $db->getConnection();
$tasks = [];
$stats = [
    'total'       => 0,
    'pending'     => 0,
    'in_progress' => 0,
    'completed'   => 0,
];

if ($pdo) {
    try {
        // 1. Ambil Statistik Metrik
        $statStmt = $pdo->query("SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress,
            SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
            FROM tasks");
        $statRow = $statStmt->fetch();
        if ($statRow) {
            $stats['total']       = (int)($statRow['total'] ?? 0);
            $stats['pending']     = (int)($statRow['pending'] ?? 0);
            $stats['in_progress'] = (int)($statRow['in_progress'] ?? 0);
            $stats['completed']   = (int)($statRow['completed'] ?? 0);
        }

        // 2. Filter & Parameter Pencarian
        $search   = trim($_GET['q'] ?? '');
        $status   = trim($_GET['status'] ?? 'all');
        $priority = trim($_GET['priority'] ?? 'all');

        $whereClauses = [];
        $params = [];

        if (!empty($search)) {
            $whereClauses[] = "(title LIKE :search OR course LIKE :search OR description LIKE :search)";
            $params[':search'] = "%{$search}%";
        }

        if ($status !== 'all' && in_array($status, ['pending', 'in_progress', 'completed'], true)) {
            $whereClauses[] = "status = :status";
            $params[':status'] = $status;
        }

        if ($priority !== 'all' && in_array($priority, ['low', 'medium', 'high'], true)) {
            $whereClauses[] = "priority = :priority";
            $params[':priority'] = $priority;
        }

        $sql = "SELECT * FROM tasks";
        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }
        $sql .= " ORDER BY deadline ASC, id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $tasks = $stmt->fetchAll();

    } catch (PDOException $e) {
        error_log("[Index Query Error] " . $e->getMessage());
    }
}
?>

<!-- Hero Banner Header -->
<section class="hero-section">
    <div class="hero-banner">
        <div class="hero-content">
            <h2>Student Task Manager</h2>
            <p>Sistem manajemen tugas perkuliahan terdistribusi berbasis PHP native dan database SQL, dideploy pada Microsoft Azure App Service (Plan Linux Free F1) menggunakan pipeline otomatis GitHub Actions CI/CD.</p>
            <div class="hero-meta">
                <span>Mahasiswa: <strong>Allfridho Prima Anjaynata Saragi</strong></span>
                <span>NIM: <strong>102062400045</strong></span>
                <span>Mata Kuliah: <strong>Komputasi Awan</strong></span>
            </div>
        </div>
        <div>
            <a href="create.php" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Tugas Baru
            </a>
        </div>
    </div>
</section>

<!-- Metrics Summary Grid -->
<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-info">
            <div class="metric-label">Total Tugas</div>
            <div class="metric-value"><?= $stats['total'] ?></div>
        </div>
        <div class="metric-icon icon-blue">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-info">
            <div class="metric-label">Belum Selesai</div>
            <div class="metric-value"><?= $stats['pending'] ?></div>
        </div>
        <div class="metric-icon icon-amber">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-info">
            <div class="metric-label">Sedang Dikerjakan</div>
            <div class="metric-value"><?= $stats['in_progress'] ?></div>
        </div>
        <div class="metric-icon icon-cyan">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-info">
            <div class="metric-label">Selesai</div>
            <div class="metric-value"><?= $stats['completed'] ?></div>
        </div>
        <div class="metric-icon icon-emerald">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
    </div>
</div>

<!-- Action Bar: Search & Filter Controls -->
<div class="action-bar">
    <div class="filter-group">
        <div class="search-box">
            <span class="search-icon">🔍</span>
            <input type="text" id="search-input" placeholder="Cari judul atau mata kuliah..." value="<?= e($_GET['q'] ?? '') ?>">
        </div>

        <select id="status-filter" class="filter-select">
            <option value="all" <?= (!isset($_GET['status']) || $_GET['status'] === 'all') ? 'selected' : '' ?>>Semua Status</option>
            <option value="pending" <?= (isset($_GET['status']) && $_GET['status'] === 'pending') ? 'selected' : '' ?>>Belum Selesai</option>
            <option value="in_progress" <?= (isset($_GET['status']) && $_GET['status'] === 'in_progress') ? 'selected' : '' ?>>Sedang Dikerjakan</option>
            <option value="completed" <?= (isset($_GET['status']) && $_GET['status'] === 'completed') ? 'selected' : '' ?>>Selesai</option>
        </select>

        <select id="priority-filter" class="filter-select">
            <option value="all" <?= (!isset($_GET['priority']) || $_GET['priority'] === 'all') ? 'selected' : '' ?>>Semua Prioritas</option>
            <option value="high" <?= (isset($_GET['priority']) && $_GET['priority'] === 'high') ? 'selected' : '' ?>>Tinggi (High)</option>
            <option value="medium" <?= (isset($_GET['priority']) && $_GET['priority'] === 'medium') ? 'selected' : '' ?>>Sedang (Medium)</option>
            <option value="low" <?= (isset($_GET['priority']) && $_GET['priority'] === 'low') ? 'selected' : '' ?>>Rendah (Low)</option>
        </select>
    </div>

    <div>
        <span style="font-size: 0.8rem; color: #94a3b8;">Menampilkan <strong><?= count($tasks) ?></strong> tugas</span>
    </div>
</div>

<!-- Task Table Card -->
<div class="table-card">
    <div class="table-header">
        <h3>Daftar Tugas Akademik</h3>
        <span style="font-size: 0.78rem; color: #64748b;">Operasi CRUD Aktif</span>
    </div>

    <div class="table-responsive">
        <table class="task-table">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Judul Tugas & Deskripsi</th>
                    <th style="width: 170px;">Mata Kuliah</th>
                    <th style="width: 130px;">Deadline</th>
                    <th style="width: 110px;">Prioritas</th>
                    <th style="width: 130px;">Status</th>
                    <th style="width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="task-table-body">
                <?php if (empty($tasks)): ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">📋</div>
                                <h4>Belum Ada Tugas</h4>
                                <p>Database saat ini tidak memiliki data tugas yang sesuai kriteria pencarian. Tambahkan tugas pertama Anda sekarang.</p>
                                <a href="create.php" class="btn btn-primary btn-sm">+ Tambah Tugas Baru</a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tasks as $task): ?>
                        <tr class="task-row" 
                            data-title="<?= e(strtolower($task['title'])) ?>" 
                            data-course="<?= e(strtolower($task['course'])) ?>" 
                            data-status="<?= e(strtolower($task['status'])) ?>" 
                            data-priority="<?= e(strtolower($task['priority'])) ?>">
                            <td><strong style="color: #64748b;">#<?= e($task['id']) ?></strong></td>
                            <td>
                                <div class="task-title"><?= e($task['title']) ?></div>
                                <?php if (!empty($task['description'])): ?>
                                    <div class="task-desc" title="<?= e($task['description']) ?>"><?= e($task['description']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="course-tag">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                    <?= e($task['course']) ?>
                                </span>
                            </td>
                            <td class="deadline-cell">
                                <?= format_date($task['deadline']) ?>
                            </td>
                            <td><?= badge_priority($task['priority']) ?></td>
                            <td><?= badge_status($task['status']) ?></td>
                            <td style="text-align: center;">
                                <div style="display: inline-flex; gap: 6px;">
                                    <a href="edit.php?id=<?= e($task['id']) ?>" class="btn btn-secondary btn-sm" title="Edit Tugas">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        Edit
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-sm btn-delete-trigger" 
                                            data-id="<?= e($task['id']) ?>" 
                                            data-title="<?= e($task['title']) ?>" 
                                            title="Hapus Tugas">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                <tr id="empty-filter-row" style="display: none;">
                    <td colspan="7">
                        <div class="empty-state">
                            <h4>Tidak Ada Hasil yang Cocok</h4>
                            <p>Tidak ada tugas yang sesuai dengan filter pencarian yang Anda masukkan.</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
