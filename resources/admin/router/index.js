/* eslint-disable no-prototype-builtins */
import Vue from "vue";
import VueRouter from "vue-router";
import Index from "../views/Index";
import AppAdminLayout from "../layouts/AppAdminLayout";
import Customers from "../views/Customers";
import Coupons from "../views/Coupons";
import ShopsProducts from "../views/ShopsProducts";
import ContentVideos from "../views/ContentVideos";
import ContentInspires from "../views/ContentInspires";
import LandingContent from "../views/StaticPages/LandingContent";
import StaticPage from "../views/StaticPage";
import Blogs from "../views/Blogs";
import Stores from "../views/Stores";
import BlogPost from "../views/Blogs";
import CreateSlug from "../views/StaticPages/CreateSlug"
import Presets from "../views/Presets";
import SocialLinks from "../views/SocialLinks";

Vue.use(VueRouter);

const routes = [
    {
        path: '*',
        redirect: { name: 'admin.index' }
    },
    {
        path: '/',
        redirect: { name: 'admin.index' }
    },
    {
        path: '/admin',
        redirect: { name: 'admin.index' }
    },
    {
        path: '/admin',
        name: 'admin',
        component: AppAdminLayout,
        children: [
            {
                name: 'admin.index',
                path: '',
                redirect: { name: 'admin.customers' }
            },
            {
                name: 'admin.customers',
                path: '/admin/customers',
                component: Customers
            },
            {
                name: 'admin.coupons',
                path: '/admin/coupons',
                component: Coupons,
            },
            {
                name: 'admin.stores',
                path: '/admin/stores',
                component: Stores,
            },
            {
                name: 'admin.presets',
                path: '/admin/presets',
                component: Presets
            },
            {
                name: 'admin.content.videos',
                path: '/admin/content/videos',
                component: ContentVideos,
            },
            {
                name: 'admin.content.links',
                path: '/admin/content/links',
                component: SocialLinks,
            },
            {
                name: 'admin.content.static-page',
                path: '/admin/content/static-page',
                redirect: '/admin/content/static-page/landing-content',
                component: StaticPage,
                children: [
                    {
                        name: 'admin.content.static-page.landing-content',
                        path: '/admin/content/static-page/landing-content',
                        alias: '/',
                        component: LandingContent,
                    },
                    {
                        name: 'admin.content.static-page.create-slug',
                        path: '/admin/content/static-page/create-slug',
                        component: CreateSlug,
                    },
                ]
            },
            {
                name: 'admin.content.inspires',
                path: '/admin/content/inspires',
                component: ContentInspires,
            },
            {
                name: 'admin.content.blog',
                path: '/admin/blog',
                component: Blogs,
            }
        ]
    },
];

const router = new VueRouter({
    mode: "history",
    base: process.env.BASE_URL,
    routes,
})

export default router;
