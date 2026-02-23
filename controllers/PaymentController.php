<?php
/**
 * PaymentController — Handles payment processing for bookings.
 * Routes users to the correct gateway (Stripe, PayPal, Bank Transfer, Cash).
 */
require_once MODELS_PATH . '/Booking.php';
require_once MODELS_PATH . '/Payment.php';
require_once CORE_PATH . '/PaymentGateway.php';

class PaymentController extends Controller
{
    /**
     * Process payment — route to correct gateway based on payment_method
     */
    public function process(string $bookingId): void
    {
        $bookingModel = new Booking();
        $booking = $bookingModel->findByReference($bookingId) ?: $bookingModel->find((int)$bookingId);
        if (!$booking) { $this->redirect('/tours'); return; }

        $method = $booking->payment_method ?? 'bank_transfer';
        $amount = (float)($booking->total ?? $booking->subtotal ?? 0);

        // Create payment record if none exists yet
        $paymentModel = new Payment();
        $existing = $paymentModel->forBooking($booking->id);
        if (empty($existing)) {
            $gateway = $this->mapMethodToGateway($method);
            $paymentModel->createForBooking($booking->id, $gateway, $amount, $booking->currency ?? 'EUR');
        }

        switch ($method) {
            case 'stripe':
            case 'card':
                $this->initiateStripe($booking, $amount);
                break;
            case 'paypal':
                $this->initiatePayPal($booking, $amount);
                break;
            case 'bank_transfer':
                $this->redirect('/payment/bank/' . $booking->reference);
                break;
            case 'mobile_money':
                $this->redirect('/payment/bank/' . $booking->reference);
                break;
            case 'cash':
            case 'pay_on_arrival':
                $this->redirect('/payment/cash/' . $booking->reference);
                break;
            default:
                $this->redirect('/payment/bank/' . $booking->reference);
        }
    }

    /**
     * Initiate Stripe Checkout Session
     */
    private function initiateStripe(object $booking, float $amount): void
    {
        $url = PaymentGateway::createStripeSession($booking, $amount, $booking->currency ?? 'EUR');
        if ($url) {
            header('Location: ' . $url);
            exit;
        }
        // Stripe not configured or failed — fall back to bank transfer
        Session::flash('error', 'Online payment is temporarily unavailable. Please use bank transfer.');
        $this->redirect('/payment/bank/' . $booking->reference);
    }

    /**
     * Initiate PayPal Checkout
     */
    private function initiatePayPal(object $booking, float $amount): void
    {
        $url = PaymentGateway::createPayPalOrder($booking, $amount, $booking->currency ?? 'EUR');
        if ($url) {
            header('Location: ' . $url);
            exit;
        }
        Session::flash('error', 'PayPal is temporarily unavailable. Please use bank transfer.');
        $this->redirect('/payment/bank/' . $booking->reference);
    }

    // ─── Stripe Callbacks ────────────────────────────────────

    /**
     * Stripe success return
     */
    public function stripeSuccess(string $reference): void
    {
        $sessionId = $_GET['session_id'] ?? '';
        $bookingModel = new Booking();
        $booking = $bookingModel->findByReference($reference);
        if (!$booking) { $this->redirect('/'); return; }

        // Verify the session with Stripe
        if ($sessionId) {
            $session = PaymentGateway::retrieveStripeSession($sessionId);
            if ($session && ($session['payment_status'] ?? '') === 'paid') {
                $this->completePayment($booking, $session['payment_intent'] ?? $sessionId);
            }
        }

        Session::flash('success', 'Payment received! Your booking is confirmed.');
        $this->redirect('/booking/confirmation/' . $reference);
    }

    /**
     * Stripe cancel return
     */
    public function stripeCancel(string $reference): void
    {
        $bookingModel = new Booking();
        $booking = $bookingModel->findByReference($reference);
        if ($booking) {
            $this->failPayment($booking);
        }
        $this->view('booking.payment-failed', [
            'pageTitle' => $this->setTitle('Payment Cancelled'),
            'booking' => $booking,
            'reason' => 'cancelled',
        ]);
    }

    /**
     * Stripe Webhook (no CSRF validation)
     */
    public function stripeWebhook(): void
    {
        $payload = file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

        $event = PaymentGateway::verifyStripeWebhook($payload, $sigHeader);
        if (!$event) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid signature']);
            return;
        }

