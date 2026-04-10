import { createRouter, createWebHistory } from "vue-router";
import simpleLayout from "./layouts/Simple.vue";
import { useAuthStore } from "./store/authStore";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/login",
            name: "login",
            component: () => import("./pages/Login.vue"),
            meta: {
                layout: simpleLayout,
            },
        },
        {
            path: "/register",
            name: "register",
            component: () => import("./pages/Register.vue"),
            meta: {
                layout: simpleLayout,
            },
        },
    ],
});

// Authentication guard
router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();

    const isAuthenticated = !!authStore.token;

    const publicRoutes = ["login", "register"];

    if (!publicRoutes.includes(to.name) && !isAuthenticated) {
        return next({ name: "login" });
    }

    next();
});

export default router;
