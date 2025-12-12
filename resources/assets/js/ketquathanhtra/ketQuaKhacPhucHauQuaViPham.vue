<template>
  <span>
    <div
      class="row block-multiple-rows"
      v-if="
        usersystem.role_code == 'admin' ||
          usersystem.role_code == 'nhanvientrungtam' ||
          usersystem.role_code == 'adminvaquanlydanhmuc'
      "
    >
      <div class="col-lg-3 col-md-6">
        <label>Số biên lai nộp phạt</label>
        <input
          type="text"
          v-model="intermediate_data.so_quyet_dinh"
          class="form-control"
          tabindex="1"
          required
          @change="change"
        />
      </div>
      <div class="col-lg-3 col-md-6">
        <label>Số báo cáo</label>
        <input
          type="text"
          v-model="intermediate_data.so_bao_cao"
          class="form-control"
          tabindex="1"
          required
          @change="change"
        />
      </div>
      <div class="col-lg-3 col-md-6">
        <label class="control-label">Ngày ban ban hành</label>
        <date-picker
          v-model="intermediate_data.ngay_ban_hanh_bao_cao"
          placeholder="Ngày ban hành kết luận"
          :config="optionsDate"
          @change="change"
        ></date-picker>
      </div>
      <div class="col-lg-3 col-md-6">
        <label class="control-label"
          >Số tiền đã nộp phạt<span style="color:rgb(216, 27, 96)">
            (VNĐ đồng)</span
          ></label
        >
        <ejs-numerictextbox
          :disabled="
            !(intermediate_data.da_nop_phat || intermediate_data.nop_mot_phan)
          "
          class="form-control"
          format=".###"
          v-model="intermediate_data.so_tien"
          type="text"
          @change="change"
        >
        </ejs-numerictextbox>
      </div>
      <div class="col-lg-12 col-md-12">
        <div class="row">
          <div class="col-lg-3 col-md-6">
            <checkbox-component
              v-model="intermediate_data.da_nop_phat"
              type="checkbox"
              id="da_nop_phat"
              text="Nộp xong"
              @change="changeNopPhat"
            >
            </checkbox-component>
            <checkbox-component
              v-model="intermediate_data.nop_mot_phan"
              type="checkbox"
              id="nop_mot_phan"
              text="Nộp một phần"
              @change="changeNopPhat"
            >
            </checkbox-component>
            <checkbox-component
              v-model="intermediate_data.da_khac_phuc"
              type="checkbox"
              id="da_khac_phuc"
              text="Đã khắc phục"
              @change="change"
            >
            </checkbox-component>
            <checkbox-component
              v-model="intermediate_data.da_bao_cao"
              type="checkbox"
              id="da_bao_cao"
              @change="change"
              text="Đã báo cáo"
            >
            </checkbox-component>
            <span v-show="intermediate_data.da_bao_cao">
              <div class="form-group">
                <multiselect
                  v-model="intermediate_data.tinh_trang_bao_cao"
                  :options="danhsachtinhtrangbaocao"
                  :searchable="false"
                  :close-on-select="false"
                  :show-labels="false"
                  placeholder="Chọn tình trạng báo cáo"
                  @change="change"
                >
                </multiselect>
              </div>
              <label class="control-label">Thời gian ban hành</label>
              <date-picker
                v-model="intermediate_data.thoi_gian_ban_hanh"
                tabindex=""
                placeholder="Ngày ban hành"
                :config="optionsDate"
                @change="change"
              ></date-picker>
              <label>Số hiệu báo cáo</label>
              <input
                type="text"
                v-model="intermediate_data.so_hieu_bao_cao"
                class="form-control"
                @change="change"
              />
            </span>
          </div>
          <div class="col-lg-9 col-md-6">
            <label>Nội dung đã khắc phục</label>
            <textarea
              type="text"
              v-model="intermediate_data.noi_dung_da_khac_phuc"
              @change="change"
              class="form-control"
              placeholder="Nhập nội dung đã khắc phục"
              rows="5"
            ></textarea>
            <label>Nội dung chưa khắc phục</label>
            <textarea
              type="text"
              v-model="intermediate_data.noi_dung_chua_khac_phuc"
              @change="change"
              class="form-control"
              placeholder="Nhập nội dung chưa khắc phục"
              rows="6"
            ></textarea>
          </div>
        </div>
      </div>
    </div>
  </span>
</template>
<script>
import * as env from "../env.js";
import { mask } from "vue-the-mask";
import moment from "../../../../node_modules/admin-lte/bower_components/moment/moment.js";
import money from "v-money";
import datePicker from "vue-bootstrap-datetimepicker";
import { en, vi } from "vuejs-datepicker/dist/locale";
import Multiselect from "vue-multiselect";

Vue.filter("formatDate", function(value) {
  if (value) {
    return moment(String(value)).format("DD/MM/YYYY");
  }
});
export default {
  components: {
    datePicker,
    Multiselect
  },
  props: ["usersystem", "inputData"],
  data: function() {
    return {
      optionsDate: {
        format: "DD/MM/YYYY",
        useCurrent: false,
        locale: "vi"
      },
      en: en,
      vi: vi,
      money: {
        decimal: ".",
        thousands: ",",
        prefix: "",
        suffix: "",
        precision: 0,
        masked: false
      },
      is_loading: false,
      danhsachtinhtrangbaocao: ["Báo cáo đúng hạn", "Báo cáo chậm"],
      intermediate_data: {
        so_quyet_dinh: null,
        so_tien: 0,
        thoi_gian_ban_hanh: null,
        da_nop_phat: false,
        nop_mot_phan: false,
        da_bao_cao: null,
        da_khac_phuc: false,
        tinh_trang_bao_cao: null,
        ngay_ban_hanh_bao_cao: null,
        so_hieu_bao_cao: null,
        noi_dung_da_khac_phuc: null,
        noi_dung_chua_khac_phuc: null,
        so_bao_cao: null
      }
    };
  },
  methods: {
    change: function() {
      this.inputData[0] = this.intermediate_data;
    },
    changeNopPhat: function() {
      this.change();
      if (
        !this.intermediate_data.da_nop_phat &&
        !this.intermediate_data.da_nop_phat
      ) {
        this.intermediate_data.so_tien = 0;
      }
    }
  },
  watch: {
    inputData: function(value) {
      if (value.length > 0) {
        this.intermediate_data = value[0];
      } else {
        this.intermediate_data = {
          so_quyet_dinh: null,
          so_tien: 0,
          thoi_gian_ban_hanh: null,
          da_nop_phat: false,
          nop_mot_phan: false,
          da_bao_cao: null,
          da_khac_phuc: false,
          tinh_trang_bao_cao: null,
          ngay_ban_hanh_bao_cao: null,
          so_hieu_bao_cao: null,
          noi_dung_da_khac_phuc: null,
          noi_dung_chua_khac_phuc: null
        };
      }
    }
  }
};
</script>
