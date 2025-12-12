<template>
  <div class="row">
    <div class="pt-4 pl-4 pr-4 d-flex">
      <div>
        <h4>
          Cơ sở sản xuất:
          <span style="max-width: 400px; color: rgb(51, 122, 183)">
            {{ coSoData.ten || "-" }}
          </span>
        </h4>
        <h4>
          Điểm trạm quan trắc:
          <span style="max-width: 400px; color: rgb(51, 122, 183)">
            {{ infoDiemTram && infoDiemTram.ten_goi ? infoDiemTram.ten_goi : "-" }}
          </span>
        </h4>
      </div>
    </div>

    <h4 class="pl-4 pt-4 font-weight-bold text-uppercase">Dữ liệu</h4>

    <!-- Bộ lọc -->
    <div class="pb-4 pl-4 pr-4 d-flex justify-content-between align-items-end">
      <div>
        Năm quan trắc:
        <select
          v-model="filterYear"
          class="form-control d-inline-block"
          style="width: 120px"
        >
          <option value="">Tất cả</option>
          <option v-for="year in yearsAvailable" :key="year" :value="year">
            {{ year }}
          </option>
        </select>
      </div>

      <div class="input-group" style="width: 400px">
        <input
          type="text"
          v-model.trim="search"
          class="form-control"
          placeholder="Tìm theo mã định danh, loại hình, loại số liệu, thông số..."
          @keyup.enter="onSearch"
        />
        <span
          class="input-group-addon"
          style="cursor: pointer; background: #f5f5f5"
          @click="onSearch"
        >
          <i class="fa fa-search"></i>
        </span>
      </div>
    </div>

    <!-- Bảng dữ liệu -->
    <div v-if="groupedData.length > 0" class="col-md-12">
      <table class="table table-hover fixed_headers">
        <thead class="row-table-header">
          <tr>
            <th>STT</th>
            <th>Loại hình QTMT</th>
            <th>Loại số liệu QTMT</th>
            <th>Mã định danh</th>
            <th>Năm quan trắc</th>
            <th>Thông số môi trường</th>
            <th>Quy chuẩn môi trường</th>
            <th>Giá trị quan trắc</th>
            <th>Đơn vị đo</th>
            <th>Giới hạn min</th>
            <th>Giới hạn max</th>
          </tr>
        </thead>

        <tbody>
          <template v-for="(group, i) in groupedData">
            <tr v-for="(item, j) in group.items" :key="'g-' + i + '-' + j">
              <template v-if="j === 0">
                <td :rowspan="group.items.length">{{ i + 1 }}</td>
                <td :rowspan="group.items.length">{{ group.loai_hinh_qtmt }}</td>
                <td :rowspan="group.items.length">{{ group.loai_so_lieu_qtmt }}</td>
                <td :rowspan="group.items.length">{{ group.ma_dinh_danh }}</td>
                <td :rowspan="group.items.length">{{ group.nam_quan_trac }}</td>
              </template>

              <td>{{ item.thong_so_ten || "-" }}</td>
              <td>{{ item.quy_chuan_ten || "-" }}</td>
              <td>{{ item.gia_tri_quan_trac || "-" }}</td>
              <td>{{ item.don_vi_ten || "-" }}</td>
              <td>{{ item.gia_tri_gioi_han_min || "-" }}</td>
              <td>{{ item.gia_tri_gioi_han_max || item.giaTriGioiHanMax || "-" }}</td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>

    <h4 v-else class="pl-4 pt-4 text-muted">
      Không có kết quả quan trắc liên quan đến điểm trạm này
    </h4>
  </div>
</template>

<script>
export default {
  props: ["data", "madinhdanh"],
  data() {
    return {
      ketQuaData: [],
      groupedData: [],
      coSoData: {},
      infoDiemTram: null,
      search: "",
      filterYear: "",
    };
  },
  computed: {
    yearsAvailable() {
      const years = this.ketQuaData.map((x) => x.nam_quan_trac).filter(Boolean);
      return [...new Set(years)].sort((a, b) => b - a);
    },
  },
  watch: {
    // ✅ Tự động lọc lại khi đổi năm
    filterYear(newVal) {
      let list = this.ketQuaData;
      if (newVal) list = list.filter((x) => x.nam_quan_trac == newVal);
      this.groupedData = this.groupByDefault(list);
    },
  },
  created() {
    const payload = JSON.parse(sessionStorage.getItem("dataCoSo") || "{}");
    this.coSoData = payload;

    if (
      payload &&
      payload.tinh_thanh.diem_tram_qtmt_theo_tinh &&
      payload.tinh_thanh.diem_tram_qtmt_theo_tinh.length
    ) {
      this.infoDiemTram = payload.tinh_thanh.diem_tram_qtmt_theo_tinh.find(
        (x) => x.ma_dinh_danh === this.madinhdanh
      );
    }

    // Lọc theo mã trạm
    this.ketQuaData = this.data.filter(
      (item) => item.ma_tram === this.madinhdanh
    );

    this.groupedData = this.groupByDefault(this.ketQuaData);
      console.log('this.groupedData', this.groupedData);
  },
  methods: {
    onSearch() {
      const q = this.search.toLowerCase().trim();
      if (!q) {
        this.groupedData = this.groupByDefault(this.ketQuaData);
        return;
      }

      const filtered = this.ketQuaData.filter(
        (x) =>
          (x.ma_dinh_danh && x.ma_dinh_danh.toLowerCase().includes(q)) ||
          (x.thong_so_ten && x.thong_so_ten.toLowerCase().includes(q)) ||
          (x.quy_chuan_ten && x.quy_chuan_ten.toLowerCase().includes(q)) ||
          (x.loai_hinh_qtmt && x.loai_hinh_qtmt.toLowerCase().includes(q))
      );

      this.groupedData = this.groupByDefault(filtered);
      
    },

    groupByDefault(list) {
      const grouped = {};
      list.forEach((item) => {
        const key = `${item.ma_dinh_danh}_${item.nam_quan_trac}`;
        if (!grouped[key]) {
          grouped[key] = {
            ma_dinh_danh: item.ma_dinh_danh,
            loai_hinh_qtmt: item.loai_hinh_qtmt,
            loai_so_lieu_qtmt: item.loai_so_lieu_qtmt,
            nam_quan_trac: item.nam_quan_trac,
            items: [],
          };
        }
        grouped[key].items.push(item);
      });
      return Object.values(grouped);
    },
  },
};
</script>

<style scoped>
.table th,
.table td {
  vertical-align: middle !important;
  border: 1px solid #ddd;
}
.table th {
  background-color: #f7f7f7;
  text-align: center;
}
</style>
