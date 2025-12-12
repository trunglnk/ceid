<template>
  <div class="timeline-body">
    <div class="col-md-12">
      <div class="nav-tabs-custom margin-top">
        <ul class="nav nav-tabs">
          <li class="active">
            <a href="#tab_dtm" data-toggle="tab" aria-expanded="true" @click="clickTab('C_LoaiVanBanDTM')">Loại văn bản
              DTM</a>
          </li>
          <li>
            <a href="#tab_giayphep" data-toggle="tab" aria-expanded="false"
              @click="clickTab('C_LoaiGiayPhepMoiTruong')">Loại giấy phép môi trường</a>
          </li>
          <li>
            <a href="#tab_khac" data-toggle="tab" aria-expanded="false"
              @click="clickTab('khac')">Loại giấy thủ tục khác</a>
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab_dtm">
            <table class="table table-hover">
              <tbody>
                <tr class="row-table-header">
                  <th style="width:5%">STT</th>
                  <th style="width:15%">Mã</th>
                  <th>Tên</th>
                  <th>Mô Tả</th>
                  <th>Phân loại</th>
                  <!-- <th style="width:5%" v-if="
                    usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                  "></th> -->
                </tr>
                <tr v-for="(item, index) in loaiDTM" :key="item.data">
                  <td>{{ index + 1 }}</td>
                  <td>
                    <input type="text" class="form-control  " v-model="item.ma" disabled />
                  </td>
                  <td>
                    <input type="text" class="form-control  " v-model="item.ten"
                      @change="updateLoaiTTHC(item.ma, item.ten, item.mo_ta, item.phan_loai, item.phan_loai)" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      " />
                    <input type="text" class="form-control  " v-model="item.ten" v-else />
                  </td>
                  <td>
                    <input type="text" class="form-control  " v-model="item.mo_ta"
                      @change="updateLoaiTTHC(item.ma, item.ten, item.mo_ta, item.phan_loai, item.phan_loai)" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      " />
                    <input type="text" class="form-control  " v-model="item.mo_ta" v-else />
                  </td>
                  <td>
                    <input type="text" class="form-control  " v-model="item.phan_loai"
                      @change="updateLoaiTTHC(item.ma, item.ten, item.mo_ta, item.phan_loai)" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      " />
                    <input type="text" class="form-control  " v-model="item.phan_loai" v-else />
                  </td>
                  <!-- <td align="center" v-if="
                    usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                  ">
                    <a @click="deleteLoaiTTHC(item.ma, index)">
                      <i class="fa fa-trash-o btn"></i></a>
                  </td> -->
                </tr>
                <tr v-if="
                  usersystem.role_code == 'admin' ||
                    usersystem.role_code == 'adminvaquanlydanhmuc'
                ">
                  <td colspan="2">
                    <input type="text" class="form-control  " v-model="ma" placeholder="Mã" /><span style="color: red"
                      v-text="error_add_loai_tthc"></span>
                  </td>
                  <td>
                    <input type="text" class="form-control  " v-model="ten" placeholder="Tên" /><span style="color: red"
                      v-text="error_add_loai_tthc"></span>
                  </td>
                  <td>
                    <input type="text" class="form-control  " v-model="mo_ta" placeholder="Mô tả" /><span
                      style="color: red" v-text="error_add_loai_tthc"></span>
                  </td>
                  <td align="center">
                    <a class="btn btn-flat" @click="addLoaiTTHC()"><i class="fa fa-plus"></i> Thêm</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="tab-pane" id="tab_giayphep">
            <table class="table table-hover">
              <tbody>
                <tr class="row-table-header">
                  <th style="width:5%">STT</th>
                  <th style="width:15%">Mã</th>
                  <th>Tên</th>
                  <th>Mô Tả</th>
                  <th>Phân loại</th>
                  <!-- <th style="width:5%" v-if="
                    usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                  "></th> -->
                </tr>
                <tr v-for="(item, index) in giayPhepMT" :key="item.data">
                  <td>{{ index + 1 }}</td>
                  <td>
                    <input type="text" class="form-control  " v-model="item.ma" disabled />
                  </td>
                  <td>
                    <input type="text" class="form-control  " v-model="item.ten"
                      @change="updateLoaiTTHC(item.ma, item.ten, item.mo_ta, item.phan_loai)" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      " />
                    <input type="text" class="form-control  " v-model="item.ten" v-else />
                  </td>
                  <td>
                    <input type="text" class="form-control  " v-model="item.mo_ta"
                      @change="updateLoaiTTHC(item.ma, item.ten, item.mo_ta, item.phan_loai)" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      " />
                    <input type="text" class="form-control  " v-model="item.mo_ta" v-else />
                    <td>
                    <input type="text" class="form-control  " v-model="item.phan_loai"
                      @change="updateLoaiTTHC(item.ma, item.ten, item.mo_ta, item.phan_loai)" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      " />
                    <input type="text" class="form-control  " v-model="item.phan_loai" v-else />
                  </td>
                  </td>
                  <!-- <td align="center" v-if="
                    usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                  ">
                    <a @click="deleteLoaiTTHC(item.ma, index)">
                      <i class="fa fa-trash-o btn"></i></a>
                  </td> -->
                </tr>
                <tr v-if="
                  usersystem.role_code == 'admin' ||
                    usersystem.role_code == 'adminvaquanlydanhmuc'
                ">
                  <td colspan="2">
                    <input type="text" class="form-control  " v-model="ma" placeholder="Mã" /><span style="color: red"
                      v-text="error_add_loai_tthc"></span>
                  </td>
                  <td>
                    <input type="text" class="form-control  " v-model="ten" placeholder="Tên" /><span style="color: red"
                      v-text="error_add_loai_tthc"></span>
                  </td>
                  <td>
                    <input type="text" class="form-control  " v-model="mo_ta" placeholder="Mô tả" /><span
                      style="color: red" v-text="error_add_loai_tthc"></span>
                  </td>
                  <td align="center">
                    <a class="btn btn-flat" @click="addLoaiTTHC()"><i class="fa fa-plus"></i> Thêm</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="tab-pane" id="tab_khac">
            <table class="table table-hover">
              <tbody>
                <tr class="row-table-header">
                  <th style="width:5%">STT</th>
                  <th style="width:15%">Mã</th>
                  <th>Tên</th>
                  <th>Mô Tả</th>
                  <th>Phân loại</th>
                  <!-- <th style="width:5%" v-if="
                    usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                  "></th> -->
                </tr>
                <tr v-for="(item, index) in thuTucKhac" :key="item.data">
                  <td>{{ index + 1 }}</td>
                  <td>
                    <input type="text" class="form-control  " v-model="item.ma" disabled />
                  </td>
                  <td>
                    <input type="text" class="form-control  " v-model="item.ten"
                      @change="updateLoaiTTHC(item.ma, item.ten, item.mo_ta, item.phan_loai)" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      " />
                    <input type="text" class="form-control  " v-model="item.ten" v-else />
                  </td>
                  <td>
                    <input type="text" class="form-control  " v-model="item.mo_ta"
                      @change="updateLoaiTTHC(item.ma, item.ten, item.mo_ta,item.phan_loai)" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      " />
                    <input type="text" class="form-control  " v-model="item.mo_ta" v-else />
                  </td>
                  <td>
                    <input type="text" class="form-control  " v-model="item.phan_loai"
                      @change="updateLoaiTTHC(item.ma, item.ten, item.mo_ta, item.phan_loai)" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      " />
                    <input type="text" class="form-control  " v-model="item.phan_loai" v-else />
                  </td>
                  <!-- <td align="center" v-if="
                    usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                  ">
                    <a @click="deleteLoaiTTHC(item.ma, index)">
                      <i class="fa fa-trash-o btn"></i></a>
                  </td> -->
                </tr>
                <tr v-if="
                  usersystem.role_code == 'admin' ||
                    usersystem.role_code == 'adminvaquanlydanhmuc'
                ">
                  <td colspan="2">
                    <input type="text" class="form-control  " v-model="ma" placeholder="Mã" /><span style="color: red"
                      v-text="error_add_loai_tthc"></span>
                  </td>
                  <td>
                    <input type="text" class="form-control  " v-model="ten" placeholder="Tên" /><span style="color: red"
                      v-text="error_add_loai_tthc"></span>
                  </td>
                  <td>
                    <input type="text" class="form-control  " v-model="mo_ta" placeholder="Mô tả" /><span
                      style="color: red" v-text="error_add_loai_tthc"></span>
                  </td>
                  <td align="center">
                    <a class="btn btn-flat" @click="addLoaiTTHC()"><i class="fa fa-plus"></i> Thêm</a>
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

