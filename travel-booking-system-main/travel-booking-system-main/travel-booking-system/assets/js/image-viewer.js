/**
 * TravelLux — Global 360° Image Hover + Detail Modal
 * Works on EVERY image across ALL pages
 */
(function () {
  'use strict';

  /* ── Spinning cursor ring ── */
  var ring = document.createElement('div');
  ring.id = 'ivRing';
  ring.style.cssText = 'position:fixed;width:48px;height:48px;border:2.5px solid rgba(124,58,237,.9);border-top-color:rgba(236,72,153,.9);border-radius:50%;pointer-events:none;z-index:999999;transform:translate(-50%,-50%);animation:ivSpin 1s linear infinite;display:none;transition:width .15s,height .15s';
  document.body.appendChild(ring);

  /* ── Inject keyframe + base styles ── */
  var style = document.createElement('style');
  style.textContent = [
    '@keyframes ivSpin{to{transform:translate(-50%,-50%) rotate(360deg)}}',
    /* wrap */
    '.iv-wrap{position:relative!important;overflow:hidden!important;cursor:none!important}',
    '.iv-wrap img{transition:transform 1.2s cubic-bezier(.25,.46,.45,.94),filter .4s!important;transform-origin:center center!important}',
    '.iv-wrap:hover img{transform:scale(1.1) rotate(5deg)!important;filter:brightness(1.12) saturate(1.25)!important}',
    /* 360 badge */
    '.iv-badge{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%) scale(0);background:rgba(0,0,0,.72);backdrop-filter:blur(8px);color:#fff;font-size:.72rem;font-weight:800;padding:7px 16px;border-radius:50px;border:1.5px solid rgba(255,255,255,.3);pointer-events:none;transition:transform .3s cubic-bezier(.34,1.56,.64,1),opacity .2s;opacity:0;z-index:20;white-space:nowrap;letter-spacing:.5px}',
    '.iv-wrap:hover .iv-badge{transform:translate(-50%,-50%) scale(1)!important;opacity:1!important}',
    /* view detail btn */
    '.iv-btn{position:absolute;bottom:12px;left:50%;transform:translateX(-50%) translateY(60px);background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff;border:none;padding:8px 20px;border-radius:50px;font-size:.75rem;font-weight:800;cursor:pointer;transition:transform .35s cubic-bezier(.34,1.56,.64,1),opacity .25s;opacity:0;z-index:21;white-space:nowrap;box-shadow:0 6px 20px rgba(124,58,237,.5);font-family:inherit;letter-spacing:.3px}',
    '.iv-wrap:hover .iv-btn{transform:translateX(-50%) translateY(0)!important;opacity:1!important}',
    '.iv-btn:hover{box-shadow:0 10px 28px rgba(124,58,237,.7)!important;transform:translateX(-50%) translateY(-2px)!important}',
    /* modal overlay */
    '.iv-ov{position:fixed;inset:0;background:rgba(0,0,0,.88);backdrop-filter:blur(18px);z-index:99998;display:flex;align-items:center;justify-content:center;padding:20px;opacity:0;pointer-events:none;transition:opacity .3s}',
    '.iv-ov.open{opacity:1!important;pointer-events:all!important}',
    /* modal box */
    '.iv-box{background:#1a1a1a;border:1px solid rgba(255,255,255,.1);border-radius:24px;overflow:hidden;max-width:880px;width:100%;max-height:90vh;overflow-y:auto;transform:scale(.88) translateY(28px);transition:transform .4s cubic-bezier(.34,1.56,.64,1);box-shadow:0 40px 120px rgba(0,0,0,.85);position:relative}',
    '.iv-ov.open .iv-box{transform:scale(1) translateY(0)!important}',
    '.iv-close{position:absolute;top:14px;right:14px;width:36px;height:36px;background:rgba(0,0,0,.6);border:none;border-radius:50%;color:#fff;font-size:1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:10;transition:all .2s;border:1px solid rgba(255,255,255,.15)}',
    '.iv-close:hover{background:rgba(236,72,153,.5)!important;transform:rotate(90deg)!important}',
    '.iv-mimg{position:relative;height:320px;overflow:hidden}',
    '.iv-mimg img{width:100%;height:100%;object-fit:cover;transition:transform 8s ease}',
    '.iv-mimg:hover img{transform:scale(1.06)!important}',
    '.iv-mimg-ov{position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.82) 0%,transparent 55%)}',
    '.iv-mbadge{position:absolute;top:14px;left:14px;background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff;padding:5px 14px;border-radius:50px;font-size:.72rem;font-weight:800}',
    '.iv-mrate{position:absolute;top:14px;right:52px;background:rgba(0,0,0,.6);backdrop-filter:blur(8px);color:#f59e0b;padding:5px 12px;border-radius:50px;font-size:.78rem;font-weight:800;border:1px solid rgba(245,158,11,.3)}',
    '.iv-mbody{padding:26px 30px 30px}',
    '.iv-mname{font-family:"Poppins",sans-serif;font-size:1.7rem;font-weight:900;color:#fff;margin-bottom:5px;line-height:1.2}',
    '.iv-mloc{color:rgba(255,255,255,.5);font-size:.88rem;margin-bottom:18px}',
    '.iv-mhls{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:20px}',
    '.iv-mhl{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:12px;text-align:center}',
    '.iv-mhl-i{font-size:1.3rem;margin-bottom:4px}',
    '.iv-mhl-v{font-family:"Poppins",sans-serif;font-size:1rem;font-weight:800;color:#fff;margin-bottom:2px}',
    '.iv-mhl-l{font-size:.65rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.8px}',
    '.iv-mdesc{color:rgba(255,255,255,.62);font-size:.92rem;line-height:1.85;margin-bottom:18px}',
    '.iv-mams{display:flex;flex-wrap:wrap;gap:7px;margin-bottom:24px}',
    '.iv-mam{background:rgba(124,58,237,.12);border:1px solid rgba(124,58,237,.25);color:#a78bfa;padding:5px 13px;border-radius:50px;font-size:.74rem;font-weight:600}',
    '.iv-mfoot{display:flex;align-items:center;justify-content:space-between;padding-top:18px;border-top:1px solid rgba(255,255,255,.08);flex-wrap:wrap;gap:14px}',
    '.iv-mprice{font-family:"Poppins",sans-serif;font-size:2rem;font-weight:900;background:linear-gradient(135deg,#a78bfa,#f9a8d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1}',
    '.iv-mpsub{font-size:.75rem;color:rgba(255,255,255,.38);-webkit-text-fill-color:rgba(255,255,255,.38);margin-top:3px}',
    '.iv-macts{display:flex;gap:10px}',
    '.iv-mbtn1{background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff;border:none;padding:13px 28px;border-radius:50px;font-size:.9rem;font-weight:800;cursor:pointer;transition:all .3s;font-family:inherit;box-shadow:0 8px 24px rgba(124,58,237,.4)}',
    '.iv-mbtn1:hover{transform:translateY(-2px)!important;box-shadow:0 14px 36px rgba(124,58,237,.6)!important}',
    '.iv-mbtn2{background:rgba(255,255,255,.08);color:#fff;border:1px solid rgba(255,255,255,.15);padding:13px 24px;border-radius:50px;font-size:.9rem;font-weight:700;cursor:pointer;transition:all .3s;font-family:inherit}',
    '.iv-mbtn2:hover{background:rgba(255,255,255,.15)!important}',
    '@media(max-width:600px){.iv-mhls{grid-template-columns:repeat(2,1fr)}.iv-mbody{padding:18px}.iv-mimg{height:200px}.iv-mname{font-size:1.3rem}}'
  ].join('');
  document.head.appendChild(style);

  /* ── Build modal (once) ── */
  var ov = document.createElement('div');
  ov.className = 'iv-ov';
  ov.innerHTML =
    '<div class="iv-box">' +
      '<button class="iv-close" id="ivClose">&#10005;</button>' +
      '<div class="iv-mimg">' +
        '<img id="ivMImg" src="" alt="">' +
        '<div class="iv-mimg-ov"></div>' +
        '<span class="iv-mbadge" id="ivMBadge"></span>' +
        '<span class="iv-mrate" id="ivMRate"></span>' +
      '</div>' +
      '<div class="iv-mbody">' +
        '<div class="iv-mname" id="ivMName"></div>' +
        '<div class="iv-mloc" id="ivMLoc"></div>' +
        '<div class="iv-mhls" id="ivMHls"></div>' +
        '<p class="iv-mdesc" id="ivMDesc"></p>' +
        '<div class="iv-mams" id="ivMAms"></div>' +
        '<div class="iv-mfoot">' +
          '<div><div class="iv-mprice" id="ivMPrice"></div><div class="iv-mpsub">per night &bull; taxes included</div></div>' +
          '<div class="iv-macts">' +
            '<button class="iv-mbtn2" id="ivMView">View Details</button>' +
            '<button class="iv-mbtn1" id="ivMBook">&#128197; Book Now</button>' +
          '</div>' +
        '</div>' +
      '</div>' +
    '</div>';
  document.body.appendChild(ov);

  function closeModal() { ov.classList.remove('open'); }
  document.getElementById('ivClose').onclick = closeModal;
  ov.addEventListener('click', function (e) { if (e.target === ov) closeModal(); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });

  /* ── Open modal ── */
  function openModal(d) {
    document.getElementById('ivMImg').src = d.img || '';
    document.getElementById('ivMName').textContent = d.name || 'Luxury Property';
    document.getElementById('ivMLoc').innerHTML = '&#128205; ' + (d.loc || 'Premium Destination');
    document.getElementById('ivMBadge').textContent = (d.stars || '5') + '\u2605 Hotel';
    document.getElementById('ivMRate').textContent = '\u2605 ' + (d.rating || '4.8');
    document.getElementById('ivMDesc').textContent = d.desc ||
      'Experience world-class luxury at this stunning property. Every detail has been carefully crafted to ensure your stay is nothing short of extraordinary. Enjoy premium amenities, breathtaking views, and exceptional service that will make your trip unforgettable.';
    document.getElementById('ivMPrice').textContent = d.price ? '$' + String(d.price).replace(/[^0-9,.]/g, '') : '';
    document.getElementById('ivMHls').innerHTML =
      '<div class="iv-mhl"><div class="iv-mhl-i">&#9733;</div><div class="iv-mhl-v">' + (d.rating || '4.8') + '</div><div class="iv-mhl-l">Rating</div></div>' +
      '<div class="iv-mhl"><div class="iv-mhl-i">&#127968;</div><div class="iv-mhl-v">' + (d.stars || '5') + ' Star</div><div class="iv-mhl-l">Class</div></div>' +
      '<div class="iv-mhl"><div class="iv-mhl-i">&#128197;</div><div class="iv-mhl-v">Free</div><div class="iv-mhl-l">Cancel</div></div>' +
      '<div class="iv-mhl"><div class="iv-mhl-i">&#9992;</div><div class="iv-mhl-v">Yes</div><div class="iv-mhl-l">Flights</div></div>';
    var ams = d.amenities && d.amenities.length ? d.amenities : ['WiFi', 'Pool', 'Spa', 'Restaurant', 'Gym', 'Parking', 'Bar', 'Beach Access'];
    document.getElementById('ivMAms').innerHTML = ams.map(function (a) {
      return '<span class="iv-mam">&#10003; ' + String(a).replace(/[✓✔✅]/g, '').trim() + '</span>';
    }).join('');
    var url = d.url || (d.id ? 'hotel-details.php?id=' + d.id : '#');
    document.getElementById('ivMView').onclick = function () { if (url !== '#') window.location.href = url; };
    document.getElementById('ivMBook').onclick = function () { if (url !== '#') window.location.href = url; };
    ov.classList.add('open');
  }

  /* ── Cursor tracking ── */
  document.addEventListener('mousemove', function (e) {
    ring.style.left = e.clientX + 'px';
    ring.style.top = e.clientY + 'px';
  });

  /* ── Skip these image parents ── */
  var SKIP = ['iv-mimg', 'iv-wrap', 'navbar', 'nav', 'footer', 'planes-layer', 'sky-planes', 'logo', 'leaflet', 'map'];

  function shouldSkip(el) {
    if (!el) return true;
    var cls = (el.className || '') + ' ' + (el.id || '');
    for (var i = 0; i < SKIP.length; i++) {
      if (cls.indexOf(SKIP[i]) !== -1) return true;
    }
    // Skip tiny images (icons, avatars < 80px)
    return false;
  }

  /* ── Attach to one image container ── */
  function attachTo(img) {
    if (!img || img.dataset.ivDone) return;
    if (img.naturalWidth && img.naturalWidth < 80) return;

    var parent = img.closest('.hcard-img, .hotel-card-img, .pkg-img, .dest-item, .dest-card, .testi-card, .feat-card, .hd-hero, .sp, .sp1, .sp2, .sp3');
    if (!parent) parent = img.parentElement;
    if (!parent || shouldSkip(parent)) return;
    if (parent.classList.contains('iv-wrap')) return;

    img.dataset.ivDone = '1';
    parent.classList.add('iv-wrap');

    // 360 badge
    var badge = document.createElement('div');
    badge.className = 'iv-badge';
    badge.textContent = '\u21BB 360\u00B0';
    parent.appendChild(badge);

    // View Detail button
    var btn = document.createElement('button');
    btn.className = 'iv-btn';
    btn.textContent = '\uD83D\uDD0D View Detail';
    parent.appendChild(btn);

    // Cursor ring on/off
    parent.addEventListener('mouseenter', function () { ring.style.display = 'block'; });
    parent.addEventListener('mouseleave', function () { ring.style.display = 'none'; });

    // Collect card data
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();

      // Walk up to find the card root
      var card = parent.closest('.hcard, .hotel-card, .pkg-card, .dest-item, .dest-card, .testi-card, [data-cat], a[href*="hotel-details"]') || parent.parentElement;

      var get = function (sel) {
        var el = card ? card.querySelector(sel) : null;
        return el ? el.textContent.trim() : '';
      };

      var href = '';
      if (card && card.tagName === 'A') href = card.href;
      else if (card) { var a = card.querySelector('a[href*="hotel-details"]'); if (a) href = a.href; }
      var id = href ? (new URLSearchParams((href.split('?')[1] || ''))).get('id') : null;

      var ams = [];
      if (card) {
        card.querySelectorAll('.hcard-tag, .amenity-tag, .inc-tag, .hd-amenity').forEach(function (t) {
          var txt = t.textContent.replace(/[✓✔✅]/g, '').trim();
          if (txt) ams.push(txt);
        });
      }

      openModal({
        img:       img.src,
        name:      get('.hcard-name, .hotel-card-name, .dest-name, .dest-item-name, .pkg-body h3, h3, .iv-mname') || img.alt || 'Luxury Property',
        loc:       get('.hcard-loc, .hotel-card-location, .dest-count, .dest-item-count, .iv-mloc'),
        price:     get('.hcard-price, .hotel-price, .curr, .pkg-price .curr, .iv-mprice'),
        rating:    get('.hcard-star + span, .rating-count'),
        stars:     get('.hcard-badge, .hotel-card-badge, .pkg-badge'),
        amenities: ams,
        url:       href || null,
        id:        id,
      });
    });
  }

  /* ── Init: attach to all current images ── */
  function init() {
    document.querySelectorAll('img').forEach(function (img) {
      // Skip nav/footer/plane images by checking parents
      var p = img.parentElement;
      if (!p) return;
      var skip = false;
      var el = p;
      for (var i = 0; i < 5; i++) {
        if (!el) break;
        var c = (el.className || '') + (el.tagName || '');
        if (/nav|footer|planes|sky-plane|iv-mimg|logo|cursor/i.test(c)) { skip = true; break; }
        el = el.parentElement;
      }
      if (!skip) attachTo(img);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  // Re-run for dynamically added images
  if (window.MutationObserver) {
    new MutationObserver(function (mutations) {
      mutations.forEach(function (m) {
        m.addedNodes.forEach(function (n) {
          if (n.nodeType !== 1) return;
          if (n.tagName === 'IMG') attachTo(n);
          else n.querySelectorAll && n.querySelectorAll('img').forEach(attachTo);
        });
      });
    }).observe(document.body, { childList: true, subtree: true });
  }

})();
