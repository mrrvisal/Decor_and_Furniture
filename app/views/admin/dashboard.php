<?php require __DIR__ . '/../helpers.php'; ?>
<section class="admin-dashboard">
    <!-- Hero Section -->
    <div class="dashboard-hero">
        <div class="hero-shine"></div>
        <div class="hero-pattern"></div>
        <div class="hero-content">
            <div class="hero-header">
                <div class="hero-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    Welcome back!
                </div>
                <h1 class="hero-title">Admin Dashboard</h1>
                <p class="hero-subtitle">Here's what's happening with your store today</p>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-icon" style="background: rgba(255, 107, 53, 0.12); color: var(--accent);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <path d="M16 10a4 4 0 0 1-8 0" />
                        </svg>
                    </div>
                </div>
                <div class="stat-card-body">
                    <span class="stat-card-value"><?= (int) ($stats['total_orders'] ?? 0) ?></span>
                    <span class="stat-card-label">Total Orders</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                </div>
                <div class="stat-card-body">
                    <span class="stat-card-value"><?= (int) ($stats['pending_orders'] ?? 0) ?></span>
                    <span class="stat-card-label">Pending Orders</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23" />
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                        </svg>
                    </div>
                </div>
                <div class="stat-card-body">
                    <span class="stat-card-value">$<?= number_format($stats['total_revenue'] ?? 0, 2) ?></span>
                    <span class="stat-card-label">Total Revenue</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-icon" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                            <line x1="7" y1="7" x2="7.01" y2="7" />
                        </svg>
                    </div>
                </div>
                <div class="stat-card-body">
                    <span class="stat-card-value"><?= (int) ($productCount ?? 0) ?></span>
                    <span class="stat-card-label">Products</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-card-icon" style="background: rgba(139, 92, 246, 0.12); color: #8b5cf6;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </div>
                </div>
                <div class="stat-card-body">
                    <span class="stat-card-value"><?= (int) ($userCount ?? 0) ?></span>
                    <span class="stat-card-label">Total Users</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="recent-orders-section">
        <div class="section-header-inline">
            <h2 class="section-title">Recent Orders</h2>
            <a href="<?= base_url('admin/orders') ?>" class="btn btn-ghost btn-sm">
                View All
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12" />
                    <polyline points="12 5 19 12 12 19" />
                </svg>
            </a>
        </div>

        <?php if (!empty($recentOrders)): ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $o): ?>
                    <tr>
                        <td>
                            <span class="order-number"><?= e($o['order_number']) ?></span>
                        </td>
                        <td>
                            <div class="customer-cell">
                                <span
                                    class="customer-avatar"><?= strtoupper(substr($o['customer_name'] ?? 'U', 0, 1)) ?></span>
                                <span class="customer-name"><?= e($o['customer_name'] ?? 'Unknown') ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="order-total">$<?= number_format($o['total_amount'], 2) ?></span>
                        </td>
                        <td>
                            <span class="status-badge status-<?= e($o['status']) ?>">
                                <?= e(ucfirst(str_replace('_', ' ', $o['status']))) ?>
                            </span>
                        </td>
                        <td>
                            <span class="order-date"><?= date('M j, Y', strtotime($o['created_at'])) ?></span>
                        </td>
                        <td>
                            <a href="<?= base_url('admin/order/' . $o['id']) ?>" class="btn btn-sm btn-outline">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                View
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state card">
            <div class="empty-state-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5">
                    <path
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h3>No orders yet</h3>
            <p class="muted">Orders will appear here once customers place them.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
/* Dashboard Section */
.admin-dashboard {
    padding: 0;
}

/* Hero Section */
.dashboard-hero {
    position: relative;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 50%, #0f3460 100%);
    border-radius: var(--radius);
    padding: 2.5rem;
    margin-bottom: 2rem;
    overflow: hidden;
}

.hero-pattern {
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.5;
}

.hero-shine {
    position: absolute;
    top: -50%;
    right: -20%;
    width: 50%;
    height: 200%;
    background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.03) 50%, transparent 100%);
    transform: rotate(-15deg);
    animation: heroShine 8s ease-in-out infinite;
}

@keyframes heroShine {

    0%,
    100% {
        transform: translateX(-100%) rotate(-15deg);
    }

    50% {
        transform: translateX(100%) rotate(-15deg);
    }
}

