<?php require __DIR__ . '/../helpers.php'; ?>
<section class="auth-section">
    <div class="auth-card admin-login-card">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div class="user-avatar-large mx-auto mb-2" style="width: 70px; height: 70px; font-size: 1.25rem;">👨‍💼
            </div>
            <h1 style="margin: 0 0 0.5rem;">Admin Login</h1>
            <p class="muted" style="margin: 0;">Sign in to access the admin panel</p>
        </div>
        <form method="post" action="<?= base_url('admin/login') ?>">
            <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
            <div class="form-group">
                <label for="email">Email or Username</label>
                <input type="text" id="email" name="email" required placeholder="admin@decorfurniture.com"
                    autocomplete="username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password"
                    autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
        </form>
        <p style="text-align: center; margin-top: 1.25rem;">
            <a href="<?= base_url() ?>" class="btn btn-ghost" style="text-decoration: none;">← Back to Store</a>
        </p>
    </div>
</section>