<template>
  <div class="timeline-body">
    <table class="table table-hover">
      <tbody>
        <tr class="row-table-header">
          <th style="width: 5%;">STT</th>
          <th style="width: 15%;">Mã</th>
          <th style="width: 25%;">Tên</th>
          <th>Mô tả</th>
          <th
            style="width: 5%;"
            v-if="
              usersystem.role_code == 'admin' ||
                usersystem.role_code == 'adminvaquanlydanhmuc'
            "
          ></th>
        </tr>
        <tr v-for="(item, index) in data" :key="item.data">
          <td>{{ index + 1 }}</td>
          <td>
            <textarea
              class="form-control"
              v-model="item.ma"
              disabled
              rows="3"
            ></textarea>
          </td>
          <td>
            <textarea
              class="form-control"
              v-model="item.ten"
              rows="3"
              @change="updateVungKinhTeTrongDiem(item.ma, item.ten, item.mo_ta)"
              v-if="
                usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
              "
            ></textarea>
            <textarea
              class="form-control"
              v-model="item.ten"
              v-else
              rows="3"
              disabled
            ></textarea>
          </td>
          <td>
            <textarea
              class="form-control"
              v-model="item.mo_ta"
              rows="3"
              @change="updateVungKinhTeTrongDiem(item.ma, item.ten, item.mo_ta)"
              v-if="
                usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
              "
            ></textarea>
            <textarea
              class="form-control"
              v-model="item.mo_ta"
              rows="3"
              v-else
              disabled
            ></textarea>
          </td>
          <td
            align="center"
            rows="3"
            v-if="
              usersystem.role_code == 'admin' ||
                usersystem.role_code == 'adminvaquanlydanhmuc'
            "
          >
            <a @click="deleteVungKinhTeTrongDiem(item.ma, index)">
              <i class="fa fa-trash-o btn"></i
            ></a>
          </td>
        </tr>
        <tr
          v-if="
            usersystem.role_code == 'admin' ||
              usersystem.role_code == 'adminvaquanlydanhmuc'
          "
        >
          <td colspan="2">
            <textarea
              class="form-control"
              v-model="ma"
              placeholder="Mã"
            ></textarea
            ><span
              style="color: red;"
              v-text="error_add_vung_kinh_te_trong_diem"
            ></span>
          </td>
          <td>
            <textarea
              class="form-control"
              v-model="ten"
              placeholder="Tên"
            ></textarea
            ><span
              style="color: red;"
              v-text="error_add_vung_kinh_te_trong_diem"
            ></span>
          </td>
          <td>
            <textarea
              class="form-control"
              v-model="mo_ta"
              placeholder="Mô tả"
            ></textarea
            ><span
              style="color: red;"
              v-text="error_add_vung_kinh_te_trong_diem"
            ></span>
          </td>
          <td align="center">
            <a class="btn btn-flat" @click="addVungKinhTeTrongDiem()"
              ><i class="fa fa-plus"></i> Thêm</a
            >
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
  data: function() {
    return {
      data: [],
      ten: null,
      ma: null,
      mo_ta: null,
      error_add_vung_kinh_te_trong_diem: null
    };
  },
  props: ["usersystem"],
  mounted() {
    axios.get(env.endpointhttp + "vungkinhtetrongdiems").then(response => {
      this.data = response.data.result;
    });
  },
  methods: {
    updateVungKinhTeTrongDiem: function(id, value_1, value_2) {
      axios
        .put(env.endpointhttp + "vungkinhtetrongdiems/" + id, {
          ten: value_1,
          mo_ta: value_2
        })
        .then(response => {
          MessageService.showSuccessMessage('Cập nhật thành công')
        });
    },
    deleteVungKinhTeTrongDiem: function(id, index) {
      this.data.splice(index, 1);
      axios
        .delete(env.endpointhttp + "vungkinhtetrongdiems/" + id)
        .then(response => {});
    },
    addVungKinhTeTrongDiem: function() {
      this.error_add_vung_kinh_te_trong_diem = "";
      axios
        .post(env.endpointhttp + "vungkinhtetrongdiems", {
          ten: this.ten,
          ma: this.ma,
          mo_ta: this.mo_ta
        })
        .then(response => {
          this.data.push(response.data.result);
          this.ten=null,
          this.ma=null,
          this.mo_ta=null
        });
    }
  }
};
</script>
