/* ===== TravelLux — Interactive Map with Country Image Panel ===== */

// Country → landmark image mapping (Unsplash curated)
const COUNTRY_IMAGES = {
  'France':       'https://images.unsplash.com/photo-1499856871958-5b9627545d1a?w=800&q=85',
  'Japan':        'https://images.unsplash.com/photo-1536098561742-ca998e48cbcc?w=800&q=85',
  'Indonesia':    'https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=800&q=85',
  'Maldives':     'https://images.unsplash.com/photo-1514282401047-d79a71a590e8?w=800&q=85',
  'Greece':       'https://images.unsplash.com/photo-1613395877344-13d4a8e0d49e?w=800&q=85',
  'UAE':          'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=800&q=85',
  'Thailand':     'https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=800&q=85',
  'Italy':        'https://images.unsplash.com/photo-1523906834658-6e24ef2386f9?w=800&q=85',
  'Spain':        'https://images.unsplash.com/photo-1543783207-ec64e4d95325?w=800&q=85',
  'USA':          'https://images.unsplash.com/photo-1485738422979-f5c462d49f74?w=800&q=85',
  'UK':           'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?w=800&q=85',
  'Australia':    'https://images.unsplash.com/photo-1506973035872-a4ec16b8e8d9?w=800&q=85',
  'Brazil':       'https://images.unsplash.com/photo-1483729558449-99ef09a8c325?w=800&q=85',
  'India':        'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?w=800&q=85',
  'Switzerland':  'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&q=85',
  'Turkey':       'https://images.unsplash.com/photo-1541432901042-2d8bd64b4a9b?w=800&q=85',
  'Mexico':       'https://images.unsplash.com/photo-1518638150340-f706e86654de?w=800&q=85',
  'Canada':       'https://images.unsplash.com/photo-1517935706615-2717063c2225?w=800&q=85',
  'Singapore':    'https://images.unsplash.com/photo-1525625293386-3f8f99389edd?w=800&q=85',
  'Morocco':      'https://images.unsplash.com/photo-1539020140153-e479b8c22e70?w=800&q=85',
  'Egypt':        'https://images.unsplash.com/photo-1539650116574-75c0c6d73f6e?w=800&q=85',
  'South Africa': 'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?w=800&q=85',
  'New Zealand':  'https://images.unsplash.com/photo-1507699622108-4be3abd695ad?w=800&q=85',
  'Portugal':     'https://images.unsplash.com/photo-1555881400-74d7acaacd8b?w=800&q=85',
  'Netherlands':  'https://images.unsplash.com/photo-1534351590666-13e3e96b5017?w=800&q=85',
  'Germany':      'https://images.unsplash.com/photo-1467269204594-9661b134dd2b?w=800&q=85',
  'China':        'https://images.unsplash.com/photo-1508804185872-d7badad00f7d?w=800&q=85',
  'Sri Lanka':    'https://images.unsplash.com/photo-1546708770-599a3abdf230?w=800&q=85',
  'Vietnam':      'https://images.unsplash.com/photo-1528360983277-13d401cdc186?w=800&q=85',
  'Peru':         'https://images.unsplash.com/photo-1526392060635-9d6019884377?w=800&q=85',
};

// City → country lookup
const CITY_COUNTRY = {
  'Paris':'France','Lyon':'France','Nice':'France',
  'Tokyo':'Japan','Kyoto':'Japan','Osaka':'Japan',
  'Bali':'Indonesia','Jakarta':'Indonesia',
  'Maldives':'Maldives','Male':'Maldives',
  'Santorini':'Greece','Athens':'Greece','Mykonos':'Greece',
  'Dubai':'UAE','Abu Dhabi':'UAE',
  'Bangkok':'Thailand','Phuket':'Thailand','Chiang Mai':'Thailand',
  'Rome':'Italy','Venice':'Italy','Florence':'Italy','Milan':'Italy',
  'Barcelona':'Spain','Madrid':'Spain','Seville':'Spain',
  'New York':'USA','Los Angeles':'USA','Miami':'USA','Las Vegas':'USA',
  'London':'UK','Edinburgh':'UK',
  'Sydney':'Australia','Melbourne':'Australia',
  'Rio de Janeiro':'Brazil','São Paulo':'Brazil',
  'Mumbai':'India','Delhi':'India','Goa':'India','Jaipur':'India',
  'Zurich':'Switzerland','Geneva':'Switzerland','Interlaken':'Switzerland',
  'Istanbul':'Turkey','Cappadocia':'Turkey',
  'Cancun':'Mexico','Mexico City':'Mexico',
  'Toronto':'Canada','Vancouver':'Canada',
  'Singapore':'Singapore',
  'Marrakech':'Morocco','Casablanca':'Morocco',
  'Cairo':'Egypt','Luxor':'Egypt',
  'Cape Town':'South Africa',
  'Queenstown':'New Zealand','Auckland':'New Zealand',
  'Lisbon':'Portugal','Porto':'Portugal',
  'Amsterdam':'Netherlands',
  'Berlin':'Germany','Munich':'Germany',
  'Shanghai':'China','Beijing':'China',
  'Colombo':'Sri Lanka',
  'Hanoi':'Vietnam','Ho Chi Minh City':'Vietnam',
  'Lima':'Peru','Cusco':'Peru',
};

