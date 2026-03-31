<?php
// Define simple escape function first before using it
if (!function_exists('e')) {
    function e(?string $s): string
    {
        return $s === null ? '' : htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
    }
}

$pageTitle = $user ? 'Edit User: ' . e($user['name']) : 'Add New User';
require dirname(__DIR__, 2) . '/helpers.php';
$isEdit = !empty($user);
?>
<section class="admin-form-section">
    <div class="admin-section-header" style="padding: 30px 40px 0; margin-bottom: 0;">
        <div class="admin-section-header-content">
            <div class="form-header-top">
                <a href="<?= base_url('admin/users') ?>" class="back-link">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                    Back to Users
                </a>
            </div>
            <h1><?= $isEdit ? 'Edit User' : 'Add New User' ?></h1>
            <p class="muted">
                <?= $isEdit ? 'Update user account details below' : 'Fill in the details to create a new user account' ?>
            </p>
        </div>
    </div>

    <div class="form-card">
        <form method="POST" action="<?= base_url($isEdit ? 'admin/user/update/' . $user['id'] : 'admin/user/store') ?>"
            class="admin-form">
            <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">

            <div class="form-grid">
                <!-- Basic Info -->
                <div class="form-card-inner">
                    <div class="form-card-header">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        <h3>Basic Information</h3>
                    </div>

                    <div class="form-group">
                        <label for="name">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                            Full Name <span class="required">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="<?= e($user['name'] ?? '') ?>"
                            class="form-input" required placeholder="Enter full name">
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                <polyline points="22,6 12,13 2,6" />
                            </svg>
                            Email Address <span class="required">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="<?= e($user['email'] ?? '') ?>"
                            class="form-input"
                            <?= $isEdit ? 'readonly placeholder="Email cannot be changed"' : 'required placeholder="Enter email address"' ?>
                            style="<?= $isEdit ? 'background: var(--bg);' : '' ?>">
                        <?php if ($isEdit): ?><small class="form-hint">Email cannot be changed</small><?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="phone">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                            </svg>
                            Phone Number
                        </label>
                        <input type="text" id="phone" name="phone" value="<?= e($user['phone'] ?? '') ?>"
                            class="form-input" placeholder="Enter phone number">
                    </div>

                    <?php if (!$isEdit): ?>
                    <div class="form-group">
                        <label for="password">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                            Password <span class="required">*</span>
                        </label>
                        <input type="password" id="password" name="password" class="form-input" required minlength="6"
                            placeholder="Min 6 characters">
                        <small class="form-hint">Minimum 6 characters</small>
                    </div>
                    <?php else: ?>
                    <div class="form-group">
                        <label for="password">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                            New Password
                        </label>
                        <input type="password" id="password" name="password" class="form-input" minlength="6"
                            placeholder="Leave blank to keep current">
                        <small class="form-hint">Leave blank to keep current password</small>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Status Options -->
                <div class="form-card-inner">
                    <div class="form-card-header">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                        <h3>Account Status</h3>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_active" value="1"
                                <?= (!isset($user['is_active']) || $user['is_active']) ? 'checked' : '' ?>>
                            <span class="checkbox-custom"></span>
                            <span class="checkbox-text">
                                <strong>Account Active</strong>
                                <small>Inactive users cannot log in</small>
                            </span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="email_verified" value="1"
                                <?= (!empty($user['email_verified_at'])) ? 'checked' : '' ?>>
                            <span class="checkbox-custom"></span>
                            <span class="checkbox-text">
                                <strong>Email Verified</strong>
                                <small>User has verified their email address</small>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="<?= base_url('admin/users') ?>" class="btn btn-ghost">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12" />
                        <polyline points="12 19 5 12 12 5" />
                    </svg>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary" style="margin: 0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    <?= $isEdit ? 'Save Changes' : 'Create User' ?>
                </button>
            </div>
        </form>
    </div>
</section>

<style>
.admin-form-section {
    padding: 0;
    max-width: 1440px;
}

.admin-section-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem !important;
    flex-wrap: wrap;
    gap: 1rem;

}

.form-header-top {
    margin-bottom: 0.5rem;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    color: var(--text-muted);
    text-decoration: none;
    font-size: 0.9rem;
    transition: color var(--transition);
}

.back-link:hover {
    color: var(--accent);
}

.admin-section-header-content h1 {
    margin: 0 0 0.5rem;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text);
}

.admin-section-header-content p {
    margin: 0;
    color: var(--text-muted);
}

.form-card {
    background: var(--bg-card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.admin-form {
    padding: 0;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
    padding: 2rem;
    border-bottom: 1px solid var(--border);
}

.form-card-inner {
    background: var(--bg);
    border-radius: var(--radius-sm);
    padding: 1.5rem;
    border: 1px solid var(--border);
}

.form-actions .btn-ghost {
    border: 1px solid var(--border);
    background: transparent;
    color: var(--text);
}

.form-actions .btn {
    height: 42px;
}

.form-card-header {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    margin-bottom: 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--border);
}

.form-card-header svg {
    color: var(--accent);
}

.form-card-header h3 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text);
}

.form-group {
    margin-bottom: 1.25rem;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    margin-bottom: 0.5rem;
    font-weight: 500;
    font-size: 0.9rem;
    color: var(--text);
}

.form-group label svg {
    color: var(--text-muted);
}

.required {
    color: var(--error);
}

.form-input {
    width: 100%;
    padding: 0.7rem 1rem;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    font-family: inherit;
    font-size: 0.95rem;
    background: var(--bg);
    transition: border-color var(--transition), box-shadow var(--transition);
}

.form-input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-soft);
    background: #fff;
}

.form-input::placeholder {
    color: var(--text-muted);
}

.form-hint {
    display: block;
    margin-top: 0.35rem;
    color: var(--text-muted);
    font-size: 0.8rem;
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem;
    background: var(--bg-card);
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: background var(--transition);
    border: 1px solid var(--border);
}

.checkbox-label:hover {
    background: var(--accent-soft);
}

.checkbox-label input[type="checkbox"] {
    display: none;
}

.checkbox-custom {
    flex-shrink: 0;
    width: 22px;
    height: 22px;
    border: 2px solid var(--border);
    border-radius: 6px;
    background: #fff;
    position: relative;
    transition: border-color var(--transition), background var(--transition);
    margin-top: 1px;
}

.checkbox-label input[type="checkbox"]:checked+.checkbox-custom {
    background: var(--accent);
    border-color: var(--accent);
}

.checkbox-label input[type="checkbox"]:checked+.checkbox-custom::after {
    content: '';
    position: absolute;
    left: 6px;
    top: 2px;
    width: 5px;
    height: 10px;
    border: solid #fff;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.checkbox-text {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}

.checkbox-text strong {
    font-size: 0.95rem;
    color: var(--text);
}

.checkbox-text small {
    font-size: 0.85rem;
    color: var(--text-muted);
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    padding: 1.25rem 2rem;
    background: var(--bg);
}

.form-actions .btn-ghost {
    border: 1px solid var(--border);
}

@media (max-width: 768px) {
    .admin-section-header {
        flex-direction: column;
        align-items: stretch;
    }

    .form-grid {
        grid-template-columns: 1fr;
        padding: 1.5rem;
    }

    .form-actions {
        flex-direction: column;
        padding: 1.5rem;
    }

    .form-actions .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>