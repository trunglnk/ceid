<template>
  <div>
    <div class="col-lg-12" style="margin-top: 10px">
      <label>KẾT QUẢ THANH TRA CHẤT THẢI</label>
      <p class="text-muted">
        (Click vào từng mục nước thải, khí thải, chất thải rắn sinh hoạt, chất
        thải rắn công nghiệp thông thường, và chất thải nguy hại)
      </p>
      <hr style="margin-top: 7px; margin-bottom: 7px" />
    </div>
    <div class="col-md-12">
      <div class="nav-tabs-custom margin-top">
        <ul class="nav nav-tabs">
          <li class="active">
            <a :href="'#tab_1' + index1" data-toggle="tab" aria-expanded="true"
              >Nước thải</a
            >
          </li>
          <li class>
            <a :href="'#tab_2' + index1" data-toggle="tab" aria-expanded="false"
              >Khí thải,bụi</a
            >
          </li>
          <li class>
            <a :href="'#tab_3' + index1" data-toggle="tab" aria-expanded="false"
              >Chất thải rắn sinh hoạt</a
            >
          </li>
          <li class>
            <a :href="'#tab_4' + index1" data-toggle="tab" aria-expanded="false"
              >Chất thải rắn công nghiệp thông thường</a
            >
          </li>
          <li class>
            <a :href="'#tab_5' + index1" data-toggle="tab" aria-expanded="false"
              >Chất thải nguy hại</a
            >
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" :id="'tab_1' + index1">
            <!-- <PhieuThuNghiem
              :form_chat_thai="form_nuoc_thai"
              :optionsDate2="optionsDate2"
              @updateFormNuocThai="handleFormNuocThai"
            /> -->
            <KetQuaQuanTracEdit
              :inputData.sync="value.ket_qua_quan_trac"
              loai="chat_thai_nuoc_thai"
              @update:inputData="value.ket_qua_quan_trac = $event"
            />

            <div class="">
              <div
                class="row block-multiple-rows"
                v-if="
                  usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'nhanvientrungtam' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
                "
              >
                <div class="col-lg-12" style="margin-top: 5px">
                  <p class="text-muted">
                    Nhập thông tin lưu lượng nước thải, công suất hệ thống, loại
                    nước thải phát sinh ... và nhấn nút thêm để thêm kết quả
                    thanh tra nước thải
                  </p>
                  <hr style="margin-top: 7px; margin-bottom: 7px" />
                </div>
                <div class="col col-lg-2 col-md-4">
                  <div class="form-group">
                    <ejs-numerictextbox
                      class="form-control"
                      format=""
                      v-model="form_nuoc_thai.luu_luong_nt"
                      placeholder="Lưu lượng m3/ngày đêm"
                    ></ejs-numerictextbox>
                  </div>
                </div>
                <div class="col col-lg-2 col-md-4">
                  <div class="form-group">
                    <ejs-numerictextbox
                      class="form-control"
                      format=""
                      v-model="form_nuoc_thai.cong_suat_xl_nt"
                      placeholder="Công suất hệ thống m3/ngày đêm"
                    ></ejs-numerictextbox>
                  </div>
                </div>
                <div class="col col-lg-2 col-md-4">
                  <div class="form-group">
                    <input
                      type="text"
                      class="form-control"
                      v-model="form_nuoc_thai.thanh_phan_nt"
                      placeholder="Loại nước thải phát sinh"
                    />
                  </div>
                </div>
                <div class="col col-lg-2 col-md-4">
                  <div class="form-group">
                    <input
                      type="text"
                      class="form-control"
                      v-model="form_nuoc_thai.thong_so_vuot_nt"
                      placeholder="Thông số vượt QCKT"
                    />
                  </div>
                </div>
                <div class="col col-lg-3 col-md-6">
                  <div class="form-group">
                    <input
                      type="text"
                      class="form-control"
                      v-model="form_nuoc_thai.nguon_tiep_nhan_nt"
                      placeholder="Nguồn tiếp nhận"
                    />
                  </div>
                </div>
                <div class="col col-lg-3 col-md-6">
                  <div class="form-group">
                    <Multiselect
                      :placeholder="'Loại hình quan trắc'"
                      :options="optionLoaiHinhQuanTracs"
                      label="ten"
                      track-by="id"
                      v-model="form_nuoc_thai.loai_hinh_quan_trac"
                      :searchable="true"
                      :clear-on-select="false"
                      :close-on-select="true"
                      :options-limit="300"
                      :limit="3"
                      :disabled="!canEdit"
                      :show-no-results="false"
                      :hide-selected="false"
                      :tabindex="1"
                      selectLabel="Nhấn enter để chọn"
                      deselectLabel="Nhấn enter để bỏ chọn"
                      selectedLabel="Đã chọn"
                    >
                      <span slot="noResult">Không tìm thấy kết quả</span>
                    </Multiselect>
                  </div>
                </div>
                <div class="col col-lg-1 col-md-2">
                  <button
                    type="button"
                    class="btn btn-flat pull-right"
                    @click="addNuocthai()"
                  >
                    <i class="fa fa-plus"></i> Thêm
                  </button>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <table class="table table-hover table-responsive" role="grid">
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
                        <th>Loại hình quan trắc</th>
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
                    </thead>
                    <tbody>
                      <tr
                        v-for="(
                          item, index
                        ) in co_so_san_xuat.ket_qua_thanh_tra_nuoc_thais"
                        :key="index"
                      >
                        <td>{{ item.luu_luong | numFormat }}</td>
                        <td>{{ item.cong_suat_he_thong_xu_ly | numFormat }}</td>
                        <td>{{ item.thanh_phan }}</td>
                        <td>{{ item.nguon_tiep_nhan }}</td>
                        <td>
                          {{ item.thong_so_nuoc_thai_vuot_chuan }}
                        </td>
                        <td>
                          {{
                            (
                              optionLoaiHinhQuanTracs.find(
                                (x) =>
                                  x.id ===
                                  (item.loai_hinh_quan_trac &&
                                  item.loai_hinh_quan_trac.id
                                    ? item.loai_hinh_quan_trac.id
                                    : item.loai_hinh_quan_trac)
                              ) || {}
                            ).ten || ""
                          }}
                        </td>
                        <td
                          align="center"
                          v-if="
                            usersystem.role_code == 'admin' ||
                            usersystem.role_code == 'nhanvientrungtam' ||
                            usersystem.role_code == 'adminvaquanlydanhmuc'
                          "
                        >
                          <a @click="deleteNuocthai(index)">
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
          <div class="tab-pane" :id="'tab_2' + index1">
            <!-- <PhieuThuNghiem
              :form_chat_thai="form_khi_thai"
              :optionsDate2="optionsDate2"
              @updateFormKhiThai="handleFormKhiThai"
            /> -->
            <KetQuaQuanTracEdit
              :inputData.sync="value.ket_qua_quan_trac"
              loai="chat_thai_khi_thai"
              @update:inputData="value.ket_qua_quan_trac = $event"
            />
            <div class="">
              <div
                class="row block-multiple-rows"
                v-if="
                  usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'nhanvientrungtam' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
                "
              >
                <div class="col-lg-12" style="margin-top: 5px">
                  <p class="text-muted">
                    Nhập thông tin lưu lượng khí thải, công suất hệ thống, thành
                    phần ... và nhấn nút thêm để thêm kết quả thanh tra khí thải
                  </p>
                  <hr style="margin-top: 7px; margin-bottom: 7px" />
                </div>
                <div class="col col-lg-2 col-md-4">
                  <div class="form-group">
                    <ejs-numerictextbox
                      type="text"
                      class="form-control"
                      format=""
                      v-model="form_khi_thai.luu_luong_kt"
                      placeholder="Lưu lượng khí thải (m3/giờ)"
                    ></ejs-numerictextbox>
                  </div>
                </div>
                <div class="col col-lg-2 col-md-4">
                  <div class="form-group">
                    <ejs-numerictextbox
                      type="text"
                      class="form-control"
                      format=""
                      v-model="form_khi_thai.cong_suat_xl_kt"
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
                      v-model="form_khi_thai.thanh_phan_kt"
                    />
                  </div>
                </div>
                <div class="col col-lg-2 col-md-6">
                  <div class="form-group">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Thông số vượt chuẩn"
                      v-model="form_khi_thai.thong_so_vuot_kt"
                    />
                  </div>
                </div>
                <div class="col col-lg-3 col-md-6">
                  <div class="form-group">
                    <Multiselect
                      :placeholder="'Loại hình quan trắc'"
                      :options="optionLoaiHinhQuanTracs"
                      label="ten"
                      track-by="id"
                      v-model="form_khi_thai.loai_hinh_quan_trac"
                      :searchable="true"
                      :clear-on-select="false"
                      :close-on-select="true"
                      :options-limit="300"
                      :limit="3"
                      :disabled="!canEdit"
                      :show-no-results="false"
                      :hide-selected="false"
                      :tabindex="1"
                      selectLabel="Nhấn enter để chọn"
                      deselectLabel="Nhấn enter để bỏ chọn"
                      selectedLabel="Đã chọn"
                    >
                      <span slot="noResult">Không tìm thấy kết quả</span>
                    </Multiselect>
                  </div>
                </div>
                <div class="col col-lg-11 col-md-10">
                  <div class="form-group">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Quy trình xử lý"
                      v-model="form_khi_thai.quy_trinh_xu_ly"
                    />
                  </div>
                </div>
                <div class="col col-lg-1 col-md-2">
                  <button
                    type="button"
                    class="btn btn-default btn-flat pull-right"
                    @click="addKhithai()"
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
                        <!-- <th>Nguồn tiếp nhận</th> -->
                        <th>Thông số vượt chuẩn</th>
                        <th>Quy trình xử lý</th>
                        <th>Loại hình quan trắc</th>
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
                    </thead>
                    <tbody>
                      <tr
                        v-for="(
                          item, index
                        ) in co_so_san_xuat.ket_qua_thanh_tra_khi_thais"
                        :key="index"
                      >
                        <td>{{ item.luu_luong | numFormat }}</td>
                        <td>{{ item.cong_suat_he_thong_xu_ly | numFormat }}</td>
                        <td>{{ item.thanh_phan }}</td>
                        <!-- <td>{{item.nguon_tiep_nhan}}</td> -->
                        <td>{{ item.thong_so_khi_thai_vuot_chuan }}</td>
                        <td>{{ item.quy_trinh_xu_ly }}</td>
                        <td>
                          {{
                            (
                              optionLoaiHinhQuanTracs.find(
                                (x) =>
                                  x.id ===
                                  (item.loai_hinh_quan_trac &&
                                  item.loai_hinh_quan_trac.id
                                    ? item.loai_hinh_quan_trac.id
                                    : item.loai_hinh_quan_trac)
                              ) || {}
                            ).ten || ""
                          }}
                        </td>
                        <td
                          align="center"
                          v-if="
                            usersystem.role_code == 'admin' ||
                            usersystem.role_code == 'nhanvientrungtam' ||
                            usersystem.role_code == 'adminvaquanlydanhmuc'
                          "
                        >
                          <a @click="deleteKhithai(index)">
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
          <!-- /.tab-pane -->
          <div class="tab-pane" :id="'tab_3' + index1">
            <!-- <PhieuThuNghiem
              :form_chat_thai="form_chat_thai.ctrsh"
              :optionsDate2="optionsDate2"
              @updateFormCtrsh="handleFormCtrsh"
            /> -->
            <KetQuaQuanTracEdit
              :inputData.sync="value.ket_qua_quan_trac"
              loai="chat_thai_ran_sinh_hoat"
              @update:inputData="value.ket_qua_quan_trac = $event"
            />
            <div class="">
              <div
                class="row block-multiple-rows"
                v-if="
                  usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'nhanvientrungtam' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
                "
              >
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
                          format
                          v-model="
                            form_chat_thai.ctrsh.khoi_luong_phat_sinh_ctrsh
                          "
                          placeholder="Khối lượng phát sinh"
                        ></ejs-numerictextbox>
                      </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                      <div class="input-group">
                        <multiselect
                          v-model="form_chat_thai.ctrsh.don_vi_ctrsh"
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
                          <span slot="noResult">Không tìm thấy kết quả</span>
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
                          v-model="form_chat_thai.ctrsh.thanh_phan_chinh_ctrsh"
                        />
                      </div>
                    </div>
                    <div
                      class="col-lg-2 col-md-3"
                      v-if="!form_chat_thai.ctrsh.thue_xu_ly_ctrsh"
                    >
                      <checkbox-component
                        v-model="form_chat_thai.ctrsh.tu_xu_ly_ctrsh"
                        type="checkbox"
                        :id="'tu_xu_ly_ctrsh' + index1"
                        text="Tự xử lý"
                      ></checkbox-component>
                    </div>
                    <div
                      class="col-lg-2 col-md-3"
                      v-if="!form_chat_thai.ctrsh.tu_xu_ly_ctrsh"
                    >
                      <checkbox-component
                        v-model="form_chat_thai.ctrsh.thue_xu_ly_ctrsh"
                        type="checkbox"
                        :id="'thue_xu_ly_ctrsh' + index1"
                        text="Thuê xử lý"
                      ></checkbox-component>
                    </div>
                  </div>
                  <div class="row">
                    <div
                      class="col-lg-4 col-md-10"
                      v-if="form_chat_thai.ctrsh.thue_xu_ly_ctrsh"
                    >
                      <input
                        type="text"
                        class="form-control"
                        v-model="form_chat_thai.ctrsh.co_quan_thue_xu_ly_ctrsh"
                        placeholder="Đơn vị xử lý"
                      />
                    </div>
                    <div class="col-lg-1 col-md-2">
                      <button
                        type="button"
                        class="btn btn-flat pull-right btn-block"
                        @click="addCtrsh()"
                        style="height: 40px"
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
                          Khối lượng phát sinh
                          <span
                            style="color: rgb(216, 27, 96)"
                            v-text="'(' + ten_don_vi_ctrsh + ')'"
                          ></span>
                        </th>
                        <th>Thành phần</th>
                        <th>Tự xử lý</th>
                        <th>Thuê xử lý</th>
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
                    </thead>
                    <tbody>
                      <tr
                        v-for="(
                          item, index
                        ) in co_so_san_xuat.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats"
                        :key="index"
                      >
                        <td>{{ item.khoi_luong_phat_sinh }}</td>
                        <td>{{ item.thanh_phan_chinh }}</td>
                        <td>
                          <i
                            class="fa fa-check-square-o"
                            v-if="item.tu_xu_ly"
                          ></i>
                          <i class="fa fa-square-o" v-else></i>
                        </td>
                        <td>
                          <i
                            class="fa fa-check-square-o"
                            v-if="item.thue_xu_ly"
                          ></i>
                          <i class="fa fa-square-o" v-else></i>
                          {{ item.co_quan_thue_xu_ly }}
                        </td>
                        <td
                          align="center"
                          v-if="
                            usersystem.role_code == 'admin' ||
                            usersystem.role_code == 'nhanvientrungtam' ||
                            usersystem.role_code == 'adminvaquanlydanhmuc'
                          "
                        >
                          <a @click="deleteCtrsh(index)">
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
          <div class="tab-pane" :id="'tab_4' + index1">
            <!-- <PhieuThuNghiem
              :form_chat_thai="form_chat_thai.ctrcntt"
              :optionsDate2="optionsDate2"
              @updateFormCtrCntt="handleFormCtrCntt"
            /> -->
            <KetQuaQuanTracEdit
              :inputData.sync="value.ket_qua_quan_trac"
              loai="chat_thai_ran_cntt"
              @update:inputData="value.ket_qua_quan_trac = $event"
            />
            <div class="">
              <div
                class="row block-multiple-rows"
                v-if="
                  usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'nhanvientrungtam' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
                "
              >
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
                            form_chat_thai.ctrcntt.khoi_luong_phat_sinh_ctrcntt
                          "
                          placeholder="Khối lượng phát sinh"
                        ></ejs-numerictextbox>
                      </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                      <div class="input-group">
                        <multiselect
                          v-model="form_chat_thai.ctrcntt.don_vi_ctrcntt"
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
                          <span slot="noResult">Không tìm thấy kết quả</span>
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
                            form_chat_thai.ctrcntt.thanh_phan_chinh_ctrcntt
                          "
                          placeholder="Thành phần chính"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div
                      class="col-lg-2 col-md-3"
                      v-if="!form_chat_thai.ctrcntt.thue_xu_ly_ctrcntt"
                    >
                      <checkbox-component
                        v-model="form_chat_thai.ctrcntt.tu_xu_ly_ctrcntt"
                        type="checkbox"
                        :id="'tu_xu_ly_ctrsh1' + index1"
                        text="Tự xử lý"
                      ></checkbox-component>
                    </div>
                    <div
                      class="col-lg-2 col-md-3"
                      v-if="!form_chat_thai.ctrcntt.tu_xu_ly_ctrcntt"
                    >
                      <checkbox-component
                        v-model="form_chat_thai.ctrcntt.thue_xu_ly_ctrcntt"
                        type="checkbox"
                        :id="'thue_xu_ly_ctrsh1' + index1"
                        text="Thuê xử lý"
                      ></checkbox-component>
                    </div>
                  </div>
                  <div class="row">
                    <div
                      class="col-lg-4 col-md-10"
                      v-if="form_chat_thai.ctrcntt.thue_xu_ly_ctrcntt"
                    >
                      <input
                        type="text"
                        class="form-control"
                        v-model="
                          form_chat_thai.ctrcntt.co_quan_thue_xu_ly_ctrcntt
                        "
                        placeholder="Đơn vị xử lý"
                      />
                    </div>
                    <div class="col-lg-2 col-md-2">
                      <button
                        type="button"
                        class="btn bg-flat btn-flat pull-right btn-block"
                        @click="addCtrcntt()"
                        style="height: 40px"
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
                          Khối lượng phát sinh
                          <span
                            style="color: rgb(216, 27, 96)"
                            v-text="'(' + ten_don_vi_ctrsh + ')'"
                          ></span>
                        </th>
                        <th>Thành phần</th>
                        <th>Tự xử lý</th>
                        <th>Thuê xử lý</th>
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
                    </thead>
                    <tbody>
                      <tr
                        v-for="(
                          item, index
                        ) in co_so_san_xuat.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t"
                        :key="index"
                      >
                        <td>{{ item.khoi_luong_phat_sinh | f2Number }}</td>
                        <td>{{ item.thanh_phan_chinh }}</td>
                        <td>
                          <i
                            class="fa fa-check-square-o"
                            v-if="item.tu_xu_ly"
                          ></i>
                          <i class="fa fa-square-o" v-else></i>
                        </td>
                        <td>
                          <i
                            class="fa fa-check-square-o"
                            v-if="item.thue_xu_ly"
                          ></i>
                          <i class="fa fa-square-o" v-else></i>
                          {{ item.co_quan_thue_xu_ly }}
                        </td>
                        <td
                          align="center"
                          v-if="
                            usersystem.role_code == 'admin' ||
                            usersystem.role_code == 'nhanvientrungtam' ||
                            usersystem.role_code == 'adminvaquanlydanhmuc'
                          "
                        >
                          <a @click="deleteCtrcntt(index)">
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
          <div class="tab-pane" :id="'tab_5' + index1">
            <!-- <PhieuThuNghiem
              :form_chat_thai="form_chat_thai.ctnh"
              :optionsDate2="optionsDate2"
              @updateFormCtnh="handleFormCtnh"
            /> -->
            <KetQuaQuanTracEdit
              :inputData.sync="value.ket_qua_quan_trac"
              loai="chat_thai_nguy_hai"
              @update:inputData="value.ket_qua_quan_trac = $event"
            />
            <div class="">
              <div
                class="row block-multiple-rows"
                v-if="
                  usersystem.role_code == 'admin' ||
                  usersystem.role_code == 'nhanvientrungtam' ||
                  usersystem.role_code == 'adminvaquanlydanhmuc'
                "
              >
                <div class="col-lg-12" style="margin-top: 5px">
                  <p class="text-muted">
                    Nhập thông tin khối lượng phát sinh thực tế, khối lượng phát
                    sinh theo đăng ký, thành phấn chính... và nhấn nút thêm để
                    thêm kết quả thanh tra chất thải nguy hại. Trường hợp thiếu
                    đơn vị, mở tab mới danh mục, trong mục đơn vị chất thải nguy
                    hại, thêm mới đơn vị và nhấn biểu tượng refresh ô đơn vị
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
                          form_chat_thai.ctnh.khoi_luong_phat_sinh_thuc_te_ctnh
                        "
                        placeholder="Khối lượng phát sinh thực tế"
                      ></ejs-numerictextbox>
                    </div>
                    <div class="col-lg-2 col-md-5">
                      <div class="input-group">
                        <multiselect
                          v-model="form_chat_thai.ctnh.don_vi_ctnh"
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
                          <span slot="noResult">Không tìm thấy kết quả</span>
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
                          form_chat_thai.ctnh.khoi_luong_phat_sinh_theo_dk_ctnh
                        "
                        placeholder="Khối lượng phát sinh theo đăng ký"
                      ></ejs-numerictextbox>
                    </div>
                    <div class="col-lg-2 col-md-5">
                      <div class="input-group">
                        <multiselect
                          v-model="form_chat_thai.ctnh.don_vi_ctnhdk"
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
                          <span slot="noResult">Không tìm thấy kết quả</span>
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
                        v-model="form_chat_thai.ctnh.thanh_phan_chinh_ctnh"
                        placeholder="Thành phần chính"
                      />
                    </div>
                  </div>
                  <br />
                  <div class="row">
                    <div
                      class="col-lg-2 col-md-3"
                      v-if="!form_chat_thai.ctnh.thue_xu_ly_ctnh"
                    >
                      <checkbox-component
                        v-model="form_chat_thai.ctnh.tu_xu_ly_ctnh"
                        type="checkbox"
                        :id="'tuxuly' + index1"
                        text="Tự xử lý"
                      ></checkbox-component>
                    </div>
                    <div
                      class="col-lg-2 col-md-3"
                      v-if="!form_chat_thai.ctnh.tu_xu_ly_ctnh"
                    >
                      <checkbox-component
                        v-model="form_chat_thai.ctnh.thue_xu_ly_ctnh"
                        type="checkbox"
                        :id="'thuexuly' + index1"
                        text="Thuê xử lý"
                      ></checkbox-component>
                    </div>
                  </div>
                  <div class="row">
                    <div
                      class="col-lg-3 col-md-10"
                      v-if="form_chat_thai.ctnh.thue_xu_ly_ctnh"
                    >
                      <input
                        type="text"
                        class="form-control"
                        v-model="form_chat_thai.ctnh.co_quan_thue_xu_ly_ctnh"
                        placeholder="Đơn vị xử lý"
                      />
                    </div>
                    <div class="col-lg-1 col-md-2">
                      <button
                        type="button"
                        class="btn btn-flat pull-right btn-block"
                        style="height: 40px"
                        @click="addchatthainguyhai()"
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
                          Khối lượng phát sinh thực tế
                          <span
                            style="color: rgb(216, 27, 96)"
                            v-text="'(' + ten_don_vi_ctrsh + ')'"
                          ></span>
                        </th>
                        <th>
                          Khối lượng phát sinh theo đăng ký
                          <span
                            style="color: rgb(216, 27, 96)"
                            v-text="'(' + ten_don_vi_ctrsh + ')'"
                          ></span>
                        </th>
                        <th>Thành phần chính</th>
                        <th>Tự xử lý</th>
                        <th>Thuê xử lý</th>
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
                    </thead>
                    <tbody>
                      <tr
                        v-for="(
                          item, index
                        ) in co_so_san_xuat.ket_qua_thanh_tra_chat_thai_nguy_hai"
                        :key="index"
                      >
                        <td>
                          {{ item.khoi_luong_phat_sinh_thuc_te | f2Number }}
                        </td>
                        <td>
                          {{
                            item.khoi_luong_phat_sinh_theo_so_dang_ki | f2Number
                          }}
                        </td>
                        <td>{{ item.thanh_phan_chinh }}</td>
                        <td>
                          <i
                            class="fa fa-check-square-o"
                            v-if="item.tu_xu_ly"
                          ></i>
                          <i class="fa fa-square-o" v-else></i>
                        </td>
                        <td>
                          <i
                            class="fa fa-check-square-o"
                            v-if="item.thue_xu_ly"
                          ></i>
                          <i class="fa fa-square-o" v-else></i>
                          {{ item.co_quan_thue_xu_ly }}
                        </td>
                        <td
                          align="center"
                          v-if="
                            usersystem.role_code == 'admin' ||
                            usersystem.role_code == 'nhanvientrungtam' ||
                            usersystem.role_code == 'adminvaquanlydanhmuc'
                          "
                        >
                          <a @click="deleteCtnh(index)">
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
</template>

