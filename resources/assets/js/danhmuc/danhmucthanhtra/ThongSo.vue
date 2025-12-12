<template>
  <div class="nav-tabs-custom">
    <div style="display:flex; align-items:center; justify-content:space-between">
      <div v-if="load_thoi_gian">
        <div v-if="this.thoi_gian == ''">Chưa đồng bộ với CSDL quốc gia</div>
          <div v-else>Đồng bộ lần cuối: {{ thoi_gian_dong_bo }}</div>
      </div>
      <div v-else>Đang đồng bộ với CSDL quốc gia</div>
      <div style="margin-right:20px; margin-bottom:10px;">
        <div style="width: 140px;" class="ml-4">
          <div class="btn btn-flat btn-block btn-default d-flex align-center" @click="dongbodulieu()"
            style="height: 40px">
            <i class="fa fa-refresh" style="padding-right:5px"></i>
            Đồng bộ dữ liệu
          </div>
        </div>
      </div>
    </div>
    <ul class="nav nav-tabs">
      <li v-for="(tab, i) in tabs" :key="i" :class="{ active: currentTab == tab.type }" @click="loadDataTab(tab.type)">
        <a data-toggle="tab" aria-expanded="true">{{ tab.text }}</a>
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active">
        <div class="row">
          <div class="col-sm-12">
            <table class="table table-hover">
              <tbody>
                <tr class="row-table-header">
                  <th>STT</th>
                  <th>Tên</th>
                  <th>Ký hiệu hóa học</th>
                  <th style="width: 5%;" v-if="
                    usersystem.role_code == 'admin' ||
                    usersystem.role_code == 'adminvaquanlydanhmuc'
                  "></th>
                </tr>
                <tr v-for="(item, index) in data" :key="item.data">
                  <td>{{ index + 1 }}</td>
                  <td>
                    <input type="text" class="form-control" v-model="item.ten" @change="update(item, index)" />
                  </td>
                  <td>
                    <input type="text" class="form-control" v-model="item.ky_hieu_hoa_hoc"
                      @change="update(item, index)" />
                  </td>
                  <!-- <td align="center" v-if="
                    usersystem.role_code == 'admin' ||
                    usersystem.role_code == 'adminvaquanlydanhmuc'
                  ">
                    <a @click="destroy(item.id, index)"><i class="fa fa-trash-o"></i></a>
                  </td> -->
                </tr>
                <tr v-if="
                  usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
                ">
                  <td colspan="2">
                    <input type="text" class="form-control" v-model="form.ten" placeholder="Tên" /><span
                      style="color: red;" v-text="error_thongso"></span>
                  </td>
                  <td>
                    <input type="text" class="form-control" v-model="form.ky_hieu_hoa_hoc"
                      placeholder="Ký hiệu hóa học" /><span style="color: red;" v-text="error_thongso"></span>
                  </td>
                  <td align="center" colspan="2">
                    <a class="btn btn-flat" @click="add()"><i class="fa fa-plus"></i> Thêm</a>
                  </td>
                </tr>
              </tbody>
            </table>
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
  props: ["usersystem"],
  data: function () {
    return {
      data: [],
      form: {},
      error_thongso: "",
      currentTab: "",
      tabs: [
        { text: "Nước thải", type: "nuoc_thai" },
        { text: "Khí thải,bụi", type: "khi_thai" },
        { text: "Chất thải rắn", type: "chat_thai_ran" },
        { text: "Bùn thải", type: "bun_thai" },
        { text: "Không khi xung quanh", type: "khong_khi_xung_quanh" },
        { text: "Nước mặt", type: "nuoc_mat" },
        { text: "Trầm tích", type: "tram_tich" },
        { text: "Đất", type: "dat" },
        { text: "Nước dưới đất", type: "nuoc_duoi_dat" },
        { text: "Chưa phân loại", type: "chua_phan_loai" },

      ],
      //danh_muc_dong_bo: 'Thông số',
      thoi_gian_dong_bo: null,
      load_thoi_gian: true,
      thoi_gian:'',
    };
  },
  mounted() {
    this.loadDataTab("nuoc_thai");
    this.getThoiGianDongBo();
  },

  methods: {
    checkData(data) {
      if (!data.ten) return "Lỗi chưa nhập trường bắt buộc";
    },
    getThoiGianDongBo() {
      axios.get(env.endpointhttp + "inthoigian/thong_so").then((response) => {
        this.thoi_gian=response.data
        this.thoi_gian_dong_bo = new Date(response.data.updated_at).getHours() + ':' + new Date(response.data.updated_at).getMinutes() + ':' + new Date(response.data.updated_at).getSeconds() + ' ' + (new Date(response.data.updated_at)).toLocaleDateString('en-TT')
        //console.log(response.data[2].updated_at)
        this.load_thoi_gian = true;
      });
    },
    add() {
      if (
        this.usersystem.role_code == "admin" ||
        this.usersystem.role_code == "adminvaquanlydanhmuc"
      ) {
        this.error_thongso = "";
        this.error_thongso = this.checkData(this.form);
        if (this.error_thongso) return;
        this.form.type = this.currentTab;
        axios
          .post(env.endpointhttp + "danhmuc/thongsovuotchuan", this.form)
          .then(response => {
            this.data.push(response.data.result);
            this.form = {};
          });
      }
    },
    update(item, index) {
      if (
        this.usersystem.role_code == "admin" ||
        this.usersystem.role_code == "adminvaquanlydanhmuc"
      ) {
        this.error_thongso = "";
        this.error_thongso = this.checkData(item);
        if (this.error_thongso) return;
        axios
          .put(env.endpointhttp + "danhmuc/thongsovuotchuan/" + item.id, item)
          .then(response => { });
      }
    },
    destroy(id, index) {
      if (
        this.usersystem.role_code == "admin" ||
        this.usersystem.role_code == "adminvaquanlydanhmuc"
      ) {
        this.data.splice(index, 1);
        axios
          .delete(env.endpointhttp + "danhmuc/thongsovuotchuan/" + id)
          .then(response => { });
      }
    },
    loadDataTab(type) {
      this.currentTab = type;
      axios
        .get(env.endpointhttp + "danhmuc/thongsovuotchuan", {
          params: { type }
        })
        .then(response => {
          this.data = response.data.result;
          console.log(this.data)
        });
    },
    dongbodulieu() {
      this.load_thoi_gian = false;
      axios
        .get(env.endpointhttp + 'moitruongquocgia/dongbothongso')
        .then((response) => {
          this.loadDataTab("nuoc_thai");
          this.getThoiGianDongBo();
          MessageService.showSuccessMessage('Đồng bộ thành công')
        })
    },
  }
};
</script>
