<template>
  <div class="timeline-body">
    <div
      style="display: flex; align-items: center; justify-content: space-between"
    >
      <div v-if="load_thoi_gian">
        <div v-if="this.thoi_gian == ''">Chưa đồng bộ với CSDL quốc gia</div>
        <div v-else>Đồng bộ lần cuối: {{ thoi_gian_dong_bo }}</div>
      </div>
      <div v-else>Đang đồng bộ với CSDL quốc gia</div>
      <div style="margin-bottom: 10px">
        <div style="width: 140px" class="ml-4">
          <div
            class="btn btn-flat btn-block btn-default d-flex align-center"
            @click="dongbodulieu()"
            :disabled="!trangThaiDongBo"
            style="height: 40px"
          >
            <i class="fa fa-refresh" style="padding-right: 5px"></i>
            Đồng bộ dữ liệu
          </div>
        </div>
      </div>
    </div>
    <div class="nav-tabs-custom margin-top">
      <ul class="nav nav-tabs">
        <li class="active">
          <a href="#tab_dongbo" data-toggle="tab" aria-expanded="true"
            >Dữ liệu đã đồng bộ từ MTQG
          </a>
        </li>
        <li v-if="dataChuaDongBo && dataChuaDongBo.length > 0">
          <a href="#tab_khac" data-toggle="tab" aria-expanded="false"
            >Dữ liệu khác</a
          >
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_dongbo">
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
                placeholder="Tìm kiếm tên khu"
                v-on:keyup.enter="searchDataDongBo()"
              />
              <span
                class="input-group-addon"
                style="cursor: pointer"
                @click="searchDataDongBo()"
              >
                <i class="fa fa-search"></i>
              </span>
            </div>
          </div>
          <table class="table table-hover">
            <tbody>
              <tr class="row-table-header">
                <th style="width: 5%">STT</th>
                <th style="width: 15%">Mã</th>
                <th>Tên</th>
              </tr>
              <tr v-for="(item, index) in dataDongBo" :key="item.id">
                <td>{{ index + 1 }}</td>
                <td>
                  {{ item.ma_muc }}
                </td>
                <td>
                  {{ item.ten }}
                </td>
              </tr>

              <!-- Dòng thêm mới -->
              <tr v-if="isEditable" style="min-width: 100%">
                <td></td>
                <td>
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.ma"
                    placeholder="Mã"
                  />
                  <span
                    style="color: red"
                    v-text="error_add_loai_kcn_ma"
                  ></span>
                </td>
                <td>
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.ten"
                    placeholder="Tên"
                  />
                  <span
                    style="color: red"
                    v-text="error_add_loai_kcn_ten"
                  ></span>
                </td>
                <td style="width: 5%">
                  <a class="btn btn-flat" @click="addLoaiCoSo()"
                    ><i class="fa fa-plus"></i> Thêm</a
                  >
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div
          class="tab-pane"
          id="tab_khac"
          v-if="tabKhac && tabKhac.length > 0"
        >
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
                placeholder="Tìm kiếm tên khu"
                v-on:keyup.enter="searchDataChuaDongBo()"
              />
              <span
                class="input-group-addon"
                style="cursor: pointer"
                @click="searchDataChuaDongBo()"
              >
                <i class="fa fa-search"></i>
              </span>
            </div>
          </div>
          <table class="table table-hover">
            <tbody>
              <tr class="row-table-header">
                <th style="width: 5%">STT</th>
                <th style="width: 15%">Mã</th>
                <th>Tên</th>
                <th>Xóa</th>
              </tr>
              <tr v-for="(item, index) in dataChuaDongBo" :key="item.id">
                <td>{{ index + 1 }}</td>
                <td>
                  {{ item.ma }}
                </td>
                <td>
                  <input
                    type="text"
                    class="form-control"
                    v-model="item.ten"
                    :disabled="!isEditable"
                    @change="updateLoaiCoSo(item.ma, item.ten)"
                  />
                </td>
                <td style="width: 5%">
                  <a @click="showConfirmDelete(item)">
                    <i class="fa fa-trash-o btn"></i
                  ></a>
                </td>
              </tr>

              <!-- Dòng thêm mới -->
              <tr v-if="isEditable" style="min-width: 100%">
                <td></td>
                <td>
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.ma"
                    placeholder="Mã"
                  />
                  <span
                    style="color: red"
                    v-text="error_add_loai_kcn_ma"
                  ></span>
                </td>
                <td>
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.ten"
                    placeholder="Tên"
                  />
                  <span
                    style="color: red"
                    v-text="error_add_loai_kcn_ten"
                  ></span>
                </td>
                <td style="width: 5%">
                  <a class="btn btn-flat" @click="addLoaiCoSo()"
                    ><i class="fa fa-plus"></i> Thêm</a
                  >
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <modal-component
      width="450px"
      v-model="showModel"
      center
      @submit="deleteKCN()"
      :title="'Xác nhận xóa'"
    >
      <div>Bạn có chắc chắn muốn xóa {{ khuCNDelete.ten }} không?</div>
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
      error_add_loai_kcn: null,
      thoi_gian_dong_bo: null,
      load_thoi_gian: true,
      thoi_gian: "",
      khuCNDelete: {},
      showModel: false,
      dataDongBo: [],
      dataChuaDongBo: [],
      searchdata: null,
      tabKhac: null,
      trangThaiDongBo: false,
      form: {},
      error_add_loai_kcn_ma: null,
      error_add_loai_kcn_ten: null,
    };
  },
  props: ["usersystem"],
  created() {
    this.getData();
    this.getThoiGianDongBo();
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
    searchDataDongBo() {
      if (this.searchdata) {
        this.dataChuaDongBo = this.data.filter(
          (el) =>
            el.trang_thai_dong_bo == "da_dong_bo" &&
            el.ten.toUpperCase().search(this.searchdata.toUpperCase()) != -1
        );
      } else {
        this.dataDongBo = this.data.filter(
          (el) => el.trang_thai_dong_bo == "da_dong_bo"
        );
      }
    },
    searchDataChuaDongBo() {
      if (this.searchdata) {
        this.dataChuaDongBo = this.data.filter(
          (el) =>
            el.trang_thai_dong_bo !== "da_dong_bo" &&
            el.ten.toUpperCase().search(this.searchdata.toUpperCase()) != -1
        );
      } else {
        this.dataChuaDongBo = this.data.filter(
          (el) => el.trang_thai_dong_bo !== "da_dong_bo"
        );
      }
    },
    getData() {
      axios.get(env.endpointhttp + "loaikhucongnghieps").then((response) => {
        this.data = response.data.result;
        this.dataDongBo = this.data.filter(
          (el) => el.trang_thai_dong_bo == "da_dong_bo"
        );
        this.dataChuaDongBo = this.data.filter(
          (el) => el.trang_thai_dong_bo !== "da_dong_bo"
        );
        this.tabKhac = this.dataChuaDongBo;
      });
    },
    getThoiGianDongBo() {
      axios
        .get(env.endpointhttp + "inthoigian/loai_khu_cong_nghiep")
        .then((response) => {
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
    dongbodulieu() {
      this.load_thoi_gian = false;
      axios
        .get(env.endpointhttp + "moitruongquocgia/loaikhucongnghiep")
        .then((response) => {
          this.getData();
          this.getThoiGianDongBo();
          MessageService.showSuccessMessage("Đồng bộ dữ liệu thành công");
        });
    },
    updateLoaiCoSo: function (id, value) {
      axios
        .put(env.endpointhttp + "loaikhucongnghieps/" + id, { ten: value })
        .then((response) => {
          MessageService.showSuccessMessage("Cập nhật thành công");
        });
    },
    deleteKCN: function () {
      axios
        .delete(env.endpointhttp + "loaikhucongnghieps/" + this.khuCNDelete.ma)
        .then((response) => {
          this.getData();
          MessageService.showSuccessMessage("Xóa thành công");
          this.showModel = false;
        })
        .catch((error) => {
          MessageService.showWarningMessage(
            error.response && error.response.data
              ? error.response.data.message
              : "Không thể xóa"
          );
          this.showModel = false;
        });
    },
    showConfirmDelete(kcn) {
      this.showModel = true;
      this.khuCNDelete = kcn;
    },
    addLoaiCoSo: function () {
      this.error_add_loai_kcn_ma = "";
      this.error_add_loai_kcn_ten = "";

      if (
        this.form.ten == "" ||
        this.form.ten == null ||
        this.form.ma == "" ||
        this.form.ma == null
      ) {
        if (this.form.ten == "" || this.form.ten == null) {
          this.error_add_loai_kcn_ten = "Chưa nhập tên.";
        }
        if (this.form.ma == "" || this.form.ma == null) {
          this.error_add_loai_kcn_ma = "Chưa nhập mã.";
        }
      } else {
        this.error_add_loai_kcn_ma == "";
        const existed = this.data.some(
          (el) =>
            el.ma.trim().toUpperCase() === this.form.ma.trim().toUpperCase()
        );

        if (existed) {
          this.error_add_loai_kcn_ma = "Mã đã có, vui lòng nhập mã khác";
        }
        if (this.error_add_loai_kcn_ma == "") {
          axios
            .post(env.endpointhttp + "loaikhucongnghieps", {
              ten: this.form.ten,
              ma: this.form.ma,
            })
            .then(() => {
              this.getData();
              this.form = {};
              MessageService.showSuccessMessage("Thêm mới thành công");
            })
            .catch(() => {
              MessageService.showWarningMessage("Thêm thất bại");
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
