<?php
require_once __DIR__ . '/../../config/db.php';

class Hotel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll($filters = [], $limit = 12, $offset = 0) {
        $where = ['1=1'];
        $params = [];

        if (!empty($filters['city'])) {
            $where[] = "city LIKE ?";
            $params[] = '%' . $filters['city'] . '%';
        }
        if (!empty($filters['min_price'])) {
            $where[] = "price >= ?";
            $params[] = $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $where[] = "price <= ?";
            $params[] = $filters['max_price'];
        }
        if (!empty($filters['rating'])) {
            $where[] = "rating >= ?";
            $params[] = $filters['rating'];
        }
        if (!empty($filters['stars'])) {
            $where[] = "stars = ?";
            $params[] = $filters['stars'];
        }

        $orderBy = "created_at DESC";
        if (!empty($filters['sort'])) {
            $allowed = ['price ASC', 'price DESC', 'rating DESC', 'name ASC'];
            if (in_array($filters['sort'], $allowed)) $orderBy = $filters['sort'];
        }

        $sql = "SELECT * FROM hotels WHERE " . implode(' AND ', $where) . " ORDER BY $orderBy LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getFeatured($limit = 6) {
        $stmt = $this->pdo->prepare("SELECT * FROM hotels WHERE is_featured = 1 ORDER BY rating DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM hotels WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO hotels (name, location, city, country, lat, lng, price, stars, image, description, amenities, total_rooms, available_rooms, is_featured) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
            $data['name'], $data['location'], $data['city'], $data['country'],
            $data['lat'] ?? null, $data['lng'] ?? null, $data['price'], $data['stars'],
            $data['image'], $data['description'], $data['amenities'],
            $data['total_rooms'] ?? 10, $data['available_rooms'] ?? 10, $data['is_featured'] ?? 0
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];
        foreach ($data as $key => $val) {
            $fields[] = "$key = ?";
            $values[] = $val;
        }
        $values[] = $id;
        $stmt = $this->pdo->prepare("UPDATE hotels SET " . implode(', ', $fields) . " WHERE id = ?");
        return $stmt->execute($values);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM hotels WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function count($filters = []) {
        $where = ['1=1'];
        $params = [];
        if (!empty($filters['city'])) { $where[] = "city LIKE ?"; $params[] = '%' . $filters['city'] . '%'; }
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM hotels WHERE " . implode(' AND ', $where));
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function updateRating($hotelId) {
        $stmt = $this->pdo->prepare("UPDATE hotels SET rating = (SELECT AVG(rating) FROM reviews WHERE hotel_id = ?) WHERE id = ?");
        return $stmt->execute([$hotelId, $hotelId]);
    }
}
