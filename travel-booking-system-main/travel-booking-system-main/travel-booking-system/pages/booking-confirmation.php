<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../backend/middleware/auth.php';
require_once __DIR__ . '/../backend/helpers/functions.php';
require_once __DIR__ . '/../backend/models/Booking.php';

$id = intval($_GET['id'] ?? 0);
$bookingModel = new Booking($pdo);
$booking = $bookingModel->findById($id);

if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
    redirect(APP_URL . '/index.php');
}

$nights = calcNights($booking['check_in'], $booking['check_out']);
$pageTitle = 'Booking Confirmed - TravelLux';
require_once __DIR__ . '/../includes/header.php';
?>
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 120px 24px 80px; background: var(--bg);">
    <div style="max-width: 600px; width: 100%; text-align: center;">
        <div style="font-size: 5rem; margin-bottom: 24px; animation: scaleIn 0.6s ease;">🎉</div>
        <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--dark); margin-bottom: 12px;">Booking Confirmed!</h1>
        <p style="color: var(--text-light); font-size: 1.1rem; margin-bottom: 40px;">Your reservation has been successfully placed.</p>

        <div class="hotel-card" style="padding: 32px; text-align: left; margin-bottom: 32px;">
            <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 24px;">
                <img src="<?= htmlspecialchars($booking['image']) ?>" alt="" style="width: 80px; height: 80px; border-radius: var(--radius); object-fit: cover;">
                <div>
                    <h3 style="font-weight: 700;"><?= htmlspecialchars($booking['hotel_name']) ?></h3>
                    <p style="color: var(--text-light); font-size: 0.9rem;">📍 <?= htmlspecialchars($booking['location']) ?></p>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div style="background: var(--bg); padding: 16px; border-radius: var(--radius);">
                    <div style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase; font-weight: 700; margin-bottom: 4px;">Check In</div>
                    <div style="font-weight: 700;"><?= formatDate($booking['check_in']) ?></div>
                </div>
                <div style="background: var(--bg); padding: 16px; border-radius: var(--radius);">
                    <div style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase; font-weight: 700; margin-bottom: 4px;">Check Out</div>
                    <div style="font-weight: 700;"><?= formatDate($booking['check_out']) ?></div>
                </div>
                <div style="background: var(--bg); padding: 16px; border-radius: var(--radius);">
                    <div style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase; font-weight: 700; margin-bottom: 4px;">Duration</div>
                    <div style="font-weight: 700;"><?= $nights ?> Night<?= $nights > 1 ? 's' : '' ?></div>
                </div>
                <div style="background: var(--bg); padding: 16px; border-radius: var(--radius);">
                    <div style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase; font-weight: 700; margin-bottom: 4px;">Total</div>
                    <div style="font-weight: 700; color: var(--primary); font-size: 1.2rem;"><?= formatPrice($booking['total_price']) ?></div>
                </div>
            </div>
            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <span style="color: var(--text-light); font-size: 0.85rem;">Booking #<?= str_pad($booking['id'], 6, '0', STR_PAD_LEFT) ?></span>
                <span class="badge badge-warning">Pending Confirmation</span>
            </div>
        </div>

        <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
            <a href="<?= APP_URL ?>/pages/user/bookings.php" class="btn btn-primary">View My Bookings</a>
            <a href="<?= APP_URL ?>/pages/search.php" class="btn btn-outline" style="color: var(--primary); border-color: var(--primary);">Explore More</a>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
