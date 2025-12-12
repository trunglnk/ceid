<template>
  <div class="row">
    <div class="row ma-2">
      <div class="col-md-6">
        <label>Tên chủ đầu tư (*)</label>
        <input
          v-model="form.ten"
          type="text"
          class="form-control"
          placeholder="Tên cơ chủ đầu tư"
          v-validate="'required'"
          name="ten"
        />
        <span
          v-show="errors.has('ten')"
          class="help is-danger"
          style="color: red"
          >Tên chủ đầu tư không thể bỏ trống</span
        >
      </div>
      <div class="col-md-3">
        <label>Số điện thoại</label>
        <input
          v-model="form.so_dien_thoai"
          type="text"
          class="form-control"
          placeholder="Số điện thoại"
        />
      </div>
      <div class="col-md-3">
        <label>Email</label>
        <input
          v-model="form.email"
          type="text"
          class="form-control"
          placeholder="Email thư điện tử"
        />
      </div>
    </div>
    <div class="row ma-2 mt-4">
      <div class="col-md-6">
        <label>Người đại diện</label>
        <input
          v-model="form.nguoi_dai_dien"
          type="text"
          class="form-control"
          placeholder="Người đại diện"
        />
      </div>
      <div class="col-md-3">
        <label>Mã số QLCTNH</label>
        <input
          v-model="form.ma_so_qlctnh"
          type="text"
          class="form-control"
          placeholder="Mã số QLCTNH"
        />
      </div>
      <div class="col-md-3">
        <label>Số Fax</label>
        <input
          v-model="form.fax"
          type="text"
          class="form-control"
          placeholder="Số fax"
        />
      </div>
    </div>
    <div class="row ma-2 mt-4">
      <div class="col-md-3">
        <label>Tỉnh thành</label>
        <!-- <input
          v-model="tinhThanhSelect.ten"
          type="text"
          class="form-control"
          placeholder="Tỉnh thành"
          :options="tinhs"
        /> -->
        <multiselect v-model="tinhThanhSelect" label="ten" track-by="id" placeholder="Gõ tên Tỉnh thành"
          selectLabel="Nhấn enter để chọn" deselectLabel="Nhấn enter để bỏ chọn" :options="tinhs" :multiple="false"
          :searchable="true">
        </multiselect>
      </div>
      <div class="col-md-3">
        <label>Quận huyện</label>
        <!-- <input
          v-model="quanHuyenSelect.ten"
          type="text"
          class="form-control"
          placeholder="Quận huyện"
        /> -->
        <multiselect v-model="quanHuyenSelect" label="ten" track-by="id" placeholder="Gõ tên quận huyện"
          selectLabel="Nhấn enter để chọn" deselectLabel="Nhấn enter để bỏ chọn" :options="quanHuyenOption"
          :multiple="false" :searchable="true">
          <span slot="noResult">Không tìm thấy kết quả</span>
        </multiselect>
      </div>
      <div class="col-md-3">
        <label>Xã phường</label>
        <input
          v-model="form.xa_phuong"
          type="text"
          class="form-control"
          placeholder="Xã phường"
        />
      </div>
      <div class="col-md-3">
        <label>Địa chỉ</label>
        <input
          v-model="form.dia_chi"
          type="text"
          class="form-control"
          placeholder="Địa chỉ - số nhà"
        />
      </div>
    </div>
    <div style="border: 1px solid #d7dbdd" class="ma-4 pb-3">
      <div
        class="pl-4 pt-2"
        style="color: #2471a3; font-size: 16px; font-weight: bold"
      >
        Giấy chứng nhận kinh doanh
      </div>
      <div class="row ma-1 mt-4">
        <div class="col-md-6">
          <label>Số giấy chứng nhận kinh doanh</label>
          <input
            v-model="form.so_giay_chung_nhan_dang_ky_kinh_doanh"
            type="text"
            class="form-control"
            placeholder="Nhập số giấy chứng nhận đăng ký kinh doanh"
          />
        </div>
        <div class="col-md-6">
          <label>Cơ quan cấp cấp</label>
          <multiselect
            v-model="coquanSelect"
            label="ten"
            track-by="id"
            placeholder="Gõ tên cơ quan"
            selectLabel="Nhấn enter để chọn"
            deselectLabel="Nhấn enter để bỏ chọn"
            :options="coquans"
            :multiple="false"
            :searchable="true"
          >
          </multiselect>
        </div>
      </div>

      <div class="row ma-1 mt-4">
        <div class="col-md-3">
          <label>Ngày cấp</label>
          <input
            v-model="form.ngay_cap_giay_chung_nhan_dang_ky_kinh_doanh"
            type="date"
            class="form-control"
          />
        </div>
        <div class="col-md-3">
          <label>Lần cấp</label>
          <input
            v-model="form.lan_cap_giay_chung_nhan_dang_ky_kinh_doanh"
            type="number"
            class="form-control"
            placeholder="lần cấp thứ"
          />
        </div>
        <div class="col-md-3">
          <label>Ngày cấp lần đầu</label>
          <input
            v-model="form.ngay_cap_lan_dau_giay_chung_nhan_dang_ky_kinh_doanh"
            type="date"
            class="form-control"
          />
        </div>
      </div>
    </div>
    <div style="border: 1px solid #d7dbdd" class="ma-4 pb-3">
      <div
        class="pl-4 pt-2"
        style="color: #2471a3; font-size: 16px; font-weight: bold"
      >
        Giấy chứng nhận đầu tư
      </div>
      <div class="row ma-1 mt-4">
        <div class="col-md-6">
          <label>Số giấy chứng nhận đầu tư</label>
          <input
            v-model="form.so_giay_chung_nhan_dau_tu"
            type="text"
            class="form-control"
            placeholder="Nhập số giấy chứng nhận đầu tư"
          />
        </div>
        <div class="col-md-6">
          <label>Nơi cấp giấy chứng nhận đầu tư</label>
          <multiselect
            v-model="noicapSelect"
            label="ten"
            track-by="id"
            placeholder="Gõ tên cơ quan"
            selectLabel="Nhấn enter để chọn"
            deselectLabel="Nhấn enter để bỏ chọn"
            :options="noicaps"
            :multiple="false"
            :searchable="true"
          >
          </multiselect>
        </div>
      </div>

      <div class="row ma-1 mt-4">
        <div class="col-md-3">
          <label>Ngày cấp</label>
          <input
            v-model="form.ngay_cap_giay_chung_nhan_dau_tu"
            type="date"
            class="form-control"
          />
        </div>
        <div class="col-md-3">
          <label>Lần cấp</label>
          <input
            v-model="form.lan_cap_giay_chung_nhan_dau_tu"
            type="number"
            class="form-control"
            placeholder="lần cấp thứ"
          />
        </div>
        <div class="col-md-3">
          <label>Ngày cấp lần đầu</label>
          <input
            v-model="form.ngay_cap_lan_dau_giay_chung_nhan_dau_tu"
            type="date"
            class="form-control"
          />
        </div>
      </div>
    </div>
    <div class="row ma-2">
      <div class="col-md-12">
        <label>Ghi chú</label>
        <input
          v-model="form.ghi_chu"
          type="text"
          class="form-control"
          placeholder="Nhập ghi chú"
        />
      </div>
    </div>
    <hr />
    <div
      style="display: flex; justify-content: space-between"
      class="px-4 pb-4"
    >
      <button class="btn bg-red btn-flat" @click="goBack">
        <i class="fa fa-close"></i> Hủy
      </button>

      <button type="submit" class="btn bg-olive btn-flat" @click="addChuDauTu">
        <i class="fa fa-plus"></i> Thêm mới
      </button>
    </div>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import { ValidationProvider } from "vee-validate";
