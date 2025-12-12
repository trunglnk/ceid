<template>
  <div class="dataTables_length" id="perpage">
    <label>
      Hiển thị
      <select
        v-model="perpage"
        name="perpage"
        id="select_perpage"
        aria-controls="perpage"
        class="form-control"
      >
        <option
          v-for="(item, index) in options"
          :value="item"
          :key="`${item}${index}`"
          >{{ item }}</option
        >
      </select>
      mục
    </label>
  </div>
</template>
<script>
//perpage-component
export default {
  props: ["options", "default"],
  data: () => ({
    perpage: 10,
    firstTime: false
  }),
  created() {
    this.perpage = this.default;
  },
  watch: {
    perpage(val) {
      if (this.firstTime) {
        this.$emit("changePerPage", this.perpage);
      }
      this.firstTime = true;
    }
  }
};
</script>
