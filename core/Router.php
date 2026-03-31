<?php
/**
 * Simple MVC Router - maps URI to Controller@action
 */
namespace Core;

class Router
{
    private array $routes = [];
    private string $basePath = '';

    public function __construct(string $basePath = '')
    {
        $this->basePath = rtrim($basePath, '/');
    }

    /**
     * Add GET route
     */
    public function get(string $uri, string $handler): self
    {
        $path = $this->normalizePath($this->basePath . $uri);
        $this->routes['GET'][$path] = $handler;
        return $this;
    }

    /**
     * Add POST route
     */
    public function post(string $uri, string $handler): self
    {
        $path = $this->normalizePath($this->basePath . $uri);
        $this->routes['POST'][$path] = $handler;
        return $this;
    }

    /**
     * Add route for both GET and POST
     */
    public function any(string $uri, string $handler): self
    {
        $this->get($uri, $handler);
        $this->post($uri, $handler);
        return $this;
    }

    /**
     * Dispatch current request
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->getUri();

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $pattern = $this->routeToRegex($route);
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $this->callHandler($handler, $matches);
                return;
            }
        }

        http_response_code(404);
        echo '404 - Page not found';
    }

    private function getUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'];
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        // Strip /index.php from URI when mod_rewrite is off (e.g. /decor/public/index.php -> /decor/public)
        if (preg_match('#^(.*)/index\.php/*$#', $uri, $m)) {
            $uri = $m[1] === '' ? '/' : $m[1];
        }
        return $this->normalizePath($uri);
    }

    /** Normalize path: trim slashes, ensure single leading slash, empty becomes "/" */
    private function normalizePath(string $path): string
    {
        $path = '/' . trim($path, '/');
        return $path === '' ? '/' : $path;
    }

    private function routeToRegex(string $route): string
    {
        $route = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $route);
        return '#^' . $route . '$#';
    }

    private function callHandler(string $handler, array $params): void
    {
        [$controllerName, $action] = explode('@', $handler);
        $controllerClass = 'App\\Controllers\\' . $controllerName;
        if (!class_exists($controllerClass)) {
            throw new \RuntimeException("Controller not found: {$controllerClass}");
        }
        $controller = new $controllerClass();
        if (!method_exists($controller, $action)) {
            throw new \RuntimeException("Action not found: {$action}");
        }
        $controller->$action(...array_values($params));
    }
}