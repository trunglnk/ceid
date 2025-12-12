<template>
  <div class="row">
    <div
      class="pr-4 pl-4 pt-4"
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
    <div
      class="pb-4 pl-4 pr-4"
      style="
        display: flex;
        justify-content: end;
        height: 80px;
        align-items: flex-end;
      "
    >
      <div class="input-group">
        <input
          type="text"
          v-model="search"
          class="form-control"
          style="z-index: 0; width: 400px"
          placeholder="Tìm kiếm thông tin"
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
    <div class="nav-tabs-custom margin-top">
      <ul class="nav nav-tabs">
        <li :class="{ active: this.activeTab === this.tabDongBo }">
          <a
            :href="this.tabDongBo"
            data-toggle="tab"
            aria-expanded="true"
            @click="onTabClick('#tab_' + this.nghi_dinh_code + 'dongbo')"
            >Dữ liệu đã đồng bộ từ MTQG
          </a>
        </li>
        <li :class="{ active: this.activeTab === this.tabKhac }">
          <a
            :href="this.tabKhac"
            data-toggle="tab"
            aria-expanded="false"
            @click="onTabClick('#tab_' + this.nghi_dinh_code + 'khac')"
            v-if="dataChuaDongBo && dataChuaDongBo.length > 0"
            >Dữ liệu khác</a
          >
        </li>
      </ul>
      <div class="tab-content">
        <div
          class="tab-pane"
          :class="{ active: this.activeTab == this.tabDongBo }"
          :id="this.tabDongBo"
        >
          <table class="table table-hover fixed_headers">
            <thead class="row-table-header">
              <tr class="row-table-header">
                <th>STT</th>
                <th>Mã hành vi</th>
                <th>Nhóm hành vi</th>
                <th>Tên hành vi</th>
                <th>Mức phạt nhỏ nhất (VNĐ)</th>
                <th>Mức phạt lớn nhất (VNĐ)</th>
                <th>Điều luật</th>
                <th>Hình thức xử phạt bổ sung</th>
                <th>Biện pháp khăc phục hậu quả</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in dataDongBo" :key="index">
                <td style="width: 50px">{{ (page - 1) * 10 + index + 1 }}</td>
                <td style="width: 100px">{{ item.ma_hanh_vi }}</td>
                <td>{{ item.nhom_hanh_vi }}</td>
                <td>{{ item.ten_hanh_vi }}</td>
                <td>
                  {{ Number(item.phat_nho_nhat).toLocaleString("de-DE") }}
                </td>
                <td>
                  {{ Number(item.phat_lon_nhat).toLocaleString("de-DE") }}
                </td>
                <td>{{ item.dieu_luat }}</td>
                <td>{{ item.xu_phat_bo_xung }}</td>
                <td>{{ item.bien_phap_khac_phuc_hau_qua }}</td>
              </tr>

              <!-- Dòng thêm mới -->
              <tr>
                <td></td>
                <td style="width: 100px">
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.ma_hanh_vi"
                    placeholder="Mã"
                  />
                  <span style="color: red" v-text="error_add_hvvp_ma"></span>
                </td>
                <td>
                  <textarea
                    class="form-control"
                    v-model="form.nhom_hanh_vi"
                    placeholder="Nhóm"
                  >
                  </textarea>
                  <span style="color: red" v-text="error_add_hvvp_nhom"></span>
                </td>
                <td>
                  <textarea
                    class="form-control"
                    v-model="form.ten_hanh_vi"
                    placeholder="Tên"
                  >
                  </textarea>
                </td>
                <td>
                  <input
                    type="number"
                    class="form-control"
                    v-model="form.phat_nho_nhat"
                    placeholder="Mức phạt nhỏ"
                  />
                </td>
                <td>
                  <input
                    type="number"
                    class="form-control"
                    v-model="form.phat_lon_nhat"
                    placeholder="Mức phạt lớn"
                  />
                </td>
                <td>
                  <textarea
                    class="form-control"
                    v-model="form.dieu_luat"
                    placeholder="Điều luật"
                  ></textarea>
                </td>
                <td>
                  <textarea
                    class="form-control"
                    v-model="form.xu_phat_bo_xung"
                    placeholder="Xử phạt"
                  ></textarea>
                </td>
                <td>
                  <textarea
                    class="form-control"
                    v-model="form.bien_phap_khac_phuc_hau_qua"
                    placeholder="Biện pháp"
                  ></textarea>
                </td>
                <td>
                  <a class="btn btn-flat" @click="addHanhViViPham()"
                    ><i class="fa fa-plus"></i> Thêm</a
                  >
                </td>
              </tr>
            </tbody>
          </table>
          <hr />
          <div class="d-flex">
            <div>
              Hiển thị từ {{ (pagedongbo - 1) * perpage + 1 }} tới
              {{
                pagedongbo * perpage > totaldongbo
                  ? totaldongbo
                  : pagedongbo * perpage
              }}
              của
              {{ totaldongbo }}
            </div>
            <paginate
              v-model="pagedongbo"
              :page-count="pageCountdongbo"
              :page-range="3"
              :margin-pages="2"
              :click-handler="changePagedongbo"
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
          :class="{ active: this.activeTab == tabKhac }"
          :id="this.tabKhac"
          v-if="dataChuaDongBo && dataChuaDongBo.length > 0"
        >
          <table class="table table-hover fixed_headers">
            <thead class="row-table-header">
              <tr class="row-table-header">
                <th>STT</th>
                <th>Mã hành vi</th>
                <th>Nhóm hành vi</th>
                <th>Tên hành vi</th>
                <th>Mức phạt nhỏ nhất (VNĐ)</th>
                <th>Mức phạt lớn nhất (VNĐ)</th>
                <th>Điều luật</th>
                <th>Hình thức xử phạt bổ sung</th>
                <th>Biện pháp khăc phục hậu quả</th>
                <th>Xóa</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in dataChuaDongBo" :key="index">
                <td style="width: 50px">{{ (page - 1) * 10 + index + 1 }}</td>
                <td style="width: 100px">
                  <input
                    type="text"
                    class="form-control"
                    v-model="item.ma_hanh_vi"
                    @change="updateHanhVi(item)"
                  />
                </td>
                <td>
                  <textarea
                    class="form-control"
                    v-model="item.nhom_hanh_vi"
                    @change="updateHanhVi(item)"
                  >
                  </textarea>
                </td>
                <td>
                  <textarea
                    class="form-control"
                    v-model="item.ten_hanh_vi"
                    @change="updateHanhVi(item)"
                  >
                  </textarea>
                </td>
                <td>
                  <input
                    type="number"
                    class="form-control"
                    v-model="item.phat_nho_nhat"
                    @change="updateHanhVi(item)"
                  />
                </td>
                <td>
                  <input
                    type="number"
                    class="form-control"
                    v-model="item.phat_lon_nhat"
                    @change="updateHanhVi(item)"
                  />
                </td>
                <td>
                  <textarea
                    class="form-control"
                    v-model="item.dieu_luat"
                    @change="updateHanhVi(item)"
                  ></textarea>
                </td>
                <td>
                  <textarea
                    class="form-control"
                    v-model="item.xu_phat_bo_xung"
                    @change="updateHanhVi(item)"
                  ></textarea>
                </td>
                <td>
                  <textarea
                    class="form-control"
                    v-model="item.bien_phap_khac_phuc_hau_qua"
                    @change="updateHanhVi(item)"
                  ></textarea>
                </td>
                <td>
                  <i
                    style="cursor: pointer"
                    class="fa fa-trash-o"
                    @click="showConfirmDelete(item)"
                  ></i>
                </td>
              </tr>

              <!-- Dòng thêm mới -->
              <tr>
                <td></td>
                <td style="width: 100px">
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.ma_hanh_vi"
                    placeholder="Mã"
                  />
                  <span style="color: red" v-text="error_add_hvvp_ma"></span>
                </td>
                <td>
                  <textarea
                    class="form-control"
                    v-model="form.nhom_hanh_vi"
                    placeholder="Nhóm"
                  >
                  </textarea>
                  <span style="color: red" v-text="error_add_hvvp_nhom"></span>
                </td>
                <td>
                  <textarea
                    class="form-control"
                    v-model="form.ten_hanh_vi"
                    placeholder="Tên"
                  >
                  </textarea>
                </td>
                <td>
                  <input
                    type="number"
                    class="form-control"
                    v-model="form.phat_nho_nhat"
                    placeholder="Mức phạt nhỏ"
                  />
                </td>
                <td>
                  <input
                    type="number"
                    class="form-control"
                    v-model="form.phat_lon_nhat"
                    placeholder="Mức phạt lớn"
                  />
                </td>
                <td>
                  <textarea
                    class="form-control"
                    v-model="form.dieu_luat"
                    placeholder="Điều luật"
                  ></textarea>
                </td>
                <td>
                  <textarea
                    class="form-control"
                    v-model="form.xu_phat_bo_xung"
                    placeholder="Xử phạt"
                  ></textarea>
                </td>
                <td>
                  <textarea
                    class="form-control"
                    v-model="form.bien_phap_khac_phuc_hau_qua"
                    placeholder="Biện pháp"
                  ></textarea>
                </td>
                <td>
                  <a class="btn btn-flat" @click="addHanhViViPham()"
                    ><i class="fa fa-plus"></i> Thêm</a
                  >
                </td>
              </tr>
            </tbody>
          </table>
          <hr />
          <div class="d-flex">
            <div>
              Hiển thị từ {{ (page - 1) * perpage + 1 }} tới
              {{ page * perpage > total ? total : page * perpage }} của
              {{ total }}
            </div>
            <paginate
              v-model="page"
              :page-count="pageCount"
              :page-range="3"
              :margin-pages="2"
              :click-handler="changePage"
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
      @submit="deleteLoaiCoSo()"
      :title="'Xóa loại cơ sở'"
    >
      <div>
        Bạn có chắc chắn muốn xóa hành vi <b>{{ mahanhvi }}</b> ra khỏi các hành
        vi vi phạm không?
      </div>
    </modal-component>
  </div>
