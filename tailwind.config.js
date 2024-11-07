// tailwind.config.js
module.exports = {
  darkMode: 'class', // Enable dark mode via the 'dark' class
  content: [
    './src/**/*.{html,js,php}', // Adjust paths to your project files
    './student/index.php',
    './student/exam_reg.php',
  ],
  theme: {
    extend: {
      colors: {
        gray: {
          700: '#374151',
          800: '#1f2937',
        },
      },
    },
  },
};
