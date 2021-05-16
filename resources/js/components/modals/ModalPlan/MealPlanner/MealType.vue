<template>
    <div>
        <div class="meal-type">
            <div class="meal-type__header">
                <div class="meal-type__header-box">
                    <h3 class="meal-type__title">{{ title }}</h3>
                    <template v-if=" this.copying_eating !== 'all'">
                        <template v-if="!myFood.length || isCopy">
                            <button @click="copyAllInEating()"
                                    v-if="copyCheck()"
                                    class="meal-setup__button">
                                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" stroke="currentColor">
                                    <path d="M4 11.5V16H16V4H11.5" stroke-width="0.5" stroke-dasharray="4 4"/>
                                    <rect x="0.5" y="0.5" width="11" height="11"/>
                                </svg>
                                <span class="meal-setup__button-text">Copy</span>
                            </button>
                            <button
                                v-if="copiedCheck()"
                                class="meal-setup__button copied">
                                <svg width="18" height="13" viewBox="0 0 18 13" stroke="currentColor" fill="none">
                                    <path d="M1 6.78947L5.33333 12L14 1"/>
                                    <path d="M4 6.78947L8.33333 12L17 1"/>
                                </svg>
                                <span class="meal-setup__button-text">Copied</span>
                            </button>

                            <button @click="pasteItem()"
                                    v-if="pasteCheck()"
                                    class="meal-setup__button">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor">
                                    <path d="M4 2C9.5 2 8 5 8 14M8 14L12 10M8 14L4 10"/>
                                </svg>
                                <span class="meal-setup__button-text">Paste</span>
                            </button>

                        </template>
                    </template>
                </div>
                <div class="meal-type__total">
                    <div v-if="totalCalories">{{ totalCalories }}</div>
                    <div v-if="totalCost">{{ phrase.currencySm }}{{ totalCost }}</div>
                </div>
            </div>
            <div>
                <Container
                    :group-name="'food'"
                    :remove-on-drop-out="true"
                    drag-class="card-ghost"
                    drop-class="card-ghost-drop"
                    @drop="(e) => {storeFood(e)}"
                    :drop-placeholder="upperDropPlaceholderOptions"
                    drag-handle-selector=".movable"
                >
                    <Draggable v-for="(product, index) in products" :key="index">
                        <card-product
                            v-if="!product.product.is_dish"
                            :id="product.product.id"
                            :image="product.product.image"
                            :title="product.product.name"
                            :store="product.product.store.name"
                            :servingSize="product.product.serving_size"
                            :package_size="product.product.package_size"
                            :servings="product.servings"
                            :caloriesPerServing="product.product.calories"
                            :cost="product.product.cost"
                            :eating="product.eating"
                            :date="product.date"
                            :meal_id="product.id"
                            :url="product.product.url"
                            :isCustom="product.product.is_custom"
                            :store-id="product.product.store.id"
                            @delete="fetchRemoveFromPlanner(product.id)"
                        />
                        <card-product-dish
                            v-if="product.product.is_dish"
                            :id="product.product.id"
                            :image="product.product.image"
                            :title="product.product.name"
                            :store="product.product.store.name"
                            :servingSize="product.product.serving_size"
                            :package_size="product.product.package_size"
                            :servings="product.servings"
                            :caloriesPerServing="product.product.calories"
                            :cost="product.product.cost"
                            :eating="product.eating"
                            :date="product.date"
                            :meal_id="product.id"
                            :dish="product.product.dish"
                            @delete="fetchRemoveFromPlanner(product.id)"
                        />
                    </Draggable>
                </Container>
                <div v-if="products.length === 0" class="meal-type__empty">
                    <div class="meal-type__empty__svg">
                        <svg width="46" height="29" viewBox="0 0 46 29">
                            <path
                                d="M42.4098 21.2615C42.091 16.5701 40.1131 12.2033 36.7577 8.84656C33.4023 5.48996 29.0371 3.51128 24.3477 3.19239V0H21.6523V3.19239C16.9629 3.51128 12.5976 5.48996 9.24223 8.84665C5.88692 12.2033 3.90901 16.5702 3.59025 21.2615H0V28.7992H46V21.2615H42.4098ZM11.1481 10.7532C14.3139 7.5862 18.523 5.84201 23 5.84201C27.477 5.84201 31.6861 7.5862 34.8519 10.7532C37.6979 13.6002 39.3934 17.2909 39.7072 21.2615H6.29284C6.60657 17.2909 8.30219 13.6002 11.1481 10.7532ZM43.3047 26.1029H2.69531V23.9578H43.3047V26.1029Z"
                                fill="#C7C7C7"/>
                        </svg>
                    </div>
                    <div class="meal-type__empty__text">
                        Drag and drop here
                    </div>
                </div>
            </div>


        </div>
        <overlay-component v-if="isLoading == title.toLowerCase() || isFetching"/>
    </div>
