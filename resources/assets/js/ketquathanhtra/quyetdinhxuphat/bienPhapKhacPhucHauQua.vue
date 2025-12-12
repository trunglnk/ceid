<template>
  <span>
    <div class="row">
      <div class="col-md-12 col-lg-12">
        <label class="control-label"
          >Số tiền chưng cầu
          <span style="color: rgb(216, 27, 96)">(VNĐ đồng)</span>
        </label>
        <ejs-numerictextbox
          class="form-control"
          format=".###"
          v-model="data.so_tien_chung_cau"
          type="text"
          @change="changeData"
          :readonly="readonly"
        >
        </ejs-numerictextbox>
      </div>
      <div class="col-md-12 col-lg-12">
        <label class="control-label"
          >Nội dung biện pháp khắc phục hậu quả</label
        >
        <textarea
          class="form-control"
          v-model="data.noi_dung_bien_phap_khac_phuc_hau_qua"
          rows="5"
          @change="changeData"
          :readonly="readonly"
        ></textarea>
      </div>
    </div>
    <p class="text-muted" v-if="!readonly">
      Chọn điều, khoản, điểm từ các ô tìm kiếm dưới đây, nhấn dấu + để thêm vào
      danh sách
    </p>
    <table class="table table-hover table-responsive">
      <tbody>
        <tr class="row-table-header">
          <th>Điều/Khoản/Mục</th>
          <th style="width: 5%; text-align: center" v-if="!readonly">Xóa</th>
        </tr>
        <tr v-for="(item, index) in data.chi_tiet_bien_phap_khac_phuc_hau_quas">
          <td>{{ item.cac_bien_phap_khac_phuc_hau_qua.ten }}</td>
          <td align="center" v-if="!readonly">
            <span>
              <i class="fa fa-trash-o btn" @click="remove(index)"></i>
            </span>
          </td>
        </tr>
        <tr class="row-table-header" v-if="!readonly">
          <td>
            <multiselect
              v-model="intermediate_data.cac_bien_phap_khac_phuc_hau_qua"
              label="ten"
              track-by="id"
              placeholder="Chọn điều/khoản/mục"
              selectedLabel="Đã chọn"
              open-direction="bottom"
              :options="cacbienphapkhacphuchauquas"
              :multiple="false"
              :searchable="true"
              :clear-on-select="true"
              :close-on-select="true"
              :show-no-results="false"
              :hide-selected="false"
            >
              <span slot="noResult">Không tìm thấy kết quả</span>
            </multiselect>
            <span
              class="help-block"
              v-if="errors.cac_bien_phap_khac_phuc_hau_qua"
            >
              <strong>{{ errors.cac_bien_phap_khac_phuc_hau_qua }}</strong>
            </span>
          </td>
          <td>
            <button
              type="button"
              class="btn btn-flat pull-right"
              @click="add()"
            >
              <i class="fa fa-plus"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </span>
</template>
<script>
import * as env from "../../env.js";
import { mask } from "vue-the-mask";
import moment from "../../../../../node_modules/admin-lte/bower_components/moment/moment.js";
import money from "v-money";
import Multiselect from "vue-multiselect";

export default {
  components: {
    Multiselect,
  },
  props: ["usersystem", "inputData", "readonly"],
  data: function () {
    return {
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
        cac_bien_phap_khac_phuc_hau_qua: null,
      },
      cacbienphapkhacphuchauquas: [],
      intermediate_data: {
        cac_bien_phap_khac_phuc_hau_qua: null,
      },
      data: {
        chi_tiet_bien_phap_khac_phuc_hau_quas: []
      }
    };
  },
  methods: {
    validator() {
      this.resetErrors();
      var valid = true;
      if (!this.intermediate_data.cac_bien_phap_khac_phuc_hau_qua) {
        this.errors.cac_bien_phap_khac_phuc_hau_qua =
          "Chưa chọn điều/khoản/mục.";
        valid = false;
      }
      return valid;
    },
    resetErrors() {
      this.errors.cac_bien_phap_khac_phuc_hau_qua = null;
    },
    add: function () {
      if (this.validator()) {
        this.errors.cac_bien_phap_khac_phuc_hau_qua = null;
        this.data.chi_tiet_bien_phap_khac_phuc_hau_quas.push({
          cac_bien_phap_khac_phuc_hau_qua: this.intermediate_data
            .cac_bien_phap_khac_phuc_hau_qua,
        });
        this.intermediate_data.cac_bien_phap_khac_phuc_hau_qua = null;
        this.changeData();
      }
    },
    remove(index) {
      this.data.chi_tiet_bien_phap_khac_phuc_hau_quas.splice(index, 1);
      this.changeData();
    },
    changeData() {
      this.inputData[0] = this.data;
    },
    initData: function (value) {
      if (value.length > 0) {
        this.data = value[0];
      }
    },
  },
  mounted: function () {
    if (localStorage.getItem("cacbienphapkhacphuchauquas")) {
      this.cacbienphapkhacphuchauquas = JSON.parse(
        localStorage.getItem("cacbienphapkhacphuchauquas")
      );
    } else {
      this.is_loading = true;
      axios
        .get(env.endpointhttp + "cacbienphapkhacphuchauquas")
        .then((response) => {
          this.cacbienphapkhacphuchauquas = response.data.result;
          localStorage.setItem(
            "cacbienphapkhacphuchauquas",
            JSON.stringify(this.cacbienphapkhacphuchauquas)
          );
        })
        .catch(function (error) {
          console.log(error);
        })
        .finally(() => (this.is_loading = false));
    }
  },
  watch: {
    inputData: "initData",    
  },
  computed: {
    data: function () {
      if (this.inputData && this.inputData.length > 0) {
        return this.inputData[0];
      } else {
        return {
          so_tien_chung_cau: 0,
          noi_dung_bien_phap_khac_phuc_hau_qua: null,
          chi_tiet_bien_phap_khac_phuc_hau_quas: [],
        };
      }
    },
  },
};
</script>
