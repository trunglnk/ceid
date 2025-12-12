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
        <li class="active">
          <a href="#tab_dongbo" data-toggle="tab" aria-expanded="true"
            >Dữ liệu đã đồng bộ từ MTQG
          </a>
        </li>
        <li>
          <a
            href="#tab_khac"
            data-toggle="tab"
            aria-expanded="false"
            v-if="dataChuaDongBo && dataChuaDongBo.length > 0"
            >Dữ liệu khác</a
          >
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_dongbo">
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
          id="tab_khac"
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
                <td>
                  <i
                    style="cursor: pointer"
                    class="fa fa-trash-o"
                    @click="showConfirmDelete(item)"
                  ></i>
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
        Bạn có chắc chắn muốn xóa hành vi <b>{{ mahanhvi }}</b> ra khỏi các loại
        hình cơ sở không?
      </div>
    </modal-component>
  </div>
</template>

<script>
import * as env from "../../env.js";
import MessageService from "../../services/MessageService";

export default {
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
    trangThaiDongBo: false,
  }),
  mounted() {
    this.getHanhVi();
    this.getHanhViDongBo();
    this.getThoiGianDongBo();
    this.getTrangThaiDongBo();
  },
  methods: {
    searchData() {
      this.page = 1;
      this.getHanhVi();
      this.getHanhViDongBo();
    },
    getThoiGianDongBo() {
      axios
        .get(env.endpointhttp + "inthoigian/nghi_dinh_45")
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
        .get(env.endpointhttp + "moitruongquocgia/dongbohanhvi")
        .then((response) => {
          this.getHanhVi();
          this.getHanhViDongBo();
          this.getThoiGianDongBo();
          MessageService.showSuccessMessage("Đồng bộ thành công");
        });
    },
    getHanhVi() {
      axios
        .get(env.endpointhttp + "getnghidinh45cp", {
          params: {
            page: this.page,
            perPage: this.perpage,
            search: this.search,
          },
        })
        .then((response) => {
          this.dataChuaDongBo = response.data.data;
          this.pageCount = Number(response.data.last_page);
          this.total = response.data.total;
        });
    },
    getHanhViDongBo() {
      axios
        .get(env.endpointhttp + "getnghidinh45cpdongbo", {
          params: {
            page: this.pagedongbo,
            perPage: this.perpage,
            search: this.search,
          },
        })
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
  },
};
</script>

<style scoped></style>
