const mix = require('laravel-mix'),
    vinylFs = require('vinyl-fs'),
    svgSprite = require('gulp-svg-sprite'),
    svgmin = require('gulp-svgmin'),
    cheerio = require('gulp-cheerio'),
    replace = require('gulp-replace');

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

mix.webpackConfig({
    stats: {
        children: true
    }
})

let paths = {
    resources: {
        web: {
            base: 'resources/web',
            static: 'resources/web/static',
            fonts: 'resources/web/fonts',
            scss: 'resources/web/scss/main.scss',
            js: 'resources/web/js/**/*.js',
        },
        admin: {
            base: 'resources/admin',
            static: 'resources/admin/static',
            fonts: 'resources/admin/fonts',
            scss: 'resources/admin/scss/main.scss',
            js: 'resources/admin/js/all.js',
        },
    },
    public: {
        web: {
            base: 'public/web',
            static: 'public/web/static',
            fonts: 'public/web/fonts',
            css: 'public/web/css/all.css',
            js: 'public/web/js/all.js',
        },
        admin: {
            base: 'public/admin',
            static: 'public/admin/static',
            fonts: 'public/admin/fonts',
            css: 'public/admin/css/all.css',
            js: 'public/admin/js/all.js',
        },
    },
}

mix.extend('svgSprite', function () {
    vinylFs.src(`${paths.resources.web.static}/icons/*.svg`)
        .pipe(svgmin({
            js2svg: {
                pretty: true
            }
        }))
        .pipe(cheerio({
            run: function ($) {
                $('[fill]').removeAttr('fill');
                $('[stroke]').removeAttr('stroke');
                $('[style]').removeAttr('style');
            },
            parserOptions: {
                xmlMode: true
            }
        }))
        .pipe(replace('&gt;', '>'))
        .pipe(svgSprite({
            mode: {
                symbol: {
                    sprite: "sprite.svg",
                }
            }
        }))
        .pipe(vinylFs.dest(paths.public.web.static));
});

// web
mix
    // .copyDirectory(paths.resources.web.static, paths.public.web.static)
    // .copyDirectory(paths.resources.web.fonts, paths.public.web.fonts)
    .sass(paths.resources.web.scss, paths.public.web.css).options({processCssUrls: false})
    // .sourceMaps()
    // .js(paths.resources.web.js, paths.public.web.js)
    // .svgSprite();

//admin
mix
    .copyDirectory(paths.resources.admin.static, paths.public.admin.static)
    .copyDirectory(paths.resources.admin.fonts, paths.public.admin.fonts)
    .sass(paths.resources.admin.scss, paths.public.admin.css).options({processCssUrls: false})
    .sourceMaps()
    .js(paths.resources.admin.js, paths.public.admin.js);


if (mix.inProduction()) {
    mix.version();
} else {
    mix.browserSync({
        proxy: {
            target: process.env.APP_URL
        }
    });
}
