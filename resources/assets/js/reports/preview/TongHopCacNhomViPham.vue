<template>
  <div class="table table-container table-responsiver">
    <table cellspacing="0" border="0">
      <thead>
        <tr>
          <td colspan="15" height="21" align="center" valign="top">
            <b
              ><font color="#000000"
                >T&#7892;NG H&#7906;P VI PH&#7840;M</font
              ></b
            >
          </td>
        </tr>
        <tr>
          <td rowspan="2" height="42" align="center" valign="top">
            <b><font color="#000000">TT</font></b>
          </td>
          <td rowspan="2" align="center" valign="top">
            <b><font color="#000000">T&ecirc;n c&#417; s&#7903;</font></b>
          </td>
          <td rowspan="2" align="center" valign="top">
            <b><font color="#000000">&#272;&#7883;a ch&#7881;</font></b>
          </td>
          <td rowspan="2" align="center" valign="top">
            <b
              ><font color="#000000"
                >L&#297;nh v&#7921;c ho&#7841;t &#273;&#7897;ng</font
              ></b
            >
          </td>
          <td rowspan="2" align="center" valign="top">
            <b
              ><font color="#000000"
                >N&#432;&#7899;c th&#7843;i (m3/ng&agrave;y
                &#273;&ecirc;m)</font
              ></b
            >
          </td>
          <td rowspan="2" align="center" valign="top">
            <b><font color="#000000">Kh&iacute; th&#7843;i (m3/h)</font></b>
          </td>
          <td colspan="4" align="center" valign="top">
            <b><font color="#000000">Vi ph&#7841;m v&#7873; TTHC</font></b>
          </td>
          <td align="center" valign="top">
            <b
              ><font color="#000000"
                >X&#7843; n&#432;&#7899;c th&#7843;i v&#432;&#7907;t QCVN
                (0/1)</font
              ></b
            >
          </td>
          <td align="center" valign="top">
            <b
              ><font color="#000000"
                >Th&#7843;i kh&iacute;, b&#7909;i th&#7843;i v&#432;&#7907;t
                QCVN (0/1)</font
              ></b
            >
          </td>
          <td align="center" valign="top">
            <b
              ><font color="#000000"
                >X&acirc;y l&#7855;p, v&#7853;n h&agrave;nh c&ocirc;ng
                tr&igrave;nh BVMT (0/1)</font
              ></b
            >
          </td>
          <td align="center" valign="top">
            <b><font color="#000000">QL CTNH (0/1)</font></b>
          </td>
          <td align="center" valign="top">
            <b
              ><font color="#000000"
                >QL CTR th&ocirc;ng th&#432;&#7901;ng (0/1)</font
              ></b
            >
          </td>
        </tr>
        <tr>
          <td align="center" valign="top">
            <b
              ><font color="#000000"
                >&#272;TM/KHBVMT/&#272;&#7873; &aacute;n</font
              ></b
            >
          </td>
          <td align="center" valign="top">
            <b
              ><font color="#000000"
                >GXN ho&agrave;n th&agrave;nh CTBVMT</font
              ></b
            >
          </td>
          <td align="center" valign="top">
            <b><font color="#000000">GSMT</font></b>
          </td>
          <td align="center" valign="top">
            <b><font color="#000000">Công trình BVMT</font></b>
          </td>
          <td align="center" valign="top">
            <b
              ><font color="#000000"><br /></font
            ></b>
          </td>
          <td align="center" valign="top">
            <b
              ><font color="#000000"><br /></font
            ></b>
          </td>
          <td align="center" valign="top">
            <b
              ><font color="#000000"><br /></font
            ></b>
          </td>
          <td align="center" valign="top">
            <b
              ><font color="#000000"><br /></font
            ></b>
          </td>
          <td align="center" valign="top">
            <b
              ><font color="#000000"><br /></font
            ></b>
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
  },
};
</script>
