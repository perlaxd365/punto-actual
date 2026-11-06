import './bootstrap';
import './analytics';
function initCarousels() {
    document.querySelectorAll('[data-carousel]').forEach((carousel) => {
        const slides = Array.from(carousel.querySelectorAll('[data-carousel-slide]'));
        const dots = Array.from(carousel.querySelectorAll('[data-carousel-dot]'));

        if (slides.length < 2) {
            return;
        }

        const interval = Number(carousel.dataset.interval) || 5000;
        let index = 0;
        let timer = null;

        const show = (next) => {
            slides.forEach((slide, i) => {
                const active = i === next;
                slide.classList.toggle('is-active', active);
                slide.setAttribute('aria-hidden', active ? 'false' : 'true');
            });

            dots.forEach((dot, i) => dot.classList.toggle('is-active', i === next));
            index = next;
        };

        const next = () => show((index + 1) % slides.length);

        const start = () => {
            stop();
            timer = window.setInterval(next, interval);
        };

        const stop = () => {
            if (timer) {
                window.clearInterval(timer);
                timer = null;
            }
        };

        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                show(i);
                start();
            });
        });

        carousel.addEventListener('mouseenter', stop);
        carousel.addEventListener('mouseleave', start);

        start();
    });
}

function initSliders() {
    document.querySelectorAll('[data-slider]').forEach((slider) => {
        const track = slider.querySelector('.slider-track');
        const slides = Array.from(slider.querySelectorAll('.slider-slide'));
        const prev = slider.querySelector('[data-slider-prev]');
        const next = slider.querySelector('[data-slider-next]');
        const dots = Array.from(slider.querySelectorAll('[data-slider-dot]'));

        if (!track || slides.length < 2) {
            return;
        }

        const interval = Number(slider.dataset.interval) || 5000;
        let index = 0;
        let timer = null;

        const render = () => {
            track.style.transform = `translateX(-${index * 100}%)`;
            dots.forEach((dot, i) => dot.classList.toggle('is-active', i === index));
            slides.forEach((slide, i) => slide.setAttribute('aria-hidden', i === index ? 'false' : 'true'));
        };

        const go = (n) => {
            index = (n + slides.length) % slides.length;
            render();
        };

        const stop = () => {
            if (timer) {
                window.clearInterval(timer);
                timer = null;
            }
        };

        const start = () => {
            stop();
            timer = window.setInterval(() => go(index + 1), interval);
        };

        prev?.addEventListener('click', () => {
            go(index - 1);
            start();
        });

        next?.addEventListener('click', () => {
            go(index + 1);
            start();
        });

        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                go(i);
                start();
            });
        });

        slider.addEventListener('mouseenter', stop);
        slider.addEventListener('mouseleave', start);

        render();
        start();
    });
}

function initReveal() {
    const elements = Array.from(document.querySelectorAll('[data-reveal]'));

    if (! elements.length) {
        return;
    }

    const revealAll = () => elements.forEach((element) => element.classList.add('is-visible'));

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || ! ('IntersectionObserver' in window)) {
        revealAll();
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -48px 0px' });

    elements.forEach((element) => observer.observe(element));

    window.setTimeout(revealAll, 3000);
}

function initScrollProgress() {
    const bar = document.getElementById('scroll-progress');

    if (! bar) {
        return;
    }

    const update = () => {
        const max = document.documentElement.scrollHeight - window.innerHeight;
        const progress = max > 0 ? Math.min(window.scrollY / max, 1) : 0;
        bar.style.transform = `scaleX(${progress})`;
    };

    update();
    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
}

function initInteractive() {
    initCarousels();
    initSliders();
    initReveal();
    initScrollProgress();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initInteractive);
} else {
    initInteractive();
}
