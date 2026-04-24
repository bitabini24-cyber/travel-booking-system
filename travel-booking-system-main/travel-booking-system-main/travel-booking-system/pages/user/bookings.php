<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../backend/middleware/auth.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';
require_once __DIR__ . '/../../backend/models/Booking.php';

$bookingModel = new Booking($pdo);
$bookings = $bookingModel->getByUser($_SESSION['user_id']);

$pageTitle = 'My Bookings - TravelLux';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="dashboard-layout">
    <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>
    <main class="dashboard-main">
        <h1 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 32px;">My Bookings</h1>
        <?php if (empty($bookings)): ?>
            <div class="empty-state">
                <div class="empty-icon">✈️</div>
                <h3>No bookings yet</h3>
                <a href="<?= APP_URL ?>/pages/search.php" class="btn btn-primary" style="margin-top: 16px;">Find Hotels</a>
            </div>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <?php foreach ($bookings as $b): ?>
                <div class="hotel-card" style="padding: 24px; display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
                    <img src="<?= htmlspecialchars($b['image']) ?>" style="width: 100px; height: 80px; border-radius: var(--radius); object-fit: cover; flex-shrink: 0;" alt="">
                    <div style="flex: 1; min-width: 200px;">
                        <h3 style="font-weight: 700; margin-bottom: 4px;"><?= htmlspecialchars($b['hotel_name']) ?></h3>
                        <p style="color: var(--text-light); font-size: 0.85rem; margin-bottom: 8px;">📍 <?= htmlspecialchars($b['city']) ?></p>
                        <div style="display: flex; gap: 16px; font-size: 0.85rem; color: var(--text-light);">
                            <span>📅 <?= formatDate($b['check_in']) ?> → <?= formatDate($b['check_out']) ?></span>
                            <span>👥 <?= $b['guests'] ?> guests</span>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 1.3rem; font-weight: 800; color: var(--primary); margin-bottom: 8px;"><?= formatPrice($b['total_price']) ?></div>
                        <?php
                        $badgeMap = ['pending' => 'warning', 'confirmed' => 'success', 'cancelled' => 'danger', 'completed' => 'info'];
                        ?>
                        <span class="badge badge-<?= $badgeMap[$b['status']] ?? 'info' ?>"><?= ucfirst($b['status']) ?></span>
                        <?php if ($b['status'] === 'pending'): ?>
                        <form method="POST" action="<?= APP_URL ?>/backend/cancel-booking.php" style="margin-top: 8px;">
                            <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Cancel this booking?')">Cancel</button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
