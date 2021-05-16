/* eslint-disable no-prototype-builtins */
import Vue from "vue";
import VueRouter from "vue-router";
import Login from "../views/Login";
import SignUpFree from "../views/SignUpFree";
import GroceryStore from "../views/GroceryStore";
import Landing from "../views/Landing";
import Policy from "../views/Policy";
import FAQ from "../views/FAQ";
import About from "../views/About";
import Support from "../views/Support";
import ProfileActivation from "../views/ProfileActivation";
import CreateWeeklyPlan from "../views/CreateWeeklyPlan";
import Blogs from "../views/Blogs";
import Post from "../views/Post";
import Drag from "../views/Drag";
import verification from "../views/verification";
import CheckEmail from "../views/CheckEmail";
import ResetPassword from "../views/ResetPassword";
import SetNewPassword from "../views/SetNewPassword";
import SlugPage from "../views/SlugPage";
import store from '../../store/index'

Vue.use(VueRouter);

const routes = [
    {
        path: "/pending-verification",
        name: "verification",
        component: verification
    },
    {
        path: "/signup",
        name: "SignUp",
        component: SignUpFree,
        beforeEnter(to, from, next) {
            if (store.getters['auth/authenticated']) {
                window.location = '/meal-planner';
            } else {
                next();
            }
        },
    },
    {
        path: "/login",
        name: "Login",
        component: Login,
        beforeEnter(to, from, next) {
            if (store.getters['auth/authenticated']) {
                window.location = '/meal-planner';
            } else {
                next();
            }
        },
    },
    {
        path: "/store",
        name: "GroceryStore",
        component: GroceryStore,
        beforeEnter(to, from, next) {
            if (!store.getters['auth/authenticated']) {
                next({name: '/login'});
            } else {
                let subscriptionStatus = store.dispatch('auth/checkSubscriptionStatus');
                if(subscriptionStatus == null || subscriptionStatus.is_expired){
                    window.location = '/meal-planner/settings';
                }
                else{
                    next();
                }
            }
        },
    },
    {
        path: "/",
        name: 'Landing',
        component: Landing
    },
    {
        path: "/privacy-policy",
        name: "Policy",
        component: Policy,
    },
    // {
    //     path: "/weekly-plan",
    //     name: 'CreateWeeklyPlan',
    //     component: CreateWeeklyPlan,
    //     beforeEnter(to, from, next) {
    //         if (!store.getters['auth/authenticated']) {
    //             window.location = '/login';
    //         } else {
    //             next();
    //         }
    //     },
    // },
    {
        path: "/about",
        name: "About",
        component: About,
    },    
    {
        path: "/faq",
        name: "FAQ",
        component: FAQ,
    },
    {
        path: "/support",
        name: "Support",
        component: Support,
    },
    {
        path: "/activate-account/:hash",
        name: "ProfileActivation",
        component: ProfileActivation,
    },
    {
        path: "/blog/:taxonomy?",
        name: "Blogs",
        component: Blogs
    },
    {
        path: "/post/:slug?",
        name: "Post",
        component: Post
    },
    {
        path: "/checkEmail",
        name: "Check",
        component: CheckEmail
    },
    {
        path: "/reset-password",
        name: "ResetPassword",
        component: ResetPassword
    },
    {
        path: "/set-new-password/:token",
        name: "SetNewPassword",
        component: SetNewPassword
    },
    {
        path: "/:slug",
        name: "SlugPage",
        component: SlugPage
    },
    {
        path: "*",
        redirect: { name: 'Landing' },
    }
];

const router = new VueRouter({
    mode: "history",
    base: process.env.BASE_URL,
    routes,
    scrollBehavior (to, from, savedPosition) {
        if(to.name !== from.name) {
            if(to.hash) {
                return {
                    selector: to.hash
                }
            } else {
                return { x: 0, y: 0 }

            }
        }

        if(from.hash && (to.hash !== from.hash) && (to.hash === '')) {
            return { x: 0, y: 0 }

        }

        if(to.hash) {
            return {
                selector: to.hash
            }
        }

    },
})

export default router;
