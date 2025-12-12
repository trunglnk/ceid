<template>
  <span>
    <div class="row block-multiple-rows">
      <div class="col-lg-12" style="margin-top: 5px">
        <label>THÔNG TIN CHUNG</label>
        <p class="text-muted">
          (Nhập thông tin chung và click vào từng mục hình thức xử phạt chính,
          hình thức xử phạt bổ sung và biện pháp khắc phục hậu quả để nhập thông
          tin chi tiết)
        </p>
        <hr style="margin-top: 7px; margin-bottom: 7px" />
        <div class="row block-multiple-rows">
          <div class="col-md-6 col-lg-3">
            <label>Cơ quan ra quyết định</label>
            <multiselect
              v-model="intermediate_data.co_quan_quyet_dinh_xu_phat"
              label="ten"
              track-by="id"
              placeholder="Gõ tên cơ quan"
              selectedLabel="Đã chọn"
              open-direction="bottom"
              :options="this.coquantochucs"
              :multiple="false"
              :searchable="true"
              :clear-on-select="true"
              :close-on-select="true"
              :show-no-results="false"
              :hide-selected="false"
              :tabindex="1"
              @input="change"
              :disabled="!isHaveAccess"
            >
              <span slot="noResult">Không tìm thấy kết quả</span>
            </multiselect>
          </div>
          <div class="col-md-6 col-lg-3">
            <label>Số quyết định</label>
            <input
              type="text"
              v-model="intermediate_data.so_quyet_dinh"
              class="form-control"
              required
              @change="change"
              :readonly="!isHaveAccess"
            />
          </div>
          <div class="col-md-6 col-lg-3">
            <label class="control-label">Thời gian ban hành</label>
            <date-picker
              v-model="intermediate_data.thoi_gian_ban_hanh"
              placeholder="Thời gian ban hành"
              :config="optionsDate"
              @change="change"
              :readonly="!isHaveAccess"
            >
            </date-picker>
          </div>
          <div class="col-md-6 col-lg-3">
            <label>Số quyết định sửa đổi</label>
            <input
              type="text"
              v-model="intermediate_data.so_quyet_dinh_sua_doi"
              class="form-control"
              @change="change"
              :readonly="!isHaveAccess"
            />
          </div>
          <div class="col-md-12 col-lg-12">
            <label class="control-label">Ngày sửa đổi quyết định</label>
            <date-picker
              v-model="intermediate_data.ngay_sua_doi_quyet_dinh"
              placeholder="Ngày sửa đổi QĐ"
              :config="optionsDate"
              @change="change"
              :readonly="!isHaveAccess"
            >
            </date-picker>
          </div>
          <!-- <div class="col-md-12 col-lg-12">
              <label class="control-label">Tồn tại</label>
              <textarea class="form-control" v-model="intermediate_data.ghi_chu" rows="5" @change="change"></textarea>
            </div> -->
          <div class="col-md-12 col-lg-12">
            <div class="nav-tabs-custom margin-top">
              <ul class="nav nav-tabs">
                <li class="active">
                  <a
                    href="#hinh_thu_xu_phat_chinh"
                    data-toggle="tab"
                    aria-expanded="true"
                    >Hình thức xử phạt chính</a
                  >
                </li>
                <li>
                  <a
                    href="#hinh_thuc_xu_phat_bo_xung"
                    data-toggle="tab"
                    aria-expanded="false"
                    >Hình thức xử phạt bổ sung</a
                  >
                </li>
                <li>
                  <a
                    href="#bien_phap_khac_phuc_hau_qua"
                    data-toggle="tab"
                    aria-expanded="false"
                    >Biện pháp khắc phục hậu quả</a
                  >
                </li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="hinh_thu_xu_phat_chinh">
                  <XuPhatChinh
                    :inputData.sync="intermediate_data.xu_phat_chinh"
                    :readonly="!isHaveAccess"
                  ></XuPhatChinh>
                </div>
                <div class="tab-pane" id="hinh_thuc_xu_phat_bo_xung">
                  <XuPhatBoXung
                    :inputData.sync="intermediate_data.xu_phat_bo_sung"
                    :readonly="!isHaveAccess"
                  ></XuPhatBoXung>
                </div>
                <div class="tab-pane" id="bien_phap_khac_phuc_hau_qua">
                  <BienPhapKhacPhucHauQua
                    :inputData.sync="
                      intermediate_data.bien_phap_khac_phuc_hau_qua
                    "
                    :readonly="!isHaveAccess"
                  ></BienPhapKhacPhucHauQua>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </span>
