<template>
  <div class="row">
    <div class="pa-4 d-flex">
      <div>
        <button class="btn bg-olive btn-flat" @click="traCuu()">
          <i class="fa fa-search mr-2"></i>Tra cứu trên bản đồ
        </button>
        <a
          href="https://moitruongquocgia.mae.gov.vn/#/group/csdl_mt/kho_so_bo?_view=T_MoiTruongCoSo&_id=NULL&menu=thong_tin_chung&fallbackviewCoSo=co_so"
        >
          <button type="submit" id="onsubmit" class="btn bg-olive btn-flat">
            <i class="fa fa-plus"></i> Thêm mới cơ sở sản xuất MTQG
          </button>
        </a>
        <a href="/cososanxuat/add">
          <button type="submit" id="onsubmit" class="btn bg-olive btn-flat">
            <i class="fa fa-plus"></i> Thêm mới cơ sở sản xuất nội bộ
          </button>
        </a>
        <button
          class="btn bg-olive btn-flat"
          @click="dongbodulieu()"
          :disabled="!trangThaiDongBo"
        >
          <i
            class="fa fa-refresh"
            style="padding-right: 5px"
            v-if="load_thoi_gian"
          ></i>
          <i v-else class="fa fa-spinner fa-spin" style="font-size: 20px"></i>
          Đồng bộ dữ liệu cơ sở
        </button>
        <button
          class="btn bg-olive btn-flat"
          @click="dongbodulieuketquaqtmt()"
          :disabled="!trangThaiDongBo"
        >
          <i
            class="fa fa-refresh"
            style="padding-right: 5px"
            v-if="load_thoi_gian_ket_qua"
          ></i>
          <i v-else class="fa fa-spinner fa-spin" style="font-size: 20px"></i>
          Đồng bộ dữ liệu kết quả QTMT
        </button>
      </div>
      <div>
        <div v-if="load_thoi_gian || load_thoi_gian_ket_qua">
          <div v-if="this.thoi_gian == ''">Chưa đồng bộ với CSDL quốc gia</div>
          <div v-else>Đồng bộ lần cuối: {{ thoi_gian_dong_bo }}</div>
        </div>
        <div v-else>Đang đồng bộ với CSDL quốc gia</div>
      </div>
    </div>
    <div class="nav-tabs-custom margin-top">
      <ul class="nav nav-tabs">
        <li class="active">
          <a href="#tab_dongbo" data-toggle="tab" aria-expanded="true"
            >Dữ liệu đã đồng bộ từ MTQG
          </a>
        </li>
        <li v-if="dataChuaDongBo">
          <a href="#tab_khac" data-toggle="tab" aria-expanded="false"
            >Dữ liệu khác</a
          >
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_dongbo">
          <div
            class="pb-4 pl-4 pr-4"
            style="
              display: flex;
              justify-content: space-between;
              align-items: flex-end;
            "
          >
            <div>
              Hiển thị
              <select
                v-model="perpage"
                style="width: 80px"
                @change="getCoSoSanXuat()"
              >
                <option
                  v-for="(item, index) in options"
                  :value="item"
                  :key="`${item}${index}`"
                >
                  {{ item }}
                </option>
              </select>
              mục
            </div>
            <div class="input-group">
              <input
                type="text"
                v-model="search"
                class="form-control"
                style="z-index: 0; width: 400px"
                placeholder="Tìm kiếm cơ sở"
                v-on:keyup.enter="searchData('da_dong_bo')"
              />
              <span
                class="input-group-addon"
                style="cursor: pointer"
                @click="searchData('da_dong_bo')"
              >
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
                  <th>Chủ đầu tư</th>
                  <th>Địa chỉ</th>
                  <th>Điểm trạm quan trắc</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in CoSoSanXuat" :key="index">
                  <td style="width: 50px">{{ (page - 1) * 10 + index + 1 }}</td>
                  <td
                    style="
                      max-width: 400px;
                      color: rgb(51, 122, 183);
                      cursor: pointer;
                    "
                    @click="detail(item.id)"
                  >
                    {{ item.ten }}
                  </td>
                  <td>{{ item.ma_dinh_danh }}</td>
                  <td>
                    {{
                      item.organization
                        ? item.organization.chu_dau_tu
                          ? item.organization.chu_dau_tu.ten
                          : null
                        : null
                    }}
                  </td>
                  <td>{{ item.dia_chi_lien_he }}</td>
                  <td
                    style="
                      max-width: 400px;
                      color: rgb(51, 122, 183);
                      cursor: pointer;
                    "
                  >
                    <template
                      v-if="
                        Array.isArray(item.tinh_thanh.diem_tram_qtmt_theo_tinh) &&
                        item.tinh_thanh.diem_tram_qtmt_theo_tinh.length
                      "
                    >
                      <span
                        v-for="(diem, idx) in item.tinh_thanh.diem_tram_qtmt_theo_tinh"
                        :key="diem.id || idx"
                        class="underline cursor-pointer hover:opacity-80"
                        @click.stop="ketquaqtmt(diem.ma_dinh_danh)"
                        :title="diem.ten_goi"
                      >
                        {{ diem.ten_goi
                        }}<span
                          v-if="idx < item.tinh_thanh.diem_tram_qtmt_theo_tinh.length - 1"
                          >,
                        </span>
                      </span>
                    </template>
                  </td>
                </tr>
              </tbody>
            </table>
            <hr />
            <div
              style="
                display: flex;
                align-items: center;
                justify-content: space-between;
              "
            >
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
        <div class="tab-pane" id="tab_khac" v-if="dataChuaDongBo">
          <div
            class="pb-4 pl-4 pr-4"
            style="
              display: flex;
              justify-content: space-between;
              align-items: flex-end;
            "
          >
            <div>
              Hiển thị
              <select
                v-model="perpage"
                style="width: 80px"
                @change="getCoSoSanXuat()"
              >
                <option
                  v-for="(item, index) in options"
                  :value="item"
                  :key="`${item}${index}`"
                >
                  {{ item }}
                </option>
              </select>
              mục
            </div>
            <div class="input-group">
              <input
                type="text"
                v-model="search"
                class="form-control"
                style="z-index: 0; width: 400px"
                placeholder="Tìm kiếm cơ sở"
                v-on:keyup.enter="searchData('chua_dong_bo')"
              />
              <span
                class="input-group-addon"
                style="cursor: pointer"
                @click="searchData('chua_dong_bo')"
              >
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
                  <th>Chủ đầu tư</th>
                  <th>Địa chỉ</th>
                  <th>Điểm trạm quan trắc</th>
                  <th>Xóa</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in dataChuaDongBo" :key="index">
                  <td style="width: 50px">{{ (page - 1) * 10 + index + 1 }}</td>
                  <td
                    style="
                      max-width: 400px;
                      color: rgb(51, 122, 183);
                      cursor: pointer;
                    "
                    @click="detail(item.id)"
                  >
                    {{ item.ten }}
                  </td>
                  <td>
                    {{
                      item.organization
                        ? item.organization.chu_dau_tu
                          ? item.organization.chu_dau_tu.ten
                          : null
                        : null
                    }}
                  </td>
                  <td>{{ item.dia_chi_hoat_dong }}</td>
                  <td
                    style="
                      max-width: 400px;
                      color: rgb(51, 122, 183);
                      cursor: pointer;
                    "
                  >
                    <template
                      v-if="
                        Array.isArray(
                          item.tinh_thanh.diem_tram_qtmt_theo_tinh
                        ) && item.tinh_thanh.diem_tram_qtmt_theo_tinh.length
                      "
                    >
                      <span
                        v-for="(diem, idx) in item.tinh_thanh.diem_tram_qtmt_theo_tinh"
                        :key="diem.id || idx"
                        class="underline cursor-pointer hover:opacity-80"
                        @click.stop="ketquaqtmt(diem.ma_dinh_danh, item)"
                        :title="diem.ten_goi"
                      >
                        {{ diem.ten_goi
                        }}<span
                          v-if="idx < item.tinh_thanh.diem_tram_qtmt_theo_tinh.length - 1"
                          >,
                        </span>
                      </span>
                    </template>
                  </td>
                  <td>
                    <i
                      style="cursor: pointer"
                      class="fa fa-trash-o"
                      @click="showConfirmDelete(item)"
                      title="Xóa"
                    ></i>
                  </td>
                </tr>
              </tbody>
            </table>
            <hr />
            <div
              style="
                display: flex;
                align-items: center;
                justify-content: space-between;
              "
            >
              <div>
                Hiển thị từ {{ (pageChuaDongBo - 1) * pageChuaDongBo + 1 }} tới
                {{
                  pageChuaDongBo * pageChuaDongBo > totalChuaDongBo
                    ? totalChuaDongBo
                    : pageChuaDongBo * perpageChuaDongBo
                }}
                của
                {{ totalChuaDongBo }}
              </div>
              <paginate
                v-model="pageChuaDongBo"
                :page-count="pageCountChuaDongBo"
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
            <modal-component
              width="450px"
              v-model="showModel"
              center
              @submit="xoa()"
              :title="'Xóa chủ đầu tư'"
            >
              <div>
                Bạn có chắc chắn muốn xóa cơ sở sản xuất
                <b> {{ xoaCoSo.ten }} </b>
                không ?
              </div>
            </modal-component>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as env from "../env.js";
