<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/Review.php';
require_once __DIR__ . '/../models/Hotel.php';
require_once __DIR__ . '/../helpers/functions.php';

class ReviewController {
    private $review;
    private $hotel;

    public function __construct($pdo) {
        $this->review = new Review($pdo);
        $this->hotel = new Hotel($pdo);
    }

    public function create($hotelId, $rating, $comment) {
        if (!isLoggedIn()) return ['error' => 'Please login to review.'];
        if ($rating < 1 || $rating > 5) return ['error' => 'Rating must be between 1 and 5.'];
        if ($this->review->hasReviewed($_SESSION['user_id'], $hotelId)) {
            return ['error' => 'You have already reviewed this hotel.'];
        }
        $this->review->create($_SESSION['user_id'], $hotelId, $rating, sanitize($comment));
        $this->hotel->updateRating($hotelId);
        return ['success' => true];
    }
}
