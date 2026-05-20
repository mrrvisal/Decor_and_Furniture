<?php
/**
 * Page controller - static pages like About, Contact
 */
namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;

class PageController extends Controller
{
    /** Home page */
    public function home(): void
    {
        $categoryModel = new Category();
        $productModel = new Product();

        // Get all categories
        $categories = $categoryModel->all();

        // Get featured products (latest 4)
        $featuredProducts = $productModel->getList([], 4);

        $this->view('home.index', [
            'pageTitle' => 'Home',
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'csrf_token' => $this->csrfToken(),
        ]);
    }

    /** About page */
    public function about(): void
    {
        $this->view('pages.about', [
            'pageTitle' => 'About Us',
        ]);
    }

    /** FAQ page */
    public function faq(): void
    {
        $this->view('pages.faq', [
            'pageTitle' => 'FAQ - Frequently Asked Questions',
        ]);
    }

    /** Shipping Information page */
    public function shipping(): void
    {
        $this->view('pages.shipping', [
            'pageTitle' => 'Shipping Information',
        ]);
    }

    /** Returns & Refunds page */
    public function returns(): void
    {
        $this->view('pages.returns', [
            'pageTitle' => 'Returns & Refunds Policy',
        ]);
    }

    /** Contact page */
    public function contact(): void
    {
        $this->view('pages.contact', [
            'pageTitle' => 'Contact Us',
            'csrf_token' => $this->csrfToken(),
        ]);
    }

