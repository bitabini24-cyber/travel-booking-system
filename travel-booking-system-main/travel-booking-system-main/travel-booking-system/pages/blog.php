<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../backend/helpers/functions.php';
$pageTitle = 'Travel Blog — TravelLux';
require_once __DIR__ . '/../includes/header.php';

$posts = [
    ['10 Hidden Gems in Southeast Asia You Must Visit','Discover the secret paradises that most tourists never find — from hidden waterfalls to untouched beaches.','https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=600&q=80','Sarah Johnson','Travel Writer','March 28, 2026','12 min read','Adventure','#7C3AED'],
    ['The Ultimate Guide to Booking Luxury Hotels on a Budget','Learn insider tricks to stay in 5-star hotels without breaking the bank. Real tips that actually work.','https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=600&q=80','James Chen','Hotel Expert','March 22, 2026','8 min read','Tips & Tricks','#EC4899'],
    ['Paris in Spring: A Complete 7-Day Itinerary','Everything you need to know about visiting Paris during the most beautiful season of the year.','https://images.unsplash.com/photo-1502602898657-3e91760cbb34?w=600&q=80','Marie Dubois','City Explorer','March 15, 2026','15 min read','City Guide','#06B6D4'],
    ['Maldives vs Bali: Which Paradise is Right for You?','An honest comparison of two of the world\'s most popular tropical destinations to help you decide.','https://images.unsplash.com/photo-1573843981267-be1999ff37cd?w=600&q=80','Alex Rivera','Travel Blogger','March 10, 2026','10 min read','Comparison','#10B981'],
    ['How to Travel Solo Safely: 20 Essential Tips','Everything a solo traveler needs to know to stay safe, meet people, and have the trip of a lifetime.','https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=600&q=80','Emma Wilson','Solo Traveler','March 5, 2026','11 min read','Solo Travel','#F59E0B'],
    ['The Best Street Food Cities in the World','From Bangkok to Mexico City — a food lover\'s guide to the world\'s greatest street food destinations.','https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=600&q=80','Kenji Tanaka','Food Traveler','Feb 28, 2026','9 min read','Food & Culture','#A78BFA'],
];
?>
<style>
body{background:#080818;}
.blog-hero{padding:140px 0 80px;text-align:center;position:relative;}
.blog-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 80% 60% at 50% 0%,rgba(236,72,153,0.25),transparent 70%);}
.blog-hero-content{position:relative;z-index:1;}
.blog-tag{display:inline-flex;align-items:center;gap:8px;background:rgba(236,72,153,0.15);border:1px solid rgba(236,72,153,0.3);color:rgba(255,255,255,0.8);padding:8px 20px;border-radius:50px;font-size:0.78rem;font-weight:800;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:20px;}
.blog-hero h1{font-family:'Poppins',sans-serif;font-size:clamp(2.5rem,6vw,5rem);font-weight:900;color:#fff;letter-spacing:-2px;margin-bottom:16px;line-height:1.1;}
.blog-hero h1 span{background:linear-gradient(135deg,#F9A8D4,#A78BFA);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.blog-hero p{color:rgba(255,255,255,0.6);font-size:1.1rem;max-width:560px;margin:0 auto;}
/* Featured post */
.blog-featured{display:grid;grid-template-columns:1.4fr 1fr;gap:28px;margin-bottom:60px;}
.blog-featured-card{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:24px;overflow:hidden;transition:all 0.4s;text-decoration:none;display:block;}
.blog-featured-card:hover{transform:translateY(-6px);box-shadow:0 30px 80px rgba(0,0,0,0.5);border-color:rgba(124,58,237,0.3);}
.blog-featured-img{height:320px;overflow:hidden;position:relative;}
.blog-featured-img img{width:100%;height:100%;object-fit:cover;transition:transform 0.7s;}
.blog-featured-card:hover .blog-featured-img img{transform:scale(1.06);}
.blog-featured-img::after{content:'';position:absolute;inset:0;background:linear-gradient(to top,rgba(8,8,24,0.7) 0%,transparent 50%);}
.blog-featured-body{padding:24px;}
.blog-cat{display:inline-block;padding:4px 12px;border-radius:50px;font-size:0.72rem;font-weight:800;color:#fff;margin-bottom:10px;}
.blog-title{font-family:'Poppins',sans-serif;font-size:1.2rem;font-weight:800;color:#fff;margin-bottom:10px;line-height:1.4;}
.blog-excerpt{color:rgba(255,255,255,0.55);font-size:0.88rem;line-height:1.7;margin-bottom:16px;}
.blog-meta{display:flex;align-items:center;gap:12px;flex-wrap:wrap;}
.blog-author-img{width:32px;height:32px;border-radius:50%;object-fit:cover;border:2px solid rgba(124,58,237,0.4);}
.blog-author-name{color:rgba(255,255,255,0.7);font-size:0.8rem;font-weight:600;}
.blog-date{color:rgba(255,255,255,0.35);font-size:0.75rem;}
.blog-read{color:rgba(255,255,255,0.35);font-size:0.75rem;}
.blog-dot{color:rgba(255,255,255,0.2);}
/* Blog grid */
.blog-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;}
.blog-card{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:20px;overflow:hidden;transition:all 0.4s;text-decoration:none;display:block;}
.blog-card:hover{transform:translateY(-8px);box-shadow:0 24px 60px rgba(0,0,0,0.4);border-color:rgba(124,58,237,0.25);}
.blog-card-img{height:180px;overflow:hidden;position:relative;}
.blog-card-img img{width:100%;height:100%;object-fit:cover;transition:transform 0.7s;}
.blog-card:hover .blog-card-img img{transform:scale(1.08);}
.blog-card-img::after{content:'';position:absolute;inset:0;background:linear-gradient(to top,rgba(8,8,24,0.5) 0%,transparent 60%);}
.blog-card-body{padding:18px;}
.blog-card-title{font-family:'Poppins',sans-serif;font-size:1rem;font-weight:700;color:#fff;margin-bottom:8px;line-height:1.4;}
.blog-card-excerpt{color:rgba(255,255,255,0.5);font-size:0.82rem;line-height:1.6;margin-bottom:14px;}
.blog-card-footer{display:flex;align-items:center;justify-content:space-between;}
.blog-read-more{color:#A78BFA;font-size:0.8rem;font-weight:700;}
@media(max-width:1024px){.blog-featured{grid-template-columns:1fr}.blog-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:640px){.blog-grid{grid-template-columns:1fr}}
</style>

<div class="blog-hero">
    <div class="blog-hero-content container">
        <div class="blog-tag">📝 Travel Blog</div>
        <h1>Stories & <span>Inspiration</span></h1>
        <p>Expert travel guides, tips, and stories from our community of passionate travelers around the world.</p>
    </div>
</div>

<div class="container" style="padding-bottom:100px;">
    <!-- Featured posts -->
    <h2 style="font-family:'Poppins',sans-serif;font-size:1.4rem;font-weight:800;color:#fff;margin-bottom:24px;">✨ Featured Stories</h2>
    <div class="blog-featured" style="margin-bottom:60px;">
        <?php foreach(array_slice($posts,0,2) as $p): ?>
        <a href="#" class="blog-featured-card">
            <div class="blog-featured-img">
                <img src="<?= $p[2] ?>" alt="<?= $p[0] ?>" loading="lazy">
            </div>
            <div class="blog-featured-body">
                <span class="blog-cat" style="background:<?= $p[8] ?>20;color:<?= $p[8] ?>;border:1px solid <?= $p[8] ?>40;"><?= $p[6] ?></span>
                <div class="blog-title"><?= $p[0] ?></div>
                <div class="blog-excerpt"><?= $p[1] ?></div>
                <div class="blog-meta">
                    <span class="blog-author-name"><?= $p[3] ?></span>
                    <span class="blog-dot">·</span>
                    <span class="blog-date"><?= $p[5] ?></span>
                    <span class="blog-dot">·</span>
                    <span class="blog-read">⏱ <?= $p[6] ?></span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- All posts -->
    <h2 style="font-family:'Poppins',sans-serif;font-size:1.4rem;font-weight:800;color:#fff;margin-bottom:24px;">📚 Latest Articles</h2>
    <div class="blog-grid">
        <?php foreach($posts as $p): ?>
        <a href="#" class="blog-card">
            <div class="blog-card-img">
                <img src="<?= $p[2] ?>" alt="<?= $p[0] ?>" loading="lazy">
            </div>
            <div class="blog-card-body">
                <span class="blog-cat" style="background:<?= $p[8] ?>20;color:<?= $p[8] ?>;border:1px solid <?= $p[8] ?>40;padding:3px 10px;border-radius:50px;font-size:0.7rem;font-weight:800;display:inline-block;margin-bottom:8px;"><?= $p[7] ?></span>
                <div class="blog-card-title"><?= $p[0] ?></div>
                <div class="blog-card-excerpt"><?= substr($p[1],0,90) ?>...</div>
                <div class="blog-card-footer">
                    <span style="color:rgba(255,255,255,0.4);font-size:0.75rem;"><?= $p[5] ?> · <?= $p[6] ?></span>
                    <span class="blog-read-more">Read →</span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
