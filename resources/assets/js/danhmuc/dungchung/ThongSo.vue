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
                placeholder="Tìm kiếm thông số"
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
                <th>Ký hiệu hóa học</th>
                <th>Phân loại</th>
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
                  <div>{{ item.ky_hieu_hoa_hoc }}</div>
                </td>
                <td>
                  <div>
                    {{ item.phan_loai ? item.phan_loai.ten : "Chưa phân loại" }}
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
                <th style="width: 70px">Mã</th>
                <th>Tên</th>
                <th>Ký hiệu hóa học</th>
                <th>Phân loại</th>
                <th>Xóa</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in dataChuaDongBo" :key="index">
                <td style="width: 70px">{{ index + 1 }}</td>
                <td style="width: 70px">
                  <input
                    type="text"
                    class="form-control"
                    v-model="item.ma"
                    placeholder="Mã"
                    @change="update(item)"
                  />
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
                    v-model="item.ky_hieu_hoa_hoc"
                    placeholder="Ký hiệu hóa học"
                    @change="update(item)"
                  />
                </td>
                <td>
                  <multiselect
                    v-model="item.phan_loai"
                    label="ten"
                    track-by="ma"
                    placeholder="Gõ Loại thông số"
                    selectLabel="Nhấn enter để chọn"
                    deselectLabel="Nhấn enter để bỏ chọn"
                    selectedLabel="Đã chọn"
                    open-direction="top"
                    :options="listLoaiThongSo"
                    :multiple="false"
                    :searchable="true"
                    :loading="is_loading"
                    :internal-search="true"
                    :clear-on-select="false"
                    :close-on-select="true"
                    :options-limit="300"
                    :limit="3"
                    :max-height="300"
                    :show-no-results="false"
                    :hide-selected="false"
                    :tabindex="1"
                    @input="
                      (val) => {
                        item.phan_loai = val;
                        update(item);
                      }
                    "
                  >
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                </td>
                <td>
                  <a @click="showConfirmDelete(item)"
                    ><i class="fa fa-trash-o btn"></i
                  ></a>
                </td>
              </tr>

              <!-- Dòng thêm mới -->
              <tr v-if="isEditable">
                <td></td>
                <td style="width: 70px">
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.ma"
                    placeholder="Mã"
                  />
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
                    v-text="error_add_thong_so_ten"
                  ></span>
                </td>
                <td>
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.ky_hieu_hoa_hoc"
                    placeholder="Ký hiệu hóa học"
                  />
                  <span
                    style="color: red"
                    v-text="error_add_thong_so_khhh"
                  ></span>
                </td>
                <td>
                  <multiselect
                    v-model="form.ma_loai"
                    label="ten"
                    track-by="ma"
                    placeholder="Gõ Loại thông số"
                    selectLabel="Nhấn enter để chọn"
                    deselectLabel="Nhấn enter để bỏ chọn"
                    selectedLabel="Đã chọn"
                    open-direction="top"
                    :options="listLoaiThongSo"
                    :multiple="false"
                    :searchable="true"
                    :loading="is_loading"
                    :internal-search="true"
                    :clear-on-select="false"
                    :close-on-select="true"
                    :options-limit="300"
                    :limit="3"
                    :max-height="300"
                    :show-no-results="false"
                    :hide-selected="false"
                    :tabindex="1"
                    :clearOnSelect="true"
                  >
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                </td>
                <td>
                  <a class="btn btn-flat" @click="add()"
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
        Bạn có chắc chắn muốn xóa {{ thongSo ? thongSo.ten : "" }} không?
      </div>
    </modal-component>

    <!-- <ul class="nav nav-tabs">
      <li v-for="(tab, i) in tabs" :key="i" :class="{ active: currentTab == tab.type }" @click="loadDataTab(tab.type)">
        <a data-toggle="tab" aria-expanded="true">{{ tab.text }}</a>
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active">
        <div class="row">
          <div class="col-sm-12">
            <table class="table table-hover">
              <tbody>
                <tr class="row-table-header">
                  <th>STT</th>
                  <th>Tên</th>
                  <th>Ký hiệu hóa học</th>
                  <th style="width: 5%" v-if="
                    usersystem.role_code == 'admin' ||
                    usersystem.role_code == 'adminvaquanlydanhmuc'
                  "></th>
                </tr>
                <tr v-for="(item, index) in data" :key="item.data">
                  <td>{{ index + 1 }}</td>
                  <td>
                    <input type="text" class="form-control" v-model="item.ten" @change="update(item, index)" />
                  </td>
                  <td>
                    <input type="text" class="form-control" v-model="item.ky_hieu_hoa_hoc"
                      @change="update(item, index)" />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div> -->
  </div>
</template>

<script>
import * as env from "../../env.js";
import MessageService from "../../services/MessageService";
import Multiselect from "vue-multiselect";

