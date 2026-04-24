<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';

if (!isLoggedIn()) {
    redirect(APP_URL . '/auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI'] ?? '/'));
}
if (!isAdmin()) {
    redirect(APP_URL . '/index.php');
}
