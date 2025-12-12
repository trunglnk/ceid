<template>
  <div class="row block-multiple-rows" v-show="isCollapse1Visible">
    <div class="col-lg-12" style="margin-top: 5px">
      <p class="text-muted text-primary">
        - Nhập thông tin phiếu thử nghiệm nước thải
      </p>
      <hr style="margin-top: 7px; margin-bottom: 7px" />
    </div>
    <div
      class="col col-lg-12 col-md-12"
      v-for="(field, index) in fields"
      :key="index"
    >
      <div class="form-group">
        <label>{{ field.label }}</label>
        <input
          v-if="field.type === 'text' || field.type === 'file'"
          type="text"
          class="form-control"
          v-model="field.model"
          :placeholder="field.placeholder"
        />
        <date-picker
          v-if="field.type === 'date'"
          class="form-control"
          v-model="field.model"
          :placeholder="field.placeholder"
          :config="optionsDate"
        />
      </div>
    </div>
    <div class="col col-lg-12 col-md-12 mt-3" style="text-align: end">
      <button class="btn btn-primary" @click="addTest">
        Thêm thông tin thử nghiệm
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    isCollapse1Visible: {
      type: Boolean,
      required: true,
    },
    coSoSanXuat: {
      type: Object,
      required: true,
    },
    optionsDate: {
      type: Object,
      required: true,
    },
  },
  computed: {
    fields() {
      return [
        {
          label: "Tên khách hàng",
          model:
            this.coSoSanXuat.chatthais.nuocthai.phieu_thu_nghiem.ten_khach_hang,
          type: "text",
          placeholder: "Tên khách hàng",
        },
        {
          label: "Địa điểm lấy mẫu",
          model:
            this.coSoSanXuat.chatthais.nuocthai.phieu_thu_nghiem
              .dia_diem_lay_mau,
          type: "text",
          placeholder: "Địa điểm lấy mẫu",
        },
        {
          label: "Địa chỉ",
          model: this.coSoSanXuat.chatthais.nuocthai.phieu_thu_nghiem.dia_chi,
          type: "text",
          placeholder: "Địa chỉ",
        },
        {
          label: "Vị trí quan trắc",
          model:
            this.coSoSanXuat.chatthais.nuocthai.phieu_thu_nghiem
              .vi_tri_quan_trac,
          type: "text",
          placeholder: "Vị trí quan trắc",
        },
        {
          label: "Kinh độ",
          model: this.coSoSanXuat.chatthais.nuocthai.phieu_thu_nghiem.kinh_do,
          type: "text",
          placeholder: "Kinh độ",
        },
        {
          label: "Vĩ độ",
          model: this.coSoSanXuat.chatthais.nuocthai.phieu_thu_nghiem.vi_do,
          type: "text",
          placeholder: "Vĩ độ",
        },
        {
          label: "Ngày quan trắc, lấy mẫu",
          model:
            this.coSoSanXuat.chatthais.nuocthai.phieu_thu_nghiem.ngay_quan_trac,
          type: "date",
          placeholder: "Ngày quan trắc, lấy mẫu",
        },
        {
          label: "Người quan trắc, lấy mẫu",
          model:
            this.coSoSanXuat.chatthais.nuocthai.phieu_thu_nghiem
              .nguoi_quan_trac,
          type: "text",
          placeholder: "Người quan trắc, lấy mẫu",
        },
        {
          label: "Đặc điểm thời tiết",
          model: this.coSoSanXuat.chatthais.nuocthai.phieu_thu_nghiem.thoi_tiet,
          type: "text",
          placeholder: "Đặc điểm thời tiết",
        },
        {
          label: "Ngày phân tích",
          model:
            this.coSoSanXuat.chatthais.nuocthai.phieu_thu_nghiem.ngay_phan_tich,
          type: "date",
          placeholder: "Ngày phân tích",
        },
        {
          label: "Ngày kết thúc phân tích",
          model:
            this.coSoSanXuat.chatthais.nuocthai.phieu_thu_nghiem
              .ngay_ket_thuc_phan_tich,
          type: "date",
          placeholder: "Ngày kết thúc phân tích",
        },
        {
          label: "Người phân tích",
          model:
            this.coSoSanXuat.chatthais.nuocthai.phieu_thu_nghiem
              .nguoi_phan_tich,
          type: "text",
          placeholder: "Người phân tích",
        },
        {
          label: "File thử nghiệm",
          model: null, // có thể xử lý riêng nếu cần
          type: "file",
        },
      ];
    },
  },
  methods: {
    addTest() {
      // Thực hiện thêm thông tin thử nghiệm
      console.log("Thêm thông tin thử nghiệm:", this.coSoSanXuat);
    },
  },
};
</script>

<style scoped>
.block-multiple-rows {
  margin: 10px 0;
}
</style>
