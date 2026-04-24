<?php
/**
 * Payment Service - Ready for Stripe/PayPal integration
 * Replace the placeholder methods with actual SDK calls
 */
class PaymentService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Create a payment intent (Stripe-ready structure)
     * Replace with: \Stripe\PaymentIntent::create([...])
     */
    public function createIntent($amount, $currency = 'usd', $metadata = []) {
        // TODO: Initialize Stripe with secret key
        // \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
        // return \Stripe\PaymentIntent::create(['amount' => $amount * 100, 'currency' => $currency]);

        return [
            'client_secret' => 'demo_' . bin2hex(random_bytes(16)),
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'requires_payment_method'
        ];
    }

    /**
     * Record a transaction in the database
     */
    public function recordTransaction($bookingId, $amount, $method, $transactionId, $status = 'success') {
        $stmt = $this->pdo->prepare("INSERT INTO transactions (booking_id, amount, method, transaction_id, status) VALUES (?,?,?,?,?)");
        return $stmt->execute([$bookingId, $amount, $method, $transactionId, $status]);
    }

    /**
     * Process refund (Stripe-ready)
     */
    public function refund($transactionId) {
        // TODO: \Stripe\Refund::create(['payment_intent' => $transactionId]);
        return ['success' => true, 'refund_id' => 'refund_demo_' . time()];
    }
}
