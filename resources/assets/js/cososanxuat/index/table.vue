<template>
  <div>
    <table class="table table-hover table-responsive">
      <thead>
        <tr class="row-table-header">
          <th :style="`${item.order ? 'cursor:pointer' : ''}`" class="text-center" v-for="(item, index) in header"
            :key="index" :rowspan="item.type == 'rowspan' ? item.number : null"
            :colspan="item.type == 'colspan' ? item.number : null" :width="item.width"
            @click="goOrder(index, item.value, item.order)">
            <div class="d-flex align-center justify-center">
              <div>{{ item.name }}</div>
              <i v-if="item.order" class="fa fa-sort-asc icon" :style="`height:9px;transition: all .1s;${item.order == 'asc' ? '' : 'transform: rotate(180deg);'
                }`"></i>
            </div>
          </th>
        </tr>
        <tr class="row-table-header">
          <th v-for="item in headerChildren" :rowspan="item.type == 'rowspan' ? item.number : null"
            :colspan="item.type == 'colspan' ? item.number : null">
            {{ item.name }}
          </th>
        </tr>
      </thead>
      <tbody>
        <template v-for="(item, index) in dataTable">
          <tr :key="`${item}${index}`" style="cursor: pointer">
            <td :rowspan="
              item.ket_qua_thanh_tras && item.ket_qua_thanh_tras.length != 0
                ? item.ket_qua_thanh_tras.length
                : null
            ">
              <a style="color: #337ab7" :href="`/cososanxuat/showformupdate/${item.id}`" target="_blank">{{ item.ten
              }}</a>
              <i style="font-size: 13px" v-html="item.old_name"></i>
            </td>
            <td :rowspan="
              item.ket_qua_thanh_tras && item.ket_qua_thanh_tras.length != 0
                ? item.ket_qua_thanh_tras.length
                : null
            ">
              {{ item.dia_chi_lien_he }}
            </td>
            <td>
              <a target="_blank" style="color: #337ab7" v-if="
                item.ket_qua_thanh_tras[0] &&
                item.ket_qua_thanh_tras[0].quyet_dinh_thanh_tra
              " :href="
  '/doanthanhtra/update/' +
  item.ket_qua_thanh_tras[0].quyet_dinh_thanh_tra.id
">{{
  item.ket_qua_thanh_tras[0]
  ? item.ket_qua_thanh_tras[0].quyet_dinh_thanh_tra
    .so_quyet_dinh
  : null
}}
                <div style="font-size: 12px; font-weight: bold; color: gray">{{ item.ket_qua_thanh_tras[0]
                  ? item.ket_qua_thanh_tras[0].quyet_dinh_thanh_tra
                    .ma_dinh_danh
                  : null }}</div>
              </a>
            </td>

            <td>
              {{
                item.ket_qua_thanh_tras[0] &&
                item.ket_qua_thanh_tras[0].quyet_dinh_thanh_tra
                ? item.ket_qua_thanh_tras[0].quyet_dinh_thanh_tra
                  .ngay_ra_quyet_dinh
                : null
              }}
            </td>
            <td>
              <a target="_blank" style="color: #337ab7" v-if="item.ket_qua_thanh_tras[0]"
                :href="'/ketquathanhtra/edit/' + item.ket_qua_thanh_tras[0].id">
                <div>
                  {{
                    item.ket_qua_thanh_tras[0]
                    ? item.ket_qua_thanh_tras[0].so_quyet_dinh
                    : null
                  }}
                </div>
                <div style="font-size: 12px; font-weight: bold; color: gray">{{item.ket_qua_thanh_tras[0] ?  item.ket_qua_thanh_tras[0].ma_dinh_danh : null}}
                </div>
              </a>
            </td>
            <!-- <td>
              {{
                item.ket_qua_thanh_tras[0]
                  ? item.ket_qua_thanh_tras[0].ma_dinh_danh
                  : null
              }}
            </td> -->
            <td>
              {{
                item.ket_qua_thanh_tras[0]
                ? item.ket_qua_thanh_tras[0].ngay_thanh_tra
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
            <td :rowspan="
              item.ket_qua_thanh_tras && item.ket_qua_thanh_tras.length != 0
                ? item.ket_qua_thanh_tras.length
                : null
            ">
              <div class="d-flex justify-center align-center">
                <i class="fa fa-edit btn text-center" @click="goToLink(item.id)"></i>
              </div>
            </td>
            <td v-if="
              usersystem.role_code == 'admin' ||
              usersystem.role_code == 'nhanvientrungtam' ||
              usersystem.role_code == 'adminvaquanlydanhmuc'
            " :rowspan="
  item.ket_qua_thanh_tras && item.ket_qua_thanh_tras.length != 0
    ? item.ket_qua_thanh_tras.length
    : null
">
              <div class="d-flex justify-center align-center">
                <i class="fa fa-trash-o btn text-center" @click="goDelete(item.id)"></i>
              </div>
            </td>
          </tr>
          <tr style="cursor: pointer" v-for="(kq, kqindex) in item.ket_qua_thanh_tras"
            :key="`${kq.so_quyet_dinh}${index}${kqindex}`" v-if="kqindex != 0">
            <td>
              <a target="_blank" v-if="kq.quyet_dinh_thanh_tra" style="color: #337ab7"
                :href="'/doanthanhtra/update/' + kq.quyet_dinh_thanh_tra.id">{{ kq.quyet_dinh_thanh_tra.so_quyet_dinh
                }}</a>
            </td>
            <td>
              {{
                kq.quyet_dinh_thanh_tra &&
                kq.quyet_dinh_thanh_tra.ngay_ra_quyet_dinh
              }}
            </td>
            <td>
              <a target="_blank" style="color: #337ab7" :href="'/ketquathanhtra/edit/' + kq.id">{{ kq.so_quyet_dinh }}</a>
            </td>
            <td>
              {{ kq.ngay_thanh_tra }}
            </td>

            <td>{{ kq.nguoi_cap_nhat.name }}</td>
          </tr>
        </template>
      </tbody>
    </table>
    <!-- paging -->
    <paging-component @change-page="changePage" :paging.sync="paging" />
    <!-- paging -->
    <modal-component width="450px" v-model="showModel" center @submit="deleteCS">
      <div class="text-center">Xác nhận xóa ?</div>
    </modal-component>
  </div>
</template>
<script>
import * as env from "../../env";
import MessageService from "../../services/MessageService";
export default {
  props: ["data", "dataPage", "usersystem"],
  data: () => ({
    header: [
      {
        name: "Cơ sở thanh tra",
        type: "rowspan",
        number: 2,
        width: "25%",
        order: "asc",
        value: "ten",
      },
      {
        name: "Địa chỉ liên hệ",
        type: "rowspan",
        number: 2,
        width: "25%",
        order: "asc",
        value: "dia_chi_lien_he",
      },
      {
        name: "Kết quả thanh tra",
        type: "colspan",
        number: 5,
      },
      {
        name: "",
        type: "colspan",
        number: 2,
      },
    ],
    headerChildren: [
      {
        name: "Số QĐTL đoàn TT / Mã định danh",
      },
      {
        name: "Ngày ban hành QĐ ",
      },
      {
        name: "Số kết luận TT / Mã định danh",
      },
      {
        name: "Ngày ban hành KL ",
      },

      {
        name: "Người cập nhập ",
      },
      {
        name: "Chỉnh sửa ",
      },
    ],
    showModel: false,
    selectID: null,
    dataTable: [],
    paging: {},
  }),
  mounted() {
    let usersystem = this.usersystem;
    if (
      usersystem.role_code == "admin" ||
      usersystem.role_code == "nhanvientrungtam" ||
      usersystem.role_code == "adminvaquanlydanhmuc"
    ) {
      this.headerChildren.push({
        name: "Xoá",
      });
    }
  },
  watch: {
    showModel(val) {
      if (!this.showModel) {
        this.selectID = null;
      }
    },
    dataPage: {
      handler: function (val) {
        this.paging = val;
      },
      deep: true,
    },
    data: {
      handler: function (val) {
        this.formatData(val);
      },
    },
  },
  created() {
    this.paging = this.dataPage;
    this.formatData(this.data);
  },
  methods: {
    formatData(val) {
      this.dataTable = val.map((x) => {
        var old_name = "";
        x.co_so_san_xuats.map((item, key) => {
          if (this.old(item.old_name)) {
            old_name +=
              "<br> Đia điểm " + (key + 1) + "-" + this.old(item.old_name);
          }
        });
        x.old_name = old_name;

        return x;
      });
      console.log(33, this.dataTable)
    },
    old(val) {
      if (val && val.length > 0) {
        let str = "";
        val.forEach((x) => {
          str += `${x}, `;
        });
        return `(${str.substring(0, str.length - 2)})`;
      }
    },
    goToLink(id) {
      window.location = `/cososanxuat/showformupdate/${id}`;
    },
    goDelete(id) {
      this.showModel = true;
      this.selectID = id;
    },
    deleteCS() {
      let usersystem = this.usersystem;
      if (
        usersystem.role_code == "admin" ||
        usersystem.role_code == "nhanvientrungtam" ||
        usersystem.role_code == "adminvaquanlydanhmuc"
      ) {
        axios
          .delete(`${env.endpointhttp}cososanxuat/delete/${this.selectID}`)
          .then((response) => {
            MessageService.showSuccessMessage("Xóa thành công");
          })
          .finally(() => {
            this.showModel = false;
            window.location = "/cososanxuat";
          });
      }
    },
    changePage(data) {
      this.paging.current_page = data;
      this.$emit("change-data", this.paging);
    },
    goOrder(index, value, order) {
      this.header[index].order = order == "asc" ? "desc" : "asc";
      this.$emit("goOrder", {
        order_column: value,
        order_direction: this.header[index].order,
      });
    },
  },
};
</script>
<style scoped lang="scss">
.row-table-header {
  th {
    .icon {
      display: none;
    }

    &:hover {
      .icon {
        display: inline-block;
      }
    }
  }
}
</style>
