var gulp = require('gulp'),
  sass = require('gulp-sass'),
  usemin = require('gulp-usemin'),
  prefix = require('gulp-autoprefixer'),
  uglify = require('gulp-uglify'),
  htmlclean = require('gulp-htmlclean'),
  gzip = require('gulp-gzip'),
  clean = require('gulp-rimraf'),
  runSequence = require('run-sequence');


gulp.task('watch', function() {

  // compile sass to css
  gulp.watch(['public/assets/sass/*.sass', 'public/assets/sass/**/*.sass'], ['sass']);
  // build views + js
  gulp.watch(['app/views/*.php', 'public/assets/js/*.js'], ['build']);
});

gulp.task('build', function(done) {
  runSequence('views', 'js', 'rm', function() {
    done();
  });
});

gulp.task('js', function() {
  
  return gulp.src('app/views/build/assets/js/app.min.js')
    .pipe(gzip({append: true, gzipOptions: {level: 9}}))
    .pipe(gulp.dest('public/assets/js'));
}); 

gulp.task('rm', function() {
  return gulp.src('app/views/build/assets/js/app.min.js', {read: false})
    .pipe(clean());
});

gulp.task('views', function() {

  return gulp.src('app/views/*.php')
    .pipe(usemin({
      js: [uglify, 'concat']
    }))
    .pipe(htmlclean({
      edit: function(html) { return html; }
    }))
    .pipe(gulp.dest('app/views/build'));
});

gulp.task('sass', function() {
    return gulp.src('public/assets/sass/main.sass')
        .pipe(sass({
            outputStyle: 'compressed',
            onError: sass.logError
        }))
        .pipe(prefix(['last 15 versions', '> 1%', 'ie 8', 'ie 7'], { cascade: true }))
        .pipe(gzip({append: true, gzipOptions: {level: 9}}))
        .pipe(gulp.dest('public/assets/css'))
        .on('error', function() {console.log('sass-error');});
});


gulp.task('default', ['watch']);
