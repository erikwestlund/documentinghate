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
    // glyphicons
    gulp.src('node_modules/bootstrap-sass/assets/fonts/bootstrap/**')
        .pipe(gulp.dest('public/build/assets/fonts/bootstrap'));

    // font awesome
    gulp.src('node_modules/font-awesome/fonts/**')
        .pipe(gulp.dest('public/build/assets/fonts'));

    gulp.src('node_modules/leaflet/dist/images/**')
        .pipe(gulp.dest('public/build/assets/css/images'));

});


elixir(mix => {

    Elixir.webpack.mergeConfig({
        module: {
            loaders: [{
                test: /\.jsx?$/, 
                loader: 'babel',
                exclude: /node_modules(?!\/(vue-tables-2|vue-pagination-2))/
            }],    
        },
        resolve: {
            alias: {
                jquery: __dirname + '/node_modules/jquery/dist/jquery'
            },
        },         
    });

    mix.sass('resources/assets/sass/app.scss', 'public/assets/css/app.css');

    mix.webpack('resources/assets/js/app.js', 'public/assets/js/app.js');
   mix.webpack('resources/assets/js/admin.js', 'public/assets/js/admin.js');

    mix.version([
        'assets/css/app.css',
        'assets/js/app.js',
        'assets/js/admin.js',
    ]);
});
