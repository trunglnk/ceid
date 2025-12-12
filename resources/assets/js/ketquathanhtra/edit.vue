<template>
  <div class="row" id="kqtt_wrap">
    <div class="col-lg-12 col-md-12">
      <div class="row">
        <div class="col-md-6">
          <h2 class="page-header-button-header title_master_form">
            Kết quả thanh tra
          </h2>
        </div>
        <div class="col-md-6">
          <button
            type="submit"
            id="onsubmit"
            class="btn bg-olive btn-flat pull-right margin-top margin-left"
            :disabled="is_loading || !is_valid"
            tabindex="22"
            @click="update()"
            v-if="isHaveAccess"
          >
            <i class="fa fa-check"></i> Cập nhật
          </button>
          <button
            type="submit"
            :disabled="!validateMaDinhDanh"
            id="onsubmit"
            class="btn bg-olive btn-flat pull-right margin-top margin-left"
            @click="sendDataMTQG"
          >
            <i class="fa fa-send"></i> Gửi lên MTQG
          </button>
          <a
            :href="
              '/cososanxuat/showformupdate/' + this.kqthanhtra.organization.id
            "
            class="btn btn-default btn-flat pull-right margin-top margin-left"
            target="_blank"
          >
            <i class="fa fa-map-marker"></i>&nbsp;Thông tin cơ sở thanh tra
          </a>
          <a
            href="/cososanxuat"
            class="btn btn-default btn-flat pull-right margin-top"
          >
            <i class="fa fa-undo"></i> Trở lại
          </a>
          <!-- Countdown Display -->
          <button
            type="button"
            class="btn btn-flat pull-right margin-top margin-right"
            :class="countdownClass"
            @click="restartCountdown"
          >
            Lưu tạm sau {{ countdown }}s
          </button>
        </div>
      </div>
      <div class="row block-multiple-rows">
        <div class="col-lg-12" style="margin-top: 5px">
          <label>THÔNG TIN CHUNG</label>
          <div style="float: right">
            <div v-if="kqthanhtra.trang_thai_dong_bo === 'da_dong_bo'">
              Đã đồng bộ lên MTQG lúc:
              {{
                thoi_gian_dong_bo ? thoi_gian_dong_bo : thoi_gian_dong_bo_chung
              }}
            </div>
            <div v-else>Chưa đồng bộ lên MTQG</div>
          </div>
          <hr style="margin-top: 7px; margin-bottom: 7px" />
        </div>
        <div class="col-lg-12 col-md-12" style="margin-bottom: 15px">
          <label>Mã định danh: </label>
          {{ kqthanhtra.ma_dinh_danh }}
          <span
            v-if="!validateMaDinhDanh"
            style="padding-left: 20px; color: red; font-style: italic"
            >Mã định danh không hợp lệ, không thể gửi dữ liệu lên MTQG</span
          >
        </div>
        <div class="col-lg-2 col-md-3">
          <label>Quyết định thanh tra</label>
          <multiselect
            :disabled="false"
            v-model="form_data.quyet_dinh_thanh_tra"
            id="ajax"
            label="so_quyet_dinh"
            track-by="id"
            placeholder="Gõ số quyết định"
            selectLabel="Nhấn enter để chọn"
            deselectLabel="Nhấn enter để bỏ chọn"
            selectedLabel="Đã chọn"
            open-direction="bottom"
            :options="this.quyetdinhthanhtras"
            :multiple="false"
            :searchable="true"
            :loading="is_loading"
            :internal-search="false"
            :clear-on-select="false"
            :close-on-select="true"
            :options-limit="300"
            :limit="3"
            :limit-text="limitText"
            :max-height="600"
            :show-no-results="false"
            :hide-selected="false"
            :tabindex="1"
            :clearOnSelect="true"
            @search-change="asyncFindQuyetDinh"
          >
            <span slot="noResult">Không tìm thấy kết quả</span>
          </multiselect>
          <span style="color: red">{{ form_data.error_quyet_dinh }}</span>
        </div>
        <div class="col-lg-2 col-md-3">
          <label>Số kết luận<span style="color: red">*</span></label>
          <input
            class="form-control"
            type="text"
            v-model="form_data.so_quyet_dinh"
          />
          <span style="color: red">{{ form_data.error_so_quyet_dinh }}</span>
        </div>
        <div class="col-lg-2 col-md-3">
          <label>Ngày kết luận</label>
          <date-picker
            v-model="form_data.ngay_thanh_tra"
            placeholder="Ngày xác nhận"
            tabindex="17"
            :config="optionsDate"
            :readonly="!isHaveAccess"
          ></date-picker>
        </div>
        <div class="col-lg-6 col-md-3">
          <label>Tên</label>
          <input
            class="form-control"
            type="text"
            v-model="kqthanhtra.organization.ten"
          />
        </div>
        <div class="col-lg-12 col-md-12">
          <label>Địa chỉ liên hệ</label>
          <input
            class="form-control"
            type="text"
            v-model="kqthanhtra.organization.dia_chi_lien_he"
          />
        </div>
      </div>
      <div class="row block-multiple-rows">
        <div class="col-lg-12" style="margin-top: 5px">
          <span class="mr-3" title="Xem chi tiết">
            <i
              class="fa fa-bars"
              style="cursor: pointer"
              @click="goChuDauTu(chuDauTu.id)"
            ></i
          ></span>
          <label
            style="cursor: pointer; text-decoration: underline"
            @click="goDanhSachCDT"
            >CHỦ ĐẦU TƯ</label
          >
          <hr style="margin-top: 7px; margin-bottom: 7px" />
        </div>
        <div class="col-lg-12">
          <div class="col-lg-6">
            <div v-if="!showEditChuDauTu && chuDauTu">
              <b
                @click="goChuDauTu(chuDauTu.id)"
                style="text-decoration: underline; cursor: pointer"
                >{{ chuDauTu.ten }}</b
              >
              <!-- <span class="ml-3" title="Chỉnh sửa">
                <i class="fa fa-edit" style="cursor: pointer" @click="changeChuDauTu"></i></span> -->
            </div>
            <div style="display: flex" v-else>
              <multiselect
                v-model="chuDauTu"
                label="ten"
                track-by="id"
                placeholder="Gõ tên chủ đầu tư"
                selectLabel="Nhấn enter để chọn"
                deselectLabel="Nhấn enter để bỏ chọn"
                selectedLabel="Đã chọn"
                open-direction="bottom"
                :options="chuDauTus"
                :multiple="false"
                :searchable="true"
                :loading="is_loading_cdt"
                :internal-search="true"
                :clear-on-select="false"
                :close-on-select="true"
                :options-limit="300"
                :limit="3"
                :max-height="600"
                :show-no-results="false"
                :hide-selected="false"
                :clearOnSelect="true"
                @search-change="asyncFindChuDauTu"
              >
                <span slot="noResult">Không tìm thấy thông tin</span>
              </multiselect>
              <button
                type="submit"
                id="onsubmit"
                class="btn bg-olive btn-flat pull-right"
                @click="goAddChuDauTu"
              >
                <i class="fa fa-plus"></i> Thêm chủ đầu tư
              </button>
            </div>
          </div>
        </div>
        <br />
        <div class="col-lg-12" v-if="chuDauTu">
          <div class="col-lg-6">
            <div class="pt-2">
              Mã định danh:
              <b>{{ chuDauTu.ma_dinh_danh }}</b>
            </div>
            <div class="pt-2">
              Tỉnh thành:
              {{ chuDauTu.tinh_thanh ? chuDauTu.tinh_thanh.ten : "" }}
            </div>
          </div>
          <div class="col-lg-3">
            <div>
              Giấy đăng ký KD:
              {{ chuDauTu.so_giay_chung_nhan_dang_ky_kinh_doanh }}
            </div>
            <div class="pt-2">
              Quận Huyện:
              {{ chuDauTu.quan_huyen ? chuDauTu.quan_huyen.ten : "" }}
            </div>
          </div>
          <div class="col-lg-3">
            <div>Địa chỉ: {{ chuDauTu.dia_chi }}</div>
            <div class="pt-2">
              Xã phường:
              {{ chuDauTu.xa_phuong }}
            </div>
          </div>
        </div>
      </div>

      <template v-if="isHaveAccess">
        <div
          class="row block-multiple-rows"
          v-if="form_data.co_so_san_xuats.length > 0"
        >
          <div class="col-lg-8 col-md-12" style="margin-top: 5px">
            <p class="text-muted">
              Nếu cơ sở thanh tra có địa điểm hoạt động mới, nhấn nút thêm mới
              địa điểm hoạt động
            </p>
          </div>
          <div class="col-lg-4 col-md-12" style="margin-top: 5px">
            <button
              type="submit"
              class="btn btn-default btn-flat pull-right"
              tabindex="22"
              @click="addDiaDiemHoatDong()"
            >
              <i class="fa fa-plus"></i> Thêm mới địa điểm hoạt động
            </button>
          </div>
        </div>
      </template>
      <div
        class="nav-tabs-custom margin-top"
        v-if="form_data.co_so_san_xuats.length > 1"
      >
        <ul class="nav nav-tabs">
          <li
            v-for="(co_so_san_xuat, index) in form_data.co_so_san_xuats"
            :key="co_so_san_xuat.id"
            :class="{ active: currentTab == co_so_san_xuat.id }"
            @click="currentTab = co_so_san_xuat.id"
          >
            <a data-toggle="tab" aria-expanded="true" href="#">{{
              `Địa điểm hoạt động số ${parseInt(index) + 1}`
            }}</a>
          </li>
        </ul>
      </div>
      <div
        v-for="(co_so_san_xuat, index1) in form_data.co_so_san_xuats"
        :key="co_so_san_xuat.id"
        v-show="currentTab == co_so_san_xuat.id"
      >
        <diaDiemHoatDong
          :value="co_so_san_xuat"
          @input="form_data.co_so_san_xuats[index1] = $event"
          :usersystem="usersystem"
          :donvicongsuatthietkes="donvicongsuatthietkes"
          :donvicongsuathoatdongs="donvicongsuathoatdongs"
          :tinhs="tinhs"
          :huyens="huyens"
          :optionsDate="optionsDate"
          :coquantochucs="coquantochucs"
          :donvidientich="donvidientich"
          :donvinuoc="donvinuoc"
          :loai_hinh_co_so_tree_listing="loai_hinh_co_so_tree_listing"
          @click-delete="deleteDiaChiHoatDong(index1)"
          :readonly="!isHaveAccess"
        />
        <div class="row block-multiple-rows">
          <ketQuaThanhTraChatThai
            :value="co_so_san_xuat"
            @input="form_data.co_so_san_xuats[index1] = $event"
            :index1="index1"
            :usersystem="usersystem"
            @click:refreshDVCTNH="refreshDVCTNH"
            :donvikhoiluongphatsinhs="donvikhoiluongphatsinhs"
            :ten_don_vi_ctrsh="ten_don_vi_ctrsh"
            @updateFormNuocThai="updateFormNuocThai"
            @updateFormKhiThai="updateFormKhiThai"
            @updateFormCtrsh="updateFormCtrsh"
            @updateFormCtrCntt="updateFormCtrCntt"
            @updateFormCtnh="updateFormCtnh"
            :skipInitFromApi="hasTempStorage"
          />
          <ketQuaThanhTraThuTucHanhChinh
            :value="co_so_san_xuat"
            @input="form_data.co_so_san_xuats[index1] = $event"
            :index1="index1"
            :usersystem="usersystem"
            :coquantochucs="coquantochucs"
            :optionsDate="optionsDate"
          />
        </div>
        <KetQuaPhanTichMoiTruongEdit
          :inputData.sync="
            form_data.co_so_san_xuats[index1].ket_qua_phan_tich_moi_truong
          "
        />
      </div>
      <div class="row block-multiple-rows">
        <div class="col-lg-12" style="margin-top: 5px">
          <label>Kết luận thanh tra, Quyết định xử phạt</label>
          <p class="text-muted">
            (Click vào từng mục kết luận thanh tra, quyết định xử phạt và khắc
            phục vi phạm để xem và nhập thông tin kết luận thanh tra)
          </p>
          <hr style="margin-top: 7px; margin-bottom: 7px" />
        </div>
        <div class="col-md-12">
          <div class="nav-tabs-custom margin-top">
            <ul class="nav nav-tabs">
              <li class="active">
                <a
                  href="#tab_ketluansauthanhtra"
                  data-toggle="tab"
                  aria-expanded="true"
                  >Kết luận thanh tra</a
                >
              </li>
              <li>
                <a
                  href="#tab_quyetdinhxuphat"
                  data-toggle="tab"
                  aria-expanded="false"
                  >Quyết định xử phạt</a
                >
              </li>
              <li>
                <a
                  href="#tab_ketquakhacphuchauquavipham"
                  data-toggle="tab"
                  aria-expanded="false"
                  >Kết quả khắc phục hậu quả vi phạm</a
                >
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_ketluansauthanhtra">
                <NhomHanhViViPham
                  :usersystem="usersystem"
                  :coSoSanXuats="form_data.co_so_san_xuats"
                  @checkValid="
                    (value) => {
                      is_valid = value;
                    }
                  "
                  :inputData.sync="form_data.nhom_hanh_vi_vi_phams"
                  :donviThongSoVuotChuan="donviThongSoVuotChuan"
                >
                </NhomHanhViViPham>
              </div>
              <div class="tab-pane" id="tab_quyetdinhxuphat">
                <QuyetDinhXuPhat
                  :usersystem="usersystem"
                  :inputData.sync="form_data.danh_sach_quyet_dinh_xu_phat"
                >
                </QuyetDinhXuPhat>
              </div>
              <div class="tab-pane" id="tab_ketquakhacphuchauquavipham">
                <KetQuaKhacPhucHauQuaViPham
                  :usersystem="usersystem"
                  :inputData.sync="
                    form_data.danh_sach_ket_qua_khac_phuc_vi_pham
                  "
                ></KetQuaKhacPhucHauQuaViPham>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row block-multiple-rows">
        <div class="col-lg-12">
          <label>Những nội dung đã thực hiện</label>
          <textarea
            v-model="form_data.mo_ta"
            type="text"
            class="form-control"
            placeholder="Nhập nội dung đã thực hiện"
            :readonly="!isHaveAccess"
          ></textarea>
        </div>
        <div class="col-lg-12">
          <label>Những nội dung chưa thực hiện</label>
          <textarea
            v-model="form_data.noi_dung_chua_thuc_hien"
            type="text"
            class="form-control"
            placeholder="Nhập nội dung chưa thực hiện"
            :readonly="!isHaveAccess"
          ></textarea>
        </div>
      </div>
      <div class="row block-multiple-rows">
        <div class="col-lg-12" style="margin-top: 5px">
          <label>TÀI LIỆU ĐÍNH KÈM</label>
          <hr style="margin-top: 7px; margin-bottom: 7px" />
          <index-file-kqtt
            :usersystem="usersystem"
            :value="kqthanhtra.id"
            @files="getFile($event)"
            @analyze="analyzePdf($event)"
          ></index-file-kqtt>
        </div>
      </div>
      <div class="box-footer box-footer-form">
        <button
          type="submit"
          id="onsubmit"
          class="btn bg-olive btn-flat pull-right"
          :disabled="is_loading || !is_valid"
          tabindex="22"
          @click="update()"
          v-if="isHaveAccess"
        >
          <i class="fa fa-check"></i> Cập nhật
        </button>
        <a
          :href="'/cososanxuat/showformupdate/' + kqthanhtra.organization.id"
          class="btn btn-default btn-flat pull-right margin-right"
          target="_blank"
        >
          <i class="fa fa-map-marker"></i>&nbsp;Thông tin cơ sở thanh tra
        </a>
        <a
          :href="'/ketquathanhtra/excel/' + kqthanhtra.id"
          class="btn btn-default btn-flat pull-right margin-right"
          tabindex="1"
        >
          <i class="fa fa-file-excel-o"></i> Xuất Excel
        </a>
        <a
          href="/cososanxuat"
          id="btnback"
          class="btn btn-default btn-flat"
          tabindex="31"
        >
          <i class="fa fa-undo"></i> Trở lại
        </a>
        <button
          type="button"
          class="btn btn-danger btn-flat"
          @click="showModel = true"
          v-if="isHaveAccess"
        >
          <i class="fa fa-trash"></i> Xóa
        </button>
        <modal-component
          width="450px"
          v-model="showModel"
          center
          @submit="deleteKetQuaThanhTra"
        >
          <div class="text-center">Bạn có muốn xoá mục này</div>
        </modal-component>
      </div>
    </div>
    <confirm-dialog
      width="450px"
      v-model="showAnalysisModal"
      title="Trích xuất tài liệu PDF"
      center
      :buttons="modalButtons"
      @preview="onPreview"
      @confirm="onConfirm"
    >
      <div v-if="isProcessing" class="processing-section">
        <div class="spinner"></div>
        <h2>Đang phân tích tài liệu...</h2>
        <p>{{ (fileSelect && fileSelect.name) || "" }}</p>
        <p>Quá trình này có thể mất vài phút, vui lòng chờ.</p>
      </div>
      <div v-else class="text-center">
        {{ modalText }}
      </div>
      <div v-if="error" class="text-center">
        {{ error }}
      </div>
    </confirm-dialog>
    <VueDraggableResizable
      v-if="analysisResult"
      class="draggable-box"
      :w="450"
      :h="600"
      :min-width="350"
      :min-height="400"
      :max-width="900"
      :max-height="1200"
      :x="0"
      :y="0"
      :active="true"
      :parent="true"
      :drag-handle="'.draggable-header'"
      :drag-cancel="'.draggable-content-wrapper, .close-btn'"
    >
      <div class="draggable-header">
        <h5 class="header-title">
          {{ (fileSelect && fileSelect.name) || "" }}
        </h5>
        <button @click="resetState" class="close-btn" title="Đóng">
          &times;
        </button>
      </div>
      <div class="draggable-content-wrapper">
        <AnalysisResult :isShowContent="false" :result="analysisResult" />
      </div>
    </VueDraggableResizable>
  </div>
