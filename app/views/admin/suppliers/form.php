<?php require __DIR__ . '/../helpers.php';
$isEdit = !empty($supplier);
$action = $isEdit ? base_url('admin/supplier/update/' . $supplier['id']) : base_url('admin/supplier/store');
?>
<section class="admin-form-section">
    <div class="admin-section-header">
        <div class="admin-section-header-content">
            <h1><?= $isEdit ? 'Edit Supplier' : 'Add Supplier' ?></h1>
            <p class="muted"><?= $isEdit ? 'Update supplier information' : 'Fill supplier details' ?></p>
        </div>
    </div>

    <div class="form-card">
        <form method="post" action="<?= $action ?>" class="admin-form needs-validation" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e($csrf_token ?? '') ?>">

            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" required class="form-input" value="<?= e($supplier['name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="contact_name">Contact Name</label>
                    <input type="text" id="contact_name" name="contact_name" class="form-input" value="<?= e($supplier['contact_name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required class="form-input" value="<?= e($supplier['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" class="form-input" value="<?= e($supplier['phone'] ?? '') ?>">
                </div>
                <div class="form-group full-width">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" rows="3" class="form-textarea"><?= e($supplier['address'] ?? '') ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" <?= ($supplier['is_active'] ?? 1) ? 'checked' : '' ?>>
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">Active</span>
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?= base_url('admin/suppliers') ?>" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Update Supplier' : 'Add Supplier' ?></button>
            </div>
        </form>
    </div>
</section>
