<template>
  <div class="form-group ta-icheck" :title="text">
    <input
      type="checkbox"
      :id="id"
      class="form-control vue-checkbox"
      :disabled="disabled || readonly"
    />
    <label :for="id" v-if="text" class="control-label"> {{ text }}</label>
  </div>
</template>

<style scoped>
.ta-icheck {
  position: relative;
  display: block;
  margin-top: 10px;
  margin-bottom: 10px;
}
</style>

<script>
export default {
  props: {
    id: { type: [String, Number], required: true },
    text: { type: String },
    value: { type: Boolean, default: false },
    disabled: Boolean,
    readonly: Boolean,
  },
  watch: {
    value: {
      handler: function (value) {
        if (value) $("#" + this.id).iCheck("check");
        else $("#" + this.id).iCheck("uncheck");
        this.$emit("change", this.value);
      },
      immediate: true,
    },
  },
  mounted: function () {
    var controller = this;
    let check = $("#" + controller.id).iCheck({
      checkboxClass: "icheckbox_square-green",
      radioClass: "iradio_square-green",
      increaseArea: "20%",
    });
    check.trigger("ifChanged").on("ifChanged", function (event) {
      if (!controller.disabled && !controller.readonly) {
        controller.$emit("change", [
          $("#" + controller.id).iCheck("update")[0].checked,
          controller.id,
        ]);
        controller.$emit(
          "input",
          $("#" + controller.id).iCheck("update")[0].checked
        );
      }
    });

    if (this.value) $("#" + this.id).iCheck("check");
    else $("#" + this.id).iCheck("uncheck");
  },
};
</script>
