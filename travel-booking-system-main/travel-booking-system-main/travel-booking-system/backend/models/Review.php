<?php
require_once __DIR__ . '/../../config/db.php';

class Review {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getByHotel($hotelId) {
        $stmt = $this->pdo->prepare("SELECT r.*, u.name as user_name, u.avatar FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.hotel_id = ? ORDER BY r.created_at DESC");
        $stmt->execute([$hotelId]);
        return $stmt->fetchAll();
    }

    public function create($userId, $hotelId, $rating, $comment) {
        $stmt = $this->pdo->prepare("INSERT INTO reviews (user_id, hotel_id, rating, comment) VALUES (?,?,?,?)");
        $stmt->execute([$userId, $hotelId, $rating, $comment]);
        return $this->pdo->lastInsertId();
    }

    public function hasReviewed($userId, $hotelId) {
        $stmt = $this->pdo->prepare("SELECT id FROM reviews WHERE user_id = ? AND hotel_id = ?");
        $stmt->execute([$userId, $hotelId]);
        return $stmt->fetch() !== false;
    }

    public function getAverage($hotelId) {
        $stmt = $this->pdo->prepare("SELECT AVG(rating) as avg, COUNT(*) as total FROM reviews WHERE hotel_id = ?");
        $stmt->execute([$hotelId]);
        return $stmt->fetch();
    }

    public function getAll($limit = 50) {
        $stmt = $this->pdo->prepare("SELECT r.*, u.name as user_name, h.name as hotel_name FROM reviews r JOIN users u ON r.user_id = u.id JOIN hotels h ON r.hotel_id = h.id ORDER BY r.created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM reviews WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
