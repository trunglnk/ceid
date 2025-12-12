<template>
  <div class="row block-multiple-rows">
    <div class="col-lg-12" style="margin-top: 5px">
      <p class="text-muted text-primary">- Nhập thông tin phiếu thử nghiệm</p>
      <hr style="margin-top: 7px; margin-bottom: 7px" />
    </div>

    <div class="col col-lg-12 col-md-12">
      <div class="form-group">
        Địa điểm lấy mẫu
        <input
          type="text"
          class="form-control"
          v-model="form_chat_thai.phieu_thu_nghiem.dia_diem_lay_mau"
          placeholder="Địa điểm lấy mẫu"
        />
      </div>
    </div>

    <div class="col col-lg-12 col-md-12">
      <div class="form-group">
        Địa chỉ
        <input
          type="text"
          class="form-control"
          v-model="form_chat_thai.phieu_thu_nghiem.dia_chi"
          placeholder="Địa chỉ"
        />
      </div>
    </div>

    <div class="col col-lg-12 col-md-12">
      File thử nghiệm
      <singleFileInput
        :value="form_chat_thai.phieu_thu_nghiem.attachment"
        @file-uploaded="handleKhiThaiFileUpdate"
        @file-deleted="handleKhiThaiFileUpdate(null)"
      />
      <!-- <input type="file" class="form-control" /> -->
    </div>
  </div>
</template>

<script>
import datePicker from "vue-bootstrap-datetimepicker";
import singleFileInput from "../ketquathanhtra/singleFileInput.vue";

export default {
  components: { datePicker, singleFileInput },
  props: {
    form_chat_thai: {
      type: Object,
      required: true,
    },
    optionsDate2: {
      type: Object,
      required: true,
    },
  },
  mounted() {
    console.log("data: ", this.form_chat_thai);
  },
  methods: {
    handleKhiThaiFileUpdate(file) {
      this.$set(this.form_chat_thai.phieu_thu_nghiem, "attachment", file);
    },
  },
};
</script>

<style scoped>
/* Bạn có thể thêm các styles tùy ý ở đây */
</style>
