<template>
    <div class="modal-review-list">
        <review-list/>
        <button-close-modal
            @click="actionsModal({
                name: 'modalReviewList',
                action: 'close'
            })"
        />
    </div>
</template>

<script>
import { mapActions } from "vuex";
import ReviewList from "./ReviewList";
import ButtonCloseModal from "../../buttons/ButtonCloseModal";

export default {
    components: {
        ReviewList,
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
    }
}
</script>

<style scoped lang="scss">
.modal-review-list {
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

    @media screen and (max-width: 600px) {
        padding: 90px 30px 85px 30px;
    }
}
</style>


