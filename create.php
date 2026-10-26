<?php
/**
 * Operasi CREATE - Tambah Tugas Baru
 * Praktikum 3 Komputasi Awan - Deploy Web App melalui Azure App Service
 * Mahasiswa: Allfridho Prima Anjaynata Saragi (NIM: 102062400045)
 */

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$db = get_db();
$errors = [];
$old = [
    'title'       => '',
    'description' => '',
    'course'      => '',
    'deadline'    => date('Y-m-d', strtotime('+3 days')),
    'priority'    => 'medium',
    'status'      => 'pending'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Verifikasi Token CSRF
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        set_flash('danger', 'Sesi kedaluwarsa atau token CSRF tidak valid. Silakan coba kembali.');
        header('Location: create.php');
        exit;
    }

    // 2. Ambil & Sanitasi Input
    $old = [
        'title'       => trim($_POST['title'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'course'      => trim($_POST['course'] ?? ''),
        'deadline'    => trim($_POST['deadline'] ?? ''),
        'priority'    => trim($_POST['priority'] ?? 'medium'),
        'status'      => trim($_POST['status'] ?? 'pending')
    ];

    // 3. Validasi Server-Side
    $errors = validate_task_input($old);

    // 4. Eksekusi Penyimpanan jika Valid
    if (empty($errors)) {
        $pdo = $db->getConnection();
        if (!$pdo) {
            set_flash('danger', 'Koneksi ke database terputus. Data gagal disimpan.');
        } else {
            try {
                $sql = "INSERT INTO tasks (title, description, course, deadline, priority, status) 
                        VALUES (:title, :description, :course, :deadline, :priority, :status)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':title'       => $old['title'],
                    ':description' => $old['description'],
                    ':course'      => $old['course'],
                    ':deadline'    => $old['deadline'],
                    ':priority'    => $old['priority'],
                    ':status'      => $old['status']
                ]);

                set_flash('success', 'Tugas "' . $old['title'] . '" berhasil ditambahkan ke database!');
                header('Location: index.php');
                exit;
            } catch (PDOException $e) {
                error_log("[Create Error] " . $e->getMessage());
                set_flash('danger', 'Terjadi kesalahan sistem saat menyimpan tugas: ' . $e->getMessage());
            }
        }
    }
}

$pageTitle = 'Tambah Tugas Baru';
require_once __DIR__ . '/includes/header.php';
?>

<div class="form-card">
    <div class="form-header">
        <h2>Tambah Tugas Perkuliahan</h2>
        <p>Lengkapi formulir di bawah ini untuk menambahkan catatan tugas baru ke database Azure.</p>
    </div>

    <form action="create.php" method="POST" class="form-body needs-validation" novalidate>
        <input type="hidden" name="csrf_token" value="<?= get_csrf_token() ?>">

        <div class="form-group">
            <label class="form-label" for="title">Judul Tugas <span class="required">*</span></label>
            <input type="text" id="title" name="title" class="form-control" 
                   placeholder="Contoh: Praktikum 3 Komputasi Awan (Azure)" 
                   value="<?= e($old['title']) ?>" required autofocus maxlength="255">
            <?php if (isset($errors['title'])): ?>
                <div class="form-error"><?= e($errors['title']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label" for="course">Mata Kuliah <span class="required">*</span></label>
                <input type="text" id="course" name="course" class="form-control" 
                       placeholder="Contoh: Komputasi Awan" 
                       value="<?= e($old['course']) ?>" required maxlength="150">
                <?php if (isset($errors['course'])): ?>
                    <div class="form-error"><?= e($errors['course']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="deadline">Tanggal Deadline <span class="required">*</span></label>
                <input type="date" id="deadline" name="deadline" class="form-control" 
                       value="<?= e($old['deadline']) ?>" required>
                <?php if (isset($errors['deadline'])): ?>
                    <div class="form-error"><?= e($errors['deadline']) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label" for="priority">Tingkat Prioritas</label>
                <select id="priority" name="priority" class="form-control">
                    <option value="low" <?= $old['priority'] === 'low' ? 'selected' : '' ?>>Rendah (Low)</option>
                    <option value="medium" <?= $old['priority'] === 'medium' ? 'selected' : '' ?>>Sedang (Medium)</option>
                    <option value="high" <?= $old['priority'] === 'high' ? 'selected' : '' ?>>Tinggi (High)</option>
                </select>
                <?php if (isset($errors['priority'])): ?>
                    <div class="form-error"><?= e($errors['priority']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="status">Status Pengerjaan</label>
                <select id="status" name="status" class="form-control">
                    <option value="pending" <?= $old['status'] === 'pending' ? 'selected' : '' ?>>Belum Selesai</option>
                    <option value="in_progress" <?= $old['status'] === 'in_progress' ? 'selected' : '' ?>>Sedang Dikerjakan</option>
                    <option value="completed" <?= $old['status'] === 'completed' ? 'selected' : '' ?>>Selesai</option>
                </select>
                <?php if (isset($errors['status'])): ?>
                    <div class="form-error"><?= e($errors['status']) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Deskripsi & Catatan Tugas</label>
            <textarea id="description" name="description" class="form-control" 
                      placeholder="Tuliskan instruksi tugas, kriteria pengumpulan, atau catatan teknis..."><?= e($old['description']) ?></textarea>
        </div>

        <div class="form-actions">
            <a href="index.php" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Simpan Tugas
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
