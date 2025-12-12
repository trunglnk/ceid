<template>
  <span>
    <div class="row">
      <div class="col-md-12 col-lg-12">
        <label class="control-label">Số tiền xử phạt
          <span style="color: rgb(216, 27, 96)">(VNĐ đồng)</span>
        </label>
        <ejs-numerictextbox class="form-control" format=".###" v-model="xuPhat.so_tien_xu_phat_chinh" type="text"
          @change="changeData" :readonly="readonly">
        </ejs-numerictextbox>
      </div>
      <div class="col-md-12 col-lg-12">
        <label class="control-label">Nội dung vi phạm</label>
        <textarea class="form-control" v-model="xuPhat.noi_dung_vi_pham" rows="5" @change="changeData"
          :readonly="readonly"></textarea>
      </div>
    </div>
    <!-- <div style="font-weight: bold">Hành vi vi phạm theo nghị định 155/2016/NĐ-CP</div>
    <p class="text-muted" v-if="!readonly">
      Chọn điều, khoản, điểm từ các ô tìm kiếm dưới đây, nhấn dấu + để thêm vào
      danh sách hành vi vi phạm
    </p>
    <table class="table table-hover table-responsive">
      <tbody>
        <tr class="row-table-header">
          <th>Điều</th>
          <th>Khoản</th>
          <th>Điểm</th>
          <th style="width: 5%; text-align: center" v-if="!readonly">Xóa</th>
        </tr>
        <tr v-for="(item, index) in xuPhat.hanh_vi_vi_phams" :key="index">
          <td>{{ item.dieu.ten }}</td>
          <td>{{ item.khoan.ten }}</td>
          <td v-if="item.muc_id">
            {{
            item.muc ? "Mục " + item.muc.ma + " - " + item.muc.mo_ta : null
            }}
          </td>
          <td v-else><span>---</span></td>
          <td align="center" v-if="!readonly">
            <span>
              <i class="fa fa-trash-o btn" @click="remove(index)"></i>
            </span>
          </td>
        </tr>
        <tr class="row-table-header" v-if="!readonly">
          <td>
            <multiselect v-model="intermediate_data.dieu" label="ten" track-by="id" placeholder="Chọn điều"
              selectedLabel="Đã chọn" open-direction="bottom" :options="dieus" :multiple="false" :searchable="true"
              :clear-on-select="true" :close-on-select="true" :show-no-results="false" :hide-selected="false"
              :tabindex="1" @input="selectDieu">
              <span slot="noResult">Không tìm thấy kết quả</span>
            </multiselect>
            <span class="help-block" v-if="errors.dieu">
              <strong>{{ errors.dieu }}</strong>
            </span>
          </td>
          <td>
            <multiselect v-model="intermediate_data.khoan" label="ten" track-by="id" placeholder="Chọn khoản"
              selectedLabel="Đã chọn" open-direction="bottom" :options="khoans" :multiple="false" :searchable="true"
              :clear-on-select="true" :close-on-select="true" :show-no-results="false" :hide-selected="false"
              :tabindex="1" @input="selectKhoan">
              <span slot="noResult">Không tìm thấy kết quả</span>
            </multiselect>
            <span class="help-block" v-if="errors.khoan">
              <strong>{{ errors.khoan }}</strong>
            </span>
          </td>
          <td>
            <multiselect v-model="intermediate_data.muc" label="ma_ten" track-by="id" placeholder="Chọn điểm"
              selectedLabel="Đã chọn" open-direction="bottom" :options="mucs" :multiple="false" :searchable="true"
              :clear-on-select="true" :close-on-select="true" :show-no-results="false" :hide-selected="false"
              :tabindex="1" :custom-label="maTen">
              <span slot="noResult">Không tìm thấy kết quả</span>
            </multiselect>
            <span class="help-block" v-if="errors.muc">
              <strong>{{ errors.muc }}</strong>
            </span>
          </td>
          <td>
            <button type="button" class="btn btn-flat pull-right" @click="addViPham()">
              <i class="fa fa-plus"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table> -->
    <div style="font-weight: bold">Hành vi vi phạm theo nghị định 45/2022/NĐ-CP</div>
    <div class="row">
      <div class="col-md-6 col-lg-6">
        <multiselect v-model="maMucNd45" label="ma_hanh_vi" track-by="id" placeholder="Mã danh mục" style="width: 100%"
          selectedLabel="Đã chọn" open-direction="bottom" :options="hanhViNd45" :multiple="false" :searchable="true"
          @search-change="getDanhMucNghiDinh45"
          :clear-on-select="true" :close-on-select="true" :show-no-results="false" :hide-selected="false" :tabindex="1">
          <span slot="noResult">Không tìm thấy kết quả</span>
          <template slot="option" slot-scope="props">
            <div>{{ props.option.ma_hanh_vi }}</div>
            <div style="font-size: 13px;" class="mt-1">{{ props.option.nhom_hanh_vi }}</div>
          </template>

        </multiselect>

      </div>
      <div class="col-md-2 col-lg-2">
        <button type="button" class="btn btn-flat" style="height: 40px; width: 50px" @click="addViPhamNd45()">
          <i class="fa fa-plus"></i>
        </button>
      </div>
    </div>

    <div style="font-weight: bold">Hành vi vi phạm theo nghị định 155/2016/NĐ-CP</div>
    <div class="row">
      <div class="col-md-6 col-lg-6">
        <multiselect v-model="maMucNd155" label="ma_hanh_vi" track-by="id" placeholder="Mã danh mục" style="width: 100%"
          selectedLabel="Đã chọn" open-direction="bottom" :options="hanhViNd155" :multiple="false" :searchable="true"
          :clear-on-select="true" :close-on-select="true" :show-no-results="false" :hide-selected="false" :tabindex="1"
          @search-change="getDanhMucNghiDinh155">
          <span slot="noResult">Không tìm thấy kết quả</span>
          <template slot="option" slot-scope="props">
            <div>{{ props.option.ma_hanh_vi }}</div>
            <div style="font-size: 13px;" class="mt-1">{{ props.option.nhom_hanh_vi }}</div>
          </template>

        </multiselect>

      </div>
      <div class="col-md-2 col-lg-2">
        <button type="button" class="btn btn-flat" style="height: 40px; width: 50px" @click="addViPhamNd155()">
          <i class="fa fa-plus"></i>
        </button>
      </div>
      <div class="col-md-12 col-lg-12 mt-4">
        <table class="table table-hover fixed_headers">
          <thead class="row-table-header">
            <tr class="row-table-header">
              <th>Mã hành vi</th>
              <th>Nhóm hành vi</th>
              <th>Mức phạt nhỏ nhất</th>
              <th>Mức phạt lớn nhất</th>
              <th>Tên hành vi</th>
              <th>Hình thức xử phạt bổ sung</th>
              <th>Biện pháp khăc phục hậu quả</th>
              <td>Xóa</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in xuPhat.hanh_vi_vi_phams" :key="index">
              <td style="width: 100px">{{ item.hanh_vi.ma_hanh_vi }}</td>
              <td>{{ item.hanh_vi.nhom_hanh_vi }}</td>
              <td>{{ Number(item.hanh_vi.phat_nho_nhat).toLocaleString('de-DE') }}</td>
              <td>{{ Number(item.hanh_vi.phat_lon_nhat).toLocaleString('de-DE') }}</td>
              <td>{{ item.hanh_vi.ten_hanh_vi }}</td>
              <td>{{ item.hanh_vi.xu_phat_bo_xung }}</td>
              <td>{{ item.hanh_vi.bien_phap_khac_phuc_hau_qua }}</td>
              <td align="center" v-if="!readonly">
                <span>
                  <i class="fa fa-trash-o btn" @click="remove(index)"></i>
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

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
        dieu: null,
        khoan: null,
        muc: null,
      },
      dieus: [],
      khoans: [],
      mucs: [],
      intermediate_data: {
        dieu: null,
        khoan: null,
        muc: null,
      },
      maMucNd45: null,
      maMucNd155: null,
      hanhViNd155: [],
      viPhamNd155: [],
      hanhViNd45: [],
      viPhamNd45: [],
      xuPhat: {
      hanh_vi_vi_phams: [],
      },
    };
  },
  methods: {
    maTen({ ma, mo_ta }) {
      return `Mục ${ma} - ${mo_ta}`;
    },
    getDanhMucNghiDinh45(query) {
      axios
        .get(env.endpointhttp + "getnghidinh45cpdongbo", {
          params: {
            page: this.page,
            perPage: 10000,
            search: this.query,
          },
        })
        .then((response) => {
          this.hanhViNd45 = response.data.data;
        });
    },
    getDanhMucNghiDinh155(query) {
      axios
        .get(env.endpointhttp + "getnghidinh155dongbo", {
          params: {
            page: this.page,
            perPage: 10000,
            search: this.query,
          },
        })
        .then((response) => {
          this.hanhViNd155 = response.data.data;
        });
    },
    validator() {
      this.resetErrors();
      var valid = true;
      return valid;
    },
    resetErrors() {
      this.errors.dieu = null;
      this.errors.khoan = null;
      this.errors.muc = null;
    },
    selectDieu() {
      this.intermediate_data.khoan = null;
      this.intermediate_data.muc = null;
      this.mucs = [];
      if (this.intermediate_data.dieu) {
        var khoans = this.intermediate_data.dieu.khoans;
        this.khoans = khoans;
      } else {
        this.khoans = [];
      }
    },
    selectKhoan() {
      this.intermediate_data.muc = null;
      if (this.intermediate_data.khoan) {
        this.mucs = this.intermediate_data.khoan.mucs;
      } else {
        this.mucs = [];
      }
    },
    addViPham: function () {
      this.errors.dieu = null;
      this.errors.khoan = null;
      this.errors.muc = null;
      if (
        this.intermediate_data.dieu == null ||
        this.intermediate_data.dieu == ""
      ) {
        this.errors.dieu = "Chưa chọn điều.";
        this.errors.khoan = null;
      } else if (
        this.intermediate_data.khoan == null ||
        this.intermediate_data.khoan == ""
      ) {
        this.errors.khoan = "Chưa chọn khoản.";
        this.errors.dieu = null;
      } else {
        this.errors.dieu = null;
        this.errors.khoan = null;
        this.xuPhat.hanh_vi_vi_phams.push({
          dieu: this.intermediate_data.dieu,
          khoan: this.intermediate_data.khoan,
          muc: this.intermediate_data.muc,
        });
        this.intermediate_data.dieu = null;
        this.intermediate_data.khoan = null;
        this.intermediate_data.muc = null;
        this.khoans = [];
        this.mucs = [];
        this.changeData();
      }
    },
    remove(index) {
      this.xuPhat.hanh_vi_vi_phams.splice(index, 1);
      this.changeData();
    },
    removeViPhamNd45(index) {
      this.viPhamNd45.splice(index, 1);
    },
    changeData() {
      this.inputData[0] = this.xuPhat;
    },
    addViPhamNd45() {
      if (this.maMucNd45) {
        const checkPused = this.viPhamNd45.find(el => el.id == this.maMucNd45.id);
        if (!checkPused) {
          this.viPhamNd45.push(this.maMucNd45);
          this.xuPhat.hanh_vi_vi_phams.push({hanh_vi: this.maMucNd45})
          this.changeData()
          this.maMucNd45 = null
        }
      }


    },
    addViPhamNd155() {
      if (this.maMucNd155) {
        const checkPused = this.viPhamNd155.find(el => el.id == this.maMucNd155.id);
        if (!checkPused) {
          this.xuPhat.hanh_vi_vi_phams.push({hanh_vi: this.maMucNd155})
          this.changeData()
          this.maMucNd155 = null
        }
      }
    },
    change: function () {
      this.inputData[0] = this.xuPhat;
    },
    initData: function (value) {
      if (value.length > 0) {
        this.xuPhat = value[0];
      }
    },
  },
  mounted: function () {
    this.getDanhMucNghiDinh45()
    this.getDanhMucNghiDinh155()
    if (localStorage.getItem("dieus")) {
      this.dieus = JSON.parse(localStorage.getItem("dieus"));
    } else {
      this.is_loading = true;
      axios
        .get(env.endpointhttp + "dieus")
        .then((response) => {
          this.dieus = response.data.result;
          localStorage.setItem("dieus", JSON.stringify(this.dieus));
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
    xuPhat: function () {
      if (this.inputData && this.inputData.length > 0) {
        return this.inputData[0];
      } else {
        return {
          so_tien_xu_phat_chinh: 0,
          noi_dung_vi_pham: null,
          hanh_vi_vi_phams: [],
        };
      }
    }
  },
};
</script>
