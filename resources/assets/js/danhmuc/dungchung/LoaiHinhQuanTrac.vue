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
            style="height: 40px"
            :disabled="!trangThaiDongBo"
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
                placeholder="Tìm kiếm loại hình"
                v-on:keyup.enter="searchDataDongBo"
              />
              <span
                class="input-group-addon"
                style="cursor: pointer"
                @click="searchDataDongBo"
              >
                <i class="fa fa-search"></i>
              </span>
            </div>
          </div>
          <table class="table table-hover fixed_headers">
            <thead class="row-table-header">
              <tr class="row-table-header">
                <th style="width: 70px">STT</th>
                <th style="width: 70px">Mã</th>
                <th>Tên</th>
                <th>Trạng thái</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in data" :key="index">
                <td style="width: 70px">{{ index + 1 }}</td>
                <td style="width: 70px">
                  <div>{{ item.ma }}</div>
                </td>
                <td>
                  <div>{{ item.ten }}</div>
                </td>
                <td>
                  <div>
                    {{
                      item.trang_thai_dong_bo === "da_dong_bo"
                        ? "Đã đồng bộ"
                        : "Chưa đồng bộ"
                    }}
                  </div>
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
            class="pl-4 pr-4 pb-4"
            style="display: flex; justify-content: end; align-items: flex-end"
          >
            <div class="input-group">
              <input
                type="text"
                v-model="searchdata"
                class="form-control"
                style="z-index: 0; width: 400px"
                placeholder="Tìm kiếm tên"
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
                <th style="width: 70px">STT</th>
                <th style="width: 170px">Mã</th>
                <th>Tên</th>
                <th>Phân loại</th>
                <th>Xóa</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in dataChuaDongBo" :key="index">
                <td style="width: 70px">
                  {{ (page_chua_dong_bo - 1) * per_page + index + 1 }}
                </td>
                <td style="width: 70px">
                  <input
                    type="text"
                    class="form-control"
                    v-model="item.ma"
                    @change="updateCode(item.id, item.ma)"
                    v-if="
                      usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  />
                  <input
                    type="text"
                    class="form-control"
                    v-model="item.ma"
                    v-else
                  />
                </td>
                <td>
                  <input
                    type="text"
                    class="form-control"
                    v-model="item.ten"
                    @change="updateName(item.id, item.ten)"
                    v-if="
                      usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  />
                  <input
                    type="text"
                    class="form-control"
                    v-model="item.ten"
                    v-else
                  />
                </td>
                <td>
                  <div>
                    {{
                      item.trang_thai_dong_bo === "da_dong_bo"
                        ? "Đã đồng bộ"
                        : "Chưa đồng bộ"
                    }}
                  </div>
                </td>
                <td>
                  <a @click="showConfirmDelete(item)"
                    ><i class="fa fa-trash-o btn"></i
                  ></a>
                </td>
              </tr>
              <tr
                v-if="
                  usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
                "
              >
                <td colspan="1">
                  {{
                    (page_chua_dong_bo - 1) * per_page +
                    dataChuaDongBo.length +
                    1
                  }}
                </td>
                <td colspan="1">
                  <input
                    type="text"
                    class="form-control"
                    autofocus
                    v-model="ma"
                  /><span
                    class="text-danger"
                    v-if="error_code && error_code.ma && error_code.ma.length"
                  >
                    {{ error_code.ma[0] }}
                  </span>
                </td>
                <td colspan="1">
                  <input
                    type="text"
                    class="form-control"
                    autofocus
                    v-model="ten"
                  />
                  <span
                    class="text-danger"
                    v-if="error_code && error_code.ten && error_code.ten.length"
                  >
                    {{ error_code.ten[0] }}
                  </span>
                </td>
                <td colspan="1">
                  <input
                    type="text"
                    class="form-control"
                    autofocus
                    :value="'Chưa đồng bộ'"
                    disabled
                  />
                  <input type="hidden" v-model="trang_thai_dong_bo" />
                </td>

                <td align="center">
                  <a class="btn btn-flat" @click="addLoaiHinhQuanTrac()"
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
      @submit="destroy()"
      :title="'Xác nhận xóa'"
    >
      <div>
        Bạn có chắc chắn xóa loại hình
        <b>{{ loaiHinh ? loaiHinh.ten : "" }}</b> không?
      </div>
    </modal-component>
  </div>
</template>

<script>
import * as env from "../../env.js";
import MessageService from "../../services/MessageService";

