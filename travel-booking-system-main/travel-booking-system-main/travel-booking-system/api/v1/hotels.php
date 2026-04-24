<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../backend/models/Hotel.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';

$hotelModel = new Hotel($pdo);
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $id = intval($_GET['id'] ?? 0);
    if ($id) {
        $hotel = $hotelModel->findById($id);
        if (!$hotel) { jsonResponse(['error' => 'Hotel not found'], 404); }
        jsonResponse(['data' => $hotel]);
    }

    $filters = [
        'city'      => $_GET['city'] ?? '',
        'min_price' => intval($_GET['min_price'] ?? 0),
        'max_price' => intval($_GET['max_price'] ?? 0),
        'rating'    => floatval($_GET['rating'] ?? 0),
        'sort'      => $_GET['sort'] ?? '',
    ];
    $page = max(1, intval($_GET['page'] ?? 1));
    $limit = min(50, intval($_GET['limit'] ?? 12));
    $offset = ($page - 1) * $limit;

    $hotels = $hotelModel->getAll($filters, $limit, $offset);
    $total = $hotelModel->count($filters);

    jsonResponse([
        'data' => $hotels,
        'meta' => ['total' => $total, 'page' => $page, 'limit' => $limit, 'pages' => ceil($total / $limit)]
    ]);
}

jsonResponse(['error' => 'Method not allowed'], 405);
