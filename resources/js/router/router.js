import { createRouter, createWebHistory } from "vue-router";
import { authMiddleware } from "../middlewares/auth";
import SignIn from "../pages/auth/SignIn.vue";
import SignUp from "../pages/auth/SignUp.vue";
import Home from "../pages/Home.vue";
import EditPost from "../pages/posts/Edit.vue";
import PageNotFound from "../pages/PageNotFound.vue";
import Query from "../pages/Query.vue";
import KnowledgeBase from "../pages/knowledge_base/KnowledgeBase.vue";
import CreateKB from "../pages/knowledge_base/Create.vue";
import EditKB from "../pages/knowledge_base/Edit.vue";
import ViewKB from "../pages/knowledge_base/View.vue";
import ArchiveKB from "../pages/knowledge_base/TrashBin.vue";
import Profile from "../pages/Profile.vue";
import Account from "../pages/Account.vue";

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
        name: 'edit-post',
        path: '/post/edit/:post_id',
        component: EditPost,
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
        name: 'edit-knowledge-base',
        path: '/knowledge-base/edit/:answer_id',
        component: EditKB,
        meta: { Layout: true, requiresAuth: true }
    },
    {
        name: 'view-knowledge-base',
        path: '/knowledge-base/view/:answer_id',
        component: ViewKB,
        meta: { Layout: true, requiresAuth: true }
    },
    {
        name: 'archive-knowledge-base',
        path: '/knowledge-base/archive',
        component: ArchiveKB,
        meta: { Layout: true, requiresAuth: true }
    },
    {
        name: 'profile',
        path: '/profile',
        component: Profile,
        meta: { Layout: true, requiresAuth: true }
    },
    {
        name: 'account',
        path: '/account',
        component: Account,
        meta: { Layout: true, requiresAuth: true }
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach(authMiddleware);

export default router;