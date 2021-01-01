import Vue from "vue";
import VueRouter from "vue-router";

import TopPage from "./pages/TopPage.vue";
import TagSearch from "./pages/TagSearch.vue";
import Login from "./pages/Login.vue";
import Ranking from "./pages/Ranking.vue";
import UsersList from "./pages/UsersList.vue";
import RankProduct from "./pages/RankProduct.vue";
import UserDetail from "./pages/UserDetail.vue";
import UserSettings from "./pages/UserSettings.vue";
import FollowList from "./pages/FollowList.vue";
import MyDrawing from "./pages/MyDrawing.vue";
import ProductDetail from "./pages/ProductDetail.vue";
import Notification from "./pages/Notification.vue";

import SystemError from "./pages/errors/System.vue";
import NotFound from "./pages/errors/NotFound.vue";

import store from "./store";

Vue.use(VueRouter);

// パスとコンポーネントのマッピング
const routes = [
    {
        path: "/",
        component: TopPage
    },
    {
        path: "/tagsearch",
        component: TagSearch,
        props: route => {
            const page = route.query.page;
            return {
                page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1
            };
        }
    },
    {
        path: "/login",
        component: Login,
        beforeEnter(to, from, next) {
            if (store.getters["auth/check"]) {
                next("/");
            } else {
                next();
            }
        }
    },
    {
        path: "/notification",
        component: Notification,
        beforeEnter(to, from, next) {
            if (store.getters["auth/check"]) {
                next();
            } else {
                next("/login");
            }
        },
        props: true
    },
    {
        path: "/ranking",
        component: Ranking,
        children: [
            {
                path: "users",
                name: "rank-users",
                component: UsersList,
                props: route => {
                    const page = route.query.page;
                    return {
                        page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1
                    };
                }
            },
            {
                path: "product",
                name: "rank-product",
                component: RankProduct,
                props: route => {
                    const page = route.query.page;
                    return {
                        page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1
                    };
                }
            }
        ]
    },
    {
        path: "/users/:id",
        component: UserDetail,
        props: true
    },
    {
        path: "/settings/:id",
        component: UserSettings,
        beforeEnter(to, from, next) {
            if (store.getters["auth/check"]) {
                next();
            } else {
                next("/login");
            }
        },
        props: true
    },
    {
        path: "/follow/:id",
        component: FollowList,
        props: true,
        name: "follow"
    },
    {
        path: "/follower/:id",
        component: FollowList,
        props: true,
        name: "follower"
    },
    {
        path: "/drawing",
        component: MyDrawing,
        beforeEnter(to, from, next) {
            if (!store.getters["auth/check"]) {
                next("/");
            } else {
                next();
            }
        },
        props: route => {
            const page = route.query.page;
            return {
                page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1
            };
        },
        props: true
    },
    {
        path: "/products/:id",
        component: ProductDetail,
        props: true
    },
    {
        path: "/500",
        component: SystemError
    },
    {
        path: "*",
        component: NotFound
    }
];

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;
