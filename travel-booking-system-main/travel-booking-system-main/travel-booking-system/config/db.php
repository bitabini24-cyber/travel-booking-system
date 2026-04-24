<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_user');
define('DB_PASS', 'your_password');
define('DB_NAME', 'travel_booking');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    $msg = $e->getMessage();
    $isNoDb = str_contains($msg, 'Unknown database');
    $hint   = $isNoDb
        ? 'The database <strong>travel_booking</strong> does not exist yet.<br>Run the SQL setup commands below.'
        : 'Could not connect to MySQL. Make sure XAMPP MySQL is running and credentials are correct.';
    die('<!DOCTYPE html><html><head><title>DB Setup Required</title>
    <style>body{font-family:system-ui;background:#f8f9ff;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0}
    .box{background:white;border-radius:16px;padding:40px;max-width:620px;width:90%;box-shadow:0 4px 30px rgba(0,0,0,.1)}
    h2{color:#dc2626;margin-top:0}pre{background:#1e1e2e;color:#cdd6f4;padding:16px;border-radius:8px;overflow-x:auto;font-size:.85rem}
    .badge{background:#fef9c3;color:#854d0e;padding:4px 12px;border-radius:50px;font-size:.8rem;font-weight:700}</style>
    </head><body><div class="box">
    <h2>&#9888; Database Setup Required</h2>
    <p>' . $hint . '</p>
    <p><span class="badge">Step 1</span> Open <strong>http://localhost/phpmyadmin</strong></p>
    <p><span class="badge">Step 2</span> Click <strong>SQL</strong> tab and run:</p>
    <pre>CREATE DATABASE IF NOT EXISTS travel_booking CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;</pre>
    <p><span class="badge">Step 3</span> Select the <strong>travel_booking</strong> database, click SQL again and paste the contents of:</p>
    <pre>database/schema.sql
database/seed.sql</pre>
    <p><span class="badge">Step 4</span> Refresh this page.</p>
    <hr style="border-color:#e5e7eb;margin:24px 0">
    <p style="color:#6b7280;font-size:.85rem">MySQL error: ' . htmlspecialchars($msg) . '</p>
    </div></body></html>');
}
