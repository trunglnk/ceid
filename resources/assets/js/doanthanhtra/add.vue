<template>
  <div class="row">
    <div class="col-lg-8 col-md-12">
      <div class="row">
        <div class="col-md-8">
          <h2 class="page-header-button-header title_master_form">
            Thêm mới quyết định thành lập đoàn thanh tra
          </h2>
        </div>
        <!-- <div class="col-md-4">
          <button
            type="submit"
            id="onsubmit"
            class="btn bg-olive btn-flat pull-right margin-top margin-left"
            @click="addQuyetDinhThanhLapDoanThanhTra"
            tabindex="13"
            :disabled="is_loading"
          >
            <i class="fa fa-plus"></i> Thêm mới
          </button>
          <a
            href="/doanthanhtra"
            class="btn btn-default btn-flat pull-right margin-top"
          >
            <i class="fa fa-undo"></i> Trở lại
          </a>
        </div> -->
      </div>
      <div class="row">
        <div class="col-md-12 col-lg-6">
          <div class="block-multiple-rows padding-left padding-right">
            <div class="row">
              <div class="col-lg-12" style="margin-top: 5px">
                <label>THÔNG TIN CHUNG</label>
                <hr style="margin-top: 7px; margin-bottom: 7px" />
              </div>
            </div>
            <div class="mt-1">
              <label class="control-label">
                Mã định danh
                <span style="color: red">*</span>
              </label>
              <input type="text" class="form-control" v-model="form_data.ma_dinh_danh" disabled autofocus
                placeholder="<CoQuanThanhTra>-<NamThucHien>-<SoDoan>.TTKT" v-validate="'required'" name="ten" />
            </div>
            <div class="mt-1">
              <label class="control-label">
                Tên quyết định
                <span style="color: red">*</span>
              </label>
              <input type="text" class="form-control" v-model="form_data.ten" autofocus
                placeholder="Tên QĐ thành lập đoàn thanh tra" v-validate="'required'" name="ten" />
              <span v-show="errors.has('ten')" class="help is-danger" style="color: red">Tên quyết định không thể
                bỏ trống</span>
            </div>
            <div class="mt-1">
              <label class="control-label">
                Số QĐ thành lập đoàn thanh tra
                <span style="color: red">*</span>
              </label>
              <input type="text" class="form-control" v-model="form_data.so_quyet_dinh"
                placeholder="Số QĐ thành lập đoàn thanh tra" v-validate="'required'" name="soquyetdinh" />
              <span v-show="errors.has('soquyetdinh')" class="help is-danger" style="color: red">Số quyết định không thể
                bỏ trống</span>
            </div>
            <div class="mt-1">
              <label class="control-label">
                Năm kế hoạch
                <span style="color: red">*</span>
              </label>
              <input type="text" class="form-control" v-model="form_data.namKeHoach" placeholder="Năm thực hiện"
                v-validate="'required'" name="namKeHoach" />
              <span v-show="errors.has('namKeHoach')" class="help is-danger" style="color: red">Năm thực hiện không thể bỏ
                trống</span>
            </div>
            <div class="mt-4">
              <label>
                Cơ quan ký quyết định thành lập đoàn thanh tra
                <span style="color: red">*</span>
              </label>
              <multiselect @input="taoMaDinhDanh" v-model="coquanky" label="ten" track-by="id" @search-change="asyncFindCoQuanToChuc"
                placeholder="Gõ tên cơ ký quyết định thành lập đoàn" selectLabel="Nhấn enter để chọn"
                deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn" open-direction="bottom"
                :options="this.coquantochucs" :multiple="false" :searchable="true" :internal-search="false"
                :clear-on-select="false" :close-on-select="true" :max-height="600" :show-no-results="false"
                :hide-selected="false" :tabindex="1" :clearOnSelect="true" v-validate="'required'" name="coquanky">
                <span slot="noResult">Không tìm thấy kết quả</span>
              </multiselect>
              <span v-show="errors.has('coquanky')" class="help is-danger" style="color: red">Cơ quan ký quyết định không
                thể bỏ trống</span>
            </div>
            <div class="mt-4">
              <label>
                Cơ quan thực hiện
                <span style="color: red">*</span>
              </label>
              <multiselect v-model="coquantt" label="ten" track-by="id" placeholder="Gõ tên cơ quan thực hiện"
                selectLabel="Nhấn enter để chọn" deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn"
                open-direction="bottom" :options="this.coquantochucs" :multiple="false" :searchable="true"
                :internal-search="false" :clear-on-select="false" :close-on-select="true" :max-height="600"
                :show-no-results="false" :hide-selected="false" :tabindex="1" :clearOnSelect="true"
                v-validate="'required'" @search-change="asyncFindCoQuanToChuc" name="coquanthuchien">
                <span slot="noResult">Không tìm thấy kết quả</span>
              </multiselect>
              <span v-show="errors.has('coquanthuchien')" class="help is-danger" style="color: red">Cơ quan thực hiện
                không thể bỏ trống</span>
            </div>
            <div class="mt-4">
              <label>
                Hình thức thanh tra
                <span style="color: red">*</span>
              </label>
              <multiselect v-model="chedo" label="ten" track-by="id" placeholder="Chọn hình thức thanh tra"
                selectLabel="Nhấn enter để chọn" deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn"
                open-direction="bottom" :options="this.chedothanhtras" :multiple="false" :close-on-select="true"
                :tabindex="1" v-validate="'required'" name="hinhthuc">
                <span slot="noResult">Không tìm thấy kết quả</span>
              </multiselect>
              <span v-show="errors.has('hinhthuc')" class="help is-danger" style="color: red">Hình thức thanh tra không
                thể bỏ trống</span>
            </div>
            <div class="mt-4">
              <label class="control-label">
                Thời gian ký QĐ thành lập đoàn thanh tra
                <span style="color: red">*</span>
              </label>
              <date-picker v-model="ngayky" tabindex="6" placeholder="DD/MM/YYYY" :config="optionsDate"
                v-validate="'required'" name="ngayky">
              </date-picker>
              <span v-show="errors.has('ngayky')" class="help is-danger" style="color: red">Số quyết định không thể bỏ
                trống</span>
            </div>
            <!-- <div class="mt-4">
              <label class="control-label">
                Ngày ký
                <span style="color: red">*</span>
              </label>
              <date-picker v-model="namKeHoach" tabindex="6" placeholder="DD/MM/YYYY" :config="optionsDate"
                v-validate="'required'" name="ngayky">
              </date-picker>
              <span v-show="errors.has('ngayky')" class="help is-danger" style="color: red">Số quyết định không thể bỏ
                trống</span>
            </div> -->
          </div>
        </div>
        <div class="col-md-12 col-lg-6">
          <div class="block-multiple-rows padding-left padding-right">
            <div class="mt-4">
              <label class="control-label">Tài liệu đính kèm</label>
              <div class="row">
                <div class="col-md-12 btn btn-file btn-flat">
                  <i class="fa fa-paperclip"></i> Chọn tập tin (Không quá 30MB)
                  <input type="file" id="file" ref="file" v-on:change="handleFileUpload()" />
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <table class="table table-hover table-responsive" v-if="attachments.length > 0">
                    <tbody>
                      <tr>
                        <th>Tên file</th>
                        <th style="width: 5%; text-align: center"></th>
                        <th style="width: 5%; text-align: center"></th>
                      </tr>
                      <tr v-for="(item, index) in attachments" :key="index">
                        <td>{{ item.name }}</td>
                        <td align="center">
                          <a @click="dowloadFile(item.file_id)">
                            <i class="fa fa-download btn"></i>
                          </a>
                        </td>
                        <td align="center">
                          <a @click="deleteFile(item.file_id, index)">
                            <i class="fa fa-trash-o btn"></i>
                          </a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="box-footer box-footer-form">
        <button type="submit" id="onsubmit" class="btn btn-flat bg-olive pull-right"
          @click="addQuyetDinhThanhLapDoanThanhTra" tabindex="13" :disabled="is_loading">
          <i class="fa fa-plus"></i> Thêm mới
        </button>
        <a href="/doanthanhtra" id="btnback" class="btn btn-default btn-flat" tabindex="14">
          <i class="fa fa-undo"></i> Trở lại
        </a>
      </div>
    </div>
  </div>
