<template>
    <div class="modal-plan">
        <app-caption class="m-b-16"
            :title="'Choose your meal'"
        />
        <select-store/>
        <select-food/>
        <food-list/>
        <food-actions/>
        <meal-planner/>
        <button-close-modal
            @click="actionsModal({
                name: 'modalPlan',
                action: 'close'
            })"
        />
    </div>
</template>

<script>
    import { mapActions } from "vuex";
    import { Container, Draggable } from 'vue-smooth-dnd';
    import AppCaption from "../../common/AppCaption";
    import SelectStore from "./Selects/SelectStore";
    import SelectFood from "./Selects/SelectFood";
    import FoodList from "./FoodPlanner/FoodList";
    import FoodActions from "./FoodPlanner/FoodActions";
    import MealPlanner from "./MealPlanner/MealPlanner";
    import ButtonCloseModal from "../../buttons/ButtonCloseModal";

    export default {
        components: {
            Container,
            Draggable,
            AppCaption,
            SelectStore,
            SelectFood,
            FoodList,
            FoodActions,
            MealPlanner,
            ButtonCloseModal
        },
        created() {
            document.body.style.overflow = 'hidden';
        },
        destroyed() {
            document.body.style.overflow = '';
        },
        methods: {
            ...mapActions({
                actionsModal: 'modals/actionsModal',
            }),
            onDrop() {
            }
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../../sass/mixins';

    .modal-plan {
        max-width: 1180px;
        width: 100%;
        height: auto;
        padding: 90px 90px 64px 90px;
        position: relative;
        background: var(--white);
        border-radius: 12px;
        border: 1px solid #E5E5E5;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        align-self: flex-start;

        @media screen  and (min-width: 768px) and (max-width: 1020px) {
            padding: 60px 30px;
        }

        @media screen and (max-width: 767px) {
            padding: 60px 14px 40px 14px;
        }

        @media screen and (max-width: 480px) {
            margin-bottom: 40px;
        }

    }

    .m-b-16 {
        margin-bottom: 16px;
    }

    .m-b-46 {
        margin-bottom: 46px;
    }
</style>


