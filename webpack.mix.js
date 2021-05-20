const mix = require('laravel-mix')

mix.js('resources/js/app.js', 'public/dist')

mix.postCss('resources/css/app.css', 'public/dist', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
])

if (mix.inProduction()) {
    mix.version()
}
