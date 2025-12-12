<template>
  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active">
        <a
          href="#tab_dv_1"
          @click="loadDataTab1"
          data-toggle="tab"
          aria-expanded="true"
          >Lưu lượng nước thải</a
        >
      </li>
      <li>
        <a
          href="#tab_dv_2"
          @click="loadDataTab2"
          data-toggle="tab"
          aria-expanded="true"
          >Lưu lượng khí thải</a
        >
      </li>
      <li class="">
        <a
          href="#tab_dv_3"
          @click="loadDataTab3"
          data-toggle="tab"
          aria-expanded="false"
          >Công suất xử lý nước thải
        </a>
      </li>
      <li class="">
        <a
          href="#tab_dv_4"
          data-toggle="tab"
          @click="loadDataTab4"
          aria-expanded="false"
          >Công suất xử lý khí thải</a
        >
      </li>
      <li class="">
        <a
          href="#tab_dv_5"
          data-toggle="tab"
          @click="loadDataTab5"
          aria-expanded="false"
          >Khối lượng phát sinh chất thải</a
        >
      </li>
      <li class="">
        <a
          href="#tab_dv_6"
          data-toggle="tab"
          @click="loadDataTab6"
          aria-expanded="false"
          >Công suất hoạt động</a
        >
      </li>
      <li class="">
        <a
          href="#tab_dv_7"
          data-toggle="tab"
          @click="loadDataTab7"
          aria-expanded="false"
          >Công suất thiết kế</a
        >
      </li>
      <li class="">
        <a
          href="#tab_dv_8"
          data-toggle="tab"
          @click="loadDataTab8"
          aria-expanded="false"
          >Diện tích</a
        >
      </li>
      <li class="">
        <a
          href="#tab_dv_9"
          data-toggle="tab"
          @click="loadDataTab9"
          aria-expanded="false"
          >Đơn vị thông số vượt chuẩn</a
        >
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="tab_dv_1">
        <div class="row">
          <div class="col-sm-12">
            <table class="table table-hover">
              <tbody>
                <tr class="row-table-header">
                  <th>STT</th>
                  <th>Tên</th>
                  <th>Tỷ lệ</th>
                  <th>Mô Tả</th>
                  <th>Đơn vị gốc</th>
                  <th
                    style="width:5%"
                    v-if="
                      usersystem.role_code == 'admin' ||
                        usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  ></th>
                </tr>
                <tr v-for="(item, index) in data" :key="item.data">
                  <td>{{ index + 1 }}</td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.ten"
                      disabled
                    />
                  </td>
                  <td>
                    <input
                      class="form-control "
                      v-model="item.ty_le"
                      type="number"
                      format="n3"
                      v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      "
                    />
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="item.ty_le"
                      type="text"
                      format="n3"
                      v-else
                    ></ejs-numerictextbox>
                  </td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.mo_ta"
                      @change="
                        updateChuyenDonVi(
                          item.id,
                          item.ten,
                          item.ty_le,
                          item.mo_ta
                        )
                      "
                      v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      "
                    />
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.mo_ta"
                      v-else
                    />
                  </td>
                  <td align="center">
                    <input
                      class="truong_phong_1"
                      :checked="item.don_vi_goc"
                      :id="item.id"
                      :loai="item.loai"
                      name="truong_phong_1"
                      type="radio"
                    />
                  </td>
                  <!-- <td
                    align="center"
                    v-if="
                      usersystem.role_code == 'admin' ||
                        usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  >
                    <a @click="deleteChuyendonvi(item.id, index)"
                      ><i class="fa fa-trash-o"></i
                    ></a>
                  </td> -->
                </tr>
                <tr
                  v-if="
                    usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                  "
                >
                  <td colspan="2">
                    <input
                      type="text"
                      class="form-control  "
                      v-model="ten"
                      placeholder="Tên"
                    /><span
                      style="color: red"
                      v-text="errors_chuyendonvi"
                    ></span>
                  </td>
                  <td>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="ty_le"
                      type="text"
                      placeholder="Tỷ lệ"
                    >
                    </ejs-numerictextbox>
                    <span style="color: red" v-text="errors_chuyendonvi"></span>
                  </td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="mo_ta"
                      placeholder="Mô tả"
                    /><span
                      style="color: red"
                      v-text="errors_chuyendonvi"
                    ></span>
                  </td>
                  <td align="center" colspan="2">
                    <a class="btn btn-flat" @click="addChuyenDonVi()"
                      ><i class="fa fa-plus"></i> Thêm</a
                    >
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab_dv_2">
        <div class="row">
          <div class="col-sm-12">
            <table class="table table-hover">
              <tbody>
                <tr class="row-table-header">
                  <th>STT</th>
                  <th>Tên</th>
                  <th>Tỷ lệ</th>
                  <th>Mô Tả</th>
                  <th>Đơn vị gốc</th>
                  <th
                    style="width:5%"
                    v-if="
                      usersystem.role_code == 'admin' ||
                        usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  ></th>
                </tr>
                <tr
                  v-for="(item, index) in luu_luong_khi_thais"
                  :key="item.luu_luong_khi_thais"
                >
                  <td>{{ index + 1 }}</td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.ten"
                      disabled
                    />
                  </td>
                  <td>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="item.ty_le"
                      type="text"
                      @change="
                        updateChuyenDonVi(
                          item.id,
                          item.ten,
                          item.ty_le,
                          item.mo_ta
                        )
                      "
                      v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      "
                    ></ejs-numerictextbox>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="item.ty_le"
                      type="text"
                      v-else
                    ></ejs-numerictextbox>
                  </td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.mo_ta"
                      @change="
                        updateChuyenDonVi(
                          item.id,
                          item.ten,
                          item.ty_le,
                          item.mo_ta
                        )
                      "
                      v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      "
                    />
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.mo_ta"
                      v-else
                    />
                  </td>
                  <td align="center">
                    <input
                      class="truong_phong_2"
                      :checked="item.don_vi_goc"
                      :id="item.id"
                      :loai="item.loai"
                      name="truong_phong"
                      type="radio"
                    />
                  </td>
                  <!-- <td
                    align="center"
                    v-if="
                      usersystem.role_code == 'admin' ||
                        usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  >
                    <a @click="deleteChuyendonvidv(item.id, index)"
                      ><i class="fa fa-trash-o"></i
                    ></a>
                  </td> -->
                </tr>
                <tr
                  v-if="
                    usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                  "
                >
                  <td colspan="2">
                    <input
                      type="text"
                      class="form-control  "
                      v-model="tendv"
                      placeholder="Tên"
                    /><span
                      style="color: red"
                      v-text="errors_chuyendonvidv"
                    ></span>
                  </td>
                  <td>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="ty_ledv"
                      type="text"
                      placeholder="Tỷ lệ"
                    >
                    </ejs-numerictextbox>
                    <span
                      style="color: red"
                      v-text="errors_chuyendonvidv"
                    ></span>
                  </td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="mo_tadv"
                      placeholder="Mô tả"
                    /><span
                      style="color: red"
                      v-text="errors_chuyendonvidv"
                    ></span>
                  </td>
                  <td align="center" colspan="2">
                    <a class="btn btn-flat" @click="addChuyenDonVikt()"
                      ><i class="fa fa-plus"></i> Thêm</a
                    >
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab_dv_3">
        <div class="row">
          <div class="col-sm-12">
            <table class="table table-hover">
              <tbody>
                <tr class="row-table-header">
                  <th>STT</th>
                  <th>Tên</th>
                  <th>Tỷ lệ</th>
                  <th>Mô Tả</th>
                  <th>Đơn vị gốc</th>
                  <th
                    style="width:5%"
                    v-if="
                      usersystem.role_code == 'admin' ||
                        usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  ></th>
                </tr>
                <tr
                  v-for="(item, index) in cong_xuat_xu_ly_nuoc_thais"
                  :key="item.cong_xuat_xu_ly_nuoc_thais"
                >
                  <td>{{ index + 1 }}</td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.ten"
                      disabled
                    />
                  </td>
                  <td>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="item.ty_le"
                      type="text"
                      @change="
                        updateChuyenDonVi(
                          item.id,
                          item.ten,
                          item.ty_le,
                          item.mo_ta
                        )
                      "
                      v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      "
                    ></ejs-numerictextbox>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="item.ty_le"
                      type="text"
                      v-else
                    ></ejs-numerictextbox>
                  </td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.mo_ta"
                      @change="
                        updateChuyenDonVi(
                          item.id,
                          item.ten,
                          item.ty_le,
                          item.mo_ta
                        )
                      "
                      v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      "
                    />
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.mo_ta"
                      v-else
                    />
                  </td>
                  <td align="center">
                    <input
                      class="truong_phong_3"
                      :checked="item.don_vi_goc"
                      :id="item.id"
                      :loai="item.loai"
                      name="truong_phong"
                      type="radio"
                    />
                  </td>
                  <!-- <td
                    align="center"
                    v-if="
                      usersystem.role_code == 'admin' ||
                        usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  >
                    <a @click="deleteChuyendonvicsnt(item.id, index)"
                      ><i class="fa fa-trash-o"></i
                    ></a>
                  </td> -->
                </tr>
                <tr
                  v-if="
                    usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                  "
                >
                  <td colspan="2">
                    <input
                      type="text"
                      class="form-control  "
                      v-model="tencsnt"
                      placeholder="Tên"
                    /><span
                      style="color: red"
                      v-text="errors_chuyendonvicsnt"
                    ></span>
                  </td>
                  <td>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="ty_lecsnt"
                      type="text"
                      placeholder="Tỷ lệ"
                    >
                    </ejs-numerictextbox>
                    <span
                      style="color: red"
                      v-text="errors_chuyendonvicsnt"
                    ></span>
                  </td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="mo_tacsnt"
                      placeholder="Mô tả"
                    /><span
                      style="color: red"
                      v-text="errors_chuyendonvicsnt"
                    ></span>
                  </td>
                  <td align="center" colspan="2">
                    <a class="btn btn-flat" @click="addChuyenDonVicsnt()"
                      ><i class="fa fa-plus"></i> Thêm</a
                    >
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab_dv_4">
        <div class="row">
          <div class="col-sm-12">
            <table class="table table-hover">
              <tbody>
                <tr class="row-table-header">
                  <th>STT</th>
                  <th>Tên</th>
                  <th>Tỷ lệ</th>
                  <th>Mô Tả</th>
                  <th>Đơn vị gốc</th>
                  <th
                    style="width:5%"
                    v-if="
                      usersystem.role_code == 'admin' ||
                        usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  ></th>
                </tr>
                <tr
                  v-for="(item, index) in cong_xuat_xu_ly_khi_thais"
                  :key="item.cong_xuat_xu_ly_khi_thais"
                >
                  <td>{{ index + 1 }}</td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.ten"
                      disabled
                    />
                  </td>
                  <td>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="item.ty_le"
                      type="text"
                      @change="
                        updateChuyenDonVi(
                          item.id,
                          item.ten,
                          item.ty_le,
                          item.mo_ta
                        )
                      "
                      v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      "
                    ></ejs-numerictextbox>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="item.ty_le"
                      type="text"
                      v-else
                    ></ejs-numerictextbox>
                  </td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.mo_ta"
                      @change="
                        updateChuyenDonVi(
                          item.id,
                          item.ten,
                          item.ty_le,
                          item.mo_ta
                        )
                      "
                      v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      "
                    />
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.mo_ta"
                      v-else
                    />
                  </td>
                  <td align="center">
                    <input
                      class="truong_phong_4"
                      :checked="item.don_vi_goc"
                      :id="item.id"
                      :loai="item.loai"
                      name="truong_phong"
                      type="radio"
                    />
                  </td>
                  <!-- <td
                    align="center"
                    v-if="
                      usersystem.role_code == 'admin' ||
                        usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  >
                    <a @click="deleteChuyendonvicskt(item.id, index)"
                      ><i class="fa fa-trash-o"></i
                    ></a>
                  </td> -->
                </tr>
                <tr
                  v-if="
                    usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                  "
                >
                  <td colspan="2">
                    <input
                      type="text"
                      class="form-control  "
                      v-model="tencskt"
                      placeholder="Tên"
                    /><span
                      style="color: red"
                      v-text="errors_chuyendonvicskt"
                    ></span>
                  </td>

                  <td>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="ty_lecskt"
                      type="text"
                      placeholder="Tỷ lệ"
                    >
                    </ejs-numerictextbox>
                    <span
                      style="color: red"
                      v-text="errors_chuyendonvicskt"
                    ></span>
                  </td>

                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="mo_tacskt"
                      placeholder="Mô tả"
                    /><span
                      style="color: red"
                      v-text="errors_chuyendonvicskt"
                    ></span>
                  </td>
                  <td align="center" colspan="2">
                    <a class="btn btn-flat" @click="addChuyenDonVicskt()"
                      ><i class="fa fa-plus"></i> Thêm</a
                    >
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab_dv_5">
        <div class="row">
          <div class="col-sm-12">
            <table class="table table-hover">
              <tbody>
                <tr class="row-table-header">
                  <th>STT</th>
                  <th>Tên</th>
                  <th>Tỷ lệ</th>
                  <th>Mô Tả</th>
                  <th>Đơn vị gốc</th>
                  <th
                    style="width:5%"
                    v-if="
                      usersystem.role_code == 'admin' ||
                        usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  ></th>
                </tr>
                <tr
                  v-for="(item, index) in khoi_luong_phat_sinh_chat_thais"
                  :key="item.khoi_luong_phat_sinh_chat_thais"
                >
                  <td>{{ index + 1 }}</td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.ten"
                      disabled
                    />
                  </td>
                  <td>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="item.ty_le"
                      type="text"
                      format="n4"
                      @change="
                        updateChuyenDonVi(
                          item.id,
                          item.ten,
                          item.ty_le,
                          item.mo_ta
                        )
                      "
                      v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      "
                    ></ejs-numerictextbox>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="item.ty_le"
                      type="text"
                      format="n4"
                      v-else
                    ></ejs-numerictextbox>
                  </td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.mo_ta"
                      @change="
                        updateChuyenDonVi(
                          item.id,
                          item.ten,
                          item.ty_le,
                          item.mo_ta
                        )
                      "
                      v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      "
                    />
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.mo_ta"
                      v-else
                    />
                  </td>
                  <td align="center">
                    <input
                      class="truong_phong_5"
                      :checked="item.don_vi_goc"
                      :id="item.id"
                      :loai="item.loai"
                      name="truong_phong"
                      type="radio"
                    />
                  </td>
                  <!-- <td
                    align="center"
                    v-if="
                      usersystem.role_code == 'admin' ||
                        usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  >
                    <a @click="deleteChuyendonvipsct(item.id, index)"
                      ><i class="fa fa-trash-o"></i
                    ></a>
                  </td> -->
                </tr>
                <tr
                  v-if="
                    usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                  "
                >
                  <td colspan="2">
                    <input
                      type="text"
                      class="form-control  "
                      v-model="tenpsct"
                      placeholder="Tên"
                    /><span
                      style="color: red"
                      v-text="errors_chuyendonvipsct"
                    ></span>
                  </td>

                  <td>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="ty_lepsct"
                      type="text"
                      placeholder="Tỷ lệ"
                    >
                    </ejs-numerictextbox>
                    <span
                      style="color: red"
                      v-text="errors_chuyendonvipsct"
                    ></span>
                  </td>

                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="mo_tapsct"
                      placeholder="Mô tả"
                    /><span
                      style="color: red"
                      v-text="errors_chuyendonvipsct"
                    ></span>
                  </td>
                  <td align="center" colspan="2">
                    <a class="btn btn-flat" @click="addChuyenDonVipsct()"
                      ><i class="fa fa-plus"></i> Thêm</a
                    >
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab_dv_6">
        <div class="row">
          <div class="col-sm-12">
            <table class="table table-hover">
              <tbody>
                <tr class="row-table-header">
                  <th>STT</th>
                  <th>Tên</th>
                  <th>Tỷ lệ</th>
                  <th>Mô Tả</th>
                  <th>Đơn vị gốc</th>
                  <th
                    style="width:5%"
                    v-if="
                      usersystem.role_code == 'admin' ||
                        usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  ></th>
                </tr>
                <tr
                  v-for="(item, index) in cong_suat_he_thongs"
                  :key="item.cong_suat_he_thongs"
                >
                  <td>{{ index + 1 }}</td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.ten"
                      disabled
                    />
                  </td>
                  <td>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="item.ty_le"
                      type="text"
                      @change="
                        updateChuyenDonVi(
                          item.id,
                          item.ten,
                          item.ty_le,
                          item.mo_ta
                        )
                      "
                      v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      "
                    ></ejs-numerictextbox>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="item.ty_le"
                      type="text"
                      v-else
                    ></ejs-numerictextbox>
                  </td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.mo_ta"
                      @change="
                        updateChuyenDonVi(
                          item.id,
                          item.ten,
                          item.ty_le,
                          item.mo_ta
                        )
                      "
                      v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      "
                    />
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.mo_ta"
                      v-else
                    />
                  </td>
                  <td align="center">
                    <input
                      class="truong_phong_6"
                      :checked="item.don_vi_goc"
                      :id="item.id"
                      :loai="item.loai"
                      name="truong_phong"
                      type="radio"
                    />
                  </td>
                  <!-- <td
                    align="center"
                    v-if="
                      usersystem.role_code == 'admin' ||
                        usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  >
                    <a @click="deleteChuyendonvicsht(item.id, index)"
                      ><i class="fa fa-trash-o"></i
                    ></a>
                  </td> -->
                </tr>
                <tr
                  v-if="
                    usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                  "
                >
                  <td colspan="2">
                    <input
                      type="text"
                      class="form-control  "
                      v-model="tencsht"
                      placeholder="Tên"
                    /><span
                      style="color: red"
                      v-text="errors_chuyendonvicsht"
                    ></span>
                  </td>
                  <td>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="ty_lecsht"
                      type="text"
                      placeholder="Tỷ lệ"
                    >
                    </ejs-numerictextbox>
                    <span
                      style="color: red"
                      v-text="errors_chuyendonvicsht"
                    ></span>
                  </td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="mo_tacsht"
                      placeholder="Mô tả"
                    /><span
                      style="color: red"
                      v-text="errors_chuyendonvicsht"
                    ></span>
                  </td>
                  <td align="center" colspan="2">
                    <a class="btn btn-flat" @click="addChuyenDonVicsht()"
                      ><i class="fa fa-plus"></i> Thêm</a
                    >
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab_dv_7">
        <div class="row">
          <div class="col-sm-12">
            <table class="table table-hover">
              <tbody>
                <tr class="row-table-header">
                  <th>STT</th>
                  <th>Tên</th>
                  <th>Tỷ lệ</th>
                  <th>Mô Tả</th>
                  <th>Đơn vị gốc</th>
                  <th
                    style="width:5%"
                    v-if="
                      usersystem.role_code == 'admin' ||
                        usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  ></th>
                </tr>
                <tr
                  v-for="(item, index) in cong_suat_thiet_kes"
                  :key="item.cong_suat_thiet_kes"
                >
                  <td>{{ index + 1 }}</td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.ten"
                      disabled
                    />
                  </td>
                  <td>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="item.ty_le"
                      type="text"
                      @change="
                        updateChuyenDonVi(
                          item.id,
                          item.ten,
                          item.ty_le,
                          item.mo_ta
                        )
                      "
                      v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      "
                    ></ejs-numerictextbox>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="item.ty_le"
                      type="text"
                      v-else
                    ></ejs-numerictextbox>
                  </td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.mo_ta"
                      @change="
                        updateChuyenDonVi(
                          item.id,
                          item.ten,
                          item.ty_le,
                          item.mo_ta
                        )
                      "
                      v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      "
                    />
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.mo_ta"
                      v-else
                    />
                  </td>
                  <td align="center">
                    <input
                      class="truong_phong_7"
                      :checked="item.don_vi_goc"
                      :id="item.id"
                      :loai="item.loai"
                      name="truong_phong"
                      type="radio"
                    />
                  </td>
                  <!-- <td
                    align="center"
                    v-if="
                      usersystem.role_code == 'admin' ||
                        usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  >
                    <a @click="deleteChuyendonvicstk(item.id, index)"
                      ><i class="fa fa-trash-o"></i
                    ></a>
                  </td> -->
                </tr>
                <tr
                  v-if="
                    usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                  "
                >
                  <td colspan="2">
                    <input
                      type="text"
                      class="form-control  "
                      v-model="tencstk"
                      placeholder="Tên"
                    /><span
                      style="color: red"
                      v-text="errors_chuyendonvicstk"
                    ></span>
                  </td>
                  <td>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="ty_lecstk"
                      type="text"
                      placeholder="Tỷ lệ"
                    >
                    </ejs-numerictextbox>
                    <span
                      style="color: red"
                      v-text="errors_chuyendonvicstk"
                    ></span>
                  </td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="mo_tacstk"
                      placeholder="Mô tả"
                    /><span
                      style="color: red"
                      v-text="errors_chuyendonvicstk"
                    ></span>
                  </td>
                  <td align="center" colspan="2">
                    <a class="btn btn-flat" @click="addChuyenDonVicstk()"
                      ><i class="fa fa-plus"></i> Thêm</a
                    >
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab_dv_8">
        <div class="row">
          <div class="col-sm-12">
            <table class="table table-hover">
              <tbody>
                <tr class="row-table-header">
                  <th>STT</th>
                  <th>Tên</th>
                  <th>Tỷ lệ</th>
                  <th>Mô Tả</th>
                  <th>Đơn vị gốc</th>
                  <th
                    style="width:5%"
                    v-if="
                      usersystem.role_code == 'admin' ||
                        usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  ></th>
                </tr>
                <tr v-for="(item, index) in dien_tichs" :key="item.dien_tichs">
                  <td>{{ index + 1 }}</td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.ten"
                      disabled
                    />
                  </td>
                  <td>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="item.ty_le"
                      type="text"
                      format="n4"
                      @change="
                        updateChuyenDonVi(
                          item.id,
                          item.ten,
                          item.ty_le,
                          item.mo_ta
                        )
                      "
                      v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      "
                    ></ejs-numerictextbox>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="item.ty_le"
                      type="text"
                      format="n4"
                      v-else
                    ></ejs-numerictextbox>
                  </td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.mo_ta"
                      @change="
                        updateChuyenDonVi(
                          item.id,
                          item.ten,
                          item.ty_le,
                          item.mo_ta
                        )
                      "
                      v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      "
                    />
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.mo_ta"
                      v-else
                    />
                  </td>
                  <td align="center">
                    <input
                      class="truong_phong_8"
                      :checked="item.don_vi_goc"
                      :id="item.id"
                      :loai="item.loai"
                      name="truong_phong"
                      type="radio"
                    />
                  </td>
                  <!-- <td
                    align="center"
                    v-if="
                      usersystem.role_code == 'admin' ||
                        usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  >
                    <a @click="deleteChuyendonviDienTich(item.id, index)"
                      ><i class="fa fa-trash-o"></i
                    ></a>
                  </td> -->
                </tr>
                <tr
                  v-if="
                    usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                  "
                >
                  <td colspan="2">
                    <input
                      type="text"
                      class="form-control  "
                      v-model="tendientich"
                      placeholder="Tên"
                    /><span
                      style="color: red"
                      v-text="errors_chuyendonvidientich"
                    ></span>
                  </td>
                  <td>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="ty_ledientich"
                      type="text"
                      format="n3"
                      placeholder="Tỷ lệ"
                    ></ejs-numerictextbox>
                    <span
                      style="color: red"
                      v-text="errors_chuyendonvidientich"
                    ></span>
                  </td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="mo_tadientich"
                      placeholder="Mô tả"
                    /><span
                      style="color: red"
                      v-text="errors_chuyendonvidientich"
                    ></span>
                  </td>
                  <td align="center" colspan="2">
                    <a class="btn btn-flat" @click="addChuyenDonViDienTich()"
                      ><i class="fa fa-plus"></i> Thêm</a
                    >
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab_dv_9">
        <div class="row">
          <div class="col-sm-12">
            <table class="table table-hover">
              <tbody>
                <tr class="row-table-header">
                  <th>STT</th>
                  <th>Tên</th>
                  <th>Tỷ lệ</th>
                  <th>Mô Tả</th>
                  <th>Đơn vị gốc</th>
                  <th
                    style="width:5%"
                    v-if="
                      usersystem.role_code == 'admin' ||
                        usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  ></th>
                </tr>
                <tr
                  v-for="(item, index) in don_vi_thong_so_vuot_chuans"
                  :key="index"
                >
                  <td>{{ index + 1 }}</td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.ten"
                      disabled
                    />
                  </td>
                  <td>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="item.ty_le"
                      type="text"
                      format="n4"
                      @change="
                        updateChuyenDonVi(
                          item.id,
                          item.ten,
                          item.ty_le,
                          item.mo_ta
                        )
                      "
                      v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      "
                    ></ejs-numerictextbox>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="item.ty_le"
                      type="text"
                      format="n4"
                      v-else
                    ></ejs-numerictextbox>
                  </td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.mo_ta"
                      @change="
                        updateChuyenDonVi(
                          item.id,
                          item.ten,
                          item.ty_le,
                          item.mo_ta
                        )
                      "
                      v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      "
                    />
                    <input
                      type="text"
                      class="form-control  "
                      v-model="item.mo_ta"
                      v-else
                    />
                  </td>
                  <td align="center">
                    <input
                      class="truong_phong_8"
                      :checked="item.don_vi_goc"
                      :id="item.id"
                      :loai="item.loai"
                      name="truong_phong"
                      type="radio"
                    />
                  </td>
                  <!-- <td
                    align="center"
                    v-if="
                      usersystem.role_code == 'admin' ||
                        usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                  >
                    <a
                      @click="deleteChuyendonviThongSoVuotChuan(item.id, index)"
                      ><i class="fa fa-trash-o"></i
                    ></a>
                  </td> -->
                </tr>
                <tr
                  v-if="
                    usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                  "
                >
                  <td></td>
                  <td colspan="1">
                    <input
                      type="text"
                      class="form-control  "
                      v-model="ten_don_vi_thong_so_vuot_chuan"
                      placeholder="Tên"
                    />
                  </td>
                  <td>
                    <ejs-numerictextbox
                      class="form-control "
                      v-model="ty_le_don_vi_thong_so_vuot_chuan"
                      type="text"
                      format="n3"
                      placeholder="Tỷ lệ"
                    ></ejs-numerictextbox>
                  </td>
                  <td>
                    <input
                      type="text"
                      class="form-control  "
                      v-model="mo_ta_don_vi_thong_so_vuot_chuan"
                      placeholder="Mô tả"
                    />
                  </td>
                  <td align="center" colspan="2">
                    <a
                      class="btn btn-flat"
                      @click="addChuyenDonViThongSoVuotChuan()"
                      ><i class="fa fa-plus"></i> Thêm</a
                    >
                  </td>
                </tr>
                <tr>
                  <td colspan="4">
                    <span
                      style="color: red"
                      v-text="errors_chuyendovi_thong_so_vuot_chuan"
                    ></span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as env from "../../env.js";
