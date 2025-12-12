<template>
  <div class="timeline-body">
    <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-4">
            <label class="control-label">Tỉnh/Thành phố</label>
            <multiselect v-model="tinh.value" placeholder="Chọn tỉnh, thành phố" label="ten" track-by="id"
              :options="tinh.options" :multiple="true" selectLabel="Nhấn enter để chọn"
              deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn" :loading="tinh.isLoading" :tabindex="2"
              @input="getProvince()">
            </multiselect>
          </div>
          <div class="col-md-4">
            <label class="control-label">Quận/Huyện</label>
            <multiselect v-model="huyen.value" placeholder="Chọn quận, huyện" label="ten" track-by="id"
              :options="huyen.options" :multiple="true" selectLabel="Nhấn enter để chọn"
              deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn" :loading="huyen.isLoading" :tabindex="3">
            </multiselect>
          </div>
          <div class="col-md-4">
            <label class="control-label">Loại khu công nghiệp</label>
            <multiselect v-model="loaikhucongnghiep.value" placeholder="Chọn loại khu công nghiệp" label="ten"
              track-by="ma" :options="loaikhucongnghiep.options" :multiple="false" selectLabel="Nhấn enter để chọn"
              deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn" :loading="loaikhucongnghiep.isLoading"
              :tabindex="4">
            </multiselect>
          </div>
        </div>
        <div class="row" style="padding: 20px 0px;">
          <div class="col-md-4">
            <div class="input-group">
              <input type="text" v-model="search" class="form-control" style="z-index: 0"
                placeholder="Nhập thông tin tìm kiếm" tabindex="1" autofocus @keyup.enter="searchData()" />
              <span class="input-group-addon">
                <i class="fa fa-search"></i>
              </span>
            </div>
          </div>
          <div class="col-md-4">
            <button type="button" class="btn btn-flat" @click="searchData()">
              <i class="fa fa-search"></i>&nbsp;&nbsp;Tìm kiếm
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <table class="table table-hover">
          <tbody>
            <tr class="row-table-header">
              <th>STT</th>
              <th>Tên</th>
              <th>Địa chỉ</th>
              <th>Tỉnh thành</th>
              <th>Quận huyện</th>
              <th>Loại khu công nghiệp</th>
              <th
                style="width:5%"
                v-if="
                  usersystem.role_code == 'admin' ||
                    usersystem.role_code == 'adminvaquanlydanhmuc'
                "
              ></th>
            </tr>
            <tr v-for="(item, index) in data" :key="item.data">
              <td>{{ index + 1 }}</td>
              <td>
                <input type="text" class="form-control  " v-model="item.ten" @change="updateIndustrialZone(item)" v-if="
                  usersystem.role_code == 'admin' ||
                    usersystem.role_code == 'adminvaquanlydanhmuc'
                " />
                <input type="text" class="form-control  " v-model="item.ten" v-else />
              </td>
              <td>
                <input type="text" class="form-control  " v-model="item.dia_chi" @change="updateIndustrialZone(item)"
                  v-if="
                    usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                  " />
                <input type="text" class="form-control  " v-model="item.dia_chi" v-else />
              </td>
              <td>
                <multiselect v-model="item.tinh_thanh" placeholder="Chọn" label="ten" track-by="id"
                  :options="tinh.options" :multiple="false" selectLabel="Nhấn enter để chọn"
                  deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn" :tabindex="3"
                  @input="updateIndustrialZone(item, 1)" v-if="
                    usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                  ">
                </multiselect>
                <input type="text" v-model="item.tinh_thanh.ten" class="form-control" v-else />
              </td>
              <td>
                <multiselect v-model="item.quan_huyen" placeholder="Chọn" label="ten" track-by="id" :options="
                  item.tinh_thanh_id ? item.tinh_thanh.quan_huyens : []
                " :multiple="false" selectLabel="Nhấn enter để chọn" deselectLabel="Nhấn enter để bỏ chọn"
                  selectedLabel="Đã chọn" :tabindex="3" @input="updateIndustrialZone(item)" v-if="
                    usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                  ">
                </multiselect>
                <input type="text" v-model="item.quan_huyen.ten" class="form-control" v-else />
              </td>
              <td>
                <multiselect v-model="item.loai_khu_cong_nghiep" placeholder="Chọn" label="ten" track-by="ma"
                  :options="loaikhucongnghiep.options" :multiple="false" selectLabel="Nhấn enter để chọn"
                  deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn" :tabindex="3"
                  @input="updateIndustrialZone(item)" v-if="
                    usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                  ">
                </multiselect>
                <input type="text" v-model="item.loai_khu_cong_nghiep.ten" class="form-control" v-else />
              </td>
              <td
                align="center"
                v-if="
                  usersystem.role_code == 'admin' ||
                    usersystem.role_code == 'adminvaquanlydanhmuc'
                "
              >
                <a @click="deleteIndustrialZone(item, index)">
                  <i class="fa fa-trash-o btn"></i
                ></a>
              </td>
            </tr>
            <tr v-if="
              usersystem.role_code == 'admin' ||
                usersystem.role_code == 'adminvaquanlydanhmuc'
            ">
              <td colspan="2">
                <input type="text" class="form-control" placeholder="Tên" v-model="ten" />
              </td>
              <td>
                <input type="text" class="form-control" placeholder="Địa chỉ" v-model="dia_chi" />
              </td>
              <td>
                <multiselect v-model="tinh_thanh" placeholder="Chọn" label="ten" track-by="id" :options="tinh.options"
                  :multiple="false" selectLabel="Nhấn enter để chọn" deselectLabel="Nhấn enter để bỏ chọn"
                  selectedLabel="Đã chọn" @input="quan_huyen = []" :tabindex="3">
                </multiselect>
              </td>
              <td>
                <multiselect v-model="quan_huyen" placeholder="Chọn" label="ten" track-by="id" :options="
                  tinh_thanh ? (tinh_thanh.length == 0 ? huyen.data : tinh_thanh.quan_huyens) : huyen.data
                " :multiple="false" selectLabel="Nhấn enter để chọn" deselectLabel="Nhấn enter để bỏ chọn"
                  selectedLabel="Đã chọn" :tabindex="3">
                </multiselect>
              </td>
              <td>
                <multiselect v-model="loai_khu_cong_nghiep" placeholder="Chọn" label="ten" track-by="ma"
                  :options="loaikhucongnghiep.options" :multiple="false" selectLabel="Nhấn enter để chọn"
                  deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn" :tabindex="3">
                </multiselect>
              </td>
              <td align="center">
                <a @click="addIndustrialZone()"><i class="fa fa-plus btn"></i></a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div style="float: right">
        <paginate v-model="page" :page-count="count" :page-range="3" :margin-pages="2" :click-handler="changePage"
          :prev-text="'‹‹'" :next-text="'››'" :container-class="'pagination'" :page-class="'page-item'">
        </paginate>
    </div>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import * as env from "../../env.js";
