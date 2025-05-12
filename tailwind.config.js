/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.php",
    "./products.php",
    "./product-details.php",
    "./cart.php",
    "./account.php",
    "./contact.php",
    "./admin/*.php",
    "./assets/js/*.js"
  ],
  theme: {
    extend: {
      colors: {
        'brand-primary': '#8B4513', // Warm brown for a velvet-like theme
        'brand-secondary': '#D2691E', // Complementary darker tone
        'brand-accent': '#F4A460' // Lighter accent color
      },
      fontFamily: {
        'body': ['Poppins', 'sans-serif'],
        'heading': ['Playfair Display', 'serif']
      },
      boxShadow: {
        'elegant': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)'
      }
    },
  },
  plugins: [],
}