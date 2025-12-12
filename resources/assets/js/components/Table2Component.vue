<template>
  <div>
    <table class="table table-hover table-responsive">
      <thead>
        <tr class="row-table-header">
          <th
            :style="`width:${item.width ? item.width : null}`"
            class="text-center"
            v-for="(item, index) in header"
            :key="index"
            :rowspan="item.type == 'rowspan' ? item.number : null"
            :colspan="item.type == 'colspan' ? item.number : null"
          >
            {{ item.name }}
          </th>
        </tr>
        <tr class="row-table-header">
          <th
            v-for="item in headerChildren"
            :rowspan="item.type == 'rowspan' ? item.number : null"
            :colspan="item.type == 'colspan' ? item.number : null"
          >
            {{ item.name }}
          </th>
        </tr>
      </thead>
      <tbody>
        <template v-for="(item, index) in dataTable">
          <tr :key="`${item}${index}`" style="cursor:pointer">
            <td
              @click="goToLink(item.id)"
              :rowspan="
                item.ket_qua_thanh_tras && item.ket_qua_thanh_tras.length != 0
                  ? item.ket_qua_thanh_tras.length
                  : null
              "
            >
              {{ item.ten }}
            </td>
            <td
              @click="goToLink(item.id)"
              :rowspan="
                item.ket_qua_thanh_tras && item.ket_qua_thanh_tras.length != 0
                  ? item.ket_qua_thanh_tras.length
                  : null
              "
            >
              {{ item.dia_chi_lien_he }}
            </td>
            <td>
              {{
                item.ket_qua_thanh_tras[0]
                  ? item.ket_qua_thanh_tras[0].quyet_dinh_thanh_tra
                      .so_quyet_dinh
                  : null
              }}
            </td>
            <td>
              {{
                item.ket_qua_thanh_tras[0]
                  ? item.ket_qua_thanh_tras[0].so_quyet_dinh
                  : null
              }}
            </td>
            <td>
              {{
                item.ket_qua_thanh_tras[0]
                  ? item.ket_qua_thanh_tras[0].quyet_dinh_thanh_tra
                      .ngay_ra_quyet_dinh
                  : null
              }}
            </td>
            <td>
              {{
                item.ket_qua_thanh_tras[0]
                  ? item.ket_qua_thanh_tras[0].nguoi_cap_nhat.name
                  : null
              }}
            </td>
            <td
              :rowspan="
                item.ket_qua_thanh_tras && item.ket_qua_thanh_tras.length != 0
                  ? item.ket_qua_thanh_tras.length
                  : null
              "
            >
              <div class="d-flex justify-center align-center">
                <i
                  class="fa fa-edit btn text-center"
                  @click="goToLink(item.id)"
                ></i>
              </div>
            </td>
            <td
              :rowspan="
                item.ket_qua_thanh_tras && item.ket_qua_thanh_tras.length != 0
                  ? item.ket_qua_thanh_tras.length
                  : null
              "
            >
              <div class="d-flex justify-center align-center">
                <i
                  class="fa fa-trash-o btn text-center"
                  @click="goDelete(item.id)"
                ></i>
              </div>
            </td>
          </tr>
          <tr
            style="cursor:pointer"
            v-for="(kq, kqindex) in item.ket_qua_thanh_tras"
            :key="`${kq.so_quyet_dinh}${index}${kqindex}`"
            v-if="kqindex != 0"
          >
            <td>{{ kq.quyet_dinh_thanh_tra.so_quyet_dinh }}</td>
            <td>{{ kq.so_quyet_dinh }}</td>
            <td>{{ kq.quyet_dinh_thanh_tra.ngay_ra_quyet_dinh }}</td>
            <td>{{ kq.nguoi_cap_nhat.name }}</td>
          </tr>
        </template>
      </tbody>
    </table>
    <!-- paging -->
    <paging-component @change-page="changePage" :paging.sync="paging" />
    <!-- paging -->
    <modal-component v-model="showModel" center @submit="deleteCS">
      <div class="text-center">Bạn có muốn xoá mục này</div>
    </modal-component>
  </div>
</template>
<script>
import * as env from "../env.js";

export default {
  props: ["data"],
  data: () => ({
    header: [
      {
        name: "Cơ sở thanh tra",
        type: "rowspan",
        number: 2,
        width: "30%"
      },
      {
        name: "Địa chỉ liên hệ",
        type: "rowspan",
        number: 2,
        width: "30%"
      },
      {
        name: "Kết quả thanh tra",
        type: "colspan",
        number: 4
      },
      {
        name: "",
        type: "colspan",
        number: 2
      }
    ],
    headerChildren: [
      {
        name: "Số QDTL đoàn thanh tra "
      },
      {
        name: "Số quyết định "
      },
      {
        name: "Ngày ban hành "
      },
      {
        name: "Người cập nhập "
      },
      {
        name: "Chỉnh sửa "
      },
      {
        name: "Xoá"
      }
    ],
    keyHeaderChildren: [
      "so_quyet_dinh",
      "so_quyet_dinh",
      "so_quyet_dinh",
      "so_quyet_dinh"
    ],
    showModel: false,
    selectID: null,
    dataTable: [],
    paging: {
      perpage: 10,
      current_page: 1,
      last_page: 1
    }
  }),
  watch: {
    showModel(val) {
      if (!this.showModel) {
        this.selectID = null;
      }
    }
  },
  created() {
    this.dataTable = this.data.data;
    this.paging.current_page = this.data.current_page;
    this.paging.last_page = this.data.last_page;
    this.paging.perpage = this.data.per_page;
  },
  methods: {
    getData() {
      axios
        .get(`${env.endpointhttp}cososanxuats`, {
          params: {
            perpage: this.paging.perpage,
            page: this.paging.current_page,
            paging: true,
            nhap_lieu_cssx: true
          }
        })
        .then(res => {
          this.dataTable = res.data.result.data;
          this.paging.current_page = res.data.result.current_page;
          this.paging.last_page = res.data.result.last_page;
          this.paging.perpage = res.data.result.per_page;
        });
    },
    goToLink(id) {
      window.location = `/cososanxuat/showformupdate/${id}`;
    },
    goDelete(id) {
      this.showModel = true;
      this.selectID = id;
    },
    deleteCS() {
      axios.delete(`${env.endpointhttp}cososanxuat/delete/${this.selectID}`);
    },
    changePage(data) {
      this.paging.current_page = data;
      this.getData();
    }
  }
};
</script>
