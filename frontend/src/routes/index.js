import Vue from "vue";
import Router from "vue-router";

import Middleware from "../middleware";

import Login from "../pages/Login/index.vue";
import Dashboard from "../pages/Dashboard/index.vue"
import Produtos from '../pages/Produtos/index.vue';
import Estoque from '../pages/Estoque/index.vue';

Vue.use(Router);

const routes = [
  {
    name: "login",
    path: "/",
    component: Login,
  },
  {
    name: "dashboard",
    path: "/admin",
    component: Dashboard,
  },
  {
    name: "produtos",
    path: "/admin/produtos",
    component: Produtos,
  },
  {
    name: "estoque",
    path: "/admin/estoque",
    component: Estoque,
  },
];
const router = new Router({
  mode: "history",
  routes,
});
// eslint-disable-next-line no-unused-vars
router.beforeEach((to, from, next) => {
  if (!Middleware() && to.name !== "login") {
    next({ name: "login" });
  } else if (Middleware() && to.name === "login") {
    console.log('asdw')
    next({
      name: "dashboard",
    });
  } else {
    next();
  }
});
export default router;
