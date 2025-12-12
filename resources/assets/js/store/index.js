import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    macoso: null,
    tencoso: null,
    diachi: null,
    hinhanh: null
  },

  getters: {},

  plugins: [],

  mutations: {
    addMacoso(state, customer) {
      // mutate state
      state.macoso.push(customer);
    }
  }
});

export default store;
