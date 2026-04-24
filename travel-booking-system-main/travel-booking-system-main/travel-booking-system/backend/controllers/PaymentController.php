<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../services/PaymentService.php';
require_once __DIR__ . '/../helpers/functions.php';

class PaymentController {
    private $pdo;
    private $payment;
    private $booking;

    public function __construct($pdo) {
        $this->pdo     = $pdo;
        $this->payment = new PaymentService($pdo);
        $this->booking = new Booking($pdo);
    }

    /**
     * Initiate payment for a booking
     */
    public function initiate($bookingId) {
        if (!isLoggedIn()) return ['error' => 'Unauthorized.'];

        $booking = $this->booking->findById($bookingId);
        if (!$booking) return ['error' => 'Booking not found.'];
        if ($booking['user_id'] != $_SESSION['user_id']) return ['error' => 'Unauthorized.'];
        if ($booking['payment_status'] === 'paid') return ['error' => 'Already paid.'];

        $intent = $this->payment->createIntent($booking['total_price'], 'usd', [
            'booking_id' => $bookingId,
            'user_id'    => $_SESSION['user_id'],
        ]);

        return ['success' => true, 'client_secret' => $intent['client_secret'], 'amount' => $booking['total_price']];
    }

    /**
     * Confirm payment after client-side processing
     */
    public function confirm($bookingId, $transactionId, $method = 'card') {
        if (!isLoggedIn()) return ['error' => 'Unauthorized.'];

        $booking = $this->booking->findById($bookingId);
        if (!$booking) return ['error' => 'Booking not found.'];

        // Record transaction
        $this->payment->recordTransaction($bookingId, $booking['total_price'], $method, $transactionId, 'success');

        // Update booking status
        $stmt = $this->pdo->prepare("UPDATE bookings SET status = 'confirmed', payment_status = 'paid' WHERE id = ?");
        $stmt->execute([$bookingId]);

        return ['success' => true];
    }

    /**
     * Process refund
     */
    public function refund($bookingId) {
        if (!isLoggedIn()) return ['error' => 'Unauthorized.'];

        $stmt = $this->pdo->prepare("SELECT * FROM transactions WHERE booking_id = ? AND status = 'success' LIMIT 1");
        $stmt->execute([$bookingId]);
        $tx = $stmt->fetch();

        if (!$tx) return ['error' => 'No payment found for this booking.'];

        $result = $this->payment->refund($tx['transaction_id']);
        if ($result['success']) {
            $this->pdo->prepare("UPDATE bookings SET payment_status = 'refunded', status = 'cancelled' WHERE id = ?")->execute([$bookingId]);
            $this->pdo->prepare("UPDATE transactions SET status = 'refunded' WHERE id = ?")->execute([$tx['id']]);
        }

        return $result;
    }
}
