<template>
    <div class="modal-food">
        <modal-container v-if="isActiveModalFood" :style="{ filter: filterValue }"
                         :zIndex="zIndex"
        >
            <food-content/>
        </modal-container>
    </div>
</template>

<script>
    import { modals } from '../../mixins/modals'
    import ModalContainer from "../ModalContainer";
    import FoodContent from "./FoodContent";

    export default {
        name: "ModalFood",
        components: {
            FoodContent, 
            ModalContainer
        },
        mixins: [modals],
        computed: {
            isActiveModalFood() {
                return this.findActiveModal('modalFood');
            }
        }
    }
</script>


