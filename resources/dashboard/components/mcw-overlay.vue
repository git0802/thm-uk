<template>
    <div v-if="overlay" class="mcw-overlay" :class="{hey: overlay}"></div>
</template>

<script>
    import { mapGetters, mapMutations } from 'vuex';

    export default {
        name: "mcw-overlay",
        computed: {
            ...mapGetters({
                right_drawer: "plannerUi/getRightDrawer",
                left_drawer: "plannerUi/getLeftDrawer",
                is_mobile: "plannerUi/getMobile",
                overlay: "plannerUi/getOverlay"
            }),
        },
        methods: {
            ...mapMutations({
                // handleLeftDrawer: 'plannerUi/handleLeftDrawer',
                handleOverlay: 'plannerUi/handleOverlay',
                setOverlay: 'plannerUi/setOverlay',
            }),
        },
        watch: {
            left_drawer(newValue, oldValue) {
                if(this.is_mobile) {
                    this.setOverlay(newValue)
                }
            },
            right_drawer(newValue, oldValue) {
                if(this.is_mobile) {
                    this.setOverlay(newValue)
                }
            },
            is_mobile(newVale, oldValue) {

            },
            overlay(newVal, oldVal) {
                let body = document.body;
                newVal ?
                    body.style.overflow = 'hidden' :
                    body.removeAttribute('style');
            },
        }
    }
</script>

<style scoped lang="scss">
    .mcw-overlay {
        position: fixed;
        display: none;
        /*height: 100vh;*/
        /*width: 100vw;*/
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0,0,0,0.5);
        z-index: 7;
        &.hey {
            display: block;
        }
    }

</style>
