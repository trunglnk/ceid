<template>
  <div class="row">
    <div class="col-lg-12 col-md-12" style="margin-bottom: 15px">
      <div class="col-md-6">
        <h2 class="title_master_form page-header-button-header">
          Thêm mới cơ sở sản xuất
        </h2>
      </div>
      <div class="col-md-6">
        <button
          type="submit"
          id="onsubmit"
          class="btn bg-olive btn-flat pull-right margin-top margin-left"
          tabindex="22"
          @click="addCoSoSanXuat()"
        >
          <i class="fa fa-plus"></i> Thêm mới
        </button>
        <a
          href="/danhsachcoso"
          class="btn btn-default btn-flat pull-right margin-top"
        >
          <i class="fa fa-undo"></i> Trở lại
        </a>
      </div>
    </div>
    <div class="col-sm-12 col-lg-4">
      <div class="block-multiple-rows padding-left padding-right">
        <div class="row">
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
            <multiselect
              v-model="chuDauTu"
              label="ten"
              track-by="id"
              placeholder="Gõ tên chủ đầu tư"
              selectLabel="Nhấn enter để chọn"
              deselectLabel="Nhấn enter để bỏ chọn"
              selectedLabel="Đã chọn"
              open-direction="bottom"
              :options="chudautus"
              :multiple="false"
              :searchable="true"
              :close-on-select="true"
              :options-limit="300"
              :limit="3"
              :max-height="600"
              :show-no-results="false"
              :hide-selected="false"
              :clearOnSelect="true"
            >
              <span slot="noResult">Không tìm thấy thông tin</span>
            </multiselect>
          </div>
          <div class="col-md-12" style="margin-top: 10px">
            <label>Tên&nbsp;<span style="color: red">*</span></label>
            <textarea
              v-model="co_so_san_xuat.ten"
              type="text"
              class="form-control"
              tabindex="1"
              autofocus
              placeholder="Tên cơ sở sản xuất"
            />
          </div>
          <div class="col-md-12">
            <label>Mã định danh</label>
            <input
              v-model="co_so_san_xuat.ma_dinh_danh"
              type="text"
              class="form-control"
              tabindex="1"
              autofocus
              placeholder="Mã định danh"
            />
          </div>
          <div class="col-md-12 mt-3">
            <label
              >Loại hình cơ sở &nbsp;<span style="color: red"></span
            ></label>
            <multiselect
              v-model="co_so_san_xuat.loai_khu_cong_nghiep"
              open-direction="bottom"
              placeholder="Chọn một loại hình"
              label="ten"
              track-by="ma"
              selectLabel="Nhấn enter để chọn"
              deselectLabel="Nhấn enter để bỏ chọn"
              selectedLabel="Đã chọn"
              :options="loaiHinhs"
              :multiple="false"
              :searchable="true"
            >
            </multiselect>
          </div>

          <div class="col-md-12 mt-4">
            <div class="lead">Địa chỉ hoạt động</div>
            <label>Thuộc KCN, CCN, KCX, KCNC, làng nghề</label>
            <multiselect
              v-model="co_so_san_xuat.khu_cong_nghiep"
              label="ten"
              track-by="id"
              placeholder="Gõ tên khu công nghiệp"
              selectLabel="Nhấn enter để chọn"
              deselectLabel="Nhấn enter để bỏ chọn"
              selectedLabel="Đã chọn"
              open-direction="bottom"
              :options="khucongnghieps"
              :multiple="false"
              :searchable="true"
              :loading="is_loading"
              :internal-search="true"
              :clear-on-select="false"
              :close-on-select="true"
              :options-limit="300"
              :limit="3"
              :max-height="600"
              :show-no-results="false"
              :hide-selected="false"
              :tabindex="1"
              :clearOnSelect="true"
              @search-change="asyncFindKhuCongNghiep"
              @input="changeKCN"
            >
              <span slot="noResult">Không tìm thấy kết quả</span>
            </multiselect>
            <div class="row">
              <div class="col-md-12">
                <label>Tỉnh<span style="color: red">*</span></label>
                <multiselect
                  v-model="co_so_san_xuat.tinh_thanhs"
                  label="ten"
                  track-by="id"
                  placeholder="Gõ tên Tỉnh thành"
                  selectLabel="Nhấn enter để chọn"
                  deselectLabel="Nhấn enter để bỏ chọn"
                  selectedLabel="Đã chọn"
                  open-direction="bottom"
                  :options="tinhs"
                  :multiple="true"
                  :searchable="true"
                  :loading="is_loading"
                  :internal-search="true"
                  :clear-on-select="false"
                  :close-on-select="true"
                  :options-limit="300"
                  :limit="3"
                  :max-height="600"
                  :show-no-results="false"
                  :hide-selected="false"
                  :tabindex="1"
                  :clearOnSelect="true"
                  @input="changeTinh()"
                >
                  <span slot="noResult">Không tìm thấy kết quả</span>
                </multiselect>
              </div>

              <div class="col-md-12">
                <label>Huyện</label>
                <multiselect
                  v-model="co_so_san_xuat.quan_huyens"
                  label="ten"
                  track-by="id"
                  placeholder="Gõ tên huyện"
                  selectLabel="Nhấn enter để chọn"
                  deselectLabel="Nhấn enter để bỏ chọn"
                  selectedLabel="Đã chọn"
                  open-direction="bottom"
                  :options="currentHuyens"
                  :multiple="true"
                  :searchable="true"
                  :loading="is_loading"
                  :internal-search="true"
                  :clear-on-select="false"
                  :close-on-select="true"
                  :options-limit="300"
                  :limit="3"
                  :max-height="600"
                  :show-no-results="false"
                  :hide-selected="false"
                  :tabindex="1"
                  :clearOnSelect="true"
                  @input="changeHuyen(co_so_san_xuat)"
                >
                  <span slot="noResult">Không tìm thấy kết quả</span>
                </multiselect>
              </div>
              <div class="col-md-12">
                <label>Xã</label>
                <multiselect
                  v-model="co_so_san_xuat.phuong_xas"
                  id="ajax"
                  label="ten"
                  track-by="id"
                  placeholder="Xã phường"
                  selectLabel="Nhấn enter để chọn"
                  deselectLabel="Nhấn enter để bỏ chọn"
                  selectedLabel="Đã chọn"
                  open-direction="bottom"
                  :options="phuongXas"
                  :multiple="true"
                  :searchable="true"
                  :loading="is_loading"
                  :internal-search="false"
                  :clear-on-select="false"
                  :close-on-select="true"
                  :options-limit="300"
                  :limit="3"
                  :max-height="600"
                  :show-no-results="false"
                  :hide-selected="false"
                  :tabindex="1"
                  :clearOnSelect="true"
                >
                  <span slot="noResult">Không tìm thấy kết quả</span>
                </multiselect>
              </div>
            </div>
            <label>Tọa độ</label>
            <div class="row">
              <div class="col-md-6">
                <input
                  v-model="co_so_san_xuat.kinh_do"
                  type="text"
                  class="form-control"
                  placeholder="Kinh độ"
                  tabindex="1"
                />
              </div>
              <div class="col-md-6">
                <input
                  v-model="co_so_san_xuat.vi_do"
                  type="text"
                  class="form-control"
                  placeholder="Vĩ độ"
                  tabindex="1"
                />
              </div>
            </div>
            <label>Địa chỉ hoạt động <span style="color: red">*</span></label>
            <textarea
              v-model="co_so_san_xuat.dia_chi_hoat_dong"
              type="text"
              class="form-control"
              tabindex="19"
              placeholder="Nhập địa chỉ hoạt động"
            ></textarea>
            <label>Địa chỉ liên hệ</label>
            <textarea
              v-model="co_so_san_xuat.dia_chi_lien_he"
              type="text"
              class="form-control"
              tabindex="18"
              placeholder="Nhập địa chỉ liên hệ"
            ></textarea>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12 col-lg-8">
      <div class="row block-multiple-rows">
        <div class="col-lg-8" style="margin-top: 5px">
          <label>THÔNG TIN CƠ SỞ THANH TRA</label>
          <p class="text-muted">
            (Thông tin chung của kết luận thanh tra gần nhất)
          </p>
          <hr style="margin-top: 7px; margin-bottom: 7px" />
        </div>
        <div
          class="col-lg-4"
          style="margin-top: 5px"
          v-if="
            usersystem.role_code == 'admin' ||
            usersystem.role_code == 'nhanvientrungtam' ||
            usersystem.role_code == 'adminvaquanlydanhmuc'
          "
        ></div>
        <div class="col-lg-12">
          <hr style="margin-top: 7px; margin-bottom: 7px" />
        </div>
        <div class="col-md-12 mb-3" v-if="isLoaiHinhCoSoKCN" key="'kcn'">
          <div class="row">
            <div class="col-md-4">
              <label>Tỷ lệ lấp đầy (%)</label>
              <ejs-numerictextbox
                class="form-control"
                format=".###"
                v-model="co_so_san_xuat.ty_le_lap_day"
                type="text"
              ></ejs-numerictextbox>
            </div>

            <div class="col-md-4">
              <label>Tổng diện tích</label>
              <ejs-numerictextbox
                class="form-control"
                format=".###"
                v-model="co_so_san_xuat.tong_dien_tich"
                type="text"
              ></ejs-numerictextbox>
            </div>

            <div class="col-md-4">
              <label>Đơn vị diện tích</label>
              <multiselect
                v-model="co_so_san_xuat.chuyen_doi_don_vi"
                label="ten"
                track-by="id"
                placeholder="Gõ đơn vị"
                selectLabel="Nhấn enter để chọn"
                deselectLabel="Nhấn enter để bỏ chọn"
                selectedLabel="Đã chọn"
                open-direction="bottom"
                :options="donvidientich"
                :multiple="false"
                :searchable="true"
                :clear-on-select="true"
                :close-on-select="true"
                :options-limit="300"
                :limit="3"
                :show-no-results="false"
                :hide-selected="false"
                :tabindex="1"
              >
                <span slot="noResult">Không tìm thấy kết quả</span>
              </multiselect>
            </div>
          </div>

          <div class="row" style="margin-top: 10px">
            <div class="col-md-4">
              <label>Diện tích đất CN</label>
              <ejs-numerictextbox
                class="form-control"
                format=".###"
                v-model="co_so_san_xuat.dien_tich_dat_cn"
                type="text"
              ></ejs-numerictextbox>
            </div>

            <div class="col-md-4">
              <label>Diện tích đất cho thuê</label>
              <ejs-numerictextbox
                class="form-control"
                format=".###"
                v-model="co_so_san_xuat.dien_tich_dat_cho_thue"
                type="text"
              ></ejs-numerictextbox>
            </div>

            <div class="col-md-4">
              <label>Diện tích đất cây xanh</label>
              <ejs-numerictextbox
                class="form-control"
                format=".###"
                v-model="co_so_san_xuat.dien_tich_dat_cay_xanh"
                type="text"
              ></ejs-numerictextbox>
            </div>
          </div>
        </div>

        <div class="col-md-12" :key="'default'">
          <div class="row">
            <div class="col-md-12">
              <label>Loại hình nguy cơ gây ô nhiễm</label>
              <multiselect
                v-model="co_so_san_xuat.loai_hinh_o_nhiem"
                label="ten"
                track-by="id"
                placeholder="Chọn các loại hình gây ô nhiễm"
                selectLabel="Nhấn enter để chọn"
                deselectLabel="Nhấn enter để bỏ chọn"
                selectedLabel="Đã chọn"
                open-direction="bottom"
                :options="loaiHinhONhiem"
                :multiple="true"
                :searchable="true"
                :clear-on-select="true"
                :close-on-select="true"
              >
                <span slot="noResult">Không tìm thấy kết quả</span>
              </multiselect>
            </div>

            <div class="col-md-3">
              <label>Diện tích&nbsp;</label>
              <ejs-numerictextbox
                class="form-control"
                format=".###"
                v-model="co_so_san_xuat.dien_tich"
                type="text"
              >
              </ejs-numerictextbox>
            </div>
            <div class="col-md-3">
              <label>Đơn vị đo</label>
              <multiselect
                v-model="co_so_san_xuat.don_vi_dien_tich"
                label="ten"
                track-by="id"
                placeholder="Gõ đơn vị"
                selectLabel="Nhấn enter để chọn"
                deselectLabel="Nhấn enter để bỏ chọn"
                selectedLabel="Đã chọn"
                open-direction="bottom"
                :options="donvidientich"
                :multiple="false"
                :searchable="true"
                :clear-on-select="true"
                :close-on-select="true"
                :options-limit="300"
                :limit="3"
                :show-no-results="false"
                :hide-selected="false"
                :tabindex="1"
              >
                <span slot="noResult">Không tìm thấy kết quả</span>
              </multiselect>
            </div>
            <div class="col-md-3">
              <label>Lượng nước sử dụng</label>
              <ejs-numerictextbox
                class="form-control"
                format=".###"
                v-model="co_so_san_xuat.luong_nuoc_su_dung"
                type="text"
              >
              </ejs-numerictextbox>
            </div>
            <div class="col-md-3">
              <label>Đơn vị đo</label>
              <multiselect
                v-model="co_so_san_xuat.don_vi_nuoc_su_dung"
                label="ten"
                track-by="id"
                placeholder="Gõ đơn vị"
                selectLabel="Nhấn enter để chọn"
                deselectLabel="Nhấn enter để bỏ chọn"
                selectedLabel="Đã chọn"
                open-direction="bottom"
                :options="donvinuoc"
                :multiple="false"
                :searchable="true"
                :clear-on-select="true"
                :close-on-select="true"
                :options-limit="300"
                :limit="3"
                :show-no-results="false"
                :hide-selected="false"
                :tabindex="1"
              >
                <span slot="noResult">Không tìm thấy kết quả</span>
              </multiselect>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label
                >Số lượng lao động&nbsp;<span style="color: #d81b60">
                  (người)</span
                ></label
              >
              <input
                class="form-control"
                v-model="co_so_san_xuat.so_nguoi_lao_dong"
                type="number"
              />
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>Nguyên liệu chính sử dụng</label>
              <textarea
                v-model="co_so_san_xuat.nguyen_lieu_chinh_su_dung"
                class="form-control"
                tabindex="1"
              ></textarea>
            </div>
            <div class="col-md-6">
              <label>Nhiên liệu chính sử dụng</label>
              <textarea
                v-model="co_so_san_xuat.nhien_lieu_chinh_su_dung"
                class="form-control"
                tabindex="1"
              ></textarea>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>Hóa chất sử dụng</label>
              <textarea
                v-model="co_so_san_xuat.hoa_chat_su_dung"
                class="form-control"
                tabindex="1"
              ></textarea>
            </div>
            <div class="col-md-6">
              <label>Nguồn nước sử dụng</label>
              <textarea
                v-model="co_so_san_xuat.nguon_nuoc_su_dung"
                class="form-control"
                tabindex="28"
              ></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="row block-multiple-rows">
        <div
          class="col-lg-12"
          style="margin-top: 5px"
          v-if="
            usersystem.role_code == 'admin' ||
            usersystem.role_code == 'nhanvientrungtam' ||
            usersystem.role_code == 'adminvaquanlydanhmuc'
          "
        >
          <label>THÔNG TIN LOẠI NGÀNH NGHỀ KINH TẾ</label>
          <p class="text-muted">
            (Chọn loại ngành nghề kinh tế ở Việt Nam, nhập thông tin Công suất
            thiết kế/Quy mô, công suất hoạt động/quy mô và nhấn + để thêm vào
            danh sách Loại ngành nghề kinh tế)
          </p>
          <hr style="margin-top: 7px; margin-bottom: 7px" />
        </div>
        <template
          v-if="
            usersystem.role_code == 'admin' ||
            usersystem.role_code == 'nhanvientrungtam' ||
            usersystem.role_code == 'adminvaquanlydanhmuc'
          "
        >
          <div class="col-md-12 mb-4">
            <div class="form-group">
              <label
                >Loại ngành nghề kinh tế&nbsp;<span style="color: red"
                  >*</span
                ></label
              >
              <treeselect
                :multiple="true"
                :options="loaiHinhNganhNghes"
                placeholder="Chọn một loại ngành nghề kinh tế kinh tế ở Việt Nam"
                v-model="co_so_san_xuat.loai_nganh_nghes"
                value-format="object"
                v-validate="'required'"
                :show-count="true"
              >
                <label
                  slot="option-label"
                  slot-scope="{
                    node,
                    shouldShowCount,
                    count,
                    labelClassName,
                    countClassName,
                  }"
                  :class="labelClassName"
                >
                  {{ node.raw ? node.raw.ma : "" }}. {{ node.label }}
                  <span v-if="shouldShowCount" :class="countClassName"
                    >({{ count }})</span
                  >
                </label>
              </treeselect>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>
