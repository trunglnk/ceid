<template>
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <div class="row">
        <div class="col-md-6">
          <h2 class="page-header-button-header title_master_form">
            Thêm mới kết quả thanh tra
          </h2>
        </div>
        <div class="col-md-6">
          <button
            type="submit"
            id="onsubmit"
            class="btn bg-olive btn-flat pull-right margin-top margin-left"
            :disabled="is_loading || !is_valid"
            tabindex="22"
            @click="addKetQuaThanhTra()"
          >
            <i class="fa fa-plus"></i> Thêm mới
          </button>
          <a
            href="/cososanxuat"
            class="btn btn-default btn-flat pull-right margin-top"
          >
            <i class="fa fa-undo"></i> Trở lại
          </a>
        </div>
      </div>
      <div class="row block-multiple-rows">
        <div class="col-lg-12" style="margin-top: 5px">
          <label>THÔNG TIN CHUNG</label>
          <hr style="margin-top: 7px; margin-bottom: 7px" />
        </div>
        <div class="col-lg-4">
          <label>Quyết định thanh tra<span style="color: red">*</span></label>
          <multiselect
            v-model="form_data.quyet_dinh_thanh_tra"
            label="so_quyet_dinh"
            track-by="id"
            placeholder="Gõ số quyết định thành lập đoàn thanh tra"
            selectLabel="Nhấn enter để chọn"
            deselectLabel="Nhấn enter để bỏ chọn"
            selectedLabel="Đã chọn"
            open-direction="bottom"
            :options="this.quyetdinhthanhtras"
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
            @search-change="asyncFindQuyetDinh"
          >
            <span slot="noResult">Không tìm thấy số quyết định</span>
          </multiselect>
          <span style="color: red">{{ this.error_quyet_dinh }}</span>
        </div>
        <div class="col-lg-4">
          <label>Số kết luận<span style="color: red">*</span></label>
          <input
            class="form-control"
            type="text"
            v-model="form_data.so_quyet_dinh"
          />
          <span style="color: red">{{ this.error_so_quyet_dinh }}</span>
        </div>
        <div class="col-lg-4">
          <label>Ngày kết luận</label>
          <date-picker
            v-model="form_data.ngay_ket_luan"
            placeholder="Ngày kết nhận"
            :config="optionsDate"
          >
          </date-picker>
        </div>
      </div>
      <div class="row block-multiple-rows">
        <div class="col-sm-12 col-lg-12">
          <div class="block-multiple-rows padding-left padding-right">
            <div class="row">
              <div class="col-lg-12" style="margin-top: 5px">
                <label>THÔNG TIN CƠ SỞ THANH TRA</label>
                <p class="text-muted">
                  (Nhập tên để tìm kiếm cơ sở thanh tra trong hệ thống. Nếu
                  không tồn tại cơ sở thanh tra trong hệ thống, vui lòng nhấn
                  nút thêm mới cơ sở thanh tra)
                </p>
                <hr style="margin-top: 7px; margin-bottom: 7px" />
              </div>
              <div class="col-lg-8 col-md-12">
                <label class="control-label">Cơ sở thanh tra</label>
                <multiselect
                  v-model="orgnaization"
                  label="ten"
                  track-by="id"
                  placeholder="Gõ tên cơ sở thanh tra"
                  selectLabel="Nhấn enter để chọn"
                  deselectLabel="Nhấn enter để bỏ chọn"
                  selectedLabel="Đã chọn"
                  open-direction="bottom"
                  :options="this.orgnaizations"
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
                  @search-change="asyncFindToChuc"
                  @input="selectToChuc"
                >
                  <span slot="noResult">Không tìm thấy thông tin</span>
                </multiselect>
              </div>
              <div class="col-lg-4 col-md-12">
                <button
                  v-if="form_data.co_so_san_xuats.length == 0"
                  type="submit"
                  class="btn btn-default btn-flat"
                  style="margin-top: 25px; height: 40px"
                  :disabled="is_loading"
                  tabindex="22"
                  @click="addCoSoSanXuat()"
                >
                  <i class="fa fa-plus"></i> Thêm mới cơ sở thanh tra
                </button>
              </div>
            </div>
          </div>
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
          <label style="cursor: pointer; text-decoration: underline;"  @click="goDanhSachCDT">CHỦ ĐẦU TƯ</label>
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
              <!-- <button
                type="submit"
                id="onsubmit"
                class="btn bg-olive btn-flat pull-right"
                @click="goAddChuDauTu"
              >
                <i class="fa fa-plus"></i> Thêm chủ đầu tư
              </button> -->
            </div>
          </div>
        </div>
        <br />
        <div class="col-lg-12" v-if="chuDauTu">
          <div class="col-lg-6">
            <div class="pt-2">Địa chỉ: {{ chuDauTu.dia_chi }}</div>
            <div class="pt-2">Mã số QLCTNH: {{ chuDauTu.ma_so_qlctnh }}</div>
          </div>
          <div class="col-lg-3">
            <div>
              Giấy đăng ký KD:
              {{ chuDauTu.so_giay_chung_nhan_dang_ky_kinh_doanh }}
            </div>
            <div class="pt-2">
              Cơ quan cấp:
              {{
                chuDauTu.co_quan_cap_giay_kinh_doanh
                  ? chuDauTu.co_quan_cap_giay_kinh_doanh.ten
                  : ""
              }}
            </div>
            <div class="pt-2">
              Ngày cấp:
              {{
                chuDauTu.ngay_cap_giay_chung_nhan_dang_ky_kinh_doanh
                  ? new Date(
                      chuDauTu.ngay_cap_giay_chung_nhan_dang_ky_kinh_doanh
                    ).toLocaleDateString("en-TT")
                  : ""
              }}
            </div>
          </div>
          <div class="col-lg-3">
            <div>
              Giấy đăng ký đầu tư:
              {{ chuDauTu.so_giay_chung_nhan_dau_tu }}
            </div>
            <div class="pt-2">
              Cơ quan cấp:
              {{ chuDauTu.co_quan_cap_giay_chung_nhan_dau_tu }}
            </div>
            <div class="pt-2">
              Ngày cấp:
              {{
                chuDauTu.ngay_cap_giay_chung_nhan_dau_tu
                  ? new Date(
                      chuDauTu.ngay_cap_giay_chung_nhan_dau_tu
                    ).toLocaleDateString("en-TT")
                  : ""
              }}
            </div>
          </div>
        </div>
      </div>
      <template
        v-if="
          usersystem.role_code == 'admin' ||
          usersystem.role_code == 'nhanvientrungtam' ||
          usersystem.role_code == 'adminvaquanlydanhmuc'
        "
      >
        <div
          class="row block-multiple-rows"
          v-if="form_data.co_so_san_xuats.length > 0"
        >
          <div class="col-lg-8 col-md-12" style="margin-top: 5px">
            <p class="text-muted">
              Nếu cơ sở thanh tra có địa điểm hoạt động mới, nhấn nút thêm mới
              địa điểm hoạt động
            </p>
          </div>
          <div class="col-lg-4 col-md-12" style="margin-top: 5px">
            <button
              type="submit"
              class="btn btn-default btn-flat pull-right"
              tabindex="22"
              @click="addDiaDiemHoatDong()"
            >
              <i class="fa fa-plus"></i> Thêm mới địa điểm hoạt động
            </button>
          </div>
        </div>
      </template>

      <div
        class="nav-tabs-custom margin-top"
        v-if="form_data.co_so_san_xuats.length > 1"
      >
        <ul class="nav nav-tabs">
          <li
            v-for="(co_so_san_xuat, index) in form_data.co_so_san_xuats"
            :key="co_so_san_xuat.id"
            :class="{ active: currentTab == co_so_san_xuat.id }"
            @click="currentTab = co_so_san_xuat.id"
          >
            <a data-toggle="tab" aria-expanded="true" href="#">{{
              `Địa điểm hoạt động số ${parseInt(index) + 1}`
            }}</a>
          </li>
        </ul>
      </div>
      <div
        v-for="(co_so_san_xuat, index1) in form_data.co_so_san_xuats"
        :key="co_so_san_xuat.id"
        v-show="currentTab == co_so_san_xuat.id"
      >
        <diaDiemHoatDong
          :value="co_so_san_xuat"
          @input="form_data.co_so_san_xuats[index1] = $event"
          :usersystem="usersystem"
          :donvicongsuatthietkes="donvicongsuatthietkes"
          :donvicongsuathoatdongs="donvicongsuathoatdongs"
          :tinhs="tinhs"
          :huyens="huyens"
          :optionsDate="optionsDate"
          :coquantochucs="coquantochucs"
          :donvidientich="donvidientich"
          :donvinuoc="donvinuoc"
          :loai_hinh_co_so_tree_listing="loai_hinh_co_so_tree_listing"
          @click-delete="deleteDiaChiHoatDong(index1)"
          keyLoaiHinhCoSo="chi_tiet_cong_suat"
        />

        <div class="row block-multiple-rows">
          <div class="col-lg-12" style="margin-top: 5px">
            <label>KẾT QUẢ THANH TRA CHẤT THẢI</label>
            <p class="text-muted">
              (Click vào từng mục nước thải, khí thải, chất thải rắn sinh hoạt,
              chất thải rắn công nghiệp thông thường, và chất thải nguy hại)
            </p>
            <hr style="margin-top: 7px; margin-bottom: 7px" />
          </div>
          <div class="col-md-12">
            <div class="nav-tabs-custom margin-top">
              <ul class="nav nav-tabs">
                <li class="active">
                  <a
                    :href="'#tab_1' + index1"
                    data-toggle="tab"
                    aria-expanded="true"
                    >Nước thải</a
                  >
                </li>
                <li class="">
                  <a
                    :href="'#tab_2' + index1"
                    data-toggle="tab"
                    aria-expanded="false"
                    >Khí thải,bụi</a
                  >
                </li>
                <li class="">
                  <a
                    :href="'#tab_3' + index1"
                    data-toggle="tab"
                    aria-expanded="false"
                    >Chất thải rắn sinh hoạt</a
                  >
                </li>
                <li class="">
                  <a
                    :href="'#tab_4' + index1"
                    data-toggle="tab"
                    aria-expanded="false"
                    >Chất thải rắn công nghiệp thông thường</a
                  >
                </li>
                <li class="">
                  <a
                    :href="'#tab_5' + index1"
                    data-toggle="tab"
                    aria-expanded="false"
                    >Chất thải nguy hại</a
                  >
                </li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" :id="'tab_1' + index1">
                  <div class="row block-multiple-rows">
                    <div class="col-lg-12" style="margin-top: 5px">
                      <p class="text-muted">
                        Nhập thông tin lưu lượng nước thải, công suất hệ thống,
                        loại nước thải phát sinh ... và nhấn nút thêm để thêm
                        kết quả thanh tra nước thải
                      </p>
                      <hr style="margin-top: 7px; margin-bottom: 7px" />
                    </div>
                    <div class="col col-lg-2 col-md-4">
                      <div class="form-group">
                        <ejs-numerictextbox
                          class="form-control"
                          format=""
                          v-model="
                            co_so_san_xuat.intermediate_data.chatthais.nuocthai
                              .luu_luong_nt
                          "
                          placeholder="Lưu lượng m3/ngày đêm"
                        >
                        </ejs-numerictextbox>
                      </div>
                    </div>
                    <div class="col col-lg-2 col-md-4">
                      <div class="form-group">
                        <ejs-numerictextbox
                          class="form-control"
                          format=""
                          v-model="
                            co_so_san_xuat.intermediate_data.chatthais.nuocthai
                              .cong_suat_xl_nt
                          "
                          placeholder="Công suất hệ thống m3/ngày đêm"
                        ></ejs-numerictextbox>
                      </div>
                    </div>
                    <div class="col col-lg-2 col-md-4">
                      <div class="form-group">
                        <input
                          type="text"
                          class="form-control"
                          v-model="
                            co_so_san_xuat.intermediate_data.chatthais.nuocthai
                              .thanh_phan_nt
                          "
                          placeholder="Loại nước thải phát sinh"
                        />
                      </div>
                    </div>
                    <div class="col col-lg-2 col-md-4">
                      <div class="form-group">
                        <input
                          type="text"
                          class="form-control"
                          v-model="
                            co_so_san_xuat.intermediate_data.chatthais.nuocthai
                              .thong_so_vuot_nt
                          "
                          placeholder="Thông số vượt QCKT"
                        />
                      </div>
                    </div>
                    <div class="col col-lg-3 col-md-6">
                      <div class="form-group">
                        <input
                          type="text"
                          class="form-control"
                          v-model="
                            co_so_san_xuat.intermediate_data.chatthais.nuocthai
                              .nguon_tiep_nhan_nt
                          "
                          placeholder="Nguồn tiếp nhận"
                        />
                      </div>
                    </div>
                    <div class="col col-lg-1 col-md-2">
                      <button
                        type="button"
                        class="btn btn-flat pull-right btn-block"
                        style="height: 40px"
                        @click="addNuocthai(co_so_san_xuat)"
                      >
                        <i class="fa fa-plus"></i> Thêm
                      </button>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <table
                        class="table table-hover table-responsive"
                        role="grid"
                      >
                        <thead>
                          <tr class="row-table-header">
                            <th>
                              Lưu lượng<span style="color: rgb(216, 27, 96)"
                                >(m3/ngày đêm)</span
                              >
                            </th>
                            <th>
                              Công suất hệ thống xử lý<span
                                style="color: rgb(216, 27, 96)"
                                >(m3/ngày đêm)</span
                              >
                            </th>
                            <th>Loại nước thải phát sinh</th>
                            <th>Nguồn tiếp nhận</th>
                            <th>Thông số vượt QCKT</th>
                            <th style="width: 5%; text-align: center">Xóa</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(item, index) in co_so_san_xuat.chatthais
                              .nuocthai"
                          >
                            <td>{{ item.luu_luong }}</td>
                            <td>{{ item.cong_suat_he_thong_xu_ly }}</td>
                            <td>{{ item.thanh_phan }}</td>
                            <td>{{ item.nguon_tiep_nhan }}</td>
                            <td>{{ item.thong_so_nuoc_thai_vuot_chuan }}</td>
                            <td align="center">
                              <a @click="deleteNuocthai(co_so_san_xuat, index)">
                                <i class="fa fa-trash-o"></i>
                              </a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="tab-pane" :id="'tab_2' + index1">
                  <div class="row block-multiple-rows">
                    <div class="col-lg-12" style="margin-top: 5px">
                      <p class="text-muted">
                        Nhập thông tin lưu lượng khí thải, công suất hệ thống,
                        thành phần ... và nhấn nút thêm để thêm kết quả thanh
                        tra khí thải
                      </p>
                      <hr style="margin-top: 7px; margin-bottom: 7px" />
                    </div>
                    <div class="col col-lg-2 col-md-4">
                      <div class="form-group">
                        <ejs-numerictextbox
                          type="text"
                          class="form-control"
                          format=""
                          v-model="
                            co_so_san_xuat.intermediate_data.chatthais.khithai
                              .luu_luong_kt
                          "
                          placeholder="Lưu lượng khí thải (m3/giờ)"
                        >
                        </ejs-numerictextbox>
                      </div>
                    </div>
                    <div class="col col-lg-2 col-md-4">
                      <div class="form-group">
                        <ejs-numerictextbox
                          type="text"
                          class="form-control"
                          format=""
                          v-model="
                            co_so_san_xuat.intermediate_data.chatthais.khithai
                              .cong_suat_xl_kt
                          "
                          placeholder="Công suất hệ thống (m3/ngày đêm)"
                        ></ejs-numerictextbox>
                      </div>
                    </div>
                    <div class="col col-lg-2 col-md-4">
                      <div class="form-group">
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Nguồn phát sinh"
                          v-model="
                            co_so_san_xuat.intermediate_data.chatthais.khithai
                              .thanh_phan_kt
                          "
                        />
                      </div>
                    </div>
                    <div class="col col-lg-2 col-md-6">
                      <div class="form-group">
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Thông số vượt chuẩn"
                          v-model="
                            co_so_san_xuat.intermediate_data.chatthais.khithai
                              .thong_so_vuot_kt
                          "
                        />
                      </div>
                    </div>
                    <div class="col col-lg-11 col-md-10">
                      <div class="form-group">
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Quy trình xử lý"
                          v-model="
                            co_so_san_xuat.intermediate_data.chatthais.khithai
                              .quy_trinh_xu_ly
                          "
                        />
                      </div>
                    </div>
                    <div class="col col-lg-1 col-md-2">
                      <button
                        type="button"
                        class="btn btn-default btn-flat pull-right btn-block"
                        style="height: 40px"
                        @click="addKhithai(co_so_san_xuat)"
                      >
                        <i class="fa fa-plus"></i> Thêm
                      </button>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <table class="table table-hover table-responsive">
                        <thead>
                          <tr class="row-table-header">
                            <th>
                              Lưu lượng<span style="color: rgb(216, 27, 96)"
                                >(m3/giờ)</span
                              >
                            </th>
                            <th>
                              Công suất hệ thống xử lý<span
                                style="color: rgb(216, 27, 96)"
                                >(m3/ngày đêm)</span
                              >
                            </th>
                            <th>Nguồn phát sinh</th>
                            <th>Thông số vượt chuẩn</th>
                            <th>Quy trình xử lý</th>
                            <th style="width: 5%; text-align: center">Xóa</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(item, index) in co_so_san_xuat.chatthais
                              .khithai"
                          >
                            <td>{{ item.luu_luong }}</td>
                            <td>{{ item.cong_suat_he_thong_xu_ly }}</td>
                            <td>{{ item.thanh_phan }}</td>
                            <td>{{ item.thong_so_nuoc_thai_vuot_chuan }}</td>
                            <td>{{ item.quy_trinh_xu_ly }}</td>
                            <td align="center">
                              <a @click="deleteKhithai(co_so_san_xuat, index)">
                                <i class="fa fa-trash-o"></i>
                              </a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" :id="'tab_3' + index1">
                  <div class="row block-multiple-rows">
                    <div class="col-lg-12" style="margin-top: 5px">
                      <p class="text-muted">
                        Nhập thông tin khối lượng phát sinh, đơn vị, thành phần
                        chính chất thải rắn ... và nhấn nút thêm để thêm kết quả
                        thanh tra chất thải rắn sinh hoạt
                      </p>
                      <hr style="margin-top: 7px; margin-bottom: 7px" />
                    </div>
                    <div class="col-m-12 margin-left margin-right">
                      <div class="row">
                        <div class="col col-lg-4 col-md-6">
                          <div class="form-group">
                            <ejs-numerictextbox
                              type="text"
                              class="form-control"
                              format=""
                              v-model="
                                co_so_san_xuat.intermediate_data.chatthais.ctrsh
                                  .khoi_luong_phat_sinh_ctrsh
                              "
                              placeholder="Khối lượng phát sinh"
                            ></ejs-numerictextbox>
                          </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                          <div class="input-group">
                            <multiselect
                              v-model="
                                co_so_san_xuat.intermediate_data.chatthais.ctrsh
                                  .don_vi_ctrsh
                              "
                              label="ten"
                              track-by="id"
                              placeholder="Gõ đơn vị"
                              selectedLabel="Đã chọn"
                              open-direction="bottom"
                              :options="donvikhoiluongphatsinhs"
                              :multiple="false"
                              :searchable="true"
                              :clear-on-select="true"
                              :close-on-select="true"
                              :show-no-results="false"
                              :hide-selected="false"
                              :tabindex="1"
                            >
                              <span slot="noResult"
                                >Không tìm thấy kết quả</span
                              >
                            </multiselect>
                            <span class="input-group-addon btn"
                              ><a @click="refreshDVCTRSH"
                                ><i class="fa fa-refresh"></i></a
                            ></span>
                          </div>
                        </div>
                        <div class="col col-lg-6 col-md-12">
                          <div class="form-group">
                            <input
                              type="text"
                              class="form-control"
                              placeholder="Thành phần chính CTRSH"
                              v-model="
                                co_so_san_xuat.intermediate_data.chatthais.ctrsh
                                  .thanh_phan_chinh_ctrsh
                              "
                            />
                          </div>
                        </div>
                        <div
                          class="col-lg-2 col-md-3"
                          v-if="
                            !co_so_san_xuat.intermediate_data.chatthais.ctrsh
                              .thue_xu_ly_ctrsh
                          "
                        >
                          <checkbox-component
                            v-model="
                              co_so_san_xuat.intermediate_data.chatthais.ctrsh
                                .tu_xu_ly_ctrsh
                            "
                            type="checkbox"
                            :id="'tu_xu_ly_ctrsh' + index1"
                            text="Tự xử lý"
                          >
                          </checkbox-component>
                        </div>
                        <div
                          class="col-lg-2 col-md-3"
                          v-if="
                            !co_so_san_xuat.intermediate_data.chatthais.ctrsh
                              .tu_xu_ly_ctrsh
                          "
                        >
                          <checkbox-component
                            v-model="
                              co_so_san_xuat.intermediate_data.chatthais.ctrsh
                                .thue_xu_ly_ctrsh
                            "
                            type="checkbox"
                            :id="'thue_xu_ly_ctrsh' + index1"
                            text="Thuê xử lý"
                          >
                          </checkbox-component>
                        </div>
                      </div>
                      <div class="row">
                        <div
                          class="col-lg-4 col-md-10"
                          v-if="
                            co_so_san_xuat.intermediate_data.chatthais.ctrsh
                              .thue_xu_ly_ctrsh
                          "
                        >
                          <input
                            type="text"
                            class="form-control"
                            v-model="
                              co_so_san_xuat.intermediate_data.chatthais.ctrsh
                                .co_quan_thue_xu_ly_ctrsh
                            "
                            placeholder="Đơn vị xử lý"
                          />
                        </div>
                        <div class="col-lg-2 col-md-2">
                          <button
                            type="button"
                            class="btn btn-flat pull-right btn-block"
                            style="height: 40px"
                            @click="addCtrsh(co_so_san_xuat)"
                          >
                            <i class="fa fa-plus"></i> Thêm
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <table class="table table-hover table-responsive">
                        <thead>
                          <tr class="row-table-header">
                            <th>
                              Khối lượng phát sinh<span
                                style="color: rgb(216, 27, 96)"
                                v-text="'(' + ten_don_vi_ctrsh + ')'"
                              ></span>
                            </th>
                            <th>Thành phần</th>
                            <th>Tự xử lý</th>
                            <th>Thuê xử lý</th>
                            <th style="width: 5%; text-align: center">Xóa</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(item, index) in co_so_san_xuat.chatthais
                              .ctrsh"
                          >
                            <td>{{ item.khoi_luong_phat_sinh | f2Number }}</td>
                            <td>{{ item.thanh_phan_chinh }}</td>
                            <td>
                              <i
                                class="fa fa-check-square-o"
                                v-if="item.tu_xu_ly_ctrsh"
                              ></i>
                              <i class="fa fa-square-o" v-else></i>
                            </td>
                            <td>
                              <i
                                class="fa fa-check-square-o"
                                v-if="item.thue_xu_ly_ctrsh"
                              ></i>
                              <i class="fa fa-square-o" v-else></i>
                              {{ item.co_quan_thue_xu_ly }}
                            </td>
                            <td align="center">
                              <a @click="deleteCtrsh(co_so_san_xuat, index)">
                                <i class="fa fa-trash-o"></i>
                              </a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="tab-pane" :id="'tab_4' + index1">
                  <div class="row block-multiple-rows">
                    <div class="col-lg-12" style="margin-top: 5px">
                      <p class="text-muted">
                        Nhập thông tin khối lượng phát sinh, đơn vị, thành phấn
                        chính... và nhấn nút thêm để thêm kết quả thanh tra chất
                        thải rắn công nghiệp thông thường
                      </p>
                      <hr style="margin-top: 7px; margin-bottom: 7px" />
                    </div>
                    <div class="col-md-12 margin-left margin-right">
                      <div class="row">
                        <div class="col col-lg-4 col-md-6">
                          <div class="form-group">
                            <ejs-numerictextbox
                              type="text"
                              class="form-control"
                              format=""
                              v-model="
                                co_so_san_xuat.intermediate_data.chatthais
                                  .ctrcntt.khoi_luong_phat_sinh_ctrcntt
                              "
                              placeholder="Khối lượng phát sinh"
                            ></ejs-numerictextbox>
                          </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                          <div class="input-group">
                            <multiselect
                              v-model="
                                co_so_san_xuat.intermediate_data.chatthais
                                  .ctrcntt.don_vi_ctrcntt
                              "
                              label="ten"
                              track-by="id"
                              placeholder="Gõ đơn vị"
                              selectedLabel="Đã chọn"
                              open-direction="bottom"
                              :options="donvikhoiluongphatsinhs"
                              :multiple="false"
                              :searchable="true"
                              :clear-on-select="true"
                              :close-on-select="true"
                              :show-no-results="false"
                              :hide-selected="false"
                              :tabindex="1"
                            >
                              <span slot="noResult"
                                >Không tìm thấy kết quả</span
                              >
                            </multiselect>
                            <span class="input-group-addon btn"
                              ><a @click="refreshDVCTRSH"
                                ><i class="fa fa-refresh"></i></a
                            ></span>
                          </div>
                        </div>
                        <div class="col col-lg-6 col-md-12">
                          <div class="form-group">
                            <input
                              type="text"
                              class="form-control"
                              v-model="
                                co_so_san_xuat.intermediate_data.chatthais
                                  .ctrcntt.thanh_phan_chinh_ctrcntt
                              "
                              placeholder="Thành phần chính"
                            />
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div
                          class="col-lg-2 col-md-3"
                          v-if="
                            !co_so_san_xuat.intermediate_data.chatthais.ctrcntt
                              .thue_xu_ly_ctrcntt
                          "
                        >
                          <checkbox-component
                            v-model="
                              co_so_san_xuat.intermediate_data.chatthais.ctrcntt
                                .tu_xu_ly_ctrcntt
                            "
                            type="checkbox"
                            :id="'tu_xu_ly_ctrcntt' + index1"
                            text="Tự xử lý"
                          >
                          </checkbox-component>
                        </div>
                        <div
                          class="col-lg-2 col-md-3"
                          v-if="
                            !co_so_san_xuat.intermediate_data.chatthais.ctrcntt
                              .tu_xu_ly_ctrcntt
                          "
                        >
                          <checkbox-component
                            v-model="
                              co_so_san_xuat.intermediate_data.chatthais.ctrcntt
                                .thue_xu_ly_ctrcntt
                            "
                            type="checkbox"
                            :id="'thue_xu_ly_ctrsh1' + index1"
                            text="Thuê xử lý"
                          >
                          </checkbox-component>
                        </div>
                      </div>
                      <div class="row">
                        <div
                          class="col-lg-4 col-md-10"
                          v-if="
                            co_so_san_xuat.intermediate_data.chatthais.ctrcntt
                              .thue_xu_ly_ctrcntt
                          "
                        >
                          <input
                            type="text"
                            class="form-control"
                            v-model="
                              co_so_san_xuat.intermediate_data.chatthais.ctrcntt
                                .co_quan_thue_xu_ly_ctrcntt
                            "
                            placeholder="Đơn vị xử lý"
                          />
                        </div>
                        <div class="col-lg-2 col-md-2">
                          <button
                            type="button"
                            class="btn bg-flat btn-flat pull-right btn-block"
                            style="height: 40px"
                            @click="addCtrcntt(co_so_san_xuat)"
                          >
                            <i class="fa fa-plus"></i> Thêm
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <table class="table table-hover table-responsive">
                        <thead>
                          <tr class="row-table-header">
                            <th>
                              Khối lượng phát sinh<span
                                style="color: rgb(216, 27, 96)"
                                v-text="'(' + ten_don_vi_ctrsh + ')'"
                              ></span>
                            </th>
                            <th>Thành phần</th>
                            <th>Tự xử lý</th>
                            <th>Thuê xử lý</th>
                            <th style="width: 5%; text-align: center">Xóa</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(item, index) in co_so_san_xuat.chatthais
                              .ctrcntt"
                          >
                            <td>{{ item.khoi_luong_phat_sinh | f2Number }}</td>
                            <td>{{ item.thanh_phan_chinh }}</td>
                            <td>
                              <i
                                class="fa fa-check-square-o"
                                v-if="item.tu_xu_ly_ctrcntt"
                              ></i>
                              <i class="fa fa-square-o" v-else></i>
                            </td>
                            <td>
                              <i
                                class="fa fa-check-square-o"
                                v-if="item.thue_xu_ly_ctrcntt"
                              ></i>
                              <i class="fa fa-square-o" v-else></i>
                              {{ item.co_quan_thue_xu_ly_ctrcntt }}
                            </td>
                            <td align="center">
                              <a @click="deleteCtrcntt(co_so_san_xuat, index)">
                                <i class="fa fa-trash-o"></i>
                              </a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="tab-pane" :id="'tab_5' + index1">
                  <div class="row block-multiple-rows">
                    <div class="col-lg-12" style="margin-top: 5px">
                      <p class="text-muted">
                        Nhập thông tin khối lượng phát sinh thực tế, khối lượng
                        phát sinh theo đăng ký, thành phấn chính... và nhấn nút
                        thêm để thêm kết quả thanh tra chất thải nguy hại.
                        Trường hợp thiếu đơn vị, mở tab mới danh mục, trong mục
                        đơn vị chất thải nguy hại, thêm mới đơn vị và nhấn biểu
                        tượng refresh ô đơn vị
                      </p>
                      <hr style="margin-top: 7px; margin-bottom: 7px" />
                    </div>
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-lg-2 col-md-7">
                          <ejs-numerictextbox
                            type="text"
                            class="form-control"
                            format=".###"
                            v-model="
                              co_so_san_xuat.intermediate_data.chatthais.ctnh
                                .khoi_luong_phat_sinh_thuc_te_ctnh
                            "
                            placeholder="Khối lượng phát sinh thực tế"
                          >
                          </ejs-numerictextbox>
                        </div>
                        <div class="col-lg-2 col-md-5">
                          <div class="input-group">
                            <multiselect
                              v-model="
                                co_so_san_xuat.intermediate_data.chatthais.ctnh
                                  .don_vi_ctnh
                              "
                              label="ten"
                              track-by="id"
                              placeholder="Gõ đơn vị"
                              selectedLabel="Đã chọn"
                              open-direction="bottom"
                              :options="donvikhoiluongphatsinhs"
                              :multiple="false"
                              :searchable="true"
                              :clear-on-select="true"
                              :close-on-select="true"
                              :show-no-results="false"
                              :hide-selected="false"
                              :tabindex="1"
                            >
                              <span slot="noResult"
                                >Không tìm thấy kết quả</span
                              >
                            </multiselect>
                            <span class="input-group-addon btn"
                              ><a @click="refreshDVCTNH"
                                ><i class="fa fa-refresh"></i></a
                            ></span>
                          </div>
                        </div>
                        <div class="col-lg-2 col-md-7">
                          <ejs-numerictextbox
                            class="form-control"
                            format=".###"
                            v-model="
                              co_so_san_xuat.intermediate_data.chatthais.ctnh
                                .khoi_luong_phat_sinh_theo_dk_ctnh
                            "
                            placeholder="Khối lượng phát sinh theo đăng ký"
                          >
                          </ejs-numerictextbox>
                        </div>
                        <div class="col-lg-2 col-md-5">
                          <div class="input-group">
                            <multiselect
                              v-model="
                                co_so_san_xuat.intermediate_data.chatthais.ctnh
                                  .don_vi_ctnhdk
                              "
                              label="ten"
                              track-by="id"
                              placeholder="Gõ đơn vị"
                              selectedLabel="Đã chọn"
                              open-direction="bottom"
                              :options="donvikhoiluongphatsinhs"
                              :multiple="false"
                              :searchable="true"
                              :clear-on-select="true"
                              :close-on-select="true"
                              :show-no-results="false"
                              :hide-selected="false"
                              :tabindex="1"
                            >
                              <span slot="noResult"
                                >Không tìm thấy kết quả</span
                              >
                            </multiselect>
                            <span class="input-group-addon btn"
                              ><a @click="refreshDVCTNH"
                                ><i class="fa fa-refresh"></i></a
                            ></span>
                          </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                          <input
                            type="text"
                            class="form-control"
                            v-model="
                              co_so_san_xuat.intermediate_data.chatthais.ctnh
                                .thanh_phan_chinh_ctnh
                            "
                            placeholder="Thành phần chính"
                          />
                        </div>
                      </div>
                      <br />
                      <div class="row">
                        <div
                          class="col-lg-2 col-md-3"
                          v-if="
                            !co_so_san_xuat.intermediate_data.chatthais.ctnh
                              .thue_xu_ly_ctnh
                          "
                        >
                          <checkbox-component
                            v-model="
                              co_so_san_xuat.intermediate_data.chatthais.ctnh
                                .tu_xu_ly_ctnh
                            "
                            type="checkbox"
                            :id="'tuxuly' + index1"
                            text="Tự xử lý"
                          >
                          </checkbox-component>
                        </div>
                        <div
                          class="col-lg-2 col-md-3"
                          v-if="
                            !co_so_san_xuat.intermediate_data.chatthais.ctnh
                              .tu_xu_ly_ctnh
                          "
                        >
                          <checkbox-component
                            v-model="
                              co_so_san_xuat.intermediate_data.chatthais.ctnh
                                .thue_xu_ly_ctnh
                            "
                            type="checkbox"
                            :id="'thuexuly' + index1"
                            text="Thuê xử lý"
                          >
                          </checkbox-component>
                        </div>
                      </div>
                      <div class="row">
                        <div
                          class="col-lg-3 col-md-10"
                          v-if="
                            co_so_san_xuat.intermediate_data.chatthais.ctnh
                              .thue_xu_ly_ctnh
                          "
                        >
                          <input
                            type="text"
                            class="form-control"
                            v-model="
                              co_so_san_xuat.intermediate_data.chatthais.ctnh
                                .co_quan_thue_xu_ly_ctnh
                            "
                            placeholder="Đơn vị xử lý"
                          />
                        </div>
                        <div class="col-lg-2 col-md-2">
                          <button
                            type="button"
                            class="btn btn-flat pull-right btn-block"
                            style="height: 40px"
                            @click="addchatthainguyhai(co_so_san_xuat)"
                          >
                            <i class="fa fa-plus"></i> Thêm mới
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <table class="table table-hover table-responsive">
                        <thead>
                          <tr class="row-table-header">
                            <th>
                              Khối lượng phát sinh thực tế<span
                                style="color: rgb(216, 27, 96)"
                                v-text="'(' + ten_don_vi_ctrsh + ')'"
                              >
                              </span>
                            </th>
                            <th>
                              Khối lượng phát sinh theo đăng ký<span
                                style="color: rgb(216, 27, 96)"
                                v-text="'(' + ten_don_vi_ctrsh + ')'"
                              >
                              </span>
                            </th>
                            <th>Thành phần chính</th>
                            <th>Tự xử lý</th>
                            <th>Thuê xử lý</th>
                            <th style="width: 5%; text-align: center">Xóa</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(item, index) in co_so_san_xuat.chatthais
                              .ctnh"
                          >
                            <td>
                              {{
                                item.khoi_luong_phat_sinh_thuc_te_ctnh
                                  | f2Number
                              }}
                            </td>
                            <td>
                              {{
                                item.khoi_luong_phat_sinh_theo_dk_ctnh
                                  | f2Number
                              }}
                            </td>
                            <td>{{ item.thanh_phan_chinh }}</td>
                            <td>
                              <i
                                class="fa fa-check-square-o"
                                v-if="item.tu_xu_ly_ctnh"
                              ></i>
                              <i class="fa fa-square-o" v-else></i>
                            </td>
                            <td>
                              <i
                                class="fa fa-check-square-o"
                                v-if="item.thue_xu_ly_ctnh"
                              ></i>
                              <i class="fa fa-square-o" v-else></i>
                              {{ item.co_quan_thue_xu_ly_ctnh }}
                            </td>
                            <td align="center">
                              <a @click="deleteCtnh(co_so_san_xuat, index)">
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
            </div>
          </div>
        </div>

        <div class="row block-multiple-rows">
          <div class="col-lg-12" style="margin-top: 5px">
            <label>KẾT QUẢ THANH TRA THỦ TỤC HÀNH CHÍNH</label>
            <p class="text-muted">
              (Click vào từng mục chi tiết để nhập thông tin kết quả thanh tra
              thủ tục hành chính.)
            </p>
            <hr style="margin-top: 7px; margin-bottom: 7px" />
          </div>
          <div class="col-md-12">
            <div class="nav-tabs-custom margin-top">
              <ul class="nav nav-tabs">
                <li class="active">
                  <a
                    :href="'#tab_thutuc_1' + index1"
                    data-toggle="tab"
                    aria-expanded="true"
                    >ĐTM</a
                  >
                </li>
                <li class="">
                  <a
                    :href="'#tab_thutuc_2' + index1"
                    data-toggle="tab"
                    aria-expanded="false"
                    >Đề án bảo vệ môi trường</a
                  >
                </li>
                <li class="">
                  <a
                    :href="'#tab_thutuc_3' + index1"
                    data-toggle="tab"
                    aria-expanded="false"
                    >Kế hoạch BVMT/Cam kết BVMT/bản đăng ký đạt TCMT</a
                  >
                </li>
                <li class="">
                  <a
                    :href="'#tab_thutuc_4' + index1"
                    data-toggle="tab"
                    aria-expanded="false"
                    >Giấy xác nhận công trình BVMT</a
                  >
                </li>
                <li class="">
                  <a
                    :href="'#tab_thutuc_5' + index1"
                    data-toggle="tab"
                    aria-expanded="false"
                    >Sổ đăng kí chủ nguồn thải CTNH</a
                  >
                </li>
                <li class="">
                  <a
                    :href="'#tab_thutuc_6' + index1"
                    data-toggle="tab"
                    aria-expanded="false"
                    >Giấy xác nhận đủ điều kiện BVMT nhập khẩu phế liệu làm
                    NLSX</a
                  >
                </li>
                <li class="">
                  <a
                    :href="'#tab_thutuc_7' + index1"
                    data-toggle="tab"
                    aria-expanded="false"
                    >Giấy phép xả thải</a
                  >
                </li>
                <li class="">
                  <a
                    :href="'#tab_thutuc_8' + index1"
                    data-toggle="tab"
                    aria-expanded="false"
                    >Các thủ tục khác</a
                  >
                </li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" :id="'tab_thutuc_1' + index1">
                  <div class="row block-multiple-rows">
                    <div class="col-lg-3 col-md-6">
                      <div class="form-group">
                        <input
                          type="text"
                          class="form-control"
                          v-model="
                            co_so_san_xuat.intermediate_data.thutuchanhchinhs
                              .dtm.so_quyet_dinh
                          "
                          placeholder="Số QĐ phê duyệt"
                        />
                        <span style="color: red">{{
                          intermediate_data.thutuchanhchinhs.dtm
                            .error_so_quyet_dinh
                        }}</span>
                      </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                      <date-picker
                        v-model="
                          co_so_san_xuat.intermediate_data.thutuchanhchinhs.dtm
                            .thoi_gian_phe_duyet
                        "
                        tabindex=""
                        placeholder="Ngày ra quyết định phê duyệt ĐTM"
                        :config="optionsDate"
                      >
                      </date-picker>
                    </div>
                    <div class="col-lg-6 col-md-10">
                      <div class="form-group">
                        <multiselect
                          v-model="
                            co_so_san_xuat.intermediate_data.thutuchanhchinhs
                              .dtm.co_quan_phe_duyet
                          "
                          label="ten"
                          track-by="id"
                          placeholder="Gõ tên cơ quan phê duyệt"
                          selectedLabel="Đã chọn"
                          open-direction="bottom"
                          :options="coquantochucs"
                          :multiple="false"
                          :searchable="true"
                          :clear-on-select="true"
                          :close-on-select="true"
                          :show-no-results="false"
                          :hide-selected="false"
                          :tabindex="1"
                        >
                          <span slot="noResult">Không tìm thấy kết quả</span>
                        </multiselect>
                      </div>
                    </div>
                    <div class="col-lg-1 col-md-2">
                      <button
                        type="button"
                        class="btn btn-flat pull-right"
                        tabindex="22"
                        @click="addDtm(co_so_san_xuat)"
                      >
                        <i class="fa fa-plus"></i> Thêm
                      </button>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <table class="table table-hover table-responsive">
                        <thead>
                          <tr class="row-table-header">
                            <th>Số QĐ phê duyệt ĐTM</th>
                            <th>Cơ quan phê duyệt ĐTM</th>
                            <th>Ngày ra quyết định phê duyệt ĐTM</th>
                            <th style="width: 5%; text-align: center">Xóa</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(item, index) in co_so_san_xuat
                              .thutuchanhchinhs.dtm"
                          >
                            <td>{{ item.so_quyet_dinh }}</td>
                            <td>
                              {{
                                item.co_quan_quyet_dinh
                                  ? item.co_quan_quyet_dinh.ten
                                  : null
                              }}
                            </td>
                            <td>{{ item.thoi_gian_phe_duyet }}</td>
                            <td align="center">
                              <a @click="deleteDtm(co_so_san_xuat, index)">
                                <i class="fa fa-trash-o"></i>
                              </a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" :id="'tab_thutuc_2' + index1">
                  <div class="row block-multiple-rows">
                    <div class="col-lg-12" style="margin-top: 5px">
                      <p class="text-muted">
                        Nhập thông tin số quyết định phê duyệt, cơ quan phê
                        duyệt, ngày ra quyết định phê duyệt và nhấn nút thêm để
                        thêm kết quả thanh tra ĐABVMT
                      </p>
                      <hr style="margin-top: 7px; margin-bottom: 7px" />
                    </div>
                    <div class="col-lg-3 col-md-6">
                      <div class="form-group">
                        <input
                          type="text"
                          class="form-control"
                          v-model="
                            co_so_san_xuat.intermediate_data.thutuchanhchinhs
                              .dabvmt.so_quyet_dinh
                          "
                          placeholder="Số QĐ phê duyệt"
                        />
                        <span style="color: red">{{
                          co_so_san_xuat.intermediate_data.thutuchanhchinhs
                            .dabvmt.error_so_quyet_dinh
                        }}</span>
                      </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                      <date-picker
                        v-model="
                          co_so_san_xuat.intermediate_data.thutuchanhchinhs
                            .dabvmt.thoi_gian_phe_duyet
                        "
                        placeholder="Ngày ra quyết định phê duyệt ĐABVMT"
                        :config="optionsDate"
                      >
                      </date-picker>
                    </div>
                    <div class="col-lg-6 col-md-10">
                      <div class="form-group">
                        <multiselect
                          v-model="
                            co_so_san_xuat.intermediate_data.thutuchanhchinhs
                              .dabvmt.co_quan_phe_duyet
                          "
                          label="ten"
                          track-by="id"
                          placeholder="Gõ tên cơ quan"
                          selectedLabel="Đã chọn"
                          open-direction="bottom"
                          :options="coquantochucs"
                          :multiple="false"
                          :searchable="true"
                          :clear-on-select="true"
                          :close-on-select="true"
                          :show-no-results="false"
                          :hide-selected="false"
                          :tabindex="1"
                        >
                          <span slot="noResult">Không tìm thấy kết quả</span>
                        </multiselect>
                      </div>
                    </div>
                    <div class="col-lg-1 col-md-2">
                      <button
                        type="button"
                        class="btn btn-flat pull-right"
                        @click="addDabvmt(co_so_san_xuat)"
                      >
                        <i class="fa fa-plus"></i> Thêm
                      </button>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <table class="table table-hover table-responsive">
                        <thead>
                          <tr class="row-table-header">
                            <th>Số QĐ phê duyệt ĐABVMT</th>
                            <th>Cơ quan phê duyệt ĐABVMT</th>
                            <th>Ngày ra quyết định phê duyệt ĐABVMT</th>
                            <th style="width: 5%; text-align: center">Xóa</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(item, index) in co_so_san_xuat
                              .thutuchanhchinhs.dabvmt"
                          >
                            <td>{{ item.so_quyet_dinh }}</td>
                            <td>
                              {{
                                item.co_quan_quyet_dinh
                                  ? item.co_quan_quyet_dinh.ten
                                  : null
                              }}
                            </td>
                            <td>{{ item.thoi_gian_phe_duyet }}</td>
                            <td align="center">
                              <a @click="deleteDabvmt(co_so_san_xuat, index)">
                                <i class="fa fa-trash-o"></i>
                              </a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" :id="'tab_thutuc_3' + index1">
                  <div class="row block-multiple-rows">
                    <div class="col-lg-12" style="margin-top: 5px">
                      <p class="text-muted">
                        Nhập thông tin số quyết định phê duyệt, cơ quan phê
                        duyệt, ngày ra quyết định phê duyệt và nhấn nút thêm để
                        thêm kết quả thanh tra phương án BVMT/Cam kết BVMT
                      </p>
                      <hr style="margin-top: 7px; margin-bottom: 7px" />
                    </div>
                    <div class="col-lg-3 col-md-6">
                      <div class="form-group">
                        <input
                          type="text"
                          class="form-control"
                          required
                          placeholder="Số QĐ phê duyệt"
                          v-model="
                            co_so_san_xuat.intermediate_data.thutuchanhchinhs
                              .pabvmt.so_quyet_dinh
                          "
                        />
                        <span style="color: red">{{
                          co_so_san_xuat.intermediate_data.thutuchanhchinhs
                            .pabvmt.error_so_quyet_dinh
                        }}</span>
                      </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                      <date-picker
                        v-model="
                          co_so_san_xuat.intermediate_data.thutuchanhchinhs
                            .pabvmt.thoi_gian_phe_duyet
                        "
                        tabindex=""
                        placeholder="Ngày ra quyết định phê duyệt PA/CK BVMT"
                        :config="optionsDate"
                      >
                      </date-picker>
                    </div>
                    <div class="col-lg-6 col-md-10">
                      <div class="form-group">
                        <multiselect
                          v-model="
                            co_so_san_xuat.intermediate_data.thutuchanhchinhs
                              .pabvmt.co_quan_phe_duyet
                          "
                          label="ten"
                          track-by="id"
                          placeholder="Gõ tên cơ quan"
                          selectedLabel="Đã chọn"
                          open-direction="bottom"
                          :options="coquantochucs"
                          :multiple="false"
                          :searchable="true"
                          :clear-on-select="true"
                          :close-on-select="true"
                          :show-no-results="false"
                          :hide-selected="false"
                          :tabindex="1"
                        >
                          <span slot="noResult">Không tìm thấy kết quả</span>
                        </multiselect>
                      </div>
                    </div>
                    <div class="col-lg-1 col-md-2">
                      <button
                        type="button"
                        class="btn btn-flat pull-right"
                        @click="addPabvmt(co_so_san_xuat)"
                      >
                        <i class="fa fa-plus"></i> Thêm
                      </button>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <table class="table table-hover table-responsive">
                        <thead>
                          <tr class="row-table-header">
                            <th>Số QĐ phê duyệt PA/CK BVMT</th>
                            <th>Cơ quan phê duyệt PA/CK BVMT</th>
                            <th>Ngày ra quyết định phê duyệt PA/CK BVMT</th>
                            <th style="width: 5%; text-align: center">Xóa</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(item, index) in co_so_san_xuat
                              .thutuchanhchinhs.pabvmt"
                          >
                            <td>{{ item.so_quyet_dinh }}</td>
                            <td>
                              {{
                                item.co_quan_quyet_dinh
                                  ? item.co_quan_quyet_dinh.ten
                                  : null
                              }}
                            </td>
                            <td>{{ item.thoi_gian_phe_duyet }}</td>
                            <td align="center">
                              <a @click="deletePabvmt(co_so_san_xuat, index)">
                                <i class="fa fa-trash-o"></i>
                              </a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="tab-pane" :id="'tab_thutuc_4' + index1">
                  <div class="row block-multiple-rows">
                    <div class="col-lg-12" style="margin-top: 5px">
                      <p class="text-muted">
                        Nhập thông tin số giấy xác nhận, cơ quan xác nhận, ngày
                        ra quyết định phê duyệt và nhấn nút thêm để thêm kết quả
                        thanh tra giấy xác nhận công trình BVMT
                      </p>
                      <hr style="margin-top: 7px; margin-bottom: 7px" />
                    </div>
                    <div class="col-lg-3 col-md-6">
                      <div class="form-group">
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Số giấy xác nhận"
                          v-model="
                            co_so_san_xuat.intermediate_data.thutuchanhchinhs
                              .gxnctbvmt.so_quyet_dinh
                          "
                        />
                        <span style="color: red">{{
                          intermediate_data.thutuchanhchinhs.gxnctbvmt
                            .error_so_quyet_dinh
                        }}</span>
                      </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                      <date-picker
                        v-model="
                          co_so_san_xuat.intermediate_data.thutuchanhchinhs
                            .gxnctbvmt.thoi_gian_phe_duyet
                        "
                        tabindex=""
                        placeholder="Ngày ra quyết định phê duyệt CTBVMT"
                        :config="optionsDate"
                      >
                      </date-picker>
                    </div>
                    <div class="col-lg-6 col-md-10">
                      <div class="form-group">
                        <multiselect
                          v-model="
                            co_so_san_xuat.intermediate_data.thutuchanhchinhs
                              .gxnctbvmt.co_quan_phe_duyet
                          "
                          label="ten"
                          track-by="id"
                          placeholder="Gõ tên cơ quan xác nhận"
                          selectedLabel="Đã chọn"
                          open-direction="bottom"
                          :options="coquantochucs"
                          :multiple="false"
                          :searchable="true"
                          :clear-on-select="true"
                          :close-on-select="true"
                          :show-no-results="false"
                          :hide-selected="false"
                          :tabindex="1"
                        >
                          <span slot="noResult">Không tìm thấy kết quả</span>
                        </multiselect>
                      </div>
                    </div>

                    <div class="col-lg-1 col-md-2">
                      <button
                        type="button"
                        class="btn btn-flat pull-right"
                        @click="addGxnctbvmt(co_so_san_xuat)"
                      >
                        <i class="fa fa-plus"></i> Thêm
                      </button>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <table class="table table-hover table-responsive">
                        <thead>
                          <tr class="row-table-header">
                            <th>Số QĐ phê duyệt CTBVMT</th>
                            <th>Cơ quan phê duyệt CTBVMT</th>
                            <th>Ngày ra quyết định phê duyệt CTBVMT</th>
                            <th style="width: 5%; text-align: center">Xóa</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(item, index) in co_so_san_xuat
                              .thutuchanhchinhs.gxnctbvmt"
                          >
                            <td>{{ item.so_quyet_dinh }}</td>
                            <td>
                              {{
                                item.co_quan_quyet_dinh
                                  ? item.co_quan_quyet_dinh.ten
                                  : null
                              }}
                            </td>
                            <td>{{ item.thoi_gian_phe_duyet }}</td>
                            <td>
                              <a
                                @click="deleteGxnctbvmt(co_so_san_xuat, index)"
                              >
                                <i class="fa fa-trash-o"></i>
                              </a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="tab-pane" :id="'tab_thutuc_5' + index1">
                  <div class="row block-multiple-rows">
                    <div class="col-lg-3 col-md-6">
                      <div class="form-group">
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Số sổ đăng ký"
                          v-model="
                            co_so_san_xuat.intermediate_data.thutuchanhchinhs
                              .sdkcntctnh.so_quyet_dinh
                          "
                        />
                        <span style="color: red">{{
                          co_so_san_xuat.intermediate_data.thutuchanhchinhs
                            .sdkcntctnh.error_so_quyet_dinh
                        }}</span>
                      </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                      <date-picker
                        v-model="
                          co_so_san_xuat.intermediate_data.thutuchanhchinhs
                            .sdkcntctnh.thoi_gian_phe_duyet
                        "
                        tabindex=""
                        placeholder="Ngày ra quyết định phê duyệt CNTCTNH"
                        :config="optionsDate"
                      >
                      </date-picker>
                    </div>
                    <div class="col-lg-6 col-md-10">
                      <div class="form-group">
                        <multiselect
                          v-model="
                            co_so_san_xuat.intermediate_data.thutuchanhchinhs
                              .sdkcntctnh.co_quan_phe_duyet
                          "
                          label="ten"
                          track-by="id"
                          placeholder="Gõ tên cơ quan cấp"
                          selectedLabel="Đã chọn"
                          open-direction="bottom"
                          :options="coquantochucs"
                          :multiple="false"
                          :searchable="true"
                          :clear-on-select="true"
                          :close-on-select="true"
                          :show-no-results="false"
                          :hide-selected="false"
                          :tabindex="1"
                        >
                          <span slot="noResult">Không tìm thấy kết quả</span>
                        </multiselect>
                      </div>
                    </div>

                    <div class="col-lg-1 col-md-2">
                      <button
                        type="button"
                        class="btn btn-flat pull-right"
                        @click="addSdkcntctnh(co_so_san_xuat)"
                      >
                        <i class="fa fa-plus"></i> Thêm
                      </button>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <table class="table table-hover table-responsive">
                        <thead>
                          <tr class="row-table-header">
                            <th>Số QĐ phê duyệt CNTCTNH</th>
                            <th>Cơ quan phê duyệt CNTCTNH</th>
                            <th>Ngày ra quyết định phê duyệt CNTCTNH</th>
                            <th style="width: 5%; text-align: center">Xóa</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(item, index) in co_so_san_xuat
                              .thutuchanhchinhs.sdkcntctnh"
                          >
                            <td>{{ item.so_quyet_dinh }}</td>
                            <td>
                              {{
                                item.co_quan_quyet_dinh
                                  ? item.co_quan_quyet_dinh.ten
                                  : null
                              }}
                            </td>
                            <td>{{ item.thoi_gian_phe_duyet }}</td>
                            <td align="center">
                              <a
                                @click="deleteSdkcntctnh(co_so_san_xuat, index)"
                              >
                                <i class="fa fa-trash-o"></i>
                              </a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="tab-pane" :id="'tab_thutuc_6' + index1">
                  <div class="row block-multiple-rows">
                    <div class="col-lg-12" style="margin-top: 5px">
                      <p class="text-muted">
                        Nhập thông tin số giấy xác nhận, cơ quan xác nhận, ngày
                        ra quyết định phê duyệt và nhấn nút thêm để thêm kết quả
                        thanh tra giấy xác nhận đủ điều kiện BVMT
                      </p>
                      <hr style="margin-top: 7px; margin-bottom: 7px" />
                    </div>
                    <div class="col-lg-3 col-md-6">
                      <div class="form-group">
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Số giấy xác nhận"
                          v-model="
                            co_so_san_xuat.intermediate_data.thutuchanhchinhs
                              .gxnddkbvmtnkpl.so_quyet_dinh
                          "
                        />
                        <span style="color: red">{{
                          co_so_san_xuat.intermediate_data.thutuchanhchinhs
                            .gxnddkbvmtnkpl.error_so_quyet_dinh
                        }}</span>
                      </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                      <date-picker
                        v-model="
                          co_so_san_xuat.intermediate_data.thutuchanhchinhs
                            .gxnddkbvmtnkpl.thoi_gian_phe_duyet
                        "
                        tabindex=""
                        placeholder="Ngày ra quyết định phê duyệt NKPL"
                        :config="optionsDate"
                      >
                      </date-picker>
                    </div>
                    <div class="col-lg-6 col-md-10">
                      <div class="form-group">
                        <multiselect
                          v-model="
                            co_so_san_xuat.intermediate_data.thutuchanhchinhs
                              .gxnddkbvmtnkpl.co_quan_phe_duyet
                          "
                          label="ten"
                          track-by="id"
                          placeholder="Gõ tên cơ xác nhận"
                          selectedLabel="Đã chọn"
                          open-direction="bottom"
                          :options="coquantochucs"
                          :multiple="false"
                          :searchable="true"
                          :clear-on-select="true"
                          :close-on-select="true"
                          :show-no-results="false"
                          :hide-selected="false"
                          :tabindex="1"
                        >
                          <span slot="noResult">Không tìm thấy kết quả</span>
                        </multiselect>
                      </div>
                    </div>

                    <div class="col-lg-1 col-md-2">
                      <button
                        type="button"
                        class="btn btn-flat pull-right"
                        @click="addGxnddkbvmtnkpl(co_so_san_xuat)"
                      >
                        <i class="fa fa-plus"></i> Thêm
                      </button>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <table class="table table-hover table-responsive">
                        <thead>
                          <tr class="row-table-header">
                            <th>Số QĐ phê duyệt NKPL</th>
                            <th>Cơ quan phê duyệt NKPL</th>
                            <th>Ngày ra quyết định phê duyệt NKPL</th>
                            <th style="width: 5%; text-align: center">Xóa</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(item, index) in co_so_san_xuat
                              .thutuchanhchinhs.gxnddkbvmtnkpl"
                          >
                            <td>{{ item.so_quyet_dinh }}</td>
                            <td>
                              {{
                                item.co_quan_quyet_dinh
                                  ? item.co_quan_quyet_dinh.ten
                                  : null
                              }}
                            </td>
                            <td>{{ item.thoi_gian_phe_duyet }}</td>
                            <td align="center">
                              <a
                                @click="
                                  deleteGxnddkbvmtnkpl(co_so_san_xuat, index)
                                "
                              >
                                <i class="fa fa-trash-o"></i>
                              </a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="tab-pane" :id="'tab_thutuc_7' + index1">
                  <div class="row block-multiple-rows">
                    <div class="col-lg-12" style="margin-top: 5px">
                      <p class="text-muted">
                        Nhập thông tin số giấy xác nhận, cơ quan xác nhận, ngày
                        ra quyết định phê duyệt và nhấn nút thêm để thêm kết quả
                        thanh tra giấy phép xả thải
                      </p>
                      <hr style="margin-top: 7px; margin-bottom: 7px" />
                    </div>
                    <div class="col-lg-3 col-md-6">
                      <div class="form-group">
                        <input
                          type="text"
                          placeholder="Số giấy xác nhận"
                          class="form-control"
                          v-model="
                            co_so_san_xuat.intermediate_data.thutuchanhchinhs
                              .gpxt.so_quyet_dinh
                          "
                        />
                        <span style="color: red">{{
                          co_so_san_xuat.intermediate_data.thutuchanhchinhs.gpxt
                            .error_so_quyet_dinh
                        }}</span>
                      </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                      <date-picker
                        v-model="
                          co_so_san_xuat.intermediate_data.thutuchanhchinhs.gpxt
                            .thoi_gian_phe_duyet
                        "
                        tabindex=""
                        placeholder="Ngày ra quyết định phê duyệt GPXT"
                        :config="optionsDate"
                      >
                      </date-picker>
                    </div>
                    <div class="col-lg-6 col-md-10">
                      <div class="form-group">
                        <multiselect
                          v-model="
                            co_so_san_xuat.intermediate_data.thutuchanhchinhs
                              .gpxt.co_quan_phe_duyet
                          "
                          label="ten"
                          track-by="id"
                          placeholder="Gõ tên cơ xác nhận"
                          selectedLabel="Đã chọn"
                          open-direction="bottom"
                          :options="coquantochucs"
                          :multiple="false"
                          :searchable="true"
                          :clear-on-select="true"
                          :close-on-select="true"
                          :show-no-results="false"
                          :hide-selected="false"
                          :tabindex="1"
                        >
                          <span slot="noResult">Không tìm thấy kết quả</span>
                        </multiselect>
                      </div>
                    </div>
                    <div class="col-lg-1 col-md-2">
                      <button
                        type="button"
                        class="btn bg-flat btn-flat pull-right"
                        @click="addGpxt(co_so_san_xuat)"
                      >
                        <i class="fa fa-plus"></i> Thêm
                      </button>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <table class="table table-hover table-responsive">
                        <thead>
                          <tr class="row-table-header">
                            <th>Số QĐ phê duyệt GPXT</th>
                            <th>Cơ quan phê duyệt GPXT</th>
                            <th>Ngày ra quyết định phê duyệt GPXT</th>
                            <th style="width: 5%; text-align: center">Xóa</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(item, index) in co_so_san_xuat
                              .thutuchanhchinhs.gpxt"
                          >
                            <td>{{ item.so_quyet_dinh }}</td>
                            <td>
                              {{
                                item.co_quan_quyet_dinh
                                  ? item.co_quan_quyet_dinh.ten
                                  : null
                              }}
                            </td>
                            <td>{{ item.thoi_gian_phe_duyet }}</td>
                            <td align="center">
                              <a @click="deleteGpxt(co_so_san_xuat, index)">
                                <i class="fa fa-trash-o"></i>
                              </a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="tab-pane" :id="'tab_thutuc_8' + index1">
                  <div class="row block-multiple-rows">
                    <div class="col-lg-12" style="margin-top: 5px">
                      <p class="text-muted">
                        Nhập thông tin số giấy xác nhận, cơ quan xác nhận, ngày
                        ra quyết định phê duyệt và nhấn nút thêm để thêm kết quả
                        thanh tra các thủ tục khác
                      </p>
                      <hr style="margin-top: 7px; margin-bottom: 7px" />
                    </div>
                    <div class="col-lg-3 col-md-6">
                      <div class="form-group">
                        <input
                          type="text"
                          placeholder="Số giấy xác nhận"
                          class="form-control"
                          v-model="
                            co_so_san_xuat.intermediate_data.thutuchanhchinhs
                              .ttk.so_quyet_dinh
                          "
                        />
                        <span style="color: red">{{
                          co_so_san_xuat.intermediate_data.thutuchanhchinhs.ttk
                            .error_so_quyet_dinh
                        }}</span>
                      </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                      <date-picker
                        v-model="
                          co_so_san_xuat.intermediate_data.thutuchanhchinhs.ttk
                            .thoi_gian_phe_duyet
                        "
                        tabindex=""
                        placeholder="Ngày xác nhận"
                        :config="optionsDate"
                      ></date-picker>
                    </div>
                    <div class="col-lg-6 col-md-10">
                      <div class="form-group">
                        <multiselect
                          v-model="
                            co_so_san_xuat.intermediate_data.thutuchanhchinhs
                              .ttk.co_quan_phe_duyet
                          "
                          label="ten"
                          track-by="id"
                          placeholder="Gõ tên cơ phê duyệt"
                          selectedLabel="Đã chọn"
                          open-direction="bottom"
                          :options="coquantochucs"
                          :multiple="false"
                          :searchable="true"
                          :clear-on-select="true"
                          :close-on-select="true"
                          :show-no-results="false"
                          :hide-selected="false"
                          :tabindex="1"
                        >
                          <span slot="noResult">Không tìm thấy kết quả</span>
                        </multiselect>
                      </div>
                    </div>

                    <div class="col-lg-1 col-md-2">
                      <a
                        type="button"
                        class="btn bg-flat btn-flat pull-right"
                        @click="addTtk(co_so_san_xuat)"
                      >
                        <i class="fa fa-plus"></i> Thêm
                      </a>
                    </div>
                    <div class="col-lg-6 col-md-10">
                      <label class="control-label">Ghi chú</label>
                      <textarea
                        class="form-control"
                        v-model="
                          co_so_san_xuat.intermediate_data.thutuchanhchinhs.ttk
                            .ghi_chu
                        "
                        rows="3"
                      ></textarea>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <table class="table table-hover table-responsive">
                        <thead>
                          <tr class="row-table-header">
                            <th>Số QĐ phê duyệt</th>
                            <th>Cơ quan phê duyệt</th>
                            <th>Thời gian phê duyệt</th>
                            <th>Ghi chú</th>
                            <th style="width: 5%; text-align: center">Xóa</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(item, index) in co_so_san_xuat
                              .thutuchanhchinhs.ttk"
                          >
                            <td>{{ item.so_quyet_dinh }}</td>
                            <td>
                              {{
                                item.co_quan_quyet_dinh
                                  ? item.co_quan_quyet_dinh.ten
                                  : null
                              }}
                            </td>
                            <td>{{ item.thoi_gian_phe_duyet }}</td>
                            <td>{{ item.ghi_chu }}</td>
                            <td align="center">
                              <a @click="deleteTtk(co_so_san_xuat, index)">
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
            </div>
          </div>
        </div>
      </div>

      <div class="row block-multiple-rows">
        <div class="col-lg-12" style="margin-top: 5px">
          <label>KẾT LUẬN SAU THANH TRA</label>
          <p class="text-muted">
            (Click vào từng mục kết luận thanh tra, quyết định xử phạt và khắc
            phục vi phạm để xem và nhập thông tin kết luận thanh tra)
          </p>
          <hr style="margin-top: 7px; margin-bottom: 7px" />
        </div>
        <div class="col-md-12">
          <div class="nav-tabs-custom margin-top">
            <ul class="nav nav-tabs">
              <li class="active">
                <a
                  href="#tab_ketluansauthanhtra"
                  data-toggle="tab"
                  aria-expanded="true"
                  >Kết luận thanh tra</a
                >
              </li>
              <li>
                <a
                  href="#tab_quyetdinhxuphat"
                  data-toggle="tab"
                  aria-expanded="false"
                  >Quyết định xử phạt</a
                >
              </li>
              <li>
                <a
                  href="#tab_ketquakhacphuchauquavipham"
                  data-toggle="tab"
                  aria-expanded="false"
                  >Kết quả khắc phục hậu quả vi phạm</a
                >
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_ketluansauthanhtra">
                <NhomHanhViViPham
                  :usersystem="usersystem"
                  :coSoSanXuats="form_data.co_so_san_xuats"
                  @checkValid="
                    (value) => {
                      is_valid = value;
                    }
                  "
                  :inputData.sync="this.form_data.nhom_hanh_vi_vi_phams"
                  :donviThongSoVuotChuan="donviThongSoVuotChuan"
                >
                </NhomHanhViViPham>
              </div>
              <div class="tab-pane" id="tab_quyetdinhxuphat">
                <QuyetDinhXuPhat
                  :usersystem="usersystem"
                  :inputData.sync="this.form_data.danh_sach_quyet_dinh_xu_phat"
                >
                </QuyetDinhXuPhat>
              </div>
              <div class="tab-pane" id="tab_ketquakhacphuchauquavipham">
                <KetQuaKhacPhucHauQuaViPham
                  :usersystem="usersystem"
                  :inputData.sync="
                    this.form_data.danh_sach_ket_qua_khac_phuc_vi_pham
                  "
                >
                </KetQuaKhacPhucHauQuaViPham>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row block-multiple-rows">
        <div class="col-lg-12 col-md-12">
          <label>Những nội dung đã thực hiện</label>
          <textarea
            v-model="form_data.mo_ta"
            type="text"
            class="form-control"
            placeholder="Nhập nội dung đã thực hiện"
          ></textarea>
        </div>
        <div class="col-lg-12 col-md-12">
          <label>Những nội dung chưa thực hiện</label>
          <textarea
            v-model="form_data.noi_dung_chua_thuc_hien"
            type="text"
            class="form-control"
            placeholder="Nhập nội dung chưa thực hiện"
          ></textarea>
        </div>
      </div>
      <div class="row block-multiple-rows">
        <div class="col-lg-12" style="margin-top: 5px">
          <label>TÀI LIỆU ĐÍNH KÈM</label>
          <hr style="margin-top: 7px; margin-bottom: 7px" />
          <index-file-kqtt
            :usersystem="usersystem"
            @files="getFile($event)"
          ></index-file-kqtt>
        </div>
      </div>
      <div class="box-footer box-footer-form">
        <button
          type="submit"
          id="onsubmit"
          class="btn bg-olive btn-flat pull-right"
          :disabled="is_loading || !is_valid"
          tabindex="22"
          @click="addKetQuaThanhTra()"
        >
          <i class="fa fa-plus"></i> Thêm mới
        </button>
        <a
          href="/cososanxuat"
          id="btnback"
          class="btn btn-default btn-flat"
          tabindex="23"
        >
          <i class="fa fa-undo"></i> Trở lại
        </a>
      </div>
    </div>
  </div>
