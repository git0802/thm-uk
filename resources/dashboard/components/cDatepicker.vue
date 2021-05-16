<template>
    <div class="picker__wrapper" style="margin-left: auto;">
        <div class="picker__arrow">
            <svg width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 5L0 0L10 0L5 5Z" fill="#292D34"/>
            </svg>
        </div>

        <vc-date-picker
            :value="selected"
            @input="update"
            :popover="popover"
            :locale="locale"
            title-position="left"
            :color="'purple'"
            :attributes="attributes"
        >
            <input
                type="text"
                class="form-control rounded-0 vc-date__input"
                slot-scope="{ inputProps, inputEvents }"
                v-bind="inputProps"
                v-on="inputEvents"
                readonly
            >
        </vc-date-picker>
    </div>
</template>

<script>
    import {mapGetters, mapMutations} from "vuex";
    import moment from "moment";

    export default {
        name: "cDatepicker",
        props: ['selected_day'],
        data: function() {
            return {
                attributes: [
                    {
                        highlight: {
                            class: 'vcc-date--current',
                            contentClass: 'vcc-date--current',
                        },
                        dates: [
                            new Date(),
                        ],
                    },
                ],
                selected: null,
                locale: {
                    id: 'en',
                    firstDayOfWeek: 2,
                    masks: {weekdays: 'WWW', input: 'MMM. DD, YYYY'}
                },
                popover: {
                    visibility: 'click',
                    placement:  'bottom-end'
                }
            }
        },
        mounted() {
            if(this.selected_day) {
                this.selected = moment(this.selected_day)._d;
            }
        },
        computed: {
            ...mapGetters({
                // selected_day: 'planner/getSelectedDay',
                // selected_week: 'planner/getSelectedWeek',
                // current_day:  'planner/getCurrentDay',
            })
        },
        methods: {
            ...mapMutations({
                // setCurrentDay: 'planner/setCurrentDay',
                // setSelectedWeek: 'planner/setSelectedWeek',
                // setSelectedDay: 'planner/setSelectedDay',
            }),
            update(date) {
                this.$emit('datepickerUpdate', date);
            },
            inputEvents(value) {},
        },
        watch: {
            selected_day: function() {
                this.selected = moment(this.selected_day)._d;
            },
        }
    }
</script>

<style lang="scss">
    .picker {
        &__wrapper {
            display: flex;
            align-items: center;
            position: relative;
        }
        &__arrow {
            position: absolute;
            display: flex;
            margin-right: 8px;
            pointer-events: none;
        }
    }
    .vc-title {
        font-family: Gilroy;
        font-size: 14px !important;

    }
    .vcc-date--current {
        background-color: white;
        color: black !important;
        border: 1px solid black;
        font-weight: 500 !important;
        &:hover {
            background-color: white !important;
        }
    }
    .vc-popover-content {
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.25) !important;
    }
    .vc-date__input {
        font-size: 14px;
        width: 120px;
        padding-left: 20px;
        cursor: pointer;
    }
</style>
