<template>
  <div class="box-body">
    <div class="box-header">
      <div class="row">
        <div class="col-xs-12 pull-right">
          <a href="/cososanxuat/tracuu" class="btn btn-flat bg-olive">
            <i class="fa fa-map"> </i>Tra cứu 
          </a>
          <a
            :disabled="is_loading"
            @click="downloadExcel"
            class="btn btn-flat btn-default"
          >
            <i class="fa fa-file-excel-o"> </i>Xuất excel
          </a>

          <a
            v-if="
              usersystem.role_code == 'admin' ||
              usersystem.role_code == 'nhanvientrungtam' ||
              usersystem.role_code == 'adminvaquanlydanhmuc'
            "
            href="/ketquathanhtra/add"
            class="btn btn-flat bg-olive"
          >
            <i class="fa fa-plus"> </i>Thêm mới kết quả thanh tra
          </a>
        </div>
      </div>
    </div>
    <div class="dataTables_wrapper form-inline dt-bootstrap">
      <div class="row">
        <div class="col-sm-4">
          <!-- perpage -->
          <perpage-component
            @changePerPage="changePerPage"
            :options="options"
            :default="paging.perpage"
          ></perpage-component>
        </div>
        <div class="col-sm-8">
          <searchData
            v-model="filter"
            :loaicosos="loaicosos"
            :tinhthanhs="tinhthanhs"
            @search="search"
          />
        </div>
      </div>
      <br />
      <div class="row">
        <div class="col-sm-12">
          <tableList
            :data="dataTable"
            :dataPage="paging"
            @change-data="changePaging"
            @goOrder="goOrder"
            :usersystem="usersystem"
          ></tableList>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import searchData from "./search";
import tableList from "./table";
import * as env from "../../env";
export default {
  props: ["data", "loaicosos", "tinhthanhs", "usersystem"],
  components: {
    searchData,
    tableList,
  },
  data: () => ({
    filter: {},
    paging: {
      perpage: 100,
      current_page: 1,
      last_page: 1,
      total: 0,
    },
    searchParams: {
      search: null,
      search_khu_cong_nghiep: null,
      search_tencososanxuat: null,
      search_masothue: null,
      search_year: null,
      tu_ngay: null,
      den_ngay: null,
      search_year: null,
      search_loai_co_so: null,
      search_tinh_thanh: null,
      search_quan_huyen: null,
      search_mien: null,
      search_vung_kinh_te_trong_diem: null,
      order_column: "ten",
      order_direction: "asc",
      search_year_QD: null,
      tu_ngay_QD: null,
      den_ngay_QD: null,
    },
    dataTable: [],
    options: [10, 20, 50, 100],
    is_loading: false,
  }),
  created() {
    this.dataTable = this.data.data;
    this.paging.current_page = this.data.current_page;
    this.paging.last_page = this.data.last_page;
    this.paging.perpage = this.data.per_page;
    this.paging.total = this.data.total;
  },
  methods: {
    getData() {
      let params = {
        ...this.searchParams,
        page: this.paging.current_page,
        perpage: this.paging.perpage,
      };
      axios
        .get(`${env.endpointhttp}dataCososanxuats`, {
          params: params,
        })
        .then((res) => {
          this.dataTable = res.data.data.data;
          this.paging.current_page = res.data.data.current_page
            ? res.data.data.current_page
            : 1;
          this.paging.last_page = res.data.data.last_page
            ? res.data.data.last_page
            : 1;
          this.paging.perpage = res.data.data.per_page
            ? res.data.data.per_page
            : 1;
          this.paging.total = res.data.data.total ? res.data.data.total : 0;
        });
    },
    search(data) {
      this.paging = this.$options.data.call(this).paging;
      this.searchParams = { ...this.searchParams, ...data };
      this.getData();
    },
    changePaging(data) {
      this.paging = { ...this.paging, ...data };
      this.getData();
    },
    changePerPage(data) {
      this.paging.perpage = data;
      this.getData();
    },
    goOrder(data) {
      this.paging = this.$options.data.call(this).paging;
      this.searchParams.order_column = data.order_column;
      this.searchParams.order_direction = data.order_direction;
      this.getData();
    },
    downloadExcel() {
      this.is_loading = true;
      axios({
        method: "get",
        url: env.endpointhttp + "cososanxuat/exports/excel",
        params: this.filter,
        headers: { Accept: "xlsx" },
        responseType: "blob",
      })
        .then((response) => {
          var fileURL = window.URL.createObjectURL(new Blob([response.data]));
          var fileLink = document.createElement("a");
          fileLink.href = fileURL;
          fileLink.setAttribute("download", "danh_sach_co_so.xlsx");
          document.body.appendChild(fileLink);
          fileLink.click();
        })
        .catch()
        .finally(() => {
          this.is_loading = false;
        });
    },
  },
};
</script>
