<template>
    <div class="modal-dish">
        <create-dish/>
        <button-close-modal
            @click="actionsModal({
                name: 'modalDish',
                action: 'close'
            })"
        />
    </div>
</template>

<script>
    import { mapActions } from "vuex";
    import CreateDish from "./CreateDish";
    import ButtonCloseModal from "../../buttons/ButtonCloseModal";

    export default {
        components: {
            CreateDish,
            ButtonCloseModal
        },
        methods: {
            ...mapActions({
                actionsModal: 'modals/actionsModal',
            }),
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../../sass/_mixins.scss';

    .modal-dish {
        width: 1180px;
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


