<?php
/**
 * Web Routes - Maps URL paths to page files
 * Include this in public/index.php for clean URL routing
 */

$routes = [
    '/'                     => __DIR__ . '/../pages/home.php',
    '/search'               => __DIR__ . '/../pages/search.php',
    '/hotel'                => __DIR__ . '/../pages/hotel-details.php',
    '/booking'              => __DIR__ . '/../pages/booking.php',
    '/checkout'             => __DIR__ . '/../pages/checkout.php',
    '/confirmation'         => __DIR__ . '/../pages/confirmation.php',

    // Auth
    '/login'                => __DIR__ . '/../auth/login.php',
    '/register'             => __DIR__ . '/../auth/register.php',
    '/logout'               => __DIR__ . '/../auth/logout.php',
    '/forgot-password'      => __DIR__ . '/../auth/forgot-password.php',
    '/reset-password'       => __DIR__ . '/../auth/reset-password.php',
    '/verify'               => __DIR__ . '/../auth/verify.php',

    // User
    '/user/dashboard'       => __DIR__ . '/../pages/user/dashboard.php',
    '/user/profile'         => __DIR__ . '/../pages/user/profile.php',
    '/user/bookings'        => __DIR__ . '/../pages/user/bookings.php',
    '/user/reviews'         => __DIR__ . '/../pages/user/reviews.php',

    // Admin
    '/admin'                => __DIR__ . '/../pages/admin/dashboard.php',
    '/admin/hotels'         => __DIR__ . '/../pages/admin/hotels.php',
    '/admin/bookings'       => __DIR__ . '/../pages/admin/bookings.php',
    '/admin/users'          => __DIR__ . '/../pages/admin/users.php',
    '/admin/reviews'        => __DIR__ . '/../pages/admin/reviews.php',
    '/admin/analytics'      => __DIR__ . '/../pages/admin/analytics.php',
];

return $routes;
