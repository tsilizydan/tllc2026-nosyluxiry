<?php
/**
 * Nosy Luxury — Application Configuration
 * =========================================
 * Update these values for your environment.
 */

// --- Environment ---
define('APP_ENV', 'development'); // 'development' | 'production'

// --- Application ---
define('APP_NAME', 'Nosy Luxury');
define('APP_TAGLINE', 'Discover Madagascar. Redefined.');
define('APP_VERSION', '1.0.0');

// --- Base URL (auto-detect or hardcode) ---
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('BASE_URL', rtrim($protocol . '://' . $host, '/'));

// --- Database ---
define('DB_HOST', 'localhost');
define('DB_NAME', 'tsilscpx_nosy_luxury');
define('DB_USER', 'tsilscpx_chibi_admin');
define('DB_PASS', '9@UPN~I@O]Dw');
define('DB_CHARSET', 'utf8mb4');

// --- Paths ---
define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('CORE_PATH', ROOT_PATH . '/core');
define('CONTROLLERS_PATH', ROOT_PATH . '/controllers');
define('MODELS_PATH', ROOT_PATH . '/models');
define('VIEWS_PATH', ROOT_PATH . '/views');
define('ASSETS_PATH', ROOT_PATH . '/assets');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');
define('LANG_PATH', ROOT_PATH . '/lang');

// --- Session ---
define('SESSION_NAME', 'nosy_luxury_session');
define('SESSION_LIFETIME', 7200); // 2 hours

// --- Security ---
define('CSRF_TOKEN_NAME', '_csrf_token');
define('HASH_ALGO', PASSWORD_BCRYPT);
define('HASH_COST', 12);

// --- Upload ---
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10 MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);

// --- Supported Languages ---
define('SUPPORTED_LANGUAGES', ['en', 'fr', 'mg']);
define('DEFAULT_LANGUAGE', 'en');

// --- Currency ---
define('DEFAULT_CURRENCY', 'EUR');
define('SUPPORTED_CURRENCIES', ['EUR', 'USD', 'MGA']);

// --- Payment Gateways ---
define('STRIPE_SECRET_KEY', '');       // sk_test_... or sk_live_...
define('STRIPE_PUBLISHABLE_KEY', '');   // pk_test_... or pk_live_...
define('STRIPE_WEBHOOK_SECRET', '');    // whsec_...

define('PAYPAL_CLIENT_ID', '');         // sandbox or live client ID
define('PAYPAL_SECRET', '');            // sandbox or live secret
define('PAYPAL_MODE', 'sandbox');       // 'sandbox' or 'live'

define('PAYMENT_DEPOSIT_PERCENT', 30); // % deposit for pay-on-arrival

// --- Pagination ---
define('ITEMS_PER_PAGE', 12);

// --- Contact ---
define('CONTACT_EMAIL', 'info@nosyluxury.com');
define('CONTACT_PHONE', '+261 34 00 000 00');
define('WHATSAPP_NUMBER', '+261340000000');
