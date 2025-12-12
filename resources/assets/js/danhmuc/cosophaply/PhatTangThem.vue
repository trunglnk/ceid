<template>
  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li
        v-for="(tab, i) in tabs"
        :key="i"
        :class="{ active: currentTab == tab.type }"
        @click="loadDataTab(tab.type)"
      >
        <a data-toggle="tab" aria-expanded="true">{{ tab.text }}</a>
      </li>
    </ul>

    <div class="tab-content">
      <div class="tab-pane active">
        <div class="row">
          <div class="col-sm-12">
            <table class="table table-hover">
              <tbody>
                <tr class="row-table-header">
                  <th style="width:5%">STT</th>
                  <th style="width:20%">Phần trăm</th>
                  <th>Từ số lần vượt mức</th>
                  <th>Đến số lần vượt mức</th>
                  <th
                    style="width:5%"
                    v-if="
                      usersystem.role_code == 'admin' ||
                        usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  ></th>
                </tr>
                <tr v-for="(item, index) in data" :key="item.data">
                  <td>{{ index + 1 }}</td>
                  <td>
                    <input
                      type="number"
                      step="0.01"
                      class="form-control"
                      v-model="item.phan_tram_tang_them"
                      @blur="updatePhatTangThem(item)"
                    />
                  </td>
                  <td>
                    <input
                      type="number"
                      step="0.01"
                      class="form-control"
                      v-model="item.tu_gia_tri"
                      @blur="updatePhatTangThem(item)"
                    />
                  </td>
                  <td>
                    <input
                      type="number"
                      step="0.01"
                      class="form-control"
                      v-model="item.den_gia_tri"
                      @blur="updatePhatTangThem(item)"
                    />
                  </td>
                  <td
                    align="center"
                    v-if="
                      usersystem.role_code == 'admin' ||
                        usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  >
                    <a @click="deletePhatTangThem(item.id, index)">
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
                  <td></td>
                  <td colspan="1">
                    <input
                      type="number"
                      step="0.01"
                      class="form-control  "
                      v-model="phan_tram_tang_them"
                      placeholder="Phần trăm"
                    /><span
                      style="color: red"
                      v-text="error_phan_tram_tang_them"
                    ></span>
                  </td>
                  <td>
                    <input
                      type="number"
                      step="0.01"
                      class="form-control  "
                      v-model="tu_gia_tri"
                      placeholder="Từ giá trị"
                    /><span style="color: red" v-text="error_tu_gia_tri"></span>
                  </td>
                  <td>
                    <input
                      type="number"
                      step="0.01"
                      class="form-control  "
                      v-model="den_gia_tri"
                      placeholder="Đến giá trị"
                    /><span
                      style="color: red"
                      v-text="error_den_gia_tri"
                    ></span>
                  </td>
                  <td align="center">
                    <a class="btn btn-flat" @click="addPhatTangThem()"
                      ><i class="fa fa-plus"></i> Thêm</a
                    >
                  </td>
                </tr>
                <tr>
                  <td colspan="5">
                    <span
                      style="color: red"
                      v-text="error_add_phat_tang_them"
                    ></span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as env from "../../env.js";
import MessageService from "../../services/MessageService";
import Multiselect from "vue-multiselect";

export default {
  components: {
    Multiselect
  },
  data: function() {
    return {
      currentTab: "",
      data: [],
      tabs: [
        { text: "Nước thải", type: "nuoc_thai" },
        { text: "Khí thải,bụi", type: "khi_thai" }
      ],
      phan_tram_tang_them: null,
      type: null,
      tu_gia_tri: null,
      den_gia_tri: null,
      error_phan_tram_tang_them: null,
      error_tu_gia_tri: null,
      error_den_gia_tri: null,
      error_add_phat_tang_them: null
    };
  },
  props: ["usersystem"],
  created() {
    this.loadDataTab(this.tabs[0].type);
  },
  methods: {
    loadDataTab(type) {
      this.currentTab = type;
      axios
        .get(env.endpointhttp + "phattangthems", {
          params: { search_type: type }
        })
        .then(response => {
          this.data = response.data.result;
        });
    },
    updatePhatTangThem: function(item) {
      axios
        .put(env.endpointhttp + "phattangthems/" + item.id, {
          phan_tram_tang_them: item.phan_tram_tang_them,
          tu_gia_tri: item.tu_gia_tri,
          den_gia_tri: item.den_gia_tri,
          type: item.loai_phat_tang_them && item.loai_phat_tang_them.id
        })
        .then(response => {
          //MessageService.showSuccessMessage("Cập nhật thành công");
        });
    },
    deletePhatTangThem: function(id, index) {
      this.data.splice(index, 1);
      axios.delete(env.endpointhttp + "phattangthems/" + id).then(response => {
        // MessageService.showSuccessMessage('Xoá thành công');
      });
    },
    addPhatTangThem: function() {
      this.error_add_phat_tang_them = "";
      this.error_phan_tram_tang_them = "";
      this.error_tu_gia_tri = "";
      this.error_den_gia_tri = "";
      this.error_loai_phat_tang_them = "";
      if (
        this.phan_tram_tang_them == "" ||
        this.phan_tram_tang_them == null ||
        this.tu_gia_tri == "" ||
        this.tu_gia_tri == null
      ) {
        this.error_add_phat_tang_them = "Chưa nhập đủ dữ liệu.";
      } else {
        axios
          .post(env.endpointhttp + "phattangthems", {
            phan_tram_tang_them: this.phan_tram_tang_them,
            tu_gia_tri: this.tu_gia_tri,
            den_gia_tri: this.den_gia_tri,
            type: this.currentTab
          })
          .then(response => {
            this.loadDataTab(this.currentTab);
            this.phan_tram_tang_them = "";
            this.tu_gia_tri = "";
            this.den_gia_tri = "";
            this.type = "";
            // MessageService.showSuccessMessage('Thêm mới thành công');
          });
      }
    }
  }
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
