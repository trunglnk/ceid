<template>
  <div
    v-if="dialog"
    v-show="dialog"
    class="modal fade in"
    :style="`text-align:initial;${
      center
        ? 'display:flex;justify-content: center;align-items: center;'
        : 'display:unset!important'
    }`"
  >
    <div
      class="modal-dialog"
      :style="`width:${width}${
        isNaN(width) ? '' : 'px'
      };min-height:${minHeight}${isNaN(minHeight) ? '' : 'px'};${
        center ? 'padding-bottom: 150px;' : null
      }`"
    >
      <div class="modal-content">
        <div class="modal-header">
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
            @click="close()"
          >
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">
            <strong>{{ title }}</strong>
          </h4>
        </div>
        <div class="modal-body">
          <slot></slot>
        </div>
        <div class="modal-footer">
          <slot name="button-left">
            <button
              type="button"
              class="btn btn-default pull-left btn-flat"
              data-dismiss="modal"
              @click="close()"
            >
              <i class="fa fa-close"></i> Hủy
            </button>
          </slot>
          <slot name="button-right">
            <button
              type="submit"
              id="onsubmit"
              class="btn btn-flat bg-olive"
              @click="submit()"
            >
              <i class="fa fa-check"></i> Đồng ý
            </button>
          </slot>
        </div>
      </div>
    </div>
    <input :value="value" v-show="false" type="radio" />
  </div>
</template>
<script>
export default {
  props: {
    title: String,
    value: Boolean,
    width: [String, Number],
    minHeight: [String, Number],
    center: Boolean,
  },
  computed: {
    dialog: {
      set(value) {
        this.$emit("input", value);
      },
      get() {
        return this.value;
      },
    },
  },
  methods: {
    close() {
      this.dialog = false;
      this.$emit("close");
    },
    submit() {
      this.$emit("submit");
    },
  },
};
</script>
<style lang="scss" scoped>
.modal-body {
  max-height: 400px;
  overflow: auto;
}
</style>
>
