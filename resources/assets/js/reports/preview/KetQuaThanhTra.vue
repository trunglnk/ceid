<template>
  <div class="table table-container table-responsiver">
    <table cellspacing="0" border="0">
      <colgroup span="15" width="107"></colgroup>
      <thead>
        <tr>
          <td colspan="15" height="21" align="center" valign="top">
            <b><font color="#000000">K&#7870;T QU&#7842; THANH TRA</font></b>
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
            <b
              ><font color="#000000"
                >&#272;&#7883;a ch&#7881; ho&#7841;t &#273;&#7897;ng</font
              ></b
            >
          </td>
          <td rowspan="2" align="center" valign="top">
            <b
              ><font color="#000000"
                >L&#297;nh v&#7921;c ho&#7841;t &#273;&#7897;ng</font
              ></b
            >
          </td>
          <td colspan="2" align="center" valign="top">
            <b
              ><font color="#000000"
                >Th&#7849;m quy&#7873;n ph&ecirc; duy&#7879;t th&#7911;
                t&#7909;c m&ocirc;i tr&#432;&#7901;ng
              </font></b
            >
          </td>
          <td rowspan="2" align="center" valign="top">
            <b
              ><font color="#000000"
                >Q&#272;TT<br />(S&ocirc;&#769; Q&#272; tha&#768;nh
                l&acirc;&#803;p &#273;oa&#768;n TT va&#768; nga&#768;y ban
                ha&#768;nh)</font
              ></b
            >
          </td>
          <td rowspan="2" align="center" valign="top">
            <b
              ><font color="#000000"
                >S&#7889; k&#7871;t lu&#7853;n TT<br />(S&ocirc;&#769; KLTT
                va&#768; nga&#768;y ban ha&#768;nh)</font
              ></b
            >
          </td>
          <td colspan="4" align="center" valign="top">
            <b
              ><font color="#000000"
                >Quy&#7871;t &#273;&#7883;nh x&#7917; ph&#7841;t VPHC</font
              ></b
            >
          </td>
          <td colspan="3" align="center" valign="top">
            <b><font color="#000000">K&#7871;t lu&#7853;n thanh tra</font></b>
          </td>
        </tr>
        <tr>
          <td align="center" valign="top">
            <b><font color="#000000">C&#7845;p b&#7897;</font></b>
          </td>
          <td align="center" valign="top">
            <b
              ><font color="#000000"
                >C&#7845;p &#273;&#7883;a ph&#432;&#417;ng</font
              ></b
            >
          </td>
          <td align="left" valign="top">
            <b><font color="#000000">S&#7889; Q&#272; XP VPHC</font></b>
          </td>
          <td align="left" valign="top" sdnum="1033;0;#,##0">
            <b
              ><font color="#000000"
                >S&#7889; ti&#7873;n x&#7917; ph&#7841;t ch&iacute;nh</font
              ></b
            >
          </td>
          <td align="left" valign="top">
            <b><font color="#000000">H&igrave;nh th&#7913;c XPBX</font></b>
          </td>
          <td align="left" valign="top">
            <b><font color="#000000">Bi&#7879;n ph&aacute;p KPHQ</font></b>
          </td>
          <td align="left" valign="top">
            <b><font color="#000000">K&#7871;t qu&#7843; ch&iacute;nh</font></b>
          </td>
          <td align="left" valign="top">
            <b><font color="#000000">Vi ph&#7841;m</font></b>
          </td>
          <td align="left" valign="top">
            <b><font color="#000000">T&#7891;n t&#7841;i</font></b>
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
<style></style>
