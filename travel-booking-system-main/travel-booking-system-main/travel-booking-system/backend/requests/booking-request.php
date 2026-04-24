<?php
/**
 * Booking Request Validator
 * Validates and sanitizes booking form input
 */

require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/validation.php';

function validateBookingRequest(array $data): array {
    $errors = [];

    // Required fields
    if (empty($data['hotel_id']) || !is_numeric($data['hotel_id'])) {
        $errors[] = 'Invalid hotel selection.';
    }
    if (empty($data['check_in'])) {
        $errors[] = 'Check-in date is required.';
    }
    if (empty($data['check_out'])) {
        $errors[] = 'Check-out date is required.';
    }

    // Date validation
    if (!empty($data['check_in']) && !empty($data['check_out'])) {
        if (!validateBookingDates($data['check_in'], $data['check_out'])) {
            $errors[] = 'Invalid dates. Check-out must be after check-in and both must be in the future.';
        }
    }

    // Guests
    $guests = intval($data['guests'] ?? 1);
    if ($guests < 1 || $guests > 20) {
        $errors[] = 'Guests must be between 1 and 20.';
    }

    // Rooms
    $rooms = intval($data['rooms'] ?? 1);
    if ($rooms < 1 || $rooms > 10) {
        $errors[] = 'Rooms must be between 1 and 10.';
    }

    return [
        'errors' => $errors,
        'data'   => [
            'hotel_id'         => intval($data['hotel_id']),
            'check_in'         => sanitize($data['check_in']),
            'check_out'        => sanitize($data['check_out']),
            'guests'           => $guests,
            'rooms'            => $rooms,
            'special_requests' => sanitize($data['special_requests'] ?? ''),
        ]
    ];
}
