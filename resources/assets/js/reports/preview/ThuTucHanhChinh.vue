<template>
    <div class="table table-container table-responsiver">
      <table>
        <colgroup span="12" width="107"></colgroup>
        <thead>
          <tr>
            <td colspan="17" align="center" valign="top">
              <b
                ><font color="#000000"
                  >BẢNG TỔNG HỢP SỐ LIỆU THANH TRA, 
                  KIỂM TRA TẠI CÁC CƠ SỞ THUỘC THẨM QUYỀN PHÊ DUYỆT ĐTM, 
                  <br>
                  CẤP GIẤY PHÉP MÔI TRƯỜNG, 
                  GIẤY PHÉP MÔI TRƯỜNG THÀNH PHẦN CỦA BỘ TÀI NGUYÊN VÀ MÔI TRƯỜNG</font
                ></b
              >
            </td>
          </tr>
          <tr>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">TT</font></b>
            </td>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">Mã định danh</font></b>
            </td>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">Tên cơ sở / Chủ đầu tư</font></b>
            </td>
            <td colspan="4" align="center" valign="top">
              <b
                ><font color="#000000"
                  >Địa chỉ</font
                ></b
              >
            </td>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">Loại hình cơ sở</font></b>
            </td>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">Ngành nghề kinh tế Việt Nam</font></b>
            </td>
            <td colspan="2" align="center" valign="top">
              <b><font color="#000000">Cấp phê duyệt</font></b>
            </td>
            <td colspan="3" align="center" valign="top">
              <b><font color="#000000">Văn bản ĐTM</font></b>
            </td>
            <td colspan="3" align="center" valign="top">
              <b><font color="#000000">Giấy phép môi trường</font></b>
            </td>
          </tr>
          <tr>
            <td align="center" valign="top">
              <b
                ><font color="#000000">Chi tiết</font></b
              >
            </td>
            <td align="center" valign="top">
              <b
                ><font color="#000000">Xã phường</font></b
              >
            </td>
            <td align="center" valign="top">
              <b
                ><font color="#000000">Quận huyện</font></b
              >
            </td>
            <td align="center" valign="top">
              <b
                ><font color="#000000">Tỉnh thành</font></b
              >
            </td>
            <td align="center" valign="top">
              <b
                ><font color="#000000">Cấp bộ</font></b
              >
            </td>
            <td align="center" valign="top">
              <b><font color="#000000">Địa phương</font></b>
            </td>
            <td align="center" valign="top">
              <b
                ><font color="#000000"
                  >Số văn bản</font
                ></b
              >
            </td>
            <td align="center" valign="top">
              <b
                ><font color="#000000">Ngày ban hành</font></b
              >
            </td>
            <td align="center" valign="top">
              <b><font color="#000000">Cơ quan ban hành</font></b>
            </td>
            <td align="center" valign="top">
              <b
                ><font color="#000000"
                  >Số giấy phép</font
                ></b
              >
            </td>
            <td align="center" valign="top">
              <b
                ><font color="#000000">Ngày ban hành</font></b
              >
            </td>
            <td align="center" valign="top">
              <b><font color="#000000">Cơ quan ban hành</font></b>
            </td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in items" :key="index">
            <td align="center">{{ item.A }}</td>
            <td>{{ item.B }}</td>
            <td>{{ item.C }}</td>
            <td>{{ item.D }}</td>
            <td align="center">{{ item.E }}</td>
            <td align="center">{{ item.F }}</td>
            <td>{{ item.G }}</td>
            <td>{{ item.H }}</td>
            <td>{{ item.I }}</td>
            <td>{{ item.J }}</td>
            <td>{{ item.K }}</td>
            <td>{{ item.L }}</td>
            <td>{{ item.M }}</td>
            <td>{{ item.N }}</td>
            <td>{{ item.O }}</td>
            <td>{{ item.P }}</td>
            <td>{{ item.Q }}</td>
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
  