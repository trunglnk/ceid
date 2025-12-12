<template>
  <span>
    <div
      class="box-header"
      v-if="
        usersystem.role_code == 'admin' ||
        usersystem.role_code == 'nhanvientrungtam' ||
        usersystem.role_code == 'adminvaquanlydanhmuc'
      "
    >
      <label class="control-label">Tài liệu đính kèm</label>
      <div class="row">
        <div class="col-xs-12 pull-right">
          <div class="btn btn-file btn-flat">
            <i class="fa fa-paperclip"></i> Chọn tập tin (Không quá 30MB)
            <input
              type="file"
              id="file"
              ref="file"
              v-on:change="handleFileUpload()"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="box-body">
      <div class="row">
        <div class="col-sm-12">
          <table
            class="table table-hover table-responsive"
            v-if="attachments.length > 0"
          >
            <tbody>
              <tr class="row-table-header">
                <th>Tên file</th>
                <th style="width: 15%; text-align: right">Thao tác</th>
                <!-- <th style="width: 5%; text-align: center"></th>
                <th
                  style="width: 5%; text-align: center"
                  v-if="
                    usersystem.role_code == 'admin' ||
                    usersystem.role_code == 'nhanvientrungtam' ||
                    usersystem.role_code == 'adminvaquanlydanhmuc'
                  "
                ></th> -->
              </tr>
              <tr v-for="(item, index) in attachments">
                <td>
                  <a
                    style="cursor: pointer"
                    @click="dowloadFile(item.file_id)"
                    >{{ item.name }}</a
                  >
                </td>
                <td class="text-right" style="white-space: nowrap">
                  <a
                    v-if="item.name && item.name.toLowerCase().endsWith('.pdf')"
                    style="cursor: pointer; margin-left: 8px"
                    @click="analyzeFile(item)"
                    title="Trích xuất"
                  >
                    <i class="fa fa-cogs btn"></i>
                  </a>
                  <a
                    v-if="
                      usersystem.role_code == 'admin' ||
                      usersystem.role_code == 'nhanvientrungtam' ||
                      usersystem.role_code == 'adminvaquanlydanhmuc'
                    "
                    style="cursor: pointer; margin-left: 8px"
                    @click="deleteFile(item.file_id, index)"
                    title="Xóa"
                  >
                    <i class="fa fa-trash-o btn"></i>
                  </a>
                  <a
                    style="cursor: pointer; margin-left: 8px"
                    @click="dowloadFile(item.file_id)"
                    title="Tải xuống"
                  >
                    <i class="fa fa-download btn"></i>
                  </a>
                </td>
                <!-- <td align="center">
                  <a style="cursor: pointer" @click="dowloadFile(item.file_id)">
                    <i class="fa fa-download btn"></i>
                  </a>
                </td>
                <td
                  style="cursor: pointer"
                  align="center"
                  v-if="
                    usersystem.role_code == 'admin' ||
                    usersystem.role_code == 'nhanvientrungtam' ||
                    usersystem.role_code == 'adminvaquanlydanhmuc'
                  "
                >
                  <a @click="deleteFile(item.file_id, index)">
                    <i class="fa fa-trash-o btn"></i>
                  </a>
                </td>
                <td -->
              </tr>
            </tbody>
          </table>
          <p class="text-muted" v-else>Chưa chọn tập tin</p>
        </div>
      </div>
    </div>
  </span>
</template>
<script>
import * as env from "../env.js";
import { mask } from "vue-the-mask";

export default {
  props: ["value", "usersystem"],
  data: function () {
    return {
      data: [],
      attachments: [],
    };
  },
  methods: {
    initAttachments() {
      if (Array.isArray(this.value) && this.value.length > 0) {
        // ✅ Trường hợp truyền vào là mảng file từ localStorage
        this.attachments = this.value;
        this.$emit("files", this.attachments);
      } else if (this.value && typeof this.value === "number") {
        // ✅ Trường hợp truyền vào là ID (load từ API)
        axios
          .get(env.endpointhttp + "ketquathanhtra/file/" + this.value)
          .then((response) => {
            this.attachments = response.data.result;
            this.$emit("files", this.attachments);
          });
      }
    },
    
    dowloadFile(file_id) {
      window.open("/file/download/" + file_id);
    },
    deleteFile(file_id, index) {
      axios
        .delete(env.endpointhttp + "file/delete/" + file_id)
        .then((response) => {
          this.attachments.splice(index, 1);
        });
    },
    handleFileUpload() {
      let formData = new FormData();
      formData.append("file", this.$refs.file.files[0]);
      axios
        .post("/file/add", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          this.attachments.push(response.data.result);
          this.$emit("files", this.attachments);
        })
        .catch(function () {
          console.log("FAILURE!!");
        });
    },
    analyzeFile(file) {
      this.$emit("analyze", file);
    },
  },
  mounted() {
    this.initAttachments();
  },
watch: {
  value: {
    handler(newVal) {
      // Khi prop value (attachments) thay đổi — tự fill lại
      if (Array.isArray(newVal) && newVal.length > 0) {
        this.attachments = newVal;
        this.$emit("files", this.attachments);
      }
    },
    deep: true,
    immediate: true,
  },
},
};
</script>
