<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../backend/helpers/functions.php';
require_once __DIR__ . '/../backend/helpers/validation.php';
require_once __DIR__ . '/../backend/models/User.php';

if (isLoggedIn()) redirect(APP_URL . '/index.php');

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    if (!validateEmail($email)) {
        $msg = 'error:Please enter a valid email address.';
    } else {
        $userModel = new User($pdo);
        $user = $userModel->findByEmail($email);
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $userModel->update($user['id'], ['reset_token' => $token]);
            // In production: send email with reset link
            // EmailService::sendReset($email, APP_URL . '/auth/reset-password.php?token=' . $token);
        }
        // Always show success to prevent email enumeration
        $msg = 'success:If that email exists, a reset link has been sent.';
    }
}

$pageTitle = 'Forgot Password - TravelLux';
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
            <h2>Reset Your Password</h2>
            <p>We'll send you a secure link to get back in</p>
        </div>
    </div>
    <div class="auth-form-wrap">
        <a href="<?= APP_URL ?>/index.php" class="auth-logo">✈ TravelLux</a>
        <h1>Forgot Password?</h1>
        <p class="auth-sub">Enter your email and we'll send a reset link</p>

        <?php if ($msg): ?>
            <?php [$type, $text] = explode(':', $msg, 2); ?>
            <div class="alert alert-<?= $type === 'success' ? 'success' : 'error' ?>"><?= htmlspecialchars($text) ?></div>
        <?php endif; ?>

        <form method="POST" class="auth-form">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="you@example.com" required autocomplete="email">
            </div>
            <button type="submit" class="btn btn-primary btn-full">Send Reset Link</button>
        </form>
        <p class="auth-switch"><a href="<?= APP_URL ?>/auth/login.php">← Back to Login</a></p>
    </div>
</div>
</body>
</html>
