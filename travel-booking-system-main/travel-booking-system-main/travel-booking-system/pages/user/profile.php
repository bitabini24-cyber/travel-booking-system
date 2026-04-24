<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../backend/middleware/auth.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';
require_once __DIR__ . '/../../backend/models/User.php';

$userModel = new User($pdo);
$user = $userModel->findById($_SESSION['user_id']);
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [];
    if (!empty($_POST['name'])) $data['name'] = sanitize($_POST['name']);
    if (!empty($_POST['phone'])) $data['phone'] = sanitize($_POST['phone']);
    if (!empty($_POST['new_password'])) {
        if ($_POST['new_password'] !== $_POST['confirm_password']) {
            $msg = 'error:Passwords do not match.';
        } else {
            $data['password'] = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
        }
    }
    if (!str_starts_with($msg, 'error') && !empty($data)) {
        $userModel->update($_SESSION['user_id'], $data);
        $_SESSION['user_name'] = $data['name'] ?? $_SESSION['user_name'];
        $user = $userModel->findById($_SESSION['user_id']);
        $msg = 'success:Profile updated successfully!';
    }
}

$pageTitle = 'My Profile - TravelLux';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="dashboard-layout">
    <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>
    <main class="dashboard-main">
        <h1 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 32px;">My Profile</h1>
        <?php if ($msg): ?>
            <?php [$type, $text] = explode(':', $msg, 2); ?>
            <div class="alert alert-<?= $type === 'success' ? 'success' : 'error' ?>"><?= htmlspecialchars($text) ?></div>
        <?php endif; ?>
        <div style="max-width: 600px;">
            <div class="hotel-card" style="padding: 32px;">
                <form method="POST">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled style="background: var(--bg); cursor: not-allowed;">
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="+1 234 567 8900">
                    </div>
                    <hr style="border-color: var(--border); margin: 24px 0;">
                    <h4 style="margin-bottom: 16px; font-weight: 700;">Change Password</h4>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" placeholder="Leave blank to keep current">
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" placeholder="Repeat new password">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </main>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
