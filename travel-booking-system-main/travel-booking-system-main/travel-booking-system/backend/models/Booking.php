<?php
require_once __DIR__ . '/../../config/db.php';

class Booking {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function isAvailable($hotelId, $checkIn, $checkOut, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM bookings 
                WHERE hotel_id = ? 
                AND status NOT IN ('cancelled') 
                AND (check_in < ? AND check_out > ?)";
        $params = [$hotelId, $checkOut, $checkIn];
        if ($excludeId) { $sql .= " AND id != ?"; $params[] = $excludeId; }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() == 0;
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO bookings (user_id, hotel_id, check_in, check_out, guests, rooms, total_price, special_requests) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([
            $data['user_id'], $data['hotel_id'], $data['check_in'], $data['check_out'],
            $data['guests'] ?? 1, $data['rooms'] ?? 1, $data['total_price'],
            $data['special_requests'] ?? null
        ]);
        return $this->pdo->lastInsertId();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT b.*, h.name as hotel_name, h.image, h.location, u.name as user_name, u.email as user_email FROM bookings b JOIN hotels h ON b.hotel_id = h.id JOIN users u ON b.user_id = u.id WHERE b.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByUser($userId) {
        $stmt = $this->pdo->prepare("SELECT b.*, h.name as hotel_name, h.image, h.location, h.city FROM bookings b JOIN hotels h ON b.hotel_id = h.id WHERE b.user_id = ? ORDER BY b.created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getAll($limit = 50, $offset = 0) {
        $stmt = $this->pdo->prepare("SELECT b.*, h.name as hotel_name, u.name as user_name, u.email FROM bookings b JOIN hotels h ON b.hotel_id = h.id JOIN users u ON b.user_id = u.id ORDER BY b.created_at DESC LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function updateStatus($id, $status) {
        $stmt = $this->pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function count() {
        return $this->pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
    }

    public function totalRevenue() {
        return $this->pdo->query("SELECT SUM(total_price) FROM bookings WHERE status = 'confirmed'")->fetchColumn();
    }
}
