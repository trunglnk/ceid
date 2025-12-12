<template>
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <div class="row">
        <div class="col-md-9">
          <h2 class="page-header-button-header title_master_form">
            Chi tiết quyết định thành lập đoàn thanh tra
          </h2>
        </div>
        <div class="col-md-3">
          <button type="submit" id="onsubmit" class="btn bg-olive btn-flat pull-right margin-top margin-left"
            @click="sendData" :disabled="isDisabled" tabindex="13" v-if="
              usersystem.role_code == 'admin' ||
              usersystem.role_code == 'nhanvientrungtam' ||
              usersystem.role_code == 'adminvaquanlydanhmuc'
            ">
            <i class="fa fa-send"></i> Gửi lên MTQG
          </button>

          <button type="submit" id="onsubmit" class="btn bg-olive btn-flat pull-right margin-top margin-left"
            @click="update" :disabled="isDisabled" tabindex="13" v-if="
              usersystem.role_code == 'admin' ||
              usersystem.role_code == 'nhanvientrungtam' ||
              usersystem.role_code == 'adminvaquanlydanhmuc'
            ">
            <i class="fa fa-check"></i> Cập nhật
          </button>
          <a href="/doanthanhtra" class="btn btn-default btn-flat pull-right margin-top">
            <i class="fa fa-undo"></i> Trở lại
          </a>
        </div>
      </div>
      <div class="row" style="margin-top: 10px; margin-bottom: 10px;float: right; margin-right: 8px;">
        <div v-if="trang_thai_dong_bo === 'da_dong_bo'">
          Đã đồng bộ lên MTQG lúc: {{ thoi_gian_dong_bo ? thoi_gian_dong_bo : thoi_gian_dong_bo_chung }}
        </div>
        <div v-else>Chưa đồng bộ lên MTQG</div>
      </div>

      <div class="row">
        <div class="col-sm-12 col-lg-12">
          <div class="block-multiple-rows padding-left padding-right">
            <div class="row">
              <div class="col-lg-12" style="margin-top: 5px">
                <label>THÔNG TIN CHUNG</label>
                <hr style="margin-top: 7px; margin-bottom: 7px" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 col-lg-6">
                <label class="control-label">Tên quyết định</label>
                <textarea type="text" class="form-control" v-model="ten" v-validate="'required'" name="ten" autofocus
                  placeholder="Tên QĐ thanh tra" tabindex="1" />
                <span v-show="errors.has('ten')" class="help is-danger" style="color: red">Tên QĐ thanh tra không thể
                  bỏ trống</span>
              </div>

              <div class="col-md-6 col-lg-3">
                <label class="control-label">Mã định danh</label>
                <input type="text" class="form-control" v-model="ma_dinh_danh" autofocus
                  placeholder="<CoQuanThanhTra>-<NamThucHien>-<SoDoan>.TTKT" disabled tabindex="1" />
              </div>

              <div class="col-md-6 col-lg-3">
                <label class="control-label">Số QĐ thành lập đoàn thanh tra<span style="color: red">*</span></label>
                <input type="text" class="form-control" name="soqd" v-model="soqd" autofocus
                  :disabled="this.value.ma_dinh_danh ? true : false" placeholder="Số QĐ thành lập đoàn thanh tra"
                  tabindex="1" v-validate="'required'" />
                <span v-show="errors.has('soqd')" class="help is-danger" style="color: red">Số quyết định thành lập không
                  thể bỏ trống</span>
              </div>
            </div>
            <div class="row mt-4">
              <div class="col-md-6 col-lg-3">
                <label>Cơ quan ký QĐ thành lập đoàn thanh tra<span style="color: red">*</span></label>
                <multiselect v-model="coquanky" label="ten" track-by="id" placeholder="Gõ tên cơ ký QĐ thành lập đoàn"
                  :disabled="this.value.ma_dinh_danh ? true : false" selectLabel="Nhấn enter để chọn"
                  deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn" open-direction="bottom"
                  :options="this.coquantochucs" :multiple="false" :searchable="true" :internal-search="false"
                  :clear-on-select="false" :close-on-select="true" :max-height="600" :show-no-results="false"
                  :hide-selected="false" :tabindex="1" :clearOnSelect="true" v-validate="'required'" name="coquanky">
                  <span slot="noResult">Không tìm thấy kết quả</span>
                </multiselect>
                <span class="help-block" v-if="errors.coquanky">
                  <strong>{{ errors.coquanky }}</strong>
                </span>
                <span v-show="errors.has('coquanky')" class="help is-danger" style="color: red">Cơ quan ký quyết định
                  không thể bỏ trống</span>
              </div>
              <div class="col-md-6 col-lg-3">
                <label>Cơ quan thực hiện<span style="color: red">*</span></label>
                <multiselect v-model="coquantt" label="ten" track-by="id" placeholder="Gõ tên cơ quan thực hiện"
                  selectLabel="Nhấn enter để chọn" deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn"
                  open-direction="bottom" :options="this.coquantochucs" :multiple="false" :searchable="true"
                  :internal-search="false" :clear-on-select="false" :close-on-select="true" :max-height="600"
                  :show-no-results="false" :hide-selected="false" :tabindex="1" :clearOnSelect="true"
                  v-validate="'required'" name="coquanthuchien">
                  <span slot="noResult">Không tìm thấy kết quả</span>
                </multiselect>
                <span v-show="errors.has('coquanthuchien')" class="help is-danger" style="color: red">Cơ quan thực hiện
                  không thể bỏ trống</span>
              </div>

              <div class="col-md-6 col-lg-3">
                <label>Năm kế hoạch<span style="color: red">*</span></label>
                <input type="text" class="form-control" v-model="namKeHoach" placeholder="Năm thực hiện"
                  :disabled="this.value.ma_dinh_danh ? true : false" v-validate="'required'" name="namKeHoach" />
                <span v-show="errors.has('namKeHoach')" class="help is-danger" style="color: red">Năm thực hiện không thể
                  bỏ
                  trống</span>
              </div>

            </div>
            <div class="row mt-4">
              <div class="col-md-6 col-lg-3">
                <label>Hình thức thanh tra<span style="color: red">*</span></label>
                <multiselect v-model="chedo" label="ten" track-by="id" placeholder="Chọn hình thức thanh tra"
                  selectLabel="Nhấn enter để chọn" deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn"
                  open-direction="bottom" :options="this.chedothanhtras" :multiple="false" :close-on-select="true"
                  :tabindex="1" v-validate="'required'" name="hinhthuc">
                  <span slot="noResult">Không tìm thấy kết quả</span>
                </multiselect>
                <span v-show="errors.has('hinhthuc')" class="help is-danger" style="color: red">Hình thức thanh tra không
                  thể bỏ trống</span>
              </div>
              <div class="col-md-6 col-lg-3">
                <label class="control-label">Thời gian ký QĐ thành lập đoàn thanh tra</label>
                <date-picker v-model="ngayky" placeholder="" tabindex="17" :config="optionsDate"></date-picker>
              </div>
            </div>
            <hr />
            <div class="row">
              <div class="col-md-12 col-lg-12">
                <label class="control-label">Tài liệu đính kèm</label>
                <div class="row" v-if="
                  usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'nhanvientrungtam' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
                ">
                  <div class="col-md-3 btn btn-file btn-flat margin text">
                    <i class="fa fa-paperclip"></i> Chọn tập tin (Không quá
                    30MB)
                    <input type="file" id="file" ref="file" v-on:change="handleFileUpload()" />
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <table class="table table-hover table-responsive" v-if="attachments.length > 0">
                      <tbody>
                        <tr class="row-table-header">
                          <th>Tên file</th>
                          <th style="width: 5%; text-align: center"></th>
                          <th style="width: 5%; text-align: center" v-if="
                            usersystem.role_code == 'admin' ||
                            usersystem.role_code == 'nhanvientrungtam' ||
                            usersystem.role_code == 'adminvaquanlydanhmuc'
                          "></th>
                        </tr>
                        <tr v-for="(item, index) in attachments" :key="index">
                          <td>{{ item.name }}</td>
                          <td align="center">
                            <a @click="dowloadFile(item.file_id)">
                              <i class="fa fa-download btn"></i>
                            </a>
                          </td>
                          <td align="center" v-if="
                            usersystem.role_code == 'admin' ||
                            usersystem.role_code == 'nhanvientrungtam' ||
                            usersystem.role_code == 'adminvaquanlydanhmuc'
                          ">
                            <a @click="deleteFile(item.file_id, index)">
                              <i class="fa fa-trash-o btn"></i>
                            </a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <p v-else class="text-muted">Không có tài liệu đính kèm</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-lg-12">
          <div class="row block-multiple-rows">
            <div class="col-md-12">
              <label>DANH SÁCH TỔ CHỨC THANH TRA</label>
              <p class="text-muted">
                (Thuộc quyết định thành lập đoàn thanh tra)
              </p>
              <hr style="margin-top: 7px; margin-bottom: 7px" />
              <table v-if="temp.length > 0" class="table table-hover table-responsive">
                <tbody>
                  <tr class="row-table-header">
                    <th style="text-align: center" width="5%">STT</th>
                    <th width="30%">Tên</th>
                    <th width="30%">Địa chỉ</th>
                  </tr>
                  <tr v-for="(item, index) of temp" :key="index">
                    <td align="center" v-text="index + 1"></td>
                    <td>
                      <a :href="'/cososanxuat/showformupdate/' + item.id" v-text="item.ten" target="_blank"></a>
                    </td>
                    <td v-text="item.dia_chi_lien_he"></td>
                  </tr>
                  <tr v-if="temp.length < 0">
                    <td colspan="4" align="center">-----------------</td>
                  </tr>
                </tbody>
              </table>
              <div class="row" v-if="temp.length > 0">
                <div class="col-md-5">
                  <div style="margin: 20px 0px 0px 0px" v-text="'Tổng số ' + this.chitiet.total + ' tổ chức'"></div>
                </div>
                <div class="col-md-7">
                  <paginate v-model="page1" :page-count="this.count2" :page-range="3" :margin-pages="2"
                    :click-handler="clickCallback2" :prev-text="'‹‹'" :next-text="'››'"
                    :container-class="'pagination margin-top pull-right'" :page-class="'page-item'">
                  </paginate>
                </div>
              </div>
              <div class="row" v-else>
                <div class="col-md-12 col-lg-12">
                  <p class="text-muted drag-text">
                    Không có tổ chức thuộc quyết định thành lập đoàn thanh tra
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="box-footer box-footer-form">
        <button type="submit" id="onsubmit" class="btn bg-olive btn-flat pull-right" @click="update"
          :disabled="isDisabled" tabindex="13" v-if="
            usersystem.role_code == 'admin' ||
            usersystem.role_code == 'nhanvientrungtam' ||
            usersystem.role_code == 'adminvaquanlydanhmuc'
          ">
          <i class="fa fa-check"></i> Cập nhật
        </button>
        <a :href="'/quyetdinhthanhlapdoan/excel/' + this.value.id"
          class="btn btn-default btn-flat pull-right margin-right" tabindex="1">
          <i class="fa fa-file-excel-o"></i> Xuất Excel
        </a>
        <a href="/doanthanhtra" id="btnback" class="btn btn-default btn-flat" tabindex="14">
          <i class="fa fa-undo"></i> Trở lại
        </a>
      </div>
    </div>
  </div>
