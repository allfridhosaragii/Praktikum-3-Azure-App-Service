<?php
/**
 * Operasi UPDATE - Edit Data Tugas
 * Praktikum 3 Komputasi Awan - Deploy Web App melalui Azure App Service
 * Mahasiswa: Allfridho Prima Anjaynata Saragi (NIM: 102062400045)
 */

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$db = get_db();
$pdo = $db->getConnection();
if (!$pdo) {
    set_flash('danger', 'Koneksi database offline.');
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
}

if (!$id) {
    set_flash('danger', 'ID tugas tidak valid.');
    header('Location: index.php');
    exit;
}

// 1. Ambil Data Tugas Eksisting
try {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $task = $stmt->fetch();

    if (!$task) {
        set_flash('danger', 'Data tugas #' . $id . ' tidak ditemukan di database.');
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    error_log("[Edit Fetch Error] " . $e->getMessage());
    set_flash('danger', 'Gagal mengambil data tugas: ' . $e->getMessage());
    header('Location: index.php');
    exit;
}

$errors = [];
$formData = $task;

// 2. Proses Perubahan Data (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        set_flash('danger', 'Token CSRF tidak valid.');
        header('Location: edit.php?id=' . $id);
        exit;
    }

    $formData = [
        'id'          => $id,
        'title'       => trim($_POST['title'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'course'      => trim($_POST['course'] ?? ''),
        'deadline'    => trim($_POST['deadline'] ?? ''),
        'priority'    => trim($_POST['priority'] ?? 'medium'),
        'status'      => trim($_POST['status'] ?? 'pending')
    ];

    $errors = validate_task_input($formData);

    if (empty($errors)) {
        try {
            $updateSql = "UPDATE tasks SET 
                title = :title,
                description = :description,
                course = :course,
                deadline = :deadline,
                priority = :priority,
                status = :status,
                updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
            
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute([
                ':title'       => $formData['title'],
                ':description' => $formData['description'],
                ':course'      => $formData['course'],
                ':deadline'    => $formData['deadline'],
                ':priority'    => $formData['priority'],
                ':status'      => $formData['status'],
                ':id'          => $id
            ]);

            set_flash('success', 'Perubahan tugas #' . $id . ' ("' . $formData['title'] . '") berhasil diperbarui!');
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            error_log("[Update Error] " . $e->getMessage());
            set_flash('danger', 'Terjadi kesalahan saat memperbarui database: ' . $e->getMessage());
        }
    }
}

$pageTitle = 'Edit Data Tugas';
require_once __DIR__ . '/includes/header.php';
?>

<div class="form-card">
    <div class="form-header">
        <h2>Perbarui Tugas #<?= e($id) ?></h2>
        <p>Ubah rincian informasi tugas di bawah ini dan simpan perubahan ke database cloud Azure.</p>
    </div>

    <form action="edit.php?id=<?= e($id) ?>" method="POST" class="form-body needs-validation" novalidate>
        <input type="hidden" name="csrf_token" value="<?= get_csrf_token() ?>">
        <input type="hidden" name="id" value="<?= e($id) ?>">

        <div class="form-group">
            <label class="form-label" for="title">Judul Tugas <span class="required">*</span></label>
            <input type="text" id="title" name="title" class="form-control" 
                   value="<?= e($formData['title']) ?>" required autofocus maxlength="255">
            <?php if (isset($errors['title'])): ?>
                <div class="form-error"><?= e($errors['title']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label" for="course">Mata Kuliah <span class="required">*</span></label>
                <input type="text" id="course" name="course" class="form-control" 
                       value="<?= e($formData['course']) ?>" required maxlength="150">
                <?php if (isset($errors['course'])): ?>
                    <div class="form-error"><?= e($errors['course']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="deadline">Tanggal Deadline <span class="required">*</span></label>
                <input type="date" id="deadline" name="deadline" class="form-control" 
                       value="<?= e($formData['deadline']) ?>" required>
                <?php if (isset($errors['deadline'])): ?>
                    <div class="form-error"><?= e($errors['deadline']) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label" for="priority">Tingkat Prioritas</label>
                <select id="priority" name="priority" class="form-control">
                    <option value="low" <?= $formData['priority'] === 'low' ? 'selected' : '' ?>>Rendah (Low)</option>
                    <option value="medium" <?= $formData['priority'] === 'medium' ? 'selected' : '' ?>>Sedang (Medium)</option>
                    <option value="high" <?= $formData['priority'] === 'high' ? 'selected' : '' ?>>Tinggi (High)</option>
                </select>
                <?php if (isset($errors['priority'])): ?>
                    <div class="form-error"><?= e($errors['priority']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="status">Status Pengerjaan</label>
                <select id="status" name="status" class="form-control">
                    <option value="pending" <?= $formData['status'] === 'pending' ? 'selected' : '' ?>>Belum Selesai</option>
                    <option value="in_progress" <?= $formData['status'] === 'in_progress' ? 'selected' : '' ?>>Sedang Dikerjakan</option>
                    <option value="completed" <?= $formData['status'] === 'completed' ? 'selected' : '' ?>>Selesai</option>
                </select>
                <?php if (isset($errors['status'])): ?>
                    <div class="form-error"><?= e($errors['status']) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Deskripsi & Catatan Tugas</label>
            <textarea id="description" name="description" class="form-control"><?= e($formData['description']) ?></textarea>
        </div>

        <div class="form-actions">
            <a href="index.php" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Perbarui Tugas
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
