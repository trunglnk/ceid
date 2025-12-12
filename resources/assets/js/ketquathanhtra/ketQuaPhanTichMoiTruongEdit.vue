<template>
  <div class="row block-multiple-rows">
    <div class="col-lg-12" style="margin-top: 5px">
      <label>KẾT QUẢ ĐO ĐẠC VÀ PHÂN TÍCH MẪU MÔI TRƯỜNG</label>
      <p class="text-muted">
        (Click vào từng mục để nhập thông tin kết quả đo đạc và phân tích)
      </p>
      <hr style="margin-top: 7px; margin-bottom: 7px" />
    </div>

    <div class="col-md-12">
      <div class="nav-tabs-custom margin-top">
        <ul class="nav nav-tabs">
          <li
            v-for="tab in tabs"
            :key="tab.key"
            :class="{ active: currentTab === tab.key }"
            @click="currentTab = tab.key"
          >
            <a data-toggle="tab" href="#">{{ tab.label }}</a>
          </li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane active" style="padding: 15px">
            <!-- Lặp qua từng mẫu -->
            <div
              v-for="(mau, mauIndex) in currentFormData.mau_moi_truong"
              :key="mauIndex"
              class="border p-3 mb-4"
              style="border-radius: 8px; border: 1px solid #ddd"
            >
              <!-- Tiêu đề -->
              <div
                class="d-flex justify-content-between align-items-center mb-3 sample-header"
              >
                <span>{{ `Mẫu ${mauIndex + 1}` }}</span>

                <button
                  type="button"
                  class="btn btn-link text-danger p-0 delete-btn"
                  @click="deleteMauMoiTruong(mauIndex)"
                  title="Xóa mẫu này"
                >
                  <i class="fa fa-trash"></i>
                </button>
              </div>

              <div class="row ma-2">
                <div class="col-md-6">
                  <label>Địa điểm lấy mẫu (*)</label>
                  <input
                    v-model="mau.dia_diem"
                    type="text"
                    class="form-control"
                    placeholder="Nhập địa điểm lấy mẫu"
                  />
                </div>
                <div class="col-md-6">
                  <label>Vị trí lấy mẫu (*)</label>
                  <input
                    v-model="mau.vi_tri"
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
                    v-model="mau.loai_mau"
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
                    v-model="mau.kinh_do"
                    type="number"
                    step="0.000001"
                    class="form-control"
                    placeholder="VD: 108.9409"
                  />
                </div>
                <div class="col-md-3">
                  <label>Vĩ độ (*)</label>
                  <input
                    v-model="mau.vi_do"
                    type="number"
                    step="0.000001"
                    class="form-control"
                    placeholder="VD: 11.3235"
                  />
                </div>
                <div class="col-md-6">
                  <label>File PDF kết quả</label>
                  <singleFileInput
                    :value="mau.attachment"
                    @file-uploaded="(file) => handleFileUpdate(mau, file)"
                    @file-deleted="() => handleFileUpdate(mau, null)"
                  />
                </div>
              </div>

              <div class="row ma-2 mt-4">
                <div class="col-md-3">
                  <label>Thông số (*)</label>
                  <input
                    v-model="mau.thong_so"
                    type="text"
                    class="form-control"
                    placeholder="VD: pH, TDS, NH4+, NO3-..."
                  />
                </div>
                <div class="col-md-3">
                  <label>Đơn vị tính</label>
                  <input
                    v-model="mau.don_vi_tinh"
                    type="text"
                    class="form-control"
                    placeholder="VD: mg/L"
                  />
                </div>
                <div class="col-md-3">
                  <label>Phương pháp phân tích (*)</label>
                  <input
                    v-model="mau.phuong_phap_phan_tich"
                    type="text"
                    class="form-control"
                    placeholder="VD: TCVN 6492:2011"
                  />
                </div>
                <div class="col-md-3">
                  <label>Kết quả (*)</label>
                  <input
                    v-model="mau.ket_qua"
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
                    v-model="mau.gia_tri_gioi_han"
                    type="text"
                    class="form-control"
                    placeholder="VD: 5.5–8.5"
                  />
                </div>
                <div class="col-md-3">
                  <label>Số lần vượt</label>
                  <input
                    v-model="mau.so_lan_vuot"
                    type="number"
                    min="0"
                    class="form-control"
                    placeholder="VD: 0"
                  />
                </div>
                <div class="col-md-2">
                  <label>&nbsp;</label>
                  <button
                    class="btn btn-flat btn-block"
                    style="height: 40px"
                    @click="addThongSo(mau)"
                  >
                    <i class="fa fa-plus"></i> Thêm thông số
                  </button>
                </div>
                <!-- <div class="col-md-2">
                  <label>&nbsp;</label>
                  <button
                    class="btn btn-danger btn-flat btn-block"
                    style="height: 40px"
                    @click="deleteMauMoiTruong(mauIndex)"
                  >
                    <i class="fa fa-trash"></i> Xóa mẫu
                  </button>
                </div> -->
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
                      <tr v-for="(ts, index) in mau.thong_sos" :key="index">
                        <td>{{ ts.thong_so }}</td>
                        <td>{{ ts.don_vi_tinh }}</td>
                        <td>{{ ts.phuong_phap_phan_tich }}</td>
                        <td>{{ ts.ket_qua }}</td>
                        <td>{{ ts.gia_tri_gioi_han }}</td>
                        <td>{{ ts.so_lan_vuot }}</td>
                        <td align="center">
                          <a @click="deleteThongSo(mau, index)">
                            <i class="fa fa-trash-o"></i>
                          </a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <!-- end v-for mẫu -->

            <div class="row mb-2">
              <div class="col-md-12 text-right">
                <button
                  type="button"
                  class="btn btn-success btn-flat"
                  @click="addMauMoiTruong"
                >
                  <i class="fa fa-plus"></i> Thêm mẫu môi trường
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import singleFileInput from "./singleFileInput.vue";

