<?php
require_once MODELS_PATH . '/Booking.php';

class AccountController extends Controller
{
    // ─── Dashboard ───────────────────────────────────────────

    public function dashboard(): void
    {
        $this->requireAuth();
        $bookingModel = new Booking();
        $bookings = $bookingModel->userBookings(Auth::id());
        $db = Database::getInstance();
        $wishlistCount = (int) $db->fetchColumn("SELECT COUNT(*) FROM wishlists WHERE user_id = ?", [Auth::id()]);
        $upcomingCount = (int) $db->fetchColumn(
            "SELECT COUNT(*) FROM bookings WHERE user_id = ? AND travel_date >= CURDATE() AND status IN ('pending','confirmed')",
            [Auth::id()]
        );
        $this->view('account.dashboard', [
            'pageTitle' => $this->setTitle(__('nav.account')),
            'user' => Auth::user(),
            'bookings' => $bookings,
            'wishlistCount' => $wishlistCount,
            'upcomingCount' => $upcomingCount,
        ]);
    }

    // ─── Profile ─────────────────────────────────────────────

    public function profile(): void
    {
        $this->requireAuth();
        $this->view('account.profile', [
            'pageTitle' => $this->setTitle('Edit Profile'),
            'user' => Auth::user(),
        ]);
    }

    public function updateProfile(): void
    {
        $this->requireAuth();
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/account/profile'); return; }

        $data = [
            'first_name' => trim($this->input('first_name')),
            'last_name' => trim($this->input('last_name')),
            'email' => trim($this->input('email')),
            'phone' => trim($this->input('phone', '')),
            'bio' => trim($this->input('bio', '')),
        ];

        // Validate email uniqueness
        $db = Database::getInstance();
        $existing = $db->fetch("SELECT id FROM users WHERE email = ? AND id != ?", [$data['email'], Auth::id()]);
        if ($existing) {
            Session::flash('error', 'That email is already taken.');
            $this->redirect('/account/profile');
            return;
        }

        // Handle avatar upload
        if (!empty($_FILES['avatar']['name'])) {
            $file = $_FILES['avatar'];
            if (in_array($file['type'], ALLOWED_IMAGE_TYPES) && $file['size'] <= MAX_UPLOAD_SIZE) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'avatars/user_' . Auth::id() . '_' . time() . '.' . $ext;
                $dest = UPLOADS_PATH . '/' . $filename;
                @mkdir(dirname($dest), 0755, true);
                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    $data['avatar'] = $filename;
                }
            }
        }

        $db->update('users', $data, 'id = ?', [Auth::id()]);

        // Update session data
        Session::set('user_name', $data['first_name'] . ' ' . $data['last_name']);

        Session::flash('success', 'Profile updated successfully.');
        $this->redirect('/account/profile');
    }

    public function changePassword(): void
    {
        $this->requireAuth();
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/account/profile'); return; }

        $current = $this->input('current_password');
        $new = $this->input('new_password');
        $confirm = $this->input('confirm_password');

        $user = Auth::user();

        if (!password_verify($current, $user->password)) {
            Session::flash('error', 'Current password is incorrect.');
            $this->redirect('/account/profile');
            return;
        }

        if (strlen($new) < 8) {
            Session::flash('error', 'New password must be at least 8 characters.');
            $this->redirect('/account/profile');
            return;
        }

        if ($new !== $confirm) {
            Session::flash('error', 'New passwords do not match.');
            $this->redirect('/account/profile');
            return;
        }

        $db = Database::getInstance();
        $db->update('users', ['password' => password_hash($new, HASH_ALGO, ['cost' => HASH_COST])], 'id = ?', [Auth::id()]);

        Session::flash('success', 'Password changed successfully.');
        $this->redirect('/account/profile');
    }

    // ─── Bookings ────────────────────────────────────────────

    public function bookings(): void
    {
        $this->requireAuth();
        $bookingModel = new Booking();
        $bookings = $bookingModel->userBookings(Auth::id());

        // Check which bookings already have reviews
        $db = Database::getInstance();
        $reviewedIds = [];
        if (!empty($bookings)) {
            $ids = array_map(fn($b) => $b->id, $bookings);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $reviews = $db->fetchAll("SELECT booking_id FROM reviews WHERE booking_id IN ({$placeholders})", $ids);
            $reviewedIds = array_map(fn($r) => $r->booking_id, $reviews);
        }

        $this->view('account.bookings', [
            'pageTitle' => $this->setTitle(__('nav.bookings')),
            'bookings' => $bookings,
            'reviewedIds' => $reviewedIds,
        ]);
    }

    public function cancelBooking(): void
    {
        $this->requireAuth();
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/account/bookings'); return; }

        $bookingId = (int) $this->input('booking_id');
        $db = Database::getInstance();
        $booking = $db->fetch("SELECT * FROM bookings WHERE id = ? AND user_id = ?", [$bookingId, Auth::id()]);

        if (!$booking) {
            Session::flash('error', 'Booking not found.');
            $this->redirect('/account/bookings');
            return;
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            Session::flash('error', 'This booking cannot be cancelled.');
            $this->redirect('/account/bookings');
            return;
        }

        $db->update('bookings', ['status' => 'cancelled'], 'id = ?', [$bookingId]);
        Session::flash('success', 'Booking ' . $booking->reference . ' has been cancelled.');
        $this->redirect('/account/bookings');
    }

    public function reviewForm(string $reference): void
    {
        $this->requireAuth();
        $bookingModel = new Booking();
        $booking = $bookingModel->findByReference($reference);

        if (!$booking || $booking->user_id != Auth::id()) {
            Session::flash('error', 'Booking not found.');
            $this->redirect('/account/bookings');
            return;
        }

        if ($booking->status !== 'completed' && $booking->status !== 'confirmed') {
            Session::flash('error', 'You can only review completed bookings.');
            $this->redirect('/account/bookings');
            return;
        }

        $this->view('account.review', [
            'pageTitle' => $this->setTitle('Leave a Review'),
            'booking' => $booking,
        ]);
    }

    public function submitReview(string $reference): void
    {
        $this->requireAuth();
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/account/bookings'); return; }

        $bookingModel = new Booking();
        $booking = $bookingModel->findByReference($reference);

        if (!$booking || $booking->user_id != Auth::id()) {
            Session::flash('error', 'Booking not found.');
            $this->redirect('/account/bookings');
            return;
        }

        $db = Database::getInstance();

        // Check for existing review
        $existing = $db->fetch("SELECT id FROM reviews WHERE booking_id = ? AND user_id = ?", [$booking->id, Auth::id()]);
        if ($existing) {
            Session::flash('error', 'You already reviewed this booking.');
            $this->redirect('/account/bookings');
            return;
        }

        $rating = max(1, min(5, (int) $this->input('rating', 5)));
        $db->insert('reviews', [
            'tour_id' => $booking->tour_id,
            'user_id' => Auth::id(),
            'booking_id' => $booking->id,
            'reviewer_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
            'rating' => $rating,
            'title' => trim($this->input('title', '')),
            'comment' => trim($this->input('comment')),
            'is_verified' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        Session::flash('success', 'Review submitted! It will appear after approval.');
        $this->redirect('/account/bookings');
    }

    // ─── Wishlist ────────────────────────────────────────────

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
