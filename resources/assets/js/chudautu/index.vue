<template>
  <div class="row">
    <div class="pt-4 pl-4 pr-4 d-flex">
      <div>
        <a
          href="https://moitruongquocgia.mae.gov.vn/#/group/csdl_mt/kho_so_bo?_view=T_ChuDauTu&_id=NULL&menu=thong_tin_chung">
          <button type="submit" id="onsubmit" class="btn bg-olive btn-flat" @click="goAdd">
            <i class="fa fa-plus"></i> Thêm chủ đầu tư
          </button>
        </a>
        <a
          href="/chudautu/add">
          <button type="submit" id="onsubmit" class="btn bg-olive btn-flat" @click="goAdd">
            <i class="fa fa-plus"></i> Thêm chủ đầu tư nội bộ
          </button>
        </a>
        <button class="btn bg-olive btn-flat" @click="dongbodulieu()" :disabled="!trangThaiDongBo">
          <i class="fa fa-refresh" style="padding-right: 5px" v-if="load_thoi_gian"></i>
          <i v-else class="fa fa-spinner fa-spin" style="font-size: 20px"></i>
          Đồng bộ dữ liệu
        </button>
      </div>

      <div>
        <div v-if="load_thoi_gian">
          <div v-if="this.thoi_gian == ''">Chưa đồng bộ với CSDL quốc gia</div>
          <div v-else>Đồng bộ lần cuối: {{ thoi_gian_dong_bo }}</div>
        </div>
        <div v-else>Đang đồng bộ với CSDL quốc gia</div>
      </div>
    </div>
    <div class="nav-tabs-custom margin-top">
      <ul class="nav nav-tabs">
        <li class="active">
          <a href="#tab_dongbo" data-toggle="tab" aria-expanded="true">Dữ liệu đã đồng bộ từ MTQG
          </a>
        </li>
        <li v-if="dataChuaDongBo">
          <a href="#tab_khac" data-toggle="tab" aria-expanded="false">Dữ liệu khác</a>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_dongbo">
          <div class="pb-4 pl-4 pr-4" style="
              display: flex;
              justify-content: space-between;
              align-items: flex-end;
            ">
            <div>
              Hiển thị
              <select v-model="perpage" style="width: 80px" @change="getChuDauTu()">
                <option v-for="(item, index) in options" :value="item" :key="`${item}${index}`">
                  {{ item }}
                </option>
              </select>
              mục
            </div>
            <div class="input-group">
              <input type="text" v-model="search" class="form-control" style="z-index: 0; width: 400px"
                placeholder="Tìm kiếm chủ đầu tư" v-on:keyup.enter="searchData('da_dong_bo')" />
              <span class="input-group-addon" style="cursor: pointer" @click="searchData('da_dong_bo')">
                <i class="fa fa-search"></i>
              </span>
            </div>
          </div>
          <div class="col-md-12">
            <table class="table table-hover fixed_headers">
              <thead class="row-table-header">
                <tr class="row-table-header">
                  <th>STT</th>
                  <th>Tên</th>
                  <th>Mã định danh</th>
                  <th>Địa chỉ</th>
                  <th>Số chứng nhận KD</th>
                  <th>Ngày cấp</th>
                  <th>Hành động</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in Chudautu" :key="index">
                  <td style="width: 50px">{{ (page - 1) * 10 + index + 1 }}</td>
                  <td style="
                      max-width: 400px;
                      color: rgb(51, 122, 183);
                      cursor: pointer;
                    " @click="goEdit(item.id)">
                    {{ item.ten }}
                  </td>
                  <td>{{ item.ma_dinh_danh }}</td>
                  <td>{{ item.dia_chi }}</td>
                  <td>{{ item.so_giay_chung_nhan_dang_ky_kinh_doanh }}</td>
                  <td>
                    {{
                    item.ngay_cap_giay_chung_nhan_dang_ky_kinh_doanh
                    ? new Date(
                    item.ngay_cap_giay_chung_nhan_dang_ky_kinh_doanh
                    ).toLocaleDateString("en-TT")
                    : ""
                    }}
                  </td>
                  <td style="width: 75px; cursor: pointer; text-align: center">
                    <i class="fa fa-info mr-3" @click="goEdit(item.id)" title="Cập nhật"></i>
                    <i class="fa fa-trash-o" @click="showConfirmDelete(item)" title="Xóa"></i>
                  </td>
                </tr>
              </tbody>
            </table>
            <hr />
            <div style="
                display: flex;
                align-items: center;
                justify-content: space-between;
              ">
              <div>
                Hiển thị từ {{ (page - 1) * perpage + 1 }} tới
                {{ page * perpage > total ? total : page * perpage }} của
                {{ total }}
              </div>
              <paginate v-model="page" :page-count="pageCount" :page-range="3" :margin-pages="2"
                :click-handler="changePage" :prev-text="'‹‹'" :next-text="'››'" :container-class="'pagination'"
                :page-class="'page-item'">
              </paginate>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab_khac" v-if="dataChuaDongBo">
          <div class="pb-4 pl-4 pr-4" style="
              display: flex;
              justify-content: space-between;
              align-items: flex-end;
            ">
            <div>
              Hiển thị
              <select v-model="perpageChuaDongBo" style="width: 80px" @change="getChuDauTu('chua_dong_bo')">
                <option v-for="(item, index) in options" :value="item" :key="`${item}${index}`">
                  {{ item }}
                </option>
              </select>
              mục
            </div>
            <div class="input-group">
              <input type="text" v-model="search" class="form-control" style="z-index: 0; width: 400px"
                placeholder="Tìm kiếm chủ đầu tư" v-on:keyup.enter="searchData('chua_dong_bo')" />
              <span class="input-group-addon" style="cursor: pointer" @click="searchData('chua_dong_bo')">
                <i class="fa fa-search"></i>
              </span>
            </div>
          </div>
          <div class="col-md-12">
            <table class="table table-hover fixed_headers">
              <thead class="row-table-header">
                <tr class="row-table-header">
                  <th>STT</th>
                  <th>Tên</th>
                  <th>Địa chỉ</th>
                  <th>Số chứng nhận KD</th>
                  <th>Ngày cấp</th>
                  <th>Hành động</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in dataChuaDongBo" :key="index">
                  <td style="width: 50px">{{ (page - 1) * 10 + index + 1 }}</td>
                  <td style="
                      max-width: 400px;
                      color: rgb(51, 122, 183);
                      cursor: pointer;
                    " @click="goEdit(item.id)">
                    {{ item.ten }}
                  </td>
                  <td>{{ item.dia_chi }}</td>
                  <td>{{ item.so_giay_chung_nhan_dang_ky_kinh_doanh }}</td>
                  <td>
                    {{
                    item.ngay_cap_giay_chung_nhan_dang_ky_kinh_doanh
                    ? new Date(
                    item.ngay_cap_giay_chung_nhan_dang_ky_kinh_doanh
                    ).toLocaleDateString("en-TT")
                    : ""
                    }}
                  </td>
                  <td style="width: 75px; cursor: pointer; text-align: center" >
                    <i class="fa fa-info mr-3" @click="goEdit(item.id)" title="Cập nhật"></i>
                    <i class="fa fa-trash-o" @click="showConfirmDelete(item)" title="Xóa"></i>
                  </td>
                </tr>
              </tbody>
            </table>
            <hr />
            <div style="
                display: flex;
                align-items: center;
                justify-content: space-between;
              ">
              <div>
                Hiển thị từ {{ (pageChuaDongBo - 1) * pageChuaDongBo + 1 }} tới
                {{ pageChuaDongBo * pageChuaDongBo > totalChuaDongBo ? totalChuaDongBo : pageChuaDongBo *
                perpageChuaDongBo }} của
                {{ totalChuaDongBo }}
              </div>
              <paginate v-model="pageChuaDongBo" :page-count="pageCountChuaDongBo" :page-range="3" :margin-pages="2"
                :click-handler="changePageChuaDongBo" :prev-text="'‹‹'" :next-text="'››'"
                :container-class="'pagination'" :page-class="'page-item'">
              </paginate>
            </div>
          </div>
        </div>
      </div>
    </div>

    <modal-component width="450px" v-model="showModel" center @submit="xoa()" :title="'Xóa chủ đầu tư'">
      <div>
        Bạn có chắc chắn muốn xóa chủ đầu tư <b>{{ xoaChuDauTu.ten }}</b> không
        ?
      </div>
    </modal-component>
  </div>