export default {
  name: "KetQuaPhanTichMoiTruongEdit",
  components: { Multiselect, singleFileInput },
  props: { inputData: { type: Array, required: true } },
  data() {
    return {
      currentTab: "nuoc_thai",
      tabs: [
        { key: "nuoc_thai", label: "Nước thải" },
        { key: "khi_thai", label: "Khí thải" },
        { key: "nuoc_mat", label: "Nước mặt" },
        { key: "nuoc_ven_bien", label: "Nước ven biển" },
        { key: "bun_thai", label: "Bùn thải" },
        { key: "khong_khi", label: "Không khí xung quanh" },
      ],
      loaiMoiTruongOptions: [
        { label: "Môi trường nước thải", value: "nuoc_thai" },
        { label: "Môi trường nước mặt", value: "nuoc_mat" },
        { label: "Môi trường khí thải", value: "khi_thai" },
        { label: "Môi trường nước ven biển", value: "nuoc_ven_bien" },
        { label: "Môi trường bùn thải", value: "bun_thai" },
        { label: "Môi trường không khí xung quanh", value: "khong_khi" },
      ],
    };
  },
  computed: {
    currentLoaiMoiTruong() {
      return this.loaiMoiTruongOptions.find(
        (opt) => opt.value === this.currentTab
      );
    },
    currentFormData: {
      get() {
        return (
          this.inputData.find(
            (item) => item.loai_moi_truong === this.currentTab
          ) || {}
        );
      },
      set(newValue) {
        const index = this.inputData.findIndex(
          (item) => item.loai_moi_truong === this.currentTab
        );
        const updated = [...this.inputData];
        if (index !== -1) updated[index] = newValue;
        else updated.push(newValue);
        this.$emit("update:inputData", updated);
      },
    },
  },
  created() {
    // 🔹 Bước 1: Gom dữ liệu thật từ DB (phẳng) thành cấu trúc 3 tầng
    const grouped = {};

    (this.inputData || []).forEach((item) => {
      const key = item.loai_moi_truong || "nuoc_thai";
      if (!grouped[key])
        grouped[key] = { loai_moi_truong: key, mau_moi_truong: [] };

      // Tìm xem mẫu có trùng địa điểm/vị trí chưa
      let existingSample = grouped[key].mau_moi_truong.find(
        (m) =>
          m.dia_diem === item.dia_diem &&
          m.vi_tri === item.vi_tri &&
          m.loai_mau === item.loai_mau
      );

      if (!existingSample) {
        existingSample = {
          dia_diem: item.dia_diem || "",
          vi_tri: item.vi_tri || "",
          loai_mau: item.loai_mau || "",
          kinh_do: item.kinh_do || null,
          vi_do: item.vi_do || null,
          tep_pdf: item.tep_pdf || null,
          attachment: item.attachment || null,
          thong_sos: [],
        };
        grouped[key].mau_moi_truong.push(existingSample);
      }

      if (item.thong_so) {
        existingSample.thong_sos.push({
          id: item.id,
          thong_so: item.thong_so,
          don_vi_tinh: item.don_vi_tinh,
          phuong_phap_phan_tich: item.phuong_phap_phan_tich,
          ket_qua: item.ket_qua,
          gia_tri_gioi_han: item.gia_tri_gioi_han,
          so_lan_vuot: item.so_lan_vuot,
        });
      }
    });

    // 🔹 Bước 2: Đảm bảo mỗi loại môi trường có ít nhất 1 mẫu trống
    this.tabs.forEach((tab) => {
      if (!grouped[tab.key]) {
        grouped[tab.key] = {
          loai_moi_truong: tab.key,
          mau_moi_truong: [
            {
              dia_diem: "",
              vi_tri: "",
              loai_mau: "",
              kinh_do: null,
              vi_do: null,
              attachment: null,
              thong_sos: [],
            },
          ],
        };
      } else if (
        !grouped[tab.key].mau_moi_truong ||
        grouped[tab.key].mau_moi_truong.length === 0
      ) {
        grouped[tab.key].mau_moi_truong = [
          {
            dia_diem: "",
            vi_tri: "",
            loai_mau: "",
            kinh_do: null,
            vi_do: null,
            attachment: null,
            thong_sos: [],
          },
        ];
      }
    });

    this.$emit("update:inputData", Object.values(grouped));
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
    },
    deleteMauMoiTruong(index) {
      const form = { ...this.currentFormData };
      form.mau_moi_truong.splice(index, 1);
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
