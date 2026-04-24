<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../backend/helpers/functions.php';
require_once __DIR__ . '/../backend/models/Hotel.php';

$hotelModel = new Hotel($pdo);

$filters = [
    'city'      => sanitize($_GET['city'] ?? ''),
    'min_price' => intval($_GET['min_price'] ?? 0),
    'max_price' => intval($_GET['max_price'] ?? 0),
    'rating'    => floatval($_GET['rating'] ?? 0),
    'stars'     => intval($_GET['stars'] ?? 0),
    'sort'      => sanitize($_GET['sort'] ?? ''),
];

$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * PER_PAGE;
$hotels = $hotelModel->getAll($filters, PER_PAGE, $offset);
$total = $hotelModel->count($filters);
$totalPages = ceil($total / PER_PAGE);

// For map markers
$mapHotels = $hotelModel->getAll($filters, 100, 0);

$pageTitle = 'Search Hotels - TravelLux';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-hero">
    <div class="container">
        <h1>Find Your Perfect Stay</h1>
        <p><?= $total ?> hotels found<?= $filters['city'] ? ' in ' . htmlspecialchars($filters['city']) : '' ?></p>
    </div>
</div>

<div class="container" style="padding-top: 40px; padding-bottom: 80px;">
    <div style="display: grid; grid-template-columns: 280px 1fr; gap: 32px; align-items: start;">

        <!-- FILTERS SIDEBAR -->
        <aside class="hotel-card" style="padding: 28px; position: sticky; top: 100px;">
            <h3 style="font-weight: 700; margin-bottom: 24px; font-size: 1.1rem;">Filter Results</h3>
            <form method="GET" id="filterForm">
                <div class="form-group">
                    <label>Destination</label>
                    <input type="text" name="city" value="<?= htmlspecialchars($filters['city']) ?>" placeholder="City or country">
                </div>
                <div class="form-group">
                    <label>Min Price / night</label>
                    <input type="number" name="min_price" value="<?= $filters['min_price'] ?: '' ?>" placeholder="$0" min="0">
                </div>
                <div class="form-group">
                    <label>Max Price / night</label>
                    <input type="number" name="max_price" value="<?= $filters['max_price'] ?: '' ?>" placeholder="$9999" min="0">
                </div>
                <div class="form-group">
                    <label>Min Rating</label>
                    <select name="rating">
                        <option value="">Any</option>
                        <option value="3" <?= $filters['rating'] == 3 ? 'selected' : '' ?>>3+ Stars</option>
                        <option value="4" <?= $filters['rating'] == 4 ? 'selected' : '' ?>>4+ Stars</option>
                        <option value="4.5" <?= $filters['rating'] == 4.5 ? 'selected' : '' ?>>4.5+ Stars</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Hotel Stars</label>
                    <select name="stars">
                        <option value="">Any</option>
                        <option value="3" <?= $filters['stars'] == 3 ? 'selected' : '' ?>>3 Stars</option>
                        <option value="4" <?= $filters['stars'] == 4 ? 'selected' : '' ?>>4 Stars</option>
                        <option value="5" <?= $filters['stars'] == 5 ? 'selected' : '' ?>>5 Stars</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Sort By</label>
                    <select name="sort">
                        <option value="">Default</option>
                        <option value="price ASC" <?= $filters['sort'] === 'price ASC' ? 'selected' : '' ?>>Price: Low to High</option>
                        <option value="price DESC" <?= $filters['sort'] === 'price DESC' ? 'selected' : '' ?>>Price: High to Low</option>
                        <option value="rating DESC" <?= $filters['sort'] === 'rating DESC' ? 'selected' : '' ?>>Top Rated</option>
                        <option value="name ASC" <?= $filters['sort'] === 'name ASC' ? 'selected' : '' ?>>Name A-Z</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-full">Apply Filters</button>
                <a href="<?= APP_URL ?>/pages/search.php" class="btn btn-full" style="margin-top: 8px; background: var(--bg); color: var(--text);">Clear All</a>
            </form>
        </aside>

        <!-- RESULTS -->
        <div>
            <!-- MAP + DESTINATION IMAGES -->
            <div style="margin-bottom:36px" data-aos="fade-up">

              <!-- Section header -->
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
                <h3 style="font-family:'Poppins',sans-serif;font-size:1.2rem;font-weight:800;color:#fff">
                  &#127758; Explore Destinations
                </h3>
                <span style="color:rgba(255,255,255,.4);font-size:.82rem"><?= $total ?> hotels found</span>
              </div>

              <!-- Destination image grid map -->
              <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;margin-bottom:20px">
                <?php
                $destMap = [
                  ['Paris','France','https://images.unsplash.com/photo-1499856871958-5b9627545d1a?w=400&q=80','48.8566','2.3522'],
                  ['Bali','Indonesia','https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=400&q=80','-8.3405','115.0920'],
                  ['Tokyo','Japan','https://images.unsplash.com/photo-1536098561742-ca998e48cbcc?w=400&q=80','35.6762','139.6503'],
                  ['Maldives','Maldives','https://images.unsplash.com/photo-1514282401047-d79a71a590e8?w=400&q=80','3.2028','73.2207'],
                  ['Dubai','UAE','https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=400&q=80','25.2048','55.2708'],
                  ['Santorini','Greece','https://images.unsplash.com/photo-1613395877344-13d4a8e0d49e?w=400&q=80','36.3932','25.4615'],
                  ['New York','USA','https://images.unsplash.com/photo-1485738422979-f5c462d49f74?w=400&q=80','40.7128','-74.0060'],
                  ['London','UK','https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?w=400&q=80','51.5074','-0.1278'],
                  ['Bangkok','Thailand','https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=400&q=80','13.7563','100.5018'],
                  ['Sydney','Australia','https://images.unsplash.com/photo-1506973035872-a4ec16b8e8d9?w=400&q=80','-33.8688','151.2093'],
                  ['Rome','Italy','https://images.unsplash.com/photo-1523906834658-6e24ef2386f9?w=400&q=80','41.9028','12.4964'],
                  ['Barcelona','Spain','https://images.unsplash.com/photo-1543783207-ec64e4d95325?w=400&q=80','41.3851','2.1734'],
                ];
                foreach($destMap as $dm):
                  $isActive = strtolower($filters['city']) === strtolower($dm[0]);
                ?>
                <a href="?city=<?= urlencode($dm[0]) ?>"
                   style="border-radius:16px;overflow:hidden;position:relative;display:block;height:140px;transition:all .35s;border:2px solid <?= $isActive ? '#7c3aed' : 'rgba(255,255,255,.06)' ?>;box-shadow:<?= $isActive ? '0 0 0 3px rgba(124,58,237,.4),0 16px 40px rgba(0,0,0,.5)' : '0 4px 16px rgba(0,0,0,.3)' ?>"
                   onmouseover="this.style.transform='translateY(-4px) scale(1.02)';this.style.borderColor='rgba(124,58,237,.6)';this.style.boxShadow='0 16px 40px rgba(0,0,0,.5)'"
                   onmouseout="this.style.transform='';this.style.borderColor='<?= $isActive ? '#7c3aed' : 'rgba(255,255,255,.06)' ?>';this.style.boxShadow='<?= $isActive ? '0 0 0 3px rgba(124,58,237,.4),0 16px 40px rgba(0,0,0,.5)' : '0 4px 16px rgba(0,0,0,.3)' ?>'">
                  <img src="<?= $dm[2] ?>" alt="<?= $dm[0] ?>"
                       style="width:100%;height:100%;object-fit:cover;display:block;transition:transform .5s"
                       onmouseover="this.style.transform='scale(1.08)'"
                       onmouseout="this.style.transform=''">
                  <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.82) 0%,rgba(0,0,0,.1) 60%,transparent 100%)"></div>
                  <?php if($isActive): ?>
                  <div style="position:absolute;top:8px;right:8px;background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff;padding:3px 10px;border-radius:50px;font-size:.65rem;font-weight:800">&#10003; Selected</div>
                  <?php endif; ?>
                  <div style="position:absolute;bottom:0;left:0;right:0;padding:10px 12px">
                    <div style="color:#fff;font-weight:800;font-size:.88rem;font-family:'Poppins',sans-serif"><?= $dm[0] ?></div>
                    <div style="color:rgba(255,255,255,.55);font-size:.7rem"><?= $dm[1] ?></div>
                  </div>
                </a>
                <?php endforeach; ?>
              </div>

              <!-- Leaflet map below the image grid -->
              
            </div>
              </div>
            </div>

            
            <!-- ===== VISUAL MAP ===== -->
            <div style="margin-bottom:36px">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
                <h3 style="font-family:'Poppins',sans-serif;font-size:1.2rem;font-weight:800;color:#fff;display:flex;align-items:center;gap:8px">
                  <span style="background:linear-gradient(135deg,#7c3aed,#ec4899);-webkit-background-clip:text;-webkit-text-fill-color:transparent">&#127758;</span> Explore Destinations
                </h3>
                <span style="color:rgba(255,255,255,.4);font-size:.82rem"><?= $total ?> hotels found</span>
              </div>

              <!-- World map image with clickable pins -->
              <div style="position:relative;border-radius:20px;overflow:hidden;background:linear-gradient(135deg,#0a0a1a,#1a1a2e);border:1px solid rgba(255,255,255,.08);box-shadow:0 20px 60px rgba(0,0,0,.6)">

                <!-- World map background image -->
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/80/World_map_-_low_resolution.svg/1280px-World_map_-_low_resolution.svg.png"
                     alt="World Map"
                     style="width:100%;height:420px;object-fit:cover;object-position:center;opacity:.18;display:block;filter:invert(1) hue-rotate(200deg) saturate(.5)">

                <!-- Dark overlay with gradient -->
                <div style="position:absolute;inset:0;background:radial-gradient(ellipse at center,rgba(124,58,237,.08) 0%,rgba(0,0,0,.3) 100%)"></div>

                <!-- Grid lines overlay -->
                <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(124,58,237,.06) 1px,transparent 1px),linear-gradient(90deg,rgba(124,58,237,.06) 1px,transparent 1px);background-size:60px 60px"></div>

                <!-- Destination pins positioned on the map -->
                <?php
                $pins = [
                  ['Paris',      'France',    '48.8566',  '2.3522',   '42%', '28%', 'https://images.unsplash.com/photo-1499856871958-5b9627545d1a?w=300&q=80', '#a78bfa'],
                  ['London',     'UK',        '51.5074',  '-0.1278',  '40%', '26%', 'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?w=300&q=80', '#67e8f9'],
                  ['Dubai',      'UAE',       '25.2048',  '55.2708',  '47%', '42%', 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=300&q=80', '#fcd34d'],
                  ['Tokyo',      'Japan',     '35.6762',  '139.6503', '38%', '68%', 'https://images.unsplash.com/photo-1536098561742-ca998e48cbcc?w=300&q=80', '#f9a8d4'],
                  ['Bali',       'Indonesia', '-8.3405',  '115.0920', '58%', '66%', 'https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=300&q=80', '#6ee7b7'],
                  ['Maldives',   'Maldives',  '3.2028',   '73.2207',  '54%', '52%', 'https://images.unsplash.com/photo-1514282401047-d79a71a590e8?w=300&q=80', '#06b6d4'],
                  ['New York',   'USA',       '40.7128',  '-74.0060', '40%', '22%', 'https://images.unsplash.com/photo-1485738422979-f5c462d49f74?w=300&q=80', '#fb923c'],
                  ['Sydney',     'Australia', '-33.8688', '151.2093', '72%', '74%', 'https://images.unsplash.com/photo-1506973035872-a4ec16b8e8d9?w=300&q=80', '#ec4899'],
                  ['Santorini',  'Greece',    '36.3932',  '25.4615',  '44%', '35%', 'https://images.unsplash.com/photo-1613395877344-13d4a8e0d49e?w=300&q=80', '#c4b5fd'],
                  ['Bangkok',    'Thailand',  '13.7563',  '100.5018', '50%', '62%', 'https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=300&q=80', '#86efac'],
                  ['Rome',       'Italy',     '41.9028',  '12.4964',  '43%', '33%', 'https://images.unsplash.com/photo-1523906834658-6e24ef2386f9?w=300&q=80', '#fda4af'],
                  ['Barcelona',  'Spain',     '41.3851',  '2.1734',   '43%', '29%', 'https://images.unsplash.com/photo-1543783207-ec64e4d95325?w=300&q=80', '#fdba74'],
                ];
                foreach($pins as $pin):
                  $isActive = strtolower($filters['city']) === strtolower($pin[0]);
                  $pinId = 'pin_' . preg_replace('/\s+/', '_', strtolower($pin[0]));
                ?>
                <!-- Pin: <?= $pin[0] ?> -->
                <div id="<?= $pinId ?>"
                     style="position:absolute;top:<?= $pin[4] ?>;left:<?= $pin[5] ?>;transform:translate(-50%,-100%);z-index:10;cursor:pointer"
                     onmouseenter="showPinCard('<?= $pinId ?>_card')"
                     onmouseleave="hidePinCard('<?= $pinId ?>_card')"
                     onclick="window.location='?city=<?= urlencode($pin[0]) ?>'">

                  <!-- Pin dot with pulse -->
                  <div style="position:relative;display:flex;flex-direction:column;align-items:center">
                    <div style="width:<?= $isActive ? '18px' : '12px' ?>;height:<?= $isActive ? '18px' : '12px' ?>;border-radius:50%;background:<?= $pin[6] ?>;box-shadow:0 0 0 3px rgba(255,255,255,.2),0 0 20px <?= $pin[6] ?>;border:2px solid rgba(255,255,255,.6);transition:all .3s;animation:pinPulse 2s ease infinite <?= rand(0,2000) ?>ms"></div>
                    <div style="width:2px;height:12px;background:linear-gradient(to bottom,<?= $pin[6] ?>,transparent)"></div>
                    <div style="background:rgba(0,0,0,.7);backdrop-filter:blur(8px);color:#fff;font-size:.6rem;font-weight:800;padding:2px 7px;border-radius:50px;white-space:nowrap;border:1px solid rgba(255,255,255,.15);margin-top:2px"><?= $pin[0] ?></div>
                  </div>

                  <!-- Hover card -->
                  <div id="<?= $pinId ?>_card"
                       style="display:none;position:absolute;bottom:calc(100% + 8px);left:50%;transform:translateX(-50%);width:200px;background:rgba(15,15,25,.97);border:1px solid rgba(255,255,255,.12);border-radius:16px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.8);z-index:100">
                    <div style="position:relative;height:110px;overflow:hidden">
                      <img src="<?= $pin[6] ?>" alt="<?= $pin[0] ?>" style="width:100%;height:100%;object-fit:cover;display:block">
                      <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.7),transparent 50%)"></div>
                      <div style="position:absolute;bottom:8px;left:10px;color:#fff;font-family:'Poppins',sans-serif;font-weight:800;font-size:.88rem"><?= $pin[0] ?></div>
                      <div style="position:absolute;top:8px;right:8px;background:<?= $pin[6] ?>;color:#000;padding:2px 8px;border-radius:50px;font-size:.6rem;font-weight:800"><?= $pin[1] ?></div>
                    </div>
                    <div style="padding:10px 12px">
                      <div style="color:rgba(255,255,255,.5);font-size:.7rem;margin-bottom:8px">&#128205; <?= $pin[1] ?></div>
                      <a href="?city=<?= urlencode($pin[0]) ?>" style="display:block;text-align:center;background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff;padding:7px;border-radius:8px;font-size:.75rem;font-weight:700;text-decoration:none">Explore Hotels &#8594;</a>
                    </div>
                  </div>
                </div>
                <?php endforeach; ?>

                <!-- Map legend -->
                <div style="position:absolute;bottom:14px;left:14px;background:rgba(0,0,0,.7);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:10px 14px;display:flex;align-items:center;gap:10px">
                  <div style="width:10px;height:10px;border-radius:50%;background:#a78bfa;box-shadow:0 0 8px #a78bfa"></div>
                  <span style="color:rgba(255,255,255,.6);font-size:.72rem">Click a pin to explore hotels</span>
                </div>

                <!-- Hotel count badge -->
                <div style="position:absolute;top:14px;right:14px;background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff;padding:6px 16px;border-radius:50px;font-size:.75rem;font-weight:800;box-shadow:0 4px 16px rgba(124,58,237,.4)">
                  &#127968; <?= $total ?> Hotels Available
                </div>
              </div>

              <style>
                @keyframes pinPulse {
                  0%,100%{box-shadow:0 0 0 3px rgba(255,255,255,.2),0 0 12px currentColor}
                  50%{box-shadow:0 0 0 6px rgba(255,255,255,.1),0 0 24px currentColor}
                }
              </style>
              <script>
                function showPinCard(id){var el=document.getElementById(id);if(el)el.style.display='block';}
                function hidePinCard(id){var el=document.getElementById(id);if(el)el.style.display='none';}
              </script>
            </div>
            <!-- HOTEL GRID -->
            <?php if (empty($hotels)): ?>
                <div class="empty-state">
                    <div class="empty-icon">🏨</div>
                    <h3>No hotels found</h3>
                    <p>Try adjusting your filters or search a different destination.</p>
                </div>
            <?php else: ?>
                <div class="hotels-grid" id="hotelsGrid">
                    <?php foreach ($hotels as $hotel): ?>
                        <a href="<?= APP_URL ?>/pages/hotel-details.php?id=<?= $hotel['id'] ?>" class="hotel-card" data-aos="fade-up">
                            <div class="hotel-card-img">
                                <img src="<?= htmlspecialchars($hotel['image']) ?>" alt="<?= htmlspecialchars($hotel['name']) ?>" loading="lazy">
                                <span class="hotel-card-badge"><?= $hotel['stars'] ?>★</span>
                                <button class="hotel-card-fav" onclick="event.preventDefault(); toggleFav(this)">♡</button>
                            </div>
                            <div class="hotel-card-body">
                                <div class="hotel-card-location">📍 <?= htmlspecialchars($hotel['city']) ?>, <?= htmlspecialchars($hotel['country']) ?></div>
                                <div class="hotel-card-name"><?= htmlspecialchars($hotel['name']) ?></div>
                                <div class="hotel-card-rating">
                                    <div class="stars"><?= renderStars($hotel['rating']) ?></div>
                                    <span class="rating-count"><?= number_format($hotel['rating'], 1) ?></span>
                                </div>
                                <div class="hotel-card-footer">
                                    <div class="hotel-price"><?= formatPrice($hotel['price']) ?><span>/night</span></div>
                                    <span class="btn btn-primary btn-sm">View</span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- PAGINATION -->
                <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?<?= http_build_query(array_merge($filters, ['page' => $i])) ?>"
                           class="page-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
