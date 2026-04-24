<?php
define('APP_NAME', 'TravelLux');
define('APP_VERSION', '1.0.0');
define('SESSION_LIFETIME', 3600);

// ── Bulletproof dynamic APP_URL ──────────────────────────────────────────────
if (!defined('APP_URL')) {
    $proto   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host    = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $docRoot = rtrim(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? '')), '/');
    $projDir = rtrim(str_replace('\\', '/', realpath(__DIR__ . '/..')), '/');

    if ($docRoot && strpos($projDir, $docRoot) === 0) {
        $subPath = substr($projDir, strlen($docRoot));
    } else {
        // Fallback: derive from SCRIPT_NAME
        $script  = $_SERVER['SCRIPT_NAME'] ?? '';
        $parts   = explode('/', trim($script, '/'));
        // Find the project folder name in the path
        $projName = basename($projDir);
        $idx = array_search($projName, $parts);
        $subPath = $idx !== false ? '/' . implode('/', array_slice($parts, 0, $idx + 1)) : '/' . $projName;
    }

    define('APP_URL', $proto . '://' . $host . $subPath);
}
// ─────────────────────────────────────────────────────────────────────────────

define('UPLOAD_PATH', __DIR__ . '/../storage/uploads/');
define('LOG_PATH',    __DIR__ . '/../storage/logs/');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
