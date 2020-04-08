const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js');
// .sass('resources/sass/app.scss', 'public/css');

mix.styles('resources/assets/css/main.css', 'public/css/main.css');
mix.styles('resources/assets/css/media.css', 'public/css/media.css');
mix.styles('resources/assets/css/fonts.css', 'public/css/fonts.css');

mix.styles([
    'node_modules/jquery-toast-plugin/src/jquery.toast.css'
], 'public/css/vendor.css');

mix.js([
    'node_modules/inputmask/dist/inputmask/inputmask.js',
    'node_modules/jquery-toast-plugin/src/jquery.toast.js'
], 'public/js/vendor.js');

mix.js('resources/assets/js/common.js', 'public/js/common.js');

mix.copyDirectory('resources/assets/fonts', 'public/fonts');

mix.copyDirectory('resources/assets/img', 'public/img');
mix.copyDirectory('resources/assets/docs', 'public/docs');

mix.copyDirectory('resources/assets/video', 'public/video');

if(mix.inProduction()) {
    mix.version();
}