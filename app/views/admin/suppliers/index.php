<?php require __DIR__ . '/../helpers.php'; ?>
<section class="admin-suppliers-section">
    <div class="admin-section-header">
        <div class="admin-section-header-content">
            <h1>Suppliers</h1>
            <p class="muted">Manage suppliers</p>
        </div>
        <a href="<?= base_url('admin/supplier/add') ?>" class="btn btn-primary">Add Supplier</a>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table suppliers-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($suppliers as $s): ?>
                    <tr>
                        <td><?= e($s['name']) ?></td>
                        <td><?= e($s['contact_name'] ?? '') ?></td>
                        <td><a href="mailto:<?= e($s['email']) ?>"><?= e($s['email']) ?></a></td>
                        <td><?= e($s['phone'] ?? '') ?></td>
                        <td class="text-center"><?= $s['is_active'] ? 'Active' : 'Inactive' ?></td>
                        <td class="text-right">
                            <a href="<?= base_url('admin/supplier/edit/' . $s['id']) ?>" class="btn btn-sm btn-outline">Edit</a>
                            <button type="button"
                                    class="btn btn-sm btn-danger delete-supplier"
                                    data-delete-url="<?= base_url('admin/supplier/delete/' . $s['id']) ?>"
                                    data-supplier-name="<?= e($s['name']) ?>">
                                Delete
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<div class="modal-overlay" id="deleteSupplierModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Delete Supplier</h3>
            <button type="button" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete supplier <strong id="deleteSupplierName"></strong>?</p>
            <p class="muted" style="margin-top: 0.5rem;">This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-ghost modal-cancel">Cancel</button>
            <form method="post" id="deleteSupplierForm">
                <input type="hidden" name="csrf_token" value="<?= e($csrf_token ?? '') ?>">
                <button type="submit" class="btn btn-danger">Delete Supplier</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteSupplierModal');
    const deleteForm = document.getElementById('deleteSupplierForm');
    const supplierName = document.getElementById('deleteSupplierName');

    function closeModal() {
        modal.classList.remove('active');
    }

    document.querySelectorAll('.delete-supplier').forEach(function(button) {
        button.addEventListener('click', function() {
            supplierName.textContent = this.dataset.supplierName || '';
            deleteForm.action = this.dataset.deleteUrl;
            modal.classList.add('active');
        });
    });

    modal.querySelector('.modal-close')?.addEventListener('click', closeModal);
    modal.querySelector('.modal-cancel')?.addEventListener('click', closeModal);
    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });
});
</script>
