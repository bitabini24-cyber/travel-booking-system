<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/Hotel.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/validation.php';

class BookingController {
    private $booking;
    private $hotel;

    public function __construct($pdo) {
        $this->booking = new Booking($pdo);
        $this->hotel = new Hotel($pdo);
    }

    public function create($data) {
        if (!isLoggedIn()) return ['error' => 'Please login to book.'];

        $errors = validateRequired(['hotel_id', 'check_in', 'check_out'], $data);
        if (!empty($errors)) return ['errors' => $errors];

        if (!validateBookingDates($data['check_in'], $data['check_out'])) {
            return ['error' => 'Invalid booking dates.'];
        }

        $hotel = $this->hotel->findById($data['hotel_id']);
        if (!$hotel) return ['error' => 'Hotel not found.'];

        if (!$this->booking->isAvailable($data['hotel_id'], $data['check_in'], $data['check_out'])) {
            return ['error' => 'Hotel is not available for selected dates.'];
        }

        $nights = calcNights($data['check_in'], $data['check_out']);
        $rooms = max(1, intval($data['rooms'] ?? 1));
        $total = $hotel['price'] * $nights * $rooms;

        $id = $this->booking->create([
            'user_id' => $_SESSION['user_id'],
            'hotel_id' => $data['hotel_id'],
            'check_in' => $data['check_in'],
            'check_out' => $data['check_out'],
            'guests' => $data['guests'] ?? 1,
            'rooms' => $rooms,
            'total_price' => $total,
            'special_requests' => sanitize($data['special_requests'] ?? '')
        ]);

        return ['success' => true, 'booking_id' => $id, 'total' => $total];
    }

    public function cancel($bookingId) {
        $booking = $this->booking->findById($bookingId);
        if (!$booking) return ['error' => 'Booking not found.'];
        if ($booking['user_id'] != $_SESSION['user_id'] && !isAdmin()) return ['error' => 'Unauthorized.'];
        $this->booking->updateStatus($bookingId, 'cancelled');
        return ['success' => true];
    }
}
