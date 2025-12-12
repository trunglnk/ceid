<template>
  <div class="col-md-12 col-lg-12">
    <div>
      <div class="mr-3 mb-3" style="text-align: end;">
        Hiển thị
        <select v-model="paging.perpage" style="width:80px" @change="change()">
          <option v-for="(item, index) in options" :value="item" :key="`${item}${index}`">{{ item }}</option>
        </select>
        mục
      </div>
    </div>
    <table class="table table-hover table-responsive">
      <thead>
        <tr class="row-table-header">
          <th
            :style="
              `width:${item.width ? item.width : ''};${
                item.order ? 'cursor:pointer' : ''
              }`
            "
            class="text-center"
            v-for="(item, index) in header"
            :key="index"
            :rowspan="item.type == 'rowspan' ? item.number : null"
            :colspan="item.type == 'colspan' ? item.number : null"
            @click="goOrder(index, item.value, item.order)"
          >
            <div class="d-flex align-center justify-center">
              <div>{{ item.name }}</div>
              <i
                v-if="item.order"
                class="fa fa-sort-asc icon"
                :style="
                  `height:9px;transition: all .1s;${
                    item.order == 'asc' ? '' : 'transform: rotate(180deg);'
                  }`
                "
              ></i>
            </div>
          </th>
        </tr>
        <tr class="row-table-header children">
          <th
            v-for="item in headerChildren"
            :rowspan="item.type == 'rowspan' ? item.number : null"
            :colspan="item.type == 'colspan' ? item.number : null"
          >
            {{ item.name }}
          </th>
        </tr>
      </thead>
      <tbody>
        <template v-for="(item, index) in dataT">
          <tr :key="`${item}${index}`" style="cursor:pointer">
            <td
              :rowspan="
                item.ket_qua_thanh_tras && item.ket_qua_thanh_tras.length != 0
                  ? item.ket_qua_thanh_tras.length
                  : null
              "
            >
              {{ item.index }}
            </td>
            <td
              :rowspan="
                item.ket_qua_thanh_tras && item.ket_qua_thanh_tras.length != 0
                  ? item.ket_qua_thanh_tras.length
                  : null
              "
            >
              <a
                target="blank"
                :href="`/cososanxuat/showformupdate/${item.organization_id}`"
                >{{ item.ten }}</a
              >
              <i v-if="item.old_name">{{ item.old_name }}</i>
            </td>
            <td
              :rowspan="
                item.ket_qua_thanh_tras && item.ket_qua_thanh_tras.length != 0
                  ? item.ket_qua_thanh_tras.length
                  : null
              "
            >
              {{ item.dia_chi_lien_he }}
            </td>
            <td
              :rowspan="
                item.ket_qua_thanh_tras && item.ket_qua_thanh_tras.length != 0
                  ? item.ket_qua_thanh_tras.length
                  : null
              "
            >
              {{ item.loai_hinh_co_so }}
            </td>
            <td>
              <a
                target="_blank"
                style="color:#337ab7"
                v-if="item.ket_qua_thanh_tras[0]"
                :href="'/ketquathanhtra/edit/' + item.ket_qua_thanh_tras[0].id"
              >
                {{
                  item.ket_qua_thanh_tras[0]
                    ? item.ket_qua_thanh_tras[0].so_quyet_dinh_tt
                    : null
                }}</a
              >
            </td>
            <td>
              <a
                target="_blank"
                style="color:#337ab7"
                v-if="item.ket_qua_thanh_tras[0]"
                :href="
                  '/doanthanhtra/update/' +
                    item.ket_qua_thanh_tras[0]
                      .so_quyet_dinh_thanh_lap_doan_tt_id
                "
                >{{
                  item.ket_qua_thanh_tras[0]
                    ? item.ket_qua_thanh_tras[0].so_quyet_dinh_thanh_lap_doan_tt
                    : null
                }}</a
              >
            </td>
            <td>
              {{
                item.ket_qua_thanh_tras[0]
                  ? item.ket_qua_thanh_tras[0]
                      .ngay_ra_quyet_dinh_thanh_lap_doan_tt
                  : null
              }}
            </td>
            <td>
              {{
                item.ket_qua_thanh_tras[0]
                  ? item.ket_qua_thanh_tras[0].hanh_vi_vi_pham
                  : null
              }}
            </td>
            <td
              :rowspan="
                item.ket_qua_thanh_tras && item.ket_qua_thanh_tras.length != 0
                  ? item.ket_qua_thanh_tras.length
                  : null
              "
            >
              {{ item.ghi_chu }}
            </td>
            <td
              :rowspan="
                item.ket_qua_thanh_tras && item.ket_qua_thanh_tras.length != 0
                  ? item.ket_qua_thanh_tras.length
                  : null
              "
            >
              <a @click="getMapOrganization(item.organization_id)" class="btn">
                <i class="fa fa-map-marker"></i>
              </a>
            </td>
          </tr>
          <tr
            style="cursor:pointer"
            v-for="(kq, kqindex) in item.ket_qua_thanh_tras"
            :key="`${kq.so_quyet_dinh_tt}${index}${kqindex}`"
            v-if="kqindex != 0"
          >
            <td>
              <a
                target="_blank"
                style="color:#337ab7"
                v-if="kq"
                :href="'/ketquathanhtra/edit/' + kq.id"
              >
                {{ kq.so_quyet_dinh_tt }}</a
              >
            </td>
            <td>
              <a
                target="_blank"
                style="color:#337ab7"
                v-if="kq"
                :href="
                  '/doanthanhtra/update/' +
                    kq.so_quyet_dinh_thanh_lap_doan_tt_id
                "
              >
                {{ kq.so_quyet_dinh_thanh_lap_doan_tt }}</a
              >
            </td>
            <td>{{ kq.ngay_ra_quyet_dinh_thanh_lap_doan_tt }}</td>
            <td>{{ kq.hanh_vi_vi_pham }}</td>
          </tr>
        </template>
        <tr
          v-if="
            !dataTable ||
              !dataTable.organizations ||
              (dataT.organizations && dataT.organizations.data.length == 0)
          "
        >
          <td :colspan="numberCol" class="text-center">Không có dữ liệu</td>
        </tr>
      </tbody>
    </table>
    <paging-component @change-page="changePage" :paging.sync="paging" />
  </div>
