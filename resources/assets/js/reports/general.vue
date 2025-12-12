<template>
  <div>
    <div class="d-flex justify-center page-header align-center">
      <h2 class="ma-0 title_master_form">Tra cứu tổng hợp</h2>
      <!-- <div class="flex-grow-0 d-flex align-center mr-2" @click="goToExport" style="cursor: pointer"
        data-toggle="tooltip" title="Xuất file báo cáo">
        <i class="fa fa-file-excel-o"></i>
      </div> -->

      <!-- <div class="flex-grow-0 d-flex align-center mr-2" @click="goFilter(!isFilter)" style="cursor: pointer"
        data-toggle="tooltip" title="Tra cứu">
        <i class="fa fa-filter"></i>
      </div> -->
      <div class="mr-5" style="font-size:medium">Từ ngày {{ tu_ngay ? tu_ngay : '__' }} tới ngày {{ den_ngay }}</div>
      <div @click="goToExport" class="btn bg-olive btn-flat mr-3">
        <i class="fa fa-file-excel-o mr-2"></i> Tra cứu trích xuất báo cáo
      </div>

      <div @click="goFilter(!isFilter)" class="btn bg-olive btn-flat">
        <i class="fa fa-filter"></i> Bộ lọc
      </div>
    </div>
    <div class="box-body">
      <div class="row">
        <div class="col-md-12">
          <div class="row block-multiple-rows">
            <div class="col-md-12 col-lg-12">
              <div class="row">
                <div class="col-lg-6" style="margin-top: 5px">
                  <strong>KẾT QUẢ TRA CỨU</strong>
                </div>
              </div>
              <hr style="margin-top: 5px; margin-bottom: 5px" />
              <div class="row">
                <div class="col-md-12 col-lg-12">
                  <div class="row">
                    <div class="col-lg-3 col-md-3">
                      <div class="small-box bg-green">
                        <div class="inner">
                          <h3>
                            {{
                              Object.values(this.datas.doan_thanh_tra).length
                            }}
                          </h3>
                          <p>Quyết định thành lập đoàn TT</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-legal"></i>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                      <div class="small-box bg-green">
                        <div class="inner">
                          <h3>{{ this.datas.tong_so_co_so | formatNumber }}</h3>
                          <p>Cơ sở đã thanh tra</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-map-marker"></i>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                      <div class="small-box bg-green">
                        <div class="inner">
                          <h3>{{ this.tong_so_ket_luan | formatNumber }}</h3>
                          <p>Tổng số kết luận</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-legal"></i>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                      <div class="small-box bg-green">
                        <div class="inner">
                          <h3>
                            {{
                              this.datas.ket_qua_thuc_hien_ket_luan_thanh_tra
                                .da_khac_phuc | formatNumber
                            }}
                          </h3>
                          <p>Cơ sở đã khắc phục</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-map-marker"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-3 col-md-3">
                      <div class="small-box bg-green">
                        <div class="inner">
                          <h3>
                            {{
                              this.datas.tong_so_co_so_bi_dinh_chi
                                | formatNumber
                            }}
                          </h3>
                          <p>Cơ sở bị đình chỉ</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-map-marker"></i>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                      <div class="small-box bg-green">
                        <div class="inner">
                          <h3>
                            {{
                              this.datas.tong_so_co_so_vi_pham | formatNumber
                            }}
                          </h3>
                          <p>Cơ sở vi phạm</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-map-marker"></i>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                      <div class="small-box bg-green">
                        <div class="inner">
                          <h3>
                            {{ this.datas.tong_so_tien_phat | formatMoney }}
                            <sup style="font-size: 20px">VNĐ</sup>
                          </h3>
                          <p>Tổng số tiền phạt</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-money"></i>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                      <div class="small-box bg-green">
                        <div class="inner">
                          <h3>
                            {{
                              this.datas.ket_qua_thuc_hien_ket_luan_thanh_tra
                                .chua_khac_phuc | formatNumber
                            }}
                          </h3>
                          <p>Cơ sở chưa khắc phục</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-map-marker"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- table -->
                <GeneralTable
                  :dataTable="this.datas"
                  @goOrder="goOrder"
                  @paginate="paginate"
                  :isLoadMore="isLoadMore"
                />
                <!-- table -->
                <div class="col-md-12 col-lg-7" v-show="false">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="row block-multiple-rows">
                        <div class="col-lg-12" style="margin-top: 5px">
                          <a
                            class="inherit"
                            style="text-decoration: none; cursor: pointer"
                            @click="
                              toggleInspectionTeam = !toggleInspectionTeam
                            "
                          >
                            <div class="d-flex">
                              <div>
                                <label>Đoàn thanh tra</label>
                                <p
                                  class="text-muted"
                                  v-if="
                                    datas.doan_thanh_tra &&
                                    Object.values(this.datas.doan_thanh_tra)
                                      .length > 0
                                  "
                                >
                                  {{
                                    "Tổng số đoàn thanh tra: " +
                                    Object.values(this.datas.doan_thanh_tra)
                                      .length
                                  }}
                                </p>
                              </div>
                              <span class="pull-right">
                                <i
                                  class="fa fa-angle-down"
                                  v-if="toggleInspectionTeam"
                                ></i>
                                <i class="fa fa-angle-up" v-else></i>
                              </span>
                            </div>
                          </a>
                          <hr style="margin-top: 7px; margin-bottom: 7px" />
                        </div>
                        <div
                          class="col-lg-12 data_bottom"
                          v-show="toggleInspectionTeam"
                        >
                          <table
                            class="table table-hover table-responsive"
                            border="0"
                          >
                            <tbody>
                              <tr class="row-table-header">
                                <th>Số QĐ thành lập đoàn thanh tra</th>
                                <th style="text-align: right">
                                  Tổng tiền phạt chính
                                  <span style="color: rgb(216, 27, 96)"
                                    >(VNĐ)</span
                                  >
                                </th>
                              </tr>
                              <tr
                                v-for="(item, index) in this.datas
                                  .doan_thanh_tra"
                              >
                                <td>{{ item.so_quyet_dinh }}</td>
                                <td style="text-align: right">
                                  {{ item.tong_so_tien_phat | formatMoney }}
                                </td>
                              </tr>
                              <tr
                                v-if="
                                  !datas.doan_thanh_tra ||
                                  datas.doan_thanh_tra.length <= 0
                                "
                              >
                                <td colspan="3">
                                  <p class="text-none-data">Không có dữ liệu</p>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 col-lg-12">
                      <div class="row block-multiple-rows">
                        <div class="col-lg-12" style="margin-top: 5px">
                          <a
                            class="inherit"
                            style="text-decoration: none; cursor: pointer"
                            @click="
                              toggleApprovedAgencyDTM = !toggleApprovedAgencyDTM
                            "
                          >
                            <div class="d-flex">
                              <div>
                                <label>Cơ quan phê duyệt ĐTM</label>
                                <p
                                  class="text-muted"
                                  v-if="
                                    datas.co_quan_phe_duyet &&
                                    datas.co_quan_phe_duyet.dtm &&
                                    Object.values(
                                      this.datas.co_quan_phe_duyet.dtm
                                    ).length > 0
                                  "
                                >
                                  {{
                                    "Tổng số: " +
                                    Object.values(
                                      this.datas.co_quan_phe_duyet.dtm
                                    ).length
                                  }}
                                </p>
                              </div>
                              <span class="pull-right">
                                <i
                                  class="fa fa-angle-down"
                                  v-if="toggleApprovedAgencyDTM"
                                ></i>
                                <i class="fa fa-angle-up" v-else></i>
                              </span>
                            </div>
                          </a>
                          <hr style="margin-top: 7px; margin-bottom: 7px" />
                        </div>
                        <div class="col-lg-12" v-show="toggleApprovedAgencyDTM">
                          <div style="overflow-y: scroll; max-height: 400px">
                            <table class="table" border="0">
                              <tbody>
                                <tr
                                  v-for="(item, index) in datas
                                    .co_quan_phe_duyet.dtm"
                                >
                                  <td>{{ index }}</td>
                                  <td style="text-align: right; width: 10%">
                                    <span class="badge bg-olive">
                                      {{ item }}
                                    </span>
                                  </td>
                                </tr>
                                <tr
                                  v-if="
                                    !datas.co_quan_phe_duyet.dtm ||
                                    datas.co_quan_phe_duyet.dtm.length <= 0
                                  "
                                >
                                  <td colspan="2">
                                    <p class="text-none-data">
                                      Không có dữ liệu
                                    </p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12 col-lg-12">
                      <div class="row block-multiple-rows">
                        <div class="col-lg-12" style="margin-top: 5px">
                          <a
                            class="inherit"
                            style="text-decoration: none; cursor: pointer"
                            @click="
                              toggleApprovedAgencyDABVMT =
                                !toggleApprovedAgencyDABVMT
                            "
                          >
                            <div class="d-flex">
                              <div>
                                <label>Cơ quan phê duyệt ĐABVMT</label>
                                <p
                                  class="text-muted"
                                  v-if="
                                    datas.co_quan_phe_duyet &&
                                    datas.co_quan_phe_duyet.dabvmt &&
                                    Object.values(
                                      this.datas.co_quan_phe_duyet.dabvmt
                                    ).length > 0
                                  "
                                >
                                  {{
                                    "Tổng số: " +
                                    Object.values(
                                      this.datas.co_quan_phe_duyet.dabvmt
                                    ).length
                                  }}
                                </p>
                              </div>
                              <span class="pull-right">
                                <i
                                  class="fa fa-angle-down"
                                  v-if="toggleApprovedAgencyDABVMT"
                                ></i>
                                <i class="fa fa-angle-up" v-else></i>
                              </span>
                            </div>
                          </a>
                          <hr style="margin-top: 7px; margin-bottom: 7px" />
                        </div>
                        <div
                          class="col-lg-12"
                          v-show="toggleApprovedAgencyDABVMT"
                        >
                          <div style="overflow-y: scroll; max-height: 400px">
                            <table class="table" border="0">
                              <tbody>
                                <tr
                                  v-for="(item, index) in datas
                                    .co_quan_phe_duyet.dabvmt"
                                >
                                  <td>{{ index }}</td>
                                  <td style="text-align: right; width: 10%">
                                    <span class="badge bg-olive">
                                      {{ item }}
                                    </span>
                                  </td>
                                </tr>
                                <tr
                                  v-if="
                                    !datas.co_quan_phe_duyet.dabvmt ||
                                    datas.co_quan_phe_duyet.dabvmt.length <= 0
                                  "
                                >
                                  <td colspan="2">
                                    <p class="text-none-data">
                                      Không có dữ liệu
                                    </p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 col-lg-12">
                      <div class="row block-multiple-rows">
                        <div class="col-lg-12" style="margin-top: 5px">
                          <a
                            class="inherit"
                            style="text-decoration: none; cursor: pointer"
                            @click="
                              toggleApprovedAgencyKHBVMT =
                                !toggleApprovedAgencyKHBVMT
                            "
                          >
                            <div class="d-flex">
                              <div>
                                <label>Cơ quan phê duyệt KHBVMT</label>
                                <p
                                  class="text-muted"
                                  v-if="
                                    datas.co_quan_phe_duyet &&
                                    datas.co_quan_phe_duyet.gxnctbvmt &&
                                    Object.values(
                                      this.datas.co_quan_phe_duyet.gxnctbvmt
                                    ).length > 0
                                  "
                                >
                                  {{
                                    "Tổng số: " +
                                    Object.values(
                                      this.datas.co_quan_phe_duyet.gxnctbvmt
                                    ).length
                                  }}
                                </p>
                              </div>
                              <span class="pull-right">
                                <i
                                  class="fa fa-angle-down"
                                  v-if="toggleApprovedAgencyKHBVMT"
                                ></i>
                                <i class="fa fa-angle-up" v-else></i>
                              </span>
                            </div>
                          </a>
                          <hr style="margin-top: 7px; margin-bottom: 7px" />
                        </div>
                        <div
                          class="col-lg-12"
                          v-show="toggleApprovedAgencyKHBVMT"
                        >
                          <div style="overflow-y: scroll; max-height: 400px">
                            <table class="table" border="0">
                              <tbody>
                                <tr
                                  v-for="(item, index) in datas
                                    .co_quan_phe_duyet.gxnctbvmt"
                                >
                                  <td>{{ index }}</td>
                                  <td style="text-align: right; width: 10%">
                                    <span class="badge bg-olive">
                                      {{ item }}
                                    </span>
                                  </td>
                                </tr>
                                <tr
                                  v-if="
                                    !datas.co_quan_phe_duyet.gxnctbvmt ||
                                    datas.co_quan_phe_duyet.gxnctbvmt.length <=
                                      0
                                  "
                                >
                                  <td colspan="2">
                                    <p class="text-none-data">
                                      Không có dữ liệu
                                    </p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12 col-lg-12">
                      <div class="row block-multiple-rows">
                        <div class="col-lg-12" style="margin-top: 5px">
                          <a
                            class="inherit"
                            style="text-decoration: none; cursor: pointer"
                            @click="
                              toggleApprovedAgencyKHBVMT =
                                !toggleApprovedAgencyKHBVMT
                            "
                          >
                            <div class="d-flex">
                              <div>
                                <label>Biện pháp xử phạt bổ sung</label>
                                <p
                                  class="text-muted"
                                  v-if="
                                    datas.xu_phat_bo_xung &&
                                    Object.values(this.datas.xu_phat_bo_xung)
                                      .length > 0
                                  "
                                >
                                  {{
                                    "Tổng số: " +
                                    Object.values(this.datas.xu_phat_bo_xung)
                                      .length
                                  }}
                                </p>
                              </div>
                              <span class="pull-right">
                                <i
                                  class="fa fa-angle-down"
                                  v-if="toggleApprovedAgencyKHBVMT"
                                ></i>
                                <i class="fa fa-angle-up" v-else></i>
                              </span>
                            </div>
                          </a>
                          <hr style="margin-top: 7px; margin-bottom: 7px" />
                        </div>
                        <div
                          class="col-lg-12"
                          v-show="toggleAdditionalSanctions"
                        >
                          <div style="overflow-y: scroll; max-height: 400px">
                            <table class="table" border="0">
                              <tbody>
                                <tr
                                  v-for="(item, index) in datas.xu_phat_bo_xung"
                                >
                                  <td>{{ index }}</td>
                                  <td style="text-align: right; width: 10%">
                                    <span class="badge bg-olive">
                                      {{ item }}
                                    </span>
                                  </td>
                                </tr>
                                <tr
                                  v-if="
                                    !datas.xu_phat_bo_xung ||
                                    datas.xu_phat_bo_xung.length <= 0
                                  "
                                >
                                  <td colspan="2">
                                    <p class="text-none-data">
                                      Không có dữ liệu
                                    </p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="row block-multiple-rows">
                        <div class="col-lg-12" style="margin-top: 5px">
                          <a
                            class="inherit"
                            style="text-decoration: none; cursor: pointer"
                            @click="
                              toggleResultsOfImplementing =
                                !toggleResultsOfImplementing
                            "
                          >
                            <div class="d-flex">
                              <div>
                                <label
                                  >Kết quả thực hiện kết luận thanh tra</label
                                >
                              </div>
                              <span class="pull-right">
                                <i
                                  class="fa fa-angle-down"
                                  v-if="toggleResultsOfImplementing"
                                ></i>
                                <i class="fa fa-angle-up" v-else></i>
                              </span>
                            </div>
                          </a>
                          <hr style="margin-top: 7px; margin-bottom: 7px" />
                        </div>
                        <div
                          class="col-lg-12"
                          v-show="toggleResultsOfImplementing"
                        >
                          <table class="table" border="0">
                            <tbody>
                              <tr>
                                <td>Đã nộp phạt</td>
                                <td style="text-align: right; width: 10%">
                                  <span class="badge bg-olive">
                                    {{
                                      datas.ket_qua_thuc_hien_ket_luan_thanh_tra
                                        .da_nop_phat
                                    }}
                                  </span>
                                </td>
                              </tr>
                              <tr>
                                <td>Đã khắc phục</td>
                                <td style="text-align: right; width: 10%">
                                  <span class="badge bg-orange">
                                    {{
                                      datas.ket_qua_thuc_hien_ket_luan_thanh_tra
                                        .da_khac_phuc
                                    }}
                                  </span>
                                </td>
                              </tr>
                              <tr>
                                <td>Đã báo cáo</td>
                                <td style="text-align: right; width: 10%">
                                  <span class="badge bg-orange">
                                    {{
                                      datas.ket_qua_thuc_hien_ket_luan_thanh_tra
                                        .da_bao_cao
                                    }}
                                  </span>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row" style="height: 145px">
                    <div class="col-md-12">
                      <div class="row block-multiple-rows">
                        <div class="col-lg-12" style="margin-top: 5px">
                          <a
                            class="inherit"
                            style="text-decoration: none; cursor: pointer"
                            @click="toggleRemedy = !toggleRemedy"
                          >
                            <div class="d-flex">
                              <div>
                                <label>Biện pháp khắc phục hậu quả</label>
                                <p
                                  class="text-muted"
                                  v-if="
                                    datas.khac_phuc_vi_pham &&
                                    Object.values(this.datas.khac_phuc_vi_pham)
                                      .length > 0
                                  "
                                >
                                  {{
                                    "Tổng số: " +
                                    Object.values(this.datas.khac_phuc_vi_pham)
                                      .length
                                  }}
                                </p>
                              </div>
                              <span class="pull-right">
                                <i
                                  class="fa fa-angle-down"
                                  v-if="toggleRemedy"
                                ></i>
                                <i class="fa fa-angle-up" v-else></i>
                              </span>
                            </div>
                          </a>
                          <hr style="margin-top: 7px; margin-bottom: 7px" />
                        </div>
                        <div class="col-md-12" v-show="toggleRemedy">
                          <div style="overflow-y: scroll; max-height: 600px">
                            <table class="table" border="0">
                              <tbody>
                                <tr
                                  v-for="(
                                    item, index
                                  ) in datas.khac_phuc_vi_pham"
                                >
                                  <td>{{ index }}</td>
                                  <td style="text-align: right; width: 10%">
                                    <span class="badge bg-olive">
                                      {{ item }}
                                    </span>
                                  </td>
                                </tr>
                                <tr
                                  v-if="
                                    !datas.khac_phuc_vi_pham ||
                                    datas.khac_phuc_vi_pham.length <= 0
                                  "
                                >
                                  <td colspan="2">
                                    <p class="text-none-data">
                                      Không có dữ liệu
                                    </p>
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
                <div class="col-md-12 col-lg-5" v-show="false">
                  <div class="row block-multiple-rows">
                    <div class="col-lg-12" style="margin-top: 5px">
                      <a
                        class="inherit"
                        style="text-decoration: none; cursor: pointer"
                        @click="toggleFacilityType = !toggleFacilityType"
                      >
                        <div class="d-flex">
                          <div>
                            <label>Loại hình cơ sở</label>
                            <p
                              class="text-muted"
                              v-if="
                                datas.loai_hinh_co_so &&
                                Object.values(this.datas.loai_hinh_co_so)
                                  .length > 0
                              "
                            >
                              {{
                                "Tổng số loại hình: " +
                                Object.values(this.datas.loai_hinh_co_so).length
                              }}
                            </p>
                          </div>
                          <span class="pull-right">
                            <i
                              class="fa fa-angle-down"
                              v-if="toggleFacilityType"
                            ></i>
                            <i class="fa fa-angle-up" v-else></i>
                          </span>
                        </div>
                      </a>
                      <hr style="margin-top: 7px; margin-bottom: 7px" />
                    </div>
                    <div
                      class="col-md-12 data_bottom"
                      v-show="toggleFacilityType"
                    >
                      <table class="table" border="0">
                        <tbody>
                          <tr v-for="(item, index) in datas.loai_hinh_co_so">
                            <td>{{ index }}</td>
                            <td style="text-align: right">
                              <span class="badge bg-olive">{{ item }}</span>
                            </td>
                          </tr>
                          <tr
                            v-if="
                              !datas.loai_hinh_co_so ||
                              datas.loai_hinh_co_so.length <= 0
                            "
                          >
                            <td colspan="2">
                              <p class="text-none-data">Không có dữ liệu</p>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="row block-multiple-rows">
                    <div class="col-md-12">
                      <div class="col-lg-12" style="margin-top: 5px">
                        <a
                          class="inherit"
                          style="text-decoration: none; cursor: pointer"
                          @click="toggleIndustrial = !toggleIndustrial"
                        >
                          <div class="d-flex">
                            <div>
                              <label>Khu công nghiệp</label>
                              <p
                                class="text-muted"
                                v-if="
                                  Object.values(this.datas.khu_cong_nghiep)
                                    .length > 0
                                "
                              >
                                {{
                                  "Tổng số KCN: " +
                                  Object.values(this.datas.khu_cong_nghiep)
                                    .length
                                }}
                              </p>
                            </div>
                            <span class="pull-right">
                              <i
                                class="fa fa-angle-down"
                                v-if="toggleIndustrial"
                              ></i>
                              <i class="fa fa-angle-up" v-else></i>
                            </span>
                          </div>
                        </a>
                        <hr style="margin-top: 7px; margin-bottom: 7px" />
                      </div>
                      <div class="col-md-12" v-show="toggleIndustrial">
                        <div style="overflow-y: scroll; max-height: 600px">
                          <table class="table" border="0">
                            <tbody>
                              <tr
                                v-for="(item, index) in datas.khu_cong_nghiep"
                              >
                                <td>{{ index }}</td>
                                <td style="text-align: right">
                                  <span class="badge bg-olive">{{ item }}</span>
                                </td>
                              </tr>
                              <tr
                                v-if="
                                  !datas.khu_cong_nghiep ||
                                  datas.khu_cong_nghiep.length <= 0
                                "
                              >
                                <td colspan="2">
                                  <p class="text-none-data">Không có dữ liệu</p>
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
        </div>
        <GeneralFilter
          @reset="goReset"
          @submit="goSubmit"
          @filter="goFilter"
          :show="isFilter"
          @change-data="changeData"
        />
      </div>
    </div>
  </div>
