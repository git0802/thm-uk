<template>
    <transition name="fade">
        <div class="finished" :class="{'finished--faded': is_finished || is_future }" v-if="is_finished || is_future " @click="jumpToThisDay">
            <div class="finished__text">
                <template v-if="is_finished">
                    Finished. Click here to jump to Today.
                </template>
                <template v-if="is_future">
                    You cant populate future week. Sorry.
                </template>
            </div>
        </div>
    </transition>
</template>

<script>
    import {mapGetters, mapMutations} from "vuex";
    import moment from 'moment';

    export default {
        name: "Finished",
        computed: {
            ...mapGetters({
                selected_day: 'planner/getSelectedDay',
                selected_week: 'planner/getSelectedWeek',
                current_day:  'planner/getCurrentDay',
            }),
            is_finished: function() {
                return moment(this.selected_day).format('YYYY-MM-DD 00:00:00') < moment().format('YYYY-MM-DD 00:00:00');
            },
            is_future: function() {
                return  !(moment().endOf('isoWeek').format('YYYY-MM-DD 23:59:59') > moment(this.selected_day).format('YYYY-MM-DD 00:00:00'))
            }
        },
        methods: {
            ...mapMutations({
                setCurrentDay: 'planner/setCurrentDay',
                setSelectedWeek: 'planner/setSelectedWeek',
                setSelectedDay: 'planner/setSelectedDay',
            }),
            jumpToThisDay() {
                this.$emit('jumptothisday');
            }
        }
    }
</script>

<style scoped lang="scss">
    .finished {
        background: #292D34;
        box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.2);
        border-radius: 4px;
        display: flex;
        padding: 15px;
        margin: 0 10px;
        cursor: pointer;
        display: none;
        transition: opacity 1s ease-out;
        opacity: 0;
        &--faded {
            opacity: 1;
            display: block;
        }
        &__text {
            font-size: 16px;
            color: white;
        }
    }
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active до версии 2.1.8 */ {
        opacity: 0;
    }

</style>