export default {
  props: ["usersystem"],
  data: function () {
    return {
      data: [],
      showModel: false,
      currentTab: "",
      last_page_dong_bo: 0,
      page_dong_bo: 1,
      per_page: 10,
      total_dong_bo: 0,
      page: 1,
      page_chua_dong_bo: 1,
      total_chua_dong_bo: 0,
      last_page_chua_dong_bo: 0,
      loaiHinh: {},
      thoi_gian_dong_bo: null,
      load_thoi_gian: true,
      thoi_gian: "",
      dataChuaDongBo: [],
      tabKhac: null,
      searchdata: null,
      trang_thai_dong_bo: "chua_dong_bo",
      error_code: null,
      trangThaiDongBo: false
    };
  },
  mounted() {
    this.loadDataTab("nuoc_thai");
    this.getThoiGianDongBo();
    this.getTrangThaiDongBo();
    axios
      .get(env.endpointhttp + "danhmuc/loaihinhquantrac", {
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
      });
  },

  methods: {
    searchDataChuaDongBo() {
      this.getDataChuaDongBo();
    },
    getThoiGianDongBo() {
      axios.get(env.endpointhttp + "inthoigian/thong_so").then((response) => {
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

    destroy() {
      if (
        this.usersystem.role_code == "admin" ||
        this.usersystem.role_code == "adminvaquanlydanhmuc"
      ) {
        axios
          .delete(
            env.endpointhttp + "danhmuc/loaihinhquantrac/" + this.loaiHinh.id
          )
          .then((response) => {
            this.getDataChuaDongBo();
            this.showModel = false;
            MessageService.showSuccessMessage("Xóa thành công");
          })
          .catch((error) => {
            MessageService.showWarningMessage(
              error.response && error.response.data
                ? error.response.data.message
                : "Không thể xóa"
            );
            this.showModel = false;
          });
      }
    },
    showConfirmDelete(loaiHinh) {
      this.showModel = true;
      this.loaiHinh = loaiHinh;
    },
    loadDataTab(type) {
      this.currentTab = type;
      axios
        .get(env.endpointhttp + "danhmuc/loaihinhquantrac", {
          params: {
            page: this.page,
            trang_thai: "da_dong_bo",
            perPage: this.per_page,
            search: this.searchdata,
          },
        })
        .then((response) => {
          this.data = response.data.data;
          this.last_page_dong_bo = response.data.last_page;
          this.per_page = response.data.per_page;
          this.total_dong_bo = response.data.total;
          this.page_dong_bo = response.data.current_page;
        });
    },
    searchDataDongBo() {
      this.page = 1;
      this.loadDataTab();
    },
    getDataChuaDongBo() {
      axios
        .get(env.endpointhttp + "danhmuc/loaihinhquantrac", {
          params: {
            page: this.page,
            trang_thai: "chua_dong_bo",
            perPage: this.per_page,
            search: this.searchdata,
          },
        })
        .then((response) => {
          this.dataChuaDongBo = response.data.data;
          this.last_page_chua_dong_bo = response.data.last_page;
          this.per_page = response.data.per_page;
          this.total_chua_dong_bo = response.data.total;
          this.page_chua_dong_bo = response.data.current_page;
        });
    },
    changePageDongBo(item) {
      this.page = item;
      this.loadDataTab();
    },
    changePageChuaDongBo(item) {
      this.page = item;
      this.getDataChuaDongBo();
    },
    dongbodulieu() {
      this.load_thoi_gian = false;
      axios
        .get(env.endpointhttp + "moitruongquocgia/dongbothongso")
        .then((response) => {
          this.loadDataTab("nuoc_thai");
          this.getThoiGianDongBo();
          MessageService.showSuccessMessage("Đồng bộ thành công");
        });
    },

    async addLoaiHinhQuanTrac() {
      const payload = {
        ma: this.ma ? this.ma.trim() : "",
        ten: this.ten ? this.ten.trim() : "",
        trang_thai_dong_bo: this.trang_thai_dong_bo,
      };

      try {
        const url = env.endpointhttp + "danhmuc/loaihinhquantrac";
        const res = await axios.post(url, payload);
        this.error_code = {};
        await Promise.all([
          this.loadDataTab(this.currentTab),
          this.getDataChuaDongBo(),
        ]);
        // cập nhật UI
        this.ma = "";
        this.ten = "";
        this.trang_thai_dong_bo = "chua_dong_bo";
        MessageService.showSuccessMessage("Thêm mới thành công");
      } catch (err) {
        if (err && err.response && err.response.data) {
          const data = err.response.data;
          this.error_code = data.errors || {};
        } else {
          this.error_code = { _error: ["Không thể kết nối đến máy chủ."] };
        }
      }
    },

    updateCode(id, value) {
      const url = env.endpointhttp + "danhmuc/loaihinhquantrac/" + id;
      axios
        .put(url, { ma: value })
        .then(() => {
          MessageService.showSuccessMessage("Cập nhật thành công");
        })
        .catch((err) => {
          if (err && err.response) {
            const data = err.response.data || {};

            if (data.errors) {
              MessageService.showWarningMessage(
                (data.errors.ma && data.errors.ma[0]) ||
                  data.message ||
                  "Dữ liệu không hợp lệ."
              );
            } else {
              MessageService.showWarningMessage(
                data.message || "Cập nhật không thành công."
              );
            }
          } else {
            MessageService.showWarningMessage("Không thể kết nối đến máy chủ.");
          }
        });
    },
    updateName(id, value) {
      const url = env.endpointhttp + "danhmuc/loaihinhquantrac/" + id;

      axios
        .put(url, {
          ten: value && typeof value === "string" ? value.trim() : value,
        })
        .then(() => {
          MessageService.showSuccessMessage("Cập nhật thành công");
        })
        .catch((err) => {
          if (err && err.response) {
            const data = err.response.data || {};

            if (data.errors) {
              MessageService.showWarningMessage(
                (data.errors.ten && data.errors.ten[0]) ||
                  data.message ||
                  "Dữ liệu không hợp lệ."
              );
            } else {
              MessageService.showWarningMessage(
                data.message || "Cập nhật không thành công."
              );
            }
          } else {
            MessageService.showWarningMessage("Không thể kết nối đến máy chủ.");
          }
        });
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
