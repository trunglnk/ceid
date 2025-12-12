<template>
  <div class="table table-container table-responsiver">
    <table cellspacing="0" border="0">
      <colgroup span="28" width="107"></colgroup>
      <thead>
        <tr>
          <td colspan="28" height="21" align="center" valign="top">
            <b
              ><font color="#000000"
                >T&#7892;NG H&#7906;P C&Aacute;C C&#416; S&#7902; VI PH&#7840;M
                V&#7872; X&#7842; TH&#7842;I</font
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
            <b><font color="#000000">Loại ngành nghề</font></b>
          </td>
          <td colspan="3" align="center" valign="top">
            <b><font color="#000000">Quyết định thanh tra</font></b>
          </td>
          <td colspan="3" align="center" valign="top">
            <b><font color="#000000">Kết luận thanh tra</font></b>
          </td>
          <td rowspan="2" align="center" valign="top">
            <b><font color="#000000">Lưu vực sông</font></b>
          </td>
          <td rowspan="2" align="center" valign="top">
            <b><font color="#000000"></font>Nước thải (m3/ngày.đêm)</b>
          </td>
          <td rowspan="2" align="center" valign="top">
            <b><font color="#000000"></font>HTXLNT (m3/ngày.đêm)</b>
          </td>
          <td rowspan="2" align="center" valign="top">
            <b><font color="#000000"></font>Nguồn tiếp nhận</b>
          </td>
          <!-- <td rowspan="2" align="center" valign="top">
            <b><font color="#000000"></font>Tổng khối lượng nước thải</b>
          </td> -->
          <td rowspan="2" align="center" valign="top">
            <b><font color="#000000"></font>Nội dung vi phạm</b>
          </td>
          <td rowspan="2" align="center" valign="top">
            <b><font color="#000000"></font>Thông số vượt</b>
          </td>
          <td rowspan="2" align="center" valign="top">
            <b><font color="#000000"></font>Số lần vượt</b>
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
            <td align="center" valign="top">
              <b
                ><font color="#000000"
                  >Số Quyết định TT</font
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
                  >Số Kết luận TT</font
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
          <td>{{ item.P }}</td>
          <td>{{ item.Q }}</td>
          <td>{{ item.R }}</td>
          <td>{{ item.S }}</td>
          <td>{{ item.T }}</td>
          <td>{{ item.U }}</td>
          <td align="center">{{ item.V }}</td>
          <td align="center">{{ item.W }}</td>
          <td>{{ item.X }}</td>
          <td>{{ item.Y }}</td>
          <td>{{ item.Z }}</td>
          <td>{{ item.AA }}</td>
          <td>{{ item.AB }}</td>
          <td>{{ item.AC }}</td>
          <!-- <td>{{ item.AD }}</td> -->
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
