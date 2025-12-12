<template>
  <div class="timeline-body">
    <div style="display:flex; align-items:center; justify-content:space-between">
      <div v-if="load_thoi_gian">
        <div v-if="this.thoi_gian == ''">Chưa đồng bộ với CSDL quốc gia</div>
          <div v-else>Đồng bộ lần cuối: {{ thoi_gian_dong_bo }}</div>
      </div>
      <div v-else>Đang đồng bộ với CSDL quốc gia</div>
      <div style="margin-right:5px; margin-bottom:10px;">
        <div style="width: 140px;" class="ml-4">
          <div class="btn btn-flat btn-block btn-default d-flex align-center" @click="dongbodulieu()"
            style="height: 40px">
            <i class="fa fa-refresh" style="padding-right:5px"></i>
            Đồng bộ dữ liệu
          </div>
        </div>
      </div>
    </div>
    <table class="table table-hover">
      <thead>
        <tr class="row-table-header">
          <th style="width: 5%">STT</th>
          <th style="width: 15%; padding-left: 50px; padding-right: 100px">
            Tên
          </th>
          <th style="width: 75%; padding-left: 50px; padding-right: 828px">
            Mô Tả
          </th>
          <!-- <th style="width: 5%" v-if="
            usersystem.role_code == 'admin' ||
            usersystem.role_code == 'adminvaquanlydanhmuc'
          "></th> -->
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in data" :key="item.data">
          <td style="width: 5%">{{ index + 1 }}</td>
          <td style="width: 15%">
            <textarea class="form-control" v-model="item.ten" @change="updateLuuVucSong(item.ma, item.ten, item.mo_ta)"
              v-if="
                usersystem.role_code == 'admin' ||
                usersystem.role_code == 'adminvaquanlydanhmuc'
              "></textarea>
            <textarea class="form-control" v-model="item.ten" v-else></textarea>
          </td>
          <td style="width: 75%">
            <textarea class="form-control" v-model="item.mo_ta"
              @change="updateLuuVucSong(item.ma, item.ten, item.mo_ta)" v-if="
                usersystem.role_code == 'admin' ||
                usersystem.role_code == 'adminvaquanlydanhmuc'
              "></textarea>
            <textarea class="form-control" v-model="item.mo_ta"
              @change="updateLuuVucSong(item.ma, item.ten, item.mo_ta)" v-else></textarea>
          </td>
          <!-- <td align="center" v-if="
            usersystem.role_code == 'admin' ||
            usersystem.role_code == 'adminvaquanlydanhmuc'
          " style="width: 5%">
            <a @click="deleteLuuVucSong(item.ma, index)">
              <i class="fa fa-trash-o btn"></i></a>
          </td> -->
        </tr>
      </tbody>
    </table>
    <div>
      <div v-if="
        usersystem.role_code == 'admin' ||
        usersystem.role_code == 'adminvaquanlydanhmuc'
      ">
        <hr />
        <div style="
            display: flex;
            align-items: center;
            justify-content: flex-start;
          ">
          <div class="ml-4">
            <div>
              <textarea style="width: 180px; height: 50px" class="form-control" v-model="ten"
                placeholder="Tên"></textarea>
              <span style="color: red" v-text="error_add_luu_vuc_song"></span>
            </div>
          </div>
          <div class="ml-4">
            <textarea style="width: 900px; height: 50px" class="form-control" v-model="mo_ta"
              placeholder="Mô tả"></textarea>
            <span style="color: red" v-text="error_add_luu_vuc_song"> </span>
          </div>
          <div style="width: 120px" class="ml-4">
            <div class="btn btn-flat btn-block btn-default d-flex align-center" @click="addLuuVucSong()"
              style="height: 50px">
              <i class="fa fa-plus"></i> Thêm mới
            </div>
          </div>
        </div>
      </div>
    </div>
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
      mo_ta: null,
      error_add_luu_vuc_song: null,
      //danh_muc_dong_bo: 'Lưu vực sông',
      thoi_gian_dong_bo: null,
      load_thoi_gian: true,
      thoi_gian:'',
    };
  },
  props: ["usersystem"],
  mounted() {
    this.getdata();
    this.getThoiGianDongBo();
  },
  methods: {
    getdata() {
      axios.get(env.endpointhttp + "luuvucsongs").then((response) => {
        this.data = response.data.result;
      });
    },
    getThoiGianDongBo() {
      axios.get(env.endpointhttp + "inthoigian/luu_vuc_song").then((response) => {
        this.thoi_gian=response.data
        this.thoi_gian_dong_bo = new Date(response.data.updated_at).getHours() + ':' + new Date(response.data.updated_at).getMinutes() + ':' + new Date(response.data.updated_at).getSeconds() + ' ' + (new Date(response.data.updated_at)).toLocaleDateString('en-TT')
        //console.log(response.data.updated_at)
        this.load_thoi_gian = true;
      });
    },
    updateLuuVucSong: function (id, value_1, value_2) {
      axios
        .put(env.endpointhttp + "luuvucsongs/" + id, {
          ten: value_1,
          mo_ta: value_2,
        })
        .then((response) => { });
    },
    deleteLuuVucSong: function (id, index) {
      this.data.splice(index, 1);
      axios
        .delete(env.endpointhttp + "luuvucsongs/" + id)
        .then((response) => { });
    },
    addLuuVucSong: function () {
      this.error_add_luu_vuc_song = "";
      if (
        this.ten == "" ||
        this.ten == null ||
        this.ma == "" ||
        this.ma == null
      ) {
        this.error_add_luu_vuc_song = "Chưa nhập đủ dữ liệu.";
      } else {
        axios
          .post(env.endpointhttp + "luuvucsongs", {
            ten: this.ten,
            ma: this.ma,
            mo_ta: this.mo_ta,
          })
          .then((response) => {
            this.data.push(response.data.result);
            this.ten = "";
            this.ma = "";
            this.mo_ta = "";
          });
      }
    },
    dongbodulieu() {
      this.load_thoi_gian = false;
      axios
        .get(env.endpointhttp + "moitruongquocgia/luuvucsong")
        .then((response) => {
          this.getdata();
          this.getThoiGianDongBo();
          MessageService.showSuccessMessage('Đồng bộ thành công')
        })
    },
  }
};
</script>
<style scoped>
thead,
tfoot {
  display: table;
}

tbody {
  display: block;
  max-height: 373px;
  overflow-y: scroll;
}
</style>
