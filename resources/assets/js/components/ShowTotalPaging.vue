<template>
  <div>
    <p
      style="float:left"
      v-if="
        paging.total &&
          paging.perpage &&
          paging.current_page &&
          paging.last_page
      "
    >
      Hiển thị từ {{ paging.perpage * (paging.current_page - 1) + 1 }} đến
      {{
        paging.current_page == paging.last_page
          ? paging.total
          : paging.current_page * paging.perpage
      }}
      của {{ paging.total }}
    </p>
  </div>
</template>
<script>
export default {
  props: ["paging"]
};
</script>
<style scoped>
.adot {
  pointer-events: none;
  cursor: default;
  border: none !important;
  background: transparent !important;
}
.page-link {
  cursor: pointer;
}
</style>
