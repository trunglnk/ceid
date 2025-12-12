<template>
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="row ma-2">
          <div class="col-md-2">
            <label>Mã chủ đầu tư (*)</label>
            <input
              v-model="form.ma_dinh_danh"
              type="text"
              class="form-control"
              placeholder="Mã chủ đầu tư"
              name="ma"
              v-validate="'required'"
            />
            <span
              v-show="errors.has('ma')"
              class="help is-danger"
              style="color: red"
              >Mã chủ đầu tư không thể bỏ trống</span
            >
          </div>
          <div class="col-md-4">
            <label>Tên chủ đầu tư (*)</label>
            <input
              v-model="form.ten"
              type="text"
              class="form-control"
              placeholder="Tên cơ chủ đầu tư"
              name="ten"
              v-validate="'required'"
            />
            <span
              v-show="errors.has('ten')"
              class="help is-danger"
              style="color: red"
              >Tên chủ đầu tư không thể bỏ trống</span
            >
          </div>
          <div class="col-md-3">
            <label>Số điện thoại</label>
            <input
              v-model="form.so_dien_thoai"
              type="text"
              class="form-control"
              placeholder="Số điện thoại"
            />
          </div>
          <div class="col-md-3">
            <label>Email</label>
            <input
              v-model="form.email"
              type="text"
              class="form-control"
              placeholder="Email thư điện tử"
            />
          </div>
        </div>
        <div class="row ma-2 mt-4">
          <div class="col-md-6">
            <label>Người đại diện</label>
            <input
              v-model="form.nguoi_dai_dien"
              type="text"
              class="form-control"
              placeholder="Người đại diện"
            />
          </div>
          <div class="col-md-3">
            <label>Mã số QLCTNH</label>
            <input
              v-model="form.ma_so_qlctnh"
              type="text"
              class="form-control"
              placeholder="Mã số QLCTNH"
            />
          </div>
          <div class="col-md-3">
            <label>Số Fax</label>
            <input
              v-model="form.fax"
              type="text"
              class="form-control"
              placeholder="Số fax"
            />
          </div>
        </div>
        <div class="row ma-2 mt-4">
          <div class="col-md-3">
            <label>Tỉnh thành</label>
            <!-- <input v-model="tinhThanhSelect.ten" type="text" class="form-control" placeholder="Tỉnh thành"
              /> -->
            <multiselect
              v-model="tinhThanhSelect"
              label="ten"
              track-by="id"
              placeholder="Gõ tên Tỉnh thành"
              selectLabel="Nhấn enter để chọn"
              deselectLabel="Nhấn enter để bỏ chọn"
              :options="tinhs"
              :multiple="false"
              :searchable="true"
              :disabled="false"
            >
            </multiselect>
          </div>
          <div class="col-md-3">
            <label>Quận huyện</label>
            <!-- <input v-model="quanHuyenSelect.ten" type="text" class="form-control" placeholder="Quận huyện"
               /> -->
            <multiselect
              v-model="quanHuyenSelect"
              label="ten"
              track-by="id"
              placeholder="Gõ tên quận huyện"
              selectLabel="Nhấn enter để chọn"
              deselectLabel="Nhấn enter để bỏ chọn"
              :options="quanHuyenOption"
              :multiple="false"
              :searchable="true"
              :disabled="false"
            >
              <span slot="noResult">Không tìm thấy kết quả</span>
            </multiselect>
          </div>
          <div class="col-md-3">
            <label>Xã phường</label>
            <!-- <input v-model="form.xa_phuong" type="text" class="form-control" placeholder="Xã phường"/> -->
            <multiselect
              v-model="xaPhuongSelect"
              label="ten"
              track-by="id"
              placeholder="Gõ tên xã/phường"
              selectLabel="Nhấn enter để chọn"
              deselectLabel="Nhấn enter để bỏ chọn"
              :options="xaPhuongOption"
              :multiple="false"
              :searchable="true"
              :disabled="false"
            >
              <span slot="noResult">Không tìm thấy kết quả</span>
            </multiselect>
          </div>
          <div class="col-md-3">
            <label>Địa chỉ</label>
            <input
              v-model="form.dia_chi"
              type="text"
              class="form-control"
              placeholder="Địa chỉ - số nhà"
            />
          </div>
        </div>
        <div style="border: 1px solid #d7dbdd" class="ma-4 pb-3">
          <div
            class="pl-4 pt-2"
            style="color: #2471a3; font-size: 16px; font-weight: bold"
          >
            Giấy chứng nhận kinh doanh
          </div>
          <div class="row ma-1 mt-4">
            <div class="col-md-6">
              <label>Số giấy chứng nhận kinh doanh</label>
              <input
                v-model="form.so_giay_chung_nhan_dang_ky_kinh_doanh"
                type="text"
                class="form-control"
                placeholder="Nhập số giấy chứng nhận đăng ký kinh doanh"
              />
            </div>
            <div class="col-md-6">
              <label>Cơ quan cấp cấp</label>
              <multiselect
                v-model="coquanSelect"
                label="ten"
                track-by="id"
                placeholder="Gõ tên cơ quan"
                selectLabel="Nhấn enter để chọn"
                deselectLabel="Nhấn enter để bỏ chọn"
                :options="coquans"
                :multiple="false"
                :searchable="true"
              >
              </multiselect>
            </div>
          </div>

          <div class="row ma-1 mt-4">
            <div class="col-md-3">
              <label>Ngày cấp</label>
              <input
                v-model="form.ngay_cap_giay_chung_nhan_dang_ky_kinh_doanh"
                type="date"
                class="form-control"
              />
            </div>
            <div class="col-md-3">
              <label>Lần cấp</label>
              <input
                v-model="form.lan_cap_giay_chung_nhan_dang_ky_kinh_doanh"
                type="number"
                class="form-control"
                placeholder="lần cấp thứ"
              />
            </div>
            <div class="col-md-3">
              <label>Ngày cấp lần đầu</label>
              <input
                v-model="
                  form.ngay_cap_lan_dau_giay_chung_nhan_dang_ky_kinh_doanh
                "
                type="date"
                class="form-control"
              />
            </div>
          </div>
        </div>
        <div style="border: 1px solid #d7dbdd" class="ma-4 pb-3">
          <div
            class="pl-4 pt-2"
            style="color: #2471a3; font-size: 16px; font-weight: bold"
          >
            Giấy chứng nhận đầu tư
          </div>
          <div class="row ma-1 mt-4">
            <div class="col-md-6">
              <label>Số giấy chứng nhận đầu tư</label>
              <input
                v-model="form.so_giay_chung_nhan_dau_tu"
                type="text"
                class="form-control"
                placeholder="Nhập số giấy chứng nhận đầu tư"
              />
            </div>
            <div class="col-md-6">
              <label>Nơi cấp giấy chứng nhận đầu tư</label>
              <multiselect
                v-model="noicapSelect"
                label="ten"
                track-by="id"
                placeholder="Gõ tên cơ quan"
                selectLabel="Nhấn enter để chọn"
                deselectLabel="Nhấn enter để bỏ chọn"
                :options="noicaps"
                :multiple="false"
                :searchable="true"
              >
              </multiselect>
            </div>
          </div>

          <div class="row ma-1 mt-4">
            <div class="col-md-3">
              <label>Ngày cấp</label>
              <input
                v-model="form.ngay_cap_giay_chung_nhan_dau_tu"
                type="date"
                class="form-control"
              />
            </div>
            <div class="col-md-3">
              <label>Lần cấp</label>
              <input
                v-model="form.lan_cap_giay_chung_nhan_dau_tu"
                type="number"
                class="form-control"
                placeholder="lần cấp thứ"
              />
            </div>
            <div class="col-md-3">
              <label>Ngày cấp lần đầu</label>
              <input
                v-model="form.ngay_cap_lan_dau_giay_chung_nhan_dau_tu"
                type="date"
                class="form-control"
              />
            </div>
          </div>
        </div>
        <div class="row ma-2">
          <div class="col-md-12">
            <label>Ghi chú</label>
            <input
              v-model="form.ghi_chu"
              type="text"
              class="form-control"
              placeholder="Nhập ghi chú"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12 mt-3">
      <div style="font-weight: bold">Danh sách cơ sở sản xuất</div>
      <div class="mt-4">
        <div
          v-if="!toChucTrucThuoc || toChucTrucThuoc.length == 0"
          style="text-align: center"
        >
          Không có cơ sở sản xuất nào
        </div>
        <table class="table table-hover fixed_headers" v-else>
          <thead class="row-table-header">
            <tr class="row-table-header">
              <th style="width: 70px">STT</th>
              <th>Tên cơ sở</th>
              <th style="width: 70px">Xóa</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in toChucTrucThuoc" :key="item.id">
              <td style="width: 70px">{{ index + 1 }}</td>
              <td
                @click="goEditCoSoSanXuat(item)"
                style="color: rgb(51, 122, 183); cursor: pointer"
              >
                {{ item.ten }}
              </td>
              <td align="center">
                <a @click="removeCoSo(item.id)">
                  <i class="fa fa-trash-o"></i>
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-md-12 mt-3">
      <button
        type="submit"
        id="onsubmit"
        class="btn bg-olive btn-flat pull-right"
        tabindex="22"
        @click="updateChuDauTu()"
      >
        <i class="fa fa-check"></i> Cập nhật
      </button>
      <a
        href="/chudautu"
        id="btnback"
        class="btn btn-default btn-flat"
        tabindex="31"
      >
        <i class="fa fa-undo"></i> Trở lại
      </a>
    </div>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import * as env from "../env.js";
