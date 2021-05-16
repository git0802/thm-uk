require('laravel-mix-bundle-analyzer');
require('laravel-mix-ignore');

const mix = require('laravel-mix');

// Front
mix.js('resources/js/main.js', 'public/js/app.js')
    .sass('resources/sass/app.scss', 'public/css/app.css')
    .version()
;

// Back
mix.js('resources/dashboard/main.js', 'public/js/planner.js')
    .sass('resources/dashboard/sass/app.scss', 'public/css/planner.css')
    .version();

// Admin
mix.js('resources/admin/main.js', 'public/js/admin.js')
    .sass('resources/admin/sass/app.scss', 'public/css/admin.css')
    .version();

if (!mix.inProduction()) {
    mix.sourceMaps();
    mix.bundleAnalyzer({
        openAnalyzer: false,
        analyzerMode: 'static',
    });
}

mix.ignore(
    /^\.\/locale$/,
    /moment$/
);
