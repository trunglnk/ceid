<template>
  <div class="row">
    <div class="row ma-2">
      <div class="col-md-6">
        <label>Trạng thái</label>
        <multiselect
          v-model="loaiTrangThaiSelect"
          label="label"
          track-by="value"
          placeholder="Chọn trạng thái"
          :options="loaiTrangThaiOptions"
          :multiple="false"
          :searchable="true"
        />
      </div>
      <div class="col-md-12">
        <button
          type="submit"
          class="btn bg-olive btn-flat"
          @click="updateTrangThaiDongBo"
        >
          <i class="fa fa-check"></i> Cập nhật
        </button>
      </div>
    </div>

    <!-- 🔹 Phần hiển thị log -->
    <div class="col-md-12 mt-4">
      <h5><i class="fa fa-history"></i> Lịch sử đồng bộ</h5>
      <table class="table table-bordered table-striped mt-2">
        <thead>
          <tr>
            <th style="width: 50px">#</th>
            <th>Người thực hiện</th>
            <th>Thời gian</th>
            <th>Dữ liệu</th>
            <th>Yêu cầu</th>
            <th>Kết quả</th>
            <th>Mô tả</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(log, index) in logs" :key="log.id">
            <td>{{ (currentPage - 1) * perPage + index + 1 }}</td>
            <td>
              {{ log.user && log.user.name ? log.user.name : "Hệ thống" }}
            </td>
            <td>{{ formatDate(log.created_at) }}</td>
            <td>
              <span
                :class="{
                  'badge bg-success': log.hanh_dong.includes('Bật'),
                  'badge bg-danger': log.hanh_dong.includes('Tắt'),
                }"
              >
                {{ log.hanh_dong }}
              </span>
            </td>
            <td>
              <button
                class="btn btn-xs btn-info"
                @click="viewJson(log.yeu_cau, 'Yêu cầu')"
              >
                Xem JSON
              </button>
            </td>
            <td>
              <button
                class="btn btn-xs btn-primary"
                @click="viewJson(log.ket_qua, 'Kết quả')"
              >
                Xem JSON
              </button>
            </td>
            <td>{{ log.mo_ta }}</td>
          </tr>
        </tbody>
      </table>

      <!-- 🔹 Phân trang -->
      <div class="d-flex justify-content-between align-items-center">
        <div>
          Hiển thị từ {{ (page - 1) * page + 1 }} tới
          {{ page * page > totalPages ? totalPages : page * perPage }}
          của
          {{ totalPages }}
        </div>
        <paginate
          v-model="page"
          :page-count="pageCount"
          :page-range="3"
          :margin-pages="2"
          :click-handler="changePage"
          :prev-text="'‹‹'"
          :next-text="'››'"
          :container-class="'pagination'"
          :page-class="'page-item'"
        >
        </paginate>
      </div>
    </div>

    <!-- 🔹 Modal xem JSON -->
    <div
      class="modal fade"
      id="jsonModal"
      tabindex="-1"
      role="dialog"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ jsonTitle }}</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="max-height: 70vh; overflow-y: auto">
            <pre>{{ formattedJson }}</pre>
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

export default {
  props: ["chitiet"],
  components: { Multiselect },
  data() {
    return {
      trangThaiDongBo: false,
      loaiTrangThaiOptions: [
        { label: "Bật đồng bộ", value: true },
        { label: "Tắt đồng bộ", value: false },
      ],
      loaiTrangThaiSelect: null,
      logs: [],
      currentPage: 1,
      perPage: 10,
      totalPages: 0,
      page: 1,
      pageCount: 0,
      jsonData: null,
      jsonTitle: "",
      formattedJson: "",
    };
  },

  mounted() {
    this.trangThaiDongBo = this.chitiet.trang_thai === true;
    this.fetchLogs();
  },
  created() {
    if (this.chitiet) {
      this.loaiTrangThaiSelect = this.loaiTrangThaiOptions.find(
        (opt) => opt.value === this.chitiet.trang_thai
      );
    }
  },

  methods: {
    updateTrangThaiDongBo() {
      this.trangThaiDongBo = this.loaiTrangThaiSelect
        ? this.loaiTrangThaiSelect.value
        : null;
      this.$nextTick(() => {
        axios
          .put(env.endpointhttp + "dongbocsdl/1", {
            trang_thai: this.trangThaiDongBo,
          })
          .then(() => {
            if (this.trangThaiDongBo) {
              MessageService.showSuccessMessage(
                "Đã bật đồng bộ dữ liệu từ CSDL MTQG"
              );
            } else {
              MessageService.showSuccessMessage(
                "Đã tắt đồng bộ dữ liệu từ CSDL MTQG"
              );
            }
            this.fetchLogs(1);
          })
          .catch((error) => {
            MessageService.showErrorMessage("Cập nhật thất bại");
          });
      });
    },

    // 🔹 Lấy danh sách log
    fetchLogs(page = this.page) {
      axios
        .get(`${env.endpointhttp}dongbocsdl/logs?page=${page}`)
        .then((res) => {
          this.logs = res.data.data;
          this.currentPage = res.data.current_page;
          this.totalPages = res.data.total;
          this.pageCount = res.data.last_page;
        })
        .catch((err) => {
          console.error("Không thể tải log:", err);
        });
    },

    // 🔹 Format ngày giờ
    formatDate(dateStr) {
      if (!dateStr) return "";
      const date = new Date(dateStr);
      return date.toLocaleString("vi-VN", {
        hour12: false,
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
      });
    },

    changePage() {
      this.fetchLogs();
    },

    viewJson(data, title) {
      this.jsonTitle = title;
      try {
        this.formattedJson = JSON.stringify(data, null, 2);
      } catch (e) {
        this.formattedJson = "Không thể hiển thị dữ liệu JSON";
      }
      $("#jsonModal").modal("show");
    },
  },
};
</script>

<style scoped>
.badge {
  padding: 4px 8px;
  border-radius: 4px;
}

.bg-success {
  background-color: #001f3f;
}

.bg-danger {
  background-color: #ff6a6a;
}
.table th,
.table td {
  vertical-align: middle !important;
}
.pagination-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
}

.pre {
  background: #f8f9fa;
  border-radius: 6px;
  padding: 12px;
  font-size: 13px;
  overflow-x: auto;
}
</style>
