<template>
    <div class="table table-container table-responsiver">
      <table cellspacing="0" border="0">
        <colgroup span="19" width="107"></colgroup>
        <thead>
          <tr>
            <td colspan="19" align="center" valign="top">
              <b
                ><font color="#000000"
                  >ĐỐI TƯỢNG THANH TRA CƠ BẢN</font
                ></b
              >
            </td>
          </tr>
          <tr>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000"> TT </font></b>
            </td>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">Mã định danh</font></b>
            </td>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">Tên cơ sở</font></b>
            </td>
            <td colspan="4" align="center" valign="top">
              <b
                ><font color="#000000"
                  >Địa chỉ</font
                ></b
              >
            </td>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">Mã định danh</font></b>
            </td>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">Tên chủ đầu tư</font></b>
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
              <b><font color="#000000">Loại hình ô nhiễm</font></b>
            </td>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">Ngành nghề kinh tế Việt Nam</font></b>
            </td>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">Năm thanh tra</font></b>
            </td>
            <td rowspan="2" align="center" valign="top">
              <b><font color="#000000">Lưu vực sông</font></b>
            </td>
          </tr>
          <tr>
            <td align="center" valign="top">
              <b
                ><font color="#000000"
                  >Chi tiết</font
                ></b
              >
            </td>
            <td align="center" valign="top">
              <b
                ><font color="#000000">Xã/Phường</font></b
              >
            </td>
            <td align="center" valign="top">
              <b><font color="#000000">Quận/Huyện</font></b>
            </td>
            <td align="center" valign="top">
              <b><font color="#000000">Tỉnh/Thành phố</font></b>
            </td>
            <td align="center" valign="top">
              <b
                ><font color="#000000"
                  >Chi tiết</font
                ></b
              >
            </td>
            <td align="center" valign="top">
              <b
                ><font color="#000000">Xã/Phường</font></b
              >
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
            <td align="center">{{ item.A }}</td>
            <td align="center">{{ item.B }}</td>
            <td>{{ item.C }}</td>
            <td>{{ item.D }}</td>
            <td>{{ item.E }}</td>
            <td>{{ item.F }}</td>
            <td>{{ item.G }}</td>
            <td align="center">{{ item.H }}</td>
            <td>{{ item.I }}</td>
            <td>{{ item.J }}</td>
            <td>{{ item.K }}</td>
            <td>{{ item.L }}</td>
            <td>{{ item.M }}</td>
            <td>{{ item.N }}</td>
            <td>{{ item.O }}</td>
            <td>{{ item.P }}</td>
            <td>{{ item.Q }}</td>
            <td>{{ item.R }}</td>
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
  