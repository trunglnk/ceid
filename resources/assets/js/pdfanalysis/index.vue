<template>
  <div>
    <div class="pdf-analysis-container">
      <div v-if="!analysisResult" class="upload-section">
        <div
          class="drop-zone"
          @dragover.prevent="isDragOver = true"
          @dragleave.prevent="isDragOver = false"
          @drop.prevent="handleDrop"
          @click="triggerFileInput"
          :class="{ 'drag-over': isDragOver }"
        >
          <input
            type="file"
            ref="fileInput"
            @change="handleFileSelect"
            accept="application/pdf"
            style="display: none"
          />
          <div class="upload-instructions">
            <i class="fa fa-cloud-upload-alt upload-icon"></i>
            <p class="main-text">Kéo và thả tệp PDF của bạn vào đây</p>
            <p class="sub-text">hoặc</p>
            <button class="btn btn-primary">
              <i class="fa fa-upload"></i> Chọn tệp từ máy tính
            </button>
            <p v-if="error" class="error-message">{{ error }}</p>
          </div>
        </div>
        <p class="upload-hint">
          Chỉ hỗ trợ định dạng .pdf, dung lượng tối đa 50MB
        </p>
      </div>

      <div v-else class="results-section">
        <div class="results-header">
          <h4>
            <i class="fa fa-poll"></i> Kết quả phân tích cho tệp:
            <strong>{{ fileName }}</strong>
          </h4>
          <button @click="resetState" class="btn bg-olive">
            <i class="fa fa-plus-circle"></i> Phân tích tài liệu mới
          </button>
        </div>
        <AnalysisResult :result="analysisResult" />
      </div>
    </div>

    <confirm-dialog
      width="450px"
      v-model="showAnalysisModal"
      title="Trích xuất tài liệu PDF"
      center
      :disabled="isProcessing"
      :buttons="modalButtons"
      @preview="onPreview"
      @confirm="onConfirm"
    >
      <div v-if="isProcessing" class="processing-section">
        <div class="spinner"></div>
        <h2>
          {{ isChecking ? "Đang kiểm tra..." : "Đang phân tích tài liệu..." }}
        </h2>
        <p>{{ fileName }}</p>
        <p v-if="!isChecking">
          Quá trình này có thể mất vài phút, vui lòng chờ.
        </p>
      </div>
      <div v-else class="text-center">
        {{ modalText }}
      </div>
      <div
        v-if="error"
        class="error-message text-center"
        style="margin-top: 10px"
      >
        {{ error }}
      </div>
    </confirm-dialog>
  </div>
</template>

<script>
import axios from "axios";
import AnalysisResult from "./components/AnalysisResult.vue";
import * as env from "../env";

