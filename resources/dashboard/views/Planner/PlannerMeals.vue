<template>
    <div class="container__main__slot">
        <div class="container__content" style="width: 100%">
            <template v-if="!$store.state.auth.user.finished_setup">
                <div style="display: flex; justify-content: center; margin-top: 25px; font-size: 1.5rem;">
                    You haven't finished your initial setup
                </div>
                <div style="display: flex; justify-content: center; margin: 1em 1em 0 1em; font-size: 1rem; font-weight: 500; text-align: center;">
                    Your meal plan & analytics cannot start until you have filled in foods correctly (i.e. earned purple checkmarks) for ALL 7 days!
                </div>
                <div style="margin-top: 25px; text-align: center">
                    <button-base
                        :text="'User setup'"
                        @click="handleNewPlan"
                        class="meal-planner__btn"
                    />
                </div>
            </template>

            <template v-else-if="need_to_update">
                    <NeedToUpdate/>
            </template>

            <template v-else-if="isPlannerMessedUp">
                <div style="display: flex; justify-content: center; margin-top: 25px; font-size: 1.1rem;text-align: center;">
                    Your planner isn't valid. Please, finish planner setup
                </div>
                <div style="margin-top: 25px; text-align: center">
                    <button-base
                        :text="'User setup'"
                        @click="handleNewPlan"
                        class="meal-planner__btn"
                    />
                </div>
            </template>

            <template v-else-if="plannerExists">
                <template v-if="(valid) || (!isCurrentWeek)">
                    <div class="meals" v-for="(items, key) in meals[selected_day]['meals']">
                        <div class="meals__head">
                            <div class="meals__head__title">
                                <div class="caption">
                                    {{key}}
                                </div>

                                <span  data-v-step="1"  @click="$emit('refetchmeals')">
                                    <spin-circle/>
                                </span>

                            </div>
                            <div class="meals__head__info cal-cash">
                                <div>
                                    {{calculateCalories(items)}} cal.
                                </div>
                                <div>
                                    £{{calculatePrice(items)}}
                                </div>
                            </div>
                        </div>
                        <div class="meals__entries">
                            <template v-for="item in items">
                                <app-meal-entry
                                    v-if="!item.product.is_dish"
                                    :item="item"
                                    v-on:updateitemeaten="passEmitToParent"
                                />
                                <app-meal-dish
                                    v-else
                                    :item="item"
                                    :dish="item.product.dish"
                                    v-on:updateitemeaten="passEmitToParent"
                                />
                            </template>
                            <template v-if="items.length === 0">
                                <div class="empty_block">
                                    <span>Empty</span> <img height="32" width="32" src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/120/twitter/259/fork-and-knife-with-plate_1f37d.png" alt="">
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="">
                        <div class="meals__head">
                            <div class="meals__head__title">
                                <div class="caption">
                                    Extra Calories
                                </div>
                            </div>

                        </div>
                        <div class="extra-items">
                            <app-extra-consumed/>
                            <app-extra-exercise/>
                        </div>
                    </div>
                </template>
            </template>

            <template v-else>
                <div style="display: flex; justify-content: center; margin-top: 50px; font-size: 1.5rem;">
                    Planner for this week doesn't exist
                </div>
            </template>

        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    import { mapMutations, mapGetters } from 'vuex';
    import AppMealEntry from "../../components/planner/meals/AppMealEntry";
    import TourTemplate from "../../components/TourTemplate";
    import ButtonBase from "../../../js/components/buttons/ButtonBase";
    import DragDrop from "../../../js/components/DragDrop";
    import AppMealDish from "../../components/planner/meals/AppMealDish";
    import NeedToUpdate from "../../components/NeedToUpdate";

    export default {
        name: "PlannerMeals",
        components: {NeedToUpdate, DragDrop, ButtonBase, TourTemplate, AppMealEntry, AppMealDish},
        props: ['title', 'meals', 'finished_setup', 'need_to_update', 'valid'],
        data: function() {
            return {
            }
        },
        mounted() {
            this.init();
            this.$store.dispatch('stores/getStoresList')
        },
        watch: {
            validation(newValue, oldValue) {
                if(newValue.is_valid && this.isCurrentWeek) {
                    document.location.reload();
                }
            },
            isPlannerMessedUp(value) {
                if(value) {
                    // this.$emit('setheadertitle', 'Meal Planner Set Up');
                    this.$emit('setheadertitle', this.title);

                }  else {
                    this.$emit('setheadertitle', this.title);
                }
            }
        },
        computed: {
            ...mapGetters({
                selected_day: 'planner/getSelectedDay',
                selected_week: 'planner/getSelectedWeek',
                user: 'auth/user',
                current_day:  'planner/getCurrentDay',
                validation: 'planner/getValidation',
            }),
            plannerExists: {
                get() {
                    return Object.keys(this.meals).length !== 0;
                }
            },
            isCurrentWeek() {
                return moment(this.current_day, 'YYYY-MM-DD').isBetween(moment(this.selected_week.starts, 'YYYY-MM-DD'), moment(this.selected_week.ends, 'YYYY-MM-DD'), 'days','[]');
            },
            dayInAcceptedRange() {
                return moment(Object.keys(this.meals)[0], 'YYYY-MM-DD').isBetween(moment(this.selected_week.starts, 'YYYY-MM-DD'), moment(this.selected_week.ends, 'YYYY-MM-DD'), 'days','[]');
            },
            isPlannerMessedUp() {
                return !this.valid && this.plannerExists && this.isCurrentWeek && this.dayInAcceptedRange
            }
        },
        methods: {
            ...mapMutations({
                setCurrentDay: 'planner/setCurrentDay',
                setSelectedWeek: 'planner/setSelectedWeek',
                setSelectedDay: 'planner/setSelectedDay',
            }),
            handleNewPlan() {
                if(this.user.finished_setup === false){
                    window.location.href = '/store'
                } else {
                    window.location = '/meal-planner/setup';
                }
            },
            passEmitToParent(data) {
                this.$emit('updateitemeaten', data)
            },
            init() {
                this.$emit('setheadertitle', this.title);
            },
            calculateCalories(items) {
                return items.reduce((acc, curr) => acc + curr.product['calories'] * curr.servings, 0)
            },
            calculatePrice(items) {
                return (items.reduce((acc, curr) => acc + curr.product['cost'] * curr.servings, 0)).toFixed(2)
            }
        }
    }
