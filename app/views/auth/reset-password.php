<?php require __DIR__ . '/../helpers.php'; ?>
<section class="auth-section">
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="font-size: 3rem; margin-bottom: 0.75rem;">🔒</div>
            <h1 style="margin: 0 0 0.5rem;">Reset Password</h1>
            <p class="muted" style="margin: 0;">Enter your new password below</p>
        </div>
        <form method="post" action="<?= base_url('auth/reset-password') ?>" class="needs-validation" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
            <div class="form-group">
                <label for="password">New password (min 6 characters)</label>
                <input type="password" id="password" name="password" required minlength="6" placeholder="••••••••">
                <div class="invalid-feedback">Password must be at least 6 characters</div>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Update Password</button>
        </form>
        <p style="text-align: center; margin-top: 1.25rem;">
            <a href="<?= base_url('auth/login') ?>" style="color: var(--accent);">Back to Login</a>
            <span class="muted" style="margin: 0 0.5rem;">·</span>
            <a href="<?= base_url() ?>" style="color: var(--accent);">Back to store</a>
        </p>
    </div>
</section>