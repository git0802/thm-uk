<template>
    <modal-container v-if="isActiveModalStore" :style="{ filter: filterValue }"
                     :zIndex="zIndex"
    >
        <store-content/>
    </modal-container>
</template>

<script>
import { modals } from '../../mixins/modals'
import ModalContainer from "../ModalContainer";
import StoreContent from "./StoreContent";

export default {
    name: "ModalStore",
    components: {
        ModalContainer,
        StoreContent
    },
    mixins: [modals],
    computed: {
        isActiveModalStore() {
            return this.findActiveModal('modalStore');
        }
    }
}
</script>
