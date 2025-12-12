<template>
  <div class="row block-multiple-rows">
    <div class="col-lg-12" style="margin-top: 5px;">
      <label>KẾT QUẢ THANH TRA THỦ TỤC HÀNH CHÍNH</label>
      <p class="text-muted">
        (Click vào từng mục chi tiết thông tin kết quả thanh tra thủ tục hành
        chính.)
      </p>
      <hr style="margin-top: 7px; margin-bottom: 7px;" />
    </div>
    <div class="col-md-12">
      <div class="nav-tabs-custom margin-top">
        <ul class="nav nav-tabs">
          <li class="active">
            <a :href="'#tab_thutuc_1' + index1" data-toggle="tab" aria-expanded="true">ĐTM</a>
          </li>
          <li class>
            <a :href="'#tab_thutuc_2' + index1" data-toggle="tab" aria-expanded="false">Đề án bảo vệ môi trường</a>
          </li>
          <li class>
            <a :href="'#tab_thutuc_3' + index1" data-toggle="tab" aria-expanded="false">Kế hoạch BVMT/Cam kết BVMT/bản
              đăng ký đạt TCMT</a>
          </li>
          <li class>
            <a :href="'#tab_thutuc_4' + index1" data-toggle="tab" aria-expanded="false">Giấy xác nhận công trình
              BVMT</a>
          </li>
          <li class>
            <a :href="'#tab_thutuc_5' + index1" data-toggle="tab" aria-expanded="false">Sổ đăng kí chủ nguồn thải
              CTNH</a>
          </li>
          <li class>
            <a :href="'#tab_thutuc_6' + index1" data-toggle="tab" aria-expanded="false">
              Giấy xác nhận đủ điều kiện BVMT nhập khẩu phế liệu làm NLSX
            </a>
          </li>
          <li class>
            <a :href="'#tab_thutuc_7' + index1" data-toggle="tab" aria-expanded="false">Giấy phép xả thải</a>
          </li>
          <li class>
            <a :href="'#tab_thutuc_8' + index1" data-toggle="tab" aria-expanded="false">Các thủ tục khác</a>
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" :id="'tab_thutuc_1' + index1">
            <div class="row block-multiple-rows" v-if="
              usersystem.role_code == 'admin' ||
                usersystem.role_code == 'nhanvientrungtam' ||
                usersystem.role_code == 'adminvaquanlydanhmuc'
            ">
              <div class="col-lg-3 col-md-6">
                <div class="form-group">
                  <input type="text" class="form-control" v-model="form_dtm.so_quyet_dinh"
                    placeholder="Số QĐ phê duyệt" />
                  <span style="color: red;">{{ form_dtm.error_so_quyet_dinh }}
                  </span>
                </div>
              </div>
              <div class="col-lg-2 col-md-6">
                <date-picker v-model="form_dtm.thoi_gian_phe_duyet" tabindex
                  placeholder="Ngày ra quyết định phê duyệt ĐTM" :config="optionsDate"></date-picker>
              </div>
              <div class="col-lg-6 col-md-10">
                <div class="form-group">
                  <multiselect v-model="form_dtm.co_quan_phe_duyet" label="ten" track-by="id"
                    placeholder="Gõ tên cơ quan phê duyệt" selectedLabel="Đã chọn" open-direction="bottom"
                    :options="coquantochucs" :multiple="false" :searchable="true" :clear-on-select="true"
                    :close-on-select="true" :show-no-results="false" :hide-selected="false" :tabindex="1">
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                </div>
              </div>
              <div class="col-lg-1 col-md-2">
                <button type="button" class="btn btn-flat pull-right" tabindex="22" @click="addDtm()">
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
                      <th style="width: 5%; text-align: center;" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'nhanvientrungtam' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      ">
                        Xóa
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in co_so_san_xuat.thutuchanhchinhs
                    .dtm" :key="index">
                      <td>{{ item.so_quyet_dinh }}</td>
                      <td>
                        {{
                        item.co_quan_quyet_dinh
                        ? item.co_quan_quyet_dinh.ten
                        : null
                        }}
                      </td>
                      <td>{{ item.thoi_gian_phe_duyet }}</td>
                      <td align="center" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'nhanvientrungtam' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      ">
                        <a @click="deleteDtm(index)">
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
            <div class="row block-multiple-rows" v-if="
              usersystem.role_code == 'admin' ||
                usersystem.role_code == 'nhanvientrungtam' ||
                usersystem.role_code == 'adminvaquanlydanhmuc'
            ">
              <div class="col-lg-12" style="margin-top: 5px;">
                <p class="text-muted">
                  Nhập thông tin số quyết định phê duyệt, cơ quan phê duyệt,
                  ngày ra quyết định phê duyệt và nhấn nút thêm để thêm kết quả
                  thanh tra ĐABVMT
                </p>
                <hr style="margin-top: 7px; margin-bottom: 7px;" />
              </div>
              <div class="col-lg-3 col-md-6">
                <div class="form-group">
                  <input type="text" class="form-control" v-model="form_dabvmt.so_quyet_dinh"
                    placeholder="Số QĐ phê duyệt" />
                  <span style="color: red;">{{ form_dabvmt.error_so_quyet_dinh }}
                  </span>
                </div>
              </div>
              <div class="col-lg-2 col-md-6">
                <date-picker v-model="form_dabvmt.thoi_gian_phe_duyet" placeholder="Ngày ra quyết định phê duyệt ĐABVMT"
                  :config="optionsDate"></date-picker>
              </div>
              <div class="col-lg-6 col-md-10">
                <div class="form-group">
                  <multiselect v-model="form_dabvmt.co_quan_phe_duyet" label="ten" track-by="id"
                    placeholder="Gõ tên cơ quan" selectedLabel="Đã chọn" open-direction="bottom"
                    :options="coquantochucs" :multiple="false" :searchable="true" :clear-on-select="true"
                    :close-on-select="true" :show-no-results="false" :hide-selected="false" :tabindex="1">
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                </div>
              </div>
              <div class="col-lg-1 col-md-2">
                <button type="button" class="btn btn-flat pull-right" @click="addDabvmt()">
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
                      <th style="width: 5%; text-align: center;" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'nhanvientrungtam' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      ">
                        Xóa
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in co_so_san_xuat.thutuchanhchinhs
                    .dabvmt" :key="index">
                      <td>{{ item.so_quyet_dinh }}</td>
                      <td>
                        {{
                        item.co_quan_quyet_dinh
                        ? item.co_quan_quyet_dinh.ten
                        : null
                        }}
                      </td>
                      <td>{{ item.thoi_gian_phe_duyet }}</td>
                      <td align="center" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'nhanvientrungtam' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      ">
                        <a @click="deleteDabvmt(index)">
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
            <div class="row block-multiple-rows" v-if="
              usersystem.role_code == 'admin' ||
                usersystem.role_code == 'nhanvientrungtam' ||
                usersystem.role_code == 'adminvaquanlydanhmuc'
            ">
              <div class="col-lg-12" style="margin-top: 5px;">
                <p class="text-muted">
                  Nhập thông tin số quyết định phê duyệt, cơ quan phê duyệt,
                  ngày ra quyết định phê duyệt và nhấn nút thêm để thêm kết quả
                  thanh tra phương án BVMT/Cam kết BVMT
                </p>
                <hr style="margin-top: 7px; margin-bottom: 7px;" />
              </div>
              <div class="col-lg-3 col-md-6">
                <div class="form-group">
                  <input type="text" class="form-control" required placeholder="Số QĐ phê duyệt"
                    v-model="form_pabvmt.so_quyet_dinh" />
                  <span style="color: red;">{{ form_pabvmt.error_so_quyet_dinh }}
                  </span>
                </div>
              </div>
              <div class="col-lg-2 col-md-6">
                <date-picker v-model="form_pabvmt.thoi_gian_phe_duyet" tabindex
                  placeholder="Ngày ra quyết định phê duyệt PA/CK BVMT" :config="optionsDate"></date-picker>
              </div>
              <div class="col-lg-6 col-md-10">
                <div class="form-group">
                  <multiselect v-model="form_pabvmt.co_quan_phe_duyet" label="ten" track-by="id"
                    placeholder="Gõ tên cơ quan" selectedLabel="Đã chọn" open-direction="bottom"
                    :options="coquantochucs" :multiple="false" :searchable="true" :clear-on-select="true"
                    :close-on-select="true" :show-no-results="false" :hide-selected="false" :tabindex="1">
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                </div>
              </div>
              <div class="col-lg-1 col-md-2">
                <button type="button" class="btn btn-flat pull-right" @click="addPabvmt()">
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
                      <th style="width: 5%; text-align: center;" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'nhanvientrungtam' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      ">
                        Xóa
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in co_so_san_xuat.thutuchanhchinhs
                    .pabvmt" :key="index">
                      <td>{{ item.so_quyet_dinh }}</td>
                      <td>
                        {{
                        item.co_quan_quyet_dinh
                        ? item.co_quan_quyet_dinh.ten
                        : null
                        }}
                      </td>
                      <td>{{ item.thoi_gian_phe_duyet }}</td>
                      <td align="center" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'nhanvientrungtam' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      ">
                        <a @click="deletePabvmt(index)">
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
            <div class="row block-multiple-rows" v-if="
              usersystem.role_code == 'admin' ||
                usersystem.role_code == 'nhanvientrungtam' ||
                usersystem.role_code == 'adminvaquanlydanhmuc'
            ">
              <div class="col-lg-12" style="margin-top: 5px;">
                <p class="text-muted">
                  Nhập thông tin số giấy xác nhận, cơ quan xác nhận, ngày ra
                  quyết định phê duyệt và nhấn nút thêm để thêm kết quả thanh
                  tra giấy xác nhận công trình BVMT
                </p>
                <hr style="margin-top: 7px; margin-bottom: 7px;" />
              </div>
              <div class="col-lg-3 col-md-6">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Số giấy xác nhận"
                    v-model="form_gxnctbvmt.so_quyet_dinh" />
                  <span style="color: red;">{{ form_gxnctbvmt.error_so_quyet_dinh }}
                  </span>
                </div>
              </div>
              <div class="col-lg-2 col-md-6">
                <date-picker v-model="form_gxnctbvmt.thoi_gian_phe_duyet" tabindex
                  placeholder="Ngày ra quyết định phê duyệt CTBVMT" :config="optionsDate"></date-picker>
              </div>
              <div class="col-lg-6 col-md-10">
                <div class="form-group">
                  <multiselect v-model="form_gxnctbvmt.co_quan_phe_duyet" label="ten" track-by="id"
                    placeholder="Gõ tên cơ quan xác nhận" selectedLabel="Đã chọn" open-direction="bottom"
                    :options="coquantochucs" :multiple="false" :searchable="true" :clear-on-select="true"
                    :close-on-select="true" :show-no-results="false" :hide-selected="false" :tabindex="1">
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                </div>
              </div>

              <div class="col-lg-1 col-md-2">
                <button type="button" class="btn btn-flat pull-right" @click="addGxnctbvmt()">
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
                      <th style="width: 5%; text-align: center;" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'nhanvientrungtam' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      ">
                        Xóa
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in co_so_san_xuat.thutuchanhchinhs
                    .gxnctbvmt" :key="index">
                      <td>{{ item.so_quyet_dinh }}</td>
                      <td>
                        {{
                        item.co_quan_quyet_dinh
                        ? item.co_quan_quyet_dinh.ten
                        : null
                        }}
                      </td>
                      <td>{{ item.thoi_gian_phe_duyet }}</td>
                      <td v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'nhanvientrungtam' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      ">
                        <a @click="deleteGxnctbvmt(index)">
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
            <div class="row block-multiple-rows" v-if="
              usersystem.role_code == 'admin' ||
                usersystem.role_code == 'nhanvientrungtam' ||
                usersystem.role_code == 'adminvaquanlydanhmuc'
            ">
              <div class="col-lg-3 col-md-6">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Số sổ đăng ký"
                    v-model="form_sdkcntctnn.so_quyet_dinh" />
                  <span style="color: red;">{{ form_sdkcntctnn.error_so_quyet_dinh }}
                  </span>
                </div>
              </div>
              <div class="col-lg-2 col-md-6">
                <date-picker v-model="form_sdkcntctnn.thoi_gian_phe_duyet" tabindex
                  placeholder="Ngày ra quyết định phê duyệt CNTCTNH" :config="optionsDate"></date-picker>
              </div>
              <div class="col-lg-6 col-md-10">
                <div class="form-group">
                  <multiselect v-model="form_sdkcntctnn.co_quan_phe_duyet" label="ten" track-by="id"
                    placeholder="Gõ tên cơ quan cấp" selectedLabel="Đã chọn" open-direction="bottom"
                    :options="coquantochucs" :multiple="false" :searchable="true" :clear-on-select="true"
                    :close-on-select="true" :show-no-results="false" :hide-selected="false" :tabindex="1">
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                </div>
              </div>

              <div class="col-lg-1 col-md-2">
                <button type="button" class="btn btn-flat pull-right" @click="addSdkcntctnh()">
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
                      <th style="width: 5%; text-align: center;" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'nhanvientrungtam' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      ">
                        Xóa
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in co_so_san_xuat.thutuchanhchinhs
                    .sdkcntctnh" :key="index">
                      <td>{{ item.so_quyet_dinh }}</td>
                      <td>
                        {{
                        item.co_quan_quyet_dinh
                        ? item.co_quan_quyet_dinh.ten
                        : null
                        }}
                      </td>
                      <td>{{ item.thoi_gian_phe_duyet }}</td>
                      <td align="center" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'nhanvientrungtam' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      ">
                        <a @click="deleteSdkcntctnh(index)">
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
            <div class="row block-multiple-rows" v-if="
              usersystem.role_code == 'admin' ||
                usersystem.role_code == 'nhanvientrungtam' ||
                usersystem.role_code == 'adminvaquanlydanhmuc'
            ">
              <div class="col-lg-12" style="margin-top: 5px;">
                <p class="text-muted">
                  Nhập thông tin số giấy xác nhận, cơ quan xác nhận, ngày ra
                  quyết định phê duyệt và nhấn nút thêm để thêm kết quả thanh
                  tra giấy xác nhận đủ điều kiện BVMT
                </p>
                <hr style="margin-top: 7px; margin-bottom: 7px;" />
              </div>
              <div class="col-lg-3 col-md-6">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Số giấy xác nhận"
                    v-model="form_gxnddkbvmtnkpl.so_quyet_dinh" />
                  <span style="color: red;">{{ form_gxnddkbvmtnkpl.error_so_quyet_dinh }}
                  </span>
                </div>
              </div>
              <div class="col-lg-2 col-md-6">
                <date-picker v-model="form_gxnddkbvmtnkpl.thoi_gian_phe_duyet" tabindex
                  placeholder="Ngày ra quyết định phê duyệt NKPL" :config="optionsDate"></date-picker>
              </div>
              <div class="col-lg-6 col-md-10">
                <div class="form-group">
                  <multiselect v-model="form_gxnddkbvmtnkpl.co_quan_phe_duyet" label="ten" track-by="id"
                    placeholder="Gõ tên cơ xác nhận" selectedLabel="Đã chọn" open-direction="bottom"
                    :options="coquantochucs" :multiple="false" :searchable="true" :clear-on-select="true"
                    :close-on-select="true" :show-no-results="false" :hide-selected="false" :tabindex="1">
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                </div>
              </div>

              <div class="col-lg-1 col-md-2">
                <button type="button" class="btn btn-flat pull-right" @click="addGxnddkbvmtnkpl()">
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
                      <th style="width: 5%; text-align: center;" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'nhanvientrungtam' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      ">
                        Xóa
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in co_so_san_xuat.thutuchanhchinhs
                    .gxnddkbvmtnkpl" :key="index">
                      <td>{{ item.so_quyet_dinh }}</td>
                      <td>
                        {{
                        item.co_quan_quyet_dinh
                        ? item.co_quan_quyet_dinh.ten
                        : null
                        }}
                      </td>
                      <td>{{ item.thoi_gian_phe_duyet }}</td>
                      <td align="center" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'nhanvientrungtam' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      ">
                        <a @click="deleteGxnddkbvmtnkpl(index)">
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
            <div class="row block-multiple-rows" v-if="
              usersystem.role_code == 'admin' ||
                usersystem.role_code == 'nhanvientrungtam' ||
                usersystem.role_code == 'adminvaquanlydanhmuc'
            ">
              <div class="col-lg-12" style="margin-top: 5px;">
                <p class="text-muted">
                  Nhập thông tin số giấy xác nhận, cơ quan xác nhận, ngày ra
                  quyết định phê duyệt và nhấn nút thêm để thêm kết quả thanh
                  tra giấy phép xả thải
                </p>
                <hr style="margin-top: 7px; margin-bottom: 7px;" />
              </div>
              <div class="col-lg-3 col-md-6">
                <div class="form-group">
                  <input type="text" placeholder="Số giấy xác nhận" class="form-control"
                    v-model="form_gpxt.so_quyet_dinh" />
                  <span style="color: red;">{{ form_gpxt.error_so_quyet_dinh }}
                  </span>
                </div>
              </div>
              <div class="col-lg-2 col-md-6">
                <date-picker v-model="form_gpxt.thoi_gian_phe_duyet" tabindex
                  placeholder="Ngày ra quyết định phê duyệt GPXT" :config="optionsDate"></date-picker>
              </div>
              <div class="col-lg-6 col-md-10">
                <div class="form-group">
                  <multiselect v-model="form_gpxt.co_quan_phe_duyet" label="ten" track-by="id"
                    placeholder="Gõ tên cơ xác nhận" selectedLabel="Đã chọn" open-direction="bottom"
                    :options="coquantochucs" :multiple="false" :searchable="true" :clear-on-select="true"
                    :close-on-select="true" :show-no-results="false" :hide-selected="false" :tabindex="1">
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                </div>
              </div>
              <div class="col-lg-1 col-md-2">
                <button type="button" class="btn bg-flat btn-flat pull-right" @click="addGpxt()">
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
                      <th style="width: 5%; text-align: center;" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'nhanvientrungtam' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      ">
                        Xóa
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in co_so_san_xuat.thutuchanhchinhs
                    .gpxt" :key="index">
                      <td>{{ item.so_quyet_dinh }}</td>
                      <td>
                        {{
                        item.co_quan_quyet_dinh
                        ? item.co_quan_quyet_dinh.ten
                        : null
                        }}
                      </td>
                      <td>{{ item.thoi_gian_phe_duyet }}</td>
                      <td align="center" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'nhanvientrungtam' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      ">
                        <a @click="deleteGpxt(index)">
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
            <div class="row block-multiple-rows" v-if="
              usersystem.role_code == 'admin' ||
                usersystem.role_code == 'nhanvientrungtam' ||
                usersystem.role_code == 'adminvaquanlydanhmuc'
            ">
              <div class="col-lg-12" style="margin-top: 5px;">
                <p class="text-muted">
                  Nhập thông tin số giấy xác nhận, cơ quan xác nhận, ngày ra
                  quyết định phê duyệt và nhấn nút thêm để thêm kết quả thanh
                  tra các thủ tục khác
                </p>
                <hr style="margin-top: 7px; margin-bottom: 7px;" />
              </div>
              <div class="col-lg-3 col-md-6">
                <div class="form-group">
                  <input type="text" placeholder="Số giấy xác nhận" class="form-control"
                    v-model="form_ttk.so_quyet_dinh" />
                  <span style="color: red;">{{ form_ttk.error_so_quyet_dinh }}
                  </span>
                </div>
              </div>
              <div class="col-lg-2 col-md-6">
                <date-picker v-model="form_ttk.thoi_gian_phe_duyet" tabindex placeholder="Ngày xác nhận"
                  :config="optionsDate"></date-picker>
              </div>
              <div class="col-lg-6 col-md-10">
                <div class="form-group">
                  <multiselect v-model="form_ttk.co_quan_phe_duyet" label="ten" track-by="id"
                    placeholder="Gõ tên cơ phê duyệt" selectedLabel="Đã chọn" open-direction="bottom"
                    :options="coquantochucs" :multiple="false" :searchable="true" :clear-on-select="true"
                    :close-on-select="true" :show-no-results="false" :hide-selected="false" :tabindex="1">
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </multiselect>
                </div>
              </div>
              <div class="col-lg-1 col-md-2">
                <a type="button" class="btn bg-flat btn-flat pull-right" @click="addTtk()">
                  <i class="fa fa-plus"></i> Thêm
                </a>
              </div>
              <div class="col-lg-6 col-md-10">
                <label class="control-label">Ghi chú</label>
                <textarea class="form-control" v-model="form_ttk.ghi_chu" rows="3"></textarea>
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
                      <th style="width: 5%; text-align: center;" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'nhanvientrungtam' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      ">
                        Xóa
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in co_so_san_xuat.thutuchanhchinhs
                    .ttk" :key="index">
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
                      <td align="center" v-if="
                        usersystem.role_code == 'admin' ||
                          usersystem.role_code == 'nhanvientrungtam' ||
                          usersystem.role_code == 'adminvaquanlydanhmuc'
                      ">
                        <a @click="deleteTtk(index)">
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
    <div class="col-md-12" v-if="
      usersystem.role_code == 'admin' ||
        usersystem.role_code == 'nhanvientrungtam' ||
        usersystem.role_code == 'adminvaquanlydanhmuc'
    ">
      <div class="col-lg-2 col-md-6">
        <div class="form-group">
          <multiselect v-model="loaiThuTuc" label="name" track-by="code" @input="changeLoaiThuTuc"
            placeholder="Thủ tục hành chính" selectedLabel="Đã chọn" open-direction="bottom" :options="loaiThuTucs"
            :multiple="false" :searchable="true" :clear-on-select="true" :close-on-select="true"
            :show-no-results="false" :hide-selected="false">
            <span slot="noResult">Không tìm thấy kết quả</span>
          </multiselect>
        </div>
      </div>
      <div class="col-lg-2 col-md-6">
        <div class="form-group">
          <multiselect v-model="loaiVanBan" label="ten" track-by="id" placeholder="Loại văn bản" selectedLabel="Đã chọn"
            open-direction="bottom" :options="loaiVanBanSelect" :multiple="false" :searchable="true"
            :clear-on-select="true" :close-on-select="true" :show-no-results="false" :hide-selected="false">
            <span slot="noResult">Không tìm thấy kết quả</span>
          </multiselect>
        </div>
      </div>
      <div class="col-lg-2 col-md-6">
        <div class="form-group">
          <input type="text" class="form-control" v-model="form_dtm.so_quyet_dinh" placeholder="Số QĐ phê duyệt" />
          <span style="color: red;">{{ form_dtm.error_so_quyet_dinh }}
          </span>
        </div>
      </div>
      <div class="col-lg-2 col-md-6">
        <date-picker v-model="form_dtm.thoi_gian_phe_duyet" tabindex placeholder="Ngày ra quyết định"
          :config="optionsDate"></date-picker>
      </div>
      <div class="col-lg-3 col-md-10">
        <div class="form-group">
          <multiselect v-model="form_dtm.co_quan_phe_duyet" label="ten" track-by="id"
            placeholder="Gõ tên cơ quan phê duyệt" selectedLabel="Đã chọn" open-direction="bottom"
            :options="coquantochucs" :multiple="false" :searchable="true" :clear-on-select="true"
            :close-on-select="true" :show-no-results="false" :hide-selected="false" :tabindex="1">
            <span slot="noResult">Không tìm thấy kết quả</span>
          </multiselect>
        </div>
      </div>
      <div class="col-lg-1 col-md-2">
        <button type="button" class="btn btn-flat pull-right" tabindex="22" @click="addDtm()">
          <i class="fa fa-plus"></i> Thêm
        </button>
      </div>
    </div>
    <div class="col-md-12">
      <table class="table table-hover table-responsive">
        <thead>
          <tr class="row-table-header">
            <th>Loại thủ tục hành chính</th>
            <th>Số QĐ phê duyệt</th>
            <th>Cơ quan phê duyệt</th>
            <th>Ngày ra quyết định phê duyệt</th>
            <th style="width: 5%; text-align: center;" v-if="
              usersystem.role_code == 'admin' ||
                usersystem.role_code == 'nhanvientrungtam' ||
                usersystem.role_code == 'adminvaquanlydanhmuc'
            ">
              Xóa
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in co_so_san_xuat.danhsachthutuchanhchinh" :key="index">
          <td>{{ item.loai_thu_tuc ? item.loai_thu_tuc.ten : null  }}</td>
            <td>{{ item.so_quyet_dinh_phe_duyet }}</td>
            <td>
              {{
              item.co_quan_to_chuc
              ? item.co_quan_to_chuc.ten
              : null
              }}
            </td>
            <td>{{ item.thoi_gian_phe_duyet }}</td>
            <td align="center" v-if="
              usersystem.role_code == 'admin' ||
                usersystem.role_code == 'nhanvientrungtam' ||
                usersystem.role_code == 'adminvaquanlydanhmuc'
            ">
              <a @click="deleteDtm(index)">
                <i class="fa fa-trash-o"></i>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import moment from "../../../../../node_modules/admin-lte/bower_components/moment/moment.js";
