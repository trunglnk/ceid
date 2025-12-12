<template>
  <div class="row">
    <div class="row block-multiple-rows">
      <div class="col-md-12">
        <div class="nav-tabs-custom margin-top">
          <ul class="nav nav-tabs">
            <!-- Lặp qua nghi_dinhs để tạo các tab -->
            <li
              v-for="(nghiDinh, index) in nghi_dinhs"
              :key="nghiDinh.id"
              :class="{ active: index === 0 }"
            >
              <a
                :href="'#tab_' + encodeId(nghiDinh.code)"
                data-toggle="tab"
                :aria-expanded="index === 0 ? 'true' : 'false'"
                role="tab"
              >
                {{ nghiDinh.code }}
              </a>
            </li>
          </ul>
          <div class="tab-content">
            <!-- Lặp qua để tạo nội dung cho từng tab -->
            <div
              v-for="(nghiDinh, index) in nghi_dinhs"
              :key="nghiDinh.id"
              :id="'tab_' + encodeId(nghiDinh.code)"
              :class="['tab-pane', { active: index === 0 }]"
            >
              <!-- Nội dung của từng tab -->
              <!-- <p>{{ nghiDinh.name }} - Nội dung của Nghị định {{ nghiDinh.code }}</p> -->
              <hanhvivipham-component :nghi_dinh_id = "nghiDinh.id" :nghi_dinh_code = "encodeId(nghiDinh.code)"></hanhvivipham-component>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import NghiDinh45 from './nghiDinh45.vue';
import NghiDinh155 from './nghiDInh155.vue'
import CoSoPhapLy from './IndexPage.vue'
import * as env from "../../env";

export default {
  components: {
    NghiDinh45,
    NghiDinh155,
    CoSoPhapLy
  },
  props: ["usersystem"],
  data: () => ({
    nghi_dinhs: []
  }),

  methods: {
    loadDataTabNghiDinh() {
      axios
        .get(env.endpointhttp + "nghidinhs")
        .then(response => {
          this.nghi_dinhs = response.data;
        });
    },
    encodeId(value) {
      // Hàm này thay thế các ký tự không hợp lệ bằng ký tự an toàn
      return value.replace(/[^\w\s\u00C0-\u017F]/gi, "_"); // Thay thế các ký tự đặc biệt bằng dấu gạch ngang
    },
  },
  mounted: function () {
    this.loadDataTabNghiDinh()
  }

};
</script>

<style scoped>

</style>
