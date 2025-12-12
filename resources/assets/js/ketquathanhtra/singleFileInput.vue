<template>
  <div class="single-file-container">
    <input
      type="file"
      ref="fileInput"
      @change="handleFileSelect"
      style="display: none"
      :disabled="isLoading"
    />

    <div class="input-group">
      <div class="input-group-btn">
        <button
          type="button"
          class="btn btn-default btn-flat"
          @click="triggerFileInput"
          :disabled="isLoading"
        >
          <i v-if="!isLoading" class="fa fa-paperclip"></i>
          <i v-else class="fa fa-spinner fa-spin"></i>
          Chọn File
        </button>
      </div>

      <div class="form-control file-display-area">
        <span v-if="currentFile" class="file-info">
          <a
            :href="`/storage/files/${currentFile.file_id}-${currentFile.name}`"
            target="_blank"
            title="Tải file xuống"
          >
            {{ currentFile.name }}
          </a>
        </span>
        <span v-else class="text-muted">Chưa có file nào được chọn</span>
      </div>

      <!-- Nút xóa file hiện tại -->
      <div class="input-group-btn" v-if="currentFile">
        <button
          type="button"
          class="btn btn-danger btn-flat"
          @click="removeCurrentFile"
          :disabled="isLoading"
          title="Xóa file hiện tại"
        >
          <i class="fa fa-trash-o"></i>
          Xóa
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "SingleFileInput",
  props: {
    value: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      currentFile: null,
      isLoading: false,
    };
  },
  watch: {
    value: {
      handler(newValue) {
        this.currentFile = newValue;
      },
      immediate: true,
    },
  },
  methods: {
    triggerFileInput() {
      this.$refs.fileInput.click();
    },
    async handleFileSelect(event) {
      const file = event.target.files[0];
      if (!file) return;

      this.isLoading = true;

      try {
        if (this.currentFile && this.currentFile.id) {
          await axios.delete("/file/delete/" + this.currentFile.id);
          this.$emit("file-deleted");
        }

        const formData = new FormData();
        formData.append("file", file);

        const response = await axios.post("/file/add", formData, {
          headers: { "Content-Type": "multipart/form-data" },
        });

        this.currentFile = response.data.result;
        this.$emit("file-uploaded", this.currentFile);
        console.log("Tải file lên thành công!");
      } catch (error) {
        console.error("Lỗi xử lý file:", error);
        this.currentFile = null;
      } finally {
        this.isLoading = false;
        this.$refs.fileInput.value = null;
      }
    },

    async removeCurrentFile() {
      if (!this.currentFile || !this.currentFile.id) return;
      this.isLoading = true;
      try {
        await axios.delete("/file/delete/" + this.currentFile.id);
        console.log("Đã xóa file thành công!");
        this.currentFile = null;
        this.$emit("file-deleted");
      } catch (error) {
        console.error("Lỗi xóa file:", error);
      } finally {
        this.isLoading = false;
      }
    },
  },
};
</script>

<style scoped>
.single-file-container .input-group {
  width: 100%;
}

.file-display-area {
  display: flex;
  align-items: center;
  height: 34px;
  background-color: #fff;
  border-left: none;
  border-right: none;
}

.input-group .input-group-btn:first-child > .btn {
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}

.input-group .input-group-btn:last-child > .btn {
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
}

.file-info {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.text-muted {
  color: #777;
  font-style: italic;
}
</style>
