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
                placeholder="Tìm kiếm tên xã phường"
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
                <th>Quận huyện</th>
                <th>Tên Xã phường</th>
              </tr>
              <tr v-for="(item, index) in data" :key="index">
                <td>{{ index + 1 }}</td>
                <td>{{ item.ma }}</td>
                <td>{{ item.quan_huyen.tinh_thanh.ten }}</td>
                <td>{{ item.quan_huyen.ten }}</td>
                <td>{{ item.ten }}</td>
              </tr>

              <!-- Thêm mới -->
              <tr>
                <td></td>
                <td>
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.ma"
                    placeholder="Mã"
                  />
                  <span style="color: red" v-text="error_ma"></span>
                </td>
                <td>
                  <multiselect
                    v-model="form.tinh_thanhs"
                    label="ten"
                    track-by="id"
                    placeholder="Gõ tên Tỉnh thành"
                    selectLabel="Nhấn enter để chọn"
                    deselectLabel="Nhấn enter để bỏ chọn"
                    selectedLabel="Đã chọn"
                    open-direction="top"
                    :options="tinhs"
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
                    @input="changeTinh()"
                  >
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                </td>
                <td>
                  <multiselect
                    v-model="form.quan_huyens"
                    label="ten"
                    track-by="id"
                    placeholder="Gõ tên huyện"
                    selectLabel="Nhấn enter để chọn"
                    deselectLabel="Nhấn enter để bỏ chọn"
                    selectedLabel="Đã chọn"
                    open-direction="top"
                    :options="listQuanHuyen"
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
                  >
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                  <span style="color: red" v-text="error_quan"></span>
                </td>
                <td>
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.ten"
                    placeholder="Tên"
                  />
                  <span style="color: red" v-text="error_ten"></span>
                </td>
                <td style="width: 5%">
                  <a class="btn btn-flat" @click="addPhuongXa()"
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
                <th>Quận huyện</th>
                <th>Tên Xã phường</th>
                <th></th>
              </tr>
              <tr v-for="(item, index) in dataChuaDongBo" :key="index">
                <td>{{ index + 1 }}</td>
                <td>
                  {{ item.ma }}
                </td>
                <td>{{ item.quan_huyen.tinh_thanh.ten }}</td>
                <td>{{ item.quan_huyen.ten }}</td>
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
                  <a @click="showConfirmDelete(item)"
                    ><i class="fa fa-trash-o btn"></i
                  ></a>
                </td>
              </tr>

              <!-- Thêm mới -->
              <tr>
                <td></td>
                <td>
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.ma"
                    placeholder="Mã"
                  />
                  <span style="color: red" v-text="error_ma"></span>
                </td>
                <td>
                  <multiselect
                    v-model="form.tinh_thanhs"
                    label="ten"
                    track-by="id"
                    placeholder="Gõ tên Tỉnh thành"
                    selectLabel="Nhấn enter để chọn"
                    deselectLabel="Nhấn enter để bỏ chọn"
                    selectedLabel="Đã chọn"
                    open-direction="top"
                    :options="tinhs"
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
                    @input="changeTinh()"
                  >
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                </td>
                <td>
                  <multiselect
                    v-model="form.quan_huyens"
                    label="ten"
                    track-by="id"
                    placeholder="Gõ tên huyện"
                    selectLabel="Nhấn enter để chọn"
                    deselectLabel="Nhấn enter để bỏ chọn"
                    selectedLabel="Đã chọn"
                    open-direction="top"
                    :options="listQuanHuyen"
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
                  >
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                  <span style="color: red" v-text="error_quan"></span>
                </td>
                <td>
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.ten"
                    placeholder="Tên"
                  />
                  <span style="color: red" v-text="error_ten"></span>
                </td>
                <td style="width: 5%">
                  <a class="btn btn-flat" @click="addPhuongXa()"
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
      form: {
        ma: "",
        ten: "",
        tinh_thanhs: null,
        quan_huyens: null,
      },
      tinhs: [],
      listQuanHuyen: [],
      error_ma: null,
      error_quan: null,
      error_ten: null,
      allPhuongXa: {},
    };
  },
  props: ["usersystem"],
  mounted() {
    this.is_loading = true;
    this.getDataDongBo();
    this.getThoiGianDongBo();
    this.getTrangThaiDongBo();
    this.getDataChuaDongBo();
    axios
      .get(env.endpointhttp + "phuongxa", {
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
      this.tinhs = response.data.result;
      console.log("tinhs", this.tinhs);
    });

    axios.get(env.endpointhttp + "allphuongxa", {}).then((response) => {
      this.allPhuongXa = response.data.result;
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
        .get(env.endpointhttp + "phuongxa", {
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
        .get(env.endpointhttp + "phuongxa", {
          params: {
            page: this.page,
            perPage: this.per_page,
            search: this.searchChuaDongBo,
            trang_thai: "chua_dong_bo",
          },
        })
        .then((response) => {
          this.dataChuaDongBo = response.data.data;
          console.log("this.dataChuaDongBo", this.dataChuaDongBo);

          this.last_page_chua_dong_bo = response.data.last_page;
          this.per_page = response.data.per_page;
          this.total_chua_dong_bo = response.data.total;
          this.page_chua_dong_bo = response.data.current_page;
          this.is_loading = false;
        });
    },
    getThoiGianDongBo() {
      axios.get(env.endpointhttp + "inthoigian/phuong_xa").then((response) => {
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
        .get(env.endpointhttp + "moitruongquocgia/xaphuong")
        .then((response) => {
          this.getDataDongBo();
          this.getDataChuaDongBo();
          this.getThoiGianDongBo();
          MessageService.showSuccessMessage("Đồng bộ thành công");
        });
    },
    deleteQuanHuyen() {
      axios
        .delete(env.endpointhttp + "phuongxa/" + this.quanHuyen.id)
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
    changeTinh() {
      console.log("aa", this.form.tinh_thanhs);
      this.form.quan_huyens = null;
      this.listQuanHuyen = this.form.tinh_thanhs
        ? this.form.tinh_thanhs.quan_huyens
        : [];
    },
    addPhuongXa: function () {
      this.error_ma = "";
      this.error_ten = "";
      this.error_quan = "";

      if (
        this.form.ma == "" ||
        this.form.ma == null ||
        this.form.ten == "" ||
        this.form.ten == null ||
        !this.form.quan_huyens ||
        this.form.quan_huyens.length === 0
      ) {
        if (this.form.ten == "" || this.form.ten == null) {
          this.error_ten = "Chưa nhập tên.";
        }
        if (this.form.ma == "" || this.form.ma == null) {
          this.error_ma = "Chưa nhập mã.";
        }
        if (this.form.quan_huyens == [] || this.form.quan_huyens == null) {
          this.error_quan = "Chưa chọn quận.";
        }
      } else {
        this.error_ma == "";
        this.allPhuongXa.forEach((item) => {
          if (this.form.ma == item.ma) {
            this.error_ma = "Mã đã có, vui lòng nhập mã khác";
          }
        });
        console.log("this.form", this.form, this.allPhuongXa);

        if (this.error_ma == "") {
          const payload = {
            ma: this.form.ma,
            ten: this.form.ten,
            quan_huyen_id: this.form.quan_huyens.id,
          };

          axios.post(env.endpointhttp + "phuongxa", payload).then(() => {
            this.getDataDongBo();
            this.getDataChuaDongBo();
            this.form.ma = "";
            this.form.ten = "";
            this.form.quan_huyens = null;
            this.form.tinh_thanhs = null;
            this.listQuanHuyen = [];
            MessageService.showSuccessMessage("Thêm mới thành công");
          });
        }
      }
    },
    update(item) {
      if (
        this.usersystem.role_code == "admin" ||
        this.usersystem.role_code == "adminvaquanlydanhmuc"
      ) {
        const payload = {
          ma: item.ma,
          ten: item.ten,
          quan_huyen_id: item.quan_huyens_id,
        };
        axios
          .put(env.endpointhttp + "phuongxas/" + item.id, payload)
          .then((response) => {
            MessageService.showSuccessMessage("Cập nhật thành công");
          });
      }
    },
  },
};
</script>
