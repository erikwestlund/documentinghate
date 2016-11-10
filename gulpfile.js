const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

gulp.task('copyfiles', function() {
    // font awesome
    gulp.src('node_modules/font-awesome/fonts/**')
        .pipe(gulp.dest('public/build/assets/fonts'));

});


elixir(mix => {
    mix.sass('resources/assets/sass/app.scss', 'public/assets/css/app.css');
    mix.webpack('resources/assets/js/app.js', 'public/assets/js/app.js');

    mix.version([
        'assets/js/app.js',
        'assets/css/app.css'
    ]);
});