import MessageService from "../../services/MessageService";
import Paginate from "vuejs-paginate";

export default {
  components: {
    Multiselect
  },
  props: ["usersystem"],
  data: function () {
    return {
      popUpAdd: -1,
      search: null,
      data: [],
      ma: null,
      ten: null,
      dia_chi: null,
      quan_huyen: [],
      tinh_thanh: [],
      loai_khu_cong_nghiep: [],
      count: 0,
      page: 1,
      tinh: {
        value: [],
        options: [],
        data: [],
        isLoading: true
      },
      huyen: {
        value: [],
        options: [],
        data: [],
        isLoading: true
      },
      loaikhucongnghiep: {
        value: [],
        options: [],
        data: [],
        isLoading: true
      },
      queryUrl: ''
    };
  },
  mounted() {
    this.getIndustrialZone();
    axios
      .get(env.endpointhttp + "tinhthanhs")
      .then(response => {
        this.tinh.options = response.data.result;
        this.tinh.data = this.tinh.options;
      })
      .catch(error => {
        console.log(error);
      })
      .finally(() => (this.tinh.isLoading = false));
    axios
      .get(env.endpointhttp + "quanhuyens")
      .then(response => {
        this.huyen.options = response.data.result;
        this.huyen.data = this.huyen.options;
      })
      .catch(error => {
        console.log(error);
      })
      .finally(() => (this.huyen.isLoading = false));
    axios
      .get(env.endpointhttp + "loaikhucongnghieps")
      .then(response => {
        this.loaikhucongnghiep.options = response.data.result;
        this.loaikhucongnghiep.data = this.loaikhucongnghiep.options;
      })
      .catch(error => {
        console.log(error);
      })
      .finally(() => (this.loaikhucongnghiep.isLoading = false));
  },
  methods: {
    getProvince() {
      if (this.tinh.value.length > 0) {
        this.huyen.value = [];
        this.huyen.options = this.huyen.data.filter(function (
          currentValue,
          index,
          arr
        ) {
          return (
            this.findIndex(function (tinh) {
              return currentValue.tinh_thanh_id == tinh.id;
            }) >= 0
          );
        },
          this.tinh.value);
      } else {
        this.huyen.options = this.huyen.data;
      }
    },
    changePage() {
      axios
        .get(
          env.endpointhttp +
          "khucongnghieps?screen=danhmuc" +
          "&page=" +
          this.page + this.queryUrl
        )
        .then(response => {
          this.data = response.data.result.data;
          this.count = response.data.result.last_page;
        })
        .catch(error => {
          console.log(error);
        })
        .finally();
    },
    searchData() {
      this.page = 1;
      this.getIndustrialZone()
    },
    getIndustrialZone() {
      var Url = "";
      if (this.search) {
        Url = Url + "&search=" + this.search + "&&";
      }
      if (this.tinh.value) {
        var tinhIds = [];
        this.tinh.value.forEach(element => {
          tinhIds.push(element.id);
        });
        Url = Url + "&tinh=" + tinhIds + "&&";
      }
      if (this.huyen.value) {
        var huyenIds = [];
        this.huyen.value.forEach(element => {
          huyenIds.push(element.id);
        });
        Url = Url + "&huyen=" + huyenIds + "&&";
      }
      if (this.loaikhucongnghiep.value) {
        if (this.loaikhucongnghiep.value.ma) {
          Url =
            Url +
            "&loaikhucongnghiep=" +
            this.loaikhucongnghiep.value.ma +
            "&&";
        }
      }
      this.queryUrl = Url
      axios
        .get(
          env.endpointhttp +
          "khucongnghieps?screen=danhmuc" +
          "&page=" +
          this.page +
          Url
        )
        .then(response => {
          this.data = response.data.result.data;
          this.count = response.data.result.last_page;
        })
        .catch(error => {
          console.log(error);
        })
        .finally();
    },
    updateIndustrialZone(item, value) {
      if (value) {
        item.quan_huyen.id = null;
      }
      if (item.ten == "" || item.ten == null) {
        MessageService.showWarningMessage("Bạn bắt buộc phải nhập tên");
      } else {
        axios
          .put(env.endpointhttp + "khucongnghiep/update/" + item.id, {
            ten: item.ten,
            dia_chi: item.dia_chi,
            quan_huyen_id: item.quan_huyen.id,
            tinh_thanh_id: item.tinh_thanh.id,
            loai_khu_cong_nghiep: item.loai_khu_cong_nghiep.ma
          })
          .then(response => {
            if (response.status == 200) {
              MessageService.showSuccessMessage("Cập nhật thành công");
            }
          })
          .catch(error => {
            MessageService.showWarningMessage("Cập nhật thất bại");
          })
          .finally();
      }
    },
    deleteIndustrialZone(value, index) {
      axios
        .delete(env.endpointhttp + "khucongnghiep/delete/" + value.id)
        .then(response => {
          if (response.data.message == "message.success") {
            this.data.splice(index, 1);
            MessageService.showSuccessMessage("Xoá thành công");
            this.getIndustrialZone();
          } else if (response.data.message == "message.fails") {
            MessageService.showDangerMessage(message || "Xóa không thành công");
          }
        })
        .catch(error => { })
        .finally();
    },
    addIndustrialZone() {
      if (this.ten == "" || this.ten == null) {
        MessageService.showWarningMessage(
          "Bạn bắt buộc phải nhập tên khu công nghiệp"
        );
      } else {
        axios
          .post(env.endpointhttp + "khucongnghiep/add", {
            ten: this.ten,
            dia_chi: this.dia_chi,
            quan_huyen_id: this.quan_huyen.id,
            tinh_thanh_id: this.tinh_thanh.id,
            loai_khu_cong_nghiep: this.loai_khu_cong_nghiep.ma
          })
          .then(response => {
            this.data.push(response.data.result);
            this.ten = "";
            this.dia_chi = "";
            this.quan_huyen = [];
            (this.tinh_thanh = []), (this.loai_khu_cong_nghiep = []);
            MessageService.showSuccessMessage("Thêm mới thành công");
            this.getIndustrialZone();
          })
          .catch(error => {
            MessageService.showWarningMessage("Thêm mới thất bại");
          })
          .finally();
      }
    }
  }
};
</script>
