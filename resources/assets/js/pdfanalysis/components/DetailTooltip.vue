<template>
  <span class="detail-wrap">
    <button
      type="button"
      class="detail-chip"
      :title="title"
      aria-haspopup="dialog"
      :aria-expanded="String(isOpen)"
      @mouseenter="onEnter"
      @mouseleave="onLeave"
      @click.stop="toggle"
    >
      <i class="fa fa-file-text"></i>
    </button>

    <copy-action v-if="isShowCopy" :value="copyValue" />

    <span
      class="detail-popover"
      role="dialog"
      v-show="isOpen"
      :class="{ 'is-up': isPositionedUp }"
      @mouseenter="cancelClose"
      @mouseleave="scheduleClose"
    >
      <div class="detail-popover__inner">
        <div class="detail-popover__row">
          <strong>Độ tin cậy:</strong>
          {{
            meta && meta.confidence != null
              ? (meta.confidence * 100).toFixed(1) + "%"
              : "—"
          }}
        </div>
        <div class="detail-popover__row"><strong>Nguồn:</strong></div>
        <pre class="detail-popover__source">{{ safeSource }}</pre>
      </div>
    </span>
  </span>
</template>

<script>
import CopyAction from "./CopyAction.vue";

export default {
  name: "DetailTooltip",
  components: { CopyAction },
  props: {
    meta: { type: Object, required: true },
    isShowCopy: { type: Boolean, default: true },
    // copyValue: { type: [String, Number], default: "" },
  },
  data() {
    return {
      isOpen: false,
      hoverTimer: null,
      isPositionedUp: false,
    };
  },
  computed: {
    title() {
      console.log("mettttta", this.meta);
      return this.meta && this.meta.confidence != null
        ? `Độ tin cậy: ${(this.meta.confidence * 100).toFixed(1)}%`
        : "Chi tiết";
    },
    safeSource() {
      const s = (this.meta && this.meta.source) || "";
      return s.replace(/</g, "<").replace(/>/g, ">");
    },
    copyValue() {
      return (this.meta && this.meta.rawValue) || "";
    },
  },
  mounted() {
    window.addEventListener("detail-tooltip:close-all", this.close, true);
  },
  beforeDestroy() {
    window.removeEventListener("detail-tooltip:close-all", this.close, true);
    clearTimeout(this.hoverTimer);
  },
  methods: {
    open() {
      if (this.isOpen) return;
      this.isOpen = true;
      this.$nextTick(() => {
        this.updatePosition();
      });
    },
    updatePosition() {
      const button = this.$el.querySelector(".detail-chip");
      const popover = this.$el.querySelector(".detail-popover");
      if (!button || !popover) return;
      const buttonRect = button.getBoundingClientRect();
      const popoverHeight = popover.offsetHeight;
      const spaceBelow = window.innerHeight - buttonRect.bottom;
      // Nếu không gian bên dưới không đủ cho popover, hiển thị nó ở trên
      this.isPositionedUp =
        spaceBelow < popoverHeight && buttonRect.top > popoverHeight;
    },
    close() {
      this.isOpen = false;
    },
    toggle() {
      this.isOpen = !this.isOpen;
      if (this.isOpen) {
        this.$nextTick(() => {
          this.updatePosition();
        });
      }
    },
    onEnter() {
      clearTimeout(this.hoverTimer);
      this.open();
    },
    onLeave() {
      this.scheduleClose();
    },
    scheduleClose() {
      clearTimeout(this.hoverTimer);
      this.hoverTimer = setTimeout(() => this.close(), 120);
    },
    cancelClose() {
      clearTimeout(this.hoverTimer);
    },
  },
};
</script>

<style scoped>
.detail-wrap {
  position: relative;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  margin-left: 6px;
  vertical-align: middle;
}

.detail-chip {
  font-size: 12px;
  line-height: 1;
  padding: 4px 8px;
  border-radius: 999px;
  border: 1px solid #e0e0e0;
  background: #f7f7f7;
  cursor: pointer;
}

.detail-chip:hover {
  background: #efefef;
}

.detail-popover {
  position: absolute;
  z-index: 30;
  top: 120%;
  left: 0;
  min-width: 260px;
  max-width: 420px;
  background: #fff;
  border: 1px solid #e0e0e0;
  border-radius: 10px;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
  padding: 10px;
}

.detail-popover.is-up {
  top: auto;
  bottom: 120%;
}

.detail-popover__inner {
  font-size: 12px;
  color: #333;
  max-height: 260px;
  overflow: auto;
}

.detail-popover__row {
  margin-bottom: 6px;
}

.detail-popover__source {
  white-space: pre-wrap;
  word-wrap: break-word;
  background: #fafafa;
  border: 1px dashed #e5e5e5;
  border-radius: 6px;
  padding: 8px;
  margin: 0;
}
</style>
