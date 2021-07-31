const mix = require('laravel-mix');
const path = require('path');
require('laravel-mix-merge-manifest');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.postCss('resources/css/app.css', 'public/css', [
    //
]);

mix.js('resources/js/app.js', 'public/js').extract([
  'jquery',
  'popper.js',
  'bootstrap',
  'lodash',
  'axios',
]);

mix.alias({
  '@': path.resolve(__dirname, 'resources/js'),
});

mix.version();
mix.mergeManifest();
