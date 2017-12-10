var
  gulp = require('gulp')
  , less = require('gulp-less')
  , cssLint = require('gulp-csslint')
  , imagemin = require("gulp-imagemin")
  , autoprefixer = require('gulp-autoprefixer')
  // , concat = require('gulp-concat')
  , cssmin = require('gulp-cssmin')
  , browserSync = require('browser-sync');

gulp.task('min-img', function(){
  gulp.src('../imagens/jpg/*.jpg')
    .pipe(imagemin())
    .pipe(gulp.dest('../../wp-content/themes/integral/images'));
});
gulp.task('server', function(){
  browserSync.init({
    proxy: 'merepresenta.host'
  });

  gulp.watch('../../wp-content/themes/integral/**/*').on('change', function(){
    browserSync.reload();
  });

  gulp.watch('../../wp-content/themes/integral/less/*.less').on('change', function(event){
    console.log("Compiling less file: " + event.path);

    gulp.src(event.path)
      .pipe(less()).on('error', function(error){
        console.log(error.message);
      })
      .pipe(autoprefixer())
      .pipe(cssmin())
      .pipe(gulp.dest('../../wp-content/themes/integral/css'));
  });

  gulp.watch('../../wp-content/themes/integral/css/*.css').on('change', function(event){
    console.log("Lint css file: " + event.path);

    gulp.src(event.path)
      .pipe(cssLint()).on('error', function(error){
        console.log(error.message);
      })
      .pipe(cssLint.formatter());
  });
});

gulp.task('lessc', function(){
  gulp.src('../../wp-content/themes/integral/less/*.less')
    .pipe(less()).on('error', function(error){
      console.log(error.message);
    })
    .pipe(autoprefixer())
    .pipe(cssmin())
    .pipe(gulp.dest('../../wp-content/themes/integral/css'));
});