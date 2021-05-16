import axios from "axios";

const state = {
    /*Array from backend for specific store after search*/
    food: [],
    //Array food which selected from search and add to food list
    myFoodChoosed: [],
    myFood: [],
    //Array ID food which we choosed from food list
    choosedMeal: [],
    choosedFood: [],
    isBusy: false,
    isCopy: false,
    copying_eating: '',
    copying_date: '',
    meal_ids: [],
    product_ids: [],
    errors: [],
    drag_start_food: {},
    //Object for edit not custom food
    editableFood: {},
    // prohibit to add food to chooosed list
    prohibit: false,
}

const getters = {
    getFood: (state) => {
        return state.food;
    },
    getMealIds: (state) => {
        return state.meal_ids;
    },
    getProductIds: (state) => {
        return state.product_ids;
    },
    getMyFood: (state) => {
        return state.myFood;
    },
    getMyFoodChoosed: (state) => {
        return state.myFoodChoosed;
    },
    dragStartFood: (state) => {
        return state.drag_start_food;
    },
    getChoosedFood: (state) => {
        return state.choosedFood;
    },
    getChoosedMeal: (state) => {
        return state.choosedMeal;
    },
    getEditableFood: (state) => {
        return state.editableFood;
    },
    getIsBusy: (state) => {
        return state.isBusy;
    },
    getIsCopy: (state) => {
        return state.isCopy;
    },
    getCopyingEating: (state) => {
        return state.copying_eating;
    },
    getCopyingDate: (state) => {
        return state.copying_date;
    },
    getErrors: (state) => {
        return state.errors;
    },
    getProhibit: (state) => {
        return state.prohibit;
    },
}

const mutations = {
    setIsCopy(state, payload){
        state.isCopy = payload;
    },
    addFood: (state, value) => {
        state.myFood.push(value);
    },
    setMealIds: (state, value) => {
        state.meal_ids = value;
    },
    setProductIds: (state, value) => {
        state.product_ids = value;
    },
    addFoodChoosed: (state, value) => {
        state.myFoodChoosed.push(value);
    },
    setDragStartFood: (state, value) => {
        state.drag_start_food = value;
    },
    clearMyFoodChoosed: (state) => {
        state.myFoodChoosed = [];
    },
    clearMyFood: (state) => {
        state.myFood = [];
    },
    deleteItem: (state, index) => {
        state.myFood.splice(index, 1);
    },
    deleteFoodId (state, id) {
        let index = state.myFood.findIndex(item => item.meal_id == id)
        state.myFood.splice(index, 1)
    },
    deleteFoodIdChoosed (state, id) {
        let index = state.myFoodChoosed.findIndex(item => item.id == id)
        state.myFoodChoosed.splice(index, 1)
    },
    choose: (state, id) => {
        state.choosedFood.push(id);
    },
    chooseMeal: (state, id) => {
        state.choosedMeal.push(id);
    },
    cancelChoose: (state, id) => {
        let index = state.choosedFood.findIndex(item => item == id)
        state.choosedFood.splice(index, 1);
    },
    cancelChooseMeal: (state, id) => {
        let index = state.choosedMeal.findIndex(item => item == id)
        state.choosedMeal.splice(index, 1);
    },
    clearChoosedFood: (state) => {
        state.choosedFood = [];
    },
    clearChoosedMeal: (state) => {
        state.choosedMeal = [];
    },
    setCopyingEating: (state, payload) => {
        state.copying_eating = payload;
    },
    setCopyingDate: (state, payload) => {
        state.copying_date = payload;
    },
    setFood: (state, products) => {
        state.food = [ ...products];
    },
    editFood: (state, food) => {
        state.editableFood = { ...food };
    },
    clearEditFood: (state) => {
        state.editableFood = {};
    },
    setIsBusy: (state, value) => {
        state.isBusy = value;
    },
    addError: (state, value) => {
        state.errors.push(value);
    },
    clearErrors: (state) => {
        state.errors = [];
    },
    setProhibit: (state, value) => {
        state.prohibit = value
    },
}

const actions = {
    createFood: (context, food) => {
        context.commit('setIsBusy', true);
        context.commit('clearErrors');

        const formData = new FormData();
        formData.append("name", food.name);
        formData.append("serving_size", food.servingSize);
        formData.append("calories", food.calories);
        formData.append("cost", food.cost);
        formData.append("image", food.image);

        //Add clone_id if we edit food
        const editableFood = context.getters.getEditableFood;

        if (Object.keys(editableFood).length) {
            if(editableFood.is_custom) {
                formData.append("edit_id", editableFood.id);
            } else {
                formData.append("clone_id", editableFood.id);
            }
        }

        let store_id = food.storeId || editableFood.store_id
        axios.post(`/api/product/${store_id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
            .then((response) => {
                if (response.data.success) {
                    if(!context.state.prohibit) {
                        context.commit('clearMyFoodChoosed');
                        //Added product to myFood
                        context.commit('addFoodChoosed', response.data.product);
                    } else {
                        if(context.state.myFoodChoosed.length > 0 ? context.state.myFoodChoosed[0].id === editableFood.id : false) {
                            context.commit('clearMyFoodChoosed');
                            //Added product to myFood
                            context.commit('addFoodChoosed', response.data.product);
                        }
                    }
                    //Clear editable food
                    context.commit('clearEditFood');
                    //Close modal createFood
                    context.commit('setProhibit', false)
                    context.dispatch('modals/actionsModal', {
                        name: 'modalFood',
                        action: 'close'
                    }, { root: true })
                    context.dispatch('planner/fetchWeekPlan', null, {root:true})
                }
            })
            .catch((error) => {
                console.log(error)
                const errorMessage = error.response.data.message;

                context.commit('addError', errorMessage);
            })
            .then((response) => {
                context.commit('setIsBusy', false);
            })
    },

    deleteFood: (context) => {
        context.state.choosedFood.forEach((id) => {
            context.state.myFood.forEach((food, index) => {
                if (food.id === id) {
                    context.commit('deleteItem', index);
                }
            })
        });

        context.commit('clearChoosedFood');
    },

    clearMessages: (context) => {
        context.commit('clearErrors');
    }
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
