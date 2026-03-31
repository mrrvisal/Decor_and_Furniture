<?php require __DIR__ . '/../helpers.php'; ?>
<section class="auth-section">
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="font-size: 3rem; margin-bottom: 0.75rem;">🔑</div>
            <h1 style="margin: 0 0 0.5rem;">Forgot Password</h1>
            <p class="muted" style="margin: 0;">Enter your email and we'll send you a code to reset it.</p>
        </div>
        <form id="forgot-form" method="post" action="<?= base_url('auth/forgot/send-otp') ?>">
            <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="you@example.com" autocomplete="email">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Send OTP</button>
        </form>
        <p id="forgot-message" style="text-align: center; margin: 1rem 0;"></p>
        <p style="text-align: center; margin-top: 1.25rem;">
            <a href="<?= base_url('auth/login') ?>" class="btn btn-ghost" style="text-decoration: none;">← Back to
                Login</a>
            <span class="muted" style="margin: 0 0.5rem;">·</span>
            <a href="<?= base_url() ?>" class="btn btn-ghost" style="text-decoration: none;">Back to store</a>
        </p>
    </div>
</section>
<script>
    document.getElementById('forgot-form').addEventListener('submit', function (e) {
        e.preventDefault();
        var form = this;
        var msg = document.getElementById('forgot-message');
        msg.textContent = '';
        msg.className = '';
        var fd = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            body: fd
        })
            .then(r => r.json())
            .then(data => {
                msg.textContent = data.message;
                msg.className = data.success ? 'success' : 'error';
                if (data.success) window.location = '<?= base_url('auth/forgot/verify-form') ?>';
            })
            .catch(function () {
                msg.textContent = 'Something went wrong.';
                msg.className = 'error';
            });
    });
</script>