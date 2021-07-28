/* eslint-disable no-prototype-builtins */
import Vue from "vue";
import VueRouter from "vue-router";
import AppFullLayout from "../components/AppFullLayout";
import PlannerMeals from "../views/Planner/PlannerMeals";
import PlannerShoppingList from "../views/Planner/PlannerShoppingList";
import ExerciseVideos from "../views/ExerciseVideos";
import UserProgress from "../views/UserProgress";
import AppFullPlannerLayout from "../components/AppFullPlannerLayout";
import Support from "../views/Support";
import Home from "../views/Home";
import Settings from "../views/Settings";
import PlannerSetup from "../views/PlannerSetup";
import store from "../../store";

Vue.use(VueRouter);

const routes = [
    {
        path: "*",
        redirect: { name: 'dashboard.planner.meals' }
    },
    {
        path: '/meal-planner/home',
        name: 'Home',
        component: Home
    },
    {
        path: "/meal-planner/planner",
        name: "dashboard",
        component: AppFullLayout,

        children: [
            {
                name:  'dashboard.planner',
                path: '',
                component: AppFullPlannerLayout,
                beforeEnter(to, from, next) {
                    if (!store.getters['auth/authenticated']) {
                        window.location = '/login';
                    } else {
                        let user = store.getters['auth/user'];
                        if (user && !user.finished_setup && !user.is_admin) {
                            window.location = '/store';
                        }
                        let subscriptionStatus = store.getters['auth/getSubscriptionStatus'];
                        if(subscriptionStatus == null || subscriptionStatus.is_expired){
                            next({name: 'dashboard.settings'});
                        }
                        else{
                            next();
                        }
                    }
                },
                children: [
                    {
                        name: 'dashboard.planner.meals',
                        path: '',
                        props: { title: 'Meal Planner' },
                        component: PlannerMeals,
                    },
                    // {
                    //     name: 'dashboard.planner.shopping',
                    //     props: { title: 'Review Your Shopping List for your Meal Plan' },
                    //     component: PlannerShoppingList
                    // },
                ]
            },
            {
                name: 'dashboard.planner.shopping',
                path: '/meal-planner/shopping',
                props: { title: 'Review Your Shopping List for your Meal Plan' },
                component: PlannerShoppingList,
                beforeEnter(to, from, next) {
                    if (!store.getters['auth/authenticated']) {
                        window.location = '/login';
                    } else {
                        let subscriptionStatus = store.getters['auth/getSubscriptionStatus'];
                        if(subscriptionStatus == null || subscriptionStatus.is_expired){
                            next({name: 'dashboard.settings'});
                        }
                        else{
                            next();
                        }
                    }
                },
            },
            {
                name: 'dashboard.videos',
                path: '/meal-planner/videos',
                component: ExerciseVideos,
                beforeEnter(to, from, next) {
                    if (!store.getters['auth/authenticated']) {
                        window.location = '/login';
                    } else {
                        let subscriptionStatus = store.getters['auth/getSubscriptionStatus'];
                        if(subscriptionStatus == null || subscriptionStatus.is_expired){
                            next({name: 'dashboard.settings'});
                        }
                        else{
                            next();
                        }
                    }
                },
            },
            {
                name: 'dashboard.setup',
                path: '/meal-planner/setup',
                component: PlannerSetup,
                beforeEnter(to, from, next) {
                    if (!store.getters['auth/authenticated']) {
                        window.location = '/login';
                    } else {
                        let subscriptionStatus = store.getters['auth/getSubscriptionStatus'];
                        if(subscriptionStatus == null || subscriptionStatus.is_expired){
                            next({name: 'dashboard.settings'});
                        }
                        else{
                            next();
                        }
                    }
                },
            },
            {
                name: 'dashboard.progress',
                path: '/meal-planner/progress',
                component: UserProgress,
                beforeEnter(to, from, next) {
                    if (!store.getters['auth/authenticated']) {
                        window.location = '/login';
                    } else {
                        let subscriptionStatus = store.getters['auth/getSubscriptionStatus'];
                        if(subscriptionStatus == null || subscriptionStatus.is_expired){
                            next({name: 'dashboard.settings'});
                        }
                        else{
                            next();
                        }
                    }
                },
            },
            {
                name: 'dashboard.support',
                path: '/meal-planner/support',
                component: Support,
            },
            {
                name: 'dashboard.settings',
                path: '/meal-planner/settings',
                component: Settings,
                beforeEnter(to, from, next) {
                    next();
                },
            },

        ]
    }
];

const router = new VueRouter({
    mode: "history",
    base: process.env.BASE_URL,
    routes,

});

export default router;
