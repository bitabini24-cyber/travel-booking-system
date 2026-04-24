<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../backend/middleware/admin.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';
require_once __DIR__ . '/../../backend/controllers/AdminController.php';

$admin = new AdminController($pdo);
$bookings = $admin->booking->getAll(100, 0);
$pageTitle = 'Manage Bookings - Admin';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="dashboard-layout">
    <?php require_once __DIR__ . '/../../includes/admin-sidebar.php'; ?>
    <main class="dashboard-main">
        <h1 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 32px;">All Bookings</h1>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr><th>#</th><th>Guest</th><th>Hotel</th><th>Dates</th><th>Total</th><th>Status</th><th>Update</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td><?= str_pad($b['id'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td>
                            <div style="font-weight: 600;"><?= htmlspecialchars($b['user_name']) ?></div>
                            <div style="font-size: 0.75rem; color: var(--text-light);"><?= htmlspecialchars($b['email']) ?></div>
                        </td>
                        <td><?= htmlspecialchars($b['hotel_name']) ?></td>
                        <td style="font-size: 0.85rem;"><?= formatDate($b['check_in']) ?><br><?= formatDate($b['check_out']) ?></td>
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
                                    <?php foreach (['pending', 'confirmed', 'cancelled', 'completed'] as $s): ?>
                                        <option value="<?= $s ?>" <?= $b['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                                    <?php endforeach; ?>
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
