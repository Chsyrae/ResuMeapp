import { createRouter, createWebHashHistory } from "vue-router";
import { userAuthStore } from "../stores/userAuth";

const Home    = () => import("../views/home.vue");
const Builder = () => import("../views/builder.vue");
const Login   = () => import("../views/login.vue");

const ifAuthenticated = (to, from) => {
    const authStore = userAuthStore();
  
    if (!authStore.isAuthenticated) return "/";
  
    if (to?.meta?.permission) {
      if (!ability.can("view-assign-group-access")) return false;
    }
  };
const router  = createRouter({
    history: createWebHashHistory(),
    routes: [
        {
            path: "/",
            name: "Home",
            component: Home,
        },

        {
            path: "/login",
            name: "Login",
            component: Login,
        },

        {
            path: "/builder",
            name: "Builder",
            component: Builder,
            beforeEnter: ifAuthenticated,
        },

    ],
});
export default router;
