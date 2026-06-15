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
            colors: {
                night: '#171d33',
                accent: '#c2b8b7',
                sec: '#36538f',
                body: '#FAEBD7',
                text: '#FAEBD7',
                primary: '#171d33',
            },
            fontFamily: {
                display: ['Playfair Display', ...defaultTheme.fontFamily.serif],
                body: ['Source Sans 3', ...defaultTheme.fontFamily.sans],
                accent: ['Great Vibes', 'cursive'],
            },
            boxShadow: {
                soft: '0 30px 80px -35px rgba(23, 29, 51, 0.45)',
                glow: '0 25px 45px -22px rgba(54, 83, 143, 0.4)',
            },
        },
    },

    plugins: [forms],
};