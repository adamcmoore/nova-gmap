let mix = require('laravel-mix')
let path = require('path')

mix.setPublicPath('dist')
   .js('resources/js/field.js', 'js')
   .vue()
   .sass('resources/sass/field.scss', 'css');


mix.webpackConfig({
   resolve: {
      alias: {
         '@': path.resolve(__dirname, 'resources/js')
      },
   },
   externals: {
      vue: 'Vue',
   }
});

mix.alias({
   'laravel-nova': path.join(
      __dirname,
      'vendor/laravel/nova/resources/js/mixins/packages.js'
   ),
   'nova-mixins': path.join(__dirname,'./vendor/laravel/nova/resources/js/mixins'),
   '@/util': path.resolve(__dirname, 'vendor/laravel/nova/resources/js/util'),
});