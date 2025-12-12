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
                placeholder="Tìm kiếm tên lưu vực sông"
                v-on:keyup.enter="searchdongbo()"
              />
              <span
                class="input-group-addon"
                style="cursor: pointer"
                @click="searchdongbo()"
              >
                <i class="fa fa-search"></i>
              </span>
            </div>
          </div>
          <table class="table table-hover">
            <thead style="width: 100%">
              <tr class="row-table-header">
                <th style="width: 5%">STT</th>
                <th style="width: 15%">Tên</th>
                <th style="width: 30%">Tỉnh thành</th>
                <th style="width: 10%; text-align: center">Chiều dài (Km)</th>
                <th style="width: 10%; text-align: center">Diện tích (Km2)</th>
                <th style="width: 30%">Mô Tả</th>
              </tr>
            </thead>
            <tbody style="width: 100%">
              <tr v-for="(item, index) in dataDongBo" :key="index">
                <td style="width: 5%">{{ index + 1 }}</td>
                <td style="width: 15%">
                  {{ item.ten }}
                </td>
                <td style="width: 30%">
                  {{ item.tinhThanh }}
                </td>
                <td style="width: 10%; text-align: center">
                  {{ item.chieu_dai }}
                </td>
                <td style="width: 10%; text-align: center">
                  {{ item.dien_tich }}
                </td>
                <td style="width: 30%">
                  {{ item.mo_ta }}
                </td>
              </tr>
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
                placeholder="Tìm kiếm tên lưu vực sông"
                v-on:keyup.enter="searchchuadongbo()"
              />
              <span
                class="input-group-addon"
                style="cursor: pointer"
                @click="searchchuadongbo()"
              >
                <i class="fa fa-search"></i>
              </span>
            </div>
          </div>
          <table class="table table-hover">
            <thead style="width: 100%">
              <tr class="row-table-header">
                <th style="width: 5%">STT</th>
                <th style="width: 15%">Tên</th>
                <th style="width: 30%; text-align: center">Tỉnh thành</th>
                <th style="width: 10%; text-align: center">Chiều dài (Km)</th>
                <th style="width: 10%; text-align: center">Diện tích (Km2)</th>
                <th style="width: 25%">Mô Tả</th>
                <th style="width: 5%">Xóa</th>
              </tr>
            </thead>
            <tbody style="width: 100%">
              <tr
                v-for="(item, index) in dataChuaDongBo"
                :key="index"
                style="min-width: 100%"
              >
                <td style="width: 5%">{{ index + 1 }}</td>
                <td style="width: 15%">
                  <textarea
                    class="form-control"
                    v-model="item.ten"
                    :disabled="!isEditable"
                    @change="updateLuuVucSong(item)"
                    rows="2"
                  >
                  </textarea>
                </td>
                <td style="width: 30%; text-align: center">
                  <multiselect
                    v-model="item.tinhThanhSelected"
                    :options="tinhThanh"
                    label="ten"
                    track-by="id"
                    placeholder="Gõ tên Tỉnh thành"
                    selectLabel="Nhấn enter để chọn"
                    deselectLabel="Nhấn enter để bỏ chọn"
                    selectedLabel="Đã chọn"
                    :multiple="true"
                    :searchable="true"
                    :close-on-select="true"
                    :clear-on-select="false"
                    :hide-selected="false"
                    :max-height="300"
                    open-direction="bottom"
                    :disabled="!isEditable"
                    @input="updateLuuVucSong(item)"
                  >
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                </td>
                <td style="width: 10%; text-align: center">
                  <input
                    type="number"
                    class="form-control text-center"
                    v-model="item.chieu_dai"
                    :disabled="!isEditable"
                    @change="updateLuuVucSong(item)"
                  />
                </td>
                <td style="width: 10%; text-align: center">
                  <input
                    type="number"
                    class="form-control text-center"
                    v-model="item.dien_tich"
                    :disabled="!isEditable"
                    @change="updateLuuVucSong(item)"
                  />
                </td>
                <td style="width: 25%">
                  <textarea
                    class="form-control"
                    v-model="item.mo_ta"
                    :disabled="!isEditable"
                    @change="updateLuuVucSong(item)"
                    rows="2"
                  >
                  </textarea>
                </td>
                <td style="width: 5%">
                  <a @click="showConfirmDelete(item)">
                    <i class="fa fa-trash-o btn"></i
                  ></a>
                </td>
              </tr>

              <!-- Dòng thêm mới -->
              <tr v-if="isEditable" style="min-width: 100%">
                <td style="width: 6%">
                  <textarea
                    class="form-control"
                    v-model="form.ma"
                    placeholder="Mã"
                    rows="2"
                  ></textarea>
                  <span
                    style="color: red"
                    v-text="error_add_luu_vuc_song_ma"
                  ></span>
                </td>
                <td style="width: 15%">
                  <textarea
                    class="form-control"
                    v-model="form.ten"
                    placeholder="Tên"
                    rows="2"
                  ></textarea>
                  <span
                    style="color: red"
                    v-text="error_add_luu_vuc_song_ten"
                  ></span>
                </td>
                <td style="width: 30%; text-align: center">
                  <multiselect
                    v-model="form.tinhThanh"
                    label="ten"
                    track-by="id"
                    placeholder="Gõ tên Tỉnh thành"
                    selectLabel="Nhấn enter để chọn"
                    deselectLabel="Nhấn enter để bỏ chọn"
                    selectedLabel="Đã chọn"
                    open-direction="top"
                    :options="tinhThanh"
                    :multiple="true"
                    :searchable="true"
                    :loading="is_loading"
                    :internal-search="true"
                    :clear-on-select="false"
                    :close-on-select="true"
                    :options-limit="300"
                    :limit="3"
                    :max-height="300"
                    :show-no-results="false"
                    :hide-selected="false"
                    :tabindex="1"
                    :clearOnSelect="true"
                  >
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                </td>
                <td style="width: 10%; text-align: center">
                  <input
                    type="number"
                    class="form-control text-center"
                    v-model="form.chieu_dai"
                    placeholder="Km"
                  />
                </td>
                <td style="width: 10%; text-align: center">
                  <input
                    type="number"
                    class="form-control text-center"
                    v-model="form.dien_tich"
                    placeholder="Km²"
                  />
                </td>
                <td style="width: 25%">
                  <textarea
                    class="form-control"
                    v-model="form.mo_ta"
                    placeholder="Mô tả"
                    rows="2"
                  ></textarea>
                </td>
                <td style="width: 5%">
                  <a class="btn btn-flat" @click="addLuuVucSong()"
                    ><i class="fa fa-plus"></i> Thêm</a
                  >
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <modal-component
      width="450px"
      v-model="showModel"
      center
      @submit="deleteLuuVucSong()"
      :title="'Xác nhận xóa'"
    >
      <div>
        Bạn có chắc chắn muốn xóa lưu vực sông
        {{ luuVuc ? luuVuc.ten : "" }} không?
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
      ten: null,
      ma: null,
      mo_ta: null,
      error_add_luu_vuc_song: null,
      thoi_gian_dong_bo: null,
      load_thoi_gian: true,
      thoi_gian: "",
      stt: null,
      dataDongBo: [],
      dataChuaDongBo: [],
      showModel: false,
      luuVuc: null,
      searchdata: null,
      tabKhac: null,
      trangThaiDongBo: false,
      form: {},
      tinhThanh: [],
      error_add_luu_vuc_song_ma: null,
      error_add_luu_vuc_song_ten: null,
    };
  },
  props: ["usersystem"],
  mounted() {
    this.getdata();
    this.getThoiGianDongBo();
    this.getTrangThaiDongBo();
    this.getTinhThanh();
  },
  computed: {
    isEditable() {
      return (
        this.usersystem.role_code == "admin" ||
        this.usersystem.role_code == "adminvaquanlydanhmuc"
      );
    },
  },
  methods: {
    searchdongbo() {
      if (this.searchdata) {
        this.dataDongBo = this.data.filter(
          (el) =>
            el.trang_thai_dong_bo == "da_dong_bo" &&
            el.ten.toUpperCase().search(this.searchdata.toUpperCase()) !== -1
        );
      } else {
        this.dataDongBo = this.data.filter(
          (el) => el.trang_thai_dong_bo == "da_dong_bo"
        );
      }
    },
    searchchuadongbo() {
      if (this.searchdata) {
        this.dataChuaDongBo = this.data.filter(
          (el) =>
            el.trang_thai_dong_bo !== "da_dong_bo" &&
            el.ten.toUpperCase().search(this.searchdata.toUpperCase()) !== -1
        );
      } else {
        this.dataChuaDongBo = this.data.filter(
          (el) => el.trang_thai_dong_bo !== "da_dong_bo"
        );
      }
    },
    getdata() {
      axios.get(env.endpointhttp + "luuvucsongs").then((response) => {
        this.data = response.data.result;
        this.data = this.data.map((item) => {
          const selectedFrom =
            item.tinh_thanh_news && item.tinh_thanh_news.length
              ? item.tinh_thanh_news
              : item.tinhthanhs || [];

          const tinhThanhSelected = selectedFrom.map((p) => ({
            id: p.id,
            ten: p.ten,
          }));

          let tinhThanh = "";
          selectedFrom.forEach((el) => {
            tinhThanh = `${tinhThanh} ${el.ten}, `;
          });

          return { ...item, tinhThanh, tinhThanhSelected };
        });
        this.dataDongBo = this.data.filter(
          (el) => el.trang_thai_dong_bo == "da_dong_bo"
        );
        this.dataChuaDongBo = this.data.filter(
          (el) => el.trang_thai_dong_bo !== "da_dong_bo"
        );
        this.tabKhac = this.dataChuaDongBo;
      });
    },
    getThoiGianDongBo() {
      axios
        .get(env.endpointhttp + "inthoigian/luu_vuc_song")
        .then((response) => {
          this.thoi_gian = response.data;
          this.thoi_gian_dong_bo =
            new Date(response.data.updated_at).getHours() +
            ":" +
            new Date(response.data.updated_at).getMinutes() +
            ":" +
            new Date(response.data.updated_at).getSeconds() +
            " " +
            new Date(response.data.updated_at).toLocaleDateString("en-TT");
          this.load_thoi_gian = true;
        });
    },
    getTinhThanh() {
      axios.get(env.endpointhttp + "tinhthanhs").then((response) => {
        this.tinhThanh = (response.data.result || []).map((x) => ({
          id: x.id,
          ten: x.ten,
        }));
      });
    },
    updateLuuVucSong(item) {
      const payload = {
        ten: item.ten,
        mo_ta: item.mo_ta,
        tinhThanh: (item.tinhThanhSelected || []).map((x) => ({ id: x.id })),
        chieu_dai: item.chieu_dai,
        dien_tich: item.dien_tich,
      };

      axios
        .put(env.endpointhttp + "luuvucsongs/" + item.id, payload)
        .then(() => {
          MessageService.showSuccessMessage("Cập nhật thành công");
        })
        .catch(() => {
          MessageService.showWarningMessage("Cập nhật thất bại");
        });
    },
    showConfirmDelete(luuVuc) {
      this.showModel = true;
      this.luuVuc = luuVuc;
    },
    deleteLuuVucSong: function () {
      axios
        .delete(env.endpointhttp + "luuvucsongs/" + this.luuVuc.id)
        .then((response) => {
          this.showModel = false;
          MessageService.showSuccessMessage("Xóa thành công");
          this.getdata();
        });
    },
    addLuuVucSong: function () {
      this.error_add_luu_vuc_song_ma = "";
      this.error_add_luu_vuc_song_ten = "";
      // if (!this.form.ten || this.form.ten.trim() === "") {
      //   MessageService.showWarningMessage("Vui lòng nhập tên lưu vực sông");
      //   return;
      // }
      if (
        this.form.ten == "" ||
        this.form.ten == null ||
        this.form.ma == "" ||
        this.form.ma == null
      ) {
        if (this.form.ten == "" || this.form.ten == null) {
          this.error_add_luu_vuc_song_ten = "Chưa nhập tên.";
        }
        if (this.form.ma == "" || this.form.ma == null) {
          this.error_add_luu_vuc_song_ma = "Chưa nhập mã.";
        }
      } else {
        if (this.error_add_luu_vuc_song_ma == "" && this.error_add_luu_vuc_song_ten == "") {
          axios
            .post(env.endpointhttp + "luuvucsongs", this.form)
            .then(() => {
              this.getdata();
              this.form = {};
              MessageService.showSuccessMessage("Thêm mới thành công");
            })
            .catch(() => {
              MessageService.showWarningMessage("Thêm thất bại");
            });
        }
      }
    },
    dongbodulieu() {
      this.load_thoi_gian = false;
      axios
        .get(env.endpointhttp + "moitruongquocgia/luuvucsong")
        .then((response) => {
          this.getdata();
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
  },
};
</script>
<style scoped>
thead,
tfoot {
  display: table;
}

tbody {
  display: block;
  max-height: 373px;
  overflow-y: scroll;
}
</style>
