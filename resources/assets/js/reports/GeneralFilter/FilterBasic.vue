<template>
  <div>
    <div class="col-md-12">
      <label class="control-label">Tên cơ sở</label>
      <input v-model="search_ten" @change="ChangeCoso" placeholder="Nhập tên cơ sở" type="text" class="form-control" />
    </div>
    <!-- date time filter -->
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-4">
          <label class="control-label">Chọn năm</label>
          <input v-model="search_year" @change="changeYear()" name="search_year" placeholder="Nhập năm" type="number"
            class="form-control" id="search_year" />
        </div>
        <!-- <div class="col-md-12 col-lg-5">
          <button
            class="btn btn-flat btn-block btn-success"
            style="height: 40px;"
            @click="changeDate()"
          >
            <span>Tra cứu</span>
          </button>
        </div>-->
        <div class="col-md-4">
          <label class="control-label">Từ ngày</label>
          <date-picker v-model="tu_ngay" placeholder="Chọn ngày" tabindex="17" :config="optionsDate"
            @input="changeDate()"></date-picker>
        </div>
        <div class="col-md-4">
          <label class="control-label">Đến ngày</label>
          <date-picker v-model="den_ngay" placeholder="Chọn ngày" tabindex="17" :config="optionsDate"
            @input="changeDate()"></date-picker>
        </div>
      </div>
      <!-- <div class="my-2">
        <div class="row">
          <div class="col-md-6">
            <label class="control-label">Từ ngày</label>
            <date-picker
              v-model="tu_ngay"
              placeholder="Chọn ngày"
              tabindex="17"
              :config="optionsDate"
              @input="changeDate()"
            ></date-picker>
          </div>
          <div class="col-md-6">
            <label class="control-label">Đến ngày</label>
            <date-picker
              v-model="den_ngay"
              placeholder="Chọn ngày"
              tabindex="17"
              :config="optionsDate"
              @input="changeDate()"
            ></date-picker>
          </div>
        </div>
      </div>-->
    </div>
    <!--end date time filter -->
    <div class="col-md-12">
      <div class="row d-flex">
        <div class="col-md-12 col-lg-4">
          <label class="control-label">Miền</label>
          <multiselect v-model="mien.value" placeholder="Chọn miền" label="ten" track-by="id" :options="mien.options" open-direction="top"
            :multiple="true" selectLabel="Nhấn enter để chọn" deselectLabel="Nhấn enter để bỏ chọn"
            selectedLabel="Đã chọn" :loading="mien.isLoading" :tabindex="1" @input="getProvince"></multiselect>
        </div>
        <div class="col-md-12 col-lg-4">
          <label class="control-label">Tỉnh/Thành phố</label>
          <multiselect v-model="tinh.value" placeholder="Chọn tỉnh, thành phố" label="ten" track-by="id" open-direction="top"
            :options="tinh.options" :multiple="true" selectLabel="Nhấn enter để chọn"
            deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn" :loading="tinh.isLoading" :tabindex="2"
            @input="changeTinh"></multiselect>
        </div>
        <div class="col-md-12 col-lg-4">
          <label class="control-label">Lưu vực sông</label>
          <multiselect v-model="luuvucsong.value" placeholder="Chọn lưu vực sông" label="ten" track-by="id" open-direction="above"
            :options="luuvucsong.options" :multiple="true" selectLabel="Nhấn enter để chọn"
            deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn" :loading="luuvucsong.isLoading" :tabindex="3"
            @input="ChangLuuvucsong"></multiselect>
        </div>
        <div class="col-md-12 col-lg-4">
          <label class="control-label">Vùng kinh tế trọng điểm</label>
          <multiselect v-model="vungkinhtes.value" placeholder="Chọn vùng kinh tế" label="ten" track-by="id" open-direction="top"
            :options="vungkinhtes.options" :multiple="true" selectLabel="Nhấn enter để chọn"
            deselectLabel="Nhấn enter để bỏ chọn" selectedLabel="Đã chọn" :loading="vungkinhtes.isLoading" :tabindex="4"
            @input="ChangeVungkinhtes"></multiselect>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";
import * as env from "../../env.js";
import Multiselect from "vue-multiselect";
import datePicker from "vue-bootstrap-datetimepicker";

