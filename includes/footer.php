<?php
/**
 * Footer Template - Student Task Manager
 * Praktikum 3 Komputasi Awan - Allfridho Prima Anjaynata Saragi (102062400045)
 */
?>
    </main>

    <!-- Delete Confirmation Modal Component -->
    <div id="delete-modal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-title">Konfirmasi Hapus Tugas</h3>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus tugas <strong id="modal-task-title" style="color: #fff;"></strong> secara permanen dari database?</p>
                <p style="font-size: 0.78rem; color: #94a3b8; margin-top: -10px;">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-actions">
                <button type="button" id="modal-cancel-btn" class="btn btn-secondary btn-sm">Batal</button>
                <form id="modal-delete-form" action="delete.php" method="POST" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?= get_csrf_token() ?>">
                    <input type="hidden" name="id" id="modal-delete-task-id" value="">
                    <button type="submit" id="confirm-delete-btn" class="btn btn-danger btn-sm">Ya, Hapus Data</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Academic Footer -->
    <footer class="footer">
        <div class="container footer-content">
            <div>
                <strong>Praktikum 3 Komputasi Awan</strong> &bull; Allfridho Prima Anjaynata Saragi (NIM: 102062400045)
                <div style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">
                    Microsoft Azure App Service (PaaS Linux F1) &bull; PHP Native 8.x &bull; CI/CD GitHub Actions &bull; Build v<?= APP_VERSION ?>
                </div>
            </div>
            <div class="footer-links">
                <span style="font-size: 0.74rem; color: #94a3b8;">Telkom University &bull; Ganjil 2026/2027</span>
            </div>
        </div>
    </footer>

    <script src="assets/js/app.js?v=<?= APP_VERSION ?>"></script>
</body>
</html>
