<template>
  <div class="row block-multiple-rows">
    <div class="col-lg-12" style="margin-top: 5px;">
      <label>KẾT QUẢ THANH TRA THỦ TỤC HÀNH CHÍNH</label>
      <p class="text-muted">
        (Chọn loại thủ tục hành chính phù hợp và nhấn +thêm )
      </p>
      <hr style="margin-top: 7px; margin-bottom: 7px;" />
    </div>
    <div
      class="col-md-12"
      v-if="
        usersystem.role_code == 'admin' ||
          usersystem.role_code == 'nhanvientrungtam' ||
          usersystem.role_code == 'adminvaquanlydanhmuc'
      "
    >
      <div class="col-lg-2 col-md-6">
        <div class="form-group">
          <multiselect
            v-model="loaiThuTuc"
            label="name"
            track-by="code"
            @input="changeLoaiThuTuc"
            placeholder="Thủ tục hành chính"
            selectedLabel="Đã chọn"
            open-direction="bottom"
            :options="loaiThuTucs"
            :multiple="false"
            :searchable="true"
            :clear-on-select="true"
            :close-on-select="true"
            :show-no-results="false"
            :hide-selected="false"
          >
            <span slot="noResult">Không tìm thấy kết quả</span>
          </multiselect>
        </div>
      </div>
      <div class="col-lg-2 col-md-6">
        <div class="form-group">
          <multiselect
            v-model="loaiVanBan"
            label="ten"
            track-by="id"
            placeholder="Loại văn bản"
            selectedLabel="Đã chọn"
            open-direction="bottom"
            :options="loaiVanBanSelect"
            :multiple="false"
            :searchable="true"
            :clear-on-select="true"
            :close-on-select="true"
            :show-no-results="false"
            :hide-selected="false"
          >
            <span slot="noResult">Không tìm thấy kết quả</span>
          </multiselect>
        </div>
      </div>
      <div class="col-lg-2 col-md-6">
        <div class="form-group">
          <input
            type="text"
            class="form-control"
            v-model="soQuyetDinh"
            placeholder="Số QĐ phê duyệt"
          />
        </div>
      </div>
      <div class="col-lg-2 col-md-6">
        <date-picker
          v-model="thoiGianPheDuyet"
          tabindex
          placeholder="Ngày ra quyết định"
          :config="optionsDate"
        ></date-picker>
      </div>
      <div class="col-lg-3 col-md-10">
        <div class="form-group">
          <multiselect
            v-model="coQuanPheDuyet"
            label="ten"
            track-by="id"
            placeholder="Gõ tên cơ quan phê duyệt"
            selectedLabel="Đã chọn"
            open-direction="bottom"
            :options="coquantochucs"
            :multiple="false"
            :searchable="true"
            :clear-on-select="true"
            :close-on-select="true"
            :show-no-results="false"
            :hide-selected="false"
            :tabindex="1"
            @search-change="asyncFindCoQuanToChuc"
          >
            <span slot="noResult">Không tìm thấy kết quả</span>
          </multiselect>
        </div>
      </div>
      <div class="col-lg-1 col-md-2">
        <button
          type="button"
          class="btn btn-flat pull-right"
          tabindex="22"
          @click="addThuTuc()"
        >
          <i class="fa fa-plus"></i> Thêm
        </button>
      </div>
    </div>
    <div class="col-md-12">
      <table class="table table-hover table-responsive">
        <thead>
          <tr class="row-table-header">
            <th>Loại thủ tục hành chính</th>
            <th>Số QĐ phê duyệt</th>
            <th>Cơ quan phê duyệt</th>
            <th>Ngày ra quyết định phê duyệt</th>
            <th
              style="width: 5%; text-align: center;"
              v-if="
                usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'nhanvientrungtam' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
              "
            >
              Xóa
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(item, index) in co_so_san_xuat.danhsachthutuchanhchinh"
            :key="index"
          >
            <td>{{ item.loai_thu_tuc ? item.loai_thu_tuc.ten : null }}</td>
            <td>{{ item.so_quyet_dinh_phe_duyet }}</td>
            <td>
              {{ item.co_quan_to_chuc ? item.co_quan_to_chuc.ten : null }}
            </td>
            <td>{{ item.thoi_gian_phe_duyet }}</td>
            <td
              align="center"
              v-if="
                usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'nhanvientrungtam' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
              "
            >
              <a @click="removeThuTuc(index)">
                <i class="fa fa-trash-o"></i>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import moment from "../../../../../node_modules/admin-lte/bower_components/moment/moment.js";
import Multiselect from "vue-multiselect";
import datePicker from "vue-bootstrap-datetimepicker";
import * as env from "../../env.js";

export default {
  components: { Multiselect, datePicker },
  props: {
    index1: {},
    usersystem: {},
    value: { type: Object, default: () => ({}) },
    // coquantochucs: { type: Array, default: () => [] },
    optionsDate: {}
  },
  data: () => ({
    soQuyetDinh: null,
    coQuanPheDuyet: null,
    thoiGianPheDuyet: null,
    loaiThuTucs: [
      { name: "Văn bản ĐTM", code: "C_LoaiVanBanDTM" },
      { name: "Loại giấy phép môi trường", code: "C_LoaiGiayPhepMoiTruong" },
      { name: "Văn bản khác (cũ)", code: "khac" }
    ],
    loaiThuTuc: null,
    loaiVanBans: [],
    loaiVanBanSelect: [],
    loaiVanBan: null,
    coquantochucs: []
  }),
  computed: {
    co_so_san_xuat: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("input", value);
      }
    }
  },
  mounted() {
    this.getThuTucHanhChinh();
    this.asyncFindCoQuanToChuc('')
  },
  methods: {
    getThuTucHanhChinh() {
      axios.get(env.endpointhttp + "loaithutuchanhchinhs").then(response => {
        this.loaiVanBans = response.data.result;
      });
    },
    asyncFindCoQuanToChuc(query) {
      axios.get(env.endpointhttp + "coquantochucs?search=" + query).then((response) => {
        this.coquantochucs = response.data.result;
      });
    },
    changeLoaiThuTuc() {
      this.loaiVanBanSelect = [];
      if (this.loaiThuTuc) {
        this.loaiVanBanSelect = this.loaiVanBans.filter(
          el => el.phan_loai == this.loaiThuTuc.code
        );
      }
    },
    addThuTuc() {
      if (this.coQuanPheDuyet && this.soQuyetDinh && this.thoiGianPheDuyet) {
        const item = {
          co_quan_to_chuc: this.coQuanPheDuyet,
          so_quyet_dinh_phe_duyet: this.soQuyetDinh,
          loai_thu_tuc: this.loaiVanBan,
          thoi_gian_phe_duyet: this.thoiGianPheDuyet,
          loai_thu_tuc_hanh_chinh_id: this.loaiVanBan.id,
          co_quan_phe_duyet_id: this.coQuanPheDuyet.id
        };
        this.co_so_san_xuat.danhsachthutuchanhchinh.push(item);
      }
      this.coQuanPheDuyet = null;
      this.soQuyetDinh = null;
      this.thoiGianPheDuyet = null;
      this.loaiThuTuc = null;
      this.loaiVanBan = null;
    },
    removeThuTuc(index) {
      this.co_so_san_xuat.danhsachthutuchanhchinh.splice(index, 1);
    }
  }
};
</script>

<style scoped></style>
