<template>
  <div class="row">
    <div class="col-md-3">
      <div class="box box-solid">
        <div class="box-header">
          <p class="lead">HÀNH VI VI PHẠM HÀNH CHÍNH</p>
        </div>
        <div class="box-body no-padding">
          <ul class="nav nav-pills nav-stacked">
            <li v-for="(item, index) in dieus" :class="{ active: index == 0 }">
              <a
                :href="'#tab_' + index"
                data-toggle="tab"
                aria-expanded="true"
                v-if="index == 0"
              >
                <i class="fa fa-circle-o text-blue"></i>
                <b>{{ item.ten }}.</b> {{ item.mo_ta }}
              </a>
              <a
                :href="'#tab_' + index"
                data-toggle="tab"
                aria-expanded="false"
                v-else
              >
                <i class="fa fa-circle-o text-blue"></i>
                <b>{{ item.ten }}.</b> {{ item.mo_ta }}
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-9">
      <div class="tab-content">
        <div
          class="tab-pane"
          :id="'tab_' + index"
          v-for="(item, index) in dieus"
          :class="{ active: index == 0 }"
        >
          <div class="timeline-item" v-for="(khoan, i) in item.khoans">
            <h3 style="text-align: justify" class="timeline-header">
              {{ khoan.ten }}. {{ khoan.mo_ta }}
            </h3>

            <div class="timeline-body">
              <table class="table table-hover">
                <tbody>
                  <tr v-if="khoan.mucs.length > 0">
                    <th>#</th>
                    <th>Mục</th>
                    <th>Nội dung</th>
                    <th>Mức phạt nhỏ nhất (VND)</th>
                    <th>Mức phạt lớn nhất (VND)</th>
                  </tr>
                  <tr v-for="(muc, y) in khoan.mucs">
                    <td>{{ muc.ma }}</td>
                    <td>{{ muc.ten }}</td>
                    <td style="text-align:justify">{{ muc.mo_ta }}</td>
                    <td>{{ muc.muc_phat_nho_nhat | formatNumber }}</td>
                    <td>{{ muc.muc_phat_lon_nhat | formatNumber }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- /.tab-content -->
    </div>
  </div>
</template>

<script>
import * as env from "../../env.js";

export default {
  data: function() {
    return {
      dieus: []
    };
  },
  created: function() {
    axios.get(env.endpointhttp + "dieus").then(response => {
      this.dieus = response.data.result;
    });
  }
};
</script>
