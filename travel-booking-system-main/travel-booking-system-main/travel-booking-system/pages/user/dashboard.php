<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../backend/middleware/auth.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';
require_once __DIR__ . '/../../backend/models/Booking.php';
require_once __DIR__ . '/../../backend/models/User.php';

$bookingModel = new Booking($pdo);
$userModel = new User($pdo);
$user = $userModel->findById($_SESSION['user_id']);
$bookings = $bookingModel->getByUser($_SESSION['user_id']);
$upcoming = array_filter($bookings, fn($b) => $b['check_in'] >= date('Y-m-d') && $b['status'] !== 'cancelled');
$past = array_filter($bookings, fn($b) => $b['check_out'] < date('Y-m-d') || $b['status'] === 'completed');

$pageTitle = 'My Dashboard - TravelLux';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="dashboard-layout">
    <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>
    <main class="dashboard-main">
        <h1 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 8px;">Welcome back, <?= htmlspecialchars($user['name']) ?> 👋</h1>
        <p style="color: var(--text-light); margin-bottom: 32px;">Here's your travel overview</p>

        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-card-icon">✈️</div>
                <div class="stat-card-value"><?= count($bookings) ?></div>
                <div class="stat-card-label">Total Bookings</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon">📅</div>
                <div class="stat-card-value"><?= count($upcoming) ?></div>
                <div class="stat-card-label">Upcoming Trips</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon">🏨</div>
                <div class="stat-card-value"><?= count($past) ?></div>
                <div class="stat-card-label">Completed Trips</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon">💰</div>
                <div class="stat-card-value"><?= formatPrice(array_sum(array_column($bookings, 'total_price'))) ?></div>
                <div class="stat-card-label">Total Spent</div>
            </div>
        </div>

        <h2 style="font-weight: 700; margin-bottom: 20px;">Recent Bookings</h2>
        <?php if (empty($bookings)): ?>
            <div class="empty-state">
                <div class="empty-icon">🌍</div>
                <h3>No bookings yet</h3>
                <p>Start exploring and book your first adventure!</p>
                <a href="<?= APP_URL ?>/pages/search.php" class="btn btn-primary" style="margin-top: 16px;">Explore Hotels</a>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Hotel</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($bookings, 0, 10) as $b): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <img src="<?= htmlspecialchars($b['image']) ?>" style="width: 48px; height: 48px; border-radius: 8px; object-fit: cover;" alt="">
                                    <div>
                                        <div style="font-weight: 600;"><?= htmlspecialchars($b['hotel_name']) ?></div>
                                        <div style="font-size: 0.8rem; color: var(--text-light);"><?= htmlspecialchars($b['city']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?= formatDate($b['check_in']) ?></td>
                            <td><?= formatDate($b['check_out']) ?></td>
                            <td style="font-weight: 700; color: var(--primary);"><?= formatPrice($b['total_price']) ?></td>
                            <td>
                                <?php
                                $badgeMap = ['pending' => 'warning', 'confirmed' => 'success', 'cancelled' => 'danger', 'completed' => 'info'];
                                $badge = $badgeMap[$b['status']] ?? 'info';
                                ?>
                                <span class="badge badge-<?= $badge ?>"><?= ucfirst($b['status']) ?></span>
                            </td>
                            <td>
                                <?php if ($b['status'] === 'pending'): ?>
                                <form method="POST" action="<?= APP_URL ?>/backend/cancel-booking.php" style="display: inline;">
                                    <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Cancel this booking?')">Cancel</button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
