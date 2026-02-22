<?php
/**
 * Controller — Base controller class
 */
class Controller
{
    protected Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Render a view with layout
     */
    protected function view(string $view, array $data = [], string $layout = 'public'): void
    {
        // Allow layout override from data array
        if (isset($data['layout'])) {
            $layout = $data['layout'];
            unset($data['layout']);
        }

        // Extract data to make variables available in view
        extract($data);

        // Capture the view content
        ob_start();
        $viewFile = VIEWS_PATH . '/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewFile)) {
            http_response_code(500);
            die("View not found: {$view}");
        }
        include $viewFile;
        $content = ob_get_clean();

        // Render within layout
        $layoutFile = VIEWS_PATH . '/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            include $layoutFile;
        } else {
            echo $content;
        }
    }

    /**
     * Render a view without layout
     */
    protected function partial(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = VIEWS_PATH . '/' . str_replace('.', '/', $view) . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        }
    }

    /**
     * Redirect to URL
     */
    protected function redirect(string $url): void
    {
        if (!str_starts_with($url, 'http')) {
            $url = BASE_URL . '/' . ltrim($url, '/');
        }
        header('Location: ' . $url);
        exit;
    }

    /**
     * Return JSON response
     */
    protected function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Get POST data
     */
    protected function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Get GET data
     */
    protected function query(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Validate CSRF token
     */
    protected function validateCsrf(): bool
    {
        $token = $this->input(CSRF_TOKEN_NAME) ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
        return Session::validateCsrf($token);
    }

    /**
     * Require authentication
     */
    protected function requireAuth(): void
    {
        if (!Auth::check()) {
            Session::flash('error', __('auth.login_required'));
            $this->redirect('/login');
        }
    }

    /**
     * Require admin role
     */
    protected function requireAdmin(): void
    {
        $this->requireAuth();
        if (!Auth::isAdmin()) {
            http_response_code(403);
            die('Access denied.');
        }
    }

    /**
     * Get current page for pagination
     */
    protected function getPage(): int
    {
        return max(1, (int) ($this->query('page', 1)));
    }

    /**
     * Set page title
     */
    protected function setTitle(string $title): string
    {
        return $title . ' — ' . APP_NAME;
    }
}