import MessageService from "../services/MessageService";
import { ValidationProvider } from "vee-validate";

export default {
  props: ["tinhs", "quans", "xas", "coquans", "chitiet", "noicaps"],
  components: {
    Multiselect,
    ValidationProvider,
  },
  data: () => ({
    tinhThanhSelect: {},
    quanHuyenSelect: {},
    xaPhuongSelect: {},
    coquanSelect: {},
    coSoSelect: {},
    noicapSelect: null,
    quanHuyenOption: [],
    xaPhuongOption: [],
    is_loading: false,
    toChucs: [],
    toChucTrucThuoc: [],
    form: {
      ten: null,
      tinh_thanh_id: null,
      quan_huyen_id: null,
      email: null,
      nguoi_dai_dien: null,
      so_dien_thoai: null,
      fax: null,
      dia_chi: null,
      xa_phuong: null,
      email: null,
      ma_so_qlctnh: null,
      so_giay_chung_nhan_dang_ky_kinh_doanh: null,
      co_quan_cap_giay_chung_nhan_dang_ky_kinh_doanh: null,
      ngay_cap_giay_chung_nhan_dang_ky_kinh_doanh: null,
      lan_cap_giay_chung_nhan_dang_ky_kinh_doanh: null,
      ngay_cap_lan_dau_giay_chung_nhan_dang_ky_kinh_doanh: null,
      so_giay_chung_nhan_dau_tu: null,
      noi_cap_giay_chung_nhan_dau_tu: null,
      ngay_cap_giay_chung_nhan_dau_tu: null,
      lan_cap_giay_chung_nhan_dau_tu: null,
      ngay_cap_lan_dau_giay_chung_nhan_dau_tu: null,
      ghi_chu: null,
      co_quan_cap_giay_chung_nhan_dang_ky_kinh_doanh: null,
      noi_cap_giay_chung_nhan_dau_tu: null,
      ma_dinh_danh: null,
    },
    isFillingData: false,
  }),
  watch: {
    tinhThanhSelect(val) {
      if (this.isFillingData) return;
      this.quanHuyenOption = val
        ? this.quans.filter((el) => el.tinh_thanh_id == val.id)
        : [];

      // Reset 2 trường phụ thuộc
      this.quanHuyenSelect = null;
      this.xaPhuongOption = [];
      this.xaPhuongSelect = null;
    },

    // Khi đổi quận → cập nhật xã và reset xã
    quanHuyenSelect(val) {
      if (this.isFillingData) return;
      if (val) {
        this.tinhThanhSelect = this.tinhs.find(
          (el) => el.id == val.tinh_thanh_id
        );

        // cập nhật danh sách xã theo quận
        this.xaPhuongOption = this.xas.filter(
          (el) => el.quan_huyen_id == val.id
        );
      } else {
        this.xaPhuongOption = [];
      }

      // reset xã phường khi đổi quận
      this.xaPhuongSelect = null;
    },
  },

  created() {
    this.quanHuyenOption = this.quans;
    this.getData();
    console.log(this.chitiet);
  },
  methods: {
    goBack() {
      window.location.href = "/chudautu";
    },
    loadToChuc(query) {
      if (query) {
        this.is_loading = true;
        axios
          .get(env.endpointhttp + "asynctochuc?search=" + query)
          .then((response) => {
            this.toChucs = response.data.result;
          })
          .catch((error) => {})
          .finally(() => (this.is_loading = false));
      }
    },
    getData() {
      this.isFillingData = true;
      this.toChucTrucThuoc = [...this.chitiet.co_sos];
      if (this.chitiet.tinh_thanh_id) {
        this.tinhThanhSelect = this.tinhs.find(
          (el) => el.id == this.chitiet.tinh_thanh_id
        );
        // lọc lại danh sách huyện theo tỉnh
        this.quanHuyenOption = this.quans.filter(
          (el) => el.tinh_thanh_id == this.chitiet.tinh_thanh_id
        );
      } else {
        this.quanHuyenOption = [];
      }
      if (this.chitiet.quan_huyen_id) {
        this.quanHuyenSelect = this.quans.find(
          (el) => el.id == this.chitiet.quan_huyen_id
        );
        this.xaPhuongOption = this.xas.filter(
          (el) => el.quan_huyen_id == this.chitiet.quan_huyen_id
        );
      }
      if (this.chitiet.xa_phuong) {
        this.xaPhuongOption = this.xas.filter(
          (el) => el.quan_huyen_id == this.chitiet.quan_huyen_id
        );

        this.xaPhuongSelect =
          this.xaPhuongOption.find(
            (el) =>
              el.ten.trim().toLowerCase() ===
              this.chitiet.xa_phuong.trim().toLowerCase()
          ) || null;
      }
      for (const key in this.form) {
        this.form[key] = this.chitiet[key];
      }
      if (this.chitiet.co_quan_cap_giay_chung_nhan_dang_ky_kinh_doanh) {
        this.coquanSelect = this.coquans.find(
          (el) =>
            el.id == this.chitiet.co_quan_cap_giay_chung_nhan_dang_ky_kinh_doanh
        );
      }
      if (this.chitiet.noi_cap_giay_chung_nhan_dau_tu) {
        this.noicapSelect = this.noicaps.find(
          (el) => el.id == this.chitiet.noi_cap_giay_chung_nhan_dau_tu
        );
      }

      this.$nextTick(() => {
        this.isFillingData = false;
      });
    },
    themCoSo() {
      if (this.coSoSelect) {
        let check = this.toChucTrucThuoc.find(
          (el) => el.id == this.coSoSelect.id
        );
        if (!check) {
          this.toChucTrucThuoc.push(this.coSoSelect);
        }
        this.coSoSelect = null;
      }
    },
    removeCoSo(id) {
      this.toChucTrucThuoc = this.toChucTrucThuoc.filter((el) => el.id != id);
    },
    updateChuDauTu() {
      this.$validator.validateAll().then((validate) => {
        this.form.tinh_thanh_id = this.tinhThanhSelect
          ? this.tinhThanhSelect.id
          : null;
        this.form.quan_huyen_id = this.quanHuyenSelect
          ? this.quanHuyenSelect.id
          : null;
        if (this.xaPhuongSelect && this.xaPhuongSelect.ten) {
          this.form.xa_phuong = this.xaPhuongSelect.ten;
        }
        this.form.co_quan_cap_giay_chung_nhan_dang_ky_kinh_doanh = this
          .coquanSelect
          ? this.coquanSelect.id
          : null;
        this.form.noi_cap_giay_chung_nhan_dau_tu = this.noicapSelect
          ? this.noicapSelect.id
          : null;
        this.form.co_sos = this.toChucTrucThuoc.map((el) => el.id);
        let idChuDauTu = this.chitiet.id;
        if (validate) {
          axios
            .put(env.endpointhttp + "chudautu/" + idChuDauTu, this.form)
            .then((response) => {
              MessageService.showSuccessMessage("Cập nhật thành công");
              this.goBack();
            });
        }
      });
    },
    goEditCoSoSanXuat(item) {
      window.location.href = `/cososanxuat/showformupdate/${item.id}`;
    },
  },
};
</script>

<style scoped>
input {
  background-color: white !important;
}
/* thead,
tfoot {
  display: table;
}
tbody {
  display: block;
  max-height: 500px;
  overflow-y: scroll;
} */
</style>