import MessageService from "../../services/MessageService";

export default {
  props: ["usersystem"],
  data: function() {
    return {
      data: [],
      ten: null,
      ty_le: null,
      mo_ta: null,
      tencskt: null,
      ty_lecskt: null,
      mo_tacskt: null,
      tencsht: null,
      ty_lecsht: null,
      mo_tacsht: null,
      tencstk: null,
      ty_lecstk: null,
      mo_tacstk: null,
      tendientich: null,
      ty_ledientich: null,
      mo_tadientich: null,
      tencsnt: null,
      ty_lecsnt: null,
      mo_tacsnt: null,
      tenpsct: null,
      ty_lepsct: null,
      mo_tapsct: null,
      tendv: null,
      ty_ledv: null,
      mo_tadv: null,
      ten_don_vi_thong_so_vuot_chuan: null,
      ty_le_don_vi_thong_so_vuot_chuan: null,
      mo_ta_don_vi_thong_so_vuot_chuan: null,
      errors_chuyendonvidientich: null,
      errors_chuyendonvi: null,
      errors_chuyendonvicskt: null,
      errors_chuyendonvicsnt: null,
      errors_chuyendonvipsct: null,
      errors_chuyendonvicsht: null,
      errors_chuyendonvicstk: null,
      errors_chuyendonvidv: null,
      errors_chuyendovi_thong_so_vuot_chuan: null,
      dv_goc_dien_tich: null,
      dv_goc_luu_luong_nt: null,
      dv_goc_luu_luong_kt: null,
      dv_goc_cong_suat_xu_ly_kt: null,
      dv_goc_cong_suat_xu_ly_nt: null,
      dv_goc_khoi_luong_phat_sinh_ct: null,
      dv_goc_cong_suat_he_thong: null,
      dv_goc_cong_suat_thiet_ke: null,
      luu_luong_nuoc_thais: [],
      luu_luong_khi_thais: [],
      cong_xuat_xu_ly_khi_thais: [],
      cong_xuat_xu_ly_nuoc_thais: [],
      khoi_luong_phat_sinh_chat_thais: [],
      cong_suat_he_thongs: [],
      cong_suat_thiet_kes: [],
      dien_tichs: [],
      don_vi_thong_so_vuot_chuans: [],
      checked_dv_goc_luu_luong_nt: {}
    };
  },
  mounted() {
    axios.get(env.endpointhttp + "luuluongnuocthai").then(response => {
      this.data = response.data.result;
      setTimeout(function() {
        $("input").iCheck({
          checkboxClass: "icheckbox_square-green",
          radioClass: "iradio_square-green",
          increaseArea: "20%"
        });
        $("input[type=radio]").on("ifChecked", function(event) {
          axios
            .put(env.endpointhttp + "updatedonvigoc", {
              id: $(this).attr("id"),
              loai: $(this).attr("loai")
            })
            .then(response => {});
        });
      }, 1);
    });
  },

  methods: {
    addRadio() {
      setTimeout(function() {
        $("input").iCheck({
          checkboxClass: "icheckbox_square-green",
          radioClass: "iradio_square-green",
          increaseArea: "20%"
        });
        $("input[type=radio]").on("ifChecked", function(event) {
          axios
            .put(env.endpointhttp + "updatedonvigoc", {
              id: $(this).attr("id"),
              loai: $(this).attr("loai")
            })
            .then(response => {});
        });
      }, 1);
    },
    updateChuyenDonVi: function(id, value_1, value_2, value_3) {
      axios
        .put(env.endpointhttp + "updateluuluongnc/" + id, {
          ten: value_1,
          ty_le: value_2,
          mo_ta: value_3
        })
        .then(response => {
          MessageService.showSuccessMessage('Cập nhật thành công');
        });
    },
    deleteChuyendonvi: function(id, index) {
      this.data.splice(index, 1);
      axios
        .delete(env.endpointhttp + "deleteluuluongnuoc/" + id)
        .then(response => {
          // MessageService.showSuccessMessage('Xoá thành công');
        });
    },
    deleteChuyendonvidv: function(id, index) {
      this.luu_luong_khi_thais.splice(index, 1);
      axios
        .delete(env.endpointhttp + "deleteluuluongnuoc/" + id)
        .then(response => {
          // MessageService.showSuccessMessage('Xoá thành công');
        });
    },
    deleteChuyendonvicskt: function(id, index) {
      this.cong_xuat_xu_ly_khi_thais.splice(index, 1);
      axios
        .delete(env.endpointhttp + "deleteluuluongnuoc/" + id)
        .then(response => {
          // MessageService.showSuccessMessage('Xoá thành công');
        });
    },
    deleteChuyendonvicsnt: function(id, index) {
      this.cong_xuat_xu_ly_nuoc_thais.splice(index, 1);
      axios
        .delete(env.endpointhttp + "deleteluuluongnuoc/" + id)
        .then(response => {
          // MessageService.showSuccessMessage('Xoá thành công');
        });
    },
    deleteChuyendonvipsct: function(id, index) {
      this.khoi_luong_phat_sinh_chat_thais.splice(index, 1);
      axios
        .delete(env.endpointhttp + "deleteluuluongnuoc/" + id)
        .then(response => {
          // MessageService.showSuccessMessage('Xoá thành công');
        });
    },
    deleteChuyendonvicsht: function(id, index) {
      this.cong_suat_he_thongs.splice(index, 1);
      axios
        .delete(env.endpointhttp + "deleteluuluongnuoc/" + id)
        .then(response => {
          // MessageService.showSuccessMessage('Xoá thành công');
        });
    },
    deleteChuyendonvicstk: function(id, index) {
      this.cong_suat_thiet_kes.splice(index, 1);
      axios
        .delete(env.endpointhttp + "deleteluuluongnuoc/" + id)
        .then(response => {
          // MessageService.showSuccessMessage('Xoá thành công');
        });
    },
    deleteChuyendonviDienTich: function(id, index) {
      this.dien_tichs.splice(index, 1);
      axios
        .delete(env.endpointhttp + "deleteluuluongnuoc/" + id)
        .then(response => {
          // MessageService.showSuccessMessage('Xoá thành công');
        });
    },
    deleteChuyendonviThongSoVuotChuan: function(id, index) {
      this.don_vi_thong_so_vuot_chuans.splice(index, 1);
      axios
        .delete(env.endpointhttp + "deleteluuluongnuoc/" + id)
        .then(response => {
          // MessageService.showSuccessMessage('Xoá thành công');
        });
    },

    addChuyenDonVi: function() {
      this.errors_chuyendonvi = "";
      if (
        this.ten == "" ||
        this.ten == null ||
        this.ty_le == "" ||
        this.ty_le == null
      ) {
        this.errors_chuyendonvi = "Chưa nhập đủ dữ liệu.";
      } else {
        axios
          .post(env.endpointhttp + "addluuluongnuocthai", {
            ten: this.ten,
            ty_le: this.ty_le,
            mo_ta: this.mo_ta
          })
          .then(response => {
            this.data = response.data.result;
            this.ten = "";
            this.ty_le = "";
            this.mo_ta = "";
            this.addRadio();
            // MessageService.showSuccessMessage('Thêm mới thành công');
          });
      }
    },
    addChuyenDonVikt: function() {
      this.errors_chuyendonvidv = "";
      if (
        this.tendv == "" ||
        this.tendv == null ||
        this.ty_ledv == "" ||
        this.ty_ledv == null
      ) {
        this.errors_chuyendonvidv = "Chưa nhập đủ dữ liệu.";
      } else {
        axios
          .post(env.endpointhttp + "addluuluongkhithai", {
            ten: this.tendv,
            ty_le: this.ty_ledv,
            mo_ta: this.mo_tadv
          })
          .then(response => {
            this.luu_luong_khi_thais = response.data.result;
            this.tendv = "";
            this.ty_ledv = "";
            this.mo_tadv = "";
            this.addRadio();
            // MessageService.showSuccessMessage('Thêm mới thành công');
          });
      }
    },
    addChuyenDonVicskt: function() {
      this.errors_chuyendonvicskt = "";
      if (
        this.tencskt == "" ||
        this.tencskt == null ||
        this.ty_lecskt == "" ||
        this.ty_lecskt == null
      ) {
        this.errors_chuyendonvicskt = "Chưa nhập đủ dữ liệu.";
      } else {
        axios
          .post(env.endpointhttp + "addcongsuatkhithai", {
            ten: this.tencskt,
            ty_le: this.ty_lecskt,
            mo_ta: this.mo_tacskt
          })
          .then(response => {
            this.cong_xuat_xu_ly_khi_thais = response.data.result;
            this.tencskt = "";
            this.ty_lecskt = "";
            this.mo_tacskt = "";
            this.addRadio();
            // MessageService.showSuccessMessage('Thêm mới thành công');
          });
      }
    },
    addChuyenDonVicsnt: function() {
      this.errors_chuyendonvicsnt = "";
      if (
        this.tencsnt == "" ||
        this.tencsnt == null ||
        this.ty_lecsnt == "" ||
        this.ty_lecsnt == null
      ) {
        this.errors_chuyendonvicsnt = "Chưa nhập đủ dữ liệu.";
      } else {
        axios
          .post(env.endpointhttp + "addcongsuatnuocthai", {
            ten: this.tencsnt,
            ty_le: this.ty_lecsnt,
            mo_ta: this.mo_tacsnt
          })
          .then(response => {
            this.cong_xuat_xu_ly_nuoc_thais = response.data.result;
            this.tencsnt = "";
            this.ty_lecsnt = "";
            this.mo_tacsnt = "";
            this.addRadio();
            // MessageService.showSuccessMessage('Thêm mới thành công');
          });
      }
    },
    addChuyenDonVipsct: function() {
      this.errors_chuyendonvipsct = "";
      if (
        this.tenpsct == "" ||
        this.tenpsct == null ||
        this.ty_lepsct == "" ||
        this.ty_lepsct == null
      ) {
        this.errors_chuyendonvipsct = "Chưa nhập đủ dữ liệu.";
      } else {
        axios
          .post(env.endpointhttp + "addphatsinhchatthai", {
            ten: this.tenpsct,
            ty_le: this.ty_lepsct,
            mo_ta: this.mo_tapsct
          })
          .then(response => {
            this.khoi_luong_phat_sinh_chat_thais = response.data.result;
            this.tenpsct = "";
            this.ty_lepsct = "";
            this.mo_tapsct = "";
            this.addRadio();
            // MessageService.showSuccessMessage('Thêm mới thành công');
          });
      }
    },
    addChuyenDonVicsht: function() {
      this.errors_chuyendonvicsht = "";
      if (
        this.tencsht == "" ||
        this.tencsht == null ||
        this.ty_lecsht == "" ||
        this.ty_lecsht == null
      ) {
        this.errors_chuyendonvicsht = "Chưa nhập đủ dữ liệu.";
      } else {
        axios
          .post(env.endpointhttp + "addcongsuathethong", {
            ten: this.tencsht,
            ty_le: this.ty_lecsht,
            mo_ta: this.mo_tacsht
          })
          .then(response => {
            this.cong_suat_he_thongs = response.data.result;
            this.tencsht = "";
            this.ty_lecsht = "";
            this.mo_tacsht = "";
            this.addRadio();
            // MessageService.showSuccessMessage('Thêm mới thành công');
          });
      }
    },
    addChuyenDonVicstk: function() {
      this.errors_chuyendonvicstk = "";
      if (
        this.tencstk == "" ||
        this.tencstk == null ||
        this.ty_lecstk == "" ||
        this.ty_lecstk == null
      ) {
        this.errors_chuyendonvicstk = "Chưa nhập đủ dữ liệu.";
      } else {
        axios
          .post(env.endpointhttp + "addcongsuatthietke", {
            ten: this.tencstk,
            ty_le: this.ty_lecstk,
            mo_ta: this.mo_tacstk
          })
          .then(response => {
            this.cong_suat_thiet_kes = response.data.result;
            this.tencstk = "";
            this.ty_lecstk = "";
            this.mo_tacstk = "";
            this.addRadio();
            // MessageService.showSuccessMessage('Thêm mới thành công');
          });
      }
    },
    addChuyenDonViDienTich: function() {
      this.errors_chuyendonvidientich = "";
      if (
        this.tendientich == "" ||
        this.tendientich == null ||
        this.ty_ledientich == "" ||
        this.ty_ledientich == null
      ) {
        this.errors_chuyendonvidientich = "Chưa nhập đủ dữ liệu.";
      } else {
        axios
          .post(env.endpointhttp + "adddientich", {
            ten: this.tendientich,
            ty_le: this.ty_ledientich,
            mo_ta: this.mo_tadientich
          })
          .then(response => {
            this.dien_tichs = response.data.result;
            this.tendientich = "";
            this.ty_ledientich = "";
            this.mo_tadientich = "";
            this.addRadio();
            // MessageService.showSuccessMessage('Thêm mới thành công');
          });
      }
    },

    addChuyenDonViThongSoVuotChuan() {
      this.errors_chuyendonvidientich = "";
      if (
        this.ten_don_vi_thong_so_vuot_chuan == "" ||
        this.ten_don_vi_thong_so_vuot_chuan == null ||
        this.ty_le_don_vi_thong_so_vuot_chuan == "" ||
        this.ty_le_don_vi_thong_so_vuot_chuan == null
      ) {
        this.errors_chuyendonvidientich = "Chưa nhập đủ dữ liệu.";
      } else {
        axios
          .post(env.endpointhttp + "addthongsovuotchuan", {
            ten: this.ten_don_vi_thong_so_vuot_chuan,
            ty_le: this.ty_le_don_vi_thong_so_vuot_chuan,
            mo_ta: this.mo_ta_don_vi_thong_so_vuot_chuan
          })
          .then(response => {
            this.don_vi_thong_so_vuot_chuans = response.data.result;
            this.ten_don_vi_thong_so_vuot_chuan = "";
            this.ty_le_don_vi_thong_so_vuot_chuan = "";
            this.mo_ta_don_vi_thong_so_vuot_chuan = "";
            this.addRadio();
            // MessageService.showSuccessMessage('Thêm mới thành công');
          });
      }
    },

    loadDataTab2() {
      axios.get(env.endpointhttp + "luuluongkhithai").then(response => {
        this.luu_luong_khi_thais = response.data.result;
        setTimeout(function() {
          $("input").iCheck({
            checkboxClass: "icheckbox_square-green",
            radioClass: "iradio_square-green",
            increaseArea: "20%"
          });
          $("input[type=radio]").on("ifChecked", function(event) {
            axios
              .put(env.endpointhttp + "updatedonvigoc", {
                id: $(this).attr("id"),
                loai: $(this).attr("loai")
              })
              .then(response => {});
          });
        }, 1);
      });
    },
    loadDataTab3() {
      axios.get(env.endpointhttp + "congsuatxulynuocthai").then(response => {
        this.cong_xuat_xu_ly_nuoc_thais = response.data.result;
        setTimeout(function() {
          $("input").iCheck({
            checkboxClass: "icheckbox_square-green",
            radioClass: "iradio_square-green",
            increaseArea: "20%"
          });
          $("input[type=radio]").on("ifChecked", function(event) {
            axios
              .put(env.endpointhttp + "updatedonvigoc", {
                id: $(this).attr("id"),
                loai: $(this).attr("loai")
              })
              .then(response => {});
          });
        }, 1);
      });
    },
    loadDataTab4() {
      axios.get(env.endpointhttp + "congsuatxulykhithai").then(response => {
        this.cong_xuat_xu_ly_khi_thais = response.data.result;
        setTimeout(function() {
          $("input").iCheck({
            checkboxClass: "icheckbox_square-green",
            radioClass: "iradio_square-green",
            increaseArea: "20%"
          });
          $("input[type=radio]").on("ifChecked", function(event) {
            axios
              .put(env.endpointhttp + "updatedonvigoc", {
                id: $(this).attr("id"),
                loai: $(this).attr("loai")
              })
              .then(response => {});
          });
        }, 1);
      });
    },
    loadDataTab5() {
      axios
        .get(env.endpointhttp + "khoiluongphatsinhchatthai")
        .then(response => {
          this.khoi_luong_phat_sinh_chat_thais = response.data.result;
          var controller = this;
          setTimeout(function() {
            $("input").iCheck({
              checkboxClass: "icheckbox_square-green",
              radioClass: "iradio_square-green",
              increaseArea: "20%"
            });
            $("input[type=radio]").on("ifChecked", function(event) {
              axios
                .put(env.endpointhttp + "updatedonvigoc", {
                  id: $(this).attr("id"),
                  loai: $(this).attr("loai")
                })
                .then(response => {
                  if (
                    response.data.result.loai ==
                    "khoi_luong_phat_sinh_chat_thai"
                  ) {
                    controller.loadDataTab5();
                  }
                });
            });
          }, 1);
        });
    },
    loadDataTab6() {
      axios.get(env.endpointhttp + "congsuathethong").then(response => {
        this.cong_suat_he_thongs = response.data.result;
        setTimeout(function() {
          $("input").iCheck({
            checkboxClass: "icheckbox_square-green",
            radioClass: "iradio_square-green",
            increaseArea: "20%"
          });
          $("input[type=radio]").on("ifChecked", function(event) {
            axios
              .put(env.endpointhttp + "updatedonvigoc", {
                id: $(this).attr("id"),
                loai: $(this).attr("loai")
              })
              .then(response => {});
          });
        }, 1);
      });
    },
    loadDataTab7() {
      axios.get(env.endpointhttp + "congsuatthietke").then(response => {
        this.cong_suat_thiet_kes = response.data.result;
        setTimeout(function() {
          $("input").iCheck({
            checkboxClass: "icheckbox_square-green",
            radioClass: "iradio_square-green",
            increaseArea: "20%"
          });
          $("input[type=radio]").on("ifChecked", function(event) {
            axios
              .put(env.endpointhttp + "updatedonvigoc", {
                id: $(this).attr("id"),
                loai: $(this).attr("loai")
              })
              .then(response => {});
          });
        }, 1);
      });
    },
    loadDataTab8() {
      axios.get(env.endpointhttp + "dientich").then(response => {
        this.dien_tichs = response.data.result;
        var controller = this;
        setTimeout(function() {
          $("input").iCheck({
            checkboxClass: "icheckbox_square-green",
            radioClass: "iradio_square-green",
            increaseArea: "20%"
          });
          $("input[type=radio]").on("ifChecked", function(event) {
            axios
              .put(env.endpointhttp + "updatedonvigoc", {
                id: $(this).attr("id"),
                loai: $(this).attr("loai")
              })
              .then(response => {
                if (response.data.result.loai == "dien_tich") {
                  controller.loadDataTab8();
                }
              });
          });
        }, 1);
      });
    },
    loadDataTab9() {
      axios.get(env.endpointhttp + "thongsovuotchuan").then(response => {
        this.don_vi_thong_so_vuot_chuans = response.data.result;
        var controller = this;
        setTimeout(function() {
          $("input").iCheck({
            checkboxClass: "icheckbox_square-green",
            radioClass: "iradio_square-green",
            increaseArea: "20%"
          });
        }, 1);
      });
    },
    loadDataTab1() {
      axios.get(env.endpointhttp + "luuluongnuocthai").then(response => {
        this.data = response.data.result;
        var controller = this;
        setTimeout(function() {
          $("input").iCheck({
            checkboxClass: "icheckbox_square-green",
            radioClass: "iradio_square-green",
            increaseArea: "20%"
          });
          $("input[type=radio]").on("ifChecked", function(event) {
            axios
              .put(env.endpointhttp + "updatedonvigoc", {
                id: $(this).attr("id"),
                loai: $(this).attr("loai")
              })
              .then(response => {
                if (response.data.result.loai == "luu_luong_nuoc_thai") {
                  controller.loadDataTab1();
                }
              });
          });
        }, 1);
      });
    }
  }
};
</script>
