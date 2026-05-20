<?php
/**
 * Auth controller - Register, Login, Logout, Forgot Password, OTP
 */
namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Models\Admin;
use App\Models\OtpVerification;
use Core\Mail;

class AuthController extends Controller
{
    private User $userModel;
    private Admin $adminModel;
    private OtpVerification $otpModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->adminModel = new Admin();
        $this->otpModel = new OtpVerification();
    }

    /** Show login form (user) */
    public function loginForm(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect($this->baseUrl());
        }
        $this->view('auth.login', ['csrf_token' => $this->csrfToken()]);
    }

    /** Process user login */
    public function login(): void
    {
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('auth/login'));
        }
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if (!$email || !$password) {
            $_SESSION['error'] = 'Email and password required.';
            $this->redirect($this->baseUrl('auth/login'));
        }
        $user = $this->userModel->findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Invalid email or password.';
            $this->redirect($this->baseUrl('auth/login'));
        }
        if (empty($user['email_verified_at'])) {
            $_SESSION['error'] = 'Please verify your email first.';
            $this->redirect($this->baseUrl('auth/login'));
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $redirect = $_SESSION['redirect_after_login'] ?? $this->baseUrl();
        unset($_SESSION['redirect_after_login']);
        $this->redirect($redirect);
    }

    /** Show register form */
    public function registerForm(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect($this->baseUrl());
        }
        $this->view('auth.register', ['csrf_token' => $this->csrfToken()]);
    }

    /** Send OTP for registration */
    public function registerSendOtp(): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.']);
        }
        $email = trim($_POST['email'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $password = $_POST['password'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        if (!$email || !$name || !$password) {
            $this->json(['success' => false, 'message' => 'Name, email and password required.']);
        }
        if (strlen($password) < 6) {
            $this->json(['success' => false, 'message' => 'Password must be at least 6 characters.']);
        }
        if ($this->userModel->findByEmail($email)) {
            $this->json(['success' => false, 'message' => 'Email already registered.']);
        }
        $otp = $this->otpModel->create($email, 'register');
        if (Mail::sendOtp($email, $otp, 'register')) {
            $_SESSION['register_pending'] = ['email' => $email, 'name' => $name, 'password' => $password, 'phone' => $phone];
            $this->json(['success' => true, 'message' => 'OTP sent to your email.']);
        }
        $error = $_SESSION['email_error'] ?? 'Failed to send email. Please check your email configuration or try again.';
        $this->json(['success' => false, 'message' => $error, 'debug' => Mail::getLastError()]);
    }

    /** Verify OTP and complete registration */
    public function registerVerify(): void
    {
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('auth/register'));
        }
        $otp = trim($_POST['otp'] ?? '');
        $pending = $_SESSION['register_pending'] ?? null;
        if (!$pending || !$otp) {
            $_SESSION['error'] = 'Invalid or expired. Please register again.';
            unset($_SESSION['register_pending']);
            $this->redirect($this->baseUrl('auth/register'));
        }
        if (!$this->otpModel->verify($pending['email'], $otp, 'register')) {
            $_SESSION['error'] = 'Invalid or expired OTP.';
            $this->view('auth.register-verify', ['csrf_token' => $this->csrfToken(), 'email' => $pending['email']]);
            return;
        }
        $userId = $this->userModel->create([
            'name' => $pending['name'],
            'email' => $pending['email'],
            'password' => $pending['password'],
            'phone' => $pending['phone'] ?? null,
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);
        unset($_SESSION['register_pending']);
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $pending['name'];
        $_SESSION['user_email'] = $pending['email'];
        $_SESSION['success'] = 'Registration successful!';
        $this->redirect($this->baseUrl());
    }

    /** Show OTP verify form (after register form submit) */
    public function registerVerifyForm(): void
    {
        $pending = $_SESSION['register_pending'] ?? null;
        if (!$pending) {
            $this->redirect($this->baseUrl('auth/register'));
        }
        $this->view('auth.register-verify', ['csrf_token' => $this->csrfToken(), 'email' => $pending['email']]);
    }

    /** Forgot password - show form */
    public function forgotForm(): void
    {
        $this->view('auth.forgot', ['csrf_token' => $this->csrfToken()]);
    }

    /** Forgot password - send OTP */
    public function forgotSendOtp(): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.']);
        }
        $email = trim($_POST['email'] ?? '');
        if (!$email) {
            $this->json(['success' => false, 'message' => 'Email required.']);
        }
        if (!$this->userModel->findByEmail($email)) {
            $this->json(['success' => false, 'message' => 'No account with this email.']);
        }
        $otp = $this->otpModel->create($email, 'forgot_password');
        if (Mail::sendOtp($email, $otp, 'forgot_password')) {
            $_SESSION['forgot_email'] = $email;
            $this->json(['success' => true, 'message' => 'OTP sent to your email.']);
        }
        $error = $_SESSION['email_error'] ?? 'Failed to send email. Please check your email configuration or try again.';
        $this->json(['success' => false, 'message' => $error, 'debug' => Mail::getLastError()]);
    }

    /** Forgot password - verify OTP and show reset form */
    public function forgotVerify(): void
    {
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('auth/forgot'));
        }
        $email = $_SESSION['forgot_email'] ?? '';
        $otp = trim($_POST['otp'] ?? '');
        if (!$email || !$this->otpModel->verify($email, $otp, 'forgot_password')) {
            $_SESSION['error'] = 'Invalid or expired OTP.';
            $this->redirect($this->baseUrl('auth/forgot'));
        }
        $_SESSION['forgot_verified'] = $email;
        $this->redirect($this->baseUrl('auth/reset-password'));
    }

    /** Forgot password - show OTP input form (after send OTP) */
    public function forgotVerifyForm(): void
    {
        if (empty($_SESSION['forgot_email'])) {
            $this->redirect($this->baseUrl('auth/forgot'));
        }
        $this->view('auth.forgot-verify', ['csrf_token' => $this->csrfToken()]);
    }

    /** Reset password form */
    public function resetPasswordForm(): void
    {
        if (empty($_SESSION['forgot_verified'])) {
            $this->redirect($this->baseUrl('auth/forgot'));
        }
        $this->view('auth.reset-password', ['csrf_token' => $this->csrfToken()]);
    }

    /** Reset password submit */
    public function resetPassword(): void
    {
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('auth/forgot'));
        }
        $email = $_SESSION['forgot_verified'] ?? '';
        $password = $_POST['password'] ?? '';
        if (!$email) {
            $this->redirect($this->baseUrl('auth/forgot'));
        }
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Password must be at least 6 characters.';
            $this->redirect($this->baseUrl('auth/reset-password'));
        }
        $user = $this->userModel->findByEmail($email);
        if ($user) {
            $this->userModel->update($user['id'], ['password' => $password]);
        }
        unset($_SESSION['forgot_verified'], $_SESSION['forgot_email']);
        $_SESSION['success'] = 'Password updated. You can login now.';
        $this->redirect($this->baseUrl('auth/login'));
    }

    /** Logout (user) */
    public function logout(): void
    {
        unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_email']);
        session_destroy();
        session_start();
        $_SESSION['success'] = 'Logged out successfully.';
        $this->redirect($this->baseUrl());
    }

    /** Admin login form */
    public function adminLoginForm(): void
    {
        if (!empty($_SESSION['admin_id'])) {
            $this->redirect($this->baseUrl('admin'));
        }
        $this->view('auth.admin-login', ['csrf_token' => $this->csrfToken()]);
    }

    /** Admin login process */
    public function adminLogin(): void
    {
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('admin/login'));
        }
        $login = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $admin = $this->adminModel->verify($login, $password);
        if (!$admin) {
            $_SESSION['error'] = 'Invalid credentials.';
            $this->redirect($this->baseUrl('admin/login'));
        }
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['admin_avatar'] = $admin['avatar'] ?? null;
        $this->redirect($this->baseUrl('admin'));
    }

    /** Admin logout */
    public function adminLogout(): void
    {
        unset($_SESSION['admin_id'], $_SESSION['admin_name']);
        $this->redirect($this->baseUrl());
    }
}