</script>

<style scoped lang="scss">
    .empty_block {
        display: flex;
        justify-content: center;
        padding: 15px;
        font-family: Gilroy;
        align-items: center;
        > span {
            padding-right: 15px;
        }
    }

    .meal-planner__btn {
        margin-left: 10px;
        max-width: 262px;
        @media only screen and (max-width: 480px) {
            width: calc(100% - 20px)!important;
        }
    }

    .cal-cash {
        font-weight: 800;
        &.light {
            font-family: Gilroy;
            font-size: 14px;
            font-weight: 500;
        }
        div:nth-child(n+2) {
            margin-left: 50px;
            &.entry {
                @media only screen and (max-width: 600px) {
                    margin-left: auto !important;
                }
            }
        }

    }


    .meals {
        margin-bottom: 15px;
        position: relative;
        font-weight: 500;

        &__entries {
            padding: 0 10px;
        }

        &__head {
            flex-direction: row;
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            margin-left: 10px;

            &__title {
                position: relative;

                display: flex;
                align-items: center;

                .caption {
                    font-size: 20px;
                    text-transform: capitalize;
                    font-weight: 800;
                }

            }

            &__info {
                margin-left: auto;
                position: relative;
                display: flex;
                margin-right: 14px;
                font-size: 14px;
                width: 190px;
                @media screen and (min-width: 681px) and (max-width: 767px) {
                    width: 159px;
                }
                @media screen and (max-width: 680px) {
                    width: 152px;
                }
                div {
                    width: 50%;
                    padding-left: 8px;
                    text-align: right;
                    margin-left: 0!important;
                }
            }
        }
    }
    .extra-items {
        margin: 0 10px;
        display: flex;
        @media screen and (max-width: 600px) {
            flex-direction: column;
        }
    }
</style>