</template>

<script>
import axios from "axios";
import {mapActions, mapGetters, mapMutations} from "vuex";
import moment from "moment";
import {Container, Draggable} from "vue-smooth-dnd";
import CardProductDish from "./CardProductDish";
import CardProduct from "./CardProduct";
import OverlayComponent from "../../../overlay/OverlayComponent";

export default {
    props: {
        title: {
            type: String,
            required: true,
            default: ''
        },
        totalCalories: {
            required: true,
            default: ''
        },
        totalCost: {
            required: true,
            default: ''
        },
        products: {
            type: Array,
            required: true,
            default: []
        },
        isLoading: {
            type: String,
            required: true,
            default: ''
        }
    },
    components: {OverlayComponent, CardProduct, CardProductDish, Container, Draggable},

    data() {
        return {
            isMyFoodChecked: false,
            isFetching: false,
            upperDropPlaceholderOptions: {
                className: 'cards-drop-preview',
                animationDuration: '150',
                showOnTop: true
            },
        }
    },
    computed: {
        ...mapGetters({
            isCopy: 'food/getIsCopy',
            planner_id: 'planner/getPlannerId',
            selectedDayHoursNumber: 'planner/getSelectedDay',
            validation: 'planner/getValidation',
            myFood: 'food/getMyFood',
            selected_day: 'planner/getSelectedDay',
            notValidDays: 'planner/getNotValidationDays',
            breakfastProducts: 'planner/getBreakfastProducts',
            lunchProducts: 'planner/getLunchProducts',
            dinnerProducts: 'planner/getDinnerProducts',
            snacksProducts: 'planner/getSnacksProducts',
            copying_eating: 'food/getCopyingEating',
            copying_date: 'food/getCopyingDate',
            meal_ids: 'food/getMealIds',
            product_ids: 'food/getProductIds',
            choosed_product_ids: 'food/getChoosedFood'
        }),

    },
    methods: {
        ...mapMutations({
            setValidation: 'planner/setValidation',
            clearMyFoodChoosed: 'food/clearMyFoodChoosed',
            clearNotValidationDays: 'planner/clearNotValidationDays',
            setNotValidationDays: 'planner/setNotValidationDays',
            setIsCopy: 'food/setIsCopy',
            setProductIds: 'food/setProductIds',
            setMealIds: 'food/setMealIds',
            setCopyingEating: 'food/setCopyingEating',
            setCopyingDate: 'food/setCopyingDate',
        }),
        copyCheck() {
            let check;
            if (this.isCopy) {
                check = false
            } else check = this[this.title.toLowerCase() + 'Products'].length >= 1;
            return check;
        },
        copiedCheck() {
            let check;


            if (this.isCopy) {
                if (this.myFood.length < 1) {
                    this[this.title.toLowerCase() + 'Products'].forEach((item) => {
                        check = item.eating == this.copying_eating && item.date == this.copying_date;
                    })
                } else {
                    this.myFood.forEach((item) => {
                        this.products.forEach((food) => {
                            check = item.eating == food.eating && item.date == food.date;
                        })
                    })
                }
            } else if (this[this.title.toLowerCase() + 'Products'].length < 1) {
                check = false
            } else {
                check = false
            }

            return check;

        },
        pasteCheck() {
            let check;
            if (this.isCopy) {
                check = !(this.selected_day === this.copying_date && this.title.toLowerCase() === this.copying_eating);
            } else {
                check = false
            }
            return check;

        },
        copyAllInEating() {
            this.setMealIds([])
            this.setIsCopy(true);
            this.setCopyingEating(this.title.toLowerCase())
            this.setCopyingDate(this.selected_day)
            let product_ids = [];
            let meal_ids = [];
            if (this.myFood.length > 0) {
                this.myFood.forEach((item) => {
                    product_ids.push(item.product.id);
                    meal_ids.push(item.meal_id)
                })
            } else {
                this.products.forEach((food) => {
                    if (this.copying_eating == food.eating) {
                        product_ids.push(food.product.id);
                        meal_ids.push(food.id)
                    }
                })
            }
            this.setMealIds(meal_ids)
            this.setProductIds(product_ids.sort((a, b) => a - b))
        },
        arrayContainsArray(superset, subset) {
            return subset.every(function (value) {
                return (superset.indexOf(value) >= 0);
            });
        },
        async pasteItem() {
            let paste_prod_ids = [];
            let noValid = true;
            this[this.title.toLowerCase() + 'Products'].forEach((food) => {
                paste_prod_ids.push(food.product.id)
            })
            paste_prod_ids.sort((a, b) => a - b)
            let parse_prod_ids = JSON.parse(JSON.stringify(this.product_ids))
            let choosed_product_ids = JSON.parse(JSON.stringify(this.choosed_product_ids))

            if (paste_prod_ids.length > parse_prod_ids.length) {
                if (parse_prod_ids.length > 0) {
                    noValid = paste_prod_ids.some(r => parse_prod_ids.includes(r))
                } else {
                    noValid = this.arrayContainsArray(paste_prod_ids, choosed_product_ids)
                }
            } else {
                if (parse_prod_ids.length > 0) {
                    noValid = this.arrayContainsArray(paste_prod_ids, parse_prod_ids)
                } else {
                    noValid = this.arrayContainsArray(paste_prod_ids, choosed_product_ids)
                }

            }
            if (!noValid) {
                this.isFetching = true
                try {

                    let data = {
                        case: 1,
                        meal_ids: this.meal_ids,
                        eating: this.title.toLowerCase(),
                        date: this.selected_day
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
            } else {
                this.$notify({
                    group: 'planner',
                    title: `Yay`,
                    type: 'error',
                    text: 'You cannot add identical dishes here',
                });
            }

        },
        storeFood(event) {
            if (event.addedIndex !== null && event.payload !== undefined) {
                this.$emit('fooddrop', this.title);
                this.clearMyFoodChoosed()
            }
        },
        async fetchWeekPlan() {
            await this.$store.dispatch('planner/fetchWeekPlan')
            // await this.$store.dispatch('planner/validateMealPlanner')
        },
        async fetchRemoveFromPlanner(meal_id) {
            this.isFetching = true
            try {
                let res = await axios.delete('/api/meal/' + this.planner_id + '/' + meal_id)
                await this.fetchWeekPlan();
            } catch (e) {
            }
            this.isFetching = false

        },
    },
}
</script>

<style scoped lang="scss">
@import '../../../../../sass/mixins';

.meal-type {
    display: flex;
    flex-direction: column;
    margin-bottom: 30px;

    &__title {
        min-width: 5.6rem;
    }

    &__header {
        display: flex;
        justify-content: space-between;
        padding-right: 20px;
        margin-bottom: 20px;
        padding-left: 6px;
        @media screen and (max-width: 767px) {
            padding-right: 16px;
        }
    }

    &__header-box {
        display: flex;
        align-items: center;
        @media screen and (max-width: 480px) {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    h3 {
        @include head-20-font;
    }

    &__empty {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        position: absolute;
        width: calc(100% - 12px);
        background: #F3F3F3;
        border: 1px solid #DCDCDC;
        border-radius: 8px;
        height: 81px;
        bottom: 0;
        margin: 0 6px 6px 6px;

        &__text {
            font-size: 14px;
            color: #5C5C5C;
            font-weight: 500;
            margin-top: 8px;
        }
    }

    &__total {
        font-size: 14px;
        line-height: 100%;
        font-weight: 600;
        width: 190px;
        display: flex;
        align-items: center;

        @media screen and (max-width: 480px) {
            align-items: flex-start;
        }

        @media screen and (max-width: 767px) {
            width: 154px;
        }

        div {
            width: 50%;
            padding-left: 8px;
            text-align: right;
        }
    }
}
</style>


