<template>
  <div class="table table-container table-responsiver">
    <table border="1" cellspacing="0" cellpadding="4" width="100%">
      <thead>
        <tr>
          <td colspan="12" align="center" valign="top">
            <b><font color="#000000">TỔNG HỢP KẾT QUẢ KIỂM TRA, VI PHẠM</font></b>
          </td>
        </tr>
        <tr>
          <th style="width:50px;text-align:center">TT</th>
          <th style="text-align:center">Tên cơ sở kiểm tra</th>
          <th style="text-align:center">Địa chỉ</th>
          <th style="text-align:center; white-space:nowrap">Kết quả kiểm tra<br/>(Vi phạm, tồn tại)</th>
          <th style="text-align:center; white-space:nowrap">Lập BBVP<br/>(Có/không)</th>
          <th style="text-align:center">QĐXP</th>
          <th style="text-align:center; white-space:nowrap">Tiền phạt (đồng)</th>
          <th style="text-align:center; white-space:nowrap">Chi phí KPHQ (đồng)</th>
          <th style="text-align:center; white-space:nowrap">Số tiền đã nộp (đồng)</th>
          <th style="text-align:center">Thông báo KLKT</th>
          <th style="text-align:center">Cán bộ xử lý</th>
          <th style="text-align:center">Tiến độ công việc</th>
        </tr>
      </thead>

      <tbody>
        <tr
          v-for="(item, index) in items"
          :key="index"
          :style="item.__is_header ? 'font-weight:bold;background:#f3f3f3' : ''"
        >
          <!-- Dòng dữ liệu -->
          <template>
            <!-- A: TT -->
            <td style="text-align:center">{{ item.A }}</td>

            <!-- B: Tên cơ sở kiểm tra -->
            <td>{{ item.B }}</td>

            <!-- C: Địa chỉ -->
            <td>{{ item.C }}</td>

            <!-- D: Kết quả kiểm tra (Vi phạm, tồn tại) -->
            <td>{{ item.D }}</td>

            <!-- E: Lập BBVP (Có/không) -->
            <td style="text-align:center">{{ item.E }}</td>

            <!-- F: QĐXP (nhiều dòng) -->
            <td style="white-space:pre-line">{{ item.F }}</td>

            <!-- G: Tiền phạt -->
            <td style="text-align:right">{{ formatMoney(item.G) }}</td>

            <!-- H: Chi phí KPHQ -->
            <td style="text-align:right">{{ formatMoney(item.H) }}</td>

            <!-- I: Số tiền đã nộp -->
            <td style="text-align:right">{{ formatMoney(item.I) }}</td>

            <!-- J: Thông báo KLKT -->
            <td style="white-space:pre-line">{{ item.J }}</td>

            <!-- K: Cán bộ xử lý -->
            <td style="white-space:pre-line">{{ item.K }}</td>

            <!-- L: Tiến độ công việc -->
            <td style="white-space:pre-line">{{ item.L }}</td>
          </template>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: "TongHopKetQuaKiemTraViPham",
  props: {
    items: {
      type: Array,
      default: () => [],
    },
  },
  methods: {
    formatMoney(val) {
      if (val === null || val === undefined || val === "") return "";
      const num = Number(val);
      if (isNaN(num)) return val;
      // format kiểu VNĐ: ngăn cách hàng nghìn bằng dấu chấm
      return num.toLocaleString("vi-VN");
    },
  },
};
</script>

<style scoped>
.table-container {
  margin-top: 10px;
  overflow-x: auto;
}
table {
  border-collapse: collapse;
  width: 100%;
}
th, td {
  border: 1px solid #ccc;
  padding: 6px;
  vertical-align: top;
}
thead th {
  background: #fafafa;
}
</style>
