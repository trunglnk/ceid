<template>
  <modal-component title="Tra cứu" v-model="filter" minHeight="300px">
    <div class="col-md-12">
      <!-- <ul class="nav nav-stacked mb-2 d-flex align-center">
        <li class="shrink" :class="{ active: tagShow == 1 }">
          <a
            href="#tab_1"
            data-toggle="tab"
            aria-expanded="true"
            @click="tagShow = 1"
          >
            <i class="fa fa-tag"></i> Tra cứu cơ bản
          </a>
        </li>
        <li class="shrink" :class="{ active: tagShow == 2 }">
          <a
            href="#tab_2"
            data-toggle="tab"
            aria-expanded="true"
            @click="tagShow = 2"
          >
            <i class="fa fa-tag"></i> Tra cứu nâng cao
          </a>
        </li>
        <div class="grow"></div>
      </ul> -->
      <ul class="nav nav-stacked mb-2 d-flex align-center">
        <li class="shrink" :class="{ active: tagShow == 1 }">
          <a @click="tagShow = 1"> <i class="fa fa-tag"></i> Tra cứu cơ bản </a>
        </li>
        <li class="shrink" :class="{ active: tagShow == 2 }">
          <a @click="tagShow = 2">
            <i class="fa fa-tag"></i> Tra cứu nâng cao
          </a>
        </li>
        <div class="grow"></div>
      </ul>
    </div>
    <div class="col-md-12">
      <div class="tab-content">
        <div class="tab-pane active">
          <div class="timeline-item">
            <!-- <FilterBasic :key="key" @change-data="ChangeData" /> -->
            <FilterBasic v-show="tagShow == 1" @change-data="ChangeData" class="row" :key="key"></FilterBasic>
          </div>
        </div>
        <div class="tab-pane active">
          <div class="timeline-item">
            <FilterAdvanced v-show="tagShow == 2" @change-data="ChangeData" class="row" :key="key" />
            <!-- <FilterAdvanced :key="key" @change-data="ChangeData" /> -->
          </div>
        </div>
      </div>
    </div>

    <template v-slot:button-left>
      <button @click="reset" class="btn btn-flat bg-olive">
        <i class="fa fa-refresh"></i> Làm mới
      </button>
    </template>
    <template v-slot:button-right>
      <button type="submit" id="onsubmit" class="btn btn-flat bg-olive" @click="submit()">
        <i class="fa fa-search"></i> Tra cứu
      </button>
    </template>
  </modal-component>
</template>
<script>
import FilterBasic from "./FilterBasic";
import FilterAdvanced from "./FilterAdvanced";

