<template>
  <div class="timeline-body">
    <table class="table table-hover">
      <tbody>
        <tr class="row-table-header">
          <th style="width:5%">STT</th>
          <th style="width:15%">Mã</th>
          <th style="width:15%">Tên</th>
          <th>Mô tả</th>
          <th style="width:5%" v-if="
            usersystem.role_code == 'admin' ||
            usersystem.role_code == 'adminvaquanlydanhmuc'
          "></th>
        </tr>
        <tr v-for="(item, index) in data" :key="item.data">
          <td>{{ index + 1 }}</td>
          <td>
            <textarea class="form-control" v-model="item.ma" disabled rows="3"></textarea>
          </td>
          <td>
            <textarea class="form-control" v-model="item.ten" v-if="
              usersystem.role_code == 'admin' ||
              usersystem.role_code == 'adminvaquanlydanhmuc'
            " @change="updateMien(item.ma, item.ten, item.mo_ta)" rows="3"></textarea>
            <textarea class="form-control" v-else v-model="item.ten" rows="3" disabled></textarea>
          </td>
          <td>
            <textarea class="form-control" v-model="item.mo_ta" v-if="
              usersystem.role_code == 'admin' ||
              usersystem.role_code == 'adminvaquanlydanhmuc'
            " @change="updateMien(item.ma, item.ten, item.mo_ta)" rows="3"></textarea>
            <textarea class="form-control" v-model="item.mo_ta" v-else rows="3" disabled></textarea>
          </td>
          <td align="center" v-if="
            usersystem.role_code == 'admin' ||
            usersystem.role_code == 'adminvaquanlydanhmuc'
          ">
            <a @click="deleteMien(item.id, index)">
              <i class="fa fa-trash-o btn"></i></a>
          </td>
        </tr>
        <tr v-if="
          usersystem.role_code == 'admin' ||
          usersystem.role_code == 'adminvaquanlydanhmuc'
        ">
          <td></td>
          <td>
            <textarea class="form-control" v-model="form.ma" placeholder="Mã" rows="3"></textarea>
          </td>
          <td>
            <textarea class="form-control" v-model="form.ten" placeholder="Tên" rows="3"></textarea>
          </td>
          <td>
            <textarea class="form-control" v-model="form.mo_ta" placeholder="Mô tả" rows="3"></textarea>
          </td>
          <td align="center">
            <a class="btn btn-flat" @click="add()"><i class="fa fa-plus"></i> Thêm</a>
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
      mo_ta: null,
      error_add_mien: null,
      form: {}
    };
  },
  props: ["usersystem"],
  mounted() {
    this.getData();
  },
  methods: {
    getData() {
      axios.get(env.endpointhttp + "miens").then(response => {
        this.data = response.data.result;
      });
    },
    add() {
      axios
        .post(env.endpointhttp + "miens", this.form)
        .then(() => {
          this.getData();
          this.form.ma = null
          this.form.ten = null
          this.form.mo_ta = null
        });
    },
    updateMien: function (id, value_1, value_2) {
      axios
        .put(env.endpointhttp + "miens/" + id, { ten: value_1, mo_ta: value_2 })
        .then(response => {
          MessageService.showSuccessMessage('Cập nhật thành công')
        });
    },
    deleteMien: function (id, index) {
      axios.delete(env.endpointhttp + "miens/" + id)
      .then(response => { this.data.splice(index, 1); })
      .catch((error) => {
          MessageService.showWarningMessage("Xoá thất bại");
        });
      // this.data.splice(index, 1);
    }
  }
};
</script>
