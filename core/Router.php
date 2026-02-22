<?php
/**
 * Router — URL routing engine
 */
class Router
{
    private array $routes = [];
    private array $namedRoutes = [];

    public function get(string $path, string $action, ?string $name = null): self
    {
        return $this->addRoute('GET', $path, $action, $name);
    }

    public function post(string $path, string $action, ?string $name = null): self
    {
        return $this->addRoute('POST', $path, $action, $name);
    }

    private function addRoute(string $method, string $path, string $action, ?string $name): self
    {
        $pattern = $this->convertToRegex($path);
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'pattern' => $pattern,
            'action' => $action,
        ];
        if ($name) {
            $this->namedRoutes[$name] = $path;
        }
        return $this;
    }

    private function convertToRegex(string $path): string
    {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    public function dispatch(string $uri, string $method): void
    {
        $uri = '/' . trim(parse_url($uri, PHP_URL_PATH), '/');
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;

            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->callAction($route['action'], $params);
                return;
            }
        }

        // 404 — no route matched
        http_response_code(404);
        $this->render404();
    }

    private function callAction(string $action, array $params): void
    {
        [$controllerName, $methodName] = explode('@', $action);

        // Determine file path
        $file = CONTROLLERS_PATH . '/' . str_replace('\\', '/', $controllerName) . '.php';
        if (!file_exists($file)) {
            http_response_code(500);
            die("Controller file not found: {$controllerName}");
        }

        require_once $file;

        // Get just the class name (last segment)
        $className = basename(str_replace('\\', '/', $controllerName));
        if (!class_exists($className)) {
            http_response_code(500);
            die("Controller class not found: {$className}");
        }

        $controller = new $className();
        if (!method_exists($controller, $methodName)) {
            http_response_code(500);
            die("Method not found: {$className}@{$methodName}");
        }

        call_user_func_array([$controller, $methodName], $params);
    }

    private function render404(): void
    {
        $viewFile = VIEWS_PATH . '/errors/404.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo '<h1>404 — Page Not Found</h1>';
        }
    }

    public function url(string $name, array $params = []): string
    {
        if (!isset($this->namedRoutes[$name])) return '#';
        $path = $this->namedRoutes[$name];
        foreach ($params as $key => $value) {
            $path = str_replace('{' . $key . '}', $value, $path);
        }
        return BASE_URL . $path;
    }
}