</template>
<script>
import moment from "moment";

export default {
  props: {
    dataTable: { type: [Array, Object], required: true },
    isLoadMore: Boolean
  },
  data: () => ({
    options:[20,50,100],
    header: [
      {
        name: "STT",
        "text-align": "center",
        value: "index",
        width: "2%",
        type: "rowspan",
        number: 2
      },
      {
        name: "Tên cơ sở",
        width: "20%",
        value: "ten",
        type: "rowspan",
        number: 2,
        order: "asc"
      },
      {
        name: "Địa chỉ liên hệ",
        width: "15%",
        value: "dia_chi_lien_he",
        type: "rowspan",
        number: 2,
        order: "asc"
      },
      {
        name: "Loại hình sản xuất",
        width: "10%",
        type: "rowspan",
        number: 2
        // order: "asc",
        // value: "loai_hinh_co_so",
      },
      {
        name: "Kết quả thanh tra",
        type: "colspan",
        width: "30%",
        number: 4
      },
      {
        name: "Ghi chú",
        type: "rowspan",
        value: "ghi_chu",
        number: 2,
        width: "15%"
      },
      {
        name: "Bản đồ",
        type: "rowspan",
        number: 2,
        width: "2%"
      }
    ],
    headerChildren: [
      {
        name: "Số KLTT"
      },
      {
        name: "Số QĐ thành lập đoàn TT"
      },
      {
        name: "Ngày QĐ thành lập đoàn TT"
      },
      {
        name: "Hành vi vi phạm"
      }
    ],
    dataT: [],
    nhomHanhViVIPham: {
      // dtmdakhbvmt_thuc_hien_khong_dung_noi_dung:
      //   "DTMDAKHBVMT thực hiện không đúng nội dung",
      // dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung:
      //   "DTMDAKHBVMT không thực hiện một trong các nội dung",
      // co_ket_hoach_quan_ly_moi_truong: "Có kế hoạch quản lý môi trường",
      // khong_xay_lap: "Không xây lấp",
      // van_hanh_khong_dung_khong_thuong_xuyen:
      //   "Vận hành không đúng, không thường xuyên",
      // gsmt_khong_thuc_hien: "GSMT không thực hiện",
      // gsmt_thuc_hien_khong_dung_khong_du: "GSMT thực hiện không đúng không đủ",
      // thuc_hien_sai_giay_xac_nhan: "Thực hiện sai giấy xác nhận",
      nuoc_thai_vuot: "Nước thải vượt",
      khi_thai_vuot: "khí thải vượt",
      // nuoc_thai_vuot_qcvn_tu: "Nước thải vượt QCVN tu",
      // nuoc_thai_vuot_qcvn_toi: "Nước thải vượt QCVN toi",
      // co_bien_phap_xu_ly_khi_thai: "Có biện pháp xử lý khí thải",
      // khi_thai_vuot_qcvn_tu: "Khí thải vượt QCVN tu",
      // khi_thai_vuot_qcvn_toi: "Khí thải vượt QCVN toi",
      // ctrsh_co_thu_gom: "CTRSH có thu gom",
      // ctrsh_co_phan_loai: "CTRSH có phân loại",
      // ctrsh_co_luu_tru: "CTRSH có lưu trữ",
      // ctrsh_co_chuyen_giao: "CTRSH có chuyển giao",
      // ctrcn_co_thu_gom: "CTRCN có thu gom",
      // ctrcn_co_phan_loai: "CTRCN có phân loại",
      // ctrcn_co_luu_tru: "CTRCN có lưu trữ",
      // ctrcn_co_chuyen_giao: "CTRCN có chuyển giao",
      // ctrnh_vi_pham_chung_tu: "CTRNH vi phạm chứng từ",
      // ctrnh_co_thu_gom: "CTRNH có thu gom",
      // ctrnh_co_phan_loai: "CTRNH có phân loại",
      // ctrnh_co_luu_tru: "CTRNH có lưu trữ",
      // ctrnh_co_de_lan: "CTRNH cố để lan",
      // ctrnh_co_chuyen_giao: "CTRNH có chuyển giao",
      // ctrnh_co_chon_lap: "CTRNH có chôn lấp",
      // ctrnh_co_do_thai: "CTRNH có đổ thải",
      // ctrnh_co_giay_phep: "CTRNH có giấy phép",
      // nhom_hanh_vi_khac: "Nhóm hành vi khác",
      // khong_co_giay_xac_nhan_hoan_thanh: "Không có giấy xác nhận hoàn thành",
      // gsmt_thuc_hien: "GSMT thực hiện",
      // co_he_thong_thu_gom_nuoc_thai_rieng_biet:
      //   "Có hệ thống thu gom nước thải riêng biệt",
      // vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong:
      //   "Vi phạm quy định về thu thập sử dụng thống tin môi trường",
      // vi_pham_cac_quy_dinh_bao_ve_moi_truong:
      //   "Vi phạm các quy định bảo vệ môi trường",
      // co_hanh_vi_can_tro_ve_bvmt: "Có hành vi cản trở về BVMT",
      // khong_thuc_hien_giay_xac_nhan: "Không thực hiện giấy xác nhận",
      // khong_co_giay_phep_xa_thai: "Không có giấy phép xả thải",
      vi_pham_thu_tuc_hanh_chinh: "Vi phạm thủ tục hành chính"
      // vi_pham_xa_thai: "Vi phạm xả thải",
      // khong_co_bao_cao_dtm: "Không có báo cáo DTM",
      // phat_tang_them_10: "Phạt tăng thêm 10",
      // thong_so_vuot_chuan: " Thông số vượt chuẩn",
    },
    numberCol: 0,
    paging: {
      page: 1,
      last_page: 1,
      total: 0,
      perpage:100,
      current_page:1,
    }
  }),
  watch: {
    dataTable: {
      handler(val) {
        if (val) {
          this.dataT = Object.assign({}, val);
          let loai_hinh_co_so = [];
          this.paging = {
            page: this.dataT.organizations.current_page,
            last_page: this.dataT.organizations.last_page,
            total: this.dataT.organizations.total,
            current_page: this.dataT.organizations.current_page,
            perpage: this.dataT.organizations.per_page
          };
          
          this.dataT = this.dataT.organizations.data.map((obj, index) => {
            loai_hinh_co_so = [];
            let ket_qua_thanh_tras = [];
            if (obj.ket_qua_thanh_tras && obj.ket_qua_thanh_tras.length > 0) {
              //get loai_hinh_co_so
              obj.ket_qua_thanh_tras.forEach(kq => {
                if (kq.ket_qua_thanh_tra_chungs) {
                  kq.ket_qua_thanh_tra_chungs.forEach(e => {
                    if (e.ket_qua_thanh_tra_chung_loai_hinh_hoat_dongs) {
                      e.ket_qua_thanh_tra_chung_loai_hinh_hoat_dongs.forEach(
                        x => {
                          if (x.loai_hinh_co_so) {
                            loai_hinh_co_so.push(x.loai_hinh_co_so.ten);
                          }
                        }
                      );
                    }
                  });
                }
              });
              //map ket_qua_thanh_tras Array
              ket_qua_thanh_tras = obj.ket_qua_thanh_tras.map(kq => {
                return {
                  id: kq.id,
                  so_quyet_dinh_tt: kq.so_quyet_dinh,
                  so_quyet_dinh_thanh_lap_doan_tt:
                    kq.quyet_dinh_thanh_tra.so_quyet_dinh,
                  so_quyet_dinh_thanh_lap_doan_tt_id:
                    kq.quyet_dinh_thanh_tra.id,
                  ngay_ra_quyet_dinh_thanh_lap_doan_tt:
                    kq.quyet_dinh_thanh_tra.ngay_ra_quyet_dinh,
                  hanh_vi_vi_pham: this.nhomViPham(kq.nhom_hanh_vi_vi_phams[0])
                };
              });
            }

            // if (obj.ket_qua_thanh_tras.ket_qua_thanh_tra_chungs) {
            //   obj.ket_qua_thanh_tras.ket_qua_thanh_tra_chungs.forEach((e) => {
            //     if (e.ket_qua_thanh_tra_chung_loai_hinh_hoat_dongs) {
            //       e.ket_qua_thanh_tra_chung_loai_hinh_hoat_dongs.forEach(
            //         (x) => {
            //           if (x.loai_hinh_co_so) {
            //             loai_hinh_co_so.push(x.loai_hinh_co_so.ten);
            //           }
            //         }
            //       );
            //     }
            //   });
            // }
            return {
              id: obj.co_so_san_xuats && obj.co_so_san_xuats[0] ? obj.co_so_san_xuats[0].id : obj,
              organization_id: obj.id,
              index: ++index,
              ten: obj.ten,
              dia_chi_lien_he: obj.dia_chi_lien_he,
              ket_qua_thanh_tras: ket_qua_thanh_tras,
              old_name:  obj.co_so_san_xuats && obj.co_so_san_xuats[0] ? this.old(obj.co_so_san_xuats[0].old_name) : '',
              loai_hinh_co_so: [...new Set(loai_hinh_co_so)].sort().join(", "),
              // ghi_chu: (obj.ket_qua_thanh_tras && obj.ket_qua_thanh_tras[0] && 
              // obj.ket_qua_thanh_tras[0].ket_qua_thanh_tra_chungs && 
              // obj.ket_qua_thanh_tras[0].ket_qua_thanh_tra_chungs[0] && 
              // obj.ket_qua_thanh_tras[0].ket_qua_thanh_tra_chungs[0].ket_qua_thanh_tra_chung_loai_hinh_hoat_dongs && 
              // obj.ket_qua_thanh_tras[0].ket_qua_thanh_tra_chungs[0].ket_qua_thanh_tra_chung_loai_hinh_hoat_dongs[0]) 
              // ? obj.ket_qua_thanh_tras[0].ket_qua_thanh_tra_chungs[0].ket_qua_thanh_tra_chung_loai_hinh_hoat_dongs[0].ghi_chu 
              // : "",
              ghi_chu: (obj.ket_qua_thanh_tras && obj.ket_qua_thanh_tras[0] && 
              obj.ket_qua_thanh_tras[0].ket_qua_thanh_tra_chungs) 
              ? obj.ket_qua_thanh_tras[0].ket_qua_thanh_tra_chungs
                  .flatMap(e => e.ket_qua_thanh_tra_chung_loai_hinh_hoat_dongs)
                  .map(x => x.ghi_chu)
                  .filter(x => x)
                  .join(", ") 
              : "",
            };
          });
        }
      },
      immediate: true
    }
  },
  created() {
    this.numberCol = this.countCol();
  },
  methods: {
    change(){
      this.$emit("paginate", this.paging);
    },
    getMapOrganization(value) {
      window.open("/cososanxuat/showformupdate/" + value);
    },
    nhomViPham(val) {
      if (val) {
        let str = "";
        Object.keys(this.nhomHanhViVIPham).forEach(e => {
          if (val[e]) {
            str += `${this.nhomHanhViVIPham[e]}, `;
          }
        });
        return str ? str.substring(0, str.length - 2) : "";
      }
      return "";
    },
    old(val) {
      if (val && val.length > 0) {
        let str = "";
        val.forEach(x => {
          str += `${x}, `;
        });
        return `(${str.substring(0, str.length - 2)})`;
      }
    },
    changePage(data) {
      this.paging.current_page = data;
      this.$emit("paginate", this.paging);
    },
    goOrder(index, value, order) {
      this.header[index].order = order == "asc" ? "desc" : "asc";

      this.$emit("goOrder", {
        order_column: value,
        order_direction: this.header[index].order
      });
    },
    countCol() {
      let count = 0;
      this.header.forEach(e => {
        count += e.type == "colspan" ? e.number : 1;
      });
      return count;
    }
  },
  filters: {}
};
</script>
<style lang="scss" scoped>
.table {
  position: relative;
}
.row-table-header {
  &.children {
    th {
      top: 36px;
    }
  }
  th {
    position: sticky;
    top: -1px;
    background-color: #f4f4f4;
    cursor: pointer;
    .icon {
      display: none;
    }
    &:hover {
      .icon {
        display: inline-block;
      }
    }
  }
}
</style>