<script>
import Multiselect from "vue-multiselect";
import moment from "../../../../../node_modules/admin-lte/bower_components/moment/moment.js";
import PhieuThuNghiem from "../../components/PhieuThuNghiem.vue";
import datePicker from "vue-bootstrap-datetimepicker";
import KetQuaQuanTracEdit from "../KetQuaQuanTracEdit.vue";

Vue.filter("formatDate", function (value) {
  if (value) {
    return moment(String(value)).format("DD/MM/YYYY");
  }
});
export default {
  components: { Multiselect, datePicker, PhieuThuNghiem, KetQuaQuanTracEdit },
  props: {
    index1: {},
    usersystem: {},
    value: { type: Object, default: () => ({}) },
    donvikhoiluongphatsinhs: { type: Array, default: () => [] },
    ten_don_vi_ctrsh: String,
    skipInitFromApi: { type: Boolean, default: false },
  },
  data: () => ({
    isCollapse1Visible: false,
    isCollapse2Visible: false,
    form_nuoc_thai: {
      phieu_thu_nghiem: {
        ten_khach_hang: "",
        dia_diem_lay_mau: "",
        dia_chi: "",
        vi_tri_quan_trac: "",
        kinh_do: "",
        vi_do: "",
        ngay_quan_trac: "",
        nguoi_quan_trac: "",
        thoi_tiet: "",
        ngay_phan_tich: "",
        ngay_ket_thuc_phan_tich: "",
        nguoi_phan_tich: "",
        attachment: null,
      },
      nuoc_thais: [],
    },
    form_khi_thai: {
      phieu_thu_nghiem: {
        ten_khach_hang: "",
        dia_diem_lay_mau: "",
        dia_chi: "",
        vi_tri_quan_trac: "",
        kinh_do: "",
        vi_do: "",
        ngay_quan_trac: "",
        nguoi_quan_trac: "",
        thoi_tiet: "",
        ngay_phan_tich: "",
        ngay_ket_thuc_phan_tich: "",
        nguoi_phan_tich: "",
        attachment: null,
      },
      khi_thais: [],
    },
    form_chat_thai: {
      ctrsh: {
        phieu_thu_nghiem: {
          ten_khach_hang: "",
          dia_diem_lay_mau: "",
          dia_chi: "",
          vi_tri_quan_trac: "",
          kinh_do: "",
          vi_do: "",
          ngay_quan_trac: "",
          nguoi_quan_trac: "",
          thoi_tiet: "",
          ngay_phan_tich: "",
          ngay_ket_thuc_phan_tich: "",
          nguoi_phan_tich: "",
        },
        ctrsh: [],
      },
      ctrcntt: {
        phieu_thu_nghiem: {
          ten_khach_hang: "",
          dia_diem_lay_mau: "",
          dia_chi: "",
          vi_tri_quan_trac: "",
          kinh_do: "",
          vi_do: "",
          ngay_quan_trac: "",
          nguoi_quan_trac: "",
          thoi_tiet: "",
          ngay_phan_tich: "",
          ngay_ket_thuc_phan_tich: "",
          nguoi_phan_tich: "",
        },
        ctrcntt: [],
      },
      ctnh: {
        phieu_thu_nghiem: {
          ten_khach_hang: "",
          dia_diem_lay_mau: "",
          dia_chi: "",
          vi_tri_quan_trac: "",
          kinh_do: "",
          vi_do: "",
          ngay_quan_trac: "",
          nguoi_quan_trac: "",
          thoi_tiet: "",
          ngay_phan_tich: "",
          ngay_ket_thuc_phan_tich: "",
          nguoi_phan_tich: "",
        },
        ctnh: [],
      },
    },
    optionLoaiHinhQuanTracs: [
      {
        id: "dinh_ky",
        ten: "Định kỳ",
      },
      {
        id: "tu_dong",
        ten: "Tự động",
      },
    ],
  }),
  optionsDate2: {
    format: "YYYY-MM-DD",
    useCurrent: false,
    locale: "vi",
  },
  mounted() {
    // if (!this.skipInitFromApi) {
    this.initFromSource();
    // }
    // nuoc thai
    this.form_nuoc_thai.phieu_thu_nghiem.ten_khach_hang =
      this.value.ket_qua_thanh_tra_nuoc_thais[0].phieu_thu_nghiem.ten_khach_hang;
    this.form_nuoc_thai.phieu_thu_nghiem.dia_diem_lay_mau =
      this.value.ket_qua_thanh_tra_nuoc_thais[0].phieu_thu_nghiem.dia_diem_lay_mau;
    this.form_nuoc_thai.phieu_thu_nghiem.dia_chi =
      this.value.ket_qua_thanh_tra_nuoc_thais[0].phieu_thu_nghiem.dia_chi;
    this.form_nuoc_thai.phieu_thu_nghiem.vi_tri_quan_trac =
      this.value.ket_qua_thanh_tra_nuoc_thais[0].phieu_thu_nghiem.vi_tri_quan_trac;
    this.form_nuoc_thai.phieu_thu_nghiem.kinh_do =
      this.value.ket_qua_thanh_tra_nuoc_thais[0].phieu_thu_nghiem.kinh_do;
    this.form_nuoc_thai.phieu_thu_nghiem.vi_do =
      this.value.ket_qua_thanh_tra_nuoc_thais[0].phieu_thu_nghiem.vi_do;
    this.form_nuoc_thai.phieu_thu_nghiem.ngay_quan_trac = moment(
      this.value.ket_qua_thanh_tra_nuoc_thais[0].phieu_thu_nghiem.ngay_lay_mau
    ).toDate();
    this.form_nuoc_thai.phieu_thu_nghiem.nguoi_quan_trac =
      this.value.ket_qua_thanh_tra_nuoc_thais[0].phieu_thu_nghiem.nguoi_lay_mau;
    this.form_nuoc_thai.phieu_thu_nghiem.thoi_tiet =
      this.value.ket_qua_thanh_tra_nuoc_thais[0].phieu_thu_nghiem.thoi_tiet;
    this.form_nuoc_thai.phieu_thu_nghiem.ngay_phan_tich = moment(
      this.value.ket_qua_thanh_tra_nuoc_thais[0].phieu_thu_nghiem
        .ngay_phan_tich_start
    ).toDate();
    this.form_nuoc_thai.phieu_thu_nghiem.ngay_ket_thuc_phan_tich = moment(
      this.value.ket_qua_thanh_tra_nuoc_thais[0].phieu_thu_nghiem
        .ngay_phan_tich_end
    ).toDate();
    this.form_nuoc_thai.phieu_thu_nghiem.nguoi_phan_tich =
      this.value.ket_qua_thanh_tra_nuoc_thais[0].phieu_thu_nghiem.nguoi_phan_tich;
    this.form_nuoc_thai.phieu_thu_nghiem.id =
      this.value.ket_qua_thanh_tra_nuoc_thais[0].phieu_thu_nghiem.id;
    this.form_nuoc_thai.phieu_thu_nghiem.attachment =
      this.value.ket_qua_thanh_tra_nuoc_thais[0].phieu_thu_nghiem.attachment ||
      null;

    // khi thai
    this.form_khi_thai.phieu_thu_nghiem.ten_khach_hang =
      this.value.ket_qua_thanh_tra_khi_thais[0].phieu_thu_nghiem.ten_khach_hang;
    this.form_khi_thai.phieu_thu_nghiem.dia_diem_lay_mau =
      this.value.ket_qua_thanh_tra_khi_thais[0].phieu_thu_nghiem.dia_diem_lay_mau;
    this.form_khi_thai.phieu_thu_nghiem.dia_chi =
      this.value.ket_qua_thanh_tra_khi_thais[0].phieu_thu_nghiem.dia_chi;
    this.form_khi_thai.phieu_thu_nghiem.vi_tri_quan_trac =
      this.value.ket_qua_thanh_tra_khi_thais[0].phieu_thu_nghiem.vi_tri_quan_trac;
    this.form_khi_thai.phieu_thu_nghiem.kinh_do =
      this.value.ket_qua_thanh_tra_khi_thais[0].phieu_thu_nghiem.kinh_do;
    this.form_khi_thai.phieu_thu_nghiem.vi_do =
      this.value.ket_qua_thanh_tra_khi_thais[0].phieu_thu_nghiem.vi_do;
    this.form_khi_thai.phieu_thu_nghiem.ngay_quan_trac = moment(
      this.value.ket_qua_thanh_tra_khi_thais[0].phieu_thu_nghiem.ngay_lay_mau
    ).toDate();
    this.form_khi_thai.phieu_thu_nghiem.nguoi_quan_trac =
      this.value.ket_qua_thanh_tra_khi_thais[0].phieu_thu_nghiem.nguoi_lay_mau;
    this.form_khi_thai.phieu_thu_nghiem.thoi_tiet =
      this.value.ket_qua_thanh_tra_khi_thais[0].phieu_thu_nghiem.thoi_tiet;
    this.form_khi_thai.phieu_thu_nghiem.ngay_phan_tich = moment(
      this.value.ket_qua_thanh_tra_khi_thais[0].phieu_thu_nghiem
        .ngay_phan_tich_start
    ).toDate();
    this.form_khi_thai.phieu_thu_nghiem.ngay_ket_thuc_phan_tich = moment(
      this.value.ket_qua_thanh_tra_khi_thais[0].phieu_thu_nghiem
        .ngay_phan_tich_end
    ).toDate();
    this.form_khi_thai.phieu_thu_nghiem.nguoi_phan_tich =
      this.value.ket_qua_thanh_tra_khi_thais[0].phieu_thu_nghiem.nguoi_phan_tich;
    this.form_khi_thai.phieu_thu_nghiem.id =
      this.value.ket_qua_thanh_tra_khi_thais[0].phieu_thu_nghiem.id;
    this.form_khi_thai.phieu_thu_nghiem.attachment =
      this.value.ket_qua_thanh_tra_khi_thais[0].phieu_thu_nghiem.attachment ||
      null;

    // ctrsh
    this.form_chat_thai.ctrsh.phieu_thu_nghiem.ten_khach_hang =
      this.value.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats[0].phieu_thu_nghiem.ten_khach_hang;
    this.form_chat_thai.ctrsh.phieu_thu_nghiem.dia_diem_lay_mau =
      this.value.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats[0].phieu_thu_nghiem.dia_diem_lay_mau;
    this.form_chat_thai.ctrsh.phieu_thu_nghiem.dia_chi =
      this.value.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats[0].phieu_thu_nghiem.dia_chi;
    this.form_chat_thai.ctrsh.phieu_thu_nghiem.vi_tri_quan_trac =
      this.value.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats[0].phieu_thu_nghiem.vi_tri_quan_trac;
    this.form_chat_thai.ctrsh.phieu_thu_nghiem.kinh_do =
      this.value.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats[0].phieu_thu_nghiem.kinh_do;
    this.form_chat_thai.ctrsh.phieu_thu_nghiem.vi_do =
      this.value.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats[0].phieu_thu_nghiem.vi_do;
    this.form_chat_thai.ctrsh.phieu_thu_nghiem.ngay_quan_trac = moment(
      this.value.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats[0].phieu_thu_nghiem
        .ngay_lay_mau
    ).toDate();
    this.form_chat_thai.ctrsh.phieu_thu_nghiem.nguoi_quan_trac =
      this.value.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats[0].phieu_thu_nghiem.nguoi_lay_mau;
    this.form_chat_thai.ctrsh.phieu_thu_nghiem.thoi_tiet =
      this.value.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats[0].phieu_thu_nghiem.thoi_tiet;
    this.form_chat_thai.ctrsh.phieu_thu_nghiem.ngay_phan_tich = moment(
      this.value.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats[0].phieu_thu_nghiem
        .ngay_phan_tich_start
    ).toDate();
    this.form_chat_thai.ctrsh.phieu_thu_nghiem.ngay_ket_thuc_phan_tich = moment(
      this.value.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats[0].phieu_thu_nghiem
        .ngay_phan_tich_end
    ).toDate();
    this.form_chat_thai.ctrsh.phieu_thu_nghiem.nguoi_phan_tich =
      this.value.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats[0].phieu_thu_nghiem.nguoi_phan_tich;
    this.form_chat_thai.ctrsh.phieu_thu_nghiem.id =
      this.value.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats[0].phieu_thu_nghiem.id;

    // ctrcntt
    this.form_chat_thai.ctrcntt.phieu_thu_nghiem.ten_khach_hang =
      this.value.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t[0].phieu_thu_nghiem.ten_khach_hang;
    this.form_chat_thai.ctrcntt.phieu_thu_nghiem.dia_diem_lay_mau =
      this.value.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t[0].phieu_thu_nghiem.dia_diem_lay_mau;
    this.form_chat_thai.ctrcntt.phieu_thu_nghiem.dia_chi =
      this.value.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t[0].phieu_thu_nghiem.dia_chi;
    this.form_chat_thai.ctrcntt.phieu_thu_nghiem.vi_tri_quan_trac =
      this.value.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t[0].phieu_thu_nghiem.vi_tri_quan_trac;
    this.form_chat_thai.ctrcntt.phieu_thu_nghiem.kinh_do =
      this.value.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t[0].phieu_thu_nghiem.kinh_do;
    this.form_chat_thai.ctrcntt.phieu_thu_nghiem.vi_do =
      this.value.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t[0].phieu_thu_nghiem.vi_do;
    this.form_chat_thai.ctrcntt.phieu_thu_nghiem.ngay_quan_trac = moment(
      this.value.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t[0].phieu_thu_nghiem
        .ngay_lay_mau
    ).toDate();
    this.form_chat_thai.ctrcntt.phieu_thu_nghiem.nguoi_quan_trac =
      this.value.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t[0].phieu_thu_nghiem.nguoi_lay_mau;
    this.form_chat_thai.ctrcntt.phieu_thu_nghiem.thoi_tiet =
      this.value.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t[0].phieu_thu_nghiem.thoi_tiet;
    this.form_chat_thai.ctrcntt.phieu_thu_nghiem.ngay_phan_tich = moment(
      this.value.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t[0].phieu_thu_nghiem
        .ngay_phan_tich_start
    ).toDate();
    this.form_chat_thai.ctrcntt.phieu_thu_nghiem.ngay_ket_thuc_phan_tich =
      moment(
        this.value.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t[0].phieu_thu_nghiem
          .ngay_phan_tich_end
      ).toDate();
    this.form_chat_thai.ctrcntt.phieu_thu_nghiem.nguoi_phan_tich =
      this.value.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t[0].phieu_thu_nghiem.nguoi_phan_tich;
    this.form_chat_thai.ctrcntt.phieu_thu_nghiem.id =
      this.value.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t[0].phieu_thu_nghiem.id;

    // ctnh
    this.form_chat_thai.ctnh.phieu_thu_nghiem.ten_khach_hang =
      this.value.ket_qua_thanh_tra_chat_thai_nguy_hai[0].phieu_thu_nghiem.ten_khach_hang;
    this.form_chat_thai.ctnh.phieu_thu_nghiem.dia_diem_lay_mau =
      this.value.ket_qua_thanh_tra_chat_thai_nguy_hai[0].phieu_thu_nghiem.dia_diem_lay_mau;
    this.form_chat_thai.ctnh.phieu_thu_nghiem.dia_chi =
      this.value.ket_qua_thanh_tra_chat_thai_nguy_hai[0].phieu_thu_nghiem.dia_chi;
    this.form_chat_thai.ctnh.phieu_thu_nghiem.vi_tri_quan_trac =
      this.value.ket_qua_thanh_tra_chat_thai_nguy_hai[0].phieu_thu_nghiem.vi_tri_quan_trac;
    this.form_chat_thai.ctnh.phieu_thu_nghiem.kinh_do =
      this.value.ket_qua_thanh_tra_chat_thai_nguy_hai[0].phieu_thu_nghiem.kinh_do;
    this.form_chat_thai.ctnh.phieu_thu_nghiem.vi_do =
      this.value.ket_qua_thanh_tra_chat_thai_nguy_hai[0].phieu_thu_nghiem.vi_do;
    this.form_chat_thai.ctnh.phieu_thu_nghiem.ngay_quan_trac = moment(
      this.value.ket_qua_thanh_tra_chat_thai_nguy_hai[0].phieu_thu_nghiem
        .ngay_lay_mau
    ).toDate();
    this.form_chat_thai.ctnh.phieu_thu_nghiem.nguoi_quan_trac =
      this.value.ket_qua_thanh_tra_chat_thai_nguy_hai[0].phieu_thu_nghiem.nguoi_lay_mau;
    this.form_chat_thai.ctnh.phieu_thu_nghiem.thoi_tiet =
      this.value.ket_qua_thanh_tra_chat_thai_nguy_hai[0].phieu_thu_nghiem.thoi_tiet;
    this.form_chat_thai.ctnh.phieu_thu_nghiem.ngay_phan_tich = moment(
      this.value.ket_qua_thanh_tra_chat_thai_nguy_hai[0].phieu_thu_nghiem
        .ngay_phan_tich_start
    ).toDate();
    this.form_chat_thai.ctnh.phieu_thu_nghiem.ngay_ket_thuc_phan_tich = moment(
      this.value.ket_qua_thanh_tra_chat_thai_nguy_hai[0].phieu_thu_nghiem
        .ngay_phan_tich_end
    ).toDate();
    this.form_chat_thai.ctnh.phieu_thu_nghiem.nguoi_phan_tich =
      this.value.ket_qua_thanh_tra_chat_thai_nguy_hai[0].phieu_thu_nghiem.nguoi_phan_tich;
    this.form_chat_thai.ctnh.phieu_thu_nghiem.id =
      this.value.ket_qua_thanh_tra_chat_thai_nguy_hai[0].phieu_thu_nghiem.id;
  },
  computed: {
    loaiChatThai() {
      return this.value.loai_moi_truong || "chat_thai_nuoc_thai";
    },
    co_so_san_xuat: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("input", value);
      },
    },
    canEdit: function () {
      if (this.usersystem)
        return (
          this.usersystem.role_code == "admin" ||
          this.usersystem.role_code == "nhanvientrungtam" ||
          this.usersystem.role_code == "adminvaquanlydanhmuc"
        );
      else return false;
    },
  },
  methods: {
    initFromSource() {
      const x = this.value;
      if (!Array.isArray(x.ket_qua_quan_trac)) return;

      const grouped = {};

      x.ket_qua_quan_trac.forEach((row) => {
        const type = row.loai_moi_truong;

        if (!grouped[type]) {
          grouped[type] = {
            loai_moi_truong: type,
            mau_moi_truong: [],
          };
        }

        // Tạo khóa gom theo mẫu
        const key =
          (row.dia_diem || "") +
          "__" +
          (row.vi_tri || "") +
          "__" +
          (row.loai_mau || "") +
          "__" +
          (row.kinh_do || "") +
          "__" +
          (row.vi_do || "");

        let mau = grouped[type].mau_moi_truong.find((m) => m._key === key);

        // Nếu chưa có mẫu thì tạo mẫu mới
        if (!mau) {
          mau = {
            _key: key,
            dia_diem: row.dia_diem,
            vi_tri: row.vi_tri,
            loai_mau: row.loai_mau,
            kinh_do: row.kinh_do,
            vi_do: row.vi_do,
            attachment: row.attachment || null,
            thong_sos: [],
          };
          grouped[type].mau_moi_truong.push(mau);
        }

        // Push thông số vào mẫu
        mau.thong_sos.push({
          thong_so: row.thong_so,
          don_vi_tinh: row.don_vi_tinh,
          phuong_phap_phan_tich: row.phuong_phap_phan_tich,
          ket_qua: row.ket_qua,
          gia_tri_gioi_han: row.gia_tri_gioi_han,
          so_lan_vuot: row.so_lan_vuot,
        });
      });

      // Xóa _key khi emit ra form
      Object.values(grouped).forEach((type) =>
        type.mau_moi_truong.forEach((m) => delete m._key)
      );

      // Gán lại dữ liệu đã gom đúng chuẩn
      this.value.ket_qua_quan_trac = Object.values(grouped);
    },

    emitFormNuocThai() {
      this.$emit("updateFormNuocThai", this.form_nuoc_thai);
    },
    emitFormKhiThai() {
      this.$emit("updateFormKhiThai", this.form_khi_thai);
    },
    emitFormCtrsh() {
      this.$emit("updateFormCtrsh", this.form_chat_thai.ctrsh);
    },
    emitFormCtrCntt() {
      this.$emit("updateFormCtrCntt", this.form_chat_thai.ctrcntt);
    },
    emitFormCtnh() {
      this.$emit("updateFormCtnh", this.form_chat_thai.ctnh);
    },
    toggleCollapse1() {
      this.isCollapse1Visible = !this.isCollapse1Visible;
      // this.isCollapse2Visible = false;
    },
    toggleCollapse2() {
      this.isCollapse2Visible = !this.isCollapse2Visible;
      // this.isCollapse1Visible = false;
    },
    addNuocthai() {
      console.log({
        "form_nuoc_thai: ": this.form_nuoc_thai,
        ket_qua_thanh_tra_nuoc_thais:
          this.co_so_san_xuat.ket_qua_thanh_tra_nuoc_thais,
      });
      this.co_so_san_xuat.ket_qua_thanh_tra_nuoc_thais.push({
        luu_luong: this.form_nuoc_thai.luu_luong_nt || 0,
        thanh_phan: this.form_nuoc_thai.thanh_phan_nt,
        cong_suat_he_thong_xu_ly: this.form_nuoc_thai.cong_suat_xl_nt || 0,
        nguon_tiep_nhan: this.form_nuoc_thai.nguon_tiep_nhan_nt,
        thong_so_nuoc_thai_vuot_chuan: this.form_nuoc_thai.thong_so_vuot_nt,
        loai_hinh_quan_trac: this.form_nuoc_thai.loai_hinh_quan_trac,
      });
      // this.form_nuoc_thai = {};
      this.form_nuoc_thai = {
        phieu_thu_nghiem: {
          ten_khach_hang: this.form_nuoc_thai.phieu_thu_nghiem.ten_khach_hang,
          dia_diem_lay_mau:
            this.form_nuoc_thai.phieu_thu_nghiem.dia_diem_lay_mau,
          dia_chi: this.form_nuoc_thai.phieu_thu_nghiem.dia_chi,
          vi_tri_quan_trac:
            this.form_nuoc_thai.phieu_thu_nghiem.vi_tri_quan_trac,
          kinh_do: this.form_nuoc_thai.phieu_thu_nghiem.kinh_do,
          vi_do: this.form_nuoc_thai.phieu_thu_nghiem.vi_do,
          ngay_quan_trac: this.form_nuoc_thai.phieu_thu_nghiem.ngay_quan_trac,
          nguoi_quan_trac: this.form_nuoc_thai.phieu_thu_nghiem.nguoi_quan_trac,
          thoi_tiet: this.form_nuoc_thai.phieu_thu_nghiem.thoi_tiet,
          ngay_phan_tich: this.form_nuoc_thai.phieu_thu_nghiem.ngay_phan_tich,
          ngay_ket_thuc_phan_tich:
            this.form_nuoc_thai.phieu_thu_nghiem.ngay_ket_thuc_phan_tich,
          nguoi_phan_tich: this.form_nuoc_thai.phieu_thu_nghiem.nguoi_phan_tich,
          attachment: this.form_nuoc_thai.phieu_thu_nghiem.attachment,
        },
        nuoc_thais: [],
      };
    },
    deleteNuocthai(index) {
      this.co_so_san_xuat.ket_qua_thanh_tra_nuoc_thais.splice(index, 1);
    },
    addKhithai() {
      this.co_so_san_xuat.ket_qua_thanh_tra_khi_thais.push({
        luu_luong: this.form_khi_thai.luu_luong_kt || 0,
        thanh_phan: this.form_khi_thai.thanh_phan_kt,
        cong_suat_he_thong_xu_ly: this.form_khi_thai.cong_suat_xl_kt || 0,
        // nguon_tiep_nhan: this.form_khi_thai.nguon_tiep_nhan_kt,
        thong_so_khi_thai_vuot_chuan: this.form_khi_thai.thong_so_vuot_kt,
        quy_trinh_xu_ly: this.form_khi_thai.quy_trinh_xu_ly,
        loai_hinh_quan_trac: this.form_khi_thai.loai_hinh_quan_trac,
      });
      // this.form_khi_thai = {};
      this.form_khi_thai = {
        phieu_thu_nghiem: {
          ten_khach_hang: this.form_khi_thai.phieu_thu_nghiem.ten_khach_hang,
          dia_diem_lay_mau:
            this.form_khi_thai.phieu_thu_nghiem.dia_diem_lay_mau,
          dia_chi: this.form_khi_thai.phieu_thu_nghiem.dia_chi,
          vi_tri_quan_trac:
            this.form_khi_thai.phieu_thu_nghiem.vi_tri_quan_trac,
          kinh_do: this.form_khi_thai.phieu_thu_nghiem.kinh_do,
          vi_do: this.form_khi_thai.phieu_thu_nghiem.vi_do,
          ngay_quan_trac: this.form_khi_thai.phieu_thu_nghiem.ngay_quan_trac,
          nguoi_quan_trac: this.form_khi_thai.phieu_thu_nghiem.nguoi_quan_trac,
          thoi_tiet: this.form_khi_thai.phieu_thu_nghiem.thoi_tiet,
          ngay_phan_tich: this.form_khi_thai.phieu_thu_nghiem.ngay_phan_tich,
          ngay_ket_thuc_phan_tich:
            this.form_khi_thai.phieu_thu_nghiem.ngay_ket_thuc_phan_tich,
          nguoi_phan_tich: this.form_khi_thai.phieu_thu_nghiem.nguoi_phan_tich,
          attachment: this.form_khi_thai.phieu_thu_nghiem.attachment,
        },
        khi_thais: [],
      };
    },
    deleteKhithai(index) {
      this.co_so_san_xuat.ket_qua_thanh_tra_khi_thais.splice(index, 1);
    },
    addCtrsh() {
      var ty_le = 1;
      if (this.form_chat_thai.ctrsh.don_vi_ctrsh) {
        this.donvikhoiluongphatsinhs.forEach((element) => {
          if (element.id == this.form_chat_thai.ctrsh.don_vi_ctrsh.id) {
            ty_le = element.ty_le;
          }
        });
      }

      this.form_chat_thai.ctrsh.khoi_luong_phat_sinh_ctrsh =
        this.form_chat_thai.ctrsh.khoi_luong_phat_sinh_ctrsh * ty_le || 0;
      this.co_so_san_xuat.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats.push({
        khoi_luong_phat_sinh:
          this.form_chat_thai.ctrsh.khoi_luong_phat_sinh_ctrsh,
        thanh_phan_chinh: this.form_chat_thai.ctrsh.thanh_phan_chinh_ctrsh,
        don_vi_ctrsh: this.form_chat_thai.ctrsh.don_vi_ctrsh,
        co_quan_thue_xu_ly: this.form_chat_thai.ctrsh.co_quan_thue_xu_ly_ctrsh,
        tu_xu_ly: this.form_chat_thai.ctrsh.tu_xu_ly_ctrsh,
        thue_xu_ly: this.form_chat_thai.ctrsh.thue_xu_ly_ctrsh,
      });
      this.form_chat_thai.ctrsh = {
        phieu_thu_nghiem: {
          ten_khach_hang:
            this.form_chat_thai.ctrsh.phieu_thu_nghiem.ten_khach_hang,
          dia_diem_lay_mau:
            this.form_chat_thai.ctrsh.phieu_thu_nghiem.dia_diem_lay_mau,
          dia_chi: this.form_chat_thai.ctrsh.phieu_thu_nghiem.dia_chi,
          vi_tri_quan_trac:
            this.form_chat_thai.ctrsh.phieu_thu_nghiem.vi_tri_quan_trac,
          kinh_do: this.form_chat_thai.ctrsh.phieu_thu_nghiem.kinh_do,
          vi_do: this.form_chat_thai.ctrsh.phieu_thu_nghiem.vi_do,
          ngay_quan_trac:
            this.form_chat_thai.ctrsh.phieu_thu_nghiem.ngay_quan_trac,
          nguoi_quan_trac:
            this.form_chat_thai.ctrsh.phieu_thu_nghiem.nguoi_quan_trac,
          thoi_tiet: this.form_chat_thai.ctrsh.phieu_thu_nghiem.thoi_tiet,
          ngay_phan_tich:
            this.form_chat_thai.ctrsh.phieu_thu_nghiem.ngay_phan_tich,
          ngay_ket_thuc_phan_tich:
            this.form_chat_thai.ctrsh.phieu_thu_nghiem.ngay_ket_thuc_phan_tich,
          nguoi_phan_tich:
            this.form_chat_thai.ctrsh.phieu_thu_nghiem.nguoi_phan_tich,
        },
        ctrsh: [],
      };
    },
    deleteCtrsh(index) {
      this.co_so_san_xuat.ket_qua_thanh_tra_chat_thai_ran_sinh_hoats.splice(
        index,
        1
      );
    },
    addCtrcntt() {
      var ty_le = 1;
      if (this.form_chat_thai.ctrcntt.don_vi_ctrcntt) {
        this.donvikhoiluongphatsinhs.forEach((element) => {
          if (element.id == this.form_chat_thai.ctrcntt.don_vi_ctrcntt.id) {
            ty_le = element.ty_le;
          }
        });
      }

      this.form_chat_thai.ctrcntt.khoi_luong_phat_sinh_ctrcntt = (
        this.form_chat_thai.ctrcntt.khoi_luong_phat_sinh_ctrcntt * ty_le
      ).toFixed(5);
      this.co_so_san_xuat.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t.push({
        khoi_luong_phat_sinh:
          this.form_chat_thai.ctrcntt.khoi_luong_phat_sinh_ctrcntt,
        thanh_phan_chinh: this.form_chat_thai.ctrcntt.thanh_phan_chinh_ctrcntt,
        tu_xu_ly: this.form_chat_thai.ctrcntt.tu_xu_ly_ctrcntt,
        thue_xu_ly: this.form_chat_thai.ctrcntt.thue_xu_ly_ctrcntt,
        co_quan_thue_xu_ly:
          this.form_chat_thai.ctrcntt.co_quan_thue_xu_ly_ctrcntt,
      });

      // this.form_chat_thai.ctrcntt = {};
      this.form_chat_thai.ctrcntt = {
        phieu_thu_nghiem: {
          ten_khach_hang:
            this.form_chat_thai.ctrcntt.phieu_thu_nghiem.ten_khach_hang,
          dia_diem_lay_mau:
            this.form_chat_thai.ctrcntt.phieu_thu_nghiem.dia_diem_lay_mau,
          dia_chi: this.form_chat_thai.ctrcntt.phieu_thu_nghiem.dia_chi,
          vi_tri_quan_trac:
            this.form_chat_thai.ctrcntt.phieu_thu_nghiem.vi_tri_quan_trac,
          kinh_do: this.form_chat_thai.ctrcntt.phieu_thu_nghiem.kinh_do,
          vi_do: this.form_chat_thai.ctrcntt.phieu_thu_nghiem.vi_do,
          ngay_quan_trac:
            this.form_chat_thai.ctrcntt.phieu_thu_nghiem.ngay_quan_trac,
          nguoi_quan_trac:
            this.form_chat_thai.ctrcntt.phieu_thu_nghiem.nguoi_quan_trac,
          thoi_tiet: this.form_chat_thai.ctrcntt.phieu_thu_nghiem.thoi_tiet,
          ngay_phan_tich:
            this.form_chat_thai.ctrcntt.phieu_thu_nghiem.ngay_phan_tich,
          ngay_ket_thuc_phan_tich:
            this.form_chat_thai.ctrcntt.phieu_thu_nghiem
              .ngay_ket_thuc_phan_tich,
          nguoi_phan_tich:
            this.form_chat_thai.ctrcntt.phieu_thu_nghiem.nguoi_phan_tich,
        },
        ctrcntt: [],
      };
    },
    deleteCtrcntt(index) {
      this.co_so_san_xuat.ket_qua_thanh_tra_chat_thai_ran_c_n_t_t.splice(
        index,
        1
      );
    },
    refreshDVCTNH() {
      this.$emit("click:refreshDVCTNH");
    },
    addchatthainguyhai() {
      var ty_le = 1;
      if (this.form_chat_thai.ctnh.don_vi_ctnh) {
        this.donvikhoiluongphatsinhs.forEach((element) => {
          if (element.id == this.form_chat_thai.ctnh.don_vi_ctnh.id) {
            ty_le = element.ty_le;
          }
        });
      }
      this.form_chat_thai.ctnh.khoi_luong_phat_sinh_thuc_te_ctnh =
        this.form_chat_thai.ctnh.khoi_luong_phat_sinh_thuc_te_ctnh * ty_le;
      var ty_le2 = 1;
      if (this.form_chat_thai.ctnh.don_vi_ctnhdk) {
        this.donvikhoiluongphatsinhs.forEach((element) => {
          if (element.id == this.form_chat_thai.ctnh.don_vi_ctnhdk.id) {
            ty_le2 = element.ty_le;
          }
        });
      }
      this.form_chat_thai.ctnh.khoi_luong_phat_sinh_theo_dk_ctnh =
        this.form_chat_thai.ctnh.khoi_luong_phat_sinh_theo_dk_ctnh * ty_le2;
      this.co_so_san_xuat.ket_qua_thanh_tra_chat_thai_nguy_hai.push({
        khoi_luong_phat_sinh_thuc_te_ctnh:
          this.form_chat_thai.ctnh.khoi_luong_phat_sinh_thuc_te_ctnh,
        khoi_luong_phat_sinh_theo_dk_ctnh:
          this.form_chat_thai.ctnh.khoi_luong_phat_sinh_theo_dk_ctnh,
        khoi_luong_phat_sinh_thuc_te:
          this.form_chat_thai.ctnh.khoi_luong_phat_sinh_thuc_te_ctnh,
        khoi_luong_phat_sinh_theo_so_dang_ki:
          this.form_chat_thai.ctnh.khoi_luong_phat_sinh_theo_dk_ctnh,
        thanh_phan_chinh: this.form_chat_thai.ctnh.thanh_phan_chinh_ctnh,
        tu_xu_ly: this.form_chat_thai.ctnh.tu_xu_ly_ctnh,
        thue_xu_ly: this.form_chat_thai.ctnh.thue_xu_ly_ctnh,
        co_quan_thue_xu_ly: this.form_chat_thai.ctnh.co_quan_thue_xu_ly_ctnh,
      });
      // this.form_chat_thai.ctnh = {};
      this.form_chat_thai.ctnh = {
        phieu_thu_nghiem: {
          ten_khach_hang:
            this.form_chat_thai.ctnh.phieu_thu_nghiem.ten_khach_hang,
          dia_diem_lay_mau:
            this.form_chat_thai.ctnh.phieu_thu_nghiem.dia_diem_lay_mau,
          dia_chi: this.form_chat_thai.ctnh.phieu_thu_nghiem.dia_chi,
          vi_tri_quan_trac:
            this.form_chat_thai.ctnh.phieu_thu_nghiem.vi_tri_quan_trac,
          kinh_do: this.form_chat_thai.ctnh.phieu_thu_nghiem.kinh_do,
          vi_do: this.form_chat_thai.ctnh.phieu_thu_nghiem.vi_do,
          ngay_quan_trac:
            this.form_chat_thai.ctnh.phieu_thu_nghiem.ngay_quan_trac,
          nguoi_quan_trac:
            this.form_chat_thai.ctnh.phieu_thu_nghiem.nguoi_quan_trac,
          thoi_tiet: this.form_chat_thai.ctnh.phieu_thu_nghiem.thoi_tiet,
          ngay_phan_tich:
            this.form_chat_thai.ctnh.phieu_thu_nghiem.ngay_phan_tich,
          ngay_ket_thuc_phan_tich:
            this.form_chat_thai.ctnh.phieu_thu_nghiem.ngay_ket_thuc_phan_tich,
          nguoi_phan_tich:
            this.form_chat_thai.ctnh.phieu_thu_nghiem.nguoi_phan_tich,
        },
        ctnh: [],
      };
    },
    deleteCtnh(index) {
      this.co_so_san_xuat.ket_qua_thanh_tra_chat_thai_nguy_hai.splice(index, 1);
    },
    refreshDVCTRSH() {
      this.$emit("click:refreshDVCTRSH");
    },
  },
  watch: {
    form_nuoc_thai: {
      deep: true,
      handler() {
        this.emitFormNuocThai();
      },
    },
    form_khi_thai: {
      deep: true,
      handler() {
        this.emitFormKhiThai();
      },
    },
    "form_chat_thai.ctrsh": {
      deep: true,
      handler() {
        this.emitFormCtrsh();
      },
    },
    "form_chat_thai.ctrcntt": {
      deep: true,
      handler() {
        this.emitFormCtrCntt();
      },
    },
    "form_chat_thai.ctnh": {
      deep: true,
      handler() {
        this.emitFormCtnh();
      },
    },
  },
};
</script>

<style scoped></style>