</template>

<script>
import * as env from "../env.js";
import datePicker from "vue-bootstrap-datetimepicker";
import { en, vi } from "vuejs-datepicker/dist/locale";
import Multiselect from "vue-multiselect";
import { ValidationProvider } from "vee-validate";
import MessageService from "../services/MessageService";

export default {
  components: {
    datePicker,
    Multiselect,
    ValidationProvider,
  },
  props: ["value", "chitiet", "usersystem"],
  data: function () {
    return {
      optionsDate: {
        format: "DD/MM/YYYY",
        useCurrent: false,
        locale: "vi",
      },
      en: en,
      vi: vi,
      ma_dinh_danh: null,
      ten: null,
      temp: [],
      count2: 0,
      attachments: [],
      coquantochucs: [],
      chedothanhtras: [],
      soqd: null,
      coquanky: null,
      chedo: null,
      ngayky: null,
      coquantt: null,
      thoigian: null,
      page1: 1,
      isLoading: true,
      namKeHoach: null,
      isDisabled: true,
      thoi_gian_dong_bo_chung: null,
      thoi_gian_dong_bo: null,
      trang_thai_dong_bo: null
    };
  },
  watch: {
    coquanky() {
      this.taoMaDinhDanh()
    },
    namKeHoach() {
      this.taoMaDinhDanh()
    },
    soqd() {
      this.taoMaDinhDanh()
    }
  },
  methods: {
    sendData() {
      this.$validator.validateAll().then((validate) => {
        if (validate) {
          this.isLoading = true;
          const coSoSanXuat = []
          this.chitiet.data.forEach(element => {
            if (element.co_so_san_xuats) {
              element.co_so_san_xuats.forEach(item => {
                coSoSanXuat.push({MaDinhDanh: item.ma_dinh_danh, TenGoi: item.ten, ChuDauTu: {MaDinhDanh: element.chu_dau_tu.ma_dinh_danh, TenGoi: element.chu_dau_tu.ten}})
              })
            }
          });

          const data = {
            DonViChuTri: {
              MaDinhDanh: this.coquanky.ma_dinh_danh,
              type: "T_CoQuanDonVi",
              TenGoi: this.coquanky.ten
            },
            SoHieuVanBan: this.soqd,
            type: "T_DoanThanhTraKiemTra",
            NamKeHoach: this.namKeHoach,
            NgayQuyetDinh: this.ngayky,
            CoQuanQuyetDinh: {
              MaDinhDanh: this.coquantt.ma_dinh_danh,
              type: "T_CoQuanDonVi",
              TenGoi: this.coquantt.ten
            },
            MaDinhDanh: this.ma_dinh_danh,
            TenGoi: this.ten,
            DuAnCoSo: coSoSanXuat
          }
          axios
            .post(env.endpointhttp + "moitruongquocgia/create/quyetdinh", { data: JSON.stringify(data), id: this.value.id })
            .then((response) => {
              MessageService.showSuccessMessage("Đồng bộ thành công");
              setTimeout(() => {
                window.location.href = "/doanthanhtra/update/" + this.value.id;
              }, 2500)
            })
            .catch((error) => {
              console.log(error);
              MessageService.showWarningMessage("Gửi dữ liệu không thành công");
            })
            .finally(() => {
              this.isLoading = false;
            });
        }
      });
    },
    formatDateTime(dateTime) {
      try {
        const time = new Date(dateTime)
        if (!dateTime) {
          return ''
        }
        return time.getHours() +
          ":" +
          time.getMinutes() +
          ":" +
          time.getSeconds() +
          " " +
          time.toLocaleDateString("en-TT");
      } catch (error) {
        return ''
      }

    },
    clickCallback2(pageNum1) {
      this.page1 = pageNum1;
      this.temp = [];
      axios
        .get(
          env.endpointhttp +
          "cosothanhtra/" +
          this.value.id +
          "?page=" +
          this.page1
        )
        .then((response) => {
          var i = 0;
          this.temp = response.data.result.data;

          this.count2 = response.data.result.last_page;
        });
    },
    taoMaDinhDanh() {
      let coQuan = this.coquanky && this.coquanky.ma_dinh_danh ? this.coquanky.ma_dinh_danh : '<CoQuanThanhTra>';
      let namThucHien = this.namKeHoach ? this.namKeHoach : '<NamThucHien>';
      let soDoan = '<SoDoan>';
      if (this.soqd) {
        let soQdArray = this.soqd.split('/');
        soDoan = soQdArray[0];
      }
      this.ma_dinh_danh = `${coQuan}-${namThucHien}-${soDoan}.TTKT`
    },
    dowloadFile(file_id) {
      window.open("/file/download/" + file_id);
    },
    deleteFile(file_id, index) {
      axios
        .delete(env.endpointhttp + "file/delete/" + file_id)
        .then((response) => {
          this.attachments.splice(index, 1);
        });
    },
    handleFileUpload() {
      let formData = new FormData();
      formData.append("file", this.$refs.file.files[0]);
      axios
        .post("/file/add", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          this.attachments.push(response.data.result);
        })
        .catch(function () {
          console.log("FAILURE!!");
        });
    },
    getThoiGianDongBo() {
      axios.get(env.endpointhttp + "inthoigian/quyet_dinh_thanh_lap").then((response) => {
        this.thoi_gian_dong_bo_chung = this.formatDateTime(response.data.updated_at)
      })
    },
    update: function () {
      this.$validator.validateAll().then((validate) => {
        if (validate) {
          this.isLoading = true;
          axios
            .put(env.endpointhttp + "doanthanhtra/update/" + this.value.id, {
              attachments: this.attachments,
              thanhtra: {
                so_quyet_dinh: this.soqd,
                ten: this.ten,
                ma_dinh_danh: this.ma_dinh_danh,
                nam_ke_hoach: this.namKeHoach,
                co_quan_quyet_dinh: this.coquanky.id,
                ngay_ra_quyet_dinh: this.ngayky,
                thoi_gian_thong_bao: this.thoigian,
                co_quan_thuc_hien: this.coquantt.id,
                hinh_thuc_thanh_tra: this.chedo.id,
              },
            })
            .then((response) => {
              window.location.href = "/doanthanhtra/update/" + this.value.id;
            })
            .catch((error) => {
              console.log(error);
            })
            .finally(() => {
              this.isLoading = false;
            });
        }
      });
    },
  },
  mounted: function () {
    console.log(this.chitiet)
    this.isLoading = true;
    this.isDisabled = true;
    this.temp = this.chitiet.data;
    this.attachments = this.value.attachments;
    this.soqd = this.value.so_quyet_dinh;
    this.ngayky = this.value.ngay_ra_quyet_dinh;
    this.thoigian = this.value.thoi_gian_thong_bao;
    this.count2 = this.chitiet.last_page;
    this.ma_dinh_danh = this.value.ma_dinh_danh
    this.ten = this.value.ten
    this.namKeHoach = this.value.nam_ke_hoach;
    this.getThoiGianDongBo();
    this.trang_thai_dong_bo = this.value.trang_thai_dong_bo_ve;
    this.thoi_gian_dong_bo = this.value.thoi_gian_dong_bo ? this.formatDateTime(this.value.thoi_gian_dong_bo) : null
    Promise.all([
      axios.get(env.endpointhttp + "chedothanhtras").then((response) => {
        this.chedothanhtras = response.data.result;
        this.chedo = this.chedothanhtras.find(
          (item) => item.id === this.value.hinh_thuc_thanh_tra
        );
      }),
      axios.get(env.endpointhttp + "loaihinhcosos").then((response) => {
        this.loaicosos = response.data.result;
      }),

      axios.get(env.endpointhttp + "tinhthanhs").then((response) => {
        this.tinhs = response.data.result;
      }),
      axios.get(env.endpointhttp + "quanhuyens").then((response) => {
        this.huyens = response.data.result;
        this.all_districts = response.data.result;
      }),

      axios.get(env.endpointhttp + "khucongnghieps").then((response) => {
        this.khucongnghieps = response.data.result;
      }),
      axios.get(env.endpointhttp + "coquantochucs").then((response) => {
        this.coquantochucs = response.data.result;
        this.coquanky = this.coquantochucs.find(
          (item) => item.id === this.value.co_quan_quyet_dinh
        );
        this.coquantt = this.coquantochucs.find(
          (item) => item.id === this.value.co_quan_thuc_hien
        );
      }),
    ]).then((response) => {
      this.isDisabled = false;
      this.isLoading = false;
    });
  },
};
</script>
