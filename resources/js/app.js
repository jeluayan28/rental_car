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

// ---- Show / hide password ----------------------------------------------------------------------

const EYE = '<path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>';
const EYE_OFF = '<path d="M17.9 17.9A10.4 10.4 0 0 1 12 19C5.6 19 2 12 2 12a18.7 18.7 0 0 1 5.1-5.9M9.9 5.2A9.7 9.7 0 0 1 12 5c6.4 0 10 7 10 7a18.6 18.6 0 0 1-2.2 3.2M1 1l22 22M9.9 9.9a3 3 0 0 0 4.2 4.2"/>';

document.querySelectorAll('input[type="password"]').forEach((input) => {
    const wrap = document.createElement('div');
    wrap.className = 'relative';
    input.parentNode.insertBefore(wrap, input);
    wrap.appendChild(input);
    input.classList.add('pr-11');

    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'absolute inset-y-0 right-0 flex w-11 items-center justify-center text-cream/55 transition hover:text-sun focus:outline-none focus-visible:text-sun';
    button.setAttribute('aria-label', 'Show password');
    const icon = (path) => `<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">${path}</svg>`;
    button.innerHTML = icon(EYE);

    button.addEventListener('click', () => {
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        button.innerHTML = icon(show ? EYE_OFF : EYE);
        button.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    });

    wrap.appendChild(button);
});
