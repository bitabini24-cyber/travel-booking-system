<?php
/**
 * Booking System Tests
 * Run: php tests/booking-test.php
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../backend/helpers/functions.php';
require_once __DIR__ . '/../backend/helpers/validation.php';
require_once __DIR__ . '/../backend/models/Booking.php';
require_once __DIR__ . '/../backend/models/Hotel.php';

$passed = 0;
$failed = 0;

function assert_test(string $name, bool $condition): void {
    global $passed, $failed;
    if ($condition) {
        echo "\033[32m✓ PASS\033[0m $name\n";
        $passed++;
    } else {
        echo "\033[31m✗ FAIL\033[0m $name\n";
        $failed++;
    }
}

echo "\n=== Booking Tests ===\n\n";

// --- Date Validation ---
$today    = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));
$nextWeek = date('Y-m-d', strtotime('+7 days'));
$yesterday = date('Y-m-d', strtotime('-1 day'));

assert_test('Valid future dates pass',          validateBookingDates($tomorrow, $nextWeek));
assert_test('Past check-in fails',              !validateBookingDates($yesterday, $tomorrow));
assert_test('Same day check-in/out fails',      !validateBookingDates($tomorrow, $tomorrow));
assert_test('Check-out before check-in fails',  !validateBookingDates($nextWeek, $tomorrow));
assert_test('Invalid date format fails',        !validateBookingDates('not-a-date', $tomorrow));

// --- Night Calculation ---
assert_test('calcNights: 7 nights',  calcNights($tomorrow, $nextWeek) === 7);
assert_test('calcNights: 1 night',   calcNights($tomorrow, date('Y-m-d', strtotime('+2 days'))) === 1);

// --- Booking Model ---
$bookingModel = new Booking($pdo);

// Test availability check (hotel 1 should exist from seed data)
$available = $bookingModel->isAvailable(1, $nextWeek, date('Y-m-d', strtotime('+14 days')));
assert_test('Availability check returns boolean', is_bool($available));

// Test getByUser with non-existent user returns empty array
$bookings = $bookingModel->getByUser(99999);
assert_test('getByUser returns array for unknown user', is_array($bookings));
assert_test('getByUser returns empty for unknown user', count($bookings) === 0);

// --- Hotel Model ---
$hotelModel = new Hotel($pdo);
$hotels = $hotelModel->getFeatured(3);
assert_test('getFeatured returns array',         is_array($hotels));
assert_test('getFeatured respects limit',        count($hotels) <= 3);

$hotel = $hotelModel->findById(1);
assert_test('findById returns hotel or null',    $hotel === false || is_array($hotel));

// --- Price Calculation ---
$price  = 200.00;
$nights = 5;
$rooms  = 2;
$total  = $price * $nights * $rooms;
assert_test('Price calculation: 200 × 5 nights × 2 rooms = 2000', $total === 2000.00);

// --- Summary ---
echo "\n--- Results: $passed passed, $failed failed ---\n\n";
exit($failed > 0 ? 1 : 0);
