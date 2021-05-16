<template>
    <div class="daily-calories">
        <icon-warning/>
        <div>You have
            <span class="daily-calories__balance">{{ balance.value }} calories</span>
            {{ balance.result }} (You're allowed to exceed by 60 calories).
        </div>
    </div>
</template>

<script>
    import IconWarning from "../../../svg/IconWarning";

    export default {
        components: {IconWarning},
        props: {
            goalCalories: {
                required: true,
                default: 1000
            },
            selectedDay: {
                type: String,
                required: true,
                default: 'Monday'
            },
            isCalculate: {
                type: Boolean,
                required: true,
                default: false
            },
            weeklyCalories: {
                required: true,
                default: 1000
            },
        },
        data() {
            return {
                daysOfTheWeek: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                totalCaloriesPerDay: '',
            }
        },
        computed: {
            balance() {
                if (this.isCalculate) {
                    this.findTotalDayCalories(this.selectedDay);
                    return this.calcBalance();
                }
            }
        },
        methods: {
            findTotalDayCalories(selectedDay) {
                this.daysOfTheWeek.forEach((day) => {
                    if (day === selectedDay) {
                        this.totalCaloriesPerDay = this.weeklyCalories;
                    }
                })
            },
            calcBalance() {
                let difference = 0;
                let value = '';
                let result = '';

                difference = this.goalCalories - this.totalCaloriesPerDay;
                result = difference >= 0 ? ' left for the day.': ' exceeded.';
                value = String(Math.abs(difference));

                if (value.length > 3) {
                    const thousands = value.slice(0, value.length - 3);
                    const hundreds = value.slice(-3);
                    value = `${thousands},${hundreds}`;
                }

                return {
                    value: value,
                    result: result
                }
            },
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../../../sass/mixins';

    .daily-calories {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        line-height: 12px;
        color: var(--black-dark);

        @media screen and (max-width: 767px) {
            text-align: center;
        }

        @media screen and (max-width: 480px) {
            flex-direction: column;
        }

        > div {
            line-height: 1.2;
            padding-left: 4px;
        }

        &__balance {
            color: var(--purple);
        }

        svg {
            min-width: 18px;
            margin-right: 4px;
            @media screen and (max-width: 480px) {
                margin: 0 0 6px 0;
            }
        }
    }
</style>


