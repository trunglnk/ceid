<template>
  <div class="timeline-body">
    <table class="table table-hover">
      <tbody>
        <tr class="row-table-header">
          <th style="width:5%">STT</th>
          <th>Mã nghị định</th>
          <th>Tên nghị định</th>
          <th>Mô tả</th>
          <!-- <th
            style="width:5%"
            v-if="
              usersystem.role_code == 'admin' ||
                usersystem.role_code == 'adminvaquanlydanhmuc'
            "
          ></th> -->
        </tr>
        <tr v-for="(item, index) in data" :key="item.id">
          <td>{{ index + 1 }}</td>
          <td>
            <input
              type="text"
              class="form-control  "
              v-model="item.code"
              @change="updateCode(item.id, item.code)"
              v-if="
                usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
              "
            />
            <input
              type="text"
              class="form-control  "
              v-model="item.code"
              v-else
            />
          </td>
          <td>
            <input
              type="text"
              class="form-control  "
              v-model="item.name"
              @change="updateName(item.id, item.name)"
              v-if="
                usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
              "
            />
            <input
              type="text"
              class="form-control  "
              v-model="item.name"
              v-else
            />
          </td>
          <td>
            <input
              type="text"
              class="form-control  "
              v-model="item.description"
              @change="updateDescription(item.id, item.description)"
              v-if="
                usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
              "
            />
            <input
              type="text"
              class="form-control  "
              v-model="item.description"
              v-else
            />
          </td>
          <td style="width: 5%">
            <a @click="showConfirmDelete(item)">
              <i class="fa fa-trash-o btn"></i>
            </a>
          </td>

        </tr>
        <tr
          v-if="
            usersystem.role_code == 'admin' ||
              usersystem.role_code == 'adminvaquanlydanhmuc'
          "
        >
          <td colspan="1">{{ data.length + 1}}</td>
          <td colspan="1">
            <input
              type="text"
              class="form-control"
              autofocus
              v-model="code"
            /><span style="color: red" v-text="error_code"></span>
          </td>
          <td colspan="1">
            <input
              type="text"
              class="form-control"
              autofocus
              v-model="name"
            />
          </td>
          <td colspan="1">
            <input
              type="text"
              class="form-control"
              autofocus
              v-model="description"
            />
          </td>

          <td align="center">
            <a class="btn btn-flat" @click="addNghiDinh()"
              ><i class="fa fa-plus"></i> Thêm</a
            >
          </td>
        </tr>
      </tbody>
    </table>
    <modal-component width="450px" v-model="showModel" center @submit="deleteNghiDinh(nghiDinhDelete.id)" :title="'Xác nhận xóa'">
      <div>Bạn có chắc chắn muốn xóa {{nghiDinhDelete.code}} không?</div>
    </modal-component>
  </div>
</template>

<script>
import * as env from "../../env.js";
import MessageService from "../../services/MessageService.js";

export default {
  data: function() {
    return {
      data: [],
      code: null,
      name: undefined,
      description: undefined,
      showModel: false,
      nghiDinhDelete: {},
      error_code: null
    };
  },
  props: ["usersystem"],
  mounted() {
    this.fetchNghiDinhs();
  },
  methods: {
    fetchNghiDinhs() {
      axios.get(env.endpointhttp + "nghidinhs").then(response => {
        this.data = response.data;
      });
    },

    updateCode: function(id, value) {
      axios
        .put(env.endpointhttp + "nghidinhs/" + id, { code: value })
        .then(response => {
          MessageService.showSuccessMessage("Cập nhật thành công");
        });
    },
    updateName: function(id, value) {
      axios
        .put(env.endpointhttp + "nghidinhs/" + id, { name: value })
        .then(response => {
          MessageService.showSuccessMessage("Cập nhật thành công");
        });
    },
    updateDescription: function(id, value) {
      axios
        .put(env.endpointhttp + "nghidinhs/" + id, { description: value })
        .then(response => {
          MessageService.showSuccessMessage("Cập nhật thành công");
        });
    },

    deleteNghiDinh: function(id, index) {
      axios.delete(env.endpointhttp + "nghidinhs/" + id)
      .then(response => {
        MessageService.showSuccessMessage("Xoá thành công");
        // Gọi lại API sau khi xóa thành công để cập nhật danh sách
        this.fetchNghiDinhs();
      })
      this.showModel = false;

    },
    showConfirmDelete(item) {
      this.showModel = true;
      this.nghiDinhDelete = item
    },
    addNghiDinh: function() {
      console.log({'code: ': this.code, 'data: ': this.data})
      if (this.code == "" || this.code == null) {
        console.log('this.error_code22: ', this.error_code)
        this.error_code = "Chưa nhập mã nghị định.";
      } else if (this.data.find(item => item.code === this.code)) {
        this.error_code = "Mã nghị định không được trùng nhau.";
      } else {
        axios
          .post(env.endpointhttp + "nghidinhs", { code: this.code, name: this.name, description: this.description })
          .then(response => {
            this.data.push(response.data.result);
            this.code = null;
            this.name = undefined;
            this.description = undefined;
            MessageService.showSuccessMessage("Thêm mới thành công");
          });
          this.error_code = null;


      }
    }
  }
};
</script>
