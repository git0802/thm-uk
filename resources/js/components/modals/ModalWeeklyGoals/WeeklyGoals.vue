<template>
    <div class="main-goals">
        <div class="plan">
            <div class="plan__setup"><span>Setup</span></div>
            <div class="plan__insert"><h1>Change your weekly goals</h1></div>
            <div class="params-container">
                <input-user-params
                    class="params-container__input"
                    :name="'current_weight'"
                    :placeHolder="'Your current weight'"
                    :minValue="$phrase.minWeight"
                    :maxValue="$phrase.maxWeight"
                    :errorMessage="`*Enter weight from ${$phrase.minWeight} to ${$phrase.maxWeight}`"
                    @validation="validationParam"
                />
                <select-params
                    class="params-container__select"
                    :name="'goal'"
                    :options="goalList"
                    :placeHolder="'Choose your goal'"
                    :errorMessage="'*Choose your goal'"
                    :disabled="false"
                    @validation="validationSelectParam"
                />
                <input-disabled class="params-container__result"
                                :placeHolder="'Calories goal'"
                                :value="newGoal.calories"
                />
            </div>
            <div class="params-container__btn-block">
                <button-base
                    :text="'Get new weekly plan'"
                    :disabled="isDisabledNextButton"
                    @click="save"
                />
            </div>
        </div>

    </div>
</template>

<script>
import InputUserParams from "../../inputs/InputUserParams";
import SelectParams from "../../selects/SelectParams";
import InputDisabled from "../../inputs/InputDisabled";
import ButtonBase from "../../buttons/ButtonBase";
import {mapGetters, mapActions, mapMutations} from "vuex";
import axios from 'axios';

export default {
    components: {
        InputUserParams,
        SelectParams,
        InputDisabled,
        ButtonBase
    },
    name: "WeeklyGoals",
    data() {
        return {
            isDisabledNextButton: true,
            validationParamsForm: {
                current_weight: false,
                goal: false,
            },
            goalList: this.$phrase.goalList,
            newGoal: {
                calories: '',
                goal: ''
            },
            selectedGoal: '',
            current_weight: '',
            maintainCalories: 0,
            stepCalories: 250,
            activityFactor: 1.2,
        }
    },
    computed: {
        ...mapGetters({
            userParams: 'params/getUserParams',
            user: 'auth/user',
            planner_id: 'planner/getPlannerId',
            validation: 'planner/getValidation',
        })
    },
    methods: {
        ...mapMutations({
           setUserWeight: 'auth/setUserWeight'
        }),
        ...mapActions({
            fetchWeekPlan: 'planner/fetchWeekPlan',
            actionsModal: 'modals/actionsModal',
            fetchStats: 'plannerUi/fetchStats',
            check: 'auth/check'
        }),
        calculateMaintainCalories() {
            const weightKG = (this.$useMetricSystem ? this.current_weight : this.convertToKg(this.current_weight));
            const heightCM = this.user.height;
            const age = this.user.age;
            const genderCorrectingValue = this.user.gender === 'male' ? 5 : -161;

            //Mifflin-St Jeor Equation
            this.maintainCalories = Math.round((10 * weightKG + 6.25 * heightCM - 5 * age + genderCorrectingValue) * this.activityFactor);
        },
        calculateCalories(value) {
            const MIN_CALORIES_MEN = 1200;
            const MIN_CALORIES_WOMEN = 1000;
            const minCalories = this.user.gender === 'male' ? MIN_CALORIES_MEN : MIN_CALORIES_WOMEN;

            const index = this.goalList.findIndex((item) => {
                return item === value;
            });

            if (index !== -1) {
                if (index === 0) {
                    this.newGoal.calories = this.maintainCalories - 1000;
                    this.newGoal.goal = -1;
                } else if (index === 6) {
                    this.newGoal.calories = this.maintainCalories + 1000;
                    this.newGoal.goal = 1;
                } else {
                    this.newGoal.calories = this.maintainCalories + (this.stepCalories * (index - 3));
                    this.newGoal.goal = (index - 3) * 0.25;
                }
            }

            if (this.newGoal.calories < minCalories) {
                this.newGoal.calories = minCalories;
            }

            this.newGoal.calories = String(this.newGoal.calories);
        },
        save() {
            axios.patch('/api/planner/' + this.planner_id + '/goals', {
                weight: this.current_weight,
                goal: this.newGoal.goal,
                calories_goal: this.newGoal.calories
            })
                .then((res) => {
                    this.setUserWeight(this.current_weight);

                    this.fetchWeekPlan();
                    this.fetchStats(this.$http)
                    this.check()
                    this.actionsModal({
                        name: 'modalWeeklyGoals',
                        action: 'close'
                    })
                })
        },
        validationSelectParam(name, state, value) {
            if (state) {
                this.selectedGoal = value;
            } else {
                this.selectedGoal = '';
            }

            this.validationParam(name, state);
        },
        validationParam(name, state, value) {
            for (let key in this.validationParamsForm) {
                if (key === name) {
                    this.validationParamsForm[key] = state;
                }
            }

            if (name === 'current_weight') {
                this.current_weight = value;
            }

            this.validationForm();
        },
        validationForm() {
            const values = Object.values(this.validationParamsForm);
            let isValid = false;

            for (let i = 0; i < values.length; i++) {
                if (!values[i]) {
                    isValid = true;
                    break;
                }
            }

            if (!isValid) {
                this.calculateMaintainCalories();
                this.calculateCalories(this.selectedGoal);
            }

            this.isDisabledNextButton = isValid;
        },
    }
}
</script>

<style  lang="scss">
@import "../../../../sass/mixins";
.main-goals {
    display: flex;
    justify-content: center;
}
.plan {
    margin: 4.25rem 0;
    &__profile {
        @include head-24-font;

        font-weight: 800;
        line-height: 138.18%;
        color: var(--black);
        h1 {
            line-height: 138.18%;
            margin-left: 2.0625rem;
            @media screen and (max-width: 767px) {
                margin-left: 18px;
            }
        }
    }
    &__setup {
        font-weight: 300;
        font-size: 15px;
        line-height: 151.19%;
        position: relative;
        padding-left: 50px;
        margin-bottom: 14px;
        color: var(--primary);
        opacity: 0.6;
        span {
            font-size: 18px;
            line-height: 151.19%;
        }
    }
    &__setup:before {
        content: "";
        display: block;
        position: absolute;
        vertical-align: middle;
        width: 35px;
        height: 1px;
        left: 0px;
        top: 50%;
        background-color: var(--primary-light);
    }
    &__insert {
        max-width: 40.625rem;
        h1 {
            @include title-font;
        }
    }
    button.button {
        max-width: 30rem;
        font-size: 17px;
    }
}
.params-container {
    display: flex;
    flex-direction: column;
    max-width: 30rem;
    margin: 2.6875rem 0;
    .input-container, &__select {
        margin-bottom: 1.6875rem;
    }
    &__btn-block {
        display: flex;
        justify-content: space-between;
    }
}
</style>
