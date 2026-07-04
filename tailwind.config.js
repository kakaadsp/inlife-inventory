import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Outfit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Telkomsel brand colors
                brand: {
                    50:  '#fff1f1',
                    100: '#ffe1e1',
                    200: '#ffc7c7',
                    300: '#ffa0a0',
                    400: '#ff6b6b',
                    500: '#f83b3b',
                    600: '#EC2028', // Primary Red
                    700: '#c91b21',
                    800: '#a61a20',
                    900: '#891c21',
                },
                accent: {
                    500: '#F37021', // Secondary Orange
                    600: '#d9611a',
                },
                surface: {
                    50:  '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    800: '#1e293b',
                    850: '#172032',
                    900: '#0f172a',
                    950: '#080d18',
                },
            },
            boxShadow: {
                'soft':  '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                'card':  '0 4px 6px -1px rgba(0, 0, 0, 0.04), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
                'hover': '0 10px 25px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
            },
            borderRadius: {
                'xl': '1rem',
                '2xl': '1.25rem',
            },
            animation: {
                'fade-in':    'fadeIn 0.3s ease-in-out',
                'slide-up':   'slideUp 0.3s ease-out',
                'slide-in':   'slideIn 0.3s ease-out',
                'bounce-in':  'bounceIn 0.5s ease-out',
                'count-up':   'countUp 1s ease-out',
                'pulse-slow': 'pulse 3s infinite',
            },
            keyframes: {
                fadeIn:   { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                slideUp:  { '0%': { opacity: '0', transform: 'translateY(10px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                slideIn:  { '0%': { opacity: '0', transform: 'translateX(-10px)' }, '100%': { opacity: '1', transform: 'translateX(0)' } },
                bounceIn: { '0%': { opacity: '0', transform: 'scale(0.9)' }, '50%': { transform: 'scale(1.02)' }, '100%': { opacity: '1', transform: 'scale(1)' } },
            },
            transitionDuration: {
                '250': '250ms',
            },
        },
    },

    plugins: [forms],
};
