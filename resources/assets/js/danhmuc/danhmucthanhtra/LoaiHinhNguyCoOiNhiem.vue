<template>
  <div class="timeline-body">
    <div class="col-md-12">
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
      <table class="table table-hover fixed_headers">
        <thead class="row-table-header">
          <tr class="row-table-header">
            <th style="width: 70px">STT</th>
            <th style="width: 1100px">
              Tên loại hình sản xuất có nguy cơ tác động xấu đến môi trường
            </th>
            <th style="width: 70px">Sửa</th>
            <!-- <th style="width: 70px">Xóa</th> -->
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in loaiHinhs" :key="item.nhoms">
            <td style="width: 70px">{{ index + 1 }}</td>
            <td style="width: 1106px">
              <div>
                <div v-if="dong != index">{{ item.ten }}</div>
                <div v-else>
                  <textarea v-model="nhap" style="width: 95%"> </textarea>
                </div>
              </div>
            </td>
            <td style="width: 70px">
              <div v-if="dong != index">
                <i style="cursor: pointer;" class="fa fa-edit" @click="sua(index)"></i>
              </div>
              <div v-else>
                <i style="cursor: pointer;" class="fa fa-check" @click="done(item.id)"></i>
              </div>
            </td>
            <!-- <td style="width: 64px">
              <div v-if="dong != index">
                <i style="cursor: pointer;" class="fa fa-trash-o" @click="showConfirmDelete(item)"></i>
              </div>
              <div v-else>
                <i style="cursor: pointer;" class="fa fa-close" @click="huy()"></i>
              </div>
            </td> -->
          </tr>
        </tbody>
      </table>
      <div v-if="
        usersystem.role_code == 'admin' ||
        usersystem.role_code == 'adminvaquanlydanhmuc'
      ">
        <hr />
        <div style="display: flex; align-items: flex-end">
          <div style="flex: 1" class="ml-4">
            <div>Tên loại hình sản xuất</div>
            <input class="form-control" v-model="ten_loai_hinh"
              placeholder="Tên loại hình sản xuất có nguy cơ tác động xấu đến môi trường" v-validate="'required'"
              name="tennghe" />
            <span v-show="errors.has('tennghe')" class="help is-danger" style="color: red">Tên loại hình không thể bỏ
              trống</span>
          </div>
          <div style="width: 120px" class="ml-4">
            <div class="btn btn-flat btn-block btn-default d-flex align-center" @click="addLoaiCoSo()"
              style="height: 40px">
              <i class="fa fa-plus"></i> Thêm mới
            </div>
          </div>
        </div>
      </div>
    </div>
    <modal-component width="450px" v-model="showModel" center @submit="deleteLoaiHinhOiNhiem()"
      :title="'Xóa loại hình có nguy cơ ôi nhiễm'">
      <div>Bạn có chắc chắn muốn xóa <b>{{ xoaLoaihinhoinhiem.ten }}</b> ra khỏi các loại hình sản xuất có nguy cơ tác
        động xấu đến môi trường không ?</div>
    </modal-component>
  </div>
</template>



<script>
import * as env from "../../env.js";
import MessageService from "../../services/MessageService";
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import { ValidationProvider } from "vee-validate";

export default {
  components: { Treeselect, ValidationProvider },
  data: function () {
    return {
      nhoms: [],
      nhom: null,
      data: [],
      ten_co_so_moi: null,
      error_add_loai_co_so: null,
      nhomcs: [],
      loaiHinhs: [],
      ten_loai_hinh: null,
      nhap: '',
      dong: null,
      showModel: false,
      id: null,
      xoaLoaihinhoinhiem: {},
      //danh_muc_dong_bo: 'Loại hình nguy cơ ôi nhiễm',
      thoi_gian_dong_bo: null,
      load_thoi_gian: true,
      thoi_gian:'',
      trangThaiDongBo: false
    };
  },
  props: ["usersystem"],
  watch: {
    "form.parent_id": function (val) {
      console.log(val);
    },
  },

  created() {
    this.getLoaiOiNhiem();
    this.getThoiGianDongBo();
    this.getTrangThaiDongBo();
  },
  methods: {
    getLoaiOiNhiem() {
      axios.get(env.endpointhttp + "loaihinhoinhiem").then((response) => {
        this.loaiHinhs = response.data;
        console.log(this.loaiHinhs)
      });
    },
    getThoiGianDongBo() {
      axios
        .get(env.endpointhttp + "inthoigian/loai_hinh_nguy_co_oi_nhiem")
        .then((response) => {
          this.thoi_gian=response.data
          this.thoi_gian_dong_bo = new Date(response.data.updated_at).getHours() + ':' + new Date(response.data.updated_at).getMinutes() + ':' + new Date(response.data.updated_at).getSeconds() + ' ' + (new Date(response.data.updated_at)).toLocaleDateString('en-TT')
          console.log(response.data)
          //this.thoi_gian_dong_bo=response.data[0].updated_at
          this.load_thoi_gian = true;
        });
    },
    updateLoaiCoSo: function (id, value, parent_id) {
      axios
        .put(env.endpointhttp + "loaihinhcosos/" + id, {
          ten: value,
          parent_id: parent_id,
        })
        .then((response) => {
          MessageService.showSuccessMessage("Cập nhật thành công");
        });
    },
    addLoaiCoSo: function () {
      this.$validator.validateAll().then((validate) => {
        if (validate) {
          axios
            .post(env.endpointhttp + "loaihinhoinhiem", {
              ten: this.ten_loai_hinh,
            })
            .then((response) => {
              this.getLoaiOiNhiem();
              this.ten_loai_hinh = null;
              MessageService.showSuccessMessage("Thêm mới thành công");
            });
        };
      });
    },
    done(id) {
      if (this.nhap) {
        axios
          .put(env.endpointhttp + "loaihinhoinhiem/" + id, {
            ten: this.nhap,
          })
          .then((response) => {
            this.dong = null;
            this.getLoaiOiNhiem();
            MessageService.showSuccessMessage("Sửa thành công");
          });
      }
    },
    sua(index) {
      this.nhap = this.loaiHinhs[index].ten;
      this.dong = index;
    },
    deleteLoaiHinhOiNhiem: function () {
      axios
        .put(env.endpointhttp + "loaihinhoinhiem/" + this.xoaLoaihinhoinhiem.id, {
          inactive: true
        })
        .then((response) => {
          this.getLoaiOiNhiem();
          MessageService.showSuccessMessage("Xóa thành công");
        })
      this.showModel = false;
    },
    showConfirmDelete(Loaihinhoinhiem) {
      this.showModel = true;
      this.xoaLoaihinhoinhiem = Loaihinhoinhiem;
    },
    huy() {
      this.dong = null;
    },
    dongbodulieu() {
      this.load_thoi_gian = false;
      axios
        .get(env.endpointhttp + 'moitruongquocgia/loaihinhoinnhiem')
        .then((response) => {
          this.getLoaiOiNhiem();
          this.getThoiGianDongBo();
          MessageService.showSuccessMessage('Đồng bộ thành công')
        })
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

