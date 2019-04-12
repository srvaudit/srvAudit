let mix = require('laravel-mix');
var path = require('path');
var webpack = require('webpack');

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

mix.less('resources/assets/less/app.less', 'public/css')
   .copy('node_modules/sweetalert/dist/sweetalert.min.js', 'public/js/sweetalert.min.js')
   .copy('node_modules/sweetalert/dist/sweetalert.css', 'public/css/sweetalert.css')
   .copy('resources/assets/js/vim.js', 'public/js')
   .js('resources/assets/js/app.js', 'public/js')
   .js('resources/assets/js/rowlink.js', 'public/js')
   .js('node_modules/moment/src/lib/moment/moment.js', 'public/js')
   .js('node_modules/jquery/dist/jquery.min.js', 'public/js')
   .js('node_modules/jquery-ui-dist/jquery-ui.min.js', 'public/js')
   .js('resources/assets/js/timeago.js', 'public/js')
   .webpackConfig({
        resolve: {
            modules: [
                path.resolve(__dirname, 'vendor/laravel/spark/resources/assets/js'),
                'node_modules'
            ],
            alias: {
                'vue$': 'vue/dist/vue.js'
            }
        },
        plugins: [
            new webpack.ProvidePlugin({
                jQuery: 'jquery',
                $: 'jquery'
            })
        ]
   });
