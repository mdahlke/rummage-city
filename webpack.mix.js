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

const production = mix.inProduction();
const devtool = production ? false : 'source-map';

mix.webpackConfig({
    devtool,
    output: {
        chunkFilename: 'js/chunks/[chunkhash].js'
    }
});

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/sw/sw.js', 'public/sw.js')
    .sass('resources/sass/app.scss', 'public/css');
