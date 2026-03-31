<?php require __DIR__ . '/../helpers.php';
$isEdit = !empty($product);
$action = $isEdit ? base_url('admin/product/update/' . $product['id']) : base_url('admin/product/store');
?>
<section class="admin-form-section">
    <div class="admin-section-header">
        <div class="admin-section-header-content">
            <div class="form-header-top">
                <a href="<?= base_url('admin/products') ?>" class="back-link">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                    Back to Products
                </a>
            </div>
            <h1><?= $isEdit ? 'Edit Product' : 'Add New Product' ?></h1>
            <p class="muted">
                <?= $isEdit ? 'Update product information below' : 'Fill in the details to create a new product' ?>
            </p>
        </div>
    </div>

    <div class="form-card">
        <form method="post" action="<?= $action ?>" enctype="multipart/form-data" class="admin-form needs-validation"
            novalidate>
            <input type="hidden" name="csrf_token" value="<?= e($csrf_token ?? '') ?>">

            <div class="form-grid">
                <div class="form-group">
                    <label for="category_id">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                        </svg>
                        Category <span class="required">*</span>
                    </label>
                    <select id="category_id" name="category_id" required class="form-select">
                        <option value="">Select category</option>
                        <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id'] ?>"
                            <?= ($product['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                            <?= e($c['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Please select a category</div>
                </div>

                <div class="form-group">
                    <label for="name">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        Product Name <span class="required">*</span>
                    </label>
                    <input type="text" id="name" name="name" required placeholder="Enter product name"
                        value="<?= e($product['name'] ?? '') ?>" class="form-input">
                    <div class="invalid-feedback">Product name is required</div>
                </div>

                <div class="form-group full-width">
                    <label for="description">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                            <polyline points="10 9 9 9 8 9" />
                        </svg>
                        Description
                    </label>
                    <textarea id="description" name="description" rows="4" placeholder="Enter product description"
                        class="form-textarea"><?= e($product['description'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="price">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23" />
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                        </svg>
                        Price <span class="required">*</span>
                    </label>
                    <div class="input-with-icon">
                        <span class="input-icon">$</span>
                        <input type="number" id="price" name="price" step="0.01" required placeholder="0.00"
                            value="<?= e($product['price'] ?? '') ?>" class="form-input">
                    </div>
                    <div class="invalid-feedback">Please enter a valid price</div>
                </div>

                <div class="form-group">
                    <label for="stock">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path
                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                            <line x1="12" y1="22.08" x2="12" y2="12" />
                        </svg>
                        Stock Quantity
                    </label>
                    <input type="number" id="stock" name="stock" min="0" placeholder="0"
                        value="<?= e($product['stock'] ?? 0) ?>" class="form-input">
                </div>

                <div class="form-group">
                    <label for="image">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg>
                        Product Image
                    </label>
                    <div class="file-upload-wrap">
                        <input type="file" id="image" name="image" accept="image/*" class="file-upload-input">
                        <div class="file-upload-content">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="17 8 12 3 7 8" />
                                <line x1="12" y1="3" x2="12" y2="15" />
                            </svg>
                            <span>Click to upload or drag and drop</span>
                            <small>PNG, JPG, GIF up to 2MB</small>
                        </div>
                    </div>
                </div>

                <?php if (!empty($product['image'])): ?>
                <div class="form-group">
                    <label>Current Image</label>
                    <div class="current-image-wrap">
                        <img src="<?= asset('images/products/' . $product['image']) ?>" alt=""
                            onerror="this.style.display='none'">
                    </div>
                </div>
                <?php endif; ?>

                <div class="form-group full-width">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1"
                            <?= ($product['is_active'] ?? 1) ? 'checked' : '' ?>>
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            <strong>Active</strong>
                            <small>Product will be visible in the store</small>
                        </span>
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?= base_url('admin/products') ?>" class="btn btn-ghost">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12" />
                        <polyline points="12 19 5 12 12 5" />
                    </svg>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    <?= $isEdit ? 'Update Product' : 'Add Product' ?>
                </button>
            </div>
        </form>
    </div>
</section>

<style>
/* Admin Form Section */
.admin-form-section {
    padding: 2.5rem 0;
    background: #f1f5f9;
    min-height: calc(100vh - 80px);
}

.admin-section-header {
    max-width: 900px;
    margin: 0 auto 2rem;
    padding: 0 1.5rem;
}

.admin-section-header-content {
    position: relative;
    padding-left: 1.5rem;
}

.admin-section-header-content::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0.15rem;
    width: 4px;
    height: 3.5rem;
    background: linear-gradient(180deg, var(--accent), var(--accent-hover));
    border-radius: 2px;
}

.form-header-top {
    margin-bottom: 1rem;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-muted);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    padding: 0.5rem 0;
    transition: all 0.2s ease;
}

.back-link:hover {
    color: var(--accent);
}

.admin-section-header h1 {
    margin: 0 0 0.5rem;
    font-size: 2rem;
    font-weight: 800;
    color: var(--text);
    letter-spacing: -0.02em;
}

.admin-section-header .muted {
    margin: 0;
    font-size: 1rem;
    color: var(--text-muted);
}

/* Form Card */
.form-card {
    max-width: 900px;
    margin: 0 auto;
    background: var(--bg-card);
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.03);
    overflow: hidden;
    position: relative;
}

.form-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--accent), var(--accent-hover));
}

/* Form Styles */
.admin-form {
    padding: 2rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.form-group {
    margin-bottom: 0;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.6rem;
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--text);
}

.form-group label svg {
    color: var(--accent);
    flex-shrink: 0;
}

