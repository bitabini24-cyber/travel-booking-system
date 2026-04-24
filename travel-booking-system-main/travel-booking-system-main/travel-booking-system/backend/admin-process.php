<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/middleware/admin.php';
require_once __DIR__ . '/helpers/functions.php';
require_once __DIR__ . '/models/Booking.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(APP_URL . '/index.php');

$action = $_POST['action'] ?? '';

if ($action === 'update_booking_status') {
    $bookingModel = new Booking($pdo);
    $bookingModel->updateStatus(intval($_POST['booking_id']), sanitize($_POST['status']));
    redirect(APP_URL . '/pages/admin/bookings.php');
}

redirect(APP_URL . '/pages/admin/dashboard.php');
