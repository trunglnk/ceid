<template>
  <div class="timeline-body">
    <div style="display:flex; align-items:center; justify-content:space-between">
      <div v-if="load_thoi_gian">
        <div v-if="this.thoi_gian == ''">Chưa đồng bộ với CSDL quốc gia</div>
        <div v-else>Đồng bộ lần cuối: {{ thoi_gian_dong_bo }}</div>
      </div>
      <div v-else>Đang đồng bộ với CSDL quốc gia</div>
      <div style="margin-right:20px; margin-bottom:10px;">
        <div style="width: 140px;" class="ml-4">
          <div class="btn btn-flat btn-block btn-default d-flex align-center" @click="dongbodulieu()"
            style="height: 40px">
            <i class="fa fa-refresh" style="padding-right:5px"></i>
            Đồng bộ dữ liệu
          </div>
        </div>
      </div>
    </div>
    <table class="table table-hover">
      <tbody>
        <tr class="row-table-header">
          <th>Mã</th>
          <th>Tên</th>
          <th>Miền</th>
          <th>Vùng kinh tế trọng điểm</th>
          <th>Lưu vực sông</th>
        </tr>
        <template v-for="(item, index) in data">
          <tr :key="item.ma">
            <td>{{ item.ma }}</td>
            <td>{{ item.ten }}</td>
            <td>
              <multiselect v-model="item.mien" label="ten" track-by="id" placeholder="Gõ tên miền"
                selectLabel="Nhấn enter để chọn" deselectLabel="Nhấn enter để bỏ chọn" :showLabels="false"
                open-direction="bottom" :options="miens" :searchable="false" :loading="is_loading"
                :internal-search="false" :options-limit="300" :max-height="600" :allowEmpty="false" :tabindex="1"
                required @input="update(item, index)">
                <span slot="noResult">Không tìm thấy kết quả</span>
              </multiselect>
            </td>
            <td>
              <multiselect v-model="item.vung_kinh_te_trong_diem" label="ten" track-by="id"
                placeholder="Chọn vùng kinh tế trọng điểm" selectLabel="Nhấn enter để chọn"
                deselectLabel="Nhấn enter để bỏ chọn" :showLabels="false" open-direction="bottom"
                :options="vungkinhtetrongdiems" :searchable="false" :loading="is_loading" :internal-search="false"
                :options-limit="300" :max-height="600" :allowEmpty="true" :tabindex="1" required
                @input="update(item, index)">
                <span slot="noResult">Không tìm thấy kết quả</span>
              </multiselect>
            </td>
            <td>
              <multiselect v-model="item.luu_vuc_song" label="ten" track-by="id" placeholder="Chọn lưu vực sông"
                selectLabel="Nhấn enter để chọn" deselectLabel="Nhấn enter để bỏ chọn" :showLabels="false"
                open-direction="bottom" :options="luuvucsongs" :searchable="false" :loading="is_loading"
                :internal-search="false" :options-limit="300" :max-height="600" :allowEmpty="true" :tabindex="1"
                required @input="update(item, index)">
                <span slot="noResult">Không tìm thấy kết quả</span>
              </multiselect>
            </td>
          </tr>
          <tr :key="item.ma + 'error'">
            <td colspan="5" v-if="erros[item.ma]">{{ erros[item.ma] }}</td>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
</template>

<script>
import * as env from "../../env.js";
import MessageService from "../../services/MessageService";

import Multiselect from "vue-multiselect";
export default {
  components: { Multiselect },
  data: function () {
    return {
      data: [
      ],
      luuvucsongs: [],
      vungkinhtetrongdiems: [],
      miens: [],
      is_loading: false,
      erros: {},
      thoi_gian_dong_bo: null,
      load_thoi_gian: true,
      thoi_gian: ''
    };
  },
  props: ["usersystem"],
  mounted() {
    this.is_loading = true;
    this.getThoiGianDongBo();
    this.getData();
    // Promise.all([
    //   axios.get(env.endpointhttp + "luuvucsongs"),
    //   axios.get(env.endpointhttp + "vungkinhtetrongdiems"),
    //   axios.get(env.endpointhttp + "tinhthanhs"),
    //   axios.get(env.endpointhttp + "miens")
    // ]).then(datas => {
    //   this.is_loading = false;
    //   this.luuvucsongs = datas[0].data.result;
    //   this.vungkinhtetrongdiems = datas[1].data.result;
    //   this.data = datas[2].data.result;
    //   this.miens = datas[3].data.result;
    // });
  },
  methods: {
    getData(){
      Promise.all([
      axios.get(env.endpointhttp + "luuvucsongs"),
      axios.get(env.endpointhttp + "vungkinhtetrongdiems"),
      axios.get(env.endpointhttp + "tinhthanhs"),
      axios.get(env.endpointhttp + "miens")
    ]).then(datas => {
      this.is_loading = false;
      this.luuvucsongs = datas[0].data.result;
      this.vungkinhtetrongdiems = datas[1].data.result;
      this.data = datas[2].data.result;
      this.miens = datas[3].data.result;
    });
    },
    getThoiGianDongBo() {
      axios
        .get(env.endpointhttp + "inthoigian/tinh_thanh")
        .then((response) => {
          this.thoi_gian = response.data
          this.thoi_gian_dong_bo = new Date(response.data.updated_at).getHours() + ':' + new Date(response.data.updated_at).getMinutes() + ':' + new Date(response.data.updated_at).getSeconds() + ' ' + (new Date(response.data.updated_at)).toLocaleDateString('en-TT')
          console.log(response.data)
          //this.thoi_gian_dong_bo=response.data[0].updated_at
          this.load_thoi_gian = true;
        });
    },
    update(item, index) {
      console.log("update -> item", item);
      if (
        this.usersystem.role_code == "admin" ||
        this.usersystem.role_code == "adminvaquanlydanhmuc"
      ) {
        this.erros[item.ma] = "";
        this.$set(this.erros, item.ma, this.checkData(item));
        if (this.erros[item.ma]) return;
        item.vung_kinh_te_trong_diem_id = item.vung_kinh_te_trong_diem
          ? item.vung_kinh_te_trong_diem.id
          : null;
        item.mien_id = item.mien ? item.mien.id : null;
        item.luu_vuc_song_id = item.luu_vuc_song ? item.luu_vuc_song.id : null;
        axios
          .put(env.endpointhttp + "tinhthanhs/" + item.id, item)
          .then(response => { });
      }
    },
    checkData(data) {
      if (!data.ten || !data.mien || !data.mien.ten)
        return "Lỗi chưa nhập trường bắt buộc";
    },
    dongbodulieu() {
      this.load_thoi_gian = false;
      axios
        .get(env.endpointhttp + 'moitruongquocgia/tinhthanh')
        .then((response) => {
          this.getData();
          this.getThoiGianDongBo();
          MessageService.showSuccessMessage('Đồng bộ thành công')
        })
    },
  }
};
</script>