</template>

<script>
import * as env from "../env";
import MessageService from "../services/MessageService";

export default {
  props: {
    nghi_dinh_id: Number,
    nghi_dinh_code: String,
  },
  data: () => ({
    page: 1,
    perpage: 10,
    hanhVis: [],
    pageCount: 0,
    showModel: false,
    search: "",
    day: null,
    month: null,
    year: null,
    total: null,
    load_thoi_gian: null,
    thoi_gian: "",
    thoi_gian_dong_bo: null,
    dataDongBo: [],
    dataChuaDongBo: [],
    pagedongbo: 1,
    pageCountdongbo: 0,
    totaldongbo: null,
    showModel: false,
    hanhVi: null,
    mahanhvi: null,
    activeTab: "#tab_" + this.nghi_dinh_code + "dongbo",
    tabDongBo: "#tab_" + this.nghi_dinh_code + "dongbo",
    tabKhac: "#tab_" + this.nghi_dinh_code + "khac",
    trangThaiDongBo: false,
    form: {},
    error_add_hvvp_ma: null,
    error_add_hvvp_nhom: null,
  }),
  mounted() {
    this.getHanhVi();
    this.getHanhViDongBo();
    this.getThoiGianDongBo();
    this.getTrangThaiDongBo();
  },
  methods: {
    onTabClick(tabName) {
      this.activeTab = tabName; // Cập nhật activeTab khi nhấn vào tab
    },
    searchData() {
      this.page = 1;
      this.getHanhVi();
      this.getHanhViDongBo();
    },
    getThoiGianDongBo() {
      axios
        .get(env.endpointhttp + "inthoigian/" + this.nghi_dinh_code)
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
        .get(
          env.endpointhttp +
            "moitruongquocgia/dongbohanhvi/" +
            this.nghi_dinh_id
        )
        .then((response) => {
          this.getHanhVi();
          this.getHanhViDongBo();
          this.getThoiGianDongBo();
          MessageService.showSuccessMessage("Đồng bộ thành công");
        });
    },
    getHanhVi() {
      axios
        .get(
          env.endpointhttp +
            "danhmuc/hanhvivipham/" +
            this.nghi_dinh_id +
            "/chuadongbo",
          {
            params: {
              page: this.page,
              perPage: this.perpage,
              search: this.search,
            },
          }
        )
        .then((response) => {
          this.dataChuaDongBo = response.data.data;
          this.pageCount = Number(response.data.last_page);
          this.total = response.data.total;
          if (response.data.data.length == 0) {
            this.activeTab = this.tabDongBo;
          }
        });
    },
    getHanhViDongBo() {
      axios
        .get(
          env.endpointhttp +
            "danhmuc/hanhvivipham/" +
            this.nghi_dinh_id +
            "/dadongbo",
          {
            params: {
              page: this.pagedongbo,
              perPage: this.perpage,
              search: this.search,
            },
          }
        )
        .then((response) => {
          this.dataDongBo = response.data.data;
          this.pageCountdongbo = Number(response.data.last_page);
          this.totaldongbo = response.data.total;
        });
    },
    changePage() {
      this.getHanhVi();
    },
    changePagedongbo() {
      this.getHanhViDongBo();
    },
    updateHanhVi(item) {
      const payload = {
        bien_phap_khac_phuc_hau_qua: item.bien_phap_khac_phuc_hau_qua,
        dieu_luat: item.dieu_luat,
        ma_hanh_vi: item.ma_hanh_vi,
        nghi_dinh_id: item.nghi_dinh_id,
        nhom_hanh_vi: item.nhom_hanh_vi,
        phap_ly: item.phap_ly,
        phat_lon_nhat: item.phat_lon_nhat,
        phat_nho_nhat: item.phat_nho_nhat,
        ten_hanh_vi: item.ten_hanh_vi,
        xu_phat_bo_xung: item.xu_phat_bo_xung,
      };

      axios
        .put(env.endpointhttp + "hanhvivipham/" + item.id, payload)
        .then((response) => {
          this.getHanhVi();
          MessageService.showSuccessMessage("Cập nhật thành công");
        });
    },
    showConfirmDelete(item) {
      (this.showModel = true),
        (this.hanhVi = item.id),
        (this.mahanhvi = item.ma_hanh_vi);
    },
    deleteLoaiCoSo() {
      axios
        .delete(env.endpointhttp + "xoahanhvi/" + this.hanhVi)
        .then((response) => {
          this.getHanhVi();
          MessageService.showSuccessMessage("Xóa thành công");
        })
        .catch(() => {
          MessageService.showWarningMessage("Xóa không thành công");
        });

      this.showModel = false;
    },
    getTrangThaiDongBo() {
      axios
        .get(env.endpointhttp + "dongbocsdl/getTrangThaiDongBo")
        .then((response) => {
          this.trangThaiDongBo = response.data.data[0].trang_thai;
        });
    },
    addHanhViViPham: function () {
      this.error_add_hvvp_ma = "";
      this.error_add_hvvp_nhom = "";
      if (
        this.form.ma_hanh_vi == "" ||
        this.form.ma_hanh_vi == null ||
        this.form.nhom_hanh_vi == "" ||
        this.form.nhom_hanh_vi == null
      ) {
        if (this.form.ma_hanh_vi == "" || this.form.ma_hanh_vi == null) {
          this.error_add_hvvp_ma = "Chưa nhập mã.";
        }
        if (this.form.nhom_hanh_vi == "" || this.form.nhom_hanh_vi == null) {
          this.error_add_hvvp_nhom = "Chưa nhập nhóm.";
        }
      } else {
        if (this.error_add_hvvp_ma == "" && this.error_add_hvvp_nhom == "") {
          this.form = {
            ...this.form,
            nghi_dinh_id: this.nghi_dinh_id,
            phap_ly:
              this.nghi_dinh_id === 1 ? "155/2016/NĐ-CP" : "45/2022/NĐ-CP",
          };
          // console.log("this.form", this.form, this.nghi_dinh_id);
          axios.post(env.endpointhttp + "hanhvivipham", this.form).then(() => {
            this.getHanhVi();
            this.form = {};
            MessageService.showSuccessMessage("Thêm mới thành công");
          });
        }
      }
    },
  },
};
</script>

<style scoped></style>