export default {
  data: function () {
    return {
      data: [],
      ten: null,
      ma: null,
      mo_ta: null,
      error_add_loai_tthc: null,
      loaiDTM: [],
      giayPhepMT: [],
      thuTucKhac: [],
      phan_loai: 'C_LoaiVanBanDTM'
    };
  },
  props: ["usersystem"],
  created() {
    this.getData()
  },
  methods: {
    getData() {
      axios.get(env.endpointhttp + "loaithutuchanhchinhs").then(response => {
        this.data = response.data.result;
        this.loaiDTM = this.data.filter(el => el.phan_loai == 'C_LoaiVanBanDTM');
        this.giayPhepMT = this.data.filter(el => el.phan_loai == 'C_LoaiGiayPhepMoiTruong');
        this.thuTucKhac = this.data.filter(el => el.phan_loai !== 'C_LoaiGiayPhepMoiTruong' && el.phan_loai !== 'C_LoaiVanBanDTM')
      });
    },
    updateLoaiTTHC: function (id, value_1, value_2, value_3) {
      axios
        .put(env.endpointhttp + "loaithutuchanhchinhs/" + id, {
          ten: value_1,
          mo_ta: value_2,
          phan_loai: value_3
        })
        .then(response => {
          // MessageService.showSuccessMessage('Cập nhật thành công');
        });
    },
    clickTab(type) {
      this.phan_loai = type
    },
    deleteLoaiTTHC: function (id, index) {
      this.data.splice(index, 1);
      axios
        .delete(env.endpointhttp + "loaithutuchanhchinhs/" + id)
        .then(response => {
          // MessageService.showSuccessMessage('Xoá thành công');
        });
    },
    addLoaiTTHC: function () {
      this.error_add_loai_tthc = "";
      if (
        this.ten == "" ||
        this.ten == null ||
        this.ma == "" ||
        this.ma == null
      ) {
        this.error_add_loai_tthc = "Chưa nhập đủ dữ liệu.";
      } else {
        axios
          .post(env.endpointhttp + "loaithutuchanhchinhs", {
            ten: this.ten,
            ma: this.ma,
            mo_ta: this.mo_ta,
            phan_loai: this.phan_loai
          })
          .then(response => {
            this.getData()
            this.ten = "";
            this.ma = "";
            this.mo_ta = "";
            MessageService.showSuccessMessage('Thêm mới thành công');
          });
      }
    }
  }
};
</script>
