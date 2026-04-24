<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../backend/middleware/auth.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';
require_once __DIR__ . '/../../backend/models/Review.php';

$reviewModel = new Review($pdo);

// Handle delete own review
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ? AND user_id = ?");
    $stmt->execute([intval($_GET['delete']), $_SESSION['user_id']]);
    redirect(APP_URL . '/pages/user/reviews.php');
}

$stmt = $pdo->prepare("SELECT r.*, h.name as hotel_name, h.image, h.city FROM reviews r JOIN hotels h ON r.hotel_id = h.id WHERE r.user_id = ? ORDER BY r.created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$reviews = $stmt->fetchAll();

$pageTitle = 'My Reviews - TravelLux';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="dashboard-layout">
    <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>
    <main class="dashboard-main">
        <h1 style="font-size:1.8rem; font-weight:800; margin-bottom:32px;">My Reviews</h1>

        <?php if (empty($reviews)): ?>
            <div class="empty-state">
                <div class="empty-icon">⭐</div>
                <h3>No reviews yet</h3>
                <p>Book a stay and share your experience!</p>
                <a href="<?= APP_URL ?>/pages/search.php" class="btn btn-primary" style="margin-top:16px;">Find Hotels</a>
            </div>
        <?php else: ?>
            <div style="display:flex; flex-direction:column; gap:20px;">
                <?php foreach ($reviews as $r): ?>
                <div class="hotel-card" style="padding:24px; display:flex; gap:20px; align-items:flex-start; flex-wrap:wrap;">
                    <img src="<?= htmlspecialchars($r['image']) ?>"
                         style="width:90px; height:70px; border-radius:var(--radius); object-fit:cover; flex-shrink:0;" alt="">
                    <div style="flex:1; min-width:200px;">
                        <h3 style="font-weight:700; margin-bottom:4px;"><?= htmlspecialchars($r['hotel_name']) ?></h3>
                        <p style="color:var(--text-light); font-size:0.8rem; margin-bottom:10px;">📍 <?= htmlspecialchars($r['city']) ?></p>
                        <div class="stars" style="margin-bottom:10px;"><?= renderStars($r['rating']) ?></div>
                        <p style="color:var(--text-light); font-size:0.9rem; line-height:1.6;"><?= nl2br(htmlspecialchars($r['comment'])) ?></p>
                        <p style="font-size:0.75rem; color:var(--text-muted); margin-top:8px;"><?= formatDate($r['created_at']) ?></p>
                    </div>
                    <a href="?delete=<?= $r['id'] ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Delete this review?')">Delete</a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
