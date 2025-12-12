<template>
  <div class="timeline-body">
    <table class="table table-hover">
      <tbody>
        <tr class="row-table-header">
          <th style="width: 5%;">STT</th>
          <th>Tên đơn vị</th>
          <th style="width: 15%;">Tên tiếng anh</th>
          <th style="width: 15%;">Tên viết tắt</th>
          <th style="width: 15%;">Website</th>
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
              v-model="item.ten"
              @change="update(item)"
              :disabled="
                !(
                  usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
                )
              "
              rows="3"
            ></textarea>
          </td>
          <td>
            <textarea
              class="form-control"
              v-model="item.eng_ten"
              @change="update(item)"
              :disabled="
                !(
                  usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
                )
              "
              rows="3"
            ></textarea>
          </td>
          <td>
            <textarea
              class="form-control"
              v-model="item.short_ten"
              @change="update(item)"
              :disabled="
                !(
                  usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
                )
              "
              rows="3"
            ></textarea>
          </td>
          <td>
            <textarea
              class="form-control"
              v-model="item.website"
              @change="update(item)"
              :disabled="
                !(
                  usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
                )
              "
              rows="3"
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
            <a @click="destroy(item.id, index)">
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
              v-model="form.ten"
              placeholder="Tên"
              rows="3"
            ></textarea>
            <span
              style="color: red;"
              v-text="error_add_vung_kinh_te_trong_diem"
            ></span>
          </td>
          <td>
            <textarea
              class="form-control"
              v-model="form.eng_ten"
              placeholder="Tên tiếng anh"
              rows="3"
            ></textarea>
            <span
              style="color: red;"
              v-text="error_add_vung_kinh_te_trong_diem"
            ></span>
          </td>
          <td>
            <textarea
              class="form-control"
              v-model="form.short_ten"
              placeholder="Tên viết tắt"
              rows="3"
            ></textarea>
            <span
              style="color: red;"
              v-text="error_add_vung_kinh_te_trong_diem"
            ></span>
          </td>
          <td>
            <textarea
              class="form-control"
              v-model="form.website"
              placeholder="Website"
              rows="3"
            ></textarea>
            <span
              style="color: red;"
              v-text="error_add_vung_kinh_te_trong_diem"
            ></span>
          </td>
          <td align="center">
            <a class="btn btn-flat" @click="store()"
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
      form: {},
      error_add_vung_kinh_te_trong_diem: null
    };
  },
  props: ["usersystem"],
  mounted() {
    axios.get(env.endpointhttp + "danhmuc/donvi").then(response => {
      this.data = response.data.result;
    });
  },
  methods: {
    update(item) {
      if (
        this.usersystem.role_code == "admin" ||
        this.usersystem.role_code == "adminvaquanlydanhmuc"
      ) {
        axios
          .put(env.endpointhttp + "danhmuc/donvi/" + item.id, item)
          .then(response => {
            MessageService.showSuccessMessage('Cập nhật thành công');
          });
      }
    },
    destroy: function(id, index) {
      this.data.splice(index, 1);
      axios
        .delete(env.endpointhttp + "danhmuc/donvi/" + id)
        .then(response => {});
    },
    store: function() {
      this.error_add_vung_kinh_te_trong_diem = "";
      axios
        .post(env.endpointhttp + "danhmuc/donvi", this.form)
        .then(response => {
          this.data.push(response.data.result);
        });
    }
  }
};
</script>
