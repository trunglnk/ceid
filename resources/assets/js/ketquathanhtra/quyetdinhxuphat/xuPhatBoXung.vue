<template>
  <span>
    <div class="row">
      <!-- <div class="col-md-12 col-lg-12">
                <label class="control-label">Số tiền xử phạt bổ sung
                    <span style="color:rgb(216, 27, 96)">(VNĐ đồng)</span>
                </label>
                <ejs-numerictextbox class="form-control" format='.###' v-model="data.so_tien_xu_phat_bo_sung" type="text" @change="changeData">
                </ejs-numerictextbox>
            </div> -->
      <div class="col-md-12 col-lg-12">
        <label class="control-label">Nội dung xử phạt bổ sung</label>
        <textarea
          class="form-control"
          v-model="data.noi_dung_xu_phat_bo_sung"
          rows="5"
          @change="changeData"
          :readonly="readonly"
        ></textarea>
      </div>
      <div class="col-md-12 col-lg-6">
        <checkbox-component
          v-model="data.dinh_chi"
          type="checkbox"
          id="dinh_chi"
          text="Đình chỉ"
          @change="changeData"
          :readonly="readonly"
        >
        </checkbox-component>
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
        <tr v-for="(item, index) in data.chi_tiet_xu_phat_bo_sungs">
          <td>{{ item.loai_xu_phat_bo_sung.ten }}</td>
          <td align="center" v-if="!readonly">
            <span>
              <i class="fa fa-trash-o btn" @click="remove(index)"></i>
            </span>
          </td>
        </tr>
        <tr class="row-table-header" v-if="!readonly">
          <td>
            <multiselect
              v-model="intermediate_data.loai_xu_phat_bo_sung"
              label="ten"
              track-by="id"
              placeholder="Chọn điều"
              selectedLabel="Đã chọn"
              open-direction="bottom"
              :options="loaixuphatbosungs"
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
            <span class="help-block" v-if="errors.loai_xu_phat_bo_sung">
              <strong>{{ errors.loai_xu_phat_bo_sung }}</strong>
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
        loai_xu_phat_bo_sung: null,
      },
      loaixuphatbosungs: [],
      intermediate_data: {
        loai_xu_phat_bo_sung: null,
      },
      data: {
      chi_tiet_xu_phat_bo_sungs: [],
      },
    };
  },
  methods: {
    validator() {
      this.resetErrors();
      var valid = true;
      if (!this.intermediate_data.loai_xu_phat_bo_sung) {
        this.errors.loai_xu_phat_bo_sung = "Chưa chọn điều/khoản/mục.";
        valid = false;
      }
      return valid;
    },
    resetErrors() {
      this.errors.loai_xu_phat_bo_sung = null;
    },
    add: function () {
      if (this.validator()) {
        this.errors.loai_xu_phat_bo_sung = null;
        this.data.chi_tiet_xu_phat_bo_sungs.push({
          loai_xu_phat_bo_sung: this.intermediate_data.loai_xu_phat_bo_sung,
        });
        this.intermediate_data.loai_xu_phat_bo_sung = null;
        this.changeData();
      }
    },
    remove(index) {
      this.data.chi_tiet_xu_phat_bo_sungs.splice(index, 1);
      this.changeData();
    },
    changeData() {
      this.inputData[0] = this.data;
    },
    // change: function () {
    //   this.inputData[0] = this.data;
    // },
    initData: function (value) {
      if (value.length > 0) {
        this.data = value[0];
      }
    },
  },
  mounted: function () {
    if (localStorage.getItem("loaixuphatbosungs")) {
      this.loaixuphatbosungs = JSON.parse(
        localStorage.getItem("loaixuphatbosungs")
      );
    } else {
      this.is_loading = true;
      axios
        .get(env.endpointhttp + "loaixuphatbosungs")
        .then((response) => {
          this.loaixuphatbosungs = response.data.result;
          localStorage.setItem(
            "loaixuphatbosungs",
            JSON.stringify(this.loaixuphatbosungs)
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
          so_tien_xu_phat_bo_sung: 0,
          noi_dung_xu_phat_bo_sung: null,
          dinh_chi: false,
          chi_tiet_xu_phat_bo_sungs: [],
        };
      }
    },
  },
};
</script>
