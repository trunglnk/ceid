<template>
  <div class="row">
    <div class="col-lg-6 col-md-6">
      <multiselect
        v-model="quyet_dinh_thanh_tra"
        label="so_quyet_dinh"
        track-by="id"
        placeholder="Gõ tên quyết định thanh tra"
        selectLabel="Nhấn enter để chọn"
        deselectLabel="Nhấn enter để bỏ chọn"
        selectedLabel="Đã chọn"
        open-direction="bottom"
        :options="this.quyet_dinh_thanh_tras"
        :multiple="true"
        :searchable="true"
        :loading="is_loading"
        :internal-search="false"
        :clear-on-select="false"
        :close-on-select="true"
        :options-limit="300"
        :limit="3"
        :limit-text="limitTextQuyetDinhThanhTra"
        :max-height="600"
        :show-no-results="false"
        :hide-selected="false"
        :tabindex="1"
        :clearOnSelect="true"
        id="search_quyet_dinh_thanh_tra"
        @search-change="asyncFindQuyetDinhThanhTra"
      >
        <span slot="noResult">Không tìm thấy kết quả</span>
      </multiselect>
      <input
        type="hidden"
        v-for="item in this.quyet_dinh_thanh_tra"
        name="search_quyet_dinh_thanh_tra[]"
        :value="item.id"
      />
    </div>
    <div class="col-lg-6 col-md-6">
      <multiselect
        v-model="co_so_thanh_tra"
        label="ten"
        track-by="id"
        placeholder="Gõ tên cơ sở thanh tra"
        selectLabel="Nhấn enter để chọn"
        deselectLabel="Nhấn enter để bỏ chọn"
        selectedLabel="Đã chọn"
        open-direction="bottom"
        :options="this.co_so_thanh_tras"
        :multiple="true"
        :searchable="true"
        :loading="is_loading"
        :internal-search="false"
        :clear-on-select="false"
        :close-on-select="true"
        :options-limit="300"
        :limit="3"
        :limit-text="limitTextCoSoThanhTra"
        :max-height="600"
        :show-no-results="false"
        :hide-selected="false"
        :tabindex="1"
        :clearOnSelect="true"
        @search-change="asyncFindCoSoThanhTra"
        id="search_to_chuc"
      >
        <span slot="noResult">Không tìm thấy kết quả</span>
      </multiselect>
      <input
        type="hidden"
        v-for="item in co_so_thanh_tra"
        name="search_to_chuc[]"
        :value="item.id"
      />
    </div>
  </div>
</template>

<script>
import * as env from "../env.js";
import Multiselect from "vue-multiselect";

export default {
  components: {
    Multiselect
  },
  props: ["search_quyet_dinh_thanh_tra", "search_to_chuc"],
  data: function() {
    return {
      quyet_dinh_thanh_tra: [],
      quyet_dinh_thanh_tras: [],
      co_so_thanh_tra: [],
      co_so_thanh_tras: [],
      is_loading: false
    };
  },
  methods: {
    asyncFindQuyetDinhThanhTra(query) {
      if (query) {
        this.is_loading = true;
        axios
          .get(env.endpointhttp + "quyetdinhthanhtras?search=" + query)
          .then(response => {
            this.quyet_dinh_thanh_tras = response.data.result;
          })
          .catch(error => {})
          .finally(() => (this.is_loading = false));
      }
    },
    limitTextQuyetDinhThanhTra(count) {
      return `và ${count} quyết định thanh tra khác`;
    },

    asyncFindCoSoThanhTra(query) {
      if (query) {
        this.is_loading = true;
        axios
          .get(env.endpointhttp + "asynorganization?search=" + query)
          .then(response => {
            this.co_so_thanh_tras = response.data.result;
          })
          .catch(error => {})
          .finally(() => (this.is_loading = false));
      }
    },
    limitTextCoSoThanhTra(count) {
      return `và ${count} quyết định thanh tra khác`;
    }
  },
  mounted() {
    this.quyet_dinh_thanh_tra = this.search_quyet_dinh_thanh_tra;
    this.co_so_thanh_tra = this.search_to_chuc;
  }
};
</script>
