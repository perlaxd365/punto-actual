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

function initInteractive() {
    initCarousels();
    initSliders();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initInteractive);
} else {
    initInteractive();
}
