<?php
/**
 * Nosy Luxury — Front Controller
 * ================================
 * All requests are routed through this file.
 */

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load configuration
require_once dirname(__DIR__) . '/config/app.php';

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
