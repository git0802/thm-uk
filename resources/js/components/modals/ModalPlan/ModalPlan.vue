<template>
        <modal-container v-if="isActiveModalPlan" :style="{ filter: filterValue }"
            :zIndex="zIndex"
        >
            <plan-content/>
        </modal-container>

</template>

<script>
    import { modals } from '../../mixins/modals'
    import { Container, Draggable } from "vue-smooth-dnd";
    import PlanContent from "./PlanContent";
    import ModalContainer from "../ModalContainer";


    export default {
        name: "ModalPlan",
        components: {
            Container,
            Draggable,
            ModalContainer,
            PlanContent
        },
        mixins: [modals],
        computed: {
            isActiveModalPlan() {
                return this.findActiveModal('modalPlan');
            }
        }
    }
</script>


