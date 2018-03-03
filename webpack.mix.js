let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/assets/js');

// mix.js('resources/assets/js/bootstrap.js', 'public/assets/js');

mix.styles([
    'resources/assets/css/app.css',
    'resources/assets/css/bootstrap.css',
], 'public/assets/css/all.css');

mix.copy('node_modules/laravel-echo/src/echo.ts', 'public/assets/js');
mix.copy('node_modules/twilio-video/dist/twilio-video.js', 'public/assets/js');


//playing around TODO //
mix.copy('node_modules/socketcluster-client/socketcluster.js', 'public/assets/js');


mix.copy('node_modules/font-awesome/css/font-awesome.css', 'public/assets/css/font-awesome.css');
mix.copy('node_modules/font-awesome/fonts', 'public/assets/fonts');