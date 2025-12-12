<template>
  <div class="box-search" id="box-search">
    <div class="input-group pull-right" id="adv-search">
      <input
        v-model="form.search"
        type="text"
        class="form-control"
        name="search"
        @keyup.enter="goSearch"
        placeholder="Nhập thông tin tìm kiếm"
      />
      <span class="input-group-btn">
        <div class="btn-group" style="height: 40px">
          <button type="button" class="btn btn-flat bg-olive button-search">
            <span class="caret"></span>
          </button>
          <button class="btn bg-olive pull-right flat" @click="goSearch">
            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
          </button>
        </div>
      </span>
    </div>
    <div class="dropdown dropdown-lg" id="dropdown">
      <div class="dropdown-menu dropdown-menu-right" style="margin-top: 40px">
        <div class="box-body">
          <div class="row mt-4">
            <div class="col-md-3">
              <label for="input-masoDKKD">Mã số ĐKKD/ Mã số thuế</label>
              <input
                v-model="form.search_masothue"
                type="text"
                class="form-control"
                id="input-masoDKKD"
                placeholder="Mã số ĐKKD/ Mã số thuế"
                style="height: 36px"
                name="search_masothue"
                @keyup.enter="goSearch"
              />
            </div>
            <div class="col-md-3">
              <label for="input-year">Năm ban hành QĐ</label>
              <input
                v-model="form.search_year_QD"
                type="number"
                class="form-control"
                id="input-year"
                placeholder="Năm"
                style="height: 36px"
                name="search_year_QD"
                min="1970"
                max="3000"
                @change="changeYearQD()"
                @keyup.enter="goSearch"
              />
            </div>
            <div class="col-md-3">
              <label for="input-year">Ban hành QĐ từ ngày</label>
              <date-picker
                class="datetime--input"
                v-model="form.tu_ngay_QD"
                placeholder="Chọn ngày"
                tabindex="17"
                :config="optionsDate"
              ></date-picker>
            </div>
            <div class="col-md-3">
              <label for="input-year">đến ngày</label>
              <date-picker
                class="datetime--input"
                v-model="form.den_ngay_QD"
                placeholder="Chọn ngày"
                tabindex="17"
                :config="optionsDate"
              ></date-picker>
            </div>
            <div class="col-md-3">
              <label for="input-year">Năm kết luận thanh tra</label>
              <input
                v-model="form.search_year"
                type="number"
                class="form-control"
                id="input-year"
                placeholder="Năm"
                style="height: 36px"
                name="search_year"
                min="1970"
                max="3000"
                @change="changeYear()"
                @keyup.enter="goSearch"
              />
            </div>
            <div class="col-md-3">
              <label for="input-year">Kết luận thanh tra từ ngày</label>
              <date-picker
                class="datetime--input"
                v-model="form.tu_ngay"
                placeholder="Chọn ngày"
                tabindex="17"
                :config="optionsDate"
              ></date-picker>
            </div>
            <div class="col-md-3">
              <label for="input-year">đến ngày</label>
              <date-picker
                class="datetime--input"
                v-model="form.den_ngay"
                placeholder="Chọn ngày"
                tabindex="17"
                :config="optionsDate"
              ></date-picker>
            </div>
          </div>
          <div class="row">
            <input
              v-model="form.order_column"
              type="hidden"
              v-if="form.order_column"
              id="order_column"
              name="order_column"
              @keyup.enter="goSearch"
            />
            <input
              type="hidden"
              v-model="form.order_direction"
              v-if="form.order_direction"
              id="order_direction"
              name="order_direction"
              @keyup.enter="goSearch"
            />
          </div>
          <div class="row">
            <div class="col-md-4">
              <label for="input-cososanxuat">Tên cơ sở sản xuất</label>
              <input
                v-model="form.search_tencososanxuat"
                type="text"
                class="form-control"
                id="input-cososanxuat"
                placeholder="Tên cơ sở sản xuất"
                style="height: 36px"
                name="search_tencososanxuat"
                @keyup.enter="goSearch"
              />
            </div>
            <div class="col-md-4">
              <label>Tỉnh thành</label>
              <multiselect
                v-model="form.search_tinh_thanh"
                :options="tinhthanhs"
                placeholder="Chọn"
                selectLabel="Nhấn enter để chọn"
                deselectLabel="Nhấn enter để bỏ chọn"
                selectedLabel="Đã chọn"
                :multiple="true"
                :close-on-select="false"
                :clear-on-select="false"
                :preserve-search="true"
                :tabindex="6"
                label="ten"
                track-by="id"
                :maxHeight="300"
                :taggable="false"
                :group-select="false"
                :limit="5"
                :limitText="(count) => `và ${count} tỉnh thành nữa`"
              ></multiselect>
            </div>
            <div class="col-md-4">
              <label>Loại hình hoạt động</label>
              <multiselect
                :options="loaiHinhHoatDong"
                v-model="form.search_loai_co_so"
                placeholder="Chọn"
                selectLabel="Nhấn enter để chọn"
                deselectLabel="Nhấn enter để bỏ chọn"
                selectedLabel="Đã chọn"
                :close-on-select="false"
                :clear-on-select="false"
                :multiple="true"
                :tabindex="6"
                label="ten"
                track-by="id"
                :group-select="false"
                :limit="2"
                :maxHeight="300"
                :limitText="(count) => `và ${count} loại hình cơ sở nữa`"
              ></multiselect>
            </div>
            <div class="col-md-4">
              <checkbox-component
                v-model="form.search_co_so_da_thay_doi_thong_tin"
                type="checkbox"
                :id="'search_co_so_da_thay_doi_thong_tin'"
                text="Cơ sở đã thay đổi thông tin"
              >
              </checkbox-component>
            </div>
          </div>
        </div>
        <div class="box-footer">
          <a class="btn btn-flat btn-default" @click="onResetFilter">
            <i class="fa fa-history"></i> Làm mới
          </a>
          <button class="btn btn-flat bg-olive pull-right" @click="goSearch">
            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
            Tìm kiếm
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Multiselect from "vue-multiselect";
import datePicker from "vue-bootstrap-datetimepicker";

