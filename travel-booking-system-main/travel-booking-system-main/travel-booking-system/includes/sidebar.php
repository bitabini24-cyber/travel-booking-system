<aside class="sidebar">
    <div style="margin-bottom: 32px;">
        <div style="width: 56px; height: 56px; border-radius: 50%; background: var(--gradient); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.4rem; font-weight: 700; margin-bottom: 12px;">
            <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
        </div>
        <div style="color: white; font-weight: 700;"><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></div>
        <div style="color: rgba(255,255,255,0.5); font-size: 0.8rem;"><?= isAdmin() ? 'Administrator' : 'Traveler' ?></div>
    </div>
    <nav class="sidebar-nav">
        <a href="<?= APP_URL ?>/pages/user/dashboard.php" class="<?= strpos($_SERVER['REQUEST_URI'], 'user/dashboard') !== false ? 'active' : '' ?>">🏠 Dashboard</a>
        <a href="<?= APP_URL ?>/pages/user/bookings.php" class="<?= strpos($_SERVER['REQUEST_URI'], 'user/bookings') !== false ? 'active' : '' ?>">📅 My Bookings</a>
        <a href="<?= APP_URL ?>/pages/user/profile.php" class="<?= strpos($_SERVER['REQUEST_URI'], 'user/profile') !== false ? 'active' : '' ?>">👤 Profile</a>
        <a href="<?= APP_URL ?>/pages/user/reviews.php" class="<?= strpos($_SERVER['REQUEST_URI'], 'user/reviews') !== false ? 'active' : '' ?>">⭐ My Reviews</a>
        <a href="<?= APP_URL ?>/pages/search.php">🔍 Explore Hotels</a>
        <?php if (isAdmin()): ?>
        <hr style="border-color: rgba(255,255,255,0.1); margin: 16px 0;">
        <a href="<?= APP_URL ?>/pages/admin/dashboard.php">⚙️ Admin Panel</a>
        <?php endif; ?>
        <hr style="border-color: rgba(255,255,255,0.1); margin: 16px 0;">
        <a href="<?= APP_URL ?>/auth/logout.php">🚪 Logout</a>
    </nav>
</aside>
