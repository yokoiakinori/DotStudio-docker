import Vue from "vue";
import VueRouter from "vue-router";

import TopPage from "./pages/TopPage.vue";
import Search from "./pages/Search.vue";
import TagSearch from "./pages/TagSearch.vue";
import Login from "./pages/Login.vue";
import Ranking from "./pages/Ranking.vue";
import RankUserFollower from "./pages/RankUserFollower.vue";
import RankProductLike from "./pages/RankProductLike.vue";
import RankProductWatch from "./pages/RankProductWatch.vue";
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
        path: "/search",
        component: Search,
        props: route => {
            const page = route.query.page;
            return {
                page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1
            };
        }
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
                component: RankUserFollower,
                props: route => {
                    const page = route.query.page;
                    return {
                        page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1
                    };
                }
            },
            {
                path: "product/like",
                name: "rank-product-like",
                component: RankProductLike,
                props: route => {
                    const page = route.query.page;
                    return {
                        page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1
                    };
                }
            },
            {
                path: "product/watch",
                name: "rank-product-watch",
                component: RankProductWatch,
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
