<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';
require_once __DIR__ . '/../../backend/models/Review.php';
require_once __DIR__ . '/../../backend/controllers/ReviewController.php';

$reviewModel = new Review($pdo);
$method      = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $hotelId = intval($_GET['hotel_id'] ?? 0);
    if (!$hotelId) jsonResponse(['error' => 'hotel_id required'], 400);
    $reviews = $reviewModel->getByHotel($hotelId);
    $avg     = $reviewModel->getAverage($hotelId);
    jsonResponse(['data' => $reviews, 'average' => round($avg['avg'] ?? 0, 1), 'total' => $avg['total'] ?? 0]);
}

if ($method === 'POST') {
    if (!isLoggedIn()) jsonResponse(['error' => 'Unauthorized'], 401);
    $data    = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $ctrl    = new ReviewController($pdo);
    $result  = $ctrl->create(intval($data['hotel_id'] ?? 0), intval($data['rating'] ?? 0), $data['comment'] ?? '');
    jsonResponse($result, isset($result['success']) ? 201 : 400);
}

if ($method === 'DELETE') {
    if (!isLoggedIn()) jsonResponse(['error' => 'Unauthorized'], 401);
    $id   = intval($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    jsonResponse(['success' => $stmt->rowCount() > 0]);
}

jsonResponse(['error' => 'Method not allowed'], 405);
