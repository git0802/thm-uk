<template>
    <div style="margin-top: 2.5em">
        <div class="plan__insert">
            <h1>
                To get started on your new weekly plan, please insert your new weight
            </h1>
        </div>

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
                @click="updateGoals"
            />
        </div>
    </div>
</template>

<script>
import moment from "moment";
import ButtonBase from "../../js/components/buttons/ButtonBase";
import SelectParams from "../../js/components/selects/SelectParams";
import InputDisabled from "../../js/components/inputs/InputDisabled";
import InputUserParams from "../../js/components/inputs/InputUserParams";
import {mapGetters} from "vuex";

export default {
    name: "NeedToUpdate",
    components: {
        ButtonBase, SelectParams, InputDisabled, InputUserParams
    },
    data: function() {
        return {
            tryToSave: false,

            isLoading: false,
            today: moment().format('DD MMMM YYYY'),
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
            finished_setup: null,
            currentStep: 1,
            stepperContent: [
                {
                    content: '01',
                },
                {
                    content: '02',
                },
                {
                    content: '03',
                }
            ]
        }
    },
    computed: {
        ...mapGetters({
            user: 'auth/user',
            planner_id: 'planner/getPlannerId'
        }),
    },
    methods: {
        async updateGoals() {
            try {
                let res = await this.$http.patch('/api/planner/' + this.planner_id + '/goals', {
                    weight: this.current_weight,
                    goal: this.newGoal.goal,
                    calories_goal: this.newGoal.calories,
                    need_to_update: false
                })

                if(res.data.success) {
                    window.location = '/meal-planner/setup'
                }
            } catch (e) {

            }
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

            if(!isValid) {
                this.calculateMaintainCalories();
                this.calculateCalories(this.selectedGoal);
            }

            this.isDisabledNextButton = isValid;
        },
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
    }


}
</script>

<style scoped>

</style>