</template>

<script>
import * as env from "../env.js";
import moment from "../../../../node_modules/admin-lte/bower_components/moment/moment.js";
import datePicker from "vue-bootstrap-datetimepicker";
import { en, vi } from "vuejs-datepicker/dist/locale";
import Multiselect from "vue-multiselect";
import MessageService from "../services/MessageService";
import KetQuaKhacPhucHauQuaViPham from "./ketQuaKhacPhucHauQuaViPham.vue";
import QuyetDinhXuPhat from "./quyetdinhxuphat/quyetDinhXuPhat.vue";
import NhomHanhViViPham from "./indexNhomHanhViViPham.vue";
import diaDiemHoatDong from "./diaDiemHoatDong.vue";
Vue.filter("formatDate", function (value) {
  if (value) {
    return moment(String(value)).format("DD/MM/YYYY");
  }
});
import ketQuaThanhTraChatThai from "./diaDiemHoatDong/ketQuaThanhTraChatThai.vue";
import ketQuaThanhTraThuTucHanhChinh from "./diaDiemHoatDong/ketQuaThanhTraThuTucHanhChinh.vue";
import KetQuaPhanTichMoiTruongEdit from "./ketQuaPhanTichMoiTruongEdit.vue";
import VueDraggableResizable from "vue-draggable-resizable";
import "vue-draggable-resizable/dist/VueDraggableResizable.css";
import AnalysisResult from "../pdfanalysis/components/AnalysisResult.vue";

