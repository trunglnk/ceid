<template>
  <div class="row mb-5">
    <div class="col-md-12 col-lg-3">
      <br />
      <ul class="nav nav-pills nav-stacked">
        <li class="active">
          <a
            href="#tab_1"
            data-toggle="tab"
            aria-expanded="true"
            @click="tagShow = 1"
          >
            <i class="fa fa-tag"></i> Miền
          </a>
        </li>
        <li>
          <a
            href="#tab_2"
            data-toggle="tab"
            aria-expanded="true"
            @click="tagShow = 2"
          >
            <i class="fa fa-tag"></i> Vùng kinh tế trọng điểm
          </a>
        </li>
        <li>
          <a
            href="#tab_6"
            data-toggle="tab"
            aria-expanded="true"
            @click="tagShow = 6"
          >
            <i class="fa fa-tag"></i> Loại hình hoạt động
          </a>
        </li>


        <li>
          <a
            href="#tab_8"
            data-toggle="tab"
            aria-expanded="true"
            @click="tagShow = 8"
          >
            <i class="fa fa-tag"></i> Khu công nghiệp
          </a>
        </li>
        <li>
          <a
            href="#tab_9"
            data-toggle="tab"
            aria-expanded="true"
            @click="tagShow = 9"
          >
            <i class="fa fa-tag"></i> Cơ quan quản lý
          </a>
        </li>


        <li>
          <a
            href="#tab_11"
            data-toggle="tab"
            aria-expanded="true"
            @click="tagShow = 11"
          >
            <i class="fa fa-tag"></i> Chuyển đổi đơn vị
          </a>
        </li>
        <li>
          <a
            href="#tab_12"
            data-toggle="tab"
            aria-expanded="true"
            @click="tagShow = 12"
          >
            <i class="fa fa-tag"></i> Đơn vị tạo danh mục
          </a>
        </li>
        <!-- <li>
          <a
            href="#tab_16"
            data-toggle="tab"
            aria-expanded="true"
            @click="tagShow = 16"
          >
            <i class="fa fa-tag"></i> Hành vi vi phạm
          </a>
        </li> -->
      </ul>
    </div>
    <div class="col-md-12 col-lg-9">
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="timeline-item">
            <h3 class="timeline-header">Miền</h3>
            <Mien v-if="tagShow == 1" :usersystem="usersystem"></Mien>
          </div>
        </div>
        
        <div class="tab-pane" id="tab_2">
          <div class="timeline-item">
            <h3 class="timeline-header">Vùng kinh tế trọng điểm</h3>
            <VungKinhTeTrongDiem
              v-if="tagShow == 2"
              :usersystem="usersystem"
            ></VungKinhTeTrongDiem>
          </div>
        </div>

        <div class="tab-pane" id="tab_6">
          <div class="timeline-item">
            <h3 class="timeline-header">
              Loại hình hoạt động theo TT 04/2012/TT-BTNMT
            </h3>
            <LoaiHinhHoatDong
              v-if="tagShow == 6"
              :usersystem="usersystem"
            ></LoaiHinhHoatDong>
          </div>
        </div>

        <div class="tab-pane" id="tab_8">
          <div class="timeline-item">
            <h3 class="timeline-header">Khu công nghiệp</h3>
            <KhuCongNghiep
              v-if="tagShow == 8"
              :usersystem="usersystem"
            ></KhuCongNghiep>
          </div>
        </div>
        <div class="tab-pane" id="tab_9">
          <div class="timeline-item">
            <h3 class="timeline-header">Cơ quan quản lý</h3>
            <Coquantochuc
              v-if="tagShow == 9"
              :usersystem="usersystem"
            ></Coquantochuc>
          </div>
        </div>

        <div class="tab-pane" id="tab_11">
          <div class="timeline-item">
            <h3 class="timeline-header">Chuyển đổi đơn vị</h3>
            <ChuyenDoiDonVi
              v-if="tagShow == 11"
              :usersystem="usersystem"
            ></ChuyenDoiDonVi>
          </div>
        </div>
        <div class="tab-pane" id="tab_12">
          <div class="timeline-item">
            <h3 class="timeline-header">Đơn vị tạo danh mục</h3>
            <DonVi v-if="tagShow == 12" :usersystem="usersystem"></DonVi>
          </div>
        </div>
        <!-- <div class="tab-pane" id="tab_16">
          <div class="timeline-item">
            <h3 class="timeline-header">Danh mục hành vi vi phạm</h3>
            <HanhViViPham v-if="tagShow == 16" :usersystem="usersystem"></HanhViViPham>
          </div>
        </div> -->
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
import HanhViViPham from '../cosophaply/index.vue'
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
    HanhViViPham
},
  props: ["usersystem"],
  data: function () {
    return {
      tagShow: 1,
    };
  },
};
</script>

