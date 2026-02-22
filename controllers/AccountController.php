<?php
require_once MODELS_PATH . '/Booking.php';

class AccountController extends Controller
{
    public function dashboard(): void
    {
        $this->requireAuth();
        $bookingModel = new Booking();
        $bookings = $bookingModel->userBookings(Auth::id());
        $this->view('account.dashboard', [
            'pageTitle' => $this->setTitle(__('nav.account')),
            'user' => Auth::user(),
            'bookings' => $bookings,
        ]);
    }

    public function bookings(): void
    {
        $this->requireAuth();
        $bookingModel = new Booking();
        $this->view('account.bookings', [
            'pageTitle' => $this->setTitle(__('nav.bookings')),
            'bookings' => $bookingModel->userBookings(Auth::id()),
        ]);
    }

    public function wishlist(): void
    {
        $this->requireAuth();
        $db = Database::getInstance();
        $tours = $db->fetchAll(
            "SELECT t.* FROM wishlists w JOIN tours t ON w.tour_id = t.id WHERE w.user_id = ? ORDER BY w.created_at DESC",
            [Auth::id()]
        );
        $this->view('account.wishlist', [
            'pageTitle' => $this->setTitle(__('nav.wishlist')),
            'tours' => $tours,
        ]);
    }

    public function toggleWishlist(): void
    {
        $this->requireAuth();
        if (!$this->validateCsrf()) { $this->json(['error' => 'Invalid token'], 403); return; }
        $tourId = (int) $this->input('tour_id');
        $db = Database::getInstance();
        $exists = $db->fetch("SELECT id FROM wishlists WHERE user_id = ? AND tour_id = ?", [Auth::id(), $tourId]);
        if ($exists) {
            $db->delete('wishlists', 'user_id = ? AND tour_id = ?', [Auth::id(), $tourId]);
            $this->json(['wishlisted' => false]);
        } else {
            $db->insert('wishlists', ['user_id' => Auth::id(), 'tour_id' => $tourId, 'created_at' => date('Y-m-d H:i:s')]);
            $this->json(['wishlisted' => true]);
        }
    }
}
