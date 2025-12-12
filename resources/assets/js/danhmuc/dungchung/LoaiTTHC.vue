<template>
  <div class="timeline-body">
    <div class="col-md-12">
      <div class="nav-tabs-custom margin-top">
        <ul class="nav nav-tabs">
          <li class="active">
            <a
              href="#tab_dtm"
              data-toggle="tab"
              aria-expanded="true"
              @click="clickTab('C_LoaiVanBanDTM')"
              >Loại văn bản ĐTM</a
            >
          </li>
          <li>
            <a
              href="#tab_giayphep"
              data-toggle="tab"
              aria-expanded="false"
              @click="clickTab('C_LoaiGiayPhepMoiTruong')"
              >Loại giấy phép môi trường</a
            >
          </li>
          <li>
            <a
              href="#tab_khac"
              data-toggle="tab"
              aria-expanded="false"
              @click="clickTab('khac')"
              >Loại giấy thủ tục khác</a
            >
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab_dtm">
            <div
              style="
                display: flex;
                align-items: center;
                justify-content: space-between;
              "
            >
              <div v-if="load_thoi_gian">
                <div v-if="this.thoi_gian == ''">
                  Chưa đồng bộ với CSDL quốc gia
                </div>
                <div v-else>Đồng bộ lần cuối: {{ thoi_gian_dong_bo }}</div>
              </div>
              <div v-else>Đang đồng bộ với CSDL quốc gia</div>
              <div style="margin-bottom: 10px">
                <div style="width: 140px" class="ml-4">
                  <div
                    class="btn btn-flat btn-block btn-default d-flex align-center"
                    @click="dongbodulieu('C_LoaiVanBanDTM')"
                    style="height: 40px"
                    :disabled="!trangThaiDongBo"
                  >
                    <i class="fa fa-refresh" style="padding-right: 5px"></i>
                    Đồng bộ dữ liệu
                  </div>
                </div>
              </div>
            </div>
            <div
              class="pl-4 pr-4 pb-4"
              style="display: flex; justify-content: end; align-items: flex-end"
            >
              <div class="input-group">
                <input
                  type="text"
                  v-model="searchdata"
                  class="form-control"
                  style="z-index: 0; width: 400px"
                  placeholder="Tìm kiếm tên tỉnh thành"
                  v-on:keyup.enter="searchDTM()"
                />
                <span
                  class="input-group-addon"
                  style="cursor: pointer"
                  @click="searchDTM()"
                >
                  <i class="fa fa-search"></i>
                </span>
              </div>
            </div>
            <table class="table table-hover">
              <tbody>
                <tr class="row-table-header">
                  <th style="width: 10%">STT</th>
                  <th style="width: 15%">Mã mục</th>
                  <th>Tên</th>
                </tr>
                <tr v-for="(item, index) in loaiDTM" :key="item.data">
                  <td>{{ index + 1 }}</td>
                  <td>
                    {{ item.ma }}
                  </td>
                  <td>
                    {{ item.ten }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="tab-pane" id="tab_giayphep">
            <div
              style="
                display: flex;
                align-items: center;
                justify-content: space-between;
              "
            >
              <div v-if="load_thoi_gian">
                <div v-if="this.thoi_gian == ''">
                  Chưa đồng bộ với CSDL quốc gia
                </div>
                <div v-else>Đồng bộ lần cuối: {{ thoi_gian_dong_bo }}</div>
              </div>
              <div v-else>Đang đồng bộ với CSDL quốc gia</div>
              <div style="margin-bottom: 10px">
                <div style="width: 140px" class="ml-4">
                  <div
                    class="btn btn-flat btn-block btn-default d-flex align-center"
                    @click="dongbodulieu('C_LoaiGiayPhepMoiTruong')"
                    style="height: 40px"
                  >
                    <i class="fa fa-refresh" style="padding-right: 5px"></i>
                    Đồng bộ dữ liệu
                  </div>
                </div>
              </div>
            </div>
            <div
              class="pl-4 pr-4 pb-4"
              style="display: flex; justify-content: end; align-items: flex-end"
            >
              <div class="input-group">
                <input
                  type="text"
                  v-model="searchdata"
                  class="form-control"
                  style="z-index: 0; width: 400px"
                  placeholder="Tìm kiếm tên tỉnh thành"
                  v-on:keyup.enter="searchGiayphep()"
                />
                <span
                  class="input-group-addon"
                  style="cursor: pointer"
                  @click="searchGiayphep()"
                >
                  <i class="fa fa-search"></i>
                </span>
              </div>
            </div>
            <table class="table table-hover">
              <tbody>
                <tr class="row-table-header">
                  <th style="width: 10%">STT</th>
                  <th style="width: 15%">Mã mục</th>
                  <th>Tên</th>
                </tr>
                <tr v-for="(item, index) in giayPhepMT" :key="item.data">
                  <td>{{ index + 1 }}</td>
                  <td>
                    {{ item.ma }}
                  </td>
                  <td>
                    {{ item.ten }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="tab-pane" id="tab_khac">
            <div
              class="pl-4 pr-4 pb-4"
              style="display: flex; justify-content: end; align-items: flex-end"
            >
              <div class="input-group">
                <input
                  type="text"
                  v-model="searchdata"
                  class="form-control"
                  style="z-index: 0; width: 400px"
                  placeholder="Tìm kiếm tên tỉnh thành"
                  v-on:keyup.enter="searchKhac()"
                />
                <span
                  class="input-group-addon"
                  style="cursor: pointer"
                  @click="searchKhac()"
                >
                  <i class="fa fa-search"></i>
                </span>
              </div>
            </div>
            <table class="table table-hover">
              <tbody>
                <tr class="row-table-header">
                  <th style="width: 10%">STT</th>
                  <th style="width: 15%">Mã mục</th>
                  <th>Tên</th>
                  <th>Mô tả</th>
                  <th
                    style="width: 10%; text-align: center"
                    v-if="
                      usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  >
                    Xóa
                  </th>
                </tr>
                <tr v-for="(item, index) in thuTucKhac" :key="item.data">
                  <td>{{ index + 1 }}</td>
                  <td>
                    {{ item.ma }}
                  </td>
                  <td>
                    <textarea
                      class="form-control"
                      v-model="item.ten"
                      :disabled="!isEditable"
                      @change="
                        updateLoaiTTHC(item.ma, item.ten, item.mo_ta)
                      "
                      rows="2"
                    >
                    </textarea>
                  </td>
                  <td>
                    <textarea
                      class="form-control"
                      v-model="item.mo_ta"
                      :disabled="!isEditable"
                      @change="
                        updateLoaiTTHC(item.ma, item.ten, item.mo_ta)
                      "
                      rows="2"
                    >
                    </textarea>
                  </td>
                  <td
                    align="center"
                    v-if="
                      usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  >
                    <a @click="showConfirmDelete(item)">
                      <i class="fa fa-trash-o btn"></i
                    ></a>
                  </td>
                </tr>
                <tr
                  v-if="
                    usersystem.role_code == 'admin' ||
                    usersystem.role_code == 'adminvaquanlydanhmuc'
                  "
                >
                  <td></td>
                  <td>
                    <input
                      type="text"
                      class="form-control"
                      v-model="ma"
                      placeholder="Mã"
                    /><span
                      style="color: red"
                      v-text="error_add_loai_tthc_ma"
                    ></span>
                  </td>
                  <td>
                    <textarea
                      class="form-control"
                      v-model="ten"
                      :disabled="!isEditable"
                      rows="2"
                      placeholder="Tên"
                    >
                    </textarea>
                    <span
                      style="color: red"
                      v-text="error_add_loai_tthc_ten"
                    ></span>
                  </td>
                  <td>
                    <textarea
                      class="form-control"
                      v-model="mo_ta"
                      :disabled="!isEditable"
                      rows="2"
                      placeholder="Mô tả"
                    >
                    </textarea>
                  </td>
                  <td align="center">
                    <a class="btn btn-flat" @click="addLoaiTTHC()"
                      ><i class="fa fa-plus"></i> Thêm</a
                    >
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <modal-component
      width="450px"
      v-model="showModel"
      center
      @submit="deleteLoaiTTHC()"
      :title="'Xác nhận xóa'"
    >
      <div>Bạn có chắc chắn muốn xóa {{ thuTuc ? thuTuc.ten : "" }} không?</div>
    </modal-component>
  </div>
</template>

<script>
import * as env from "../../env.js";
import MessageService from "../../services/MessageService";

export default {
  data: function () {
    return {
      data: [],
      ten: null,
      ma: null,
      mo_ta: null,
      error_add_loai_tthc_ma: null,
      error_add_loai_tthc_ten: null,
      loaiDTM: [],
      giayPhepMT: [],
      thuTucKhac: [],
      phan_loai: "C_LoaiVanBanDTM",
      loaiTTHC: [],
      thoi_gian_dong_bo: null,
      load_thoi_gian: true,
      thoi_gian: "",
      showModel: false,
      thuTuc: {},
      searchdata: null,
      trangThaiDongBo: false,
    };
  },
  props: ["usersystem"],
  created() {
    this.getData();
    this.getThoiGianDongBo("giay_phep_mt");
    this.getThoiGianDongBo("van_ban_DTM");
    this.getTrangThaiDongBo();
  },
  computed: {
    isEditable() {
      return (
        this.usersystem.role_code == "admin" ||
        this.usersystem.role_code == "adminvaquanlydanhmuc"
      );
    },
  },
  methods: {
    searchDTM() {
      if (this.searchdata) {
        this.loaiDTM = this.data.filter(
          (el) =>
            el.phan_loai == "C_LoaiVanBanDTM" &&
            el.ten.toUpperCase().search(this.searchdata.toUpperCase()) != -1
        );
      } else {
        this.loaiDTM = this.data.filter(
          (el) => el.phan_loai == "C_LoaiVanBanDTM"
        );
      }
    },
    searchGiayphep() {
      if (this.searchdata) {
        this.giayPhepMT = this.data.filter(
          (el) =>
            el.phan_loai == "C_LoaiGiayPhepMoiTruong" &&
            el.ten.toUpperCase().search(this.searchdata.toUpperCase()) != -1
        );
      } else {
        this.giayPhepMT = this.data.filter(
          (el) => el.phan_loai == "C_LoaiGiayPhepMoiTruong"
        );
      }
    },
    searchKhac() {
      if (this.searchdata) {
        this.thuTucKhac = this.data.filter(
          (el) =>
            el.phan_loai !== "C_LoaiGiayPhepMoiTruong" &&
            el.phan_loai !== "C_LoaiVanBanDTM" &&
            el.ten.toUpperCase().search(this.searchdata.toUpperCase()) != -1
        );
      } else {
        this.thuTucKhac = this.data.filter(
          (el) =>
            el.phan_loai !== "C_LoaiGiayPhepMoiTruong" &&
            el.phan_loai !== "C_LoaiVanBanDTM"
        );
      }
    },
    getData() {
      axios.get(env.endpointhttp + "loaithutuchanhchinhs").then((response) => {
        this.data = response.data.result;
        this.loaiDTM = this.data.filter(
          (el) => el.phan_loai == "C_LoaiVanBanDTM"
        );
        this.giayPhepMT = this.data.filter(
          (el) => el.phan_loai == "C_LoaiGiayPhepMoiTruong"
        );
        this.thuTucKhac = this.data.filter(
          (el) =>
            el.phan_loai !== "C_LoaiGiayPhepMoiTruong" &&
            el.phan_loai !== "C_LoaiVanBanDTM"
        );
      });
    },
    updateLoaiTTHC: function (id, value_1, value_2) {
      axios
        .put(env.endpointhttp + "loaithutuchanhchinhs/" + id, {
          ten: value_1,
          mo_ta: value_2,
          phan_loai: "khac",
        })
        .then((response) => {
          MessageService.showSuccessMessage("Cập nhật thành công");
        });
    },
    clickTab(type) {
      this.phan_loai = type;
      this.loaiTTHC =
        type == "C_LoaiVanBanDTM"
          ? this.loaiDTM
          : type == "C_LoaiGiayPhepMoiTruong"
          ? this.giayPhepMT
          : this.thuTucKhac;
    },
    deleteLoaiTTHC: function () {
      axios
        .delete(env.endpointhttp + "loaithutuchanhchinhs/" + this.thuTuc.id)
        .then((response) => {
          this.getData();
          MessageService.showSuccessMessage("Xóa thành công");
          this.showModel = false;
        })
        .catch((error) => {
          this.showModel = false;
          MessageService.showWarningMessage(
            error.response && error.response.data
              ? error.response.data.message
              : "Không thể xóa"
          );
        });
    },
    dongbodulieu(value) {
      this.load_thoi_gian = false;
      if (value == "C_LoaiVanBanDTM") {
        axios
          .get(env.endpointhttp + "moitruongquocgia/dongbovanbandtm")
          .then((response) => {
            this.getData();
            this.getThoiGianDongBo("van_ban_DTM");
            MessageService.showSuccessMessage("Đồng bộ thành công");
          });
      }
      if (value == "C_LoaiGiayPhepMoiTruong") {
        axios
          .get(env.endpointhttp + "moitruongquocgia/dongbogiayphepmoitruong")
          .then((response) => {
            this.getData();
            this.getThoiGianDongBo("giay_phep_mt");
            MessageService.showSuccessMessage("Đồng bộ thành công");
          });
      }
    },
    getThoiGianDongBo(value) {
      axios.get(env.endpointhttp + "inthoigian/" + value).then((response) => {
        this.thoi_gian = response.data;
        this.thoi_gian_dong_bo =
          new Date(response.data.updated_at).getHours() +
          ":" +
          new Date(response.data.updated_at).getMinutes() +
          ":" +
          new Date(response.data.updated_at).getSeconds() +
          " " +
          new Date(response.data.updated_at).toLocaleDateString("en-TT");
        this.load_thoi_gian = true;
      });
    },
    showConfirmDelete(thuTuc) {
      this.showModel = true;
      this.thuTuc = thuTuc;
    },
    addLoaiTTHC: function () {
      this.error_add_loai_tthc_ma = "";
      this.error_add_loai_tthc_ten = "";
      if (
        this.ten == "" ||
        this.ten == null ||
        this.ma == "" ||
        this.ma == null
      ) {
        if (this.ten == "" || this.ten == null) {
          this.error_add_loai_tthc_ten = "Chưa nhập tên.";
        }
        if (this.ma == "" || this.ma == null) {
          this.error_add_loai_tthc_ma = "Chưa nhập mã.";
        }
      } else {
        this.error_add_loai_tthc_ma == "";
        this.loaiTTHC.forEach((item) => {
          if (this.ma == item.ma) {
            this.error_add_loai_tthc_ma = "Mã đã có, vui lòng nhập mã khác";
          }
        });
        if (this.error_add_loai_tthc_ma == "") {
          axios
            .post(env.endpointhttp + "loaithutuchanhchinhs", {
              ten: this.ten,
              ma: this.ma,
              mo_ta: this.mo_ta,
              phan_loai: this.phan_loai,
            })
            .then((response) => {
              this.getData();
              this.ten = "";
              this.ma = "";
              this.mo_ta = "";
              MessageService.showSuccessMessage("Thêm mới thành công");
            });
        }
      }
    },
    getTrangThaiDongBo() {
      axios
        .get(env.endpointhttp + "dongbocsdl/getTrangThaiDongBo")
        .then((response) => {
          this.trangThaiDongBo = response.data.data[0].trang_thai;
        });
    },
  },
};
</script>
