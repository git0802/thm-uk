<template>
    <div class="meal-setup meal-setup__navbar--top">
        <div class="meal-setup__wrapper">

            <div class="meal-setup__top">
                <daily-calories
                    :goalCalories="calories_goal"
                    :selectedDay="selectedDay"
                    :isCalculate="isCalculate"
                    :weeklyCalories="sumAllCaloriesPerDay()"
                />
                <button class="btn-goal" v-if="$store.state.auth.guest" @click="actionsModal({
                    name: 'modalRegister',
                    action: 'open'
                })">Change your goal
                </button>
                <button class="btn-goal" v-else @click="actionsModal({
                    name: 'modalWeeklyGoals',
                    action: 'open'
                })">Change your goal
                </button>
            </div>

            <div class="choosing-from">
                <div class="choosing-from__box-top">
                    <span class="meal-setup__title">1. Choosing from:</span>

                    <select-store :stores="stores"/>

                    <div class="choosing-from__box-group">
                        <button @click="deleteStore(selectedStore.id)" v-if="selectedStore.is_custom == true"
                                class="btn-text">
                            <span class="btn-text__title">Delete Store</span>
                            <svg width="14" height="16" viewBox="0 0 14 16" fill="currentColor"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M4.13764 5.51644L5.31334 5.4705L5.56824 12.8856L4.39254 12.9314L4.13764 5.51644Z"/>
                                <path d="M6.41177 5.49347H7.58823V12.9086H6.41177V5.49347Z"/>
                                <path
                                    d="M8.43197 12.8854L8.68686 5.47028L9.86256 5.51625L9.60764 12.9314L8.43197 12.8854Z"/>
                                <path
                                    d="M14 2.40209V3.65534H12.7741L11.8016 15.4283C11.7749 15.7518 11.5206 16 11.2157 16H2.80412C2.49921 16 2.2447 15.7517 2.21824 15.4281L1.24569 3.65534H0V2.40209H14ZM3.34292 14.7467H10.6771L11.5931 3.65534H2.42664L3.34292 14.7467Z"/>
                                <path
                                    d="M5.07843 0H8.92157C9.46216 0 9.90196 0.4685 9.90196 1.04437V3.02872H8.7255V1.25325H5.2745V3.02872H4.09804V1.04437C4.09804 0.4685 4.53784 0 5.07843 0Z"/>
                            </svg>
                        </button>
                        <button-add-item
                            :title="'Add custom food'"
                            @click="actionsModal({
                                name: 'modalFood',
                                action: 'open'
                            }); clearMessages()"
                        />
                    </div>
                </div>


                <search-params
                    @showMessage="showMessage = true"
                    @update="showMessage = false"
                />
                <div class="select-food__message">
                    <warning-message v-if="showMessage"
                                     :message="'You already choose this product'"
                    />
                </div>
            </div>

            <div class="you-chosen">
                <!--<div class="you-chosen__top">
                    <span class="meal-setup__title">2. You’ve chosen <b v-if="choosedFood.length > 0">( {{ choosedFood.length }} items)</b></span>
                </div>-->

                <food-list v-if="myChoosedFood.length"/>

                <!--<div class="you-chosen__btn-group">
                    <button type="button" class="btn btn--primary"
                            @click="deleteFood"
                    >
                        <svg width="10" height="12" viewBox="0 0 10 12" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.03846 1.5H6.92308V1.125C6.92308 0.503672 6.40649 0 5.76923 0H4.23077C3.59351 0 3.07692 0.503672 3.07692 1.125V1.5H0.961538C0.430505 1.5 0 1.91974 0 2.4375V3.1875C0 3.39462 0.172188 3.5625 0.384615 3.5625H9.61539C9.82781 3.5625 10 3.39462 10 3.1875V2.4375C10 1.91974 9.5695 1.5 9.03846 1.5ZM3.84615 1.125C3.84615 0.918281 4.01875 0.75 4.23077 0.75H5.76923C5.98125 0.75 6.15385 0.918281 6.15385 1.125V1.5H3.84615V1.125Z"/>
                            <path d="M0.729904 4.3125C0.661274 4.3125 0.606587 4.36842 0.609856 4.43527L0.927163 10.9284C0.95649 11.5294 1.46274 12 2.07957 12H7.92043C8.53726 12 9.04351 11.5294 9.07284 10.9284L9.39014 4.43527C9.39341 4.36842 9.33873 4.3125 9.2701 4.3125H0.729904ZM6.53846 5.25C6.53846 5.04281 6.71058 4.875 6.92308 4.875C7.13558 4.875 7.30769 5.04281 7.30769 5.25V10.125C7.30769 10.3322 7.13558 10.5 6.92308 10.5C6.71058 10.5 6.53846 10.3322 6.53846 10.125V5.25ZM4.61538 5.25C4.61538 5.04281 4.7875 4.875 5 4.875C5.2125 4.875 5.38462 5.04281 5.38462 5.25V10.125C5.38462 10.3322 5.2125 10.5 5 10.5C4.7875 10.5 4.61538 10.3322 4.61538 10.125V5.25ZM2.69231 5.25C2.69231 5.04281 2.86442 4.875 3.07692 4.875C3.28942 4.875 3.46154 5.04281 3.46154 5.25V10.125C3.46154 10.3322 3.28942 10.5 3.07692 10.5C2.86442 10.5 2.69231 10.3322 2.69231 10.125V5.25Z"/>
                        </svg>
                        <span>DELETE ITEMS</span>
                    </button>
                    <button type="button" class="btn btn--primary"
                            :disabled="choosedFood.length < 2"
                            @click="actionsModal({
                                    name: 'modalDish',
                                    action: 'open'
                                }); clearMessages()"
                    >
                        <span>GROUP ITEMS</span>
                    </button>
                </div>-->

                <!--<div class="food-grouping">
                    <span class="food-grouping__title">Optionally select & group any of the above items together in 1 combined recipe.</span>

                    <food-list-dish/>

                </div>-->
            </div>

            <ApplyPreset @loading="setApplyPresetEmit"/>

            <meal-planner/>

        </div>

        <template>
            <transition name="meal-button">
                <div class="meal-setup__btn-group"
                     v-if="choosedMeal.length >= 1 || isCopy"
                >
                    <button v-if="isCopy === false" @click="groupFood()" type="button" class="meal-setup__btn purple"
                            :disabled="choosedMeal.length < 2"
                    >
                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none" stroke="currentColor">
                            <path d="M4 9.625V13H13V4H9.625"/>
                            <path d="M7 12.625V16H16V7H12.625"/>
                            <rect x="1" y="1" width="9" height="9"/>
                        </svg>
                        <span class="meal-setup__btn-title">Group</span>
                    </button>

                    <button v-if="isCopy === false" @click="copyFood()" type="button" class="meal-setup__btn purple">
                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none" stroke="currentColor">
                            <path d="M4 11.5V16H16V4H11.5" stroke-width="0.5" stroke-dasharray="4 4"/>
                            <rect x="0.5" y="0.5" width="11" height="11"/>
                        </svg>
                        <span class="meal-setup__btn-title">Copy</span>
                    </button>

                    <button  v-if="isCopy === true" @click="cancel()"  type="button" class="meal-setup__btn primary">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor">
                            <path d="M15 1L1 15"/>
                            <path d="M1 1L15 15"/>
                        </svg>
                        <span class="meal-setup__btn-title">Done</span>
                    </button>

                    <button v-if="isCopy === false" @click="deleteFromPlanner" type="button" class="meal-setup__btn primary">
                        <svg width="14" height="16" viewBox="0 0 14 16" fill="currentColor">
                            <path d="M4.13764 5.51644L5.31334 5.4705L5.56824 12.8856L4.39254 12.9314L4.13764 5.51644Z"/>
                            <path d="M6.41177 5.49347H7.58823V12.9086H6.41177V5.49347Z"/>
                            <path
                                d="M8.43197 12.8854L8.68686 5.47028L9.86256 5.51625L9.60764 12.9314L8.43197 12.8854Z"/>
                            <path
                                d="M14 2.40209V3.65534H12.7741L11.8016 15.4283C11.7749 15.7518 11.5206 16 11.2157 16H2.80412C2.49921 16 2.2447 15.7517 2.21824 15.4281L1.24569 3.65534H0V2.40209H14ZM3.34292 14.7467H10.6771L11.5931 3.65534H2.42664L3.34292 14.7467Z"/>
                            <path
                                d="M5.07843 0H8.92157C9.46216 0 9.90196 0.4685 9.90196 1.04437V3.02872H8.7255V1.25325H5.2745V3.02872H4.09804V1.04437C4.09804 0.4685 4.53784 0 5.07843 0Z"/>
                        </svg>
                        <span class="meal-setup__btn-title">Delete</span>
                    </button>
                </div>
            </transition>
        </template>
        <overlay-component v-if="loading"/>

    </div>
