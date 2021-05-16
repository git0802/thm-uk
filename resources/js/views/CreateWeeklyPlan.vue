<template>
    <div style="overflow: auto;height: 100vh;">
        <app-layout :style="{ 'filter': filterValue }">
            <overlay-component v-if="isLoading" />

            <div  class="wrapper wrapper__padded wrapper__break">

                <template v-if="currentStep === 0">
                    <div class="plan">
                        <div class="plan__profile flex items-center justify-between" style="margin-left: 6px;">
                            <div class="flex items-center">
                                <userpic style="pointer-events: none" />
                                <h1>Hi <span class="purple">{{ user.name }}</span>, let's add a new weekly plan!</h1>
                            </div>
                            <div class="todays-date">
                                <h5>Today’s date: <span>{{ today }}</span></h5>
                            </div>
                        </div>
                        <div class="plan__setup"><span>Setup</span></div>
                        <div class="plan__insert"><h1>To get started on your new weekly
                            plan, please insert your new weight</h1></div>
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
                                @click="openModalPlan"
                            />
<!--                            <button-base-->
<!--                                :text="'I just want to update my groceries'"-->
<!--                                v-if="finished_setup == true"-->
<!--                                @click="skipWeeklyPlan"-->
<!--                            />-->
                        </div>
                    </div>
                </template>

                <template v-if="currentStep === 1">
                    <drag-drop :stores="$store.state.stores.stores" />

                    <div class="meal-planner__save">
                        <button-base
                            :text="'Save'"
                            @click="nextStep()"
                        />
                    </div>
                    <div v-if="tryToSave && notValidDays.length > 0" class="meal-planner__validation">
            <span >You do not have a valid planner. Check back the following days: <span
                v-for="days in notValidDays">{{ days + '; ' }}</span></span>
                    </div>

                </template>

                <template v-if="currentStep === 2">
                    <review-list/>
                </template>


            </div>


        </app-layout>
        <modal-plan/>
        <modal-store/>
        <modal-food/>
        <modal-dish/>
        <modal-weekly-goals/>
    </div>
</template>

<script>
import GroceryStore from "./GroceryStore";
import { mapActions, mapGetters, mapMutations } from "vuex";
import axios from 'axios';
import moment from "moment";
import Userpic from "../../dashboard/components/user/Userpic";
import DragDrop from "../components/DragDrop";
import ReviewList from "../components/modals/ModalReviewList/ReviewList";
import ModalWeeklyGoals from "../components/modals/ModalWeeklyGoals/ModalWeeklyGoals";

export default {
    name: "CreateWeeklyPlan",
    components: {ModalWeeklyGoals, DragDrop, Userpic, GroceryStore, ReviewList},
    data() {
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
            currentStep: 0,
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
            activeModals: 'modals/getActiveModals',
            userParams: 'params/getUserParams',
            user: 'auth/user',
            planner_id: 'planner/getPlannerId',
            validation: 'planner/getValidation',
            notValidDays: 'planner/getNotValidationDays',
        }),

        filterValue() {
            return this.activeModals.length ? 'blur(4px)' : 'none';
        }
    },
    created() {
        this.checkIsFinishedSetup();
        this.fetchWeekPlan(moment().format('YYYY-MM-DD 00:00:00'))
        this.$store.dispatch('stores/getStoresList')
    },
    methods: {
        ...mapMutations({
           setUserWeight: 'auth/setUserWeight'
        }),
        ...mapActions({
            actionsModal: 'modals/actionsModal',
        }),
        skipWeeklyPlan(){
            this.storeComponent = true;
        },
        checkIsFinishedSetup(){
            if(this.user.finished_setup === 0){
                window.location.href = '/store'
            }
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
        openModalPlan() {
            axios.patch('/api/planner/' + this.planner_id + '/goals', {
                weight: this.current_weight,
                goal: this.newGoal.goal,
                calories_goal: this.newGoal.calories
            }).then((res) => {
                this.setUserWeight(this.current_weight);

                this.currentStep = 1;
            })
        },
        nextStep() {
            if(!this.validation.is_valid){
                this.tryToSave = true;
            } else {
                this.currentStep++;
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
                this.current_weight = this.setWeight(value);
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
        async fetchWeekPlan(date) {
            this.isLoading = true;
            const response = await axios.get(`/api/planner`, {
                params: {
                    date: date
                }
            });
            this.finished_setup = response.data.data.finished_setup
            this.$store.commit('planner/setPlannerId', response.data.data.id);
            this.$store.commit('planner/setFinishedSetup', response.data.data.finished_setup);
            this.isLoading = false;
            return response.data;

        },
    }
}
</script>

<style scoped lang="scss">
@import "../../sass/mixins";
    .wrapper {
        min-height: calc(100vh - 126px);
        position: relative;
    }
    .justify-between {
        justify-content: space-between;
    }
    .items-center {
        align-items: center;
    }
    .flex {
        display: flex;
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
            margin-top: 4.3125rem;
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
    button {
        font-weight: 800;
        font-size: 17.4545px;
        font-style: normal;
        line-height: 132.19%;
        color: var(--white);
        background: var(--primary);
        border-radius: 7px;
        padding: 13px 51px 12px;
        box-shadow: 0px 8px 16px rgba(223, 44, 101, 0.24);
    }
    .button:hover {
        background: var(--primary-dark);
    }
    .todays-date {
        @include head-14-font;

        font-style: normal;
        font-weight: 300;
        line-height: 138.18%;
        text-align: right;
        padding-left: 20px;
        color: #292D34;
    }
    .purple {
        color: #B47EFB;
    }
    .modal {
        position: fixed;
        top: 0;
        z-index: 9;
    }
    @media screen and (max-width: 980px) {
        .plan__profile {
            align-items: flex-start!important;
            flex-direction: column-reverse;
        }
        .todays-date {
            margin-bottom: 16px;
        }
        .wrapper {
            padding-top: 59px!important;
        }
    }
</style>