import * as env from "../env.js";
import MessageService from "../services/MessageService";

export default {
  props: ["tinhs", "quans", "coquans", "noicaps"],
  components: {
    Multiselect,
    ValidationProvider
  },
  data: () => ({
    tinhThanhSelect: null,
    quanHuyenSelect: {},
    quanHuyenOption: [],
    noicapSelect: null,
    toChucs: [],
    coquanSelect: null,
    form: {
      ten: null,
      tinh_thanh_id: null,
      quan_huyen_id: null,
      email: null,
      nguoi_dai_dien: null,
      so_dien_thoai: null,
      fax: null,
      xa_phuong: null,
      dia_chi: null,
      email: null,
      ma_so_qlctnh: null,
      so_giay_chung_nhan_dang_ky_kinh_doanh: null,
      co_quan_cap_giay_chung_nhan_dang_ky_kinh_doanh: null,
      ngay_cap_giay_chung_nhan_dang_ky_kinh_doanh: null,
      lan_cap_giay_chung_nhan_dang_ky_kinh_doanh: null,
      ngay_cap_lan_dau_giay_chung_nhan_dang_ky_kinh_doanh: null,
      so_giay_chung_nhan_dau_tu: null,
      noi_cap_giay_chung_nhan_dau_tu: null,
      ngay_cap_giay_chung_nhan_dau_tu: null,
      lan_cap_giay_chung_nhan_dau_tu: null,
      ngay_cap_lan_dau_giay_chung_nhan_dau_tu: null,
      ghi_chu: null,
    }
  }),
  watch: {
    tinhThanhSelect: function(val) {
      this.quanHuyenOption = [];
      this.quanHuyenSelect = null;
      if (val) {
        this.quanHuyenOption = this.quans.filter(
          el => el.tinh_thanh_id == val.id
        );
      }
    },
    quanHuyenSelect: function(val) {
      if (val) {
        this.tinhThanhSelect = this.tinhs.find(
          el => el.id == val.tinh_thanh_id
        );
      }
    }
  },
  created() {
    this.quanHuyenOption = this.quans;
  },
  methods: {
    goBack() {
      window.location.href = "/chudautu";
    },
    addChuDauTu() {
      this.$validator.validateAll().then(validate => {
        this.form.tinh_thanh_id = this.tinhThanhSelect
          ? this.tinhThanhSelect.id
          : null;
        this.form.quan_huyen_id = this.quanHuyenSelect
          ? this.quanHuyenSelect.id
          : null;
        this.form.co_quan_cap_giay_chung_nhan_dang_ky_kinh_doanh = this.coquanSelect
          ? this.coquanSelect.ten
          : null;
        this.form.noi_cap_giay_chung_nhan_dau_tu = this.noicapSelect
          ? this.noicapSelect.ten
          : null;
        if (validate) {
          axios
            .post(env.endpointhttp + "chudautu", this.form)
            .then(response => {
              MessageService.showSuccessMessage("Thêm mới thành công");
              this.goBack();
            });
        }
      });
    }
  }
};
</script>

<style>
input {
  background-color: white !important;
}
</style>
