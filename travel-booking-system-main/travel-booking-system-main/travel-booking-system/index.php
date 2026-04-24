<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/backend/helpers/functions.php';
require_once __DIR__ . '/backend/models/Hotel.php';

$pageTitle = 'TravelLux — Find Your Perfect Dream Hotel';
$pageDesc  = 'Discover luxury hotels worldwide. Best prices, stunning destinations, unforgettable experiences with TravelLux.';

$hotelModel = new Hotel($pdo);
$featured   = $hotelModel->getFeatured(6);

require_once __DIR__ . '/includes/header.php';
?>
<style>
/* ===== RESET & BASE ===== */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{background:#0f0f0f;color:#f0f0ff;font-family:'Inter',sans-serif;overflow-x:hidden}
a{text-decoration:none;color:inherit}
img{display:block;max-width:100%}
.container{max-width:1280px;margin:0 auto;padding:0 24px}

/* ===== HERO ===== */
.hero{position:relative;height:100vh;min-height:680px;display:flex;align-items:center;justify-content:center;overflow:hidden}
.hero-bg{position:absolute;inset:0;background:url('https://images.unsplash.com/photo-1573843981267-be1999ff37cd?w=1920&q=90') center/cover no-repeat;z-index:0}
.hero-overlay{position:absolute;inset:0;background:linear-gradient(135deg,rgba(0,0,0,.72) 0%,rgba(60,0,80,.45) 60%,rgba(0,0,0,.65) 100%);z-index:1}
.hero-content{position:relative;z-index:2;text-align:center;padding:0 20px;max-width:860px}
.hero-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);backdrop-filter:blur(12px);padding:8px 20px;border-radius:50px;font-size:.85rem;font-weight:500;color:#d4d4ff;margin-bottom:28px}
.hero-badge span{width:8px;height:8px;background:#22c55e;border-radius:50%;box-shadow:0 0 8px #22c55e;animation:pulse-dot 2s infinite}
@keyframes pulse-dot{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.6;transform:scale(1.3)}}
.hero h1{font-family:'Poppins',sans-serif;font-size:clamp(2.6rem,6vw,5rem);font-weight:900;line-height:1.1;color:#fff;margin-bottom:20px}
.hero h1 .gradient-text{background:linear-gradient(135deg,#a855f7,#ec4899,#f97316);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;background-size:200% 200%;animation:grad-shift 4s ease infinite}
@keyframes grad-shift{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
.hero-sub{font-size:1.15rem;color:rgba(255,255,255,.7);margin-bottom:40px;max-width:560px;margin-left:auto;margin-right:auto;line-height:1.7}

/* ===== SEARCH BAR ===== */
.search-bar{background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.14);backdrop-filter:blur(20px);border-radius:20px;padding:20px 24px;display:flex;flex-wrap:wrap;gap:12px;align-items:center;margin-bottom:28px}
.search-field{flex:1;min-width:160px;display:flex;flex-direction:column;gap:4px}
.search-field label{font-size:.72rem;font-weight:600;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.08em}
.search-field input{background:transparent;border:none;outline:none;color:#fff;font-size:.95rem;font-family:'Inter',sans-serif;padding:4px 0}
.search-field input::placeholder{color:rgba(255,255,255,.35)}
.search-divider{width:1px;height:44px;background:rgba(255,255,255,.12);align-self:center}
.search-btn{background:linear-gradient(135deg,#7c3aed,#ec4899);border:none;color:#fff;font-size:.95rem;font-weight:700;padding:14px 32px;border-radius:14px;cursor:pointer;white-space:nowrap;transition:transform .2s,box-shadow .2s;font-family:'Inter',sans-serif}
.search-btn:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(124,58,237,.5)}

/* ===== POPULAR PILLS ===== */
.popular-pills{display:flex;flex-wrap:wrap;gap:10px;justify-content:center;margin-bottom:44px}
.popular-pills span{font-size:.8rem;color:rgba(255,255,255,.5);align-self:center}
.pill{background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.8);padding:7px 18px;border-radius:50px;font-size:.85rem;cursor:pointer;transition:all .2s}
.pill:hover{background:rgba(124,58,237,.3);border-color:#7c3aed;color:#fff}

/* ===== HERO STATS ===== */
.hero-stats{display:flex;flex-wrap:wrap;gap:0;justify-content:center;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);backdrop-filter:blur(16px);border-radius:16px;overflow:hidden}
.stat-item{flex:1;min-width:130px;padding:18px 24px;text-align:center;border-right:1px solid rgba(255,255,255,.08)}
.stat-item:last-child{border-right:none}
.stat-num{font-family:'Poppins',sans-serif;font-size:1.5rem;font-weight:800;background:linear-gradient(135deg,#a855f7,#ec4899);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.stat-label{font-size:.75rem;color:rgba(255,255,255,.5);margin-top:2px}

/* ===== SECTIONS ===== */
.section{padding:96px 0}
.section-tag{display:inline-block;background:rgba(124,58,237,.15);border:1px solid rgba(124,58,237,.3);color:#a855f7;font-size:.78rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:6px 16px;border-radius:50px;margin-bottom:16px}
.section-title{font-family:'Poppins',sans-serif;font-size:clamp(1.8rem,3.5vw,2.8rem);font-weight:800;color:#fff;margin-bottom:12px}
.section-sub{color:rgba(255,255,255,.5);font-size:1rem;max-width:520px;line-height:1.7}
.section-header{margin-bottom:56px}

/* ===== DESTINATIONS ===== */
.dest-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:16px}
.dest-card{position:relative;height:220px;border-radius:20px;overflow:hidden;cursor:pointer;transition:transform .35s,box-shadow .35s}
.dest-card:hover{transform:translateY(-8px);box-shadow:0 24px 60px rgba(0,0,0,.6)}
.dest-card img{width:100%;height:100%;object-fit:cover;transition:transform .5s}
.dest-card:hover img{transform:scale(1.1)}
.dest-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.75) 0%,transparent 55%)}
.dest-info{position:absolute;bottom:16px;left:16px;right:16px}
.dest-flag{font-size:1.4rem;margin-bottom:4px}
.dest-name{font-family:'Poppins',sans-serif;font-size:1.05rem;font-weight:700;color:#fff}
.dest-count{font-size:.78rem;color:rgba(255,255,255,.65);margin-top:2px}

/* ===== FEATURED HOTELS ===== */
.hotels-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
.hotel-card{background:#1a1a1a;border:1px solid rgba(255,255,255,.08);border-radius:20px;overflow:hidden;transition:transform .3s,box-shadow .3s}
.hotel-card:hover{transform:translateY(-6px);box-shadow:0 20px 50px rgba(0,0,0,.5)}
.hotel-img-wrap{position:relative;height:210px;overflow:hidden}
.hotel-img-wrap img{width:100%;height:100%;object-fit:cover;transition:transform .5s}
.hotel-card:hover .hotel-img-wrap img{transform:scale(1.07)}
.hotel-badge{position:absolute;top:14px;left:14px;background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff;font-size:.72rem;font-weight:700;padding:4px 12px;border-radius:50px}
.hotel-fav{position:absolute;top:14px;right:14px;width:34px;height:34px;background:rgba(0,0,0,.45);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.15);border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:1rem;transition:background .2s}
.hotel-fav:hover{background:rgba(236,72,153,.3)}
.hotel-body{padding:20px}
.hotel-location{font-size:.78rem;color:rgba(255,255,255,.45);margin-bottom:6px;display:flex;align-items:center;gap:4px}
.hotel-name{font-family:'Poppins',sans-serif;font-size:1.05rem;font-weight:700;color:#fff;margin-bottom:8px;line-height:1.3}
.hotel-stars{color:#f59e0b;font-size:.85rem;margin-bottom:10px}
.hotel-tags{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px}
.hotel-tag{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.6);font-size:.72rem;padding:3px 10px;border-radius:50px}
.hotel-footer{display:flex;align-items:center;justify-content:space-between;padding-top:14px;border-top:1px solid rgba(255,255,255,.07)}
.hotel-price{font-family:'Poppins',sans-serif}
.hotel-price .amount{font-size:1.3rem;font-weight:800;color:#a855f7}
.hotel-price .per{font-size:.75rem;color:rgba(255,255,255,.4)}
.btn-book{background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff;border:none;padding:9px 20px;border-radius:10px;font-size:.85rem;font-weight:600;cursor:pointer;transition:transform .2s,box-shadow .2s;font-family:'Inter',sans-serif}
.btn-book:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(124,58,237,.45)}
.no-hotels{text-align:center;padding:60px 20px;color:rgba(255,255,255,.4);font-size:1rem}

/* ===== HOW IT WORKS ===== */
.how-bg{background:#111}
.steps-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
.step-card{background:#1a1a1a;border:1px solid rgba(255,255,255,.08);border-radius:20px;padding:36px 28px;text-align:center;transition:transform .3s,border-color .3s}
.step-card:hover{transform:translateY(-6px);border-color:rgba(124,58,237,.4)}
.step-num{width:60px;height:60px;background:linear-gradient(135deg,#7c3aed,#ec4899);border-radius:16px;display:flex;align-items:center;justify-content:center;font-family:'Poppins',sans-serif;font-size:1.4rem;font-weight:900;color:#fff;margin:0 auto 20px}
.step-icon{font-size:1.8rem;margin-bottom:12px}
.step-title{font-family:'Poppins',sans-serif;font-size:1.1rem;font-weight:700;color:#fff;margin-bottom:10px}
.step-desc{font-size:.9rem;color:rgba(255,255,255,.5);line-height:1.7}

/* ===== TESTIMONIALS ===== */
.testi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
.testi-card{background:#1a1a1a;border:1px solid rgba(255,255,255,.08);border-radius:20px;padding:28px;transition:transform .3s,border-color .3s}
.testi-card:hover{transform:translateY(-4px);border-color:rgba(124,58,237,.3)}
.testi-stars{color:#f59e0b;font-size:.9rem;margin-bottom:14px}
.testi-text{font-size:.92rem;color:rgba(255,255,255,.7);line-height:1.75;margin-bottom:20px;font-style:italic}
.testi-author{display:flex;align-items:center;gap:12px}
.testi-avatar{width:44px;height:44px;border-radius:50%;object-fit:cover;border:2px solid rgba(124,58,237,.4)}
.testi-name{font-weight:600;color:#fff;font-size:.9rem}
.testi-trip{font-size:.78rem;color:rgba(255,255,255,.4)}

/* ===== CTA BANNER ===== */
.cta-section{padding:96px 0}
.cta-inner{background:linear-gradient(135deg,#7c3aed,#ec4899,#06b6d4);background-size:300% 300%;animation:grad-shift 6s ease infinite;border-radius:28px;padding:72px 48px;text-align:center;position:relative;overflow:hidden}
.cta-inner::before{content:'';position:absolute;inset:0;background:rgba(0,0,0,.15)}
.cta-inner>*{position:relative;z-index:1}
.cta-title{font-family:'Poppins',sans-serif;font-size:clamp(1.8rem,3.5vw,2.8rem);font-weight:900;color:#fff;margin-bottom:14px}
.cta-sub{color:rgba(255,255,255,.8);font-size:1.05rem;margin-bottom:36px;max-width:500px;margin-left:auto;margin-right:auto}
.cta-btns{display:flex;gap:16px;justify-content:center;flex-wrap:wrap}
.btn-white{background:#fff;color:#7c3aed;font-weight:700;padding:14px 32px;border-radius:14px;border:none;cursor:pointer;font-size:.95rem;font-family:'Inter',sans-serif;transition:transform .2s,box-shadow .2s}
.btn-white:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,.3)}
.btn-outline-white{background:transparent;color:#fff;font-weight:700;padding:14px 32px;border-radius:14px;border:2px solid rgba(255,255,255,.6);cursor:pointer;font-size:.95rem;font-family:'Inter',sans-serif;transition:all .2s}
.btn-outline-white:hover{background:rgba(255,255,255,.15);border-color:#fff}

/* ===== NEWSLETTER ===== */
.newsletter{background:#111;padding:80px 0}
.newsletter-inner{max-width:560px;margin:0 auto;text-align:center}
.newsletter-title{font-family:'Poppins',sans-serif;font-size:1.8rem;font-weight:800;color:#fff;margin-bottom:10px}
.newsletter-sub{color:rgba(255,255,255,.5);margin-bottom:28px;font-size:.95rem}
.newsletter-form{display:flex;gap:12px;flex-wrap:wrap;justify-content:center}
.newsletter-input{flex:1;min-width:220px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);color:#fff;padding:14px 20px;border-radius:14px;font-size:.95rem;font-family:'Inter',sans-serif;outline:none;transition:border-color .2s}
.newsletter-input::placeholder{color:rgba(255,255,255,.35)}
.newsletter-input:focus{border-color:#7c3aed}
.btn-subscribe{background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff;border:none;padding:14px 28px;border-radius:14px;font-weight:700;font-size:.95rem;cursor:pointer;font-family:'Inter',sans-serif;transition:transform .2s,box-shadow .2s;white-space:nowrap}
.btn-subscribe:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(124,58,237,.45)}

/* ===== RESPONSIVE ===== */
@media(max-width:1024px){.dest-grid{grid-template-columns:repeat(3,1fr)}.hotels-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:768px){.dest-grid{grid-template-columns:repeat(2,1fr)}.hotels-grid{grid-template-columns:1fr}.steps-grid{grid-template-columns:1fr}.testi-grid{grid-template-columns:1fr}.search-bar{flex-direction:column}.search-divider{display:none}.hero-stats{flex-direction:column}.stat-item{border-right:none;border-bottom:1px solid rgba(255,255,255,.08)}.stat-item:last-child{border-bottom:none}.cta-inner{padding:48px 24px}}
@media(max-width:480px){.dest-grid{grid-template-columns:1fr 1fr}}
</style>
<!-- ===== HERO ===== -->
<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <div class="hero-badge" data-aos="fade-down">
      <span></span> ✈ Trusted by 50,000+ Travelers
    </div>
    <h1 data-aos="fade-up" data-aos-delay="100">
      Find Your Perfect<br>
      <span class="gradient-text">Dream Hotel</span>
    </h1>
    <p class="hero-sub" data-aos="fade-up" data-aos-delay="200">
      Discover handpicked luxury hotels across the world's most breathtaking destinations. Unbeatable prices, unforgettable experiences.
    </p>

    <!-- Search Bar -->
    <form class="search-bar" action="/travel-booking-system/pages/search.php" method="GET" data-aos="fade-up" data-aos-delay="300">
      <div class="search-field">
        <label>Destination</label>
        <input type="text" name="city" placeholder="Where are you going?">
      </div>
      <div class="search-divider"></div>
      <div class="search-field">
        <label>Check-in</label>
        <input type="date" name="checkin">
      </div>
      <div class="search-divider"></div>
      <div class="search-field">
        <label>Check-out</label>
        <input type="date" name="checkout">
      </div>
      <div class="search-divider"></div>
      <div class="search-field">
        <label>Guests</label>
        <input type="number" name="guests" placeholder="2 guests" min="1" max="20">
      </div>
      <button type="submit" class="search-btn">🔍 Search</button>
    </form>

    <!-- Popular Pills -->
    <div class="popular-pills" data-aos="fade-up" data-aos-delay="400">
      <span>Popular:</span>
      <a href="/travel-booking-system/pages/search.php?city=Paris" class="pill">🗼 Paris</a>
      <a href="/travel-booking-system/pages/search.php?city=Bali" class="pill">🌴 Bali</a>
      <a href="/travel-booking-system/pages/search.php?city=Tokyo" class="pill">⛩ Tokyo</a>
      <a href="/travel-booking-system/pages/search.php?city=Maldives" class="pill">🏝 Maldives</a>
      <a href="/travel-booking-system/pages/search.php?city=Dubai" class="pill">🏙 Dubai</a>
      <a href="/travel-booking-system/pages/search.php?city=Santorini" class="pill">🌅 Santorini</a>
    </div>

    <!-- Stats -->
    <div class="hero-stats" data-aos="fade-up" data-aos-delay="500">
      <div class="stat-item">
        <div class="stat-num">500+</div>
        <div class="stat-label">Destinations</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">50K+</div>
        <div class="stat-label">Travelers</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">1,200+</div>
        <div class="stat-label">Hotels</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">98%</div>
        <div class="stat-label">Satisfaction</div>
      </div>
    </div>
  </div>
</section>
<!-- ===== DESTINATIONS ===== -->
<section class="section">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <span class="section-tag">✈ Explore</span>
      <h2 class="section-title">Top Destinations</h2>
      <p class="section-sub">From ancient temples to pristine beaches — find your perfect escape.</p>
    </div>
    <div class="dest-grid">
      <a href="/travel-booking-system/pages/search.php?city=Paris" class="dest-card" data-aos="fade-up" data-aos-delay="0">
        <img src="https://images.unsplash.com/photo-1499856871958-5b9627545d1a?w=600&q=80" alt="Paris" loading="lazy">
        <div class="dest-overlay"></div>
        <div class="dest-info">
          <div class="dest-flag">🇫🇷</div>
          <div class="dest-name">Paris</div>
          <div class="dest-count">142 hotels</div>
        </div>
      </a>
      <a href="/travel-booking-system/pages/search.php?city=Bali" class="dest-card" data-aos="fade-up" data-aos-delay="80">
        <img src="https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=600&q=80" alt="Bali" loading="lazy">
        <div class="dest-overlay"></div>
        <div class="dest-info">
          <div class="dest-flag">🇮🇩</div>
          <div class="dest-name">Bali</div>
          <div class="dest-count">98 hotels</div>
        </div>
      </a>
      <a href="/travel-booking-system/pages/search.php?city=Tokyo" class="dest-card" data-aos="fade-up" data-aos-delay="160">
        <img src="https://images.unsplash.com/photo-1536098561742-ca998e48cbcc?w=600&q=80" alt="Tokyo" loading="lazy">
        <div class="dest-overlay"></div>
        <div class="dest-info">
          <div class="dest-flag">🇯🇵</div>
          <div class="dest-name">Tokyo</div>
          <div class="dest-count">117 hotels</div>
        </div>
      </a>
      <a href="/travel-booking-system/pages/search.php?city=Santorini" class="dest-card" data-aos="fade-up" data-aos-delay="240">
        <img src="https://images.unsplash.com/photo-1613395877344-13d4a8e0d49e?w=600&q=80" alt="Santorini" loading="lazy">
        <div class="dest-overlay"></div>
        <div class="dest-info">
          <div class="dest-flag">🇬🇷</div>
          <div class="dest-name">Santorini</div>
          <div class="dest-count">64 hotels</div>
        </div>
      </a>
      <a href="/travel-booking-system/pages/search.php?city=Maldives" class="dest-card" data-aos="fade-up" data-aos-delay="320">
        <img src="https://images.unsplash.com/photo-1514282401047-d79a71a590e8?w=600&q=80" alt="Maldives" loading="lazy">
        <div class="dest-overlay"></div>
        <div class="dest-info">
          <div class="dest-flag">🇲🇻</div>
          <div class="dest-name">Maldives</div>
          <div class="dest-count">53 hotels</div>
        </div>
      </a>
    </div>
  </div>
</section>
<!-- ===== FEATURED HOTELS ===== -->
<section class="section" style="background:#111;">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <span class="section-tag">⭐ Handpicked</span>
      <h2 class="section-title">Featured Hotels</h2>
      <p class="section-sub">Our curated selection of the world's finest stays — chosen for quality, comfort, and unforgettable experiences.</p>
    </div>
    <div class="hotels-grid"><?php if (!empty($featured)): foreach ($featured as $i => $hotel):
    $amenities = !empty($hotel['amenities']) ? array_slice(explode(',', $hotel['amenities']), 0, 3) : ['WiFi', 'Pool', 'Spa'];
    $stars = str_repeat('★', min(5, (int)($hotel['stars'] ?? 4))) . str_repeat('☆', max(0, 5 - (int)($hotel['stars'] ?? 4)));
    $img = !empty($hotel['image']) ? htmlspecialchars($hotel['image']) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80';
    $delay = $i * 100;
?>
      <div class="hotel-card" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
        <div class="hotel-img-wrap">
          <img src="<?= $img ?>" alt="<?= htmlspecialchars($hotel['name']) ?>" loading="lazy">
          <div class="hotel-badge">⭐ Featured</div>
          <div class="hotel-fav" title="Save to wishlist">♡</div>
        </div>
        <div class="hotel-body">
          <div class="hotel-location">📍 <?= htmlspecialchars($hotel['city'] ?? $hotel['location'] ?? 'Unknown') ?></div>
          <div class="hotel-name"><?= htmlspecialchars($hotel['name']) ?></div>
          <div class="hotel-stars"><?= $stars ?> <span style="color:rgba(255,255,255,.4);font-size:.78rem;margin-left:4px;">(<?= number_format((float)($hotel['rating'] ?? 4.5), 1) ?>)</span></div>
          <div class="hotel-tags">
            <?php foreach ($amenities as $tag): ?>
              <span class="hotel-tag"><?= htmlspecialchars(trim($tag)) ?></span>
            <?php endforeach; ?>
          </div>
          <div class="hotel-footer">
            <div class="hotel-price">
              <span class="amount">$<?= number_format((float)($hotel['price'] ?? 199)) ?></span>
              <span class="per"> / night</span>
            </div>
            <a href="/travel-booking-system/pages/hotel-details.php?id=<?= (int)$hotel['id'] ?>">
              <button class="btn-book">Book Now</button>
            </a>
          </div>
        </div>
      </div>
<?php endforeach; else: ?>
      <div class="no-hotels" style="grid-column:1/-1;">
        <p style="font-size:2rem;margin-bottom:12px;">🏨</p>
        <p>No featured hotels yet. Check back soon!</p>
        <a href="/travel-booking-system/pages/search.php" style="color:#a855f7;margin-top:12px;display:inline-block;">Browse all hotels →</a>
      </div>
<?php endif; ?>    </div>
    <div style="text-align:center;margin-top:48px;" data-aos="fade-up">
      <a href="/travel-booking-system/pages/search.php" style="display:inline-flex;align-items:center;gap:8px;background:rgba(124,58,237,.15);border:1px solid rgba(124,58,237,.4);color:#a855f7;padding:14px 32px;border-radius:14px;font-weight:600;transition:all .2s;" onmouseover="this.style.background='rgba(124,58,237,.3)'" onmouseout="this.style.background='rgba(124,58,237,.15)'">
        View All Hotels →
      </a>
    </div>
  </div>
</section>
<!-- ===== HOW IT WORKS ===== -->
<section class="section how-bg">
  <div class="container">
    <div class="section-header" style="text-align:center;" data-aos="fade-up">
      <span class="section-tag">🗺 Simple Process</span>
      <h2 class="section-title">How It Works</h2>
      <p class="section-sub" style="margin:0 auto;">Book your dream hotel in three easy steps.</p>
    </div>
    <div class="steps-grid">
      <div class="step-card" data-aos="fade-up" data-aos-delay="0">
        <div class="step-num">1</div>
        <div class="step-icon">🔍</div>
        <div class="step-title">Search</div>
        <p class="step-desc">Enter your destination, travel dates, and number of guests. Our smart search finds the best options instantly.</p>
      </div>
      <div class="step-card" data-aos="fade-up" data-aos-delay="150">
        <div class="step-num">2</div>
        <div class="step-icon">🏨</div>
        <div class="step-title">Choose</div>
        <p class="step-desc">Browse curated hotels with real photos, verified reviews, and transparent pricing. Filter by stars, amenities, and budget.</p>
      </div>
      <div class="step-card" data-aos="fade-up" data-aos-delay="300">
        <div class="step-num">3</div>
        <div class="step-icon">✅</div>
        <div class="step-title">Book</div>
        <p class="step-desc">Secure your reservation with our encrypted checkout. Instant confirmation sent to your email. No hidden fees.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===== TESTIMONIALS ===== -->
<section class="section">
  <div class="container">
    <div class="section-header" style="text-align:center;" data-aos="fade-up">
      <span class="section-tag">💬 Reviews</span>
      <h2 class="section-title">What Travelers Say</h2>
      <p class="section-sub" style="margin:0 auto;">Real experiences from real travelers who booked with TravelLux.</p>
    </div>
    <div class="testi-grid">
      <div class="testi-card" data-aos="fade-up" data-aos-delay="0">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"Absolutely seamless experience from search to check-in. Found an incredible overwater villa in the Maldives at a price I couldn't believe. TravelLux is now my go-to for every trip."</p>
        <div class="testi-author">
          <img class="testi-avatar" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&q=80" alt="Sarah M.">
          <div>
            <div class="testi-name">Sarah Mitchell</div>
            <div class="testi-trip">Maldives · Ocean Villa</div>
          </div>
        </div>
      </div>
      <div class="testi-card" data-aos="fade-up" data-aos-delay="150">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"Booked a last-minute stay in Tokyo and the whole process took under 5 minutes. The hotel was exactly as described — stunning views, impeccable service. Highly recommend!"</p>
        <div class="testi-author">
          <img class="testi-avatar" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&q=80" alt="James K.">
          <div>
            <div class="testi-name">James Kowalski</div>
            <div class="testi-trip">Tokyo · Luxury Suite</div>
          </div>
        </div>
      </div>
      <div class="testi-card" data-aos="fade-up" data-aos-delay="300">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"The Santorini boutique hotel I found here was a hidden gem. Breathtaking caldera views, private pool, and the booking was instant. TravelLux truly delivers on its promise."</p>
        <div class="testi-author">
          <img class="testi-avatar" src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&q=80" alt="Elena R.">
          <div>
            <div class="testi-name">Elena Rossi</div>
            <div class="testi-trip">Santorini · Caldera View</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== CTA BANNER ===== -->
<section class="cta-section">
  <div class="container">
    <div class="cta-inner" data-aos="zoom-in">
      <h2 class="cta-title">Ready for Your Next Adventure?</h2>
      <p class="cta-sub">Join over 50,000 travelers who trust TravelLux for their perfect getaway. Start exploring today.</p>
      <div class="cta-btns">
        <a href="/travel-booking-system/pages/search.php"><button class="btn-white">🔍 Explore Hotels</button></a>
        <a href="/travel-booking-system/auth/register.php"><button class="btn-outline-white">✨ Create Free Account</button></a>
      </div>
    </div>
  </div>
</section>

<!-- ===== NEWSLETTER ===== -->
<section class="newsletter">
  <div class="container">
    <div class="newsletter-inner" data-aos="fade-up">
      <h3 class="newsletter-title">Stay in the Loop</h3>
      <p class="newsletter-sub">Get exclusive deals, travel inspiration, and early access to new destinations — straight to your inbox.</p>
      <form class="newsletter-form" onsubmit="event.preventDefault();this.innerHTML='<p style=\'color:#22c55e;font-weight:600;\'>✅ You\'re subscribed! Check your inbox.</p>'">
        <input type="email" class="newsletter-input" placeholder="Enter your email address" required>
        <button type="submit" class="btn-subscribe">Subscribe →</button>
      </form>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>