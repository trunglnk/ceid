<template>
  <div class="row block-multiple-rows">
    <div class="col-sm-12 col-lg-4">
      <div class="block-multiple-rows padding-left padding-right">
        <div class="row">
          <div class="col-md-12">
            <label>Tên&nbsp;<span style="color: red">*</span></label>
            <textarea
              v-model="co_so_san_xuat.ten"
              type="text"
              class="form-control"
              tabindex="1"
              autofocus
              placeholder="Tên cơ sở sản xuất"
            />
            <span class="help-block" v-if="errors.tencs">
              <strong>{{ errors.tencs }}</strong>
            </span>
          </div>
          <div class="col-md-12 mt-3">
            <label
              >Loại hình cơ sở &nbsp;<span style="color: red"></span
            ></label>
            <multiselect
              v-model="co_so_san_xuat.loai_khu_cong_nghiep"
              open-direction="bottom"
              placeholder="Chọn một loại hình"
              label="ten"
              track-by="ma"
              selectLabel="Nhấn enter để chọn"
              deselectLabel="Nhấn enter để bỏ chọn"
              selectedLabel="Đã chọn"
              :options="loaiHinhs"
              :multiple="false"
              :searchable="true"
            >
            </multiselect>
          </div>

          <div class="col-md-12 mt-4">
            <div class="lead">Địa chỉ hoạt động</div>
            <label>Thuộc KCN, CCN, KCX, KCNC, làng nghề</label>
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
              :internal-search="true"
              :clear-on-select="false"
              :close-on-select="true"
              :options-limit="300"
              :limit="3"
              :max-height="600"
              :show-no-results="false"
              :hide-selected="false"
              :tabindex="1"
              :clearOnSelect="true"
              @search-change="asyncFindKhuCongNghiep"
              :disabled="disabled || readonly"
              @input="changeKCN"
            >
              <span slot="noResult">Không tìm thấy kết quả</span>
            </multiselect>
            <div class="row">
              <div class="col-md-12">
                <label>Tỉnh<span style="color: red">*</span></label>
                <multiselect
                  v-model="co_so_san_xuat.tinh_thanhs"
                  label="ten"
                  track-by="id"
                  placeholder="Gõ tên Tỉnh thành"
                  selectLabel="Nhấn enter để chọn"
                  deselectLabel="Nhấn enter để bỏ chọn"
                  selectedLabel="Đã chọn"
                  open-direction="bottom"
                  :options="tinhs"
                  :multiple="true"
                  :searchable="true"
                  :loading="is_loading"
                  :internal-search="true"
                  :clear-on-select="false"
                  :close-on-select="true"
                  :options-limit="300"
                  :limit="3"
                  :max-height="600"
                  :show-no-results="false"
                  :hide-selected="false"
                  :tabindex="1"
                  :clearOnSelect="true"
                  @input="changeTinh()"
                >
                  <span slot="noResult">Không tìm thấy kết quả</span>
                </multiselect>
                <span class="help-block" v-if="co_so_san_xuat.errors.tinh">
                  <strong>{{ co_so_san_xuat.errors.tinh }}</strong>
                </span>
              </div>

              <div class="col-md-12">
                <label>Huyện</label>
                <multiselect
                  v-model="co_so_san_xuat.quan_huyens"
                  label="ten"
                  track-by="id"
                  placeholder="Gõ tên huyện"
                  selectLabel="Nhấn enter để chọn"
                  deselectLabel="Nhấn enter để bỏ chọn"
                  selectedLabel="Đã chọn"
                  open-direction="bottom"
                  :options="currentHuyens"
                  :multiple="true"
                  :searchable="true"
                  :loading="is_loading"
                  :internal-search="true"
                  :clear-on-select="false"
                  :close-on-select="true"
                  :options-limit="300"
                  :limit="3"
                  :max-height="600"
                  :show-no-results="false"
                  :hide-selected="false"
                  :tabindex="1"
                  :clearOnSelect="true"
                  @input="changeHuyen(co_so_san_xuat)"
                >
                  <span slot="noResult">Không tìm thấy kết quả</span>
                </multiselect>
                <span class="help-block" v-if="co_so_san_xuat.errors.huyen">
                  <strong>{{ co_so_san_xuat.errors.huyen }}</strong>
                </span>
              </div>
              <div class="col-md-12">
                <label>Xã</label>
                <multiselect
                  v-model="co_so_san_xuat.phuong_xas"
                  id="ajax"
                  label="ten"
                  track-by="id"
                  placeholder="Xã phường"
                  selectLabel="Nhấn enter để chọn"
                  deselectLabel="Nhấn enter để bỏ chọn"
                  selectedLabel="Đã chọn"
                  open-direction="bottom"
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
                  v-model="co_so_san_xuat.kinh_do"
                  type="text"
                  class="form-control"
                  placeholder="Kinh độ"
                  tabindex="1"
                  :disabled="disabled"
                  :readonly="readonly"
                />
              </div>
              <div class="col-md-6">
                <input
                  v-model="co_so_san_xuat.vi_do"
                  type="text"
                  class="form-control"
                  placeholder="Vĩ độ"
                  tabindex="1"
                  :disabled="disabled"
                  :readonly="readonly"
                />
              </div>
            </div>
            <div class="row" style="padding: 10px">
              <div class="welcome">
                <google-map
                  @tendiachi="getTenDiaChi($event)"
                  :css="1"
                  @toado="getToaDo($event)"
                  :toado="{
                    kinhdo: co_so_san_xuat.kinh_do,
                    vido: co_so_san_xuat.vi_do,
                  }"
                  style="margin-top: -5px"
                  :hidenInput="0"
                >
                </google-map>
              </div>
            </div>
            <label>Địa chỉ hoạt động <span style="color: red">*</span></label>
            <textarea
              v-model="co_so_san_xuat.dia_chi_hoat_dong"
              type="text"
              class="form-control"
              tabindex="19"
              placeholder="Nhập địa chỉ hoạt động"
              @change="getLatLonByAddressText()"
              :disabled="disabled"
              :readonly="readonly"
            ></textarea>
            <span class="help-block" v-if="co_so_san_xuat.errors.diachi">
              <strong>{{ co_so_san_xuat.errors.diachi }}</strong>
            </span>
            <label>Địa chỉ liên hệ</label>
            <textarea
              v-model="co_so_san_xuat.dia_chi_lien_he"
              type="text"
              class="form-control"
              tabindex="18"
              placeholder="Nhập địa chỉ liên hệ"
              :disabled="disabled"
              :readonly="readonly"
            ></textarea>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12 col-lg-8">
      <div class="row block-multiple-rows">
        <div class="col-lg-8" style="margin-top: 5px">
          <label>THÔNG TIN CƠ SỞ THANH TRA</label>
          <p class="text-muted">
            (Thông tin chung của kết luận thanh tra gần nhất)
          </p>
          <hr style="margin-top: 7px; margin-bottom: 7px" />
        </div>
        <div
          class="col-lg-4"
          style="margin-top: 5px"
          v-if="
            usersystem.role_code == 'admin' ||
            usersystem.role_code == 'nhanvientrungtam' ||
            usersystem.role_code == 'adminvaquanlydanhmuc'
          "
        >
          <!-- <button class="btn btn-default btn-flat pull-right" @click="$emit('click-delete')">
            <i class="fa fa-trash"></i> Xóa địa chỉ hoạt động
          </button> -->
        </div>
        <div class="col-lg-12">
          <hr style="margin-top: 7px; margin-bottom: 7px" />
        </div>
        <div class="col-md-12 mb-3" v-if="isLoaiHinhCoSoKCN" key="'kcn'">
          <div class="row">
            <div class="col-md-4">
              <label>Tỷ lệ lấp đầy (%)</label>
              <ejs-numerictextbox
                class="form-control"
                format=".###"
                v-model="co_so_san_xuat.ty_le_lap_day"
                type="text"
              ></ejs-numerictextbox>
            </div>

            <div class="col-md-4">
              <label>Tổng diện tích</label>
              <ejs-numerictextbox
                class="form-control"
                format=".###"
                v-model="co_so_san_xuat.tong_dien_tich"
                type="text"
              ></ejs-numerictextbox>
            </div>

            <div class="col-md-4">
              <label>Đơn vị diện tích</label>
              <multiselect
                v-model="co_so_san_xuat.chuyen_doi_don_vi"
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

          <div class="row" style="margin-top: 10px">
            <div class="col-md-4">
              <label>Diện tích đất CN</label>
              <ejs-numerictextbox
                class="form-control"
                format=".###"
                v-model="co_so_san_xuat.dien_tich_dat_cn"
                type="text"
              ></ejs-numerictextbox>
            </div>

            <div class="col-md-4">
              <label>Diện tích đất cho thuê</label>
              <ejs-numerictextbox
                class="form-control"
                format=".###"
                v-model="co_so_san_xuat.dien_tich_dat_cho_thue"
                type="text"
              ></ejs-numerictextbox>
            </div>

            <div class="col-md-4">
              <label>Diện tích đất cây xanh</label>
              <ejs-numerictextbox
                class="form-control"
                format=".###"
                v-model="co_so_san_xuat.dien_tich_dat_cay_xanh"
                type="text"
              ></ejs-numerictextbox>
            </div>
          </div>
        </div>

        <div class="col-md-12" :key="'default'">
          <!-- <label>Chứng nhận kinh doanh</label>
          <div class="row">
            <div class="col-md-3">
              <input
                v-model="co_so_san_xuat.so_giay_chung_nhan_kinh_doanh"
                type="text"
                class="form-control"
                placeholder="Giấy chứng nhận"
                tabindex="1"
                :disabled="disabled"
                :readonly="readonly"
              />
            </div>
            <div class="col-md-3">
              <date-picker
                v-model="co_so_san_xuat.ngay_cap_giay_chung_nhan_kinh_doanh"
                placeholder="Ngày cấp"
                tabindex="17"
                :config="optionsDate"
                :disabled="disabled"
                :readonly="readonly"
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
                :internal-search="true"
                :clear-on-select="false"
                :close-on-select="true"
                :options-limit="300"
                :limit="3"
                :max-height="600"
                :show-no-results="false"
                :hide-selected="false"
                :tabindex="1"
                :clearOnSelect="true"
                :disabled="disabled || readonly"
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
                :disabled="disabled"
                :readonly="readonly"
              />
            </div>
            <div class="col-md-3">
              <date-picker
                v-model="co_so_san_xuat.ngay_cap_giay_chung_nhan_dau_tu"
                placeholder="Ngày cấp"
                tabindex="17"
                :config="optionsDate"
                :disabled="disabled"
                :readonly="readonly"
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
                :internal-search="true"
                :clear-on-select="false"
                :close-on-select="true"
                :options-limit="300"
                :limit="3"
                :max-height="600"
                :show-no-results="false"
                :hide-selected="false"
                :tabindex="1"
                :disabled="disabled || readonly"
              >
                <span slot="noResult">Không tìm thấy kết quả</span>
              </multiselect>
            </div>
          </div> -->

          <div class="row">
            <div class="col-md-12">
              <label>Loại hình nguy cơ gây ô nhiễm</label>
              <multiselect
                v-model="co_so_san_xuat.loai_hinh_o_nhiem"
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
                :disabled="disabled"
                :readonly="readonly"
              >
              </ejs-numerictextbox>
              <span class="help-block" v-if="co_so_san_xuat.errors.dien_tich">
                <strong>{{ co_so_san_xuat.errors.dien_tich }}</strong>
              </span>
            </div>
            <div class="col-md-3">
              <label>Đơn vị đo</label>
              <multiselect
                v-model="co_so_san_xuat.don_vi_dien_tich"
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
                :clear-on-select="true"
                :close-on-select="true"
                :options-limit="300"
                :limit="3"
                :show-no-results="false"
                :hide-selected="false"
                :tabindex="1"
                :disabled="disabled || readonly"
              >
                <span slot="noResult">Không tìm thấy kết quả</span>
              </multiselect>
              <span
                class="help-block"
                v-if="co_so_san_xuat.errors.don_vi_dien_tich"
              >
                <strong>{{ co_so_san_xuat.errors.don_vi_dien_tich }}</strong>
              </span>
            </div>
            <div class="col-md-3">
              <label>Lượng nước sử dụng</label>
              <ejs-numerictextbox
                class="form-control"
                format=".###"
                v-model="co_so_san_xuat.luong_nuoc_su_dung"
                type="text"
                :disabled="disabled"
                :readonly="readonly"
              >
              </ejs-numerictextbox>
            </div>
            <div class="col-md-3">
              <label>Đơn vị đo</label>
              <multiselect
                v-model="co_so_san_xuat.don_vi_nuoc_su_dung"
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
                :disabled="disabled || readonly"
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
                :disabled="disabled"
                :readonly="readonly"
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
                :disabled="disabled"
                :readonly="readonly"
              ></textarea>
            </div>
            <div class="col-md-6">
              <label>Nhiên liệu chính sử dụng</label>
              <textarea
                v-model="co_so_san_xuat.nhien_lieu_chinh_su_dung"
                class="form-control"
                tabindex="1"
                :disabled="disabled"
                :readonly="readonly"
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
                :disabled="disabled"
                :readonly="readonly"
              ></textarea>
            </div>
            <div class="col-md-6">
              <label>Nguồn nước sử dụng</label>
              <textarea
                v-model="co_so_san_xuat.nguon_nuoc_su_dung"
                class="form-control"
                tabindex="28"
                :disabled="disabled"
                :readonly="readonly"
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
            (Chọn loại ngành nghề kinh tế ở Việt Nam, nhập thông tin Công suất
            thiết kế/Quy mô, công suất hoạt động/quy mô và nhấn + để thêm vào
            danh sách Loại ngành nghề kinh tế)
          </p>
          <hr style="margin-top: 7px; margin-bottom: 7px" />
        </div>
        <template
          v-if="
            usersystem.role_code == 'admin' ||
            usersystem.role_code == 'nhanvientrungtam' ||
            usersystem.role_code == 'adminvaquanlydanhmuc'
          "
        >
          <div class="col-md-12 mb-4">
            <div class="form-group" :class="{ 'has-error': errors.loaicst }">
              <label
                >Loại ngành nghề kinh tế&nbsp;<span style="color: red"
                  >*</span
                ></label
              >
              <!-- <multiselect
                v-model="form.loai_hinh_co_so"
                placeholder="Chọn loại hình hoạt động"
                label="ten"
                track-by="id"
                group-values="childrens"
                group-label="parent"
                :group-select="false"
                :options="loai_hinh_co_so_tree_listing"
                :multiple="false"
                selectLabel="Nhấn enter để chọn"
                deselectLabel="Nhấn enter để bỏ chọn"
                selectedLabel="Đã chọn"
                :tabindex="10"
                :disabled="disabled || readonly"
              >
              </multiselect>
              <span class="help-block" v-if="errors.loaics">
                <strong>{{ errors.loaics }}</strong>
              </span> -->

              <treeselect
                :multiple="false"
                :options="loaiHinhNganhNghes"
                placeholder="Chọn một loại ngành nghề kinh tế kinh tế ở Việt Nam"
                v-model="form.loai_hinh_co_so"
                value-format="object"
                v-validate="'required'"
                :show-count="true"
              >
                <label
                  slot="option-label"
                  slot-scope="{
                    node,
                    shouldShowCount,
                    count,
                    labelClassName,
                    countClassName,
                  }"
                  :class="labelClassName"
                >
                  {{ node.raw ? node.raw.ma : "" }}. {{ node.label }}
                  <span v-if="shouldShowCount" :class="countClassName"
                    >({{ count }})</span
                  >
                </label>
              </treeselect>
              <span style="color: red" v-text="errors.loaics"></span>
            </div>
          </div>
          <div class="col-md-5">
            <div class="form-group">
              <label>Công suất thiết kế/Quy mô</label>
              <ejs-numerictextbox
                class="form-control"
                format=".###"
                v-model="form.cong_suat_thiet_ke"
                type="text"
                :disabled="disabled"
                :readonly="readonly"
              >
              </ejs-numerictextbox>
              <span class="help-block" v-if="co_so_san_xuat.errors.congsuattk">
                <strong>{{ co_so_san_xuat.errors.congsuattk }}</strong>
              </span>
            </div>
            <div class="form-group">
              <label>Công suất hoạt động/Quy mô</label>
              <ejs-numerictextbox
                class="form-control"
                format=".###"
                v-model="form.cong_suat_hoat_dong"
                type="text"
                :disabled="disabled"
                :readonly="readonly"
              >
              </ejs-numerictextbox>
              <span class="help-block" v-if="co_so_san_xuat.errors.congsuathd">
                <strong>{{ co_so_san_xuat.errors.congsuathd }}</strong>
              </span>
            </div>
          </div>
          <div class="col-md-6">
            <div
              class="form-group"
              :class="{ 'has-error': errors.don_vi_cstk }"
            >
              <label>Đơn vị đo</label>
              <multiselect
                v-model="form.don_vi_cong_suat_thiet_ke"
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
                :disabled="disabled || readonly"
              >
                <span slot="noResult">Không tìm thấy kết quả</span>
              </multiselect>
              <span class="help-block" v-if="errors.don_vi_cstk">
                <strong>{{ errors.don_vi_cstk }}</strong>
              </span>
            </div>
            <div
              class="form-group"
              :class="{ 'has-error': errors.don_vi_cshd }"
            >
              <label>Đơn vị đo</label>
              <multiselect
                v-model="form.don_vi_cong_suat_hoat_dong"
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
                :disabled="disabled || readonly"
                :readonly="readonly"
              >
                <span slot="noResult">Không tìm thấy kết quả</span>
              </multiselect>
              <span class="help-block" v-if="errors.don_vi_cshd">
                <strong>{{ errors.don_vi_cshd }}</strong>
              </span>
            </div>
          </div>
          <div class="col-lg-11 col-md-11">
            <div class="form-group">
              <label>Ghi chú</label>
              <p class="text-muted">(Nhập thông tin chi tiết từng năm)</p>
              <textarea
                v-model="form.ghi_chu"
                type="text"
                class="form-control"
                placeholder="Nhập thông tin chi tiết"
                rows="5"
              ></textarea>
            </div>
          </div>
          <div class="col col-sm-1" style="margin-top: 60px">
            <!-- <label>Thêm</label>
            <a
              type="button"
              class="btn bg-flat btn-flat pull-center"
              @click="addcshd(co_so_san_xuat)"
            >
              <i class="fa fa-plus"></i>
            </a> -->

            <button
              type="button"
              style="height: 60px; width: 60px"
              class="btn bg-flat btn-flat pull-center"
              @click="addcshd(co_so_san_xuat)"
              title="Thêm loại ngành nghề"
            >
              <i class="fa fa-plus"></i>
            </button>
          </div>
        </template>

        <div class="col-lg-12">
          <hr style="margin-top: 5px; margin-bottom: 5px" />
          <label>DANH SÁCH LOẠI NGÀNH NGHỀ KINH TẾ</label>
          <p class="text-muted">
            (Bảng thông tin danh sách loại ngành nghề kinh tế của cơ sở thanh
            tra)
          </p>
          <hr style="margin-top: 7px; margin-bottom: 7px" />
        </div>
        <div class="col-md-12">
          <table class="table table-hover table-responsive" role="grid">
            <tbody>
              <tr class="row-table-header">
                <th>Loại ngành nghề kinh tế</th>
                <th>Công suất thiết kế/Quy mô</th>
                <th>Đơn vị đo</th>
                <th>Công suất hoạt động/Quy mô</th>
                <th>Đơn vị đo</th>
                <th>Ghi chú</th>
                <th
                  style="width: 5%; text-align: center"
                  v-if="
                    usersystem.role_code == 'admin' ||
                    usersystem.role_code == 'nhanvientrungtam' ||
                    usersystem.role_code == 'adminvaquanlydanhmuc'
                  "
                >
                  Xóa
                </th>
              </tr>
              <tr
                v-for="(item, index) in co_so_san_xuat[keyLoaiHinhCoSo]"
                :key="item.id"
              >
                <td>
                  {{ item.loai_hinh_co_so.ten || item.loai_hinh_co_so.label }}
                </td>
                <td>{{ item.thong_so_thiet_ke | numFormat }}</td>
                <td>
                  {{ item.don_vi_thiet_ke ? item.don_vi_thiet_ke.ten : null }}
                </td>
                <td>{{ item.thong_so_hoat_dong | numFormat }}</td>
                <td>
                  {{ item.don_vi_hoat_dong ? item.don_vi_hoat_dong.ten : null }}
                </td>
                <td>
                  {{ item.ghi_chu ? item.ghi_chu : null }}
                </td>
                <td
                  align="center"
                  v-if="
                    usersystem.role_code == 'admin' ||
                    usersystem.role_code == 'nhanvientrungtam' ||
                    usersystem.role_code == 'adminvaquanlydanhmuc'
                  "
                >
                  <a @click="deletecshd(index)">
                    <i class="fa fa-trash-o"></i>
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Multiselect from "vue-multiselect";
import * as env from "../env.js";
import datePicker from "vue-bootstrap-datetimepicker";
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
export default {
  components: { Multiselect, datePicker, Treeselect },
  props: {
    disabled: Boolean,
    readonly: Boolean,
    value: { type: Object, default: () => ({}) },
    usersystem: {},
    optionsDate: {},
    donvidientich: { type: Array, default: () => [] },
    donvinuoc: { type: Array, default: () => [] },
    donvicongsuatthietkes: { type: Array, default: () => [] },
    donvicongsuathoatdongs: { type: Array, default: () => [] },
    coquantochucs: { type: Array, default: () => [] },
    tinhs: { type: Array, default: () => [] },
    huyens: { type: Array, default: () => [] },
    loai_hinh_co_so_tree_listing: { type: Array, default: () => [] },
    keyLoaiHinhCoSo: {
      type: String,
      default: "ket_qua_thanh_tra_chung_loai_hinh_hoat_dongs",
    },
  },
  data: () => ({
    khucongnghieps: [],
    loaiHinhONhiem: [],
    form: {},
    errors: {},
    is_loading: false,
    loaiHinhNganhNghes: [],
    loaiHinhs: [],
    autoSetDonViDienTichDone: false,
    autoSetDonViNuocDone: false,
    phuongXas: [],
  }),
  watch: {
    donvidientich() {
      this.ensureDefaultDonViDienTichOnce();
    },
    donvinuoc() {
      this.ensureDefaultDonViNuocOnce();
    },
    "value.loai_nganh_nghe": function (newValue) {
      // Khi `value.loai_nganh_nghe` thay đổi, cập nhật giá trị cho `form.loai_hinh_co_so`
      this.form.loai_hinh_co_so = newValue ? newValue[0] : null;
    },
  },
  computed: {
    co_so_san_xuat: {
      get() {
        // this.form.loai_hinh_co_so = this.value.loai_nganh_nghe
        //   ? this.value.loai_nganh_nghe[0]
        //   : null;
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
    isLoaiHinhCoSoKCN() {
      const loai = this.co_so_san_xuat.loai_khu_cong_nghiep;
      return loai && loai.ma_muc === "02";
    },
  },
  mounted() {
    this.getCoSoTreeSelect();
    this.getLoaiHinhCoSo();
    this.getLoaiHinhONhiem();
    console.log(221, this.value);
    // this.ensureDefaultDonViDienTichOnce();
    // this.ensureDefaultDonViNuocOnce();
    if (this.value.loai_nganh_nghe) {
      this.form.loai_hinh_co_so = this.value.loai_nganh_nghe[0];
    }

    this.$nextTick(() => {
      this.ensureDefaultDonViDienTichOnce();
      this.ensureDefaultDonViNuocOnce();
    });

    if (
      this.co_so_san_xuat &&
      this.co_so_san_xuat.quan_huyens &&
      this.co_so_san_xuat.quan_huyens.length > 0
    ) {
      this.changeHuyen(this.co_so_san_xuat);
    }
  },
  methods: {
    ensureDefaultDonViNuocOnce() {
      if (this.autoSetDonViNuocDone) return;

      const hasValue =
        this.co_so_san_xuat &&
        this.co_so_san_xuat.luong_nuoc_su_dung != null &&
        this.co_so_san_xuat.luong_nuoc_su_dung !== "" &&
        Number(this.co_so_san_xuat.luong_nuoc_su_dung) > 0;

      const notSelected = !this.co_so_san_xuat.don_vi_nuoc_su_dung;
      const dvGoc = Array.isArray(this.donvinuoc)
        ? this.donvinuoc.find((x) => x && x.don_vi_goc === true)
        : null;

      if (hasValue && notSelected && dvGoc) {
        this.$set(this.co_so_san_xuat, "don_vi_nuoc_su_dung", dvGoc);
        this.autoSetDonViNuocDone = true;
      }
    },
    ensureDefaultDonViDienTichOnce() {
      if (this.autoSetDonViDienTichDone) return;

      const hasDienTich =
        this.co_so_san_xuat &&
        this.co_so_san_xuat.dien_tich != null &&
        this.co_so_san_xuat.dien_tich !== "" &&
        Number(this.co_so_san_xuat.dien_tich) > 0;

      const notSelected = !this.co_so_san_xuat.don_vi_dien_tich;
      const dvGoc = Array.isArray(this.donvidientich)
        ? this.donvidientich.find((x) => x && x.don_vi_goc === true)
        : null;

      if (hasDienTich && notSelected && dvGoc) {
        this.$set(this.co_so_san_xuat, "don_vi_dien_tich", dvGoc);
        this.autoSetDonViDienTichDone = true;
      }
    },
    getLoaiHinhCoSo() {
      this.loaiHinhCoSo = null;
      axios.get(env.endpointhttp + "loaikhucongnghieps").then((response) => {
        this.loaiHinhs = response.data.result;
      });
    },
    getLoaiHinhONhiem() {
      axios.get(env.endpointhttp + "loaihinhoinhiem").then((response) => {
        this.loaiHinhONhiem = response.data;
      });
    },
    getCoSoTreeSelect() {
      axios.get(env.endpointhttp + "treeloaihinh").then((response) => {
        this.loaiHinhNganhNghes = response.data;
      });
    },
    changeKCN() {
      const kcn = this.co_so_san_xuat.khu_cong_nghiep;
      if (!kcn) return;

      // Set Tỉnh
      const tinh = this.tinhs.find((t) => t.id === kcn.tinh_thanh_id);
      this.co_so_san_xuat.tinh_thanhs = tinh ? [tinh] : [];

      // Set Huyện
      const huyen = this.huyens.find((h) => h.id === kcn.quan_huyen_id);
      this.co_so_san_xuat.quan_huyens = huyen ? [huyen] : [];

      // Load xã theo huyện
      axios
        .get(env.endpointhttp + "async-phuong-xa", {
          params: { quan_huyen: [kcn.quan_huyen_id] },
        })
        .then((res) => {
          this.phuongXas = res.data;

          // Nếu KCN có xã cụ thể
          if (kcn.xa_phuong) {
            const xa = res.data.find((x) => x.id === kcn.xa_phuong.id);
            this.co_so_san_xuat.phuong_xas = xa ? [xa] : [];
          } else {
            this.co_so_san_xuat.phuong_xas = [];
          }
        });

      // Set địa chỉ hoạt động
      this.co_so_san_xuat.dia_chi_hoat_dong = kcn.dia_chi;
    },

    asyncFindKhuCongNghiep(query) {
      if (query) {
        this.is_loading = true;
        axios
          .get(env.endpointhttp + "asynkhucongnghiep?search=" + query)
          .then((response) => {
            this.khucongnghieps = response.data.result;
            console.log("this.khucongnghieps", this.khucongnghieps);
          })
          .catch((error) => {})
          .finally(() => (this.is_loading = false));
      }
    },
    getToaDo(value) {
      this.co_so_san_xuat.kinh_do = value.kinhdo;
      this.co_so_san_xuat.vi_do = value.vido;
    },
    getTenDiaChi(value) {
      this.co_so_san_xuat.dia_chi_hoat_dong = value;
    },
    getLatLonByAddressText() {
      axios
        .post(env.endpointhttp + "cososanxuat/getlatlon", {
          diachi: this.co_so_san_xuat.dia_chi_hoat_dong,
        })
        .then((response) => {
          this.co_so_san_xuat.kinh_do = response.data.result.long;
          this.co_so_san_xuat.vi_do = response.data.result.lat;
        });
    },
    // ---------- Thay đổi Tỉnh ----------
    changeTinh() {
      const tinh_thanh_ids = this.co_so_san_xuat.tinh_thanhs
        ? this.co_so_san_xuat.tinh_thanhs.map((el) => el.id)
        : [];
      // lọc huyện nằm trong các tỉnh đã chọn
      this.co_so_san_xuat.quan_huyens = this.co_so_san_xuat.quan_huyens
        ? this.co_so_san_xuat.quan_huyens.filter((el) =>
            tinh_thanh_ids.includes(el.tinh_thanh_id)
          )
        : [];
      // reset xã
      // this.phuongXas = [];
      this.co_so_san_xuat.phuong_xas = [];
    },

    // ---------- Thay đổi Huyện ----------
    changeHuyen(co_so_san_xuat) {
      const quan_huyen_ids = co_so_san_xuat.quan_huyens
        ? co_so_san_xuat.quan_huyens.map((el) => el.id)
        : [];

      if (quan_huyen_ids.length > 0) {
        axios
          .get(env.endpointhttp + "async-phuong-xa", {
            params: { quan_huyen: quan_huyen_ids },
          })
          .then((response) => {
            this.phuongXas = response.data;
          });
      } else {
        // this.phuongXas = [];
      }

      // Giữ lại xã thuộc huyện đã chọn
      if (co_so_san_xuat.phuong_xas) {
        co_so_san_xuat.phuong_xas = co_so_san_xuat.phuong_xas.filter((el) =>
          quan_huyen_ids.includes(el.quan_huyen_id)
        );
      }
    },
    addcshd() {
      this.errors = {
        don_vi_cstk: null,
        don_vi_cshd: null,
        loaics: null,
        tinh: null,
        huyen: null,
      };
      if (this.form.loai_hinh_co_so == null) {
        this.errors.loaics = "Chưa chọn Loại ngành nghề kinh tế";
      } else {
        if (
          !this.co_so_san_xuat[this.keyLoaiHinhCoSo] ||
          this.co_so_san_xuat[this.keyLoaiHinhCoSo].length == 0
        ) {
          this.co_so_san_xuat[this.keyLoaiHinhCoSo] = [];
        }
        this.co_so_san_xuat[this.keyLoaiHinhCoSo].push({
          don_vi_thiet_ke: this.form.don_vi_cong_suat_thiet_ke,
          don_vi_hoat_dong: this.form.don_vi_cong_suat_hoat_dong,
          loai_hinh_co_so: this.form.loai_hinh_co_so,
          loai_hinh_co_so_id: this.form.loai_hinh_co_so.id,
          don_vi_hoat_dong_id: this.form.don_vi_cong_suat_hoat_dong
            ? this.form.don_vi_cong_suat_hoat_dong.id
            : null,
          thong_so_hoat_dong: this.form.cong_suat_hoat_dong,
          don_vi_thiet_ke_id: this.form.don_vi_cong_suat_thiet_ke
            ? this.form.don_vi_cong_suat_thiet_ke.id
            : null,
          thong_so_thiet_ke: this.form.cong_suat_thiet_ke,
          thong_so: this.form.cong_suat_thiet_ke,
          ghi_chu: this.form.ghi_chu,
        });
        this.form = {};
      }
    },
    deletecshd(index) {
      this.co_so_san_xuat[this.keyLoaiHinhCoSo].splice(index, 1);
    },
  },
};
</script>

<style scoped></style>
