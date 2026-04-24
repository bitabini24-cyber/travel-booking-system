<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';

// Only enforce if not already called
if (!isLoggedIn()) {
    redirect(APP_URL . '/auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI'] ?? '/'));
}