</template>

<script>
import * as env from "../env.js";
import Paginate from "vuejs-paginate";
import datePicker from "vue-bootstrap-datetimepicker";
import MessageService from "../services/MessageService";
import Multiselect from "vue-multiselect";
import ValidatorService from "../services/ValidatorService";
import { ValidationProvider } from "vee-validate";

export default {
  components: {
    Multiselect,
    datePicker,
    ValidationProvider,
  },
  data: function () {
    return {
      optionsDate: {
        format: "DD/MM/YYYY",
        useCurrent: false,
        locale: "vi",
      },
      attachments: [],
      coquantochucs: [],
      chedothanhtras: [],
      data: [],
      coquanky: null,
      chedo: null,
      ngayky: null,
      coquantt: null,
      cososanxuat: [],
      arrayCheck: [],
      is_loading: false,
      form_data: {
        so_quyet_dinh: null,
        ma_dinh_danh: null,
        ten: null
      },
    };
  },
  watch: {
    coQuan(val){
      this.taoMaDinhDanh()
    },
    "form_data.namKeHoach"(val){
      this.taoMaDinhDanh()
    },
    "form_data.so_quyet_dinh": function(val){
      this.taoMaDinhDanh()
    }
  },
  methods: {
    isInValidData() {
      ValidatorService.setNull(this.errors);
      var invalid = false;
      if (this.form_data.so_quyet_dinh == null) {
        this.errors.so_quyet_dinh = "Thiếu số quyết định";
        invalid = true;
      }
      if (this.coquanky == null) {
        this.errors.coquanky = "Thiếu cơ quan ký";
        invalid = true;
      }
      if (this.coquantt == null) {
        this.errors.coquantt = "Thiếu cơ quan thực hiện";
        invalid = true;
      }
      if (this.chedo == null) {
        this.errors.chedo = "Thiếu chế độ thanh tra";
        invalid = true;
      }
      if (this.ngayky == null) {
        this.errors.ngayky = "Thiếu ngày ký quyết định";
        invalid = true;
      }
      return invalid;
    },
    taoMaDinhDanh() {
      let coQuan = this.coquanky && this.coquanky.ma_dinh_danh ? this.coquanky.ma_dinh_danh : '<CoQuanThanhTra>';
      let namThucHien = this.form_data.namKeHoach ? this.form_data.namKeHoach : '<NamThucHien>';
      let soDoan = '<SoDoan>';
      if(this.form_data.so_quyet_dinh){
        let soQdArray =  this.form_data.so_quyet_dinh.split('/');
        soDoan = soQdArray[0];
      }
      this.form_data.ma_dinh_danh = `${coQuan}-${namThucHien}-${soDoan}.TTKT`
    },
    addQuyetDinhThanhLapDoanThanhTra: function () {
      this.$validator.validateAll().then((validate) => {
        if (validate) {
          this.is_loading = true;
          axios
            .post(env.endpointhttp + "doanthanhtra/add", {
              data: this.cososanxuat,
              thanhtra: {
                ten: this.form_data.ten,
                ma_dinh_danh: this.form_data.ma_dinh_danh,
                nam_ke_hoach: this.form_data.namKeHoach,
                so_quyet_dinh: this.form_data.so_quyet_dinh,
                co_quan_quyet_dinh: this.coquanky.id,
                ngay_ra_quyet_dinh: this.ngayky,
                co_quan_thuc_hien: this.coquantt.id,
                hinh_thuc_thanh_tra: this.chedo.id,
              },
              attachments: this.attachments,
            })
            .then((response) => {
              window.location.href = "/doanthanhtra";
              MessageService.showSuccessMessage("Thêm mới thành công");
            })
            .catch((error) => {
              console.log(error)
              MessageService.showWarningMessage("Thêm mới thất bại");
            })
            .finally(() => {
              this.is_loading = false;
            });
        }
      });

      // try {
      //   this.is_loading = true;
      //   if (this.isInValidData()) {
      //     this.is_loading = true;
      //     return;
      //   }
      //   axios
      //     .post(env.endpointhttp + "doanthanhtra/add", {
      //       data: this.cososanxuat,
      //       thanhtra: {
      //         so_quyet_dinh: this.form_data.so_quyet_dinh,
      //         co_quan_quyet_dinh: this.coquanky.id,
      //         ngay_ra_quyet_dinh: this.ngayky,
      //         co_quan_thuc_hien: this.coquantt.id,
      //         hinh_thuc_thanh_tra: this.chedo.id,
      //       },
      //       attachments: this.attachments,
      //     })
      //     .then((response) => {
      //       window.location.href = "/doanthanhtra";
      //     })
      //     .catch((error) => {
      //       if (error.response && error.response.data) {
      //         MessageService.showWarningMessage(error.response.data.message);
      //         var message = error.response.data.message;
      //         if (error.response.data.result) {
      //           var errors = error.response.data.result;
      //           for (var key in errors) {
      //             if (errors.hasOwnProperty(key)) {
      //               this.errors.key = errors[key][0];
      //               if (Array.isArray(errors[key])) {
      //                 this.errors.key = errors[key].join(";");
      //               }
      //             }
      //           }
      //         }
      //       }
      //     })
      //     .finally(() => {
      //       this.is_loading = false;
      //     });
      // } catch (error) {
      //   console.log(error.message);
      // } finally {
      //   this.is_loading = false;
      // }
    },
    asyncFindCoQuanToChuc(query) {
      axios.get(env.endpointhttp + "coquantochucs?search=" + query).then((response) => {
        this.coquantochucs = response.data.result;
      });
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
  },
  mounted: function () {
    axios.get(env.endpointhttp + "chedothanhtras").then((response) => {
      this.chedothanhtras = response.data.result;
    });
    axios.get(env.endpointhttp + "loaihinhcosos").then((response) => {
      this.loaicosos = response.data.result;
    });
    axios.get(env.endpointhttp + "coquantochucs").then((response) => {
      this.coquantochucs = response.data.result;
    });
  },
};
</script>
