<template>
  <div class="row">
    <div class="pt-4 pl-4 pr-4 d-flex">
      <div>
        <a href="/ketquaphantichmau/add">
          <button type="submit" id="onsubmit" class="btn bg-olive btn-flat">
            <i class="fa fa-plus"></i> Thêm kết quả
          </button>
        </a>
      </div>
    </div>
    <div class="nav-tabs-custom margin-top">
      <ul class="nav nav-tabs">
        <li
          v-for="tab in tabs"
          :key="tab.key"
          :class="{ active: currentTab === tab.key }"
        >
          <a href="javascript:void(0)" @click="changeTab(tab.key)">
            {{ tab.label }}
          </a>
        </li>
      </ul>
      <div class="tab-content">
        <div
          v-for="tab in tabs"
          :key="tab.key"
          :class="['tab-pane', { active: currentTab === tab.key }]"
        >
          <div
            class="pb-4 pl-4 pr-4 d-flex justify-content-between align-items-end"
          >
            <div>
              Hiển thị
              <select
                v-model="paging[tab.key].perPage"
                style="width: 80px"
                @change="getData(tab.key)"
              >
                <option v-for="item in options" :value="item" :key="item">
                  {{ item }}
                </option>
              </select>
              mục
            </div>

            <div class="input-group">
              <input
                type="text"
                v-model="search[tab.key]"
                class="form-control"
                style="z-index: 0; width: 400px"
                placeholder="Tìm kiếm"
                @keyup.enter="getData(tab.key)"
              />
              <span
                class="input-group-addon"
                style="cursor: pointer"
                @click="getData(tab.key)"
              >
                <i class="fa fa-search"></i>
              </span>
            </div>
          </div>

          <!-- Bảng dữ liệu -->
          <div class="col-md-12">
            <table class="table table-hover fixed_headers">
              <thead class="row-table-header">
                <tr>
                  <th>STT</th>
                  <th>Loại mẫu</th>
                  <th>Địa điểm lấy mẫu</th>
                  <th>Vị trí lấy mẫu</th>
                  <th>Kinh độ</th>
                  <th>Vĩ độ</th>
                  <th>Thông số</th>
                  <th>Đơn vị tính</th>
                  <th>Phương pháp phân tích</th>
                  <th>Kết quả</th>
                  <th>Giá trị giới hạn</th>
                  <th>Số lần vượt</th>
                  <th>Hành động</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(item, index) in data[tab.key]"
                  :key="item.id || index"
                >
                  <td style="width: 50px">
                    {{
                      (paging[tab.key].page - 1) * paging[tab.key].perPage +
                      index +
                      1
                    }}
                  </td>
                  <td
                    style="color: rgb(51, 122, 183); cursor: pointer"
                    @click="goEdit(item.id)"
                  >
                    {{ item.loai_mau }}
                  </td>
                  <td>{{ item.dia_diem }}</td>
                  <td>{{ item.vi_tri }}</td>
                  <td>{{ item.kinh_do }}</td>
                  <td>{{ item.vi_do }}</td>
                  <td>{{ item.thong_so }}</td>
                  <td>{{ item.don_vi_tinh }}</td>
                  <td>{{ item.phuong_phap_phan_tich }}</td>
                  <td>{{ item.ket_qua }}</td>
                  <td>{{ item.gia_tri_gioi_han }}</td>
                  <td>{{ item.so_lan_vuot }}</td>
                  <td style="width: 75px; text-align: center">
                    <i
                      class="fa fa-info mr-3"
                      @click="goEdit(item.id)"
                      title="Cập nhật"
                    ></i>
                    <i
                      class="fa fa-trash-o"
                      @click="showConfirmDelete(item)"
                      title="Xóa"
                    ></i>
                  </td>
                </tr>
              </tbody>
            </table>

            <!-- Phân trang -->
            <div class="d-flex justify-content-between align-items-center">
              <div>
                Hiển thị từ
                {{ (paging[tab.key].page - 1) * paging[tab.key].perPage + 1 }}
                tới
                {{
                  Math.min(
                    paging[tab.key].page * paging[tab.key].perPage,
                    paging[tab.key].total
                  )
                }}
                của {{ paging[tab.key].total }}
              </div>
              <paginate
                v-model="paging[tab.key].page"
                :page-count="paging[tab.key].pageCount"
                :click-handler="() => getData(tab.key)"
                :prev-text="'‹‹'"
                :next-text="'››'"
                :container-class="'pagination'"
                :page-class="'page-item'"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal xác nhận -->
    <modal-component
      width="450px"
      v-model="showModel"
      center
      @submit="xoa()"
      :title="'Xóa chủ đầu tư'"
    >
      <div>
        Bạn có chắc chắn muốn xóa mẫu <b>{{ xoaKetQua.loai_mau }}</b> không?
      </div>
    </modal-component>
  </div>
