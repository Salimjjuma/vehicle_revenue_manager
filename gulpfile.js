"use strict";

const {
  series,
  parallel,
  src,
  dest,
  watch
} = require("gulp");
const autoprefixer = require("gulp-autoprefixer");
const browsersync = require("browser-sync").create();
const cleanCSS = require("gulp-clean-css");
const del = require("del");
const babel = require("gulp-babel");
const concat = require("gulp-concat");
const uglify = require("gulp-uglify");
const rename = require("gulp-rename");
const concatCss = require("gulp-concat-css");
const merge = require("merge-stream");

// Load package.json for banner
const pkg = require("./package.json");
const gulpConcatCss = require("gulp-concat-css");

// Clean assets
function clean() {
  return del(["./assets/", "./dist"]);
}

// Third-party modules task
function modules() {
  const positiveGlobs = [
    "./node_modules/jquery/dist/*",
    "./node_modules/@popperjs/core/dist/umd/popper.min.js",
    "./node_modules/bootstrap/dist/js/*",
    "./node_modules/bootstrap/dist/css/*",
    "./node_modules/chart.js/dist/chart.umd.js",
    "./node_modules/izitoast/dist/js/*",
    "./node_modules/izitoast/dist/css/*",
    "./node_modules/datatables.net/js/*.js",
    "./node_modules/datatables.net-bs4/js/*.js",
    "./node_modules/datatables.net-bs4/css/*.css",
    "./node_modules/@fortawesome/**/*",
    "./node_modules/progressbar.js/dist/*.js",
    "./node_modules/nprogress/**",
    "./node_modules/select2/dist/**",
    "./node_modules/select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.css",
    "./node_modules/jquery.easing/*.js",
    "./node_modules/@chenfengyuan/datepicker/dist/*",
    "./node_modules/jquery-validation/dist/*.js",
  ];

  const negativeGlobs = ["!./node_modules/jquery/dist/core.js"];

  const copyModules = src([...positiveGlobs, ...negativeGlobs], {
    base: "./node_modules",
  }).pipe(dest("./assets"));

  return copyModules;
}

function customJs() {
  var webfonts = src("./assets/@fortawesome/fontawesome-free/webfonts/*").pipe(
    dest("./dist/webfonts")
  );

  var vehicle = src("./vehicles/src/js/**")
    .pipe(concat("vehicle.js"))
    .pipe(uglify())
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(dest("./dist/js/vehicles"));

  var view_vehicle = src("./vehicles/pages/src/js/index.js")
    .pipe(concat("view_vehicle.js"))
    .pipe(uglify())
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(dest("./dist/js/vehicles"));

  var overview_vehicle = src("./vehicles/pages/src/js/overview.js")
    .pipe(concat("overview_vehicle.js"))
    .pipe(uglify())
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(dest("./dist/js/vehicles"));

  var vehicle_drivers_conductors = src(
      "./vehicles/pages/src/js/vehicle_driver_conductor.js"
    )
    .pipe(concat("viewDriverAndConductorForVehicle.js"))
    .pipe(uglify())
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(dest("./dist/js/vehicles"));

  var categories = src("./categories/src/js/**").pipe(
    dest("./dist/js/categories")
  );

  var vehicle_brands = src("./vehicle_brands/src/js/**")
    .pipe(concat("vehicle_brands.js"))
    .pipe(uglify())
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(dest("./dist/js/vehicle_brands"));

  var accounts = src("./accounts/src/js/**")
    .pipe(concat("accounts.js"))
    .pipe(uglify())
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(dest("./dist/js/accounts"));

  var charts_of_accounts = src("./charts_of_accounts/src/js/**")
    .pipe(concat("charts_of_accounts.js"))
    .pipe(uglify())
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(dest("./dist/js/charts_of_accounts"));

  var view_charts_of_accounts = src("./charts_of_accounts/page/src/js/index.js")
    .pipe(concat("view_charts_of_accounts.js"))
    .pipe(uglify())
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(dest("./dist/js/charts_of_accounts"));

  var owners = src("./owners/src/js/**")
    .pipe(concat("owners.js"))
    .pipe(uglify())
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(dest("./dist/js/owners"));

  var owner_vehicles = src("./owners/page/src/js/**")
    .pipe(concat("owners_view.js"))
    .pipe(uglify())
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(dest("./dist/js/owners"));

  var users = src("./users/src/js/**")
    .pipe(concat("users.js"))
    .pipe(uglify())
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(dest("./dist/js/users"));

  var dashboard = src("./dashboard/src/js/**")
    .pipe(concat("dashboard.js"))
    .pipe(uglify())
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(dest("./dist/js/dashboard"));

  var vehicle_drivers = src("./drivers/src/js/**")
    .pipe(concat("vehicle_drivers.js"))
    .pipe(uglify())
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(dest("./dist/js/vehicle_drivers"));

  var vehicle_drivers_details = src("./drivers/pages/src/js/**")
    .pipe(concat("driver_details.js"))
    .pipe(uglify())
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(dest("./dist/js/vehicle_drivers"));

  var settings = src("./settings/src/js/**")
    .pipe(concat("settings.js"))
    .pipe(uglify())
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(dest("./dist/js/settings"));

  return merge(
    vehicle,
    categories,
    vehicle_brands,
    charts_of_accounts,
    view_charts_of_accounts,
    accounts,
    webfonts,
    view_vehicle,
    owners,
    overview_vehicle,
    owner_vehicles,
    users,
    dashboard,
    vehicle_drivers,
    vehicle_drivers_details,
    vehicle_drivers_conductors,
    settings
  );
}

