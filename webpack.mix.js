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

// mix.js('resources/js/app.js', 'public/js')
//    .sass('resources/sass/app.scss', 'public/css');


mix.scripts([
    'node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js'
], 'public/js/global.js');

mix.styles([
    'node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css'
], 'public/css/global.css');

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);

mix.copyDirectory('public/bower_components/AdminLTE/plugins', 'public/Plugins');
mix.copyDirectory('public/custom_components', 'public/customComponents');
mix.copyDirectory('node_modules/dropzone', 'public/dropzone');
mix.copyDirectory('node_modules/datetimepicker', 'public/datetimepicker');