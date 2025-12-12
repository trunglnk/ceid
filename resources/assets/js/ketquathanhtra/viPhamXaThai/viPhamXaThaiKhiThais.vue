<template>
  <div>
    <label class="control-label">Khí thải,bụi</label>
    <hr />
    <checkbox-component
      v-model="intermediate_data.khi_thai_vuot"
      type="checkbox"
      id="khi_thai_vuot"
      text="Xả"
      @change="change"
      :disabled="!canEdit"
    ></checkbox-component>
    <checkbox-component
      :value="!intermediate_data.co_bien_phap_xu_ly_khi_thai"
      @input="intermediate_data.co_bien_phap_xu_ly_khi_thai = !$event"
      type="checkbox"
      id="co_bien_phap_xu_ly_khi_thai"
      text="Không có hệ thống xử lý khí thải,bụi"
      @change="change"
      :disabled="!canEdit"
    ></checkbox-component>
    <vi-pham-phu
      v-model="intermediate_data.vi_pham_xa_thai_khi_thais"
      :danhMucThongSoVuotChuans="danhMucThongSoVuotChuanKhiThais"
      :coSoSanXuats="coSoSanXuats"
      :optionPhatTangThems="optionPhatTangThemKhiThais"
      :khoans="khiThaiKhoans"
      :donviThongSoVuotChuan="donviThongSoVuotChuan"
      :is_loading="is_loading"
      @change="change"
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
    khiThaiKhoans: { type: Array, default: () => [] },
    optionPhatTangThemKhiThais: { type: Array, default: () => [] },
    danhMucThongSoVuotChuanKhiThais: { type: Array, default: () => [] },
    donviThongSoVuotChuan: { type: Array, default: () => [] },
    coSoSanXuats: { type: Array, default: () => [] },
  },
  data: () => ({
    currentTab: 0,
    vi_pham_xa_thai_khi_thais: {
      khoan: undefined,
      muc: undefined,
      thong_so: undefined,
      co_so_san_xuat: undefined,
      chi_tiet_phat_tang_thems: [
        {
          phat_tang_them: undefined,
          so_lan_vuot: 0,
          don_vi: undefined,
          ket_qua: 0,
          thong_so: undefined,
        },
      ],
    },
  }),
  watch: {},
  computed: {
    khiThaiMucViPham() {
      return this.vi_pham_xa_thai_khi_thais.khoan
        ? this.vi_pham_xa_thai_khi_thais.khoan.mucs
        : [];
    },
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
    addViPhamPhu() {
      this.vi_pham_xa_thai_khi_thais.chi_tiet_phat_tang_thems.push({
        phat_tang_them: undefined,
        so_lan_vuot: 0,
        don_vi: undefined,
        ket_qua: 0,
        thong_so: undefined,
      });
      this.currentTab =
        this.vi_pham_xa_thai_khi_thais.chi_tiet_phat_tang_thems.length - 1;
    },
    deleteViPhamPhu(index) {
      this.vi_pham_xa_thai_khi_thais.chi_tiet_phat_tang_thems.splice(index, 1);
      if (this.vi_pham_xa_thai_khi_thais.chi_tiet_phat_tang_thems.length == 0) {
        this.addViPhamPhu();
      }
      this.currentTab = 0;
      this.$forceUpdate();
    },
    deleteViPhamPhuToData(index) {
      this.intermediate_data.vi_pham_xa_thai_khi_thais.splice(index, 1);
      this.$forceUpdate();
    },
    change() {
      this.$emit("change");
    },
    addViPhamPhuToData() {
      if (!this.intermediate_data.vi_pham_xa_thai_khi_thais) {
        this.intermediate_data.vi_pham_xa_thai_khi_thais = [];
      }
      this.intermediate_data.vi_pham_xa_thai_khi_thais.push(
        JSON.parse(JSON.stringify(this.vi_pham_xa_thai_khi_thais))
      );
      this.vi_pham_xa_thai_khi_thais = {
        khoan: undefined,
        muc: undefined,
        thong_so: undefined,
        co_so_san_xuat: undefined,
        chi_tiet_phat_tang_thems: [
          {
            phat_tang_them: undefined,
            so_lan_vuot: 0,
            don_vi: undefined,
            ket_qua: 0,
            thong_so: undefined,
          },
        ],
      };
    },
  },
  filters: {
    showViPhamPhu(value) {
      if (!value || value.length == 0) return "---";
      let str = "";
      str = value
        .map(
          (x) =>
            `${x.phat_tang_them ? x.phat_tang_them.name : ""};${
              x.so_lan_vuot
            };${x.don_vi ? x.don_vi.ten : ""};${x.ket_qua};${
              x.thong_so ? x.thong_so.ten : ""
            }`
        )
        .join("\n");
      return str;
    },
  },
};
</script>
