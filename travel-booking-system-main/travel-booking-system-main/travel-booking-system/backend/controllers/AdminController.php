<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Hotel.php';
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/Review.php';
require_once __DIR__ . '/../helpers/functions.php';

class AdminController {
    private $pdo;
    public $user, $hotel, $booking, $review;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->user = new User($pdo);
        $this->hotel = new Hotel($pdo);
        $this->booking = new Booking($pdo);
        $this->review = new Review($pdo);
    }

    public function getStats() {
        return [
            'users' => $this->user->count(),
            'hotels' => $this->hotel->count(),
            'bookings' => $this->booking->count(),
            'revenue' => $this->booking->totalRevenue()
        ];
    }

    public function saveHotel($data, $id = null) {
        $required = ['name', 'location', 'city', 'country', 'price', 'stars'];
        $errors = [];
        foreach ($required as $f) {
            if (empty($data[$f])) $errors[] = ucfirst($f) . ' is required.';
        }
        if (!empty($errors)) return ['errors' => $errors];

        $payload = [
            'name' => sanitize($data['name']),
            'location' => sanitize($data['location']),
            'city' => sanitize($data['city']),
            'country' => sanitize($data['country']),
            'lat' => $data['lat'] ?? null,
            'lng' => $data['lng'] ?? null,
            'price' => floatval($data['price']),
            'stars' => intval($data['stars']),
            'image' => sanitize($data['image'] ?? ''),
            'description' => sanitize($data['description'] ?? ''),
            'amenities' => sanitize($data['amenities'] ?? ''),
            'total_rooms' => intval($data['total_rooms'] ?? 10),
            'available_rooms' => intval($data['available_rooms'] ?? 10),
            'is_featured' => isset($data['is_featured']) ? 1 : 0
        ];

        if ($id) {
            $this->hotel->update($id, $payload);
            return ['success' => true, 'id' => $id];
        } else {
            $newId = $this->hotel->create($payload);
            return ['success' => true, 'id' => $newId];
        }
    }
}
