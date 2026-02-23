<?php
/**
 * Auth — Authentication helper
 */
class Auth
{
    /**
     * Attempt login
     */
    public static function attempt(string $email, string $password): bool
    {
        $db = Database::getInstance();
        $user = $db->fetch("SELECT * FROM users WHERE email = ? AND status = 'active'", [$email]);

        if ($user && password_verify($password, $user->password)) {
            Session::set('user_id', $user->id);
            Session::set('user_role', $user->role);
            Session::set('user_name', $user->first_name . ' ' . $user->last_name);
            Session::set('user_email', $user->email);

            // Update last login
            $db->update('users', ['last_login' => date('Y-m-d H:i:s')], 'id = ?', [$user->id]);
            return true;
        }

        return false;
    }

    /**
     * Register a new user
     */
    public static function register(array $data): int
    {
        $db = Database::getInstance();
        $data['password'] = password_hash($data['password'], HASH_ALGO, ['cost' => HASH_COST]);
        $data['created_at'] = date('Y-m-d H:i:s');
        return $db->insert('users', $data);
    }

    /**
     * Check if user is logged in
     */
    public static function check(): bool
    {
        return Session::has('user_id');
    }

    /**
     * Get current user ID
     */
    public static function id(): ?int
    {
        return Session::get('user_id');
    }

    /**
     * Get current user object
     */
    public static function user(): ?object
    {
        if (!self::check()) return null;
        $db = Database::getInstance();
        return $db->fetch("SELECT * FROM users WHERE id = ?", [self::id()]);
    }

    /**
     * Get current user's role
     */
    public static function role(): ?string
    {
        return Session::get('user_role');
    }

    /**
     * Check if current user is admin
     */
    public static function isAdmin(): bool
    {
        return in_array(self::role(), ['admin', 'super_admin']);
    }

    /**
     * Logout
     */
    public static function logout(): void
    {
        Session::remove('user_id');
        Session::remove('user_role');
        Session::remove('user_name');
        Session::remove('user_email');
        Session::destroy();
    }
}
