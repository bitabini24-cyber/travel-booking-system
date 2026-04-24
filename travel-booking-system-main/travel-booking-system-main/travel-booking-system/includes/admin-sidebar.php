<aside class="sidebar">
    <div style="margin-bottom: 32px;">
        <div style="width: 56px; height: 56px; border-radius: 50%; background: var(--gradient); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.4rem; font-weight: 700; margin-bottom: 12px;">A</div>
        <div style="color: white; font-weight: 700;"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></div>
        <div style="color: rgba(255,255,255,0.5); font-size: 0.8rem;">Administrator</div>
    </div>
    <nav class="sidebar-nav">
        <a href="<?= APP_URL ?>/pages/admin/dashboard.php" class="<?= strpos($_SERVER['REQUEST_URI'], 'admin/dashboard') !== false ? 'active' : '' ?>">📊 Dashboard</a>
        <a href="<?= APP_URL ?>/pages/admin/hotels.php" class="<?= strpos($_SERVER['REQUEST_URI'], 'admin/hotels') !== false ? 'active' : '' ?>">🏨 Hotels</a>
        <a href="<?= APP_URL ?>/pages/admin/bookings.php" class="<?= strpos($_SERVER['REQUEST_URI'], 'admin/bookings') !== false ? 'active' : '' ?>">📅 Bookings</a>
        <a href="<?= APP_URL ?>/pages/admin/users.php" class="<?= strpos($_SERVER['REQUEST_URI'], 'admin/users') !== false ? 'active' : '' ?>">👥 Users</a>
        <a href="<?= APP_URL ?>/pages/admin/reviews.php" class="<?= strpos($_SERVER['REQUEST_URI'], 'admin/reviews') !== false ? 'active' : '' ?>">⭐ Reviews</a>
        <a href="<?= APP_URL ?>/pages/admin/analytics.php" class="<?= strpos($_SERVER['REQUEST_URI'], 'admin/analytics') !== false ? 'active' : '' ?>">📈 Analytics</a>
        <hr style="border-color: rgba(255,255,255,0.1); margin: 16px 0;">
        <a href="<?= APP_URL ?>/index.php">🌐 View Site</a>
        <a href="<?= APP_URL ?>/auth/logout.php">🚪 Logout</a>
    </nav>
</aside>
