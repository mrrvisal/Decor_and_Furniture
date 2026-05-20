<?php require __DIR__ . '/../helpers.php'; ?>
<section class="admin-profile-section">
    <div class="profile-header">
        <h1 class="profile-title">Admin Profile</h1>
        <p class="profile-subtitle">Manage your account settings and preferences</p>
    </div>

    <div class="profile-grid">
        <!-- Profile Card -->
        <div class="profile-main-card">
            <div class="profile-cover"></div>
            <div class="profile-content">
                <div class="profile-avatar-wrap">
                    <div class="profile-avatar">
                        <?= strtoupper(substr($admin['name'] ?? $admin['username'] ?? 'A', 0, 2)) ?>
                    </div>
                    <span class="profile-status"></span>
                </div>
                <h2 class="profile-name"><?= e($admin['name'] ?? $admin['username']) ?></h2>
                <p class="profile-email"><?= e($admin['email'] ?? 'No email') ?></p>
                <div class="profile-role">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7L12 17l-6.3 4 2.3-7-6-4.6h7.6z" />
                    </svg>
                    <span>Administrator</span>
                </div>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="profile-details">
            <div class="info-card">
                <div class="info-card-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    <h3>Account Information</h3>
                </div>
                <div class="info-card-body">
                    <div class="info-item">
                        <div class="info-item-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </div>
                        <div class="info-item-content">
                            <span class="info-item-label">Username</span>
                            <span class="info-item-value"><?= e($admin['username'] ?? 'N/A') ?></span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </div>
                        <div class="info-item-content">
                            <span class="info-item-label">Full Name</span>
                            <span class="info-item-value"><?= e($admin['name'] ?? 'Not set') ?></span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                <polyline points="22,6 12,13 2,6" />
                            </svg>
                        </div>
                        <div class="info-item-content">
                            <span class="info-item-label">Email Address</span>
                            <span class="info-item-value"><?= e($admin['email'] ?? 'N/A') ?></span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7L12 17l-6.3 4 2.3-7-6-4.6h7.6z" />
                            </svg>
                        </div>
                        <div class="info-item-content">
                            <span class="info-item-label">Role</span>
                            <span class="info-item-value role-badge">Administrator</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                        </div>
                        <div class="info-item-content">
                            <span class="info-item-label">Member Since</span>
                            <span
                                class="info-item-value"><?= !empty($admin['created_at']) ? date('F j, Y', strtotime($admin['created_at'])) : 'N/A' ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Profile Section */
.admin-profile-section {
    padding: 2rem 0;
}

.profile-header {
    margin-bottom: 2rem;
}

.profile-title {
    margin: 0 0 0.5rem;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text);
}

.profile-subtitle {
    margin: 0;
    color: var(--text-muted);
    font-size: 0.95rem;
}

/* Profile Grid */
.profile-grid {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 1.5rem;
}

@media (max-width: 1024px) {
    .profile-grid {
        grid-template-columns: 1fr;
    }
}

/* Main Profile Card */
.profile-main-card {
    background: var(--bg-card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    position: relative;
}

.profile-cover {
    height: 100px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 50%, #0f3460 100%);
    position: relative;
}

.profile-cover::before {
    content: "";
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.5;
}

.profile-content {
    padding: 0 1.5rem 1.5rem;
    text-align: center;
    position: relative;
}

.profile-avatar-wrap {
    position: relative;
    display: inline-block;
    margin-top: -50px;
    margin-bottom: 1rem;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, var(--accent), #ff8c5a);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
    color: #fff;
    box-shadow: 0 4px 20px rgba(255, 107, 53, 0.3);
    border: 4px solid var(--bg-card);
}

.profile-status {
    position: absolute;
    bottom: 8px;
    right: 8px;
    width: 18px;
    height: 18px;
    background: var(--success);
    border-radius: 50%;
    border: 3px solid var(--bg-card);
}

.profile-name {
    margin: 0 0 0.35rem;
    font-size: 1.35rem;
    font-weight: 700;
    color: var(--text);
}

.profile-email {
    margin: 0 0 0.75rem;
    color: var(--text-muted);
    font-size: 0.9rem;
}

.profile-role {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.45rem 0.85rem;
    background: var(--accent-soft);
    color: var(--accent);
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 1.25rem;
}

.profile-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* Details Section */
.profile-details {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

/* Info Card */
.info-card {
    background: var(--bg-card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.info-card-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border);
}

.info-card-header svg {
    color: var(--accent);
}

.info-card-header h3 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text);
}

.info-card-body {
    padding: 0.5rem 0;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.85rem 1.25rem;
    transition: background var(--transition);
}

.info-item:hover {
    background: var(--bg);
}

.info-item-icon {
    width: 40px;
    height: 40px;
    background: var(--bg);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    flex-shrink: 0;
    transition: all var(--transition);
}

.info-item:hover .info-item-icon {
    background: var(--accent-soft);
    color: var(--accent);
}

.info-item-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
    min-width: 0;
}

.info-item-label {
    font-size: 0.8rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

.info-item-value {
    font-weight: 500;
    color: var(--text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.25rem 0.65rem;
    background: var(--accent-soft);
    color: var(--accent);
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    width: fit-content;
}

/* Stats Mini Grid */
.stats-mini-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

@media (max-width: 480px) {
    .stats-mini-grid {
        grid-template-columns: 1fr;
    }
}

.stat-mini {
    background: var(--bg-card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.85rem;
    transition: all var(--transition);
}

.stat-mini:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-hover);
}

.stat-mini-icon {
    width: 44px;
    height: 44px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-mini-content {
    display: flex;
    flex-direction: column;
}

.stat-mini-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text);
    line-height: 1.2;
}

.stat-mini-label {
    font-size: 0.8rem;
    color: var(--text-muted);
}

/* Responsive */
@media (max-width: 768px) {
    .admin-profile-section {
        padding: 1.5rem 0;
    }

    .profile-title {
        font-size: 1.5rem;
    }

    .profile-actions {
        flex-direction: column;
    }

    .profile-actions .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>