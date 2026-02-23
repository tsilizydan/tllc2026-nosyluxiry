<?php
/**
 * PaymentGateway — Server-side payment processing via cURL
 * Supports Stripe Checkout Sessions and PayPal Orders API.
 * No external dependencies — pure PHP + cURL.
 */
class PaymentGateway
{
    // ─── Stripe Checkout ─────────────────────────────────────

    /**
     * Create a Stripe Checkout Session and return the URL
     */
    public static function createStripeSession(object $booking, float $amount, string $currency = 'EUR'): ?string
    {
        $secretKey = STRIPE_SECRET_KEY;
        if (empty($secretKey)) return null;

        $successUrl = BASE_URL . '/payment/stripe/success/' . urlencode($booking->reference) . '?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl  = BASE_URL . '/payment/stripe/cancel/' . urlencode($booking->reference);

        $data = http_build_query([
            'payment_method_types[]' => 'card',
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'client_reference_id' => $booking->reference,
            'customer_email' => $booking->guest_email,
            'line_items[0][price_data][currency]' => strtolower($currency),
            'line_items[0][price_data][product_data][name]' => 'Booking ' . $booking->reference . ' — ' . ($booking->tour_name ?? 'Tour'),
            'line_items[0][price_data][unit_amount]' => (int) round($amount * 100), // cents
            'line_items[0][quantity]' => 1,
            'metadata[booking_ref]' => $booking->reference,
        ]);

        $response = self::curlPost('https://api.stripe.com/v1/checkout/sessions', $data, [
            'Authorization: Basic ' . base64_encode($secretKey . ':'),
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        if (!$response || !isset($response['url'])) {
            error_log('Stripe session creation failed: ' . json_encode($response));
            return null;
        }

        return $response['url'];
    }

    /**
     * Retrieve Stripe Checkout Session details
     */
    public static function retrieveStripeSession(string $sessionId): ?array
    {
        $secretKey = STRIPE_SECRET_KEY;
        if (empty($secretKey)) return null;

        return self::curlGet('https://api.stripe.com/v1/checkout/sessions/' . urlencode($sessionId), [
            'Authorization: Basic ' . base64_encode($secretKey . ':'),
        ]);
    }

    /**
     * Verify Stripe webhook signature
     */
    public static function verifyStripeWebhook(string $payload, string $signature): ?array
    {
        $secret = STRIPE_WEBHOOK_SECRET;
        if (empty($secret)) return null;

        $elements = [];
        foreach (explode(',', $signature) as $part) {
            [$key, $value] = explode('=', $part, 2);
            $elements[trim($key)] = trim($value);
        }

        if (empty($elements['t']) || empty($elements['v1'])) return null;

        $signedPayload = $elements['t'] . '.' . $payload;
        $expected = hash_hmac('sha256', $signedPayload, $secret);

        if (!hash_equals($expected, $elements['v1'])) return null;

        // Reject if timestamp is > 5 minutes old
        if (abs(time() - (int)$elements['t']) > 300) return null;

        return json_decode($payload, true);
    }

    // ─── PayPal Orders API ───────────────────────────────────

    /**
     * Create a PayPal Order and return the approval URL
     */
    public static function createPayPalOrder(object $booking, float $amount, string $currency = 'EUR'): ?string
    {
        $token = self::getPayPalAccessToken();
        if (!$token) return null;

        $baseUrl = PAYPAL_MODE === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        $body = json_encode([
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => $booking->reference,
                'description' => 'Booking ' . $booking->reference,
                'amount' => [
                    'currency_code' => strtoupper($currency),
                    'value' => number_format($amount, 2, '.', ''),
                ],
            ]],
            'application_context' => [
                'brand_name' => APP_NAME,
                'return_url' => BASE_URL . '/payment/paypal/return/' . urlencode($booking->reference),
                'cancel_url' => BASE_URL . '/payment/paypal/cancel/' . urlencode($booking->reference),
                'user_action' => 'PAY_NOW',
            ],
        ]);

        $response = self::curlPost($baseUrl . '/v2/checkout/orders', $body, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ], true);

        if (!$response || empty($response['id'])) {
            error_log('PayPal order creation failed: ' . json_encode($response));
            return null;
        }

        // Find approval URL
        foreach ($response['links'] as $link) {
            if ($link['rel'] === 'approve') return $link['href'];
        }

        return null;
    }

    /**
     * Capture an approved PayPal Order
     */
    public static function capturePayPalOrder(string $orderId): ?array
    {
        $token = self::getPayPalAccessToken();
        if (!$token) return null;

        $baseUrl = PAYPAL_MODE === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        $response = self::curlPost($baseUrl . '/v2/checkout/orders/' . urlencode($orderId) . '/capture', '{}', [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ], true);

        return $response;
    }

    /**
     * Get PayPal access token
     */
    private static function getPayPalAccessToken(): ?string
    {
        $clientId = PAYPAL_CLIENT_ID;
        $secret   = PAYPAL_SECRET;
        if (empty($clientId) || empty($secret)) return null;

        $baseUrl = PAYPAL_MODE === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        $response = self::curlPost($baseUrl . '/v1/oauth2/token', 'grant_type=client_credentials', [
            'Authorization: Basic ' . base64_encode($clientId . ':' . $secret),
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        return $response['access_token'] ?? null;
    }

    // ─── cURL Helpers ────────────────────────────────────────

    private static function curlPost(string $url, string $data, array $headers, bool $isJson = false): ?array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            error_log("PaymentGateway cURL error: {$error}");
            return null;
        }

        $decoded = json_decode($response, true);
        if ($httpCode >= 400) {
            error_log("PaymentGateway HTTP {$httpCode}: " . $response);
        }

        return $decoded;
    }

    private static function curlGet(string $url, array $headers): ?array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            error_log("PaymentGateway cURL GET error: {$error}");
            return null;
        }

        return json_decode($response, true);
    }
}
