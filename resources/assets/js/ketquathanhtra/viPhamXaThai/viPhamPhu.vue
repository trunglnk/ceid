<template>
  <div>
    <div class="row block-multiple-rows" v-if="!disabled">
      <div class="col-md-12">
        <div
          class="form-group"
          :class="{ 'has-error': error.error_co_so_san_xuat }"
        >
          <label class="control-label">Địa điểm hoạt động</label>
          <Multiselect
            v-model="form.co_so_san_xuat"
            label="ten"
            track-by="id"
            placeholder="Gõ tên"
            selectLabel="Nhấn enter để chọn"
            deselectLabel="Nhấn enter để bỏ chọn"
            selectedLabel="Đã chọn"
            open-direction="bottom"
            :options="currentCoSoSanXuats"
            :multiple="false"
            :searchable="true"
            :loading="is_loading"
            :internal-search="true"
            :clear-on-select="false"
            :close-on-select="true"
            :options-limit="300"
            :limit="3"
            :max-height="600"
            :show-no-results="false"
            :hide-selected="false"
            :tabindex="1"
            :clearOnSelect="true"
          >
            <span slot="noResult">Không tìm thấy kết quả</span>
          </Multiselect>
          <span class="help-block">{{ error.error_co_so_san_xuat }}</span>
        </div>
        <label class="control-label">Địa điểm lấy mẫu</label>
        <input
          v-model="form.dia_diem_lay_mau"
          type="text"
          class="form-control"
        />
        <div
          class="form-group"
          :class="{ 'has-error': error.error_khoan_vi_pham }"
        >
          <label class="control-label">Hành vi vi phạm</label>
          <Multiselect
            :placeholder="'Hành vi vi phạm'"
            :options="khoans"
            label="mo_ta"
            track-by="id"
            v-model="form.khoan"
            :searchable="true"
            :clear-on-select="false"
            :close-on-select="true"
            :options-limit="300"
            :limit="3"
            :show-no-results="false"
            :hide-selected="false"
            :tabindex="1"
            selectLabel="Nhấn enter để chọn"
            deselectLabel="Nhấn enter để bỏ chọn"
            selectedLabel="Đã chọn"
          >
            <span slot="noResult">Không tìm thấy kết quả</span>
          </Multiselect>
          <span class="help-block">{{ error.error_khoan_vi_pham }}</span>
        </div>

        <div
          class="form-group"
          :class="{ 'has-error': error.error_muc_vi_pham }"
        >
          <label class="control-label">{{ textLuuLuong }}</label>

          <div v-if="!form.khoan" class="form-control">Chọn khoản trước</div>
          <Multiselect
            v-else-if="mucs.length > 0"
            :placeholder="textLuuLuong"
            :options="mucs"
            label="mo_ta"
            track-by="id"
            v-model="form.muc"
            :searchable="true"
            :clear-on-select="false"
            :close-on-select="true"
            :options-limit="300"
            :limit="3"
            :show-no-results="false"
            :hide-selected="false"
            selectLabel="Nhấn enter để chọn"
            deselectLabel="Nhấn enter để bỏ chọn"
            selectedLabel="Đã chọn"
            :tabindex="1"
            :disabled="!form.khoan"
          >
            <span slot="noResult">Không tìm thấy kết quả</span>
          </Multiselect>
          <div v-else class="form-control">Khoản không có mục</div>
          <span class="help-block">{{ error.error_muc_vi_pham }}</span>
        </div>
        <div
          class="form-group"
          :class="{ 'has-error': error.error_thong_so_vuot_chuan }"
        >
          <label class="control-label">Thông số vượt chuẩn</label>
          <Multiselect
            v-model="form.thong_so"
            label="ten"
            track-by="id"
            placeholder="Gõ tên"
            selectLabel="Nhấn enter để chọn"
            deselectLabel="Nhấn enter để bỏ chọn"
            selectedLabel="Đã chọn"
            open-direction="bottom"
            :options="danhMucThongSoVuotChuans"
            :multiple="false"
            :searchable="true"
            :loading="is_loading"
            :internal-search="true"
            :clear-on-select="false"
            :close-on-select="true"
            :options-limit="300"
            :limit="3"
            :max-height="600"
            :show-no-results="false"
            :hide-selected="false"
            :tabindex="1"
            :clearOnSelect="true"
          >
            <span slot="noResult">Không tìm thấy kết quả</span>
          </Multiselect>
          <span class="help-block">{{ error.error_thong_so_vuot_chuan }}</span>
        </div>
        <label class="control-label">Kết quả</label>
        <input
          v-model="form.ket_qua"
          type="number"
          step="any"
          class="form-control"
        />

        <label class="control-label">Chọn đơn vị</label>
        <Multiselect
          v-model="form.don_vi"
          label="ten"
          track-by="id"
          placeholder="Gõ tên"
          selectLabel="Nhấn enter để chọn"
          deselectLabel="Nhấn enter để bỏ chọn"
          selectedLabel="Đã chọn"
          open-direction="bottom"
          :options="donviThongSoVuotChuan"
          :multiple="false"
          :searchable="true"
          :loading="is_loading"
          :internal-search="true"
          :clear-on-select="false"
          :close-on-select="true"
          :options-limit="300"
          :limit="3"
          :max-height="600"
          :show-no-results="false"
          :hide-selected="false"
          :tabindex="1"
          :clearOnSelect="true"
        >
          <span slot="noResult">Không tìm thấy kết quả</span>
        </Multiselect>
        <label class="control-label">Số lần vượt</label>
        <input
          v-model="form.so_lan_vuot"
          type="number"
          step="any"
          class="form-control"
        />

        <div class="nav-tabs-custom margin-top">
          <ul class="nav nav-tabs">
            <li
              v-for="(vi_pham_phu, index) in form.chi_tiet_phat_tang_thems"
              :key="index"
              :class="{ active: currentTab == index }"
              @click="currentTab = index"
              style="cursor: pointer"
            >
              <a data-toggle="tab" aria-expanded="true" class="d-flex"
                >{{ `# ${parseInt(index) + 1}` }}
                <div
                  @click.stop="deleteViPhamPhu(index)"
                  style="cursor: pointer; padding-left: 10px"
                >
                  <i class="fa fa-times"></i>
                </div>
              </a>
            </li>
            <li @click="addViPhamPhu">
              <button class="btn">
                <i class="fa fa-plus"></i>
                Thêm mới xử phạt bổ sung
              </button>
            </li>
          </ul>
          <div
            v-for="(vi_pham_phu, index) in form.chi_tiet_phat_tang_thems"
            :key="index"
            v-show="currentTab == index"
            class="tab-content"
          >
            <div class="row block-multiple-rows">
              <div class="col-md-6">
                <div
                  class="form-group"
                  :class="{
                    'has-error':
                      error.chi_tiet_phat_tang_thems[index] &&
                      error.chi_tiet_phat_tang_thems[index].error_thong_so,
                  }"
                >
                  <label class="control-label">Thông số vượt chuẩn</label>
                  <Multiselect
                    v-model="form.chi_tiet_phat_tang_thems[index].thong_so"
                    label="ten"
                    track-by="id"
                    placeholder="Gõ tên"
                    selectLabel="Nhấn enter để chọn"
                    deselectLabel="Nhấn enter để bỏ chọn"
                    selectedLabel="Đã chọn"
                    open-direction="bottom"
                    :options="danhMucThongSoVuotChuans"
                    :multiple="false"
                    :searchable="true"
                    :loading="is_loading"
                    :internal-search="true"
                    :clear-on-select="false"
                    :close-on-select="true"
                    :options-limit="300"
                    :limit="3"
                    :max-height="600"
                    :show-no-results="false"
                    :hide-selected="false"
                    :tabindex="1"
                    :clearOnSelect="true"
                    @input="change"
                  >
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </Multiselect>
                  <span
                    class="help-block"
                    v-if="error.chi_tiet_phat_tang_thems[index]"
                    >{{
                      error.chi_tiet_phat_tang_thems[index].error_thong_so
                    }}</span
                  >
                </div>
              </div>

              <div class="col-md-6">
                <div
                  class="form-group"
                  :class="{
                    'has-error':
                      error.chi_tiet_phat_tang_thems[index] &&
                      error.chi_tiet_phat_tang_thems[index]
                        .error_phat_tang_them,
                  }"
                >
                  <!-- Phạt tăng thêm -->
                  <label class="control-label">Phạt tăng thêm</label>
                  <Multiselect
                    v-model="
                      form.chi_tiet_phat_tang_thems[index].phat_tang_them
                    "
                    label="name"
                    track-by="id"
                    placeholder="Gõ tên"
                    selectLabel="Nhấn enter để chọn"
                    deselectLabel="Nhấn enter để bỏ chọn"
                    selectedLabel="Đã chọn"
                    open-direction="bottom"
                    :options="optionPhatTangThems"
                    :multiple="false"
                    :searchable="true"
                    :loading="is_loading"
                    :internal-search="true"
                    :clear-on-select="false"
                    :close-on-select="true"
                    :options-limit="300"
                    :limit="3"
                    :max-height="600"
                    :show-no-results="false"
                    :hide-selected="false"
                    :tabindex="1"
                    :clearOnSelect="true"
                  >
                    <span slot="noResult">Không tìm thấy kết quả</span>
                  </Multiselect>
                  <span
                    class="help-block"
                    v-if="error.chi_tiet_phat_tang_thems[index]"
                    >{{
                      error.chi_tiet_phat_tang_thems[index].error_phat_tang_them
                    }}</span
                  >
                </div>
              </div>
              <div class="col-md-4">
                <label class="control-label">Kết quả</label>
                <input
                  v-model="form.chi_tiet_phat_tang_thems[index].ket_qua"
                  type="number"
                  step="any"
                  min="0"
                  class="form-control"
                />
              </div>
              <!-- chọn đơn vị -->
              <div class="col-md-4">
                <label class="control-label">Chọn đơn vị</label>
                <Multiselect
                  v-model="form.chi_tiet_phat_tang_thems[index].don_vi"
                  label="ten"
                  track-by="id"
                  placeholder="Gõ tên"
                  selectLabel="Nhấn enter để chọn"
                  deselectLabel="Nhấn enter để bỏ chọn"
                  selectedLabel="Đã chọn"
                  open-direction="bottom"
                  :options="donviThongSoVuotChuan"
                  :multiple="false"
                  :searchable="true"
                  :loading="is_loading"
                  :internal-search="true"
                  :clear-on-select="false"
                  :close-on-select="true"
                  :options-limit="300"
                  :limit="3"
                  :max-height="600"
                  :show-no-results="false"
                  :hide-selected="false"
                  :tabindex="1"
                  :clearOnSelect="true"
                >
                  <span slot="noResult">Không tìm thấy kết quả</span>
                </Multiselect>
              </div>
              <div class="col-md-4">
                <!-- Số lần vượt -->
                <label class="control-label">Số lần vượt</label>
                <input
                  v-model="form.chi_tiet_phat_tang_thems[index].so_lan_vuot"
                  type="number"
                  step="1"
                  min="0"
                  class="form-control"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <button class="btn" @click="addViPhamPhuToData">Thêm mới</button>
      </div>
    </div>
    <div class="row block-multiple-rows">
      <div class="col-md-12">
        <table class="table table-hover table-responsive">
          <thead>
            <tr class="row-table-header">
              <th>Địa điểm hoạt động</th>
              <th>Địa điểm lấy mẫu</th>
              <th>Hành vi vi phạm</th>
              <th>{{ textLuuLuong }}</th>
              <th class="text-center">Thông số vượt chuẩn</th>
              <th class="text-center">Kết quả</th>
              <th class="text-center">Đơn vị</th>
              <th class="text-center">Số lần vượt</th>
              <th>Phạt tăng thêm</th>
              <th style="width: 5%; text-align: center" v-if="!disabled">
                Xóa
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(item, index) in intermediate_data"
              :key="`viphamnnuocthai-${index}`"
            >
              <td
                data-toggle="tooltip"
                class="ellipsis"
                :title="item.co_so_san_xuat ? item.co_so_san_xuat.ten : ''"
              >
                <span>
                  {{ item.co_so_san_xuat ? item.co_so_san_xuat.ten : "" }}
                </span>
              </td>
              <td
                data-toggle="tooltip"
                class="ellipsis"
                :title="item.dia_diem_lay_mau"
              >
                <span>
                  {{ item.dia_diem_lay_mau }}
                </span>
              </td>
              <td
                data-toggle="tooltip"
                class="ellipsis"
                :title="item.khoan ? item.khoan.mo_ta : ''"
              >
                <span>
                  {{ item.khoan ? item.khoan.mo_ta : "" }}
                </span>
              </td>
              <td
                data-toggle="tooltip"
                class="ellipsis"
                :title="item.muc ? item.muc.mo_ta : ''"
              >
                <span>
                  {{ item.muc ? item.muc.mo_ta : "" }}
                </span>
              </td>
              <td
                data-toggle="tooltip"
                class="ellipsis text-center"
                :title="item.thong_so ? item.thong_so.ten : ''"
              >
                <span>
                  {{ item.thong_so ? item.thong_so.ten : "" }}
                </span>
              </td>
              <td
                data-toggle="tooltip"
                class="ellipsis text-center"
                :title="item.ket_qua"
              >
                <span>
                  {{ item.ket_qua }}
                </span>
              </td>
              <td
                data-toggle="tooltip"
                class="ellipsis text-center"
                :title="item.don_vi ? item.don_vi.ten : ''"
              >
                <span>
                  {{ item.don_vi ? item.don_vi.ten : "" }}
                </span>
              </td>
              <td
                data-toggle="tooltip"
                class="ellipsis text-center"
                :title="item.so_lan_vuot"
              >
                <span>
                  {{ item.so_lan_vuot }}
                </span>
              </td>
              <td
                data-toggle="tooltip"
                class="ellipsis"
                :title="item.chi_tiet_phat_tang_thems | showViPhamPhu"
              >
                <span>
                  {{ item.chi_tiet_phat_tang_thems | showViPhamPhu }}
                </span>
              </td>
              <td align="center" v-if="!disabled">
                <a
                  @click="deleteViPhamPhuToData(index)"
                  style="cursor: pointer"
                >
                  <i class="fa fa-trash-o"></i>
                </a>
              </td>
            </tr>
            <tr v-if="form.length == 0">
              <td colspan="6" class="text-center"></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
