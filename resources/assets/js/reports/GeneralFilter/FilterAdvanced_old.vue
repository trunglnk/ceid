<template>
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-12">
        <label>Hình thức thanh tra</label>
        <div class="row">
          <div class="col-md-6">
            <checkbox-component id="thanh_tra_dot_xuat" v-model="ky_thanh_tra.dot_xuat" text="Đột xuất"
              @change="ChangeKythanhtra"></checkbox-component>
          </div>
          <div class="col-md-6">
            <checkbox-component id="thanh_tra_dinh_ky" v-model="ky_thanh_tra.dinh_ky" text="Kế hoạch"
              @change="ChangeKythanhtra"></checkbox-component>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-12">
            <label class="control-label">Cơ quan phê duyệt</label>
          </div>
          <div class="col-md-12">
            <label class="control-label">ĐTM</label>
            <multiselect v-model="coquanpheduyetdtm.value" placeholder="Chọn cơ quan" label="ten" track-by="id"
              :options="coquanpheduyetdtm.options" :multiple="true" selectLabel="Nhấn enter để chọn"
              deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn" :loading="coquanpheduyetdtm.isLoading"
              :tabindex="1" @input="changeCoquanpheduyetdtm"></multiselect>
          </div>
          <div class="col-md-6 col-lg-6">
            <checkbox-component id="dtm_cap_bo" v-model="coquanpheduyetdtm.cap_bo" text="Cấp bộ"
              @change="change_dtm_cap"></checkbox-component>
          </div>
          <div class="col-md-6 col-lg-6">
            <checkbox-component id="dtm_cap_dia_phuong" v-model="coquanpheduyetdtm.cap_dia_phuong" text="Cấp địa phương"
              @change="change_dtm_cap"></checkbox-component>
          </div>
        </div>
        <hr />
        <div class="row">
          <div class="col-md-12">
            <label class="control-label">Đề án BVMT</label>
            <multiselect v-model="coquanpheduyetdeanbvmt.value" placeholder="Chọn cơ quan" label="ten" track-by="id"
              :options="coquanpheduyetdeanbvmt.options" :multiple="true" selectLabel="Nhấn enter để chọn"
              deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn" :loading="coquanpheduyetdeanbvmt.isLoading"
              :tabindex="1" @input="changeCoquanpheduyetdeanbvmt"></multiselect>
          </div>
          <div class="col-md-6 col-lg-6">
            <checkbox-component id="deanbvmt_cap_bo" v-model="coquanpheduyetdeanbvmt.cap_bo" text="Cấp bộ"
              @change="change_deanbvmt_cap"></checkbox-component>
          </div>
          <div class="col-md-6 col-lg-6">
            <checkbox-component id="deanbvmt_cap_dia_phuong" v-model="coquanpheduyetdeanbvmt.cap_dia_phuong"
              text="Cấp địa phương" @change="change_deanbvmt_cap"></checkbox-component>
          </div>
        </div>
        <hr />
        <div class="row">
          <div class="col-md-12">
            <label class="control-label">Kế hoạch BVMT hoặc cam kết BVMT</label>
            <multiselect v-model="coquanpheduyetkehoachbvmt.value" placeholder="Chọn cơ quan" label="ten" track-by="id"
              :options="coquanpheduyetkehoachbvmt.options" :multiple="true" selectLabel="Nhấn enter để chọn"
              deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn"
              :loading="coquanpheduyetkehoachbvmt.isLoading" :tabindex="1" @input="changeCoquanpheduyetkehoachbvmt">
            </multiselect>
          </div>
          <div class="col-md-6 col-lg-6">
            <checkbox-component id="kehoachbvmt_cap_bo" v-model="coquanpheduyetkehoachbvmt.cap_bo" text="Cấp bộ"
              @change="change_kehoachbvmt_cap"></checkbox-component>
          </div>
          <div class="col-md-6 col-lg-6">
            <checkbox-component id="kehoachbvmt_cap_dia_phuong" v-model="coquanpheduyetkehoachbvmt.cap_dia_phuong"
              text="Cấp địa phương" @change="change_kehoachbvmt_cap"></checkbox-component>
          </div>
        </div>
      </div>
      <!-- QD -->
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-12">
            <label class="control-label">Số QĐ thành lập đoàn thanh tra</label>
          </div>
          <div class="col-md-12">
            <multiselect v-model="doanthanhtra.value" id="ajax" label="so_quyet_dinh" track-by="id"
              placeholder="Gõ số quyết định" selectLabel="Nhấn enter để chọn" deselectLabel="Nhấn enter để bỏ chọn"
              selectedLabel="Đã chọn" open-direction="bottom" :options="doanthanhtra.options" :multiple="true"
              :searchable="true" :loading="doanthanhtra.isLoading" :internal-search="false" :clear-on-select="false"
              @input="changeDoanThanhTra" :close-on-select="false" :options-limit="300" :limit="3"
              :limit-text="limitText" :max-height="600" :show-no-results="false" :hide-selected="true"
              @search-change="asyncFindDoanThanhTra">
              <template slot="tag" slot-scope="{ option, remove }">
                <span class="custom__tag">
                  <span>
                    {{ option.so_quyet_dinh }}
                  </span>
                  <span class="custom__remove" @click="remove(option)">
                    &nbsp;
                    <i class="fa fa-close fa-xs" style="
                        font-size: smaller;
                        font-weight: 100;
                        padding-left: 5px;
                        color: darkgreen;
                      "></i>
                  </span>
                </span>
              </template>
              <template slot="clear" slot-scope="props">
                <div class="multiselect__clear" v-if="doanthanhtra.value.length"
                  @mousedown.prevent.stop="clearAllDoanThanhTra(props.search)"></div>
              </template>
              <span slot="noResult">Không tìm thấy kết quả</span>
            </multiselect>
          </div>
        </div>
      </div>
      <!-- end QD -->
      <!-- loai hinh cơ sở  -->
      <div class="col-md-12">
        <label class="control-label">Loại hình sản xuất</label>
        <multiselect v-model="loaihinhcoso.value" placeholder="Chọn loại hình sản xuất" label="ten" track-by="id"
          group-values="childrens" group-label="parent" :group-select="true" :options="loaihinhcoso.options"
          :multiple="true" selectLabel="Nhấn enter để chọn" deselectLabel="Nhấn enter để bỏ chọn"
          selectedLabel="Đã chọn" :loading="loaihinhcoso.isLoading" :tabindex="10" @input="Changeloaihinhcoso">
        </multiselect>
      </div>
      <!-- end loai hinh cơ sở  -->
      <!-- khu cong nghiep -->
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-12">
            <label class="control-label">Khu công nghiệp</label>
          </div>
          <div class="col-md-12">
            <multiselect v-model="khucongnghiep.value" placeholder="Chọn khu công nghiệp" label="ten" track-by="id"
              :options="khucongnghiep.options" :multiple="true" selectLabel="Nhấn enter để chọn"
              deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn" :loading="khucongnghiep.isLoading"
              :tabindex="11" @input="changeKhucongnghiep"></multiselect>
          </div>
        </div>
      </div>
      <!-- end khu cong nghiep -->
      <!-- <div class="col-md-12">
        <div class="row">
          <div class="col-md-12">
            <label class="control-label">Kết luận</label>
          </div>
          <div class="col-md-12 col-lg-12">
            <checkbox-component
              id="ketluan_vi_pham"
              v-model="ket_luan.vi_pham"
              text="Vi phạm"
              @change="changeKetluan"
            ></checkbox-component>
          </div>
        </div>
      </div> -->
      <!-- Hanh vi vi pham  -->
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-12">
            <label class="control-label">Hành vi vi phạm</label>
          </div>
          <div class="col-md-12">
            <div class="col-md-12">
              <div class="row">
                <!-- vi pham thu tuc hanh chinh -->
                <div class="col-md-6">
                  <span>
                    <label style="margin-top: 5px;">Nhóm VP thủ tục hành chính</label>
                  </span>
                  <checkbox-component id="vi_pham_thu_tuc_hanh_chinh"
                    v-model="ketquathuchien.vi_pham_thu_tuc_hanh_chinh" text="Vi phạm thủ tục hành chính"
                    @change="changeHanhvi"></checkbox-component>
                  <div class="row">
                    <div class="col-md-12" v-show="ketquathuchien.vi_pham_thu_tuc_hanh_chinh">
                      <hr />
                      <div class="row">
                        <div class="col-md-12">
                          <label class="control-label">ĐTM/Đề án/KHBVMT</label>
                        </div>
                        <div class="col-md-12 col-lg-12">
                          <checkbox-component id="dtm_dean_kehoach_bvmt_filter" v-model="
                            ketquathuchien.dtm_dean_kehoach_bvmt.filter
                          " text="Không" @change="changeHanhvi"></checkbox-component>
                        </div>
                        <div class="col-md-12 col-lg-12">
                          <checkbox-component id="dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung" v-model="
                            ketquathuchien.dtm_dean_kehoach_bvmt
                              .dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung
                          " text="Không thực hiện một trong các nội dung" @change="changeHanhvi">
                          </checkbox-component>
                        </div>
                        <div class="col-md-12 col-lg-12">
                          <checkbox-component id="dtmdakhbvmt_thuc_hien_khong_dung_noi_dung" v-model="
                            ketquathuchien.dtm_dean_kehoach_bvmt
                              .dtmdakhbvmt_thuc_hien_khong_dung_noi_dung
                          " text="Thực hiện không đúng một trong các nội dung" @change="changeHanhvi">
                          </checkbox-component>
                        </div>
                      </div>
                      <hr />
                      <div class="row">
                        <div class="col-md-12">
                          <label class="control-label">KHQLMT</label>
                        </div>
                        <div class="col-md-12 col-lg-12">
                          <checkbox-component id="co_ket_hoach_quan_ly_moi_truong" v-model="
                            ketquathuchien.co_ket_hoach_quan_ly_moi_truong
                          " text="Không" @change="changeHanhvi"></checkbox-component>
                        </div>
                      </div>
                      <hr />
                      <div class="row">
                        <div class="col-md-12">
                          <label class="control-label">Công trình BVMT</label>
                        </div>
                        <div class="col-md-12 col-lg-12">
                          <checkbox-component id="congtrinhBVMT_khong_xay_lap"
                            v-model="ketquathuchien.congtrinhBVMT.khong_xay_lap" text="Không xây lắp"
                            @change="changeHanhvi"></checkbox-component>
                        </div>
                        <div class="col-md-12 col-lg-12">
                          <checkbox-component id="congtrinhBVMT_xay_lap_khong_dung" v-model="
                            ketquathuchien.congtrinhBVMT.xay_lap_khong_dung
                          " text="Xây lắp không đúng" @change="changeHanhvi"></checkbox-component>
                        </div>
                        <div class="col-md-12 col-lg-12">
                          <checkbox-component id="congtrinhBVMT_khong_co_giay_xac_nhan_hoan_thanh" v-model="
                            ketquathuchien.congtrinhBVMT
                              .khong_co_giay_xac_nhan_hoan_thanh
                          " text="Không có giấy xác nhận hoàn thành" @change="changeHanhvi"></checkbox-component>
                        </div>
                        <div class="col-md-12 col-lg-12">
                          <checkbox-component id="congtrinhBVMT_van_hanh_khong_dung_khong_thuong_xuyen" v-model="
                            ketquathuchien.congtrinhBVMT
                              .van_hanh_khong_dung_khong_thuong_xuyen
                          " text="Vận hành ko đúng/không thường xuyên" @change="changeHanhvi"></checkbox-component>
                        </div>
                      </div>
                      <hr />
                      <div class="row">
                        <div class="col-md-12">
                          <label class="control-label">GSMT</label>
                        </div>
                        <div class="col-md-12 col-lg-12">
                          <checkbox-component id="gsmt_khong_thuc_hien"
                            v-model="ketquathuchien.gsmt.gsmt_khong_thuc_hien" text="Không thực hiện"
                            @change="changeHanhvi"></checkbox-component>
                        </div>
                        <div class="col-md-12 col-lg-12">
                          <checkbox-component id="gsmt_thuc_hien_khong_dung_khong_du" v-model="
                            ketquathuchien.gsmt
                              .gsmt_thuc_hien_khong_dung_khong_du
                          " text="Thực hiện không đúng/không đầy đủ" @change="changeHanhvi"></checkbox-component>
                        </div>
                      </div>
                      <hr />
                      <div class="row">
                        <div class="col-md-12">
                          <label class="control-label">Giấy phép xả thải</label>
                        </div>
                        <div class="col-md-12 col-lg-12">
                          <checkbox-component id="co_giay_phep_xa_thai"
                            v-model="ketquathuchien.khong_co_giay_phep_xa_thai"
                            text="Không có giấy xác nhận theo quy định" @change="changeHanhvi"></checkbox-component>
                        </div>
                      </div>
                      <hr />
                      <div class="row">
                        <div class="col-md-12">
                          <label class="control-label">Giấy xác nhận hoàn thành công trình BVMT</label>
                        </div>
                        <div class="col-md-12">
                          <checkbox-component id="khong_thuc_hien_giay_xac_nhan" v-model="
                            ketquathuchien.giay_xac_nhan
                              .khong_thuc_hien_giay_xac_nhan
                          " text="Không thực hiện giấy xác nhận" @change="changeHanhvi"></checkbox-component>
                        </div>
                        <div class="col-md-12">
                          <checkbox-component id="thuc_hien_sai_giay_xac_nhan" v-model="
                            ketquathuchien.giay_xac_nhan
                              .thuc_hien_sai_giay_xac_nhan
                          " text="Thực hiện không đúng" @change="changeHanhvi"></checkbox-component>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end vi pham thu tuc hanh chinh -->

                <!-- Vi phạm nhóm hành vi về xả thải -->
                <div class="col-md-6">
                  <label style="margin-top: 5px;">Nhóm vi phạm xả thải</label>
                  <checkbox-component id="vi_pham_nhom_hanh_vi_xa_thai"
                    v-model="ketquathuchien.vi_pham_nhom_hanh_vi_xa_thai" text="Vi phạm nhóm hành vi về xả thải"
                    @change="changeHanhvi"></checkbox-component>
                  <div class="row" v-show="ketquathuchien.vi_pham_nhom_hanh_vi_xa_thai">
                    <div class="col-md-12 col-lg-12">
                      <hr />
                      <div class="row">
                        <div class="col-md-12">
                          <label class="control-label">Nước thải</label>
                        </div>
                        <div class="col-md-12 col-lg-12">
                          <checkbox-component id="nuoc_thai_vuot" v-model="ketquathuchien.nuoc_thai.nuoc_thai_vuot"
                            text="Xả vượt" @change="changeHanhvi"></checkbox-component>
                        </div>
                        <div class="col-md-12 col-lg-12">
                          <checkbox-component id="co_he_thong_thu_gom_nuoc_thai_rieng_biet" v-model="
                            ketquathuchien.nuoc_thai
                              .co_he_thong_thu_gom_nuoc_thai_rieng_biet
                          " text="Có hệ thống xử lý nước thải" @change="changeHanhvi"></checkbox-component>
                        </div>
                        <div class="col-md-12">
                          <label class="control-label">Xả nước thải vượt QCVN</label>
                          <div class="row">
                            <div class="col-md-12 col-lg-12">
                              <label class="control-label">Thông số</label>
                              <Multiselect v-model="thong_so_vi_pham_nuoc_thai" label="ten" track-by="id"
                                placeholder="Gõ tên" selectLabel="Nhấn enter để chọn"
                                deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn" open-direction="bottom"
                                :options="danhMucThongSoVuotChuanNuocThais" :searchable="true" :internal-search="true"
                                :clear-on-select="false" :close-on-select="true" :options-limit="300" :limit="3"
                                :max-height="600" :show-no-results="false" :hide-selected="false" :tabindex="1"
                                :clearOnSelect="true">
                                <span slot="noResult">Không tìm thấy kết quả</span>
                              </Multiselect>
                            </div>
                            <div class="col-md-5 col-lg-5">
                              <label class="control-label">Số lần vượt từ</label>
                              <ejs-numerictextbox v-model="so_lan_vuot_quy_chuan_nuoc_thai_tu" format
                                class="form-control" @input="changeHanhvi"></ejs-numerictextbox>
                            </div>
                            <div class="col-md-5 col-lg-5">
                              <label class="control-label">đến</label>
                              <ejs-numerictextbox v-model="so_lan_vuot_quy_chuan_nuoc_thai_den" format
                                class="form-control" @input="changeHanhvi"></ejs-numerictextbox>
                            </div>
                            <div class="col-md-2">
                              <label class="control-label"></label>
                              <button @click="addViPham('nuoc_thai')" style="margin-top:2.1rem"
                                class="btn bg-olive btn-flat pull-right margin-top margin-left">
                                <i class="fa fa-plus"></i>
                              </button>
                            </div>
                          </div>
                          <div class="row text-center">
                            <p style="color:red">{{ error_nuoc_thai }}</p>
                          </div>
                          <table class="table table-hover table-responsive">
                            <tbody>
                              <tr v-for="(item, index) in ketquathuchien.nuoc_thai
                              .chi_tiet_vi_pham_xa_thais" :key="`viphamnnuocthai-${index}`">
                                <td>
                                  <Span v-if="index > 0">Và </Span>
                                  {{ formatViPham(item) }}
                                </td>
                                <td align="center">
                                  <a @click="
                                    deleteArr(
                                      index,
                                      ketquathuchien.nuoc_thai
                                        .chi_tiet_vi_pham_xa_thais
                                    )
                                  " style="cursor: pointer;">
                                    <i class="fa fa-trash-o"></i>
                                  </a>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <hr />
                      <div class="row">
                        <div class="col-md-12">
                          <label class="control-label">Khí thải,bụi</label>
                        </div>
                        <div class="col-md-12 col-lg-12">
                          <checkbox-component id="khi_thai_vuot" v-model="ketquathuchien.khi_thai.khi_thai_vuot"
                            text="Xả vượt" @change="changeHanhvi"></checkbox-component>
                        </div>
                        <div class="col-md-12 col-lg-12">
                          <checkbox-component id="co_bien_phap_xu_ly_khi_thai" v-model="
                            ketquathuchien.khi_thai
                              .co_bien_phap_xu_ly_khi_thai
                          " text="Có hệ thống xử lý khí thải" @change="changeHanhvi"></checkbox-component>
                        </div>
                        <div class="col-md-12">
                          <label class="control-label">Thải bụi, khí thải vượt QCVN</label>
                          <div class="row">
                            <div class="col-md-12 col-lg-12">
                              <label class="control-label">Thông số</label>
                              <Multiselect v-model="thong_so_vi_pham_khi_thai" label="ten" track-by="id"
                                placeholder="Gõ tên" selectLabel="Nhấn enter để chọn"
                                deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn" open-direction="bottom"
                                :options="danhMucThongSoVuotChuanKhiThais" :searchable="true" :internal-search="true"
                                :clear-on-select="false" :close-on-select="true" :options-limit="300" :limit="3"
                                :max-height="600" :show-no-results="false" :hide-selected="false" :tabindex="1"
                                :clearOnSelect="true">
                                <span slot="noResult">Không tìm thấy kết quả</span>
                              </Multiselect>
                            </div>
                            <div class="col-md-5 col-lg-5">
                              <label class="control-label">Số lần vượt từ</label>
                              <ejs-numerictextbox v-model="so_lan_vuot_quy_chuan_khi_thai_tu" format
                                class="form-control"></ejs-numerictextbox>
                            </div>
                            <div class="col-md-5 col-lg-5">
                              <label class="control-label">đến</label>
                              <ejs-numerictextbox v-model="so_lan_vuot_quy_chuan_khi_thai_den" format
                                class="form-control"></ejs-numerictextbox>
                            </div>
                            <div class="col-md-2">
                              <label class="control-label"></label>
                              <button @click="addViPham('khi_thai')" style="margin-top:2.1rem"
                                class="btn bg-olive btn-flat pull-right margin-top margin-left">
                                <i class="fa fa-plus"></i>
                              </button>
                            </div>
                          </div>
                          <div class="row text-center">
                            <p style="color:red">{{ error_khi_thai }}</p>
                          </div>
                          <table class="table table-hover table-responsive">
                            <tbody>
                              <tr v-for="(item, index) in ketquathuchien.khi_thai
                              .chi_tiet_vi_pham_xa_thais" :key="`viphamnkhithai-${index}`">
                                <td>
                                  <Span v-if="index > 0">Và </Span>
                                  {{ formatViPham(item) }}
                                </td>
                                <td align="center">
                                  <a @click="
                                    deleteArr(
                                      index,
                                      ketquathuchien.khi_thai
                                        .chi_tiet_vi_pham_xa_thais
                                    )
                                  " style="cursor: pointer;">
                                    <i class="fa fa-trash-o"></i>
                                  </a>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <hr />
                      <div class="row">
                        <div class="col-md-12">
                          <label class="control-label">Chất thải rắn sinh hoạt</label>
                        </div>
                        <div class="col-md-12 col-lg-12">
                          <checkbox-component id="ctrsh_co_thu_gom" v-model="ketquathuchien.ctrsh.ctrsh_co_thu_gom"
                            text="Thu gom" @change="changeHanhvi"></checkbox-component>
                          <checkbox-component id="ctrsh_co_phan_loai" v-model="ketquathuchien.ctrsh.ctrsh_co_phan_loai"
                            text="Phân loại" @change="changeHanhvi"></checkbox-component>
                          <checkbox-component id="ctrsh_co_luu_tru" v-model="ketquathuchien.ctrsh.ctrsh_co_luu_tru"
                            text="Lưu trữ" @change="changeHanhvi"></checkbox-component>
                          <checkbox-component id="ctrsh_co_chuyen_giao"
                            v-model="ketquathuchien.ctrsh.ctrsh_co_chuyen_giao" text="Chuyển giao"
                            @change="changeHanhvi"></checkbox-component>
                        </div>
                      </div>
                      <hr />
                      <div class="row">
                        <div class="col-md-12">
                          <label class="control-label">Chất thải rắn công nghiệp</label>
                        </div>
                        <div class="col-md-12 col-lg-12">
                          <checkbox-component id="ctrcn_co_thu_gom" v-model="ketquathuchien.ctrcn.ctrcn_co_thu_gom"
                            text="Thu gom" @change="changeHanhvi"></checkbox-component>
                          <checkbox-component id="ctrcn_co_phan_loai" v-model="ketquathuchien.ctrcn.ctrcn_co_phan_loai"
                            text="Phân loại" @change="changeHanhvi"></checkbox-component>
                          <checkbox-component id="ctrcn_co_luu_tru" v-model="ketquathuchien.ctrcn.ctrcn_co_luu_tru"
                            text="Lưu trữ" @change="changeHanhvi"></checkbox-component>
                          <checkbox-component id="ctrcn_co_chuyen_giao"
                            v-model="ketquathuchien.ctrcn.ctrcn_co_chuyen_giao" text="Chuyển giao"
                            @change="changeHanhvi"></checkbox-component>
                        </div>
                      </div>
                      <hr />
                      <div class="row">
                        <div class="col-md-12">
                          <label class="control-label">CTNH</label>
                        </div>
                        <div class="col-md-12 col-lg-12">
                          <checkbox-component id="ctrnh_vi_pham_chung_tu"
                            v-model="ketquathuchien.ctnh.ctrnh_vi_pham_chung_tu"
                            text="Không báo cáo quản lý chất thải nguy hại" @change="changeHanhvi">
                          </checkbox-component>
                          <checkbox-component id="ctrnh_co_thu_gom" v-model="ketquathuchien.ctnh.ctrnh_co_thu_gom"
                            text="Thu gom" @change="changeHanhvi"></checkbox-component>
                          <checkbox-component id="ctrnh_co_phan_loai" v-model="ketquathuchien.ctnh.ctrnh_co_phan_loai"
                            text="Phân loại" @change="changeHanhvi"></checkbox-component>
                          <checkbox-component id="ctrnh_co_luu_tru" v-model="ketquathuchien.ctnh.ctrnh_co_luu_tru"
                            text="Lưu trữ" @change="changeHanhvi"></checkbox-component>
                          <checkbox-component id="ctrnh_co_de_lan" v-model="ketquathuchien.ctnh.ctrnh_co_de_lan"
                            text="Để lẫn" @change="changeHanhvi"></checkbox-component>
                          <checkbox-component id="ctrnh_co_chuyen_giao"
                            v-model="ketquathuchien.ctnh.ctrnh_co_chuyen_giao" text="Chuyển giao"
                            @change="changeHanhvi"></checkbox-component>
                          <checkbox-component id="ctrnh_co_chon_lap" v-model="ketquathuchien.ctnh.ctrnh_co_chon_lap"
                            text="Chôn lấp" @change="changeHanhvi"></checkbox-component>
                          <checkbox-component id="ctrnh_co_do_thai" v-model="ketquathuchien.ctnh.ctrnh_co_do_thai"
                            text="Đổ thải" @change="changeHanhvi"></checkbox-component>
                          <checkbox-component id="ctrnh_co_giay_phep" v-model="ketquathuchien.ctnh.ctrnh_co_giay_phep"
                            text="Có giấy phép" @change="changeHanhvi"></checkbox-component>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--End Vi phạm nhóm hành vi về xả thải -->
                <!-- Nhóm hành vi vi phạm khác -->
                <div class="col-md-6">
                  <label style="margin-top: 5px;">Nhóm hành vi vi phạm khác</label>
                  <checkbox-component id="vi_pham_nhom_hanh_vi_khac" v-model="ketquathuchien.vi_pham_nhom_hanh_vi_khac"
                    text="Vi phạm nhóm hành vi khác" @change="changeHanhvi()"></checkbox-component>
                  <div class="row" v-show="ketquathuchien.vi_pham_nhom_hanh_vi_khac">
                    <div class="col-md-12 col-lg-12">
                      <hr />
                      <div class="row">
                        <div class="col-md-12 col-lg-12">
                          <checkbox-component id="vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong" v-model="
                            ketquathuchien.vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong
                          " text="Vi phạm quy định về thu thập, sử dụng TTMT" @change="changeHanhvi()">
                          </checkbox-component>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 col-lg-12">
                          <checkbox-component id="vi_pham_cac_quy_dinh_bao_ve_moi_truong" v-model="
                            ketquathuchien.vi_pham_cac_quy_dinh_bao_ve_moi_truong
                          " text="Vi phạm về các quy định BVMT" @change="changeHanhvi()"></checkbox-component>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 col-lg-12">
                          <checkbox-component id="co_hanh_vi_can_tro_ve_bvmt"
                            v-model="ketquathuchien.co_hanh_vi_can_tro_ve_bvmt" text="Hành vi cản trở về BVMT"
                            @change="changeHanhvi()"></checkbox-component>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- EndNhóm hành vi vi phạm khác -->
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--End Hanh vi vi pham  -->
      <div class="col-md-12">
        <label class="control-label">Cơ sở bị đình chỉ</label>
        <checkbox-component id="bienphapxuphatboxung_dinh_chi" v-model="bienphapxuphatboxung.dinh_chi" text="Đình chỉ"
          @change="changeBienphapxuphatboxung"></checkbox-component>
      </div>
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-12">
            <label class="control-label">Kết quả khắc phục hậu quả vi phạm</label>
          </div>
          <div class="col-md-6 col-lg-6">
            <checkbox-component id="ketquakhacphuchauqua_nop_mot_phan" v-model="ketquakhacphuchauqua.nop_mot_phan"
              text="Nộp một phần" @change="changeKetquakhacphuchauqua"></checkbox-component>
          </div>
          <div class="col-md-6 col-lg-6">
            <checkbox-component id="ketquakhacphuchauqua_da_nop_phat" v-model="ketquakhacphuchauqua.da_nop_phat"
              text="Đã nộp phạt" @change="changeKetquakhacphuchauqua"></checkbox-component>
          </div>
          <div class="col-md-6 col-lg-6">
            <checkbox-component id="ketquakhacphuchauqua_da_khac_phuc" v-model="ketquakhacphuchauqua.da_khac_phuc"
              text="Đã khắc phục" @change="changeKetquakhacphuchauqua"></checkbox-component>
          </div>
          <div class="col-md-6 col-lg-6">
            <checkbox-component id="ketquakhacphuchauqua_da_bao_cao" v-model="ketquakhacphuchauqua.da_bao_cao"
              text="Đã báo cáo" @change="changeKetquakhacphuchauqua"></checkbox-component>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-lg-12">
        <label class="control-label">Biện pháp xử phạt bổ sung</label>
        <multiselect v-model="bienphapxuphatboxung.value" placeholder="Chọn biện pháp xử phạt bổ sung" label="ten"
          track-by="id" :options="bienphapxuphatboxung.options" :multiple="true" selectLabel="Nhấn enter để chọn"
          deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn" :loading="bienphapxuphatboxung.isLoading"
          :tabindex="1" @input="changeBienphapxuphatboxung"></multiselect>
      </div>
      <div class="col-md-12">
        <label class="control-label">Biện pháp khắc phục hậu quả</label>
        <multiselect v-model="bienphapkhacphuchauqua.value" placeholder="Chọn biện pháp khắc phục hậu quả" label="ten"
          track-by="id" :options="bienphapkhacphuchauqua.options" :multiple="true" selectLabel="Nhấn enter để chọn"
          deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn" :loading="bienphapkhacphuchauqua.isLoading"
          :tabindex="1" @input="changeBienphapkhacphuchauqua"></multiselect>
      </div>
    </div>
  </div>
