<?php require __DIR__ . '/../helpers.php'; ?>
<section class="admin-products-section">
    <div class="admin-section-header">
        <div class="admin-section-header-content">
            <h1>Products</h1>
            <p class="muted">Manage your product catalog</p>
        </div>
        <a href="<?= base_url('admin/product/add') ?>" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Add Product
        </a>
    </div>

    <div class="admin-card">
        <form method="get" action="<?= base_url('admin/products') ?>" class="admin-filters-form"
            id="admin-filters-form">
            <div class="filter-group">
                <div class="filter-search">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                    <input type="text" name="q" placeholder="Search products..."
                        value="<?= e($filters['search'] ?? '') ?>" id="search-input">
                </div>
            </div>
            <div class="filter-group">
                <select name="category" id="category-select" class="filter-select">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $c): ?>
                    <option value="<?= $c['id'] ?>"
                        <?= (($filters['category_id'] ?? 0) == $c['id']) ? 'selected' : '' ?>>
                        <?= e($c['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <select name="min_price" id="min-price-select" class="filter-select">
                    <option value="">Min Price</option>
                    <?php $minPriceOptions = [0, 50, 100, 200, 300, 500, 1000, 2000, 5000]; ?>
                    <?php foreach ($minPriceOptions as $opt): ?>
                    <option value="<?= $opt ?>"
                        <?= (isset($filters['min_price']) && $filters['min_price'] !== '' && $filters['min_price'] == $opt) ? 'selected' : '' ?>>
                        $<?= $opt ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <select name="max_price" id="max-price-select" class="filter-select">
                    <option value="">Max Price</option>
                    <?php $maxPriceOptions = [100, 200, 300, 500, 1000, 2000, 5000, 10000, 50000]; ?>
                    <?php foreach ($maxPriceOptions as $opt): ?>
                    <option value="<?= $opt ?>"
                        <?= (isset($filters['max_price']) && $filters['max_price'] !== '' && $filters['max_price'] == $opt) ? 'selected' : '' ?>>
                        $<?= $opt ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php $hasFilters = !empty($filters['search']) || !empty($filters['category_id']) || !empty($filters['min_price']) || !empty($filters['max_price']); ?>
            <?php if ($hasFilters): ?>
            <a href="<?= base_url('admin/products') ?>" class="btn btn-ghost btn-sm" id="clear-filter-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
                Clear
            </a>
            <?php endif; ?>
        </form>

        <div class="table-responsive">
            <table class="admin-table products-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 80px;">Image</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th class="text-right">Price</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                    <tr>
                        <td class="text-center">
                            <div class="product-thumb-wrap">
                                <img src="<?= $p['image'] ? asset('images/products/' . $p['image']) : placeholder_image() ?>"
                                    alt="" class="product-thumb" onerror="this.src='<?= placeholder_image() ?>'">
                            </div>
                        </td>
                        <td>
                            <span class="product-name"><?= e($p['name']) ?></span>
                        </td>
                        <td>
                            <span class="category-badge"><?= e($p['category_name'] ?? '') ?></span>
                        </td>
                        <td class="text-right">
                            <span class="price">$<?= number_format($p['price'], 2) ?></span>
                        </td>
                        <td class="text-center">
                            <span
                                class="stocks-badge <?= $p['stock'] < 10 ? 'low-stock' : '' ?>"><?= $p['stock'] ?></span>
                        </td>
                        <td class="text-center">
                            <span class="status-indicator <?= $p['is_active'] ? 'active' : 'inactive' ?>">
                                <span class="status-dot"></span>
                                <?= $p['is_active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td class="text-right">
                            <div class="action-buttons">
                                <a href="<?= base_url('admin/product/edit/' . $p['id']) ?>"
                                    class="btn btn-sm btn-outline" title="Edit">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </a>
                                <form method="post" action="<?= base_url('admin/product/toggle-status/' . $p['id']) ?>"
                                    style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?= e($csrf_token ?? '') ?>">
                                    <button type="submit"
                                        class="btn btn-sm <?= $p['is_active'] ? 'btn-warning' : 'btn-success' ?>"
                                        title="<?= $p['is_active'] ? 'Deactivate' : 'Activate' ?>">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <?php if ($p['is_active']): ?>
                                            <circle cx="12" cy="12" r="10" />
                                            <line x1="15" y1="9" x2="9" y2="15" />
                                            <line x1="9" y1="9" x2="15" y2="15" />
                                            <?php else: ?>
                                            <circle cx="12" cy="12" r="10" />
                                            <path d="M9 12l2 2 4-4" />
                                            <?php endif; ?>
                                        </svg>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-sm btn-remove delete-btn"
                                    data-product-name="<?= e($p['name']) ?>"
                                    data-delete-url="<?= base_url('admin/product/delete/' . $p['id']) ?>"
                                    data-csrf-token="<?= e($csrf_token ?? '') ?>" title="Delete">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <polyline points="3 6 5 6 21 6" />
                                        <path
                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                        <line x1="10" y1="11" x2="10" y2="17" />
                                        <line x1="14" y1="11" x2="14" y2="17" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</section>
<!-- Delete Confirmation Modals -->
<div class="modals fade" id="deleteModals" tabindex="-1" aria-labelledby="deleteModalsLabel" aria-hidden="true">
    <div class="modals-dialog modals-dialog-centered">
        <div class="modals-content">
            <div class="modals-header">
                <h5 class="modals-title" id="deleteModalsLabel">Confirm Delete</h5>
                <button type="button" class="modals-close-btn" data-bs-dismiss="modals" aria-label="Close">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>
            <div class="modals-body">
                <div class="delete-modals-icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 6h18" />
                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                        <line x1="10" y1="11" x2="10" y2="17" />
                        <line x1="14" y1="11" x2="14" y2="17" />
                    </svg>
                </div>
                <p class="delete-modals-text">Are you sure you want to delete <strong id="deleteProductName"></strong>?
                </p>
                <p class="delete-modals-warning">This action cannot be undone.</p>
            </div>
            <div class="modals-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modals">Cancel</button>
                <form id="deleteForm" method="post" style="display:inline;">
                    <input type="hidden" name="csrf_token" id="deleteCsrfToken" value="">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteModals = document.getElementById('deleteModals');
    const deleteProductName = document.getElementById('deleteProductName');
    const deleteForm = document.getElementById('deleteForm');
    const deleteCsrfToken = document.getElementById('deleteCsrfToken');

    // Handle delete button clicks
    document.querySelectorAll('.delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const productName = this.getAttribute('data-product-name');
            const deleteUrl = this.getAttribute('data-delete-url');
            const csrfToken = this.getAttribute('data-csrf-token');

            deleteProductName.textContent = productName;
            deleteForm.setAttribute('action', deleteUrl);
            deleteCsrfToken.value = csrfToken;

            deleteModals.classList.add('show');
            document.body.style.overflow = 'hidden';
        });
    });

    // Function to close modals
    function closeModals() {
        deleteModals.classList.remove('show');
        document.body.style.overflow = '';
    }

    // Close modals when clicking backdrop
    deleteModals.addEventListener('click', function(e) {
        if (e.target === deleteModals) {
            closeModals();
        }
    });

    // Close modals when pressing Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && deleteModals.classList.contains('show')) {
            closeModals();
        }
    });

    // Close modals when clicking cancel button
    deleteModals.querySelectorAll('.btn-secondary').forEach(function(btn) {
        btn.addEventListener('click', function() {
            closeModals();
        });
    });

    // Close modals when clicking close button
    deleteModals.querySelectorAll('.modals-close-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            closeModals();
        });
    });
});
</script>

