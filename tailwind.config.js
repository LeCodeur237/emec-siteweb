/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                emec: {
                    night: '#0f1b3d',
                    navy: '#142857',
                    blue: '#1d4ed8',
                    gold: '#d9b35f',
                },
            },
        },
    },
    plugins: [],
}
