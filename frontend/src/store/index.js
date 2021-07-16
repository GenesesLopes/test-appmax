import Vue from "vue";
import Vuex from "vuex";

import user from './modules/user';
import estoque from './modules/estoque';
import produtos from "./modules/produtos";

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    user,
    estoque,
    produtos
  },
});
