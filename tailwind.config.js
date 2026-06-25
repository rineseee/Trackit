/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './resources/js/**/*.jsx',
    ],
    theme: {
        extend: {
            colors: {
                'slate': {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                },
                'indigo': {
                    50: '#eef2ff',
                    600: '#4f46e5',
                    700: '#4338ca',
                },
                'pink': {
                    500: '#ec4899',
                },
                'orange': {
                    400: '#fb923c',
                },
                'green': {
                    500: '#22c55e',
                },
            },
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.5rem',
                '32px': '2rem',
            },
            boxShadow: {
                'xl': '0 20px 25px -5px rgba(0, 0, 0, 0.1)',
            },
            backdropBlur: {
                'xl': '20px',
            },
        },
    },
    plugins: [],
};