</template>

<script>
import * as env from "../env.js";
import MessageService from "../services/MessageService";

export default {
  data: () => ({
    page: 1,
    perpage: 10,
    Chudautu: [],
    pageCount: 0,
    showModel: false,
    search: "",
    xoaChuDauTu: {},
    day: null,
    month: null,
    year: null,
    total: null,
    options: [10, 20, 50],
    thoi_gian_dong_bo: null,
    load_thoi_gian: true,
    thoi_gian: "",
    dataChuaDongBo: [],
    pageCountChuaDongBo: 0,
    totalChuaDongBo: 0,
    pageChuaDongBo: 1,
    perpageChuaDongBo: 10,
    trangThaiDongBo: false
  }),
  created() {
    this.getChuDauTu('da_dong_bo');
    this.getChuDauTu('chua_dong_bo');
    this.getThoiGianDongBo();
    this.getTrangThaiDongBo();
  },
  methods: {
    searchData(trangThai = 'da_dong_bo') {
      this.page = 1;
      this.pageChuaDongBo = 1
      this.getChuDauTu(trangThai);
    },
    getChuDauTu(trangThai = 'da_dong_bo') {
      axios
        .get(env.endpointhttp + "chudautu/get", {
          params: {
            page: trangThai == 'da_dong_bo' ? this.page : this.pageChuaDongBo,
            perPage: trangThai == 'da_dong_bo' ? this.perpage : this.perpageChuaDongBo,
            search: this.search,
            dong_bo: trangThai
          },
        })
        .then((response) => {
          if (trangThai == 'da_dong_bo') {
            this.Chudautu = response.data.data;
            this.pageCount = Number(response.data.last_page);
            this.total = Number(response.data.total);
          }
          if (trangThai == 'chua_dong_bo') {
            this.dataChuaDongBo = response.data.data;
            this.pageCountChuaDongBo = Number(response.data.last_page);
            this.totalChuaDongBo = Number(response.data.total);
          }

        });
    },
    changePage() {
      this.getChuDauTu('da_dong_bo');
    },
    changePageChuaDongBo() {
      this.getChuDauTu('chua_dong_bo');
    },
    goEdit(id) {
      window.location.href = "/chudautu/edit/" + id;
    },
    getThoiGianDongBo() {
      axios.get(env.endpointhttp + "inthoigian/chu_dau_tu").then((response) => {
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
        .get(env.endpointhttp + "moitruongquocgia/dongbochudautu")
        .then((response) => {
          this.getChuDauTu('da_dong_bo');
          this.getChuDauTu('chua_dong_bo');
          this.getThoiGianDongBo();
          MessageService.showSuccessMessage("Đồng bộ thành công");
        }).catch((err) => {
          MessageService.showWarningMessage("Đồng bộ không thành công");
        })
        .finally(() => (this.load_thoi_gian = true));;
    },
    xoa() {
      axios
        .delete(env.endpointhttp + "chudautu/delete/" + this.xoaChuDauTu.id)
        .then((response) => {
          this.getChuDauTu('chua_dong_bo');
          this.getChuDauTu('da_dong_bo');
          this.showModel = false;
          MessageService.showSuccessMessage("Xóa thành công");
        })
        .catch((error) => {
          MessageService.showWarningMessage("Xoá thất bại");
        })
        .finally();
    },
    goAdd() {
      // window.location.href = "/chudautu/add/";
    },
    showConfirmDelete(chuDauTu) {
      this.showModel = true;
      this.xoaChuDauTu = chuDauTu;
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

</style>