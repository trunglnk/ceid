<template>
  <div class="table table-container table-responsiver">
    <table border="1" cellspacing="0" cellpadding="4" width="100%">
      <thead>
        <tr>
            <td colspan="38" align="center" valign="top">
              <b
                ><font color="#000000"
                  >TỔNG HỢP KẾT QUẢ THANH TRA KIỂM TRA, XỬ LÝ VI PHẠM HÀNH CHÍNH</font
                ></b
              >
            </td>
          </tr>
        <tr>
          <th style="width:40px;text-align:center">STT</th>
          <th style="text-align:center">Tên cơ sở</th>
          <th style="text-align:center">Số tiền xử phạt (VNĐ)</th>
          <th style="text-align:center">QĐXP và hành vi vi phạm</th>
          <th style="text-align:center">Nhóm hành vi vi phạm</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(item, index) in items"
          :key="index"
          :style="item.__is_header ? 'font-weight:bold;background:#f3f3f3' : ''"
        >
          <!-- Nếu là dòng header tỉnh -->
          <template v-if="item.__is_header">
            <td></td>
            <td :colspan="2">{{ item.B }}</td>
            <td colspan="2" style="text-align:right">
              Tổng: {{ item.C }}
            </td>
          </template>

          <!-- Nếu là dòng chi tiết -->
          <template v-else>
            <td style="text-align:center">{{ item.A }}</td>
            <td>{{ item.B }}</td>
            <td style="text-align:right">{{ item.C }}</td>
            <td style="white-space:pre-line">{{ item.D }}</td>
            <td style="white-space:pre-line">{{ item.E }}</td>
          </template>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    items: {
      type: Array,
      default: () => [],
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
</style>
