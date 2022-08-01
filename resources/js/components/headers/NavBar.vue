<template>
        <div
            class="nav-bar nav-bar__wrapper"
            :class="[classSignin, {'nav-bar__wrapper--sticky': navWhite || stickNav, 'nav-bar__wrapper--menu-opened': menu, 'nav-bar__guest': $store.state.auth.guest && this.$route.path === '/store'}]"
            :style="getNavStyle" >
            <div class="nav-bar__container wrapper">
                <div class="brand"  @click="routerPush">
                    <the-hot-meal-white v-if="$store.state.auth.guest && this.$route.path === '/store'" :svgHeight="'22px'" :svgWidth="'unset'" :textSize="'22px'" />
                    <the-hot-meal v-else :svgHeight="'22px'" :svgWidth="'unset'" :textSize="'22px'" />
                </div>
                <div class="nav-links">
                    <div class="guest-alert" v-if="$store.state.auth.guest && this.$route.path === '/store'">
                        <span v-if="user">ALERT: This is a DUMMY version of the site based on a {{ user.height_in_feet }} male who’s {{ user.weight }} pounds. To customize for YOUR own weight goals, please
                        <router-link :to="{name: 'SignUp'}" @click.native="close()" exact>sign up FREE</router-link>.</span>
                    </div>
                    <div class="right-block">
                        <ul class="links" v-if="site_content">

                            <router-link class="links__item" :active-class="'links__item--active'" :to="{name: 'Landing'}" @click.native="close()" exact>
                                {{ site_content[0].block_name }}
                            </router-link>
                            <a class="links__item" :active-class="'links__item--active'"  @click="anchor({name: 'Landing', hash: '#success'})">
                                Client Success
                            </a>
                            <a class="links__item" :active-class="'links__item--active'"  @click="anchor({name: 'Landing', hash: '#howitworks'})">
                                {{ site_content[1].block_name }}
                            </a>
                            <a class="links__item" :active-class="'links__item--active'" href="/about">
                                About us
                            </a>
                            <a class="links__item" :active-class="'links__item--active'"  @click="anchor({name: 'Landing', hash: '#whyitworks'})">
                                {{ site_content[2].block_name }}
                            </a>
                            <a class="links__item" :active-class="'links__item--active'"  @click="anchor({name: 'Landing', hash: '#benefits'})">
                                Benefits
                            </a>
                            <a class="links__item" href="https://blog.thehotmeal.com">
                                Blog
                            </a>
                        </ul>
                    </div>
                    <div class="login-block">
                        <div v-if="$store.state.auth.authenticated && !$store.state.auth.guest">
                            <a class="auth__item auth__signup" href="/meal-planner">
                                Go to MEAL PLANNER
                            </a>

                            <a class="auth__item" @click="handleLogout">
                                Logout
                            </a>

                        </div>
                        <div v-else>
                            <router-link class="auth__item auth__login" :active-class="'auth__item--active'" :to="{ name: 'Login' }" @click="close()" exact>
                                Log in
                            </router-link>

                            <router-link class="auth__item auth__signup" :active-class="'auth__item--active'" :to="{name: 'SignUp'}" @click.native="close()" exact>
                                GET FREE MEAL PLAN
                            </router-link>
                        </div>
                    </div>

                    <img class="nav-bar__mob-girl" src="/assets/images/girl-menu-desing.svg" alt="">

                    <span class="nav-bar__mob-footer">The Hot Meal. All rights reserved.</span>
                </div>
            </div>

            <div class="nav-bar__menu" @click="menu = !menu">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <guest-banner v-if="$store.state.auth.guest && this.$route.path === '/store'"/>
        </div>


</template>

<script>
import {mapGetters} from "vuex";
import GuestBanner from "./GuestBanner";

