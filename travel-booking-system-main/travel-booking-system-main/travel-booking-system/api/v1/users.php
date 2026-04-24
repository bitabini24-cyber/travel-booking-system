<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../backend/helpers/functions.php';
require_once __DIR__ . '/../../backend/models/User.php';

// Require authentication
if (!isLoggedIn()) jsonResponse(['error' => 'Unauthorized'], 401);

$userModel = new User($pdo);
$method    = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Admin: list all users
    if (isAdmin()) {
        $users = $userModel->getAll(100, 0);
        jsonResponse(['data' => $users, 'total' => $userModel->count()]);
    }
    // User: own profile
    $user = $userModel->findById($_SESSION['user_id']);
    jsonResponse(['data' => $user]);
}

if ($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $allowed = ['name', 'phone'];
    $payload = [];
    foreach ($allowed as $f) {
        if (isset($data[$f])) $payload[$f] = sanitize($data[$f]);
    }
    if (empty($payload)) jsonResponse(['error' => 'Nothing to update.'], 400);
    $userModel->update($_SESSION['user_id'], $payload);
    jsonResponse(['success' => true]);
}

jsonResponse(['error' => 'Method not allowed'], 405);
