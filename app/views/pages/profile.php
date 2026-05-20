<?php require __DIR__ . '/../helpers.php'; ?>
<section class="profile-section">
    <div class="container">
        <div class="profile-header">
            <div class="profile-header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <div class="profile-header-content">
                <div class="profile-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    My Account
                </div>
                <h1 class="profile-title">My Profile</h1>
                <p class="profile-subtitle">Manage your account information and preferences</p>
            </div>
            <div class="profile-header-decoration">
                <div class="profile-decor-circle"></div>
                <div class="profile-decor-dots"></div>
            </div>
        </div>

        <div class="profile-grid">
            <div class="profile-card">
                <div class="card-header">
                    <div class="profile-avatar">
                        <div class="avatar-container">
                            <span class="avatar-initials"><?= strtoupper(substr($user['name'] ?? 'U', 0, 2)) ?></span>
                        </div>
                        <div class="avatar-glow"></div>
                    </div>
                </div>
                <div class="card-body">
                    <h2 class="profile-name"><?= e($user['name']) ?></h2>
                    <p class="profile-email">
                        <span class="email-icon">✉️</span>
                        <?= e($user['email']) ?>
                    </p>
                    <div class="profile-status">
                        <?php if (!empty($user['email_verified_at'])): ?>
                        <span class="status-badge verified">
                            <span class="status-icon">✓</span>
                            <span>Verified Account</span>
                        </span>
                        <?php else: ?>
                        <span class="status-badge unverified">
                            <span class="status-icon">⚠️</span>
                            <span>Unverified</span>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="profile-details">
                <div class="details-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span class="card-icon">👤</span>
                            Account Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">
                                    <span class="label-icon">📝</span>
                                    Full Name
                                </div>
                                <div class="info-value"><?= e($user['name']) ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <span class="label-icon">✉️</span>
                                    Email Address
                                </div>
                                <div class="info-value">
                                    <?= e($user['email']) ?>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <span class="label-icon">📞</span>
                                    Phone Number
                                </div>
                                <div class="info-value">
                                    <?= e($user['phone'] ?? 'Not provided') ?>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <span class="label-icon">📅</span>
                                    Member Since
                                </div>
                                <div class="info-value">
                                    <?= date('F j, Y', strtotime($user['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
.profile-section {
    padding: 0 0 4rem;
    background: var(--bg);
}

.profile-header {
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
    padding: 2.5rem 2rem;
    margin-bottom: 2.5rem;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 50%, #0f3460 100%);
    border-radius: var(--radius);
    position: relative;
    overflow: hidden;
}

.profile-header::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.5;
}

.profile-header-icon {
    width: 72px;
    height: 72px;
    background: linear-gradient(135deg, var(--accent), var(--accent-hover));
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    box-shadow: 0 10px 30px rgba(255, 107, 53, 0.3);
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}

.profile-header-content {
    position: relative;
    z-index: 1;
    flex: 1;
}

.profile-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 0.85rem;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50px;
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.02em;
    margin-bottom: 0.75rem;
}

.profile-badge svg {
    color: var(--accent);
}

.profile-title {
    margin: 0 0 0.5rem;
    font-size: 2rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: -0.02em;
}

.profile-subtitle {
    margin: 0;
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.5;
}

.profile-header-decoration {
    position: absolute;
    right: 2rem;
    bottom: -20px;
    z-index: 0;
}

.profile-decor-circle {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(255, 107, 53, 0.15), rgba(255, 107, 53, 0.05));
    border: 1px solid rgba(255, 107, 53, 0.1);
}

.profile-decor-dots {
    position: absolute;
    top: -25px;
    right: -15px;
    width: 50px;
    height: 50px;
    background-image: radial-gradient(rgba(255, 255, 255, 0.12) 2px, transparent 2px);
    background-size: 10px 10px;
}

.profile-grid {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 2.5rem;
    align-items: start;
}

@media (max-width: 1024px) {
    .profile-grid {
        grid-template-columns: 320px 1fr;
        gap: 2rem;
    }
}

@media (max-width: 768px) {
    .profile-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
}

/* Profile Card */
.profile-card {
    background: var(--bg-card);
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(11, 18, 32, 0.08);
    overflow: hidden;
    transition: all var(--transition);
    border: 1px solid rgba(11, 18, 32, 0.05);
}

.profile-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 60px rgba(11, 18, 32, 0.12);
}

.card-header {
    background: linear-gradient(135deg, var(--accent) 0%, #ff8c5a 100%);
    padding: 2rem 2rem 3rem;
    position: relative;
}

.profile-avatar {
    position: relative;
    margin: 0 auto;
    width: 120px;
    height: 120px;
}

.avatar-container {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.2);
    border: 4px solid #fff;
    position: relative;
    z-index: 2;
}