const mapHotels = <?= json_encode(array_map(fn($h) => [
    'id' => $h['id'], 'name' => $h['name'], 'city' => $h['city'],
    'country' => $h['country'], 'lat' => $h['lat'], 'lng' => $h['lng'],
    'price' => $h['price'], 'image' => $h['image'], 'rating' => $h['rating']
], array_filter($mapHotels, fn($h) => $h['lat'] && $h['lng']))) ?>;

// Country images lookup
const CIMG = {
  'France':'https://images.unsplash.com/photo-1499856871958-5b9627545d1a?w=600&q=80',
  'Japan':'https://images.unsplash.com/photo-1536098561742-ca998e48cbcc?w=600&q=80',
  'Indonesia':'https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=600&q=80',
  'Maldives':'https://images.unsplash.com/photo-1514282401047-d79a71a590e8?w=600&q=80',
  'Greece':'https://images.unsplash.com/photo-1613395877344-13d4a8e0d49e?w=600&q=80',
  'UAE':'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=600&q=80',
  'Thailand':'https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=600&q=80',
  'Italy':'https://images.unsplash.com/photo-1523906834658-6e24ef2386f9?w=600&q=80',
  'Spain':'https://images.unsplash.com/photo-1543783207-ec64e4d95325?w=600&q=80',
  'USA':'https://images.unsplash.com/photo-1485738422979-f5c462d49f74?w=600&q=80',
  'UK':'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?w=600&q=80',
  'Australia':'https://images.unsplash.com/photo-1506973035872-a4ec16b8e8d9?w=600&q=80',
  'India':'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?w=600&q=80',
  'Switzerland':'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&q=80',
  'Turkey':'https://images.unsplash.com/photo-1541432901042-2d8bd64b4a9b?w=600&q=80',
  'Singapore':'https://images.unsplash.com/photo-1525625293386-3f8f99389edd?w=600&q=80',
  'Morocco':'https://images.unsplash.com/photo-1539020140153-e479b8c22e70?w=600&q=80',
  'Egypt':'https://images.unsplash.com/photo-1539650116574-75c0c6d73f6e?w=600&q=80',
  'Portugal':'https://images.unsplash.com/photo-1555881400-74d7acaacd8b?w=600&q=80',
  'Germany':'https://images.unsplash.com/photo-1467269204594-9661b134dd2b?w=600&q=80',
  'China':'https://images.unsplash.com/photo-1508804185872-d7badad00f7d?w=600&q=80',
  'Vietnam':'https://images.unsplash.com/photo-1528360983277-13d401cdc186?w=600&q=80',
  'Brazil':'https://images.unsplash.com/photo-1483729558449-99ef09a8c325?w=600&q=80',
  'Mexico':'https://images.unsplash.com/photo-1518638150340-f706e86654de?w=600&q=80',
  'Canada':'https://images.unsplash.com/photo-1517935706615-2717063c2225?w=600&q=80',
};
const FALLBACK_IMG = 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=600&q=80';