export default {
        components: {GuestBanner},
        name: "NavBar",
        props: {
            navWhite: {
                type: Boolean,
                default: false
            }
        },
        data: function() {
            return {
                last_scroll_pos: null,
                current_scroll_pos: null,
                width: null,
                menu: false
            }
        },
        mounted() {
            window.addEventListener('scroll', this.scrollHandler);
            window.addEventListener('resize', this.resizeHandler);
        },
        beforeDestroy() {
            window.removeEventListener('scroll', this.scrollHandler);
            window.removeEventListener('resize', this.resizeHandler);
        },

        computed: {
            ...mapGetters({
                site_content: 'adminUi/getSiteContent',
                user: 'auth/user',
            }),
            stickNav() {
                return this.current_scroll_pos > 2
            },
            getNavStyle() {
                return [
                    {'width': this.width + 'px'},
                    {'z-index': 10},
                    {'position': 'fixed'},
                    {'width': '100%'},
                    {'top': 0}
                ]
            },
            classSignin() {
                let segment = window.location.pathname.split('/');

                return segment[1];
            },
        },
        methods: {
            routerPush() {
                this.$router.push('/').catch(()=>{});
            },
            anchor(path){
                this.close();
                window.location.hash = '';
                this.$router.push(path)
            },
            scrollHandler(event) {
                const currentScrollPosition = window.pageYOffset || document.documentElement.scrollTop
                this.last_scroll_pos = this.current_scroll_pos;
                this.current_scroll_pos = currentScrollPosition;
            },
            resizeHandler(event) {
                this.width = document.body.offsetWidth;
            },
            handleLogout() {
                this.$store.dispatch('auth/signOut', this.$http)

            },
            close(){
                this.menu = false;
            }
        }
    }
</script>