import MessageService from "../services/MessageService";

export default {
  data: () => ({
    page: 1,
    perpage: 10,
    CoSoSanXuat: [],
    pageCount: 0,
    showModel: false,
    search: "",
    day: null,
    month: null,
    year: null,
    total: null,
    options: [10, 20, 50],
    thoi_gian_dong_bo: null,
    load_thoi_gian: true,
    load_thoi_gian_ket_qua: true,
    thoi_gian: "",
    dataChuaDongBo: [],
    pageCountChuaDongBo: 0,
    totalChuaDongBo: 0,
    pageChuaDongBo: 1,
    perpageChuaDongBo: 10,
    showModel: false,
    xoaCoSo: {},
    trangThaiDongBo: false,
  }),
  created() {
    this.getCoSoSanXuat("da_dong_bo");
    this.getCoSoSanXuat("chua_dong_bo");
    this.getThoiGianDongBo();
    this.getTrangThaiDongBo();
  },
  methods: {
    searchData(trangThai = "da_dong_bo") {
      this.page = 1;
      this.getCoSoSanXuat(trangThai);
    },
    getCoSoSanXuat(trangThai = "da_dong_bo") {
      axios
        .get(env.endpointhttp + "getdanhsachcoso", {
          params: {
            page: trangThai == "da_dong_bo" ? this.page : this.pageChuaDongBo,
            perPage: this.perpage,
            search: this.search,
            dong_bo: trangThai,
          },
        })
        .then((response) => {
          if (trangThai == "da_dong_bo") {
            this.CoSoSanXuat = response.data.data;
            this.pageCount = Number(response.data.last_page);
            this.total = Number(response.data.total);
          }
          if (trangThai == "chua_dong_bo") {
            this.dataChuaDongBo = response.data.data;
            this.pageCountChuaDongBo = Number(response.data.last_page);
            this.totalChuaDongBo = Number(response.data.total);
          }
        });
    },
    changePage() {
      this.getCoSoSanXuat("da_dong_bo");
    },
    changePageChuaDongBo() {
      this.getCoSoSanXuat("chua_dong_bo");
    },
    dongbodulieu() {
      this.load_thoi_gian = false;
      axios
        .get(env.endpointhttp + "moitruongquocgia/dongbocoso")
        .then((response) => {
          MessageService.showSuccessMessage("Đồng bộ thành công");
          this.getCoSoSanXuat("da_dong_bo");
          this.getCoSoSanXuat("chua_dong_bo");
          this.getThoiGianDongBo();
          this.load_thoi_gian = true;
        })
        .catch((err) => {
          MessageService.showWarningMessage("Đồng bộ không thành công");
        })
        .finally(() => (this.load_thoi_gian = true));
    },
    dongbodulieuketquaqtmt() {
      // this.load_thoi_gian_ket_qua = false;
      axios
        .get(env.endpointhttp + "moitruongquocgia/dongboketquaQTMT")
        .then((response) => {
          MessageService.showSuccessMessage("Đồng bộ thành công");
          // this.getThoiGianDongBo();
          // this.load_thoi_gian_ket_qua = true;
        })
        .catch((err) => {
          MessageService.showWarningMessage("Đồng bộ không thành công");
        })
        .finally(() => (this.load_thoi_gian_ket_qua = true));
    },
    showConfirmDelete(coSo) {
      this.showModel = true;
      this.xoaCoSo = coSo;
    },
    getThoiGianDongBo() {
      axios
        .get(env.endpointhttp + "inthoigian/co_so_san_xuat")
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
    detail(id) {
      window.location.href = "cososanxuat/update/" + id;
    },
    ketquaqtmt(id, dataCoSo) {
      sessionStorage.setItem("dataCoSo", JSON.stringify(dataCoSo));
      window.location.href = "cososanxuat/ketquaqtmt/" + id;
    },
    traCuu() {
      window.location.href = "/cososanxuat/tracuu";
    },
    xoa() {
      axios
        .delete(env.endpointhttp + "cososanxuat/delete/" + this.xoaCoSo.id)
        .then((response) => {
          this.getCoSoSanXuat("chua_dong_bo");
          this.showModel = false;
          MessageService.showSuccessMessage("Xóa thành công");
        })
        .catch((error) => {
          MessageService.showWarningMessage("Xoá thất bại");
        })
        .finally();
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