<?php if (empty($products)): ?>
<div class="empty-state">
    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
        <line x1="3" y1="6" x2="21" y2="6" />
        <path d="M16 10a4 4 0 0 1-8 0" />
    </svg>
    <h3>No products found</h3>
    <p class="muted">Start by adding your first product to the catalog.</p>
    <a href="<?= base_url('admin/product/add') ?>" class="btn btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Add Product
    </a>
</div>
<?php endif; ?>
</div>
</section>

<style>
.admin-products-section {
    padding: 0;
}

.admin-section-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.admin-section-header-content h1 {
    margin: 0 0 0.25rem;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text);
}

.admin-section-header-content p {
    margin: 0;
    color: var(--text-muted);
}

.admin-card {
    background: var(--bg-card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.admin-filters-form {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    align-items: center;
    padding: 1.25rem;
    border-bottom: 1px solid var(--border);
    background: var(--bg);
}

.filter-group {
    flex: 0 1 auto;
}

.filter-search {
    position: relative;
    display: flex;
    align-items: center;
}

.filter-search svg {
    position: absolute;
    left: 0.85rem;
    color: var(--text-muted);
    pointer-events: none;
}

.filter-search input {
    padding: 0.5rem 0.85rem 0.5rem 2.4rem;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    font-family: inherit;
    font-size: 0.95rem;
    background: #fff;
    min-width: 220px;
    transition: border-color var(--transition), box-shadow var(--transition);
}

.filter-search input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-soft);
}

.filter-select {
    padding: 0.5rem 2rem 0.5rem 0.85rem;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    font-family: inherit;
    font-size: 0.95rem;
    background: #fff;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    transition: border-color var(--transition);
}

.filter-select:focus {
    outline: none;
    border-color: var(--accent);
}

.table-responsive {
    overflow-x: auto;
}

