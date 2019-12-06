/**
 *
 *
 * !!!! READ ME !!!!!
 *
 *
 * Why do we have two webpack.*.js files?
 * Because there are some bugs with JS chunking and the .extract() method
 * in Laravel's mix and webpack that haven't been worked out
 *
 * @see https://github.com/JeffreyWay/laravel-mix/issues/1914
 */

require('laravel-mix-merge-manifest');
const mix = require('laravel-mix');
const ChunkRenamePlugin = require('webpack-chunk-rename-plugin');

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
        publicPath: '/',
        filename: '[name].js',
        chunkFilename: '[name].js',
    },
    plugins: [
        new ChunkRenamePlugin({
            initialChunksWithEntry: true,
            '/js/vendor': '/vendor.js',
            '/js/manifest': '/manifest.js'
        }),
    ],
});

mix.options({
    processCssUrls: true,
    postCss: [require('autoprefixer')],
    extractVueStyles: false
});

mix.js('resources/js/app.js', 'public/js').extract();
// mix.js('resources/js/sw/sw.js', 'public/sw.js')
mix.mergeManifest();

if (production) {
    mix.version();
} else {
    mix.sourceMaps(true, 'hidden-source-map');
}
