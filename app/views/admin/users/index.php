<?php
// Define simple escape function first before using it
if (!function_exists('e')) {
    function e(?string $s): string
    {
        return $s === null ? '' : htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
    }
}

$pageTitle = 'Users';
require dirname(__DIR__, 2) . '/helpers.php';

// Check if any filter is active
$hasFilters = !empty($filters['search']) ||
    (isset($filters['status']) && $filters['status'] !== '') ||
    !empty($filters['date_from']) ||
    !empty($filters['date_to']);
?>
<section class="admin-users-section">
    <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 50px;">
        <h1 class="section-title" style="margin: 0;">Users</h1>
        <a href="<?php echo base_url('admin/user/add'); ?>" class="btn btn-primary">+ Add User</a>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" class="filters-form" id="filterForm">
            <div class="form-row">
                <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 200px;">
                    <input type="text" name="q" placeholder="Search name or email..."
                        value="<?php echo e($filters['search'] ?? ''); ?>" class="filter-input" id="searchInput">
                </div>
                <div class="form-group" style="margin-bottom: 0; flex: 0 0 140px;">
                    <select name="status" class="filter-select" id="statusSelect">
                        <option value="">All Status</option>
                        <option value="1" <?php echo (isset($filters['status']) && $filters['status'] === 1) ? 'selected' : ''; ?>>
                            Active
                        </option>
                        <option value="0" <?php echo (isset($filters['status']) && $filters['status'] === 0) ? 'selected' : ''; ?>>
                            Inactive</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0; flex: 0 0 150px;">
                    <input type="date" name="date_from" value="<?php echo e($filters['date_from'] ?? ''); ?>"
                        class="filter-input" id="dateFromInput" placeholder="From Date">
                </div>
                <div class="form-group" style="margin-bottom: 0; flex: 0 0 150px;">
                    <input type="date" name="date_to" value="<?php echo e($filters['date_to'] ?? ''); ?>"
                        class="filter-input" id="dateToInput" placeholder="To Date">
                </div>
                <?php if ($hasFilters): ?>
                    <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-ghost">Clear</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="results-info mb-2">
        <p class="muted" style="margin: 0;"><?php echo e($totalCount); ?> user(s) found</p>
    </div>

    <!-- Users table -->
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?php echo e($u['id']); ?></td>
                        <td>
                            <div class="user-cell">
                                <span class="user-avatar-small"><?php echo strtoupper(substr($u['name'], 0, 1)); ?></span>
                                <span><?php echo e($u['name']); ?></span>
                            </div>
                        </td>
                        <td><?php echo e($u['email']); ?></td>
                        <td><?php echo e($u['phone'] ?: '-'); ?></td>
                        <td>
                            <?php if ($u['is_active']): ?>
                                <span class="badge" style="background: var(--success-bg); color: var(--success);">Active</span>
                            <?php else: ?>
                                <span class="badge" style="background: var(--error-bg); color: var(--error);">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($u['created_at'])); ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo base_url('admin/user/' . $u['id']); ?>"
                                    class="btn btn-sm btn-secondary">View</a>
                                <a href="<?php echo base_url('admin/user/edit/' . $u['id']); ?>"
                                    class="btn btn-sm btn-primary">Edit</a>
                                <button type="button"
                                    class="btn btn-sm <?php echo $u['is_active'] ? 'btn-remove' : 'btn btn-sm btn-success'; ?>"
                                    style="<?php echo $u['is_active'] ? 'background: var(--warning-bg); color: #92400e;' : ''; ?>"
                                    data-bs-toggle="modal" data-bs-target="#toggleModal"
                                    data-id="<?php echo e($u['id']); ?>" data-name="<?php echo e($u['name']); ?>"
                                    data-action="<?php echo $u['is_active'] ? 'deactivate' : 'activate'; ?>"
                                    data-url="<?php echo base_url('admin/user/toggle-status/' . $u['id']); ?>">
                                    <?php echo $u['is_active'] ? 'Off' : 'On'; ?>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-id="<?php echo e($u['id']); ?>"
                                    data-name="<?php echo e($u['name']); ?>"
                                    data-url="<?php echo base_url('admin/user/delete/' . $u['id']); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6" />
                                        <path
                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if (empty($users)): ?>
        <div class="empty-state card">
            <div style="text-align: center; padding: 3rem 2rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">👥</div>
                <p class="muted" style="margin: 0 0 0.5rem; font-size: 1.1rem;">No users found</p>
                <p class="muted" style="margin: 0; font-size: 0.9rem;">Try adjusting your filters or add a new user.</p>
                <a href="<?php echo base_url('admin/user/add'); ?>" class="btn btn-primary mt-2">+ Add User</a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <nav aria-label="Users pagination" class="mt-3">
            <ul class="pagination" style="justify-content: center; margin: 0; padding: 1rem 0;">
                <?php
                $queryParams = array();
                if (!empty($filters['search']))
                    $queryParams['q'] = $filters['search'];
                if (isset($filters['status']) && $filters['status'] !== '')
                    $queryParams['status'] = $filters['status'];
                if (!empty($filters['date_from']))
                    $queryParams['date_from'] = $filters['date_from'];
                if (!empty($filters['date_to']))
                    $queryParams['date_to'] = $filters['date_to'];
                ?>
                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                    <li class="page-item">
                        <a href="<?php echo base_url('admin/users?page=' . $p . '&' . http_build_query($queryParams)); ?>"
                            class="pagination-link <?php echo ($p === $filters['page']) ? 'active' : ''; ?>"
                            style="<?php echo ($p === $filters['page']) ? '' : 'border-color: var(--border);'; ?>">
                            <?php echo e($p); ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</section>

