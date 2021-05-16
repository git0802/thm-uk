<template>
    <div class="container__main__slot" :class="{'w-max-750': right_drawer}">
        <template v-if="is_busy">
            <OverlayComponent/>
        </template>
        <div class="container__head top-head top-head--sticky top-head--borderless">
            <app-left-drawer-burger/>

            <div class="title">
                {{ title }}
            </div>
            <c-datepicker v-on:datepickerUpdate="selectDate" :selected_day="selected_day"/>
        </div>
        <div v-if="meals && selected_week" class="w-max-1020">
            <app-dows :isEmpty="Object.keys(meals).length === 0"  v-on:test="refetchWeekPlan" :need_to_update="need_to_update"/>
            <finished v-on:jumptothisday="jumpToThisDay" />
            <router-view v-on:setheadertitle="setHeaderTitle"
                         v-on:refetchmeals="refetchWeekPlan"
                         v-on:updateitemeaten="updateMeal"
                         :valid="valid"
                         :meals="meals" :finished_setup="finished_setup" :need_to_update="need_to_update" />
        </div>
    </div>
</template>

<script>
    import AppFullLayout from "./AppFullLayout";
    import { mapGetters, mapMutations } from "vuex";
    import moment from "moment";
    import axios from 'axios';
    import CDatepicker from "./cDatepicker";
    import OverlayComponent from "../../js/components/overlay/OverlayComponent";

    export default {
        name: "AppFullPlannerLayout",
        components: {CDatepicker, AppFullLayout, OverlayComponent},
        data: function() {
            return {
                title: null,
                meals: null,
                plannedId: null,
                caloriesGoal: null,
                extraCalories:null,

                starts: null,
                is_busy: false,

                valid: false,

                finished_setup: null,
                need_to_update: null,
                error: {
                    status: false,
                    code: null,
                }
            }
        },
        mounted() {
            this.init();
        },
        computed: {
            ...mapGetters({
                selected_day: 'planner/getSelectedDay',
                selected_week: 'planner/getSelectedWeek',
                current_day:  'planner/getCurrentDay',
                right_drawer: "plannerUi/getRightDrawer",
            })
        },
        methods: {
            ...mapMutations({
                setCurrentDay: 'planner/setCurrentDay',
                setSelectedWeek: 'planner/setSelectedWeek',
                setSelectedDay: 'planner/setSelectedDay',
            }),
            setHeaderTitle(value) {
                this.title = value;
            },
            async selectDate(date) {
                this.setSelectedDay(moment(date).format('YYYY-MM-DD'))
                let res = await this.fetchWeekPlan(moment(date).format('YYYY-MM-DD 00:00:00'))
                await this.processWeek(res)
            },
            async refetchWeekPlan() {
                let res = await this.fetchWeekPlan(moment(this.selected_day).format('YYYY-MM-DD 00:00:00'))
                // todo: questionable
                await this.processWeek(res)
            },
            updateMeal(data) {
                this.meals[data.date].meals[data.eating].forEach((e) => {
                    if(data.id === e.id) {
                        e.is_eaten = data.is_eaten;
                    }
                });
            },
            async fetchWeekPlan(date) {
                this.is_busy = true;
                try {
                    const response = await axios.get(`/api/planner`, {
                        params: {
                            date: date
                        }
                    });

                    if(response.data.data !== null) {
                        this.meals = response.data.data.list;
                        this.plannedId = response.data.data.id;
                        this.$store.commit('planner/setPlannerId', response.data.data.id);

                        this.caloriesGoal = response.data.data.calories_goal;
                        this.extraCalories = response.data.data.extra_calories;
                        this.starts = response.data.data.starts;
                        this.ends = response.data.data.ends;
                        this.is_busy = false;
                        this.finished_setup = response.data.data.finished_setup;
                        this.need_to_update = response.data.data.need_to_update;

                        this.valid = response.data.data.validation.is_valid;
                        this.$store.commit('planner/setFinishedSetup', response.data.data.finished_setup);

                    } else {
                        this.meals = {};
                        this.finished_setup = null;
                        this.is_busy = false;
                        this.$store.commit('planner/setFinishedSetup', false);
                    }
                    return response.data;
                } catch (e) {
                    this.is_busy = false;
                    this.error.status = true;
                    this.error.code = null;
                }
            },
            async init() {

                if(!this.selected_day && !this.current_day) {
                    let currentDay = moment();
                    this.setCurrentDay(currentDay.format('YYYY-MM-DD'));
                    this.setSelectedDay(currentDay.format('YYYY-MM-DD'));
                    this.date = currentDay._d;


                    let response = await this.fetchWeekPlan(currentDay.format('YYYY-MM-DD 00:00:00'));

                    await this.processWeek(response);
                }
                if(!this.meals) {
                    let selectedDay = moment(this.selected_day);
                    let response = await this.fetchWeekPlan(selectedDay.format('YYYY-MM-DD 00:00:00'));
                    await this.processWeek(response);
                }
            },
            async processWeek(response) {
                let z = {}

                Object.keys(this.meals).forEach((key, index) => {
                    z[this.meals[key].day.toLowerCase()] = key
                });

                if(response.data !== null) {
                    this.setSelectedWeek({
                        start: response.data.starts,
                        end: response.data.ends,
                        days: z,
                    });
                }
            },
            async jumpToThisDay() {
                let currentDay = moment();
                this.setSelectedDay(currentDay.format('YYYY-MM-DD'));
                this.date = currentDay._d;

                let response = await this.fetchWeekPlan(currentDay.format('YYYY-MM-DD 00:00:00'));

                await this.processWeek(response);
            }
        },
        watch: {
            selected_day: async function(newValue, oldValue) {
                if(oldValue !== null) {
                    if(Object.keys(this.meals).length === 0) {
                        let selectedDay = moment(this.selected_day);
                        let response = await this.fetchWeekPlan(selectedDay.format('YYYY-MM-DD 00:00:00'));
                        await this.processWeek(response);
                    }
                }
                // todo: not sure to have this
                // let selectedDay = moment(this.selected_day);
                // let response = await this.fetchWeekPlan(selectedDay.format('YYYY-MM-DD 00:00:00'));
                // await this.processWeek(response);

            }
        }
    }
</script>

<style scoped lang="scss">

</style>
