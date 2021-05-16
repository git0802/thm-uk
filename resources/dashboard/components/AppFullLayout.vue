<template>
    <div style="background: #f7f7f7;overflow: auto;height: 100vh;">
        <tour-template/>
        <div :style="{ 'filter': filterValue }">
            <mcw-overlay/>
            <div class="container__wrapper">
                <app-left-drawer/>
                <div slot="content" class="container__main">
                    <router-view/>
                    <app-footer/>
                    <overlay-component v-if="isLoading"/>
                </div>
                    <right-drawer-container/>

            </div>
        </div>
        <!--modals start-->
        <modal-food/>
        <modal-dish/>
        <modal-store/>
        <modal-weekly-goals/>

        <notifications group="planner" classes="n-light" position="bottom right"/>
        <!--modals end-->
    </div>
</template>

<script>
import OverlayComponent from '../../js/components/overlay/OverlayComponent'
import TheHotMeal from "./icons/TheHotMeal";
import RightDrawerContainer from "./drawers/right/RightDrawerContainer";
import AppFooter from "./AppFooter";
import ModalFood from "../../js/components/modals/ModalFood/ModalFood";
import ModalDish from "../../js/components/modals/ModalDish/ModalDish";
import ModalStore from "../../js/components/modals/ModalStore/ModalStore";
import { mapGetters } from "vuex";
import ModalWeeklyGoals from "../../js/components/modals/ModalWeeklyGoals/ModalWeeklyGoals";

export default {
    name: "AppFullLayout",
    components: {
        ModalWeeklyGoals,
        AppFooter,
        RightDrawerContainer,
        TheHotMeal,
        OverlayComponent,
        ModalFood,
        ModalDish,
        ModalStore
    },
    computed: {
        ...mapGetters({
            isLoading: 'plannerUi/getIsLoading',
            activeModals: 'modals/getActiveModals',
        }),

        filterValue() {
            return this.activeModals.length ? 'blur(4px)' : 'none';
        }
    }
}
</script>

<style scoped>
    .container__main {
        position: relative;
    }
</style>