document.addEventListener('DOMContentLoaded', function() {
  // ── Build country cards row ──
  var seen = {}, cards = [];
  mapHotels.forEach(function(h) {
    var key = h.country || h.city;
    if (!seen[key]) {
      seen[key] = true;
      cards.push({ country: key, city: h.city, img: CIMG[key] || FALLBACK_IMG });
    }
  });

  var row = document.getElementById('countryCards');
  if (row) {
    cards.forEach(function(c) {
      var el = document.createElement('a');
      el.href = '?city=' + encodeURIComponent(c.city);
      el.style.cssText = 'flex:0 0 160px;border-radius:14px;overflow:hidden;position:relative;display:block;text-decoration:none;transition:transform .3s,box-shadow .3s;border:2px solid rgba(124,58,237,.2)';
      el.onmouseover = function(){ this.style.transform='translateY(-4px)'; this.style.boxShadow='0 16px 40px rgba(0,0,0,.5)'; showPanel(c); };
      el.onmouseout  = function(){ this.style.transform=''; this.style.boxShadow=''; };
      el.innerHTML = '<img src="'+c.img+'" alt="'+c.country+'" style="width:100%;height:100px;object-fit:cover;display:block">'
        + '<div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.75),transparent);display:flex;align-items:flex-end;padding:10px">'
        + '<span style="color:#fff;font-weight:700;font-size:.82rem;font-family:Poppins,sans-serif">'+c.country+'</span></div>';
      row.appendChild(el);
    });
  }

  // ── Init Leaflet map at world level ──
  if (typeof L === "undefined") { console.error("Leaflet not loaded"); return; }

  var map = L.map("map", { zoomControl: false }).setView([20, 10], 2);

  L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; OpenStreetMap contributors', maxZoom: 18
  }).addTo(map);

  L.control.zoom({ position: 'bottomright' }).addTo(map);

  var palette = ['#A78BFA','#F9A8D4','#67E8F9','#6EE7B7','#FCD34D','#FB923C'];
  var bounds = [];

  mapHotels.forEach(function(h, i) {
    var color = palette[i % palette.length];
    var icon = L.divIcon({
      html: '<div style="background:'+color+';color:#fff;font-weight:900;font-size:.78rem;font-family:Poppins,sans-serif;padding:5px 11px;border-radius:8px;box-shadow:0 4px 16px rgba(0,0,0,.4);white-space:nowrap;border:2px solid rgba(255,255,255,.3)">$'+Math.round(h.price)+'</div>',
      className: '', iconSize: [70,32], iconAnchor: [35,32], popupAnchor: [0,-36]
    });

    var country = h.country || h.city;
    var cimg = CIMG[country] || FALLBACK_IMG;

    var marker = L.marker([h.lat, h.lng], { icon: icon }).addTo(map);
    marker.bindPopup(
      '<div style="font-family:Inter,sans-serif;min-width:220px">'
      + '<img src="'+cimg+'" style="width:100%;height:130px;object-fit:cover;display:block;border-radius:8px 8px 0 0">'
      + '<div style="padding:12px 14px;background:rgba(15,12,41,.97)">'
      + '<div style="color:#fff;font-weight:700;font-size:.92rem;margin-bottom:3px">'+h.name+'</div>'
      + '<div style="color:rgba(255,255,255,.45);font-size:.72rem;margin-bottom:10px">📍 '+h.city+', '+country+'</div>'
      + '<div style="display:flex;align-items:center;justify-content:space-between">'
      + '<span style="font-family:Poppins,sans-serif;font-size:1.1rem;font-weight:900;background:linear-gradient(135deg,#a78bfa,#f9a8d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent">$'+Math.round(h.price)+'<span style="font-size:.7rem;font-weight:400;color:rgba(255,255,255,.4);-webkit-text-fill-color:rgba(255,255,255,.4)">/night</span></span>'
      + '<a href="hotel-details.php?id='+h.id+'" style="background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff;padding:6px 14px;border-radius:8px;font-size:.75rem;font-weight:700;text-decoration:none">View →</a>'
      + '</div></div></div>',
      { className: 'map-popup-wrap', maxWidth: 260 }
    );

    marker.on('mouseover click', function() {
      showPanel({ country: country, city: h.city, img: cimg });
    });

    bounds.push([h.lat, h.lng]);
  });

  if (bounds.length > 1) map.fitBounds(bounds, { padding: [40,40], maxZoom: 5 });
  else if (bounds.length === 1) map.setView(bounds[0], 5);

  // Inject popup styles
  var s = document.createElement('style');
  s.textContent = '.map-popup-wrap .leaflet-popup-content-wrapper{background:transparent!important;border:none!important;padding:0!important;border-radius:12px!important;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.7)!important}.map-popup-wrap .leaflet-popup-tip-container{display:none}.map-popup-wrap .leaflet-popup-content{margin:0!important}.leaflet-control-zoom a{background:rgba(15,12,41,.9)!important;color:#fff!important;border:none!important;border-bottom:1px solid rgba(255,255,255,.08)!important}.leaflet-control-zoom a:hover{background:rgba(124,58,237,.5)!important}.leaflet-control-zoom{border:none!important;border-radius:10px!important;overflow:hidden}';
  document.head.appendChild(s);
});

function showPanel(c) {
  var p = document.getElementById('countryPanel');
  var img = document.getElementById('cpImg');
  var name = document.getElementById('cpName');
  var city = document.getElementById('cpCity');
  var btn = document.getElementById('cpBtn');
  if (!p) return;
  img.src = c.img;
  name.textContent = c.country;
  city.textContent = '📍 ' + c.city;
  btn.href = '?city=' + encodeURIComponent(c.city);
  p.style.display = 'block';
}
</script>



<?php require_once __DIR__ . '/../includes/footer.php'; ?>
