<?php
// Define simple escape function first before using it
if (!function_exists('e')) {
    function e(?string $s): string {
        return $s === null ? '' : htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
    }
}

// Now include helpers for other functions
require dirname(__DIR__, 2) . '/helpers.php';

$pageTitle = 'User: ' . e($user['name']);
?>
<section class="admin-user-show">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-ghost btn-sm mb-1">← Back to Users</a>
        </div>
        <a href="<?php echo base_url('admin/user/edit/' . $user['id']); ?>" class="btn btn-primary">Edit User</a>
    </div>

    <h1 class="section-title">User Details</h1>

    <div class="user-detail-grid">
        <!-- User Info Card -->
        <div class="card user-info-card" style="text-align: center;">
            <div class="user-avatar-large mx-auto mb-2">
                <?php echo strtoupper(substr($user['name'], 0, 2)); ?>
            </div>
            <h2 style="margin: 0 0 0.25rem; font-size: 1.25rem;"><?php echo e($user['name']); ?></h2>
            <p class="muted mb-2" style="margin: 0;"><?php echo e($user['email']); ?></p>
            <div class="d-flex flex-column align-items-center gap-1" style="gap: 0.5rem;">
                <?php if ($user['is_active']): ?>
                <span class="badge" style="background: var(--success-bg); color: var(--success);">Active</span>
                <?php else: ?>
                <span class="badge" style="background: var(--error-bg); color: var(--error);">Inactive</span>
                <?php endif; ?>
                <?php if ($user['email_verified_at']): ?>
                <span class="badge" style="background: var(--success-bg); color: var(--success);">✓ Verified</span>
                <?php else: ?>
                <span class="badge" style="background: var(--warning-bg); color: #92400e;">Unverified</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="card">
            <h3
                style="margin: 0 0 1rem; font-size: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border);">
                Contact Information</h3>
            <div class="info-list">
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?php echo e($user['email']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone</span>
                    <span class="info-value"><?php echo e($user['phone'] ?: 'Not provided'); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Account Status</span>
                    <span class="info-value">
                        <?php if ($user['is_active']): ?>
                        <span class="badge" style="background: var(--success-bg); color: var(--success);">Active</span>
                        <?php else: ?>
                        <span class="badge" style="background: var(--error-bg); color: var(--error);">Inactive</span>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="info-row" style="border-bottom: none;">
                    <span class="info-label">Email Verified</span>
                    <span class="info-value">
                        <?php if ($user['email_verified_at']): ?>
                        <span style="color: var(--success);">Yes -
                            <?php echo date('M j, Y', strtotime($user['email_verified_at'])); ?></span>
                        <?php else: ?>
                        <span class="muted">No</span>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Account Info -->
        <div class="card">
            <h3
                style="margin: 0 0 1rem; font-size: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border);">
                Account Details</h3>
            <div class="info-list">
                <div class="info-row">
                    <span class="info-label">User ID</span>
                    <span class="info-value">#<?php echo e($user['id']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Joined</span>
                    <span class="info-value"><?php echo date('F j, Y', strtotime($user['created_at'])); ?></span>
                </div>
                <div class="info-row" style="border-bottom: none;">
                    <span class="info-label">Last Updated</span>
                    <span class="info-value"><?php echo date('F j, Y', strtotime($user['updated_at'])); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Order History -->
    <div class="mt-3">
        <h3 class="section-title" style="font-size: 1.25rem;">Order History</h3>
        <?php if (!empty($orders)): ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo e($order['order_number']); ?></td>
                        <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td><?php echo e(ucfirst(str_replace('_', ' ', $order['status']))); ?></td>
                        <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                        <td>
                            <a href="<?php echo base_url('admin/order/' . $order['id']); ?>"
                                class="btn btn-sm btn-secondary">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state card">
            <div style="text-align: center; padding: 2rem;">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📦</div>
                <p class="muted" style="margin: 0;">This user has no orders yet.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>