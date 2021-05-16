<template>
    <div class="meal-planner">
        <h3>2. Drag your foods into the meal planner below</h3>
        <div class="meal-planner__select-day" :ref="`parent_tabs`">
            <div v-for="(day, key) in meals" :ref="`ref_${day.day}`" :key="day.day">
                <button-select-day
                    :selectedDay="selectedDay"
                    :name="day.day"
                    :dkey="key"
                    @select="selectDayOfTheWeek(day.day, key)"
                />
            </div>
            <div class="meal-planner__select-underline" :ref="`underline`" :style="getUnderlineStyles"></div>
        </div>
        <div
            v-if="meals"
            class="meal-planner__type">
            <div class="meal-planner__header">
                <div class="meal-planner__header--box">
                    <span class="meal-planner__title-day">All day</span>
                    <template v-if="!myFood.length">
                        <button @click="copyAllDay" v-if="!isCopy && lengthAllProducts() > 0"
                                class="meal-setup__button">
                            <svg width="17" height="17" viewBox="0 0 17 17" fill="none" stroke="currentColor"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 11.5V16H16V4H11.5" stroke-width="0.5" stroke-dasharray="4 4"/>
                                <rect x="0.5" y="0.5" width="11" height="11"/>
                            </svg>
                            <span class="meal-setup__button-text">Copy</span>
                        </button>

                        <button v-if="checkCopied() && this.selectedDayNumber === this.copying_date"
                                class="meal-setup__button copied">
                            <svg width="18" height="13" viewBox="0 0 18 13" stroke="currentColor" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 6.78947L5.33333 12L14 1"/>
                                <path d="M4 6.78947L8.33333 12L17 1"/>
                            </svg>
                            <span class="meal-setup__button-text">Copied</span>
                        </button>

                        <button v-if="checkCopied() && this.selectedDayNumber !== this.copying_date"
                                @click="pasteItem()"
                                class="meal-setup__button">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 2C9.5 2 8 5 8 14M8 14L12 10M8 14L4 10"/>
                            </svg>
                            <span class="meal-setup__button-text">Paste</span>
                        </button>
                    </template>
                </div>
                <div class="meal-planner__header--box">
                    <div class="meal-planner__basis20">Total Calories</div>
                    <div class="meal-planner__basis20">Avg cost</div>
                </div>
            </div>
            <div class="meal-planner__day-wrap">
                <div class="meal-planner__breakfast"
                >
                    <meal-type
                        :title="'Breakfast'"
                        :totalCost="Math.ceil((cost(breakfastProducts))*100)/100"
                        :totalCalories="Math.ceil(sumCalories(breakfastProducts)*100)/100"
                        :products="breakfastProducts"
                        :isLoading="isLoading"
                        v-on:fooddrop="testEmit"
                    />
                </div>
                <div class="meal-planner__lunch"

                >
                    <meal-type
                        :title="'Lunch'"
                        :totalCost="Math.ceil((cost(lunchProducts))*100)/100"
                        :totalCalories="Math.ceil(sumCalories(lunchProducts)*100)/100"
                        :products="lunchProducts"
                        :isLoading="isLoading"
                        v-on:fooddrop="testEmit"

                    />
                </div>
                <div class="meal-planner__dinner"

                >
                    <meal-type
                        :title="'Dinner'"
                        :totalCost="Math.ceil((cost(dinnerProducts))*100)/100"
                        :totalCalories="Math.ceil(sumCalories(dinnerProducts)*100)/100"
                        :products="dinnerProducts"
                        :isLoading="isLoading"
                        v-on:fooddrop="testEmit"

                    />
                </div>
                <div class="meal-planner__snacks"

                >
                    <meal-type
                        :title="'Snacks'"
                        :totalCost="Math.ceil((cost(snacksProducts))*100)/100"
                        :totalCalories="Math.ceil(sumCalories(snacksProducts)*100)/100"
                        :products="snacksProducts"
                        :isLoading="isLoading"
                        v-on:fooddrop="testEmit"

                    />
                </div>
            </div>
        </div>
        <overlay-component v-if="isFetching"/>
    </div>