import Multiselect from "vue-multiselect";
import datePicker from "vue-bootstrap-datetimepicker";
import * as env from "../../env.js";

export default {
  components: { Multiselect, datePicker },
  props: {
    index1: {},
    usersystem: {},
    value: { type: Object, default: () => ({}) },
    coquantochucs: { type: Array, default: () => [] },
    optionsDate: {}
  },
  data: () => ({
    form_dtm: {},
    form_dabvmt: {},
    form_pabvmt: {},
    form_gxnctbvmt: {},
    form_sdkcntctnn: {},
    form_gxnddkbvmtnkpl: {},
    form_gpxt: {},
    form_ttk: {},
    loaiThuTucs: [
      { name: 'Văn bản ĐTM', code: 'C_LoaiVanBanDTM' },
      { name: 'Loại giấy phép môi trường', code: 'C_LoaiGiayPhepMoiTruong' },
      { name: 'Văn bản khác (cũ)', code: 'khac' },
    ],
    loaiThuTuc: null,
    loaiVanBans: [],
    loaiVanBanSelect: [],
    loaiVanBan: null
  }),
  computed: {
    co_so_san_xuat: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("input", value);
      }
    }
  },
  mounted() {
    this.getThuTucHanhChinh()
  },
  methods: {
    getThuTucHanhChinh() {
      axios.get(env.endpointhttp + "loaithutuchanhchinhs").then(response => {
        this.loaiVanBans = response.data.result;
      });
    },
    changeLoaiThuTuc() {
      this.loaiVanBanSelect = [];
      if (this.loaiThuTuc) {
        this.loaiVanBanSelect = this.loaiVanBans.filter(el => el.phan_loai == this.loaiThuTuc.code)
      }
    },
    addDtm() {
      if (
        this.form_dtm.so_quyet_dinh == "" ||
        this.form_dtm.so_quyet_dinh == null
      ) {
        this.form_dtm.error_so_quyet_dinh = "Nhập số quyết định";
      } else {
        this.co_so_san_xuat.thutuchanhchinhs.dtm.push({
          so_quyet_dinh: this.form_dtm.so_quyet_dinh,
          co_quan_quyet_dinh: this.form_dtm.co_quan_phe_duyet,
          thoi_gian_phe_duyet: this.form_dtm.thoi_gian_phe_duyet
        });
        this.form_dtm.so_quyet_dinh = null;
        this.form_dtm.co_quan_phe_duyet = null;
        this.form_dtm.error_so_quyet_dinh = null;
        this.form_dtm.error_co_quan_phe_duyet = null;
        this.form_dtm.thoi_gian_phe_duyet = null;
      }
    },
    deleteDtm(index) {
      this.co_so_san_xuat.thutuchanhchinhs.dtm.splice(index, 1);
    },
    addDabvmt() {
      if (
        this.form_dabvmt.so_quyet_dinh == "" ||
        this.form_dabvmt.so_quyet_dinh == null
      ) {
        this.form_dabvmt.error_so_quyet_dinh = "Nhập số quyết định";
      } else {
        this.co_so_san_xuat.thutuchanhchinhs.dabvmt.push({
          so_quyet_dinh: this.form_dabvmt.so_quyet_dinh,
          co_quan_quyet_dinh: this.form_dabvmt.co_quan_phe_duyet,
          thoi_gian_phe_duyet: this.form_dabvmt.thoi_gian_phe_duyet
        });
        this.form_dabvmt.so_quyet_dinh = null;
        this.form_dabvmt.co_quan_phe_duyet = null;
        this.form_dabvmt.error_so_quyet_dinh = null;
        this.form_dabvmt.error_co_quan_phe_duyet = null;
        this.form_dabvmt.thoi_gian_phe_duyet = moment().format("DD/MM/YYYY");
      }
    },
    deleteDabvmt(index) {
      this.co_so_san_xuat.thutuchanhchinhs.dabvmt.splice(index, 1);
    },
    addPabvmt() {
      if (
        this.form_pabvmt.so_quyet_dinh == "" ||
        this.form_pabvmt.so_quyet_dinh == null
      ) {
        this.form_pabvmt.error_so_quyet_dinh = "Nhập số quyết định";
      } else {
        this.co_so_san_xuat.thutuchanhchinhs.pabvmt.push({
          so_quyet_dinh: this.form_pabvmt.so_quyet_dinh,
          co_quan_quyet_dinh: this.form_pabvmt.co_quan_phe_duyet,
          thoi_gian_phe_duyet: this.form_pabvmt.thoi_gian_phe_duyet
        });
        this.form_pabvmt.so_quyet_dinh = null;
        this.form_pabvmt.co_quan_phe_duyet = null;
        this.form_pabvmt.error_so_quyet_dinh = null;
        this.form_pabvmt.error_co_quan_phe_duyet = null;
        this.form_pabvmt.thoi_gian_phe_duyet = moment().format("DD/MM/YYYY");
      }
    },
    deletePabvmt(index) {
      this.co_so_san_xuat.thutuchanhchinhs.pabvmt.splice(index, 1);
    },

    addGxnctbvmt() {
      if (
        this.form_gxnctbvmt.so_quyet_dinh == "" ||
        this.form_gxnctbvmt.so_quyet_dinh == null
      ) {
        this.form_gxnctbvmt.error_so_quyet_dinh = "Nhập số quyết định";
      } else {
        this.co_so_san_xuat.thutuchanhchinhs.gxnctbvmt.push({
          so_quyet_dinh: this.form_gxnctbvmt.so_quyet_dinh,
          co_quan_quyet_dinh: this.form_gxnctbvmt.co_quan_phe_duyet,
          thoi_gian_phe_duyet: this.form_gxnctbvmt.thoi_gian_phe_duyet
        });
        this.form_gxnctbvmt.so_quyet_dinh = null;
        this.form_gxnctbvmt.co_quan_phe_duyet = null;
        this.form_gxnctbvmt.error_so_quyet_dinh = null;
        this.form_gxnctbvmt.error_co_quan_phe_duyet = null;
        this.form_gxnctbvmt.thoi_gian_phe_duyet = moment().format("DD/MM/YYYY");
      }
    },
    deleteGxnctbvmt(index) {
      this.co_so_san_xuat.thutuchanhchinhs.gxnctbvmt.splice(index, 1);
    },
    addSdkcntctnh() {
      if (
        this.form_sdkcntctnn.so_quyet_dinh == "" ||
        this.form_sdkcntctnn.so_quyet_dinh == null
      ) {
        this.form_sdkcntctnn.error_so_quyet_dinh = "Nhập số sổ đăng ký";
      } else {
        this.co_so_san_xuat.thutuchanhchinhs.sdkcntctnh.push({
          so_quyet_dinh: this.form_sdkcntctnn.so_quyet_dinh,
          co_quan_quyet_dinh: this.form_sdkcntctnn.co_quan_phe_duyet,
          thoi_gian_phe_duyet: this.form_sdkcntctnn.thoi_gian_phe_duyet
        });
        this.form_sdkcntctnn.so_quyet_dinh = null;
        this.form_sdkcntctnn.co_quan_phe_duyet = null;
        this.form_sdkcntctnn.error_so_quyet_dinh = null;
        this.form_sdkcntctnn.error_co_quan_phe_duyet = null;
        this.form_sdkcntctnn.thoi_gian_phe_duyet = moment().format(
          "DD/MM/YYYY"
        );
      }
    },
    deleteSdkcntctnh(index) {
      this.co_so_san_xuat.thutuchanhchinhs.sdkcntctnh.splice(index, 1);
    },
    addGxnddkbvmtnkpl() {
      if (
        this.form_gxnddkbvmtnkpl.so_quyet_dinh == "" ||
        this.form_gxnddkbvmtnkpl.so_quyet_dinh == null
      ) {
        this.form_gxnddkbvmtnkpl.error_so_quyet_dinh = "Nhập số giấy xác nhận";
      } else {
        this.co_so_san_xuat.thutuchanhchinhs.gxnddkbvmtnkpl.push({
          so_quyet_dinh: this.form_gxnddkbvmtnkpl.so_quyet_dinh,
          co_quan_quyet_dinh: this.form_gxnddkbvmtnkpl.co_quan_phe_duyet,
          thoi_gian_phe_duyet: this.form_gxnddkbvmtnkpl.thoi_gian_phe_duyet
        });
        this.form_gxnddkbvmtnkpl.so_quyet_dinh = null;
        this.form_gxnddkbvmtnkpl.co_quan_phe_duyet = null;
        this.form_gxnddkbvmtnkpl.error_so_quyet_dinh = null;
        this.form_gxnddkbvmtnkpl.error_co_quan_phe_duyet = null;
        this.form_gxnddkbvmtnkpl.thoi_gian_phe_duyet = moment().format(
          "DD/MM/YYYY"
        );
      }
    },
    deleteGxnddkbvmtnkpl(index) {
      this.co_so_san_xuat.thutuchanhchinhs.gxnddkbvmtnkpl.splice(index, 1);
    },
    addGpxt() {
      if (
        this.form_gpxt.so_quyet_dinh == "" ||
        this.form_gpxt.so_quyet_dinh == null
      ) {
        this.form_gpxt.error_so_quyet_dinh = "Nhập số giấy xác nhận";
      } else {
        this.co_so_san_xuat.thutuchanhchinhs.gpxt.push({
          so_quyet_dinh: this.form_gpxt.so_quyet_dinh,
          co_quan_quyet_dinh: this.form_gpxt.co_quan_phe_duyet,
          thoi_gian_phe_duyet: this.form_gpxt.thoi_gian_phe_duyet
        });
        this.form_gpxt.so_quyet_dinh = null;
        this.form_gpxt.co_quan_phe_duyet = null;
        this.form_gpxt.error_so_quyet_dinh = null;
        this.form_gpxt.error_co_quan_phe_duyet = null;
        this.form_gpxt.thoi_gian_phe_duyet = moment().format("DD/MM/YYYY");
      }
    },
    deleteGpxt(index) {
      this.co_so_san_xuat.thutuchanhchinhs.gpxt.splice(index, 1);
    },
    addTtk() {
      if (
        this.form_ttk.so_quyet_dinh == "" ||
        this.form_ttk.so_quyet_dinh == null
      ) {
        this.form_ttk.error_so_quyet_dinh = "Nhập số giấy xác nhận";
      } else {
        this.co_so_san_xuat.thutuchanhchinhs.ttk.push({
          so_quyet_dinh: this.form_ttk.so_quyet_dinh,
          co_quan_quyet_dinh: this.form_ttk.co_quan_phe_duyet,
          thoi_gian_phe_duyet: this.form_ttk.thoi_gian_phe_duyet,
          ghi_chu: this.form_ttk.ghi_chu
        });
        this.form_ttk.so_quyet_dinh = null;
        this.form_ttk.co_quan_phe_duyet = null;
        this.form_ttk.error_so_quyet_dinh = null;
        this.form_ttk.error_co_quan_phe_duyet = null;
        this.form_ttk.ghi_chu = null;
        this.form_ttk.thoi_gian_phe_duyet = moment().format("DD/MM/YYYY");
      }
    },
    deleteTtk(index) {
      this.co_so_san_xuat.thutuchanhchinhs.ttk.splice(index, 1);
    }
  }
};
</script>

<style scoped>

</style>
