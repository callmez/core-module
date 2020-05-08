const mix = require('laravel-mix')
require('laravel-mix-merge-manifest')

const publicPath = '../../../public';

mix.setPublicPath(publicPath).mergeManifest()

mix
    .js(__dirname + '/resources/assets/js/admin/app.js', 'js/admin.js')
    .sass(__dirname + '/resources/assets/sass/admin/app.scss', 'css/admin.css')
    .copy('resources/assets/vendor/layuiadmin', publicPath + '/js/layuiadmin')

    .extract([
        'vue',
        'axios',
        'lodash',
        'moment',
    ])
    .version()
    .sourceMaps()