<script>
import Multiselect from "vue-multiselect";
import * as env from "../env.js";
import datePicker from "vue-bootstrap-datetimepicker";
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import MessageService from "../services/MessageService";

export default {
  components: { Multiselect, datePicker, Treeselect },
  props: {
    usersystem: {},
    optionsDate: {},
    coquantochucs: { type: Array, default: () => [] },
    tinhs: { type: Array, default: () => [] },
    huyens: { type: Array, default: () => [] },
    chudautus: { type: Array, default: () => [] },
    loai_hinh_co_so_tree_listing: { type: Array, default: () => [] },
    keyLoaiHinhCoSo: {
      type: String,
      default: "loai_nganh_nghes",
    },
  },
  data: () => ({
    khucongnghieps: [],
    loaiHinhONhiem: [],
    form: {},
    chuDauTu: null,
    is_loading: false,
    loaiHinhNganhNghes: [],
    donvicongsuatthietkes: [],
    donvicongsuathoatdongs: [],
    donvidientich: [],
    donvinuoc: [],
    loaiHinhs: [],
    phuongXas: [],
    is_loading_cdt: false,
    co_so_san_xuat: {
      ten: null,
      dia_chi_lien_he: null,
      dia_chi_hoat_dong: null,
      organization_id: null,
      created_by: null,
      updated_by: null,
      trang_thai_dong_bo: null,
      loai_du_lieu: null,
      nhien_lieu_chinh_su_dung: null,
      hoa_chat_su_dung: null,
      nguyen_lieu_chinh_su_dung: null,
      ma_dinh_danh: null,
      loai_khu_cong_nghiep: null,
      luong_nuoc_su_dung: null,
      dien_tich: null,
      nguon_nuoc_su_dung: null,
      kinh_do: null,
      vi_do: null,
      so_nguoi_lao_dong: null,
      phuong_xas: [],
      tinh_thanhs: [],
      quan_huyens: [],
      loai_nganh_nghe: [],
      loai_hinh_o_nhiem: [],
      // ====== THÊM MỚI ======
      ty_le_lap_day: null,
      tong_dien_tich: null,
      chuyen_doi_don_vi: null,
      dien_tich_dat_cn: null,
      dien_tich_dat_cho_thue: null,
      dien_tich_dat_cay_xanh: null,
    },
  }),
  computed: {
    currentHuyens() {
      if (
        !this.co_so_san_xuat.tinh_thanhs ||
        this.co_so_san_xuat.tinh_thanhs.length == 0
      ) {
        return this.huyens;
      }
      const tinh_thanh_ids = this.co_so_san_xuat.tinh_thanhs.map((el) => el.id);
      return this.huyens.filter((huyen) =>
        tinh_thanh_ids.includes(huyen.tinh_thanh_id)
      );
    },

    isLoaiHinhCoSoKCN() {
      const loai = this.co_so_san_xuat.loai_khu_cong_nghiep;
      return loai && loai.ma_muc === "02";
    },
  },
  mounted() {
    this.getCoSoTreeSelect();
    this.getLoaiHinhCoSo();
    this.getLoaiHinhONhiem();
    this.getDonVi();
  },
  methods: {
    getDonVi() {
      axios
        .get(env.endpointhttp + "danhmuc/chuyendoidonvis")
        .then((response) => {
          console.log("donvi", response.data.result.don_vi);

          this.donvicongsuathoatdongs =
            response.data.result.don_vi.cong_suat_hoat_dong;
          this.donvicongsuatthietkes =
            response.data.result.don_vi.cong_suat_thiet_ke;
          this.donvinuoc = response.data.result.don_vi.luu_luong_nuoc_thai;
          this.donvidientich = response.data.result.don_vi.dien_tich;
        });
    },

    getLoaiHinhCoSo() {
      this.loaiHinhCoSo = null;
      axios.get(env.endpointhttp + "loaikhucongnghieps").then((response) => {
        this.loaiHinhs = response.data.result;
        console.log("this.loaiHinhs", this.loaiHinhs);
      });
    },
    getLoaiHinhONhiem() {
      axios.get(env.endpointhttp + "loaihinhoinhiem").then((response) => {
        this.loaiHinhONhiem = response.data;
      });
    },
    getCoSoTreeSelect() {
      axios.get(env.endpointhttp + "treeloaihinh").then((response) => {
        this.loaiHinhNganhNghes = response.data;
      });
    },
    changeKCN() {
      const kcn = this.co_so_san_xuat.khu_cong_nghiep;
      if (!kcn) return;

      // 1. Set tỉnh (mảng)
      const tinh = this.tinhs.find((t) => t.id === kcn.tinh_thanh_id);
      this.co_so_san_xuat.tinh_thanhs = tinh ? [tinh] : [];

      // 2. Set huyện (mảng)
      const huyen = this.huyens.find((h) => h.id === kcn.quan_huyen_id);
      this.co_so_san_xuat.quan_huyens = huyen ? [huyen] : [];

      // 3. Load xã theo huyện
      axios
        .get(env.endpointhttp + "async-phuong-xa", {
          params: { quan_huyen: [kcn.quan_huyen_id] },
        })
        .then((res) => {
          this.phuongXas = res.data;

          if (kcn.xa_phuong) {
            const xa = res.data.find((x) => x.id === kcn.xa_phuong.id);
            this.co_so_san_xuat.phuong_xas = xa ? [xa] : [];
          } else {
            this.co_so_san_xuat.phuong_xas = [];
          }
        });

      // 4. Set địa chỉ hoạt động từ KCN
      this.co_so_san_xuat.dia_chi_hoat_dong = kcn.dia_chi;
    },

    asyncFindKhuCongNghiep(query) {
      if (query) {
        this.is_loading = true;
        axios
          .get(env.endpointhttp + "asynkhucongnghiep?search=" + query)
          .then((response) => {
            this.khucongnghieps = response.data.result;
          })
          .catch((error) => {})
          .finally(() => (this.is_loading = false));
      }
    },
    getTenDiaChi(value) {
      this.co_so_san_xuat.dia_chi_hoat_dong = value;
    },
    changeHuyen: function (co_so_san_xuat) {
      const quan_huyen_ids = co_so_san_xuat.quan_huyens
        ? co_so_san_xuat.quan_huyens.map((el) => el.id)
        : [];
      if (co_so_san_xuat.quan_huyens && co_so_san_xuat.quan_huyens.length > 0) {
        axios
          .get(env.endpointhttp + "async-phuong-xa", {
            params: { quan_huyen: quan_huyen_ids },
          })
          .then((response) => {
            this.phuongXas = response.data;
          })
          .catch((error) => {});
      } else {
        this.phuongXas = [];
      }
      if (this.co_so_san_xuat.phuong_xas) {
        this.co_so_san_xuat.phuong_xas = this.co_so_san_xuat.phuong_xas.filter(
          (el) => quan_huyen_ids.includes(el.quan_huyen_id)
        );
      }
    },
    changeTinh() {
      const tinh_thanh_ids = this.co_so_san_xuat.tinh_thanhs
        ? this.co_so_san_xuat.tinh_thanhs.map((el) => el.id)
        : [];
      this.co_so_san_xuat.quan_huyens = this.co_so_san_xuat.quan_huyens
        ? this.co_so_san_xuat.quan_huyens.filter((el) =>
            tinh_thanh_ids.includes(el.tinh_thanh_id)
          )
        : null;
    },
    addcshd() {
      if (!this.form.loai_hinh_co_so) {
        return;
      }
      if (
        !this.co_so_san_xuat[this.keyLoaiHinhCoSo] ||
        this.co_so_san_xuat[this.keyLoaiHinhCoSo].length == 0
      ) {
        this.co_so_san_xuat[this.keyLoaiHinhCoSo] = [];
      }
      this.co_so_san_xuat[this.keyLoaiHinhCoSo].push({
        don_vi_thiet_ke: this.form.don_vi_cong_suat_thiet_ke,
        don_vi_hoat_dong: this.form.don_vi_cong_suat_hoat_dong,
        loai_hinh_co_so: this.form.loai_hinh_co_so,
        loai_hinh_co_so_id: this.form.loai_hinh_co_so.id,
        don_vi_hoat_dong_id: this.form.don_vi_cong_suat_hoat_dong
          ? this.form.don_vi_cong_suat_hoat_dong.id
          : null,
        thong_so_hoat_dong: this.form.cong_suat_hoat_dong,
        don_vi_thiet_ke_id: this.form.don_vi_cong_suat_thiet_ke
          ? this.form.don_vi_cong_suat_thiet_ke.id
          : null,
        thong_so_thiet_ke: this.form.cong_suat_thiet_ke,
        thong_so: this.form.cong_suat_thiet_ke,
      });
      this.form = {};
    },
    deletecshd(index) {
      this.co_so_san_xuat[this.keyLoaiHinhCoSo].splice(index, 1);
    },
    async addCoSoSanXuat() {
      try {
        this.co_so_san_xuat.organization_id =
          this.chuDauTu && this.chuDauTu.organization
            ? this.chuDauTu.organization.id
            : null;

        const payload = {
          ...this.co_so_san_xuat,
          chuyen_doi_don_vi_id: this.co_so_san_xuat.chuyen_doi_don_vi
            ? this.co_so_san_xuat.chuyen_doi_don_vi.id
            : null,
        };
        const response = await axios.post("/cososanxuat/add-co-so", payload);
        MessageService.showSuccessMessage("Thêm mới thành công");
        window.location.href = "/cososanxuat/update/" + response.data.data.id;
      } catch (error) {
        MessageService.showDangerMessage(
          error.response.data.message || "Thêm mới thất bại"
        );
      }
    },
    goDanhSachCDT() {
      window.open("/chudautu", "_blank");
    },
    goAddChuDauTu() {
      window.open("/chudautu/add/", "_blank");
    },
  },
};
</script>

<style scoped></style>
