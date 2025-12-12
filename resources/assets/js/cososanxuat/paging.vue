<template>
  <div>
    <nav
      aria-label="Page navigation example"
      style="display: flex;justify-content: flex-end;"
    >
      <ul class="pagination">
        <li class="page-item" @click="changePage(null, null, true)">
          <a class="page-link" href="#">
            <i class="fa fa-angle-left" aria-hidden="true"></i>
          </a>
        </li>
        <template v-if="paging.last_page < 9">
          <li
            :class="[item == paging.current_page ? 'active' : '']"
            class="page-item"
            v-for="item in paging.last_page"
            :key="item"
            @click="changePage(item)"
          >
            <a class="page-link" href="#">{{ item }}</a>
          </li>
        </template>
        <template v-else>
          <template v-for="item in paging.last_page">
            <li
              :class="[item == paging.current_page ? 'active' : '']"
              class="page-item"
              :key="item"
              v-if="
                paging.current_page > 3 &&
                  (item == 1 ||
                    (item <= 3 && paging.current_page > paging.last_page - 2))
              "
              @click="changePage(item)"
            >
              <a class="page-link" href="#">{{ item }}</a>
              <a
                href
                class="adot"
                v-if="
                  (item == 1 && paging.current_page <= paging.last_page - 2) ||
                    item == 3
                "
                >...</a
              >
            </li>
            <template v-else-if="paging.current_page <= 3">
              <li
                :class="[item == paging.current_page ? 'active' : '']"
                class="page-item"
                :key="item"
                v-if="
                  paging.current_page == 2 &&
                    (item <= paging.current_page + 1 ||
                      item >= paging.last_page - 1)
                "
                @click="changePage(item)"
              >
                <a class="page-link" href="#">{{ item }}</a>
                <a href class="adot" v-if="item == paging.current_page + 1"
                  >...</a
                >
              </li>
              <li
                :class="[item == paging.current_page ? 'active' : '']"
                class="page-item"
                :key="item"
                v-else-if="
                  paging.current_page == 2 &&
                    item != 4 &&
                    (item <= paging.current_page + 2 ||
                      item >= paging.last_page - 2)
                "
                @click="changePage(item)"
              >
                <a class="page-link" href="#">{{ item }}</a>
                <a href class="adot" v-if="item == paging.current_page + 2"
                  >...</a
                >
              </li>
              <li
                :class="[item == paging.current_page ? 'active' : '']"
                class="page-item"
                :key="item"
                v-else-if="
                  paging.current_page != 2 &&
                    (item <= paging.current_page + 2 ||
                      item >= paging.last_page - 2)
                "
                @click="changePage(item)"
              >
                <a
                  class="page-link"
                  href="#"
                  v-if="
                    (item == paging.last_page &&
                      paging.current_page < paging.last_page - 3) ||
                      (item != paging.last_page &&
                        item < paging.last_page - 3) ||
                      paging.current_page == 1
                  "
                  >{{ item }}</a
                >
                <a href class="adot" v-if="item == paging.current_page + 2"
                  >...</a
                >
              </li>
            </template>

            <li
              :class="[item == paging.current_page ? 'active' : '']"
              class="page-item"
              :key="item"
              v-else-if="
                (item <= paging.current_page + 1 &&
                  item >= paging.current_page - 1) ||
                  item >= paging.last_page - 2 ||
                  (paging.current_page == paging.last_page - 2 &&
                    item == paging.current_page - 2)
              "
              @click="changePage(item)"
            >
              <a
                class="page-link"
                href="#"
                v-if="
                  (item == paging.last_page &&
                    paging.current_page < paging.last_page - 3) ||
                    (item != paging.last_page &&
                      item <= paging.last_page - 3) ||
                    paging.current_page > paging.last_page - 3 ||
                    (paging.current_page == paging.last_page - 3 &&
                      item != paging.last_page - 1)
                "
                >{{ item }}</a
              >
              <!-- <a href>abc</a> -->
              <a
                href
                class="adot"
                v-if="
                  item == paging.current_page + 1 &&
                    paging.current_page < paging.last_page - 1 &&
                    paging.current_page != paging.last_page - 2
                "
                >...</a
              >
            </li>
          </template>
        </template>
        <li class="page-item" @click="changePage(null, true, null)">
          <a class="page-link" href="#">
            <i class="fa fa-angle-right" aria-hidden="true"></i>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</template>
<script>
export default {
  props: ["paging"],
  methods: {
    changePage(page = null, next = null, previous = null) {
      let numberpage = this.paging.current_page;
      if (page) {
        numberpage = page;
      } else if (next) {
        if (numberpage != this.paging.last_page) {
          numberpage++;
        }
      } else if (previous) {
        if (numberpage != 1) {
          numberpage--;
        }
      }
      this.$emit("change-page", numberpage);
    }
  }
};
</script>
<style scoped>
.adot {
  pointer-events: none;
  cursor: default;
  border: none !important;
  background: transparent !important;
}
</style>
