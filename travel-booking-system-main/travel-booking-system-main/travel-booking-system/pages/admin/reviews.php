<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../backend/middleware/admin.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';
require_once __DIR__ . '/../../backend/controllers/AdminController.php';

$admin = new AdminController($pdo);

if (isset($_GET['delete'])) {
    $admin->review->delete(intval($_GET['delete']));
    redirect(APP_URL . '/pages/admin/reviews.php');
}

$reviews = $admin->review->getAll(100);
$pageTitle = 'Manage Reviews - Admin';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="dashboard-layout">
    <?php require_once __DIR__ . '/../../includes/admin-sidebar.php'; ?>
    <main class="dashboard-main">
        <h1 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 32px;">Manage Reviews</h1>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr><th>User</th><th>Hotel</th><th>Rating</th><th>Comment</th><th>Date</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($reviews as $r): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= htmlspecialchars($r['user_name']) ?></td>
                        <td><?= htmlspecialchars($r['hotel_name']) ?></td>
                        <td><?= renderStars($r['rating']) ?></td>
                        <td style="max-width: 300px; font-size: 0.85rem; color: var(--text-light);"><?= htmlspecialchars(substr($r['comment'], 0, 100)) ?>...</td>
                        <td><?= formatDate($r['created_at']) ?></td>
                        <td>
                            <a href="?delete=<?= $r['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete review?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
