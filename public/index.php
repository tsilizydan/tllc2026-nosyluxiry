<?php
/**
 * Nosy Luxury — Front Controller
 * ================================
 * All requests are routed through this file.
 */

// Load configuration first (defines APP_ENV)
require_once dirname(__DIR__) . '/config/app.php';

// Error reporting — environment-aware
error_reporting(E_ALL);
if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
}

// Global exception handler — prevent raw PHP errors from leaking
set_exception_handler(function (Throwable $e) {
    http_response_code(500);
    if (defined('APP_ENV') && APP_ENV === 'development') {
        echo '<h1>500 — Server Error</h1>';
        echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        if (defined('VIEWS_PATH') && file_exists(VIEWS_PATH . '/errors/500.php')) {
            include VIEWS_PATH . '/errors/500.php';
        } else {
            echo '<h1>500 — Something went wrong</h1><p>Please try again later.</p>';
        }
    }
    exit;
});

set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

// Load core classes
require_once CORE_PATH . '/Database.php';
require_once CORE_PATH . '/Session.php';
require_once CORE_PATH . '/Auth.php';
require_once CORE_PATH . '/Language.php';
require_once CORE_PATH . '/Helpers.php';
require_once CORE_PATH . '/Model.php';
require_once CORE_PATH . '/Controller.php';
require_once CORE_PATH . '/Router.php';

// Start session
Session::start();

// Initialize language
Language::init();

// Generate CSRF token
Session::generateCsrf();

// Create router and load routes
$router = new Router();
require_once CONFIG_PATH . '/routes.php';

// Get the request URI
$uri = $_GET['url'] ?? '/';
$uri = '/' . trim($uri, '/');

// Dispatch the request
$router->dispatch($uri, $_SERVER['REQUEST_METHOD']);