export default {
  components: { Multiselect },
  props: ["usersystem"],
  data: function () {
    return {
      data: [],
      showModel: false,
      form: {},
      error_thongso: "",
      currentTab: "",
      last_page_dong_bo: 0,
      page_dong_bo: 1,
      per_page: 10,
      total_dong_bo: 0,
      page: 1,
      page_chua_dong_bo: 1,
      total_chua_dong_bo: 0,
      las_page_chua_dong_bo: 0,
      thongSo: {},
      tabs: [
        { text: "Nước thải", type: "nuoc_thai" },
        { text: "Khí thải,bụi", type: "khi_thai" },
        { text: "Chất thải rắn", type: "chat_thai_ran" },
        { text: "Bùn thải", type: "bun_thai" },
        { text: "Không khi xung quanh", type: "khong_khi_xung_quanh" },
        { text: "Nước mặt", type: "nuoc_mat" },
        { text: "Trầm tích", type: "tram_tich" },
        { text: "Đất", type: "dat" },
        { text: "Nước dưới đất", type: "nuoc_duoi_dat" },
        { text: "Chưa phân loại", type: "chua_phan_loai" },
      ],
      //danh_muc_dong_bo: 'Thông số',
      thoi_gian_dong_bo: null,
      load_thoi_gian: true,
      thoi_gian: "",
      dataChuaDongBo: [],
      tabKhac: null,
      searchdata: null,
      trangThaiDongBo: false,
      listLoaiThongSo: [],
      error_add_thong_so_ten: null,
      error_add_thong_so_khhh: null,
    };
  },
  mounted() {
    this.loadDataTab("nuoc_thai");
    this.getLoaiThongSo();
    this.getThoiGianDongBo();
    this.getTrangThaiDongBo();
    axios
      .get(env.endpointhttp + "danhmuc/thongsovuotchuan", {
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
  computed: {
    isEditable() {
      return (
        this.usersystem.role_code == "admin" ||
        this.usersystem.role_code == "adminvaquanlydanhmuc"
      );
    },
  },
  methods: {
    searchDataChuaDongBo() {
      this.getDataChuaDongBo();
    },
    getLoaiThongSo() {
      axios.get(env.endpointhttp + "getLoaiThongSo").then((response) => {
        this.listLoaiThongSo = response.data;
        console.log("thongSo", this.listLoaiThongSo);
      });
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
        //console.log(response.data[2].updated_at)
        this.load_thoi_gian = true;
      });
    },
    add() {
      this.error_add_thong_so_ten = "";
      this.error_add_thong_so_khhh = "";
      if (
        this.form.ten == "" ||
        this.form.ten == null ||
        this.form.ky_hieu_hoa_hoc == "" ||
        this.form.ky_hieu_hoa_hoc == null
      ) {
        if (this.form.ten == "" || this.form.ten == null) {
          this.error_add_thong_so_ten = "Chưa nhập tên.";
        }
        if (
          this.form.ky_hieu_hoa_hoc == "" ||
          this.form.ky_hieu_hoa_hoc == null
        ) {
          this.error_add_thong_so_khhh = "Chưa nhập ký hiệu hóa học.";
        }
      } else {
        if (
          this.error_add_thong_so_ten == "" &&
          this.error_add_thong_so_khhh == ""
        ) {
          if (
            this.usersystem.role_code == "admin" ||
            this.usersystem.role_code == "adminvaquanlydanhmuc"
          ) {
            if (this.form.ma_loai && typeof this.form.ma_loai === "object") {
              this.form.ma_loai = String(this.form.ma_loai.id);
            }
            axios
              .post(env.endpointhttp + "danhmuc/thongsovuotchuan", this.form)
              .then((response) => {
                this.data.push(response.data.result);
                this.form = {};
                MessageService.showSuccessMessage("Thêm mới thành công");
              });
          }
        }
      }
    },
    update(item, index) {
      if (
        this.usersystem.role_code == "admin" ||
        this.usersystem.role_code == "adminvaquanlydanhmuc"
      ) {
        console.log("item", item);
        const payload = {
          ten: item.ten,
          ma: item.ma,
          ky_hieu_hoa_hoc: item.ky_hieu_hoa_hoc,
          ma_loai: item.phan_loai ? item.phan_loai.ma : ''
        };
        axios
          .put(env.endpointhttp + "danhmuc/thongsovuotchuan/" + item.id, payload)
          .then((response) => {
            MessageService.showSuccessMessage("Cập nhật thành công");
          });
      }
    },
    destroy(id, index) {
      if (
        this.usersystem.role_code == "admin" ||
        this.usersystem.role_code == "adminvaquanlydanhmuc"
      ) {
        axios
          .delete(
            env.endpointhttp + "danhmuc/thongsovuotchuan/" + this.thongSo.id
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
    showConfirmDelete(thongSo) {
      this.showModel = true;
      this.thongSo = thongSo;
    },
    loadDataTab(type) {
      this.currentTab = type;
      axios
        .get(env.endpointhttp + "danhmuc/thongsovuotchuan", {
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
        .get(env.endpointhttp + "danhmuc/thongsovuotchuan", {
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
