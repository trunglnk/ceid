<template>
    <div class="table table-container table-responsiver">
      <table>
        <colgroup span="14" width="107"></colgroup>
        <thead>
          <!-- <tr>
            <td colspan="16" height="21" align="center" valign="top">
              <b
                ><font color="#000000"
                  >THÔNG TIN CHUNG CỦA CÁC CƠ SỞ/CHỦ ĐẦU TƯ</font
                ></b
              >
            </td>
          </tr> -->
          <tr>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">TT</font></b>
            </td>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">Mã định danh</font></b>
            </td>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">Tên khu sản xuất dịch vụ tập trung</font></b>
            </td>
            <td colspan="4" align="center" valign="top">
              <b
                ><font color="#000000"
                  >Địa chỉ</font
                ></b
              >
            </td>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">Quyết định thành lập đoàn thanh tra</font></b>
            </td>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">Kết luận thanh tra</font></b>
            </td>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">Năm thanh tra</font></b>
            </td>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">Năm xử phạt</font></b>
            </td>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">Số tiền xử phạt</font></b>
            </td>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">Nội dung vi phạm</font></b>
            </td>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">Xử phạt bổ sung</font></b>
            </td>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">Biện pháp khắc phục hậu quả vi phạm</font></b>
            </td>
          </tr>
          <tr>
            <td align="center" valign="top">
              <b><font color="#000000">Chi tiết</font></b>
            </td>
            <td align="center" valign="top">
              <b><font color="#000000">Xã/Phường</font></b>
            </td>
            <td align="center" valign="top">
              <b><font color="#000000">Quận/Huyện</font></b>
            </td>
            <td align="center" valign="top">
              <b><font color="#000000">Tỉnh/Thành phố</font></b>
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
            <td>{{ item.M }}</td>
            <td>{{ item.N }}</td>
            <td>{{ item.O }}</td>
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
  