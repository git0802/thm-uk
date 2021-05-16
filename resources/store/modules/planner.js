import axios from "axios";
import moment from "moment";

const state = {
    current_day: null,
    selected_week: {
        // monday
        starts: null,
        // sunday
        ends: null,
        // object of days with dates and day names
        days: null,
    },
    // selected_week_start: null,
    // selected_week_end: null,
    selected_day: null,
    planner_id: null,
    meals: [],
    calories_goal: null,
    goal: null,
    validation: [],
    notValidDays: [],
    breakfastProducts: [],
    lunchProducts: [],
    dinnerProducts: [],
    snacksProducts: [],
    selected_daily_calories_day: '',
    finished_setup: false

};

const getters = {
    getSelectedDailyCaloriesDay(state) {
        return state.selected_daily_calories_day;
    },
    getBreakfastProducts(state) {
        return state.breakfastProducts;
    },
    getLunchProducts(state) {
        return state.lunchProducts;
    },
    getDinnerProducts(state) {
        return state.dinnerProducts;
    },
    getSnacksProducts(state) {
        return state.snacksProducts;
    },
    getCurrentDay(state) {
        return state.current_day;
    },
    getSelectedWeek(state) {
        return state.selected_week;
    },
    getSelectedDay(state) {
        return state.selected_day;
    },
    getPlannerId(state) {
        return state.planner_id;
    },
    getMeals(state) {
        return state.meals;
    },
    getCaloriesGoal(state) {
        return state.calories_goal;
    },
    getGoal(state) {
        return state.goal;
    },
    getValidation(state) {
        return state.validation;
    },
    getNotValidationDays(state) {
        return state.notValidDays;
    },
    getFinishedSetup(state) {
        return state.finished_setup;
    }
};

const actions = {
    async fetchWeekPlan({commit, dispatch}) {
        try {
            const response = await axios.get(`/api/planner`);
            commit('setFinishedSetup', response.data.data.finished_setup)
            commit('setPlannerId', response.data.data.id)
            commit('setValidation', response.data.data.validation)
            commit('setCaloriesGoal', response.data.data.calories_goal)
            commit('setMeals', response.data.data.list)
            dispatch('plannerUi/fetchStats', axios, {root:true});
            await dispatch('validateMealPlanner');
            return response.data;
        } catch (e) {
        }
    },
    async validateMealPlanner({commit, dispatch}) {
        await commit('clearNotValidationDays');
        for (const [key, value] of Object.entries(state.validation.days)) {
            if (value.is_valid == false) {
               await commit('setNotValidationDays', (moment(key).format('dddd')))
            }
        }
    }
};

const mutations = {
    getSelectedDailyCaloriesDay(state) {
        return state.selected_daily_calories_day;
    },
    setCurrentDay(state, current_day) {
        state.current_day = current_day;
    },
    setSelectedWeek(state, object) {
        state.selected_week.starts = object.start;
        state.selected_week.ends = object.end;
        state.selected_week.days = object.days;
    },
    setSelectedDay(state, selected_day) {
        state.selected_day = selected_day;
    },
    setPlannerId(state, id) {
        state.planner_id = id;
    },
    setMeals(state, payload) {
        state.meals = payload;
    },
    setValidation(state, payload) {
        state.validation = payload;
    },
    setFinishedSetup(state, payload) {
        state.finished_setup = payload;
    },
    setCaloriesGoal(state, payload) {
        state.calories_goal = Number(payload);
    },
    setGoal(state, payload) {
        state.goal = Number(payload);
    },
    setNotValidationDays(state, payload) {
        state.notValidDays.push(payload);
    },
    clearNotValidationDays(state) {
        state.notValidDays = [];
    },
    setSelectedDailyCaloriesDay(state, payload) {
        state.selected_daily_calories_day = payload;
    },
    addToEating(state, {eating, data}){
        state[eating].push(data);
    },
    deleteFromEatingsArray(state, {eating, id}){
        let index = state[eating].findIndex(item => item.id == id)
        state[eating].splice(index, 1)
    },
    clearEatingArrays(state) {
        state.breakfastProducts = [];
        state.dinnerProducts = [];
        state.lunchProducts = [];
        state.snacksProducts = [];
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