.hero-content {
    position: relative;
    z-index: 1;
}

.hero-header {
    margin-bottom: 2rem;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 50px;
    color: rgba(255, 255, 255, 0.95);
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 1rem;
    backdrop-filter: blur(8px);
}

.hero-title {
    margin: 0 0 0.5rem;
    font-size: 2rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: -0.02em;
}

.hero-subtitle {
    margin: 0;
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.7);
}

.hero-stats-wrapper {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

@media (max-width: 768px) {
    .hero-stats-wrapper {
        grid-template-columns: 1fr;
    }
}

.hero-stat-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--radius);
    backdrop-filter: blur(8px);
    transition: all var(--transition);
    position: relative;
    overflow: hidden;
}

.hero-stat-card:hover {
    background: rgba(255, 255, 255, 0.12);
    transform: translateY(-2px);
}

.hero-stat-icon {
    width: 52px;
    height: 52px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    flex-shrink: 0;
}

.hero-stat-icon.pending {
    background: rgba(245, 158, 11, 0.25);
    color: #fbbf24;
}

.hero-stat-icon.revenue {
    background: rgba(16, 185, 129, 0.25);
    color: #34d399;
}

.hero-stat-info {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}

.hero-stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #fff;
    line-height: 1.2;
}

.hero-stat-label {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.6);
}

.hero-stat-decoration {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--accent), transparent);
    opacity: 0.6;
}

/* Stats Section */
.stats-section {
    margin-bottom: 2rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
}

.stat-card {
    background: var(--bg-card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 1.25rem;
    transition: all var(--transition);
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-hover);
}

.stat-card-header {
    margin-bottom: 1rem;
}

.stat-card-icon {
    width: 44px;
    height: 44px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-card-body {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.stat-card-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text);
    line-height: 1.2;
}

.stat-card-label {
    font-size: 0.85rem;
    color: var(--text-muted);
}

/* Recent Orders Section */
.recent-orders-section {
    background: var(--bg-card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.section-header-inline {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--border);
}

.section-header-inline .section-title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
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

/* Table Styles */
.table-responsive {
    overflow-x: auto;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th,
.admin-table td {
    padding: 1rem 1.5rem;
    text-align: left;
    border-bottom: 1px solid var(--border);
}

.admin-table th {
    background: var(--bg);
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

.admin-table tbody tr:last-child td {
    border-bottom: none;
}

.admin-table tbody tr:hover {
    background: var(--bg);
}

.order-number {
    font-weight: 600;
    font-family: 'SF Mono', Monaco, monospace;
    color: var(--primary);
}

.customer-cell {
    display: flex;
    align-items: center;
    gap: 0.65rem;
}

.customer-avatar {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, var(--accent), #ff8c5a);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 600;
    color: #fff;
}

.customer-name {
    font-weight: 500;
    color: var(--text);
}

.order-total {
    font-weight: 600;
    color: var(--primary);
}

.order-date {
    color: var(--text-muted);
    font-size: 0.9rem;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.75rem;
    border-radius: 999px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status-pending,
.status-waiting_payment {
    background: var(--warning-bg);
    color: #92400e;
}

.status-paid,
.status-processing,
.status-shipped {
    background: #dbeafe;
    color: #1e40af;
}

.status-delivered {
    background: var(--success-bg);
    color: #0f766e;
}

.status-cancelled {
    background: var(--error-bg);
    color: #b91c1c;
}

/* Empty State */
.empty-state {
    padding: 4rem 2rem;
    text-align: center;
}

.empty-state-icon {
    color: var(--border);
    margin-bottom: 1rem;
}

.empty-state h3 {
    margin: 0 0 0.5rem;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text);
}

.empty-state p {
    margin: 0;
    font-size: 0.95rem;
    color: var(--text-muted);
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-hero {
        padding: 1.5rem;
    }

    .hero-title {
        font-size: 1.5rem;
    }

    .hero-stat-card {
        padding: 1rem;
    }

    .hero-stat-value {
        font-size: 1.25rem;
    }

    .section-header-inline {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .admin-table th,
    .admin-table td {
        padding: 0.75rem 1rem;
    }
}
</style>