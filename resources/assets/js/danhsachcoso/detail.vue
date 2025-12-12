<template>
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="row ma-2">
          <div class="col-md-4">
            <label>Tên cơ sở sản xuất</label>
            <input :disabled="true" v-model="form.ten" type="text" class="form-control" placeholder="Tên cơ sở sản xuất"
              name="ten" />
          </div>
          <div class="col-md-4">
            <label>Mã định danh</label>
            <input :disabled="true" v-model="form.ma_dinh_danh" type="text" class="form-control"
              placeholder="Tên chủ cơ sở trống" />
          </div>
          <div class="col-md-4">
            <label>Tên chủ đầu tư</label>
            <input :disabled="true"
              v-model="form.organization.chu_dau_tu ? form.organization.chu_dau_tu.ten : form.chu_dau_tu" type="text"
              class="form-control" placeholder="Chủ đầu tư trống" />
          </div>
        </div>
        <!-- <div style="border: 1px solid #d7dbdd" class="ma-4 pb-3">
            <div class="pl-4 pt-2" style="color: #2471a3; font-size: 16px; font-weight: bold">
                Giấy chứng nhận đầu tư
            </div>
            <div class="row ma-2 mt-4">
                <div class="col-md-4">
                <label>Cơ quan cấp giấy chứng nhận đầu tư</label>
                <input :disabled="true" v-model="form.co_quan_dau_tu? form.co_quan_dau_tu.ten : form.co_quan_dau_tu" type="text" class="form-control"/>
                </div>
                <div class="col-md-4">
                <label>Ngày cấp giấy chứng nhận đầu tư</label>
                <input :disabled="true" v-model="form.ngay_cap_giay_chung_nhan_dau_tu" type="text" class="form-control"/>
                </div>
                <div class="col-md-4">
                <label>Số giấy chứng nhận đầu tư</label>
                <input :disabled="true" v-model="form.so_giay_chung_nhan_dau_tu" type="text" class="form-control"/>
                </div>
            </div>
          </div>
          <div style="border: 1px solid #d7dbdd" class="ma-4 pb-3">
            <div class="pl-4 pt-2" style="color: #2471a3; font-size: 16px; font-weight: bold">
                Giấy chứng nhận kinh doanh
            </div>
            <div class="row ma-2 mt-4">
                <div class="col-md-4">
                <label>Cơ quan cấp giấy chứng nhận kinh doanh</label>
                <input :disabled="true" v-model="form.co_quan_kinh_doanh? form.co_quan_kinh_doanh.ten : form.co_quan_kinh_doanh" type="text" class="form-control"/>
                </div>
                <div class="col-md-4">
                <label>Ngày cấp giấy chứng nhận kinh doanh</label>
                <input :disabled="true" v-model="form.ngay_cap_giay_chung_nhan_kinh_doanh" type="text" class="form-control"/>
                </div>
                <div class="col-md-4">
                <label>Số giấy chứng nhận đầu tư</label>
                <input :disabled="true" v-model="form.so_giay_chung_nhan_kinh_doanh" type="text" class="form-control"/>
                </div>
            </div>
          </div> -->
        <div style="border: 1px solid #d7dbdd; height: 100%" class="ma-4 pb-3">
          <div class="pl-4 pt-2" style="color: #2471a3; font-size: 16px; font-weight: bold">
            Thông tin chi tiết
          </div>
          <div class="row ma-2 mt-4">
            <div class="col-md-6">
              <label>Địa chỉ hoạt động</label>
              <input :disabled="true" v-model="form.dia_chi_hoat_dong" type="text" class="form-control"
                placeholder="Địa chỉ hoạt động trống" />
            </div>
            <div class="col-md-6">
              <label>Địa chỉ liên hệ</label>
              <input :disabled="true" v-model="form.dia_chi_lien_he" type="text" class="form-control"
                placeholder="Địa chỉ liên hệ trống" />
            </div>
          </div>
          <div class="row ma-2 mt-4">
            <div class="col-md-3">
              <label>Vùng kinh tế trọng điểm</label>
              <input :disabled="true" v-model="form.vung_kinh_te_trong_diem.ten" type="text" class="form-control" />
            </div>
            <div class="col-md-3">
              <label>Loại cơ sở</label>
              <input :disabled="true" v-model="form.loai_co_so.ten_co_so" type="text" class="form-control" />
            </div>
            <div class="col-md-3">
              <label>Khu công nghiệp</label>
              <input :disabled="true" v-model="form.khu_cong_nghiep ? form.khu_cong_nghiep.ten : form.khu_cong_nghiep"
                type="text" class="form-control" />
            </div>
            <div class="col-md-3">
              <label>Diện tích</label>
              <input :disabled="true" v-model="form.dien_tich" type="text" class="form-control" />
            </div>
          </div>
          <div class="row ma-2 mt-4">
            <div class="col-md-3">
              <label>Lưu vực sông</label>
              <input :disabled="true" v-model="form.luu_vuc_song.ten" type="text" class="form-control" />
            </div>
            <div class="col-md-3">
              <label>Miền</label>
              <input :disabled="true" v-model="form.mien.ten" type="text" class="form-control" />
            </div>
            <div class="col-md-3">
              <label>Loại hình nguy cơ gây ô nhiễm</label>
              <input :disabled="true" v-model="loai_hinh_o_nhiem" type="text" class="form-control" />
            </div>
            <div class="col-md-3">
              <label>Loại ngành nghề kinh tế</label>
              <input :disabled="true" v-model="loai_nganh_nghe" type="text" class="form-control" />
            </div>
          </div>

          <div class="row ma-2 mt-4">
            <div class="col-md-3">
              <label>Phường xã</label>
              <div v-if="form.phuong_xas && form.phuong_xas.length > 0">
                <div v-for="item in form.phuong_xas" :key="item.id">{{ item.ten }}</div>
              </div>
              <!-- <input :disabled="true" v-model="form.quan_huyen.ten" type="text" class="form-control"/> -->
            </div>
            <div class="col-md-3">
              <label>Quận huyện</label>
              <div v-if="form.quan_huyens && form.quan_huyens.length > 0">
                <div v-for="item in form.quan_huyens" :key="item.id">{{ item.ten }}</div>
              </div>
            </div>
            <div class="col-md-3">
              <label>Tỉnh thành phố</label>
              <div v-if="form.tinh_thanhs && form.tinh_thanhs.length > 0">
                <div v-for="item in form.tinh_thanhs" :key="item.id">{{ item.ten }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row ma-2 mt-4">

        <div class="row ma-2 mt-4">
          <div class="col-md-6">
            <label>Nguồn nước sử dụng</label>
            <input :disabled="true" v-model="form.nguon_nuoc_su_dung" type="text" class="form-control" />
          </div>
          <div class="col-md-3">
            <label>Lượng nước sử dụng</label>
            <input :disabled="true" v-model="form.luong_nuoc_su_dung" type="text" class="form-control" />
          </div>
          <div class="col-md-3">
            <label>Hóa chất sử dụng</label>
            <input :disabled="true" v-model="form.hoa_chat_su_dung" type="text" class="form-control" />
          </div>
        </div>
        <div class="row ma-2 mt-4">
          <div class="col-md-3">
            <label>Nguyên liệu chính sử dụng</label>
            <input :disabled="true" v-model="form.nguyen_lieu_chinh_su_dung" type="text" class="form-control" />
          </div>
          <div class="col-md-3">
            <label>Nhiên liệu chính sử dụng</label>
            <input :disabled="true" v-model="form.nhien_lieu_chinh_su_dung" type="text" class="form-control" />
          </div>
          <div class="col-md-3">
            <label>Số người lao động</label>
            <input :disabled="true" v-model="form.so_nguoi_lao_dong" type="text" class="form-control" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
  
<script>
import Multiselect from "vue-multiselect";
import * as env from "../env.js";
import MessageService from "../services/MessageService";
import { ValidationProvider } from "vee-validate";

export default {
  props: ["chitiet"],
  components: {
    Multiselect,
    ValidationProvider,
  },
  data: () => ({
    is_loading: false,
    loai_hinh_o_nhiem: null,
    loai_nganh_nghe: null,
    form: {
      ten: null,
      organization: {
        ten: null,
        chu_dau_tu: {
          ten: null
        }
      },
      dia_chi_hoat_dong: null,
      dia_chi_lien_he: null,
      dien_tich: null,
      tinh_thanh: {
        ten: null,
      },
      quan_huyen: {
        ten: null,
      },
      mien: {
        ten: null,
      },
      co_quan_dau_tu: {
        ten: null,
      },
      co_quan_kinh_doanh: {
        ten: null,
      },
      khu_cong_nghiep: {
        ten: null,
      },
      loai_co_so: {
        ten_co_so: null,
      },
      luu_vuc_song: {
        ten: null,
      },
      luong_nuoc_su_dung: null,
      hoa_chat_su_dung: null,
      vung_kinh_te_trong_diem: {
        ten: null,
      },
      nguon_nuoc_su_dung: null,
      ngay_cap_giay_chung_nhan_dau_tu: null,
      ngay_cap_giay_chung_nhan_kinh_doanh: null,
      nguyen_lieu_chinh_su_dung: null,
      nhien_lieu_chinh_su_dung: null,
      so_giay_chung_nhan_dau_tu: null,
      so_giay_chung_nhan_kinh_doanh: null,
      so_nguoi_lao_dong: null,

    },
  }),
  mounted() {
    this.form = this.chitiet
    console.log(this.form)
    this.loai_nganh_nghe = this.form.loai_nganh_nghe.map(el => el.ten).join(' / ')
    this.loai_hinh_o_nhiem = this.form.loai_hinh_o_nhiem.map(el => el.ten).join(' / ')
  },
  methods: {
    goBack() {
      window.location.href = "/danhsachcoso";
    },
    goEditCoSoSanXuat(item) {
      window.location.href = `/cososanxuat/showformupdate/${item.id}`
    },
  },
};
</script>
  
<style scoped>
input {
  background-color: white !important;
}
</style>