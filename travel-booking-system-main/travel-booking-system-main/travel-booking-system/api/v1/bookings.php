<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';
require_once __DIR__ . '/../../backend/models/Booking.php';
require_once __DIR__ . '/../../backend/controllers/BookingController.php';

if (!isLoggedIn()) jsonResponse(['error' => 'Unauthorized'], 401);

$method = $_SERVER['REQUEST_METHOD'];
$bookingModel = new Booking($pdo);

if ($method === 'GET') {
    $bookings = $bookingModel->getByUser($_SESSION['user_id']);
    jsonResponse(['data' => $bookings]);
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $ctrl = new BookingController($pdo);
    $result = $ctrl->create($data);
    jsonResponse($result, isset($result['success']) ? 201 : 400);
}

jsonResponse(['error' => 'Method not allowed'], 405);
