<template>
    <modal-container v-if="isActiveModalDish" :style="{ filter: filterValue }"
        :z-index="zIndex"
    >
        <dish-content/>
    </modal-container>
</template>

<script>
    import { modals } from '../../mixins/modals'
    import ModalContainer from "../ModalContainer";
    import DishContent from "./DishContent";

    export default {
        name: "ModalDish",
        components: {
            ModalContainer,
            DishContent
        },
        mixins: [modals],
        computed: {
            isActiveModalDish() {
                return this.findActiveModal('modalDish');
            }
        }
    }
</script>



