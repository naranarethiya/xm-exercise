import { createWebHistory, createRouter } from "vue-router";
import home from "./pages/home.vue"

export const routes = [
    {
        name: "dashboard",
        path: "/",
        component: home
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
