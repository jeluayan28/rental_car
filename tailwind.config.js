import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                midnight: '#0B1020',
                tangerine: '#FF6B35',
                sun: '#FFC857',
                electric: '#2EC4FF',
                graphite: '#1B2236',
                cream: '#F4F1DE',
            },
        },
    },

    plugins: [forms],
};
