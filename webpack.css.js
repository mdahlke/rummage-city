require('laravel-mix-merge-manifest');

const mix = require('laravel-mix');
const production = mix.inProduction();
const devtool = production ? false : 'source-map';

mix.options({
    devtool,
    processCssUrls: true,
    postCss: [require('autoprefixer')],
    extractVueStyles: false
});

mix.sass('resources/sass/app.scss', 'public/css')
    .mergeManifest();

if (production) {
    mix.version();
}
