<template>
    <modal-container v-if="isActiveModalWeeklyGoals" :style="{ filter: filterValue }"
                     :zIndex="zIndex"
    >
        <WeeklyGoalsContent/>
    </modal-container>
</template>

<script>
import {modals} from '../../mixins/modals'
import ModalContainer from "../ModalContainer";
import WeeklyGoalsContent from "./WeeklyGoalsContent";
export default {
    name: "ModalWeeklyGoals",
    components: {WeeklyGoalsContent, ModalContainer},
    mixins: [modals],
    computed: {
        isActiveModalWeeklyGoals() {
            return this.findActiveModal('modalWeeklyGoals');
        }
    }
}
</script>


