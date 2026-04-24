document.addEventListener('DOMContentLoaded', () => {
    initAOS();
    initNavbar();
    initNavToggle();
    initHeroSlider();
    initParticles();
    initScrollReveal();
    initCounters();
    initRipple();
    initStarPicker();
    initDateDefaults();
    initFavourites();
});

// ===== AOS =====
function initAOS() {
    if (typeof AOS !== 'undefined') {
        AOS.init({ duration: 750, once: true, offset: 70, easing: 'ease-out-cubic' });
    }
}

// ===== NAVBAR =====
function initNavbar() {
    const navbar = document.getElementById('navbar');
    if (!navbar) return;
    const update = () => navbar.classList.toggle('scrolled', window.scrollY > 60);
    window.addEventListener('scroll', update, { passive: true });
    update();
}

// ===== MOBILE NAV =====
function initNavToggle() {
    const toggle = document.getElementById('navToggle');
    const links  = document.getElementById('navLinks');
    if (!toggle || !links) return;
    toggle.addEventListener('click', () => {
        links.classList.toggle('open');
        toggle.classList.toggle('open');
    });
    links.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
        links.classList.remove('open');
        toggle.classList.remove('open');
    }));
}

// ===== HERO SLIDESHOW =====
function initHeroSlider() {
    const slides = document.querySelectorAll('.hero-slide');
    const dots   = document.querySelectorAll('.hero-dot');
    if (!slides.length) return;

    let current = 0;
    let timer;

    function goTo(idx) {
        slides[current].classList.remove('active');
        dots[current]?.classList.remove('active');
        current = (idx + slides.length) % slides.length;
        slides[current].classList.add('active');
        dots[current]?.classList.add('active');
    }

    function next() { goTo(current + 1); }

    function startAuto() { timer = setInterval(next, 5000); }
    function resetAuto()  { clearInterval(timer); startAuto(); }

    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            goTo(parseInt(dot.dataset.slide));
            resetAuto();
        });
    });

    startAuto();
}

// ===== PARTICLES =====
function initParticles() {
    const container = document.getElementById('heroParticles');
    if (!container) return;

    const colors = ['rgba(124,58,237,0.6)', 'rgba(236,72,153,0.5)', 'rgba(6,182,212,0.5)', 'rgba(245,158,11,0.5)', 'rgba(255,255,255,0.4)'];

    for (let i = 0; i < 28; i++) {
        const p = document.createElement('div');
        p.className = 'particle';
        const size = Math.random() * 6 + 2;
        const x    = Math.random() * 100;
        const dur  = Math.random() * 8 + 5;
        const del  = Math.random() * 8;
        const tx   = (Math.random() - 0.5) * 120;
        const col  = colors[Math.floor(Math.random() * colors.length)];
        p.style.cssText = `
            left:${x}%; bottom:-10px; width:${size}px; height:${size}px;
            background:${col}; --dur:${dur}s; --delay:${del}s; --tx:${tx}px;
            border-radius:50%; animation-delay:${del}s;
        `;
        container.appendChild(p);
    }
}

// ===== SCROLL REVEAL =====
function initScrollReveal() {
    const els = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');
    if (!els.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                observer.unobserve(e.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    els.forEach(el => observer.observe(el));
}

// ===== COUNTERS =====
function initCounters() {
    const counters = document.querySelectorAll('[data-count]');
    if (!counters.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const el     = entry.target;
            const target = parseInt(el.dataset.count);
            const dur    = 2200;
            const step   = target / (dur / 16);
            let current  = 0;
            const timer  = setInterval(() => {
                current = Math.min(current + step, target);
                el.textContent = Math.floor(current).toLocaleString() + (el.dataset.suffix || '');
                if (current >= target) clearInterval(timer);
            }, 16);
            observer.unobserve(el);
        });
    }, { threshold: 0.5 });

    counters.forEach(c => observer.observe(c));
}

// ===== RIPPLE =====
function initRipple() {
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            ripple.className = 'ripple-effect';
            const rect = this.getBoundingClientRect();
            ripple.style.left = (e.clientX - rect.left) + 'px';
            ripple.style.top  = (e.clientY - rect.top) + 'px';
            this.appendChild(ripple);
            setTimeout(() => ripple.remove(), 700);
        });
    });
}

// ===== STAR PICKER =====
function initStarPicker() {
    const picker = document.getElementById('starPicker');
    const input  = document.getElementById('ratingInput');
    if (!picker || !input) return;

    const stars  = picker.querySelectorAll('.star-pick');
    let selected = 5;

    const highlight = (val) => {
        stars.forEach((s, i) => {
            s.style.color = i < val ? '#F59E0B' : '#D1D5DB';
            s.style.textShadow = i < val ? '0 0 8px rgba(245,158,11,0.5)' : 'none';
        });
    };

    highlight(selected);
    stars.forEach((star, i) => {
        star.addEventListener('mouseenter', () => highlight(i + 1));
        star.addEventListener('mouseleave', () => highlight(selected));
        star.addEventListener('click', () => { selected = i + 1; input.value = selected; highlight(selected); });
    });
}

// ===== DATE DEFAULTS =====
function initDateDefaults() {
    const checkIn  = document.getElementById('checkIn')  || document.getElementById('bookCheckIn');
    const checkOut = document.getElementById('checkOut') || document.getElementById('bookCheckOut');
    if (!checkIn || !checkOut) return;

    const fmt = d => d.toISOString().split('T')[0];
    const tomorrow = new Date(); tomorrow.setDate(tomorrow.getDate() + 1);
    const dayAfter  = new Date(); dayAfter.setDate(dayAfter.getDate() + 2);

    if (!checkIn.value)  checkIn.value  = fmt(tomorrow);
    if (!checkOut.value) checkOut.value = fmt(dayAfter);

    checkIn.addEventListener('change', () => {
        const minOut = new Date(checkIn.value);
        minOut.setDate(minOut.getDate() + 1);
        checkOut.min = fmt(minOut);
        if (checkOut.value <= checkIn.value) checkOut.value = fmt(minOut);
    });
}

// ===== FAVOURITES =====
function initFavourites() {
    const favs = JSON.parse(localStorage.getItem('favHotels') || '[]');
    document.querySelectorAll('.hotel-card-fav').forEach(btn => {
        const link = btn.closest('a');
        if (!link) return;
        const id = new URL(link.href).searchParams.get('id');
        if (favs.includes(id)) { btn.textContent = '♥'; btn.classList.add('active'); }
    });
}

function toggleFav(btn) {
    const link = btn.closest('a');
    if (!link) return;
    const id   = new URL(link.href).searchParams.get('id');
    let favs   = JSON.parse(localStorage.getItem('favHotels') || '[]');
    if (favs.includes(id)) {
        favs = favs.filter(f => f !== id);
        btn.textContent = '♡'; btn.classList.remove('active');
        showToast('Removed from favourites', 'success');
    } else {
        favs.push(id);
        btn.textContent = '♥'; btn.classList.add('active');
        showToast('Added to favourites ♥', 'success');
    }
    localStorage.setItem('favHotels', JSON.stringify(favs));
}

// ===== TOAST =====
function showToast(message, type = 'success') {
    const existing = document.querySelector('.toast');
    if (existing) existing.remove();
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `<span>${type === 'success' ? '✓' : '✕'}</span> ${message}`;
    document.body.appendChild(toast);
    setTimeout(() => { toast.classList.add('hide'); setTimeout(() => toast.remove(), 400); }, 3000);
}