export default {
  components: {
    Multiselect,
    datePicker
  },
  data: () => ({
    optionsDate: {
      format: "DD/MM/YYYY",
      useCurrent: false,
      locale: "vi"
    },
    //mien
    search_ten: "",
    mien: {
      value: [],
      options: [],
      data: [],
      isLoading: true
    },
    tinh: {
      value: [],
      options: [],
      data: [],
      isLoading: true
    },
    luuvucsong: {
      value: [],
      options: [],
      data: [],
      isLoading: true
    },
    vungkinhtes: {
      value: [],
      options: [],
      data: [],
      isLoading: true
    },
    //date
    search_year: null,
    tu_ngay: null,
    den_ngay: moment()
      .endOf("year")
      .format("DD/MM/YYYY")
  }),
  watch: {},

  mounted() {
    // mien
    if (localStorage.getItem("miens")) {
      this.mien.isLoading = false;
      this.mien.options = JSON.parse(localStorage.getItem("miens"));
      this.mien.data = this.mien.options;
    } else {
      this.mien.isLoading = true;
      axios
        .get(env.endpointhttp + "miens")
        .then(response => {
          this.mien.options = response.data.result;
          this.mien.data = this.mien.options;
          localStorage.setItem("miens", JSON.stringify(this.mien.options));
        })
        .catch(error => { })
        .finally(() => (this.mien.isLoading = false));
    }
    // tinh thanh
    if (localStorage.getItem("tinhthanhs")) {
      this.tinh.isLoading = false;
      this.tinh.options = JSON.parse(localStorage.getItem("tinhthanhs"));
      this.tinh.data = this.tinh.options;
    } else {
      this.tinh.isLoading = true;
      axios
        .get(env.endpointhttp + "tinhthanhs")
        .then(response => {
          this.tinh.options = response.data.result;
          this.tinh.data = this.tinh.options;
          localStorage.setItem("tinhthanhs", JSON.stringify(this.tinh.options));
        })
        .catch(error => { })
        .finally(() => (this.tinh.isLoading = false));
    }
    // luu vuc song

    this.luuvucsong.isLoading = true;
    axios
      .get(env.endpointhttp + "luuvucsongs")
      .then(response => {
        this.luuvucsong.options = response.data.result;
        localStorage.setItem(
          "luuvucsongs",
          JSON.stringify(this.luuvucsong.options)
        );
      })
      .catch(error => { })
      .finally(() => (this.luuvucsong.isLoading = false));


    // vung kinh te trong diem
    if (localStorage.getItem("vungkinhtetrongdiems")) {
      this.vungkinhtes.isLoading = false;
      this.vungkinhtes.options = JSON.parse(
        localStorage.getItem("vungkinhtetrongdiems")
      );
    } else {
      this.vungkinhtes.isLoading = true;
      axios
        .get(env.endpointhttp + "vungkinhtetrongdiems")
        .then(response => {
          this.vungkinhtes.options = response.data.result;
          localStorage.setItem(
            "vungkinhtetrongdiems",
            JSON.stringify(this.vungkinhtes.options)
          );
        })
        .catch(error => { })
        .finally(() => (this.vungkinhtes.isLoading = false));
    }

    this.changeDate();
  },
  methods: {
    changeYear() {
      this.tu_ngay = "1/1/" + this.search_year;
      this.den_ngay = "31/12/" + this.search_year;
      this.changeData({ search_year: this.search_year });
    },
    changeDate() {
      this.changeData({ tu_ngay: this.tu_ngay, den_ngay: this.den_ngay });
    },
    getProvince() {
      if (this.mien.value.length > 0) {
        this.tinh.value = [];
        const vm = this;
        this.tinh.options = this.tinh.data.filter(function (
          currentValue,
          index,
          arr
        ) {
          return (
            this.findIndex(mien => {
              return currentValue.mien_id == mien.id;
            }) >= 0
          );
        },
          this.mien.value);
        vm.changeData({ mien: this.mien });
      } else {
        this.tinh.options = this.tinh.data;
      }
    },
    changeTinh(val) {
      let data = { tinh: this.tinh };
      this.changeData(data);
    },
    ChangLuuvucsong(val) {
      let data = { luuvucsong: this.luuvucsong };
      this.changeData(data);
    },
    ChangeVungkinhtes(val) {
      let data = { vungkinhtes: this.vungkinhtes };
      this.changeData(data);
    },
    changeData(val) {
      this.$emit("change-data", val);
    },
    ChangeCoso() {
      this.changeData({ search_ten: this.search_ten });
    }
  }
};
</script>
<style scoped>
.d-flex {
  flex-wrap: wrap;
  padding-bottom: 12px;
}

.control-label {
  font-weight: 400;
}
</style>
