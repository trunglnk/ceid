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
            class="pb-4 pl-4 pr-4"
            style="display: flex; justify-content: end; align-items: flex-end"
          >
            <div class="input-group">
              <input
                type="text"
                v-model="searchDaDongBo"
                class="form-control"
                style="z-index: 0; width: 400px"
                placeholder="Tìm kiếm tên quận huyện"
                v-on:keyup.enter="searchDaTaDongBo()"
              />
              <span
                class="input-group-addon"
                style="cursor: pointer"
                @click="searchDaTaDongBo()"
              >
                <i class="fa fa-search"></i>
              </span>
            </div>
          </div>

          <table class="table table-hover">
            <tbody>
              <tr class="row-table-header">
                <th>STT</th>
                <th>Mã</th>
                <th>Tỉnh thành</th>
                <th>Tên quận huyện</th>
              </tr>
              <tr v-for="(item, index) in data" :key="index">
                <td>{{ index + 1 }}</td>
                <td>{{ item.ma_dinh_danh }}</td>
                <td>{{ item.tinh_thanh.ten }}</td>
                <td>{{ item.ten }}</td>
              </tr>
            </tbody>
          </table>
          <div
            style="
              display: flex;
              align-items: center;
              justify-content: space-between;
            "
          >
            <div>
              Hiển thị từ {{ (page_dong_bo - 1) * per_page + 1 }} tới
              {{
                page_dong_bo * per_page > total_dong_bo
                  ? total_dong_bo
                  : page_dong_bo * per_page
              }}
              của
              {{ total_dong_bo }}
            </div>
            <paginate
              v-model="page_dong_bo"
              :page-count="last_page_dong_bo"
              :page-range="3"
              :margin-pages="2"
              :click-handler="changePageDongBo"
              :prev-text="'‹‹'"
              :next-text="'››'"
              :container-class="'pagination'"
              :page-class="'page-item'"
            >
            </paginate>
          </div>
        </div>
        <div
          class="tab-pane"
          id="tab_khac"
          v-if="tabKhac && tabKhac.length > 0"
        >
          <div
            class="pb-4 pl-4 pr-4"
            style="display: flex; justify-content: end; align-items: flex-end"
          >
            <div class="input-group">
              <input
                type="text"
                v-model="searchChuaDongBo"
                class="form-control"
                style="z-index: 0; width: 400px"
                placeholder="Tìm kiếm tên quận huyện"
                v-on:keyup.enter="searchDaTaChuaDongBo()"
              />
              <span
                class="input-group-addon"
                style="cursor: pointer"
                @click="searchDaTaChuaDongBo()"
              >
                <i class="fa fa-search"></i>
              </span>
            </div>
          </div>

          <table class="table table-hover">
            <tbody>
              <tr class="row-table-header">
                <th>STT</th>
                <th>Mã</th>
                <th>Tỉnh thành</th>
                <th>Tên quận huyện</th>
                <th>Mã định danh</th>
                <th>Xóa</th>
              </tr>
              <tr v-for="(item, index) in dataChuaDongBo" :key="index">
                <td>{{ index + 1 }}</td>
                <td>{{ item.ma }}</td>
                <td>
                  <multiselect
                    v-model="item.tinh_thanh"
                    label="ten"
                    track-by="id"
                    placeholder="Gõ tên tỉnh thành"
                    selectLabel="Nhấn enter để chọn"
                    deselectLabel="Nhấn enter để bỏ chọn"
                    selectedLabel="Đã chọn"
                    :showLabels="false"
                    open-direction="bottom"
                    :options="tinhThanh"
                    :searchable="false"
                    :loading="is_loading"
                    :internal-search="false"
                    :options-limit="300"
                    :max-height="600"
                    :allowEmpty="false"
                    :tabindex="1"
                    required
                    @input="update(item)"
                  >
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                </td>
                <td>
                  <input
                    type="text"
                    class="form-control"
                    v-model="item.ten"
                    placeholder="Tên"
                    @change="update(item)"
                  />
                </td>
                <td>
                  <input
                    type="text"
                    class="form-control"
                    v-model="item.ma_dinh_danh"
                    placeholder="Mã định danh"
                    @change="update(item)"
                  />
                </td>
                <td>
                  <a @click="showConfirmDelete(item)"
                    ><i class="fa fa-trash-o btn"></i
                  ></a>
                </td>
              </tr>

              <!-- Dòng thêm mới -->
              <tr>
                <td></td>
                <td>
                  <input
                    type="number"
                    class="form-control"
                    v-model="form.ma"
                    placeholder="Mã"
                  />
                  <span
                    style="color: red"
                    v-text="error_add_quan_huyen_ma"
                  ></span>
                </td>
                <td>
                  <multiselect
                    v-model="form.tinh_thanh"
                    label="ten"
                    track-by="id"
                    placeholder="Gõ tên tỉnh thành"
                    selectLabel="Nhấn enter để chọn"
                    deselectLabel="Nhấn enter để bỏ chọn"
                    selectedLabel="Đã chọn"
                    :showLabels="false"
                    open-direction="bottom"
                    :options="tinhThanh"
                    :searchable="false"
                    :loading="is_loading"
                    :internal-search="false"
                    :options-limit="300"
                    :max-height="600"
                    :allowEmpty="false"
                    :tabindex="1"
                    required
                  >
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                  <span
                    style="color: red"
                    v-text="error_add_quan_huyen_tinh"
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
                    v-text="error_add_quan_huyen_ten"
                  ></span>
                </td>
                <td>
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.ma_dinh_danh"
                    placeholder="Mã định danh"
                  />
                </td>
                <td style="width: 5%">
                  <a class="btn btn-flat" @click="addQuanHuyen()"
                    ><i class="fa fa-plus"></i> Thêm</a
                  >
                </td>
              </tr>
            </tbody>
          </table>
          <div
            style="
              display: flex;
              align-items: center;
              justify-content: space-between;
            "
          >
            <div>
              Hiển thị từ {{ (page_chua_dong_bo - 1) * per_page + 1 }} tới
              {{
                page_chua_dong_bo * per_page > total_chua_dong_bo
                  ? total_chua_dong_bo
                  : page_chua_dong_bo * per_page
              }}
              của
              {{ total_chua_dong_bo }}
            </div>
            <paginate
              v-model="page_chua_dong_bo"
              :page-count="last_page_chua_dong_bo"
              :page-range="3"
              :margin-pages="2"
              :click-handler="changePageChuaDongBo"
              :prev-text="'‹‹'"
              :next-text="'››'"
              :container-class="'pagination'"
              :page-class="'page-item'"
            >
            </paginate>
          </div>
        </div>
      </div>
    </div>
    <modal-component
      width="450px"
      v-model="showModel"
      center
      @submit="deleteQuanHuyen()"
      :title="'Xác nhận xóa'"
    >
      <div>
        Bạn có chắc chắn muốn xóa quận huyện
        {{ quanHuyen ? quanHuyen.ten : "" }} không?
      </div>
    </modal-component>
  </div>
