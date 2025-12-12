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
            <th style="width: 150px">Mã cấp</th>
            <th style="width: 960px">Tên ngành nghề</th>
            <th>
              <div v-if="dong == null" style="width: 30px">Sửa</div>
              <div v-else style="width: 30px">Xong</div>
            </th>
            <!-- <th>
              <div v-if="dong == null" style="width: 30px">Xóa</div>
              <div v-else style="width: 30px">Hủy</div>
            </th> -->
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in nganhNghes" :key="item.nhoms">
            <td style="width: 70px">{{ index + 1 }}</td>
            <td style="width: 150px">
              <div v-if="dong != index">{{ item.ma }}</div>
              <div v-else>
                <input v-model="ma_input" style="width: 130px" />
              </div>
            </td>
            <td style="width: 970px">
              <div v-if="dong != index">{{ item.ten }}</div>
              <div v-else>
                <textarea v-model="ten_input" style="width: 95%"></textarea>
              </div>
            </td>
            <td class="pointer">
              <div v-if="dong != index">
                <i class="fa fa-edit" @click="edit(index)" title="Cập nhật"></i>
              </div>
              <div v-else>
                <i class="fa fa-check" @click="updateLoaiCoSo(item.id)" title="Xong"></i>
              </div>
            </td>
            <!-- <td class="pointer">
              <div v-if="dong != index" style="width: 30px; padding-left: 15px">
                <i class="fa fa-trash-o" title="Xóa" @click="showConfirmDelete(item)"></i>
              </div>
              <div v-else style="width: 30px; padding-left: 15px">
                <i class="fa fa-close" title="Hủy bỏ" @click="huy()"></i>
              </div>
            </td> -->
          </tr>
        </tbody>
      </table>
      <!-- <div v-if="
              usersystem.role_code == 'admin' ||
              usersystem.role_code == 'adminvaquanlydanhmuc'
            ">
              <hr />
              <div>
                Danh mục bậc trên
                <span style="color: #1f618d; font-weight: bold">({{ infoParent ? infoParent : "*" }})</span>
              </div>
              <treeselect :multiple="false" :options="loaiHinhs" placeholder="Chọn một danh mục bậc trên"
                v-model="parentSelect" value-format="object" v-validate="'required'" name="danhmuccha"
                :show-count="true">
                <label slot="option-label" slot-scope="{
                    node,
                    shouldShowCount,
                    count,
                    labelClassName,
                    countClassName,
                  }" :class="labelClassName">
                  {{ node.raw ? node.raw.ma : "" }}. {{ node.label }}
                  <span v-if="shouldShowCount" :class="countClassName">({{ count }})</span>
                </label>
              </treeselect>
              <span v-show="errors.has('danhmuccha')" class="help is-danger" style="color: red">Danh mục cha không thể
                bỏ trống</span>
              <br />
              <div style="display: flex">
                <div style="width: 300px">
                  <div>Mã cấp</div>
                  <input v-model="form.ma" class="form-control" placeholder="Nhập mã cấp bậc" />
                </div>
                <div style="flex: 1" class="ml-4">
                  <div>Tên ngành nghề</div>
                  <input class="form-control" v-model="form.ten"
                    placeholder="Nhập tên loại ngành nghề kinh tế ở Việt Nam" v-validate="'required'" name="tennghe" />
                  <span v-show="errors.has('tennghe')" class="help is-danger" style="color: red">Tên ngành nghề không
                    thể
                    bỏ trống</span>
                </div>
              </div>
              <br />
              <div class="btn btn-flat btn-block btn-default" @click="addLoaiCoSo()">
                <i class="fa fa-plus"></i> Thêm mới loại ngành nghề
              </div>
            </div> -->
    </div>
    <modal-component width="450px" v-model="showModel" center @submit="deleteLoaiHinhCoSo()" :title="'Xóa'">
      <div>Bạn có chắc chắn muốn xóa không ?</div>
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
      nganhNghes: [],
      loaiHinhs: [],
      form: {
        parent_id: null,
        ma: null,
        ten: null,
      },
      ten_input: null,
      ma_input: null,
      dong: null,
      parentSelect: null,
      showModel: null,
      xoaLoaihinhcoso: {},
      thoi_gian_dong_bo: null,
      load_thoi_gian: true,
      thoi_gian: '',
      trangThaiDongBo: false
    };
  },
  props: ["usersystem"],
  computed: {
    infoParent() {
      if (this.parentSelect) {
        return "Mã danh mục " + this.parentSelect.ma_danh_muc;
      }
      return "";
    },
  },
  created() {
    axios.get(env.endpointhttp + "loaihinhcosos").then((response) => {
      this.data = response.data.result;
    });
    axios.get(env.endpointhttp + "nhomloaihinhcosos").then((response) => {
      this.nhoms = response.data.result;
    });
    this.getCoSoTreeSelect();
    this.getLoaiNganhNghe();
    this.getThoiGianDongBo();
    this.getTrangThaiDongBo();
  },
  methods: {
    getCoSoTreeSelect() {
      axios.get(env.endpointhttp + "treeloaihinh").then((response) => {
        this.loaiHinhs = response.data;
      });
    },
    getLoaiNganhNghe() {
      axios.get(env.endpointhttp + "nganhnghes").then((response) => {
        this.nganhNghes = response.data;
      });
    },
    getThoiGianDongBo() {
      axios
        .get(env.endpointhttp + "inthoigian/loai_hinh_co_so",)
        .then((response) => {
          this.thoi_gian = response.data
          this.thoi_gian_dong_bo = new Date(response.data.updated_at).getHours() + ':' + new Date(response.data.updated_at).getMinutes() + ':' + new Date(response.data.updated_at).getSeconds() + ' ' + (new Date(response.data.updated_at)).toLocaleDateString('en-TT')
          console.log(response.data)
          this.load_thoi_gian = true;
        });
    },
    updateLoaiCoSo: function (id) {
      axios
        .put(env.endpointhttp + "loaihinhcosos/" + id, {
          ma: this.ma_input,
          ten: this.ten_input,
        })
        .then((response) => {
          this.dong = null;
          this.getLoaiNganhNghe();
          MessageService.showSuccessMessage("Cập nhật thành công");
        });
    },
    resetForm() {
      this.form = {
        parent_id: null,
        ma: null,
        ten: null,
      };
      this.parentSelect = null;
    },
    addLoaiCoSo: function () {
      this.$validator.validateAll().then((validate) => {
        this.form.parent_id = this.parentSelect ? this.parentSelect.id : null;
        if (validate && this.form.parent_id) {
          axios
            .post(env.endpointhttp + "loaihinhcosos", this.form)
            .then((response) => {
              this.getLoaiNganhNghe();
              this.getCoSoTreeSelect();
              this.resetForm();
              MessageService.showSuccessMessage("Thêm mới thành công");
            });
        }
      });
    },
    loadChiTietNhomCoSo: function (id) {
      axios
        .get(env.endpointhttp + "chitietloaihinhcoso/" + id)
        .then((response) => {
          this.nhomcs = response.data.result;
        });
    },
    edit(index) {
      this.ten_input = this.nganhNghes[index].ten;
      this.ma_input = this.nganhNghes[index].ma;
      this.dong = index;
    },
    deleteLoaiHinhCoSo: function () {
      axios
        .delete(env.endpointhttp + "loaihinhcosos/" + this.xoaLoaihinhcoso.id)
        .then((response) => {
          this.getLoaiNganhNghe();
          this.showModel = false;
          MessageService.showSuccessMessage("Xóa thành công");
        });
      this.showModel = false;
    },
    huy() {
      this.dong = null;
    },
    showConfirmDelete(Loaihinhcoso) {
      this.showModel = true;
      this.xoaLoaihinhcoso = Loaihinhcoso;
    },
    dongbodulieu() {
      this.load_thoi_gian = false;
      axios
        .get(env.endpointhttp + 'moitruongquocgia/loainganhnghekinhte')
        .then((response) => {
          this.getLoaiNganhNghe();
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

.pointer {
  cursor: pointer;
}
</style>
