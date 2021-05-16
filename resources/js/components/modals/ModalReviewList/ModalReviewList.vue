<template>
    <modal-container v-if="isActiveModalReviewList" :style="{ filter: filterValue }"
                     :zIndex="zIndex"
    >
        <ReviewListContent/>
    </modal-container>
</template>

<script>
import { modals } from '../../mixins/modals'
import ModalContainer from "../ModalContainer";
import ReviewListContent from "./ReviewListContent";

export default {
    name: "ModalReviewList",
    components: {
        ModalContainer,
        ReviewListContent
    },
    mixins: [modals],
    computed: {
        isActiveModalReviewList() {
            return this.findActiveModal('modalReviewList');
        }
    }
}
</script>


