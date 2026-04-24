<?php
require_once __DIR__ . '/../config/app.php';
$pageTitle = 'Travel Packages — TravelLux';
$pkgs = [
  ['id'=>1,'name'=>'Maldives Paradise','cat'=>'beach','img'=>'https://images.unsplash.com/photo-1514282401047-d79a71a590e8?w=600&q=80','price'=>2499,'orig'=>3200,'days'=>7,'nights'=>6,'rating'=>4.9,'reviews'=>312,'badge'=>'Best Seller','inc'=>['5-Star Resort','All Meals','Snorkeling','Airport Transfer','Spa Access']],
  ['id'=>2,'name'=>'Paris Romance','cat'=>'romantic','img'=>'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?w=600&q=80','price'=>1899,'orig'=>2400,'days'=>5,'nights'=>4,'rating'=>4.8,'reviews'=>245,'badge'=>'Hot Deal','inc'=>['Luxury Hotel','Breakfast','Seine Cruise','Eiffel Tour','Wine Tasting']],
  ['id'=>3,'name'=>'Bali Adventure','cat'=>'adventure','img'=>'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=600&q=80','price'=>1299,'orig'=>1700,'days'=>8,'nights'=>7,'rating'=>4.7,'reviews'=>189,'badge'=>'Popular','inc'=>['Villa Stay','Breakfast','Volcano Trek','Rafting','Temple Tour']],
  ['id'=>4,'name'=>'Dubai Luxury','cat'=>'luxury','img'=>'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=600&q=80','price'=>3299,'orig'=>4100,'days'=>6,'nights'=>5,'rating'=>4.9,'reviews'=>278,'badge'=>'Premium','inc'=>['7-Star Hotel','All Meals','Desert Safari','Burj Khalifa','Yacht Cruise']],
  ['id'=>5,'name'=>'Santorini Escape','cat'=>'romantic','img'=>'https://images.unsplash.com/photo-1570077188670-e3a8d69ac5ff?w=600&q=80','price'=>2199,'orig'=>2800,'days'=>6,'nights'=>5,'rating'=>4.8,'reviews'=>203,'badge'=>'Trending','inc'=>['Cave Hotel','Breakfast','Sunset Cruise','Wine Tour','Photo Session']],
  ['id'=>6,'name'=>'Thailand Explorer','cat'=>'adventure','img'=>'https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=600&q=80','price'=>999,'orig'=>1400,'days'=>10,'nights'=>9,'rating'=>4.6,'reviews'=>156,'badge'=>'Value Pick','inc'=>['Boutique Hotel','Breakfast','Island Hopping','Elephant Sanctuary','Cooking Class']],
];
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?= $pageTitle ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
<script>(function(){var t=localStorage.getItem("travellux_theme")||"dark";document.documentElement.setAttribute("data-theme",t);})();</script>
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--bg:#0a0a0f;--bg2:#12121a;--card:#1a1a2e;--border:rgba(255,255,255,0.08);--text:#f0f0ff;--muted:rgba(240,240,255,0.55);--accent:#7c3aed;--accent2:#ec4899;--accent3:#06b6d4;--gold:#f59e0b;--green:#10b981;--radius:16px}
[data-theme=light]{--bg:#f0f4ff;--bg2:#e8eeff;--card:#fff;--border:rgba(0,0,0,0.08);--text:#1a1a2e;--muted:rgba(26,26,46,0.55)}
body{background:var(--bg);color:var(--text);font-family:"Inter",sans-serif;min-height:100vh;overflow-x:hidden}
a{color:inherit;text-decoration:none}
.nav{position:fixed;top:0;left:0;right:0;z-index:1000;padding:16px 40px;display:flex;align-items:center;justify-content:space-between;background:rgba(10,10,15,0.85);backdrop-filter:blur(20px);border-bottom:1px solid var(--border)}
.nav-logo{font-family:"Poppins",sans-serif;font-size:1.5rem;font-weight:800;background:linear-gradient(135deg,#7c3aed,#ec4899);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.nav-links{display:flex;gap:32px;align-items:center}
.nav-links a{color:var(--muted);font-size:.9rem;font-weight:500;transition:.2s}
.nav-links a:hover,.nav-links a.active{color:var(--text)}
.nav-cta{background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff!important;padding:10px 24px;border-radius:50px;font-weight:600!important}
.hero{min-height:70vh;display:flex;align-items:center;justify-content:center;text-align:center;position:relative;overflow:hidden;padding:120px 20px 60px}
.hero-bg{position:absolute;inset:0;background:url("https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=1600&q=80") center/cover no-repeat;filter:brightness(.35)}
.hero-overlay{position:absolute;inset:0;background:linear-gradient(135deg,rgba(124,58,237,.6),rgba(236,72,153,.4),rgba(6,182,212,.3))}
.hero-content{position:relative;z-index:2;max-width:800px}
.hero-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);padding:8px 20px;border-radius:50px;font-size:.85rem;font-weight:600;margin-bottom:24px;backdrop-filter:blur(10px)}
.hero h1{font-family:"Poppins",sans-serif;font-size:clamp(2.5rem,6vw,4.5rem);font-weight:900;line-height:1.1;margin-bottom:20px}
.hero h1 span{background:linear-gradient(135deg,#f59e0b,#ec4899,#7c3aed);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.hero p{font-size:1.15rem;color:rgba(255,255,255,.8);max-width:560px;margin:0 auto 40px}
.hero-stats{display:flex;gap:40px;justify-content:center;flex-wrap:wrap}
.hero-stat strong{display:block;font-family:"Poppins",sans-serif;font-size:2rem;font-weight:800;background:linear-gradient(135deg,#f59e0b,#ec4899);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.hero-stat span{font-size:.8rem;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:1px}
</style>
</head><body>
<nav class="nav">
  <a href="../index.php" class="nav-logo">&#9992; TravelLux</a>
  <div class="nav-links">
    <a href="../index.php">Home</a>
    <a href="search.php">Hotels</a>
    <a href="packages.php" class="active">Packages</a>
    <a href="blog.php">Blog</a>
    <a href="contact.php">Contact</a>
    <a href="../auth/login.php" class="nav-cta">Book Now</a>
  </div>
</nav>
<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <div class="hero-badge">&#127775; Limited Time Offers &mdash; Up to 35% Off</div>
    <h1>Curated <span>Dream Packages</span><br>For Every Traveler</h1>
    <p>Hand-picked luxury experiences combining flights, hotels, tours and more &mdash; all in one seamless package.</p>
    <div class="hero-stats">
      <div class="hero-stat"><strong>500+</strong><span>Packages</span></div>
      <div class="hero-stat"><strong>80+</strong><span>Destinations</span></div>
      <div class="hero-stat"><strong>50K+</strong><span>Happy Travelers</span></div>
      <div class="hero-stat"><strong>4.9&#9733;</strong><span>Avg Rating</span></div>
    </div>
  </div>
</section>
<style>
.countdown-bar{background:linear-gradient(135deg,#7c3aed,#ec4899,#06b6d4);padding:18px 40px;display:flex;align-items:center;justify-content:center;gap:24px;flex-wrap:wrap}
.countdown-bar p{font-weight:700;font-size:1rem;color:#fff}
.cd-units{display:flex;gap:12px}
.cd-unit{background:rgba(0,0,0,.3);border-radius:10px;padding:8px 16px;text-align:center;min-width:64px}
.cd-unit strong{display:block;font-size:1.6rem;font-weight:800;color:#fff;font-family:"Poppins",sans-serif}
.cd-unit span{font-size:.65rem;color:rgba(255,255,255,.7);text-transform:uppercase;letter-spacing:1px}
.filters-section{padding:60px 40px 20px;max-width:1400px;margin:0 auto}
.filters-section h2{font-family:"Poppins",sans-serif;font-size:2rem;font-weight:800;margin-bottom:8px}
.filters-section p{color:var(--muted);margin-bottom:32px}
.filter-btns{display:flex;gap:12px;flex-wrap:wrap}
.filter-btn{padding:10px 28px;border-radius:50px;border:2px solid var(--border);background:var(--card);color:var(--muted);font-weight:600;font-size:.9rem;cursor:pointer;transition:.25s}
.filter-btn:hover{border-color:var(--accent);color:var(--accent)}
.filter-btn.active{background:linear-gradient(135deg,#7c3aed,#ec4899);border-color:transparent;color:#fff}
.packages-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(360px,1fr));gap:28px;padding:32px 40px 60px;max-width:1400px;margin:0 auto}
.pkg-card{background:var(--card);border:1px solid var(--border);border-radius:16px;overflow:hidden;transition:.35s;position:relative}
.pkg-card:hover{transform:translateY(-8px);box-shadow:0 24px 60px rgba(124,58,237,.25);border-color:rgba(124,58,237,.4)}
.pkg-card.hidden{display:none}
.pkg-img{position:relative;height:220px;overflow:hidden}
.pkg-img img{width:100%;height:100%;object-fit:cover;transition:.5s}
.pkg-card:hover .pkg-img img{transform:scale(1.08)}
.pkg-badge{position:absolute;top:14px;left:14px;background:linear-gradient(135deg,#f59e0b,#ef4444);color:#fff;font-size:.72rem;font-weight:700;padding:5px 14px;border-radius:50px;text-transform:uppercase;letter-spacing:.5px}
.pkg-cat{position:absolute;top:14px;right:14px;background:rgba(0,0,0,.6);backdrop-filter:blur(8px);color:#fff;font-size:.72rem;font-weight:600;padding:5px 12px;border-radius:50px;text-transform:capitalize}
.pkg-body{padding:24px}
.pkg-rating{display:flex;align-items:center;gap:6px;margin-bottom:10px}
.stars{color:#f59e0b;font-size:.9rem}
.pkg-rating span{font-size:.8rem;color:var(--muted)}
.pkg-body h3{font-family:"Poppins",sans-serif;font-size:1.25rem;font-weight:700;margin-bottom:8px}
.pkg-meta{display:flex;gap:16px;margin-bottom:16px}
.pkg-meta span{font-size:.82rem;color:var(--muted)}
.pkg-includes{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:20px}
.inc-tag{background:rgba(124,58,237,.12);border:1px solid rgba(124,58,237,.2);color:#a78bfa;font-size:.72rem;font-weight:600;padding:4px 10px;border-radius:50px}
.pkg-footer{display:flex;align-items:center;justify-content:space-between;padding-top:16px;border-top:1px solid var(--border)}
.pkg-price .orig{font-size:.8rem;color:var(--muted);text-decoration:line-through;display:block}
.pkg-price .curr{font-family:"Poppins",sans-serif;font-size:1.6rem;font-weight:800;background:linear-gradient(135deg,#7c3aed,#ec4899);-webkit-background-clip:text;-webkit-text-fill-color:transparent;display:block}
.pkg-price .per{font-size:.75rem;color:var(--muted);display:block}
.pkg-price .save{font-size:.75rem;color:#10b981;font-weight:600;display:block}
.btn-book{background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff;border:none;padding:12px 24px;border-radius:50px;font-weight:700;font-size:.9rem;cursor:pointer;transition:.25s;white-space:nowrap}
.btn-book:hover{transform:scale(1.05);box-shadow:0 8px 24px rgba(124,58,237,.4)}
</style><div class="countdown-bar">
  <p>&#128293; Flash Sale Ends In:</p>
  <div class="cd-units">
    <div class="cd-unit"><strong id="cdD">00</strong><span>Days</span></div>
    <div class="cd-unit"><strong id="cdH">00</strong><span>Hours</span></div>
    <div class="cd-unit"><strong id="cdM">00</strong><span>Mins</span></div>
    <div class="cd-unit"><strong id="cdS">00</strong><span>Secs</span></div>
  </div>
  <p style="font-size:.85rem;opacity:.85">Prices reset at midnight!</p>
</div>
<div class="filters-section">
  <h2>Explore Our Packages</h2>
  <p>Filter by travel style to find your perfect getaway</p>
  <div class="filter-btns">
    <button class="filter-btn active" onclick="filterPkg('all',this)">&#127757; All Packages</button>
    <button class="filter-btn" onclick="filterPkg('beach',this)">&#127958;&#65039; Beach</button>
    <button class="filter-btn" onclick="filterPkg('romantic',this)">&#128145; Romantic</button>
    <button class="filter-btn" onclick="filterPkg('luxury',this)">&#128142; Luxury</button>
    <button class="filter-btn" onclick="filterPkg('adventure',this)">&#129495; Adventure</button>
  </div>
</div>
<div class="packages-grid" id="pkgGrid"><?php foreach($pkgs as $p): $save=round((($p["orig"]-$p["price"])/$p["orig"])*100); ?>
<div class="pkg-card" data-cat="<?= $p["cat"] ?>">
  <div class="pkg-img">
    <img src="<?= $p["img"] ?>" alt="<?= htmlspecialchars($p["name"]) ?>" loading="lazy">
    <span class="pkg-badge"><?= $p["badge"] ?></span>
    <span class="pkg-cat"><?= ucfirst($p["cat"]) ?></span>
  </div>
  <div class="pkg-body">
    <div class="pkg-rating"><span class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span><strong><?= $p["rating"] ?></strong><span>(<?= $p["reviews"] ?> reviews)</span></div>
    <h3><?= htmlspecialchars($p["name"]) ?></h3>
    <div class="pkg-meta"><span>&#128197; <?= $p["days"] ?> Days / <?= $p["nights"] ?> Nights</span><span>&#9992; Flights Included</span></div>
    <div class="pkg-includes"><?php foreach($p["inc"] as $inc): ?><span class="inc-tag">&#10003; <?= $inc ?></span><?php endforeach; ?></div>
    <div class="pkg-footer">
      <div class="pkg-price">
        <span class="orig">$<?= number_format($p["orig"]) ?></span>
        <span class="curr">$<?= number_format($p["price"]) ?></span>
        <span class="per">per person</span>
        <span class="save">Save <?= $save ?>%</span>
      </div>
      <button class="btn-book" onclick="window.location='../auth/login.php'">Book Now &#8594;</button>
    </div>
  </div>
</div>
<?php endforeach; ?>
</div><style>
.why-section{padding:80px 40px;max-width:1400px;margin:0 auto;text-align:center}
.why-section h2{font-family:"Poppins",sans-serif;font-size:2.5rem;font-weight:800;margin-bottom:12px}
.why-section>p{color:var(--muted);font-size:1.05rem;margin-bottom:48px}
.why-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px}
.why-card{background:var(--card);border:1px solid var(--border);border-radius:16px;padding:32px;transition:.3s}
.why-card:hover{border-color:var(--accent);transform:translateY(-4px)}
.why-icon{font-size:3rem;margin-bottom:16px}
.why-card h3{font-family:"Poppins",sans-serif;font-size:1.15rem;font-weight:700;margin-bottom:10px}
.why-card p{color:var(--muted);font-size:.9rem;line-height:1.6}
.compare-section{padding:80px 40px;max-width:1400px;margin:0 auto}
.compare-section h2{font-family:"Poppins",sans-serif;font-size:2.5rem;font-weight:800;text-align:center;margin-bottom:48px}
.compare-table{overflow-x:auto}
table{width:100%;border-collapse:collapse;background:var(--card);border:1px solid var(--border);border-radius:16px;overflow:hidden}
thead{background:linear-gradient(135deg,#7c3aed,#ec4899)}
thead th{color:#fff;font-weight:700;padding:18px;text-align:left;font-size:.9rem;text-transform:uppercase;letter-spacing:.5px}
tbody tr{border-bottom:1px solid var(--border)}
tbody tr:last-child{border-bottom:none}
tbody td{padding:16px;font-size:.88rem}
tbody td:first-child{font-weight:600}
.check{color:#10b981;font-size:1.2rem}
.cross{color:#ef4444;font-size:1.2rem}
.site-footer{background:var(--bg2);border-top:1px solid var(--border);padding:60px 40px 30px;margin-top:80px}
.footer-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:40px;max-width:1400px;margin:0 auto 40px}
.footer-logo{font-family:"Poppins",sans-serif;font-size:1.5rem;font-weight:800;background:linear-gradient(135deg,#7c3aed,#ec4899);-webkit-background-clip:text;-webkit-text-fill-color:transparent;display:inline-block;margin-bottom:12px}
.footer-brand p{color:var(--muted);font-size:.88rem;line-height:1.6}
.footer-social{display:flex;gap:12px;margin-top:16px;font-size:1.3rem}
.footer-links h4{font-family:"Poppins",sans-serif;font-size:1rem;font-weight:700;margin-bottom:16px}
.footer-links ul{list-style:none}
.footer-links li{margin-bottom:10px}
.footer-links a{color:var(--muted);font-size:.88rem;transition:.2s}
.footer-links a:hover{color:var(--accent)}
.footer-bottom{text-align:center;padding-top:30px;border-top:1px solid var(--border);color:var(--muted);font-size:.82rem;max-width:1400px;margin:0 auto}
</style>
<section class="why-section">
  <h2>Why Choose TravelLux?</h2>
  <p>We make luxury travel accessible, seamless and unforgettable</p>
  <div class="why-grid">
    <div class="why-card"><div class="why-icon">&#128737;&#65039;</div><h3>Best Price Guarantee</h3><p>Find a lower price anywhere and we will match it plus give you an extra 10% off your booking.</p></div>
    <div class="why-card"><div class="why-icon">&#127919;</div><h3>Curated Experiences</h3><p>Every package is hand-picked by our travel experts to ensure the highest quality and value.</p></div>
    <div class="why-card"><div class="why-icon">&#128260;</div><h3>Free Cancellation</h3><p>Plans change. Cancel up to 48 hours before departure for a full refund, no questions asked.</p></div>
    <div class="why-card"><div class="why-icon">&#127758;</div><h3>24/7 Support</h3><p>Our dedicated travel concierge team is available around the clock to assist you anywhere in the world.</p></div>
  </div>
</section><section class="compare-section">
  <h2>Package Comparison</h2>
  <div class="compare-table">
    <table>
      <thead><tr><th>Feature</th><th>Maldives</th><th>Paris</th><th>Bali</th><th>Dubai</th><th>Santorini</th><th>Thailand</th></tr></thead>
      <tbody>
        <tr><td>Flights Included</td><td><span class="check">&#10003;</span></td><td><span class="check">&#10003;</span></td><td><span class="check">&#10003;</span></td><td><span class="check">&#10003;</span></td><td><span class="check">&#10003;</span></td><td><span class="check">&#10003;</span></td></tr>
        <tr><td>All Meals</td><td><span class="check">&#10003;</span></td><td><span class="cross">&#10007;</span></td><td><span class="cross">&#10007;</span></td><td><span class="check">&#10003;</span></td><td><span class="cross">&#10007;</span></td><td><span class="cross">&#10007;</span></td></tr>
        <tr><td>Airport Transfer</td><td><span class="check">&#10003;</span></td><td><span class="check">&#10003;</span></td><td><span class="check">&#10003;</span></td><td><span class="check">&#10003;</span></td><td><span class="check">&#10003;</span></td><td><span class="check">&#10003;</span></td></tr>
        <tr><td>Guided Tours</td><td><span class="check">&#10003;</span></td><td><span class="check">&#10003;</span></td><td><span class="check">&#10003;</span></td><td><span class="check">&#10003;</span></td><td><span class="check">&#10003;</span></td><td><span class="check">&#10003;</span></td></tr>
        <tr><td>Spa / Wellness</td><td><span class="check">&#10003;</span></td><td><span class="cross">&#10007;</span></td><td><span class="cross">&#10007;</span></td><td><span class="check">&#10003;</span></td><td><span class="cross">&#10007;</span></td><td><span class="cross">&#10007;</span></td></tr>
        <tr><td>Free Cancellation</td><td><span class="check">&#10003;</span></td><td><span class="check">&#10003;</span></td><td><span class="check">&#10003;</span></td><td><span class="check">&#10003;</span></td><td><span class="check">&#10003;</span></td><td><span class="check">&#10003;</span></td></tr>
      </tbody>
    </table>
  </div>
</section>
<footer class="site-footer">
  <div class="footer-grid">
    <div class="footer-brand">
      <a href="../index.php" class="footer-logo">&#9992; TravelLux</a>
      <p>Your premium travel companion. Discover extraordinary destinations and create memories that last a lifetime.</p>
      <div class="footer-social"><a href="#">&#128248;</a><a href="#">&#128248;</a><a href="#">&#128248;</a></div>
    </div>
    <div class="footer-links"><h4>Packages</h4><ul><li><a href="packages.php">All Packages</a></li><li><a href="packages.php">Beach Escapes</a></li><li><a href="packages.php">Romantic Getaways</a></li><li><a href="packages.php">Luxury Stays</a></li></ul></div>
    <div class="footer-links"><h4>Company</h4><ul><li><a href="blog.php">Blog</a></li><li><a href="contact.php">Contact</a></li><li><a href="#">About Us</a></li></ul></div>
    <div class="footer-links"><h4>Support</h4><ul><li><a href="#">Help Center</a></li><li><a href="#">Privacy Policy</a></li><li><a href="../auth/login.php">My Account</a></li></ul></div>
  </div>
  <div class="footer-bottom"><p>&copy; <?php echo date('Y'); ?> TravelLux. All rights reserved.</p></div>
</footer>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init({duration:700,once:true,offset:60});
var end=new Date();end.setDate(end.getDate()+3);
function tick(){var now=new Date(),diff=end-now;if(diff<=0)return;var d=Math.floor(diff/864e5),h=Math.floor((diff%864e5)/36e5),m=Math.floor((diff%36e5)/6e4),s=Math.floor((diff%6e4)/1e3);document.getElementById('cdD').textContent=String(d).padStart(2,'0');document.getElementById('cdH').textContent=String(h).padStart(2,'0');document.getElementById('cdM').textContent=String(m).padStart(2,'0');document.getElementById('cdS').textContent=String(s).padStart(2,'0');}
tick();setInterval(tick,1000);
function filterPkg(cat,btn){document.querySelectorAll('.filter-btn').forEach(b=>b.classList.remove('active'));btn.classList.add('active');document.querySelectorAll('.pkg-card').forEach(c=>{c.classList.toggle('hidden',cat!=='all'&&c.dataset.cat!==cat);});}
var t=localStorage.getItem('travellux_theme')||'dark';document.documentElement.setAttribute('data-theme',t);
</script>
<script src="/travel-booking-system/assets/js/image-viewer.js"></script></body>
</html>