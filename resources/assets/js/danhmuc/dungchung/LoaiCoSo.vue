<template>
  <div class="timeline-body">
    <div class="col-md-12">
      <table class="table table-hover fixed_headers">
        <thead class="row-table-header">
          <tr class="row-table-header">
            <th style="width: 70px">STT</th>
            <th style="width: 1100px">
              Tên loại hình cơ sở
            </th>
            <th style="width: 70px">Sửa</th>
            <th style="width: 70px"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in loaiCoSo" :key="index">
            <td style="width: 70px">{{ index + 1 }}</td>
            <td style="width: 1106px">
              <div>
                <div v-if="dong != index">{{ item.ten_co_so }}</div>
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
                <!-- <i style="cursor: pointer;" class="fa fa-close" @click="huy()"></i> -->
              </div>
            </td>
            <td style="width: 64px">
              <div v-if="dong != index">
                <i style="cursor: pointer;" class="fa fa-trash-o" @click="showConfirmDelete(item)"></i>
              </div>
              <div v-else>
                <i style="cursor: pointer;" class="fa fa-close" @click="huy()"></i>
              </div>
            </td>
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
            <div>Tên loại cơ sở</div>
            <input class="form-control" v-model="ten_co_so"
              placeholder="Tên loại cơ sở" v-validate="'required'"
              name="tennghe" />
            <span v-if="!submit" v-show="errors.has('tennghe')" class="help is-danger" style="color: red">Tên loại cơ sở không được bỏ trống</span>
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
    <modal-component width="450px" v-model="showModel" center @submit="deleteLoaiCoSo()"
      :title="'Xóa loại cơ sở'">
      <div>Bạn có chắc chắn muốn xóa <b>{{ xoaLoaiCoSo.ten_co_so }}</b> ra khỏi các loại hình cơ sở không?</div>
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
        loaiCoSo: null,
        nhap: '',
        dong: null,
        ten_co_so:null,
        xoaLoaiCoSo:{},
        showModel:false,
        submit:true
    };
  },
  props: ["usersystem"],
  watch: {
    "form.parent_id": function (val) {
      console.log(val);
    },
  },

  created() {
    this.getLoaiCoSo();
  },
  methods: {
    getLoaiCoSo() {
      axios.get(env.endpointhttp + "getloaicoso").then((response) => {
        this.loaiCoSo = response.data;
      });
    },
    addLoaiCoSo: function () {
      this.$validator.validateAll().then((validate) => {
        if (validate) {
          axios
            .post(env.endpointhttp + "addloaicoso", {
              ten_co_so: this.ten_co_so,
            })
            .then((response) => {
              this.getLoaiCoSo();
              this.ten_co_so = null;
              this.submit=true;
              MessageService.showSuccessMessage("Thêm mới thành công");
            });
        }
        else{
          this.submit=false;
        };
      });
    },
    done(id) {
      if (this.nhap) {
        axios
          .put(env.endpointhttp + "updateloaicoso/" + id, {
            ten_co_so: this.nhap,
          })
          .then((response) => {
            this.dong = null;
            this.getLoaiCoSo();
            MessageService.showSuccessMessage("Cập nhật thành công");
          });
      }
    },
    sua(index) {
      this.nhap = this.loaiCoSo[index].ten_co_so;
      this.dong = index;
    },
    deleteLoaiCoSo: function () {
      axios
        .delete(env.endpointhttp + "deleteloaicoso/" + this.xoaLoaiCoSo.id)
        .then((response) => {
          this.getLoaiCoSo();
          MessageService.showSuccessMessage("Xóa thành công");
        })
      this.showModel = false;
    },
    showConfirmDelete(LoaiCoSo) {
      this.showModel = true;
      this.xoaLoaiCoSo = LoaiCoSo;
    },
    huy() {
      this.dong = null;
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

