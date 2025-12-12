<template>
  <div class="row block-multiple-rows">
    <div class="col-lg-12" style="margin-top: 5px">
      <label>KẾT QUẢ QUAN TRẮC {{ labelTheoLoai }}</label>
      <hr style="margin-top: 7px; margin-bottom: 7px" />
    </div>

    <div class="col-md-12">
      <div class="nav-tabs-custom margin-top">
        <div style="padding: 15px">
          <!-- Tabs cho từng mẫu -->
          <ul
            class="nav nav-tabs"
            v-if="
              currentFormData.mau_moi_truong &&
              currentFormData.mau_moi_truong.length
            "
          >
            <li
              v-for="(mau, idx) in currentFormData.mau_moi_truong"
              :key="idx"
              :class="{ active: activeMauIndex === idx }"
            >
              <a href="#" @click.prevent="activeMauIndex = idx">
                {{ `Kết quả ${idx + 1}` }}
              </a>
            </li>
          </ul>

          <!-- Form chi tiết cho mẫu đang chọn -->
          <div
            v-if="activeMau"
            class="border p-3 mb-4"
            style="border-radius: 8px; border: 1px solid #ddd; margin-top: 15px"
          >
            <div
              class="d-flex justify-content-between align-items-center mb-3 sample-header"
            >
              <span>{{ `Mẫu ${activeMauIndex + 1}` }}</span>

              <button
                type="button"
                class="btn btn-link text-danger p-0 delete-btn"
                @click="deleteMauMoiTruong(activeMauIndex)"
                title="Xóa mẫu này"
              >
                <i class="fa fa-trash"></i>
              </button>
            </div>

            <div class="row ma-2">
              <div class="col-md-6">
                <label>Địa điểm lấy mẫu (*)</label>
                <input
                  v-model="activeMau.dia_diem"
                  type="text"
                  class="form-control"
                  placeholder="Nhập địa điểm lấy mẫu"
                />
              </div>
              <div class="col-md-6">
                <label>Vị trí lấy mẫu (*)</label>
                <input
                  v-model="activeMau.vi_tri"
                  type="text"
                  class="form-control"
                  placeholder="Vị trí lấy mẫu cụ thể"
                />
              </div>
            </div>

            <div class="row ma-2 mt-4">
              <div class="col-md-6">
                <label>Loại mẫu (*)</label>
                <input
                  v-model="activeMau.loai_mau"
                  type="text"
                  class="form-control"
                  placeholder="VD: Nước biển ven bờ, ..."
                />
              </div>
              <div class="col-md-6">
                <label>Phân loại môi trường</label>
                <multiselect
                  :value="currentLoaiMoiTruong"
                  label="label"
                  track-by="value"
                  :options="loaiMoiTruongOptions"
                  :disabled="true"
                />
              </div>
            </div>

            <div class="row ma-2 mt-4">
              <div class="col-md-3">
                <label>Kinh độ (*)</label>
                <input
                  v-model="activeMau.kinh_do"
                  type="number"
                  step="0.000001"
                  class="form-control"
                  placeholder="VD: 108.9409"
                />
              </div>
              <div class="col-md-3">
                <label>Vĩ độ (*)</label>
                <input
                  v-model="activeMau.vi_do"
                  type="number"
                  step="0.000001"
                  class="form-control"
                  placeholder="VD: 11.3235"
                />
              </div>
              <div class="col-md-6">
                <label>File PDF kết quả</label>
                <singleFileInput
                  :value="activeMau.attachment"
                  @file-uploaded="(file) => handleFileUpdate(activeMau, file)"
                  @file-deleted="() => handleFileUpdate(activeMau, null)"
                />
              </div>
            </div>

            <div class="row ma-2 mt-4">
              <div class="col-md-3">
                <label>Thông số (*)</label>
                <input
                  v-model="activeMau.thong_so"
                  type="text"
                  class="form-control"
                  placeholder="VD: pH, TDS, NH4+, NO3-..."
                />
              </div>
              <div class="col-md-3">
                <label>Đơn vị tính</label>
                <input
                  v-model="activeMau.don_vi_tinh"
                  type="text"
                  class="form-control"
                  placeholder="VD: mg/L"
                />
              </div>
              <div class="col-md-3">
                <label>Phương pháp phân tích (*)</label>
                <input
                  v-model="activeMau.phuong_phap_phan_tich"
                  type="text"
                  class="form-control"
                  placeholder="VD: TCVN 6492:2011"
                />
              </div>
              <div class="col-md-3">
                <label>Kết quả (*)</label>
                <input
                  v-model="activeMau.ket_qua"
                  type="text"
                  class="form-control"
                  placeholder="VD: 6.77"
                />
              </div>
            </div>

            <div class="row ma-2 mt-4">
              <div class="col-md-3">
                <label>Giá trị giới hạn (*)</label>
                <input
                  v-model="activeMau.gia_tri_gioi_han"
                  type="text"
                  class="form-control"
                  placeholder="VD: 5.5–8.5"
                />
              </div>
              <div class="col-md-3">
                <label>Số lần vượt</label>
                <input
                  v-model="activeMau.so_lan_vuot"
                  type="number"
                  min="0"
                  class="form-control"
                  placeholder="VD: 0"
                />
              </div>
              <div class="col-md-2">
                <label>&nbsp;</label>
                <button
                  type="button"
                  class="btn btn-flat pull-right btn-block"
                  style="height: 40px"
                  @click="addThongSo(activeMau)"
                >
                  <i class="fa fa-plus"></i> Thêm thông số
                </button>
              </div>
            </div>

            <div class="row ma-2 mt-4">
              <div class="col-sm-12">
                <table class="table table-hover table-responsive" role="grid">
                  <thead>
                    <tr class="row-table-header">
                      <th>Thông số</th>
                      <th>Đơn vị tính</th>
                      <th>Phương pháp phân tích</th>
                      <th>Kết quả</th>
                      <th>Giá trị giới hạn</th>
                      <th>Số lần vượt</th>
                      <th style="width: 5%; text-align: center">Xóa</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(ts, index) in activeMau.thong_sos" :key="index">
                      <td>{{ ts.thong_so }}</td>
                      <td>{{ ts.don_vi_tinh }}</td>
                      <td>{{ ts.phuong_phap_phan_tich }}</td>
                      <td>{{ ts.ket_qua }}</td>
                      <td>{{ ts.gia_tri_gioi_han }}</td>
                      <td>{{ ts.so_lan_vuot }}</td>
                      <td align="center">
                        <a @click="deleteThongSo(activeMau, index)">
                          <i class="fa fa-trash-o"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Nút thêm mẫu -->
          <div class="row">
            <div class="col-md-12 text-right mb-2">
              <button
                type="button"
                class="btn btn-flat btn-success"
                @click="addMauMoiTruong"
              >
                <i class="fa fa-plus"></i> Thêm kết quả quan trắc
              </button>
            </div>
          </div>
          <!-- end -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import singleFileInput from "./singleFileInput.vue";

