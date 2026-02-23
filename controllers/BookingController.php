<?php
require_once MODELS_PATH . '/Booking.php';
require_once MODELS_PATH . '/Tour.php';

class BookingController extends Controller
{
    public function create(string $tourId): void
    {
        $tourModel = new Tour();
        $tour = $tourModel->find((int)$tourId);
        if (!$tour) { http_response_code(404); include VIEWS_PATH . '/errors/404.php'; return; }
        $this->view('booking.checkout', [
            'pageTitle' => $this->setTitle(__('booking.title')),
            'tour' => $tour,
        ]);
    }

    public function store(string $tourId): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/tours'); return; }
        $tourModel = new Tour();
        $tour = $tourModel->find((int)$tourId);
        if (!$tour) { $this->redirect('/tours'); return; }
        $guests = max(1, (int) $this->input('guests', 1));
        $price = $tour->sale_price ?? $tour->price;
        $total = $price * $guests;
        $bookingModel = new Booking();
        $ref = $bookingModel->generateReference();
        $paymentMethod = $this->input('payment_method', 'bank_transfer');
        $bookingId = $bookingModel->create([
            'reference' => $ref,
            'user_id' => Auth::id(),
            'tour_id' => $tour->id,
            'guest_name' => $this->input('name'),
            'guest_email' => $this->input('email'),
            'guest_phone' => $this->input('phone'),
            'num_guests' => $guests,
            'special_requests' => $this->input('special_requests'),
            'subtotal' => $total,
            'total' => $total,
            'currency' => DEFAULT_CURRENCY,
            'status' => 'pending',
            'payment_method' => $paymentMethod,
            'payment_status' => 'unpaid',
            'travel_date' => $this->input('travel_date'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Create payment record
        require_once MODELS_PATH . '/Payment.php';
        $paymentModel = new Payment();
        $gateway = match ($paymentMethod) {
            'stripe', 'card' => 'stripe',
            'paypal' => 'paypal',
            'mobile_money' => 'mobile_money',
            'cash', 'pay_on_arrival' => 'cash',
            default => 'bank_transfer',
        };
        $paymentModel->createForBooking($bookingId, $gateway, $total, DEFAULT_CURRENCY);

        // Route to payment processing
        Session::set('pending_booking_ref', $ref);

        // For offline methods, skip payment processing and go directly
        if (in_array($paymentMethod, ['bank_transfer', 'mobile_money'])) {
            Session::flash('success', __('booking.success'));
            $this->redirect('/payment/bank/' . $ref);
        } elseif (in_array($paymentMethod, ['cash', 'pay_on_arrival'])) {
            Session::flash('success', __('booking.success'));
            $this->redirect('/payment/cash/' . $ref);
        } else {
            // Online payment — redirect to payment processor
            require_once CORE_PATH . '/PaymentGateway.php';
            $booking = $bookingModel->findByReference($ref);
            if ($paymentMethod === 'stripe' || $paymentMethod === 'card') {
                $url = PaymentGateway::createStripeSession($booking, $total, DEFAULT_CURRENCY);
                if ($url) { header('Location: ' . $url); exit; }
            } elseif ($paymentMethod === 'paypal') {
                $url = PaymentGateway::createPayPalOrder($booking, $total, DEFAULT_CURRENCY);
                if ($url) { header('Location: ' . $url); exit; }
            }
            // Fallback if gateway fails
            Session::flash('error', 'Online payment temporarily unavailable. Please use bank transfer.');
            $this->redirect('/payment/bank/' . $ref);
        }
    }

    public function confirmation(string $reference): void
    {
        $bookingModel = new Booking();
        $booking = $bookingModel->findByReference($reference);
        if (!$booking) { http_response_code(404); include VIEWS_PATH . '/errors/404.php'; return; }
        $this->view('booking.confirmation', [
            'pageTitle' => $this->setTitle(__('booking.success')),
            'booking' => $booking,
        ]);
    }

    public function checkAvailability(string $tourId): void
    {
        $db = Database::getInstance();
        $dates = $db->fetchAll(
            "SELECT * FROM tour_dates WHERE tour_id = ? AND start_date >= CURDATE() AND status = 'available' ORDER BY start_date",
            [(int)$tourId]
        );
        $this->json(['dates' => $dates]);
    }
}
