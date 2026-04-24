<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/Hotel.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/validation.php';

class HotelController {
    private $hotel;

    public function __construct($pdo) {
        $this->hotel = new Hotel($pdo);
    }

    public function search($filters = [], $page = 1, $limit = 12) {
        $offset = ($page - 1) * $limit;
        $hotels = $this->hotel->getAll($filters, $limit, $offset);
        $total  = $this->hotel->count($filters);
        return [
            'hotels'      => $hotels,
            'total'       => $total,
            'pages'       => ceil($total / $limit),
            'current_page'=> $page,
        ];
    }

    public function getDetail($id) {
        $hotel = $this->hotel->findById($id);
        if (!$hotel) return null;
        // Parse amenities into array
        $hotel['amenities_list'] = $hotel['amenities']
            ? array_map('trim', explode(',', $hotel['amenities']))
            : [];
        return $hotel;
    }

    public function uploadImage($hotelId, $file) {
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file['type'], $allowed)) return ['error' => 'Invalid file type.'];
        if ($file['size'] > 5 * 1024 * 1024) return ['error' => 'File too large (max 5MB).'];

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'hotel_' . $hotelId . '_' . time() . '.' . $ext;
        $dest     = UPLOAD_PATH . 'hotels/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) return ['error' => 'Upload failed.'];

        $this->hotel->update($hotelId, ['image' => APP_URL . '/storage/uploads/hotels/' . $filename]);
        return ['success' => true, 'image' => $filename];
    }
}