export default {
  props: {
    show: Boolean,
  },
  components: {
    FilterBasic,
    FilterAdvanced,
  },
  computed: {
    filter: {
      set(value) {
        this.$emit("filter", value);
      },
      get() {
        return this.show;
      },
    },
  },
  data: () => ({
    loaihinhcoso: {
      value: [],
      options: [],
      isLoading: true,
    },
    tagShow: 1,
    key: 1,
    form: {
      coQuanPheDuyet: {},
      loaiVanBan: {
        value: [],
        options: [],
        isLoading: true,
      },
      ket_luan: {
        vi_pham: false,
      },
      optionsDate: {
        format: "DD/MM/YYYY",
        useCurrent: false,
        locale: "vi",
      },
      //mien
      mien: {
        value: [],
        options: [],
        data: [],
        isLoading: true,
      },
      tinh: {
        value: [],
        options: [],
        data: [],
        isLoading: true,
      },
      luuvucsong: {
        value: [],
        options: [],
        data: [],
        isLoading: true,
      },
      vungkinhtes: {
        value: [],
        options: [],
        data: [],
        isLoading: true,
      },
      //date
      search_year: new Date().getFullYear(),
      tu_ngay: null,
      den_ngay: null,
      ky_thanh_tra: {
        dot_xuat: true,
        dinh_ky: true,
      },
      khucongnghiep: {
        value: [],
        options: [],
        isLoading: true,
        data: [],
      },
      coquantochucs: {
        isLoading: true,
      },
      coquanpheduyetdtm: {
        value: [],
        options: [],
        cap_bo: false,
        cap_dia_phuong: false,
      },
      coquanpheduyetdeanbvmt: {
        value: [],
        options: [],
        cap_bo: false,
        cap_dia_phuong: false,
      },
      coquanpheduyetkehoachbvmt: {
        value: [],
        options: [],
        cap_bo: false,
        cap_dia_phuong: false,
      },
      doanthanhtra: {
        value: [],
        options: [],
        isLoading: false,
      },
      ketquathuchien: {
        vi_pham_thu_tuc_hanh_chinh: false,
        dtm_dean_kehoach_bvmt: {
          filter: null,
          dtmdakhbvmt_thuc_hien_khong_dung_noi_dung: false,
          dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung: false,
        },
        khong_co_giay_phep_xa_thai: false,
        co_ket_hoach_quan_ly_moi_truong: false,
        congtrinhBVMT: {
          khong_xay_lap: false,
          xay_lap_khong_dung: false,
          khong_co_giay_xac_nhan_hoan_thanh: false,
          van_hanh_khong_dung_khong_thuong_xuyen: false,
        },
        gsmt: {
          gsmt_khong_thuc_hien: false,
          gsmt_thuc_hien: false,
          gsmt_thuc_hien_khong_dung_khong_du: false,
        },
        giay_xac_nhan: {
          khong_thuc_hien_giay_xac_nhan: false,
          thuc_hien_sai_giay_xac_nhan: false,
        },
        // vi pham xa thai
        vi_pham_nhom_hanh_vi_xa_thai: false,
        nuoc_thai: {
          co_he_thong_thu_gom_nuoc_thai_rieng_biet: false,
          nuoc_thai_vuot: false,
          nuoc_thai_vuot_qcvn_tu: -1,
          nuoc_thai_vuot_qcvn_toi: -1,
        },
        khi_thai: {
          khi_thai_vuot: false,
          co_bien_phap_xu_ly_khi_thai: false,
          khi_thai_vuot_qcvn_tu: -1,
          khi_thai_vuot_qcvn_toi: -1,
        },
        ctrsh: {
          ctrsh_co_thu_gom: false,
          ctrsh_co_phan_loai: false,
          ctrsh_co_luu_tru: false,
          ctrsh_co_chuyen_giao: false,
        },
        ctrcn: {
          ctrcn_co_thu_gom: false,
          ctrcn_co_phan_loai: false,
          ctrcn_co_luu_tru: false,
          ctrcn_co_chuyen_giao: false,
        },
        ctnh: {
          ctrnh_vi_pham_chung_tu: false,
          ctrnh_co_thu_gom: false,
          ctrnh_co_phan_loai: false,
          ctrnh_co_luu_tru: false,
          ctrnh_co_de_lan: false,
          ctrnh_co_chuyen_giao: false,
          ctrnh_co_chon_lap: false,
          ctrnh_co_do_thai: false,
          ctrnh_co_giay_phep: false,
        },
        vi_pham_nhom_hanh_vi_khac: false,
        vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong: false,
        vi_pham_cac_quy_dinh_bao_ve_moi_truong: false,
        co_hanh_vi_can_tro_ve_bvmt: false,
      },
      bienphapxuphatboxung: {
        value: [],
        options: [],
        isLoading: true,
        dinh_chi: false,
      },
      ketquakhacphuchauqua: {
        nop_mot_phan: false,
        da_nop_phat: false,
        da_khac_phuc: false,
        da_bao_cao: false,
      },
      bienphapkhacphuchauqua: {
        value: [],
        options: [],
        isLoading: true,
      },
    },
  }),
  methods: {
    reset() {
      this.$emit("reset");
      this.key++;
    },
    submit() {
      this.$emit("submit");
      // this.filter = false;
    },
    ChangeData(e) {
      this.form = Object.assign(this.form, e);
      this.$emit("change-data", this.form);
    },
  },
};
</script>

<style scoped>
/deep/ .control-label {
  margin: 1rem 0;
}

.nav li.active>a {
  border-bottom-color: #3d9970 !important;
}
</style>
