<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../backend/helpers/functions.php';

$token = sanitize($_GET['token'] ?? '');
$msg = 'error:Invalid verification link.';

if ($token) {
    $stmt = $pdo->prepare("UPDATE users SET is_verified = 1, reset_token = NULL WHERE reset_token = ?");
    $stmt->execute([$token]);
    if ($stmt->rowCount() > 0) {
        $msg = 'success:Email verified successfully! You can now login.';
    }
}

[$type, $text] = explode(':', $msg, 2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verify Email - TravelLux</title>
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
</head>
<body style="display:flex; align-items:center; justify-content:center; min-height:100vh; background:var(--bg);">
<div style="text-align:center; max-width:400px; padding:40px;">
    <div style="font-size:4rem; margin-bottom:20px;"><?= $type === 'success' ? '✅' : '❌' ?></div>
    <h1 style="font-size:1.8rem; font-weight:800; margin-bottom:12px;">
        <?= $type === 'success' ? 'Email Verified!' : 'Verification Failed' ?>
    </h1>
    <p style="color:var(--text-light); margin-bottom:32px;"><?= htmlspecialchars($text) ?></p>
    <a href="<?= APP_URL ?>/auth/login.php" class="btn btn-primary">Go to Login</a>
</div>
</body>
</html>