</template>

<script>
import ModalWeeklyGoals from "../../ModalWeeklyGoals/ModalWeeklyGoals";
import {mapActions, mapGetters, mapMutations} from "vuex";
import moment from 'moment';
import axios from "axios";
import vueCustomScrollbar from 'vue-custom-scrollbar';
import OverlayComponent from "../../../overlay/OverlayComponent";
import MealType from "./MealType";
import DailyCalories from "./DailyCalories";
import ButtonSelectDay from "../../../buttons/ButtonSelectDay";

export default {
    components: {
        DailyCalories,
        MealType,
        OverlayComponent,
        vueCustomScrollbar,
        ButtonSelectDay,
        ModalWeeklyGoals
    },
    data() {
        return {
            meal_ids: [],
            isFetching: false,
            isLoading: '',
            selectedDay: '',
            quantity: 0,
            selectedDayNumber: '',
            copying_date: '',
            isCalculate: true,
            f: false,
            settingsScroll: {
                suppressScrollX: true,
            },
            underline: {
                width: 0,
                left: 0,
                right: 0,
                center: 0,
            },
            length_all_products: null
        }
    },
    computed: {
        ...mapGetters({
            isCopy: 'food/getIsCopy',
            myFood: 'food/getMyFood',
            dragStartFood: 'food/dragStartFood',
            planner_id: 'planner/getPlannerId',
            selectedDayHoursNumber: 'planner/getSelectedDay',
            meals: 'planner/getMeals',
            calories_goal: 'planner/getCaloriesGoal',
            validation: 'planner/getValidation',
            notValidDays: 'planner/getNotValidationDays',
            breakfastProducts: 'planner/getBreakfastProducts',
            lunchProducts: 'planner/getLunchProducts',
            dinnerProducts: 'planner/getDinnerProducts',
            snacksProducts: 'planner/getSnacksProducts',
            copying_eating: 'food/getCopyingEating',
        }),
        all_eatings(){
            return [this.breakfastProducts, this.dinnerProducts, this.lunchProducts, this.snacksProducts]
        },
        getUnderlineStyles() {
            return [
                {'width': `${this.underline.width}px`},
                {'left': `${this.underline.left}px`},
            ]
        }
    },
    watch: {
        meals(newMeals) {
            this.setMeals(newMeals);
            this.updatedFoodsArrays()
        },
    },
    mounted() {

    },
    methods: {
        copyAllDay() {
            this.meal_ids = [];
            this.length_all_products = [];
            this.setIsCopy(true);
            this.setCopyingEating('all');
            this.copying_date = this.selectedDayNumber;

            for (let i = 0; i < this.all_eatings.length; i++){
                for(let j = 0; j < this.all_eatings[i].length; j++){
                    this.meal_ids.push(this.all_eatings[i][j].id)
                    this.length_all_products = this.all_eatings[i][j].length
                }
            }

        },
        lengthAllProducts(){
            let temporary = []
            for (let i = 0; i < this.all_eatings.length; i++){
                for(let j = 0; j < this.all_eatings[i].length; j++){
                    temporary.push(this.all_eatings[i][j])
                }
            }
            return temporary.length
        },
        checkCopied() {
            return this.isCopy && this.copying_eating === 'all';
        },
        async pasteItem() {
            this.isFetching = true
            try {
                let data = {
                    case: 2,
                    meal_ids: this.meal_ids,
                    date: this.selectedDayNumber
                }
                let res = await axios.post('/api/meal/clone/' + this.planner_id, data)
                this.$notify({
                    group: 'planner',
                    title: 'Yay!',
                    type: 'success',
                    text: `Product(s) pasted. <b>` + res.data.data.created + '</b> item(s) - created, <b>' + res.data.data.dupes + '</b> item(s) - duplicates'
                });
                await this.fetchWeekPlan();
            } catch (e) {
            }
            this.isFetching = false
        },
        saveButton() {
            this.actionsModal({
                name: 'modalReviewList',
                action: 'open'
            })
        },
        nextStep() {
            this.$emit('next');
        },
        cost(data) {
            if (typeof data === 'string') {
                let vm = this
                return vm[data].reduce((acc, curr) => acc + curr.product['cost'], 0)
            } else {
                if (data.length) {
                    return data.reduce((acc, curr) => acc + curr.product['cost'], 0)
                }
            }
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
        ...mapMutations({
            setMeals: 'planner/setMeals',
            setPlannerId: 'planner/setPlannerId',
            setSelectedDay: 'planner/setSelectedDay',
            setValidation: 'planner/setValidation',
            clearNotValidationDays: 'planner/clearNotValidationDays',
            setNotValidationDays: 'planner/setNotValidationDays',
            clearArrays: 'planner/clearEatingArrays',
            addToEating: 'planner/addToEating',
            setSelectedDailyCaloriesDay: 'planner/setSelectedDailyCaloriesDay',
            setIsCopy: 'food/setIsCopy',
            setCopyingEating: 'food/setCopyingEating',
        }),
        async fetchWeekPlan() {
            this.isFetching = true;
            await this.$store.dispatch('planner/fetchWeekPlan');
            // await this.$store.dispatch('planner/validateMealPlanner');
            this.isFetching = false;
        },
        ...mapActions({
            actionsModal: 'modals/actionsModal',
        }),
        calculateStyle(key) {
            let parent = this.$refs[`parent_tabs`].getBoundingClientRect();
            let day = this.$refs[`ref_${key}`][0].getBoundingClientRect();
            let underline = this.$refs[`underline`].getBoundingClientRect();

            this.underline.width = day.width;
            this.underline.left = Math.abs(parent.left - day.left);
            this.underline.right = day.right;
            this.underline.center = (this.underline.width / 2);
        },
        async fetchAddToPlanner(product_id, eating) {
            if (product_id) {
                this.isLoading = eating;
                try {
                    let res = await axios.put('/api/meal/' + this.planner_id + '/' + product_id, {
                        servings: 1,
                        date: this.selectedDayNumber,
                        eating: eating
                    })
                    await this.fetchWeekPlan();
                    this.isLoading = '';
                } catch (e) {
                }
            }
        },
        async init() {
            let currentDay = moment();
            await this.fetchWeekPlan();
            this.selectDayOfTheWeek(currentDay.format('dddd'), currentDay.format('YYYY-MM-DD'));
        },
        updatedFoodsArrays() {
            this.clearArrays()
            if (this.meals[this.selectedDayNumber]) {
                let meal = this.meals[this.selectedDayNumber].meals;
                for (let i = 0; i < meal.breakfast.length; i++) {
                    this.addToEating({eating: 'breakfastProducts', data: meal.breakfast[i]})
                }
                for (let i = 0; i < meal.lunch.length; i++) {
                    this.addToEating({eating: 'lunchProducts', data: meal.lunch[i]})
                }
                for (let i = 0; i < meal.dinner.length; i++) {
                    this.addToEating({eating: 'dinnerProducts', data: meal.dinner[i]})
                }
                for (let i = 0; i < meal.snacks.length; i++) {
                    this.addToEating({eating: 'snacksProducts', data: meal.snacks[i]})
                }
            }
        },
        selectDayOfTheWeek(value, day_numeric) {
            this.calculateStyle(value);
            this.selectedDay = value;
            this.setSelectedDailyCaloriesDay(value);
            this.selectedDayNumber = day_numeric;
            this.setSelectedDay(moment(day_numeric).format('YYYY-MM-DD'))
            this.updatedFoodsArrays();
            this.$store.dispatch('planner/validateMealPlanner');
            this.updatedFoodsArrays();
        },
        overBreakfast() {

        },
        foodsForEach(data, eating) {
            let vm = this;
            for (let i = 0; i < vm[data].length; i++) {
                if (vm[data][i].product.id === vm.dragStartFood.id) {
                    this.$notify({
                        group: 'planner',
                        title: `Error!`,
                        type: 'error',
                        text: 'You cannot add two identical dishes here',
                    });
                    return
                }
            }
            this.addToEating({eating: data, data: vm.dragStartFood})
            this.fetchAddToPlanner(vm.dragStartFood.id, eating)
            this.updatedFoodsArrays()
        },


        /**
         *  Payload is Breakfast, Lunch, Dinner or Snacks;
         *
         * @param payload
         * @returns {Promise<void>}
         */

        async testEmit(payload) {
            this[`addFoodTo${payload}`]();

        },


        // refactor this
        addFoodToBreakfast() {
            this.foodsForEach('breakfastProducts', 'breakfast');
        },
        addFoodToLunch() {
            this.foodsForEach('lunchProducts', 'lunch');
        },
        addFoodToDinner() {
            this.foodsForEach('dinnerProducts', 'dinner');
        },
        addFoodToSnacks() {
            this.foodsForEach('snacksProducts', 'snacks');
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
        }
    },
    async created() {
        await this.init();
        this.sumAllCaloriesPerDay()
    }
}
</script>

<style scoped lang="scss">
@import '../../../../../sass/mixins';


.meal-planner {
    position: relative;

    h3 {
        @include head-20-font;

        margin-bottom: 24px;
        margin-top: 40px;
    }

    &__info {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 66px 0px 52px;

        @media screen and (max-width: 767px) {
            flex-direction: column;
        }

        a {
            @include anchor-font;

            margin-left: 16px;
            text-decoration: underline;
            padding-top: 2px;

            &:hover {
                color: var(--primary);
            }

            @media screen and (max-width: 767px) {
                margin-left: 0px;
                margin-top: 5px;
            }
        }
    }

    &__header--box {
        display: flex;
        align-items: center;
        @media screen and (max-width: 480px) {
            align-items: flex-start;
            &:first-child {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    }

    &__title-day {
        min-width: 5.6rem;
        font-size: 20px;
        white-space: nowrap;
    }

    &__day-wrap {
        padding: 0 10px;
        //max-height: 664px;
    }

    &__select-underline {
        bottom: 0;
        position: absolute;
        left: 0;
        height: 5px;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        background: var(--purple);
        z-index: 1;
        pointer-events: none;
        transition: all 0.35s ease-in-out;
    }

    &__select-day {
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        font-size: 14px;
        line-height: 100%;
        position: relative;
    }

    &__divider {
        height: 1px;
        border: none;
        background-color: var(--grey-2);
        width: 100%;
        margin-top: 23px;
    }

    &__quantity, &__cost {
        display: flex;
        flex-direction: row;
    }

    &__save {
        display: flex;
        flex-direction: row;
        justify-content: center;
        margin-top: 13px;
    }

    &__type {
        border: 1px solid var(--grey-5);
        border-radius: 7px;
        background: var(--white);
        display: flex;
        flex-direction: column;
        padding: 11px 0 8px 0;
    }

    &__header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 16px;
        margin-top: 8px;
        padding: 0 30px 0 16px;

        @media screen and (max-width: 767px) {
            padding: 0 26px 0 16px;
        }
    }

    &__basis20 {
        font-weight: 300;
        font-size: 13px;
        line-height: 151.19%;
        width: 95px;
        padding-left: 8px;
        text-align: right;
        @media screen and (max-width: 767px) {
            &:last-child {
                width: 77px;
            }
        }
    }

    &__validation {
        text-align: center;
        margin-top: 1rem;
        color: #ff0000;
        //font-weight: 300;
        //color: black;
        font-weight: 500;
        display: flex;
        flex-direction: column;
    }

    &__breakfast, &__lunch, &__dinner, &__snacks {
        min-height: 100px;
        position: relative;
    }

    &__breakfast, &__lunch, &__dinner {
        margin-bottom: 10px;
    }
}
</style>

