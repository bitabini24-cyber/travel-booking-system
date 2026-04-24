<nav class="navbar" id="navbar">
    <div class="container nav-inner">
        <a href="<?= APP_URL ?>/index.php" class="nav-logo">✈ TravelLux</a>

        <ul class="nav-links" id="navLinks">
            <li><a href="<?= APP_URL ?>/index.php">Home</a></li>
            <li><a href="<?= APP_URL ?>/pages/search.php">Destinations</a></li>
            <li><a href="<?= APP_URL ?>/pages/packages.php">Packages</a></li>
            <li><a href="<?= APP_URL ?>/pages/blog.php">Blog</a></li>
            <li><a href="<?= APP_URL ?>/pages/contact.php">Contact</a></li>
            <?php if (isLoggedIn()): ?>
                <li><a href="<?= APP_URL ?>/pages/user/dashboard.php">My Trips</a></li>
                <?php if (isAdmin()): ?>
                    <li><a href="<?= APP_URL ?>/pages/admin/dashboard.php">Admin</a></li>
                <?php endif; ?>
                <li><a href="<?= APP_URL ?>/auth/logout.php" class="btn btn-outline" style="padding:9px 22px;font-size:0.88rem;">Logout</a></li>
            <?php else: ?>
                <li><a href="<?= APP_URL ?>/auth/login.php" class="btn btn-outline" style="padding:9px 22px;font-size:0.88rem;">Login</a></li>
                <li><a href="<?= APP_URL ?>/auth/register.php" class="btn btn-primary" style="padding:9px 22px;font-size:0.88rem;">Sign Up</a></li>
            <?php endif; ?>
            <li id="themeSwitcher"></li>
        </ul>

        <div style="display:flex;align-items:center;gap:12px;">
            <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</nav>
