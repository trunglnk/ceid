<template>
  <div class="row mb-5">
    <div class="col-md-12 col-lg-3">
      <br />
      <ul class="nav nav-pills nav-stacked">
        <!-- <li class="active">
          <a
            href="#tab_1"
            data-toggle="tab"
            aria-expanded="true"
            @click="tagShow = 1"
          >
            <i class="fa fa-tag"></i> Vùng miền
          </a>
        </li> -->
        <li class="active">
          <a
            href="#tab_3"
            data-toggle="tab"
            aria-expanded="true"
            @click="tagShow = 3"
          >
            <i class="fa fa-tag"></i> Lưu vực sông
          </a>
        </li>
        <li>
          <a
            href="#tab_4"
            data-toggle="tab"
            aria-expanded="false"
            @click="tagShow = 4"
          >
            <i class="fa fa-tag"></i> Tỉnh thành
          </a>
        </li>
        <li>
          <a
            href="#tab_6"
            data-toggle="tab"
            aria-expanded="false"
            @click="tagShow = 6"
          >
            <i class="fa fa-tag"></i> Quận huyện
          </a>
        </li>
        <li>
          <a
            href="#tab_2"
            data-toggle="tab"
            aria-expanded="false"
            @click="tagShow = 2"
          >
            <i class="fa fa-tag"></i> Phường xã
          </a>
        </li>
        <li>
          <a
            href="#tab_5"
            data-toggle="tab"
            aria-expanded="true"
            @click="tagShow = 5"
          >
            <i class="fa fa-tag"></i> Loại ngành nghề kinh tế Việt Nam
          </a>
        </li>

        <li>
          <a
            href="#tab_7"
            data-toggle="tab"
            aria-expanded="true"
            @click="tagShow = 7"
          >
            <i class="fa fa-tag"></i> Loại hình cơ sở
          </a>
        </li>
        <li>
          <a
            href="#tab_10"
            data-toggle="tab"
            aria-expanded="true"
            @click="tagShow = 10"
          >
            <i class="fa fa-tag"></i> Loại thủ tục hành chính
          </a>
        </li>
        <li>
          <a
            href="#tab_13"
            data-toggle="tab"
            aria-expanded="true"
            @click="tagShow = 13"
          >
            <i class="fa fa-tag"></i> Thông số
          </a>
        </li>
        <li>
          <a
            href="#tab_14"
            data-toggle="tab"
            aria-expanded="true"
            @click="tagShow = 14"
          >
            <i class="fa fa-tag"></i> Loại hình nguy cơ gây ô nhiễm
          </a>
        </li>
        <li>
          <a
            href="#tab_16"
            data-toggle="tab"
            aria-expanded="true"
            @click="tagShow = 16"
          >
            <i class="fa fa-tag"></i> Hành vi vi phạm
          </a>
        </li>
        <li>
          <a
            href="#tab_17"
            data-toggle="tab"
            aria-expanded="true"
            @click="tagShow = 17"
          >
            <i class="fa fa-tag"></i> Nghị định
          </a>
        </li>
        <li>
          <a
            href="#tab_18"
            data-toggle="tab"
            aria-expanded="true"
            @click="tagShow = 18"
          >
            <i class="fa fa-tag"></i> Loại hình quan trắc
          </a>
        </li>
      </ul>
    </div>
    <div class="col-md-12 col-lg-9">
      <div class="tab-content">
        <!-- <div class="tab-pane active" id="tab_1">
          <div class="timeline-item">
            <h3 class="timeline-header">Vùng miền</h3>
            <Mien v-if="tagShow == 1" :usersystem="usersystem"></Mien>
          </div>
        </div> -->

        <div class="tab-pane active" id="tab_3">
          <div class="timeline-item">
            <h3 class="timeline-header">Lưu vực sông</h3>
            <Luuvucsong
              v-if="tagShow == 3"
              :usersystem="usersystem"
            ></Luuvucsong>
          </div>
        </div>
        <div class="tab-pane" id="tab_4">
          <div class="timeline-item">
            <h3 class="timeline-header">Tỉnh thành</h3>
            <TinhThanh v-if="tagShow == 4" :usersystem="usersystem"></TinhThanh>
          </div>
        </div>
        <div class="tab-pane" id="tab_6">
          <div class="timeline-item">
            <h3 class="timeline-header">Quận huyện</h3>
            <QuanHuyen v-if="tagShow == 6" :usersystem="usersystem"></QuanHuyen>
          </div>
        </div>
        <div class="tab-pane" id="tab_2">
          <div class="timeline-item">
            <h3 class="timeline-header">Phường xã</h3>
            <PhuongXa v-if="tagShow == 2" :usersystem="usersystem"></PhuongXa>
          </div>
        </div>
        <div class="tab-pane" id="tab_5">
          <div class="timeline-item">
            <h3 class="timeline-header">
              Loại ngành nghề kinh tế Việt Nam theo QĐ 27/2018/QĐ-TTg
            </h3>
            <LoaiHinhCoSo
              v-if="tagShow == 5"
              :usersystem="usersystem"
            ></LoaiHinhCoSo>
          </div>
        </div>
        <div class="tab-pane" id="tab_7">
          <div class="timeline-item">
            <h3 class="timeline-header">Loại hình cơ sở</h3>
            <LoaiKCN v-if="tagShow == 7" :usersystem="usersystem"></LoaiKCN>
          </div>
        </div>
        <div class="tab-pane" id="tab_10">
          <div class="timeline-item">
            <h3 class="timeline-header">Loại thủ tục hành chính</h3>
            <LoaiTTHC v-if="tagShow == 10" :usersystem="usersystem"></LoaiTTHC>
          </div>
        </div>
        <div class="tab-pane" id="tab_13">
          <div class="timeline-item">
            <h3 class="timeline-header">Thông số</h3>
            <ThongSo v-if="tagShow == 13" :usersystem="usersystem"></ThongSo>
          </div>
        </div>
        <div class="tab-pane" id="tab_14">
          <div class="timeline-item">
            <h3 class="timeline-header">
              Tên loại hình sản xuất có nguy cơ tác động xấu đến môi trường
            </h3>
            <LoaiHinhNguyCoOiNhiemVue
              v-if="tagShow == 14"
              :usersystem="usersystem"
            ></LoaiHinhNguyCoOiNhiemVue>
          </div>
        </div>
        <div class="tab-pane" id="tab_16">
          <div class="timeline-item">
            <h3 class="timeline-header">Danh mục hành vi vi phạm</h3>
            <HanhViViPham
              v-if="tagShow == 16"
              :usersystem="usersystem"
            ></HanhViViPham>
          </div>
        </div>
        <div class="tab-pane" id="tab_17">
          <div class="timeline-item">
            <h3 class="timeline-header">Quản lý nghị định</h3>
            <!-- <HanhViViPham v-if="tagShow == 17" :usersystem="usersystem"></HanhViViPham> -->
            <NghiDinh :usersystem="usersystem"></NghiDinh>
          </div>
        </div>
        <div class="tab-pane" id="tab_18">
          <div class="timeline-item">
            <h3 class="timeline-header">Loại hình quan trắc</h3>
            <LoaiHinhQuanTrac v-if="tagShow == 18" :usersystem="usersystem"></LoaiHinhQuanTrac>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import LoaiHinhCoSo from "./LoaiHinhCoSo.vue";