document.addEventListener('DOMContentLoaded', () => {
  const mapEl = document.getElementById('map');
  if (!mapEl || typeof L === 'undefined') return;

  injectMapStyles();

  if (typeof hotelLat !== 'undefined' && hotelLat && hotelLng) {
    initSingleMap(hotelLat, hotelLng, hotelName, hotelPrice);
  } else if (typeof mapHotels !== 'undefined' && mapHotels.length > 0) {
    initMultiMap(mapHotels);
  }
});

function getDarkTiles() {
  return L.tileLayer(
    'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png',
    {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      maxZoom: 19,
    }
  );
}

function makePriceMarker(price, color, glowColor) {
  return L.divIcon({
    html: `<div class="map-marker-wrap" style="--mc:${color};--mg:${glowColor};">
             <div class="map-marker-pulse"></div>
             <div class="map-marker-body">
               <span class="map-marker-price">$${Math.round(price)}</span>
             </div>
             <div class="map-marker-arrow"></div>
           </div>`,
    className: '',
    iconSize:   [80, 44],
    iconAnchor: [40, 44],
    popupAnchor:[0, -52],
  });
}

function initSingleMap(lat, lng, name, price) {
  const map = L.map('map', { zoomControl: false }).setView([lat, lng], 14);
  getDarkTiles().addTo(map);
  L.control.zoom({ position: 'bottomright' }).addTo(map);

  const marker = L.marker([lat, lng], {
    icon: makePriceMarker(price || 0, '#A78BFA', 'rgba(167,139,250,0.6)')
  }).addTo(map);

  const countryImg2 = COUNTRY_IMAGES[country] || 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=800&q=80';
    const hotelImg = hotel.image || countryImg2;
    marker.bindPopup(`
      <div class="map-popup">
        <div style="position:relative">
          <img src="${hotelImg}" alt="${hotel.name}" class="map-popup-img" onerror="this.src='${countryImg2}'">
          <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.75) 0%,transparent 55%)"></div>
          <div style="position:absolute;bottom:8px;left:10px;background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff;padding:3px 10px;border-radius:50px;font-size:.65rem;font-weight:800">${hotel.stars||5}&#9733; Hotel</div>
          <div style="position:absolute;top:8px;right:8px;background:rgba(0,0,0,.65);color:#f59e0b;padding:3px 10px;border-radius:50px;font-size:.68rem;font-weight:800;border:1px solid rgba(245,158,11,.3)">&#9733; ${hotel.rating||'4.8'}</div>
        </div>
        <div class="map-popup-body">
          <div class="map-popup-name">${hotel.name}</div>
          <div class="map-popup-loc">&#128205; ${hotel.city}, ${hotel.country}</div>
          <div style="display:flex;gap:5px;flex-wrap:wrap;margin-bottom:10px">
            <span style="background:rgba(124,58,237,.15);border:1px solid rgba(124,58,237,.3);color:#a78bfa;padding:3px 9px;border-radius:50px;font-size:.65rem;font-weight:600">WiFi</span>
            <span style="background:rgba(124,58,237,.15);border:1px solid rgba(124,58,237,.3);color:#a78bfa;padding:3px 9px;border-radius:50px;font-size:.65rem;font-weight:600">Pool</span>
            <span style="background:rgba(124,58,237,.15);border:1px solid rgba(124,58,237,.3);color:#a78bfa;padding:3px 9px;border-radius:50px;font-size:.65rem;font-weight:600">Spa</span>
          </div>
          <div class="map-popup-row">
            <div class="map-popup-price">$${Math.round(hotel.price)}<span>/night</span></div>
            <a href="hotel-details.php?id=${hotel.id}" class="map-popup-btn">View &#8594;</a>
          </div>
        </div>
      </div>`, { className: 'map-popup-wrap', maxWidth: 280 });

    marker.on('mouseover', function() {
      this.openPopup();
      const c = CITY_COUNTRY[hotel.city] || hotel.country || hotel.city;
      showCountryPanel(c, hotel.city);
    });

    marker.on('click', function() {
      map.flyTo([hotel.lat, hotel.lng], 10, { duration: 1.5 });
      const c = CITY_COUNTRY[hotel.city] || hotel.country || hotel.city;
      showCountryPanel(c, hotel.city);
    });

    bounds.push([hotel.lat, hotel.lng]);
  });

  if (bounds.length > 1) {
    map.fitBounds(bounds, { padding: [50, 50] });
  } else {
    map.setView(bounds[0], 10);
  }
}

