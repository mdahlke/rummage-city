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

mix.copy('node_modules/@fortawesome/fontawesome-pro/css/all.css', 'public/css/fontawesome.css');
mix.copyDirectory('node_modules/@fortawesome/fontawesome-pro/webfonts', 'public/webfonts');

mix.mergeManifest();

if (production) {
    mix.version();
}
