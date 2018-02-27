/* file: gulpfile.js */

var gulp          = require('gulp'),
    autoprefixer  = require('gulp-autoprefixer'),
    concat        = require('gulp-concat'),
    jshint        = require('gulp-jshint'),
    livereload    = require('gulp-livereload'),
    cleanCSS     = require('gulp-clean-css'),
    rename        = require('gulp-rename'),
    sass          = require('gulp-sass'),
    sourcemaps    = require('gulp-sourcemaps'),
    gulputil      = require('gulp-util'),
    uglify        = require('gulp-uglify');
notify        = require('gulp-notify');

require('es6-promise').polyfill();

/* build css */

gulp.task('build-css', function() {
    return gulp.src('../sass/main.scss')
        .pipe(sourcemaps.init())
        .pipe(sass({outputStyle: 'expanded'})).on('error', sass.logError)
        .pipe(autoprefixer({
	    browsers: ['last 3 versions', 'ie >= 8'],
	}))
        .pipe(concat('main.compiled.css'))
        .pipe(sourcemaps.write('../css/'))
        .on("error", notify.onError({
	    message: "<%= error.message %>",
	    title: "Error CSS"
	}))
        .pipe(gulp.dest('../css/'))
});

gulp.task('minify-css', ['build-css'], function() {
    gulp.src('../css/main.compiled.css')
        .pipe(cleanCSS({debug: true}, function(details) {
	    console.log(details.name + ': size=' + details.stats.originalSize);
	    console.log(details.name + ': size=' + details.stats.minifiedSize);
	}))
        .pipe(rename('main.compiled.min.css'))
        .on("error", notify.onError({
	    message: "<%= error.message %>",
	    title: "Error CSS"
	}))
        .pipe(gulp.dest('../css/'))

});

/* build js */

gulp.task('build-js', function() {
    gulp.src(['../js/grunts/*.js','../js/base.js'])
        .pipe(sourcemaps.init())
        .pipe(concat('main.compiled.js'))
        .pipe(gulp.dest('../js'))
        .pipe(rename('main.compiled.min.js'))
//        .pipe(uglify().on('error', gulputil.log))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('../js'))
        .pipe(livereload());
});


/* updated watch task to include sass */

gulp.task('watch', function() {
    livereload.listen();
    gulp.watch(['../js/base.js', '../js/grunts/*.js'], ['build-js']);
    gulp.watch('../sass/**/*.scss', ['build-css', 'minify-css']);
});

// Default Task
gulp.task('default', ['build-css', 'minify-css', 'build-js', 'watch']);
