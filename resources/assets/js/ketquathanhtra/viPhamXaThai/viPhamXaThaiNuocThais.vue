<template>
  <div>
    <label class="control-label">Nước thải</label>
    <hr />
    <checkbox-component
      v-model="intermediate_data.nuoc_thai_vuot"
      type="checkbox"
      id="nuoc_thai_vuot"
      text="Xả vượt"
      @change="change"
      :disabled="!canEdit"
    ></checkbox-component>
    <checkbox-component
      :value="!intermediate_data.co_he_thong_thu_gom_nuoc_thai_rieng_biet"
      @input="
        intermediate_data.co_he_thong_thu_gom_nuoc_thai_rieng_biet = !$event
      "
      type="checkbox"
      id="co_he_thong_thu_gom_nuoc_thai_rieng_biet"
      text="Không có hệ thống xử lý nước thải"
      @change="change"
      :disabled="!canEdit"
    ></checkbox-component>
    <vi-pham-phu
      v-model="intermediate_data.vi_pham_xa_thai_nuoc_thais"
      :danhMucThongSoVuotChuans="danhMucThongSoVuotChuanNuocThais"
      :coSoSanXuats="coSoSanXuats"
      :optionPhatTangThems="optionPhatTangThemNuocThais"
      :khoans="nuocThaiKhoans"
      :donviThongSoVuotChuan="donviThongSoVuotChuan"
      :is_loading="is_loading"
      @change="change"
      text-luu-luong="Lưu lượng xả vượt"
      :disabled="!canEdit"
    ></vi-pham-phu>
    <hr />
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import viPhamPhu from "./viPhamPhu";
export default {
  components: { Multiselect, viPhamPhu },
  props: {
    is_loading: Boolean,
    value: {},
    canEdit: Boolean,
    nuocThaiKhoans: { type: Array, default: () => [] },
    optionPhatTangThemNuocThais: { type: Array, default: () => [] },
    danhMucThongSoVuotChuanNuocThais: { type: Array, default: () => [] },
    donviThongSoVuotChuan: { type: Array, default: () => [] },
    coSoSanXuats: { type: Array, default: () => [] },
  },
  data: () => ({}),
  watch: {},
  computed: {
    intermediate_data: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("input", value);
      },
    },
  },
  methods: {
    change() {
      this.$emit("change");
    },
  },
};
</script>
