<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../backend/middleware/auth.php';
require_once __DIR__ . '/../backend/helpers/functions.php';
require_once __DIR__ . '/../backend/models/Booking.php';

$bookingId = intval($_GET['booking_id'] ?? 0);
$bookingModel = new Booking($pdo);
$booking = $bookingModel->findById($bookingId);

if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
    redirect(APP_URL . '/index.php');
}

$pageTitle = 'Checkout - TravelLux';
require_once __DIR__ . '/../includes/header.php';
?>
<div style="min-height: 100vh; padding: 120px 24px 80px; background: var(--bg);">
    <div class="container" style="max-width: 800px;">
        <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 32px;">Complete Your Booking</h1>
        <div style="display: grid; grid-template-columns: 1fr 380px; gap: 32px; align-items: start;">
            <div>
                <div class="hotel-card" style="padding: 32px; margin-bottom: 24px;">
                    <h3 style="font-weight: 700; margin-bottom: 24px;">Payment Details</h3>
                    <div class="alert alert-info">
                        💳 Payment gateway integration ready. Connect Stripe or PayPal by adding your API keys to <code>config/app.php</code> and updating <code>backend/services/PaymentService.php</code>.
                    </div>
                    <form id="paymentForm">
                        <div class="form-group">
                            <label>Card Number</label>
                            <input type="text" placeholder="4242 4242 4242 4242" maxlength="19" id="cardNumber">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Expiry Date</label>
                                <input type="text" placeholder="MM/YY" maxlength="5">
                            </div>
                            <div class="form-group">
                                <label>CVV</label>
                                <input type="text" placeholder="123" maxlength="4">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Cardholder Name</label>
                            <input type="text" placeholder="John Doe">
                        </div>
                        <button type="submit" class="btn btn-primary btn-full btn-lg">
                            Pay <?= formatPrice($booking['total_price']) ?>
                        </button>
                    </form>
                </div>
            </div>
            <div class="booking-card">
                <h3 style="font-weight: 700; margin-bottom: 20px;">Order Summary</h3>
                <div style="display: flex; gap: 12px; margin-bottom: 20px;">
                    <img src="<?= htmlspecialchars($booking['image']) ?>" style="width: 70px; height: 60px; border-radius: 8px; object-fit: cover;" alt="">
                    <div>
                        <div style="font-weight: 700; font-size: 0.95rem;"><?= htmlspecialchars($booking['hotel_name']) ?></div>
                        <div style="color: var(--text-light); font-size: 0.8rem;"><?= htmlspecialchars($booking['location']) ?></div>
                    </div>
                </div>
                <div style="border-top: 1px solid var(--border); padding-top: 16px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.9rem;">
                        <span>Check In</span><span><?= formatDate($booking['check_in']) ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 0.9rem;">
                        <span>Check Out</span><span><?= formatDate($booking['check_out']) ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-weight: 800; font-size: 1.2rem; border-top: 1px solid var(--border); padding-top: 16px;">
                        <span>Total</span>
                        <span style="color: var(--primary);"><?= formatPrice($booking['total_price']) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('cardNumber')?.addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '').replace(/(.{4})/g, '$1 ').trim();
});
document.getElementById('paymentForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    showToast('Payment processing... (Demo mode)', 'success');
    setTimeout(() => window.location.href = '<?= APP_URL ?>/pages/booking-confirmation.php?id=<?= $bookingId ?>', 2000);
});
</script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
