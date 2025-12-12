<template>
  <div class="form-group" :id="id">
    <input
      type="radio"
      :disabled="disabled"
      :name="name"
      :id="id + '_active'"
    />
    {{ text_active }} &nbsp;&nbsp;
    <input
      type="radio"
      :disabled="disabled"
      :name="name"
      :id="id + '_inactive'"
    />
    {{ text_inactive }}
  </div>
</template>

<script>
export default {
  props: {
    name: { type: String, required: true },
    id: { type: String, required: true },
    disabled: { type: Boolean, default: false },
    text_active: { type: String, default: "Active" },
    text_inactive: { type: String, default: "Inactive" },
    value: Boolean
  },
  watch: {
    value: function(value) {
      if (value) $("#" + this.id + "_active").iCheck("check");
      else $("#" + this.id + "_inactive").iCheck("check");
    }
  },
  mounted: function() {
    var controller = this;
    $("#" + this.id + "_active")
      .iCheck({
        checkboxClass: "icheckbox_square-green",
        radioClass: "iradio_square-green",
        increaseArea: "20%"
      })
      .trigger("ifChanged")
      .on("ifChanged", function(event) {
        controller.$emit("input", true);
      });
    $("#" + this.id + "_inactive")
      .iCheck({
        checkboxClass: "icheckbox_square-green",
        radioClass: "iradio_square-green",
        increaseArea: "20%"
      })
      .trigger("ifChanged")
      .on("ifChanged", function(event) {
        controller.$emit("input", false);
      });
    if (this.value) $("#" + this.id + "_active").iCheck("check");
    else $("#" + this.id + "_inactive").iCheck("check");
  }
};
</script>