</template>
<script>
import * as env from "../../env.js";
import { mask } from "vue-the-mask";
import moment from "../../../../../node_modules/admin-lte/bower_components/moment/moment.js";
import money from "v-money";
import datePicker from "vue-bootstrap-datetimepicker";
import { en, vi } from "vuejs-datepicker/dist/locale";
import Multiselect from "vue-multiselect";
import XuPhatChinh from "./xuPhatChinh.vue";
import XuPhatBoXung from "./xuPhatBoXung.vue";
import BienPhapKhacPhucHauQua from "./bienPhapKhacPhucHauQua.vue";

Vue.filter("formatDate", function (value) {
  if (value) {
    return moment(String(value)).format("DD/MM/YYYY");
  }
});
export default {
  components: {
    datePicker,
    Multiselect,
    XuPhatChinh,
    XuPhatBoXung,
    BienPhapKhacPhucHauQua,
  },
  props: ["usersystem", "inputData"],
  data: function () {
    return {
      optionsDate: {
        format: "DD/MM/YYYY",
        useCurrent: false,
        locale: "vi",
      },
      en: en,
      vi: vi,
      money: {
        decimal: ".",
        thousands: ",",
        prefix: "",
        suffix: "",
        precision: 2,
        masked: true,
      },
      is_loading: false,
      errors: {
        dieu: null,
        khoan: null,
        muc: null,
        so_quyet_dinh: null,
        co_quan_ban_hanh: null,
        thoi_gian_ban_hanh: null,
        co_so_san_xuat: null,
      },
      coquantochucs: [],
      intermediate_data: {
        so_quyet_dinh: null,
        co_quan_quyet_dinh_xu_phat: null,
        thoi_gian_ban_hanh: null,
        ghi_chu: null,
        so_quyet_dinh_sua_doi: null,
        ngay_sua_doi_quyet_dinh: null,
        xu_phat_chinh: [],
        xu_phat_bo_sung: [],
        bien_phap_khac_phuc_hau_qua: [],
      },
    };
  },
  computed: {
    isHaveAccess() {
      return ["admin", "nhanvientrungtam", "adminvaquanlydanhmuc"].includes(
        this.usersystem.role_code
      );
    },
  },
  methods: {
    maTen({ ma, ten }) {
      return `Mục ${ma} - ${ten}`;
    },
    validator() {
      this.resetErrors();
      var valid = true;
      return valid;
    },
    resetErrors() {
      this.errors.so_quyet_dinh = null;
      this.errors.co_quan_ban_hanh = null;
      this.errors.thoi_gian_ban_hanh = null;
      this.errors.co_so_san_xuat = null;
    },
    change() {
      this.inputData[0] = this.intermediate_data;
    },
  },
  mounted: function () {
    // co quan to chuc
    if (localStorage.getItem("coquantochucs")) {
      this.coquantochucs = JSON.parse(localStorage.getItem("coquantochucs"));
    } else {
      axios.get(env.endpointhttp + "coquantochucs").then((response) => {
        this.coquantochucs = response.data.result;
        localStorage.setItem(
          "coquantochucs",
          JSON.stringify(this.coquantochucs)
        );
      });
    }
  },
  watch: {
    inputData: function (value) {
      if (value.length > 0) {
        this.intermediate_data = value[0];
      } else {
        this.intermediate_data = {
          so_quyet_dinh: null,
          co_quan_quyet_dinh_xu_phat: null,
          thoi_gian_ban_hanh: null,
          ghi_chu: null,
          so_quyet_dinh_sua_doi: null,
          ngay_sua_doi_quyet_dinh: null,
          xu_phat_chinh: [],
          xu_phat_bo_sung: [],
          bien_phap_khac_phuc_hau_qua: [],
        };
      }
    },
    "intermediate_data.xu_phat_chinh": function(val){
      this.inputData[0] = this.intermediate_data
    }
  },
};
</script>
