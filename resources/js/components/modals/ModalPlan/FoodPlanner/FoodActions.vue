<template>
    <div class="food-actions">
        <button-food-action class="m-r-32"
            :isDisabled="false"
            :text="'Delete'"
            @click="deleteFood"
        >
            <icon-delete/>
        </button-food-action>
        <button-food-action
            :isDisabled="choosedFood.length < 2"
            :text="'Group food items'"
            @click="actionsModal({
                name: 'modalDish',
                action: 'open'
            }), clearMessages()"
        >
            <icon-create-dish/>
        </button-food-action>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import ButtonFoodAction from "../../../buttons/ButtonFoodAction";
    import IconDelete from "../../../svg/IconDelete";
    import IconCreateDish from "../../../svg/IconCreateDish"

    export default {
        components: {
            ButtonFoodAction,
            IconDelete,
            IconCreateDish
        },
        computed: {
            ...mapGetters({
                choosedFood: 'food/getChoosedFood',
            }),
        },
        methods: {
            ...mapActions({
                actionsModal: 'modals/actionsModal',
                deleteFood: 'food/deleteFood',
                clearMessages: 'dish/clearMessages'
            }),
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../../../sass/mixins';

    .food-actions {
        @include button-food-action;

        display: flex;
        flex-direction: row;
        padding: 18px 16px;
        /*margin-bottom: 56px;*/
    }

    .m-r-32 {
        margin-right: 32px;
    }
</style>


