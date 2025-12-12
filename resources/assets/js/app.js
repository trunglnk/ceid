/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import { NumericTextBoxPlugin } from "@syncfusion/ej2-vue-inputs";
import Paginate from "vuejs-paginate";
import "./bootstrap";
import "./bootstrap-notify";
import VeeValidate from "vee-validate";

import store from "./store";

window.Vue = require("vue");
var numeral = require("numeral");

Vue.use(NumericTextBoxPlugin);

Vue.use(VeeValidate, { fieldsBagName: "formFields" });

Vue.component("pagination", require("laravel-vue-pagination"));
Vue.component("paginate", Paginate);
Vue.component(
  "select2-component",
  require("./components/Select2Component.vue")
);
Vue.component("modal-component", require("./components/Modal.vue"));
Vue.component("confirm-dialog", require("./components/ConfirmDialog.vue"));
Vue.component(
  "hanhvivipham-component",
  require("./components/HanhViViPham.vue")
);
Vue.component("select-component", require("./components/SelectComponent.vue"));
Vue.component("paging-component", require("./components/paging.vue"));
Vue.component("perpage-component", require("./components/perpage.vue"));
Vue.component(
  "show-total-component",
  require("./components/ShowTotalPaging.vue")
);

Vue.component(
  "cososanxuat-component",
  require("./cososanxuat/index/index.vue")
);

Vue.component("table-component", require("./components/Table2Component.vue"));

Vue.component(
  "group-checkbox-component",
  require("./components/GroupCheckboxComponent.vue")
);
Vue.component("map-component", require("./components/Map.vue"));

Vue.component(
  "checkbox-component",
  require("./components/CheckboxComponent.vue")
);
Vue.component("google-map", require("./components/MapComponent.vue"));
Vue.component("pagination", require("laravel-vue-pagination"));
Vue.component("cososanxuat-add", require("./cososanxuat/add.vue"));
Vue.component("ketquathanhtra-add", require("./ketquathanhtra/add.vue"));
Vue.component("ketquathanhtra-edit", require("./ketquathanhtra/edit.vue"));
Vue.component("cososanxuat-edit", require("./cososanxuat/edit.vue"));
Vue.component("chudautu", require("./chudautu/index.vue"));
Vue.component("addchudautu", require("./chudautu/add.vue"));
Vue.component("editchudautu", require("./chudautu/edit.vue"));

Vue.component("diemtramquantrac", require("./diemtramquantrac/index.vue"));
Vue.component("adddiemtramquantrac", require("./diemtramquantrac/add.vue"));
Vue.component("editdiemtramquantrac", require("./diemtramquantrac/edit.vue"));

Vue.component("ketquaphantichmau", require("./ketquaphantichmau/index.vue"));
Vue.component("addketquaphantichmau", require("./ketquaphantichmau/add.vue"));
Vue.component("editketquaphantichmau", require("./ketquaphantichmau/edit.vue"));

Vue.component("dongbocsdl", require("./dongbocsdl/index.vue"));

Vue.component("DanhSachCoSo", require("./danhsachcoso/index.vue"));
Vue.component("them-co-so-san-xuat", require("./danhsachcoso/add.vue"));
Vue.component("update-co-so-san-xuat", require("./danhsachcoso/update.vue"));
Vue.component("ketquaqtmt", require("./ketquaqtmt/index.vue"));

Vue.component("DetailCoSoSanXuat", require("./danhsachcoso/detail.vue"));

Vue.component("index-file-kqtt", require("./ketquathanhtra/indexFile.vue"));

Vue.component(
  "danhmuc-index-page",
  require("./danhmuc/dungchung/IndexPage.vue")
);
Vue.component(
  "danhmuc-thanhtra",
  require("./danhmuc/danhmucthanhtra/IndexPage.vue")
);
Vue.component(
  "danhmuc-cosophaply-index-page",
  require("./danhmuc/cosophaply/index.vue")
);

// Vue.component(
//   "danhmuc-cosophaply-nd45",
//   require("./danhmuc/nghidinh45/index.vue")
// );

Vue.component("doanthanhtra-add", require("./doanthanhtra/add.vue"));
Vue.component("doanthanhtra-edit", require("./doanthanhtra/edit.vue"));
Vue.component("tracuu", require("./cososanxuat/tracuu.vue"));

Vue.component("report-general", require("./reports/general.vue"));

Vue.component("report-preview", require("./reports/preview.vue"));

Vue.component(
  "search-ketquathanhtra",
  require("./ketquathanhtra/box-search.vue")
);

Vue.component("documents", require("./documents/index.vue"));
Vue.component("pdfanalysis", require("./pdfanalysis/index.vue"));

Vue.filter("formatNumber", function (value) {
  return numeral(value).format("0,0");
});

Vue.filter("formatMoney", function (value) {
  return numeral(value).format(" 0,0[.]00");
});

Vue.filter("numFormat", function (value) {
  if (value) {
    return Number(value)
      .toLocaleString(undefined, {
        minimumFractionDigits: 20,
      })
      .replace(/\.?0+$/, "");
  } else {
    return value;
  }
});

Vue.filter("fNumber", function (value) {
  return numeral(value).format("0.000");
});

Vue.filter("f2Number", function (value) {
  return numeral(value).format("0.00");
});

const app = new Vue({
  el: "#app",
  store,
});
$(function () {
  $('[data-toggle="tooltip"]').tooltip({
    placement: "bottom",
    template: `<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner bg-green"></div></div>`,
  });
});
