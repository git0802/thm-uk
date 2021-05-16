<template>
    <div style="display: flex; flex-direction: column; z-index: 10; position: relative" :style="drawerStyle">
        <div style="position: sticky; top: 0;">
            <app-right-drawer-buttons/>
            <app-right-drawer />
        </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    export default {
        name: "RightDrawerContainer",
        computed: {
            ...mapGetters({
                right_drawer: "plannerUi/getRightDrawer",
                is_mobile: "plannerUi/getMobile"
            }),
            drawerStyle() {
                return [
                    {'width': this.right_drawer ? 280 + 'px' : (this.is_mobile ? 0 + 'px' : 50 + 'px')},
                ]
            }
        }
    }
</script>

<style scoped>

</style>
