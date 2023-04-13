const gulp = require('gulp');
const yargs = require('yargs');
const dartSass = require('sass');
const gulpSass = require('gulp-sass');
const sass = gulpSass( dartSass );
const cleanCSS = require('gulp-clean-css');
const gulpif = require('gulp-if');
const sourcemaps = require('gulp-sourcemaps');
const browserSync = require('browser-sync');
const uglify = require('gulp-uglify');
const del = require('del');
const named = require('vinyl-named');
const zip = require('gulp-zip');
const replace = require('gulp-replace');
const info = require('./package.json');
const infoPlugin = require('./package.json');

const PRODUCTION = yargs.argv.prod;
const NOSOURCE = yargs.argv.nosours;
const server = browserSync.create();


// Paths for files.
const paths = {
	styles: {
		src: ['assets/sass/app.scss', 'assets/sass/admin.scss'],
		dest: 'assets/css'
	},
	images: {
		src: 'src/images/**/*.{jpg,jpeg,png,svg,gif}',
		// dest: 'assets/images'
	},
	scripts: {
		src: 'src/js/app.js',
		dest: 'assets/js'
	},
	other: {
		src: 'assets/fonts/*',
		dest: 'assets/fonts/'
	},
	package: {
		src: ['**/*', '!.vscode', '!node_modules{,/**}', '!package{,/**}', '!assets{,/css/app.css.map}', '!src{,/**}', '!.babelrc', '!.gitignore', '!gulpfile.babel.js', '!package.json', '!package-lock.json'],
		dest: 'package'
	},
	packageplugin: {
		src: ['**/*', '!.vscode', '!node_modules{,/**}', '!package{,/**}', '!assets{,/css/app.css.map}', '!src{,/**}', '!.babelrc', '!.gitignore', '!gulpfile.js', '!composer.json', '!composer.lock', '!package.json', '!package-lock.json'],
		dest: 'package'
	}
}


// Serve and watch files for changes with BrowserSync.
const serve = (done) => {
	server.init({
		proxy: "http://wpunialumni.test/",
	});
	done();
}

// Reload browser after changes.
const reload = (done) => {
	server.reload();
	done();
}

// Clean up the css and js file.
const clean =  () => del(['assets/css/app.css']);

// Compile Sass into CSS & auto-inject into browsers.
const styles = () => {
	return gulp.src(paths.styles.src)
		.pipe(gulpif(!PRODUCTION, sourcemaps.init()))
		.pipe(sass({outputStyle: "expanded"}).on('error', sass.logError))
		.pipe(gulpif(PRODUCTION && !NOSOURCE, cleanCSS({compatibility: 'ie8'})))
		.pipe(gulpif(!PRODUCTION, sourcemaps.write('.')))
		.pipe(gulp.dest(paths.styles.dest))
		.pipe(server.stream());
}

// Watch files for changes.
const watch = () => {
	gulp.watch('assets/sass/**/*.scss', styles);
	gulp.watch('assets/js/**/*.js', gulp.series(scripts, reload));
	gulp.watch('**/*.php', reload);
}

// Move the scripts file to assets folder for production use.
const scripts = () => {
	return gulp.src(paths.scripts.src)
		.pipe(named())
		.pipe(gulpif(PRODUCTION, uglify()))
		.pipe(gulp.dest(paths.scripts.dest));
}

// Compress the theme files to zip.
exports.compresstheme = () => {
	return gulp.src(paths.package.src)
		.pipe(replace('unialumni', info.projectname))
		.pipe(zip(`${info.projectname}.zip`))
		.pipe(gulp.dest(paths.package.dest));
}

// Compress the plugin files to zip.
exports.compressplugin = () => {
	return gulp.src(paths.packageplugin.src)
		.pipe(zip(`${infoPlugin.projectname}.zip`))
		.pipe(gulp.dest(paths.packageplugin.dest));
}

// Gulp Actions
exports.styles = styles;
exports.scripts = scripts;
exports.watch = watch;

// Gulp Actions Default
exports.default = gulp.series(clean, gulp.parallel(styles), serve, watch);

// Gulp Actions Build
exports.build = gulp.series(clean, gulp.parallel(styles));