.required {
    color: #dc2626;
    margin-left: 0.25rem;
}

/* Form Inputs */
.form-input,
.form-select,
.form-textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--border);
    border-radius: 10px;
    font-family: inherit;
    font-size: 0.95rem;
    background: linear-gradient(180deg, #fff, #fafbfc);
    color: var(--text);
    transition: all 0.2s ease;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
    transform: translateY(-1px);
    background: #fff;
}

.form-input::placeholder,
.form-textarea::placeholder {
    color: var(--text-muted);
}

.form-textarea {
    resize: vertical;
    min-height: 120px;
}

/* Select */
.form-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    padding-right: 2.5rem;
    cursor: pointer;
}

/* Input with Icon */
.input-with-icon {
    position: relative;
}

.input-with-icon .input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-weight: 600;
    font-size: 1rem;
    pointer-events: none;
}

.input-with-icon .form-input {
    padding-left: 2.25rem;
}

/* File Upload */
.file-upload-wrap {
    position: relative;
}

.file-upload-input {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 2;
}

.file-upload-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    border: 2px dashed var(--border);
    border-radius: 12px;
    background: linear-gradient(180deg, #fafbfc, #f5f7fa);
    transition: all 0.2s ease;
}

.file-upload-input:hover+.file-upload-content {
    border-color: var(--accent);
    background: rgba(255, 107, 53, 0.03);
}

.file-upload-input:focus+.file-upload-content {
    border-color: var(--accent);
    box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
}

.file-upload-content svg {
    color: var(--accent);
    margin-bottom: 0.75rem;
}

.file-upload-content span {
    font-size: 0.95rem;
    font-weight: 500;
    color: var(--text);
    margin-bottom: 0.35rem;
}

.file-upload-content small {
    font-size: 0.8rem;
    color: var(--text-muted);
}

/* Current Image */
.current-image-wrap {
    padding: 1rem;
    background: var(--bg);
    border-radius: 10px;
    border: 1px solid var(--border);
}

.current-image-wrap img {
    max-width: 150px;
    max-height: 150px;
    border-radius: 8px;
    object-fit: cover;
}

/* Checkbox */
.checkbox-label {
    display: flex !important;
    align-items: flex-start;
    gap: 0.85rem;
    cursor: pointer;
    padding: 1rem;
    background: var(--bg);
    border-radius: 10px;
    border: 1px solid var(--border);
    transition: all 0.2s ease;
    margin-bottom: 0;
}

.checkbox-label:hover {
    border-color: var(--accent);
}

.checkbox-label input[type="checkbox"] {
    display: none;
}

.checkbox-custom {
    width: 22px;
    height: 22px;
    min-width: 22px;
    border: 2px solid var(--border);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    margin-top: 1px;
}

.checkbox-label input[type="checkbox"]:checked+.checkbox-custom {
    background: var(--accent);
    border-color: var(--accent);
}

.checkbox-label input[type="checkbox"]:checked+.checkbox-custom::after {
    content: '';
    width: 6px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
    margin-bottom: 2px;
}

.checkbox-text strong {
    display: block;
    font-size: 0.95rem;
    color: var(--text);
    margin-bottom: 0.2rem;
}

.checkbox-text small {
    font-size: 0.85rem;
    color: var(--text-muted);
    font-weight: 400;
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border);
}

.form-actions .btn-ghost {
    border: 1px solid var(--border);
    background: transparent;
    color: var(--text);
    height: 40px mar;
    padding-top: 0px !important;
    padding-bottom: 0px !important;
    margin-top: 16px;
}

.form-actions .btn-ghost:hover {
    background: var(--bg);
    border-color: var(--text-muted);
}

.form-actions .btn-primary {
    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.25);
}

.form-actions .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
}

/* Invalid Feedback */
.invalid-feedback {
    display: none;
    color: #dc2626;
    font-size: 0.8rem;
    margin-top: 0.4rem;
}

.was-validated .form-input:invalid,
.was-validated .form-select:invalid {
    border-color: #dc2626;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23dc2626' stroke-width='2'%3E%3Ccircle cx='12' cy='12' r='10'/%3E%3Cline x1='15' y1='9' x2='9' y2='15'/%3E%3Cline x1='9' y1='9' x2='15' y2='15'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    padding-right: 2.5rem;
}

.was-validated .form-input:invalid~.invalid-feedback,
.was-validated .form-select:invalid~.invalid-feedback {
    display: block;
}

/* ===== FIX ADMIN UI FULL WIDTH (SAFE) ===== */

/* Allow admin content to take full available space */
.admin-form-section {
    width: 100%;
    max-width: none;
    margin: 0;
    padding-left: 2rem;
    padding-right: 2rem;
    box-sizing: border-box;
}

/* Remove width limitation */
.admin-section-header,
.admin-section-header-content,
.form-card {
    max-width: 100%;
    margin-left: 0;
    margin-right: 0;
}

/* Ensure card fills content area */
.form-card {
    width: 100%;
}

/* Prevent layout breaking with sidebar */
@media (min-width: 1024px) {
    .admin-form-section {
        padding-left: 3rem;
        padding-right: 3rem;
    }
}


/* Responsive */
@media (max-width: 768px) {
    .admin-form-section {
        padding: 1.5rem 0;
    }

    .admin-section-header {
        padding: 0 1rem;
    }

    .admin-section-header h1 {
        font-size: 1.65rem;
    }

    .form-card {
        margin: 0 1rem;
        border-radius: 16px;
    }

    .admin-form {
        padding: 1.5rem;
    }

    .form-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .form-actions {
        flex-direction: column-reverse;
        gap: 0.75rem;
    }

    .form-actions .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>