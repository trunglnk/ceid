<template>
  <button
    type="button"
    class="copy-action-btn"
    :class="{ 'is-copied': isCopied }"
    :title="isCopied ? 'Đã sao chép!' : 'Sao chép'"
    @click.stop="copyToClipboard"
  >
    <i class="fa" :class="isCopied ? 'fa-check' : 'fa-clipboard'"></i>
  </button>
</template>

<script>
export default {
  name: "CopyAction",
  props: {
    value: {
      type: [String, Number],
      required: true,
    },
  },
  data() {
    return {
      isCopied: false,
      copyTimeout: null,
    };
  },
  beforeDestroy() {
    // Dọn dẹp timeout khi component bị hủy để tránh memory leak
    clearTimeout(this.copyTimeout);
  },
  methods: {
    copyToClipboard() {
      if (!navigator.clipboard) {
        // Fallback cho các trình duyệt cũ hoặc môi trường không an toàn (http)
        alert("Trình duyệt của bạn không hỗ trợ sao chép an toàn.");
        return;
      }

      navigator.clipboard.writeText(this.value.toString()).then(
        () => {
          // Thành công
          this.isCopied = true;
          clearTimeout(this.copyTimeout); // Xóa timeout cũ nếu có

          this.copyTimeout = setTimeout(() => {
            this.isCopied = false;
          }, 1500);
        },
        (err) => {
          // Thất bại
          console.error("Không thể sao chép: ", err);
          alert("Sao chép thất bại!");
        }
      );
    },
  },
};
</script>

<style scoped>
.copy-action-btn {
  font-size: 12px;
  line-height: 1;
  padding: 4px 8px;
  border-radius: 999px;
  border: 1px solid #e0e0e0;
  background: #f7f7f7;
  cursor: pointer;
  margin-left: 4px; /* Tạo khoảng cách với nút bên cạnh */
  vertical-align: middle;
  transition: all 0.2s ease;
  color: #555;
}

.copy-action-btn:hover {
  background: #efefef;
  border-color: #ccc;
}

.copy-action-btn.is-copied {
  background-color: #e6ffed;
  border-color: #4caf50;
  color: #4caf50;
}
</style>
