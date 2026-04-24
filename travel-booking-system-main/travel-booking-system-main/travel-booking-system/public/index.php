<?php
/**
 * Public entry point for clean URL routing
 * Point your web server document root here
 */
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../backend/helpers/functions.php';

$routes = require __DIR__ . '/../routes/web.php';

$uri = strtok($_SERVER['REQUEST_URI'], '?');
$uri = rtrim($uri, '/') ?: '/';

// Strip base path if running in subdirectory
$basePath = parse_url(APP_URL, PHP_URL_PATH) ?? '';
if ($basePath && str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath)) ?: '/';
}

if (isset($routes[$uri]) && file_exists($routes[$uri])) {
    require $routes[$uri];
} else {
    http_response_code(404);
    echo '<!DOCTYPE html><html><head><title>404 - TravelLux</title></head><body style="font-family:system-ui;text-align:center;padding:100px;">
    <h1 style="font-size:5rem;margin:0;">404</h1>
    <p>Page not found. <a href="' . APP_URL . '">Go Home</a></p></body></html>';
}
