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
        center ? 'padding-bottom: 150px;' : ''
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
            :disabled="disabled"
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
          <template v-if="buttons && buttons.length">
            <div class="pull-left" style="display: flex; gap: 8px">
              <button
                v-for="(btn, idx) in leftButtons"
                :key="btn.key || btn.text || idx"
                :type="btn.type || 'button'"
                :class="btn.class || 'btn btn-default btn-flat'"
                @click="onBtnClick(btn)"
                :disabled="disabled"
              >
                <i v-if="btn.icon" :class="btn.icon"></i> {{ btn.text }}
              </button>
            </div>
            <div class="pull-right" style="display: flex; gap: 8px">
              <button
                v-for="(btn, idx) in rightButtons"
                :key="btn.key || btn.text || idx"
                :type="btn.type || 'button'"
                :class="btn.class || 'btn btn-primary btn-flat'"
                @click="onBtnClick(btn)"
                :disabled="disabled"
              >
                <i v-if="btn.icon" :class="btn.icon"></i> {{ btn.text }}
              </button>
            </div>
          </template>
          <template v-else>
            <slot name="button-left">
              <button
                type="button"
                class="btn btn-default pull-left btn-flat"
                data-dismiss="modal"
                @click="close()"
                :disabled="disabled"
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
                :disabled="disabled"
              >
                <i class="fa fa-check"></i> Đồng ý
              </button>
            </slot>
          </template>
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
    buttons: {
      type: Array,
      default: () => [],
    },
    disabled: {
      type: Boolean,
      default: false,
    },
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
    leftButtons() {
      return (this.buttons || []).filter((b) => b.align === "left");
    },
    rightButtons() {
      return (this.buttons || []).filter((b) => b.align !== "left");
    },
  },
  methods: {
    close() {
      if (this.disabled) return;
      this.dialog = false;
      this.$emit("close");
    },
    submit() {
      this.$emit("submit");
    },
    onBtnClick(btn) {
      this.$emit(btn.event || "button", btn.key || null, btn);
      if (btn.closeOnClick) this.close();
    },
  },
};
</script>
<style lang="scss" scoped>
.modal-body {
  max-height: 400px;
  overflow: auto;
}
.modal-footer::after {
  content: "";
  display: table;
  clear: both;
}
</style>
