<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../backend/helpers/functions.php';
require_once __DIR__ . '/../backend/helpers/validation.php';
require_once __DIR__ . '/../backend/models/User.php';

if (isLoggedIn()) redirect(APP_URL . '/index.php');

$token = sanitize($_GET['token'] ?? '');
$msg = '';

// Validate token
$stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$token || !$user) {
    $msg = 'error:Invalid or expired reset link.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    if (!validatePassword($password)) {
        $msg = 'error:Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $msg = 'error:Passwords do not match.';
    } else {
        $userModel = new User($pdo);
        $userModel->update($user['id'], [
            'password'    => password_hash($password, PASSWORD_BCRYPT),
            'reset_token' => null
        ]);
        $msg = 'success:Password reset successfully! You can now login.';
        $user = null; // Hide form
    }
}

$pageTitle = 'Reset Password - TravelLux';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?></title>
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/animations.css">
</head>
<body class="auth-page">
<div class="auth-container">
    <div class="auth-visual">
        <img src="https://images.unsplash.com/photo-1488085061387-422e29b40080?w=800" alt="Travel" loading="lazy">
        <div class="auth-visual-overlay">
            <h2>New Password</h2>
            <p>Choose a strong password to secure your account</p>
        </div>
    </div>
    <div class="auth-form-wrap">
        <a href="<?= APP_URL ?>/index.php" class="auth-logo">✈ TravelLux</a>
        <h1>Reset Password</h1>
        <p class="auth-sub">Enter your new password below</p>

        <?php if ($msg): ?>
            <?php [$type, $text] = explode(':', $msg, 2); ?>
            <div class="alert alert-<?= $type === 'success' ? 'success' : 'error' ?>"><?= htmlspecialchars($text) ?></div>
        <?php endif; ?>

        <?php if ($user): ?>
        <form method="POST" class="auth-form">
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="password" placeholder="Min. 8 characters" required>
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" placeholder="Repeat password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-full">Reset Password</button>
        </form>
        <?php endif; ?>
        <p class="auth-switch"><a href="<?= APP_URL ?>/auth/login.php">← Back to Login</a></p>
    </div>
</div>
</body>
</html>
