<template>
  <div class="row">
    <div class="col-lg-3">
      <br />
      <ul class="nav nav-pills nav-stacked">
        <li class="active">
          <a href="#tab_1" data-toggle="tab" aria-expanded="true">
            Danh mục các ký hiệu, chữ viết tắt
          </a>
        </li>
        <li>
          <a href="#tab_2" data-toggle="tab">
            Quản lý tài khoản người sử dụng
          </a>
        </li>
      </ul>
    </div>
    <div class="col-lg-9">
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="timeline-item">
            <h3 class="timeline-header">Danh mục các ký hiệu, chữ viết tắt</h3>
            <table class="table table-hover table-responsive">
              <tbody>
                <tr class="row-table-header">
                  <th>Chữ viết tắt</th>
                  <th>Nguyên nghĩa</th>
                </tr>
                <tr
                  v-for="item in bangkytuviettat"
                  v-if="bangkytuviettat.length > 0"
                >
                  <td v-text="item.chu_viet_tat"></td>
                  <td v-text="item.nguyen_nghia"></td>
                </tr>
                <tr v-else>
                  <td align="center" colspan="2">
                    <p class="text-muted">Không có dữ liệu</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="tab-pane" id="tab_2">
          <div class="timeline-item">
            <h3 class="timeline-header">Quản lý tài khoản người sử dụng</h3>
            <p class="text-muted">
              Mục này sẽ hướng dẫn bạn quy trình xem danh sách người dùng và
              thay đổi thông tin về người dùng hệ thống, cộng với việc thêm
              người dùng và gán quyền truy cập cho người dùng mới.
            </p>
            <h4 class="timeline-item-sub-title">Xem danh sách người dùng</h4>
            <ul>
              <li>Đăng nhập tài khoản quyền quản trị</li>
              <li>
                Chọn chức năng&nbsp;<a href="/users"
                  ><i class="fa fa-user"></i>&nbsp;Người dùng</a
                >&nbsp;bên menu trái
              </li>
            </ul>
            <img
              src="/images/document/users.png"
              alt="Danh sách người dùng hệ thống"
              class="img-responsive"
            />
            <ul>
              <li>
                Nhập thông tin tên, tên đăng nhập email vào ô tìm kiếm ở góc
                trên bên phải và nhấn phím Enter hoặc nhấn chọn biểu tượng tìm
                kiếm bên cạnh
              </li>
              <li>
                Nếu danh sách người dùng lớn hơn 10, bạn nhấn chọn trang tiếp
                theo để xem
              </li>
              <li>
                Nếu muốn lựa lọc thông tin người dùng nâng cao, bạn nhấn biểu
                tượng mũi tên bên cạnh biểu tượng tìm kiếm, hộp thoại tìm kiếm
                nâng cao mở ra. Bạn chọn vai trò người sử dụng hoặc trạng thái
                người sử dụng sau đó nhấn nút tìm kiếm
              </li>
            </ul>
            <hr />
            <h4 class="timeline-item-sub-title">Thêm mới người dùng</h4>
            <ul>
              <li>
                Nhấn nút thêm mới ở góc trái màn hình danh sách người dùng
              </li>
              <li>Nhập thông tin người dùng</li>
            </ul>
            <img
              src="/images/document/user-add.png"
              alt="Thêm mới người dùng"
              class="img-responsive"
            />
            <ul>
              <li>
                Trường tên dùng để hiển thị khi đăng nhập, trường tên đăng nhập,
                email và mật khẩu dùng để đăng nhập hệ thống
              </li>
              <li>
                Người dùng có thể sử dụng email hoặc tên đăng nhập để truy cập
                hệ thống
              </li>
            </ul>
            <hr />
            <h4 class="timeline-item-sub-title">
              Cập nhật thông tin người dùng
            </h4>
            <ul>
              <li>
                Trong màn hình danh sách người dùng, nhấn chọn biểu tượng cập
                nhật bên phải
              </li>
              <li>Nhập thông tin cần cập nhật</li>
            </ul>
            <img
              src="/images/document/user-update.png"
              alt="Cập nhật người dùng"
              class="img-responsive"
            />
            <ul>
              <li>
                Nếu trạng thái là&nbsp;<strong>"Hoạt động"</strong>&nbsp;thì
                người dùng có thể đăng nhập hệ thống và ngược lại. <br />Ta thay
                đổi trạng thái người dùng để tạm khóa hoặc mở khóa truy cập hệ
                thống cho tài khoản
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as env from "../env.js";
export default {
  data: function() {
    return {
      bangkytuviettat: []
    };
  },
  mounted() {
    axios.get(env.endpointhttp + "documents/bangkytuviettat").then(response => {
      this.bangkytuviettat = response.data.result;
    });
  }
};
</script>

<style scoped>
.timeline-item {
  line-height: 1.9;
}

.timeline-item-sub-title {
  font-weight: 700;
  margin: 35px 0 21px 0;
}
img {
  height: auto;
  max-width: 100%;
}

img:hover {
  box-shadow: 0 0 2px 1px #f4f4f4;
}
</style>