export default {
  name: "KetQuaQuanTrac",
  components: { Multiselect, singleFileInput },
  props: {
    inputData: { type: Array, default: () => [] },
    loai: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      loaiMoiTruongOptions: [
        { label: "Nước thải", value: "chat_thai_nuoc_thai" },
        { label: "Khí thải, bụi", value: "chat_thai_khi_thai" },
        { label: "Chất thải rắn sinh hoạt", value: "chat_thai_ran_sinh_hoat" },
        {
          label: "Chất thải rắn công nghiệp thông thường",
          value: "chat_thai_ran_cntt",
        },
        { label: "Chất thải nguy hại", value: "chat_thai_nguy_hai" },
      ],
      activeMauIndex: 0,
    };
  },
  computed: {
    labelTheoLoai() {
      switch (this.loai) {
        case "chat_thai_nuoc_thai":
          return "NƯỚC THẢI";
        case "chat_thai_khi_thai":
          return "KHÍ THẢI, BỤI";
        case "chat_thai_ran_sinh_hoat":
          return "CHẤT THẢI RẮN SINH HOẠT";
        case "chat_thai_ran_cntt":
          return "CHẤT THẢI RẮN CNTT";
        case "chat_thai_nguy_hai":
          return "CHẤT THẢI NGUY HẠI";
        default:
          return "";
      }
    },
    currentLoaiMoiTruong() {
      return this.loaiMoiTruongOptions.find(
        (opt) => opt.value === this.loai
      );
    },
    activeMau() {
      const form = this.currentFormData;
      if (!form || !form.mau_moi_truong || !form.mau_moi_truong.length) {
        return null;
      }
      return form.mau_moi_truong[this.activeMauIndex] || null;
    },

    currentFormData: {
      get() {
        if (Array.isArray(this.inputData) && this.inputData.length) {
          return this.inputData[0];
        }

        // form mặc định nếu chưa có dữ liệu
        return {
          loai_moi_truong: this.loai,
          mau_moi_truong: [],
        };
      },
      set(newValue) {
        const updated = [...this.inputData];
        if (!updated.length) updated.push(newValue);
        else updated[0] = newValue;
        this.$emit("update:inputData", updated);
      },
    },
  },

  methods: {
    addMauMoiTruong() {
      const form = { ...this.currentFormData };
      if (!form.mau_moi_truong) form.mau_moi_truong = [];
      form.mau_moi_truong.push({
        dia_diem: "",
        vi_tri: "",
        loai_mau: "",
        kinh_do: null,
        vi_do: null,
        attachment: null,
        thong_sos: [],
      });
      this.currentFormData = form;
      this.activeMauIndex = form.mau_moi_truong.length - 1; // chọn tab mới thêm
    },

    deleteMauMoiTruong(index) {
      const form = { ...this.currentFormData };
      form.mau_moi_truong.splice(index, 1);

      if (this.activeMauIndex >= form.mau_moi_truong.length) {
        this.activeMauIndex = form.mau_moi_truong.length - 1;
        if (this.activeMauIndex < 0) this.activeMauIndex = 0;
      }

      this.currentFormData = form;
    },

    handleFileUpdate(mau, file) {
      mau.attachment = file;
      this.currentFormData = { ...this.currentFormData };
    },
    addThongSo(mau) {
      if (!mau.thong_sos) mau.thong_sos = [];
      if (!mau.thong_so || !mau.ket_qua) {
        alert("Vui lòng nhập đầy đủ Thông số và Kết quả trước khi thêm.");
        return;
      }
      mau.thong_sos.push({
        thong_so: mau.thong_so,
        don_vi_tinh: mau.don_vi_tinh,
        phuong_phap_phan_tich: mau.phuong_phap_phan_tich,
        ket_qua: mau.ket_qua,
        gia_tri_gioi_han: mau.gia_tri_gioi_han,
        so_lan_vuot: mau.so_lan_vuot || 0,
      });
      mau.thong_so =
        mau.don_vi_tinh =
        mau.phuong_phap_phan_tich =
        mau.ket_qua =
        mau.gia_tri_gioi_han =
          "";
      mau.so_lan_vuot = 0;
      this.currentFormData = { ...this.currentFormData };
    },
    deleteThongSo(mau, index) {
      mau.thong_sos.splice(index, 1);
      this.currentFormData = { ...this.currentFormData };
    },
  },
};
</script>

<style scoped>
.ma-2 {
  margin-left: -10px;
  margin-right: -10px;
}
.mt-4 {
  margin-top: 1.5rem;
}
input {
  background-color: white !important;
}

.sample-header {
  font-weight: bold;
  font-size: 16px;
  color: #2c3e50;
  border-bottom: 1px solid #ddd;
  padding: 10px;
  transition: all 0.2s ease;
}

.delete-btn i {
  font-size: 18px;
  color: #c0392b;
  transition: transform 0.2s ease, color 0.2s ease;
}

.delete-btn:hover i {
  color: #e74c3c;
  transform: scale(1.2);
  cursor: pointer;
}
</style>
