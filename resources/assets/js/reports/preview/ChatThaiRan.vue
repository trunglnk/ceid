<template>
  <div class="table table-container table-responsiver">
    <table cellspacing="0" border="0">
      <colgroup span="10" width="107"></colgroup>
      <thead>
        <tr>
          <td colspan="12" height="21" align="center" valign="top">
            <b>
              <font color="#000000">Chất thải rắn</font>
            </b>
          </td>
        </tr>
        <tr>
          <td rowspan="2" height="42" align="center" valign="top">
            <b>
              <font color="#000000">TT</font>
            </b>
          </td>
          <td rowspan="2" align="center" valign="top">
            <b>
              <font color="#000000">Mã định danh</font>
            </b>
          </td>
          <td rowspan="2" align="center" valign="top">
            <b>
              <font color="#000000">Tên cơ sở</font>
            </b>
          </td>
          <td colspan="4" align="center" valign="top">
            <b>
              <font color="#000000">Địa chỉ</font>
            </b>
          </td>
          <td rowspan="2" align="center" valign="top">
            <b>
              <font color="#000000">Loại hình cơ sở</font>
            </b>
          </td>
          <td rowspan="2" align="center" valign="top">
            <b>
              <font color="#000000">Loại ngành nghề kinh tế Việt Nam</font>
            </b>
          </td>
          <td rowspan="2" align="center" valign="top">
            <b>
              <font color="#000000">Chất thải rắn sinh hoạt</font>
            </b>
          </td>
          <td rowspan="2" align="center" valign="top">
            <b>
              <font color="#000000">Chất thải rắn công nghiệp</font>
            </b>
          </td>
          <td rowspan="2" align="center" valign="top">
            <b>
              <font color="#000000">CTNH</font>
            </b>
          </td>
        </tr>
        <tr>
          <td align="center" valign="top">
            <b>
              <font color="#000000">Chi tiết</font>
            </b>
          </td>
          <td align="center" valign="top">
            <b>
              <font color="#000000">Xã/Phường</font>
            </b>
          </td>
          <td align="center" valign="top">
            <b>
              <font color="#000000">Quận/Huyện</font>
            </b>
          </td>
          <td align="center" valign="top">
            <b>
              <font color="#000000">Tỉnh/Thành phố</font>
            </b>
          </td>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in items" :key="index">
          <td>{{ item.A }}</td>
          <td>{{ item.B }}</td>
          <td>{{ item.C }}</td>
          <td>{{ item.D }}</td>
          <td>{{ item.E }}</td>
          <td>{{ item.F }}</td>
          <td>{{ item.G }}</td>
          <td>{{ item.H }}</td>
          <td>{{ item.I }}</td>
          <td>{{ item.J }}</td>
          <td>{{ item.K }}</td>
          <td>{{ item.L }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: { items: { default: () => [] } },
  data() {
    return {};
  }
};
</script>