</template>

<!--This component is reusable-->

<script>
import vSelect from "vue-select";
import 'vue-select/dist/vue-select.css';
import {mapActions, mapGetters, mapMutations} from "vuex";
import FoodListDish from "../components/modals/ModalPlan/FoodPlanner/FoodListDish";
import MealPlanner from "./modals/ModalPlan/MealPlanner/MealPlanner";
import FoodList from "./modals/ModalPlan/FoodPlanner/FoodList";
import ButtonAddItem from "./buttons/ButtonAddItem";
import WarningMessage from "./messages/WarningMessage";
import SearchParams from "./searchs/SearchParams";
import SelectStore from "./modals/ModalPlan/Selects/SelectStore";
import DailyCalories from "./modals/ModalPlan/MealPlanner/DailyCalories";
import OverlayComponent from "./overlay/OverlayComponent";
import axios from "axios";
import ApplyPreset from "./ApplyPreset";

export default {
    name: "DragDrop",
    props: ['stores'],
    components: {
        ApplyPreset,
        OverlayComponent,
        DailyCalories,
        FoodListDish,
        MealPlanner,
        FoodList,
        ButtonAddItem,
        WarningMessage,
        SearchParams,
        SelectStore,
        'v-select': vSelect,
    },
    data() {
        return {
            loading: false,
            showMessage: false,
            details: null,
            isCalculate: true,
        }
    },
    computed: {
        ...mapGetters({
            planner_id: 'planner/getPlannerId',
            selectedDay: 'planner/getSelectedDailyCaloriesDay',
            choosedMeal: 'food/getChoosedMeal',
            choosedFood: 'food/getChoosedFood',
            myChoosedFood: 'food/getMyFoodChoosed',
            myFood: 'food/getMyFood',
            calories_goal: 'planner/getCaloriesGoal',
            breakfastProducts: 'planner/getBreakfastProducts',
            lunchProducts: 'planner/getLunchProducts',
            dinnerProducts: 'planner/getDinnerProducts',
            snacksProducts: 'planner/getSnacksProducts',
            selectedStore: 'stores/getSelectedStore',
            isCopy: 'food/getIsCopy'
        }),
    },

    created() {
        this.sumAllCaloriesPerDay()
    },
    methods: {
        ...mapActions({
            actionsModal: 'modals/actionsModal',
            clearMessages: 'food/clearMessages',
            deleteFood: 'food/deleteFood',
            deleteStore: 'stores/deleteStore',
            fetchWeekPlan: 'planner/fetchWeekPlan',
        }),
        ...mapMutations({
            deleteFoodId: 'food/deleteFoodId',
            cancelChoose: 'food/cancelChoose',
            cancelChooseMeal: 'food/cancelChooseMeal',
            setIsCopy: 'food/setIsCopy',
            setCopyingEating: 'food/setCopyingEating',
            setCopyingDate: 'food/setCopyingDate',
            setMealIds: 'food/setMealIds',
            setProductIds: 'food/setProductIds',
            clearChoosedFood: 'food/clearChoosedFood',
            clearChoosedMeal: 'food/clearChoosedMeal',
            clearMyFood: 'food/clearMyFood'
        }),
        cancel(){
            this.setIsCopy(false);
            this.setCopyingEating('');
            this.setCopyingDate('');
            this.setMealIds([]);
            this.setProductIds([]);
            // for delete all selected foods
            this.clearChoosedFood();
            this.clearChoosedMeal();
            this.clearMyFood()
        },
        setApplyPresetEmit(value) {
            this.loading = value
        },
        errorsMessages(checkDays, checkEatings, val){
            let text = '';
            if(!checkDays && !checkEatings){
                text = 'You cant ' + val + ' two products from different days and different mealltimes';
            } else if (!checkEatings) {
                text = 'Sorry, you can’t ' + val + ' the food from different mealltimes';
            } else {
                text = 'You cant ' + val + ' two products from different days';

            }
            this.$notify({
                group: 'planner',
                title: `Yay`,
                type: 'error',
                text: text,
                duration: 7500
            });
        },
        copyFood(){
            let meal_ids = [];

            let eatings = [];
            let dates = [];
            this.myFood.forEach((item) => {
                eatings.push(item.eating)
                meal_ids.push(item.meal_id)
                dates.push(item.date)
            })
            let checkEatings = eatings.every((v,i,a)=>v===a[0]);
            let checkDays = dates.every((v,i,a)=>v===a[0]);
            if(checkEatings && checkDays){
                this.setIsCopy(true);
                this.setCopyingDate(dates[0]);
                this.setCopyingEating(eatings[0]);
                this.setMealIds(meal_ids)

            } else {
                this.errorsMessages(checkDays, checkEatings, 'copy')
            }

        },
        groupFood() {
            let eatings = [];
            let dates = [];
            let checkIfDish = false;

            this.myFood.forEach((item, index) => {
                eatings.push(item.eating)
                dates.push(item.date)
                if(this.myFood[index].dish){
                    checkIfDish = true;
                }
            })
            let checkEatings = eatings.every((v,i,a)=>v===a[0]);
            let checkDays = dates.every((v,i,a)=>v===a[0]);
            if(!checkIfDish){
                if(checkEatings && checkDays){
                    this.actionsModal({
                        name: 'modalDish',
                        action: 'open'
                    });
                    this.clearMessages()
                } else {
                    this.errorsMessages(checkDays, checkEatings, 'combine')
                }
            } else {
                this.$notify({
                    group: 'planner',
                    title: `Error`,
                    type: 'error',
                    text: 'Sorry this food is already grouped',
                    duration: 7500
                });
            }

        },
        deleteFromPlanner() {
            this.loading = true

            this.choosedMeal.forEach((item) => {
                axios.delete('/api/meal/' + this.planner_id + '/' + item)
                    .then((response => {
                        this.cancelChooseMeal(item);
                        this.choosedFood.forEach((item) => {
                            this.cancelChoose(item);
                            this.deleteFoodId(item);
                        })

                    }))
                    .catch(function (error) {
                    }).finally(() => {
                    this.loading = false
                    this.fetchWeekPlan();
                });
            })


        },
        sumCalories(data) {
            if (typeof data === 'string') {
                let vm = this
                return vm[data].reduce((acc, curr) => acc + curr.product['calories'] * curr.servings, 0)
            } else {
                if (data.length) {
                    return data.reduce((acc, curr) => acc + curr.product['calories'] * curr.servings, 0)
                }
            }
        },
        sumAllCaloriesPerDay() {
            let sumArray = []
            sumArray.push(this.sumCalories('breakfastProducts'));
            sumArray.push(this.sumCalories('dinnerProducts'));
            sumArray.push(this.sumCalories('lunchProducts'));
            sumArray.push(this.sumCalories('snacksProducts'));

            return sumArray.reduce(function (a, b) {
                return a + b;
            }, 0);
        },
    }
}
</script>
