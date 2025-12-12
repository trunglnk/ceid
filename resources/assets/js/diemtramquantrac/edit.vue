<template>
  <div class="row">
    <!-- Thông tin cơ bản -->
    <div class="row ma-2">
      <div class="col-md-4">
        <label>Mã định danh (*)</label>
        <input
          v-model="form.ma_dinh_danh"
          type="text"
          class="form-control"
          placeholder="Nhập mã định danh"
        />
      </div>
      <div class="col-md-8">
        <label>Tên điểm trạm quan trắc (*)</label>
        <input
          v-model="form.ten_goi"
          type="text"
          class="form-control"
          placeholder="Nhập tên điểm trạm"
        />
      </div>
    </div>

    <!-- Địa chỉ -->
    <div class="row ma-2 mt-3">
      <div class="col-md-6">
        <label>Địa chỉ chi tiết</label>
        <input
          v-model="form.dia_chi_chi_tiet"
          type="text"
          class="form-control"
          placeholder="Địa chỉ chi tiết"
        />
      </div>
      <div class="col-md-3">
        <label>Vĩ độ</label>
        <input
          v-model="form.vi_do"
          type="number"
          step="0.000001"
          class="form-control"
          placeholder="16.4664"
        />
      </div>
      <div class="col-md-3">
        <label>Kinh độ</label>
        <input
          v-model="form.kinh_do"
          type="number"
          step="0.000001"
          class="form-control"
          placeholder="107.5832"
        />
      </div>
    </div>

    <div class="row ma-2 mt-3">
      <div class="col-md-4">
        <label>Tỉnh/Thành phố</label>
        <multiselect
          v-model="tinhThanhSelect"
          label="ten"
          track-by="ma"
          placeholder="Chọn tỉnh/thành"
          :options="tinhs"
          @input="onTinhChange"  
        />
      </div>
      <div class="col-md-4">
        <label>Quận/Huyện</label>
        <multiselect
          v-model="quanHuyenSelect"
          label="ten"
          track-by="ma"
          placeholder="Chọn quận/huyện"
          :options="quanHuyenOption"
          @input="onQuanHuyenChange"  
        />
      </div>
      <div class="col-md-4">
        <label>Xã/Phường</label>
        <multiselect
          v-model="phuongXaSelect"
          label="ten"
          track-by="ma"
          placeholder="Chọn xã/phường"
          :options="xasOption"
        />
      </div>
    </div>

    <!-- Phân loại -->
    <div class="row ma-2 mt-3">
      <div class="col-md-4">
        <label>Kênh/Sông</label>
        <input
          v-model="form.kenh_song_ten"
          type="text"
          class="form-control"
          placeholder="Tên kênh/sông"
        />
      </div>
      <div class="col-md-4">
        <label>Lưu vực sông</label>
        <input
          v-model="form.luu_vuc_song_ten"
          type="text"
          class="form-control"
          placeholder="Tên lưu vực"
        />
      </div>
      <div class="col-md-4">
        <label>Loại điểm quan trắc</label>
        <input
          v-model="form.loai_diem_qtmt_ten"
          type="text"
          class="form-control"
          placeholder="VD: Điểm định kỳ"
        />
      </div>
    </div>

    <!-- Trạng thái -->
    <div class="row ma-2 mt-3">
      <div class="col-md-6">
        <label>Trạng thái</label>
        <input
          v-model="form.trang_thai_ten"
          type="text"
          class="form-control"
          placeholder="Chính thức / Nháp"
        />
      </div>
    </div>

    <!-- Phạm vi -->
    <div style="border: 1px solid #d7dbdd" class="ma-4 pb-3">
      <div
        class="pl-4 pt-2"
        style="color: #2471a3; font-size: 16px; font-weight: bold"
      >
        Thông số môi trường
      </div>
      <div
        class="row ma-1 mt-2"
        v-for="(pv, index) in form.pham_vi"
        :key="index"
      >
        <div class="col-md-2">
          <label>Mã thông số</label>
          <input
            v-model="pv.thong_so_ma"
            type="text"
            class="form-control"
            placeholder="Mã thông số"
          />
        </div>
        <div class="col-md-3">
          <label>Tên thông số</label>
          <input
            v-model="pv.thong_so_ten"
            type="text"
            class="form-control"
            placeholder="Tên thông số"
          />
        </div>
        <div class="col-md-3">
          <label>Mã quy chuẩn</label>
          <input
            v-model="pv.quy_chuan_ma"
            type="text"
            class="form-control"
            placeholder="Mã quy chuẩn"
          />
        </div>
        <div class="col-md-3">
          <label>Tên quy chuẩn</label>
          <input
            v-model="pv.quy_chuan_ten"
            type="text"
            class="form-control"
            placeholder="Tên quy chuẩn"
          />
        </div>
        <div class="col-md-1 d-flex align-items-center ">
          <button class="btn btn-light btn-sm mt-5" @click="removeThongSo(index)">
            <i class="fa fa-trash-o btn"></i>
          </button>
        </div>
      </div>
      <div class="px-3 mt-2 mx-3">
        <button class="btn bg-olive btn-flat mt-5" @click="addThongSo" type="button">
          <i class="fa fa-plus"></i> Thêm thông số
        </button>
      </div>
    </div>

    <!-- Action -->
    <hr />
    <div
      style="display: flex; justify-content: space-between"
      class="px-4 pb-4"
    >
      <button class="btn bg-red btn-flat" @click="goBack">
        <i class="fa fa-close"></i> Hủy
      </button>
      <button
        type="submit"
        class="btn bg-olive btn-flat"
        @click="updateDiemTram"
      >
        <i class="fa fa-check"></i> Cập nhật
      </button>
    </div>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import * as env from "../env.js";
