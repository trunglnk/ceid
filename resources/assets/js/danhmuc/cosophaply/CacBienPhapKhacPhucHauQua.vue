<template>
  <div class="timeline-body">
    <table class="table table-hover">
      <tbody>
        <tr class="row-table-header">
          <th style="text-align:center">STT</th>
          <th style="width:20%">Tên</th>
          <th style="width:70%">Nội dung</th>
          <th style="width:5%"></th>
        </tr>
        <tr v-for="(item, index) in data" :key="item.data">
          <td align="center">{{ index + 1 }}</td>
          <td>
            <textarea
              class="form-control  "
              v-model="item.ten"
              @change="
                updateCacBienPhapKhacPhucHauQua(item.id, item.ten, item.mo_ta)
              "
            ></textarea>
          </td>
          <td>
            <textarea
              rows="3"
              type="text"
              class="form-control  "
              v-model="item.mo_ta"
              @change="
                updateCacBienPhapKhacPhucHauQua(item.id, item.ten, item.mo_ta)
              "
            ></textarea>
          </td>
          <td align="center">
            <a @click="deleteCacBienPhapKhacPhucHauQua(item.id, index)"
              ><i class="fa fa-trash-o btn"></i
            ></a>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <textarea
              class="form-control  "
              v-model="ten"
              placeholder="Tên"
            ></textarea
            ><span
              style="color: red"
              v-text="error_add_cac_bien_phap_khac_phuc_hau_qua"
            ></span>
          </td>
          <td>
            <textarea
              class="form-control  "
              v-model="mo_ta"
              placeholder="Mô tả"
            ></textarea
            ><span
              style="color: red"
              v-text="error_add_cac_bien_phap_khac_phuc_hau_qua"
            ></span>
          </td>
          <td align="center">
            <a class="btn btn-flat" @click="addCacBienPhapKhacPhucHauQua()"
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
      error_add_cac_bien_phap_khac_phuc_hau_qua: null
    };
  },
  created() {
    axios
      .get(env.endpointhttp + "cacbienphapkhacphuchauquas")
      .then(response => {
        this.data = response.data.result;
      });
  },
  methods: {
    updateCacBienPhapKhacPhucHauQua: function(id, value_1, value_2) {
      axios
        .put(env.endpointhttp + "cacbienphapkhacphuchauquas/" + id, {
          ten: value_1,
          mo_ta: value_2
        })
        .then(response => {
          // MessageService.showSuccessMessage('Cập nhật thành công');
        });
    },
    deleteCacBienPhapKhacPhucHauQua: function(id, index) {
      this.data.splice(index, 1);
      axios
        .delete(env.endpointhttp + "cacbienphapkhacphuchauquas/" + id)
        .then(response => {
          // MessageService.showSuccessMessage('Xoá thành công');
        });
    },
    addCacBienPhapKhacPhucHauQua: function() {
      this.error_add_cac_bien_phap_khac_phuc_hau_qua = "";
      if (
        this.ten == "" ||
        this.ten == null ||
        this.mo_ta == "" ||
        this.mo_ta == null
      ) {
        this.error_add_cac_bien_phap_khac_phuc_hau_qua =
          "Chưa nhập đủ dữ liệu.";
      } else {
        axios
          .post(env.endpointhttp + "cacbienphapkhacphuchauquas", {
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
