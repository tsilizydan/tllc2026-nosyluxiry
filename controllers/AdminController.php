<?php
require_once MODELS_PATH . '/Booking.php';
require_once MODELS_PATH . '/Tour.php';
require_once MODELS_PATH . '/User.php';
require_once MODELS_PATH . '/Review.php';
require_once MODELS_PATH . '/BlogPost.php';

class AdminController extends Controller
{
    public function __construct()
    {
        if (!Auth::check() || !Auth::isAdmin()) {
            Session::flash('error', 'Access denied.');
            header('Location: ' . url('/login'));
            exit;
        }
    }

    public function dashboard(): void
    {
        $bookingModel = new Booking();
        $userModel = new User();
        $tourModel = new Tour();
        $db = Database::getInstance();

        $stats = $bookingModel->stats();
        $recentBookings = $bookingModel->recent(10);
        $totalUsers = $db->fetch("SELECT COUNT(*) as total FROM users WHERE role = 'customer'");
        $totalTours = $db->fetch("SELECT COUNT(*) as total FROM tours WHERE status = 'active'");
        $pendingReviews = $db->fetch("SELECT COUNT(*) as total FROM reviews WHERE status = 'pending'");
        $newMessages = $db->fetch("SELECT COUNT(*) as total FROM contact_messages WHERE is_read = 0");

        $this->view('admin.dashboard', [
            'pageTitle' => 'Admin Dashboard',
            'layout' => 'admin',
            'stats' => $stats,
            'recentBookings' => $recentBookings,
            'totalUsers' => $totalUsers->total ?? 0,
            'totalTours' => $totalTours->total ?? 0,
            'pendingReviews' => $pendingReviews->total ?? 0,
            'newMessages' => $newMessages->total ?? 0,
        ]);
    }

    public function tours(): void
    {
        $tourModel = new Tour();
        $result = $tourModel->paginate($this->getPage(), 20, 'name ASC');
        $this->view('admin.tours', [
            'pageTitle' => 'Manage Tours',
            'layout' => 'admin',
            'tours' => $result['items'],
            'pagination' => $result,
        ]);
    }

    public function bookings(): void
    {
        $bookingModel = new Booking();
        $result = $bookingModel->paginate($this->getPage(), 20, 'created_at DESC');
        $this->view('admin.bookings', [
            'pageTitle' => 'Manage Bookings',
            'layout' => 'admin',
            'bookings' => $result['items'],
            'pagination' => $result,
        ]);
    }

    public function users(): void
    {
        $userModel = new User();
        $result = $userModel->paginate($this->getPage(), 20, 'created_at DESC');
        $this->view('admin.users', [
            'pageTitle' => 'Manage Users',
            'layout' => 'admin',
            'users' => $result['items'],
            'pagination' => $result,
        ]);
    }

    public function reviews(): void
    {
        $reviewModel = new Review();
        $result = $reviewModel->paginate($this->getPage(), 20, 'created_at DESC');
        $this->view('admin.reviews', [
            'pageTitle' => 'Manage Reviews',
            'layout' => 'admin',
            'reviews' => $result['items'],
            'pagination' => $result,
        ]);
    }

    public function messages(): void
    {
        $db = Database::getInstance();
        $messages = $db->fetchAll("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 50");
        $this->view('admin.messages', [
            'pageTitle' => 'Messages',
            'layout' => 'admin',
            'messages' => $messages,
        ]);
    }

    public function settings(): void
    {
        $db = Database::getInstance();
        $settings = $db->fetchAll("SELECT * FROM settings ORDER BY setting_key");
        $this->view('admin.settings', [
            'pageTitle' => 'Settings',
            'layout' => 'admin',
            'settings' => $settings,
        ]);
    }

    public function updateBookingStatus(): void
    {
        if (!$this->validateCsrf()) { $this->json(['error' => 'Invalid token'], 403); return; }
        $db = Database::getInstance();
        $db->update('bookings', ['status' => $this->input('status')], 'id = ?', [(int)$this->input('id')]);
        Session::flash('success', 'Booking status updated.');
        $this->redirect('/admin/bookings');
    }

    public function approveReview(): void
    {
        if (!$this->validateCsrf()) { $this->json(['error' => 'Invalid token'], 403); return; }
        $db = Database::getInstance();
        $db->update('reviews', ['status' => $this->input('status', 'approved')], 'id = ?', [(int)$this->input('id')]);
        Session::flash('success', 'Review updated.');
        $this->redirect('/admin/reviews');
    }
}