.products-table {
    margin-top: 0;
}

.products-table th:first-child,
.products-table td:first-child {
    padding-left: 1.5rem;
}

.products-table th:last-child,
.products-table td:last-child {
    padding-right: 1.5rem;
}

.text-center {
    text-align: center;
}

.text-right {
    text-align: right;
}

.product-thumb-wrap {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 52px;
    height: 52px;
    border-radius: var(--radius-sm);
    overflow: hidden;
    background: var(--bg);
}

.product-thumb {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-name {
    font-weight: 500;
    color: var(--text);
}

.category-badge {
    display: inline-block;
    padding: 0.25rem 0.6rem;
    background: var(--bg);
    border-radius: 999px;
    font-size: 0.8rem;
    color: var(--text-muted);
}

.price {
    font-weight: 600;
    color: var(--primary);
}

.stocks-badge {
    padding: 0.25rem 0.65rem;
    background: var(--success-bg);
    color: var(--success);
    border-radius: 999px;
    font-size: 0.85rem;
    font-weight: 600;
}

.stocks-badge.low-stock {
    background: var(--error-bg);
    color: var(--error);
}

.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.85rem;
    font-weight: 500;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.status-indicator.active {
    color: var(--success);
}

.status-indicator.active .status-dot {
    background: var(--success);
}

.status-indicator.inactive {
    color: var(--text-muted);
}

.status-indicator.inactive .status-dot {
    background: var(--text-muted);
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

.btn-outline {
    background: transparent;
    border: 1px solid var(--border);
    color: var(--text);
}

.btn-outline:hover {
    background: var(--accent-soft);
    border-color: var(--accent);
    color: var(--accent);
}

.btn-remove {
    background: transparent;
    color: var(--text-muted);
    border: 1px solid var(--border);
}

.btn-remove:hover {
    background: var(--error-bg);
    color: var(--error);
    border-color: var(--error);
}

.empty-state {
    padding: 4rem 2rem;
    text-align: center;
    color: var(--text-muted);
}

.empty-state svg {
    margin-bottom: 1rem;
    opacity: 0.4;
}

.empty-state h3 {
    margin: 0 0 0.5rem;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text);
}

.empty-state p {
    margin: 0 0 1.5rem;
    font-size: 0.95rem;
}

@media (max-width: 768px) {
    .admin-section-header {
        flex-direction: column;
        align-items: stretch;
    }

    .admin-filters-form {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-search {
        width: 100%;
    }

    .filter-search input {
        width: 100%;
        min-width: auto;
    }

    .filter-group {
        width: 100%;
    }

    .filter-select {
        width: 100%;
    }
}

/* Delete Modals Styles */
.delete-modals-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 72px;
    height: 72px;
    background: var(--error-bg);
    border-radius: 50%;
    margin: 0 auto 1.25rem;
    color: var(--error);
}

.delete-modals-text {
    text-align: center;
    font-size: 1.05rem;
    color: var(--text);
    margin-bottom: 0.5rem;
}

.delete-modals-text strong {
    color: var(--text);
}

.delete-modals-warning {
    text-align: center;
    font-size: 0.9rem;
    color: var(--text-muted);
    margin: 0;
}

.modals {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, .55);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 20000;
    /* FIX */
    opacity: 0;
    visibility: hidden;
    transition: .25s ease;
}

.modals.show {
    opacity: 1;
    visibility: visible;
}

.modals-dialog {
    width: 100%;
    max-width: 420px;
    transform: scale(.95);
    transition: .25s ease;
}

.modals.show .modals-dialog {
    transform: scale(1);
}


.modals-content {
    background: var(--bg-card);
    border-radius: 16px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
    overflow: hidden;
}

.modals-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--border);
}

.modals-title {
    margin: 0;
    font-size: 1.15rem;
    font-weight: 600;
    color: var(--text);
}

.modals-close-btn {
    background: transparent;
    border: none;
    padding: 0.25rem;
    cursor: pointer;
    color: var(--text-muted);
    border-radius: 6px;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modals-close-btn:hover {
    background: var(--bg);
    color: var(--text);
}

.modals-body {
    padding: 2rem 1.5rem;
}

.modals-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    background: var(--bg);
    border-top: 1px solid var(--border);
}

.modals-footer .btn {
    padding: 0.6rem 1.25rem;
    border-radius: 8px;
    font-weight: 500;
}

.modals-footer .btn-secondary {
    background: transparent;
    border: 1px solid var(--border);
    color: var(--text);
}

.modals-footer .btn-secondary:hover {
    background: var(--border);
}

.modals-footer .btn-danger {
    background: var(--error);
    border-color: var(--error);
    color: #fff;
}

.modals-footer .btn-danger:hover {
    background: #b91c1c;
}
</style>