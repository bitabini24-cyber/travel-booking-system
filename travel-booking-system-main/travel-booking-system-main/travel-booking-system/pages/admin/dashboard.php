<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../backend/middleware/admin.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';
require_once __DIR__ . '/../../backend/controllers/AdminController.php';

$admin = new AdminController($pdo);
$stats = $admin->getStats();
$recentBookings = $admin->booking->getAll(10, 0);
$pageTitle = 'Admin Dashboard - TravelLux';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="dashboard-layout">
    <?php require_once __DIR__ . '/../../includes/admin-sidebar.php'; ?>
    <main class="dashboard-main">
        <h1 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 8px;">Admin Dashboard</h1>
        <p style="color: var(--text-light); margin-bottom: 32px;">System overview and management</p>

        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-card-icon">👥</div>
                <div class="stat-card-value"><?= number_format($stats['users']) ?></div>
                <div class="stat-card-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon">🏨</div>
                <div class="stat-card-value"><?= number_format($stats['hotels']) ?></div>
                <div class="stat-card-label">Hotels Listed</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon">📅</div>
                <div class="stat-card-value"><?= number_format($stats['bookings']) ?></div>
                <div class="stat-card-label">Total Bookings</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon">💰</div>
                <div class="stat-card-value"><?= formatPrice($stats['revenue'] ?? 0) ?></div>
                <div class="stat-card-label">Total Revenue</div>
            </div>
        </div>

        <h2 style="font-weight: 700; margin-bottom: 20px;">Recent Bookings</h2>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Guest</th>
                        <th>Hotel</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentBookings as $b): ?>
                    <tr>
                        <td><?= str_pad($b['id'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td>
                            <div style="font-weight: 600;"><?= htmlspecialchars($b['user_name']) ?></div>
                            <div style="font-size: 0.8rem; color: var(--text-light);"><?= htmlspecialchars($b['email']) ?></div>
                        </td>
                        <td><?= htmlspecialchars($b['hotel_name']) ?></td>
                        <td><?= formatDate($b['check_in']) ?></td>
                        <td><?= formatDate($b['check_out']) ?></td>
                        <td style="font-weight: 700; color: var(--primary);"><?= formatPrice($b['total_price']) ?></td>
                        <td>
                            <?php $bm = ['pending' => 'warning', 'confirmed' => 'success', 'cancelled' => 'danger', 'completed' => 'info']; ?>
                            <span class="badge badge-<?= $bm[$b['status']] ?? 'info' ?>"><?= ucfirst($b['status']) ?></span>
                        </td>
                        <td>
                            <form method="POST" action="<?= APP_URL ?>/backend/admin-process.php" style="display: flex; gap: 4px;">
                                <input type="hidden" name="action" value="update_booking_status">
                                <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                                <select name="status" style="padding: 4px 8px; border: 1px solid var(--border); border-radius: 6px; font-size: 0.8rem;">
                                    <option value="pending" <?= $b['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="confirmed" <?= $b['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                    <option value="cancelled" <?= $b['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    <option value="completed" <?= $b['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
