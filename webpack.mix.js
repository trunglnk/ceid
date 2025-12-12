let mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("resources/assets/js/app.js", "public/js");

mix.copyDirectory('node_modules/admin-lte/bower_components/bootstrap/fonts', 'public/css/fonts');
mix.copyDirectory('node_modules/admin-lte/bower_components/font-awesome/fonts', 'public/css/fonts');
mix.copy('node_modules/admin-lte/plugins/iCheck/square/green.png', 'public/css/dist/green.png');
mix.copy('node_modules/admin-lte/plugins/iCheck/square/green@2x.png', 'public/css/dist/green@2x.png');
mix.copy('node_modules/jquery/dist/jquery.min.js', 'public/js/min/jquery.min.js');
mix.copy('node_modules/admin-lte/bower_components/bootstrap/dist/js/bootstrap.min.js', 'public/js/min/bootstrap.min.js');
mix.copy('node_modules/admin-lte/plugins/iCheck/icheck.min.js', 'public/js/min/icheck.min.js');
mix.copy('node_modules/admin-lte/bower_components/select2/dist/js/select2.full.min.js', 'public/js/min/select2.full.min.js');
mix.copy('node_modules/chart.js/dist/Chart.min.js', 'public/js/Chart.min.js');
mix.copy('node_modules/jsbarcode/dist/JsBarcode.all.min.js', 'public/js/min/JsBarcode.all.min.js');
mix.copy('node_modules/admin-lte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js', 'public/js/min/bootstrap-datepicker.min.js');
mix.copy('node_modules/admin-lte/bower_components/datatables.net/js/jquery.dataTables.min.js', 'public/js/min/jquery.dataTables.min.js');
mix.copy('node_modules/admin-lte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js', 'public/js/min/dataTables.bootstrap.min.js');
mix.copy('node_modules/admin-lte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css', 'public/css/dist/dataTables.bootstrap.min.css');
mix.copy('node_modules/admin-lte/bower_components/bootstrap/dist/css/bootstrap.min.css', 'public/css/dist/bootstrap.min.css');
mix.copy('node_modules/admin-lte/bower_components/font-awesome/css/font-awesome.min.css', 'public/css/dist/font-awesome.min.css');
mix.copy('node_modules/admin-lte/bower_components/Ionicons/css/ionicons.min.css', 'public/css/dist/ionicons.min.css');
mix.copy('node_modules/admin-lte/dist/css/AdminLTE.min.css', 'public/css/dist/AdminLTE.min.css');
mix.copy('node_modules/admin-lte/plugins/iCheck/square/green.css', 'public/css/dist/green.min.css');
mix.copy('node_modules/vue-multiselect/dist/vue-multiselect.min.css', 'public/css/dist/vue-multiselect.min.css');
mix.copy('node_modules/admin-lte/bower_components/moment/min/moment.min.js', 'public/js/min/moment.min.js');
mix.copy('node_modules/admin-lte/bower_components/bootstrap-daterangepicker/daterangepicker.js', 'public/js/min/daterangepicker.js');
mix.copy('node_modules/admin-lte/bower_components/Flot/jquery.flot.pie.js', 'public/js/min/jquery.flot.pie.js');
mix.copy('node_modules/admin-lte/bower_components/Flot/jquery.flot.js', 'public/js/min/jquery.flot.js');
mix.copy('node_modules/admin-lte/bower_components/Flot/jquery.flot.resize.js', 'public/js/min/jquery.flot.resize.js');
mix.copy('node_modules/admin-lte/bower_components/Flot/jquery.flot.categories.js', 'public/js/min/jquery.flot.categories.js');
mix.copy('node_modules/admin-lte/bower_components/jquery-knob/dist/jquery.knob.min.js', 'public/js/min/jquery.knob.min.js');
mix.copy('node_modules/admin-lte/bower_components/chart.js/Chart.js', 'public/js/min/Chart.js');

mix.styles(
  [
    "node_modules/admin-lte/dist/css/skins/skin-green-light.css",
    "node_modules/admin-lte/bower_components/select2/dist/css/select2.css",
    "node_modules/admin-lte/bower_components/bootstrap-daterangepicker/daterangepicker.css",
    "node_modules/admin-lte/plugins/pace/pace.css",
    "node_modules/pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css",
    "public/css/boxsearch.css",
    "public/css/form.css",
    "public/css/select2.css",
    "public/css/table-report.css",
    "public/css/custom.css",
    "public/css/style.css",
  ],
  "public/css/dist/all.css"
);

mix.js(
  [
    "node_modules/admin-lte/bower_components/fastclick/lib/fastclick.js",
    "node_modules/admin-lte/dist/js/adminlte.min.js",
    "node_modules/admin-lte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js",
    "node_modules/admin-lte/bower_components/moment/moment.js",
    "node_modules/admin-lte/bower_components/bootstrap-daterangepicker/daterangepicker.js",
    "node_modules/admin-lte/plugins/pace/pace.js",
    "node_modules/jquery-maskmoney/dist/jquery.maskMoney.js",
    "node_modules/admin-lte/plugins/input-mask/jquery.inputmask.js",
    "node_modules/admin-lte/plugins/input-mask/jquery.inputmask.date.extensions.js",
    "node_modules/admin-lte/plugins/input-mask/jquery.inputmask.extensions.js",
    "public/js/alert.js",
    "public/js/checkbox.js",
    "public/js/select2.js",
    "public/js/perpage.js",
    "public/js/keypress.js",
    "public/js/dropdown.js",
    "public/js/maskinput.js",
    "public/js/menu.js",
  ],
  "public/js/min/base.min.js"
);

mix.js(
  ["public/js/system/role-detail.js"],
  "public/js/min/system-role-detail.min.js"
);

mix.js(
  ["public/js/system/menu-detail.js"],
  "public/js/min/system-menu-detail.min.js"
);
