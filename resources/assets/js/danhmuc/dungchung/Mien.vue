<template>
  <div class="timeline-body">
    <div style="display: flex; align-items:center; justify-content:space-between">
      <div v-if="load_thoi_gian">
        <div v-if="this.thoi_gian == ''">Chưa đồng bộ với CSDL quốc gia</div>
        <div v-else>Đồng bộ lần cuối: {{ thoi_gian_dong_bo }}</div>
      </div>
      <div v-else>Đang đồng bộ với CSDL quốc gia</div>
      <div style="margin-bottom:10px;">
        <div style="width: 140px;" class="ml-4">
          <div class="btn btn-flat btn-block btn-default d-flex align-center" @click="dongbodulieu()"
            style="height: 40px">
            <i class="fa fa-refresh" style="padding-right:5px"></i>
            Đồng bộ dữ liệu
          </div>
        </div>
      </div>
    </div>
    <div class="nav-tabs-custom margin-top">
      <ul class="nav nav-tabs">
        <li class="active">
          <a href="#tab_dongbo" data-toggle="tab" aria-expanded="true" @click="">Dữ liệu đã đồng bộ từ MTQG
          </a>
        </li>
        <li v-if="dataChuaDongBo && dataChuaDongBo.length > 0">
          <a href="#tab_khac" data-toggle="tab" aria-expanded="false" @click="">Dữ liệu khác</a>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_dongbo">
          <table class="table table-hover">
            <tbody>
              <tr class="row-table-header">
                <th style="width:10%">Mã mục</th>
                <th style="width:30%">Tên</th>
                <th>Thông tin</th>
              </tr>
              <tr v-for="(item, index) in dataDongBo" :key="item.id">
                <td>{{ item.ma_dinh_danh}}</td>
                <td>
                  {{item.ten}}
                </td>
                <td>
                  {{item.mo_ta}}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="tab-pane" id="tab_khac" v-if="dataChuaDongBo && dataChuaDongBo.length > 0">
          <table class="table table-hover">
            <tbody>
              <tr class="row-table-header">
                <th style="width:10%">Mã mục</th>
                <th style="width:30%">Tên</th>
                <th>Thông tin</th>
                <th>Xóa</th>
              </tr>
              <tr v-for="(item, index) in dataChuaDongBo" :key="item.id">
                <td>{{ item.ma_dinh_danh}}</td>
                <td>
                  {{item.ten}}
                </td>
                <td>
                  {{item.mo_ta}}
                </td>
                <td style="width: 5%">
                  <a @click="showConfirmDelete(item)">
                    <i class="fa fa-trash-o btn"></i></a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
    <modal-component width="450px" v-model="showModel" center @submit="deleteMien()" :title="'Xác nhận xóa'">
      <div>Bạn có chắc chắn muốn xóa {{mienDelete.ten}} không?</div>
    </modal-component>
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
      error_add_mien: null,
      thoi_gian_dong_bo: null,
      load_thoi_gian: true,
      thoi_gian: '',
      dataDongBo: [],
      dataChuaDongBo: [],
      showModel: false,
      mienDelete: {}
    };
  },
  props: ["usersystem"],
  mounted() {
    this.getData()
  },
  methods: {
    getData() {
      axios.get(env.endpointhttp + "miens").then(response => {
        this.data = response.data.result;
        this.dataDongBo = this.data.filter(el => el.trang_thai_dong_bo == 'da_dong_bo');
        this.dataChuaDongBo = this.data.filter(el => el.trang_thai_dong_bo !== 'da_dong_bo');
      });
    },
    updateMien: function (id, value_1, value_2) {
      axios
        .put(env.endpointhttp + "miens/" + id, { ten: value_1, mo_ta: value_2 })
        .then(response => {
          MessageService.showSuccessMessage("Cập nhật thành công");
        });
    },
    deleteMien: function () {
      axios.delete(env.endpointhttp + "miens/" + this.mienDelete.id).then(response => {
        this.getData();
        MessageService.showSuccessMessage('Đồng bộ thành công')
        this.showModel = false
      }).catch(error => {
        MessageService.showWarningMessage(error.response && error.response.data ? error.response.data.message : 'Không thể xóa')
        this.showModel = false
      });
    },
    getThoiGianDongBo() {
      axios.get(env.endpointhttp + "inthoigian/vung_mien").then((response) => {
        this.thoi_gian = response.data
        this.thoi_gian_dong_bo = new Date(response.data.updated_at).getHours() + ':' + new Date(response.data.updated_at).getMinutes() + ':' + new Date(response.data.updated_at).getSeconds() + ' ' + (new Date(response.data.updated_at)).toLocaleDateString('en-TT')
        this.load_thoi_gian = true;
      });
    },
    showConfirmDelete(mien) {
      this.showModel = true;
      this.mienDelete = mien
    },

    dongbodulieu() {
      this.load_thoi_gian = false;
      axios
        .get(env.endpointhttp + "moitruongquocgia/vungmien")
        .then((response) => {
          this.getData();
          this.getThoiGianDongBo();
          MessageService.showSuccessMessage('Đồng bộ thành công')
        })
    },
  }
};
</script>
