<template>
  <span>
    <div class="row block-multiple-rows">
      <div class="col-lg-3 col-md-6">
        <label class="control-label">Kết luận</label>
        <checkbox-component
          v-model="intermediate_data.vi_pham"
          type="checkbox"
          id="vi_pham"
          text="Vi phạm"
          @change="change"
          :disabled="!canEdit"
        ></checkbox-component>
      </div>
    </div>
    <div class="row block-multiple-rows" v-show="intermediate_data.vi_pham">
      <div class="col-lg-12 col-md-12">
        <label class="control-label">Nhóm VP thủ tục hành chính</label>
        <checkbox-component
          v-model="intermediate_data.vi_pham_thu_tuc_hanh_chinh"
          type="checkbox"
          id="vi_pham_thu_tuc_hanh_chinh"
          text="Vi phạm thủ tục hành chính"
          @change="change"
          :disabled="!canEdit"
        ></checkbox-component>
      </div>
      <div
        class="col-lg-12"
        v-show="intermediate_data.vi_pham_thu_tuc_hanh_chinh"
      >
        <hr />
        <div class="row">
          <div class="col-lg-4">
            <label class="control-label">ĐTM/Đề án/KHBVMT</label>
            <hr />
            <checkbox-component
              v-model="
                intermediate_data.dtmdakhbvmt_thuc_hien_khong_dung_noi_dung
              "
              type="checkbox"
              id="dtmdakhbvmt_thuc_hien_khong_dung_noi_dung"
              text="Thực hiện không đúng nội dung"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <checkbox-component
              v-model="
                intermediate_data.dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung
              "
              type="checkbox"
              id="dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung"
              text="Không thực hiện một trong các nội dung"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <checkbox-component
              v-model="intermediate_data.khong_co_bao_cao_dtm"
              type="checkbox"
              id="khong_co_bao_cao_dtm"
              text="Không có báo cáo ĐTM"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
          </div>
          <div class="col-lg-4">
            <label class="control-label">Kế hoạch quản lý môi trường</label>
            <hr />
            <checkbox-component
              v-model="intermediate_data.co_ket_hoach_quan_ly_moi_truong"
              type="checkbox"
              id="co_ket_hoach_quan_ly_moi_truong"
              text="Không"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
          </div>
          <div class="col-lg-4">
            <label class="control-label">Công trình BVMT</label>
            <hr />
            <checkbox-component
              v-model="intermediate_data.khong_xay_lap"
              type="checkbox"
              id="khong_xay_lap"
              text="Không xây lắp"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <checkbox-component
              v-model="intermediate_data.xay_lap_khong_dung"
              type="checkbox"
              id="xay_lap_khong_dung"
              text="Xây lắp không đúng"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <checkbox-component
              v-model="intermediate_data.khong_co_giay_xac_nhan_hoan_thanh"
              type="checkbox"
              id="khong_co_giay_xac_nhan_hoan_thanh"
              text="Không có giấy xác nhận hoàn thành"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <checkbox-component
              v-model="intermediate_data.van_hanh_khong_dung_khong_thuong_xuyen"
              type="checkbox"
              id="van_hanh_khong_dung_khong_thuong_xuyen"
              text="Vận hành ko đúng/không thường xuyên"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-4">
            <label class="control-label">GSMT</label>
            <hr />
            <checkbox-component
              v-model="intermediate_data.gsmt_thuc_hien"
              type="checkbox"
              id="gsmt_thuc_hien"
              text="Thực hiện"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <checkbox-component
              v-model="intermediate_data.gsmt_khong_thuc_hien"
              type="checkbox"
              id="gsmt_khong_thuc_hien"
              text="Không thực hiện"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <checkbox-component
              v-model="intermediate_data.gsmt_thuc_hien_khong_dung_khong_du"
              type="checkbox"
              id="gsmt_thuc_hien_khong_dung_khong_du"
              text="Thực hiện không đúng/không đầy đủ"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <label>Loại hình quan trắc</label>
            <Multiselect
              :placeholder="'Loại hình quan trắc'"
              :options="optionLoaiHinhQuanTracs"
              label="ten"
              track-by="id"
              v-model="intermediate_data.loai_hinh_quan_trac"
              :searchable="true"
              :clear-on-select="false"
              :close-on-select="true"
              :options-limit="300"
              :limit="3"
              :disabled="!canEdit"
              :show-no-results="false"
              :hide-selected="false"
              :tabindex="1"
              selectLabel="Nhấn enter để chọn"
              deselectLabel="Nhấn enter để bỏ chọn"
              selectedLabel="Đã chọn"
            >
              <span slot="noResult">Không tìm thấy kết quả</span>
            </Multiselect>
          </div>
          <div class="col-lg-4">
            <label class="control-label"
              >Giấy xác nhận hoàn thành công trình BVMT</label
            >
            <hr />
            <checkbox-component
              v-model="intermediate_data.khong_thuc_hien_giay_xac_nhan"
              type="checkbox"
              id="khong_thuc_hien_giay_xac_nhan"
              text="Không thực hiện"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <checkbox-component
              v-model="intermediate_data.thuc_hien_sai_giay_xac_nhan"
              type="checkbox"
              id="thuc_hien_sai_giay_xac_nhan"
              text="Thực hiện không đúng"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
          </div>
          <div class="col-lg-4"></div>
        </div>
      </div>
    </div>
    <div class="row block-multiple-rows" v-show="intermediate_data.vi_pham">
      <div class="col-lg-12 col-md-12">
        <label class="control-label">Nhóm vi phạm xả thải</label>
        <checkbox-component
          v-model="intermediate_data.vi_pham_xa_thai"
          type="checkbox"
          id="vi_pham_xa_thai"
          text="Vi phạm xả thải"
          @change="change"
          :disabled="!canEdit"
        ></checkbox-component>
      </div>
      <div class="col-lg-12" v-show="intermediate_data.vi_pham_xa_thai">
        <hr />
        <div class="row">
          <div class="col-lg-6">
            <viPhamXaThaiNuocThais
              @change="change"
              :is_loading="is_loading"
              v-model="intermediate_data"
              :canEdit="canEdit"
              :optionPhatTangThemNuocThais="optionPhatTangThemNuocThais"
              :nuocThaiKhoans="nuocThaiKhoans"
              :donviThongSoVuotChuan="donviThongSoVuotChuan"
              :danhMucThongSoVuotChuanNuocThais="
                danhMucThongSoVuotChuanNuocThais
              "
              :coSoSanXuats="coSoSanXuats"
            />
          </div>
          <div class="col-lg-6">
            <viPhamXaThaiKhiThais
              @change="change"
              :is_loading="is_loading"
              v-model="intermediate_data"
              :canEdit="canEdit"
              :optionPhatTangThemKhiThais="optionPhatTangThemKhiThais"
              :khiThaiKhoans="khiThaiKhoans"
              :donviThongSoVuotChuan="donviThongSoVuotChuan"
              :danhMucThongSoVuotChuanKhiThais="danhMucThongSoVuotChuanKhiThais"
              :coSoSanXuats="coSoSanXuats"
            />
          </div>
        </div>

        <div class="row">
          <div class="col-lg-4">
            <label class="control-label">CTRSH</label>
            <hr />
            <checkbox-component
              v-model="intermediate_data.ctrsh_co_thu_gom"
              type="checkbox"
              id="ctrsh_co_thu_gom"
              text="Không thu gom"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <checkbox-component
              v-model="intermediate_data.ctrsh_co_phan_loai"
              type="checkbox"
              id="ctrsh_co_phan_loai"
              text="Không phân loại"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <checkbox-component
              v-model="intermediate_data.ctrsh_co_luu_tru"
              type="checkbox"
              id="ctrsh_co_luu_tru"
              text="Không lưu giữ"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <checkbox-component
              v-model="intermediate_data.ctrsh_co_chuyen_giao"
              type="checkbox"
              id="ctrsh_co_chuyen_giao"
              text="Không chuyển giao"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
          </div>
          <div class="col-lg-4">
            <label class="control-label">CTRCN</label>
            <hr />
            <checkbox-component
              v-model="intermediate_data.ctrcn_co_thu_gom"
              type="checkbox"
              id="ctrcn_co_thu_gom"
              text="Không thu gom"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <checkbox-component
              v-model="intermediate_data.ctrcn_co_phan_loai"
              type="checkbox"
              id="ctrcn_co_phan_loai"
              text="Không phân loại"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <checkbox-component
              v-model="intermediate_data.ctrcn_co_luu_tru"
              type="checkbox"
              id="ctrcn_co_luu_tru"
              text="Không lưu giữ"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <checkbox-component
              v-model="intermediate_data.ctrcn_co_chuyen_giao"
              type="checkbox"
              id="ctrcn_co_chuyen_giao"
              text="Không chuyển giao"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
          </div>
          <div class="col-lg-4">
            <label class="control-label">CTNH</label>
            <hr />
            <checkbox-component
              v-model="intermediate_data.ctrnh_vi_pham_chung_tu"
              type="checkbox"
              id="ctrnh_vi_pham_chung_tu"
              text="Không báo cáo quản lý chất thải nguy hại"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <checkbox-component
              v-model="intermediate_data.ctrnh_co_thu_gom"
              type="checkbox"
              id="ctrnh_co_thu_gom"
              text="Không thu gom"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <checkbox-component
              v-model="intermediate_data.ctrnh_co_phan_loai"
              type="checkbox"
              id="ctrnh_co_phan_loai"
              text="Không phân loại"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <checkbox-component
              v-model="intermediate_data.ctrnh_co_luu_tru"
              type="checkbox"
              id="ctrnh_co_luu_tru"
              text="Không lưu giữ"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <checkbox-component
              v-model="intermediate_data.ctrnh_co_de_lan"
              type="checkbox"
              id="ctrnh_co_de_lan"
              text="Để lẫn"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <checkbox-component
              v-model="intermediate_data.ctrnh_co_chuyen_giao"
              type="checkbox"
              id="ctrnh_co_chuyen_giao"
              text="Không chuyển giao"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <checkbox-component
              v-model="intermediate_data.ctrnh_co_chon_lap"
              type="checkbox"
              id="ctrnh_co_chon_lap"
              text="Không chôn lấp"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>

            <checkbox-component
              v-model="intermediate_data.ctrnh_co_do_thai"
              type="checkbox"
              id="ctrnh_co_do_thai"
              text="Đổ thải"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
            <checkbox-component
              v-model="intermediate_data.ctrnh_co_giay_phep"
              type="checkbox"
              id="ctrnh_co_giay_phep"
              text="Không giấy phép"
              @change="change"
              :disabled="!canEdit"
            ></checkbox-component>
          </div>
        </div>
      </div>
    </div>
    <div class="row block-multiple-rows" v-show="intermediate_data.vi_pham">
      <div class="col-lg-12">
        <label class="control-label">Nhóm hành vi khác</label>
        <checkbox-component
          v-model="intermediate_data.nhom_hanh_vi_khac"
          type="checkbox"
          id="nhom_hanh_vi_khac"
          text="Vi phạm nhóm hành vi khác"
          @change="change"
          :disabled="!canEdit"
        ></checkbox-component>
        <div class="col-lg-12" v-show="intermediate_data.nhom_hanh_vi_khac">
          <hr />
          <checkbox-component
            v-model="
              intermediate_data.vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong
            "
            type="checkbox"
            id="vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong"
            text="Vi phạm quy định về thu thập, sử dụng thông tin MT"
            @change="change"
            :disabled="!canEdit"
          ></checkbox-component>
          <checkbox-component
            v-model="intermediate_data.vi_pham_cac_quy_dinh_bao_ve_moi_truong"
            type="checkbox"
            id="vi_pham_cac_quy_dinh_bao_ve_moi_truong"
            text="Vi phạm về các quy định BVMT"
            @change="change"
            :disabled="!canEdit"
          ></checkbox-component>
          <checkbox-component
            v-model="intermediate_data.co_hanh_vi_can_tro_ve_bvmt"
            type="checkbox"
            id="co_hanh_vi_can_tro_ve_bvmt"
            text="Hành vi cản trở về BVMT"
            @change="change"
            :disabled="!canEdit"
          ></checkbox-component>
        </div>
      </div>
    </div>
  </span>
