<template>
  <div style="height:100vh;display:flex; flex-direction:column">
    <div class="row" style="flex:none">
      <Tracuuheader :checklogin="checklogin" />
    </div>
    <div class="row" style="flex:1">
      <div class="col-md-4" style="max-height:100%;height:100%">
        <div class="row left relative padding-15" style="padding: 5px 45px 0 70px !important;">
          <div style="width: 100%">
            <p class style="
                font-size: 22px;
                font-weight: 600;
                margin: 15px 0 10px 0;
                text-transform: capitalize;
              ">
              Tra cứu cơ sở
            </p>
          </div>
          <div class="col-md-6" style="width: 100%;">
            <label>Miền</label>
            <multiselect v-model="mien" placeholder="Chọn" label="ten" track-by="id" :group-select="false"
              :options="miens" :multiple="false" selectLabel="Nhấn enter để chọn" deselectLabel="Nhấn enter để bỏ chọn"
              selectedLabel="Đã chọn" :tabindex="6"></multiselect>
            <label>Lưu vực sông</label>
            <multiselect v-model="luuvucsong" placeholder="Chọn" label="ten" track-by="id" :group-select="false"
              :options="luuvucsongs" :multiple="false" selectLabel="Nhấn enter để chọn"
              deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn" :tabindex="6"></multiselect>
            <label>Tỉnh</label>
            <multiselect v-model="tinh" placeholder="Chọn" label="ten" track-by="id" :group-select="false"
              :options="tinhs" :multiple="false" selectLabel="Nhấn enter để chọn" deselectLabel="Nhấn enter để bỏ chọn"
              selectedLabel="Đã chọn" :tabindex="6"></multiselect>

            <label>Cơ sở thanh tra</label>
            <multiselect v-model="coso" placeholder="Chọn" label="ten" track-by="id" :group-select="false"
              :options="danhsachcoso" :multiple="false" selectLabel="Nhấn enter để chọn"
              deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn" :tabindex="6"></multiselect>
          </div>
          <!-- kết quả tra cứu -->
          <div class="content-welcome" style="width: 100%" v-if="cososanxuats && cososanxuats.length > 0">
            <div style="width: 100%">
              <p class style="
                  font-size: 22px;
                  font-weight: 600;
                  margin: 15px 0 10px 0;
                  text-transform: capitalize;
                ">
                Kết quả tra cứu
              </p>
            </div>
            <div>
              <div class="auto-scroll">
                <table class="table table-hover table-responsive">
                  <tbody>
                    <tr class="row-table-header">
                      <th>Tên</th>
                      <th style="width:20%">KCN</th>
                      <th>Địa chỉ</th>
                    </tr>
                    <tr v-for="(item, index) in cososanxuats" :key="item.ten + index" @click="markerPopup(item.id)">
                      <td>{{ item.ten ? item.ten : "---" }}</td>
                      <td>
                        {{
                        item.khu_cong_nghiep.ten
                        ? item.khu_cong_nghiep.ten
                        : "---"
                        }}
                      </td>
                      <td>
                        {{
                        item.dia_chi_hoat_dong
                        ? item.dia_chi_hoat_dong
                        : "---"
                        }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <label v-if="url">Hình ảnh</label>
              <br />
              <img v-if="url != null" height="120px" width="200px" v-bind:src="url" />
              <table class="table table-hover table-responsive" v-for="task in tasks">
                <tbody>
                  <tr>
                    <td colspan="2">
                      <b>Lịch sử thanh tra</b>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="box-icon">
                        <i class="fa fa-info fa-lg" style="font-size: 15px; color: #0073b7;"></i>
                      </div>
                      &nbsp&nbspSố QĐTT
                    </td>
                    <td>
                      {{
                      task.quyet_dinh_thanh_tra.so_quyet_dinh
                      ? task.quyet_dinh_thanh_tra.so_quyet_dinh
                      : "---"
                      }}
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="box-icon">
                        <i class="fa fa-user fa-lg" style="font-size: 15px; color: #0073b7;"></i>
                      </div>
                      &nbsp&nbspCơ quan ra quyết định
                    </td>
                    <td>
                      {{
                      task.quyet_dinh_thanh_tra.co_quan_quyet_dinh.ten
                      ? task.quyet_dinh_thanh_tra.co_quan_quyet_dinh.ten
                      : "---"
                      }}
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="box-icon">
                        <i class="fa fa-calendar-check-o fa-lg" style="font-size: 15px; color: #0073b7;"></i>
                      </div>
                      &nbsp&nbspNgày ra quyết định
                    </td>
                    <td>
                      {{
                      (task.quyet_dinh_thanh_tra.ngay_ra_quyet_dinh
                      ? task.quyet_dinh_thanh_tra.ngay_ra_quyet_dinh
                      : "---") | formatDate
                      }}
                    </td>
                  </tr>
                </tbody>
              </table>
              <!-- paging -->
              <paging-component @change-page="changePage" :paging.sync="paging" />
              <!-- paging -->
            </div>
          </div>
          <!--End kết quả tra cứu -->
        </div>
      </div>
      <!-- :checklogin="checklogin" -->
      <div class="col-md-8 relative" style="margin-left: -15px; padding-right: 0px;height:100%">
        <map-component style="max-height:100%" :map_popup_id="map_popup_id" :hideText="true" noLogin
          @macoso="getId($event)" @khucongnghiep="getKhuCongNghiep($event)" @tencoso="getTen($event)"
          @hinhanh="getHinhAnh($event)" @diachi="getDiaChi($event)" @trangthai="getTrangThai($event)"
          @remove-popup="markerPopup" :cososanxuats="cososanxuats" :local="local" :hidenInput="1"></map-component>
      </div>
    </div>
    <div class="row footer justify-center align-center" style="flex:none">
      <div class="logo">
        <img src="/images/logo-06.png" alt="logo" srcset />
      </div>
      <div class="content-footer">
        <span class="ma-0 text-uppercase">Cơ sở dữ liệu thanh tra môi trường</span>
      </div>
    </div>
  </div>
</template>

<script>
import { endpoint, endpointhttp } from "../env.js";
import Tracuuheader from "./header.vue";
import moment from "../../../../node_modules/admin-lte/bower_components/moment/moment.js";
Vue.filter("formatDate", function (value) {
  if (value) {
    return moment(String(value)).format("DD/MM/YYYY");
  }
});
// Vue.component("header", header)
import Multiselect from "vue-multiselect";
export default {
  props: {
    usersystem: {
      type: Object,
      default: null
    }
  },
  components: {
    Multiselect,
    Tracuuheader
  },
  data: function () {
    return {
      checklogin: false,
      action: true,
      loading: true,
      ten: null,
      tinh: null,
      tinhs: [],
      mien: null,
      miens: [],
      cososanxuat: null,
      luuvucsong: null,
      khucongnghiep: null,
      coso: null,
      danhsachcoso: [],
      cososanxuats: [],
      luuvucsongs: [],
      tasks: [],
      url: null,
      diachi: null,
      trangthai: null,
      local: null,
      search: null,
      errors: {
        code_sender: null
      },
      paging: {
        current_page: 1,
        last_page: 1
      },
      map_popup_id: null
    };
  },
  watch: {
    mien(mien) {
      if (mien) {
        this.miens.forEach(element => {
          if (element.id == mien.id) {
            this.local = element;
          }
        });
        axios
          .get(endpointhttp + "cososanxuats?paging=1&mien_id=" + mien.id)
          .then(response => {
            this.coso = null;
            this.cososanxuats = response.data.result.data;
            this.paging.current_page = response.data.result.current_page;
            this.paging.last_page = response.data.result.last_page;
            this.danhsachcoso = response.data.result.data;
          });
        axios
          .get(endpointhttp + "tinhthanhs?mienId=" + mien.id)
          .then(response => {
            this.tinh = null;
            this.luuvucsong = null;
            this.tinhs = response.data.result;
          });
      } else {
        axios.get(endpointhttp + "tinhthanhs").then(response => {
          this.tinhs = response.data.result;
        });
      }
    },
    tinh(tinh) {
      if (tinh) {
        this.tinhs.forEach(element => {
          if (element.id == tinh.id) {
            this.local = element;
          }
        });
        axios
          .get(
            endpointhttp +
            "cososanxuats?paging=1&tinh_id=" +
            tinh.id +
            "&mien_id=" +
            (this.mien ? this.mien.id : null)
          )
          .then(response => {
            this.coso = null;
            this.cososanxuats = response.data.result.data;
            this.paging.current_page = response.data.result.current_page;
            this.paging.last_page = response.data.result.last_page;
            this.danhsachcoso = response.data.result.data;
          });
      }
    },
    luuvucsong(lucvuc) {
      if (lucvuc) {
        axios
          .get(
            endpointhttp +
            "cososanxuats?paging=1&luuvucsong_id=" +
            (lucvuc ? lucvuc.id : null) +
            "&mien_id=" +
            (this.mien ? this.mien.id : null)
          )
          .then(response => {
            this.cososanxuats = response.data.result.data;
            this.paging.current_page = response.data.result.current_page;
            this.paging.last_page = response.data.result.last_page;
            this.danhsachcoso = response.data.result.data;
            axios
              .get(endpointhttp + "tinhthanhs?luuVucSongId=" + lucvuc.id)
              .then(response => {
                this.tinh = null;
                this.tinhs = response.data.result;
              });
          });
      } else {
        axios.get(endpointhttp + "tinhthanhs").then(response => {
          this.tinhs = response.data.result;
        });
      }

    },
    coso(coso) {
      if ((this.action = true)) {
        this.action = false;
        axios
          .get(
            endpointhttp +
            "cososanxuats?paging=1&search=" +
            this.coso.id +
            "&mien_id=" +
            (this.mien ? this.mien.id : null) +
            "&tinh_thanh_id=" +
            +(this.tinh ? this.tinh.id : null)
          )
          .then(response => {
            this.cososanxuats = response.data.result.data;
            this.paging.current_page = response.data.result.current_page;
            this.paging.last_page = response.data.result.last_page;
            if (this.coso != 0) {
              this.tasks =
                response.data.result.data[0].chi_tiet_co_so_thanh_tra;
              this.ten = response.data.result.data[0].ten;
              this.khucongnghiep =
                response.data.result.data[0].khu_cong_nghiep.ten;
              this.diachi = response.data.result.data[0].dia_chi_hoat_dong;
              this.local = null;
            } else {
              this.tasks = [];
              this.khucongnghiep = null;
              this.ten = null;
              this.diachi = null;
            }
          })
          .catch(error => { })
          .finally(() => {
            this.loading = true;
            this.action = true;
          });
      }
    },
    cososanxuats(cososanxuats) {
      if (cososanxuats && cososanxuats.length > 0) {
        let data = cososanxuats.find(x => x.id == this.map_popup_id);
        if (!data) {
          this.map_popup_id = null;
        }
      }
    }
  },
  created() {
    this.checklogin = !!this.usersystem;
    axios.get(endpointhttp + "miens").then(response => {
      this.miens = response.data.result;
    });
    axios.get(endpointhttp + "tinhthanhs").then(response => {
      this.tinhs = response.data.result;
    });
    axios.get(endpointhttp + "luuvucsongs").then(response => {
      this.luuvucsongs = response.data.result;
    });
    axios.get(endpointhttp + "cososanxuats").then(response => {
      this.danhsachcoso = response.data.result;
      this.getBaocaotonghop();
    });
  },
  methods: {
    async getData() {
      let params = {
        mien_id: this.mien ? this.mien.id : null,
        tinh_thanh_id: this.tinh ? this.tinh.id : null,
        luuvucsong_id: this.luuvucsong ? this.luuvucsong.id : null,
        paging: 1,
        page: this.paging.current_page
      };

      let response = await axios.get(endpointhttp + "cososanxuats", {
        params: params
      });
      this.cososanxuats = response.data.result.data;
    },
    getBaocaotonghop() {
      var currentUrl = window.location.href;
      var url = new URL(currentUrl);
      let value = url.searchParams.get("cososanxuat?paging=1");
      for (let i in this.danhsachcoso) {
        if (this.danhsachcoso[i].id == value) {
          this.coso = this.danhsachcoso[i];
        }
      }
    },
    getKhuCongNghiep(value) {
      if (value) {
        this.khucongnghiep = value;
      }
    },
    getHinhAnh(value) {
      if (value) {
        this.url = value;
      }
    },
    getTen: function (value) {
      this.ten = value;
      this.submitCodeSender(value);
    },
    getDiaChi: function (value) {
      this.diachi = value;
    },
    getTrangThai: function (value) {
      this.trangthai = value;
    },
    getId: function (value) {
      for (let i in this.danhsachcoso) {
        if (this.danhsachcoso[i].id == value) {
          this.coso = this.danhsachcoso[i];
        }
      }
      this.submitCodeSender();
    },
    submitCodeSender() {
      this.loading = false;
      if (this.coso) {
      } else {
        this.cososanxuats = [];
        this.tasks = [];
        this.ten = null;
        this.diachi = null;
      }
    },
    changePage(data) {
      this.paging.current_page = data;
      this.getData();
    },
    markerPopup(id) {
      this.map_popup_id = id;
    }
  },
  mounted() { }
};
</script>
<style scoped>
.padding-15 {
  padding: 0 15px;
}

.relative {
  position: relative;
}

.absolute {
  position: absolute;
}

.abs-login {
  top: 0;
  right: 0;
  margin: 15px;
  z-index: 999;
  margin-right: 75px;
}

.btn-login {
  padding: 5px 15px;
  color: #fff;
  border: 1px solid #0073b7;
  font-weight: bold;
  outline: none;
  background: #0073b7;
  border-radius: 25px;
}

.content-welcome {
  margin-top: 10px;
  background: #ffffff;
  margin-left: 7px;
  max-height: 50vh;
}

.input-group {
  position: relative;
  display: table;
  border-collapse: separate;
}

.header {
  text-align: center;
  font-size: large;
  font-family: "Roboto";
  color: #0073b7;
}

.left {
  /* box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); */
  background: #fff;
  opacity: 1;
  /* border-radius: 3px; */
  height: 100%;
  max-height: 100%;
  overflow: auto;
}

.search {
  border-radius: 20px;
  font-size: 14px;
  font-weight: 400;
  font-family: "Roboto";
  margin-top: 25px;
  margin-left: 7px;
}

.auto-scroll {
  height: 300px;
  overflow-x: auto;
}

.box-icon {
  text-align: center;
  width: 30px;
  display: inline-block;
}

.text-bold {
  font-weight: bold;
  font-size: large;
}

/* label */
label {
  margin: 10px 0;
  font-size: 14px;
  font-weight: 600;
  color: #5d4e4ef2;
}

/deep/ .multiselect__tags {
  border-radius: 6px !important;
}
</style>

<style lang="scss" scoped>
.table {
  position: relative;
}

.footer {
  background-color: #333333;
  color: #717171;
  display: flex;
  height: 50px;

  .logo {
    margin-right: 20px;

    img {
      width: 40px;
    }
  }
}

table {
  tr {
    cursor: pointer;
  }
}

.row-table-header {
  th {
    position: sticky;
    top: -1px;
    background-color: #f4f4f4;
    cursor: pointer;

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
