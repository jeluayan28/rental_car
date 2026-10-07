import './bootstrap';
import './journey';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// ---- Loading states ----------------------------------------------------------------------------

// Fade images in once they have loaded, instead of popping in.
document.querySelectorAll('img').forEach((img) => {
    if (img.complete) return;
    img.classList.add('is-loading');
    const done = () => img.classList.remove('is-loading');
    img.addEventListener('load', done, { once: true });
    img.addEventListener('error', done, { once: true });
});

// Show a spinner on the submit button and block double submits while a form is being sent.
document.addEventListener('submit', (event) => {
    const form = event.target;
    if (event.defaultPrevented || !(form instanceof HTMLFormElement) || form.hasAttribute('data-no-loading')) return;

    const button = event.submitter ?? form.querySelector('button[type="submit"], button:not([type])');
    if (!button || button.disabled) return;

    // Disable on the next tick so the button's own name/value is still submitted.
    setTimeout(() => {
        button.classList.add('is-submitting');
        button.setAttribute('aria-busy', 'true');
        form.querySelectorAll('button[type="submit"], button:not([type])').forEach((b) => { b.disabled = true; });
    }, 0);
});

// Restore buttons if the user comes back via the browser's back button.
window.addEventListener('pageshow', (event) => {
    if (!event.persisted) return;
    document.querySelectorAll('.is-submitting').forEach((b) => {
        b.classList.remove('is-submitting');
        b.removeAttribute('aria-busy');
    });
    document.querySelectorAll('form button[disabled]').forEach((b) => { b.disabled = false; });
});
