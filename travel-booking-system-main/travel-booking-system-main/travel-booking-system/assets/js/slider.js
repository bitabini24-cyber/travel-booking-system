/**
 * Image slider / carousel for hotel galleries and hero sections
 */

class Slider {
    constructor(el, options = {}) {
        this.el       = typeof el === 'string' ? document.querySelector(el) : el;
        if (!this.el) return;
        this.slides   = Array.from(this.el.querySelectorAll('.slide'));
        this.current  = 0;
        this.auto     = options.auto ?? true;
        this.interval = options.interval ?? 4000;
        this.timer    = null;

        if (this.slides.length < 2) return;
        this.build();
        if (this.auto) this.startAuto();
    }

    build() {
        // Dots
        const dots = document.createElement('div');
        dots.className = 'slider-dots';
        this.slides.forEach((_, i) => {
            const dot = document.createElement('button');
            dot.className = 'slider-dot' + (i === 0 ? ' active' : '');
            dot.setAttribute('aria-label', `Slide ${i + 1}`);
            dot.addEventListener('click', () => this.goTo(i));
            dots.appendChild(dot);
        });
        this.el.appendChild(dots);

        // Arrows
        ['prev', 'next'].forEach(dir => {
            const btn = document.createElement('button');
            btn.className = `slider-arrow slider-${dir}`;
            btn.innerHTML = dir === 'prev' ? '&#8249;' : '&#8250;';
            btn.setAttribute('aria-label', dir === 'prev' ? 'Previous' : 'Next');
            btn.addEventListener('click', () => dir === 'prev' ? this.prev() : this.next());
            this.el.appendChild(btn);
        });

        // Touch support
        let startX = 0;
        this.el.addEventListener('touchstart', e => { startX = e.touches[0].clientX; }, { passive: true });
        this.el.addEventListener('touchend', e => {
            const diff = startX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 50) diff > 0 ? this.next() : this.prev();
        });

        this.update();
    }

    goTo(index) {
        this.slides[this.current].classList.remove('active');
        this.el.querySelectorAll('.slider-dot')[this.current]?.classList.remove('active');
        this.current = (index + this.slides.length) % this.slides.length;
        this.update();
        this.resetAuto();
    }

    update() {
        this.slides.forEach((s, i) => s.classList.toggle('active', i === this.current));
        this.el.querySelectorAll('.slider-dot').forEach((d, i) => d.classList.toggle('active', i === this.current));
    }

    next() { this.goTo(this.current + 1); }
    prev() { this.goTo(this.current - 1); }

    startAuto() { this.timer = setInterval(() => this.next(), this.interval); }
    resetAuto()  { if (this.auto) { clearInterval(this.timer); this.startAuto(); } }
    destroy()    { clearInterval(this.timer); }
}

// ===== SLIDER CSS (injected) =====
const sliderCSS = `
.slider-wrap { position: relative; overflow: hidden; }
.slide { display: none; animation: fadeIn 0.5s ease; }
.slide.active { display: block; }
.slider-dots { position: absolute; bottom: 16px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px; z-index: 10; }
.slider-dot { width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,0.5); border: none; cursor: pointer; transition: all 0.3s ease; padding: 0; }
.slider-dot.active { background: white; width: 24px; border-radius: 4px; }
.slider-arrow { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.9); border: none; width: 44px; height: 44px; border-radius: 50%; font-size: 1.5rem; cursor: pointer; z-index: 10; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; }
.slider-arrow:hover { background: white; transform: translateY(-50%) scale(1.1); }
.slider-prev { left: 16px; }
.slider-next { right: 16px; }
`;

const styleTag = document.createElement('style');
styleTag.textContent = sliderCSS;
document.head.appendChild(styleTag);

// Auto-init any .slider-wrap elements
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.slider-wrap').forEach(el => new Slider(el));
});