export default {
  props: ["tinhthanhs", "loaicosos", "value"],
  components: {
    Multiselect,
    datePicker
  },
  data: () => ({
    form: {
      tu_ngay: null,
      den_ngay: null,
      search_tinh_thanh: [],
      tu_ngay_QD: null,
      den_ngay_QD: null,
    },
    loaiHinhHoatDong: [],
    optionsDate: {
      format: "DD/MM/YYYY",
      useCurrent: false,
      locale: "vi"
    }
  }),
  mounted(){
    this.loaiHinhHoatDong = this.loaicosos.filter(el => el.parent_id && el.van_ban_phap_luat == '04/2012/TT-BTNMT')
  },
  watch: {
    form: {
      handler(value) {
        let data = { ...value };
        console.log('Dữ liệu tìm kiếm:', data);
        if (data.search_tinh_thanh && data.search_tinh_thanh.length > 0) {
          data.search_tinh_thanh = data.search_tinh_thanh.map(x => x.id);
        }
        if (data.search_loai_co_so && data.search_loai_co_so.length > 0) {
          data.search_loai_co_so.map(x => x.id);
        }
        this.$emit("input", data);
      },
      deep: true
    }
  },
  methods: {
    onResetFilter() {
      this.form = {
        tu_ngay: null,
        den_ngay: null,
        search_tinh_thanh: [],
        tu_ngay_QD: null,
        den_ngay_QD: null,
      };
      this.goSearch();
    },
    goSearch() {
      let data = { ...this.form };
      if (data.search_tinh_thanh && data.search_tinh_thanh.length > 0) {
        data.search_tinh_thanh = data.search_tinh_thanh.map(x => x.id);
      }
      if (data.search_loai_co_so && data.search_loai_co_so.length > 0) {
        data.search_loai_co_so = data.search_loai_co_so.map(x => x.id);
      }
      this.$emit("search", data);
    },
    changeYear() {
      this.form.tu_ngay = "1/1/" + this.form.search_year;
      this.form.den_ngay = "31/12/" + this.form.search_year;
    },
    changeYearQD() {
      this.form.tu_ngay_QD = "1/1/" + this.form.search_year_QD;
      this.form.den_ngay_QD = "31/12/" + this.form.search_year_QD;
    }
  }
};
</script>
<style scoped>
/deep/ .multiselect__tag {
  margin-top: -4px;
}
/* /deep/ .multiselect__tags {
  min-height: 37px;
  height: 37px;
} */
/deep/ .multiselect__placeholder {
  padding-top: 0px;
}
/deep/ .datetime--input {
  height: 36px;
}
</style>
