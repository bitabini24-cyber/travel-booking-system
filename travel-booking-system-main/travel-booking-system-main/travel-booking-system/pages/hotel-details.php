<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../backend/helpers/functions.php';
require_once __DIR__ . '/../backend/models/Hotel.php';
require_once __DIR__ . '/../backend/models/Review.php';
require_once __DIR__ . '/../backend/controllers/ReviewController.php';

$id = intval($_GET['id'] ?? 0);
$hotelModel = new Hotel($pdo);
$hotel = $hotelModel->findById($id);
if (!$hotel) { redirect(APP_URL . '/pages/search.php'); }

$reviewModel = new Review($pdo);
$reviews = $reviewModel->getByHotel($id);
$ratingData = $reviewModel->getAverage($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $ctrl = new ReviewController($pdo);
    $result = $ctrl->create($id, intval($_POST['rating']), $_POST['comment'] ?? '');
    if ($result['success'] ?? false) {
        $reviews = $reviewModel->getByHotel($id);
        $ratingData = $reviewModel->getAverage($id);
    }
}

$amenities = $hotel['amenities'] ? explode(',', $hotel['amenities']) : [];
$pageTitle = htmlspecialchars($hotel['name']) . ' — TravelLux';
require_once __DIR__ . '/../includes/header.php';
?>
<style>
*{box-sizing:border-box}
/* ── Hero ── */
.hd-hero{position:relative;height:70vh;min-height:500px;overflow:hidden;margin-top:72px}
.hd-hero img{width:100%;height:100%;object-fit:cover;transition:transform 8s ease}
.hd-hero:hover img{transform:scale(1.04)}
.hd-hero-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(6,6,18,.95) 0%,rgba(6,6,18,.4) 50%,transparent 100%)}
.hd-hero-content{position:absolute;bottom:0;left:0;right:0;padding:48px 60px}
.hd-badge{display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff;padding:5px 14px;border-radius:50px;font-size:.75rem;font-weight:800;margin-bottom:14px}
.hd-title{font-family:'Poppins',sans-serif;font-size:clamp(2rem,5vw,3.8rem);font-weight:900;color:#fff;line-height:1.1;margin-bottom:12px;text-shadow:0 4px 30px rgba(0,0,0,.5)}
.hd-loc{color:rgba(255,255,255,.7);font-size:1rem;display:flex;align-items:center;gap:8px;margin-bottom:20px}
.hd-meta{display:flex;gap:24px;flex-wrap:wrap}
.hd-meta-item{display:flex;align-items:center;gap:8px;background:rgba(255,255,255,.1);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.15);padding:8px 18px;border-radius:50px;color:#fff;font-size:.85rem;font-weight:600}

/* ── Layout ── */
.hd-wrap{max-width:1320px;margin:0 auto;padding:60px 40px;display:grid;grid-template-columns:1fr 380px;gap:48px;align-items:start}
@media(max-width:1024px){.hd-wrap{grid-template-columns:1fr;padding:40px 20px}}

/* ── Sections ── */
.hd-section{margin-bottom:48px}
.hd-section-title{font-family:'Poppins',sans-serif;font-size:1.4rem;font-weight:800;color:#fff;margin-bottom:20px;display:flex;align-items:center;gap:10px}
.hd-section-title::after{content:'';flex:1;height:1px;background:rgba(255,255,255,.08)}

/* ── Description ── */
.hd-desc{color:rgba(255,255,255,.65);line-height:1.9;font-size:1rem}

/* ── Amenities ── */
.hd-amenities{display:flex;flex-wrap:wrap;gap:10px}
.hd-amenity{display:flex;align-items:center;gap:8px;background:rgba(124,58,237,.12);border:1px solid rgba(124,58,237,.25);color:rgba(255,255,255,.8);padding:9px 18px;border-radius:50px;font-size:.88rem;font-weight:600;transition:.25s}
.hd-amenity:hover{background:rgba(124,58,237,.25);border-color:rgba(124,58,237,.5);transform:translateY(-2px)}

/* ── Highlights ── */
.hd-highlights{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
@media(max-width:768px){.hd-highlights{grid-template-columns:1fr}}
.hd-hl{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:16px;padding:22px;text-align:center;transition:.3s}
.hd-hl:hover{background:rgba(124,58,237,.1);border-color:rgba(124,58,237,.3);transform:translateY(-4px)}
.hd-hl-icon{font-size:2rem;margin-bottom:10px}
.hd-hl-val{font-family:'Poppins',sans-serif;font-size:1.4rem;font-weight:800;color:#fff;margin-bottom:4px}
.hd-hl-lbl{color:rgba(255,255,255,.45);font-size:.78rem;text-transform:uppercase;letter-spacing:.8px}

/* ── Reviews ── */
.hd-review{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:16px;padding:22px;margin-bottom:14px;transition:.3s}
.hd-review:hover{border-color:rgba(124,58,237,.3);background:rgba(124,58,237,.06)}
.hd-review-hdr{display:flex;align-items:center;gap:12px;margin-bottom:12px}
.hd-review-av{width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#ec4899);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:1.1rem;flex-shrink:0}
.hd-review-name{color:#fff;font-weight:700;font-size:.92rem}
.hd-review-date{color:rgba(255,255,255,.35);font-size:.75rem}
.hd-review-stars{color:#f59e0b;font-size:.85rem;margin-left:auto}
.hd-review-text{color:rgba(255,255,255,.65);font-size:.9rem;line-height:1.75;font-style:italic}

/* ── Booking card ── */
.hd-book{background:rgba(15,12,41,.95);backdrop-filter:blur(24px);border:1px solid rgba(124,58,237,.3);border-radius:24px;padding:32px;position:sticky;top:100px;box-shadow:0 24px 80px rgba(0,0,0,.5)}
.hd-book-price{font-family:'Poppins',sans-serif;font-size:2.8rem;font-weight:900;background:linear-gradient(135deg,#a78bfa,#f9a8d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1;margin-bottom:4px}
.hd-book-per{color:rgba(255,255,255,.4);font-size:.85rem;margin-bottom:24px}
.hd-book label{display:block;color:rgba(255,255,255,.55);font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px}
.hd-book input,.hd-book select,.hd-book textarea{width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:12px 16px;color:#fff;font-size:.92rem;font-family:inherit;outline:none;transition:.2s;margin-bottom:14px}
.hd-book input:focus,.hd-book select:focus,.hd-book textarea:focus{border-color:rgba(124,58,237,.6);background:rgba(124,58,237,.08)}
.hd-book select option{background:#1a1a2e;color:#fff}
.hd-book-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.hd-summary{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:16px;margin-bottom:20px}
.hd-summary-row{display:flex;justify-content:space-between;color:rgba(255,255,255,.55);font-size:.85rem;margin-bottom:8px}
.hd-summary-total{display:flex;justify-content:space-between;color:#fff;font-weight:800;font-size:1.05rem;border-top:1px solid rgba(255,255,255,.1);padding-top:10px;margin-top:4px}
.hd-btn-book{width:100%;background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff;border:none;padding:16px;border-radius:14px;font-size:1rem;font-weight:800;cursor:pointer;transition:all .3s;font-family:inherit;box-shadow:0 8px 30px rgba(124,58,237,.4)}
.hd-btn-book:hover{transform:translateY(-2px);box-shadow:0 14px 40px rgba(124,58,237,.6)}
.hd-btn-book:disabled{opacity:.5;cursor:not-allowed;transform:none}
.hd-book-trust{display:flex;justify-content:center;gap:20px;margin-top:16px;flex-wrap:wrap}
.hd-book-trust span{color:rgba(255,255,255,.4);font-size:.75rem;display:flex;align-items:center;gap:4px}

/* ── Review form ── */
.hd-review-form{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:16px;padding:24px;margin-bottom:24px}
.star-pick{font-size:2rem;cursor:pointer;color:rgba(255,255,255,.2);transition:.15s}
.star-pick.active,.star-pick:hover{color:#f59e0b}
</style>
<!-- HERO -->
<div class="hd-hero">
  <img src="<?= htmlspecialchars($hotel['image']) ?>" alt="<?= htmlspecialchars($hotel['name']) ?>" id="heroImg">
  <div class="hd-hero-overlay"></div>
  <div class="hd-hero-content">
    <div class="hd-badge"><?= $hotel['stars'] ?> &#9733; Star Hotel</div>
    <h1 class="hd-title"><?= htmlspecialchars($hotel['name']) ?></h1>
    <div class="hd-loc">&#128205; <?= htmlspecialchars($hotel['location'] ?? ($hotel['city'].', '.$hotel['country'])) ?></div>
    <div class="hd-meta">
      <div class="hd-meta-item">&#9733; <?= number_format($hotel['rating'],1) ?> Rating</div>
      <div class="hd-meta-item">&#128101; <?= count($reviews) ?> Reviews</div>
      <div class="hd-meta-item">&#128197; Free Cancellation</div>
      <div class="hd-meta-item">&#9992; Flights Available</div>
    </div>
  </div>
</div>

<div class="hd-wrap">
  <!-- LEFT -->
  <div>

    <!-- HIGHLIGHTS -->
    <div class="hd-section">
      <div class="hd-highlights">
        <div class="hd-hl"><div class="hd-hl-icon">&#9733;</div><div class="hd-hl-val"><?= number_format($hotel['rating'],1) ?>/5</div><div class="hd-hl-lbl">Guest Rating</div></div>
        <div class="hd-hl"><div class="hd-hl-icon">&#127968;</div><div class="hd-hl-val"><?= $hotel['stars'] ?> Star</div><div class="hd-hl-lbl">Hotel Class</div></div>
        <div class="hd-hl"><div class="hd-hl-icon">&#128197;</div><div class="hd-hl-val">Free</div><div class="hd-hl-lbl">Cancellation</div></div>
      </div>
    </div>

    <!-- DESCRIPTION -->
    <div class="hd-section">
      <h2 class="hd-section-title">&#127968; About This Hotel</h2>
      <p class="hd-desc"><?= nl2br(htmlspecialchars($hotel['description'] ?? 'Experience world-class luxury at this stunning property. Nestled in the heart of '.htmlspecialchars($hotel['city']).', this '.($hotel['stars']).'-star hotel offers an unparalleled blend of comfort, elegance, and exceptional service. Every detail has been carefully crafted to ensure your stay is nothing short of extraordinary.')) ?></p>
    </div>

    <!-- AMENITIES -->
    <?php if (!empty($amenities)): ?>
    <div class="hd-section">
      <h2 class="hd-section-title">&#10024; Amenities &amp; Features</h2>
      <div class="hd-amenities">
        <?php
        $icons=['WiFi'=>'&#128246;','Pool'=>'&#127946;','Spa'=>'&#128134;','Restaurant'=>'&#127374;','Bar'=>'&#127864;','Gym'=>'&#127947;','Parking'=>'&#128663;','Beach'=>'&#127958;','Diving'=>'&#129340;','Yoga'=>'&#129496;','Rooftop'=>'&#127749;','Concierge'=>'&#128717;','Air Conditioning'=>'&#10052;','Room Service'=>'&#127860;','Laundry'=>'&#128085;','Business Center'=>'&#128188;'];
        foreach($amenities as $a):
          $a=trim($a); $icon=$icons[$a]??'&#10003;';
        ?>
        <span class="hd-amenity"><?= $icon ?> <?= htmlspecialchars($a) ?></span>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- LOCATION MAP -->
    <?php if ($hotel['lat'] && $hotel['lng']): ?>
    <div class="hd-section">
      <h2 class="hd-section-title">&#128205; Location</h2>
      <div id="map" style="height:320px;border-radius:16px;overflow:hidden"></div>
    </div>
    <?php endif; ?>

    <!-- REVIEWS -->
    <div class="hd-section">
      <h2 class="hd-section-title">&#128172; Guest Reviews (<?= count($reviews) ?>)</h2>

      <?php if (isLoggedIn() && !$reviewModel->hasReviewed($_SESSION['user_id'], $id)): ?>
      <div class="hd-review-form">
        <h3 style="color:#fff;font-weight:700;margin-bottom:16px;font-size:1rem">Write a Review</h3>
        <form method="POST">
          <div style="margin-bottom:14px">
            <label style="color:rgba(255,255,255,.5);font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;display:block;margin-bottom:8px">Your Rating</label>
            <div id="starPicker" style="display:flex;gap:4px">
              <?php for($i=1;$i<=5;$i++): ?>
              <span class="star-pick" data-val="<?= $i ?>">&#9733;</span>
              <?php endfor; ?>
            </div>
            <input type="hidden" name="rating" id="ratingInput" value="5">
          </div>
          <div style="margin-bottom:14px">
            <label style="color:rgba(255,255,255,.5);font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;display:block;margin-bottom:6px">Your Comment</label>
            <textarea name="comment" rows="3" placeholder="Share your experience..." style="width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:12px 16px;color:#fff;font-size:.9rem;font-family:inherit;outline:none;resize:vertical"></textarea>
          </div>
          <button type="submit" name="submit_review" style="background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff;border:none;padding:11px 28px;border-radius:50px;font-size:.88rem;font-weight:700;cursor:pointer;font-family:inherit">Submit Review</button>
        </form>
      </div>
      <?php endif; ?>

      <?php if (empty($reviews)): ?>
        <div style="text-align:center;padding:40px;color:rgba(255,255,255,.35)">&#128172; No reviews yet. Be the first to review!</div>
      <?php else: ?>
        <?php foreach($reviews as $r): ?>
        <div class="hd-review">
          <div class="hd-review-hdr">
            <div class="hd-review-av"><?= strtoupper(substr($r['user_name'],0,1)) ?></div>
            <div><div class="hd-review-name"><?= htmlspecialchars($r['user_name']) ?></div><div class="hd-review-date"><?= formatDate($r['created_at']) ?></div></div>
            <div class="hd-review-stars"><?= str_repeat('&#9733;',$r['rating']) ?><?= str_repeat('&#9734;',5-$r['rating']) ?></div>
          </div>
          <p class="hd-review-text">"<?= nl2br(htmlspecialchars($r['comment'])) ?>"</p>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </div>

  <!-- BOOKING CARD -->
  <div>
    <div class="hd-book">
      <div class="hd-book-price">$<?= number_format($hotel['price']) ?></div>
      <div class="hd-book-per">per night &bull; taxes included</div>

      <?php if (!isLoggedIn()): ?>
        <div style="background:rgba(124,58,237,.12);border:1px solid rgba(124,58,237,.3);border-radius:12px;padding:14px;margin-bottom:18px;color:rgba(255,255,255,.7);font-size:.88rem;text-align:center">
          Please <a href="<?= APP_URL ?>/auth/login.php" style="color:#a78bfa;font-weight:700">login</a> to book this hotel
        </div>
      <?php endif; ?>

      <form action="<?= APP_URL ?>/backend/booking-process.php" method="POST" id="bookingForm">
        <input type="hidden" name="hotel_id" value="<?= $hotel['id'] ?>">
        <label>Check In</label>
        <input type="date" name="check_in" id="bookCheckIn" min="<?= date('Y-m-d') ?>" required>
        <label>Check Out</label>
        <input type="date" name="check_out" id="bookCheckOut" min="<?= date('Y-m-d',strtotime('+1 day')) ?>" required>
        <div class="hd-book-row">
          <div><label>Guests</label><select name="guests"><option>1</option><option>2</option><option>3</option><option>4+</option></select></div>
          <div><label>Rooms</label><select name="rooms"><option>1</option><option>2</option><option>3</option></select></div>
        </div>
        <label>Special Requests</label>
        <textarea name="special_requests" rows="2" placeholder="Any special requests?"></textarea>

        <div class="hd-summary" id="priceSummary" style="display:none">
          <div class="hd-summary-row"><span id="nightsLabel">0 nights</span><span id="nightsPrice">$0</span></div>
          <div class="hd-summary-row"><span>Taxes &amp; fees</span><span id="taxPrice">$0</span></div>
          <div class="hd-summary-total"><span>Total</span><span id="totalPrice">$0</span></div>
        </div>

        <button type="submit" class="hd-btn-book" <?= !isLoggedIn()?'disabled':'' ?>>
          <?= isLoggedIn() ? '&#128197; Book Now' : '&#128274; Login to Book' ?>
        </button>
      </form>

      <div class="hd-book-trust">
        <span>&#128274; Secure Payment</span>
        <span>&#9989; Free Cancellation</span>
        <span>&#127775; Best Price</span>
      </div>
    </div>
  </div>
</div>

<script>
const hotelLat = <?= $hotel['lat'] ?? 'null' ?>;
const hotelLng = <?= $hotel['lng'] ?? 'null' ?>;
const hotelName = <?= json_encode($hotel['name']) ?>;
const hotelPrice = <?= $hotel['price'] ?>;

// Star picker
document.querySelectorAll('.star-pick').forEach(function(s){
  s.addEventListener('mouseover',function(){
    var v=parseInt(this.dataset.val);
    document.querySelectorAll('.star-pick').forEach(function(x,i){x.classList.toggle('active',i<v);});
  });
  s.addEventListener('click',function(){
    document.getElementById('ratingInput').value=this.dataset.val;
  });
});

// Price calculator
var pricePerNight = <?= $hotel['price'] ?>;
function calcPrice(){
  var ci=document.getElementById('bookCheckIn').value;
  var co=document.getElementById('bookCheckOut').value;
  if(!ci||!co)return;
  var nights=Math.round((new Date(co)-new Date(ci))/(864e5));
  if(nights<1)return;
  var rooms=parseInt(document.querySelector('[name=rooms]').value)||1;
  var sub=nights*rooms*pricePerNight;
  var tax=Math.round(sub*.12);
  document.getElementById('nightsLabel').textContent=nights+' night'+(nights>1?'s':'')+' x '+rooms+' room'+(rooms>1?'s':'');
  document.getElementById('nightsPrice').textContent='$'+sub.toLocaleString();
  document.getElementById('taxPrice').textContent='$'+tax.toLocaleString();
  document.getElementById('totalPrice').textContent='$'+(sub+tax).toLocaleString();
  document.getElementById('priceSummary').style.display='block';
}
document.getElementById('bookCheckIn').addEventListener('change',calcPrice);
document.getElementById('bookCheckOut').addEventListener('change',calcPrice);
document.querySelector('[name=rooms]').addEventListener('change',calcPrice);
</script>
<script src="<?= APP_URL ?>/assets/js/booking.js"></script>
<script src="<?= APP_URL ?>/assets/js/map.js"></script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>