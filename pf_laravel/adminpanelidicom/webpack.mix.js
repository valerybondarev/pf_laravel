const mix = require('laravel-mix');
require('laravel-vue-lang/mix');
const path = require("path");

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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .version()
    .sourceMaps()
    .vue()
    .lang();

mix.webpackConfig({
    resolve: {
        alias: {
            '@lang': path.resolve('./lang'),
        },
    },
    module: {
        rules: [
            {
                test: /[\\\/]lang.+\.(php)$/,
                loader: 'php-array-loader',
            },
        ],
    },
})