export default {
  components: { Multiselect },
  props: {
    value: {},
    disabled: Boolean,
    is_loading: Boolean,
    danhMucThongSoVuotChuans: {
      type: Array,
      required: true,
      default: () => [],
    },
    coSoSanXuats: {
      type: Array,
      required: true,
      default: () => [],
    },
    optionPhatTangThems: {
      type: Array,
      required: true,
      default: () => [],
    },
    khoans: {
      type: Array,
      required: true,
      default: () => [],
    },
    donviThongSoVuotChuan: {
      type: Array,
      required: true,
      default: () => [],
    },
    textLuuLuong: {
      type: String,
      default: "Lưu lượng xả",
    },
  },

  data: () => ({
    currentTab: 0,
    form: {
      khoan: undefined,
      muc: undefined,
      thong_so: undefined,
      co_so_san_xuat: undefined,
      chi_tiet_phat_tang_thems: [],
    },
    currentData: [],
    error: { chi_tiet_phat_tang_thems: [] },
  }),
  computed: {
    currentCoSoSanXuats() {
      return this.coSoSanXuats.map((x) => {
        if (x.co_so_san_xuat && x.co_so_san_xuat.id) {
          return x.co_so_san_xuat;
        } else {
          return x;
        }
      });
    },
    mucs() {
      return this.form.khoan ? this.form.khoan.mucs : [];
    },
    intermediate_data: {
      get() {
        return this.value || this.currentData;
      },
      set(value) {
        this.currentData = value;
        this.$emit("input", value);
      },
    },
  },
  methods: {
    checkViPhamPhu() {
      let isValid = true;
      this.error = { chi_tiet_phat_tang_thems: [] };
      if (!this.form.co_so_san_xuat) {
        this.error.error_co_so_san_xuat = "Cần chọn địa điểm vi phạm";
        isValid = false;
      }
      if (!this.form.khoan) {
        this.error.error_khoan_vi_pham = "Cần chọn hành vi vi phạm";
        isValid = false;
      }
      if (this.mucs.length > 0 && !this.form.muc) {
        this.error.error_muc_vi_pham = "Cần chọn " + this.textLuuLuong;
        isValid = false;
      }
      if (!this.form.khoan) {
        this.error.error_thong_so_vuot_chuan = "Cần chọn Thông số vượt chuẩn";
        isValid = false;
      }
      this.error.chi_tiet_phat_tang_thems = this.form.chi_tiet_phat_tang_thems.map(
        (x) => {
          if (isValid) {
            isValid = !!x.thong_so || !!x.phat_tang_them;
          }
          return {
            error_thong_so: !x.thong_so ? "Cần chọn thông số vượt chuẩn" : "",
            error_phat_tang_them: !x.phat_tang_them
              ? "Cần chọn phạt tăng thêm"
              : "",
          };
        }
      );
      return isValid;
    },
    change() {
      this.$emit("input", this.intermediate_data);
      this.$emit("change");
    },
    addViPhamPhu() {
      this.form.chi_tiet_phat_tang_thems.push({
        phat_tang_them: undefined,
        so_lan_vuot: 0,
        don_vi: undefined,
        ket_qua: 0,
        thong_so: undefined,
      });
      this.currentTab = this.form.chi_tiet_phat_tang_thems.length - 1;
    },
    deleteViPhamPhu(index) {
      this.form.chi_tiet_phat_tang_thems.splice(index, 1);
      this.currentTab = 0;
      this.$forceUpdate();
    },
    deleteViPhamPhuToData(index) {
      this.intermediate_data.splice(index, 1);
      this.$forceUpdate();
    },
    addViPhamPhuToData() {
      if (!this.checkViPhamPhu()) {
        return;
      }
      if (!this.intermediate_data) {
        this.intermediate_data = [];
      }
      this.intermediate_data.push(JSON.parse(JSON.stringify(this.form)));
      this.form = {
        khoan: undefined,
        muc: undefined,
        thong_so: undefined,
        co_so_san_xuat: undefined,
        chi_tiet_phat_tang_thems: [],
      };
      this.change();
      this.$forceUpdate();
    },
  },
  filters: {
    showViPhamPhu(value) {
      if (!value || value.length == 0) return "";
      let str = "";
      str = value
        .map(
          (x) =>
            `${
              x.phat_tang_them ? "Phạt tăng thêm " + x.phat_tang_them.name : ""
            },${x.thong_so ? " Thông số vượt chuẩn: " + x.thong_so.ten : ""} ${
              x.ket_qua ? x.ket_qua : ""
            } ${x.don_vi ? x.don_vi.ten : ""} ${
              x.so_lan_vuot ? " vượt " + x.so_lan_vuot + " lần" : ""
            }`
        )
        .join("\n");
      return str;
    },
  },
};
</script>

<style scoped>
.ellipsis {
  position: relative;
}
.ellipsis:before {
  content: "&nbsp;";
  visibility: hidden;
}
.ellipsis span {
  position: absolute;
  left: 0;
  right: 0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>
