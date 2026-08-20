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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'uin': '#1d4ed8',         // Vibrant Blue (Primary)
                'uin-hover': '#1e3a8a',   // Deep Navy Blue (Hover/Dark)
                'uin-light': '#eff6ff',   // Soft Ice Blue (Backgrounds/Accents)
            }
        },
    },

    plugins: [forms],
};