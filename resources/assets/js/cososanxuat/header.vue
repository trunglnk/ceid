<template>
  <div class="header">
    <nav>
      <div class="header-container">
        <div class="logo mr-4" style="flex: 0 !important;">
          <img src="/images/logo-06.png" alt="logo" srcset="" />
        </div>
        <div class="tilte">
          <span class="ma-0 text-uppercase"
            >Cơ sở dữ liệu thanh tra môi trường</span
          >
        </div>
        <div class="header-login ml-auto">
          <button class="btn btn-text btn-success" @click="action">
            {{ `${!checklogin ? "Đăng nhập" : "Quản trị"}` }}
          </button>
        </div>
      </div>
    </nav>
  </div>
</template>
<script>
export default {
  props: ["checklogin"],
  methods: {
    action() {
      window.location.href = this.checklogin ? "/cososanxuat" : "/login";
    }
  }
};
</script>
<style lang="scss" scoped>
nav {
  background-color: #dddddd;
}
.header-container {
  padding: 10px 80px;
  min-height: 65px;
  display: flex;
  align-items: center;
  img {
    width: 40px;
  }
  .tilte {
    color: #000;
    font-weight: 600;
    font-size: 19px;
  }
  .header-login {
    button {
      font-size: 14px;
    }
  }
}
.header-container {
  padding: 10px 80px;
  min-height: 82px;
  display: flex;
  align-items: center;
  .tilte {
    color: #000;
    font-weight: 600;
    font-size: 22px;
  }
}
.btn-text {
  background-color: transparent !important;
  color: #008d4c !important;
  border: none !important;
  font-weight: 600;
  text-transform: uppercase;
  box-shadow: none !important;
  outline: 0 !important;
}
</style>