export default {
  components: {
    ketQuaThanhTraChatThai,
    ketQuaThanhTraThuTucHanhChinh,
    Multiselect,
    datePicker,
    KetQuaKhacPhucHauQuaViPham,
    QuyetDinhXuPhat,
    NhomHanhViViPham,
    diaDiemHoatDong,
    AnalysisResult,
    VueDraggableResizable,
    KetQuaPhanTichMoiTruongEdit,
  },
  props: ["kqthanhtra", "thutuc", "usersystem"],
  data: function () {
    return {
      hasAnalysis: false,
      cachedAnalysis: null,
      modalText: "File hiện tại chưa có phân tích bạn có muốn phân tích không?",
      error: null,
      showAnalysisModal: false,
      analysisResult: null,
      fileSelect: null,
      analysisX: 0,
      analysisY: 0,
      isProcessing: false,
      modalButtons: [
        {
          key: "cancel",
          text: "Hủy",
          icon: "fa fa-close",
          class: "btn btn-default btn-flat",
          align: "left",
          event: "cancel",
          closeOnClick: true,
        },
        // {
        //   key: "preview",
        //   text: "Xem lại",
        //   icon: "fa fa-eye",
        //   class: "btn btn-default btn-flat",
        //   align: "right",
        //   event: "preview",
        // },
        {
          key: "ok",
          text: "Trích xuất",
          icon: "fa fa-check",
          class: "btn bg-olive btn-flat",
          align: "right",
          event: "confirm",
          type: "submit",
        },
      ],
      form_nuoc_thai: null,
      form_khi_thai: null,
      form_ctrsh: null,
      form_ctrcntt: null,
      form_ctnh: null,
      showEditChuDauTu: false,
      chuDauTus: [],
      chuDauTu: null,
      showModel: false,
      currentTab: undefined,
      optionsDate: {
        format: "DD/MM/YYYY",
        useCurrent: false,
        locale: "vi",
      },
      en: en,
      vi: vi,
      is_loading: false,
      is_valid: true,
      khucongnghieps: [],
      coquantochucs: [],
      quyetdinhthanhtras: [],
      donvikhoiluongphatsinhs: [],
      cososanxuats: [],
      quyet_dinh_thanh_tra_selected: null,
      don_vi_ctrcntt: null,
      don_vi_ctnhdk: null,
      don_vi_ctnh: null,
      ten_don_vi_ctrsh: null,
      ten_don_vi_ctrcntt: null,
      donvinuoc: [],
      donviThongSoVuotChuan: [],
      donvidientich: [],
      loai_hinh_co_sos: [],
      loai_hinh_co_so_tree_listing: [],
      donvicongsuatthietkes: [],
      donvicongsuathoatdongs: [],
      tinhs: [],
      huyens: [],
      form_data: {
        co_so_san_xuats: [],
        error_quyet_dinh: null,
        error_so_quyet_dinh: null,
        quyet_dinh_thanh_tra: null,
        ngay_thanh_tra: null,
        so_quyet_dinh: null,
        mo_ta: null,
        noi_dung_chua_thuc_hien: null,
        nhom_hanh_vi_vi_phams: [],
        danh_sach_quyet_dinh_xu_phat: [],
        danh_sach_ket_qua_khac_phuc_vi_pham: [],
      },
      thoi_gian_dong_bo_chung: null,
      thoi_gian_dong_bo: null,
      validateMaDinhDanh: true,
      countdown: 300,
      countdownInterval: null,
      countdownBlink: false,
      storageKey: null,
      hasTempStorage: false,
    };
  },
  computed: {
    isHaveAccess() {
      return ["admin", "nhanvientrungtam", "adminvaquanlydanhmuc"].includes(
        this.usersystem.role_code
      );
    },
    countdownClass() {
      if (this.countdown <= 5 && this.countdown > 0)
        return this.countdownBlink ? "btn-danger blink" : "btn-danger";
      return "btn-warning";
    },
  },
  mounted() {
    console.log("kqthanhtra", this.kqthanhtra);
    this.storageKey = `ketquathanhtra_form_update_${this.kqthanhtra.id}`;
    // ⚙️ Nếu có dữ liệu tạm thì load lên trước
    const hasTempData = this.loadFormFromStorage();
    this.hasTempStorage = hasTempData;
    this.startCountdown();

    if (!this.kqthanhtra.ma_dinh_danh) this.taoMaDinhDanh();
    this.getThoiGianDongBo();
    this.thoi_gian_dong_bo = this.kqthanhtra.thoi_gian_dong_bo
      ? this.formatDateTime(this.kqthanhtra.thoi_gian_dong_bo)
      : null;
    this.chuDauTu = this.kqthanhtra.organization.chu_dau_tu;
    this.refresh();

    axios.get(env.endpointhttp + "danhmuc/chuyendoidonvis").then((response) => {
      this.donvicongsuathoatdongs =
        response.data.result.don_vi.cong_suat_hoat_dong;
      this.donvicongsuatthietkes =
        response.data.result.don_vi.cong_suat_thiet_ke;
      this.donvinuoc = response.data.result.don_vi.luu_luong_nuoc_thai;
      this.donvidientich = response.data.result.don_vi.dien_tich;
      this.donviThongSoVuotChuan =
        response.data.result.don_vi.thong_so_vuot_chuan;

      if (!hasTempData) {
        // fill từ API
        Object.assign(this.form_data, {
          attachments: this.kqthanhtra.attachments,
          quyet_dinh_thanh_tra: this.kqthanhtra.quyet_dinh_thanh_tra,
          ngay_thanh_tra: this.kqthanhtra.ngay_thanh_tra,
          so_quyet_dinh: this.kqthanhtra.so_quyet_dinh,
          mo_ta: this.kqthanhtra.mo_ta,
          noi_dung_chua_thuc_hien: this.kqthanhtra.noi_dung_chua_thuc_hien,
          nhom_hanh_vi_vi_phams: this.kqthanhtra.nhom_hanh_vi_vi_phams || [],
          danh_sach_ket_qua_khac_phuc_vi_pham:
            this.kqthanhtra.ket_qua_khac_phuc_hau_quas || [],
          danh_sach_quyet_dinh_xu_phat:
            this.kqthanhtra.quyet_dinh_xu_phats || [],
        });
        console.log("day", this.kqthanhtra.ket_qua_thanh_tra_chungs);
        // 🔹 Lọc chỉ các cơ sở có ngày_ket_luan khác null
        const allItems = this.kqthanhtra.ket_qua_thanh_tra_chungs || [];
        const filtered = allItems.filter(
          (item) =>
            item.co_so_san_xuat && item.co_so_san_xuat.ngay_ket_luan !== null
        );

        // 🔹 Nếu tất cả đều null thì dùng toàn bộ
        const finalData = filtered.length > 0 ? filtered : allItems;
        this.initFromSource({
          sourceCoSoSanXuats: finalData,
          useKQ: true,
        });
      } else {
        // dữ liệu tạm đã có => không reset mảng
        this.initFromSource({
          sourceCoSoSanXuats: this.form_data.co_so_san_xuats || [],
          useKQ: false,
        });
      }
    });
  },
  created: function () {
    // co quan to chuc
    if (localStorage.getItem("coquantochucs")) {
      this.coquantochucs = JSON.parse(localStorage.getItem("coquantochucs"));
    } else {
      axios.get(env.endpointhttp + "coquantochucs").then((response) => {
        this.coquantochucs = response.data.result;
        localStorage.setItem(
          "coquantochucs",
          JSON.stringify(this.coquantochucs)
        );
      });
    }
    axios.get(env.endpointhttp + "danhmuc/chuyendoidonvis").then((response) => {
      this.ten_don_vi_ctnhdk = response.data.result.don_vi_goc
        .khoi_luong_phat_sinh_chat_thai
        ? response.data.result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.id
        : null;
      this.donvikhoiluongphatsinhs =
        response.data.result.don_vi.khoi_luong_phat_sinh_chat_thai;
      this.ten_don_vi_ctrsh = response.data.result.don_vi_goc
        .khoi_luong_phat_sinh_chat_thai
        ? response.data.result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.ten
        : null;
    });
  },
  methods: {
    setPreviewVisibility(show) {
      const baseLeft = {
        key: "cancel",
        text: "Hủy",
        icon: "fa fa-close",
        class: "btn btn-default btn-flat",
        align: "left",
        event: "cancel",
        closeOnClick: true,
      };
      const btnOk = {
        key: "ok",
        text: "Trích xuất",
        icon: "fa fa-check",
        class: "btn bg-olive btn-flat",
        align: "right",
        event: "confirm",
        type: "submit",
      };
      if (show) {
        const btnPreview = {
          key: "preview",
          text: "Xem lại",
          icon: "fa fa-eye",
          class: "btn btn-default btn-flat",
          align: "right",
          event: "preview",
        };
        this.modalButtons = [baseLeft, btnPreview, btnOk];
      } else {
        this.modalButtons = [baseLeft, btnOk];
      }
    },
    caculatePosition() {
      const footer = document.querySelector(".box-footer-form");
      if (footer) {
        const rect = footer.getBoundingClientRect();
        const centerX = window.innerWidth / 2 - 200;
        const topY = rect.top + window.scrollY - 570;
        this.analysisX = centerX;
        this.analysisY = topY;
      }
      console.log(this.analysisX, this.analysisY);
    },
    async analyzePdf(file) {
      this.fileSelect = file;
      this.error = null;
      // this.isProcessing = true;

      try {
        const resp = await axios.post(
          env.endpointhttp + "checkpdfresult",
          this.fileSelect
        );
        const data = resp && resp.data ? resp.data : {};
        this.hasAnalysis = !!data.is_analysis;
        this.cachedAnalysis = this.hasAnalysis ? data.result || null : null;

        if (this.hasAnalysis) {
          this.modalText =
            "File này đã từng được phân tích bạn muốn xem lại hay phân tích mới";
          this.setPreviewVisibility(true);
        } else {
          this.modalText =
            "File hiện tại chưa có phân tích bạn có muốn phân tích không?";
          this.setPreviewVisibility(false);
        }
        this.showAnalysisModal = true;
      } catch (e) {
        console.error("CHECK PDF RESULT Error:", e);
        this.hasAnalysis = false;
        this.cachedAnalysis = null;
        this.modalText =
          "Không kiểm tra được trạng thái phân tích. Bạn có muốn tiến hành trích xuất?";
        this.setPreviewVisibility(false);
        this.error = null;
      } finally {
        // this.isProcessing = false;
      }
    },
    resetState() {
      // this.isProcessing = false;
      this.analysisResult = null;
      this.fileSelect = null;
      this.error = null;
      this.hasAnalysis = false;
      this.cachedAnalysis = null;
      this.modalText =
        "File hiện tại chưa có phân tích bạn có muốn phân tích không?";
      this.setPreviewVisibility(false);
    },
    async onPreview() {
      if (this.hasAnalysis && this.cachedAnalysis) {
        this.analysisResult = this.cachedAnalysis;
        this.showAnalysisModal = false;
        return;
      }
      this.error = "Chưa có kết quả trước đó để xem lại.";
    },
    async onConfirm() {
      this.isProcessing = true;
      await this.$nextTick();
      this.caculatePosition();
      if (!this.fileSelect) {
        this.error = "Không có file để phân tích.";
        this.isProcessing = false;
        return;
      }
      try {
        const response = await axios.post(
          env.endpointhttp + "pdfanalysis",
          this.fileSelect
        );
        this.analysisResult = response.data;
        this.showAnalysisModal = false;
        // this.analysisResult = fake_data;
      } catch (err) {
        console.error("API Error:", err);
        this.error =
          "Đã có lỗi xảy ra trong quá trình phân tích. Vui lòng thử lại.";
        this.analysisResult = null;
        // this.analysisResult = fake_data;
      } finally {
        // this.isProcessing = false;
        this.isProcessing = false;
      }
    },
    updateFormNuocThai(form) {
      this.form_nuoc_thai = form; // Cập nhật giá trị form_nuoc_thai
    },
    updateFormKhiThai(form) {
      this.form_khi_thai = form; // Cập nhật giá trị form_khi_thai
    },
    updateFormCtrsh(form) {
      this.form_ctrsh = form; // Cập nhật giá trị form_ctrsh
    },
    updateFormCtrCntt(form) {
      this.form_ctrcntt = form; // Cập nhật giá trị form_ctrsh
    },
    updateFormCtnh(form) {
      this.form_ctnh = form; // Cập nhật giá trị form_ctrsh
    },
    getThoiGianDongBo() {
      axios
        .get(env.endpointhttp + "inthoigian/quyet_dinh_thanh_lap")
        .then((response) => {
          this.thoi_gian_dong_bo_chung = this.formatDateTime(
            response.data.updated_at
          );
        });
    },
    taoMaDinhDanh() {
      this.validateMaDinhDanh = true;
      const defaultCoQuan = "<CoQuanThanhTra>";
      const defaultNamKeHoach = "<NamKeHoach>";
      const defaultSoDoan = "<SoDoan>";
      const defaultKetLuan = "<SoKetLuan>";
      let coQuan =
        this.kqthanhtra && this.kqthanhtra.quyet_dinh_thanh_tra
          ? this.kqthanhtra.quyet_dinh_thanh_tra.co_quan_quyet_dinh.ma_dinh_danh
          : defaultCoQuan;
      let namThucHien =
        this.kqthanhtra &&
        this.kqthanhtra.quyet_dinh_thanh_tra &&
        this.kqthanhtra.quyet_dinh_thanh_tra.nam_ke_hoach
          ? this.kqthanhtra.quyet_dinh_thanh_tra.nam_ke_hoach
          : defaultNamKeHoach;
      let soQuyetDinhthanhLap =
        this.kqthanhtra && this.kqthanhtra.quyet_dinh_thanh_tra
          ? this.kqthanhtra.quyet_dinh_thanh_tra.so_quyet_dinh
          : null;
      let ketLuan = this.kqthanhtra ? this.kqthanhtra.so_quyet_dinh : null;
      let soDoan = defaultSoDoan;
      let soKetLuan = defaultKetLuan;
      if (soQuyetDinhthanhLap) {
        let soQdArray = soQuyetDinhthanhLap.split("/");
        soDoan = soQdArray[0];
      }
      if (ketLuan) {
        let soKLArray = ketLuan.split("/");
        soKetLuan = soKLArray[0];
      }
      this.kqthanhtra.ma_dinh_danh = `${coQuan}-${namThucHien}-${soDoan}-${soKetLuan}.TTKT`;
      if (
        coQuan === defaultCoQuan ||
        namThucHien === defaultNamKeHoach ||
        soDoan === defaultSoDoan ||
        soKetLuan === defaultKetLuan
      ) {
        this.validateMaDinhDanh = false;
      }
    },
    taoMaDinhDanhQuyetDinhXuPhat(item) {
      try {
        const coQuan = item.co_quan_quyet_dinh_xu_phat.ma_dinh_danh;
        var thoiGian = null;
        if (item.thoi_gian_ban_hanh) {
          let timeArr = item.thoi_gian_ban_hanh.split("/");
          thoiGian = timeArr[timeArr.length - 1];
        }
        var soVanBan = null;
        if (item.so_quyet_dinh) {
          let soVanbanArr = item.so_quyet_dinh.split("/");
          soVanBan = soVanbanArr[0];
        }
        if (coQuan && thoiGian && soVanBan) {
          return `${coQuan}-${thoiGian}-${soVanBan}.XPHC`;
        }
        return null;
      } catch (error) {
        return null;
      }
    },

    sendDataMTQG() {
      if (!this.validateMaDinhDanh) return;
      const data = {
        DoanThanhTraKiemTra: {
          MaDinhDanh: this.kqthanhtra.quyet_dinh_thanh_tra.ma_dinh_danh,
          type: "T_DoanThanhTraKiemTra",
          TenGoi: this.kqthanhtra.quyet_dinh_thanh_tra.ten,
        },
        NgayBanHanh: this.form_data.ngay_thanh_tra,
        CoQuanBanHanh: {
          MaDinhDanh:
            this.kqthanhtra.quyet_dinh_thanh_tra.co_quan_quyet_dinh
              .ma_dinh_danh,
          type: "T_CoQuanDonVi",
          TenGoi: this.kqthanhtra.quyet_dinh_thanh_tra.co_quan_quyet_dinh.ten,
        },
        SoHieuVanBan: this.form_data.so_quyet_dinh,
        type: "T_KetLuanThanhTraKiemTra",
        MaDinhDanh: this.kqthanhtra.ma_dinh_danh,
        TenGoi: `Kết quả thanh tra kiểm tra: ${this.kqthanhtra.quyet_dinh_thanh_tra.ten}`,
      };

      const xuPhat = [];
      if (
        this.kqthanhtra.quyet_dinh_xu_phats &&
        this.kqthanhtra.quyet_dinh_xu_phats.length
      ) {
        this.kqthanhtra.quyet_dinh_xu_phats.forEach((item) => {
          if (item.ma_dinh_danh) {
            const xp = {
              type: "T_XuPhatViPhamHanhChinh",
              NgayQuyetDinh: item.thoi_gian_ban_hanh,
              MaDinhDanh: item.ma_dinh_danh
                ? item.ma_dinh_danh
                : this.taoMaDinhDanhQuyetDinhXuPhat(item),
              SoHieuVanBan: item.so_quyet_dinh,
              CoQuanQuyetDinh: {
                MaDinhDanh: item.co_quan_quyet_dinh_xu_phat.ma_dinh_danh,
                type: "T_CoQuanDonVi",
                TenGoi: item.co_quan_quyet_dinh_xu_phat.ten,
              },
            };
            if (xp.MaDinhDanh) {
              xuPhat.push(xp);
            }
          }
        });
      }
      if (xuPhat.length > 0) {
        data.XuPhatViPhamHanhChinh = xuPhat;
      }
      // console.log(data);
      axios
        .post(env.endpointhttp + "moitruongquocgia/create/ketluanthanhtra", {
          data: JSON.stringify(data),
          id: this.kqthanhtra.id,
        })
        .then((response) => {
          MessageService.showSuccessMessage("Đồng bộ thành công");
          // setTimeout(() => {
          //   window.location.href = "/doanthanhtra/update/" + this.value.id;
          // }, 2500)
        })
        .catch((error) => {
          console.log(error);
          MessageService.showWarningMessage("Gửi dữ liệu không thành công");
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
    formatDateTime(dateTime) {
      try {
        const time = new Date(dateTime);
        if (!dateTime) {
          return "";
        }
        return (
          time.getHours() +
          ":" +
          time.getMinutes() +
          ":" +
          time.getSeconds() +
          " " +
          time.toLocaleDateString("en-TT")
        );
      } catch (error) {
        return "";
      }
    },
    refresh: function () {
      // co quan to chuc
      if (localStorage.getItem("coquantochucs")) {
        this.coquantochucs = JSON.parse(localStorage.getItem("coquantochucs"));
      } else {
        axios.get(env.endpointhttp + "coquantochucs").then((response) => {
          this.coquantochucs = response.data.result;
          localStorage.setItem(
            "coquantochucs",
            JSON.stringify(this.coquantochucs)
          );
        });
      }
      // loai hinh co so
      if (localStorage.getItem("loaihinhcosos")) {
        this.loai_hinh_co_sos = JSON.parse(
          localStorage.getItem("loaihinhcosos")
        );
      } else {
        axios.get(env.endpointhttp + "loaihinhcosos").then((response) => {
          this.loai_hinh_co_sos = response.data.result;
          localStorage.setItem(
            "loaihinhcosos",
            JSON.stringify(this.loai_hinh_co_sos)
          );
        });
      }

      // loai hinh co so dang cay
      if (localStorage.getItem("loaihinhcosos?type=report")) {
        this.loai_hinh_co_so_tree_listing = JSON.parse(
          localStorage.getItem("loaihinhcosos?type=report")
        );
      } else {
        axios
          .get(env.endpointhttp + "loaihinhcosos?type=report")
          .then((response) => {
            this.loai_hinh_co_so_tree_listing = response.data.result;
            localStorage.setItem(
              "loaihinhcosos?type=report",
              JSON.stringify(this.loai_hinh_co_so_tree_listing)
            );
          });
      }

      // quan huyen
      if (localStorage.getItem("quanhuyens")) {
        this.huyens = JSON.parse(localStorage.getItem("quanhuyens"));
      } else {
        axios.get(env.endpointhttp + "quanhuyens").then((response) => {
          this.huyens = response.data.result;
          localStorage.setItem("quanhuyens", JSON.stringify(this.huyens));
        });
      }
      // tinh thanh
      if (localStorage.getItem("tinhthanhs")) {
        this.tinhs = JSON.parse(localStorage.getItem("tinhthanhs"));
      } else {
        axios.get(env.endpointhttp + "tinhthanhs").then((response) => {
          this.tinhs = response.data.result;
          localStorage.setItem("tinhthanhs", JSON.stringify(this.tinhs));
        });
      }
    },
    getFile(data) {
      this.form_data.attachments = data;
    },
    refreshDVCTRSH: function () {
      axios
        .get(env.endpointhttp + "danhmuc/chuyendoidonvis")
        .then((response) => {
          this.donvikhoiluongphatsinhs =
            response.data.result.don_vi.khoi_luong_phat_sinh_chat_thai;
          this.don_vi_ctrsh = response.data.result.don_vi_goc
            .khoi_luong_phat_sinh_chat_thai
            ? response.data.result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.id
            : null;
          this.ten_don_vi_ctrsh = response.data.result.don_vi_goc
            .khoi_luong_phat_sinh_chat_thai
            ? response.data.result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.ten
            : null;
        });
    },
    refreshDVCTRCNTT: function () {
      axios
        .get(env.endpointhttp + "danhmuc/chuyendoidonvis")
        .then((response) => {
          this.donvikhoiluongphatsinhs =
            response.data.result.don_vi.khoi_luong_phat_sinh_chat_thai;
          this.don_vi_ctrcntt = response.data.result.don_vi_goc
            .khoi_luong_phat_sinh_chat_thai
            ? response.data.result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.id
            : null;
          this.ten_don_vi_ctrcntt = response.data.result.don_vi_goc
            .khoi_luong_phat_sinh_chat_thai
            ? response.data.result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.ten
            : null;
        });
    },
    refreshDVCTNH: function () {
      axios
        .get(env.endpointhttp + "danhmuc/chuyendoidonvis")
        .then((response) => {
          this.donvikhoiluongphatsinhs =
            response.data.result.don_vi.khoi_luong_phat_sinh_chat_thai;
          this.don_vi_ctnh = response.data.result.don_vi_goc
            .khoi_luong_phat_sinh_chat_thai
            ? response.data.result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.id
            : null;
          this.ten_don_vi_ctnhdk = response.data.result.don_vi_goc
            .khoi_luong_phat_sinh_chat_thai
            ? response.data.result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.id
            : null;
          this.ten_don_vi_ctnh = response.data.result.don_vi_goc
            .khoi_luong_phat_sinh_chat_thai
            ? response.data.result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.ten
            : null;
        });
    },

    update() {
      if (
        !this.form_data.quyet_dinh_thanh_tra ||
        !this.form_data.quyet_dinh_thanh_tra.id
      ) {
        this.form_data.error_quyet_dinh = "Chọn quyết định thanh tra";
        MessageService.showDangerMessage("Chưa chọn quyết định thanh tra");
        return;
      }

      this.is_loading = true;
      this.form_data.ma_dinh_danh = this.kqthanhtra.ma_dinh_danh;

      this.form_data.organization = {
        ten: this.kqthanhtra.organization.ten,
        dia_chi_lien_he: this.kqthanhtra.organization.dia_chi_lien_he,
      };

      if (
        Array.isArray(this.form_data.co_so_san_xuats) &&
        this.form_data.co_so_san_xuats.length > 0
      ) {
        this.form_data.co_so_san_xuats.forEach(function (co_so_san_xuat) {
          /** ===================== NƯỚC THẢI ===================== */
          if (
            Array.isArray(co_so_san_xuat.ket_qua_thanh_tra_nuoc_thais) &&
            co_so_san_xuat.ket_qua_thanh_tra_nuoc_thais.length > 0
          ) {
            var item = co_so_san_xuat.ket_qua_thanh_tra_nuoc_thais[0];
            var form =
              this.form_nuoc_thai && this.form_nuoc_thai.phieu_thu_nghiem
                ? this.form_nuoc_thai.phieu_thu_nghiem
                : {};

            var phieu_thu_nghiem = {
              id:
                item && item.phieu_thu_nghiem ? item.phieu_thu_nghiem.id : null,
              dia_diem_lay_mau: form.dia_diem_lay_mau || null,
              dia_chi: form.dia_chi || null,
              attachment: form && form.attachment ? form.attachment : null,
            };

            var nuoc_thais = co_so_san_xuat.ket_qua_thanh_tra_nuoc_thais.map(
              function (x) {
                return {
                  luu_luong: x.luu_luong,
                  cong_suat_he_thong_xu_ly: x.cong_suat_he_thong_xu_ly,
                  thanh_phan: x.thanh_phan,
                  nguon_tiep_nhan: x.nguon_tiep_nhan,
                  thong_so_nuoc_thai_vuot_chuan:
                    x.thong_so_nuoc_thai_vuot_chuan,
                  loai_hinh_quan_trac: x.loai_hinh_quan_trac,
                };
              }
            );

            co_so_san_xuat.ket_qua_thanh_tra_nuoc_thais = {
              phieu_thu_nghiem: phieu_thu_nghiem,
              nuoc_thais: nuoc_thais,
            };
          }

          /** ===================== KHÍ THẢI ===================== */
          if (
            Array.isArray(co_so_san_xuat.ket_qua_thanh_tra_khi_thais) &&
            co_so_san_xuat.ket_qua_thanh_tra_khi_thais.length > 0
          ) {
            var item2 = co_so_san_xuat.ket_qua_thanh_tra_khi_thais[0];
            var form2 =
              this.form_khi_thai && this.form_khi_thai.phieu_thu_nghiem
                ? this.form_khi_thai.phieu_thu_nghiem
                : {};

            var phieu_thu_nghiem2 = {
              id:
                item2 && item2.phieu_thu_nghiem
                  ? item2.phieu_thu_nghiem.id
                  : null,
              dia_diem_lay_mau: form2.dia_diem_lay_mau || null,
              dia_chi: form2.dia_chi || null,
              attachment: form2 && form2.attachment ? form2.attachment : null,
            };

            var khi_thais = co_so_san_xuat.ket_qua_thanh_tra_khi_thais.map(
              function (x) {
                return {
                  luu_luong: x.luu_luong,
                  cong_suat_he_thong_xu_ly: x.cong_suat_he_thong_xu_ly,
                  thanh_phan: x.thanh_phan,
                  thong_so_khi_thai_vuot_chuan: x.thong_so_khi_thai_vuot_chuan,
                  quy_trinh_xu_ly: x.quy_trinh_xu_ly,
                  loai_hinh_quan_trac: x.loai_hinh_quan_trac,
                };
              }
            );

            co_so_san_xuat.ket_qua_thanh_tra_khi_thais = {
              phieu_thu_nghiem: phieu_thu_nghiem2,
              khi_thais: khi_thais,
            };
          }

          /** ===================== CTRSH ===================== */
          if (
            Array.isArray(
              co_so_san_xuat.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats
            ) &&
            co_so_san_xuat.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats.length > 0
          ) {
            var item3 =
              co_so_san_xuat.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats[0];
            var form3 =
              this.form_ctrsh && this.form_ctrsh.phieu_thu_nghiem
                ? this.form_ctrsh.phieu_thu_nghiem
                : {};

            var phieu_thu_nghiem3 = {
              id:
                item3 && item3.phieu_thu_nghiem
                  ? item3.phieu_thu_nghiem.id
                  : null,
              dia_diem_lay_mau: form3.dia_diem_lay_mau || null,
              dia_chi: form3.dia_chi || null,
            };

            var ctrsh =
              co_so_san_xuat.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats.map(
                function (x) {
                  return {
                    khoi_luong_phat_sinh: x.khoi_luong_phat_sinh,
                    thanh_phan_chinh: x.thanh_phan_chinh,
                    tu_xu_ly: x.tu_xu_ly,
                    thue_xu_ly: x.thue_xu_ly,
                    co_quan_thue_xu_ly: x.co_quan_thue_xu_ly,
                  };
                }
              );

            co_so_san_xuat.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats = {
              phieu_thu_nghiem: phieu_thu_nghiem3,
              ctrsh: ctrsh,
            };
          }

          /** ===================== CTR CNTT ===================== */
          if (
            Array.isArray(
              co_so_san_xuat.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t
            ) &&
            co_so_san_xuat.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t.length > 0
          ) {
            var item4 =
              co_so_san_xuat.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t[0];
            var form4 =
              this.form_ctrcntt && this.form_ctrcntt.phieu_thu_nghiem
                ? this.form_ctrcntt.phieu_thu_nghiem
                : {};

            var phieu_thu_nghiem4 = {
              id:
                item4 && item4.phieu_thu_nghiem
                  ? item4.phieu_thu_nghiem.id
                  : null,
              dia_diem_lay_mau: form4.dia_diem_lay_mau || null,
              dia_chi: form4.dia_chi || null,
            };

            var ctrcntt =
              co_so_san_xuat.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t.map(
                function (x) {
                  return {
                    khoi_luong_phat_sinh: x.khoi_luong_phat_sinh,
                    thanh_phan_chinh: x.thanh_phan_chinh,
                    tu_xu_ly: x.tu_xu_ly,
                    thue_xu_ly: x.thue_xu_ly,
                    co_quan_thue_xu_ly: x.co_quan_thue_xu_ly,
                  };
                }
              );

            co_so_san_xuat.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t = {
              phieu_thu_nghiem: phieu_thu_nghiem4,
              ctrcntt: ctrcntt,
            };
          }

          /** ===================== CTNH ===================== */
          if (
            Array.isArray(
              co_so_san_xuat.ket_qua_thanh_tra_chat_thai_nguy_hai
            ) &&
            co_so_san_xuat.ket_qua_thanh_tra_chat_thai_nguy_hai.length > 0
          ) {
            var item5 = co_so_san_xuat.ket_qua_thanh_tra_chat_thai_nguy_hai[0];
            var form5 =
              this.form_ctnh && this.form_ctnh.phieu_thu_nghiem
                ? this.form_ctnh.phieu_thu_nghiem
                : {};

            var phieu_thu_nghiem5 = {
              id:
                item5 && item5.phieu_thu_nghiem
                  ? item5.phieu_thu_nghiem.id
                  : null,
              dia_diem_lay_mau: form5.dia_diem_lay_mau || null,
              dia_chi: form5.dia_chi || null,
            };

            var ctnh = co_so_san_xuat.ket_qua_thanh_tra_chat_thai_nguy_hai.map(
              function (x) {
                return {
                  khoi_luong_phat_sinh_thuc_te:
                    x.khoi_luong_phat_sinh_thuc_te || null,
                  khoi_luong_phat_sinh_theo_so_dang_ki:
                    x.khoi_luong_phat_sinh_theo_so_dang_ki || null,
                  thanh_phan_chinh: x.thanh_phan_chinh,
                  tu_xu_ly: x.tu_xu_ly,
                  thue_xu_ly: x.thue_xu_ly,
                  co_quan_thue_xu_ly: x.co_quan_thue_xu_ly,
                };
              }
            );

            co_so_san_xuat.ket_qua_thanh_tra_chat_thai_nguy_hai = {
              phieu_thu_nghiem: phieu_thu_nghiem5,
              ctnh: ctnh,
            };
          }
        }, this); // <-- Quan trọng: bind 'this'
      }

      axios
        .put(env.endpointhttp + "ketquathanhtra/update/" + this.kqthanhtra.id, {
          data: this.form_data,
        })
        .then(
          function () {
            MessageService.showSuccessMessage("Cập nhật kết quả thành công");
            this.clearSavedForm();
            window.location.href = "/ketquathanhtra/edit/" + this.kqthanhtra.id;
          }.bind(this)
        )
        .catch(function (error) {
          console.error(error);
          MessageService.showDangerMessage("Lỗi cập nhật kết quả thanh tra");
        })
        .finally(
          function () {
            this.is_loading = false;
          }.bind(this)
        );
    },

    goDanhSachCDT() {
      window.open("/chudautu", "_blank");
    },
    goAddChuDauTu() {
      window.open("/chudautu/add/", "_blank");
    },
    goChuDauTu(id) {
      window.open("/chudautu/edit/" + id, "_blank");
    },
    changeChuDauTu() {
      this.showEditChuDauTu = true;
    },
    limitText(count) {
      return `và ${count} tên khu công nghiệp khác`;
    },
    asyncFindQuyetDinh(query) {
      if (query) {
        this.is_loading = true;
        axios
          .get(env.endpointhttp + "quyetdinhthanhtras?search=" + query)
          .then((response) => {
            this.quyetdinhthanhtras = response.data.result;
          })
          .catch((error) => {})
          .finally(() => (this.is_loading = false));
      }
    },
    asyncFindCoSoThanhTra(query) {
      if (query) {
        this.is_loading = true;
        axios
          .get(env.endpointhttp + "cososanxuatbythanhtras?search=" + query)
          .then((response) => {
            this.cososanxuats = response.data.result;
          })
          .catch((error) => {})
          .finally(() => (this.is_loading = false));
      }
    },
    addDiaDiemHoatDong() {
      let don_vi_nuoc = this.donvinuoc.find(
        (element) => element.don_vi_goc === true
      );
      let don_vi_dien_tich = this.donvidientich.find(
        (element) => element.don_vi_goc === true
      );
      var item = {
        ket_qua_thanh_tra_id: null,
        ten: null,
        kinh_do: 105,
        vi_do: 21,
        dia_chi_lien_he: null,
        khu_cong_nghiep_id: null,
        xa_phuong: null,
        quan_huyen_id: null,
        tinh_thanh_id: null,
        dien_tich: null,
        so_nguoi_lao_dong: null,
        so_giay_chung_nhan_dau_tu: null,
        ngay_cap_giay_chung_nhan_dau_tu: null,
        co_quan_cap_giay_chung_nhan_dau_tu: null,
        co_quan_dau_tu: null,
        nguyen_lieu_chinh_su_dung: null,
        nhien_lieu_chinh_su_dung: null,
        hoa_chat_su_dung: null,
        nguon_nuoc_su_dung: null,
        vung_kinh_te_trong_diem_id: null,
        mien_id: null,
        luu_vuc_song_id: null,
        ngay_cap_giay_chung_nhan_kinh_doanh: null,
        so_giay_chung_nhan_kinh_doanh: null,
        co_quan_cap_giay_chung_nhan_kinh_doanh: null,
        co_quan_kinh_doanh: null,
        luong_nuoc_su_dung: null,
        dia_chi_hoat_dong: null,
        co_so_san_xuat_id: null,
        co_so_san_xuat: null,
        ket_qua_thanh_tra_chat_thai_nguy_hai: [],
        ket_qua_thanh_tra_chat_thai_ran_c_n_t_t: [],
        ket_qua_thanh_tra_chat_thai_ran_sinh_hoats: [],
        ket_qua_thanh_tra_chung_loai_hinh_hoat_dongs: [],
        ket_qua_thanh_tra_khi_thais: [],
        ket_qua_thanh_tra_nuoc_thais: [],
        ket_qua_thanh_tra_thu_tuc_hanh_chinhs: [],
        khu_cong_nghiep: null,
        quan_huyen: null,
        tinh_thanh: null,
        thutuchanhchinhs: {
          Bdkdtcmt: [],
          ckbvmt: [],
          dabvmt: [],
          dactphmt: [],
          dtm: [],
          gcncsddkrkdscsgon: [],
          gpktks: [],
          gpktndđ: [],
          gpktsdnm: [],
          gpktsdnn: [],
          gpvmt: [],
          gpxt: [],
          gsmtdk: [],
          gxnctbvmt: [],
          gxnddkbvmtnkpl: [],
          gxnhthtnt: [],
          gxnkphqvphc: [],
          gxnqctphmt: [],
          kqktks: [],
          lcktkt: [],
          pabvmt: [],
          qtclmt: [],
          sdkcntctnh: [],
          ttk: [],
        },

        ty_le_lap_day: null,
        tong_dien_tich: null,
        chuyen_doi_don_vi: null,
        dien_tich_dat_cn: null,
        dien_tich_dat_cho_thue: null,
        dien_tich_dat_cay_xanh: null,
      };
      item.errors = {
        don_vi_cstk: null,
        don_vi_cshd: null,
        loaics: null,
        tinh: null,
        huyen: null,
      };
      item.don_vi_nuoc_su_dung = don_vi_nuoc;
      item.don_vi_dien_tich = don_vi_dien_tich;
      item.is_new = true;
      item.id = `new-dia-diem-hoat-dong-${new Date().getTime()}`;
      this.form_data.co_so_san_xuats.push(item);
      this.currentTab = item.id;
    },
    // xoa dia chi hoat dong của một tổ chức thanh tra
    deleteDiaChiHoatDong(index) {
      this.form_data.co_so_san_xuats.splice(index, 1);
      if (this.form_data.co_so_san_xuats.length > 0) {
        this.currentTab = this.form_data.co_so_san_xuats[0].id;
      }
    },
    deleteKetQuaThanhTra() {
      axios
        .delete(
          `${env.endpointhttp}ketquathanhtra/delete/${this.kqthanhtra.id}`
        )
        .then(() => {
          window.location = "/cososanxuat";
        });
    },

    // 🔹 Lưu form vào localStorage
    // 🔹 Lưu form vào localStorage (bản fix hoàn chỉnh)
    saveFormToStorage() {
      try {
        // ✅ Lấy dữ liệu từ form_data chứ không phải kqthanhtra
        const dataToSave = JSON.parse(JSON.stringify(this.form_data));

        // ✅ Gắn kqthanhtra.id để định danh
        dataToSave.id = this.kqthanhtra.id;
        dataToSave.organization = this.kqthanhtra.organization || null;

        // ✅ Đảm bảo mảng cơ sở sản xuất tồn tại
        if (!Array.isArray(dataToSave.co_so_san_xuats)) {
          dataToSave.co_so_san_xuats = [];
        }

        // ✅ Sao lưu chi tiết từng cơ sở
        dataToSave.co_so_san_xuats = dataToSave.co_so_san_xuats.map((item) => ({
          ...item,
          ket_qua_phan_tich_moi_truong: item.ket_qua_phan_tich_moi_truong || [],
          ket_qua_quan_trac: item.ket_qua_quan_trac || [],
        }));

        // ✅ Lưu vào localStorage
        localStorage.setItem(this.storageKey, JSON.stringify(dataToSave));

        MessageService.showSuccessMessage("Đã lưu dữ liệu tạm thành công!");
        console.log("Đã lưu form_data:", dataToSave);
      } catch (error) {
        console.error("❌ Lỗi khi lưu dữ liệu tạm:", error);
        MessageService.showDangerMessage("❌ Lưu dữ liệu thất bại!");
      }
    },

    // gomKetQuaQuanTrac(list) {
    //   if (!Array.isArray(list)) return [];

    //   const grouped = {};

    //   list.forEach((row) => {
    //     const type = row.loai_moi_truong;
    //     if (!grouped[type]) {
    //       grouped[type] = {
    //         loai_moi_truong: type,
    //         mau_moi_truong: [],
    //       };
    //     }

    //     const key =
    //       (row.dia_diem || "") +
    //       "__" +
    //       (row.vi_tri || "") +
    //       "__" +
    //       (row.loai_mau || "") +
    //       "__" +
    //       (row.kinh_do || "") +
    //       "__" +
    //       (row.vi_do || "");

    //     let mau = grouped[type].mau_moi_truong.find((m) => m._key === key);

    //     if (!mau) {
    //       mau = {
    //         _key: key,
    //         dia_diem: row.dia_diem,
    //         vi_tri: row.vi_tri,
    //         loai_mau: row.loai_mau,
    //         kinh_do: row.kinh_do,
    //         vi_do: row.vi_do,
    //         attachment: row.attachment || null,
    //         thong_sos: [],
    //       };
    //       grouped[type].mau_moi_truong.push(mau);
    //     }

    //     mau.thong_sos.push({
    //       thong_so: row.thong_so,
    //       don_vi_tinh: row.don_vi_tinh,
    //       phuong_phap_phan_tich: row.phuong_phap_phan_tich,
    //       ket_qua: row.ket_qua,
    //       gia_tri_gioi_han: row.gia_tri_gioi_han,
    //       so_lan_vuot: row.so_lan_vuot,
    //     });
    //   });

    //   Object.values(grouped).forEach((type) =>
    //     type.mau_moi_truong.forEach((m) => delete m._key)
    //   );

    //   return Object.values(grouped);
    // },

    // 🔹 Xóa dữ liệu tạm (nếu cần reset)
    clearSavedForm() {
      localStorage.removeItem(this.storageKey);
      // MessageService.showSuccessMessage("Đã xóa lưu tạm! ");
    },

    startCountdown() {
      if (this.countdownTimer) clearInterval(this.countdownTimer);
      this.countdown = 300;
      this.countdownBlink = false;

      this.countdownTimer = setInterval(() => {
        if (this.countdown > 0) {
          this.countdown--;

          // 🔹 Khi còn dưới 5s, kích hoạt nhấp nháy
          if (this.countdown <= 5) {
            this.countdownBlink = !this.countdownBlink;
          }
        } else {
          clearInterval(this.countdownTimer);
          this.countdownTimer = null;
          this.saveFormToStorage();

          // 🔹 Cho nhấp nháy nhanh 2 lần sau khi kết thúc
          this.countdownBlink = true;
          setTimeout(() => {
            this.countdownBlink = false;
            this.startCountdown(); // tự khởi động lại
          }, 1000);
        }
      }, 1000);
    },

    restartCountdown() {
      if (this.countdownTimer) clearInterval(this.countdownTimer);
      // 🔹 Lưu form khi click
      this.saveFormToStorage();
      this.startCountdown();
    },

    // 🔹 Khôi phục dữ liệu tạm từ localStorage
    loadFormFromStorage() {
      try {
        const saved = localStorage.getItem(this.storageKey);
        if (!saved) return false;

        const parsed = JSON.parse(saved);

        // -------------------------------
        // 🧩 Hàm flatten: chuyển cấu trúc phân cấp (loai_moi_truong → mau_moi_truong → thong_sos)
        // về dạng phẳng để hiển thị lại form
        // -------------------------------
        function flattenKetQuaPhanTichMoiTruong(arr = []) {
          const result = [];

          arr.forEach((loai) => {
            (loai.mau_moi_truong || []).forEach((mau) => {
              (mau.thong_sos || []).forEach((ts) => {
                result.push({
                  loai_moi_truong: loai.loai_moi_truong || null,
                  dia_diem: mau.dia_diem || null,
                  vi_tri: mau.vi_tri || null,
                  loai_mau: mau.loai_mau || null,
                  kinh_do: mau.kinh_do || null,
                  vi_do: mau.vi_do || null,
                  thong_so: ts.thong_so || null,
                  don_vi_tinh: ts.don_vi_tinh || null,
                  phuong_phap_phan_tich: ts.phuong_phap_phan_tich || null,
                  gia_tri_gioi_han: ts.gia_tri_gioi_han || null,
                  so_lan_vuot: ts.so_lan_vuot || null,
                  ket_qua: ts.ket_qua || null,
                  quy_chuan: ts.quy_chuan || null,
                  ghi_chu: ts.ghi_chu || null,
                  attachment: mau.attachment || ts.attachment || null,
                  tep_pdf: mau.attachment.link || ts.attachment.link || null,
                  id: ts.id,
                  created_at: ts.created_at,
                  updated_at: ts.updated_at,
                });
              });
            });
          });

          return result;
        }

        // -------------------------------
        // 🧩 Chuẩn hóa dữ liệu cơ sở sản xuất
        // -------------------------------
        if (Array.isArray(parsed.co_so_san_xuats)) {
          parsed.co_so_san_xuats = parsed.co_so_san_xuats.map((item) => {
            const flat = flattenKetQuaPhanTichMoiTruong(
              item.ket_qua_phan_tich_moi_truong
            );
            const flat2 = flattenKetQuaPhanTichMoiTruong(
              item.ket_qua_quan_trac
            );
            return {
              ...item,
              ket_qua_phan_tich_moi_truong: flat,
              ket_qua_quan_trac: flat2,
            };
          });
        }

        // -------------------------------
        // 🧩 Gán lại dữ liệu vào form_data
        // -------------------------------
        this.form_data = {
          ...this.form_data,
          ...parsed,
        };

        // ✅ Gán lại các trường văn bản
        this.form_data.mo_ta = parsed.mo_ta || "";
        this.form_data.noi_dung_chua_thuc_hien =
          parsed.noi_dung_chua_thuc_hien || "";

        // ✅ Khôi phục danh sách file đính kèm
        if (Array.isArray(parsed.attachments)) {
          // Gộp (merge) cả file gốc từ kqthanhtra nếu có
          const existing = Array.isArray(this.kqthanhtra.attachments)
            ? this.kqthanhtra.attachments
            : [];

          // Tránh trùng id hoặc tên file
          const merged = [
            ...existing,
            ...parsed.attachments.filter(
              (f) => !existing.some((e) => e.id === f.id || e.name === f.name)
            ),
          ];

          // Gán lại để Vue phản ứng được (tạo mảng mới)
          this.form_data.attachments = merged.map((file) => ({
            ...file,
            link: file.link.startsWith("http")
              ? file.link
              : "/" + file.link.replace(/^\/?/, ""), // đảm bảo path đúng
          }));
        } else {
          this.form_data.attachments = [];
        }

        // ✅ Đồng bộ ngược về this.kqthanhtra nếu cần
        this.kqthanhtra = {
          ...this.kqthanhtra,
          ...parsed,
          organization: {
            ...this.kqthanhtra.organization,
            ...parsed.organization,
          },
        };

        console.log("✅ Dữ liệu sau khi khôi phục:", this.form_data);
        MessageService.showSuccessMessage(
          "Đã khôi phục dữ liệu tạm thành công!"
        );
        return true;
      } catch (e) {
        console.error("❌ Lỗi khi load dữ liệu từ localStorage:", e);
        MessageService.showErrorMessage("Lỗi khi khôi phục dữ liệu tạm!");
        return false;
      }
    },

    initFromSource({ sourceCoSoSanXuats, useKQ = true }) {
      if (!Array.isArray(sourceCoSoSanXuats)) return;

      const don_vi_nuoc =
        this.donvinuoc.find((e) => e.don_vi_goc === true) || null;
      const don_vi_dien_tich =
        this.donvidientich.find((e) => e.don_vi_goc === true) || null;

      // Nếu dùng dữ liệu từ API (useKQ = true) → reset danh sách
      if (useKQ) {
        this.form_data.co_so_san_xuats = [];
      }

      // Nếu dùng dữ liệu tạm → chỉ cập nhật thông tin phụ, không thêm mới
      if (!useKQ) {
        this.form_data.co_so_san_xuats = sourceCoSoSanXuats.map((item) => {
          const x = { ...item };
          x.errors = x.errors || {};
          x.don_vi_nuoc_su_dung = x.don_vi_nuoc_su_dung || don_vi_nuoc;
          x.don_vi_dien_tich = x.don_vi_dien_tich || don_vi_dien_tich;
          return x;
        });

        this.currentTab =
          this.form_data.co_so_san_xuats.length > 0
            ? this.form_data.co_so_san_xuats[0].id
            : undefined;
        return;
      }

      // 🔹 Nếu là dữ liệu từ API (useKQ = true)
      (sourceCoSoSanXuats || []).forEach((item) => {
        const x = { ...item };

        // Fill các trường cơ bản
        x.dia_chi_lien_he =
          x.dia_chi_lien_he ||
          (x.co_so_san_xuat && x.co_so_san_xuat.dia_chi_lien_he) ||
          "";
        x.ten = x.ten || (x.co_so_san_xuat && x.co_so_san_xuat.ten) || "";
        x.kinh_do =
          x.kinh_do || (x.co_so_san_xuat && x.co_so_san_xuat.kinh_do) || "";
        x.vi_do = x.vi_do || (x.co_so_san_xuat && x.co_so_san_xuat.vi_do) || "";

        // Thông tin phụ
        x.errors = {};
        x.don_vi_nuoc_su_dung = don_vi_nuoc;
        x.don_vi_dien_tich = don_vi_dien_tich;

        if (x.co_so_san_xuat) {
          x.tinh_thanhs = x.tinh_thanhs || x.co_so_san_xuat.tinh_thanhs;
          x.phuong_xas = x.phuong_xas || x.co_so_san_xuat.phuong_xas;
          x.quan_huyens = x.quan_huyens || x.co_so_san_xuat.quan_huyens;
          x.ma_dinh_danh = x.ma_dinh_danh || x.co_so_san_xuat.ma_dinh_danh;
          x.loai_khu_cong_nghiep =
            x.loai_khu_cong_nghiep || x.co_so_san_xuat.loai_khu_cong_nghiep;
          x.loai_nganh_nghe =
            x.loai_nganh_nghe || x.co_so_san_xuat.loai_nganh_nghe;
          x.loai_hinh_o_nhiem =
            x.loai_hinh_o_nhiem || x.co_so_san_xuat.loai_hinh_o_nhiem;
        }

        // Đảm bảo mảng tồn tại
        x.ket_qua_phan_tich_moi_truong = x.ket_qua_phan_tich_moi_truong || [];
        x.ket_qua_quan_trac = x.ket_qua_quan_trac || [];
        x.ket_qua_thanh_tra_nuoc_thais = x.ket_qua_thanh_tra_nuoc_thais || [];
        x.ket_qua_thanh_tra_khi_thais = x.ket_qua_thanh_tra_khi_thais || [];
        x.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats =
          x.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats || [];
        x.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t =
          x.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t || [];
        x.ket_qua_thanh_tra_chat_thai_nguy_hai =
          x.ket_qua_thanh_tra_chat_thai_nguy_hai || [];
        x.ket_qua_thanh_tra_thu_tuc_hanh_chinhs =
          x.ket_qua_thanh_tra_thu_tuc_hanh_chinhs || [];

        this.form_data.co_so_san_xuats.push(x);
      });

      this.currentTab =
        this.form_data.co_so_san_xuats.length > 0
          ? this.form_data.co_so_san_xuats[0].id
          : undefined;
    },
  },
};
</script>
<style scoped>
.draggable-box {
  position: fixed !important;
  z-index: 9999;
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
  /* Sử dụng flexbox để sắp xếp header và content */
  display: flex;
  flex-direction: column;
}

/* Header của cửa sổ */
.draggable-header {
  display: flex;
  justify-content: space-between; /* Đẩy tiêu đề và nút ra 2 phía */
  align-items: center; /* Căn giữa theo chiều dọc */
  padding: 10px 15px;
  background-color: #f7f7f7;
  border-bottom: 1px solid #ddd;
  border-top-left-radius: 8px; /* Bo góc cho header */
  border-top-right-radius: 8px;
  cursor: move; /* Con trỏ di chuyển khi hover vào header */
  flex-shrink: 0; /* Không cho header co lại */
}

.header-title {
  margin: 0;
  font-size: 16px;
  font-weight: 600;
  color: #333;
}

.close-btn {
  background: transparent;
  border: none;
  font-size: 24px;
  font-weight: bold;
  line-height: 1;
  color: #888;
  cursor: pointer;
  padding: 0 5px;
}
.close-btn:hover {
  color: #000;
}

/* Wrapper cho nội dung */
.draggable-content-wrapper {
  flex-grow: 1;
  overflow-y: auto;
  padding: 0px 5px 0px 5px;
  min-height: 0;
  user-select: text;
  -webkit-user-select: text;
  -moz-user-select: text;
  -ms-user-select: text;
}

/* Tùy chỉnh các handle để thay đổi kích thước */
.draggable-box >>> .handle {
  background: transparent !important;
  border: none !important;
  width: 16px !important;
  height: 16px !important;
  box-sizing: border-box;
}

/* Thêm con trỏ chuột tương ứng cho từng handle */
.draggable-box >>> .handle-tl,
.draggable-box >>> .handle-br {
  cursor: nwse-resize;
}
.draggable-box >>> .handle-tr,
.draggable-box >>> .handle-bl {
  cursor: nesw-resize;
}
.draggable-box >>> .handle-tm,
.draggable-box >>> .handle-bm {
  cursor: ns-resize;
}
.draggable-box >>> .handle-ml,
.draggable-box >>> .handle-mr {
  cursor: ew-resize;
}

.draggable-box >>> .handle-tl {
  top: -8px;
  left: -8px;
}
.draggable-box >>> .handle-tm {
  top: -8px;
}
.draggable-box >>> .handle-tr {
  top: -8px;
  right: -8px;
}
.draggable-box >>> .handle-ml {
  left: -8px;
}
.draggable-box >>> .handle-mr {
  right: -8px;
}
.draggable-box >>> .handle-bl {
  bottom: -8px;
  left: -8px;
}
.draggable-box >>> .handle-bm {
  bottom: -8px;
}
.draggable-box >>> .handle-br {
  bottom: -8px;
  right: -8px;
}
.processing-section {
  text-align: center;
  padding: 50px;
  color: #333;
}
.spinner {
  border: 8px solid #f3f3f3;
  border-top: 8px solid #001f3f;
  border-radius: 50%;
  width: 60px;
  height: 60px;
  animation: spin 1s linear infinite;
  margin: 0 auto 20px auto;
}
@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