import MessageService from "../services/MessageService";

export default {
  props: ["tinhs", "quans", "chitiet", "xas"],
  components: { Multiselect },
  data() {
    return {
      tinhThanhSelect: null,
      quanHuyenSelect: null,
      phuongXaSelect: null,
      quanHuyenOption: [],
      xasOption: [],
      form: {
        ma_dinh_danh: null,
        ten_goi: null,
        dia_chi_chi_tiet: null,
        phuong_xa_ten: null,
        phuong_xa_ma: null,
        quan_huyen_ma: null,
        quan_huyen_ten: null,
        tinh_thanh_ma: null,
        tinh_thanh_ten: null,
        kinh_do: null,
        vi_do: null,
        kenh_song_ten: null,
        luu_vuc_song_ten: null,
        loai_diem_qtmt_ten: null,
        trang_thai_ten: "Chính thức",
        pham_vi: [],
      },
    };
  },
  watch: {
    tinhThanhSelect(val) {
      // Khi chọn tỉnh, lọc danh sách quận huyện
      this.quanHuyenOption = this.quans.filter(
        (el) => el.tinh_thanh_id == (val ? val.id : null)
      );

      if (val) {
        this.form.tinh_thanh_ma = String(val.ma); // Gán đúng giá trị `ma` cho tỉnh
        this.form.tinh_thanh_ten = val.ten;
      }
    },
    quanHuyenSelect(val) {
      // Khi chọn quận huyện, lọc danh sách xã/phường
      this.xasOption = this.xas.filter(
        (el) => el.quan_huyen_id == (val ? val.id : null)
      );
      
      if (val) {
        this.form.quan_huyen_ma = String(val.ma);
        this.form.quan_huyen_ten = val.ten;
      }
    },
    phuongXaSelect(val) {
    if (val) {
      this.form.phuong_xa_ma = String(val.ma)
      this.form.phuong_xa_ten = val.ten
    }
  }
  },
  created() {
    // Set Tỉnh
    if (this.chitiet.tinh_thanh_ten) {
      this.tinhThanhSelect = this.tinhs.find(
        (el) => el.ten == this.chitiet.tinh_thanh_ten
      );
    }

    // Set Quận/Huyện
    if (this.chitiet.quan_huyen_ten) {
      this.quanHuyenSelect = this.quans.find(
        (el) => el.ten == this.chitiet.quan_huyen_ten
      );
    }

    // Set Xã/Phường
    if (this.chitiet.phuong_xa_ten) {
      this.phuongXaSelect = this.xas.find(
        (el) => el.ten == this.chitiet.phuong_xa_ten
      );
    }

    // Fill form
    for (const key in this.form) {
      if (this.chitiet[key] !== undefined) {
        this.form[key] = this.chitiet[key];
      }
    }
    this.form.pham_vi = this.chitiet.pham_vi || [];
  },
  methods: {
    goBack() {
      window.location.href = "/diemtramquantrac";
    },
    addThongSo() {
      this.form.pham_vi.push({
        thong_so_ma: null,
        thong_so_ten: null,
        quy_chuan_ma: null,
        quy_chuan_ten: null,
      });
    },
    updateDiemTram() {
      axios
        .put(
          env.endpointhttp + "diemtramquantrac/" + this.chitiet.id,
          this.form
        )
        .then(() => {
          MessageService.showSuccessMessage("Cập nhật thành công");
          this.goBack();
        });
    },
    removeThongSo(index) {
      this.form.pham_vi.splice(index, 1);
    },
    // Hàm xử lý khi thay đổi tỉnh
    onTinhChange(val) {
      if (val) {
        this.quanHuyenOption = this.quans.filter(
          (el) => el.tinh_thanh_id == val.id
        );
        this.form.quan_huyen_ma = null;  // Reset quận huyện khi tỉnh thay đổi
        this.form.phuong_xa_ten = null;  // Reset xã phường khi quận huyện thay đổi
        this.quanHuyenSelect = null;  // Reset quận huyện
        this.phuongXaSelect = null;  // Reset xã phường
      }
    },
    // Hàm xử lý khi thay đổi quận huyện
    onQuanHuyenChange(val) {
      if (val) {
        this.xasOption = this.xas.filter(
          (el) => el.quan_huyen_id == val.id
        );
        this.form.phuong_xa_ten = null;  // Reset xã phường khi quận huyện thay đổi
        this.phuongXaSelect = null;  // Reset xã phường
      }
    },
  },
};
</script>

<style>
input {
  background-color: white !important;
}
</style>