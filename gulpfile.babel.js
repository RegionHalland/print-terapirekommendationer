'use strict'

import gulp from 'gulp'
import shell from 'gulp-shell'
import plumber from 'gulp-plumber'
import gutil from 'gulp-util'
import rename from 'gulp-rename'
import sass from 'gulp-sass'
import postcss from 'gulp-postcss'
import autoprefixer from 'autoprefixer'
import cssnano from 'cssnano'
import concat from 'gulp-concat'
import uglify from 'gulp-uglify'
import imagemin from 'gulp-imagemin'

// Build CSS
gulp.task('css:dist', () => {
	// PostCSS plugins
	const plugins = [
		autoprefixer({browsers: ['last 1 version']}),
		cssnano({ mergeLonghand: false, zindex: false })
	]
	return gulp.src('./src/scss/*.scss')
		.pipe(plumber({
			errorHandler: error => {
				gutil.beep()
				console.log(error)
			}
		}))
		.pipe(sass())
		.pipe(postcss(plugins))
		.pipe(rename({suffix: '.min'}))
		.pipe(plumber.stop())
		.pipe(gulp.dest('./dist/css/'))
})

// Build JS
gulp.task('js:dist', () => {
	// App
	gulp.src(['./src/js/vendor/*.js', './src/js/app.js', './src/js/*.js'])
		.pipe(plumber({
			errorHandler: error => {
				gutil.beep()
				console.log(error)
			}
		}))
		.pipe(concat('app.js'))
		.pipe(rename({suffix: '.min'}))
		.pipe(uglify())
		.pipe(gulp.dest('./dist/js/'))
})

// Minify images
gulp.task('img:dist', () => {
	return gulp.src('./src/img/**/*')
        .pipe(imagemin())
        .pipe(gulp.dest('./dist/img'))
})

// Copy fonts to temporary directory
gulp.task('fonts:dist', () => {
	return gulp.src('./src/fonts/*')
		.pipe(gulp.dest('./dist/fonts/'))
})

// Watch
gulp.task('watch', ['js:dist', 'css:dist', 'fonts:dist'], () => {
	gulp.watch('./src/scss/**/*.scss', ['css:dist'])
	gulp.watch(['./src/js/**/*.js'], ['js:dist'])
})

// Build
gulp.task('build', ['css:dist', 'fonts:dist', 'js:dist'])