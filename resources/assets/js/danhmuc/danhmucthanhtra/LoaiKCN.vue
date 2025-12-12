<template>
  <div class="timeline-body">
    <div style="display:flex; align-items:center; justify-content:space-between">
      <div v-if="load_thoi_gian">
        <div v-if="this.thoi_gian == ''">Chưa đồng bộ với CSDL quốc gia</div>
        <div v-else>Đồng bộ lần cuối: {{ thoi_gian_dong_bo }}</div>
      </div>
      <div v-else>Đang đồng bộ với CSDL quốc gia</div>
      <div style="margin-right:20px; margin-bottom:10px;">
        <div style="width: 140px;" class="ml-4">
          <div class="btn btn-flat btn-block btn-default d-flex align-center" @click="dongbodulieu()" :disabled="!trangThaiDongBo"
            style="height: 40px">
            <i class="fa fa-refresh" style="padding-right:5px"></i>
            Đồng bộ dữ liệu
          </div>
        </div>
      </div>
    </div>
    <table class="table table-hover">
      <tbody>
        <tr class="row-table-header">
          <th style="width: 5%">STT</th>
          <th style="width: 15%">Mã</th>
          <th>Tên</th>
          <!-- <th style="width: 5%" v-if="
            usersystem.role_code == 'admin' ||
            usersystem.role_code == 'adminvaquanlydanhmuc'
          "></th> -->
        </tr>
        <tr v-for="(item, index) in data" :key="item.data">
          <td>{{ index + 1 }}</td>
          <td>
            <input type="text" class="form-control" v-model="item.ma" disabled />
          </td>
          <td>
            <input type="text" class="form-control" v-model="item.ten" @change="updateLoaiCoSo(item.ma, item.ten)" v-if="
              usersystem.role_code == 'admin' ||
              usersystem.role_code == 'adminvaquanlydanhmuc'
            " />
            <input type="text" class="form-control" v-model="item.ten" v-else />
          </td>
          <!-- <td align="center" v-if="
            usersystem.role_code == 'admin' ||
            usersystem.role_code == 'adminvaquanlydanhmuc'
          ">
            <a @click="deleteLoaiCoSo(item.ma, index)">
              <i class="fa fa-trash-o btn"></i></a>
          </td> -->
        </tr>
        <tr v-if="
          usersystem.role_code == 'admin' ||
          usersystem.role_code == 'adminvaquanlydanhmuc'
        ">
          <td colspan="2">
            <input type="text" class="form-control" v-model="ma" placeholder="Mã" /><span style="color: red"
              v-text="error_add_loai_kcn"></span>
          </td>
          <td>
            <input type="text" class="form-control" v-model="ten" placeholder="Tên" /><span style="color: red"
              v-text="error_add_loai_kcn"></span>
          </td>
          <td align="center">
            <a class="btn btn-flat" @click="addLoaiCoSo()"><i class="fa fa-plus"></i> Thêm</a>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>


<script>
import * as env from "../../env.js";
import MessageService from "../../services/MessageService";

export default {
  data: function () {
    return {
      data: [],
      ten: null,
      ma: null,
      error_add_loai_kcn: null,
      thoi_gian_dong_bo: null,
      load_thoi_gian: true,
      thoi_gian: '',
      trangThaiDongBo: false
    };
  },
  props: ["usersystem"],
  created() {
    this.getData();
    this.getThoiGianDongBo();
    this.getTrangThaiDongBo();
  },
  methods: {
    getData() {
      axios.get(env.endpointhttp + "loaikhucongnghieps").then((response) => {
        this.data = response.data.result;
      });
    },
    getThoiGianDongBo() {
      axios
        .get(env.endpointhttp + "inthoigian/loai_khu_cong_nghiep",)
        .then((response) => {
          this.thoi_gian = response.data
          this.thoi_gian_dong_bo = new Date(response.data.updated_at).getHours() + ':' + new Date(response.data.updated_at).getMinutes() + ':' + new Date(response.data.updated_at).getSeconds() + ' ' + (new Date(response.data.updated_at)).toLocaleDateString('en-TT')
          console.log(response.data)
          this.load_thoi_gian = true;
        });
    },
    dongbodulieu() {
      this.load_thoi_gian = false;
      axios
        .get(env.endpointhttp + "moitruongquocgia/loaikhucongnghiep")
        .then((response) => {
          this.getData();
          this.getThoiGianDongBo();
          MessageService.showSuccessMessage("Đồng bộ dữ liệu thành công");
        });   
    },
    updateLoaiCoSo: function (id, value) {
      axios
        .put(env.endpointhttp + "loaikhucongnghieps/" + id, { ten: value })
        .then((response) => {
          // MessageService.showSuccessMessage('Cập nhật thành công');
        });
    },
    deleteLoaiCoSo: function (id, index) {
      this.data.splice(index, 1);
      axios
        .delete(env.endpointhttp + "loaikhucongnghieps/" + id)
        .then((response) => {
          // MessageService.showSuccessMessage('Xoá thành công');
        });
    },
    addLoaiCoSo: function () {
      this.error_add_loai_kcn = "";
      if (
        this.ten == "" ||
        this.ten == null ||
        this.ma == "" ||
        this.ma == null
      ) {
        this.error_add_loai_co_so = "Chưa nhập đủ dữ liệu.";
      } else {
        axios
          .post(env.endpointhttp + "loaikhucongnghieps", {
            ten: this.ten,
            ma: this.ma,
          })
          .then((response) => {
            this.data.push(response.data.result);
            this.ten = "";
            this.ma = "";
            // MessageService.showSuccessMessage('Thêm mới thành công');
          });
      }
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

