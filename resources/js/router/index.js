import { createRouter, createWebHashHistory } from "vue-router";
import { userAuthStore } from "../stores/userAuth";
import ability from "../services/ability"
const Home    = () => import("../views/home.vue");
const Builder = () => import("../views/builder.vue");
const Login   = () => import("../views/login.vue");

const User    = () => import("../views/admin/user.vue");
const Template    = () => import("../views/admin/template.vue");


const ifAuthenticated = (to, from, next) => {
    const authStore = userAuthStore();
  
    if (!authStore.isAuthenticated) return next("/");
  
    if (to?.meta?.permission) {
      if (!ability.can(to.meta.permission)) return next(false);
    }
    next();
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

        {
            path: "/user",
            name: "User",
            component: User,
            beforeEnter: ifAuthenticated,
            meta: { permission: 'user_view' },
        },
        {
            path: "/template",
            name: "Template",
            component: Template,
            beforeEnter: ifAuthenticated,
            meta: { permission: 'template_view' },
        },

    ],
});
export default router;
