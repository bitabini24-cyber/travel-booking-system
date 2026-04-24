<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/helpers/functions.php';
require_once __DIR__ . '/controllers/BookingController.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(APP_URL . '/index.php');
}

$ctrl   = new BookingController($pdo);
$result = $ctrl->create($_POST);

if (isset($result['success'])) {
    redirect(APP_URL . '/pages/booking-confirmation.php?id=' . $result['booking_id']);
} else {
    $error   = $result['error'] ?? implode(', ', $result['errors'] ?? ['Unknown error']);
    $hotelId = intval($_POST['hotel_id'] ?? 0);
    redirect(APP_URL . '/pages/hotel-details.php?id=' . $hotelId . '&error=' . urlencode($error));
}
