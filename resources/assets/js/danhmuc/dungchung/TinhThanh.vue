<template>
  <div class="timeline-body">
    <div
      style="display: flex; align-items: center; justify-content: space-between"
    >
      <div v-if="load_thoi_gian">
        <div v-if="this.thoi_gian == ''">Chưa đồng bộ với CSDL quốc gia</div>
        <div v-else>Đồng bộ lần cuối: {{ thoi_gian_dong_bo }}</div>
      </div>
      <div v-else>Đang đồng bộ với CSDL quốc gia</div>

      <div style="margin-bottom: 10px">
        <div style="width: 140px" class="ml-4">
          <div
            class="btn btn-flat btn-block btn-default d-flex align-center"
            @click="dongbodulieu()"
            :disabled="!trangThaiDongBo"
            style="height: 40px"
          >
            <i class="fa fa-refresh" style="padding-right: 5px"></i>
            Đồng bộ dữ liệu
          </div>
        </div>
      </div>
    </div>
    <div class="nav-tabs-custom margin-top">
      <ul class="nav nav-tabs">
        <li class="active">
          <a href="#tab_dongbo" data-toggle="tab" aria-expanded="true"
            >Dữ liệu đã đồng bộ từ MTQG
          </a>
        </li>
        <li v-if="tabKhac && tabKhac.length > 0">
          <a href="#tab_khac" data-toggle="tab" aria-expanded="false"
            >Dữ liệu khác</a
          >
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_dongbo">
          <div
            class="pl-4 pr-4 pb-4"
            style="display: flex; justify-content: end; align-items: flex-end"
          >
            <div class="input-group">
              <input
                type="text"
                v-model="searchdata"
                class="form-control"
                style="z-index: 0; width: 400px"
                placeholder="Tìm kiếm tên tỉnh thành"
                v-on:keyup.enter="searchData()"
              />
              <span
                class="input-group-addon"
                style="cursor: pointer"
                @click="searchData()"
              >
                <i class="fa fa-search"></i>
              </span>
            </div>
          </div>
          <table class="table table-hover">
            <tbody>
              <tr class="row-table-header">
                <th>Mã</th>
                <th>Tên</th>
                <th>Miền</th>
                <th>Vùng kinh tế trọng điểm</th>
              </tr>
              <template>
                <tr v-for="(item, index) in data" :key="index">
                  <td>{{ item.ma }}</td>
                  <td>{{ item.ten }}</td>
                  <td>
                    <multiselect
                      v-model="item.mien"
                      label="ten"
                      track-by="id"
                      placeholder="Gõ tên miền"
                      selectLabel="Nhấn enter để chọn"
                      deselectLabel="Nhấn enter để bỏ chọn"
                      :showLabels="false"
                      open-direction="bottom"
                      :options="miens"
                      :searchable="false"
                      :loading="is_loading"
                      :internal-search="false"
                      :options-limit="300"
                      :max-height="600"
                      :allowEmpty="false"
                      :tabindex="1"
                      required
                      @input="update(item, index)"
                    >
                      <span slot="noResult">Không tìm thấy kết quả</span>
                    </multiselect>
                  </td>
                  <td>
                    <multiselect
                      v-model="item.vung_kinh_te_trong_diem"
                      label="ten"
                      track-by="id"
                      placeholder="Chọn vùng kinh tế trọng điểm"
                      selectLabel="Nhấn enter để chọn"
                      deselectLabel="Nhấn enter để bỏ chọn"
                      :showLabels="false"
                      open-direction="bottom"
                      :options="vungkinhtetrongdiems"
                      :searchable="false"
                      :loading="is_loading"
                      :internal-search="false"
                      :options-limit="300"
                      :max-height="600"
                      :allowEmpty="true"
                      :tabindex="1"
                      required
                      @input="update(item, index)"
                    >
                      <span slot="noResult">Không tìm thấy kết quả</span>
                    </multiselect>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
        <div
          class="tab-pane"
          id="tab_khac"
          v-if="tabKhac && tabKhac.length > 0"
        >
          <div
            class="pl-4 pr-4 pb-4"
            style="display: flex; justify-content: end; align-items: flex-end"
          >
            <div class="input-group">
              <input
                type="text"
                v-model="searchdata"
                class="form-control"
                style="z-index: 0; width: 400px"
                placeholder="Tìm kiếm tên tỉnh thành"
                v-on:keyup.enter="searchDatachuadongbo()"
              />
              <span
                class="input-group-addon"
                style="cursor: pointer"
                @click="searchDatachuadongbo()"
              >
                <i class="fa fa-search"></i>
              </span>
            </div>
          </div>
          <table class="table table-hover">
            <tbody>
              <tr class="row-table-header">
                <th>Mã</th>
                <th>Tên</th>
                <th>Miền</th>
                <th>Vùng kinh tế trọng điểm</th>
                <th>Lưu vực sông</th>
                <th>Kinh độ</th>
                <th>Vĩ độ</th>
                <th>Xóa</th>
              </tr>
              <template>
                <tr v-for="(item, index) in dataChuaDongBo" :key="index">
                  <td>{{ item.ma }}</td>
                  <td>{{ item.ten }}</td>
                  <td>
                    <multiselect
                      v-model="item.mien"
                      label="ten"
                      track-by="id"
                      placeholder="Gõ tên miền"
                      selectLabel="Nhấn enter để chọn"
                      deselectLabel="Nhấn enter để bỏ chọn"
                      :showLabels="false"
                      open-direction="bottom"
                      :options="miens"
                      :searchable="false"
                      :loading="is_loading"
                      :internal-search="false"
                      :options-limit="300"
                      :max-height="600"
                      :allowEmpty="false"
                      :tabindex="1"
                      required
                      @input="update(item, index)"
                    >
                      <span slot="noResult">Không tìm thấy kết quả</span>
                    </multiselect>
                  </td>
                  <td>
                    <multiselect
                      v-model="item.vung_kinh_te_trong_diem"
                      label="ten"
                      track-by="id"
                      placeholder="Chọn vùng kinh tế trọng điểm"
                      selectLabel="Nhấn enter để chọn"
                      deselectLabel="Nhấn enter để bỏ chọn"
                      :showLabels="false"
                      open-direction="bottom"
                      :options="vungkinhtetrongdiems"
                      :searchable="false"
                      :loading="is_loading"
                      :internal-search="false"
                      :options-limit="300"
                      :max-height="600"
                      :allowEmpty="true"
                      :tabindex="1"
                      required
                      @input="update(item, index)"
                    >
                      <span slot="noResult">Không tìm thấy kết quả</span>
                    </multiselect>
                  </td>
                  <td>
                    <multiselect
                      v-model="item.luu_vuc_song"
                      label="ten"
                      track-by="id"
                      placeholder="Chọn lưu vực sông"
                      selectLabel="Nhấn enter để chọn"
                      deselectLabel="Nhấn enter để bỏ chọn"
                      :showLabels="false"
                      open-direction="bottom"
                      :options="luuvucsongs"
                      :searchable="false"
                      :loading="is_loading"
                      :internal-search="false"
                      :options-limit="300"
                      :max-height="600"
                      :allowEmpty="true"
                      :tabindex="1"
                      required
                      @input="update(item, index)"
                    >
                      <span slot="noResult">Không tìm thấy kết quả</span>
                    </multiselect>
                  </td>
                  <td>{{ item.kinh_do }}</td>
                  <td>{{ item.vi_do }}</td>
                  <td style="width: 70px">
                    <a @click="showConfirmDelete(item)">
                      <i class="fa fa-trash-o btn"></i
                    ></a>
                  </td>
                </tr>

                <!-- Dòng thêm mới -->
                <tr>
                  <td>
                    <input
                      type="number"
                      class="form-control"
                      v-model="form.ma"
                      style="width: 130px"
                      placeholder="Mã"
                    />
                    <span
                      style="color: red"
                      v-text="error_add_tinh_thanh_ma"
                    ></span>
                  </td>
                  <td>
                    <input
                      type="text"
                      class="form-control"
                      v-model="form.ten"
                      style="width: 130px"
                      placeholder="Tên"
                    />
                    <span
                      style="color: red"
                      v-text="error_add_tinh_thanh_ten"
                    ></span>
                  </td>
                  <td>
                    <multiselect
                      v-model="form.mien"
                      label="ten"
                      track-by="id"
                      placeholder="Gõ tên miền"
                      selectLabel="Nhấn enter để chọn"
                      deselectLabel="Nhấn enter để bỏ chọn"
                      :showLabels="false"
                      open-direction="bottom"
                      :options="miens"
                      :searchable="false"
                      :loading="is_loading"
                      :internal-search="false"
                      :options-limit="300"
                      :max-height="600"
                      :allowEmpty="false"
                      :tabindex="1"
                      required
                    >
                      <span slot="noResult">Không tìm thấy kết quả</span>
                    </multiselect>
                  </td>
                  <td>
                    <multiselect
                      v-model="form.vung_kinh_te_trong_diem"
                      label="ten"
                      track-by="id"
                      placeholder="Chọn vùng kinh tế trọng điểm"
                      selectLabel="Nhấn enter để chọn"
                      deselectLabel="Nhấn enter để bỏ chọn"
                      :showLabels="false"
                      open-direction="bottom"
                      :options="vungkinhtetrongdiems"
                      :searchable="false"
                      :loading="is_loading"
                      :internal-search="false"
                      :options-limit="300"
                      :max-height="600"
                      :allowEmpty="true"
                      :tabindex="1"
                      required
                    >
                      <span slot="noResult">Không tìm thấy kết quả</span>
                    </multiselect>
                  </td>
                  <td>
                    <multiselect
                      v-model="form.luu_vuc_song"
                      label="ten"
                      track-by="id"
                      placeholder="Chọn lưu vực sông"
                      selectLabel="Nhấn enter để chọn"
                      deselectLabel="Nhấn enter để bỏ chọn"
                      :showLabels="false"
                      open-direction="bottom"
                      :options="luuvucsongs"
                      :searchable="false"
                      :loading="is_loading"
                      :internal-search="false"
                      :options-limit="300"
                      :max-height="600"
                      :allowEmpty="true"
                      :tabindex="1"
                      required
                    >
                      <span slot="noResult">Không tìm thấy kết quả</span>
                    </multiselect>
                  </td>
                  <td>
                    <input
                      class="form-control"
                      v-model="form.kinh_do"
                      placeholder="Kinh độ"
                    />
                  </td>
                  <td>
                    <input
                      class="form-control"
                      v-model="form.vi_do"
                      placeholder="Vĩ độ"
                    />
                  </td>
                  <td style="width: 5%">
                    <a class="btn btn-flat" @click="addTinhThanh()"
                      ><i class="fa fa-plus"></i> Thêm</a
                    >
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <modal-component
      width="450px"
      v-model="showModel"
      center
      @submit="deleteTinhThanh()"
      :title="'Xóa loại hình có nguy cơ ô nhiễm'"
    >
      <div>
        Bạn có chắc chắn muốn xóa <b>{{ tinhThanh.ten }}</b> không ?
      </div>
    </modal-component>
  </div>