<style scoped lang="scss">
    .wrapper {
        max-width: 1280px;
        padding: 0 50px;
    }
    .nav-bar {
        height: 76px;
        display: flex;
        transform: translate3d(0, 0, 0);
        &.post {
            background: white;
            border-color: #0000001c;
            box-shadow: -3px -15px 20px 0px #000000ba;
        }
        &__guest {
            background-color: black !important;
            .guest-alert {
                color: white;
                font-weight: normal;
                padding-left: 20px;
                text-align: center;
                a {
                  color: #B47EFB;
                }
            }
            a.auth__login {
                color: #FFFFFF !important;
            }
            .nav-bar__container .login-block a.auth__item.auth__signup {
                background-color: #FFFFFF !important;
                color: #000000 !important;
                &:hover {
                  background-color: #CCCCCC !important;
                }
            }
            flex-wrap: wrap;
            height: initial;
            .nav-bar__container {
                padding: 17px 25px;
            }
        }
        &__wrapper {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            border-bottom: 1px solid transparent;
            &--sticky {
                position: fixed;
                left: 0;
                right: 0;
                top: 0;
                background-color: white;
                border-color: #0000001c;
                box-shadow: -3px -15px 20px 0px #000000ba;
                .nav-bar__container .login-block .auth__item.auth__signup {
                    background-color: #ec92af!important;
                    color: white;
                    &:hover {
                        background-color: darken(purple, 25%)!important;

                    }
                }
            }
            &--menu-opened {

            }
        }

        &__container {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            width: 100%;

            .brand {
                cursor: pointer;
                z-index: 6;
            }
            .nav-links {
                position: relative;
                display: flex;
                justify-content: flex-start;
                align-items: center;
                width: 100%;

                .nav-bar__wrapper--menu-opened & {
                    position: absolute;
                    background-color: white;
                    height: 100vh;
                    bottom: 0;
                    top: 0;
                    left: 0;
                    right: 0;
                    padding: 80px 25px 25px 25px;
                    display: flex;
                    flex-direction: column;
                    align-items: flex-start;
                    z-index: 5;
                    width: 100%;

                    .right-block {
                        margin-left: unset !important;
                        width: 100%;
                        .links {
                            display: flex;
                            flex-direction: column;

                            &__item {
                                position: relative;
                                padding-left: 50px;
                                margin-bottom: 17px;
                                padding: 8px 0 8px 50px;
                                font-size: 24px;
                                color: black;
                                opacity: 1;
                                justify-content: flex-start;

                                &:before {
                                    content: "";
                                    display: block;
                                    position: absolute;
                                    vertical-align: middle;
                                    width: 15px;
                                    height: 1px;
                                    left: 0px;
                                    top: 50%;
                                    background-color: var(--purple);
                                }
                                &--active {
                                    color: var(--purple) !important;
                                    &:before {
                                        width: 30px;

                                    }
                                }
                            }
                        }
                    }
                    .login-block {
                        display: flex;
                        flex-direction: column;
                        margin-left: unset !important;
                        color: black;
                        padding: unset !important;
                        align-items: flex-start;
                        width: 100%;
                        .auth {
                            &__item {
                                width: 100%;
                                position: relative;
                                padding-left: 50px;
                                margin-bottom: 14px;
                                font-size: 24px;
                                height: 40px;
                                margin-right: 0!important;
                                font-weight: 800;
                                color: black!important;
                                opacity: 1;
                                justify-content: flex-start;
                                &--active {
                                    color: var(--purple) !important;

                                    &:before {
                                        width: 30px !important;
                                    }
                                }
                                &:before {
                                    content: "";
                                    display: block;
                                    position: absolute;
                                    vertical-align: middle;
                                    width: 15px;
                                    height: 1px;
                                    left: 0px;
                                    top: 50%;
                                    background-color: var(--purple);
                                }

                            }
                            &__signup {
                                background-color: unset;
                                position: relative;
                                background: transparent!important;
                                padding-left: 50px;
                                margin-bottom: 14px;
                                font-size: 24px;
                                opacity: 1;
                                justify-content: flex-start;
                                &:hover {
                                    background-color: unset!important;
                                }
                                &:before {
                                    content: "";
                                    display: block;
                                    position: absolute;
                                    vertical-align: middle;
                                    width: 15px;
                                    height: 1px;
                                    left: 0px;
                                    top: 50%;
                                    background-color: var(--purple);
                                }
                            }
                        }

                    }

                }

                .right-block {
                    margin-left: auto;
                }
                .login-block {
                    margin-left: 44px;
                    position: relative;
                    color: red;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    & > div {
                        display: flex;
                    }
                    .auth {
                        &__item {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            height: 38px;
                            padding: 0 10px;
                            border-radius: 6px;
                            color: #454545;
                            cursor: pointer;
                            font-size: 14px;
                            transition: 250ms;
                            white-space: nowrap;
                            &:not(:last-child) {
                                margin-right: 5px;
                            }
                        }
                        &__login {
                            &:hover {
                                color: #ec92af;
                            }
                        }
                        &__signup {
                            &:hover {
                                background-color: #ec92af!important;
                            }
                            background-color: white;
                        }
                    }
                }
                .links {
                    list-style-type: none;
                    margin: 0;
                    padding: 0;
                    display: flex;
                    &__item {
                        position: relative;
                        color: #0000008c;
                        font-size: 14px;
                        padding: 10px;
                        margin: 0;
                        cursor: pointer;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        border-radius: 4px;
                        transition: 250ms;
                        white-space: nowrap;
                        &:hover {
                            color: var(--primary);
                            opacity: .5;
                        }
                        &--active {
                            opacity: 1!important;
                            color: black !important;
                        }
                    }
                }
            }
        }

    }

    @media screen and (min-width: 1021px) {
        img.nav-bar__mob-girl, .nav-bar__mob-footer {
            display: none;
        }
        .nav-bar__guest .nav-links .right-block{
            display: none;
        }
    }

    @media screen and (max-width: 980px) {
        .nav-bar {
            height: 59px;
            border-color: #0000001c;
            box-shadow: -3px -15px 20px 0px #000000ba;
            background: #fff;

            &__guest {
                 height: initial;
                .nav-bar__container .nav-links {
                    top: 60px;
                }
            }
        }
    }

    @media screen and (max-width: 1020px) {
        .nav-bar {
            &__guest .guest-alert {
                display: none;
            }
            &__container {
                width: auto;
                margin: 0;
                .nav-links {
                    display: none;
                    .nav-bar__wrapper--menu-opened & {
                        display: flex;
                    }
                    .login-block > div {
                        width: 100%;
                        flex-direction: column;
                    }
                }
            }
            &__menu {
                display: flex;
                justify-content: center;
                flex-direction: column;
                margin-left: auto;
                width: 35px;
                height: 1.5em;
                position: relative;
                transform: rotate(0deg);
                transition: 0.5s ease-in-out;
                cursor: pointer;
                z-index: 6;
                margin-right: 25px;
                div {
                    display: block;
                    width: 100%;
                    border-radius: 3px;
                    height: 0.2em;
                    background: #454545;
                    transition: all .3s;
                    position: relative;
                }
                div + div {
                    margin-top: 6px;
                }

                .nav-bar__wrapper--menu-opened & div:nth-child(1) {
                    animation: ease .7s top forwards;
                }

                div:nth-child(1) {
                    animation: ease .7s top-2 forwards;
                }

                .nav-bar__wrapper--menu-opened & div:nth-child(2) {
                    animation: ease .7s scaled forwards;
                }

                div:nth-child(2) {
                    animation: ease .7s scaled-2 forwards;
                }

                .nav-bar__wrapper--menu-opened & div:nth-child(3) {
                    animation: ease .7s bottom forwards;
                }

                div:nth-child(3) {
                    animation: ease .7s bottom-2 forwards;
                }
            }
        }
        .nav-bar__mob-girl {
            position: absolute;
            z-index: -1;
            opacity: .1;
            height: calc(100% - 120px);
            margin-left: 1%;
        }
        .nav-bar__mob-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            padding-bottom: 26px;
            color: rgb(178 125 255 / 0.5);
            font-size: 12px;
        }
    }

    @media screen and (max-width: 1150px) {
        .wrapper {
            padding: 0 25px;
        }
        .nav-bar__container .nav-links .login-block {
            margin-left: auto;
        }
    }

    @media screen and (min-width: 1100px) {
        .nav-bar__container .nav-links .links__item {
            padding: 10px 15px;
        }
    }

    @media screen and (min-width: 1230px) {
        .nav-bar__container .nav-links .links__item {
            padding: 10px 20px;
        }
    }



    // keyframes start

    @keyframes top {
        0% {
            top: 0;
            transform: rotate(0);
        }
        50% {
            top: 11px;
            transform: rotate(0);
        }
        100% {
            top: 11px;
            transform: rotate(45deg);
        }
    }

    @keyframes top-2 {
        0% {
            top: 11px;
            transform: rotate(45deg);
        }
        50% {
            top: 11px;
            transform: rotate(0deg);
        }
        100% {
            top: 0;
            transform: rotate(0deg);
        }
    }

    @keyframes bottom {
        0% {
            bottom: 0;
            transform: rotate(0);
        }
        50% {
            bottom: 7px;
            transform: rotate(0);
        }
        100% {
            bottom: 7px;
            transform: rotate(135deg);
        }
    }

    @keyframes bottom-2 {
        0% {
            bottom: 7px;
            transform: rotate(135deg);
        }
        50% {
            bottom: 7px;
            transform: rotate(0);
        }
        100% {
            bottom: 0;
            transform: rotate(0);
        }
    }

    @keyframes scaled {
        50% {
            transform: scale(0);
        }
        100% {
            transform: scale(0);
        }
    }

    @keyframes scaled-2 {
        0% {
            transform: scale(0);
        }
        50% {
            transform: scale(0);
        }
        100% {
            transform: scale(1);
        }
    }


</style>
