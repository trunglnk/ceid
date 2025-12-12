<template>
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <div class="row">
        <div class="col-md-5">
          <h2 class="page-header-button-header title_master_form">
            THÔNG TIN CƠ SỞ THANH TRA
          </h2>
        </div>
        <div class="col-md-7">
          <button
            type="submit"
            id="onsubmit"
            class="btn bg-olive btn-flat pull-right margin-top"
            :disabled="is_loading"
            @click="editcssx"
            v-if="
              usersystem.role_code == 'admin' ||
              usersystem.role_code == 'nhanvientrungtam' ||
              usersystem.role_code == 'adminvaquanlydanhmuc'
            "
          >
            <i class="fa fa-check"></i> Cập Nhật
          </button>
          <!-- <button type="button" class="btn btn-flat pull-right margin-top margin-right" @click="refresh"
            v-if="usersystem.role_code == 'admin' || usersystem.role_code == 'nhanvientrungtam' || usersystem.role_code == 'adminvaquanlydanhmuc'">
            <i class="fa fa-refresh"></i> Làm mới
          </button> -->
        </div>
      </div>
      <div class="row block-multiple-rows">
        <div class="col-lg-12" style="margin-top: 5px">
          <label>THÔNG TIN CHUNG</label>
          <hr style="margin-top: 7px; margin-bottom: 7px" />
        </div>
        <div class="col-lg-6 col-md-12">
          <label>Tên&nbsp;<span style="color: red">*</span></label>
          <input
            v-model="form_data.ten"
            type="text"
            class="form-control"
            tabindex="1"
            autofocus
            readonly
            placeholder="Tên cơ sở thanh tra"
          />
        </div>
        <div class="col-lg-6 col-md-12">
          <label>Địa chỉ liên hệ</label>
          <input
            v-model="form_data.dia_chi_lien_he"
            type="text"
            class="form-control"
            tabindex="1"
            readonly
            placeholder="Nhập địa chỉ liên hệ"
            rows="1"
          />
        </div>
      </div>

      <div class="row block-multiple-rows">
        <div class="col-lg-12" style="margin-top: 5px">
          <span class="mr-3" title="Xem chi tiết">
            <i
              class="fa fa-bars"
              style="cursor: pointer"
              @click="goChuDauTu(chuDauTu.id)"
            ></i
          ></span>
          <label
            style="cursor: pointer; text-decoration: underline"
            @click="goDanhSachCDT"
            >CHỦ ĐẦU TƯ</label
          >
          <hr style="margin-top: 7px; margin-bottom: 7px" />
        </div>
        <div class="col-lg-12">
          <div class="col-lg-6">
            <div v-if="!showEditChuDauTu && chuDauTu">
              <b
                @click="goChuDauTu(chuDauTu.id)"
                style="text-decoration: underline; cursor: pointer"
                >{{ chuDauTu.ten }}</b
              >
              <span class="ml-3" title="Chỉnh sửa">
                <i
                  class="fa fa-edit"
                  style="cursor: pointer"
                  @click="changeChuDauTu"
                ></i
              ></span>
            </div>
            <div style="display: flex" v-else>
              <multiselect
                v-model="chuDauTu"
                label="ten"
                track-by="id"
                placeholder="Gõ tên chủ đầu tư"
                selectLabel="Nhấn enter để chọn"
                deselectLabel="Nhấn enter để bỏ chọn"
                selectedLabel="Đã chọn"
                open-direction="bottom"
                :options="chuDauTus"
                :multiple="false"
                :searchable="true"
                :loading="is_loading_cdt"
                :internal-search="true"
                :clear-on-select="false"
                :close-on-select="true"
                :options-limit="300"
                :limit="3"
                :max-height="600"
                :show-no-results="false"
                :hide-selected="false"
                :clearOnSelect="true"
                @search-change="asyncFindChuDauTu"
              >
                <span slot="noResult">Không tìm thấy thông tin</span>
              </multiselect>
              <button
                type="submit"
                id="onsubmit"
                class="btn bg-olive btn-flat pull-right"
                @click="goAddChuDauTu"
              >
                <i class="fa fa-plus"></i> Thêm chủ đầu tư
              </button>
            </div>
          </div>
        </div>
        <br />
        <div class="col-lg-12" v-if="chuDauTu">
          <div class="col-lg-6">
            <div class="pt-2">
              Mã định danh:
              <b>{{ chuDauTu.ma_dinh_danh }}</b>
            </div>
            <div class="pt-2">
              Tỉnh thành:
              {{ chuDauTu.tinh_thanh ? chuDauTu.tinh_thanh.ten : "" }}
            </div>
          </div>
          <div class="col-lg-3">
            <div>
              Giấy đăng ký KD:
              {{ chuDauTu.so_giay_chung_nhan_dang_ky_kinh_doanh }}
            </div>
            <div class="pt-2">
              Quận Huyện:
              {{ chuDauTu.quan_huyen ? chuDauTu.quan_huyen.ten : "" }}
            </div>
          </div>
          <div class="col-lg-3">
            <div>Địa chỉ: {{ chuDauTu.dia_chi }}</div>
            <div class="pt-2">
              Xã phường:
              {{ chuDauTu.xa_phuong }}
            </div>
          </div>
        </div>
      </div>

      <div class="row block-multiple-rows padding-left padding-right">
        <div class="col-lg-12" style="margin-top: 5px">
          <label>DANH SÁCH ĐỊA ĐIỂM HOẠT ĐỘNG</label>
          <hr style="margin-top: 7px; margin-bottom: 7px" />
        </div>
        <div
          v-for="(co_so_san_xuat, index) in form_data.co_so_san_xuats"
          class="row block-multiple-rows"
          :key="index"
        >
          <div class="col-lg-12" style="margin-top: 5px">
            <label>ĐỊA ĐIỂM SỐ {{ index + 1 }}</label>
            <button
              v-if="form_data.co_so_san_xuats.length > 1"
              type="button"
              class="btn btn-flat pull-right"
              data-toggle="modal"
              :data-target="'#confirmModal' + index"
              :disabled="is_loading"
            >
              <i class="fa fa-trash-o"></i> Xóa địa điểm hoạt động
            </button>

            <div
              class="modal fade in"
              :id="'confirmModal' + index"
              style="text-align: initial"
            >
              <div class="modal-dialog" style="width: 30%">
                <div class="modal-content">
                  <div class="modal-header">
                    <button
                      type="button"
                      class="close"
                      data-dismiss="modal"
                      aria-label="Close"
                      :disabled="is_loading"
                    >
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">THÔNG BÁO</h4>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12" style="text-align: center">
                        <label class="control-label"
                          >Bạn có chắc chắn muốn xóa không ?</label
                        >
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button
                      type="button"
                      class="btn btn-default pull-left btn-flat"
                      data-dismiss="modal"
                      :disabled="is_loading"
                    >
                      <i class="fa fa-close"></i> Hủy
                    </button>
                    <button
                      type="submit"
                      id="onsubmit"
                      class="btn btn-flat bg-olive"
                      @click="deleteDiaDiemHoatDong(co_so_san_xuat)"
                      :disabled="is_loading"
                    >
                      <i class="fa fa-check"></i> Đồng ý
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <hr style="margin-top: 7px; margin-bottom: 7px" />
          </div>
          <div class="col-sm-12 col-lg-4">
            <div class="block-multiple-rows padding-left padding-right">
              <div class="row">
                <div class="col-md-12">
                  <label>Tên&nbsp;<span style="color: red">*</span></label>
                  <textarea
                    disabled
                    name="tencs"
                    v-model="co_so_san_xuat.ten"
                    type="text"
                    class="form-control"
                    tabindex="1"
                    autofocus
                    placeholder="Tên cơ sở thanh tra"
                  />
                  <span class="help-block" v-if="co_so_san_xuat.errors.tencs">
                    <strong>{{ co_so_san_xuat.errors.tencs }}</strong>
                  </span>
                </div>
                <div class="col-md-12 mt-3">
                  <label
                    >Loại hình cơ sở&nbsp;<span style="color: red"></span
                  ></label>
                  <multiselect
                    v-model="co_so_san_xuat.loai_khu_cong_nghiep"
                    open-direction="bottom"
                    label="ten"
                    track-by="ma"
                    disabled
                    placeholder="Chọn một loại hình"
                    selectLabel="Nhấn enter để chọn"
                    deselectLabel="Nhấn enter để bỏ chọn"
                    selectedLabel="Đã chọn"
                    :options="loaiHinhCoSos"
                    :multiple="false"
                    :searchable="true"
                  >
                  </multiselect>
                </div>

                <div class="col-md-12 mt-4">
                  <p class="lead">Địa chỉ hoạt động</p>
                  <label>Thuộc KCN, CCN, KCX, KCNC, làng nghề&nbsp;</label>
                  <multiselect
                    v-model="co_so_san_xuat.khu_cong_nghiep"
                    label="ten"
                    track-by="id"
                    placeholder="Gõ tên khu công nghiệp"
                    selectLabel="Nhấn enter để chọn"
                    deselectLabel="Nhấn enter để bỏ chọn"
                    selectedLabel="Đã chọn"
                    open-direction="bottom"
                    :options="khucongnghieps"
                    :multiple="false"
                    :searchable="true"
                    :loading="is_loading"
                    :internal-search="false"
                    :clear-on-select="false"
                    :close-on-select="true"
                    :options-limit="300"
                    :limit="3"
                    :limit-text="limitText"
                    :max-height="600"
                    :show-no-results="false"
                    :hide-selected="false"
                    :tabindex="1"
                    :clearOnSelect="true"
                    @search-change="asyncFindKhuCongNghiep"
                    @input="changeKCN(co_so_san_xuat)"
                  >
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                  <div class="row">
                    <div class="col-md-12 tinh">
                      <label>Tỉnh<span style="color: red">*</span></label>
                      <multiselect
                        v-model="co_so_san_xuat.tinh_thanhs"
                        label="ten"
                        track-by="id"
                        :disabled="false"
                        placeholder="Tỉnh thành"
                        selectLabel="Nhấn enter để chọn"
                        deselectLabel="Nhấn enter để bỏ chọn"
                        selectedLabel="Đã chọn"
                        open-direction="bottom"
                        :options="tinhs"
                        :multiple="true"
                        :searchable="true"
                        :loading="is_loading"
                        :internal-search="false"
                        :clear-on-select="false"
                        :close-on-select="true"
                        :options-limit="300"
                        :limit="3"
                        :limit-text="limitText"
                        :max-height="600"
                        :show-no-results="false"
                        :hide-selected="false"
                        :tabindex="1"
                        :clearOnSelect="true"
                        @search-change="asyncFindTinh"
                        @input="changeTinh(co_so_san_xuat)"
                      >
                        <span slot="noResult">Không tìm thấy kết quả</span>
                      </multiselect>
                      <span
                        class="help-block"
                        v-if="co_so_san_xuat.errors.tinh"
                      >
                        <strong>{{ co_so_san_xuat.errors.tinh }}</strong>
                      </span>
                    </div>
                    <div class="col-md-12 huyen">
                      <label>Huyện</label>
                      <multiselect
                        v-model="co_so_san_xuat.quan_huyens"
                        id="ajax"
                        label="ten"
                        track-by="id"
                        :disabled="false"
                        placeholder="Quận huyện"
                        selectLabel="Nhấn enter để chọn"
                        deselectLabel="Nhấn enter để bỏ chọn"
                        selectedLabel="Đã chọn"
                        open-direction="bottom"
                        :options="co_so_san_xuat.available_huyens || huyens"
                        :multiple="true"
                        :searchable="true"
                        :loading="is_loading"
                        :internal-search="false"
                        :clear-on-select="false"
                        :close-on-select="true"
                        :options-limit="300"
                        :limit="3"
                        :limit-text="limitText"
                        :max-height="600"
                        :show-no-results="false"
                        :hide-selected="false"
                        :tabindex="1"
                        :clearOnSelect="true"
                        @input="changeHuyen(co_so_san_xuat)"
                      >
                        <span slot="noResult">Không tìm thấy kết quả</span>
                      </multiselect>
                    </div>
                    <div class="col-md-12 xaPhuong">
                      <label>Xã</label>
                      <!-- <input name="xa" v-model="co_so_san_xuat.xa_phuong" type="text" class="form-control" :disabled="true"
                        placeholder="Xã" tabindex="1" /> -->

                      <multiselect
                        v-model="co_so_san_xuat.phuong_xas"
                        id="ajax"
                        label="ten"
                        track-by="id"
                        :disabled="false"
                        placeholder="Xã phường"
                        selectLabel="Nhấn enter để chọn"
                        deselectLabel="Nhấn enter để bỏ chọn"
                        selectedLabel="Đã chọn"
                        open-direction="top"
                        :options="phuongXas"
                        :multiple="true"
                        :searchable="true"
                        :loading="is_loading"
                        :internal-search="false"
                        :clear-on-select="false"
                        :close-on-select="true"
                        :options-limit="300"
                        :limit="3"
                        :limit-text="limitText"
                        :max-height="600"
                        :show-no-results="false"
                        :hide-selected="false"
                        :tabindex="1"
                        :clearOnSelect="true"
                      >
                        <span slot="noResult">Không tìm thấy kết quả</span>
                      </multiselect>
                    </div>
                  </div>
                  <label>Tọa độ</label>
                  <div class="row">
                    <div class="col-md-6">
                      <input
                        name="kinh_do"
                        v-model="co_so_san_xuat.kinh_do"
                        type="text"
                        class="form-control"
                        placeholder="Kinh độ"
                        tabindex="1"
                      />
                    </div>
                    <div class="col-md-6">
                      <input
                        name="vi_do"
                        v-model="co_so_san_xuat.vi_do"
                        type="text"
                        class="form-control"
                        placeholder="Vĩ độ"
                        tabindex="1"
                      />
                    </div>
                  </div>
                  <div class="row" style="padding: 10px 15px">
                    <div class="welcome d-flex align-center">
                      <!-- <google-map @tendiachi="getTenDiaChi($event, co_so_san_xuat)" :css="1"
                        @toado="getToaDo($event, co_so_san_xuat)" name="example" v-bind:toado="{
                          kinhdo: co_so_san_xuat.kinh_do,
                          vido: co_so_san_xuat.vi_do,
                        }" style="margin-top: -5px" :hidenInput="0">
                      </google-map> -->
                      <Map style="height: 300px; width: 320px" :toaDo="toaDo" />
                    </div>
                  </div>
                  <label
                    >Địa chỉ hoạt động <span style="color: red">*</span></label
                  >
                  <textarea
                    name="diachihd"
                    v-model="co_so_san_xuat.dia_chi_hoat_dong"
                    type="text"
                    class="form-control"
                    tabindex="1"
                    placeholder="Nhập địa chỉ hoạt động"
                    @change="getLatLonByAddressText(co_so_san_xuat)"
                  ></textarea>
                  <span class="help-block" v-if="co_so_san_xuat.errors.diachi">
                    <strong>{{ co_so_san_xuat.errors.diachi }}</strong>
                  </span>
                  <label>Địa chỉ liên hệ</label>
                  <textarea
                    name="diachi"
                    v-model="co_so_san_xuat.dia_chi_lien_he"
                    type="text"
                    class="form-control"
                    tabindex="1"
                    placeholder="Nhập địa chỉ liên hệ"
                  ></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12 col-lg-8">
            <div class="row block-multiple-rows">
              <div class="col-lg-12" style="margin-top: 5px">
                <label>THÔNG TIN CHUNG</label>
                <p class="text-muted">
                  (Thông tin chung của kết luận thanh tra gần nhất)
                </p>
                <hr style="margin-top: 7px; margin-bottom: 7px" />
              </div>
              <div class="col-md-12">
                <!-- <label>Chứng nhận kinh doanh</label>
                <div class="row">
                  <div class="col-md-3">
                    <input
                      v-model="co_so_san_xuat.so_giay_chung_nhan_kinh_doanh"
                      type="text"
                      class="form-control"
                      placeholder="Giấy chứng nhận"
                      tabindex="1"
                    />
                  </div>
                  <div class="col-md-3">
                    <date-picker
                      v-model="
                        co_so_san_xuat.ngay_cap_giay_chung_nhan_kinh_doanh
                      "
                      placeholder="Ngày cấp"
                      tabindex="17"
                      :config="optionsDate"
                    >
                    </date-picker>
                  </div>
                  <div class="col-md-6">
                    <multiselect
                      v-model="co_so_san_xuat.co_quan_kinh_doanh"
                      label="ten"
                      track-by="id"
                      placeholder="Gõ tên cơ quan cấp"
                      selectLabel="Nhấn enter để chọn"
                      deselectLabel="Nhấn enter để bỏ chọn"
                      selectedLabel="Đã chọn"
                      open-direction="bottom"
                      :options="coquantochucs"
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
                </div>

                <label>Chứng nhận đầu tư</label>
                <div class="row">
                  <div class="col-md-3">
                    <input
                      v-model="co_so_san_xuat.so_giay_chung_nhan_dau_tu"
                      type="text"
                      class="form-control"
                      placeholder="Giấy chứng nhận"
                      tabindex="1"
                    />
                  </div>
                  <div class="col-md-3">
                    <date-picker
                      v-model="co_so_san_xuat.ngay_cap_giay_chung_nhan_dau_tu"
                      placeholder="Ngày cấp"
                      tabindex="17"
                      :config="optionsDate"
                    >
                    </date-picker>
                  </div>
                  <div class="col-md-6">
                    <multiselect
                      v-model="co_so_san_xuat.co_quan_dau_tu"
                      label="ten"
                      track-by="id"
                      placeholder="Gõ tên cơ quan cấp"
                      selectLabel="Nhấn enter để chọn"
                      deselectLabel="Nhấn enter để bỏ chọn"
                      selectedLabel="Đã chọn"
                      open-direction="bottom"
                      :options="coquantochucs"
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
                  <div class="col-md-12">
                    <label>Loại hình nguy cơ gây ô nhiễm</label>
                    <multiselect
                      v-model="co_so_san_xuat.loaiONhiem"
                      label="ten"
                      track-by="id"
                      placeholder="Chọn các loại hình gây ô nhiễm"
                      selectLabel="Nhấn enter để chọn"
                      deselectLabel="Nhấn enter để bỏ chọn"
                      selectedLabel="Đã chọn"
                      open-direction="bottom"
                      :options="loaiHinhONhiem"
                      :multiple="true"
                      :searchable="true"
                      :clear-on-select="true"
                      :disabled="true"
                      :close-on-select="true"
                    >
                      <span slot="noResult">Không tìm thấy kết quả</span>
                    </multiselect>
                  </div>
                  <div class="col-md-3">
                    <label>Diện tích&nbsp;</label>
                    <ejs-numerictextbox
                      class="form-control"
                      format=".###"
                      v-model="co_so_san_xuat.dien_tich"
                      type="text"
                    >
                    </ejs-numerictextbox>
                    <span
                      class="help-block"
                      v-if="co_so_san_xuat.errors.dien_tich"
                    >
                      <strong>{{ co_so_san_xuat.errors.dien_tich }}</strong>
                    </span>
                  </div>
                  <div class="col-md-3">
                    <label>Đơn vị đo</label>
                    <multiselect
                      v-model="co_so_san_xuat.don_vi_dien_tich"
                      id="ajax"
                      label="ten"
                      track-by="id"
                      placeholder="Gõ đơn vị"
                      selectLabel="Nhấn enter để chọn"
                      deselectLabel="Nhấn enter để bỏ chọn"
                      selectedLabel="Đã chọn"
                      open-direction="bottom"
                      :options="donvidientich"
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
                      v-model="co_so_san_xuat.luong_nuoc_su_dung"
                      type="text"
                    >
                    </ejs-numerictextbox>
                  </div>
                  <div class="col-md-3">
                    <label>Đơn vị đo</label>
                    <multiselect
                      v-model="co_so_san_xuat.donvinc"
                      id="don_vi_luu_luong_nuoc"
                      label="ten"
                      track-by="id"
                      placeholder="Gõ đơn vị"
                      selectLabel="Nhấn enter để chọn"
                      deselectLabel="Nhấn enter để bỏ chọn"
                      selectedLabel="Đã chọn"
                      open-direction="bottom"
                      :options="donvinuoc"
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
                      >Số lượng lao động&nbsp;<span style="color: #d81b60">
                        (người)</span
                      ></label
                    >
                    <input
                      class="form-control"
                      v-model="co_so_san_xuat.so_nguoi_lao_dong"
                      type="number"
                    />
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <label>Nguyên liệu chính sử dụng</label>
                    <textarea
                      v-model="co_so_san_xuat.nguyen_lieu_chinh_su_dung"
                      class="form-control"
                      tabindex="1"
                    ></textarea>
                  </div>
                  <div class="col-md-6">
                    <label>Nhiên liệu chính sử dụng</label>
                    <textarea
                      v-model="co_so_san_xuat.nhien_lieu_chinh_su_dung"
                      class="form-control"
                      tabindex="1"
                    ></textarea>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <label>Hóa chất sử dụng</label>
                    <textarea
                      v-model="co_so_san_xuat.hoa_chat_su_dung"
                      class="form-control"
                      tabindex="1"
                    ></textarea>
                  </div>
                  <div class="col-md-6">
                    <label>Nguồn nước sử dụng</label>
                    <textarea
                      v-model="co_so_san_xuat.nguon_nuoc_su_dung"
                      class="form-control"
                      tabindex="1"
                    ></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="row block-multiple-rows">
              <div
                class="col-lg-12"
                style="margin-top: 5px"
                v-if="
                  usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'nhanvientrungtam' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
                "
              >
                <label>THÔNG TIN LOẠI NGÀNH NGHỀ KINH TẾ</label>
                <p class="text-muted">
                  (Chọn loại ngành nghề kinh tế ở Việt Nam, nhập thông tin công
                  suất thiết kế/Quy mô, công suất hoạt động và nhấn + để thêm
                  vào danh sách Loại ngành nghề kinh tế kinh tế)
                </p>
                <hr style="margin-top: 7px; margin-bottom: 7px" />
              </div>
              <div
                class="col-md-12 mb-4"
                v-if="
                  usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'nhanvientrungtam' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
                "
              >
                <label
                  >Loại ngành nghề kinh tế theo QĐ 27/2018/QĐ-TTg&nbsp;<span
                    style="color: red"
                    >*</span
                  ></label
                >
                <!-- <multiselect
                  v-model="co_so_san_xuat.intermediate_data.loaics"
                  placeholder="Chọn loại hình hoạt động"
                  label="ten"
                  track-by="id"
                  group-values="childrens"
                  group-label="parent"
                  :group-select="false"
                  :options="loaicosos"
                  :multiple="false"
                  selectLabel="Nhấn enter để chọn"
                  deselectLabel="Nhấn enter để bỏ chọn"
                  selectedLabel="Đã chọn"
                  :tabindex="10"
                >
                </multiselect>
                <span class="help-block" v-if="co_so_san_xuat.errors.loaics">
                  <strong>{{ co_so_san_xuat.errors.loaics }}</strong>
                </span> -->

                <!-- <treeselect :multiple="false" :options="loaiHinhs"
                  placeholder="Chọn một Loại ngành nghề kinh tế kinh tế ở Việt Nam"
                  v-model="co_so_san_xuat.intermediate_data.loaics" value-format="object" v-validate="'required'"
                  :searchable="true" @search-change="searchLoaiNganhNghe">
                  <label slot="option-label" slot-scope="{
                      node,
                    }">
                    {{ node.raw ? node.raw.ma : "" }}. {{ node.label }}
                  </label>
                </treeselect> -->
                <multiselect
                  v-model="co_so_san_xuat.intermediate_data.loaics"
                  track-by="id"
                  :custom-label="({ ten, ma }) => `${ma}. ${ten}`"
                  placeholder="Loại ngành nghề kinh tế Việt Nam"
                  selectLabel="Nhấn enter để chọn"
                  deselectLabel="Nhấn enter để bỏ chọn"
                  selectedLabel="Đã chọn"
                  open-direction="bottom"
                  :options="loaiHinhs"
                  :multiple="false"
                  :searchable="true"
                  :clear-on-select="true"
                  :close-on-select="true"
                >
                  <span slot="noResult">Không tìm thấy kết quả</span>
                  <template slot="option" slot-scope="props">
                    <div>{{ props.option.ma }}. {{ props.option.ten }}</div>
                  </template>
                </multiselect>

                <span class="help-block" v-if="co_so_san_xuat.errors.loaics">
                  <strong>{{ co_so_san_xuat.errors.loaics }}</strong>
                </span>
              </div>
              <div
                class="col-md-12 mb-4"
                v-if="
                  usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'nhanvientrungtam' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
                "
              >
                <label
                  >Loại hình hoạt động theo TT 04/2012/TT-BTNMT &nbsp;</label
                >

                <multiselect
                  v-model="co_so_san_xuat.intermediate_data.loaihd"
                  label="ten"
                  track-by="id"
                  placeholder="Loại hình hoạt động"
                  selectLabel="Nhấn enter để chọn"
                  deselectLabel="Nhấn enter để bỏ chọn"
                  selectedLabel="Đã chọn"
                  open-direction="bottom"
                  :options="loaiHinhHoatDongs"
                  :multiple="false"
                  :searchable="true"
                  :clear-on-select="true"
                  :close-on-select="true"
                >
                  <span slot="noResult">Không tìm thấy kết quả</span>
                </multiselect>
              </div>
              <div
                class="col-md-5"
                v-if="
                  usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'nhanvientrungtam' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
                "
              >
                <div class="form-group">
                  <label>Công suất thiết kế/Quy mô</label>
                  <ejs-numerictextbox
                    class="form-control"
                    format=""
                    v-model="co_so_san_xuat.intermediate_data.congsuattk"
                    type="text"
                  >
                  </ejs-numerictextbox>
                  <span
                    class="help-block"
                    v-if="co_so_san_xuat.errors.congsuattk"
                  >
                    <strong>{{ co_so_san_xuat.errors.congsuattk }}</strong>
                  </span>
                  <br />
                  <label>Công suất hoạt động/Quy mô</label>
                  <ejs-numerictextbox
                    class="form-control"
                    format=""
                    v-model="co_so_san_xuat.intermediate_data.congsuathd"
                    type="text"
                  >
                  </ejs-numerictextbox>
                  <span
                    class="help-block"
                    v-if="co_so_san_xuat.errors.congsuathd"
                  >
                    <strong>{{ co_so_san_xuat.errors.congsuathd }}</strong>
                  </span>
                </div>
              </div>
              <div
                class="col-md-6"
                v-if="
                  usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'nhanvientrungtam' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
                "
              >
                <label>Đơn vị đo</label>
                <multiselect
                  v-model="co_so_san_xuat.intermediate_data.don_vi_cstk"
                  id="don_vi_luu_luong_nuoc"
                  label="ten"
                  track-by="id"
                  placeholder="Gõ đơn vị"
                  selectLabel="Nhấn enter để chọn"
                  deselectLabel="Nhấn enter để bỏ chọn"
                  selectedLabel="Đã chọn"
                  open-direction="bottom"
                  :options="donvicongsuatthietkes"
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
                  v-if="co_so_san_xuat.errors.don_vi_cstk"
                >
                  <strong>{{ co_so_san_xuat.errors.don_vi_cstk }}</strong>
                </span>
                <br />
                <label>Đơn vị đo</label>
                <multiselect
                  v-model="co_so_san_xuat.intermediate_data.don_vi_cshd"
                  id="don_vi_cong_suat_hoat_dong"
                  label="ten"
                  track-by="id"
                  placeholder="Gõ đơn vị"
                  selectLabel="Nhấn enter để chọn"
                  deselectLabel="Nhấn enter để bỏ chọn"
                  selectedLabel="Đã chọn"
                  open-direction="bottom"
                  :options="donvicongsuathoatdongs"
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
                  v-if="co_so_san_xuat.errors.don_vi_cshd"
                >
                  <strong>{{ co_so_san_xuat.errors.don_vi_cshd }}</strong>
                </span>
              </div>

              <div
                class="col col-sm-1"
                style="margin-top: 60px"
                v-if="
                  usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'nhanvientrungtam' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
                "
              >
                <button
                  type="button"
                  style="height: 60px; width: 60px"
                  class="btn bg-flat btn-flat pull-center"
                  @click="addcshd(co_so_san_xuat, index)"
                  title="Thêm loại ngành nghề"
                >
                  <i class="fa fa-plus"></i>
                </button>
              </div>
              <div class="col-lg-12 col-md-12">
                <hr style="margin-top: 5px; margin-bottom: 5px" />
                <label>DANH SÁCH LOẠI NGÀNH NGHỀ KINH TẾ</label>
                <p class="text-muted">
                  (Bảng thông tin danh sách loại ngành nghề kinh tế kinh tế của
                  cơ sở thanh tra)
                </p>
                <hr style="margin-top: 7px; margin-bottom: 7px" />
              </div>
              <div class="col-md-12">
                <table class="table table-hover table-responsive" role="grid">
                  <tbody>
                    <tr class="row-table-header">
                      <th>Loại ngành nghề kinh tế</th>
                      <th>Loại hình hoạt động</th>
                      <th style="text-align: center">
                        Công suất thiết kế/Quy mô
                      </th>
                      <th style="text-align: center">Đơn vị đo</th>
                      <th style="text-align: center">
                        Công suất hoạt động/Quy mô
                      </th>
                      <th style="text-align: center">Đơn vị đo</th>
                      <th style="text-align: center">Xóa</th>
                    </tr>
                    <tr
                      v-for="(item, index) in co_so_san_xuat.chi_tiet_cong_suat"
                      :key="index"
                    >
                      <td>{{ item.loai_hinh.ten || item.loai_hinh.label }}</td>
                      <td>
                        {{
                          item.loai_hinh_hoat_dong
                            ? item.loai_hinh_hoat_dong.ten
                            : ""
                        }}
                      </td>
                      <td style="text-align: center">
                        {{ item.thong_so | numFormat }}
                      </td>
                      <td style="text-align: center">
                        {{ item.don_vi ? item.don_vi.ten : null }}
                      </td>
                      <td style="text-align: center">
                        {{ item.thong_so_hoat_dong | numFormat }}
                      </td>
                      <td style="text-align: center">
                        {{ item.don_vi_h_d ? item.don_vi_h_d.ten : null }}
                      </td>
                      <td align="center">
                        <a
                          @click="
                            deletecshd(co_so_san_xuat.chi_tiet_cong_suat, index)
                          "
                          class="btn"
                        >
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

      <div class="row block-multiple-rows">
        <div class="col-lg-12" style="margin-top: 5px">
          <label>DANH SÁCH KẾT QUẢ THANH TRA</label>
          <p class="text-muted"></p>
          <hr style="margin-top: 7px; margin-bottom: 7px" />
        </div>
        <div class="col-sm-12">
          <table class="table table-hover table-responsive" role="grid">
            <thead>
              <tr role="row" class="row-table-header">
                <th>Số kết luận TT</th>
                <th>Quyết định thanh tra</th>
                <th>Ngày ban hành</th>
                <th>Trạng thái đồng bộ</th>
                <th>Người cập nhật</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(item, index) in ket_qua_thanh_tras"
                role="row"
                :key="index"
              >
                <td>
                  <div>
                    <a
                      :href="'/ketquathanhtra/edit/' + item.id"
                      target="_blank"
                    >
                      {{ item.so_quyet_dinh }}
                    </a>
                  </div>
                  <div style="font-size: 14px; color: gray">
                    <a
                      :href="'/ketquathanhtra/edit/' + item.id"
                      target="_blank"
                    >
                      {{ item.ma_dinh_danh }}
                    </a>
                  </div>
                </td>
                <td>
                  <div>
                    <a
                      :href="'/ketquathanhtra/edit/' + item.id"
                      target="_blank"
                    >
                      {{ item.quyet_dinh_thanh_tra.so_quyet_dinh }}
                    </a>
                  </div>
                  <div style="font-size: 14px; color: gray">
                    <a
                      :href="'/ketquathanhtra/edit/' + item.id"
                      target="_blank"
                    >
                      {{ item.quyet_dinh_thanh_tra.ma_dinh_danh }}
                    </a>
                  </div>
                </td>
                <td>{{ item.ngay_thanh_tra }}</td>
                <td>
                  {{
                    item.trang_thai_dong_bo === "da_dong_bo"
                      ? "Đã đồng bộ"
                      : "Chưa đồng bộ"
                  }}
                </td>
                <td>{{ item.nguoi_cap_nhat.name }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="box-footer box-footer-form">
        <button
          type="submit"
          id="onsubmit"
          class="btn bg-olive btn-flat pull-right"
          :disabled="is_loading"
          @click="editcssx"
          tabindex="31"
          v-if="
            usersystem.role_code == 'admin' ||
            usersystem.role_code == 'nhanvientrungtam' ||
            usersystem.role_code == 'adminvaquanlydanhmuc'
          "
        >
          <i class="fa fa-check"></i> Cập Nhật
        </button>
        <button
          type="submit"
          id="onsubmit"
          class="btn btn-default btn-flat pull-right margin-right"
          data-toggle="modal"
          data-target="#confirmXuatExcel"
        >
          <i class="fa fa-file-excel-o"></i> Xuất file
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

      <div
        class="modal fade in"
        id="confirmXuatExcel"
        style="text-align: initial"
      >
        <div class="modal-dialog" style="width: 40%">
          <div class="modal-content">
            <div class="modal-header">
              <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
              <label>Chọn khoảng thời gian thanh tra</label>
              <p class="text-muted">
                (Nếu để trống, hệ thống sẽ tự động xuất dữ liệu từ thời điểm
                hiện tại lùi về 10 năm trước đó)
              </p>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <date-picker
                    v-model="tungay"
                    placeholder="Từ ngày"
                    tabindex="17"
                    :config="optionsDate"
                  ></date-picker>
                </div>
                <div class="col-md-6">
                  <date-picker
                    v-model="denngay"
                    placeholder="Đến ngày"
                    tabindex="17"
                    :config="optionsDate"
                  >
                  </date-picker>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="submit"
                id="onsubmit"
                class="btn btn-flat bg-olive"
                @click="downloadExcel()"
              >
                <i class="fa fa-check"></i> Đồng ý
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as env from "../env.js";
import money from "v-money";
import MessageService from "../services/MessageService";
import datePicker from "vue-bootstrap-datetimepicker";
import { en, vi } from "vuejs-datepicker/dist/locale";
import Multiselect from "vue-multiselect";
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import moment from "moment";
import { value } from "clean-css/lib/writer/one-time";
import Map from "../components/MapCoSoSanXuat.vue";
export default {
  components: {
    Multiselect,
    datePicker,
    Treeselect,
    Map,
  },
  props: ["value", "ket_qua_thanh_tras", "usersystem"],
  data: function () {
    return {
      optionsDate: {
        format: "DD/MM/YYYY",
        useCurrent: false,
        locale: "vi",
      },
      en: en,
      vi: vi,
      money: {
        decimal: ".",
        thousands: ",",
        prefix: "",
        suffix: "",
        precision: 0,
        masked: false,
      },
      form_data: {
        ten: null,
        dia_chi_lien_he: null,
        co_so_san_xuats: [],
      },
      chuDauTu: null,
      donvikhoiluongphatsinhs: [],
      don_vi_ctrsh: null,
      don_vi_ctrcntt: null,
      don_vi_ctnh: null,
      don_vi_dt: null,
      don_vi_nc: null,
      don_vi_dien_tich: null,
      donvidientich: [],
      hide: true,
      loaiHinhs: [],
      loaiHinhHoatDongs: [],
      loaiHinhONhiem: [],
      donvinc: null,
      coquandautu: null,
      coquankinhdoanh: null,
      luongnc: 0,
      congsuathda: [],
      thongso: null,
      donvi: null,
      kinh_do: null,
      vi_do: null,
      donvinuoc: [],
      donvicongsuathoatdongs: [],
      donvicongsuatthietkes: [],
      loaicosos: [],
      khucongnghieps: [],
      huyens: [],
      tinhs: [],
      phuongXas: [],
      don_vi_cshd: null,
      don_vi_cstk: null,
      coquantochucs: [],
      kcn: null,
      is_loading: false,
      tencs: null,
      loaics: null,
      loaihd: null,
      is_loading_cdt: false,
      chuDauTus: [],
      showEditChuDauTu: false,
      change: true,
      errors: {
        dientich: null,
        don_vi_dien_tich: null,
        tencs: null,
        tinh: null,
        diachi: null,
      },
      display: null,
      loading: false,
      diachihd: null,
      tungay: null,
      denngay: null,
      loaiHinhCoSos: [],
      toaDo: {
        kinh_do: null,
        vi_do: null,
      },
      isRestoringForm: false,
    };
  },
  methods: {
    getLoaiHinhCoSo() {
      this.loaiHinhCoSo = null;
      axios.get(env.endpointhttp + "loaikhucongnghieps").then((response) => {
        this.loaiHinhCoSos = response.data.result;
      });
    },
    goDanhSachCDT() {
      window.open("/chudautu", "_blank");
    },
    goAddChuDauTu() {
      window.open("/chudautu/add/", "_blank");
    },
    goChuDauTu(id) {
      window.open("/chudautu/edit/" + id, "_blank");
    },
    changeChuDauTu() {
      this.showEditChuDauTu = true;
    },
    searchLoaiNganhNghe(searchQuery, instanceId) {
      console.log(searchQuery, this.loaiHinhs.length);
    },
    asyncFindChuDauTu(query) {
      if (query) {
        this.is_loading_cdt = true;
        axios
          .get(env.endpointhttp + "chudautu/get?search=" + query)
          .then((response) => {
            this.chuDauTus = response.data.data;
          })
          .catch((error) => {})
          .finally(() => (this.is_loading_cdt = false));
      }
    },
    changeKinhDo() {
      this.change = true;
    },
    getLoaiHinhONhiem() {
      axios.get(env.endpointhttp + "loaihinhoinhiem").then((response) => {
        this.loaiHinhONhiem = response.data;
      });
    },
    getLoaiHinhHoatDong() {
      axios.get(env.endpointhttp + "listloaihinhhoatdong").then((response) => {
        this.loaiHinhHoatDongs = response.data;
      });
    },
    getCoSoTreeSelect() {
      axios.get(env.endpointhttp + "listloainganhnghe").then((response) => {
        this.loaiHinhs = response.data;
      });
    },
    changeViDo() {
      this.change = true;
    },
    getToaDo: function (value, co_so_san_xuat) {
      this.change = false;
      co_so_san_xuat.kinh_do = value.kinhdo;
      co_so_san_xuat.vi_do = value.vido;
    },
    getTenDiaChi: function (value, co_so_san_xuat) {
      co_so_san_xuat.dia_chi_hoat_dong = value;
    },
    getLatLonByAddressText(co_so_san_xuat) {
      axios
        .post(env.endpointhttp + "cososanxuat/getlatlon", {
          diachi: co_so_san_xuat.dia_chi_hoat_dong,
        })
        .then((response) => {
          co_so_san_xuat.kinh_do = response.data.result.long;
          co_so_san_xuat.vi_do = response.data.result.lat;
        });
    },
    refresh() {
      return Promise.all([
        axios.get(env.endpointhttp + "danhmuc/chuyendoidonvis"),
        axios.get(env.endpointhttp + "coquantochucs"),
        axios.get(env.endpointhttp + "loaihinhcosos?type=report"),
        axios.get(env.endpointhttp + "quanhuyens"),
        axios.get(env.endpointhttp + "tinhthanhs"),
      ]).then(([donviRes, cqRes, lhRes, hRes, tRes]) => {
        this.huyens = hRes.data.result;
        console.log("this.huyens", this.huyens);

        this.tinhs = tRes.data.result;
        this.coquantochucs = cqRes.data.result;
        this.loaicosos = lhRes.data.result;

        const result = donviRes.data.result;

        this.donvikhoiluongphatsinhs =
          result.don_vi.khoi_luong_phat_sinh_chat_thai;
        this.don_vi_ctrsh =
          result.don_vi_goc && result.don_vi_goc.khoi_luong_phat_sinh_chat_thai
            ? result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.id
            : null;
        this.don_vi_ctrcntt =
          result.don_vi_goc && result.don_vi_goc.khoi_luong_phat_sinh_chat_thai
            ? result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.id
            : null;
        this.don_vi_ctnh =
          result.don_vi_goc && result.don_vi_goc.khoi_luong_phat_sinh_chat_thai
            ? result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.id
            : null;
        this.don_vi_dt =
          result.don_vi_goc && result.don_vi_goc.dien_tich
            ? result.don_vi_goc.dien_tich.id
            : null;
        this.don_vi_nc =
          result.don_vi_goc && result.don_vi_goc.luu_luong_nuoc_thai
            ? result.don_vi_goc.luu_luong_nuoc_thai
            : null;

        this.don_vi_dien_tich =
          result.don_vi_goc && result.don_vi_goc.dien_tich
            ? result.don_vi_goc.dien_tich
            : null;

        this.donvicongsuathoatdongs = result.don_vi.cong_suat_hoat_dong;
        this.donvicongsuatthietkes = result.don_vi.cong_suat_thiet_ke;
        this.donvinuoc = result.don_vi.luu_luong_nuoc_thai;
        this.donvinc =
          result.don_vi_goc && result.don_vi_goc.luu_luong_nuoc_thai
            ? result.don_vi_goc.luu_luong_nuoc_thai
            : null;
        this.donvidientich = result.don_vi.dien_tich;

        const don_vi_dien_tich = this.don_vi_dien_tich;
        const donvinc = this.donvinc;
        this.value.co_so_san_xuats.forEach((element) => {
          element.donvinc = donvinc;
          element.don_vi_dien_tich = don_vi_dien_tich;
        });
      });
    },

    addcshd: function (co_so_san_xuat, index) {
      co_so_san_xuat.errors.don_vi_cstk = "";
      co_so_san_xuat.errors.don_vi_cshd = "";
      co_so_san_xuat.errors.loaics = "";
      if (co_so_san_xuat.intermediate_data.loaics == null) {
        co_so_san_xuat.errors.loaics = "Chưa chọn Loại hình cơ sở";
      } else {
        co_so_san_xuat.chi_tiet_cong_suat.push({
          don_vi: co_so_san_xuat.intermediate_data.don_vi_cstk,
          don_vi_h_d: co_so_san_xuat.intermediate_data.don_vi_cshd,
          loai_hinh: co_so_san_xuat.intermediate_data.loaics,
          loai_hinh_hoat_dong: co_so_san_xuat.intermediate_data.loaihd,
          thong_so_hoat_dong: co_so_san_xuat.intermediate_data.congsuathd,
          thong_so: co_so_san_xuat.intermediate_data.congsuattk,
        });

        co_so_san_xuat.intermediate_data.loaics = null;
        co_so_san_xuat.intermediate_data.loaihd = null;
        co_so_san_xuat.intermediate_data.don_vi_cshd = null;
        co_so_san_xuat.intermediate_data.congsuathd = 0;
        co_so_san_xuat.intermediate_data.don_vi_cstk = null;
        co_so_san_xuat.intermediate_data.congsuattk = 0;
      }
    },
    deletecshd: function (chitietcongsuat, index) {
      chitietcongsuat.splice(index, 1);
    },
    changeKCN(co_so_san_xuat) {
      const kcn = co_so_san_xuat.khu_cong_nghiep;
      if (!kcn) return;

      // ----------------------------
      // 1. Set tỉnh (mảng)
      // ----------------------------
      const tinh = this.tinhs.find((t) => t.id === kcn.tinh_thanh_id);
      co_so_san_xuat.tinh_thanhs = tinh ? [tinh] : [];

      // ----------------------------
      // 2. Set huyện (mảng)
      // ----------------------------
      const huyen = this.huyens.find((h) => h.id === kcn.quan_huyen_id);
      co_so_san_xuat.quan_huyens = huyen ? [huyen] : [];

      // ----------------------------
      // 3. Load xã theo huyện
      // ----------------------------
      axios
        .get(env.endpointhttp + "async-phuong-xa", {
          params: { quan_huyen: [kcn.quan_huyen_id] },
        })
        .then((res) => {
          this.phuongXas = res.data;

          // Nếu KCN có xã cụ thể thì tìm và set
          if (kcn.xa_phuong) {
            const xa = res.data.find((x) => x.id === kcn.xa_phuong.id);
            co_so_san_xuat.phuong_xas = xa ? [xa] : [];
          } else {
            co_so_san_xuat.phuong_xas = [];
          }
        });

      // ----------------------------
      // 4. Set địa chỉ hoạt động
      // ----------------------------
      co_so_san_xuat.dia_chi_hoat_dong = kcn.dia_chi;
    },

    editcssx: function () {
      this.is_loading = true;
      if (this.validator()) {
        this.form_data.chu_dau_tu_id = this.chuDauTu ? this.chuDauTu.id : null;

        axios
          .put(env.endpointhttp + "cososanxuat/update/" + this.value.id, {
            co_so_san_xuat: this.form_data,
          })
          .then((response) => {
            MessageService.showSuccessMessage("Cập nhật thành công");
            window.location.href =
              "/cososanxuat/showformupdate/" + this.value.id;
          })
          .catch((error) => {
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
              MessageService.showDangerMessage(
                message || "Lỗi cập nhật đối tượng thanh tra"
              );
            }
          })
          .finally(() => {
            this.is_loading = false;
          });
      } else {
        this.is_loading = false;
      }
    },
    validator() {
      var valid = true;
      this.form_data.co_so_san_xuats.forEach((element) => {
        if (!element.dia_chi_hoat_dong) {
          element.errors.diachi = "Thiếu địa chỉ hoạt động";
          valid = false;
        }
        if (!element.tinh_thanh) {
          element.errors.tinh = "Thiếu tỉnh thành";
          valid = false;
        }

        if (element.luu_vuc_song_id == 0) {
          element.luu_vuc_song_id = null;
        }

        if (element.dien_tich < 0) {
          element.dien_tich = 0;
        }
      });
      if (this.don_vi_dien_tich != 0 && this.don_vi_dien_tich == null) {
        this.errors.don_vi_dien_tich = "Thiếu đơn vị đo";
        valid = false;
      }
      if (!this.form_data.ten) {
        this.errors.tencs = "Thiếu trường tên cơ sở thanh tra";
        valid = false;
      }
      return valid;
    },
    downloadExcel(e) {
      var tungay = this.tungay;
      var denngay = this.denngay;
      var url = "/cososanxuat/xuatexcel/" + this.value.id + "?";
      $(".close").click();
      if (tungay) {
        url += "tungay=" + encodeURIComponent(this.tungay);
      }
      if (denngay) {
        url += "&denngay=" + encodeURIComponent(this.denngay);
      }
      window.location.href = url;
    },
    limitText(count) {},
    asyncFindKhuCongNghiep(query) {
      if (query) {
        this.is_loading = true;
        axios
          .get(env.endpointhttp + "asynkhucongnghiep?search=" + query)
          .then((response) => {
            this.khucongnghieps = response.data.result;
          })
          .catch((error) => {})
          .finally(() => (this.is_loading = false));
      }
    },
    // asyncFindHuyen(query) {
    //   if (query) {
    //     this.is_loading = true;
    //     axios
    //       .get(env.endpointhttp + "quanhuyens?search=" + query)
    //       .then((response) => {
    //         this.huyens = response.data.result;
    //       })
    //       .catch((error) => {})
    //       .finally(() => (this.is_loading = false));
    //   }
    // },
    asyncFindTinh(query) {
      if (query) {
        this.is_loading = true;
        axios
          .get(env.endpointhttp + "tinhthanhs?search=" + query)
          .then((response) => {
            this.tinhs = response.data.result;
          })
          .catch((error) => {})
          .finally(() => (this.is_loading = false));
      }
    },
    deleteDiaDiemHoatDong(co_so_san_xuat) {
      $(".close").click();
      axios
        .post(
          env.endpointhttp +
            "cososanxuat/diadiemhoatdong/delete/" +
            co_so_san_xuat.id
        )
        .then((response) => {
          MessageService.showSuccessMessage("Xóa thành công");
          window.location.href = "/cososanxuat/showformupdate/" + this.value.id;
        })
        .catch((error) => {})
        .finally(() => (this.is_loading = false));
    },
    // ---------- Thay đổi Tỉnh ----------
    // ---------- Khi thay đổi Tỉnh ----------
    changeTinh(element) {
      // Lấy mảng tỉnh (nhiều hay ít đều xử lý được)
      const tinhArr = Array.isArray(element.tinh_thanhs)
        ? element.tinh_thanhs
        : [element.tinh_thanhs].filter(Boolean);

      // Lấy danh sách ID tỉnh (dựa vào id hoặc pivot)
      const tinhIds = tinhArr
        .map((t) => t.id || t.pivot.tinh_thanh_id || t)
        .filter(Boolean);

      console.log("Các tỉnh được chọn:", tinhIds);

      if (tinhIds.length === 0) return;

      // ✅ Lọc huyện thuộc *bất kỳ* tỉnh nào trong danh sách
      element.available_huyens = this.huyens.filter((h) =>
        tinhIds.includes(h.tinh_thanh_id)
      );

      console.log("Huyện lọc được:", element.available_huyens);

      // Nếu đang load form → không reset huyện/xã
      if (this.isRestoringForm) return;

      // Khi người dùng đổi tỉnh → reset huyện & xã
      element.quan_huyens = [];
      element.phuong_xas = [];
    },

    // ---------- Khi thay đổi Huyện ----------
    changeHuyen(co_so_san_xuat) {
      const quan_huyen_ids =
        co_so_san_xuat.quan_huyens.map((el) => el.id) || [];

      // Reset xã nếu bỏ chọn hết huyện
      if (quan_huyen_ids.length === 0) {
        this.phuongXas = [];
        co_so_san_xuat.phuong_xas = [];
        return;
      }

      // Gọi API để lấy xã thuộc các huyện đó
      axios
        .get(env.endpointhttp + "async-phuong-xa", {
          params: { quan_huyen: quan_huyen_ids },
        })
        .then((response) => {
          this.phuongXas = response.data;
        });

      // Giữ lại xã nằm trong các huyện hiện có
      if (co_so_san_xuat.phuong_xas) {
        co_so_san_xuat.phuong_xas = co_so_san_xuat.phuong_xas.filter((el) =>
          quan_huyen_ids.includes(el.quan_huyen_id)
        );
      }
    },
  },
  watch: {
    kinh_do(id) {
      if (this.change == true) {
        this.toado = {
          kinhdo: this.kinh_do,
          vido: this.vi_do,
        };
      } else {
        // this.change=true;
      }
    },
    vi_do(id) {
      if (this.change == true) {
        this.toado = {
          kinhdo: this.kinh_do,
          vido: this.vi_do,
        };
      } else {
        // this.change=true;
      }
    },
  },
  computed: {
    co_so_san_xuat: {
      get() {
        this.form.loai_hinh_co_so = this.value.loai_nganh_nghe
          ? this.value.loai_nganh_nghe[0]
          : null;
        return this.value;
      },
      set(value) {
        this.$emit("input", value);
      },
    },
    currentHuyens() {
      if (
        !this.co_so_san_xuat.tinh_thanhs ||
        this.co_so_san_xuat.tinh_thanhs.length === 0
      ) {
        return this.huyens;
      }
      const tinh_thanh_ids = this.co_so_san_xuat.tinh_thanhs.map((el) => el.id);
      return this.huyens.filter((huyen) =>
        tinh_thanh_ids.includes(huyen.tinh_thanh_id)
      );
    },
  },
  mounted() {
    console.log("this.value", this.value);

    this.isRestoringForm = true;
    // Chờ refresh hoàn tất trước khi fill dữ liệu
    this.refresh().then(() => {
      this.getCoSoTreeSelect();
      this.getLoaiHinhHoatDong();
      this.getLoaiHinhONhiem();
      this.getLoaiHinhCoSo();

      const don_vi_dien_tich = this.don_vi_dien_tich;
      const donvinc = this.donvinc;

      this.chuDauTu = this.value.chu_dau_tu;
      this.asyncFindChuDauTu(this.chuDauTu ? this.chuDauTu.ten : null);

      this.value.co_so_san_xuats.forEach((element) => {
        element.intermediate_data = {
          don_vi_cshd: null,
          don_vi_cstk: null,
          congsuattk: 0,
          congsuathd: 0,
          loaics: element.loai_nganh_nghe ? element.loai_nganh_nghe[0] : null,
          loaihd: null,
        };
        element.loaiONhiem = element.loai_hinh_o_nhiem;
        element.errors = {
          loaics: null,
          loaihd: null,
          don_vi_cstk: null,
          congsuattk: null,
          don_vi_cshd: null,
          congsuathd: null,
          diachi: null,
          tinh: null,
          don_vi_dien_tich: null,
        };
        element.donvinc = donvinc;
        element.don_vi_dien_tich = don_vi_dien_tich;

        // ✅ Gọi tự động để fill Huyện & Xã sau khi dữ liệu tỉnh/huyện đã có
        if (element.tinh_thanhs && element.tinh_thanhs.length) {
          this.changeTinh(element);
        }
        if (element.quan_huyens && element.quan_huyens.length) {
          this.changeHuyen(element);
        }
      });

      Object.assign(this.form_data, this.value);
      if (this.value.co_so_san_xuats.length > 0) {
        this.toaDo.kinh_do = this.value.co_so_san_xuats[0].kinh_do;
        this.toaDo.vi_do = this.value.co_so_san_xuats[0].vi_do;
      }
      this.isRestoringForm = false;
    });
  },
};
</script>
<style>
/* .tinh {
  z-index: 4000;
}
.huyen {
  z-index: 3000;
}
.xaPhuong {
  z-index: 2000;
} */
</style>
