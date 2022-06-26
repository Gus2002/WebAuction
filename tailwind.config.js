/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
     // "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
      "./resources/views/*.blade.php",
      "./resources/views/auctions/*.blade.php",
      "./resources/views/auth/*.blade.php",
      "./resources/views/layouts/*.blade.php",
      "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    ],
    theme: {
      extend: {},
    },
    plugins: [],
  }
