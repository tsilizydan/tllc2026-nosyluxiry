<?php
/**
 * Helpers — Global utility functions
 */

/**
 * Generate full URL for an asset file
 */
function asset(string $path): string
{
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

/**
 * Generate full application URL
 */
function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * HTML-escape a string
 */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Format price with currency symbol
 */
function formatPrice(float $amount, string $currency = DEFAULT_CURRENCY): string
{
    $symbols = ['EUR' => '€', 'USD' => '$', 'MGA' => 'Ar'];
    $symbol = $symbols[$currency] ?? $currency;
    
    if ($currency === 'MGA') {
        return number_format($amount, 0, ',', ' ') . ' ' . $symbol;
    }
    return $symbol . number_format($amount, 2, '.', ',');
}

/**
 * Time ago helper
 */
function timeAgo(string $datetime): string
{
    $now = new DateTime();
    $past = new DateTime($datetime);
    $diff = $now->diff($past);

    if ($diff->y > 0) return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
    if ($diff->m > 0) return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
    if ($diff->d > 0) return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    if ($diff->h > 0) return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    if ($diff->i > 0) return $diff->i . ' min' . ($diff->i > 1 ? 's' : '') . ' ago';
    return 'just now';
}

/**
 * Generate a CSRF hidden input field
 */
function csrf_field(): string
{
    return Session::csrfField();
}

/**
 * Get CSRF token value
 */
function csrf_token(): string
{
    return Session::generateCsrf();
}

/**
 * Truncate text
 */
function truncate(string $text, int $length = 150, string $suffix = '...'): string
{
    if (mb_strlen($text) <= $length) return $text;
    return mb_substr($text, 0, $length) . $suffix;
}

/**
 * Generate URL-friendly slug
 */
function slugify(string $text): string
{
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text), '-'));
}

/**
 * Get upload URL
 */
function upload_url(string $path): string
{
    return BASE_URL . '/uploads/' . ltrim($path, '/');
}

/**
 * Format date
 */
function formatDate(string $date, string $format = 'M d, Y'): string
{
    return date($format, strtotime($date));
}

/**
 * Check if current URL matches
 */
function isActive(string $path): string
{
    $current = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = '/' . ltrim($path, '/');
    return ($current === $path || str_starts_with($current, $path . '/')) ? 'active' : '';
}

/**
 * Get setting value from database
 */
function setting(string $key, mixed $default = null): mixed
{
    static $settings = null;
    if ($settings === null) {
        try {
            $db = Database::getInstance();
            $rows = $db->fetchAll("SELECT setting_key, setting_value FROM settings");
            $settings = [];
            foreach ($rows as $row) {
                $settings[$row->setting_key] = $row->setting_value;
            }
        } catch (Exception $e) {
            $settings = [];
        }
    }
    return $settings[$key] ?? $default;
}

/**
 * Generate star rating HTML
 */
function starRating(float $rating): string
{
    $html = '<div class="star-rating">';
    for ($i = 1; $i <= 5; $i++) {
        if ($rating >= $i) {
            $html .= '<i class="fas fa-star"></i>';
        } elseif ($rating >= $i - 0.5) {
            $html .= '<i class="fas fa-star-half-alt"></i>';
        } else {
            $html .= '<i class="far fa-star"></i>';
        }
    }
    $html .= '</div>';
    return $html;
}

/**
 * Dump and die (development only)
 */
function dd(mixed ...$vars): void
{
    if (APP_ENV !== 'development') return;
    echo '<pre style="background:#1a1a2e;color:#eee;padding:20px;margin:20px;border-radius:8px;overflow-x:auto;">';
    foreach ($vars as $var) {
        var_dump($var);
        echo "\n---\n";
    }
    echo '</pre>';
    die();
}