</template>

<script>
import * as env from "../env.js";
import MessageService from "../services/MessageService";

export default {
  data() {
    return {
      currentTab: "nuoc_thai",
      tabs: [
        { key: "nuoc_thai", label: "Nước thải" },
        { key: "khi_thai", label: "Khí thải" },
        { key: "nuoc_mat", label: "Nước mặt" },
        { key: "nuoc_ven_bien", label: "Nước ven biển" },
        { key: "bun_thai", label: "Bùn thải" },
        { key: "khong_khi", label: "Không khí" },
      ],

      data: {
        nuoc_thai: [],
        khi_thai: [],
        nuoc_mat: [],
        nuoc_ven_bien: [],
        bun_thai: [],
        khong_khi: [],
      },

      search: {
        nuoc_thai: "",
        khi_thai: "",
        nuoc_mat: "",
        nuoc_ven_bien: "",
        bun_thai: "",
        khong_khi: "",
      },

      paging: {
        nuoc_thai: { page: 1, perPage: 10, pageCount: 0, total: 0 },
        khi_thai: { page: 1, perPage: 10, pageCount: 0, total: 0 },
        nuoc_mat: { page: 1, perPage: 10, pageCount: 0, total: 0 },
        nuoc_ven_bien: { page: 1, perPage: 10, pageCount: 0, total: 0 },
        bun_thai: { page: 1, perPage: 10, pageCount: 0, total: 0 },
        khong_khi: { page: 1, perPage: 10, pageCount: 0, total: 0 },
      },

      options: [10, 20, 50],
      showModel: false,
      xoaKetQua: {},
    };
  },
  created() {
    this.getData(this.currentTab);
  },
  methods: {
    changeTab(tabKey) {
      this.currentTab = tabKey;
      if (this.data[tabKey].length === 0) {
        this.getData(tabKey);
      }
    },

    getData(loaiMoiTruong) {
      const p = this.paging[loaiMoiTruong];

      axios
        .get(env.endpointhttp + "ketquaphantichmau/get", {
          params: {
            page: p.page,
            perPage: p.perPage,
            search: this.search[loaiMoiTruong],
            loai_moi_truong: loaiMoiTruong,
          },
        })
        .then((response) => {
          this.data[loaiMoiTruong] = response.data.data;
          this.paging[loaiMoiTruong].pageCount = Number(
            response.data.last_page
          );
          this.paging[loaiMoiTruong].total = Number(response.data.total);
          console.log(`[${loaiMoiTruong}] Dữ liệu nhận được:`, response.data);
        })
        .catch((error) => {
          console.error(`[${loaiMoiTruong}] Lỗi khi tải dữ liệu:`, error);
        });
    },

    goEdit(id) {
      window.location.href = "/ketquaphantichmau/edit/" + id;
    },

    showConfirmDelete(item) {
      this.showModel = true;
      this.xoaKetQua = item;
    },

    xoa() {
      axios
        .delete(env.endpointhttp + "ketquaphantichmau/delete/" + this.xoaKetQua.id)
        .then(() => {
          this.getData(this.currentTab);
          this.showModel = false;
          MessageService.showSuccessMessage("Xóa thành công");
        })
        .catch(() => {
          MessageService.showWarningMessage("Xoá thất bại");
        });
    },
  },
};
</script>

<style scoped></style>
