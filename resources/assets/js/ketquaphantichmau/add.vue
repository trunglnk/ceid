<template>
  <div class="row">
    <!-- Thông tin chung -->
    <div class="row ma-2">
      <div class="col-md-6">
        <label>Địa điểm lấy mẫu (*)</label>
        <input
          v-model="form.dia_diem"
          type="text"
          class="form-control"
          placeholder="Nhập địa điểm lấy mẫu"
          v-validate="'required'"
          name="dia_diem"
        />
        <span v-show="missing.includes('dia_diem')" style="color: red">
          Địa điểm không được bỏ trống
        </span>
      </div>

      <div class="col-md-6">
        <label>Vị trí lấy mẫu (*)</label>
        <input
          v-model="form.vi_tri"
          type="text"
          class="form-control"
          placeholder="Vị trí lấy mẫu cụ thể"
        />
        <span v-show="missing.includes('vi_tri')" style="color: red">
          Vị trí không được bỏ trống
        </span>
      </div>
    </div>

    <!-- Loại mẫu và phân loại môi trường -->
    <div class="row ma-2 mt-4">
      <div class="col-md-6">
        <label>Loại mẫu (*)</label>
        <input
          v-model="form.loai_mau"
          type="text"
          class="form-control"
          placeholder="VD: Nước biển ven bờ, ..."
        />
        <span v-show="missing.includes('loai_mau')" style="color: red">
          Loại mẫu không được bỏ trống
        </span>
      </div>

      <div class="col-md-6">
        <label>Phân loại môi trường</label>
        <multiselect
          v-model="loaiMoiTruongSelect"
          label="label"
          track-by="value"
          placeholder="Chọn loại môi trường"
          selectLabel="Nhấn Enter để chọn"
          deselectLabel="Nhấn Enter để bỏ chọn"
          :options="loaiMoiTruongOptions"
          :multiple="false"
          :searchable="true"
        />
      </div>
    </div>

    <div class="row ma-2 mt-4">
      <div class="col-md-3">
        <label>Kinh độ (*)</label>
        <input
          v-model="form.kinh_do"
          type="number"
          step="0.000001"
          class="form-control"
          placeholder="VD: 108.9409"
        />
        <span v-show="missing.includes('kinh_do')" style="color: red">
          Kinh độ không được bỏ trống
        </span>
      </div>
      <div class="col-md-3">
        <label>Vĩ độ (*)</label>
        <input
          v-model="form.vi_do"
          type="number"
          step="0.000001"
          class="form-control"
          placeholder="VD: 11.3235"
        />
        <span v-show="missing.includes('vi_do')" style="color: red">
          Vĩ độ không được bỏ trống
        </span>
      </div>

      <div class="col-md-3">
        <label>Thông số (*)</label>
        <input
          v-model="form.thong_so"
          type="text"
          class="form-control"
          placeholder="VD: pH, TDS, NH4+, NO3-..."
        />
        <span v-show="missing.includes('thong_so')" style="color: red">
          Thông số không được bỏ trống
        </span>
      </div>
      <div class="col-md-3">
        <label>Đơn vị tính</label>
        <input
          v-model="form.don_vi_tinh"
          type="text"
          class="form-control"
          placeholder="VD: mg/L"
        />
      </div>
    </div>

    <!-- Thông số phân tích -->
    <div class="row ma-2 mt-4">
      <div class="col-md-3">
        <label>Phương pháp phân tích (*)</label>
        <input
          v-model="form.phuong_phap_phan_tich"
          type="text"
          class="form-control"
          placeholder="VD: TCVN 6492:2011"
        />
        <span v-show="missing.includes('phuong_phap_phan_tich')" style="color: red">
          Phương pháp không được bỏ trống
        </span>
      </div>
      <div class="col-md-3">
        <label>Kết quả (*)</label>
        <input
          v-model="form.ket_qua"
          type="text"
          class="form-control"
          placeholder="VD: 6.77"
        />
        <span v-show="missing.includes('ket_qua')" style="color: red">
          Kết quả không được bỏ trống
        </span>
      </div>

      <div class="col-md-3">
        <label>Giá trị giới hạn (*)</label>
        <input
          v-model="form.gia_tri_gioi_han"
          type="text"
          class="form-control"
          placeholder="VD: 5.5–8.5"
        />
        <span v-show="missing.includes('gia_tri_gioi_han')" style="color: red">
          Giá trị không được bỏ trống
        </span>
      </div>
      <div class="col-md-3">
        <label>Số lần vượt</label>
        <input
          v-model="form.so_lan_vuot"
          type="number"
          min="0"
          class="form-control"
          placeholder="VD: 0"
        />
      </div>
    </div>

    <!-- Giá trị giới hạn & số lần vượt -->
    <div class="row ma-2 mt-4">
      <div class="col-md-6">
        <label>File PDF kết quả</label>
        <input
          ref="fileInput"
          type="file"
          class="form-control"
          accept=".pdf"
          @change="handleFileUpload"
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

      <button type="submit" class="btn bg-olive btn-flat" @click="addKetQua">
        <i class="fa fa-plus"></i> Thêm mới
      </button>
    </div>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import * as env from "../env.js";