export default {
  name: "PdfAnalysis",
  components: { AnalysisResult },
  data() {
    return {
      isDragOver: false,
      analysisResult: null,
      fileName: "",
      error: null,

      isProcessing: false,
      isChecking: false,
      showAnalysisModal: false,
      modalText: "",
      fileSelect: null,
      hasAnalysis: false,
      cachedAnalysis: null,
      modalButtons: [],
    };
  },
  methods: {
    triggerFileInput() {
      this.$refs.fileInput.click();
    },
    handleFileSelect(event) {
      const file = event.target.files[0];
      if (file) {
        this.initiateAnalysis(file);
      }
    },
    handleDrop(event) {
      this.isDragOver = false;
      const file = event.dataTransfer.files[0];
      if (file) {
        this.initiateAnalysis(file);
      }
    },
    setModalButtons(showPreview) {
      const btnCancel = {
        key: "cancel",
        text: "Hủy",
        icon: "fa fa-close",
        class: "btn btn-default btn-flat",
        align: "left",
        event: "cancel",
        closeOnClick: true,
      };
      const btnConfirm = {
        key: "ok",
        text: "Trích xuất",
        icon: "fa fa-check",
        class: "btn bg-olive btn-flat",
        align: "right",
        event: "confirm",
        type: "submit",
      };

      if (showPreview) {
        const btnPreview = {
          key: "preview",
          text: "Xem lại",
          icon: "fa fa-eye",
          class: "btn btn-default btn-flat",
          align: "right",
          event: "preview",
        };
        this.modalButtons = [btnCancel, btnPreview, btnConfirm];
      } else {
        this.modalButtons = [btnCancel, btnConfirm];
      }
    },
    async initiateAnalysis(file) {
      if (file.type !== "application/pdf") {
        this.error = "Lỗi: Vui lòng chỉ chọn tệp có định dạng PDF.";
        return;
      }

      this.resetState(false);
      this.fileSelect = file;
      this.fileName = file.name;

      this.isProcessing = true;
      this.isChecking = true;
      this.showAnalysisModal = true;

      const formData = new FormData();
      formData.append("file", file);

      try {
        const response = await axios.post(
          env.endpointhttp + "checkpdfresult",
          formData,
          {
            headers: { "Content-Type": "multipart/form-data" },
          }
        );

        const data = response.data || {};
        this.hasAnalysis = !!data.is_analysis;
        this.cachedAnalysis = this.hasAnalysis ? data.result : null;

        if (this.hasAnalysis) {
          this.modalText =
            "Tài liệu này đã được phân tích. Bạn muốn xem lại kết quả cũ hay trích xuất lại?";
        } else {
          this.modalText =
            "Tài liệu này chưa được phân tích. Bạn có muốn bắt đầu trích xuất không?";
        }
        this.setModalButtons(this.hasAnalysis);
      } catch (err) {
        console.error("Lỗi khi kiểm tra trạng thái PDF:", err);
        this.hasAnalysis = false;
        this.cachedAnalysis = null;
        this.modalText =
          "Không thể kiểm tra trạng thái. Bạn có muốn tiếp tục trích xuất không?";
        this.error = "Lỗi khi kiểm tra trạng thái phân tích của tài liệu.";
        this.setModalButtons(false);
      } finally {
        this.isProcessing = false;
        this.isChecking = false;
      }
    },
    onPreview() {
      if (this.hasAnalysis && this.cachedAnalysis) {
        this.analysisResult = this.cachedAnalysis;
        this.showAnalysisModal = false;
      } else {
        this.error = "Không có kết quả cũ để xem lại.";
      }
    },
    async onConfirm() {
      if (!this.fileSelect) {
        this.error = "Không có tệp nào được chọn để phân tích.";
        return;
      }

      this.isProcessing = true;
      this.error = null;

      const formData = new FormData();
      formData.append("file", this.fileSelect);

      try {
        const response = await axios.post(
          env.endpointhttp + "pdfanalysis",
          formData,
          {
            headers: { "Content-Type": "multipart/form-data" },
          }
        );
        this.analysisResult = response.data;
        this.showAnalysisModal = false;
      } catch (err) {
        console.error("Lỗi API phân tích PDF:", err);
        this.error =
          "Đã có lỗi xảy ra trong quá trình phân tích. Vui lòng thử lại.";
        this.isProcessing = false; // Dừng spinner khi có lỗi
      }
    },

    resetState(resetInput = true) {
      this.isProcessing = false;
      this.isChecking = false;
      this.analysisResult = null;
      this.fileName = "";
      this.error = null;
      this.showAnalysisModal = false;
      this.fileSelect = null;
      this.hasAnalysis = false;
      this.cachedAnalysis = null;
      this.modalText = "";
      this.setModalButtons(false);

      if (resetInput && this.$refs.fileInput) {
        this.$refs.fileInput.value = "";
      }
    },
  },
};
</script>

<style scoped>
.pdf-analysis-container {
  padding: 2rem;
  background-color: #f5f5f5;
  font-family: Arial, sans-serif;
  border: 1px solid #d2d6de;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
.btn {
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: 700;
  transition: all 0.2s ease;
  text-transform: uppercase;
}
.btn .fa {
  margin-right: 8px;
}
.btn-primary {
  background-color: #001f3f;
  color: white;
}
.btn-primary:hover {
  background-color: #011931;
}
.bg-olive {
  background-color: #001f3f !important;
  color: white;
}
.bg-olive:hover {
  background-color: #011931 !important;
}

.upload-section {
  max-width: 800px;
  margin: 2rem auto;
}
.drop-zone {
  border: 3px dashed #b5b5b5;
  border-radius: 10px;
  padding: 40px;
  text-align: center;
  cursor: pointer;
  transition: background-color 0.3s, border-color 0.3s;
  background-color: #fff;
}
.drop-zone:hover,
.drop-zone.drag-over {
  background-color: #f0f8ff;
  border-color: #001f3f;
}
.upload-icon {
  font-size: 4rem;
  color: #001f3f;
  opacity: 0.7;
  margin-bottom: 1rem;
}
.main-text {
  font-size: 1.5rem;
  font-weight: bold;
  color: #333;
}
.sub-text {
  color: #888;
  margin: 1rem 0;
}
.upload-hint {
  text-align: center;
  color: #888;
  margin-top: 1rem;
}
.error-message {
  color: #d81b60;
  font-weight: bold;
}
.text-center {
  text-align: center;
}

.processing-section {
  text-align: center;
  padding: 20px 0;
  color: #333;
}
.spinner {
  border: 6px solid #f3f3f3;
  border-top: 6px solid #001f3f;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
  margin: 0 auto 15px auto;
}
@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.results-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}
</style>
