<?php
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect(APP_URL . '/auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    }
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        redirect(APP_URL . '/index.php');
    }
}

function formatPrice($price) {
    return '$' . number_format($price, 2);
}

function formatDate($date) {
    return date('M d, Y', strtotime($date));
}

function calcNights($checkIn, $checkOut) {
    $d1 = new DateTime($checkIn);
    $d2 = new DateTime($checkOut);
    return $d1->diff($d2)->days;
}

function renderStars($rating) {
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        $class = $i <= round($rating) ? 'star filled' : 'star';
        $html .= "<span class='$class'>&#9733;</span>";
    }
    return $html;
}

function jsonResponse($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function logError($message) {
    $logFile = LOG_PATH . 'error_' . date('Y-m-d') . '.log';
    file_put_contents($logFile, date('[Y-m-d H:i:s] ') . $message . PHP_EOL, FILE_APPEND);
}