</template>
<script>
import * as env from "../env.js";
import Multiselect from "vue-multiselect";
import viPhamXaThaiKhiThais from "./viPhamXaThai/viPhamXaThaiKhiThais.vue";
import viPhamXaThaiNuocThais from "./viPhamXaThai/viPhamXaThaiNuocThais.vue";
export default {
  props: ["usersystem", "inputData", "donviThongSoVuotChuan", "coSoSanXuats"],
  components: { Multiselect, viPhamXaThaiKhiThais, viPhamXaThaiNuocThais },
  data: function () {
    return {
      is_loading: false,
      dieus: [],
      data_vi_pham: {
        nuoc_thai_khoan_vi_pham: null,
        nuoc_thai_muc_vi_pham: null,
        khi_thai_khoan_vi_pham: null,
        khi_thai_muc_vi_pham: null,
      },
      item_vi_pham_nuoc_thai: {
        thong_so_vuot_chuan_nuoc_thai: null,
        ket_qua_thong_so_vuot_chuan_nuoc_thai: null,
        don_vi_thong_so_vuot_chuan_nuoc_thai: null,
        so_lan_vuot_chuan_nuoc_thai: null,
        phat_tang_them_nuoc_thais: null,
      },
      item_vi_pham_khi_thai: {
        thong_so_vuot_chuan_khi_thai: null,
        ket_qua_thong_so_vuot_chuan_khi_thai: null,
        don_vi_thong_so_vuot_chuan_khi_thai: null,
        so_lan_vuot_chuan_khi_thai: null,
        phat_tang_them_khi_thais: null,
      },
      intermediate_data: {
        thong_so_vuot_chuan_khi_thai: [],
        don_vi_thong_so_vuot_chuan_khi_thai: [],
        thong_so_vuot_chuan_nuoc_thai: [],
        don_vi_thong_so_vuot_chuan_nuoc_thai: [],
        vi_pham: false,
        vi_pham_thu_tuc_hanh_chinh: false,
        khong_co_bao_cao_dtm: false,
        vi_pham_xa_thai: false,
        dtmdakhbvmt_thuc_hien_khong_dung_noi_dung: false,
        dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung: false,
        co_ket_hoach_quan_ly_moi_truong: false,
        khong_xay_lap: false,
        xay_lap_khong_dung: false,
        khong_co_giay_xac_nhan_hoan_thanh: false,
        khong_thuc_hien_giay_xac_nhan: false,
        van_hanh_khong_dung_khong_thuong_xuyen: false,
        gsmt_thuc_hien: false,
        gsmt_khong_thuc_hien: false,
        gsmt_thuc_hien_khong_dung_khong_du: false,
        thuc_hien_sai_giay_xac_nhan: false,
        loai_hinh_quan_trac: null,
        co_he_thong_thu_gom_nuoc_thai_rieng_biet: true,
        phat_tang_them_nuoc_thais: [],
        phat_tang_them_khi_thais: [],
        thong_so_vuot_chuan: null,
        co_bien_phap_xu_ly_khi_thai: true,
        khi_thai_vuot: false,
        ctrsh_co_thu_gom: false,
        ctrsh_co_phan_loai: false,
        ctrsh_co_luu_tru: false,
        ctrsh_co_chuyen_giao: false,
        ctrcn_co_thu_gom: false,
        ctrcn_co_phan_loai: false,
        ctrcn_co_luu_tru: false,
        ctrcn_co_chuyen_giao: false,
        ctrnh_vi_pham_chung_tu: false,
        ctrnh_co_thu_gom: false,
        ctrnh_co_phan_loai: false,
        ctrnh_co_luu_tru: false,
        ctrnh_co_de_lan: false,
        ctrnh_co_chuyen_giao: false,
        ctrnh_co_chon_lap: false,
        ctrnh_co_do_thai: false,
        ctrnh_co_giay_phep: false,
        nhom_hanh_vi_khac: false,
        vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong: false,
        vi_pham_cac_quy_dinh_bao_ve_moi_truong: false,
        co_hanh_vi_can_tro_ve_bvmt: false,
        khi_thai_khoan_vi_pham_id: undefined,
        khi_thai_muc_vi_pham_id: undefined,
        nuoc_thai_khoan_vi_pham_id: undefined,
        nuoc_thai_muc_vi_pham_id: undefined,
      },
      optionPhattangthemNuocThais: [],
      optionPhattangthemKhiThais: [],
      errors_phat_tang_them: null,
      khiThaiKhoans: [],
      nuocThaiKhoans: [],
      optionPhatTangThemNuocThais: [],
      optionPhatTangThemKhiThais: [],
      optionLoaiHinhQuanTracs: [
        {
          id: "dinh_ky",
          ten: "Định kỳ",
        },
        {
          id: "tu_dong",
          ten: "Tự động",
        },
      ],
      danhMucThongSoVuotChuanNuocThais: [],
      danhMucThongSoVuotChuanKhiThais: [],
      viPhamNuocThai: [],
      viPhamKhiThai: [],
      err: {
        viPhamNuocThai: false,
        viPhamKhiThai: false,
      },
    };
  },
  methods: {
    updateDataViPham() {
      setTimeout(() => {
        Object.keys(this.data_vi_pham).forEach((key) => {
          if (this.data_vi_pham[key])
            this.intermediate_data[`${key}_id`] = this.data_vi_pham[key]
              ? this.data_vi_pham[key].id
              : undefined;
        });
        this.change();
      });
    },
    change: function () {
      this.inputData[0] = this.intermediate_data;
    },
    initData: function (value) {
      if (value.length > 0) {
        this.intermediate_data = value[0];
      } else {
        this.intermediate_data = this.$options.data.call(
          this
        ).intermediate_data;
      }
      this.setDieuViPham();
    },
    setDieuViPham() {
      let temp = this.intermediate_data;
      if (
        !this.data_vi_pham.nuoc_thai_khoan_vi_pham &&
        temp.nuoc_thai_khoan_vi_pham_id
      ) {
        let nuoc_thai_khoan_vi_pham = this.nuocThaiKhoans.find(
          (x) => x.id == temp.nuoc_thai_khoan_vi_pham_id
        );
        this.data_vi_pham.nuoc_thai_khoan_vi_pham = nuoc_thai_khoan_vi_pham;
        if (nuoc_thai_khoan_vi_pham)
          this.data_vi_pham.nuoc_thai_muc_vi_pham = nuoc_thai_khoan_vi_pham.mucs.find(
            (x) => x.id == temp.nuoc_thai_muc_vi_pham_id
          );
      }
      if (
        !this.data_vi_pham.khi_thai_khoan_vi_pham &&
        temp.khi_thai_khoan_vi_pham_id
      ) {
        let khi_thai_khoan_vi_pham = this.khiThaiKhoans.find(
          (x) => x.id == temp.khi_thai_khoan_vi_pham_id
        );
        this.data_vi_pham.khi_thai_khoan_vi_pham = khi_thai_khoan_vi_pham;
        if (khi_thai_khoan_vi_pham)
          this.data_vi_pham.khi_thai_muc_vi_pham = khi_thai_khoan_vi_pham.mucs.find(
            (x) => x.id == temp.khi_thai_muc_vi_pham_id
          );
      }
    },
    async getDieus() {
      this.is_loading = true;
      let response = await axios.get(env.endpointhttp + "dieus");
      this.dieus = response.data.result;
      this.setDieuViPham();
      let dieuNuoc = this.dieus.find((x) => x.ten.includes("Điều 13"));
      this.nuocThaiKhoans = dieuNuoc ? dieuNuoc.khoans.slice(0, -1) : [];
      let dieuKhi = this.dieus.find((x) => x.ten.includes("Điều 15"));
      this.khiThaiKhoans = dieuKhi ? dieuKhi.khoans.slice(0, -1) : [];
    },
    addNuocThai() {
      this.err.viPhamNuocThai = false;
      let item = {
        thong_so: this.item_vi_pham_nuoc_thai.thong_so_vuot_chuan_nuoc_thai,
        ket_qua: this.item_vi_pham_nuoc_thai
          .ket_qua_thong_so_vuot_chuan_nuoc_thai,
        don_vi: this.item_vi_pham_nuoc_thai
          .don_vi_thong_so_vuot_chuan_nuoc_thai,
        so_lan_vuot: this.item_vi_pham_nuoc_thai.so_lan_vuot_chuan_nuoc_thai,
        phat_tang_them: this.item_vi_pham_nuoc_thai.phat_tang_them_nuoc_thais,
      };
      this.intermediate_data["phat_tang_them_nuoc_thais"].push(item);
    },
    addKhiThai() {
      this.err.viPhamKhiThai = false;
      let item = {
        thong_so: this.item_vi_pham_khi_thai.thong_so_vuot_chuan_khi_thai,
        ket_qua: this.item_vi_pham_khi_thai
          .ket_qua_thong_so_vuot_chuan_khi_thai,
        don_vi: this.item_vi_pham_khi_thai.don_vi_thong_so_vuot_chuan_khi_thai,
        so_lan_vuot: this.item_vi_pham_khi_thai.so_lan_vuot_chuan_khi_thai,
        phat_tang_them: this.item_vi_pham_khi_thai.phat_tang_them_khi_thais,
      };

      this.intermediate_data["phat_tang_them_khi_thais"].push(item);
    },
    deleteItemArr(arr, index) {
      arr.splice(index, 1);
    },
  },
  watch: {
    inputData: "initData",
  },
  computed: {
    nuocThaiMucViPham() {
      return this.data_vi_pham.nuoc_thai_khoan_vi_pham
        ? this.data_vi_pham.nuoc_thai_khoan_vi_pham.mucs
        : [];
    },
    canEdit: function () {
      if (this.usersystem)
        return (
          this.usersystem.role_code == "admin" ||
          this.usersystem.role_code == "nhanvientrungtam" ||
          this.usersystem.role_code == "adminvaquanlydanhmuc"
        );
      else return false;
    },
  },
  mounted() {
    let vm = this;
  },
  created() {
    this.getDieus();

    axios.get(env.endpointhttp + "phattangthems").then((response) => {
      this.optionPhatTangThemNuocThais = response.data.result.filter(
        (item) => item.type == "nuoc_thai"
      );
      this.optionPhatTangThemKhiThais = response.data.result.filter(
        (item) => item.type == "khi_thai"
      );
    });
    axios
      .get(env.endpointhttp + "danhmuc/thongsovuotchuan")
      .then((response) => {
        this.danhMucThongSoVuotChuanNuocThais = response.data.result.filter(
          (item) => item.type == "nuoc_thai"
        );
        this.danhMucThongSoVuotChuanKhiThais = response.data.result.filter(
          (item) => item.type == "khi_thai"
        );
      });
  },
};
</script>
<style scoped>
/deep/ .multiselect__single {
  overflow: hidden;
  text-overflow: ellipsis;
  width: 100%;
  white-space: nowrap;
}
.flex-box {
  display: flex;
}
.item-box {
  flex: 2;
  margin: 0 10px;
}
.item-box.button {
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  position: relative;
}
.item-box.button .tooltiptext {
  width: 250px;
  background-color: #616161e6;
  color: #fff;
  text-align: center;
  padding: 5px 0;
  border-radius: 6px;
  position: absolute;
  z-index: 1;
  top: 100%;
  left: -50%;
  transform: translate(-5%, 4px);
}
.item-box.button button {
  height: 42px;
}
.text_overflow {
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 80px;
  white-space: nowrap;
}
.err {
  animation: shake 0.82s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
  transform: translate3d(0, 0, 0);
  backface-visibility: hidden;
  perspective: 1000px;
}

@keyframes shake {
  10%,
  90% {
    transform: translate3d(-1px, 0, 0);
  }

  20%,
  80% {
    transform: translate3d(2px, 0, 0);
  }

  30%,
  50%,
  70% {
    transform: translate3d(-4px, 0, 0);
  }

  40%,
  60% {
    transform: translate3d(4px, 0, 0);
  }
}
</style>
