/**
 * AJAX helpers for dynamic content loading
 */

const Ajax = {
    /**
     * Generic fetch wrapper with JSON support
     */
    async request(url, options = {}) {
        const defaults = {
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            ...options
        };
        try {
            const res = await fetch(url, defaults);
            const data = await res.json();
            if (!res.ok) throw new Error(data.error || 'Request failed');
            return data;
        } catch (err) {
            console.error('Ajax error:', err);
            throw err;
        }
    },

    get(url) { return this.request(url); },

    post(url, body) {
        return this.request(url, { method: 'POST', body: JSON.stringify(body) });
    },

    postForm(url, formData) {
        return this.request(url, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });
    }
};

/**
 * Live hotel search with debounce
 */
function initLiveSearch() {
    const input = document.getElementById('cityInput');
    const grid  = document.getElementById('hotelsGrid');
    if (!input || !grid) return;

    let timer;
    input.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(async () => {
            const city = input.value.trim();
            if (city.length < 2) return;
            try {
                grid.innerHTML = '<div class="spinner"></div>';
                const data = await Ajax.get(`${APP_URL}/api/v1/hotels.php?city=${encodeURIComponent(city)}&limit=6`);
                renderHotelCards(data.data, grid);
            } catch {
                grid.innerHTML = '<p style="text-align:center;color:var(--text-light);">Search failed. Try again.</p>';
            }
        }, 400);
    });
}

/**
 * Render hotel cards from API data
 */
function renderHotelCards(hotels, container) {
    if (!hotels?.length) {
        container.innerHTML = '<div class="empty-state"><div class="empty-icon">🏨</div><p>No hotels found.</p></div>';
        return;
    }
    container.innerHTML = hotels.map(h => `
        <a href="${APP_URL}/pages/hotel-details.php?id=${h.id}" class="hotel-card">
            <div class="hotel-card-img">
                <img src="${h.image}" alt="${h.name}" loading="lazy">
                <span class="hotel-card-badge">${h.stars || ''}★</span>
            </div>
            <div class="hotel-card-body">
                <div class="hotel-card-location">📍 ${h.city}, ${h.country}</div>
                <div class="hotel-card-name">${h.name}</div>
                <div class="hotel-card-footer">
                    <div class="hotel-price">$${parseFloat(h.price).toFixed(0)}<span>/night</span></div>
                    <span class="btn btn-primary btn-sm">View</span>
                </div>
            </div>
        </a>
    `).join('');
}

/**
 * Check availability via AJAX before form submit
 */
async function checkAvailability(hotelId, checkIn, checkOut) {
    try {
        const data = await Ajax.get(
            `${APP_URL}/api/v1/bookings.php?check=availability&hotel_id=${hotelId}&check_in=${checkIn}&check_out=${checkOut}`
        );
        return data.available ?? true;
    } catch {
        return true; // Fail open, let server validate
    }
}

// Expose APP_URL to JS (set in header)
const APP_URL = document.querySelector('meta[name="app-url"]')?.content || '';

document.addEventListener('DOMContentLoaded', initLiveSearch);
