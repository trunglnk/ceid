<template>
  <div class="row">
    <div class="col-md-12">
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
        <div class="shrink" style="cursor: pointer">
          <div data-toggle="tooltip" title="Làm mới" @click="onReset">
            <i class="fa fa-refresh"></i>
          </div>
        </div>
      </ul>
      <div>
        <div style="height: 100%; width: 100%; overflow: hidden">
          <div
            style="overflow: hidden; padding: 0 15px; height: 100%; width: 100%"
          >
            <FilterBasic
              v-show="tagShow == 1"
              @change-data="changeData"
              class="row"
              :key="`basic-${key}`"
            ></FilterBasic>
            <FilterAdvanced
              v-show="tagShow == 2"
              @change-data="changeData"
              class="row"
              :key="`advanced-${key}`"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div>
        <label class="control-label">Lựa chọn mẫu báo cáo</label>
        <div class="d-flex">
          <checkbox-component
            id="checkbox-maubaoca0-1"
            v-model="form.mau_bao_caos.doi_tuong_thanh_tra_co_ban"
            text="ĐỐI TƯỢNG THANH TRA CƠ BẢN"
          ></checkbox-component>
          <checkbox-component
            id="checkbox-maubaoca0-2"
            v-model="form.mau_bao_caos.thong_tin_chung"
            text="THÔNG TIN CHUNG"
          ></checkbox-component>
          <checkbox-component
            id="checkbox-maubaoca0-3"
            v-model="form.mau_bao_caos.xa_thai"
            text="XẢ THẢI"
          ></checkbox-component>
          <checkbox-component
            id="checkbox-maubaoca0-4"
            v-model="form.mau_bao_caos.thu_tuc_hanh_chinh"
            text="THỦ TỤC HÀNH CHÍNH"
          ></checkbox-component>
          <checkbox-component
            id="checkbox-maubaoca0-5"
            v-model="form.mau_bao_caos.theo_doi_thanh_tra"
            text="THEO DÕI KẾT QUẢ TT CÁC NĂM"
          ></checkbox-component>
          <checkbox-component
            id="checkbox-maubaoca0-6"
            v-model="form.mau_bao_caos.khu_sx_dv_tap_trung"
            text="KHU SX DỊCH VỤ TẬP TRUNG"
          ></checkbox-component>
          <checkbox-component
            id="checkbox-maubaoca0-7"
            v-model="form.mau_bao_caos.chat_thai_ran"
            text="CHẤT THẢI RẮN"
          ></checkbox-component>
          <checkbox-component
            id="checkbox-maubaoca0-9"
            v-model="form.mau_bao_caos.xu_ly_vi_pham_hanh_chinh"
            text="XỬ LÝ VI PHẠM HÀNH CHÍNH"
          ></checkbox-component>
          <checkbox-component
            id="checkbox-maubaoca0-10"
            v-model="form.mau_bao_caos.tong_hop_ket_qua_kiem_tra_vi_pham"
            text="TỔNG HỢP KẾT QUẢ KIỂM TRA"
          ></checkbox-component>
          <checkbox-component
            id="checkbox-maubaoca0-8"
            text="TẤT CẢ"
            v-model="isCheckAll_mau_bao_caos"
            @change="toggleCheckALL"
          ></checkbox-component>
        </div>
      </div>
      <div class="row mb-2 pull-right">
        <button
          type="button"
          class="btn btn-outline"
          @click="getData"
          :disabled="isLoading"
        >
          Xem trước dữ liệu
        </button>
        <button
          type="button"
          class="btn btn-light bg-navy"
          @click="getDataExcel"
          style="margin-left: 10px"
          :disabled="isLoading"
        >
          Xuất báo cáo
        </button>
      </div>
    </div>
    <div v-if="loading" class="text-center">
      <i class="fa fa-spinner fa-spin" style="font-size: 24px"></i>
    </div>
    <div class="col-md-12" v-else>
      <hr />
      <div class="margin-top pb-4">
        <ul class="nav nav-stacked d-flex align-center">
          <li
            @click="tab = item.key"
            v-show="baocaoData[item.keyValue]"
            v-for="(item, i) in tabs"
            :key="i"
            class="shrink"
            :class="{ active: tab == item.key }"
          >
            <a>{{ item.text }}</a>
          </li>
        </ul>
      </div>
    </div>
    <div class="table-result-viewer">
      <div
        class="col-md-12"
        style="overflow: hidden"
        v-show="baocaoData['ChatThaiRan'] && tab == 'chat_thai_ran'"
      >
        <ChatThaiRan :items="baocaoData['ChatThaiRan']"></ChatThaiRan>
      </div>
      <div
        class="col-md-12"
        style="overflow: hidden"
        v-show="
          baocaoData['DoiTuongThanhTraCoBan'] &&
          tab == 'doi_tuong_thanh_tra_co_ban'
        "
      >
        <DoiTuongThanhTraCoBan
          :items="baocaoData['DoiTuongThanhTraCoBan']"
        ></DoiTuongThanhTraCoBan>
      </div>
      <div
        class="col-md-12"
        style="overflow: hidden"
        v-show="baocaoData['ThongTinChung'] && tab == 'thong_tin_chung'"
      >
        <ThongTinChung :items="baocaoData['ThongTinChung']"></ThongTinChung>
      </div>
      <div
        class="col-md-12"
        style="overflow: hidden"
        v-show="baocaoData['XaThai'] && tab == 'xa_thai'"
      >
        <XaThai :items="baocaoData['XaThai']"></XaThai>
      </div>
      <div
        class="col-md-12"
        style="overflow: hidden"
        v-show="baocaoData['KhuSxDvTapTrung'] && tab == 'khu_sx_dv_tap_trung'"
      >
        <KhuSxDvTapTrung
          :items="baocaoData['KhuSxDvTapTrung']"
        ></KhuSxDvTapTrung>
      </div>
      <div
        class="col-md-12"
        style="overflow: hidden"
        v-show="baocaoData['TheoDoiThanhTra'] && tab == 'theo_doi_thanh_tra'"
      >
        <TheoDoiThanhTra
          :items="baocaoData['TheoDoiThanhTra']"
        ></TheoDoiThanhTra>
      </div>
      <div
        class="col-md-12"
        style="overflow: hidden"
        v-show="baocaoData['ThuTucHanhChinh'] && tab == 'thu_tuc_hanh_chinh'"
      >
        <ThuTucHanhChinh
          :items="baocaoData['ThuTucHanhChinh']"
        ></ThuTucHanhChinh>
      </div>
      <div
        class="col-md-12"
        style="overflow: hidden"
        v-show="baocaoData['XuLyViPhamHanhChinh'] && tab == 'xu_ly_vi_pham_hanh_chinh'"
      >
        <XuLyViPhamHanhChinh
          :items="baocaoData['XuLyViPhamHanhChinh']"
        ></XuLyViPhamHanhChinh>
      </div>
      <div
        class="col-md-12"
        style="overflow: hidden"
        v-show="baocaoData['TongHopKetQuaKiemTraViPham'] && tab == 'tong_hop_ket_qua_kiem_tra_vi_pham'"
      >
        <TongHopKetQuaKiemTraViPham
          :items="baocaoData['TongHopKetQuaKiemTraViPham']"
        ></TongHopKetQuaKiemTraViPham>
      </div>
    </div>
  </div>
