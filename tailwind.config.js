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
                primary: '#353839', // Onyx Black (Base Brand Color)
                secondary: '#FFD700', // Regal Gold (Highlights/CTA)
                accent: '#C1121F', // Red (Urgent/Sale - Palette 1)
                dark: '#353839', // Onyx (Aliased for consistency)
                light: '#FDF0D5', // Warm Cream (increased warmth)
                info: '#2E5E99', // Sapphire Blue (Links/Info - Palette 5)
                surface: '#FDF0D5', // Cream (Cards/Warmth - Palette 1)
            },
        },
    },

    plugins: [forms],
};
