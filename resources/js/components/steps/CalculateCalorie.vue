<template>
    <div class="calories-container">
        <form>
            <div class="calories-container__calculate">
                <select-params class="calories-container__goal"
                               :options="goalList"
                               :placeHolder="'Choose your goal'"
                               :errorMessage="'*Choose your goal'"
                               :disabled="false"
                               :preselect-value="goal.selectedGoal"
                               @validation="calculateCalories"
                />
                <input-disabled class="calories-container__result"
                                :placeHolder="'Calories goal'"
                                :value="initialGoal.calories"
                />
            </div>
            <div class="calories-container__note">
                <ul>
                    Please note
                    <li>
                        The National Institutes of Health recommends no less than 1000 calories for women and 1200
                        calories for men.
                    </li>
                </ul>
            </div>
            <div class="calories-container__next">
                <button-next
                    :isDisabled="isDisabledNextButton"
                    @click="nextStep"
                />
            </div>
        </form>
    </div>
</template>

<script>
import { mapGetters, mapMutations } from "vuex";

export default {
    data() {
        return {
            isDisabledNextButton: true,
            goalList: this.$phrase.goalList,
            initialGoal: {
                calories: '',
                goal: 0,
                selectedGoal: ''
            },
            maintainCalories: 0,
            stepCalories: 250,
            activityFactor: 1.2
        }
    },
    computed: {
        ...mapGetters({
            userParams: 'params/getUserParams',
            goal: 'params/getInitialGoal'
        }),
    },
    created() {
        this.calculateMaintainCalories();
    },
    methods: {
        ...mapMutations({
            setGoal: 'params/setGoal',
        }),
        nextStep() {
            this.setGoal(this.initialGoal);
            this.$emit('next');
        },
        calculateMaintainCalories() {
            const weightKG = (this.$useMetricSystem ? this.userParams.weight : this.convertToKg(this.userParams.weight));
            const heightCM = this.userParams.height;
            const age = this.userParams.age;
            const genderCorrectingValue = this.userParams.gender === 'Male' ? 5 : -161;
            //Mifflin-St Jeor Equation
            this.maintainCalories = Math.round((10 * weightKG + 6.25 * heightCM - 5 * age + genderCorrectingValue) * this.activityFactor);
        },
        calculateCalories(name, state, value) {
            if (state) {
                const MIN_CALORIES_MEN = 1200;
                const MIN_CALORIES_WOMEN = 1000;
                const minCalories = this.userParams.gender === 'Male' ? MIN_CALORIES_MEN : MIN_CALORIES_WOMEN;

                this.initialGoal.selectedGoal = value;

                const index = this.goalList.findIndex((item) => {
                    return item === value;
                });

                if (index !== -1) {
                  let goalListValues = this.$phrase.goalListValues;
                  if (index === 0) {
                        this.initialGoal.calories = this.maintainCalories - 1000;
                    } else if (index === 6) {
                        this.initialGoal.calories = this.maintainCalories + 1000;
                    } else {
                        this.initialGoal.calories = this.maintainCalories + (this.stepCalories * (index - 3));
                    }
                    this.initialGoal.goal = goalListValues[index];
                }

                if (this.initialGoal.calories < minCalories) {
                    this.initialGoal.calories = minCalories;
                }

                this.initialGoal.calories = String(this.initialGoal.calories);

                this.isDisabledNextButton = false;
            }
        }
    }
}
</script>

<style lang="scss">
@import '../../../sass/_mixins.scss';

.calories-container {
    display: flex;
    flex-direction: column;
    max-width: 980px;

    &__calculate {
        display: flex;
        flex-direction: row;
        justify-content: center;
        margin-bottom: 46px;

        @media screen and (max-width: 600px) {
            flex-direction: column;
            margin-bottom: 37px;
        }
    }

    &__note, &__next {
        display: flex;
        flex-direction: row;
        justify-content: center;

        @media screen and (max-width: 600px) {
            flex-direction: column;
        }
    }

    &__note {
        justify-content: start;
        margin-bottom: 46px;

        ul {
            @include head-font;

            li {
                @include base-font;

                opacity: 0.6;
                margin-top: 22px;
                padding-left: 41px;
                position: relative;

                &:before {
                    content: '';
                    display: block;
                    width: 6px;
                    height: 6px;
                    border-radius: 50%;
                    background: var(--primary);
                    position: absolute;
                    left: 13px;
                    top: 10px;
                }
            }
        }
    }

    &__goal {
        flex-basis: 50%;
        margin-right: 20px;

        .v-select {
            .vs__selected, .vs__search {
                font-weight: 300;
            }
        }

        @media screen and (max-width: 600px) {
            margin-right: 0px;
            margin-bottom: 25px;
        }
    }

    &__result {
        flex-basis: 50%;
    }
}
</style>
