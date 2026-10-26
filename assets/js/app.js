/**
 * Student Task Manager - Interactive Frontend Logic
 * Praktikum 3 Komputasi Awan - Allfridho Prima Anjaynata Saragi (102062400045)
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Auto-dismiss Alert Messages
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-8px)';
            setTimeout(() => alert.remove(), 400);
        }, 5000);

        const closeBtn = alert.querySelector('.alert-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => alert.remove());
        }
    });

    // 2. Realtime Search & Filtering on Client Side
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');
    const priorityFilter = document.getElementById('priority-filter');
    const tableBody = document.getElementById('task-table-body');

    function filterTasks() {
        if (!tableBody) return;
        const query = (searchInput?.value || '').toLowerCase().trim();
        const selectedStatus = (statusFilter?.value || 'all').toLowerCase();
        const selectedPriority = (priorityFilter?.value || 'all').toLowerCase();
        const rows = tableBody.querySelectorAll('tr.task-row');

        let visibleCount = 0;
        rows.forEach(row => {
            const title = row.getAttribute('data-title') || '';
            const course = row.getAttribute('data-course') || '';
            const status = row.getAttribute('data-status') || '';
            const priority = row.getAttribute('data-priority') || '';

            const matchesQuery = query === '' || title.includes(query) || course.includes(query);
            const matchesStatus = selectedStatus === 'all' || status === selectedStatus;
            const matchesPriority = selectedPriority === 'all' || priority === selectedPriority;

            if (matchesQuery && matchesStatus && matchesPriority) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Toggle Empty Row if everything is filtered out
        const emptyFilterRow = document.getElementById('empty-filter-row');
        if (emptyFilterRow) {
            emptyFilterRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
        }
    }

    if (searchInput) searchInput.addEventListener('input', filterTasks);
    if (statusFilter) statusFilter.addEventListener('change', filterTasks);
    if (priorityFilter) priorityFilter.addEventListener('change', filterTasks);

    // 3. Delete Confirmation Modal
    const deleteModal = document.getElementById('delete-modal');
    const modalTaskTitle = document.getElementById('modal-task-title');
    const modalFormTaskId = document.getElementById('modal-delete-task-id');
    const modalCancelBtn = document.getElementById('modal-cancel-btn');

    document.querySelectorAll('.btn-delete-trigger').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const taskId = btn.getAttribute('data-id');
            const taskTitle = btn.getAttribute('data-title');

            if (deleteModal && modalTaskTitle && modalFormTaskId) {
                modalTaskTitle.textContent = taskTitle;
                modalFormTaskId.value = taskId;
                deleteModal.classList.add('active');
            } else {
                // Fallback standard confirm
                if (confirm(`Apakah Anda yakin ingin menghapus tugas "${taskTitle}"?`)) {
                    btn.closest('form').submit();
                }
            }
        });
    });

    if (modalCancelBtn && deleteModal) {
        modalCancelBtn.addEventListener('click', () => {
            deleteModal.classList.remove('active');
        });
        deleteModal.addEventListener('click', (e) => {
            if (e.target === deleteModal) {
                deleteModal.classList.remove('active');
            }
        });
    }

    // 4. Form Client-side Validation Indicator
    const taskForms = document.querySelectorAll('.needs-validation');
    taskForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const titleInput = form.querySelector('[name="title"]');
            const courseInput = form.querySelector('[name="course"]');
            const deadlineInput = form.querySelector('[name="deadline"]');

            if (!titleInput.value.trim() || !courseInput.value.trim() || !deadlineInput.value) {
                e.preventDefault();
                alert('Mohon lengkapi seluruh kolom yang bertanda bintang merah (*).');
            }
        });
    });
});
