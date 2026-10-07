// Scroll-driven polish: the road-trip journey, reveals, count-up numbers, hero parallax.
const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/** Animate "[data-count]" elements from 0 to their value once they scroll into view. */
function observeCounters(scope) {
    const format = (n, el) => (el.dataset.prefix ?? '') + Math.round(n).toLocaleString('en-PH') + (el.dataset.suffix ?? '');

    const counters = scope.querySelectorAll('[data-count]');
    if (!counters.length) return;

    const io = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            io.unobserve(entry.target);

            const el = entry.target;
            const target = Number(el.dataset.count);
            if (reduceMotion) { el.textContent = format(target, el); return; }

            const start = performance.now();
            const duration = 1100;
            const tick = (now) => {
                const k = Math.min(1, (now - start) / duration);
                el.textContent = format(target * (1 - Math.pow(1 - k, 3)), el);
                if (k < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        });
    }, { threshold: 0.6 });

    counters.forEach((el) => io.observe(el));
}

const root = document.querySelector('[data-journey]');

if (root) {
    document.documentElement.classList.add('reveal-ready');

    const reveal = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    reveal.unobserve(entry.target);
                }
            });
        },
        { rootMargin: '0px 0px -10% 0px', threshold: 0.1 },
    );
    root.querySelectorAll('[data-reveal]').forEach((el) => reveal.observe(el));
    observeCounters(root);

    const waypoints = [...root.querySelectorAll('[data-waypoint]')];
    const hero = document.querySelector('[data-hero]');
    let queued = false;

    const update = () => {
        queued = false;
        const headY = window.innerHeight * 0.6; // the "car" sits 60% down the viewport
        const rect = root.getBoundingClientRect();
        const progress = Math.min(1, Math.max(0, (headY - rect.top) / rect.height));

        root.style.setProperty('--p', progress.toFixed(4));
        waypoints.forEach((w) => {
            const box = w.getBoundingClientRect();
            w.classList.toggle('is-reached', box.top + box.height / 2 < headY);
        });

        // Hero car drifts as you scroll away from the hero (0 at the top, 1 when it has left).
        if (hero && !reduceMotion) {
            hero.style.setProperty('--hs', Math.min(1, Math.max(0, window.scrollY / hero.offsetHeight)).toFixed(3));
        }
    };

    window.addEventListener('scroll', () => {
        if (!queued) {
            queued = true;
            requestAnimationFrame(update);
        }
    }, { passive: true });
    window.addEventListener('resize', update);
    update();
} else {
    observeCounters(document);
}
