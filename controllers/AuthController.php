<?php
class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (Auth::check()) { $this->redirect('/account'); return; }
        $this->view('auth.login', ['pageTitle' => $this->setTitle(__('auth.login'))]);
    }

    public function login(): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/login'); return; }
        if (Auth::attempt($this->input('email'), $this->input('password'))) {
            Session::flash('success', __('auth.login_success'));
            $this->redirect(Auth::isAdmin() ? '/admin' : '/account');
        } else {
            Session::flash('error', __('auth.invalid_credentials'));
            $this->redirect('/login');
        }
    }

    public function registerForm(): void
    {
        if (Auth::check()) { $this->redirect('/account'); return; }
        $this->view('auth.register', ['pageTitle' => $this->setTitle(__('auth.register'))]);
    }

    public function register(): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/register'); return; }
        $email = $this->input('email');
        $db = Database::getInstance();
        if ($db->fetch("SELECT id FROM users WHERE email = ?", [$email])) {
            Session::flash('error', 'Email already registered.');
            $this->redirect('/register');
            return;
        }
        Auth::register([
            'first_name' => $this->input('first_name'),
            'last_name' => $this->input('last_name'),
            'email' => $email,
            'phone' => $this->input('phone'),
            'password' => $this->input('password'),
            'role' => 'customer',
            'status' => 'active',
        ]);
        Auth::attempt($email, $this->input('password'));
        Session::flash('success', __('auth.register_success'));
        $this->redirect('/account');
    }

    public function logout(): void
    {
        Auth::logout();
        Session::start();
        Session::flash('success', __('auth.logout_success'));
        $this->redirect('/');
    }
}
