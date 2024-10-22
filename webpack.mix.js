let mix = require('laravel-mix');
mix.setPublicPath('assets');

mix.setResourceRoot('../');
mix
    .js('resources/js/boot.js', 'assets/js/boot.js').vue()
    .js('resources/js/main.js', 'assets/js/plugin-main-js-file.js').vue()
    .copy('resources/images', 'assets/images')
    .sass('resources/scss/admin/app.scss', 'assets/css/element.css');
    