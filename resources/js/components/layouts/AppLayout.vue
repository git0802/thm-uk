<template>
    <div class="container">
        <nav-bar/>
        <slot/>
        <app-footer/>
        <g-d-p-r/>
        <notifications group="planner" classes="n-light" position="bottom right"/>

    </div>
</template>

<script>
    import {mapActions} from "vuex";
    import GDPR from "../GDPR";

    export default {
        name: "AppLayout",
        components: {GDPR}
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
