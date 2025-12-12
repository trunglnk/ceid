<template>
  <div class="timeline-body">
    <table class="table table-hover">
      <tbody>
        <tr class="row-table-header">
          <th style="width:5%">STT</th>
          <th>Tên</th>
          <!-- <th
            style="width:5%"
            v-if="
              usersystem.role_code == 'admin' ||
                usersystem.role_code == 'adminvaquanlydanhmuc'
            "
          ></th> -->
        </tr>
        <tr v-for="(item, index) in data" :key="item.data">
          <td>{{ index + 1 }}</td>
          <td>
            <input
              type="text"
              class="form-control  "
              v-model="item.ten"
              @change="updateCoQuan(item.id, item.ten)"
              v-if="
                usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
              "
            />
            <input
              type="text"
              class="form-control  "
              v-model="item.ten"
              v-else
            />
          </td>
          <!-- <td
            align="center"
            v-if="
              usersystem.role_code == 'admin' ||
                usersystem.role_code == 'adminvaquanlydanhmuc'
            "
          >
            <a @click="deleteCoQuan(item.id, index)">
              <i class="fa fa-trash-o btn"></i
            ></a>
          </td> -->
        </tr>
        <tr
          v-if="
            usersystem.role_code == 'admin' ||
              usersystem.role_code == 'adminvaquanlydanhmuc'
          "
        >
          <td colspan="2">
            <input
              type="text"
              class="form-control  "
              autofocus
              v-model="ten_co_quan"
            /><span style="color: red" v-text="error_ten_co_quan"></span>
          </td>
          <td align="center">
            <a class="btn btn-flat" @click="addCoQuan()"
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
      ten_co_quan: null,
      error_ten_co_quan: null
    };
  },
  props: ["usersystem"],
  mounted() {
    axios.get(env.endpointhttp + "coquantochucs").then(response => {
      this.data = response.data.result;
    });
  },
  methods: {
    updateCoQuan: function(id, value) {
      axios
        .put(env.endpointhttp + "coquantochucs/" + id, { ten: value })
        .then(response => {
          MessageService.showSuccessMessage("Cập nhật thành công");
        });
    },
    deleteCoQuan: function(id, index) {
      this.data.splice(index, 1);
      axios.delete(env.endpointhttp + "coquantochucs/" + id).then(response => {
        MessageService.showSuccessMessage("Xoá thành công");
      });
    },
    addCoQuan: function() {
      this.error_ten_co_quan = "";
      if (this.ten_co_quan == "" || this.ten_co_quan == null) {
        this.error_ten_co_quan = "Chưa nhập cơ sở.";
      } else {
        axios
          .post(env.endpointhttp + "coquantochucs", { ten: this.ten_co_quan })
          .then(response => {
            this.data.push(response.data.result);
            this.ten_co_quan = "";
            MessageService.showSuccessMessage("Thêm mới thành công");
          });
      }
    }
  }
};
</script>