</template>

<script>
import * as env from "../../env.js";
import MessageService from "../../services/MessageService";

import Multiselect from "vue-multiselect";
export default {
  components: { Multiselect },
  data: function () {
    return {
      data: [],
      showModel: false,
      is_loading: false,
      erros: {},
      load_thoi_gian: true,
      last_page_dong_bo: 0,
      las_page_chua_dong_bo: 0,
      per_page: 10,
      total_dong_bo: 0,
      total_chua_dong_bo: 0,
      page_dong_bo: 1,
      page_chua_dong_bo: 1,
      searchDaDongBo: null,
      thoi_gian_dong_bo: null,
      load_thoi_gian: true,
      thoi_gian: "",
      dataChuaDongBo: [],
      searchChuaDongBo: null,
      quanHuyen: {},
      tab: "da_dong_bo",
      tabKhac: null,
      trangThaiDongBo: false,
      tinhThanh: {},
      error_add_quan_huyen_ma: null,
      error_add_quan_huyen_tinh: null,
      error_add_quan_huyen_ten: null,
      form: {},
      allQuanHuyen: {},
    };
  },
  props: ["usersystem"],
  mounted() {
    this.is_loading = true;
    this.getDataDongBo();
    this.getThoiGianDongBo();
    this.getTrangThaiDongBo();
    axios
      .get(env.endpointhttp + "quanhuyen", {
        params: {
          page: 1,
          perPage: 10,
          search: null,
          trang_thai: "chua_dong_bo",
        },
      })
      .then((response) => {
        this.dataChuaDongBo = response.data.data;
        this.tabKhac = this.dataChuaDongBo;
        this.last_page_chua_dong_bo = response.data.last_page;
        this.per_page = response.data.per_page;
        this.total_chua_dong_bo = response.data.total;
        this.page_chua_dong_bo = response.data.current_page;
        this.is_loading = false;
      });

    axios.get(env.endpointhttp + "tinhthanhs", {}).then((response) => {
      this.tinhThanh = response.data.result;
    });

    axios.get(env.endpointhttp + "allquanhuyen", {}).then((response) => {
      this.allQuanHuyen = response.data.result;
      console.log("this.allQuanHuyen", this.allQuanHuyen);
    });
  },
  methods: {
    searchDaTaDongBo() {
      this.page = 1;
      this.getDataDongBo();
    },
    searchDaTaChuaDongBo() {
      this.page = 1;
      this.getDataChuaDongBo();
    },
    changePageDongBo(item) {
      this.page = item;
      this.getDataDongBo();
    },
    changePageChuaDongBo(item) {
      this.page = item;
      this.getDataChuaDongBo();
    },
    getDataDongBo() {
      axios
        .get(env.endpointhttp + "quanhuyen", {
          params: {
            page: this.page,
            perPage: this.per_page,
            search: this.searchDaDongBo,
            trang_thai: "da_dong_bo",
          },
        })
        .then((response) => {
          this.data = response.data.data;
          this.last_page_dong_bo = response.data.last_page;
          this.per_page = response.data.per_page;
          this.total_dong_bo = response.data.total;
          this.page_dong_bo = response.data.current_page;
          this.is_loading = false;
        });
    },
    getDataChuaDongBo() {
      axios
        .get(env.endpointhttp + "quanhuyen", {
          params: {
            page: this.page,
            perPage: this.per_page,
            search: this.searchChuaDongBo,
            trang_thai: "chua_dong_bo",
          },
        })
        .then((response) => {
          this.dataChuaDongBo = response.data.data;
          this.last_page_chua_dong_bo = response.data.last_page;
          this.per_page = response.data.per_page;
          this.total_chua_dong_bo = response.data.total;
          this.page_chua_dong_bo = response.data.current_page;
          this.is_loading = false;
        });
    },
    getThoiGianDongBo() {
      axios.get(env.endpointhttp + "inthoigian/quan_huyen").then((response) => {
        this.thoi_gian = response.data;
        this.thoi_gian_dong_bo =
          new Date(response.data.updated_at).getHours() +
          ":" +
          new Date(response.data.updated_at).getMinutes() +
          ":" +
          new Date(response.data.updated_at).getSeconds() +
          " " +
          new Date(response.data.updated_at).toLocaleDateString("en-TT");
        console.log(response.data);
        this.load_thoi_gian = true;
      });
    },

    dongbodulieu() {
      this.load_thoi_gian = false;
      axios
        .get(env.endpointhttp + "moitruongquocgia/quanhuyen")
        .then((response) => {
          this.getDataDongBo();
          this.getDataChuaDongBo();
          this.getThoiGianDongBo();
          MessageService.showSuccessMessage("Đồng bộ thành công");
        });
    },
    deleteQuanHuyen() {
      axios
        .delete(env.endpointhttp + "quanhuyen/" + this.quanHuyen.id)
        .then((response) => {
          this.showModel = false;
          MessageService.showSuccessMessage("Xóa thành công");
          this.getDataChuaDongBo();
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
    showConfirmDelete(quanHuyen) {
      this.showModel = true;
      this.quanHuyen = quanHuyen;
    },
    getTrangThaiDongBo() {
      axios
        .get(env.endpointhttp + "dongbocsdl/getTrangThaiDongBo")
        .then((response) => {
          this.trangThaiDongBo = response.data.data[0].trang_thai;
        });
    },
    update(item) {
      if (
        this.usersystem.role_code == "admin" ||
        this.usersystem.role_code == "adminvaquanlydanhmuc"
      ) {
        item.tinh_thanh_id = item.tinh_thanh ? item.tinh_thanh.id : "";

        axios
          .put(env.endpointhttp + "quanhuyens/" + item.id, item)
          .then((response) => {
            MessageService.showSuccessMessage("Cập nhật thành công");
          });
      }
    },
    addQuanHuyen: function () {
      this.error_add_quan_huyen_ma = "";
      this.error_add_quan_huyen_tinh = "";
      this.error_add_quan_huyen_ten = "";

      if (
        this.form.ma == "" ||
        this.form.ma == null ||
        this.form.ten == "" ||
        this.form.ten == null ||
        this.form.tinh_thanh == "" ||
        this.form.tinh_thanh == null
      ) {
        if (this.form.ten == "" || this.form.ten == null) {
          this.error_add_quan_huyen_ten = "Chưa nhập tên.";
        }
        if (this.form.ma == "" || this.form.ma == null) {
          this.error_add_quan_huyen_ma = "Chưa nhập mã.";
        }
        if (this.form.tinh_thanh == "" || this.form.tinh_thanh == null) {
          this.error_add_quan_huyen_tinh = "Chưa chọn tỉnh thành.";
        }
      } else {
        this.error_add_quan_huyen_ma == "";
        this.allQuanHuyen.forEach((item) => {
          if (this.form.ma == item.ma) {
            this.error_add_quan_huyen_ma = "Mã đã có, vui lòng nhập mã khác";
          }
        });
        if (this.error_add_quan_huyen_ma == "") {
          this.form = {
            ...this.form,
            tinh_thanh_id: this.form.tinh_thanh ? this.form.tinh_thanh.id : "",
          };
          axios.post(env.endpointhttp + "quanhuyens", this.form).then(() => {
            this.getDataChuaDongBo();
            this.form = {};
            MessageService.showSuccessMessage("Thêm mới thành công");
          });
        }
      }
    },
  },
};
</script>
