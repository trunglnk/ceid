<template>
  <select
    class="form-control"
    :disabled="disabled"
    :name="name"
    :id="id"
    v-model="selected"
    :class="[sizeClass]"
    :tabindex="tabindex"
  >
    <option value="null" selected="selected">{{ placeholder }}</option>
    <option
      v-for="item in options"
      :value="item[fieldvalue]"
      :key="item[fieldvalue]"
      >{{ item[fieldtext] }}</option
    >
  </select>
</template>

<script>
export default {
  props: {
    name: String,
    id: String,
    value: [String, Number],
    disabled: { type: Boolean, default: false },
    options: Array,
    fieldtext: { type: String, default: "name" },
    fieldvalue: { type: String, default: "id" },
    sizeClass: String,
    tabindex: Number,
    placeholder: { type: String, default: "Chọn" }
  },
  data: function() {
    return {
      selected: null
    };
  },
  watch: {
    selected: function(value) {
      this.$emit("input", value);
    },
    value: function(value) {
      this.selected = value;
    }
  }
};
</script>