import MessageService from "../services/MessageService";

export default {
  components: { Multiselect },
  data() {
    return {
      loaiMoiTruongSelect: null,
      form: {
        dia_diem: null,
        vi_tri: null,
        loai_mau: null,
        kinh_do: null,
        vi_do: null,
        thong_so: null,
        don_vi_tinh: null,
        phuong_phap_phan_tich: null,
        ket_qua: null,
        gia_tri_gioi_han: null,
        so_lan_vuot: null,
        loai_moi_truong: null,
        tep_pdf: null,
      },
      loaiMoiTruongOptions: [
        { label: "Môi trường nước thải", value: "nuoc_thai" },
        { label: "Môi trường nước mặt", value: "nuoc_mat" },
        { label: "Môi trường khí thải", value: "khi_thai" },
        { label: "Môi trường nước ven biển", value: "nuoc_ven_bien" },
        { label: "Môi trường bùn thải", value: "bun_thai" },
        {
          label: "Môi trường không khí xung quanh",
          value: "khong_khi_xung_quanh",
        },
      ],
      missing: []
    };
  },
  methods: {
    handleFileUpload(event) {
      this.form.tep_pdf = event.target.files[0];
    },
    goBack() {
      window.location.href = "/ketquaphantichmau";
    },
    async addKetQua() {
      // gán value thực từ select
      this.form.loai_moi_truong = this.loaiMoiTruongSelect
        ? this.loaiMoiTruongSelect.value
        : null;

      // kiểm tra required trước khi gửi
      const requiredFields = [
        "dia_diem",
        "vi_tri",
        "loai_mau",
        "kinh_do",
        "vi_do",
        "thong_so",
        "phuong_phap_phan_tich",
        "ket_qua",
        "gia_tri_gioi_han",
        "loai_moi_truong",
      ];

      this.missing = requiredFields.filter(
        (f) => !this.form[f] || this.form[f].toString().trim() === ""
      );

      if (this.missing.length > 0) {
        MessageService.showErrorMessage(
          "Vui lòng nhập đầy đủ các trường bắt buộc!"
        );
        return;
      }

      // nếu hợp lệ, tiếp tục gửi form
      const formData = new FormData();
      Object.keys(this.form).forEach((key) => {
        if (this.form[key] !== null) formData.append(key, this.form[key]);
      });

      try {
        await axios.post(env.endpointhttp + "ketquaphantichmau", formData, {
          headers: { "Content-Type": "multipart/form-data" },
        });
        MessageService.showSuccessMessage("Thêm mới kết quả thành công");
        setTimeout(() => {
          this.goBack();
        }, 1000);
      } catch (error) {
        MessageService.showErrorMessage("Lỗi khi thêm dữ liệu");
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
