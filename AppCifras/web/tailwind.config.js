/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './index.html',
    './src/**/*.{js,ts,jsx,tsx}',
    'node_modules/flowbite-react/lib/esm/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        space: {
          950: '#faf5ff',
          900: '#f5f3ff',
          800: '#ede9fe',
          700: '#e0dcf0',
          600: '#c4b5fd',
          500: '#7c3aed',
          400: '#6d28d9',
          300: '#5b21b6',
          200: '#4c1d95',
          100: '#1e1b4b',
        },
        nebula: '#6d28d9',
        cosmic: '#5b21b6',
      },
      fontFamily: {
        orbitron: ['Orbitron', 'sans-serif'],
        exo: ['Exo 2', 'sans-serif'],
      },
      backgroundImage: {
        'space-gradient': 'linear-gradient(180deg, #faf5ff 0%, #f5f3ff 50%, #ede9fe 100%)',
        'nebula-gradient': 'radial-gradient(ellipse at 50% 0%, rgba(124, 58, 237, 0.12) 0%, transparent 60%)',
      },
      boxShadow: {
        'glow': '0 0 20px rgba(124, 58, 237, 0.25)',
        'glow-lg': '0 0 40px rgba(124, 58, 237, 0.2)',
      },
    },
  },
  plugins: [require('flowbite/plugin')],
};
