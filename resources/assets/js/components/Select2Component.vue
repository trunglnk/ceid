<template>
  <select
    ref="select"
    class="form-control"
    :name="name"
    :id="id"
    :class="[addClass]"
    :tabindex="tabindex"
  >
    <option></option>
  </select>
</template>

<script>
export default {
  name: "Select2Component",
  props: {
    name: String,
    id: String,
    value: Number,
    options: { type: Array, default: [] },
    fieldtext: { type: String, default: "ten" },
    fieldvalue: { type: String, default: "id" },
    addClass: String,
    tabindex: { type: [Number, String] },
    placeholder: { type: String, default: "Chọn" }
  },
  methods: {
    initSelect2: function() {
      setTimeout(() => {
        var vm = this;
        var options = [];

        if (this.options === null || this.options.length == 0) {
          return;
        } else {
          options = this.options.map(function(e) {
            return {
              id: e[vm.fieldvalue],
              text: e[vm.fieldtext]
            };
          });
        }
        if ($(this.$el).data("select2")) {
          $(this.$el)
            .off()
            .select2("destroy");
        }

        $(this.$el)
          .empty()
          .select2({
            placeholder: this.placeholder,
            allowClear: true,
            data: options
          })
          .val(+this.value)
          .trigger("change")
          .on("change", function() {
            vm.$emit("input", +this.value);
          });
      }, 1000);
    }
  },
  mounted() {
    this.initSelect2();
  },
  watch: {
    value: function(value) {
      $(this.$el)
        .val(value)
        .trigger("change");
    },
    options: {
      handler: function(options) {
        this.initSelect2();
      },
      deep: true
    }
  },
  destroyed: function() {
    $(this.$el)
      .off()
      .select2("destroy");
  }
};
</script>
