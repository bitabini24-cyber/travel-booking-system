<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/middleware/auth.php';
require_once __DIR__ . '/helpers/functions.php';
require_once __DIR__ . '/controllers/BookingController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(APP_URL . '/index.php');

$ctrl = new BookingController($pdo);
$ctrl->cancel(intval($_POST['booking_id'] ?? 0));
redirect(APP_URL . '/pages/user/bookings.php');
