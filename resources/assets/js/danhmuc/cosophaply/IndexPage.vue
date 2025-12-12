<template>
  <div class="row">
    <div class="col-md-2">
      <br />
      <ul class="nav nav-pills nav-stacked">
        <li class="active">
          <a
            href="#tab_quy_dinh_xu_phat"
            data-toggle="tab"
            aria-expanded="true"
          >
            <i class="fa fa-tag"></i> Quy định xử phạt
          </a>
        </li>
        <li>
          <a
            href="#tab_cac_bien_phap_khac_phuc_hau_qua"
            data-toggle="tab"
            aria-expanded="true"
          >
            <i class="fa fa-tag"></i> Biện pháp khắc phục hậu quả
          </a>
        </li>
        <li>
          <a
            href="#tab_loai_xu_phat_bo_xung"
            data-toggle="tab"
            aria-expanded="true"
          >
            <i class="fa fa-tag"></i> Loại xử phạt bổ sung
          </a>
        </li>
        <li>
          <a href="#tab_phat_tang_them" data-toggle="tab" aria-expanded="true">
            <i class="fa fa-tag"></i> Phạt tăng thêm
          </a>
        </li>
      </ul>
    </div>
    <div class="col-md-10">
      <div class="tab-content">
        <div class="tab-pane active" id="tab_quy_dinh_xu_phat">
          <div class="timeline-item">
            <h3 class="timeline-header">Quy định xử phạt</h3>
            <QuyDinhXuPhat></QuyDinhXuPhat>
          </div>
        </div>
        <div class="tab-pane" id="tab_cac_bien_phap_khac_phuc_hau_qua">
          <div class="timeline-item">
            <h3 class="timeline-header">Biện pháp khắc phục hậu quả</h3>
            <CacBienPhapKhacPhucHauQua></CacBienPhapKhacPhucHauQua>
          </div>
        </div>
        <div class="tab-pane" id="tab_loai_xu_phat_bo_xung">
          <div class="timeline-item">
            <h3 class="timeline-header">Loại xử phạt bổ sung</h3>
            <LoaiXuPhatBoSung></LoaiXuPhatBoSung>
          </div>
        </div>
        <div class="tab-pane" id="tab_phat_tang_them">
          <div class="timeline-item">
            <h3 class="timeline-header">Phạt tăng thêm</h3>
            <PhatTangThem :usersystem="usersystem"></PhatTangThem>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CacBienPhapKhacPhucHauQua from "./CacBienPhapKhacPhucHauQua.vue";
import LoaiXuPhatBoSung from "./LoaiXuPhatBoSung.vue";
import QuyDinhXuPhat from "./QuyDinhXuPhat.vue";
import PhatTangThem from "./PhatTangThem.vue";

export default {
  props: ["usersystem"],
  components: {
    CacBienPhapKhacPhucHauQua,
    LoaiXuPhatBoSung,
    QuyDinhXuPhat,
    PhatTangThem
  }
};
</script>