import TinhThanh from "./TinhThanh.vue";
import LoaiKCN from "./LoaiKCN.vue";
import Coquantochuc from "./Coquantochuc.vue";
import LoaiTTHC from "./LoaiTTHC.vue";
import Luuvucsong from "./Luuvucsong.vue";
import VungKinhTeTrongDiem from "./VungKinhTeTrongDiem.vue";
import Mien from "./Mien.vue";
import ChuyenDoiDonVi from "./ChuyenDoiDonVi.vue";
import KhuCongNghiep from "./KhuCongNghiep.vue";
import DonVi from "./DonVi.vue";
import ThongSo from "./ThongSo.vue";
import LoaiHinhNguyCoOiNhiemVue from "./LoaiHinhNguyCoOiNhiem.vue";
import LoaiCoSo from "./LoaiCoSo.vue";
import LoaiHinhHoatDong from "./LoaiHinhHoatDong.vue";
import HanhViViPham from "../cosophaply/index.vue";
import QuanHuyen from "./QuanHuyen.vue";
import PhuongXa from "./PhuongXa.vue";
import NghiDinh from "./NghiDinh.vue";
import LoaiHinhQuanTrac from "./LoaiHinhQuanTrac.vue";

export default {
  components: {
    ThongSo,
    DonVi,
    KhuCongNghiep,
    ChuyenDoiDonVi,
    LoaiHinhCoSo,
    TinhThanh,
    LoaiKCN,
    Coquantochuc,
    VungKinhTeTrongDiem,
    Mien,
    LoaiTTHC,
    Luuvucsong,
    LoaiHinhNguyCoOiNhiemVue,
    LoaiCoSo,
    LoaiHinhHoatDong,
    HanhViViPham,
    QuanHuyen,
    PhuongXa,
    NghiDinh,
    LoaiHinhQuanTrac
  },
  props: ["usersystem"],
  data: function () {
    return {
      tagShow: 3,
    };
  },
};
</script>
