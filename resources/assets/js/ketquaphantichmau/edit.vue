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
        />
      </div>

      <div class="col-md-6">
        <label>Vị trí lấy mẫu</label>
        <input
          v-model="form.vi_tri"
          type="text"
          class="form-control"
          placeholder="Vị trí lấy mẫu cụ thể"
        />
      </div>
    </div>

    <!-- Loại mẫu và phân loại môi trường -->
    <div class="row ma-2 mt-4">
      <div class="col-md-6">
        <label>Loại mẫu</label>
        <input
          v-model="form.loai_mau"
          type="text"
          class="form-control"
          placeholder="VD: Nước biển ven bờ, ..."
        />
      </div>

      <div class="col-md-6">
        <label>Phân loại môi trường</label>
        <multiselect
          v-model="loaiMoiTruongSelect"
          label="label"
          track-by="value"
          placeholder="Chọn loại môi trường"
          :options="loaiMoiTruongOptions"
          :multiple="false"
          :searchable="true"
        />
      </div>
    </div>

    <!-- Tọa độ + Thông số -->
    <div class="row ma-2 mt-4">
      <div class="col-md-3">
        <label>Kinh độ</label>
        <input
          v-model="form.kinh_do"
          type="number"
          step="0.000001"
          class="form-control"
          placeholder="VD: 108.9409"
        />
      </div>
      <div class="col-md-3">
        <label>Vĩ độ</label>
        <input
          v-model="form.vi_do"
          type="number"
          step="0.000001"
          class="form-control"
          placeholder="VD: 11.3235"
        />
      </div>

      <div class="col-md-3">
        <label>Thông số</label>
        <input
          v-model="form.thong_so"
          type="text"
          class="form-control"
          placeholder="VD: pH, TDS, NH4+, NO3-..."
        />
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

    <!-- Phân tích -->
    <div class="row ma-2 mt-4">
      <div class="col-md-3">
        <label>Phương pháp phân tích</label>
        <input
          v-model="form.phuong_phap_phan_tich"
          type="text"
          class="form-control"
          placeholder="VD: TCVN 6492:2011"
        />
      </div>
      <div class="col-md-3">
        <label>Kết quả</label>
        <input
          v-model="form.ket_qua"
          type="text"
          class="form-control"
          placeholder="VD: 6.77"
        />
      </div>

      <div class="col-md-3">
        <label>Giá trị giới hạn</label>
        <input
          v-model="form.gia_tri_gioi_han"
          type="text"
          class="form-control"
          placeholder="VD: 5.5–8.5"
        />
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

    <!-- File PDF -->
    <div class="row ma-2 mt-4">
      <div class="col-md-8">
        <label>File PDF kết quả</label>
        <input
          ref="fileInput"
          type="file"
          class="form-control"
          accept=".pdf"
          @change="handleFileUpload"
        />

        <!-- Hiển thị tên file hiện tại -->
        <small v-if="form.tep_pdf" class="text-success">
          File hiện tại:
          <a
            href="#"
            class="text-primary"
            @click.prevent="downloadFile(form.tep_pdf)"
          >
            {{ getFileName(form.tep_pdf) }}
          </a>
        </small>

        <!-- Hiển thị tên file mới nếu người dùng chọn -->
        <div v-if="newFileSelected" class="mt-2 text-success">
          File mới: {{ newFileSelected.name }}
        </div>
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
      <button type="submit" class="btn bg-olive btn-flat" @click="updateKetQua">
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
  props: ["chitiet"],
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
    };
  },
  created() {
    // Fill dữ liệu từ prop chitiet
    if (this.chitiet) {
      Object.keys(this.form).forEach((key) => {
        if (this.chitiet[key] !== undefined && this.chitiet[key] !== null) {
          this.form[key] = this.chitiet[key];
        }
      });

      // Tự chọn giá trị Multiselect
      this.loaiMoiTruongSelect = this.loaiMoiTruongOptions.find(
        (opt) => opt.value === this.form.loai_moi_truong
      );
    }
  },
  methods: {
    handleFileUpload(event) {
      this.form.tep_pdf = event.target.files[0];
    },
    getFileName(path) {
      return path ? path.split("/").pop() : "";
    },
    fileUrl(path) {
      return path ? env.endpointhttp + "storage/" + path : "#";
    },
    goBack() {
      window.location.href = "/ketquaphantichmau";
    },
    async updateKetQua() {
      this.form.loai_moi_truong = this.loaiMoiTruongSelect
        ? this.loaiMoiTruongSelect.value
        : null;

      const formData = new FormData();

      Object.keys(this.form).forEach((key) => {
        // ⚡ Nếu là file thì chỉ append khi user upload mới (File object)
        if (key === "tep_pdf") {
          if (this.form.tep_pdf instanceof File) {
            formData.append("tep_pdf", this.form.tep_pdf);
          }
        } else if (this.form[key] !== null) {
          formData.append(key, this.form[key]);
        }
      });

      try {
        await axios.post(
          env.endpointhttp + "ketquaphantichmau/" + this.chitiet.id,
          formData,
          {
            headers: { "Content-Type": "multipart/form-data" },
          }
        );
        MessageService.showSuccessMessage("Cập nhật thành công");
        setTimeout(() => this.goBack(), 1500);
      } catch (error) {
        MessageService.showErrorMessage("Lỗi khi cập nhật dữ liệu");
      }
    },

    downloadFile(path) {
      const link = document.createElement("a");
      link.href = this.fileUrl(path);
      link.setAttribute("download", this.getFileName(path));
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },
  },
};
</script>

<style>
input {
  background-color: white !important;
}
</style>
