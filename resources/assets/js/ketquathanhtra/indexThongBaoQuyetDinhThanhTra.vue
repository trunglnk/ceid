<template>
  <div class="row block-multiple-rows">
    <div class="col-lg-12" style="margin-top: 5px;">
      <p class="text-muted">
        Nhập thông tin số thông báo, chọn thời gian, cơ quan ban hành và nhấn
        nút thêm để thêm kết luận thanh tra
      </p>
      <hr style="margin-top: 7px;margin-bottom: 7px;" />
    </div>
    <div class="col-lg-12 col-md-12">
      <table class="table table-hover table-responsive">
        <tbody>
          <tr class="row-table-header">
            <th>Thời gian</th>
            <th>Số thông báo quyết định</th>
            <th>Cơ quan ban hành KLTT</th>
            <th
              style="width: 5%; text-align: center"
              v-if="
                usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'nhanvientrungtam' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
              "
            >
              Xóa
            </th>
          </tr>
          <tr v-for="(item, index) in inputData">
            <td>
              <date-picker
                v-model="item.thoi_gian"
                placeholder="Thời gian"
                :config="optionsDate"
              >
              </date-picker>
            </td>
            <td>
              <input
                placeholder="Số thông báo"
                type="text"
                v-model="item.so_thong_bao_quyet_dinh"
                class="form-control"
                required
              />
            </td>
            <td>
              <multiselect
                v-model="item.co_quan_thong_bao"
                label="ten"
                track-by="id"
                placeholder="Gõ tên cơ quan ban hành"
                selectedLabel="Đã chọn"
                open-direction="bottom"
                :options="coquantochucs"
                :multiple="false"
                :searchable="true"
                :clear-on-select="true"
                :close-on-select="true"
                :show-no-results="false"
                :hide-selected="false"
                :tabindex="1"
              >
                <span slot="noResult">Không tìm thấy kết quả</span>
              </multiselect>
            </td>
            <td
              align="center"
              v-if="
                usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'nhanvientrungtam' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
              "
            >
              <a class="btn">
                <i class="fa fa-trash-o" @click="remove(index, item.id)"></i>
              </a>
            </td>
          </tr>
          <tr class="row-table-header">
            <td>
              <date-picker
                v-model="intermediate_data.thoi_gian"
                placeholder="Thời gian"
                :config="optionsDate"
              >
              </date-picker>
              <span class="help-block" v-if="errors.thoi_gian">
                <strong>{{ errors.thoi_gian }}</strong>
              </span>
            </td>
            <td>
              <input
                placeholder="Số thông báo"
                type="text"
                v-model="intermediate_data.so_thong_bao_quyet_dinh"
                class="form-control"
                required
              />
              <span class="help-block" v-if="errors.so_thong_bao_quyet_dinh">
                <strong>{{ errors.so_thong_bao_quyet_dinh }}</strong>
              </span>
            </td>
            <td>
              <multiselect
                v-model="intermediate_data.co_quan_thong_bao"
                label="ten"
                track-by="id"
                placeholder="Gõ tên cơ quan ban hành"
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
              >
                <span slot="noResult">Không tìm thấy kết quả</span>
              </multiselect>
              <span class="help-block" v-if="errors.co_quan_thong_bao">
                <strong>{{ errors.co_quan_thong_bao }}</strong>
              </span>
            </td>
            <td>
              <button
                class="btn btn-flat btn-default btn-block"
                @click="addThongBaoQuyetDinh()"
              >
                <i class="fa fa-plus"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<script>
import * as env from "../env.js";
import { mask } from "vue-the-mask";
import moment from "../../../../node_modules/admin-lte/bower_components/moment/moment.js";
import datePicker from "vue-bootstrap-datetimepicker";
import { en, vi } from "vuejs-datepicker/dist/locale";
import Multiselect from "vue-multiselect";
import ValidatorService from "../services/ValidatorService";

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
      coquantochucs: [],
      is_loading: false,
      errors: {
        so_thong_bao_quyet_dinh: null,
        co_quan_thong_bao: null,
        thoi_gian: null
      },
      intermediate_data: {
        thoi_gian: null,
        so_thong_bao_quyet_dinh: null,
        co_quan_thong_bao: null
      }
    };
  },
  methods: {
    remove: function(index, id) {
      this.inputData.splice(index, 1);
    },
    addThongBaoQuyetDinh: function() {
      this.is_loading = true;
      if (this.validator()) {
        this.inputData.push({
          so_thong_bao_quyet_dinh: this.intermediate_data
            .so_thong_bao_quyet_dinh,
          co_quan_thong_bao: this.intermediate_data.co_quan_thong_bao,
          thoi_gian: this.intermediate_data.thoi_gian
        });
        ValidatorService.setNull(this.intermediate_data);
      }
    },
    validator() {
      ValidatorService.setNull(this.errors);
      var valid = true;
      if (!this.intermediate_data.so_thong_bao_quyet_dinh) {
        this.errors.so_thong_bao_quyet_dinh = "Thiếu số thông báo";
        valid = false;
      }
      if (!this.intermediate_data.thoi_gian) {
        this.errors.thoi_gian = "Thiếu trường thời gian";
        valid = false;
      }
      if (!this.intermediate_data.co_quan_thong_bao) {
        this.errors.co_quan_thong_bao = "Thiếu cơ quan thông báo";
        valid = false;
      }
      return valid;
    }
  },
  mounted: function() {
    // co quan to chuc
    if (localStorage.getItem("coquantochucs")) {
      this.coquantochucs = JSON.parse(localStorage.getItem("coquantochucs"));
    } else {
      axios.get(env.endpointhttp + "coquantochucs").then(response => {
        this.coquantochucs = response.data.result;
        localStorage.setItem(
          "coquantochucs",
          JSON.stringify(this.coquantochucs)
        );
      });
    }
  }
};
</script>
