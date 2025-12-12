<template>
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <div class="row">
        <div class="col-md-5">
          <h2 class="page-header-button-header title_master_form">
            TẠO THÔNG TIN CƠ SỞ THANH TRA
          </h2>
        </div>
        <div class="col-md-7">
          <button
            type="submit"
            id="onsubmit"
            class="btn bg-olive btn-flat pull-right margin-top"
            :disabled="is_loading"
            @click="addCoSoThanhTra"
          >
            <i class="fa fa-plus"></i> Thêm mới
          </button>
          <!-- <button type="button" class="btn btn-flat pull-right margin-top margin-right" @click="refresh">
                        <i class="fa fa-refresh"></i> Làm mới
                    </button> -->
          <a
            href="/cososanxuat"
            class="btn btn-default btn-flat pull-right margin-top margin-right"
          >
            <i class="fa fa-undo"></i> Trở lại
          </a>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12 col-lg-4">
          <div class="block-multiple-rows padding-left padding-right">
            <div class="row">
              <div class="col-md-12">
                <label>Tên&nbsp;<span style="color:red">*</span></label>
                <input
                  v-model="form_data.ten_co_so_thanh_tra"
                  type="text"
                  class="form-control"
                  tabindex="1"
                  autofocus
                  placeholder="Tên cơ sở thanh tra"
                />
                <span class="help-block" v-if="errors.ten_co_so_thanh_tra">
                  <strong>{{ errors.ten_co_so_thanh_tra }}</strong>
                </span>
              </div>
              <div class="col-md-12">
                <p class="lead">Địa chỉ hoạt động</p>
                <label>Thuộc KCN, CCN, KCX, KCNC, làng nghề&nbsp;</label>
                <multiselect
                  v-model="form_data.khu_cong_nghiep"
                  label="ten"
                  track-by="id"
                  placeholder="Gõ tên khu công nghiệp"
                  selectLabel="Nhấn enter để chọn"
                  deselectLabel="Nhấn enter để bỏ chọn"
                  selectedLabel="Đã chọn"
                  open-direction="bottom"
                  :options="this.khu_cong_nghieps"
                  :multiple="false"
                  :searchable="true"
                  :loading="is_loading"
                  :internal-search="false"
                  :clear-on-select="false"
                  :close-on-select="true"
                  :options-limit="300"
                  :limit="3"
                  :limit-text="limitTextKhuCongNghiep"
                  :max-height="600"
                  :show-no-results="false"
                  :hide-selected="false"
                  :tabindex="1"
                  :clearOnSelect="true"
                  @search-change="asyncFindKhuCongNghiep"
                  @select="changeKCN"
                >
                  <span slot="noResult">Không tìm thấy kết quả</span>
                </multiselect>
                <div class="row">
                  <div class="col-md-12">
                    <label>Xã phường</label>
                    <input
                      v-model="form_data.xa_phuong"
                      type="text"
                      class="form-control"
                      placeholder="Xã"
                      tabindex="1"
                    />
                  </div>
                  <div class="col-md-12">
                    <label>Huyện</label>
                    <multiselect
                      v-model="form_data.quan_huyen"
                      label="ten"
                      track-by="id"
                      placeholder="Gõ tên huyện"
                      selectLabel="Nhấn enter để chọn"
                      deselectLabel="Nhấn enter để bỏ chọn"
                      selectedLabel="Đã chọn"
                      open-direction="bottom"
                      :options="huyens"
                      :loading="is_loading"
                      :clear-on-select="false"
                      :close-on-select="true"
                      :tabindex="1"
                      @input="changeHuyen"
                    >
                      <span slot="noResult">Không tìm thấy kết quả</span>
                    </multiselect>
                  </div>
                  <div class="col-md-12">
                    <label>Tỉnh<span style="color:red">*</span></label>
                    <multiselect
                      v-model="form_data.tinh_thanh"
                      label="ten"
                      track-by="id"
                      placeholder="Gõ tên Tỉnh thành"
                      selectLabel="Nhấn enter để chọn"
                      deselectLabel="Nhấn enter để bỏ chọn"
                      selectedLabel="Đã chọn"
                      open-direction="bottom"
                      :options="tinhs"
                      :loading="is_loading"
                      :close-on-select="true"
                      :max-height="600"
                      :tabindex="1"
                    >
                      <span slot="noResult">Không tìm thấy kết quả</span>
                    </multiselect>
                    <span class="help-block" v-if="errors.tinh_thanh">
                      <strong>{{ errors.tinh_thanh }}</strong>
                    </span>
                  </div>
                </div>
                <label>Tọa độ</label>
                <div class="row">
                  <div class="col-md-6">
                    <input
                      v-model="form_data.toa_do.kinhdo"
                      type="text"
                      class="form-control"
                      placeholder="Kinh độ"
                      tabindex="1"
                    />
                  </div>
                  <div class="col-md-6">
                    <input
                      v-model="form_data.toa_do.vido"
                      type="text"
                      class="form-control"
                      placeholder="Vĩ độ"
                      tabindex="1"
                    />
                  </div>
                </div>
                <div class="row" style="padding: 10px">
                  <div class="welcome">
                    <google-map
                      @tendiachi="getTenDiaChi($event)"
                      :css="1"
                      @toado="getToaDo($event)"
                      name="example"
                      :toado="form_data.toa_do"
                      style="margin-top: -5px"
                      :hidenInput="0"
                    ></google-map>
                  </div>
                </div>
                <label
                  >Địa chỉ hoạt động <span style="color:red">*</span></label
                >
                <textarea
                  v-model="form_data.dia_chi_hoat_dong"
                  type="text"
                  class="form-control"
                  tabindex="19"
                  placeholder="Nhập địa chỉ hoạt động"
                  @change="getLatLonByAddressText()"
                ></textarea>
                <span class="help-block" v-if="errors.dia_chi_hoat_dong">
                  <strong>{{ errors.dia_chi_hoat_dong }}</strong>
                </span>
                <label>Địa chỉ liên hệ</label>
                <textarea
                  v-model="form_data.dia_chi_hoat_dong"
                  type="text"
                  class="form-control"
                  tabindex="18"
                  placeholder="Nhập địa chỉ liên hệ"
                ></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-lg-8">
          <div class="row block-multiple-rows">
            <div class="col-lg-12" style="margin-top: 5px;">
              <label>THÔNG TIN CHUNG</label>
              <p class="text-muted">
                (Thông tin chung của kết luận thanh tra gần nhất)
              </p>
              <hr style="margin-top: 7px;margin-bottom: 7px;" />
            </div>
            <!-- <div class="col-md-12">
              <label>Chứng nhận kinh doanh</label>
              <div class="row">
                <div class="col-md-3">
                  <input
                    v-model="form_data.so_giay_chung_nhan_kinh_doanh"
                    type="text"
                    class="form-control"
                    placeholder="Giấy chứng nhận"
                    tabindex="1"
                  />
                </div>
                <div class="col-md-3">
                  <date-picker
                    v-model="form_data.ngay_cap_giay_chung_nhan_kinh_doanh"
                    placeholder="Ngày cấp"
                    tabindex="17"
                    :config="optionsDate"
                  ></date-picker>
                </div>
                <div class="col-md-6">
                  <multiselect
                    v-model="form_data.co_quan_cap"
                    id="ajax"
                    label="ten"
                    track-by="id"
                    placeholder="Gõ tên cơ quan cấp"
                    selectLabel="Nhấn enter để chọn"
                    deselectLabel="Nhấn enter để bỏ chọn"
                    selectedLabel="Đã chọn"
                    open-direction="bottom"
                    :options="this.co_quan_to_chucs"
                    :multiple="false"
                    :searchable="true"
                    :internal-search="false"
                    :clear-on-select="false"
                    :close-on-select="true"
                    :options-limit="300"
                    :limit="3"
                    :max-height="600"
                    :show-no-results="false"
                    :hide-selected="false"
                    :tabindex="1"
                    :clearOnSelect="true"
                  >
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                </div>
              </div> -->

              <!-- <label>Chứng nhận đầu tư</label>
              <div class="row">
                <div class="col-md-3">
                  <input
                    v-model="form_data.so_giay_chung_nhan_dau_tu"
                    type="text"
                    class="form-control"
                    placeholder="Giấy chứng nhận"
                    tabindex="1"
                  />
                </div>
                <div class="col-md-3">
                  <date-picker
                    v-model="form_data.ngay_cap_giay_chung_nhan_dau_tu"
                    placeholder="Ngày cấp"
                    tabindex="17"
                    :config="optionsDate"
                  ></date-picker>
                </div>
                <div class="col-md-6">
                  <multiselect
                    v-model="form_data.co_quan_cap_dau_tu"
                    id="ajax"
                    label="ten"
                    track-by="id"
                    placeholder="Gõ tên cơ quan đầu tư"
                    selectLabel="Nhấn enter để chọn"
                    deselectLabel="Nhấn enter để bỏ chọn"
                    selectedLabel="Đã chọn"
                    open-direction="bottom"
                    :options="this.co_quan_to_chucs"
                    :multiple="false"
                    :searchable="true"
                    :internal-search="false"
                    :clear-on-select="false"
                    :close-on-select="true"
                    :options-limit="300"
                    :limit="3"
                    :max-height="600"
                    :show-no-results="false"
                    :hide-selected="false"
                    :tabindex="1"
                    :clearOnSelect="true"
                  >
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                </div>
              </div> -->

              <div class="row">
                <div class="col-md-3">
                  <label>Diện tích&nbsp;</label>
                  <ejs-numerictextbox
                    class="form-control"
                    format=".###"
                    v-model="form_data.dien_tich"
                    type="text"
                  ></ejs-numerictextbox>
                  <span class="help-block" v-if="errors.dien_tich">
                    <strong>{{ errors.dien_tich }}</strong>
                  </span>
                </div>
                <div class="col-md-3">
                  <label>Đơn vị đo</label>
                  <multiselect
                    v-model="form_data.don_vi_dien_tich"
                    id="ajax"
                    label="ten"
                    track-by="id"
                    placeholder="Gõ đơn vị"
                    selectLabel="Nhấn enter để chọn"
                    deselectLabel="Nhấn enter để bỏ chọn"
                    selectedLabel="Đã chọn"
                    open-direction="bottom"
                    :options="this.don_vi_dien_tichs"
                    :multiple="false"
                    :searchable="true"
                    :internal-search="false"
                    :clear-on-select="true"
                    :close-on-select="true"
                    :options-limit="300"
                    :limit="3"
                    :show-no-results="false"
                    :hide-selected="false"
                    :tabindex="1"
                  >
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                  <span class="help-block" v-if="errors.don_vi_dien_tich">
                    <strong>{{ errors.don_vi_dien_tich }}</strong>
                  </span>
                </div>
                <div class="col-md-3">
                  <label>Lượng nước sử dụng</label>
                  <ejs-numerictextbox
                    class="form-control"
                    format=".###"
                    v-model="form_data.luong_nuoc_su_dung"
                    type="text"
                  ></ejs-numerictextbox>
                </div>
                <div class="col-md-3">
                  <label>Đơn vị đo</label>
                  <multiselect
                    v-model="form_data.don_vi_luu_luong_nuoc"
                    id="don_vi_luu_luong_nuoc"
                    label="ten"
                    track-by="id"
                    placeholder="Gõ đơn vị"
                    selectLabel="Nhấn enter để chọn"
                    deselectLabel="Nhấn enter để bỏ chọn"
                    selectedLabel="Đã chọn"
                    open-direction="bottom"
                    :options="this.don_vi_luu_luong_nuoc_thais"
                    :multiple="false"
                    :searchable="true"
                    :clear-on-select="true"
                    :close-on-select="true"
                    :options-limit="300"
                    :limit="3"
                    :show-no-results="false"
                    :hide-selected="false"
                    :tabindex="1"
                  >
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label
                    >Số lượng lao động&nbsp;<span style="color:#d81b60;">
                      (người)</span
                    ></label
                  >
                  <input
                    class="form-control"
                    v-model="form_data.so_nguoi_lao_dong"
                    type="number"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label>Nguyên liệu chính sử dụng</label>
                  <textarea
                    v-model="form_data.nguyen_lieu_chinh"
                    class="form-control"
                    tabindex="1"
                  ></textarea>
                </div>
                <div class="col-md-6">
                  <label>Nhiên liệu chính sử dụng</label>
                  <textarea
                    v-model="form_data.nhien_lieu_chinh"
                    class="form-control"
                    tabindex="1"
                  ></textarea>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label>Hóa chất sử dụng</label>
                  <textarea
                    v-model="form_data.hoa_chat_su_dung"
                    class="form-control"
                    tabindex="1"
                  ></textarea>
                </div>
                <div class="col-md-6">
                  <label>Nguồn nước sử dụng</label>
                  <textarea
                    v-model="form_data.nguon_nuoc_su_dung"
                    class="form-control"
                    tabindex="28"
                  ></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="row block-multiple-rows">
            <div class="col-lg-12" style="margin-top: 5px;">
              <label>THÔNG TIN LOẠI NGÀNH NGHỀ KINH TẾ</label>
              <p class="text-muted">
                (Chọn loại ngành nghề kinh tế ở Việt Nam, nhập thông tin Công suất thiết kế/Quy mô,
                công suất hoạt động/quy mô và nhấn + để thêm vào danh sách loại
                hình cơ sở)
              </p>
              <hr style="margin-top: 7px;margin-bottom: 7px;" />
            </div>
            <div class="col-md-4">
              <label
                >Loại ngành nghề kinh tế&nbsp;<span style="color:red">*</span></label
              >
              <multiselect
                v-model="
                  intermediate_data.cong_suat_hoat_dong_new_item.loai_hinh_co_so
                "
                placeholder="Chọn loại hình hoạt động"
                label="ten"
                track-by="id"
                group-values="childrens"
                group-label="parent"
                :group-select="false"
                :options="loai_hinh_co_sos"
                :multiple="false"
                selectLabel="Nhấn enter để chọn"
                deselectLabel="Nhấn enter để bỏ chọn"
                selectedLabel="Đã chọn"
                :tabindex="10"
              ></multiselect>
              <span class="help-block" v-if="errors.loai_hinh_co_so">
                <strong>{{ errors.loai_hinh_co_so }}</strong>
              </span>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Công suất thiết kế/Quy mô</label>
                <ejs-numerictextbox
                  class="form-control"
                  format=""
                  v-model="
                    intermediate_data.cong_suat_hoat_dong_new_item
                      .cong_suat_thiet_ke
                  "
                  type="text"
                ></ejs-numerictextbox>
                <span class="help-block" v-if="errors.cong_suat_thiet_ke">
                  <strong>{{ errors.cong_suat_thiet_ke }}</strong>
                </span>
                <label>Công suất hoạt động/Quy mô</label>
                <ejs-numerictextbox
                  class="form-control"
                  format=""
                  v-model="
                    intermediate_data.cong_suat_hoat_dong_new_item
                      .cong_suat_hoat_dong
                  "
                  type="text"
                ></ejs-numerictextbox>
                <span class="help-block" v-if="errors.cong_suat_hoat_dong">
                  <strong>{{ errors.cong_suat_hoat_dong }}</strong>
                </span>
              </div>
            </div>
            <div class="col-md-4">
              <label>Đơn vị đo</label>
              <multiselect
                v-model="
                  intermediate_data.cong_suat_hoat_dong_new_item
                    .don_vi_cong_suat_thiet_ke
                "
                id="don_vi_luu_luong_nuoc"
                label="ten"
                track-by="id"
                placeholder="Gõ đơn vị"
                selectLabel="Nhấn enter để chọn"
                deselectLabel="Nhấn enter để bỏ chọn"
                selectedLabel="Đã chọn"
                open-direction="bottom"
                :options="this.don_vi_cong_suat_thiet_kes"
                :multiple="false"
                :searchable="true"
                :clear-on-select="true"
                :close-on-select="true"
                :options-limit="300"
                :limit="3"
                :show-no-results="false"
                :hide-selected="false"
                :tabindex="1"
              >
                <span slot="noResult">Không tìm thấy kết quả</span>
              </multiselect>
              <span
                class="help-block"
                v-if="errors.don_vi_do_cong_suat_thiet_ke"
              >
                <strong>{{ errors.don_vi_do_cong_suat_thiet_ke }}</strong>
              </span>
              <label>Đơn vị đo</label>
              <multiselect
                v-model="
                  intermediate_data.cong_suat_hoat_dong_new_item
                    .don_vi_cong_suat_hoat_dong
                "
                id="don_vi_cong_suat_hoat_dong"
                label="ten"
                track-by="id"
                placeholder="Gõ đơn vị"
                selectLabel="Nhấn enter để chọn"
                deselectLabel="Nhấn enter để bỏ chọn"
                selectedLabel="Đã chọn"
                open-direction="bottom"
                :options="this.don_vi_cong_suat_hoat_dongs"
                :multiple="false"
                :searchable="true"
                :clear-on-select="true"
                :close-on-select="true"
                :options-limit="300"
                :limit="3"
                :show-no-results="false"
                :hide-selected="false"
                :tabindex="1"
              >
                <span slot="noResult">Không tìm thấy kết quả</span>
              </multiselect>
              <span
                class="help-block"
                v-if="errors.don_vi_do_cong_suat_hoat_dong"
              >
                <strong>{{ errors.don_vi_do_cong_suat_hoat_dong }}</strong>
              </span>
            </div>

            <div class="col col-sm-1" style="margin-top: 24px">
              <label>Thêm</label>
              <a
                type="button"
                class="btn bg-flat btn-flat pull-center"
                @click="addCongSuatHoatDong()"
              >
                <i class="fa fa-plus"></i>
              </a>
            </div>
            <div class="col-lg-12">
              <hr style="margin-top: 5px;margin-bottom: 5px;" />
              <label>DANH SÁCH LOẠI NGÀNH NGHỀ KINH TẾ</label>
              <p class="text-muted">
                (Bảng thông tin danh sách loại hình cơ sở của cơ sở thanh tra)
              </p>
              <hr style="margin-top: 7px;margin-bottom: 7px;" />
            </div>
            <div class="col-md-12">
              <table class="table table-hover table-responsive" role="grid">
                <tbody>
                  <tr class="row-table-header">
                    <th>Loại hình cơ sở</th>
                    <th>Công suất thiết kế/Quy mô</th>
                    <th>Đơn vị đo</th>
                    <th>Công suất hoạt động/Quy mô</th>
                    <th>Đơn vị đo</th>
                    <th style="width: 5%; text-align: center">Xóa</th>
                  </tr>
                  <tr v-for="(item, index) in cong_suat_hoat_dongs" :key="index">
                    <td>{{ item.ten_loai_cs }}</td>
                    <td>{{ item.thong_so_thiet_ke | numFormat }}</td>
                    <td>{{ item.ten_don_vi_tk }}</td>
                    <td>{{ item.thong_so_hoat_dong | numFormat }}</td>
                    <td>{{ item.ten_don_vi_hd }}</td>
                    <td align="center">
                      <a href="#" @click="deletecshd(index)" class="btn">
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
      <br />
      <div class="row"></div>
      <div class="box-footer box-footer-form">
        <button
          type="submit"
          id="onsubmit"
          class="btn bg-olive btn-flat pull-right"
          :disabled="is_loading"
          @click="addCoSoThanhTra"
          tabindex="31"
        >
          <i class="fa fa-plus"></i> Thêm mới
        </button>
        <a
          href="/cososanxuat"
          id="btnback"
          class="btn btn-default btn-flat"
          tabindex="31"
        >
          <i class="fa fa-undo"></i> Trở lại
        </a>
      </div>
    </div>
  </div>
