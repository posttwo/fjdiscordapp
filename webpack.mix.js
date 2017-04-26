const { mix } = require('laravel-mix');

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
//mix.autoload({ 'jquery': ['window.$', 'window.jQuery'] });
mix.js(['resources/assets/js/app.js',
        'resources/assets/js/bootstrap-checkbox-radio-switch.js',
        'resources/assets/js/bootstrap-select.js',
        'resources/assets/js/chartist.min.js',
        'resources/assets/js/light-bootstrap-dashboard.js', ], 'public/js/app.js')
   .sass('resources/assets/sass/light-bootstrap-dashboard.scss', 'public/css/app.css')
   .copy('resources/assets/css/bootstrap.min.css', 'public/css/bootstrap.min.css')
   .copy('resources/assets/css/pe-icon-7-stroke.css', 'public/css/pe-icon-7-stroke.css')
   .copy('resources/assets/css/animate.min.css', 'public/css/animate.min.css')
   .copyDirectory('resources/assets/img', 'public/img')
   .copyDirectory('resources/assets/fonts', 'public/fonts')
   .browserSync('fjapp.dev');