</template>

<script>
import * as env from "../../env.js";
import MessageService from "../../services/MessageService";

import Multiselect from "vue-multiselect";
export default {
  components: { Multiselect },
  data: function () {
    return {
      data: [],
      luuvucsongs: [],
      tinhThanh: {},
      vungkinhtetrongdiems: [],
      miens: [],
      is_loading: false,
      erros: {},
      thoi_gian_dong_bo: null,
      load_thoi_gian: true,
      thoi_gian: "",
      showModel: false,
      dataChuaDongBo: [],
      searchdata: null,
      tinhthanh: [],
      tabKhac: null,
      trangThaiDongBo: false,
      error_add_tinh_thanh_ma: null,
      error_add_tinh_thanh_ten: null,
      form: {},
    };
  },
  props: ["usersystem"],
  mounted() {
    this.is_loading = true;
    this.getThoiGianDongBo();
    this.getData();
    this.getTrangThaiDongBo();
    // Promise.all([
    //   axios.get(env.endpointhttp + "luuvucsongs"),
    //   axios.get(env.endpointhttp + "vungkinhtetrongdiems"),
    //   axios.get(env.endpointhttp + "tinhthanhs"),
    //   axios.get(env.endpointhttp + "miens")
    // ]).then(datas => {
    //   this.is_loading = false;
    //   this.luuvucsongs = datas[0].data.result;
    //   this.vungkinhtetrongdiems = datas[1].data.result;
    //   this.data = datas[2].data.result;
    //   this.miens = datas[3].data.result;
    // });
  },
  methods: {
    searchData() {
      if (this.searchdata) {
        this.data = this.tinhthanh.filter(
          (el) =>
            el.trang_thai_dong_bo == "da_dong_bo" &&
            el.ten.toUpperCase().search(this.searchdata.toUpperCase()) !== -1
        );
      } else {
        this.data = this.tinhthanh.filter(
          (el) => el.trang_thai_dong_bo == "da_dong_bo"
        );
      }
    },
    searchDatachuadongbo() {
      if (this.searchdata) {
        this.dataChuaDongBo = this.tinhthanh.filter(
          (el) =>
            el.trang_thai_dong_bo !== "da_dong_bo" &&
            el.ten.toUpperCase().search(this.searchdata.toUpperCase()) !== -1
        );
      } else {
        this.dataChuaDongBo = this.tinhthanh.filter(
          (el) => el.trang_thai_dong_bo !== "da_dong_bo"
        );
      }
    },
    getData() {
      Promise.all([
        axios.get(env.endpointhttp + "luuvucsongs"),
        axios.get(env.endpointhttp + "vungkinhtetrongdiems"),
        axios.get(env.endpointhttp + "tinhthanhs", {
          params: {
            search: this.searchdata,
          },
        }),
        axios.get(env.endpointhttp + "miens"),
      ]).then((datas) => {
        this.is_loading = false;
        this.luuvucsongs = datas[0].data.result;
        this.vungkinhtetrongdiems = datas[1].data.result;
        this.tinhthanh = datas[2].data.result;
        this.data = this.tinhthanh.filter(
          (el) => el.trang_thai_dong_bo == "da_dong_bo"
        );
        this.dataChuaDongBo = this.tinhthanh.filter(
          (el) => el.trang_thai_dong_bo != "da_dong_bo"
        );
        this.tabKhac = this.dataChuaDongBo;
        console.log("this.tabKhac", this.tabKhac);

        this.miens = datas[3].data.result;
      });
    },
    getThoiGianDongBo() {
      axios.get(env.endpointhttp + "inthoigian/tinh_thanh").then((response) => {
        this.thoi_gian = response.data;
        this.thoi_gian_dong_bo =
          new Date(response.data.updated_at).getHours() +
          ":" +
          new Date(response.data.updated_at).getMinutes() +
          ":" +
          new Date(response.data.updated_at).getSeconds() +
          " " +
          new Date(response.data.updated_at).toLocaleDateString("en-TT");
        //this.thoi_gian_dong_bo=response.data[0].updated_at
        this.load_thoi_gian = true;
      });
    },
    update(item, index) {
      console.log("update -> item", item);
      if (
        this.usersystem.role_code == "admin" ||
        this.usersystem.role_code == "adminvaquanlydanhmuc"
      ) {
        this.erros[item.ma] = "";
        this.$set(this.erros, item.ma, this.checkData(item));
        if (this.erros[item.ma]) return;
        item.vung_kinh_te_trong_diem_id = item.vung_kinh_te_trong_diem
          ? item.vung_kinh_te_trong_diem.id
          : null;
        item.mien_id = item.mien ? item.mien.id : null;
        item.luu_vuc_song_id = item.luu_vuc_song ? item.luu_vuc_song.id : null;
        axios
          .put(env.endpointhttp + "tinhthanhs/" + item.id, item)
          .then((response) => {
            MessageService.showSuccessMessage("Cập nhật thành công");
          });
      }
    },
    checkData(data) {
      if (!data.ten || !data.mien || !data.mien.ten)
        return "Lỗi chưa nhập trường bắt buộc";
    },
    showConfirmDelete(tinhThanh) {
      this.showModel = true;
      this.tinhThanh = tinhThanh;
    },
    deleteTinhThanh: function () {
      axios
        .delete(env.endpointhttp + "tinhthanh/" + this.tinhThanh.id)
        .then((response) => {
          this.showModel = false;
          MessageService.showSuccessMessage("Xóa thành công");
          this.getData();
        })
        .catch((error) => {
          MessageService.showWarningMessage(
            error.response && error.response.data
              ? error.response.data.message
              : "Không thể xóa"
          );
          this.showModel = false;
        });
    },
    dongbodulieu() {
      this.load_thoi_gian = false;
      axios
        .get(env.endpointhttp + "moitruongquocgia/tinhthanh")
        .then((response) => {
          this.getData();
          this.getThoiGianDongBo();
          MessageService.showSuccessMessage("Đồng bộ thành công");
        });
    },
    getTrangThaiDongBo() {
      axios
        .get(env.endpointhttp + "dongbocsdl/getTrangThaiDongBo")
        .then((response) => {
          this.trangThaiDongBo = response.data.data[0].trang_thai;
        });
    },
    addTinhThanh: function () {
      this.error_add_tinh_thanh_ma = "";
      this.error_add_tinh_thanh_ten = "";

      if (
        this.form.ma == "" ||
        this.form.ma == null ||
        this.form.ten == "" ||
        this.form.ten == null
      ) {
        if (this.form.ten == "" || this.form.ten == null) {
          this.error_add_tinh_thanh_ten = "Chưa nhập tên.";
        }
        if (this.form.ma == "" || this.form.ma == null) {
          this.error_add_tinh_thanh_ma = "Chưa nhập mã.";
        }
      } else {
        this.error_add_loai_co_so_ma == "";
        this.tinhthanh.forEach((item) => {
          if (this.form.ma == item.ma) {
            this.error_add_tinh_thanh_ma = "Mã đã có, vui lòng nhập mã khác";
          }
        });

        if (
          this.error_add_tinh_thanh_ma == "" &&
          this.error_add_tinh_thanh_ten == ""
        ) {
          this.form = {
            ...this.form,
            luu_vuc_song_id: this.form.luu_vuc_song
              ? this.form.luu_vuc_song.id
              : "",
            vung_kinh_te_trong_diem_id: this.form.vung_kinh_te_trong_diem
              ? this.form.vung_kinh_te_trong_diem.id
              : "",
            mien_id: this.form.mien ? this.form.mien.id : "",
          };

          axios.post(env.endpointhttp + "tinhthanhs", this.form).then(() => {
            this.getData();
            this.form = {};
            MessageService.showSuccessMessage("Thêm mới thành công");
          });
        }
      }
    },
  },
};
</script>
