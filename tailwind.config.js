/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './assets/**/*.{js,jsx,ts,tsx}', // Include all JS, JSX, TS, TSX files from your assets folder
    './templates/**/*.html.twig', // Include Twig files if you're using them
    './components/**/*.{js,jsx,ts,tsx}', // Include your components folder
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};


