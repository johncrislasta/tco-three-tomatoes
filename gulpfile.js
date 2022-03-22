'use strict'

const { src, dest, watch } = require('gulp');

const sass = require('gulp-dart-sass');
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('gulp-autoprefixer');


function compileSass(done) {
    src([ 'assets/sass/**/*.scss', "!assets/sass/_*.scss" ])
        .pipe(sourcemaps.init())
        .pipe(sass({
                outputStyle: 'compressed' //options; expanded, nested, compact, compressed
            }).on('error', sass.logError))
        .pipe(autoprefixer())
        .pipe(sourcemaps.write('.'))
        .pipe(dest('assets/css'));
    done();
}
function watchSass() {
    watch('assets/sass/**/*.scss', compileSass);
}
exports.compileSass = compileSass;
exports.default = watchSass;