</template>
<script>
import * as env from "../../env.js";
import Multiselect from "vue-multiselect";
export default {
  components: {
    Multiselect
  },
  data: () => ({
    ky_thanh_tra: {
      dot_xuat: true,
      dinh_ky: true
    },
    loaiVanBan: {
      value: [],
      options: [],
      isLoading: true
    },
    loaihinhcoso: {
      value: [],
      options: [],
      isLoading: true
    },
    khucongnghiep: {
      value: [],
      options: [],
      isLoading: true,
      data: []
    },
    coquantochucs: {
      isLoading: true
    },
    coquanpheduyetdtm: {
      value: [],
      options: [],
      cap_bo: false,
      cap_dia_phuong: false
    },
    coquanpheduyetdeanbvmt: {
      value: [],
      options: [],
      cap_bo: false,
      cap_dia_phuong: false
    },
    coquanpheduyetkehoachbvmt: {
      value: [],
      options: [],
      cap_bo: false,
      cap_dia_phuong: false
    },
    doanthanhtra: {
      value: [],
      options: [],
      isLoading: false
    },
    ketquathuchien: {
      vi_pham_thu_tuc_hanh_chinh: false,
      dtm_dean_kehoach_bvmt: {
        filter: null,
        dtmdakhbvmt_thuc_hien_khong_dung_noi_dung: false,
        dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung: false
      },
      khong_co_giay_phep_xa_thai: false,
      co_ket_hoach_quan_ly_moi_truong: false,
      congtrinhBVMT: {
        khong_xay_lap: false,
        xay_lap_khong_dung: false,
        khong_co_giay_xac_nhan_hoan_thanh: false,
        van_hanh_khong_dung_khong_thuong_xuyen: false
      },
      gsmt: {
        gsmt_khong_thuc_hien: false,
        gsmt_thuc_hien: false,
        gsmt_thuc_hien_khong_dung_khong_du: false
      },
      giay_xac_nhan: {
        khong_thuc_hien_giay_xac_nhan: false,
        thuc_hien_sai_giay_xac_nhan: false
      },
      // vi pham xa thai
      vi_pham_nhom_hanh_vi_xa_thai: false,
      nuoc_thai: {
        co_he_thong_thu_gom_nuoc_thai_rieng_biet: false,
        nuoc_thai_vuot: false,
        chi_tiet_vi_pham_xa_thais: []
      },
      khi_thai: {
        co_bien_phap_xu_ly_khi_thai: false,
        khi_thai: false,
        chi_tiet_vi_pham_xa_thais: []
      },
      ctrsh: {
        ctrsh_co_thu_gom: false,
        ctrsh_co_phan_loai: false,
        ctrsh_co_luu_tru: false,
        ctrsh_co_chuyen_giao: false
      },
      ctrcn: {
        ctrcn_co_thu_gom: false,
        ctrcn_co_phan_loai: false,
        ctrcn_co_luu_tru: false,
        ctrcn_co_chuyen_giao: false
      },
      ctnh: {
        ctrnh_vi_pham_chung_tu: false,
        ctrnh_co_thu_gom: false,
        ctrnh_co_phan_loai: false,
        ctrnh_co_luu_tru: false,
        ctrnh_co_de_lan: false,
        ctrnh_co_chuyen_giao: false,
        ctrnh_co_chon_lap: false,
        ctrnh_co_do_thai: false,
        ctrnh_co_giay_phep: false
      },
      vi_pham_nhom_hanh_vi_khac: false,
      vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong: false,
      vi_pham_cac_quy_dinh_bao_ve_moi_truong: false,
      co_hanh_vi_can_tro_ve_bvmt: false
    },
    bienphapxuphatboxung: {
      value: [],
      options: [],
      isLoading: true,
      dinh_chi: false
    },
    ketquakhacphuchauqua: {
      nop_mot_phan: false,
      da_nop_phat: false,
      da_khac_phuc: false,
      da_bao_cao: false
    },
    bienphapkhacphuchauqua: {
      value: [],
      options: [],
      isLoading: true
    },
    ket_luan: {
      vi_pham: false
    },
    so_lan_vuot_quy_chuan_nuoc_thai_den: null,
    so_lan_vuot_quy_chuan_nuoc_thai_tu: null,
    thong_so_vi_pham_nuoc_thai: null,
    error_nuoc_thai: null,
    danhMucThongSoVuotChuanNuocThais: [],

    so_lan_vuot_quy_chuan_khi_thai_den: null,
    so_lan_vuot_quy_chuan_khi_thai_tu: null,
    thong_so_vi_pham_khi_thai: null,
    error_khi_thai: null,
    danhMucThongSoVuotChuanKhiThais: []
  }),
  mounted() {
    this.getThuTucHanhChinh()
    // co quan to chuc
    if (localStorage.getItem("coquantochucs")) {
      this.coquantochucs.isLoading = false;
      this.coquanpheduyetdtm.options = JSON.parse(
        localStorage.getItem("coquantochucs")
      );
      this.coquanpheduyetdeanbvmt.options = JSON.parse(
        localStorage.getItem("coquantochucs")
      );
      this.coquanpheduyetkehoachbvmt.options = JSON.parse(
        localStorage.getItem("coquantochucs")
      );
    } else {
      this.coquantochucs.isLoading = true;
      axios
        .get(env.endpointhttp + "coquantochucs")
        .then(response => {
          this.coquanpheduyetdtm.options = response.data.result;
          this.coquanpheduyetdeanbvmt.options = response.data.result;
          this.coquanpheduyetkehoachbvmt.options = response.data.result;
          localStorage.setItem(
            "coquantochucs",
            JSON.stringify(response.data.result)
          );
        })
        .catch(error => { })
        .finally(() => (this.coquantochucs.isLoading = false));
    }
    // loaihinhcosos?type=report
    if (localStorage.getItem("loaihinhcosos?type=report")) {
      this.loaihinhcoso.isLoading = false;
      this.loaihinhcoso.options = JSON.parse(
        localStorage.getItem("loaihinhcosos?type=report")
      );
    } else {
      this.loaihinhcoso.isLoading = true;
      axios
        .get(env.endpointhttp + "loaihinhcosos?type=report")
        .then(response => {
          this.loaihinhcoso.options = response.data.result;
          localStorage.setItem(
            "loaihinhcosos?type=report",
            JSON.stringify(this.loaihinhcoso.options)
          );
        })
        .catch(error => { })
        .finally(() => (this.loaihinhcoso.isLoading = false));
    }
    // asynkhucongnghiep
    if (localStorage.getItem("asynkhucongnghiep")) {
      this.khucongnghiep.isLoading = false;
      this.khucongnghiep.options = JSON.parse(
        localStorage.getItem("asynkhucongnghiep")
      );
      this.khucongnghiep.data = this.khucongnghiep.options;
    } else {
      this.khucongnghiep.isLoading = true;
      axios
        .get(env.endpointhttp + "asynkhucongnghiep")
        .then(response => {
          this.khucongnghiep.options = response.data.result;
          this.khucongnghiep.data = this.khucongnghiep.options;
          localStorage.setItem(
            "asynkhucongnghiep",
            JSON.stringify(this.khucongnghiep.options)
          );
        })
        .catch(error => { })
        .finally(() => (this.khucongnghiep.isLoading = false));
    }
    // loaixuphatbosungs
    if (localStorage.getItem("loaixuphatbosungs")) {
      this.bienphapxuphatboxung.isLoading = false;
      this.bienphapxuphatboxung.options = JSON.parse(
        localStorage.getItem("loaixuphatbosungs")
      );
    } else {
      this.bienphapxuphatboxung.isLoading = true;
      axios
        .get(env.endpointhttp + "loaixuphatbosungs")
        .then(response => {
          this.bienphapxuphatboxung.options = response.data.result;
          localStorage.setItem(
            "loaixuphatbosungs",
            JSON.stringify(this.bienphapxuphatboxung.options)
          );
        })
        .catch(error => { })
        .finally(() => (this.bienphapxuphatboxung.isLoading = false));
    }
    // cacbienphapkhacphuchauquas
    if (localStorage.getItem("cacbienphapkhacphuchauquas")) {
      this.bienphapkhacphuchauqua.isLoading = false;
      this.bienphapkhacphuchauqua.options = JSON.parse(
        localStorage.getItem("cacbienphapkhacphuchauquas")
      );
    } else {
      this.bienphapkhacphuchauqua.isLoading = true;
      axios
        .get(env.endpointhttp + "cacbienphapkhacphuchauquas")
        .then(response => {
          this.bienphapkhacphuchauqua.options = response.data.result;
          localStorage.setItem(
            "cacbienphapkhacphuchauquas",
            JSON.stringify(this.bienphapkhacphuchauqua.options)
          );
        })
        .catch(error => { })
        .finally(() => (this.bienphapkhacphuchauqua.isLoading = false));
    }
  },
  methods: {
    getThuTucHanhChinh() {
      this.loaiVanBan.isLoading = true;
      const loaiThuTucs = [
        { name: 'Văn bản ĐTM', code: 'C_LoaiVanBanDTM' },
        { name: 'Loại giấy phép môi trường', code: 'C_LoaiGiayPhepMoiTruong' },
        { name: 'Văn bản khác (cũ)', code: 'khac' },
      ];
      axios.get(env.endpointhttp + "loaithutuchanhchinhs").then(response => {
        loaiThuTucs.forEach(it => {
          let thuTuc = response.data.result.filter(el => el.phan_loai == it.code);
          this.loaiVanBan.options.push({group: it.name, items: thuTuc})
        })
        console.log(this.loaiVanBan.options)

      }).finally(() => {
        this.loaiVanBan.isLoading = false
      });
    },
    change_dtm_cap(e) {
      var coQuanCapBo = [];
      var coQuanDiaPhuong = [];

      if (this.coquanpheduyetdtm.cap_bo) {
        coQuanCapBo = this.coquanpheduyetdtm.options.filter(function (value) {
          return value.cap_bo == true;
        });
      }
      if (this.coquanpheduyetdtm.cap_dia_phuong) {
        coQuanDiaPhuong = this.coquanpheduyetdtm.options.filter(function (
          value
        ) {
          return value.cap_bo == false;
        });
      }
      this.coquanpheduyetdtm.value = coQuanCapBo.concat(coQuanDiaPhuong);
      this.changeData({ coquanpheduyetdtm: this.coquanpheduyetdtm });
      this.changeCoquanpheduyetdtm();
    },
    change_deanbvmt_cap(e) {
      var coQuanCapBo = [];
      var coQuanDiaPhuong = [];
      if (this.coquanpheduyetdeanbvmt.cap_bo) {
        coQuanCapBo = this.coquanpheduyetdeanbvmt.options.filter(function (
          value
        ) {
          return value.cap_bo == true;
        });
      }
      if (this.coquanpheduyetdeanbvmt.cap_dia_phuong) {
        coQuanDiaPhuong = this.coquanpheduyetdeanbvmt.options.filter(function (
          value
        ) {
          return value.cap_bo == false;
        });
      }

      this.coquanpheduyetdeanbvmt.value = coQuanCapBo.concat(coQuanDiaPhuong);
      this.changeData({ coquanpheduyetdeanbvmt: this.coquanpheduyetdtm });
      this.changeCoquanpheduyetdeanbvmt();
    },
    change_kehoachbvmt_cap(e) {
      var coQuanCapBo = [];
      var coQuanDiaPhuong = [];
      if (this.coquanpheduyetkehoachbvmt.cap_bo) {
        coQuanCapBo = this.coquanpheduyetkehoachbvmt.options.filter(function (
          value
        ) {
          return value.cap_bo == true;
        });
      }
      if (this.coquanpheduyetkehoachbvmt.cap_dia_phuong) {
        coQuanDiaPhuong = this.coquanpheduyetkehoachbvmt.options.filter(
          function (value) {
            return value.cap_bo == false;
          }
        );
      }
      this.coquanpheduyetkehoachbvmt.value = coQuanCapBo.concat(
        coQuanDiaPhuong
      );
      this.changeData({
        coquanpheduyetkehoachbvmt: this.coquanpheduyetkehoachbvmt
      });
      this.changeCoquanpheduyetkehoachbvmt();
    },
    asyncFindDoanThanhTra(query) {
      if (query) {
        this.doanthanhtra.isLoading = true;
        axios
          .get(env.endpointhttp + "asyncdoanthanhtra?search=" + query)
          .then(response => {
            this.doanthanhtra.options = response.data.result;
          })
          .catch(error => {
            console.log(error);
          })
          .finally(() => (this.doanthanhtra.isLoading = false));
      }
    },
    changeViPhamNhomHanhViKhac() {
      if (this.ketquathuchien.vi_pham_nhom_hanh_vi_khac) {
        this.ketquathuchien.vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong = false;
        this.ketquathuchien.vi_pham_cac_quy_dinh_bao_ve_moi_truong = false;
        this.ketquathuchien.co_hanh_vi_can_tro_ve_bvmt = false;
      }
    },
    limitText(count) {
      return `và ${count} đoàn thanh tra khác`;
    },
    changeDoanThanhTra() {
      this.changeData({ doanthanhtra: this.doanthanhtra });
    },
    changeData(val) {
      this.$emit("change-data", val);
    },
    ChangeKythanhtra() {
      this.changeData({ ky_thanh_tra: this.ky_thanh_tra });
    },
    changeCoquanpheduyetdtm() {
      this.changeData({ coquanpheduyetdtm: this.coquanpheduyetdtm });
    },
    changeCoquanpheduyetdeanbvmt() {
      this.changeData({ coquanpheduyetdeanbvmt: this.coquanpheduyetdeanbvmt });
    },
    changeCoquanpheduyetkehoachbvmt() {
      this.changeData({
        coquanpheduyetkehoachbvmt: this.coquanpheduyetkehoachbvmt
      });
    },
    changeKhucongnghiep() {
      this.changeData({ khucongnghiep: this.khucongnghiep });
    },
    changeKetquathuchien() {
      this.changeData({ ketquathuchien: this.ketquathuchien });
    },
    changeKetquakhacphuchauqua() {
      this.changeData({ ketquakhacphuchauqua: this.ketquakhacphuchauqua });
    },
    changeBienphapxuphatboxung() {
      this.changeData({ bienphapxuphatboxung: this.bienphapxuphatboxung });
    },
    changeBienphapkhacphuchauqua() {
      this.changeData({ bienphapkhacphuchauqua: this.bienphapkhacphuchauqua });
    },
    changeKetluan() {
      this.changeData({ ket_luan: this.ket_luan });
    },
    changeHanhvi() {
      this.changeData({ ketquathuchien: this.ketquathuchien });
    },
    Changeloaihinhcoso() {
      this.changeData({ loaihinhcoso: this.loaihinhcoso });
    },
    addViPham(type) {
      if (type == "nuoc_thai") {
        this.error_nuoc_thai = "";
        if (!this.thong_so_vi_pham_nuoc_thai) {
          this.error_nuoc_thai = "Chưa chọn thông số";
          return false;
        }
        if (
          !this.so_lan_vuot_quy_chuan_nuoc_thai_tu &&
          !this.so_lan_vuot_quy_chuan_nuoc_thai_den
        ) {
          this.error_nuoc_thai = "Chưa nhập số lần vượt";
          return false;
        }
        this.ketquathuchien.nuoc_thai.chi_tiet_vi_pham_xa_thais.push({
          thong_so: this.thong_so_vi_pham_nuoc_thai.ten,
          thong_so_id: this.thong_so_vi_pham_nuoc_thai.id,
          so_lan_vuot_tu: this.so_lan_vuot_quy_chuan_nuoc_thai_tu,
          so_lan_vuot_den: this.so_lan_vuot_quy_chuan_nuoc_thai_den
        });

        this.thong_so_vi_pham_nuoc_thai = null;
        this.so_lan_vuot_quy_chuan_nuoc_thai_tu = null;
        this.so_lan_vuot_quy_chuan_nuoc_thai_den = null;
      }

      if (type == "khi_thai") {
        this.error_khi_thai = "";
        if (!this.thong_so_vi_pham_khi_thai) {
          this.error_khi_thai = "Chưa chọn thông số";
          return false;
        }
        if (
          !this.so_lan_vuot_quy_chuan_khi_thai_tu &&
          !this.so_lan_vuot_quy_chuan_khi_thai_den
        ) {
          this.error_khi_thai = "Chưa nhập số lần vượt";
          return false;
        }
        this.ketquathuchien.khi_thai.chi_tiet_vi_pham_xa_thais.push({
          thong_so: this.thong_so_vi_pham_khi_thai.ten,
          thong_so_id: this.thong_so_vi_pham_khi_thai.id,
          so_lan_vuot_tu: this.so_lan_vuot_quy_chuan_khi_thai_tu,
          so_lan_vuot_den: this.so_lan_vuot_quy_chuan_khi_thai_den
        });

        this.thong_so_vi_pham_khi_thai = null;
        this.so_lan_vuot_quy_chuan_khi_thai_tu = null;
        this.so_lan_vuot_quy_chuan_khi_thai_den = null;
      }
    },
    formatViPham(item) {
      if (item.thong_so) {
        if (item.so_lan_vuot_tu && item.so_lan_vuot_den) {
          return (
            item.thong_so +
            " có số lần vượt từ " +
            item.so_lan_vuot_tu +
            "(lần) đến " +
            item.so_lan_vuot_den +
            "(lần)"
          );
        } else if (item.so_lan_vuot_den && !item.so_lan_vuot_tu) {
          return (
            item.thong_so +
            " có số lần vượt nhỏ hơn " +
            item.so_lan_vuot_den +
            "(lần)"
          );
        } else if (!item.so_lan_vuot_den && item.so_lan_vuot_tu) {
          return (
            item.thong_so +
            " có số lần vượt lớn hơn " +
            item.so_lan_vuot_tu +
            "(lần)"
          );
        }
      }
    },
    deleteArr(index, arr) {
      arr.splice(index, 1);
    }
  },
  created() {
    axios.get(env.endpointhttp + "danhmuc/thongsovuotchuan").then(response => {
      this.danhMucThongSoVuotChuanNuocThais = response.data.result.filter(
        item => item.type == "nuoc_thai"
      );
      this.danhMucThongSoVuotChuanKhiThais = response.data.result.filter(
        item => item.type == "khi_thai"
      );
    });
  }
};
</script>
<style scoped>
.control-label {
  font-weight: 400;
}

/deep/ .multiselect__single {
  overflow: hidden;
  text-overflow: ellipsis;
  width: 100%;
  white-space: nowrap;
}
</style>