        if (($event['type'] ?? '') === 'checkout.session.completed') {
            $session = $event['data']['object'] ?? [];
            $reference = $session['client_reference_id'] ?? ($session['metadata']['booking_ref'] ?? '');

            if ($reference) {
                $bookingModel = new Booking();
                $booking = $bookingModel->findByReference($reference);
                if ($booking) {
                    $this->completePayment($booking, $session['payment_intent'] ?? '');
                }
            }
        }

        http_response_code(200);
        echo json_encode(['received' => true]);
    }

    // ─── PayPal Callbacks ────────────────────────────────────

    /**
     * PayPal return — capture the order
     */
    public function paypalReturn(string $reference): void
    {
        $token = $_GET['token'] ?? '';
        $bookingModel = new Booking();
        $booking = $bookingModel->findByReference($reference);
        if (!$booking) { $this->redirect('/'); return; }

        if ($token) {
            $result = PaymentGateway::capturePayPalOrder($token);
            $status = $result['status'] ?? '';

            if ($status === 'COMPLETED') {
                // Extract PayPal transaction ID
                $captureId = '';
                if (!empty($result['purchase_units'][0]['payments']['captures'][0]['id'])) {
                    $captureId = $result['purchase_units'][0]['payments']['captures'][0]['id'];
                }
                $this->completePayment($booking, $captureId ?: $token);
                Session::flash('success', 'Payment received via PayPal! Your booking is confirmed.');
                $this->redirect('/booking/confirmation/' . $reference);
                return;
            }
        }

        // Payment not captured
        Session::flash('error', 'PayPal payment could not be completed. Please try again.');
        $this->view('booking.payment-failed', [
            'pageTitle' => $this->setTitle('Payment Failed'),
            'booking' => $booking,
            'reason' => 'failed',
        ]);
    }

    /**
     * PayPal cancel
     */
    public function paypalCancel(string $reference): void
    {
        $bookingModel = new Booking();
        $booking = $bookingModel->findByReference($reference);
        if ($booking) {
            $this->failPayment($booking);
        }
        $this->view('booking.payment-failed', [
            'pageTitle' => $this->setTitle('Payment Cancelled'),
            'booking' => $booking,
            'reason' => 'cancelled',
        ]);
    }

    // ─── Offline Payment Pages ───────────────────────────────

    /**
     * Bank transfer instructions
     */
    public function bankInstructions(string $reference): void
    {
        $bookingModel = new Booking();
        $booking = $bookingModel->findByReference($reference);
        if (!$booking) { http_response_code(404); include VIEWS_PATH . '/errors/404.php'; return; }

        $this->view('booking.bank-instructions', [
            'pageTitle' => $this->setTitle('Bank Transfer'),
            'booking' => $booking,
        ]);
    }

    /**
     * Cash / pay on arrival deposit info
     */
    public function cashDeposit(string $reference): void
    {
        $bookingModel = new Booking();
        $booking = $bookingModel->findByReference($reference);
        if (!$booking) { http_response_code(404); include VIEWS_PATH . '/errors/404.php'; return; }

        $depositAmount = round((float)$booking->total * (PAYMENT_DEPOSIT_PERCENT / 100), 2);

        $this->view('booking.cash-deposit', [
            'pageTitle' => $this->setTitle('Deposit Required'),
            'booking' => $booking,
            'depositAmount' => $depositAmount,
            'depositPercent' => PAYMENT_DEPOSIT_PERCENT,
        ]);
    }

    // ─── Internal Helpers ────────────────────────────────────

    /**
     * Mark a booking's payment as completed
     */
    private function completePayment(object $booking, string $transactionId = ''): void
    {
        $paymentModel = new Payment();
        $payments = $paymentModel->forBooking($booking->id);
        if (!empty($payments)) {
            $paymentModel->markCompleted($payments[0]->id, $transactionId ?: null);
        }

        $bookingModel = new Booking();
        $bookingModel->update($booking->id, [
            'status' => 'confirmed',
            'payment_status' => 'paid',
        ]);
    }

    /**
     * Mark a booking's payment as failed
     */
    private function failPayment(object $booking): void
    {
        $paymentModel = new Payment();
        $payments = $paymentModel->forBooking($booking->id);
        if (!empty($payments)) {
            $paymentModel->markFailed($payments[0]->id);
        }
    }

    /**
     * Map user-facing payment method name to DB gateway enum
     */
    private function mapMethodToGateway(string $method): string
    {
        return match ($method) {
            'stripe', 'card' => 'stripe',
            'paypal' => 'paypal',
            'mobile_money' => 'mobile_money',
            'cash', 'pay_on_arrival' => 'cash',
            default => 'bank_transfer',
        };
    }
}
