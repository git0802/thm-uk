<template>
    <div class="container">
        <nav-bar nav-white/>
        <slot/>
        <app-footer v-if="!hideFooter"/>
        <g-d-p-r/>
        <p-w-a/>
        <notifications group="planner" classes="n-light" position="bottom right"/>

    </div>
</template>

<script>
    import {mapActions} from "vuex";
    import GDPR from "../GDPR";
    import PWA from "../PWA";

    export default {
        name: "AppLayout",
        components: {GDPR, PWA},
        props: {
            hideFooter: {
                type: Boolean,
                default: false
            },
            navWhite: {
                type: Boolean,
                default: false
            }
        }
    }
</script>

<style scoped lang="scss">
    .container {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    @media screen and (max-width: 980px) {
        .container {
            height: auto;
            overflow: hidden;
            min-height: 100vh;
        }
    }
</style>