</template>

<script>
import FilterBasic from "./GeneralFilter/FilterBasic";
import FilterAdvanced from "./GeneralFilter/FilterAdvanced";
import * as env from "../env.js";
import ChatThaiRan from "./preview/ChatThaiRan";
import KetQuaThanhTra from "./preview/KetQuaThanhTra";
import NhomHanhViViPham from "./preview/NhomHanhViViPham";
import TheoDoiThucHienKLTT from "./preview/TheoDoiThucHienKLTT";
import ThongTinChung from "./preview/ThongTinChung";
import TongHopCacNhomViPham from "./preview/TongHopCacNhomViPham";
import XaThai from "./preview/XaThai";
import DoiTuongThanhTraCoBan from "./preview/DoiTuongThanhTraCoBan";
import ThuTucHanhChinh from "./preview/ThuTucHanhChinh";
import TheoDoiThanhTra from "./preview/TheoDoiThanhTra";
import KhuSxDvTapTrung from "./preview/KhuSxDvTapTrung";
import XuLyViPhamHanhChinh from "./preview/XuLyViPhamHanhChinh";
import TongHopKetQuaKiemTraViPham from "./preview/TongHopKetQuaKiemTraViPham.vue";
export default {
  props: {
    show: Boolean,
  },
  components: {
    FilterAdvanced,
    FilterBasic,
    ChatThaiRan,
    KetQuaThanhTra,
    ThongTinChung,
    NhomHanhViViPham,
    TheoDoiThucHienKLTT,
    TongHopCacNhomViPham,
    XaThai,
    DoiTuongThanhTraCoBan,
    ThuTucHanhChinh,
    TheoDoiThanhTra,
    KhuSxDvTapTrung,
    XuLyViPhamHanhChinh,
    TongHopKetQuaKiemTraViPham
  },
  computed: {},
  data: () => ({
    coQuanPheDuyet: {},
    loading: false,
    tagShow: 1,
    search_ten: "",
    tong_so_ket_luan: 0,
    isFilter: false,
    optionsDate: {
      format: "DD/MM/YYYY",
      useCurrent: false,
      locale: "vi",
    },
    money: {
      decimal: ".",
      thousands: ",",
      prefix: "",
      suffix: "",
      precision: 0,
      masked: false,
    },
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
    doanthanhtra: {
      value: [],
      options: [],
      isLoading: false,
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
    ky_thanh_tra: {
      dot_xuat: true,
      dinh_ky: true,
    },
    loaihinhcoso: {
      value: [],
      options: [],
      isLoading: true,
    },
    loaiHinhONhiem: {
      value: [],
      options: [],
      isLoading: true,
    },
    khucongnghiep: {
      value: [],
      options: [],
      isLoading: true,
      data: [],
    },
    bienphapxuphatboxung: {
      value: [],
      options: [],
      isLoading: true,
      dinh_chi: false,
    },
    bienphapkhacphuchauqua: {
      value: [],
      options: [],
      isLoading: true,
    },
    ketquakhacphuchauqua: {
      nop_mot_phan: false,
      da_nop_phat: false,
      da_khac_phuc: false,
      da_bao_cao: false,
    },
    loaiVanBan: {
      value: [],
      options: [],
      isLoading: true,
    },
    coquanpheduyetId: [],
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
        co_bien_phap_xu_ly_khi_thai: false,
        khi_thai_vuot: false,
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
    ket_luan: {
      vi_pham: false,
    },
    dieu: {
      value: [],
      options: [],
      isLoading: true,
    },
    khoan: {
      value: [],
      options: [],
      isLoading: false,
    },
    muc: {
      value: [],
      options: [],
      isLoading: false,
    },
    donvics: {
      value: [],
      options: [],
      isLoading: false,
    },
    luu_luong_nuoc_thai: 0,
    luu_luong_nuoc_thai_max: null,
    luu_luong_khi_thai: 0,
    luu_luong_khi_thai_max: null,
    cong_suat_hoat_dong_max: null,
    donvi: null,
    ten_don_vi_nuoc: null,
    ten_don_vi_khi: null,
    errors_donvi: null,
    datas: {
      ky_thanh_tra: [],
      mien: [],
      ket_qua_thuc_hien_ket_luan_thanh_tra: [],
      co_quan_phe_duyet: [],
      danh_sach_ket_qua_thanh_tra: [],
    },
    search_year: null,
    tu_ngay: null,
    den_ngay: null,
    show_search_advanced: false,
    show_search_basic: false,
    show_co_quan_phe_duyet: false,
    show_search_thu_tuc_hanh_chinh: false,
    show_search_vi_pham: false,
    show_search_vi_pham_khac: false,
    toggleInspectionTeam: false,
    toggleFacilityType: false,
    toggleIndustrial: false,
    toggleApprovedAgencyDTM: false,
    toggleApprovedAgencyDABVMT: false,
    toggleApprovedAgencyKHBVMT: false,
    toggleAdditionalSanctions: false,
    toggleResultsOfImplementing: false,
    toggleRemedy: false,
    order_column: "ten",
    order_direction: "asc",
    tabs: [
      {
        key: "doi_tuong_thanh_tra_co_ban",
        keyValue: "DoiTuongThanhTraCoBan",
        text: "Đối tượng thanh tra cơ bản",
      },
      {
        key: "thong_tin_chung",
        keyValue: "ThongTinChung",
        text: "Thông tin chung",
      },
      {
        key: "xa_thai",
        keyValue: "XaThai",
        text: "Xả thải",
      },
      {
        key: "thu_tuc_hanh_chinh",
        keyValue: "ThuTucHanhChinh",
        text: "Thủ tục hành chính",
      },
      {
        key: "theo_doi_thanh_tra",
        keyValue: "TheoDoiThanhTra",
        text: "Theo dõi kết luận TT các năm",
      },
      {
        key: "khu_sx_dv_tap_trung",
        keyValue: "KhuSxDvTapTrung",
        text: "Khu SX dịch vụ tập trung",
      },
      {
        key: "chat_thai_ran",
        keyValue: "ChatThaiRan",
        text: "Chất thải rắn",
      },
      {
        key: "xu_ly_vi_pham_hanh_chinh",
        keyValue: "XuLyViPhamHanhChinh",
        text: "Xử lý vi phạm hành chính",
      },
      {
        key: "tong_hop_ket_qua_kiem_tra_vi_pham",
        keyValue: "TongHopKetQuaKiemTraViPham",
        text: "Tổng hợp kết quả kiểm tra vi phạm",
      },
    ],
    tab: "",
    isLoading: false,
    form: {
      mau_bao_caos: {
        doi_tuong_thanh_tra_co_ban: false,
        thu_tuc_hanh_chinh: false,
        thong_tin_chung: false,
        theo_doi_thanh_tra: false,
        khu_sx_dv_tap_trung: false,
        xa_thai: false,
        chat_thai_ran: false,
        xu_ly_vi_pham_hanh_chinh: false,
        tong_hop_ket_qua_kiem_tra_vi_pham: false,
      },
    },
    isCheckAll_mau_bao_caos: false,
    baocaoData: {},
    key: 0,
  }),
  // watch: {
  //   coQuanPheDuyet: function(val){
  //     console.log(val, this.loaiVanBan)
  //   }
  // },
  mounted() {
    this.isCheckAll_mau_bao_caos = true;
    this.toggleCheckALL();
  },
  methods: {
    submit() {
      this.$emit("submit");
      this.isFilter = false;
    },
    toggleCheckALL() {
      setTimeout(() => {
        this.form.mau_bao_caos.doi_tuong_thanh_tra_co_ban =
          this.isCheckAll_mau_bao_caos;
        this.form.mau_bao_caos.thu_tuc_hanh_chinh =
          this.isCheckAll_mau_bao_caos;
        this.form.mau_bao_caos.thong_tin_chung = this.isCheckAll_mau_bao_caos;
        this.form.mau_bao_caos.theo_doi_thanh_tra =
          this.isCheckAll_mau_bao_caos;
        this.form.mau_bao_caos.khu_sx_dv_tap_trung =
          this.isCheckAll_mau_bao_caos;
        this.form.mau_bao_caos.xa_thai = this.isCheckAll_mau_bao_caos;
        this.form.mau_bao_caos.chat_thai_ran = this.isCheckAll_mau_bao_caos;
        this.form.mau_bao_caos.xu_ly_vi_pham_hanh_chinh = this.isCheckAll_mau_bao_caos;
        this.form.mau_bao_caos.tong_hop_ket_qua_kiem_tra_vi_pham = this.isCheckAll_mau_bao_caos;
      }, 0);
    },
    getUrl() {
      var tu_ngays = this.tu_ngay;
      var den_ngays = this.den_ngay;
      var strUrl = "";
      if (this.order_column && this.order_direction) {
        strUrl += `order_column=${this.order_column}&&order_direction=${this.order_direction}&&`
      }

      Object.keys(this.form.mau_bao_caos).forEach((key) => {
        if (this.form.mau_bao_caos[key]) {
          strUrl = strUrl + `maus_${key}=` + 1 + "&";
        }
      });
      // loai van ban
      if (this.loaiVanBan.value.length > 0) {
        var loaiVanBanselect = "";
        this.loaiVanBan.value.forEach((item) => {
          this.coquanpheduyetId = [];
          for (let key in this.coQuanPheDuyet) {
            if (key == item.id && this.coQuanPheDuyet[key]) {
              this.coQuanPheDuyet[key].forEach((value) => {
                this.coquanpheduyetId.push(value.id);
              });
            }
          }
          loaiVanBanselect =
            loaiVanBanselect +
            '{"' +
            item.id +
            '":' +
            '"' +
            this.coquanpheduyetId +
            '"} ';
        });
        strUrl = strUrl + "search_loai_van_ban=" + loaiVanBanselect + "&";
      }
      if (this.search_ten) {
        strUrl = strUrl + "search_ten=" + this.search_ten + "&";
      }
      if (this.mien.value) {
        var mienIds = [];
        this.mien.value.forEach((element) => {
          mienIds.push(element.id);
        });
        if (mienIds !== undefined && mienIds.length > 0) {
          strUrl = strUrl + "search_mien=" + mienIds + "&";
        }
      }
      if (this.tinh.value) {
        var tinhIds = [];
        this.tinh.value.forEach((element) => {
          tinhIds.push(element.id);
        });
        if (tinhIds !== undefined && tinhIds.length > 0) {
          strUrl = strUrl + "search_tinh=" + tinhIds + "&";
        }
      }
      if (this.luuvucsong.value) {
        var luuvucsongIds = [];
        this.luuvucsong.value.forEach((element) => {
          luuvucsongIds.push(element.id);
        });
        if (luuvucsongIds !== undefined && luuvucsongIds.length > 0) {
          strUrl = strUrl + "search_luu_vuc_song=" + luuvucsongIds + "&";
        }
      }
      if (this.vungkinhtes.value) {
        var vungkinhteIds = [];
        this.vungkinhtes.value.forEach((element) => {
          vungkinhteIds.push(element.id);
        });
        strUrl = strUrl + "search_vungkinhte=" + vungkinhteIds + "&";
      }
      if (
        this.coquanpheduyetdtm.value != null &&
        this.coquanpheduyetdtm.value.length > 0
      ) {
        var coquanpheduyetdtmIds = [];
        this.coquanpheduyetdtm.value.forEach((element) => {
          coquanpheduyetdtmIds.push(element.id);
        });
        strUrl =
          strUrl + "search_co_quan_phe_duyet_dtm=" + coquanpheduyetdtmIds + "&";
      }
      if (
        this.coquanpheduyetdeanbvmt.value != null &&
        this.coquanpheduyetdeanbvmt.value.length > 0
      ) {
        var coquanpheduyetdeanbvmtIds = [];
        this.coquanpheduyetdeanbvmt.value.forEach((element) => {
          coquanpheduyetdeanbvmtIds.push(element.id);
        });
        strUrl =
          strUrl +
          "search_co_quan_phe_duyet_dabvmt=" +
          coquanpheduyetdeanbvmtIds +
          "&";
      }
      if (
        this.coquanpheduyetkehoachbvmt.value != null &&
        this.coquanpheduyetkehoachbvmt.value.length > 0
      ) {
        var coquanpheduyetkehoachbvmtIds = [];
        this.coquanpheduyetkehoachbvmt.value.forEach((element) => {
          coquanpheduyetkehoachbvmtIds.push(element.id);
        });
        strUrl =
          strUrl +
          "search_co_quan_phe_duyet_gxnctbvmt=" +
          coquanpheduyetkehoachbvmtIds +
          "&";
      }
      if (this.dieu.value != null) {
        if ((this.dieu.value.length > 0) | this.dieu.value.id) {
          strUrl = strUrl + "search_dieu=" + this.dieu.value.id + "&";
        }
      }
      if (this.khoan.value != null) {
        if ((this.khoan.value.length > 0) | this.khoan.value.id) {
          strUrl = strUrl + "search_khoan=" + this.khoan.value.id + "&";
        }
      }
      if (this.muc.value != null) {
        if (this.muc.value.length > 0) {
          var mucIds = [];
          this.muc.value.forEach((element) => {
            mucIds.push(element.id);
          });
          strUrl = strUrl + "search_muc=" + mucIds + "&";
        }
      }

      if (this.doanthanhtra.value) {
        var doanthanhtraIds = [];
        this.doanthanhtra.value.forEach((element) => {
          doanthanhtraIds.push(element.id);
        });
        strUrl = strUrl + "search_doan_thanh_tra=" + doanthanhtraIds + "&";
      }

      if (this.loaihinhcoso.value) {
        var loaihinhcosoIds = [];
        this.loaihinhcoso.value.forEach((element) => {
          loaihinhcosoIds.push(element.id);
        });
        strUrl = strUrl + "search_loai_hinh_co_so=" + loaihinhcosoIds + "&";
      }
      if (this.loaiHinhONhiem.value) {
        var loaihinhonhiemIds = [];
        this.loaiHinhONhiem.value.forEach((element) => {
          loaihinhonhiemIds.push(element.id);
        });
        strUrl = strUrl + "search_loai_hinh_o_nhiem=" + loaihinhonhiemIds + "&";
      }
      if (this.khucongnghiep.value) {
        var khucongnghiepIds = [];
        this.khucongnghiep.value.forEach((element) => {
          khucongnghiepIds.push(element.ma);
        });
        strUrl = strUrl + "search_khu_cong_nghiep=" + khucongnghiepIds + "&";
      }
      if (this.bienphapxuphatboxung.value) {
        var bienphapxuphatboxungIds = [];
        this.bienphapxuphatboxung.value.forEach((element) => {
          bienphapxuphatboxungIds.push(element.id);
        });
        strUrl =
          strUrl +
          "search_bien_phap_xu_phat_bo_xung=" +
          bienphapxuphatboxungIds +
          "&";
      }

      if (this.bienphapxuphatboxung.dinh_chi) {
        strUrl += "bienphapxuphatboxung_dinh_chi=1&";
      }

      if (this.ketquakhacphuchauqua) {
        if (this.ketquakhacphuchauqua.nop_mot_phan) {
          strUrl += "ketquakhacphuchauqua_nop_mot_phan=1&";
        }
        if (this.ketquakhacphuchauqua.da_nop_phat) {
          strUrl += "ketquakhacphuchauqua_da_nop_phat=1&";
        }
        if (this.ketquakhacphuchauqua.da_khac_phuc) {
          strUrl += "ketquakhacphuchauqua_da_khac_phuc=1&";
        }
        if (this.ketquakhacphuchauqua.da_bao_cao) {
          strUrl += "ketquakhacphuchauqua_da_bao_cao=1&";
        }
      }
      if (this.ket_luan) {
        if (this.ket_luan.vi_pham) {
          strUrl += "vi_pham=1&";
        }
      }
      // thu tuc hanh chinh
      if (this.ketquathuchien.vi_pham_thu_tuc_hanh_chinh == true) {
        strUrl += "vi_pham_thu_tuc_hanh_chinh=1&";
      }
      if (this.ketquathuchien.dtm_dean_kehoach_bvmt.filter != null) {
        strUrl +=
          "dtm_dean_khbvmt_filter=" +
          this.ketquathuchien.dtm_dean_kehoach_bvmt.filter.id +
          "&";
      }

      if (
        this.ketquathuchien.dtm_dean_kehoach_bvmt
          .dtmdakhbvmt_thuc_hien_khong_dung_noi_dung == true
      ) {
        strUrl += "dtmdakhbvmt_thuc_hien_khong_dung_noi_dung=1&";
      }
      if (
        this.ketquathuchien.dtm_dean_kehoach_bvmt
          .dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung == true
      ) {
        strUrl += "dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung=1&";
      }

      if (this.ketquathuchien.khong_co_giay_phep_xa_thai == true) {
        strUrl += "khong_co_giay_phep_xa_thai=1&";
      }
      if (this.ketquathuchien.co_ket_hoach_quan_ly_moi_truong == true) {
        strUrl += "co_ket_hoach_quan_ly_moi_truong=1&";
      }

      if (
        this.ketquathuchien.giay_xac_nhan.khong_thuc_hien_giay_xac_nhan == true
      ) {
        strUrl += "khong_thuc_hien_giay_xac_nhan=1&";
      }
      if (
        this.ketquathuchien.giay_xac_nhan.thuc_hien_sai_giay_xac_nhan == true
      ) {
        strUrl += "thuc_hien_sai_giay_xac_nhan=1&";
      }

      if (this.ketquathuchien.congtrinhBVMT.khong_xay_lap == true) {
        strUrl += "congtrinhBVMT_khong_xay_lap=1&";
      }
      if (this.ketquathuchien.congtrinhBVMT.xay_lap_khong_dung == true) {
        strUrl += "congtrinhBVMT_xay_lap_khong_dung=1&";
      }
      if (
        this.ketquathuchien.congtrinhBVMT
          .van_hanh_khong_dung_khong_thuong_xuyen == true
      ) {
        strUrl += "congtrinhBVMT_van_hanh_khong_dung_khong_thuong_xuyen=1&";
      }
      if (
        this.ketquathuchien.congtrinhBVMT.khong_co_giay_xac_nhan_hoan_thanh ==
        true
      ) {
        strUrl += "khong_co_giay_xac_nhan_hoan_thanh=1&";
      }

      // gsmt
      if (this.ketquathuchien.gsmt.gsmt_thuc_hien == true) {
        strUrl += "gsmt_thuc_hien=1&";
      }
      if (this.ketquathuchien.gsmt.gsmt_khong_thuc_hien == true) {
        strUrl += "gsmt_khong_thuc_hien=1&";
      }
      if (this.ketquathuchien.gsmt.gsmt_thuc_hien_khong_dung_khong_du == true) {
        strUrl += "gsmt_thuc_hien_khong_dung_khong_du=1&";
      }
      if (this.ketquathuchien.vi_pham_nhom_hanh_vi_xa_thai == true) {
        strUrl += "vi_pham_nhom_hanh_vi_xa_thai=1&";
      }
      // nuoc thai
      if (
        this.ketquathuchien.nuoc_thai
          .co_he_thong_thu_gom_nuoc_thai_rieng_biet == true
      ) {
        strUrl += "co_he_thong_thu_gom_nuoc_thai_rieng_biet=1&";
      }
      if (this.ketquathuchien.nuoc_thai.nuoc_thai_vuot == true) {
        strUrl += "nuoc_thai_vuot=1&";
      }
      if (this.ketquathuchien.nuoc_thai.chi_tiet_vi_pham_xa_thais.length >= 0) {
        this.ketquathuchien.nuoc_thai.chi_tiet_vi_pham_xa_thais.map(
          (item, key) => {
            strUrl +=
              "nuoc_thai_vi_pham_xa_thai[" +
              key +
              "][thong_so_id]=" +
              item.thong_so_id +
              "&";
            strUrl +=
              "nuoc_thai_vi_pham_xa_thai[" +
              key +
              "][so_lan_vuot_tu]=" +
              item.so_lan_vuot_tu +
              "&";
            strUrl +=
              "nuoc_thai_vi_pham_xa_thai[" +
              key +
              "][so_lan_vuot_den]=" +
              item.so_lan_vuot_den +
              "&";
          }
        );
      }

      // khi thai
      if (this.ketquathuchien.khi_thai.co_bien_phap_xu_ly_khi_thai == true) {
        strUrl += "co_bien_phap_xu_ly_khi_thai=1&";
      }

      if (this.ketquathuchien.khi_thai.khi_thai_vuot == true) {
        strUrl += "khi_thai_vuot=1&";
      }

      if (this.ketquathuchien.khi_thai.chi_tiet_vi_pham_xa_thais.length >= 0) {
        this.ketquathuchien.khi_thai.chi_tiet_vi_pham_xa_thais.map(
          (item, key) => {
            strUrl +=
              "khi_thai_vi_pham_xa_thai[" +
              key +
              "][thong_so_id]=" +
              item.thong_so_id +
              "&";
            strUrl +=
              "khi_thai_vi_pham_xa_thai[" +
              key +
              "][so_lan_vuot_tu]=" +
              item.so_lan_vuot_tu +
              "&";
            strUrl +=
              "khi_thai_vi_pham_xa_thai[" +
              key +
              "][so_lan_vuot_den]=" +
              item.so_lan_vuot_den +
              "&";
          }
        );
      }

      // ctrsh
      if (this.ketquathuchien.ctrsh.ctrsh_co_thu_gom == true) {
        strUrl += "ctrsh_co_thu_gom=1&";
      }
      if (this.ketquathuchien.ctrsh.ctrsh_co_phan_loai == true) {
        strUrl += "ctrsh_co_phan_loai=1&";
      }
      if (this.ketquathuchien.ctrsh.ctrsh_co_luu_tru == true) {
        strUrl += "ctrsh_co_luu_tru=1&";
      }
      if (this.ketquathuchien.ctrsh.ctrsh_co_chuyen_giao == true) {
        strUrl += "ctrsh_co_chuyen_giao=1&";
      }

      // ctrcn
      if (this.ketquathuchien.ctrcn.ctrcn_co_thu_gom == true) {
        strUrl += "ctrcn_co_thu_gom=1&";
      }
      if (this.ketquathuchien.ctrcn.ctrcn_co_phan_loai == true) {
        strUrl += "ctrcn_co_phan_loai=1&";
      }
      if (this.ketquathuchien.ctrcn.ctrcn_co_luu_tru == true) {
        strUrl += "ctrcn_co_luu_tru=1&";
      }
      if (this.ketquathuchien.ctrcn.ctrcn_co_chuyen_giao == true) {
        strUrl += "ctrcn_co_chuyen_giao=1&";
      }
      //ctnh
      if (this.ketquathuchien.ctnh.ctrnh_vi_pham_chung_tu == true) {
        strUrl += "ctrnh_vi_pham_chung_tu=1&";
      }
      if (this.ketquathuchien.ctnh.ctrnh_co_thu_gom == true) {
        strUrl += "ctrnh_co_thu_gom=1&";
      }
      if (this.ketquathuchien.ctnh.ctrnh_co_phan_loai == true) {
        strUrl += "ctrnh_co_phan_loai=1&";
      }
      if (this.ketquathuchien.ctnh.ctrnh_co_luu_tru == true) {
        strUrl += "ctrnh_co_luu_tru=1&";
      }
      if (this.ketquathuchien.ctnh.ctrnh_co_de_lan == true) {
        strUrl += "ctrnh_co_de_lan=1&";
      }
      if (this.ketquathuchien.ctnh.ctrnh_co_chuyen_giao == true) {
        strUrl += "ctrnh_co_chuyen_giao=1&";
      }
      if (this.ketquathuchien.ctnh.ctrnh_co_chon_lap == true) {
        strUrl += "ctrnh_co_chon_lap=1&";
      }
      if (this.ketquathuchien.ctnh.ctrnh_co_do_thai == true) {
        strUrl += "ctrnh_co_do_thai=1&";
      }
      if (this.ketquathuchien.ctnh.ctrnh_co_giay_phep == true) {
        strUrl += "ctrnh_co_giay_phep=1&";
      }
      // nhom hanh vi khac
      if (this.ketquathuchien.vi_pham_nhom_hanh_vi_khac == true) {
        strUrl += "vi_pham_nhom_hanh_vi_khac=1&";
      }
      if (
        this.ketquathuchien
          .vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong == true
      ) {
        strUrl +=
          "vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong=1&";
      }
      if (this.ketquathuchien.vi_pham_cac_quy_dinh_bao_ve_moi_truong == true) {
        strUrl += "vi_pham_cac_quy_dinh_bao_ve_moi_truong=1&";
      }
      if (this.ketquathuchien.co_hanh_vi_can_tro_ve_bvmt == true) {
        strUrl += "co_hanh_vi_can_tro_ve_bvmt=1&";
      }
      // bien phap khac phuc hau qua
      if (this.bienphapkhacphuchauqua.value) {
        var bienphapkhacphuchauquaIds = [];
        this.bienphapkhacphuchauqua.value.forEach((element) => {
          bienphapkhacphuchauquaIds.push(element.id);
        });
        strUrl =
          strUrl +
          "search_bien_phap_khac_phuc_vi_pham=" +
          bienphapkhacphuchauquaIds +
          "&";
      }
      if (this.luu_luong_nuoc_thai > 0) {
        strUrl = strUrl + "search_nuoc_thai=" + this.luu_luong_nuoc_thai + "&";
      }
      if (this.luu_luong_nuoc_thai_max != null) {
        strUrl =
          strUrl + "search_nuoc_thai_max=" + this.luu_luong_nuoc_thai_max + "&";
      }
      if (this.luu_luong_khi_thai > 0) {
        strUrl = strUrl + "search_khi_thai=" + this.luu_luong_khi_thai + "&";
      }
      if (this.luu_luong_khi_thai_max != null) {
        strUrl =
          strUrl + "search_khi_thai_max=" + this.luu_luong_khi_thai_max + "&";
      }

      if (tu_ngays && den_ngays) {
        strUrl = strUrl + "search_start_date=" + tu_ngays + "&";
        strUrl = strUrl + "search_end_date=" + den_ngays + "&";
      }
      strUrl = strUrl + "search_dot_xuat=" + this.ky_thanh_tra.dot_xuat + "&";
      strUrl = strUrl + "search_ke_hoach=" + this.ky_thanh_tra.dinh_ky + "&";

      return strUrl;
    },

    changeData(val) {
      Object.assign(this.$data, val);
    },
    getDataExcel() {
      this.isLoading = true;
      this.loading = true;
      setTimeout(() => {
        var url = env.endpointhttp + "baocaotonghop/export/excel?";
        var strUrl = this.getUrl();
        window.open(url + strUrl);
        this.isLoading = false;
        this.loading = false;
      });
    },
    getData() {
      this.isLoading = true;
      this.loading = true;
      var strUrl = this.getUrl();
      axios
        .get(env.endpointhttp + "baocaotonghop/dulieuxemtruoc?" + strUrl)
        .then((response) => {
          this.baocaoData = response.data.data;
          console.log('this.baocaoData', this.baocaoData);
          
          let isBreak = false;
          Object.keys(this.form.mau_bao_caos).forEach((key) => {
            if (isBreak) return;
            if (this.form.mau_bao_caos[key]) {
              this.tab = key;
              isBreak = true;
            }
          });
        })
        .catch((error) => {})
        .finally(() => ((this.isLoading = false), (this.loading = false)));
    },
    onReset() {
      Object.assign(this.$data, this.$options.data());
      this.key++;
      this.$nextTick(() => {
        this.isCheckAll_mau_bao_caos = true;
        this.toggleCheckALL();
      });
    },
  },
};
</script>

<style scoped>
/deep/ .control-label {
  margin: 1rem 0;
}
.d-flex {
  display: flex;
  flex-wrap: wrap;
}
.d-flex .form-group {
  flex: 1 1 300px;
  width: 300px;
  max-width: 300px;
}
.d-flex /deep/ .control-label {
  font-weight: normal;
  padding-left: 2px;
}

/deep/ td {
  border: 1px solid #000000;
}
/deep/ .table-container {
  width: 100%;
  overflow: auto;
  min-height: 500px;
  max-height: calc(100vh - 700px);
}
.btn-outline {
  background: none;
  color: #3d9970;
  border: 1px solid #3d9970 !important;
}
.nav li.active > a {
  border-bottom-color: #3d9970 !important;
}
</style>