// JS task

function js() {
  const jsSources = [
    "./src/js/dashboard.js",
    "./assets/jquery/dist/jquery.min.js",
    "./assets/@popperjs/core/dist/umd/popper.min.js",
    "./assets/bootstrap/dist/js/bootstrap.min.js",
    "./assets/jquery.easing/jquery.easing.min.js",
    "./assets/izitoast/dist/js/iziToast.min.js",
    "./assets/@chenfengyuan/datepicker/dist/datepicker.min.js",
    "./assets/datatables.net/js/jquery.dataTables.min.js",
    "./assets/datatables.net-bs4/js/dataTables.bootstrap4.min.js",
    "./assets/chart.js/dist/chart.umd.js",
    "./assets/select2/dist/js/select2.min.js",
    "./assets/progressbar.js/dist/progressbar.min.js",
    "./assets/nprogress/nprogress.js",
    "./assets/jquery-validation/dist/jquery.validate.min.js",
    "./src/js/nprogressbar.js",
  ];

  return src(jsSources)
    .pipe(concat("main.js"))
    .pipe(uglify())
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(dest("dist/js"));
}

// CSS task
function css() {
  const cssSources = [
    "./assets/@fortawesome/fontawesome-free/css/all.min.css",
    "./assets/bootstrap/dist/css/bootstrap.css",
    "./assets/izitoast/dist/css/iziToast.min.css",
    "./assets/@chenfengyuan/datepicker/dist/datepicker.min.css",
    "./assets/datatables.net-bs4/css/dataTables.bootstrap4.min.css",
    "./assets/select2/dist/css/select2.min.css",
    "./assets/select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.css",
    "./src/css/dashboard.css",
    "./assets/nprogress/nprogress.css",
  ];

  return src(cssSources)
    .pipe(cleanCSS())
    .pipe(concatCss("main.css"))
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(dest("dist/css"));
}

// Watch files
function watchFiles() {
  watch(["./src/js/**/*"], customJs);
  watch(["./**/src/js/*"], customJs);
  watch(["./src/css/*"], css);
}

// Define complex tasks
const assets = series(clean, modules);
const build = series(assets, parallel(css, js), customJs);
const watchIt = series(build, parallel(watchFiles));
const watchJs = watchFiles;

// Export tasks
// exports.scss = scss;
exports.css = css;
exports.js = js;
// exports.customJs = customJs;
exports.clean = clean;
exports.assets = assets;
// exports.build = build;
exports.watch = watchIt;
exports.default = build;
exports.watchJs = watchJs;