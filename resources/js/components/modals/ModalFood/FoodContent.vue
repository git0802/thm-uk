<template>
    <div class="modal-food">
        <create-food/>
        <button-close-modal
            @click="actionsModal({
                name: 'modalFood',
                action: 'close'
            }); clearEditFood()"
        />
    </div>
</template>

<script>
    import { mapMutations, mapActions } from "vuex";
    import CreateFood from "./CreateFood";
    import ButtonCloseModal from "../../buttons/ButtonCloseModal";

    export default {
        components: {
            CreateFood,
            ButtonCloseModal
        },
        methods: {
            ...mapMutations({
                clearEditFood: 'food/clearEditFood',
            }),
            ...mapActions({
                actionsModal: 'modals/actionsModal',
            }),
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../../sass/mixins';

    .modal-food {
        max-width: 1180px;
        width: 100%;
        padding: 90px 115px 85px 115px;
        position: relative;
        background: var(--white);
        border-radius: 12px;
        border: 1px solid #E5E5E5;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);

        @media screen and (max-width: 991px) {
            align-self: flex-start;
            padding: 60px;
        }

        @media screen and (max-width: 600px) {
            padding: 70px 30px 65px 30px;
        }
    }
</style>


