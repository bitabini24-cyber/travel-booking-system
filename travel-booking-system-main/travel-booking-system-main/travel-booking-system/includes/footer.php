<?php require_once __DIR__ . '/modals.php'; ?>

<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="<?= APP_URL ?>/index.php" class="footer-logo">✈ TravelLux</a>
                <p>Your premium travel companion. Discover extraordinary destinations and create memories that last a lifetime.</p>
                <div class="footer-social" style="margin-top:24px;">
                    <a href="#" aria-label="Facebook">📘</a>
                    <a href="#" aria-label="Instagram">📸</a>
                    <a href="#" aria-label="Twitter">🐦</a>
                    <a href="#" aria-label="YouTube">▶️</a>
                </div>
            </div>
            <div class="footer-links">
                <h4>Explore</h4>
                <ul>
                    <li><a href="<?= APP_URL ?>/pages/search.php">All Hotels</a></li>
                    <li><a href="<?= APP_URL ?>/pages/search.php?city=Paris">Paris</a></li>
                    <li><a href="<?= APP_URL ?>/pages/search.php?city=Bali">Bali</a></li>
                    <li><a href="<?= APP_URL ?>/pages/search.php?city=Tokyo">Tokyo</a></li>
                    <li><a href="<?= APP_URL ?>/pages/search.php?city=Maldives">Maldives</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Account</h4>
                <ul>
                    <li><a href="<?= APP_URL ?>/auth/login.php">Login</a></li>
                    <li><a href="<?= APP_URL ?>/auth/register.php">Register</a></li>
                    <li><a href="<?= APP_URL ?>/pages/user/dashboard.php">My Dashboard</a></li>
                    <li><a href="<?= APP_URL ?>/pages/user/bookings.php">My Bookings</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Support</h4>
                <ul>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> TravelLux. All rights reserved. Made with ❤️ for travelers.</p>
            <p style="font-size:0.78rem; color:rgba(255,255,255,0.25);">Powered by PHP & MySQL</p>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="<?= APP_URL ?>/assets/js/main.js"></script>
<script src="<?= APP_URL ?>/assets/js/theme.js"></script>
<script src="<?= APP_URL ?>/assets/js/planes.js"></script>
<script src="<?= APP_URL ?>/assets/js/ajax.js"></script>
<script src="<?= APP_URL ?>/assets/js/search.js"></script>
<script src="<?= APP_URL ?>/assets/js/slider.js"></script>
<script src="<?= APP_URL ?>/assets/js/animations.js"></script>
<script src="<?= APP_URL ?>/assets/js/image-viewer.js"></script>
</body>
</html>