.avatar-glow {
    position: absolute;
    top: -8px;
    left: -8px;
    right: -8px;
    bottom: -8px;
    background: radial-gradient(circle, rgba(255, 107, 53, 0.1) 0%, transparent 70%);
    border-radius: 50%;
    z-index: 1;
}

.avatar-initials {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--accent);
    text-transform: uppercase;
}

.card-body {
    padding: 2rem;
    text-align: center;
}

.profile-name {
    margin: 0 0 0.75rem;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text);
    letter-spacing: -0.01em;
}

.profile-email {
    margin: 0 0 1.5rem;
    color: var(--text-muted);
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.email-icon {
    font-size: 1.1rem;
    opacity: 0.7;
}

.profile-status {
    margin-top: 1rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.02em;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.status-badge.verified {
    background: linear-gradient(135deg, var(--success-bg) 0%, #d1fae5 100%);
    color: #065f46;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.status-badge.unverified {
    background: linear-gradient(135deg, var(--warning-bg) 0%, #fef3c7 100%);
    color: #92400e;
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.status-icon {
    font-size: 1rem;
}

/* Details Section */
.profile-details {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.details-card,
.quick-links-card {
    background: var(--bg-card);
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(11, 18, 32, 0.06);
    border: 1px solid rgba(11, 18, 32, 0.05);
    overflow: hidden;
    transition: all var(--transition);
}

.details-card:hover,
.quick-links-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(11, 18, 32, 0.08);
}

.card-header {
    background: linear-gradient(135deg, var(--bg) 0%, rgba(11, 18, 32, 0.02) 100%);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--border);
}

.card-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.card-icon {
    font-size: 1.3rem;
    opacity: 0.8;
}

.card-body {
    padding: 2rem;
}

.info-grid {
    display: grid;
    gap: 1.5rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    padding: 1rem;
    background: var(--bg);
    border-radius: 12px;
    border: 1px solid rgba(11, 18, 32, 0.05);
    transition: all var(--transition);
}

.info-item:hover {
    background: rgba(255, 107, 53, 0.02);
    border-color: rgba(255, 107, 53, 0.1);
}

.info-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

.label-icon {
    font-size: 1rem;
    opacity: 0.7;
}

.info-value {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text);
    word-break: break-word;
}

/* Quick Links */
.quick-links-grid {
    display: grid;
    gap: 0.75rem;
}

.quick-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    background: linear-gradient(135deg, var(--bg) 0%, rgba(11, 18, 32, 0.01) 100%);
    border: 1px solid rgba(11, 18, 32, 0.05);
    border-radius: 12px;
    text-decoration: none;
    color: var(--text);
    transition: all var(--transition);
    position: relative;
    overflow: hidden;
}

.quick-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 107, 53, 0.05), transparent);
    transition: left 0.5s;
}

.quick-link:hover::before {
    left: 100%;
}

.quick-link:hover {
    background: linear-gradient(135deg, var(--accent-soft) 0%, rgba(255, 107, 53, 0.08) 100%);
    border-color: rgba(255, 107, 53, 0.2);
    transform: translateX(4px);
    box-shadow: 0 4px 16px rgba(255, 107, 53, 0.1);
}

.quick-link.logout:hover {
    background: linear-gradient(135deg, var(--error-bg) 0%, rgba(220, 38, 38, 0.08) 100%);
    border-color: rgba(220, 38, 38, 0.2);
    color: var(--error);
}

.link-content {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.link-icon {
    font-size: 1.5rem;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg);
    border-radius: 10px;
    border: 1px solid rgba(11, 18, 32, 0.05);
    transition: all var(--transition);
}

.quick-link:hover .link-icon {
    background: #fff;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.link-text {
    flex: 1;
}

.link-title {
    display: block;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 0.25rem;
}

.link-desc {
    display: block;
    font-size: 0.85rem;
    color: var(--text-muted);
    font-weight: 400;
}

.link-arrow {
    font-size: 1.2rem;
    color: var(--text-muted);
    transition: all var(--transition);
    opacity: 0.6;
}

.quick-link:hover .link-arrow {
    color: var(--accent);
    opacity: 1;
    transform: translateX(2px);
}

.quick-link.logout:hover .link-arrow {
    color: var(--error);
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .profile-section {
        padding: 0 0 3rem;
    }

    .profile-header {
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 2rem 1.5rem;
    }

    .profile-header-icon {
        width: 64px;
        height: 64px;
    }

    .profile-badge {
        justify-content: center;
    }

    .profile-title {
        font-size: 1.75rem;
    }

    .profile-subtitle {
        font-size: 1rem;
    }

    .profile-header-decoration {
        display: none;
    }

    /* Animation for page load */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .profile-card,
    .details-card,
    .quick-links-card {
        animation: fadeInUp 0.6s ease-out;
    }

    .profile-card {
        animation-delay: 0.1s;
    }

    .details-card {
        animation-delay: 0.2s;
    }

    .quick-links-card {
        animation-delay: 0.3s;
    }
}
</style>