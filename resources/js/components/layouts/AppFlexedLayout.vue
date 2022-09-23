<template>
    <div class="flexed-layout">
        <nav-bar/>
        <slot/>
        <app-footer/>
        <g-d-p-r/>
        <p-w-a/>
        <notifications group="planner" classes="n-light" position="bottom right"/>

    </div>
</template>

<script>
import GDPR from "../GDPR";
import PWA from "../PWA";
export default {
    name: "AppFlexedLayout",
    components: {GDPR, PWA}
}
</script>

<style lang="scss">

.layout-centered {
    height: 100%;
    width: 100%;
    justify-content: center;
    display: flex;
    align-items: center;
    flex-direction: column;
}

.flexed-layout {
    display: flex;
    flex-direction: column;
    height: 100%
}

</style>
