<?php
/**
 * Language — Multi-language support (EN/FR/MG)
 */
class Language
{
    private static array $translations = [];
    private static string $current = DEFAULT_LANGUAGE;

    /**
     * Initialize language from session or browser
     */
    public static function init(): void
    {
        if (Session::has('lang') && in_array(Session::get('lang'), SUPPORTED_LANGUAGES)) {
            self::$current = Session::get('lang');
        } elseif (isset($_GET['lang']) && in_array($_GET['lang'], SUPPORTED_LANGUAGES)) {
            self::$current = $_GET['lang'];
            Session::set('lang', self::$current);
        }

        self::load(self::$current);
    }

    /**
     * Load a language file
     */
    private static function load(string $lang): void
    {
        $file = LANG_PATH . '/' . $lang . '.php';
        if (file_exists($file)) {
            self::$translations = require $file;
        }
    }

    /**
     * Get translation by dot-notation key
     */
    public static function get(string $key, array $replace = []): string
    {
        $keys = explode('.', $key);
        $value = self::$translations;

        foreach ($keys as $k) {
            if (is_array($value) && isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $key; // Return key if not found
            }
        }

        if (!is_string($value)) return $key;

        foreach ($replace as $search => $replacement) {
            $value = str_replace(':' . $search, $replacement, $value);
        }

        return $value;
    }

    /**
     * Get current language code
     */
    public static function current(): string
    {
        return self::$current;
    }

    /**
     * Set language
     */
    public static function set(string $lang): void
    {
        if (in_array($lang, SUPPORTED_LANGUAGES)) {
            self::$current = $lang;
            Session::set('lang', $lang);
            self::load($lang);
        }
    }

    /**
     * Get all supported languages with labels
     */
    public static function available(): array
    {
        return [
            'en' => 'English',
            'fr' => 'Français',
            'mg' => 'Malagasy',
        ];
    }
}

/**
 * Global translation helper
 */
function __(string $key, array $replace = []): string
{
    return Language::get($key, $replace);
}