    /** Contact form submit */
    public function contactSubmit(): void
    {
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('contact'));
        }
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        if (!$name || !$email || !$message) {
            $_SESSION['error'] = 'Please fill in all required fields.';
            $this->redirect($this->baseUrl('contact'));
        }
        // In production, you would send an email here
        // For now, just show success message
        $_SESSION['success'] = 'Thank you for your message! We will get back to you soon.';
        $this->redirect($this->baseUrl('contact'));
    }

    /** User profile page */
    public function profile(): void
    {
        if (empty($_SESSION['user_id']) && empty($_SESSION['admin_id'])) {
            $_SESSION['error'] = 'Please login to view your profile.';
            $this->redirect($this->baseUrl('auth/login'));
        }
        $userModel = new User();
        $userId = (int) ($_SESSION['user_id'] ?? $_SESSION['admin_id']);
        $isAdmin = !empty($_SESSION['admin_id']);
        $user = $userModel->find($userId);
        if (!$user) {
            $_SESSION['error'] = 'User not found.';
            $this->redirect($this->baseUrl());
        }
        $this->view('pages.profile', [
            'pageTitle' => 'My Profile',
            'user' => $user,
            'is_admin' => $isAdmin,
            'csrf_token' => $this->csrfToken(),
        ]);
    }

    /** User settings page */
    public function settings(): void
    {
        if (empty($_SESSION['user_id']) && empty($_SESSION['admin_id'])) {
            $_SESSION['error'] = 'Please login to access settings.';
            $this->redirect($this->baseUrl('auth/login'));
        }
        $userModel = new User();
        $userId = (int) ($_SESSION['user_id'] ?? $_SESSION['admin_id']);
        $isAdmin = !empty($_SESSION['admin_id']);
        $user = $userModel->find($userId);
        if (!$user) {
            $_SESSION['error'] = 'User not found.';
            $this->redirect($this->baseUrl());
        }
        $this->view('pages.settings', [
            'pageTitle' => 'Settings',
            'user' => $user,
            'is_admin' => $isAdmin,
            'csrf_token' => $this->csrfToken(),
        ]);
    }

    /** Update settings */
    public function settingsUpdate(): void
    {
        if (empty($_SESSION['user_id']) && empty($_SESSION['admin_id'])) {
            $_SESSION['error'] = 'Please login to update settings.';
            $this->redirect($this->baseUrl('auth/login'));
        }
        if (!$this->validateCsrf()) {
            $_SESSION['error'] = 'Invalid request.';
            $this->redirect($this->baseUrl('settings'));
        }
        $userModel = new User();
        $userId = (int) ($_SESSION['user_id'] ?? $_SESSION['admin_id']);
        $isAdmin = !empty($_SESSION['admin_id']);
        $user = $userModel->find($userId);
        if (!$user) {
            $_SESSION['error'] = 'User not found.';
            $this->redirect($this->baseUrl());
        }
        $action = $_POST['action'] ?? '';
        // Update avatar
        if ($action === 'avatar' && !empty($_FILES['avatar']['name'])) {
            try {
                require_once dirname(__DIR__, 2) . '/views/helpers.php';
                $avatar = upload_avatar($_FILES['avatar'], $userId, $isAdmin ? 'admins' : 'users');
                if ($avatar) {
                    $userModel->update($userId, ['avatar' => $avatar]);
                    if ($isAdmin) {
                        $_SESSION['admin_avatar'] = $avatar;
                    } else {
                        $_SESSION['user_avatar'] = $avatar;
                    }
                    $_SESSION['success'] = 'Avatar updated successfully.';
                }
            } catch (\Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        // Update name
        elseif ($action === 'name' && !empty($_POST['name'])) {
            $name = trim($_POST['name']);
            if (strlen($name) < 2) {
                $_SESSION['error'] = 'Name must be at least 2 characters.';
            } else {
                $userModel->update($userId, ['name' => $name]);
                if ($isAdmin) {
                    $_SESSION['admin_name'] = $name;
                } else {
                    $_SESSION['user_name'] = $name;
                }
                $_SESSION['success'] = 'Name updated successfully.';
            }
        }
        // Update email
        elseif ($action === 'email' && !empty($_POST['email'])) {
            $email = trim($_POST['email']);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'Invalid email format.';
            } else {
                // Check if email exists for another user
                $existing = $userModel->findByEmail($email);
                if ($existing && $existing['id'] != $userId) {
                    $_SESSION['error'] = 'Email already in use by another account.';
                } else {
                    $userModel->update($userId, ['email' => $email]);
                    if (!$isAdmin) {
                        $_SESSION['user_email'] = $email;
                    }
                    $_SESSION['success'] = 'Email updated successfully.';
                }
            }
        }
        // Update password
        elseif ($action === 'password' && !empty($_POST['current_password']) && !empty($_POST['new_password'])) {
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            if (!password_verify($currentPassword, $user['password'])) {
                $_SESSION['error'] = 'Current password is incorrect.';
            } elseif (strlen($newPassword) < 6) {
                $_SESSION['error'] = 'New password must be at least 6 characters.';
            } else {
                $userModel->update($userId, ['password' => $newPassword]);
                $_SESSION['success'] = 'Password updated successfully.';
            }
        }
        // Update phone
        elseif ($action === 'phone') {
            $phone = trim($_POST['phone'] ?? '');
            $userModel->update($userId, ['phone' => $phone]);
            if (!$isAdmin) {
                $_SESSION['user_phone'] = $phone;
            }
            $_SESSION['success'] = 'Phone number updated successfully.';
        }
        // Update address
        elseif ($action === 'address') {
            $address = trim($_POST['address'] ?? '');
            $city = trim($_POST['city'] ?? '');
            $postcode = trim($_POST['postcode'] ?? '');
            $country = trim($_POST['country'] ?? '');
            if ($isAdmin) {
                $adminModel = new \App\Models\Admin();
                $adminModel->update($userId, [
                    'address' => $address,
                    'city' => $city,
                    'postcode' => $postcode,
                    'country' => $country,
                ]);
            } else {
                $userModel->update($userId, [
                    'address' => $address,
                    'city' => $city,
                    'postcode' => $postcode,
                    'country' => $country,
                ]);
            }
            $_SESSION['success'] = 'Address updated successfully.';
        }
        $this->redirect($this->baseUrl('settings'));
    }
}