</template>

<script>
import * as env from "../env.js";
import money from "v-money";
import datePicker from "vue-bootstrap-datetimepicker";
import { en, vi } from "vuejs-datepicker/dist/locale";
import Multiselect from "vue-multiselect";

export default {
  components: {
    Multiselect,
    datePicker
  },
  data: function() {
    return {
      optionsDate: {
        format: "DD/MM/YYYY",
        useCurrent: false,
        locale: "vi"
      },
      en: en,
      vi: vi,
      money: {
        decimal: ".",
        thousands: ",",
        prefix: "",
        suffix: "",
        precision: 0,
        masked: false
      },
      don_vi_dien_tichs: [],
      cong_suat_hoat_dongs: [],
      don_vi_luu_luong_nuoc_thais: [],
      don_vi_cong_suat_hoat_dongs: [],
      don_vi_cong_suat_thiet_kes: [],
      loai_hinh_co_sos: [],
      khu_cong_nghieps: [],
      huyens: [],
      tinhs: [],
      co_quan_to_chucs: [],
      is_loading: false,
      errors: {
        dien_tich: null,
        don_vi_dien_tich: null,
        don_vi_do_cong_suat_thiet_ke: null,
        ten_co_so_thanh_tra: null,
        loai_hinh_co_so: null,
        cong_suat_thiet_ke: null,
        don_vi_do_cong_suat_hoat_dong: null,
        cong_suat_hoat_dong: null,
        tinh_thanh: null,
        dia_chi_hoat_dong: null
      },
      form_data: {
        don_vi_dien_tich: null,
        co_quan_cap: null,
        co_quan_cap_dau_tu: null,
        don_vi_luu_luong_nuoc: null,
        khu_cong_nghiep: null,
        ten_co_so_thanh_tra: null,
        xa_phuong: null,
        tinh_thanh: null,
        dia_chi_hoat_dong: null,
        nguon_nuoc_su_dung: null,
        luong_nuoc_su_dung: 0,
        hoa_chat_su_dung: null,
        nhien_lieu_chinh: null,
        nguyen_lieu_chinh: null,
        quan_huyen: null,
        so_nguoi_lao_dong: 0,
        ngay_cap_giay_chung_nhan_kinh_doanh: null,
        dien_tich: 0,
        so_giay_chung_nhan_kinh_doanh: null,
        so_giay_chung_nhan_dau_tu: null,
        ngay_cap_giay_chung_nhan_dau_tu: null,
        toa_do: { kinhdo: 105, vido: 21 }
      },
      intermediate_data: {
        change: true,
        cong_suat_hoat_dong_new_item: {
          loai_hinh_co_so: null,
          don_vi_cong_suat_hoat_dong: null,
          don_vi_cong_suat_thiet_ke: null,
          cong_suat_hoat_dong: 0,
          cong_suat_thiet_ke: 0
        }
      }
    };
  },
  methods: {
    // refresh data catalog
    refresh: function() {
      this.is_loading = true;
      try {
        axios
          .get(env.endpointhttp + "danhmuc/chuyendoidonvis")
          .then(response => {
            this.donvicongsuathoatdongs =
              response.data.result.don_vi.cong_suat_hoat_dong;
            this.don_vi_cong_suat_thiet_kes =
              response.data.result.don_vi.cong_suat_thiet_ke;
            this.don_vi_luu_luong_nuoc_thais =
              response.data.result.don_vi.luu_luong_nuoc_thai;
            this.don_vi_cong_suat_hoat_dongs =
              response.data.result.don_vi.cong_suat_hoat_dong;
            this.form_data.don_vi_luu_luong_nuoc = response.data.result
              .don_vi_goc.luu_luong_nuoc_thai
              ? response.data.result.don_vi_goc.luu_luong_nuoc_thai
              : null;
            this.don_vi_dien_tichs = response.data.result.don_vi.dien_tich;
            this.form_data.don_vi_dien_tich = response.data.result.don_vi_goc
              .dien_tich
              ? response.data.result.don_vi_goc.dien_tich
              : null;
          });
        axios.get(env.endpointhttp + "coquantochucs").then(response => {
          this.co_quan_to_chucs = response.data.result;
        });
        axios
          .get(env.endpointhttp + "loaihinhcosos?type=report")
          .then(response => {
            this.loai_hinh_co_sos = response.data.result;
          });
        axios.get(env.endpointhttp + "quanhuyens").then(response => {
          this.huyens = response.data.result;
        });
        axios.get(env.endpointhttp + "tinhthanhs").then(response => {
          this.tinhs = response.data.result;
        });
      } catch (e) {
        console.log(e.message);
      } finally {
        this.is_loading = false;
      }
    },
    asyncFindKhuCongNghiep(query) {
      if (query) {
        this.is_loading = true;
        axios
          .get(env.endpointhttp + "asynkhucongnghiep?search=" + query)
          .then(response => {
            this.khu_cong_nghieps = response.data.result;
          })
          .catch(error => {})
          .finally(() => (this.is_loading = false));
      }
    },
    changeKCN: function(query) {
      this.form_data.quan_huyen = null;
      this.form_data.xa_phuong = null;
      this.form_data.tinh_thanh = null;
      if (query) {
        this.form_data.quan_huyen = this.huyens.find(
          huyen => huyen.id === query.quan_huyen_id
        );
        this.form_data.tinh_thanh = this.tinhs.find(
          tinh => tinh.id === query.tinh_thanh_id
        );
        this.form_data.xa_phuong = query.xa_phuong;
      }
    },
    changeHuyen: function(query) {
      if (query) {
        this.form_data.tinh_thanh = this.tinhs.find(
          tinh => tinh.id === query.tinh_thanh_id
        );
      }
    },
    addCongSuatHoatDong: function() {
      this.resetErrorsThemCongSuatHoatDong();
      if (
        this.intermediate_data.cong_suat_hoat_dong_new_item.loai_hinh_co_so ==
        null
      ) {
        this.errors.loai_hinh_co_so = "Chưa chọn Loại hình cơ sở";
        return;
      } else if (
        (this.intermediate_data.cong_suat_hoat_dong_new_item
          .cong_suat_hoat_dong >
          0) &
        (this.intermediate_data.cong_suat_hoat_dong_new_item
          .don_vi_cong_suat_hoat_dong ==
          null)
      ) {
        this.errors.don_vi_do_cong_suat_hoat_dong =
          "Chưa chọn đơn vị công suất hoạt động/quy mô";
        return;
      } else if (
        (this.intermediate_data.cong_suat_hoat_dong_new_item
          .cong_suat_thiet_ke >
          0) &
        (this.intermediate_data.cong_suat_hoat_dong_new_item
          .don_vi_cong_suat_thiet_ke ==
          null)
      ) {
        this.errors.don_vi_do_cong_suat_thiet_ke =
          "Chưa chọn đơn vị Công suất thiết kế/Quy mô";
        return;
      } else {
        this.cong_suat_hoat_dongs.push({
          ten_don_vi_tk: this.intermediate_data.cong_suat_hoat_dong_new_item
            .don_vi_cong_suat_thiet_ke.ten,
          ten_don_vi_hd: this.intermediate_data.cong_suat_hoat_dong_new_item
            .don_vi_cong_suat_hoat_dong.ten,
          ten_loai_cs: this.intermediate_data.cong_suat_hoat_dong_new_item
            .loai_hinh_co_so.ten,
          loai_hinh_co_so: this.intermediate_data.cong_suat_hoat_dong_new_item
            .loai_hinh_co_so.id,
          don_vi_hoat_dong: this.intermediate_data.cong_suat_hoat_dong_new_item
            .don_vi_cong_suat_hoat_dong,
          thong_so_hoat_dong: this.intermediate_data
            .cong_suat_hoat_dong_new_item.cong_suat_hoat_dong,
          don_vi_thiet_ke: this.intermediate_data.cong_suat_hoat_dong_new_item
            .don_vi_cong_suat_thiet_ke.id,
          thong_so_thiet_ke: this.intermediate_data.cong_suat_hoat_dong_new_item
            .cong_suat_thiet_ke
        });
        (this.intermediate_data.cong_suat_hoat_dong_new_item.loai_hinh_co_so = null),
          (this.intermediate_data.cong_suat_hoat_dong_new_item.don_vi_cong_suat_hoat_dong = null),
          (this.intermediate_data.cong_suat_hoat_dong_new_item.cong_suat_hoat_dong = 0);

        (this.intermediate_data.cong_suat_hoat_dong_new_item.don_vi_cong_suat_thiet_ke = null),
          (this.intermediate_data.cong_suat_hoat_dong_new_item.cong_suat_thiet_ke = 0);
        return;
      }
    },
    resetErrorsThemCongSuatHoatDong() {
      (this.errors.don_vi_cst = null),
        (this.errors.loai_hinh_co_so = null),
        (this.errors.cong_suat_thiet_ke = null),
        (this.errors.don_vi_do_cong_suat_hoat_dong = null);
    },
    validator() {
      var valid = true;
      if (!this.form_data.dia_chi_hoat_dong) {
        this.errors.dia_chi_hoat_dong = "Thiếu địa chỉ hoạt động";
        valid = false;
      }
      if (!this.form_data.tinh_thanh) {
        this.errors.tinh_thanh = "Thiếu tỉnh thành";
        valid = false;
      }
      if (this.form_data.dien_tich < 0) {
        this.form_data.dien_tich = 0;
      }
      if (
        this.form_data.dien_tich != null &&
        this.form_data.don_vi_dien_tich.id == null
      ) {
        this.errors.don_vi_dien_tich = "Thiếu đơn vị đo";
        valid = false;
      }
      if (
        this.form_data.dien_tich == null &&
        this.form_data.don_vi_dien_tich != null
      ) {
        this.errors.dien_tich = "Thiếu thông số";
        valid = false;
      }
      if (this.form_data.so_nguoi_lao_dong < 0) {
        this.form_data.so_nguoi_lao_dong = 0;
      }
      if (!this.form_data.ten_co_so_thanh_tra) {
        this.errors.ten_co_so_thanh_tra = "Thiếu trường tên cơ sở thanh tra";
        valid = false;
      }
      if (this.cong_suat_hoat_dongs.length == 0) {
        this.errors.loai_hinh_co_so =
          "Cần nhập ít nhất thông tin một loại hình cơ sở";
        valid = false;
      }

      return valid;
    },
    limitTextKhuCongNghiep(count) {
      return `và ${count} tên khu công nghiệp khác`;
    },
    addCoSoThanhTra: function() {
      if (this.validator()) {
        this.is_loading = true;
        axios
          .post(env.endpointhttp + "cososanxuat/add", {
            congsuathd: this.cong_suat_hoat_dongs,
            coso: {
              donvinc: this.form_data.don_vi_luu_luong_nuoc.id,
              ten: this.form_data.ten_co_so_thanh_tra,
              dia_chi_lien_he: this.form_data.dia_chi_lien_he,
              khu_cong_nghiep_id: this.form_data.khu_cong_nghiep.id,
              xa_phuong: this.form_data.xa_phuong,
              quan_huyen_id: this.form_data.quan_huyen.id,
              tinh_thanh_id: this.form_data.tinh_thanh.id,
              dien_tich:
                this.form_data.dien_tich *
                this.form_data.don_vi_dien_tich.ty_le,
              so_nguoi_lao_dong: this.form_data.so_nguoi_lao_dong,
              so_giay_chung_nhan_dau_tu: this.form_data
                .so_giay_chung_nhan_dau_tu,
              ngay_cap_giay_chung_nhan_dau_tu: this.form_data
                .ngay_cap_giay_chung_nhan_dau_tu,
              so_giay_chung_nhan_kinh_doanh: this.form_data
                .so_giay_chung_nhan_kinh_doanh,
              ngay_cap_giay_chung_nhan_kinh_doanh: this.form_data
                .ngay_cap_giay_chung_nhan_kinh_doanh,
              nguyen_lieu_chinh_su_dung: this.form_data.nguyen_lieu_chinh,
              nhien_lieu_chinh_su_dung: this.form_data.nhien_lieu_chinh,
              hoa_chat_su_dung: this.form_data.hoa_chat_su_dung,
              nguon_nuoc_su_dung: this.form_data.nguon_nuoc_su_dung,
              kinh_do: this.form_data.toa_do.kinhdo,
              vi_do: this.form_data.toa_do.vido,
              luong_nuoc_su_dung:
                this.form_data.luong_nuoc_su_dung *
                this.form_data.don_vi_luu_luong_nuoc.ty_le,
              don_vi_dien_tich: this.form_data.don_vi_dien_tich.id,
              dia_chi_hoat_dong: this.form_data.dia_chi_hoat_dong
            }
          })
          .then(response => {
            window.location.href = "/cososanxuat";
          })
          .catch(error => {
            if (error.response && error.response.data) {
              var message = error.response.data.message;
              if (error.response.data.result) {
                var errors = error.response.data.result;
                for (var key in errors) {
                  if (errors.hasOwnProperty(key)) {
                    this.errors.key = errors[key][0];
                    if (Array.isArray(errors[key])) {
                      this.errors.key = errors[key].join(";");
                    }
                  }
                }
              }
            }
          })
          .finally(() => {
            this.is_loading = false;
          });
      }
    },
    getToaDo: function(value) {
      this.toa_do.kinhdo = value.kinhdo;
      this.toa_do.vido = value.vido;
    },
    getTenDiaChi: function(value) {
      this.form_data.dia_chi_hoat_dong = value;
    },
    deletecshd: function(index) {
      this.cong_suat_hoat_dongs.splice(index, 1);
    },
    getLatLonByAddressText() {
      axios
        .post(env.endpointhttp + "cososanxuat/getlatlon", {
          diachi: this.form_data.dia_chi_hoat_dong
        })
        .then(response => {
          this.form_data.toa_do.kinhdo = response.data.result.long;
          this.form_data.toa_do.vido = response.data.result.lat;
        });
    }
  },
  mounted: function() {
    this.refresh();
  }
};
</script>
