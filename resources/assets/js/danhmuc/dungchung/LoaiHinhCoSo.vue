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
        <li v-if="tabKhac && tabKhac.length > 0">
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
                placeholder="Tìm kiếm tên ngành nghề"
                v-on:keyup.enter="searchData()"
              />
              <span
                class="input-group-addon"
                style="cursor: pointer"
                @click="searchData()"
              >
                <i class="fa fa-search"></i>
              </span>
            </div>
          </div>
          <table class="table table-hover fixed_headers">
            <thead class="row-table-header">
              <tr class="row-table-header">
                <th style="width: 70px">STT</th>
                <th style="width: 150px">Mã cấp</th>
                <th style="width: 960px">Tên ngành nghề</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in nganhNghes" :key="item.nhoms">
                <td style="width: 70px">{{ index + 1 }}</td>
                <td style="width: 150px">
                  <div v-if="dong != index">{{ item.ma }}</div>
                  <div v-else>
                    <input v-model="ma_input" style="width: 130px" />
                  </div>
                </td>
                <td style="width: 970px">
                  <div v-if="dong != index">{{ item.ten }}</div>
                  <div v-else>
                    <textarea v-model="ten_input" style="width: 95%"></textarea>
                  </div>
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
                placeholder="Tìm kiếm tên ngành nghề"
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
          <table class="table table-hover fixed_headers">
            <thead class="row-table-header">
              <tr class="row-table-header">
                <th style="width: 70px; text-align: center">STT</th>
                <th style="width: 150px; text-align: center">Mã cấp</th>
                <th style="width: 860px; text-align: center">Tên ngành nghề</th>
                <th style="width: 860px; text-align: center">Danh mục cha</th>
                <th style="width: 60px; text-align: center">Xóa</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in dataChuaDongBo" :key="item.nhoms">
                <td style="width: 70px; text-align: center">{{ index + 1 }}</td>
                <td style="width: 150px; text-align: center">
                  <input
                    v-model="item.ma"
                    style="width: 130px"
                    @change="updateLoaiCoSo(item)"
                  />
                </td>
                <td style="width: 860px">
                  <textarea
                    v-model="item.ten"
                    style="width: 95%"
                    @change="updateLoaiCoSo(item)"
                  ></textarea>
                </td>
                <td style="width: 860px">
                  <multiselect
                    v-model="item.parent_id"
                    :options="danhMucCha"
                    label="label"
                    track-by="id"
                    :multiple="false"
                    :searchable="true"
                    :close-on-select="true"
                    :clear-on-select="true"
                    :hide-selected="false"
                    :max-height="300"
                    open-direction="bottom"
                    :append-to-body="true"
                    :allow-empty="true"
                    :show-labels="false"
                    :show-clear="true"
                    placeholder="Chọn hoặc bỏ chọn"
                    @input="
                      (val) => {
                        item.parent_id = val;
                        updateLoaiCoSo(item);
                      }
                    "
                  >
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                </td>
                <td style="width: 60px">
                  <a @click="showConfirmDelete(item)"
                    ><i class="fa fa-trash-o btn"></i
                  ></a>
                </td>
              </tr>

              <!-- Dòng thêm mới -->
              <tr v-if="isEditable">
                <td></td>
                <td style="width: 150px; text-align: center">
                  <input
                    class="form-control"
                    v-model="form.ma"
                    style="width: 130px"
                    placeholder="Mã cấp"
                  />
                  <span
                    style="color: red"
                    v-text="error_add_loai_co_so_ma"
                  ></span>
                </td>
                <td style="width: 860px">
                  <textarea
                    class="form-control"
                    v-model="form.ten"
                    style="width: 95%"
                    placeholder="Tên ngành nghề"
                  ></textarea>
                  <span
                    style="color: red"
                    v-text="error_add_loai_co_so_ten"
                  ></span>
                </td>
                <td style="width: 860px">
                  <multiselect
                    v-model="form.parent_id"
                    :options="danhMucCha"
                    label="label"
                    track-by="id"
                    :multiple="false"
                    :searchable="true"
                    :close-on-select="true"
                    :clear-on-select="true"
                    :hide-selected="false"
                    :max-height="300"
                    open-direction="bottom"
                    :append-to-body="true"
                    :allow-empty="true"
                    :show-labels="false"
                    :show-clear="true"
                    placeholder="Chọn hoặc bỏ chọn"
                  >
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
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
      @submit="deleteLoaiHinhCoSo()"
      :title="'Xác nhận xóa'"
    >
      <div>
        Bạn có chắc chắn muốn xóa {{ nganhNghe ? nganhNghe.ten : "" }} không?
      </div>
    </modal-component>
  </div>
