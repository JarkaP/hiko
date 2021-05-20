const colors = require('tailwindcss/colors')

module.exports = {
    important: true,

    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                gray: colors.warmGray,
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
}