/* ── Country Image Panel ── */
function createCountryPanel() {
  if (document.getElementById('countryPanel')) return;
  const panel = document.createElement('div');
  panel.id = 'countryPanel';
  panel.innerHTML = `
    <div id="cpClose" onclick="hideCountryPanel()">✕</div>
    <img id="cpImg" src="" alt="">
    <div id="cpOverlay">
      <div id="cpName"></div>
      <div id="cpSub"></div>
      <a id="cpBtn" href="#">Explore Hotels →</a>
    </div>`;
  document.body.appendChild(panel);
}

function showCountryPanel(country, city) {
  const panel = document.getElementById('countryPanel');
  if (!panel) return;
  const img = COUNTRY_IMAGES[country] || `https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=800&q=80`;
  document.getElementById('cpImg').src = img;
  document.getElementById('cpName').textContent = country;
  document.getElementById('cpSub').textContent = '📍 ' + city;
  document.getElementById('cpBtn').href = 'search.php?city=' + encodeURIComponent(city);
  panel.classList.add('cp-visible');
}

function hideCountryPanel() {
  const panel = document.getElementById('countryPanel');
  if (panel) panel.classList.remove('cp-visible');
}

/* ── All styles ── */
function injectMapStyles() {
  if (document.getElementById('mapStyles')) return;
  const style = document.createElement('style');
  style.id = 'mapStyles';
  style.textContent = `
    #map {
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(124,58,237,0.2);
    }
    .leaflet-control-attribution {
      background: rgba(8,8,24,0.8) !important;
      backdrop-filter: blur(10px);
      color: rgba(255,255,255,0.4) !important;
      border-radius: 8px 0 0 0 !important;
      font-size: 0.65rem !important;
      padding: 4px 8px !important;
    }
    .leaflet-control-attribution a { color: rgba(167,139,250,0.7) !important; }
    .leaflet-control-zoom {
      border: none !important;
      box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important;
      border-radius: 12px !important;
      overflow: hidden;
    }
    .leaflet-control-zoom a {
      background: rgba(15,12,41,0.9) !important;
      backdrop-filter: blur(10px);
      color: rgba(255,255,255,0.8) !important;
      border: none !important;
      border-bottom: 1px solid rgba(255,255,255,0.08) !important;
      width: 36px !important; height: 36px !important; line-height: 36px !important;
      font-size: 1.1rem !important; transition: all 0.2s !important;
    }
    .leaflet-control-zoom a:hover { background: rgba(124,58,237,0.4) !important; color: #fff !important; }
    .leaflet-control-zoom-out { border-bottom: none !important; }

    /* Marker */
    .map-marker-wrap { position:relative; display:flex; flex-direction:column; align-items:center; cursor:pointer; }
    .map-marker-pulse { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:60px; height:60px; border-radius:50%; background:var(--mg); animation:markerPulse 2.5s ease-out infinite; pointer-events:none; }
    @keyframes markerPulse { 0%{transform:translate(-50%,-50%) scale(.5);opacity:.8} 100%{transform:translate(-50%,-50%) scale(2.2);opacity:0} }
    .map-marker-body { background:linear-gradient(135deg,var(--mc),#EC4899); border:2px solid rgba(255,255,255,.3); border-radius:10px; padding:6px 12px; box-shadow:0 4px 20px var(--mg); position:relative; z-index:1; transition:transform .2s,box-shadow .2s; white-space:nowrap; }
    .map-marker-wrap:hover .map-marker-body { transform:scale(1.12) translateY(-3px); box-shadow:0 8px 30px var(--mg); }
    .map-marker-price { color:#fff; font-weight:900; font-size:.85rem; font-family:'Poppins',sans-serif; }
    .map-marker-arrow { width:0; height:0; border-left:7px solid transparent; border-right:7px solid transparent; border-top:8px solid var(--mc); margin-top:-1px; filter:drop-shadow(0 3px 6px var(--mg)); }

    /* Popup */
    .map-popup-wrap .leaflet-popup-content-wrapper { background:rgba(15,12,41,.95) !important; backdrop-filter:blur(20px) !important; border:1px solid rgba(124,58,237,.3) !important; border-radius:16px !important; box-shadow:0 20px 60px rgba(0,0,0,.6) !important; padding:0 !important; overflow:hidden; }
    .map-popup-wrap .leaflet-popup-tip-container { display:none; }
    .map-popup-wrap .leaflet-popup-content { margin:0 !important; }
    .map-popup-wrap .leaflet-popup-close-button { color:rgba(255,255,255,.5) !important; font-size:1.2rem !important; top:8px !important; right:10px !important; z-index:10; }
    .map-popup-wrap .leaflet-popup-close-button:hover { color:#fff !important; }
    .map-popup-img { width:100%; height:140px; object-fit:cover; display:block; }
    .map-popup-body { padding:14px 16px; }
    .map-popup-name { color:#fff; font-weight:700; font-size:.95rem; margin-bottom:4px; line-height:1.3; }
    .map-popup-loc { color:rgba(255,255,255,.5); font-size:.75rem; margin-bottom:12px; }
    .map-popup-row { display:flex; align-items:center; justify-content:space-between; }
    .map-popup-price { font-family:'Poppins',sans-serif; font-size:1.2rem; font-weight:900; background:linear-gradient(135deg,#A78BFA,#F9A8D4); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
    .map-popup-price span { font-size:.72rem; font-weight:400; color:rgba(255,255,255,.4); -webkit-text-fill-color:rgba(255,255,255,.4); }
    .map-popup-btn { background:linear-gradient(135deg,#7C3AED,#EC4899); color:#fff !important; padding:6px 14px; border-radius:8px; font-size:.78rem; font-weight:700; text-decoration:none; transition:all .2s; }
    .map-popup-btn:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(124,58,237,.6); }

    /* ── Country Panel ── */
    #countryPanel {
      position: fixed;
      bottom: 32px;
      right: 32px;
      width: 320px;
      border-radius: 20px;
      overflow: hidden;
      background: rgba(10,10,20,.95);
      backdrop-filter: blur(24px);
      border: 1px solid rgba(124,58,237,.4);
      box-shadow: 0 24px 80px rgba(0,0,0,.7), 0 0 0 1px rgba(167,139,250,.15);
      z-index: 9999;
      transform: translateY(120%) scale(.95);
      opacity: 0;
      transition: transform .45s cubic-bezier(.22,1,.36,1), opacity .35s ease;
      pointer-events: none;
    }
    #countryPanel.cp-visible {
      transform: translateY(0) scale(1);
      opacity: 1;
      pointer-events: all;
    }
    #cpClose {
      position: absolute;
      top: 10px; right: 12px;
      color: rgba(255,255,255,.6);
      font-size: 1rem;
      cursor: pointer;
      z-index: 2;
      width: 28px; height: 28px;
      display: flex; align-items: center; justify-content: center;
      background: rgba(0,0,0,.4);
      border-radius: 50%;
      transition: all .2s;
    }
    #cpClose:hover { background: rgba(236,72,153,.4); color: #fff; }
    #cpImg {
      width: 100%;
      height: 180px;
      object-fit: cover;
      display: block;
      transition: opacity .4s ease;
    }
    #cpOverlay {
      padding: 16px 18px 18px;
    }
    #cpName {
      font-family: 'Poppins', sans-serif;
      font-size: 1.3rem;
      font-weight: 800;
      color: #fff;
      margin-bottom: 4px;
      background: linear-gradient(135deg,#a78bfa,#f9a8d4);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    #cpSub {
      color: rgba(255,255,255,.55);
      font-size: .82rem;
      margin-bottom: 14px;
    }
    #cpBtn {
      display: inline-block;
      background: linear-gradient(135deg,#7c3aed,#ec4899);
      color: #fff;
      padding: 9px 22px;
      border-radius: 50px;
      font-size: .85rem;
      font-weight: 700;
      text-decoration: none;
      transition: all .25s;
      box-shadow: 0 6px 20px rgba(124,58,237,.4);
    }
    #cpBtn:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(124,58,237,.6); }
  `;
  document.head.appendChild(style);
}