<!-- Toggle Status Modal -->
<div class="modal-overlay" id="toggleModal">
    <div class="modal">
        <div class="modal-header">
            <h3 id="toggleModalTitle">Confirm Action</h3>
            <button type="button" class="modal-close" data-bs-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <p id="toggleModalMessage"></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-ghost modal-cancel" data-bs-dismiss="modal">Cancel</button>
            <form method="POST" id="toggleForm">
                <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">
                <button type="submit" class="btn btn-primary" id="toggleSubmitBtn">Confirm</button>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Delete User</h3>
            <button type="button" class="modal-close" data-bs-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete user <strong id="deleteUserName"></strong>?</p>
            <p class="text-muted" style="margin-top: 0.5rem;">This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-ghost modal-cancel" data-bs-dismiss="modal">Cancel</button>
            <form method="POST" id="deleteForm">
                <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('filterForm');
        const searchInput = document.getElementById('searchInput');
        const statusSelect = document.getElementById('statusSelect');
        const dateFromInput = document.getElementById('dateFromInput');
        const dateToInput = document.getElementById('dateToInput');

        // Toggle modal functionality
        const toggleModal = document.getElementById('toggleModal');
        const toggleModalTitle = document.getElementById('toggleModalTitle');
        const toggleModalMessage = document.getElementById('toggleModalMessage');
        const toggleForm = document.getElementById('toggleForm');
        const toggleSubmitBtn = document.getElementById('toggleSubmitBtn');
        const toggleModalClose = toggleModal.querySelector('.modal-close');
        const toggleModalCancel = toggleModal.querySelector('.modal-cancel');
        const toggleModalOverlay = toggleModal;

        // Open toggle modal on button click
        document.querySelectorAll('[data-bs-target="#toggleModal"]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const userId = this.getAttribute('data-id');
                const userName = this.getAttribute('data-name');
                const action = this.getAttribute('data-action');
                const toggleUrl = this.getAttribute('data-url');

                toggleModalTitle.textContent = action === 'activate' ? 'Activate User' :
                    'Deactivate User';
                toggleModalMessage.innerHTML = 'Are you sure you want to <strong>' + action +
                    '</strong> user <strong>' + userName + '</strong>?';
                toggleSubmitBtn.className = action === 'activate' ? 'btn btn-success' :
                    'btn btn-warning';
                toggleSubmitBtn.textContent = action === 'activate' ? 'Activate' : 'Deactivate';
                toggleForm.action = toggleUrl;
                toggleModal.classList.add('active');
            });
        });

        // Close toggle modal functions
        function closeToggleModal() {
            toggleModal.classList.remove('active');
        }
        if (toggleModalClose) toggleModalClose.addEventListener('click', closeToggleModal);
        if (toggleModalCancel) toggleModalCancel.addEventListener('click', closeToggleModal);
        toggleModalOverlay.addEventListener('click', function (e) {
            if (e.target === toggleModalOverlay) {
                closeToggleModal();
            }
        });

        // Delete modal functionality
        const deleteModal = document.getElementById('deleteModal');
        const deleteUserName = document.getElementById('deleteUserName');
        const deleteForm = document.getElementById('deleteForm');
        const deleteModalClose = deleteModal.querySelector('.modal-close');
        const deleteModalCancel = deleteModal.querySelector('.modal-cancel');
        const deleteModalOverlay = deleteModal;

        // Open delete modal on button click
        document.querySelectorAll('[data-bs-target="#deleteModal"]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const userId = this.getAttribute('data-id');
                const userName = this.getAttribute('data-name');
                const deleteUrl = this.getAttribute('data-url');
                deleteUserName.textContent = userName;
                deleteForm.action = deleteUrl;
                deleteModal.classList.add('active');
            });
        });

        // Close delete modal functions
        function closeDeleteModal() {
            deleteModal.classList.remove('active');
        }
        if (deleteModalClose) deleteModalClose.addEventListener('click', closeDeleteModal);
        if (deleteModalCancel) deleteModalCancel.addEventListener('click', closeDeleteModal);
        deleteModalOverlay.addEventListener('click', function (e) {
            if (e.target === deleteModalOverlay) {
                closeDeleteModal();
            }
        });

        let searchTimeout;

        // Auto-submit on select change
        statusSelect.addEventListener('change', function () {
            form.submit();
        });

        // Auto-submit on date change
        dateFromInput.addEventListener('change', function () {
            form.submit();
        });

        dateToInput.addEventListener('change', function () {
            form.submit();
        });

        // Auto-submit on search with debounce (300ms)
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function () {
                form.submit();
            }, 300);
        });
    });
</script>