</template>

<script>
import * as env from "../../env.js";
import MessageService from "../../services/MessageService";
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import { ValidationProvider } from "vee-validate";
import Multiselect from "vue-multiselect";

export default {
  components: { Treeselect, ValidationProvider, Multiselect },
  data: function () {
    return {
      nhoms: [],
      nhom: null,
      data: [],
      ten_co_so_moi: null,
      error_add_loai_co_so: null,
      nhomcs: [],
      nganhNghes: [],
      nganhNghe: {},
      showModel: false,
      loaiHinhs: [],
      form: {},
      ten_input: null,
      ma_input: null,
      dong: null,
      parentSelect: null,
      showModel: null,
      xoaLoaihinhcoso: {},
      thoi_gian_dong_bo: null,
      load_thoi_gian: true,
      thoi_gian: "",
      dataChuaDongBo: [],
      searchdata: null,
      tableData: [],
      tabKhac: null,
      trangThaiDongBo: false,
      danhMucCha: [],
      error_add_loai_co_so_ma: null,
      error_add_loai_co_so_ten: null,
    };
  },
  props: ["usersystem"],
  computed: {
    infoParent() {
      if (this.parentSelect) {
        return "Mã danh mục " + this.parentSelect.ma_danh_muc;
      }
      return "";
    },
    isEditable() {
      return (
        this.usersystem.role_code == "admin" ||
        this.usersystem.role_code == "adminvaquanlydanhmuc"
      );
    },
  },
  created() {
    axios.get(env.endpointhttp + "loaihinhcosos").then((response) => {
      this.data = response.data.result;
    });
    axios.get(env.endpointhttp + "nhomloaihinhcosos").then((response) => {
      this.nhoms = response.data.result;
    });
    this.getCoSoTreeSelect();
    this.getLoaiNganhNghe();
    this.getThoiGianDongBo();
    this.getTrangThaiDongBo();
  },
  methods: {
    searchData() {
      if (this.searchdata) {
        this.nganhNghes = this.tableData.filter(
          (el) =>
            el.trang_thai_dong_bo == "da_dong_bo" &&
            el.ten.toUpperCase().search(this.searchdata.toUpperCase()) != -1
        );
      } else {
        this.nganhNghes = this.tableData.filter(
          (el) => el.trang_thai_dong_bo == "da_dong_bo"
        );
      }
    },
    searchDataChuaDongBo() {
      if (this.searchdata) {
        this.dataChuaDongBo = this.tableData.filter(
          (el) =>
            el.trang_thai_dong_bo == "chua_dong_bo" &&
            el.ten.toUpperCase().search(this.searchdata.toUpperCase()) != -1
        );
      } else {
        this.dataChuaDongBo = this.tableData.filter(
          (el) => el.trang_thai_dong_bo != "da_dong_bo"
        );
      }
    },
    getCoSoTreeSelect() {
      axios.get(env.endpointhttp + "treeloaihinh").then((response) => {
        this.loaiHinhs = response.data;
        this.danhMucCha = this.loaiHinhs.filter((item) =>
          /^[A-Za-z]+$/.test(item.ma_danh_muc)
        );
      });
    },
    getLoaiNganhNghe() {
      axios.get(env.endpointhttp + "nganhnghes").then((response) => {
        this.tableData = response.data;
        this.nganhNghes = this.tableData.filter(
          (el) => el.trang_thai_dong_bo == "da_dong_bo"
        );
        this.dataChuaDongBo = this.tableData.filter(
          (el) => el.trang_thai_dong_bo != "da_dong_bo"
        );
        this.tabKhac = this.dataChuaDongBo;

        // Gán lại parent_id cho multiselect hiển thị đúng
        this.dataChuaDongBo = this.dataChuaDongBo.map((item) => {
          const found = this.danhMucCha.find((dm) => dm.id === item.parent_id);
          return { ...item, parent_id: found || null };
        });
      });
    },
    getThoiGianDongBo() {
      axios
        .get(env.endpointhttp + "inthoigian/loai_hinh_co_so")
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
    updateLoaiCoSo(item) {
      const payload = {
        ten: item.ten,
        ma: item.ma,
        parent_id: item.parent_id ? item.parent_id.id : null,
      };

      axios
        .put(env.endpointhttp + "loaihinhcosos/" + item.id, payload)
        .then((response) => {
          this.dong = null;
          this.getLoaiNganhNghe();
          MessageService.showSuccessMessage("Cập nhật thành công");
        });
    },
    addLoaiCoSo: function () {
      this.error_add_loai_co_so_ma = "";
      this.error_add_loai_co_so_ten = "";
      // if (!this.form.ma || this.form.ma.trim() === "") {
      //   MessageService.showWarningMessage("Vui lòng nhập mã cấp");
      //   return;
      // }
      // if (!this.form.ten || this.form.ten.trim() === "") {
      //   MessageService.showWarningMessage("Vui lòng nhập tên ngành nghề");
      //   return;
      // }
      if (this.form.parent_id && typeof this.form.parent_id === "object") {
        this.form.parent_id = this.form.parent_id.id;
      }
      if (
        this.form.ten == "" ||
        this.form.ten == null ||
        this.form.ma == "" ||
        this.form.ma == null
      ) {
        if (this.form.ten == "" || this.form.ten == null) {
          this.error_add_loai_co_so_ten = "Chưa nhập tên.";
        }
        if (this.form.ma == "" || this.form.ma == null) {
          this.error_add_loai_co_so_ma = "Chưa nhập mã.";
        }
      } else {
        this.error_add_loai_co_so_ma == "";
        this.tableData.forEach((item) => {
          if (this.form.ma == item.ma) {
            console.log('vao');
            
            this.error_add_loai_co_so_ma = "Mã đã có, vui lòng nhập mã khác";
          }
        });
        if (this.error_add_loai_co_so_ma == "" && this.error_add_loai_co_so_ten == "") {
          axios.post(env.endpointhttp + "loaihinhcosos", this.form).then(() => {
            this.getLoaiNganhNghe();
            this.getCoSoTreeSelect();
            this.form = {};
            MessageService.showSuccessMessage("Thêm mới thành công");
          });
        }
      }
    },
    loadChiTietNhomCoSo: function (id) {
      axios
        .get(env.endpointhttp + "chitietloaihinhcoso/" + id)
        .then((response) => {
          this.nhomcs = response.data.result;
        });
    },

    deleteLoaiHinhCoSo: function () {
      axios
        .delete(env.endpointhttp + "loaihinhcosos/" + this.nganhNghe.id)
        .then((response) => {
          this.getLoaiNganhNghe();
          this.showModel = false;
          MessageService.showSuccessMessage("Xóa thành công");
        });
      this.showModel = false;
    },
    huy() {
      this.dong = null;
    },
    showConfirmDelete(Loaihinhcoso) {
      this.showModel = true;
      this.xoaLoaihinhcoso = Loaihinhcoso;
    },
    dongbodulieu() {
      this.load_thoi_gian = false;
      axios
        .get(env.endpointhttp + "moitruongquocgia/loainganhnghekinhte")
        .then((response) => {
          this.getLoaiNganhNghe();
          this.getThoiGianDongBo();
          MessageService.showSuccessMessage("Đồng bộ thành công");
        });
    },
    showConfirmDelete(nganhNghe) {
      this.showModel = true;
      this.nganhNghe = nganhNghe;
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
<style scoped>
thead,
tfoot {
  display: table;
}

tbody {
  display: block;
  max-height: 373px;
  min-height: 373px;
  overflow-y: scroll;
}

.pointer {
  cursor: pointer;
}
</style>
