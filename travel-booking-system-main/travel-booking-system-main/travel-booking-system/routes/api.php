<?php
/**
 * API Routes - v1 JSON endpoints
 */

$apiRoutes = [
    'GET'    => [
        '/api/v1/hotels'    => __DIR__ . '/../api/v1/hotels.php',
        '/api/v1/bookings'  => __DIR__ . '/../api/v1/bookings.php',
        '/api/v1/users'     => __DIR__ . '/../api/v1/users.php',
        '/api/v1/reviews'   => __DIR__ . '/../api/v1/reviews.php',
    ],
    'POST'   => [
        '/api/v1/bookings'  => __DIR__ . '/../api/v1/bookings.php',
        '/api/v1/reviews'   => __DIR__ . '/../api/v1/reviews.php',
    ],
    'PUT'    => [
        '/api/v1/bookings'  => __DIR__ . '/../api/v1/bookings.php',
    ],
    'DELETE' => [
        '/api/v1/bookings'  => __DIR__ . '/../api/v1/bookings.php',
        '/api/v1/reviews'   => __DIR__ . '/../api/v1/reviews.php',
    ],
];

return $apiRoutes;
