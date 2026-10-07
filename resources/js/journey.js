// Drives the "road trip" scroll experience: road progress, waypoints and reveal-on-scroll.
const root = document.querySelector('[data-journey]');

if (root) {
    document.documentElement.classList.add('reveal-ready');

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { rootMargin: '0px 0px -10% 0px', threshold: 0.1 },
    );
    root.querySelectorAll('[data-reveal]').forEach((el) => observer.observe(el));

    const waypoints = [...root.querySelectorAll('[data-waypoint]')];
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
    };

    window.addEventListener(
        'scroll',
        () => {
            if (!queued) {
                queued = true;
                requestAnimationFrame(update);
            }
        },
        { passive: true },
    );
    window.addEventListener('resize', update);
    update();
}