</template>

<style scoped>
.small-box .inner h3 {
  font-size: 26px;
}

tbody tr td {
  border: none;
}

.text-none-data {
  color: #31466a;
  font-style: italic;
}

.page-header {
  width: 100%;
}

.page-header i {
  cursor: pointer;
}

.control-label {
  margin: 1rem 0;
}

/deep/ .icheckbox_square-green {
  margin-right: 0.5rem !important;
}

/deep/ .form-group.ta-icheck label {
  display: unset !important;
}

/deep/ .form-group.ta-icheck {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.data_bottom {
  max-height: 600px;
  overflow: auto;
}

.data_bottom .table {
  position: relative;
}

.data_bottom .row-table-header th {
  position: sticky;
  top: 0;
  background-color: #f4f4f4;
}

.small-box .icon {
  top: 1px;
  font-size: 70px;
}
</style>

<script>
import * as env from "../env.js";
import Multiselect from "vue-multiselect";
import VueNumeric from "vue-numeric";
import money from "v-money";
import datePicker from "vue-bootstrap-datetimepicker";
import moment from "moment";
import GeneralTable from "./GeneralTable";
import GeneralFilter from "./GeneralFilter/GeneralFilter";

export default {
  props: ["data"],
  components: {
    Multiselect,
    VueNumeric,
    datePicker,
    GeneralTable,
    GeneralFilter,
  },
  data: function () {
    return {
      coQuanPheDuyet: {},
      paging: {
        page: 1,
        last_page: 1,
        total: 0,
        perpage: 100,
        current_page: 1,
      },
      search_ten: "",
      tong_so_ket_luan: 0,
      isFilter: false,
      optionsDate: {
        format: "DD/MM/YYYY",
        useCurrent: false,
        locale: "vi",
      },
      money: {
        decimal: ".",
        thousands: ",",
        prefix: "",
        suffix: "",
        precision: 0,
        masked: false,
      },
      mien: {
        value: [],
        options: [],
        data: [],
        isLoading: true,
      },
      loaiVanBan: {
        value: [],
        options: [],
        data: [],
        isLoading: true,
      },
      tinh: {
        value: [],
        options: [],
        data: [],
        isLoading: true,
      },
      luuvucsong: {
        value: [],
        options: [],
        data: [],
        isLoading: true,
      },
      vungkinhtes: {
        value: [],
        options: [],
        data: [],
        isLoading: true,
      },
      doanthanhtra: {
        value: [],
        options: [],
        isLoading: false,
      },
      coquantochucs: {
        isLoading: true,
      },
      coquanpheduyetdtm: {
        value: [],
        options: [],
        cap_bo: false,
        cap_dia_phuong: false,
      },
      coquanpheduyetdeanbvmt: {
        value: [],
        options: [],
        cap_bo: false,
        cap_dia_phuong: false,
      },
      coquanpheduyetkehoachbvmt: {
        value: [],
        options: [],
        cap_bo: false,
        cap_dia_phuong: false,
      },
      ky_thanh_tra: {
        dot_xuat: true,
        dinh_ky: true,
      },
      loaihinhcoso: {
        value: [],
        options: [],
        isLoading: true,
      },
      loaiHinhONhiem: {
        value: [],
        options: [],
        isLoading: true,
      },
      khucongnghiep: {
        value: [],
        options: [],
        isLoading: true,
        data: [],
      },
      bienphapxuphatboxung: {
        value: [],
        options: [],
        isLoading: true,
        dinh_chi: false,
      },
      bienphapkhacphuchauqua: {
        value: [],
        options: [],
        isLoading: true,
      },
      ketquakhacphuchauqua: {
        nop_mot_phan: false,
        da_nop_phat: false,
        da_khac_phuc: false,
        da_bao_cao: false,
      },
      ketquathuchien: {
        vi_pham_thu_tuc_hanh_chinh: false,
        dtm_dean_kehoach_bvmt: {
          filter: null,
          dtmdakhbvmt_thuc_hien_khong_dung_noi_dung: false,
          dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung: false,
        },
        khong_co_giay_phep_xa_thai: false,
        co_ket_hoach_quan_ly_moi_truong: false,
        congtrinhBVMT: {
          khong_xay_lap: false,
          xay_lap_khong_dung: false,
          khong_co_giay_xac_nhan_hoan_thanh: false,
          van_hanh_khong_dung_khong_thuong_xuyen: false,
        },
        gsmt: {
          gsmt_khong_thuc_hien: false,
          gsmt_thuc_hien: false,
          gsmt_thuc_hien_khong_dung_khong_du: false,
        },
        giay_xac_nhan: {
          khong_thuc_hien_giay_xac_nhan: false,
          thuc_hien_sai_giay_xac_nhan: false,
        },
        // vi pham xa thai
        vi_pham_nhom_hanh_vi_xa_thai: false,
        nuoc_thai: {
          co_he_thong_thu_gom_nuoc_thai_rieng_biet: false,
          nuoc_thai_vuot: false,
          nuoc_thai_vuot_qcvn_tu: -1,
          nuoc_thai_vuot_qcvn_toi: -1,
        },
        khi_thai: {
          co_bien_phap_xu_ly_khi_thai: false,
          khi_thai_vuot: false,
          khi_thai_vuot_qcvn_tu: -1,
          khi_thai_vuot_qcvn_toi: -1,
        },
        ctrsh: {
          ctrsh_co_thu_gom: false,
          ctrsh_co_phan_loai: false,
          ctrsh_co_luu_tru: false,
          ctrsh_co_chuyen_giao: false,
        },
        ctrcn: {
          ctrcn_co_thu_gom: false,
          ctrcn_co_phan_loai: false,
          ctrcn_co_luu_tru: false,
          ctrcn_co_chuyen_giao: false,
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
          ctrnh_co_giay_phep: false,
        },
        vi_pham_nhom_hanh_vi_khac: false,
        vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong: false,
        vi_pham_cac_quy_dinh_bao_ve_moi_truong: false,
        co_hanh_vi_can_tro_ve_bvmt: false,
      },
      ket_luan: {
        vi_pham: false,
      },
      dieu: {
        value: [],
        options: [],
        isLoading: true,
      },
      khoan: {
        value: [],
        options: [],
        isLoading: false,
      },
      muc: {
        value: [],
        options: [],
        isLoading: false,
      },
      donvics: {
        value: [],
        options: [],
        isLoading: false,
      },
      luu_luong_nuoc_thai: 0,
      luu_luong_nuoc_thai_max: null,
      luu_luong_khi_thai: 0,
      luu_luong_khi_thai_max: null,
      cong_suat_hoat_dong_max: null,
      donvi: null,
      ten_don_vi_nuoc: null,
      ten_don_vi_khi: null,
      errors_donvi: null,
      datas: {
        ky_thanh_tra: [],
        mien: [],
        ket_qua_thuc_hien_ket_luan_thanh_tra: [],
        co_quan_phe_duyet: [],
        danh_sach_ket_qua_thanh_tra: [],
      },
      search_year: null,
      tu_ngay: null,
      den_ngay: null,
      show_search_advanced: false,
      show_search_basic: false,
      show_co_quan_phe_duyet: false,
      show_search_thu_tuc_hanh_chinh: false,
      show_search_vi_pham: false,
      show_search_vi_pham_khac: false,
      toggleInspectionTeam: false,
      toggleFacilityType: false,
      toggleIndustrial: false,
      toggleApprovedAgencyDTM: false,
      toggleApprovedAgencyDABVMT: false,
      toggleApprovedAgencyKHBVMT: false,
      toggleAdditionalSanctions: false,
      toggleResultsOfImplementing: false,
      toggleRemedy: false,
      order_column: "ten",
      order_direction: "asc",
      page: 1,
      isLoadMore: false,
    };
  },
  created: function () {
    this.datas = this.data;
  },
  mounted() {
    this.changeDate()
    // mien
    if (localStorage.getItem("miens")) {
      this.mien.isLoading = false;
      this.mien.options = JSON.parse(localStorage.getItem("miens"));
      this.mien.data = this.mien.options;
    } else {
      this.mien.isLoading = true;
      axios
        .get(env.endpointhttp + "miens")
        .then((response) => {
          this.mien.options = response.data.result;
          this.mien.data = this.mien.options;
          localStorage.setItem("miens", JSON.stringify(this.mien.options));
        })
        .catch((error) => {})
        .finally(() => (this.mien.isLoading = false));
    }

    // tinh thanh
    if (localStorage.getItem("tinhthanhs")) {
      this.tinh.isLoading = false;
      this.tinh.options = JSON.parse(localStorage.getItem("tinhthanhs"));
      this.tinh.data = this.tinh.options;
    } else {
      this.tinh.isLoading = true;
      axios
        .get(env.endpointhttp + "tinhthanhs")
        .then((response) => {
          this.tinh.options = response.data.result;
          this.tinh.data = this.tinh.options;
          localStorage.setItem("tinhthanhs", JSON.stringify(this.tinh.options));
        })
        .catch((error) => {})
        .finally(() => (this.tinh.isLoading = false));
    }

    // luu vuc song
    if (localStorage.getItem("luuvucsongs")) {
      this.luuvucsong.isLoading = false;
      this.luuvucsong.options = JSON.parse(localStorage.getItem("luuvucsongs"));
    } else {
      this.luuvucsong.isLoading = true;
      axios
        .get(env.endpointhttp + "luuvucsongs")
        .then((response) => {
          this.luuvucsong.options = response.data.result;
          localStorage.setItem(
            "luuvucsongs",
            JSON.stringify(this.luuvucsong.options)
          );
        })
        .catch((error) => {})
        .finally(() => (this.luuvucsong.isLoading = false));
    }

    // vung kinh te trong diem
    if (localStorage.getItem("vungkinhtetrongdiems")) {
      this.vungkinhtes.isLoading = false;
      this.vungkinhtes.options = JSON.parse(
        localStorage.getItem("vungkinhtetrongdiems")
      );
    } else {
      this.vungkinhtes.isLoading = true;
      axios
        .get(env.endpointhttp + "vungkinhtetrongdiems")
        .then((response) => {
          this.vungkinhtes.options = response.data.result;
          localStorage.setItem(
            "vungkinhtetrongdiems",
            JSON.stringify(this.vungkinhtes.options)
          );
        })
        .catch((error) => {})
        .finally(() => (this.vungkinhtes.isLoading = false));
    }

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
        .then((response) => {
          this.coquanpheduyetdtm.options = response.data.result;
          this.coquanpheduyetdeanbvmt.options = response.data.result;
          this.coquanpheduyetkehoachbvmt.options = response.data.result;
          localStorage.setItem(
            "coquantochucs",
            JSON.stringify(response.data.result)
          );
        })
        .catch((error) => {})
        .finally(() => (this.coquantochucs.isLoading = false));
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
        .then((response) => {
          this.khucongnghiep.options = response.data.result;
          this.khucongnghiep.data = this.khucongnghiep.options;
          localStorage.setItem(
            "asynkhucongnghiep",
            JSON.stringify(this.khucongnghiep.options)
          );
        })
        .catch((error) => {})
        .finally(() => (this.khucongnghiep.isLoading = false));
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
        .then((response) => {
          this.loaihinhcoso.options = response.data.result;
          localStorage.setItem(
            "loaihinhcosos?type=report",
            JSON.stringify(this.loaihinhcoso.options)
          );
        })
        .catch((error) => {})
        .finally(() => (this.loaihinhcoso.isLoading = false));
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
        .then((response) => {
          this.bienphapxuphatboxung.options = response.data.result;
          localStorage.setItem(
            "loaixuphatbosungs",
            JSON.stringify(this.bienphapxuphatboxung.options)
          );
        })
        .catch((error) => {})
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
        .then((response) => {
          this.bienphapkhacphuchauqua.options = response.data.result;
          localStorage.setItem(
            "cacbienphapkhacphuchauquas",
            JSON.stringify(this.bienphapkhacphuchauqua.options)
          );
        })
        .catch((error) => {})
        .finally(() => (this.bienphapkhacphuchauqua.isLoading = false));
    }

    // dieus
    if (localStorage.getItem("dieus")) {
      this.dieu.isLoading = false;
      this.dieu.options = JSON.parse(localStorage.getItem("dieus"));
    } else {
      this.dieu.isLoading = true;
      axios
        .get(env.endpointhttp + "cacbienphapkhacphuchauquas")
        .then((response) => {
          this.dieu.options = response.data.result;
          localStorage.setItem(
            "cacbienphapkhacphuchauquas",
            JSON.stringify(this.dieu.options)
          );
        })
        .catch((error) => {})
        .finally(() => (this.dieu.isLoading = false));
    }

    axios.get(env.endpointhttp + "danhmuc/chuyendoidonvis").then((response) => {
      this.ten_don_vi_nuoc = response.data.result.don_vi_goc.luu_luong_nuoc_thai
        ? response.data.result.don_vi_goc.luu_luong_nuoc_thai.ten
        : "---";
      this.ten_don_vi_khi = response.data.result.don_vi_goc.luu_luong_khi_thai
        ? response.data.result.don_vi_goc.luu_luong_khi_thai.ten
        : "---";
    });
  },
  computed: {},
  watch: {
    "datas.danh_sach_ket_qua_thanh_tra": {
      handler(val) {
        this.tong_so_ket_luan = [
          ...(val.map((x) => x.so_quyet_dinh)),
        ].length;
      },
      deep: true,
    },
  },
  methods: {
    goToExport() {
      var url = env.endpointhttp + "baocaotonghop/xemtruoc";
      window.location.href = url;
    },
    goReset() {
      Object.assign(this.$data, this.$options.data());
      this.changeDate();
    },
    goSubmit() {
      (this.page = 1),
        (this.order_column = "ten"),
        (this.order_direction = "asc");
      this.changeDate();
    },
    changeData(val) {
      Object.assign(this.$data, val);
    },
    goFilter(val) {
      this.isFilter = val;
    },
    getMapOrganization(value) {
      window.open("/cososanxuat/tracuu?cososanxuat=" + value);
    },
    changeYear() {
      this.tu_ngay = "1/1/" + this.search_year;
      this.den_ngay = "31/12/" + this.search_year;
    },
    getProvince() {
      if (this.mien.value.length > 0) {
        this.tinh.value = [];
        this.tinh.options = this.tinh.data.filter(function (
          currentValue,
          index,
          arr
        ) {
          return (
            this.findIndex(function (mien) {
              return currentValue.mien_id == mien.id;
            }) >= 0
          );
        },
        this.mien.value);
      } else {
        this.tinh.options = this.tinh.data;
      }
    },
    changeDieu() {
      this.khoan.value = [];
      this.khoan.options = this.dieu.value.khoans;
    },
    changeKhoan() {
      this.muc.value = [];
      this.muc.options = this.khoan.value.mucs;
    },
    changeTinh() {
      this.khucongnghiep.value = [];
      this.khucongnghiep.options = this.khucongnghiep.data.filter(function (
        currentValue,
        index,
        arr
      ) {
        return (
          this.findIndex(function (tinh) {
            return currentValue.tinh_thanh_id == tinh.id;
          }) >= 0
        );
      },
      this.tinh.value);
    },
    limitText(count) {
      return `và ${count} đoàn thanh tra khác`;
    },
    asyncFindDoanThanhTra(query) {
      if (query) {
        this.doanthanhtra.isLoading = true;
        axios
          .get(env.endpointhttp + "asyncdoanthanhtra?search=" + query)
          .then((response) => {
            this.doanthanhtra.options = response.data.result;
          })
          .catch((error) => {
            console.log(error);
          })
          .finally(() => (this.doanthanhtra.isLoading = false));
      }
    },
    clearAllDoanThanhTra() {
      this.doanthanhtra.options = [];
      this.doanthanhtra.value = [];
    },
    changeDate() {
      var strUrl = this.getUrl();
      axios
        .get(env.endpointhttp + "baocaotonghop?" + strUrl)
        .then((response) => {
          this.datas = response.data.result;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    downloadExcel(e) {
      var url = "excel/baocaotonghop?";
      var strUrl = this.getUrl();
      axios
        .get(env.endpointhttp + "baocaotonghop?" + strUrl, this.data, {
          headers: {
            Accept: "xlsx",
          },
        })
        .then((res) => {})
        .catch((err) => {});

      $(".close").click();
      window.location.href = url + strUrl;
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
      this.coquanpheduyetkehoachbvmt.value =
        coQuanCapBo.concat(coQuanDiaPhuong);
    },
    toggleSearchAdvanced() {
      this.show_search_advanced = !this.show_search_advanced;
    },
    toggleSearchBasic() {
      this.show_search_basic = !this.show_search_basic;
    },
    getUrl() {
      var tu_ngays = this.tu_ngay;
      var den_ngays = this.den_ngay;
      var strUrl = "";
      if (this.order_column && this.order_direction) {
        strUrl += `${strUrl}order_column=${this.order_column}&&order_direction=${this.order_direction}&&`;
      }
      if (!(this.paging.current_page == 1)) {
        strUrl += `${strUrl}page=${this.paging.current_page}&&`;
      }
      if (!(this.paging.perpage == 100)) {
        strUrl += `${strUrl}perpage=${this.paging.perpage}&&`;
      }
      if (this.search_ten) {
        strUrl = strUrl + "search_ten=" + this.search_ten + "&";
      }
      if (this.mien.value) {
        var mienIds = [];
        this.mien.value.forEach((element) => {
          mienIds.push(element.id);
        });
        if (mienIds !== undefined && mienIds.length > 0) {
          strUrl = strUrl + "search_mien=" + mienIds + "&";
        }
      }
      if (this.tinh.value) {
        var tinhIds = [];
        this.tinh.value.forEach((element) => {
          tinhIds.push(element.id);
        });
        if (tinhIds !== undefined && tinhIds.length > 0) {
          strUrl = strUrl + "search_tinh=" + tinhIds + "&";
        }
      }
      if (this.luuvucsong.value) {
        var luuvucsongIds = [];
        this.luuvucsong.value.forEach((element) => {
          luuvucsongIds.push(element.id);
        });
        if (luuvucsongIds !== undefined && luuvucsongIds.length > 0) {
          strUrl = strUrl + "search_luu_vuc_song=" + luuvucsongIds + "&";
        }
      }
      if (this.vungkinhtes.value) {
        var vungkinhteIds = [];
        this.vungkinhtes.value.forEach((element) => {
          vungkinhteIds.push(element.id);
        });
        strUrl = strUrl + "search_vungkinhte=" + vungkinhteIds + "&";
      }
      if (
        this.coquanpheduyetdtm.value != null &&
        this.coquanpheduyetdtm.value.length > 0
      ) {
        var coquanpheduyetdtmIds = [];
        this.coquanpheduyetdtm.value.forEach((element) => {
          coquanpheduyetdtmIds.push(element.id);
        });
        strUrl =
          strUrl + "search_co_quan_phe_duyet_dtm=" + coquanpheduyetdtmIds + "&";
      }
      if (
        this.coquanpheduyetdeanbvmt.value != null &&
        this.coquanpheduyetdeanbvmt.value.length > 0
      ) {
        var coquanpheduyetdeanbvmtIds = [];
        this.coquanpheduyetdeanbvmt.value.forEach((element) => {
          coquanpheduyetdeanbvmtIds.push(element.id);
        });
        strUrl =
          strUrl +
          "search_co_quan_phe_duyet_dabvmt=" +
          coquanpheduyetdeanbvmtIds +
          "&";
      }
      if (
        this.coquanpheduyetkehoachbvmt.value != null &&
        this.coquanpheduyetkehoachbvmt.value.length > 0
      ) {
        var coquanpheduyetkehoachbvmtIds = [];
        this.coquanpheduyetkehoachbvmt.value.forEach((element) => {
          coquanpheduyetkehoachbvmtIds.push(element.id);
        });
        strUrl =
          strUrl +
          "search_co_quan_phe_duyet_gxnctbvmt=" +
          coquanpheduyetkehoachbvmtIds +
          "&";
      }
      if (this.dieu.value != null) {
        if ((this.dieu.value.length > 0) | this.dieu.value.id) {
          strUrl = strUrl + "search_dieu=" + this.dieu.value.id + "&";
        }
      }
      if (this.khoan.value != null) {
        if ((this.khoan.value.length > 0) | this.khoan.value.id) {
          strUrl = strUrl + "search_khoan=" + this.khoan.value.id + "&";
        }
      }
      if (this.muc.value != null) {
        if (this.muc.value.length > 0) {
          var mucIds = [];
          this.muc.value.forEach((element) => {
            mucIds.push(element.id);
          });
          strUrl = strUrl + "search_muc=" + mucIds + "&";
        }
      }

      if (this.doanthanhtra.value) {
        var doanthanhtraIds = [];
        this.doanthanhtra.value.forEach((element) => {
          doanthanhtraIds.push(element.id);
        });
        strUrl = strUrl + "search_doan_thanh_tra=" + doanthanhtraIds + "&";
      }

      if (this.loaihinhcoso.value) {
        console.log(2, this.loaihinhcoso.value)
        var loaihinhcosoIds = [];
        this.loaihinhcoso.value.forEach((element) => {
          loaihinhcosoIds.push(element.id);
        });
        strUrl = strUrl + "search_loai_hinh_co_so=" + loaihinhcosoIds + "&";
      }
      if (this.loaiHinhONhiem.value) {
        var loaihinhonhiemIds = [];
        this.loaiHinhONhiem.value.forEach((element) => {
          loaihinhonhiemIds.push(element.id);
        });
        strUrl = strUrl + "search_loai_hinh_o_nhiem=" + loaihinhonhiemIds + "&";
      }

      if (this.khucongnghiep.value) {
        var khucongnghiepIds = [];
        this.khucongnghiep.value.forEach((element) => {
          khucongnghiepIds.push(element.ma);
        });
        strUrl = strUrl + "search_khu_cong_nghiep=" + khucongnghiepIds + "&";
      }
      if (this.bienphapxuphatboxung.value) {
        var bienphapxuphatboxungIds = [];
        this.bienphapxuphatboxung.value.forEach((element) => {
          bienphapxuphatboxungIds.push(element.id);
        });
        strUrl =
          strUrl +
          "search_bien_phap_xu_phat_bo_xung=" +
          bienphapxuphatboxungIds +
          "&";
      }

      if (this.bienphapxuphatboxung.dinh_chi) {
        strUrl += "bienphapxuphatboxung_dinh_chi=1&";
      }

      if (this.ketquakhacphuchauqua) {
        if (this.ketquakhacphuchauqua.nop_mot_phan) {
          strUrl += "ketquakhacphuchauqua_nop_mot_phan=1&";
        }
        if (this.ketquakhacphuchauqua.da_nop_phat) {
          strUrl += "ketquakhacphuchauqua_da_nop_phat=1&";
        }
        if (this.ketquakhacphuchauqua.da_khac_phuc) {
          strUrl += "ketquakhacphuchauqua_da_khac_phuc=1&";
        }
        if (this.ketquakhacphuchauqua.da_bao_cao) {
          strUrl += "ketquakhacphuchauqua_da_bao_cao=1&";
        }
      }
      if (this.ket_luan) {
        if (this.ket_luan.vi_pham) {
          strUrl += "vi_pham=1&";
        }
      }
      // thu tuc hanh chinh
      if (this.ketquathuchien.vi_pham_thu_tuc_hanh_chinh == true) {
        strUrl += "vi_pham_thu_tuc_hanh_chinh=1&";
      }
      if (this.ketquathuchien.dtm_dean_kehoach_bvmt.filter != null) {
        strUrl +=
          "dtm_dean_khbvmt_filter=" +
          this.ketquathuchien.dtm_dean_kehoach_bvmt.filter.id +
          "&";
      }

      if (
        this.ketquathuchien.dtm_dean_kehoach_bvmt
          .dtmdakhbvmt_thuc_hien_khong_dung_noi_dung == true
      ) {
        strUrl += "dtmdakhbvmt_thuc_hien_khong_dung_noi_dung=1&";
      }
      if (
        this.ketquathuchien.dtm_dean_kehoach_bvmt
          .dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung == true
      ) {
        strUrl += "dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung=1&";
      }
      if (this.ketquathuchien.khong_co_giay_phep_xa_thai == true) {
        strUrl += "khong_co_giay_phep_xa_thai=1&";
      }
      if (this.ketquathuchien.co_ket_hoach_quan_ly_moi_truong == true) {
        strUrl += "co_ket_hoach_quan_ly_moi_truong=1&";
      }

      if (
        this.ketquathuchien.giay_xac_nhan.khong_thuc_hien_giay_xac_nhan == true
      ) {
        strUrl += "khong_thuc_hien_giay_xac_nhan=1&";
      }
      if (
        this.ketquathuchien.giay_xac_nhan.thuc_hien_sai_giay_xac_nhan == true
      ) {
        strUrl += "thuc_hien_sai_giay_xac_nhan=1&";
      }

      if (this.ketquathuchien.congtrinhBVMT.khong_xay_lap == true) {
        strUrl += "congtrinhBVMT_khong_xay_lap=1&";
      }
      if (this.ketquathuchien.congtrinhBVMT.xay_lap_khong_dung == true) {
        strUrl += "congtrinhBVMT_xay_lap_khong_dung=1&";
      }
      if (
        this.ketquathuchien.congtrinhBVMT
          .van_hanh_khong_dung_khong_thuong_xuyen == true
      ) {
        strUrl += "congtrinhBVMT_van_hanh_khong_dung_khong_thuong_xuyen=1&";
      }
      if (
        this.ketquathuchien.congtrinhBVMT.khong_co_giay_xac_nhan_hoan_thanh ==
        true
      ) {
        strUrl += "khong_co_giay_xac_nhan_hoan_thanh=1&";
      }

      // gsmt
      if (this.ketquathuchien.gsmt.gsmt_thuc_hien == true) {
        strUrl += "gsmt_thuc_hien=1&";
      }
      if (this.ketquathuchien.gsmt.gsmt_khong_thuc_hien == true) {
        strUrl += "gsmt_khong_thuc_hien=1&";
      }
      if (this.ketquathuchien.gsmt.gsmt_thuc_hien_khong_dung_khong_du == true) {
        strUrl += "gsmt_thuc_hien_khong_dung_khong_du=1&";
      }
      if (this.ketquathuchien.vi_pham_nhom_hanh_vi_xa_thai == true) {
        strUrl += "vi_pham_nhom_hanh_vi_xa_thai=1&";
      }
      // nuoc thai
      if (
        this.ketquathuchien.nuoc_thai
          .co_he_thong_thu_gom_nuoc_thai_rieng_biet == true
      ) {
        strUrl += "co_he_thong_thu_gom_nuoc_thai_rieng_biet=1&";
      }
      if (this.ketquathuchien.nuoc_thai.nuoc_thai_vuot == true) {
        strUrl += "nuoc_thai_vuot=1&";
      }
      // if (this.ketquathuchien.nuoc_thai.chi_tiet_vi_pham_xa_thais.length >= 0) {
      //   this.ketquathuchien.nuoc_thai.chi_tiet_vi_pham_xa_thais.map(
      //     (item, key) => {
      //       strUrl +=
      //         "nuoc_thai_vi_pham_xa_thai[" +
      //         key +
      //         "][thong_so_id]=" +
      //         item.thong_so_id +
      //         "&";
      //       strUrl +=
      //         "nuoc_thai_vi_pham_xa_thai[" +
      //         key +
      //         "][so_lan_vuot_tu]=" +
      //         item.so_lan_vuot_tu +
      //         "&";
      //       strUrl +=
      //         "nuoc_thai_vi_pham_xa_thai[" +
      //         key +
      //         "][so_lan_vuot_den]=" +
      //         item.so_lan_vuot_den +
      //         "&";
      //     }
      //   );
      // }

      // khi thai
      if (this.ketquathuchien.khi_thai.co_bien_phap_xu_ly_khi_thai == true) {
        strUrl += "co_bien_phap_xu_ly_khi_thai=1&";
      }

      if (this.ketquathuchien.khi_thai.khi_thai_vuot == true) {
        strUrl += "khi_thai_vuot=1&";
      }

      // if (this.ketquathuchien.khi_thai.chi_tiet_vi_pham_xa_thais.length >= 0) {
      //   this.ketquathuchien.khi_thai.chi_tiet_vi_pham_xa_thais.map(
      //     (item, key) => {
      //       strUrl +=
      //         "khi_thai_vi_pham_xa_thai[" +
      //         key +
      //         "][thong_so_id]=" +
      //         item.thong_so_id +
      //         "&";
      //       strUrl +=
      //         "khi_thai_vi_pham_xa_thai[" +
      //         key +
      //         "][so_lan_vuot_tu]=" +
      //         item.so_lan_vuot_tu +
      //         "&";
      //       strUrl +=
      //         "khi_thai_vi_pham_xa_thai[" +
      //         key +
      //         "][so_lan_vuot_den]=" +
      //         item.so_lan_vuot_den +
      //         "&";
      //     }
      //   );
      // }

      // ctrsh
      if (this.ketquathuchien.ctrsh.ctrsh_co_thu_gom == true) {
        strUrl += "ctrsh_co_thu_gom=1&";
      }
      if (this.ketquathuchien.ctrsh.ctrsh_co_phan_loai == true) {
        strUrl += "ctrsh_co_phan_loai=1&";
      }
      if (this.ketquathuchien.ctrsh.ctrsh_co_luu_tru == true) {
        strUrl += "ctrsh_co_luu_tru=1&";
      }
      if (this.ketquathuchien.ctrsh.ctrsh_co_chuyen_giao == true) {
        strUrl += "ctrsh_co_chuyen_giao=1&";
      }

      // ctrcn
      if (this.ketquathuchien.ctrcn.ctrcn_co_thu_gom == true) {
        strUrl += "ctrcn_co_thu_gom=1&";
      }
      if (this.ketquathuchien.ctrcn.ctrcn_co_phan_loai == true) {
        strUrl += "ctrcn_co_phan_loai=1&";
      }
      if (this.ketquathuchien.ctrcn.ctrcn_co_luu_tru == true) {
        strUrl += "ctrcn_co_luu_tru=1&";
      }
      if (this.ketquathuchien.ctrcn.ctrcn_co_chuyen_giao == true) {
        strUrl += "ctrcn_co_chuyen_giao=1&";
      }
      //ctnh
      if (this.ketquathuchien.ctnh.ctrnh_vi_pham_chung_tu == true) {
        strUrl += "ctrnh_vi_pham_chung_tu=1&";
      }
      if (this.ketquathuchien.ctnh.ctrnh_co_thu_gom == true) {
        strUrl += "ctrnh_co_thu_gom=1&";
      }
      if (this.ketquathuchien.ctnh.ctrnh_co_phan_loai == true) {
        strUrl += "ctrnh_co_phan_loai=1&";
      }
      if (this.ketquathuchien.ctnh.ctrnh_co_luu_tru == true) {
        strUrl += "ctrnh_co_luu_tru=1&";
      }
      if (this.ketquathuchien.ctnh.ctrnh_co_de_lan == true) {
        strUrl += "ctrnh_co_de_lan=1&";
      }
      if (this.ketquathuchien.ctnh.ctrnh_co_chuyen_giao == true) {
        strUrl += "ctrnh_co_chuyen_giao=1&";
      }
      if (this.ketquathuchien.ctnh.ctrnh_co_chon_lap == true) {
        strUrl += "ctrnh_co_chon_lap=1&";
      }
      if (this.ketquathuchien.ctnh.ctrnh_co_do_thai == true) {
        strUrl += "ctrnh_co_do_thai=1&";
      }
      if (this.ketquathuchien.ctnh.ctrnh_co_giay_phep == true) {
        strUrl += "ctrnh_co_giay_phep=1&";
      }
      // nhom hanh vi khac
      if (this.ketquathuchien.vi_pham_nhom_hanh_vi_khac == true) {
        strUrl += "vi_pham_nhom_hanh_vi_khac=1&";
      }
      if (
        this.ketquathuchien
          .vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong == true
      ) {
        strUrl +=
          "vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong=1&";
      }
      if (this.ketquathuchien.vi_pham_cac_quy_dinh_bao_ve_moi_truong == true) {
        strUrl += "vi_pham_cac_quy_dinh_bao_ve_moi_truong=1&";
      }
      if (this.ketquathuchien.co_hanh_vi_can_tro_ve_bvmt == true) {
        strUrl += "co_hanh_vi_can_tro_ve_bvmt=1&";
      }
      // bien phap khac phuc hau qua
      if (this.bienphapkhacphuchauqua.value) {
        var bienphapkhacphuchauquaIds = [];
        this.bienphapkhacphuchauqua.value.forEach((element) => {
          bienphapkhacphuchauquaIds.push(element.id);
        });
        strUrl =
          strUrl +
          "search_bien_phap_khac_phuc_vi_pham=" +
          bienphapkhacphuchauquaIds +
          "&";
      }
      if (this.luu_luong_nuoc_thai > 0) {
        strUrl = strUrl + "search_nuoc_thai=" + this.luu_luong_nuoc_thai + "&";
      }
      if (this.luu_luong_nuoc_thai_max != null) {
        strUrl =
          strUrl + "search_nuoc_thai_max=" + this.luu_luong_nuoc_thai_max + "&";
      }
      if (this.luu_luong_khi_thai > 0) {
        strUrl = strUrl + "search_khi_thai=" + this.luu_luong_khi_thai + "&";
      }
      if (this.luu_luong_khi_thai_max != null) {
        strUrl =
          strUrl + "search_khi_thai_max=" + this.luu_luong_khi_thai_max + "&";
      }

      if (tu_ngays && den_ngays) {
        strUrl = strUrl + "search_start_date=" + tu_ngays + "&";
        strUrl = strUrl + "search_end_date=" + den_ngays + "&";
      }
      strUrl = strUrl + "search_dot_xuat=" + this.ky_thanh_tra.dot_xuat + "&";
      strUrl = strUrl + "search_ke_hoach=" + this.ky_thanh_tra.dinh_ky + "&";

      if (this.loaiVanBan.value.length > 0) {
        var loaiVanBanselect = "";
        this.loaiVanBan.value.forEach((item) => {
          this.coquanpheduyetId = [];
          for (let key in this.coQuanPheDuyet) {
            if (key == item.id) {
              this.coQuanPheDuyet[key].forEach((value) => {
                this.coquanpheduyetId.push(value.id);
              });
            }
          }
          loaiVanBanselect =
            loaiVanBanselect +
            '{"' +
            item.id +
            '":' +
            '"' +
            this.coquanpheduyetId +
            '"} ';
        });
        strUrl = strUrl + "search_loai_van_ban=" + loaiVanBanselect + "&";
        console.log(strUrl);
      }
      return strUrl;
    },
    reset() {
      (this.mien.value = []),
        (this.tinh.value = []),
        (this.luuvucsong.value = []),
        (this.vungkinhtes.value = []),
        (this.doanthanhtra.value = []),
        (this.coquantochucs.value = []),
        (this.coquanpheduyetdtm.value = []),
        (this.coquanpheduyetdtm.cap_bo = false),
        (this.coquanpheduyetdtm.cap_dia_phuong = false),
        (this.coquanpheduyetdeanbvmt.value = []),
        (this.coquanpheduyetdeanbvmt.cap_bo = false),
        (this.coquanpheduyetdeanbvmt.cap_dia_phuong = false),
        (this.coquanpheduyetkehoachbvmt.value = []),
        (this.coquanpheduyetkehoachbvmt.cap_bo = false),
        (this.coquanpheduyetkehoachbvmt.cap_dia_phuong = false),
        (this.ky_thanh_tra.dot_xuat = true),
        (this.ky_thanh_tra.dinh_ky = true),
        (this.loaihinhcoso.value = []),
        (this.khucongnghiep.value = []),
        (this.bienphapxuphatboxung.value = []),
        (this.bienphapkhacphuchauqua.value = []),
        (this.ketquakhacphuchauqua.nop_mot_phan = false),
        (this.ketquakhacphuchauqua.da_nop_phat = false),
        (this.ketquakhacphuchauqua.da_khac_phuc = false),
        (this.ketquakhacphuchauqua.da_bao_cao = false),
        (this.ketquathuchien.dtm_dean_kehoach_bvmt.filter = null),
        (this.ketquathuchien.dtm_dean_kehoach_bvmt.dtmdakhbvmt_thuc_hien_khong_dung_noi_dung = false),
        (this.ketquathuchien.dtm_dean_kehoach_bvmt.dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung = false),
        (this.ketquathuchien.khong_co_giay_phep_xa_thai = false),
        (this.ketquathuchien.co_ket_hoach_quan_ly_moi_truong = false),
        (this.ketquathuchien.congtrinhBVMT.khong_xay_lap = false),
        (this.ketquathuchien.congtrinhBVMT.xay_lap_khong_dung = false),
        (this.ketquathuchien.congtrinhBVMT.khong_co_giay_xac_nhan_hoan_thanh = false),
        (this.ketquathuchien.congtrinhBVMT.van_hanh_khong_dung_khong_thuong_xuyen = false),
        (this.ketquathuchien.gsmt.gsmt_khong_thuc_hien = false),
        (this.ketquathuchien.gsmt.gsmt_thuc_hien = false),
        (this.ketquathuchien.gsmt.gsmt_thuc_hien_khong_dung_khong_du = false),
        (this.ketquathuchien.nuoc_thai.co_he_thong_thu_gom_nuoc_thai_rieng_biet = false),
        (this.ketquathuchien.nuoc_thai.nuoc_thai_vuot = false),
        (this.ketquathuchien.nuoc_thai.nuoc_thai_vuot_qcvn_tu = 0),
        (this.ketquathuchien.nuoc_thai.nuoc_thai_vuot_qcvn_toi = 0),
        (this.ketquathuchien.khi_thai.co_bien_phap_xu_ly_khi_thai = false),
        (this.ketquathuchien.khi_thai.khi_thai_vuot = false),
        (this.ketquathuchien.khi_thai.khi_thai_vuot_qcvn_tu = 0),
        (this.ketquathuchien.khi_thai.khi_thai_vuot_qcvn_toi = 0),
        (this.ketquathuchien.ctrsh.ctrsh_co_thu_gom = false),
        (this.ketquathuchien.ctrsh.ctrsh_co_phan_loai = false),
        (this.ketquathuchien.ctrsh.ctrsh_co_luu_tru = false),
        (this.ketquathuchien.ctrsh.ctrsh_co_chuyen_giao = false),
        (this.ketquathuchien.ctrcn.ctrcn_co_thu_gom = false),
        (this.ketquathuchien.ctrcn.ctrcn_co_phan_loai = false),
        (this.ketquathuchien.ctrcn.ctrcn_co_luu_tru = false),
        (this.ketquathuchien.ctrcn.ctrcn_co_chuyen_giao = false),
        (this.ketquathuchien.ctnh.ctrnh_vi_pham_chung_tu = false),
        (this.ketquathuchien.ctnh.ctrnh_co_thu_gom = false),
        (this.ketquathuchien.ctnh.ctrnh_co_phan_loai = false),
        (this.ketquathuchien.ctnh.ctrnh_co_luu_tru = false),
        (this.ketquathuchien.ctnh.ctrnh_co_de_lan = false),
        (this.ketquathuchien.ctnh.ctrnh_co_chuyen_giao = false),
        (this.ketquathuchien.ctnh.ctrnh_co_chon_lap = false),
        (this.ketquathuchien.ctnh.ctrnh_co_do_thai = false),
        (this.ketquathuchien.ctnh.ctrnh_co_giay_phep = false),
        (this.ketquathuchien.giay_xac_nhan.filter = false),
        (this.ketquathuchien.giay_xac_nhan.thuc_hien_sai_giay_xac_nhan = false),
        (this.ket_luan.vi_pham = false),
        (this.dieu.value = []),
        (this.khoan.value = []),
        (this.muc.value = []);
    },
    toggleCoQuanPheDuyet() {
      this.show_co_quan_phe_duyet = !this.show_co_quan_phe_duyet;
    },
    toggleSearchThuTucHanhChinh() {
      this.show_search_thu_tuc_hanh_chinh =
        !this.show_search_thu_tuc_hanh_chinh;
    },
    toggleSearchViPham() {
      this.show_search_vi_pham = !this.show_search_vi_pham;
    },
    toggleSearchViPhamKhac() {
      this.show_search_vi_pham_khac = !this.show_search_vi_pham_khac;
    },
    changeViPhamThuTucHanhChinh() {
      if (this.ketquathuchien.vi_pham_thu_tuc_hanh_chinh) {
        this.ketquathuchien.dtm_dean_kehoach_bvmt.filter = null;
        this.ketquathuchien.dtm_dean_kehoach_bvmt.dtmdakhbvmt_thuc_hien_khong_dung_noi_dung = false;
        this.ketquathuchien.dtm_dean_kehoach_bvmt.dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung = false;
        this.ketquathuchien.khong_co_giay_phep_xa_thai = false;
        this.ketquathuchien.co_ket_hoach_quan_ly_moi_truong = false;
        this.ketquathuchien.congtrinhBVMT.khong_xay_lap = false;
        this.ketquathuchien.congtrinhBVMT.xay_lap_khong_dung = false;
        this.ketquathuchien.congtrinhBVMT.khong_co_giay_xac_nhan_hoan_thanh = false;
        this.ketquathuchien.congtrinhBVMT.van_hanh_khong_dung_khong_thuong_xuyen = false;

        this.ketquathuchien.gsmt.gsmt_khong_thuc_hien = false;
        this.ketquathuchien.gsmt.gsmt_thuc_hien = false;
        this.ketquathuchien.gsmt.gsmt_thuc_hien_khong_dung_khong_du = false;

        this.ketquathuchien.giay_xac_nhan.khong_thuc_hien_giay_xac_nhan = false;
        this.ketquathuchien.giay_xac_nhan.thuc_hien_sai_giay_xac_nhan = false;
      }
    },
    changeViPhamXaThai() {
      if (this.ketquathuchien.vi_pham_nhom_hanh_vi_xa_thai) {
        this.ketquathuchien.nuoc_thai.co_he_thong_thu_gom_nuoc_thai_rieng_biet = false;
        this.ketquathuchien.nuoc_thai.nuoc_thai_vuot = false;
        this.ketquathuchien.nuoc_thai.nuoc_thai_vuot_qcvn_tu = -1;
        this.ketquathuchien.nuoc_thai.nuoc_thai_vuot_qcvn_toi = -1;

        this.ketquathuchien.khi_thai.co_bien_phap_xu_ly_khi_thai = false;
        this.ketquathuchien.khi_thai.khi_thai_vuot = false;
        this.ketquathuchien.khi_thai.khi_thai_vuot_qcvn_tu = -1;
        this.ketquathuchien.khi_thai.khi_thai_vuot_qcvn_toi = -1;

        this.ketquathuchien.ctrsh.ctrsh_co_thu_gom = false;
        this.ketquathuchien.ctrsh.ctrsh_co_phan_loai = false;
        this.ketquathuchien.ctrsh.ctrsh_co_luu_tru = false;
        this.ketquathuchien.ctrsh.ctrsh_co_chuyen_giao = false;

        this.ketquathuchien.ctrcn.ctrcn_co_thu_gom = false;
        this.ketquathuchien.ctrcn.ctrcn_co_phan_loai = false;
        this.ketquathuchien.ctrcn.ctrcn_co_luu_tru = false;
        this.ketquathuchien.ctrcn.ctrcn_co_chuyen_giao = false;

        this.ketquathuchien.ctnh.ctrnh_vi_pham_chung_tu = false;
        this.ketquathuchien.ctnh.ctrnh_co_thu_gom = false;
        this.ketquathuchien.ctnh.ctrnh_co_phan_loai = false;
        this.ketquathuchien.ctnh.ctrnh_co_luu_tru = false;
        this.ketquathuchien.ctnh.ctrnh_co_de_lan = false;
        this.ketquathuchien.ctnh.ctrnh_co_chuyen_giao = false;
        this.ketquathuchien.ctnh.ctrnh_co_chon_lap = false;
        this.ketquathuchien.ctnh.ctrnh_co_do_thai = false;
        this.ketquathuchien.ctnh.ctrnh_co_giay_phep = false;
      }
    },
    changeViPhamNhomHanhViKhac() {
      if (this.ketquathuchien.vi_pham_nhom_hanh_vi_khac) {
        this.ketquathuchien.vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong = false;
        this.ketquathuchien.vi_pham_cac_quy_dinh_bao_ve_moi_truong = false;
        this.ketquathuchien.co_hanh_vi_can_tro_ve_bvmt = false;
      }
    },
    goOrder(val) {
      this.changeData(val);
      this.changeDate();
    },
    paginate(val) {
      this.paging = val;
      this.changeDate();
    },
  },
};
</script>
<style scoped>
.flex-grow-0 {
  flex-grow: 0;
}
</style>
