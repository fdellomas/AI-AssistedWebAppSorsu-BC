import { createRouter, createWebHistory } from "vue-router";
import { authMiddleware } from "../middlewares/auth";
import SignIn from "../pages/auth/SignIn.vue";
import SignUp from "../pages/auth/SignUp.vue";
import Home from "../pages/Home.vue";
import PageNotFound from "../pages/PageNotFound.vue";
import Query from "../pages/Query.vue";
import KnowledgeBase from "../pages/knowledge_base/KnowledgeBase.vue";
import CreateKB from "../pages/knowledge_base/Create.vue";
import Profile from "../pages/Profile.vue";

const routes = [
    {
        name: 'sign-in',
        path: '/sign-in',
        component: SignIn,
        meta: { Layout: false, requiresGuest: true }
    },
    {
        name: 'sign-up',
        path: '/sign-up',
        component: SignUp,
        meta: { Layout: false, requiresGuest: true }
    },
    {
        path: '/:pathMatch(.*)*',
        component: PageNotFound,
        meta: { Layout: false, requiresAuth: false }
    },
    {
        name: 'home',
        path: '/',
        component: Home,
        meta: { Layout: true, requiresAuth: true }
    },
    {
        name: 'queries',
        path: '/queries',
        component: Query,
        meta: { Layout: true, requiresAuth: true }
    },
    {
        name: 'knowledge-base',
        path: '/knowledge-base',
        component: KnowledgeBase,
        meta: { Layout: true, requiresAuth: true }
    },
    {
        name: 'create-knowledge-base',
        path: '/knowledge-base/create',
        component: CreateKB,
        meta: { Layout: true, requiresAuth: true }
    },
    {
        name: 'profile',
        path: '/profile',
        component: Profile,
        meta: { Layout: true, requiresAuth: true }
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach(authMiddleware);

export default router;