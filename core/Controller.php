<?php
/**
 * Base Controller - all controllers extend this
 */
namespace Core;

abstract class Controller
{
    /**
     * Render a view with optional data and layout
     */
    protected function view(string $view, array $data = [], ?string $layout = 'layouts.main'): void
    {
        extract($data);
        // __DIR__ = core/, dirname(__DIR__) = project root (decor/)
        $root = dirname(__DIR__);
        $viewPath = $root . '/app/views/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View not found: {$view}");
        }
        ob_start();
        require $viewPath;
        $content = ob_get_clean();
        if ($layout) {
            $layoutPath = $root . '/app/views/' . str_replace('.', '/', $layout) . '.php';
            if (file_exists($layoutPath)) {
                require $layoutPath;
                return;
            }
        }
        echo $content;
    }

    /**
     * Redirect to URL
     */
    protected function redirect(string $url, int $code = 302): void
    {
        header('Location: ' . $url, true, $code);
        exit;
    }

    /**
     * Get base URL
     */
    protected function baseUrl(string $path = ''): string
    {
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
        return $base . ($path ? '/' . ltrim($path, '/') : '');
    }

    /**
     * Get CSRF token (generate if not set)
     */
    protected function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validate CSRF token
     */
    protected function validateCsrf(): bool
    {
        $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';
        return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Require user to be logged in
     */
    protected function requireLogin(): void
    {
        if (empty($_SESSION['user_id'])) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            $this->redirect($this->baseUrl('auth/login'));
        }
    }

    /**
     * Require admin role
     */
    protected function requireAdmin(): void
    {
        if (empty($_SESSION['admin_id'])) {
            $this->redirect($this->baseUrl('auth/login'));
        }
    }

    /**
     * JSON response
     */
    protected function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
}
