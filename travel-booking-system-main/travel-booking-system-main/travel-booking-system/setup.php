<?php
/**
 * TravelLux - One-click database setup
 * Visit: http://localhost/travel-booking-system/setup.php
 */

$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'travel_booking';

$proto   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host    = $_SERVER['HTTP_HOST'] ?? 'localhost';
$dir     = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$baseUrl = $proto . '://' . $host . $dir;

$steps   = [];
$success = false;

// Helper: run SQL statements one by one, skipping safe-to-ignore errors
function runSQL(PDO $pdo, string $sql): void {
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    foreach ($statements as $stmt) {
        if (!$stmt) continue;
        try {
            $pdo->exec($stmt);
        } catch (PDOException $e) {
            $code = (int)($e->errorInfo[1] ?? 0);
            // 1050 = table already exists
            // 1061 = duplicate key name (index already exists)
            // 1068 = multiple primary key
            // 23000 = duplicate entry (seed data)
            if (!in_array($code, [1050, 1061, 1068, 1022])) {
                throw $e;
            }
        }
    }
}

try {
    // 1. Connect without selecting DB
    $pdo = new PDO(
        "mysql:host={$dbHost};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $steps[] = ['ok', 'Connected to MySQL'];

    // 2. Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $steps[] = ['ok', "Database <strong>{$dbName}</strong> ready"];

    // 3. Select database
    $pdo->exec("USE `{$dbName}`");

    // 4. Run schema — build clean SQL without CREATE DATABASE / USE lines
    $schemaFile = __DIR__ . '/database/schema.sql';
    if (!file_exists($schemaFile)) throw new Exception("database/schema.sql not found at: $schemaFile");

    $schema = file_get_contents($schemaFile);
    $schema = preg_replace('/^\s*(CREATE\s+DATABASE\b|USE\b)[^\n]*\n?/im', '', $schema);
    runSQL($pdo, $schema);
    $steps[] = ['ok', 'Schema tables and indexes ready'];

    // 5. Run seed data
    $seedFile = __DIR__ . '/database/seed.sql';
    if (!file_exists($seedFile)) throw new Exception("database/seed.sql not found at: $seedFile");

    $seed = file_get_contents($seedFile);
    $seed = preg_replace('/^\s*USE\b[^\n]*\n?/im', '', $seed);
    runSQL($pdo, $seed);
    $steps[] = ['ok', 'Sample data inserted'];

    // 6. Verify counts
    $hotels = $pdo->query("SELECT COUNT(*) FROM hotels")->fetchColumn();
    $users  = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $steps[] = ['ok', "{$hotels} hotels and {$users} users in database"];

    $success = true;

} catch (Exception $e) {
    $steps[] = ['err', 'Error: ' . htmlspecialchars($e->getMessage())];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TravelLux Setup</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:system-ui,-apple-system,sans-serif;background:#f0f4ff;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}
.card{background:#fff;border-radius:20px;padding:48px;max-width:560px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.12)}
.logo{font-size:1.8rem;font-weight:900;background:linear-gradient(135deg,#6C63FF,#FF6584);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:6px}
h1{font-size:1.4rem;font-weight:700;color:#111;margin-bottom:4px}
.sub{color:#6b7280;font-size:.9rem;margin-bottom:32px}
.step{display:flex;align-items:flex-start;gap:12px;padding:12px 0;border-bottom:1px solid #f3f4f6}
.step:last-child{border:none}
.icon{width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.85rem;flex-shrink:0;margin-top:1px;font-weight:700}
.ok .icon{background:#dcfce7;color:#16a34a}
.err .icon{background:#fee2e2;color:#dc2626}
.step-text{font-size:.9rem;color:#374151;line-height:1.5;padding-top:3px}
.actions{margin-top:32px;display:flex;gap:12px;flex-wrap:wrap}
.btn{display:inline-flex;align-items:center;gap:8px;padding:12px 28px;border-radius:10px;font-weight:700;font-size:.95rem;text-decoration:none;transition:all .2s}
.btn-primary{background:linear-gradient(135deg,#6C63FF,#FF6584);color:#fff;box-shadow:0 4px 15px rgba(108,99,255,.35)}
.btn-primary:hover{transform:translateY(-2px)}
.btn-outline{background:#fff;color:#6C63FF;border:2px solid #6C63FF}
.creds{background:#f8f9ff;border:1px solid #e0e7ff;border-radius:10px;padding:16px 20px;margin-top:16px;font-size:.85rem}
.creds h4{font-weight:700;margin-bottom:8px;color:#4338ca}
.creds table{width:100%;border-collapse:collapse}
.creds td{padding:4px 8px;color:#374151}
.creds td:first-child{font-weight:600;color:#6C63FF;width:60px}
.warn{background:#fef9c3;border:1px solid #fde68a;border-radius:10px;padding:14px 18px;margin-top:16px;font-size:.82rem;color:#854d0e}
code{background:#f3f4f6;padding:2px 6px;border-radius:4px;font-size:.82rem}
</style>
</head>
<body>
<div class="card">
    <div class="logo">✈ TravelLux</div>
    <h1>Database Setup</h1>
    <p class="sub"><?= $success ? 'All steps completed successfully!' : 'Setup encountered an error.' ?></p>

    <?php foreach ($steps as [$type, $msg]): ?>
    <div class="step <?= $type ?>">
        <div class="icon"><?= $type === 'ok' ? '✓' : '✕' ?></div>
        <div class="step-text"><?= $msg ?></div>
    </div>
    <?php endforeach; ?>

    <?php if ($success): ?>
    <div class="creds">
        <h4>Demo Login Credentials</h4>
        <table>
            <tr><td>Admin</td><td>admin@travellux.com / <code>password</code></td></tr>
            <tr><td>User</td><td>john@example.com / <code>password</code></td></tr>
        </table>
    </div>
    <div class="actions">
        <a href="<?= $baseUrl ?>/index.php" class="btn btn-primary">🚀 Launch TravelLux</a>
        <a href="<?= $baseUrl ?>/auth/login.php" class="btn btn-outline">Login</a>
    </div>
    <div class="warn">⚠️ Delete <code>setup.php</code> after setup is complete.</div>
    <?php else: ?>
    <div class="actions">
        <a href="<?= $baseUrl ?>/setup.php" class="btn btn-primary">↺ Try Again</a>
    </div>
    <div class="warn">Make sure <strong>MySQL is running</strong> in XAMPP, then try again.</div>
    <?php endif; ?>
</div>
</body>
</html>
