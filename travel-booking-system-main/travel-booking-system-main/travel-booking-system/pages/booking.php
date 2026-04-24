<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../backend/middleware/auth.php';
require_once __DIR__ . '/../backend/helpers/functions.php';
require_once __DIR__ . '/../backend/models/Hotel.php';

$hotelId = intval($_GET['hotel_id'] ?? 0);
$hotelModel = new Hotel($pdo);
$hotel = $hotelModel->findById($hotelId);
if (!$hotel) redirect(APP_URL . '/pages/search.php');

$checkIn  = sanitize($_GET['check_in'] ?? '');
$checkOut = sanitize($_GET['check_out'] ?? '');
$guests   = intval($_GET['guests'] ?? 1);
$rooms    = intval($_GET['rooms'] ?? 1);

$pageTitle = 'Book ' . htmlspecialchars($hotel['name']) . ' - TravelLux';
require_once __DIR__ . '/../includes/header.php';
?>
<div style="min-height:100vh; background:var(--bg); padding:120px 0 80px;">
    <div class="container" style="max-width:900px;">
        <a href="<?= APP_URL ?>/pages/hotel-details.php?id=<?= $hotel['id'] ?>"
           style="color:var(--primary); font-weight:600; display:inline-flex; align-items:center; gap:6px; margin-bottom:24px;">
            ← Back to Hotel
        </a>
        <h1 style="font-size:2rem; font-weight:800; margin-bottom:8px;">Complete Your Booking</h1>
        <p style="color:var(--text-light); margin-bottom:40px;">Review your details before confirming</p>

        <div style="display:grid; grid-template-columns:1fr 360px; gap:32px; align-items:start;">
            <!-- BOOKING FORM -->
            <div class="hotel-card" style="padding:32px;">
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
                <?php endif; ?>
                <form action="<?= APP_URL ?>/backend/booking-process.php" method="POST" id="bookingForm">
                    <input type="hidden" name="hotel_id" value="<?= $hotel['id'] ?>">

                    <h3 style="font-weight:700; margin-bottom:20px;">Stay Details</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Check In *</label>
                            <input type="date" name="check_in" id="bookCheckIn"
                                   value="<?= htmlspecialchars($checkIn) ?>"
                                   min="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Check Out *</label>
                            <input type="date" name="check_out" id="bookCheckOut"
                                   value="<?= htmlspecialchars($checkOut) ?>"
                                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Guests</label>
                            <select name="guests">
                                <?php for ($i = 1; $i <= 6; $i++): ?>
                                    <option value="<?= $i ?>" <?= $guests == $i ? 'selected' : '' ?>><?= $i ?> Guest<?= $i > 1 ? 's' : '' ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Rooms</label>
                            <select name="rooms">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?= $i ?>" <?= $rooms == $i ? 'selected' : '' ?>><?= $i ?> Room<?= $i > 1 ? 's' : '' ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <h3 style="font-weight:700; margin:24px 0 20px;">Guest Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>First Name *</label>
                            <input type="text" name="first_name" value="<?= htmlspecialchars(explode(' ', $_SESSION['user_name'] ?? '')[0]) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name *</label>
                            <input type="text" name="last_name" value="<?= htmlspecialchars(explode(' ', $_SESSION['user_name'] ?? '', 2)[1] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Special Requests</label>
                        <textarea name="special_requests" rows="3" placeholder="Early check-in, dietary requirements, etc."></textarea>
                    </div>

                    <!-- PRICE SUMMARY -->
                    <div id="priceSummary" style="background:var(--bg); border-radius:var(--radius); padding:20px; margin-bottom:24px; display:none;">
                        <h4 style="font-weight:700; margin-bottom:12px;">Price Breakdown</h4>
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:0.9rem;">
                            <span id="nightsLabel">0 nights</span>
                            <span id="nightsPrice">$0</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:0.9rem; color:var(--text-light);">
                            <span>Taxes & fees (10%)</span>
                            <span id="taxPrice">$0</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-weight:800; font-size:1.1rem; border-top:1px solid var(--border); padding-top:12px;">
                            <span>Total</span>
                            <span id="totalPrice" style="color:var(--primary);">$0</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full btn-lg">Confirm Booking</button>
                </form>
            </div>

            <!-- HOTEL SUMMARY CARD -->
            <div>
                <div class="booking-card" data-aos="fade-left">
                    <img src="<?= htmlspecialchars($hotel['image']) ?>"
                         style="width:100%; height:180px; object-fit:cover; border-radius:var(--radius); margin-bottom:20px;" alt="">
                    <h3 style="font-weight:700; margin-bottom:6px;"><?= htmlspecialchars($hotel['name']) ?></h3>
                    <p style="color:var(--text-light); font-size:0.85rem; margin-bottom:12px;">📍 <?= htmlspecialchars($hotel['location']) ?></p>
                    <div class="hotel-card-rating" style="margin-bottom:16px;">
                        <div class="stars"><?= renderStars($hotel['rating']) ?></div>
                        <span><?= number_format($hotel['rating'], 1) ?></span>
                    </div>
                    <div style="border-top:1px solid var(--border); padding-top:16px;">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <span style="color:var(--text-light);">Per night</span>
                            <span style="font-size:1.4rem; font-weight:800; color:var(--primary);"><?= formatPrice($hotel['price']) ?></span>
                        </div>
                    </div>
                    <?php if ($hotel['amenities']): ?>
                    <div style="margin-top:16px; display:flex; flex-wrap:wrap; gap:6px;">
                        <?php foreach (array_slice(explode(',', $hotel['amenities']), 0, 4) as $a): ?>
                            <span class="amenity-tag"><?= trim($a) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const hotelPrice = <?= $hotel['price'] ?>;
</script>
<script src="<?= APP_URL ?>/assets/js/booking.js"></script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
