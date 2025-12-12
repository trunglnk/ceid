<template>
    <div class="timeline-body">
        <div class="col-md-12" style="padding-top: 20px">
            <table class="table table-hover fixed_headers">
                <thead>
                    <tr class="row-table-header">
                        <th style="width: 5%">STT</th>
                        <th>Thuộc nhóm</th>
                        <th style="width: 30%">Tên</th>
                        <!-- <th style="width: 5%"></th> -->
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in nhoms" :key="item.nhoms">
                        <td style="width: 5%">{{ index + 1 }}</td>
                        <td style="width: 60%; cursor: pointer">
                            <a @click="loadChiTietNhomCoSo(item.id)">{{ item.ten }}</a>
                        </td>
                        <td style="width: 35%">
                            <table>
                                <tr v-for="(item1, index_nhom) in nhomcs">
                                    <td v-if="item1.parent_id == item.id" style="padding: 2%">
                                        <textarea type="text" class="form-control" v-model="item1.ten" @change="
                                          updateLoaiCoSo(
                                            item1.id,
                                            item1.ten,
                                            item1.parent_id
                                          )
                                        " v-if="
                              usersystem.role_code == 'admin' ||
                              usersystem.role_code == 'adminvaquanlydanhmuc'
                            "></textarea>
                                        <textarea type="text" class="form-control" v-model="item1.ten"
                                            v-else></textarea>
                                    </td>
                                    <!-- <td v-if="
                                      item1.parent_id == item.id &&
                                      (usersystem.role_code == 'admin' ||
                                        usersystem.role_code == 'adminvaquanlydanhmuc')
                                    " align="center">
                                        <a @click="deleteLoaiCoSo(item1.id, index_nhom)">
                                            <i class="fa fa-trash-o btn"></i>
                                        </a>
                                    </td> -->
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <modal-component width="450px" v-model="showModel" center @submit="deleteLoaiHinhCoSo()" :title="'Xóa'">
            <div>Bạn có chắc chắn muốn xóa không ?</div>
        </modal-component>
    </div>
</template>
  
  
  
<script>
import * as env from "../../env.js";
import MessageService from "../../services/MessageService";
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import { ValidationProvider } from "vee-validate";

export default {
    components: { Treeselect, ValidationProvider },
    data: function () {
        return {
            nhoms: [],
            nhom: null,
            data: [],
            ten_co_so_moi: null,
            error_add_loai_co_so: null,
            nhomcs: [],
            nganhNghes: [],
            loaiHinhs: [],
            form: {
                parent_id: null,
                ma: null,
                ten: null,
            },
            ten_input: null,
            ma_input: null,
            dong: null,
            parentSelect: null,
            showModel: null,
            xoaLoaihinhcoso: {},
            thoi_gian_dong_bo: null,
            load_thoi_gian: true,
            thoi_gian: '',
        };
    },
    props: ["usersystem"],
    computed: {
        infoParent() {
            if (this.parentSelect) {
                return "Mã danh mục " + this.parentSelect.ma_danh_muc;
            }
            return "";
        },
    },
    created() {
        axios.get(env.endpointhttp + "loaihinhcosos").then((response) => {
            this.data = response.data.result;
        });
        axios.get(env.endpointhttp + "nhomloaihinhcosos").then((response) => {
            this.nhoms = response.data.result;
        });
        this.getCoSoTreeSelect();
        this.getLoaiNganhNghe();
        this.getThoiGianDongBo();
    },
    methods: {
        getCoSoTreeSelect() {
            axios.get(env.endpointhttp + "treeloaihinh").then((response) => {
                this.loaiHinhs = response.data;
            });
        },
        getLoaiNganhNghe() {
            axios.get(env.endpointhttp + "nganhnghes").then((response) => {
                this.nganhNghes = response.data;
            });
        },
        getThoiGianDongBo() {
            axios
                .get(env.endpointhttp + "inthoigian/loai_hinh_co_so",)
                .then((response) => {
                    this.thoi_gian = response.data
                    this.thoi_gian_dong_bo = new Date(response.data.updated_at).getHours() + ':' + new Date(response.data.updated_at).getMinutes() + ':' + new Date(response.data.updated_at).getSeconds() + ' ' + (new Date(response.data.updated_at)).toLocaleDateString('en-TT')
                    console.log(response.data)
                    this.load_thoi_gian = true;
                });
        },
        updateLoaiCoSo: function (id) {
            axios
                .put(env.endpointhttp + "loaihinhcosos/" + id, {
                    ma: this.ma_input,
                    ten: this.ten_input,
                })
                .then((response) => {
                    this.dong = null;
                    this.getLoaiNganhNghe();
                    MessageService.showSuccessMessage("Cập nhật thành công");
                });
        },
        resetForm() {
            this.form = {
                parent_id: null,
                ma: null,
                ten: null,
            };
            this.parentSelect = null;
        },
        addLoaiCoSo: function () {
            this.$validator.validateAll().then((validate) => {
                this.form.parent_id = this.parentSelect ? this.parentSelect.id : null;
                if (validate && this.form.parent_id) {
                    axios
                        .post(env.endpointhttp + "loaihinhcosos", this.form)
                        .then((response) => {
                            this.getLoaiNganhNghe();
                            this.getCoSoTreeSelect();
                            this.resetForm();
                            MessageService.showSuccessMessage("Thêm mới thành công");
                        });
                }
            });
        },
        loadChiTietNhomCoSo: function (id) {
            axios
                .get(env.endpointhttp + "chitietloaihinhcoso/" + id)
                .then((response) => {
                    this.nhomcs = response.data.result;
                });
        },
        edit(index) {
            this.ten_input = this.nganhNghes[index].ten;
            this.ma_input = this.nganhNghes[index].ma;
            this.dong = index;
        },
        deleteLoaiHinhCoSo: function () {
            axios
                .delete(env.endpointhttp + "loaihinhcosos/" + this.xoaLoaihinhcoso.id)
                .then((response) => {
                    this.getLoaiNganhNghe();
                    this.showModel = false;
                    MessageService.showSuccessMessage("Xóa thành công");
                });
            this.showModel = false;
        },
        huy() {
            this.dong = null;
        },
        showConfirmDelete(Loaihinhcoso) {
            this.showModel = true;
            this.xoaLoaihinhcoso = Loaihinhcoso;
        },
        dongbodulieu() {
            this.load_thoi_gian = false;
            axios
                .get(env.endpointhttp + 'moitruongquocgia/loainganhnghekinhte')
                .then((response) => {
                    this.getLoaiNganhNghe();
                    this.getThoiGianDongBo();
                    MessageService.showSuccessMessage('Đồng bộ thành công')
                })
        },
    },
};
</script>
<style scoped>
thead,
tfoot {
    display: table;
}

tbody {
    display: block;
    max-height: 373px;
    overflow-y: scroll;
}

.pointer {
    cursor: pointer;
}
</style>
  