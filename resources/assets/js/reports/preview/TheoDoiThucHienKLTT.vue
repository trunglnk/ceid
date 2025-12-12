<template>
  <div class="table table-container table-responsiver">
    <table cellspacing="0" border="0">
      <thead>
        <tr>
          <td colspan="16" height="21" align="center" valign="top">
            <b
              ><font color="#000000"
                >B&#7842;NG T&#7892;NG H&#7906;P THEO D&Otilde;I VI&#7878;C
                TH&#7920;C HI&#7878;N K&#7870;T LU&#7852;N THANH TRA V&Agrave;
                QUY&#7870;T &#272;&#7882;NH <br />X&#7916; PH&#7840;T VI
                PH&#7840;M H&Agrave;NH CH&Iacute;NH V&#7872; B&#7842;O V&#7878;
                M&Ocirc;I TR&#431;&#7900;NG</font
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
            <b
              ><font color="#000000"
                >&#272;&#7883;a ch&#7881; <br />ho&#7841;t &#273;&#7897;ng</font
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
          <td rowspan="2" align="center" valign="top">
            <b
              ><font color="#000000"
                >Q&#272;TT<br />(S&ocirc;&#769; Q&#272; tha&#768;nh
                l&acirc;&#803;p &#273;oa&#768;n TT va&#768; nga&#768;y ban
                ha&#768;nh)<br /><br /></font
            ></b>
          </td>
          <td rowspan="2" align="center" valign="top">
            <b
              ><font color="#000000"
                >KLTT<br />(S&ocirc;&#769; KL va&#768; nga&#768;y ban
                ha&#768;nh)
              </font></b
            >
          </td>
          <td colspan="5" align="center" valign="top">
            <b
              ><font color="#000000"
                >Quy&#7871;t &#273;&#7883;nh x&#7917; ph&#7841;t</font
              ></b
            >
          </td>
          <td rowspan="2" align="center" valign="top">
            <b
              ><font color="#000000"
                >T&igrave;nh h&igrave;nh n&#7897;p ph&#7841;t</font
              ></b
            >
          </td>
          <td colspan="4" align="center" valign="top">
            <b
              ><font color="#000000"
                >Th&#7921;c hi&#7879;n<br />kh&#7855;c ph&#7909;c h&#7853;u
                qu&#7843;
              </font></b
            >
          </td>
        </tr>
        <tr>
          <td align="center" valign="top">
            <b><font color="#000000">S&#7889; Q&#272;XP</font></b>
          </td>
          <td align="center" valign="top">
            <b><font color="#000000">H&agrave;nh vi vi ph&#7841;m</font></b>
          </td>
          <td align="center" valign="top">
            <b
              ><font color="#000000">
                T&#7893;ng s&#7889; ti&#7873;n ph&#7841;t (&#273;&#7891;ng)
              </font></b
            >
          </td>
          <td align="center" valign="top">
            <b
              ><font color="#000000">
                H&igrave;nh th&#7913;c x&#7917; ph&#7841;t b&#7893; sung
              </font></b
            >
          </td>
          <td align="center" valign="top">
            <b
              ><font color="#000000">
                BP kh&#7855;c ph&#7909;c <br />h&#7853;u qu&#7843;</font
              ></b
            >
          </td>
          <td align="center" valign="top">
            <b
              ><font color="#000000"
                >B&aacute;o c&aacute;o kh&#7855;c ph&#7909;c</font
              ></b
            >
          </td>
          <td align="center" valign="top">
            <b><font color="#000000">Ch&#432;a th&#7921;c hi&#7879;n </font></b>
          </td>
          <td align="center" valign="top">
            <b
              ><font color="#000000"
                >K&#7871;t qu&#7843; &#273;&atilde; th&#7921;c hi&#7879;n
              </font></b
            >
          </td>
          <td align="center" valign="top">
            <b
              ><font color="#000000"
                >Q&#272; x&aacute;c nh&#7853;n ho&agrave;n th&agrave;nh KP</font
              ></b
            >
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
          <td>{{ item.P }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped></style>

<script>
export default {
  props: { items: { default: () => [] } },
  data() {
    return {};
  }
};
</script>
