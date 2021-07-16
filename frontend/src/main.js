import Vue from 'vue'
import App from './App.vue'
import Vuelidate from "vuelidate";
Vue.use(Vuelidate);
import { BootstrapVue, IconsPlugin } from "bootstrap-vue"; // Import Bootstrap an BootstrapVue CSS files (order is important)

import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue/dist/bootstrap-vue.css";

import "select2/dist/css/select2.min.css";
import "select2/dist/js/select2.full.min.js";

import "daterangepicker/daterangepicker.css";
import "daterangepicker/daterangepicker.js";

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

import router from "./routes";
import store from './store';

Vue.config.productionTip = false

new Vue({
  render: h => h(App),
  router,
  store
}).$mount('#app')
