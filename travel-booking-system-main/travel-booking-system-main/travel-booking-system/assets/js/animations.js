document.addEventListener('DOMContentLoaded', () => {
    if (typeof gsap === 'undefined') return;
    gsap.registerPlugin(ScrollTrigger);

    // ── Hero content entrance ──────────────────────────────────────────────
    const heroTl = gsap.timeline({ delay: 0.2 });
    heroTl
        .from('.hero-badge',   { y: -30, opacity: 0, duration: 0.7, ease: 'power3.out' })
        .from('.hero h1',      { y: 50,  opacity: 0, duration: 0.9, ease: 'power3.out' }, '-=0.3')
        .from('.hero p',       { y: 30,  opacity: 0, duration: 0.7, ease: 'power3.out' }, '-=0.5')
        .from('.search-box',   { y: 40,  opacity: 0, duration: 0.8, ease: 'power3.out' }, '-=0.4')
        .from('.hero-dots',    { opacity: 0, duration: 0.5 }, '-=0.2');

    // ── Section headers ────────────────────────────────────────────────────
    gsap.utils.toArray('.section-header').forEach(header => {
        gsap.fromTo(header,
            { opacity: 0, y: 40 },
            { opacity: 1, y: 0, duration: 0.9, ease: 'power3.out',
              scrollTrigger: { trigger: header, start: 'top 88%', once: true } }
        );
    });

    // ── Hotel cards stagger ────────────────────────────────────────────────
    gsap.utils.toArray('.hotels-grid').forEach(grid => {
        const cards = grid.querySelectorAll('.hotel-card');
        gsap.fromTo(cards,
            { opacity: 0, y: 60, scale: 0.95 },
            { opacity: 1, y: 0, scale: 1, duration: 0.7, stagger: 0.12, ease: 'power3.out',
              scrollTrigger: { trigger: grid, start: 'top 85%', once: true } }
        );
    });

    // ── Destination cards ──────────────────────────────────────────────────
    const destCards = document.querySelectorAll('.dest-card');
    if (destCards.length) {
        gsap.fromTo(destCards,
            { opacity: 0, x: 60 },
            { opacity: 1, x: 0, duration: 0.6, stagger: 0.1, ease: 'power3.out',
              scrollTrigger: { trigger: '.destinations-strip', start: 'top 85%', once: true } }
        );
    }

    // ── Feature cards ──────────────────────────────────────────────────────
    gsap.utils.toArray('.feature-card').forEach((card, i) => {
        gsap.fromTo(card,
            { opacity: 0, y: 50 },
            { opacity: 1, y: 0, duration: 0.7, delay: i * 0.12, ease: 'back.out(1.4)',
              scrollTrigger: { trigger: card, start: 'top 88%', once: true } }
        );
    });

    // ── Testimonials ───────────────────────────────────────────────────────
    gsap.utils.toArray('.testimonial-card').forEach((card, i) => {
        gsap.fromTo(card,
            { opacity: 0, y: 40 },
            { opacity: 1, y: 0, duration: 0.7, delay: i * 0.15, ease: 'power3.out',
              scrollTrigger: { trigger: card, start: 'top 88%', once: true } }
        );
    });

    // ── Stats counter ──────────────────────────────────────────────────────
    gsap.utils.toArray('.stat-number').forEach(el => {
        const raw = el.dataset.count;
        if (!raw) return;
        const target = parseInt(raw);
        gsap.fromTo({ val: 0 },
            { val: target, duration: 2.2, ease: 'power2.out',
              onUpdate: function() { el.textContent = Math.floor(this.targets()[0].val).toLocaleString(); },
              scrollTrigger: { trigger: el, start: 'top 85%', once: true }
            }
        );
    });

    // ── Stat cards ─────────────────────────────────────────────────────────
    gsap.utils.toArray('.stat-card').forEach((card, i) => {
        gsap.fromTo(card,
            { opacity: 0, y: 30 },
            { opacity: 1, y: 0, duration: 0.6, delay: i * 0.1, ease: 'power3.out',
              scrollTrigger: { trigger: card, start: 'top 88%', once: true } }
        );
    });

    // ── Booking card ───────────────────────────────────────────────────────
    const bookingCard = document.querySelector('.booking-card');
    if (bookingCard) {
        gsap.fromTo(bookingCard,
            { opacity: 0, x: 60 },
            { opacity: 1, x: 0, duration: 1, ease: 'power3.out', delay: 0.4 }
        );
    }

    // ── Review cards ───────────────────────────────────────────────────────
    gsap.utils.toArray('.review-card').forEach((card, i) => {
        gsap.fromTo(card,
            { opacity: 0, x: -30 },
            { opacity: 1, x: 0, duration: 0.6, delay: i * 0.1, ease: 'power3.out',
              scrollTrigger: { trigger: card, start: 'top 90%', once: true } }
        );
    });

    // ── Table rows ─────────────────────────────────────────────────────────
    gsap.utils.toArray('.data-table tbody tr').forEach((row, i) => {
        gsap.fromTo(row,
            { opacity: 0, x: -20 },
            { opacity: 1, x: 0, duration: 0.4, delay: i * 0.05, ease: 'power2.out',
              scrollTrigger: { trigger: row, start: 'top 95%', once: true } }
        );
    });

    // ── CTA section ────────────────────────────────────────────────────────
    const cta = document.querySelector('.stats-section');
    if (cta) {
        gsap.fromTo(cta.querySelectorAll('.stat-item'),
            { opacity: 0, y: 40 },
            { opacity: 1, y: 0, duration: 0.7, stagger: 0.15, ease: 'power3.out',
              scrollTrigger: { trigger: cta, start: 'top 80%', once: true } }
        );
    }
});