</template>

<script>
import diaDiemHoatDong from "./diaDiemHoatDong.vue";

import * as env from "../env.js";
import moment from "../../../../node_modules/admin-lte/bower_components/moment/moment.js";
import datePicker from "vue-bootstrap-datetimepicker";
import Multiselect from "vue-multiselect";
import MessageService from "../services/MessageService";
import { en, vi } from "vuejs-datepicker/dist/locale";
import KetQuaKhacPhucHauQuaViPham from "./ketQuaKhacPhucHauQuaViPham.vue";
import QuyetDinhXuPhat from "./quyetdinhxuphat/quyetDinhXuPhat.vue";
import NhomHanhViViPham from "./indexNhomHanhViViPham.vue";

Vue.filter("formatDate", function (value) {
  if (value) {
    return moment(String(value)).format("DD/MM/YYYY");
  }
});
export default {
  components: {
    datePicker,
    diaDiemHoatDong,
    Multiselect,
    KetQuaKhacPhucHauQuaViPham,
    QuyetDinhXuPhat,
    NhomHanhViViPham,
  },
  props: ["usersystem"],
  data: function () {
    return {
      currentTab: 0,
      optionsDate: {
        format: "DD/MM/YYYY",
        useCurrent: false,
        locale: "vi",
      },
      showEditChuDauTu: false,
      chuDauTus: [],
      chuDauTu: null,
      errors: {
        dientich: null,
        don_vi_dien_tich: null,
        tencs: null,
        loaics: null,
        don_vi_cstk: null,
        congsuattk: null,
        don_vi_cshd: null,
        congsuathd: null,
        tinh: null,
        diachi: null,
      },
      money: {
        decimal: ".",
        thousands: ",",
        prefix: "",
        suffix: "",
        precision: 0,
        masked: false,
      },
      donvikhoiluongphatsinhs: [],
      donvidientich: [],
      donviThongSoVuotChuan: [],
      donvinuoc: [],
      donvicongsuathoatdongs: [],
      donvicongsuatthietkes: [],
      khucongnghieps: [],
      loai_hinh_co_so_tree_listing: [],
      huyens: [],
      tinhs: [],
      coquantochucs: [],
      quyetdinhthanhtras: [],
      don_vi_ctrsh: null,
      don_vi_ctrcntt: null,
      don_vi_dt: null,
      don_vi_nc: null,
      ten_don_vi_ctrsh: null,
      ten_don_vi_ctrcntt: null,
      is_loading: false,
      is_loading_cdt: false,
      is_valid: true,
      error_quyet_dinh: null,
      error_so_quyet_dinh: null,
      toado: { kinh_do: "105.8693287", vi_do: "20.9999385" },
      change: true,
      en: en,
      vi: vi,
      orgnaization: null,
      orgnaizations: [],
      form_data: {
        quyet_dinh_thanh_tra: null,
        so_quyet_dinh: null,
        ngay_ket_luan: null,
        mo_ta: null,
        noi_dung_chua_thuc_hien: null,
        co_so_san_xuats: [],
        co_so_san_xuat: {
          id: null,
          ten: null,
          khu_cong_nghiep: null,
          xa_phuong: null,
          quan_huyen: null,
          tinh_thanh: null,
          kinh_do: null,
          vi_do: null,
          dia_chi_hoat_dong: null,
          dia_chi_lien_he: null,
          so_giay_chung_nhan_kinh_doanh: null,
          ngay_cap_giay_chung_nhan_kinh_doanh: null,
          co_quan_cap_giay_chung_nhan_kinh_doanh: null,
          so_giay_chung_nhan_dau_tu: null,
          ngay_cap_giay_chung_nhan_dau_tu: null,
          co_quan_cap_giay_chung_nhan_dau_tu: null,
          dien_tich: 0,
          don_vi_dien_tich: null,
          luong_nuoc_su_dung: null,
          don_vi_nuoc_su_dung: null,
          so_nguoi_lao_dong: 0,
          nguyen_lieu_chinh_su_dung: null,
          nhien_lieu_chinh_su_dung: null,
          hoa_chat_su_dung: null,
          nguon_nuoc_su_dung: null,
          cong_suat_hoat_dongs: [],
          chatthais: {
            nuocthai: [],
            khithai: [],
            ctrsh: [],
            ctrcntt: [],
            ctnh: [],
          },
          thutuchanhchinhs: {
            dtm: [],
            dabvmt: [],
            pabvmt: [],
            gxnctbvmt: [],
            sdkcntctnh: [],
            gxnddkbvmtnkpl: [],
            gpxt: [],
            ttk: [],
          },
          attachments: [],
        },
        nhom_hanh_vi_vi_phams: [],
        danh_sach_quyet_dinh_xu_phat: [],
        danh_sach_ket_qua_khac_phuc_vi_pham: [],
      },
      intermediate_data: {
        change: true,
        cong_suat_hoat_dong_new_item: {
          loai_hinh_co_so: null,
          don_vi_cong_suat_hoat_dong: null,
          don_vi_cong_suat_thiet_ke: null,
          cong_suat_hoat_dong: 0,
          cong_suat_thiet_ke: 0,
        },
        chatthais: {
          nuocthai: {
            luu_luong_nt: 0,
            cong_suat_xl_nt: 0,
            thanh_phan_nt: null,
            thong_so_vuot_nt: null,
            nguon_tiep_nhan_nt: null,
          },
          khithai: {
            luu_luong_kt: 0,
            cong_suat_xl_kt: 0,
            thanh_phan_kt: null,
            thong_so_vuot_kt: null,
            nguon_tiep_nhan_kt: null,
            quy_trinh_xu_ly: null,
          },
          ctrsh: {
            khoi_luong_phat_sinh_ctrsh: 0,
            don_vi_ctrsh: null,
            thanh_phan_chinh_ctrsh: null,
            tu_xu_ly_ctrsh: false,
            thue_xu_ly_ctrsh: false,
            co_quan_thue_xu_ly_ctrsh: null,
          },
          ctrcntt: {
            khoi_luong_phat_sinh_ctrcntt: 0,
            don_vi_ctrcntt: null,
            thanh_phan_chinh_ctrcntt: null,
            tu_xu_ly_ctrcntt: false,
            thue_xu_ly_ctrcntt: false,
            co_quan_thue_xu_ly_ctrcntt: null,
          },
          ctnh: {
            khoi_luong_phat_sinh_thuc_te_ctnh: 0,
            don_vi_ctnh: null,
            khoi_luong_phat_sinh_theo_dk_ctnh: 0,
            don_vi_ctnhdk: null,
            thanh_phan_chinh_ctnh: null,
            tu_xu_ly_ctnh: false,
            thue_xu_ly_ctnh: false,
            co_quan_thue_xu_ly_ctnh: null,
          },
        },
        thutuchanhchinhs: {
          dtm: {
            so_quyet_dinh: null,
            co_quan_phe_duyet: null,
            co_quan_phe_duyet_ten: null,
            thoi_gian_phe_duyet: null,
            error_so_quyet_dinh: null,
            error_co_quan_phe_duyet: null,
          },
          dabvmt: {
            so_quyet_dinh: null,
            co_quan_phe_duyet: null,
            co_quan_phe_duyet_ten: null,
            thoi_gian_phe_duyet: null,
            error_so_quyet_dinh: null,
            error_co_quan_phe_duyet: null,
          },
          pabvmt: {
            so_quyet_dinh: null,
            co_quan_phe_duyet: null,
            co_quan_phe_duyet_ten: null,
            thoi_gian_phe_duyet: null,
            error_so_quyet_dinh: null,
            error_co_quan_phe_duyet: null,
          },
          gxnctbvmt: {
            so_quyet_dinh: null,
            co_quan_phe_duyet: null,
            co_quan_phe_duyet_ten: null,
            thoi_gian_phe_duyet: null,
            error_so_quyet_dinh: null,
            error_co_quan_phe_duyet: null,
          },
          sdkcntctnh: {
            so_quyet_dinh: null,
            co_quan_phe_duyet: null,
            co_quan_phe_duyet_ten: null,
            thoi_gian_phe_duyet: null,
            error_so_quyet_dinh: null,
            error_co_quan_phe_duyet: null,
          },
          gxnddkbvmtnkpl: {
            so_quyet_dinh: null,
            co_quan_phe_duyet: null,
            co_quan_phe_duyet_ten: null,
            thoi_gian_phe_duyet: null,
            error_so_quyet_dinh: null,
            error_co_quan_phe_duyet: null,
          },
          gpxt: {
            so_quyet_dinh: null,
            co_quan_phe_duyet: null,
            co_quan_phe_duyet_ten: null,
            thoi_gian_phe_duyet: null,
            error_so_quyet_dinh: null,
            error_co_quan_phe_duyet: null,
          },
          ttk: {
            so_quyet_dinh: null,
            co_quan_phe_duyet: null,
            co_quan_phe_duyet_ten: null,
            thoi_gian_phe_duyet: null,
            error_so_quyet_dinh: null,
            error_co_quan_phe_duyet: null,
          },
        },
      },
    };
  },
  watch: {
    orgnaization: function (val) {
      this.chuDauTu = val.chu_dau_tu ? val.chu_dau_tu : null;
    },
  },
  mounted: function () {
    this.refresh();
    axios.get(env.endpointhttp + "danhmuc/chuyendoidonvis").then((response) => {
      this.donvikhoiluongphatsinhs =
        response.data.result.don_vi.khoi_luong_phat_sinh_chat_thai;
      this.donviThongSoVuotChuan =
        response.data.result.don_vi.thong_so_vuot_chuan;
      this.don_vi_ctrsh = response.data.result.don_vi_goc
        .khoi_luong_phat_sinh_chat_thai
        ? response.data.result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.id
        : null;
      this.don_vi_ctrcntt = response.data.result.don_vi_goc
        .khoi_luong_phat_sinh_chat_thai
        ? response.data.result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.id
        : null;
      this.don_vi_ctnh = response.data.result.don_vi_goc
        .khoi_luong_phat_sinh_chat_thai
        ? response.data.result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.id
        : null;
      this.don_vi_dt = response.data.result.don_vi_goc.dien_tich
        ? response.data.result.don_vi_goc.dien_tich.id
        : null;
      this.don_vi_nc = response.data.result.don_vi_goc.luu_luong_nuoc_thai
        ? response.data.result.don_vi_goc.luu_luong_nuoc_thai
        : null;
      this.ten_don_vi_ctnhdk = response.data.result.don_vi_goc
        .khoi_luong_phat_sinh_chat_thai
        ? response.data.result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.id
        : null;
      this.ten_don_vi_ctrsh = response.data.result.don_vi_goc
        .khoi_luong_phat_sinh_chat_thai
        ? response.data.result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.ten
        : null;
    });
  },
  methods: {
    goDanhSachCDT() {
      window.open("/chudautu", "_blank");
    },
    goAddChuDauTu() {
      window.open("/chudautu/add/", "_blank");
    },
    changeChuDauTu() {
      this.showEditChuDauTu = true;
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
    getFile(data) {
      this.form_data.attachments = data;
    },
    goChuDauTu(id) {
      window.open("/chudautu/edit/" + id, "_blank");
    },
    refreshDVCTRSH: function () {
      axios
        .get(env.endpointhttp + "danhmuc/chuyendoidonvis")
        .then((response) => {
          this.donvikhoiluongphatsinhs =
            response.data.result.don_vi.khoi_luong_phat_sinh_chat_thai;
          this.don_vi_ctrsh = response.data.result.don_vi_goc
            .khoi_luong_phat_sinh_chat_thai
            ? response.data.result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.id
            : null;
          this.ten_don_vi_ctrsh = response.data.result.don_vi_goc
            .khoi_luong_phat_sinh_chat_thai
            ? response.data.result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.ten
            : null;
        });
    },
    refreshDVCTRCNTT: function () {
      axios
        .get(env.endpointhttp + "danhmuc/chuyendoidonvis")
        .then((response) => {
          this.donvikhoiluongphatsinhs =
            response.data.result.don_vi.khoi_luong_phat_sinh_chat_thai;
          this.don_vi_ctrcntt = response.data.result.don_vi_goc
            .khoi_luong_phat_sinh_chat_thai
            ? response.data.result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.id
            : null;
          this.ten_don_vi_ctrcntt = response.data.result.don_vi_goc
            .khoi_luong_phat_sinh_chat_thai
            ? response.data.result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.ten
            : null;
        });
    },
    refreshDVCTNH: function () {
      axios
        .get(env.endpointhttp + "danhmuc/chuyendoidonvis")
        .then((response) => {
          this.donvikhoiluongphatsinhs =
            response.data.result.don_vi.khoi_luong_phat_sinh_chat_thai;
          this.don_vi_ctnh = response.data.result.don_vi_goc
            .khoi_luong_phat_sinh_chat_thai
            ? response.data.result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.id
            : null;
          this.ten_don_vi_ctnhdk = response.data.result.don_vi_goc
            .khoi_luong_phat_sinh_chat_thai
            ? response.data.result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.id
            : null;
          this.ten_don_vi_ctnh = response.data.result.don_vi_goc
            .khoi_luong_phat_sinh_chat_thai
            ? response.data.result.don_vi_goc.khoi_luong_phat_sinh_chat_thai.ten
            : null;
        });
    },
    addDtm: function (co_so_san_xuat) {
      if (
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.dtm.so_quyet_dinh ==
          "" ||
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.dtm.so_quyet_dinh ==
          null
      ) {
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.dtm.error_so_quyet_dinh =
          "Nhập số quyết định";
      } else {
        co_so_san_xuat.thutuchanhchinhs.dtm.push({
          so_quyet_dinh:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.dtm.so_quyet_dinh,
          co_quan_quyet_dinh:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.dtm
              .co_quan_phe_duyet,
          thoi_gian_phe_duyet:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.dtm
              .thoi_gian_phe_duyet,
        }),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.dtm.so_quyet_dinh =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.dtm.co_quan_phe_duyet =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.dtm.error_so_quyet_dinh =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.dtm.error_co_quan_phe_duyet =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.dtm.thoi_gian_phe_duyet =
            null);
      }
    },
    deleteDtm: function (co_so_san_xuat, index) {
      co_so_san_xuat.thutuchanhchinhs.dtm.splice(index, 1);
    },
    addDabvmt: function (co_so_san_xuat) {
      if (
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.dabvmt
          .so_quyet_dinh == "" ||
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.dabvmt
          .so_quyet_dinh == null
      ) {
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.dabvmt.error_so_quyet_dinh =
          "Nhập số quyết định";
      } else {
        co_so_san_xuat.thutuchanhchinhs.dabvmt.push({
          so_quyet_dinh:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.dabvmt
              .so_quyet_dinh,
          co_quan_quyet_dinh:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.dabvmt
              .co_quan_phe_duyet,
          thoi_gian_phe_duyet:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.dabvmt
              .thoi_gian_phe_duyet,
        });
        (co_so_san_xuat.intermediate_data.thutuchanhchinhs.dabvmt.so_quyet_dinh =
          null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.dabvmt.co_quan_phe_duyet =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.dabvmt.error_so_quyet_dinh =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.dabvmt.error_co_quan_phe_duyet =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.dabvmt.thoi_gian_phe_duyet =
            null);
      }
    },
    deleteDabvmt: function (co_so_san_xuat, index) {
      co_so_san_xuat.thutuchanhchinhs.dabvmt.splice(index, 1);
    },
    addPabvmt: function (co_so_san_xuat) {
      if (
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.pabvmt
          .so_quyet_dinh == "" ||
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.pabvmt
          .so_quyet_dinh == null
      ) {
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.pabvmt.error_so_quyet_dinh =
          "Nhập số quyết định";
      } else {
        co_so_san_xuat.thutuchanhchinhs.pabvmt.push({
          so_quyet_dinh:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.pabvmt
              .so_quyet_dinh,
          co_quan_quyet_dinh:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.pabvmt
              .co_quan_phe_duyet,
          thoi_gian_phe_duyet:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.pabvmt
              .thoi_gian_phe_duyet,
        });
        (co_so_san_xuat.intermediate_data.thutuchanhchinhs.pabvmt.so_quyet_dinh =
          null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.pabvmt.co_quan_phe_duyet =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.pabvmt.error_so_quyet_dinh =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.pabvmt.error_co_quan_phe_duyet =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.pabvmt.thoi_gian_phe_duyet =
            null);
      }
    },
    deletePabvmt: function (co_so_san_xuat, index) {
      co_so_san_xuat.thutuchanhchinhs.pabvmt.splice(index, 1);
    },
    addGxnctbvmt: function (co_so_san_xuat) {
      if (
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnctbvmt
          .so_quyet_dinh == "" ||
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnctbvmt
          .so_quyet_dinh == null
      ) {
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnctbvmt.error_so_quyet_dinh =
          "Nhập số quyết định";
      } else {
        co_so_san_xuat.thutuchanhchinhs.gxnctbvmt.push({
          so_quyet_dinh:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnctbvmt
              .so_quyet_dinh,
          co_quan_quyet_dinh:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnctbvmt
              .co_quan_phe_duyet,
          thoi_gian_phe_duyet:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnctbvmt
              .thoi_gian_phe_duyet,
        });
        (co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnctbvmt.so_quyet_dinh =
          null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnctbvmt.co_quan_phe_duyet =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnctbvmt.error_so_quyet_dinh =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnctbvmt.error_co_quan_phe_duyet =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnctbvmt.thoi_gian_phe_duyet =
            null);
      }
    },
    deleteGxnctbvmt: function (co_so_san_xuat, index) {
      co_so_san_xuat.thutuchanhchinhs.gxnctbvmt.splice(index, 1);
    },
    addSdkcntctnh: function (co_so_san_xuat) {
      if (
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.sdkcntctnh
          .so_quyet_dinh == "" ||
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.sdkcntctnh
          .so_quyet_dinh == null
      ) {
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.sdkcntctnh.error_so_quyet_dinh =
          "Nhập số sổ đăng ký";
      } else {
        co_so_san_xuat.thutuchanhchinhs.sdkcntctnh.push({
          so_quyet_dinh:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.sdkcntctnh
              .so_quyet_dinh,
          co_quan_quyet_dinh:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.sdkcntctnh
              .co_quan_phe_duyet,
          thoi_gian_phe_duyet:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.sdkcntctnh
              .thoi_gian_phe_duyet,
        });
        (co_so_san_xuat.intermediate_data.thutuchanhchinhs.sdkcntctnh.so_quyet_dinh =
          null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.sdkcntctnh.co_quan_phe_duyet =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.sdkcntctnh.error_so_quyet_dinh =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.sdkcntctnh.error_co_quan_phe_duyet =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.sdkcntctnh.thoi_gian_phe_duyet =
            null);
      }
    },
    deleteSdkcntctnh: function (co_so_san_xuat, index) {
      co_so_san_xuat.thutuchanhchinhs.sdkcntctnh.splice(index, 1);
    },
    addGxnddkbvmtnkpl: function (co_so_san_xuat) {
      if (
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnddkbvmtnkpl
          .so_quyet_dinh == "" ||
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnddkbvmtnkpl
          .so_quyet_dinh == null
      ) {
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnddkbvmtnkpl.error_so_quyet_dinh =
          "Nhập số giấy xác nhận";
      } else {
        co_so_san_xuat.thutuchanhchinhs.gxnddkbvmtnkpl.push({
          so_quyet_dinh:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnddkbvmtnkpl
              .so_quyet_dinh,
          co_quan_quyet_dinh:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnddkbvmtnkpl
              .co_quan_phe_duyet,
          thoi_gian_phe_duyet:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnddkbvmtnkpl
              .thoi_gian_phe_duyet,
        });
        (co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnddkbvmtnkpl.so_quyet_dinh =
          null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnddkbvmtnkpl.co_quan_phe_duyet =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnddkbvmtnkpl.error_so_quyet_dinh =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnddkbvmtnkpl.error_co_quan_phe_duyet =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.gxnddkbvmtnkpl.thoi_gian_phe_duyet =
            null);
      }
    },
    deleteGxnddkbvmtnkpl: function (co_so_san_xuat, index) {
      co_so_san_xuat.thutuchanhchinhs.gxnddkbvmtnkpl.splice(index, 1);
    },
    addGpxt: function (co_so_san_xuat) {
      if (
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.gpxt.so_quyet_dinh ==
          "" ||
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.gpxt.so_quyet_dinh ==
          null
      ) {
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.gpxt.error_so_quyet_dinh =
          "Nhập số giấy xác nhận";
      } else {
        co_so_san_xuat.thutuchanhchinhs.gpxt.push({
          so_quyet_dinh:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.gpxt
              .so_quyet_dinh,
          co_quan_quyet_dinh:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.gpxt
              .co_quan_phe_duyet,
          thoi_gian_phe_duyet:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.gpxt
              .thoi_gian_phe_duyet,
        });
        (co_so_san_xuat.intermediate_data.thutuchanhchinhs.gpxt.so_quyet_dinh =
          null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.gpxt.co_quan_phe_duyet =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.gpxt.error_so_quyet_dinh =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.gpxt.error_co_quan_phe_duyet =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.gpxt.thoi_gian_phe_duyet =
            null);
      }
    },
    deleteGpxt: function (co_so_san_xuat, index) {
      co_so_san_xuat.thutuchanhchinhs.gpxt.splice(index, 1);
    },
    addTtk: function (co_so_san_xuat) {
      if (
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.ttk.so_quyet_dinh ==
          "" ||
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.ttk.so_quyet_dinh ==
          null
      ) {
        co_so_san_xuat.intermediate_data.thutuchanhchinhs.ttk.error_so_quyet_dinh =
          "Nhập số giấy xác nhận";
      } else {
        co_so_san_xuat.thutuchanhchinhs.ttk.push({
          so_quyet_dinh:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.ttk.so_quyet_dinh,
          co_quan_quyet_dinh:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.ttk
              .co_quan_phe_duyet,
          thoi_gian_phe_duyet:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.ttk
              .thoi_gian_phe_duyet,
          ghi_chu:
            co_so_san_xuat.intermediate_data.thutuchanhchinhs.ttk.ghi_chu,
        });
        (co_so_san_xuat.intermediate_data.thutuchanhchinhs.ttk.so_quyet_dinh =
          null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.ttk.co_quan_phe_duyet =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.ttk.error_so_quyet_dinh =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.ttk.error_co_quan_phe_duyet =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.ttk.ghi_chu =
            null),
          (co_so_san_xuat.intermediate_data.thutuchanhchinhs.ttk.thoi_gian_phe_duyet =
            null);
      }
    },
    deleteTtk: function (co_so_san_xuat, index) {
      co_so_san_xuat.thutuchanhchinhs.ttk.splice(index, 1);
    },
    addNuocthai: function (co_so_san_xuat) {
      co_so_san_xuat.chatthais.nuocthai.push({
        luu_luong:
          co_so_san_xuat.intermediate_data.chatthais.nuocthai.luu_luong_nt,
        thanh_phan:
          co_so_san_xuat.intermediate_data.chatthais.nuocthai.thanh_phan_nt,
        cong_suat_he_thong_xu_ly:
          co_so_san_xuat.intermediate_data.chatthais.nuocthai.cong_suat_xl_nt,
        nguon_tiep_nhan:
          co_so_san_xuat.intermediate_data.chatthais.nuocthai
            .nguon_tiep_nhan_nt,
        thong_so_nuoc_thai_vuot_chuan:
          co_so_san_xuat.intermediate_data.chatthais.nuocthai.thong_so_vuot_nt,
      });
      (co_so_san_xuat.intermediate_data.chatthais.nuocthai.thanh_phan_nt =
        null),
        (co_so_san_xuat.intermediate_data.chatthais.nuocthai.luu_luong_nt =
          null),
        (co_so_san_xuat.intermediate_data.chatthais.nuocthai.cong_suat_xl_nt =
          null),
        (co_so_san_xuat.intermediate_data.chatthais.nuocthai.thong_so_vuot_nt =
          null),
        (co_so_san_xuat.intermediate_data.chatthais.nuocthai.nguon_tiep_nhan_nt =
          null);
    },
    deleteNuocthai: function (co_so_san_xuat, index) {
      co_so_san_xuat.chatthais.nuocthai.splice(index, 1);
    },
    addKhithai: function (co_so_san_xuat) {
      co_so_san_xuat.chatthais.khithai.push({
        luu_luong:
          co_so_san_xuat.intermediate_data.chatthais.khithai.luu_luong_kt,
        thanh_phan:
          co_so_san_xuat.intermediate_data.chatthais.khithai.thanh_phan_kt,
        cong_suat_he_thong_xu_ly:
          co_so_san_xuat.intermediate_data.chatthais.khithai.cong_suat_xl_kt,
        nguon_tiep_nhan:
          co_so_san_xuat.intermediate_data.chatthais.khithai.nguon_tiep_nhan_kt,
        thong_so_nuoc_thai_vuot_chuan:
          co_so_san_xuat.intermediate_data.chatthais.khithai.thong_so_vuot_kt,
        quy_trinh_xu_ly:
          co_so_san_xuat.intermediate_data.chatthais.khithai.quy_trinh_xu_ly,
      });
      (co_so_san_xuat.intermediate_data.chatthais.khithai.luu_luong_kt = null),
        (co_so_san_xuat.intermediate_data.chatthais.khithai.thanh_phan_kt =
          null),
        (co_so_san_xuat.intermediate_data.chatthais.khithai.cong_suat_xl_kt =
          null),
        (co_so_san_xuat.intermediate_data.chatthais.khithai.thong_so_vuot_kt =
          null),
        (co_so_san_xuat.intermediate_data.chatthais.khithai.nguon_tiep_nhan_kt =
          null);
      co_so_san_xuat.intermediate_data.chatthais.khithai.quy_trinh_xu_ly = null;
    },
    deleteKhithai: function (co_so_san_xuat, index) {
      co_so_san_xuat.chatthais.khithai.splice(index, 1);
    },
    addCtrsh: function (co_so_san_xuat) {
      var ty_le = 1;
      if (co_so_san_xuat.intermediate_data.chatthais.ctrsh.don_vi_ctrsh) {
        this.donvikhoiluongphatsinhs.forEach((element) => {
          if (
            element.id ==
            co_so_san_xuat.intermediate_data.chatthais.ctrsh.don_vi_ctrsh.id
          ) {
            ty_le = element.ty_le;
          }
        });
      }
      co_so_san_xuat.intermediate_data.chatthais.ctrsh.khoi_luong_phat_sinh_ctrsh =
        co_so_san_xuat.intermediate_data.chatthais.ctrsh
          .khoi_luong_phat_sinh_ctrsh * ty_le;
      co_so_san_xuat.chatthais.ctrsh.push({
        khoi_luong_phat_sinh:
          co_so_san_xuat.intermediate_data.chatthais.ctrsh
            .khoi_luong_phat_sinh_ctrsh,
        thanh_phan_chinh:
          co_so_san_xuat.intermediate_data.chatthais.ctrsh
            .thanh_phan_chinh_ctrsh,
        tu_xu_ly_ctrsh:
          co_so_san_xuat.intermediate_data.chatthais.ctrsh.tu_xu_ly_ctrsh,
        thue_xu_ly_ctrsh:
          co_so_san_xuat.intermediate_data.chatthais.ctrsh.thue_xu_ly_ctrsh,
        don_vi_ctrsh:
          co_so_san_xuat.intermediate_data.chatthais.ctrsh.don_vi_ctrsh,
        co_quan_thue_xu_ly:
          co_so_san_xuat.intermediate_data.chatthais.ctrsh
            .co_quan_thue_xu_ly_ctrsh,
      });
      (co_so_san_xuat.intermediate_data.chatthais.ctrsh.tu_xu_ly_ctrsh = false),
        (co_so_san_xuat.intermediate_data.chatthais.ctrsh.thue_xu_ly_ctrsh = false),
        (co_so_san_xuat.intermediate_data.chatthais.ctrsh.co_quan_thue_xu_ly_ctrsh =
          null),
        (co_so_san_xuat.intermediate_data.chatthais.ctrsh.thanh_phan_chinh_ctrsh =
          null),
        (co_so_san_xuat.intermediate_data.chatthais.ctrsh.khoi_luong_phat_sinh_ctrsh =
          null),
        (co_so_san_xuat.intermediate_data.chatthais.ctrsh.don_vi_ctrsh = null);
    },
    deleteCtrsh: function (co_so_san_xuat, index) {
      co_so_san_xuat.chatthais.ctrsh.splice(index, 1);
    },
    addCtrcntt: function (co_so_san_xuat) {
      var ty_le = 1;
      if (co_so_san_xuat.intermediate_data.chatthais.ctrcntt.don_vi_ctrcntt) {
        this.donvikhoiluongphatsinhs.forEach((element) => {
          if (
            element.id ==
            co_so_san_xuat.intermediate_data.chatthais.ctrcntt.don_vi_ctrcntt.id
          ) {
            ty_le = element.ty_le;
          }
        });
      }

      co_so_san_xuat.intermediate_data.chatthais.ctrcntt.khoi_luong_phat_sinh_ctrcntt =
        (
          co_so_san_xuat.intermediate_data.chatthais.ctrcntt
            .khoi_luong_phat_sinh_ctrcntt * ty_le
        ).toFixed(5);
      co_so_san_xuat.chatthais.ctrcntt.push({
        khoi_luong_phat_sinh:
          co_so_san_xuat.intermediate_data.chatthais.ctrcntt
            .khoi_luong_phat_sinh_ctrcntt,
        thanh_phan_chinh:
          co_so_san_xuat.intermediate_data.chatthais.ctrcntt
            .thanh_phan_chinh_ctrcntt,
        tu_xu_ly_ctrcntt:
          co_so_san_xuat.intermediate_data.chatthais.ctrcntt.tu_xu_ly_ctrcntt,
        thue_xu_ly_ctrcntt:
          co_so_san_xuat.intermediate_data.chatthais.ctrcntt.thue_xu_ly_ctrcntt,
        co_quan_thue_xu_ly_ctrcntt:
          co_so_san_xuat.intermediate_data.chatthais.ctrcntt
            .co_quan_thue_xu_ly_ctrcntt,
      });
      (co_so_san_xuat.intermediate_data.chatthais.ctrcntt.tu_xu_ly_ctrcntt = false),
        (co_so_san_xuat.intermediate_data.chatthais.ctrcntt.thue_xu_ly_ctrcntt = false),
        (co_so_san_xuat.intermediate_data.chatthais.ctrcntt.co_quan_thue_xu_ly_ctrcntt =
          null),
        (co_so_san_xuat.intermediate_data.chatthais.ctrcntt.khoi_luong_phat_sinh_ctrcntt =
          null),
        (co_so_san_xuat.intermediate_data.chatthais.ctrcntt.thanh_phan_chinh_ctrcntt =
          null),
        (co_so_san_xuat.intermediate_data.chatthais.ctrcntt.don_vi_ctrcntt =
          null);
    },
    deleteCtrcntt: function (co_so_san_xuat, index) {
      co_so_san_xuat.chatthais.ctrcntt.splice(index, 1);
    },
    addchatthainguyhai: function (co_so_san_xuat) {
      var ty_le = 1;
      if (co_so_san_xuat.intermediate_data.chatthais.ctnh.don_vi_ctnh) {
        this.donvikhoiluongphatsinhs.forEach((element) => {
          if (
            element.id ==
            co_so_san_xuat.intermediate_data.chatthais.ctnh.don_vi_ctnh.id
          ) {
            ty_le = element.ty_le;
          }
        });
      }
      co_so_san_xuat.intermediate_data.chatthais.ctnh.khoi_luong_phat_sinh_thuc_te_ctnh =
        co_so_san_xuat.intermediate_data.chatthais.ctnh
          .khoi_luong_phat_sinh_thuc_te_ctnh * ty_le;
      var ty_le2 = 1;
      if (co_so_san_xuat.intermediate_data.chatthais.ctnh.don_vi_ctnhdk) {
        this.donvikhoiluongphatsinhs.forEach((element) => {
          if (
            element.id ==
            co_so_san_xuat.intermediate_data.chatthais.ctnh.don_vi_ctnhdk.id
          ) {
            ty_le2 = element.ty_le;
          }
        });
      }
      co_so_san_xuat.intermediate_data.chatthais.ctnh.khoi_luong_phat_sinh_theo_dk_ctnh =
        co_so_san_xuat.intermediate_data.chatthais.ctnh
          .khoi_luong_phat_sinh_theo_dk_ctnh * ty_le2;
      co_so_san_xuat.chatthais.ctnh.push({
        khoi_luong_phat_sinh_thuc_te_ctnh:
          co_so_san_xuat.intermediate_data.chatthais.ctnh
            .khoi_luong_phat_sinh_thuc_te_ctnh,
        khoi_luong_phat_sinh_theo_dk_ctnh:
          co_so_san_xuat.intermediate_data.chatthais.ctnh
            .khoi_luong_phat_sinh_theo_dk_ctnh,
        thanh_phan_chinh:
          co_so_san_xuat.intermediate_data.chatthais.ctnh.thanh_phan_chinh_ctnh,
        tu_xu_ly_ctnh:
          co_so_san_xuat.intermediate_data.chatthais.ctnh.tu_xu_ly_ctnh,
        thue_xu_ly_ctnh:
          co_so_san_xuat.intermediate_data.chatthais.ctnh.thue_xu_ly_ctnh,
        co_quan_thue_xu_ly_ctnh:
          co_so_san_xuat.intermediate_data.chatthais.ctnh
            .co_quan_thue_xu_ly_ctnh,
      });
      (co_so_san_xuat.intermediate_data.chatthais.ctnh.tu_xu_ly_ctnh = false),
        (co_so_san_xuat.intermediate_data.chatthais.ctnh.thue_xu_ly_ctnh = false),
        (co_so_san_xuat.intermediate_data.chatthais.ctnh.co_quan_thue_xu_ly_ctnh =
          null),
        (co_so_san_xuat.intermediate_data.chatthais.ctnh.khoi_luong_phat_sinh_thuc_te_ctnh =
          null),
        (co_so_san_xuat.intermediate_data.chatthais.ctnh.khoi_luong_phat_sinh_theo_dk_ctnh =
          null);
      (co_so_san_xuat.intermediate_data.chatthais.ctnh.thanh_phan_chinh_ctnh =
        null),
        (co_so_san_xuat.intermediate_data.chatthais.ctnh.don_vi_ctnhdk = null),
        (co_so_san_xuat.intermediate_data.chatthais.ctnh.don_vi_ctnh = null);
    },
    deleteCtnh: function (co_so_san_xuat, index) {
      co_so_san_xuat.chatthais.ctnh.splice(index, 1);
    },
    addKetQuaThanhTra: function () {
      console.log(this.orgnaization, this.chuDauTu)
      if (this.validator()) {
        this.is_loading = true;
        this.form_data.chu_dau_tu_id = this.chuDauTu ? this.chuDauTu.id : null;
        axios
          .post(env.endpointhttp + "ketquathanhtra/add", {
            data: this.form_data,
            id: this.orgnaization ? this.orgnaization.id : null,
          })
          .then((response) => {
            window.location.href = "/cososanxuat";
          })
          .catch(() => {
            this.is_loading = false;
            MessageService.showWarningMessage("Lỗi hệ thống! ");
          });
      } else {
        this.$forceUpdate();
        MessageService.showWarningMessage("Dữ liệu không hợp lệ");
      }
    },
    asyncFindToChuc(query) {
      if (query) {
        this.is_loading = true;
        axios
          .get(env.endpointhttp + "asynctochuc?search=" + query)
          .then((response) => {
            this.orgnaizations = response.data.result;
          })
          .catch((error) => {})
          .finally(() => (this.is_loading = false));
      }
    },
    selectToChuc(val) {
      this.resetCoSoSanXuat();
      this.form_data.co_so_san_xuats = [];
      let don_vi_nuoc = this.donvinuoc.find(
        (element) => element.don_vi_goc === true
      );
      let don_vi_dien_tich = this.donvidientich.find(
        (element) => element.don_vi_goc === true
      );
      if (val) {
        axios
          .get(
            env.endpointhttp + "cososanxuats/organization?to_chuc_id=" + val.id
          )
          .then((response) => {
            var cososanxuats = response.data.result;
            if (cososanxuats.length > 0) this.currentTab = cososanxuats[0].id;
            cososanxuats.forEach((item) => {
              item.don_vi_nuoc_su_dung = don_vi_nuoc;
              item.don_vi_dien_tich = don_vi_dien_tich;
              var intermediate_data = {
                change: true,
                cong_suat_hoat_dong_new_item: {
                  loai_hinh_co_so: null,
                  don_vi_cong_suat_hoat_dong: null,
                  don_vi_cong_suat_thiet_ke: null,
                  cong_suat_hoat_dong: 0,
                  cong_suat_thiet_ke: 0,
                },
                chatthais: {
                  nuocthai: {
                    luu_luong_nt: 0,
                    cong_suat_xl_nt: 0,
                    thanh_phan_nt: null,
                    thong_so_vuot_nt: null,
                    nguon_tiep_nhan_nt: null,
                  },
                  khithai: {
                    luu_luong_kt: 0,
                    cong_suat_xl_kt: 0,
                    thanh_phan_kt: null,
                    thong_so_vuot_kt: null,
                    nguon_tiep_nhan_kt: null,
                    quy_trinh_xu_ly: null,
                  },
                  ctrsh: {
                    khoi_luong_phat_sinh_ctrsh: 0,
                    don_vi_ctrsh: null,
                    thanh_phan_chinh_ctrsh: null,
                    tu_xu_ly_ctrsh: false,
                    thue_xu_ly_ctrsh: false,
                    co_quan_thue_xu_ly_ctrsh: null,
                  },
                  ctrcntt: {
                    khoi_luong_phat_sinh_ctrcntt: 0,
                    don_vi_ctrcntt: null,
                    thanh_phan_chinh_ctrcntt: null,
                    tu_xu_ly_ctrcntt: false,
                    thue_xu_ly_ctrcntt: false,
                    co_quan_thue_xu_ly_ctrcntt: null,
                  },
                  ctnh: {
                    khoi_luong_phat_sinh_thuc_te_ctnh: 0,
                    don_vi_ctnh: null,
                    khoi_luong_phat_sinh_theo_dk_ctnh: 0,
                    don_vi_ctnhdk: null,
                    thanh_phan_chinh_ctnh: null,
                    tu_xu_ly_ctnh: false,
                    thue_xu_ly_ctnh: false,
                    co_quan_thue_xu_ly_ctnh: null,
                  },
                },
                thutuchanhchinhs: {
                  dtm: {
                    so_quyet_dinh: null,
                    co_quan_phe_duyet: null,
                    co_quan_phe_duyet_ten: null,
                    thoi_gian_phe_duyet: null,
                    error_so_quyet_dinh: null,
                    error_co_quan_phe_duyet: null,
                  },
                  dabvmt: {
                    so_quyet_dinh: null,
                    co_quan_phe_duyet: null,
                    co_quan_phe_duyet_ten: null,
                    thoi_gian_phe_duyet: null,
                    error_so_quyet_dinh: null,
                    error_co_quan_phe_duyet: null,
                  },
                  pabvmt: {
                    so_quyet_dinh: null,
                    co_quan_phe_duyet: null,
                    co_quan_phe_duyet_ten: null,
                    thoi_gian_phe_duyet: null,
                    error_so_quyet_dinh: null,
                    error_co_quan_phe_duyet: null,
                  },
                  gxnctbvmt: {
                    so_quyet_dinh: null,
                    co_quan_phe_duyet: null,
                    co_quan_phe_duyet_ten: null,
                    thoi_gian_phe_duyet: null,
                    error_so_quyet_dinh: null,
                    error_co_quan_phe_duyet: null,
                  },
                  sdkcntctnh: {
                    so_quyet_dinh: null,
                    co_quan_phe_duyet: null,
                    co_quan_phe_duyet_ten: null,
                    thoi_gian_phe_duyet: null,
                    error_so_quyet_dinh: null,
                    error_co_quan_phe_duyet: null,
                  },
                  gxnddkbvmtnkpl: {
                    so_quyet_dinh: null,
                    co_quan_phe_duyet: null,
                    co_quan_phe_duyet_ten: null,
                    thoi_gian_phe_duyet: null,
                    error_so_quyet_dinh: null,
                    error_co_quan_phe_duyet: null,
                  },
                  gpxt: {
                    so_quyet_dinh: null,
                    co_quan_phe_duyet: null,
                    co_quan_phe_duyet_ten: null,
                    thoi_gian_phe_duyet: null,
                    error_so_quyet_dinh: null,
                    error_co_quan_phe_duyet: null,
                  },
                  ttk: {
                    so_quyet_dinh: null,
                    co_quan_phe_duyet: null,
                    co_quan_phe_duyet_ten: null,
                    thoi_gian_phe_duyet: null,
                    ghi_chu: null,
                    error_so_quyet_dinh: null,
                    error_co_quan_phe_duyet: null,
                  },
                },
              };
              var chatthais = {
                nuocthai: [],
                khithai: [],
                ctrsh: [],
                ctrcntt: [],
                ctnh: [],
              };
              var thutuchanhchinhs = {
                dtm: [],
                dabvmt: [],
                pabvmt: [],
                gxnctbvmt: [],
                sdkcntctnh: [],
                gxnddkbvmtnkpl: [],
                gpxt: [],
                ttk: [],
              };
              item.chatthais = chatthais;
              item.thutuchanhchinhs = thutuchanhchinhs;
              item.errors = {
                don_vi_cstk: null,
                don_vi_cshd: null,
                loaics: null,
                tinh: null,
                huyen: null,
              };
              item.chi_tiet_cong_suat = item.chi_tiet_cong_suat.map(
                (chi_tiet) => ({
                  loai_hinh_co_so: chi_tiet.loai_hinh_co_so,
                  thong_so_thiet_ke:
                    chi_tiet.thong_so_thiet_ke || chi_tiet.thong_so,
                  don_vi_thiet_ke: chi_tiet.don_vi_thiet_ke,
                  thong_so_hoat_dong: chi_tiet.thong_so_hoat_dong,
                  don_vi_hoat_dong: chi_tiet.don_vi_hoat_dong,
                })
              );
              item.intermediate_data = intermediate_data;
              this.form_data.co_so_san_xuats.push(item);
            });
            Object.assign(this.form_data.co_so_san_xuats, cososanxuats);
          })
          .catch((error) => {})
          .finally(() => (this.is_loading = false));
      }
    },
    resetCoSoSanXuat() {
      this.form_data.co_so_san_xuat = {
        is_new: true,
        id: `new-dia-diem-hoat-dong-${new Date().getTime()}`,
        ten: null,
        khu_cong_nghiep: null,
        xa_phuong: null,
        quan_huyen: null,
        tinh_thanh: null,
        kinh_do: null,
        vi_do: null,
        dia_chi_hoat_dong: null,
        dia_chi_lien_he: null,
        so_giay_chung_nhan_kinh_doanh: null,
        ngay_cap_giay_chung_nhan_kinh_doanh: null,
        co_quan_cap_giay_chung_nhan_kinh_doanh: null,
        so_giay_chung_nhan_dau_tu: null,
        ngay_cap_giay_chung_nhan_dau_tu: null,
        co_quan_cap_giay_chung_nhan_dau_tu: null,
        dien_tich: 0,
        don_vi_dien_tich: null,
        luong_nuoc_su_dung: null,
        don_vi_nuoc_su_dung: null,
        so_nguoi_lao_dong: 0,
        nguyen_lieu_chinh_su_dung: null,
        nhien_lieu_chinh_su_dung: null,
        hoa_chat_su_dung: null,
        nguon_nuoc_su_dung: null,
        cong_suat_hoat_dongs: [],
        chatthais: {
          nuocthai: [],
          khithai: [],
          ctrsh: [],
          ctrcntt: [],
          ctnh: [],
        },
        thutuchanhchinhs: {
          dtm: [],
          dabvmt: [],
          pabvmt: [],
          gxnctbvmt: [],
          sdkcntctnh: [],
          gxnddkbvmtnkpl: [],
          gpxt: [],
          ttk: [],
        },
        attachments: [],
      };
    },
    asyncFindQuyetDinh(query) {
      if (query) {
        this.is_loading = true;
        axios
          .get(env.endpointhttp + "quyetdinhthanhtras?search=" + query)
          .then((response) => {
            this.quyetdinhthanhtras = response.data.result;
          })
          .catch((error) => {})
          .finally(() => (this.is_loading = false));
      }
    },
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
    asyncFindHuyen(co_so_san_xuat) {
      if (co_so_san_xuat.quan_huyen) {
        this.is_loading = true;
        axios
          .get(
            env.endpointhttp +
              "quanhuyens?search=" +
              co_so_san_xuat.quan_huyen.id
          )
          .then((response) => {
            co_so_san_xuat.huyens = response.data.result;
          })
          .catch((error) => {})
          .finally(() => (this.is_loading = false));
      }
    },
    changeHuyen: function (co_so_san_xuat) {
      if (co_so_san_xuat.quan_huyen) {
        co_so_san_xuat.tinh_thanh = this.tinhs.find(
          (tinh) => tinh.id === co_so_san_xuat.quan_huyen.tinh_thanh_id
        );
      } else {
        co_so_san_xuat.quan_huyen = null;
      }
    },
    changeTinh: function (co_so_san_xuat) {
      co_so_san_xuat.quan_huyen = null;
      if (co_so_san_xuat.tinh_thanh) {
        co_so_san_xuat.huyens = this.huyens.filter(
          (huyen) => huyen.tinh_thanh_id === co_so_san_xuat.tinh_thanh.id
        );
      } else {
        co_so_san_xuat.huyens = this.huyens;
      }
    },
    asyncFindTinh(co_so_san_xuat) {
      if (co_so_san_xuat.tinh_thanh) {
        this.is_loading = true;
        axios
          .get(
            env.endpointhttp +
              "tinhthanhs?search=" +
              co_so_san_xuat.tinh_thanh.id
          )
          .then((response) => {
            co_so_san_xuat.tinh_thanh.tinhs = response.data.result;
          })
          .catch((error) => {})
          .finally(() => (this.is_loading = false));
      }
    },
    changeKCN: function (co_so_san_xuat) {
      co_so_san_xuat.quan_huyen = null;
      co_so_san_xuat.xa_phuong = null;
      co_so_san_xuat.tinh_thanh = null;

      if (co_so_san_xuat.khu_cong_nghiep) {
        co_so_san_xuat.quan_huyen = this.huyens.find(
          (huyen) => huyen.id === co_so_san_xuat.khu_cong_nghiep.quan_huyen_id
        );
        co_so_san_xuat.tinh_thanh = this.tinhs.find(
          (tinh) => tinh.id === co_so_san_xuat.khu_cong_nghiep.tinh_thanh_id
        );
        co_so_san_xuat.xa_phuong = co_so_san_xuat.khu_cong_nghiep.xa_phuong;
      }
    },
    changeKinhDo() {
      this.change = true;
    },
    changeViDo() {
      this.change = true;
    },
    getToaDo: function (co_so_san_xuat, value) {
      this.change = false;
      co_so_san_xuat.kinh_do = value.kinhdo;
      co_so_san_xuat.vi_do = value.vido;
    },
    getTenDiaChi: function (co_so_san_xuat, value) {
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
    refresh: function () {
      axios
        .get(env.endpointhttp + "danhmuc/chuyendoidonvis")
        .then((response) => {
          this.donvicongsuathoatdongs =
            response.data.result.don_vi.cong_suat_hoat_dong;
          this.donvicongsuatthietkes =
            response.data.result.don_vi.cong_suat_thiet_ke;
          this.donvinuoc = response.data.result.don_vi.luu_luong_nuoc_thai;
          this.donvidientich = response.data.result.don_vi.dien_tich;
        });

      // co quan to chuc
      if (localStorage.getItem("coquantochucs")) {
        this.coquantochucs = JSON.parse(localStorage.getItem("coquantochucs"));
      } else {
        axios.get(env.endpointhttp + "coquantochucs").then((response) => {
          this.coquantochucs = response.data.result;
          localStorage.setItem(
            "coquantochucs",
            JSON.stringify(this.coquantochucs)
          );
        });
      }

      // loai hinh co so dang cay
      if (localStorage.getItem("loaihinhcosos?type=report")) {
        this.loai_hinh_co_so_tree_listing = JSON.parse(
          localStorage.getItem("loaihinhcosos?type=report")
        );
      } else {
        axios
          .get(env.endpointhttp + "loaihinhcosos?type=report")
          .then((response) => {
            this.loai_hinh_co_so_tree_listing = response.data.result;
            localStorage.setItem(
              "loaihinhcosos?type=report",
              JSON.stringify(this.loai_hinh_co_so_tree_listing)
            );
          });
      }

      // quan huyen
      if (localStorage.getItem("quanhuyens")) {
        this.huyens = JSON.parse(localStorage.getItem("quanhuyens"));
      } else {
        axios.get(env.endpointhttp + "quanhuyens").then((response) => {
          this.huyens = response.data.result;
          localStorage.setItem("quanhuyens", JSON.stringify(this.huyens));
        });
      }
      // tinh thanh
      if (localStorage.getItem("tinhthanhs")) {
        this.tinhs = JSON.parse(localStorage.getItem("tinhthanhs"));
      } else {
        axios.get(env.endpointhttp + "tinhthanhs").then((response) => {
          this.tinhs = response.data.result;
          localStorage.setItem("tinhthanhs", JSON.stringify(this.tinhs));
        });
      }
    },
    validator() {
      var valid = true;
      this.error_quyet_dinh = null;
      this.error_so_quyet_dinh = null;
      if (this.form_data.quyet_dinh_thanh_tra == null) {
        this.error_quyet_dinh = "Chọn quyết định thanh tra";
        valid = false;
      } else if (this.form_data.so_quyet_dinh == null) {
        this.error_so_quyet_dinh = "Nhập số quyết định";
        valid = false;
      }
      if (this.form_data.co_so_san_xuats.length > 0) {
        this.form_data.co_so_san_xuats.forEach((co_so_san_xuat) => {
          if (!co_so_san_xuat.dia_chi_hoat_dong) {
            co_so_san_xuat.errors.diachi = "Thiếu địa chỉ hoạt động";
            valid = false;
          }
          if (!co_so_san_xuat.tinh_thanh) {
            co_so_san_xuat.errors.tinh = "Thiếu tỉnh thành";
            valid = false;
          }
          if (!co_so_san_xuat.quan_huyen) {
            co_so_san_xuat.errors.huyen = "Thiếu quận huyện";
            valid = false;
          }
          if (co_so_san_xuat.luuvucsong == 0) {
            co_so_san_xuat.luuvucsong = null;
          }
          if (co_so_san_xuat.dien_tich < 0) {
            co_so_san_xuat.dien_tich = 0;
          }
          if (
            co_so_san_xuat.dien_tich != 0 &&
            co_so_san_xuat.don_vi_dien_tich == null
          ) {
            co_so_san_xuat.errors.don_vi_dien_tich = "Thiếu đơn vị đo";
            valid = false;
          }
          if (co_so_san_xuat.so_nguoi_lao_dong < 0) {
            co_so_san_xuat.so_nguoi_lao_dong = 0;
          }
          if (!co_so_san_xuat.ten) {
            co_so_san_xuat.errors.tencs = "Thiếu trường tên cơ sở thanh tra";
            valid = false;
          }
        });
      } else {
        valid = false;
      }
      return valid;
    },
    addDiaDiemHoatDong() {
      var co_so_san_xuat = {
        chatthais: {
          ctnh: [],
          ctrcntt: [],
          ctrsh: [],
          khithai: [],
          nuocthai: [],
        },
        chi_tiet_cong_suat: [],
        co_quan_cap_giay_chung_nhan_dau_tu: null,
        co_quan_cap_giay_chung_nhan_kinh_doanh: null,
        co_quan_dau_tu: null,
        co_quan_kinh_doanh: null,
        dia_chi_hoat_dong: null,
        dia_chi_lien_he: null,
        dien_tich: null,
        don_vi_dien_tich: this.donvidientich.find(
          (element) => element.don_vi_goc === true
        ),
        don_vi_nuoc_su_dung: this.donvinuoc.find(
          (element) => element.don_vi_goc === true
        ),
        errors: {
          don_vi_cstk: null,
          don_vi_cshd: null,
          loaics: null,
        },
        hoa_chat_su_dung: null,
        intermediate_data: null,
        khu_cong_nghiep: null,
        khu_cong_nghiep_id: null,
        kinh_do: 105,
        luong_nuoc_su_dung: null,
        luu_vuc_song_id: null,
        ma: null,
        mien_id: null,
        ngay_cap_giay_chung_nhan_dau_tu: null,
        ngay_cap_giay_chung_nhan_kinh_doanh: null,
        nguon_nuoc_su_dung: null,
        nguyen_lieu_chinh_su_dung: null,
        nhien_lieu_chinh_su_dung: null,
        organization_id: this.orgnaization ? this.orgnaization.id : null,
        quan_huyen: null,
        quan_huyen_id: null,
        so_giay_chung_nhan_dau_tu: null,
        so_giay_chung_nhan_kinh_doanh: null,
        so_nguoi_lao_dong: null,
        ten: null,
        tg: false,
        thutuchanhchinhs: {
          dabvmt: [],
          dtm: [],
          gpxt: [],
          gxnctbvmt: [],
          gxnddkbvmtnkpl: [],
          pabvmt: [],
          sdkcntctnh: [],
          ttk: [],
        },
        tinh_thanh: null,
        tinh_thanh_id: null,
        vi_do: "21.0040972",

        vung_kinh_te_trong_diem_id: null,
        xa_phuong: null,
        intermediate_data: {
          change: true,
          cong_suat_hoat_dong_new_item: {
            loai_hinh_co_so: null,
            don_vi_cong_suat_hoat_dong: null,
            don_vi_cong_suat_thiet_ke: null,
            cong_suat_hoat_dong: 0,
            cong_suat_thiet_ke: 0,
          },
          chatthais: {
            nuocthai: {
              luu_luong_nt: 0,
              cong_suat_xl_nt: 0,
              thanh_phan_nt: null,
              thong_so_vuot_nt: null,
              nguon_tiep_nhan_nt: null,
            },
            khithai: {
              luu_luong_kt: 0,
              cong_suat_xl_kt: 0,
              thanh_phan_kt: null,
              thong_so_vuot_kt: null,
              nguon_tiep_nhan_kt: null,
              quy_trinh_xu_ly: null,
            },
            ctrsh: {
              khoi_luong_phat_sinh_ctrsh: 0,
              don_vi_ctrsh: null,
              thanh_phan_chinh_ctrsh: null,
              tu_xu_ly_ctrsh: false,
              thue_xu_ly_ctrsh: false,
              co_quan_thue_xu_ly_ctrsh: null,
            },
            ctrcntt: {
              khoi_luong_phat_sinh_ctrcntt: 0,
              don_vi_ctrcntt: null,
              thanh_phan_chinh_ctrcntt: null,
              tu_xu_ly_ctrcntt: false,
              thue_xu_ly_ctrcntt: false,
              co_quan_thue_xu_ly_ctrcntt: null,
            },
            ctnh: {
              khoi_luong_phat_sinh_thuc_te_ctnh: 0,
              don_vi_ctnh: null,
              khoi_luong_phat_sinh_theo_dk_ctnh: 0,
              don_vi_ctnhdk: null,
              thanh_phan_chinh_ctnh: null,
              tu_xu_ly_ctnh: false,
              thue_xu_ly_ctnh: false,
              co_quan_thue_xu_ly_ctnh: null,
            },
          },
          thutuchanhchinhs: {
            dtm: {
              so_quyet_dinh: null,
              co_quan_phe_duyet: null,
              co_quan_phe_duyet_ten: null,
              thoi_gian_phe_duyet: null,
              error_so_quyet_dinh: null,
              error_co_quan_phe_duyet: null,
            },
            dabvmt: {
              so_quyet_dinh: null,
              co_quan_phe_duyet: null,
              co_quan_phe_duyet_ten: null,
              thoi_gian_phe_duyet: null,
              error_so_quyet_dinh: null,
              error_co_quan_phe_duyet: null,
            },
            pabvmt: {
              so_quyet_dinh: null,
              co_quan_phe_duyet: null,
              co_quan_phe_duyet_ten: null,
              thoi_gian_phe_duyet: null,
              error_so_quyet_dinh: null,
              error_co_quan_phe_duyet: null,
            },
            gxnctbvmt: {
              so_quyet_dinh: null,
              co_quan_phe_duyet: null,
              co_quan_phe_duyet_ten: null,
              thoi_gian_phe_duyet: null,
              error_so_quyet_dinh: null,
              error_co_quan_phe_duyet: null,
            },
            sdkcntctnh: {
              so_quyet_dinh: null,
              co_quan_phe_duyet: null,
              co_quan_phe_duyet_ten: null,
              thoi_gian_phe_duyet: null,
              error_so_quyet_dinh: null,
              error_co_quan_phe_duyet: null,
            },
            gxnddkbvmtnkpl: {
              so_quyet_dinh: null,
              co_quan_phe_duyet: null,
              co_quan_phe_duyet_ten: null,
              thoi_gian_phe_duyet: null,
              error_so_quyet_dinh: null,
              error_co_quan_phe_duyet: null,
            },
            gpxt: {
              so_quyet_dinh: null,
              co_quan_phe_duyet: null,
              co_quan_phe_duyet_ten: null,
              thoi_gian_phe_duyet: null,
              error_so_quyet_dinh: null,
              error_co_quan_phe_duyet: null,
            },
            ttk: {
              so_quyet_dinh: null,
              co_quan_phe_duyet: null,
              co_quan_phe_duyet_ten: null,
              thoi_gian_phe_duyet: null,
              error_so_quyet_dinh: null,
              error_co_quan_phe_duyet: null,
            },
          },
        },
      };

      co_so_san_xuat.is_new = true;
      co_so_san_xuat.id = `new-dia-diem-hoat-dong-${new Date().getTime()}`;
      co_so_san_xuat.co_so_san_xuat_id = co_so_san_xuat.co_so_san_xuat_id;
      this.form_data.co_so_san_xuats.push(co_so_san_xuat);
      this.currentTab = co_so_san_xuat.id;
    },
    addCoSoSanXuat() {
      this.form_data.co_so_san_xuats = [];
      this.orgnaization = null;
      this.addDiaDiemHoatDong();
    },
    // xoa dia chi hoat dong của một tổ chức thanh tra
    deleteDiaChiHoatDong(index) {
      this.form_data.co_so_san_xuats.splice(index, 1);
      if (this.form_data.co_so_san_xuats.length > 0) {
        this.currentTab = this.form_data.co_so_san_xuats[0].id;
      }
    },
  },
};
</script>
