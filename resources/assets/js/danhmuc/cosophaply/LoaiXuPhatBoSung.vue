<template>
  <div class="timeline-body">
    <table class="table table-hover">
      <tbody>
        <tr class="row-table-header">
          <th>STT</th>
          <th>Tên</th>
          <th>Mô tả</th>
          <th style="width:5%"></th>
        </tr>
        <tr v-for="(item, index) in data" :key="item.data">
          <td>{{ index + 1 }}</td>
          <td>
            <textarea
              class="form-control"
              rows="5"
              v-model="item.ten"
              @change="updateLoaiXuPhatBoSung(item.id, item.ten, item.mo_ta)"
            ></textarea>
          </td>
          <td>
            <textarea
              rows="5"
              type="text"
              class="form-control"
              v-model="item.mo_ta"
              @change="updateLoaiXuPhatBoSung(item.id, item.ten, item.mo_ta)"
            ></textarea>
          </td>
          <td align="center">
            <a @click="deleteLoaiXuPhatBoSung(item.id, index)"
              ><i class="fa fa-trash-o btn"></i
            ></a>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <textarea
              class="form-control"
              v-model="ten"
              placeholder="Tên"
            ></textarea
            ><span
              style="color: red"
              v-text="error_add_loai_xu_phat_bo_sung"
            ></span>
          </td>
          <td>
            <textarea
              class="form-control"
              v-model="mo_ta"
              placeholder="Mô tả"
            ></textarea
            ><span
              style="color: red"
              v-text="error_add_loai_xu_phat_bo_sung"
            ></span>
          </td>
          <td align="center">
            <a class="btn btn-flat" @click="addLoaiXuPhatBoSung()"
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
      id: null,
      ten: null,
      mo_ta: null,
      error_add_loai_xu_phat_bo_sung: null
    };
  },
  created() {
    axios.get(env.endpointhttp + "loaixuphatbosungs").then(response => {
      this.data = response.data.result;
    });
  },
  methods: {
    updateLoaiXuPhatBoSung: function(id, value_1, value_2) {
      axios
        .put(env.endpointhttp + "loaixuphatbosungs/" + id, {
          ten: value_1,
          mo_ta: value_2
        })
        .then(response => {
          // MessageService.showSuccessMessage('Cập nhật thành công');
        });
    },
    deleteLoaiXuPhatBoSung: function(id, index) {
      this.data.splice(index, 1);
      axios
        .delete(env.endpointhttp + "loaixuphatbosungs/" + id)
        .then(response => {
          // MessageService.showSuccessMessage('Xoá thành công');
        });
    },
    addLoaiXuPhatBoSung: function() {
      this.error_add_loai_xu_phat_bo_sung = "";
      if (
        this.ten == "" ||
        this.ten == null ||
        this.mo_ta == "" ||
        this.mo_ta == null
      ) {
        this.error_add_loai_xu_phat_bo_sung = "Chưa nhập đủ dữ liệu.";
      } else {
        axios
          .post(env.endpointhttp + "loaixuphatbosungs", {
            ten: this.ten,
            mo_ta: this.mo_ta
          })
          .then(response => {
            this.data.push(response.data.result);
            this.ten = "";
            this.mo_ta = "";
            // MessageService.showSuccessMessage('Thêm mới thành công');
          });
      }
    }
  }
};
</script>
