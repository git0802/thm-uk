<template>
    <div class="food-block food-block__total">
        <div class="food-block__main">

            <img :src="image" alt="" onerror="this.src='/images/no-product-image.jpg?t=0'" class="food-block__group-img">

            <div class="food-block__text">
                <div class="food-block__text-top">
                    <span class="food-block__name food-block__name--height-40"><a v-if="url" :href="url" target="_blank" class="food__links food__links-solo">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.253 6.61464L1.27006 8.59758C0.456853 9.41078 0 10.5137 0 11.6638C0 12.8138 0.456853 13.9168 1.27006 14.73C2.08326 15.5432 3.1862 16 4.33625 16C5.48629 16 6.58923 15.5432 7.40243 14.73L10.0444 12.0865C10.5348 11.5961 10.9004 10.9951 11.1105 10.3342C11.3205 9.6732 11.369 8.97148 11.2518 8.2879C11.1345 7.60433 10.855 6.95886 10.4367 6.40565C10.0184 5.85245 9.4735 5.40767 8.84773 5.10864L8.00079 5.95558C7.91479 6.04171 7.84001 6.13835 7.77821 6.2432C8.2617 6.38219 8.70054 6.64496 9.05136 7.00553C9.40217 7.3661 9.65281 7.81198 9.7785 8.2991C9.90418 8.78622 9.90056 9.2977 9.76799 9.78299C9.63542 10.2683 9.37849 10.7106 9.02261 11.0661L6.38206 13.7081C5.83967 14.2505 5.10403 14.5552 4.33697 14.5552C3.56991 14.5552 2.83427 14.2505 2.29188 13.7081C1.74949 13.1657 1.44477 12.4301 1.44477 11.663C1.44477 10.896 1.74949 10.1603 2.29188 9.61795L3.438 8.47328C3.27629 7.86731 3.21381 7.23914 3.253 6.61319V6.61464Z" fill="#DF2C65"/>
                            <path d="M4.69338 7.21877L4.93474 6.9774C5.29282 6.618 5.73959 6.35971 6.22972 6.22874C6.3607 5.7386 6.61898 5.29183 6.97838 4.93376L7.21975 4.69239C6.49272 4.65152 5.76708 4.79416 5.10962 5.10719C4.79166 5.77203 4.65291 6.49901 4.69338 7.21732V7.21877Z" fill="#DF2C65"/>
                            <path d="M5.95723 3.91205C5.46685 4.4025 5.10128 5.00343 4.8912 5.6644C4.68112 6.32537 4.63266 7.02709 4.74989 7.71066C4.86712 8.39424 5.14663 9.03971 5.56494 9.59291C5.98325 10.1461 6.52816 10.5909 7.15393 10.8899L8.27404 9.76838C7.78401 9.63694 7.3372 9.37884 6.97853 9.02001C6.61985 8.66118 6.36193 8.21427 6.2307 7.72419C6.09946 7.2341 6.09953 6.71811 6.23089 6.22806C6.36225 5.73801 6.62028 5.29116 6.97905 4.93243L9.6196 2.29043C10.162 1.74804 10.8976 1.44333 11.6647 1.44333C12.4317 1.44333 13.1674 1.74804 13.7098 2.29043C14.2522 2.83283 14.5569 3.56847 14.5569 4.33552C14.5569 5.10258 14.2522 5.83822 13.7098 6.38061L12.5637 7.52528C12.7255 8.13231 12.7877 8.76101 12.7487 9.38538L14.7316 7.40243C15.5448 6.58923 16.0017 5.48629 16.0017 4.33625C16.0017 3.1862 15.5448 2.08326 14.7316 1.27006C13.9184 0.456853 12.8155 0 11.6654 0C10.5154 0 9.41243 0.456853 8.59923 1.27006L5.95723 3.91205Z" fill="#DF2C65"/>
                            <path d="M10.8924 10.8897C11.2067 10.2326 11.3499 9.50676 11.3086 8.77954L11.0672 9.0209C10.7092 9.3803 10.2624 9.63859 9.77225 9.76957C9.64128 10.2597 9.38299 10.7065 9.02359 11.0645L8.78223 11.3059C9.50926 11.3468 10.2349 11.2041 10.8924 10.8911V10.8897Z" fill="#DF2C65"/>
                        </svg>
                        <span class="food__tooltip bottom">Check website</span>
                    </a> {{ title }}</span>
                </div>

                <div class="food-block__text-group">
                    <button type="button" class="food-block__details"
                            v-on:click="showDetails = !showDetails"
                            :class="{active: showDetails}"
                    >
                        Details
                    </button>
                    <div class="food-block__edit" v-if="$store.state.auth.guest"
                         @click="actionsModal({
                            name: 'modalRegister',
                            action: 'open'
                        })"
                    >
                        <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M52.618 2.631C49.108 -0.877 43.399 -0.877 39.889 2.631L3.827 38.693C3.81 38.71 3.8 38.731 3.785 38.749C3.764 38.773 3.746 38.799 3.727 38.825C3.674 38.899 3.633 38.978 3.602 39.064C3.593 39.09 3.58 39.113 3.573 39.139C3.57 39.149 3.564 39.159 3.561 39.169L0.026 54.019C0.01 54.086 0.006 54.154 0.004 54.221C0.004 54.234 0 54.246 0 54.259C0.001 54.373 0.026 54.484 0.065 54.591C0.074 54.616 0.084 54.638 0.095 54.662C0.144 54.769 0.205 54.872 0.291 54.958C0.386 55.053 0.498 55.126 0.619 55.176C0.74 55.226 0.869 55.251 0.998 55.251C1.075 55.251 1.153 55.242 1.229 55.224L16.079 51.689C16.106 51.683 16.13 51.668 16.156 51.659C16.19 51.648 16.222 51.635 16.255 51.62C16.327 51.587 16.394 51.546 16.456 51.497C16.48 51.478 16.505 51.464 16.528 51.443C16.536 51.435 16.546 51.431 16.554 51.423L52.617 15.36C56.127 11.85 56.127 6.14 52.618 2.631ZM51.204 4.045C53.692 6.534 53.904 10.442 51.854 13.182L42.067 3.395C44.808 1.345 48.716 1.557 51.204 4.045ZM46.254 18.895L36.354 8.995L37.768 7.581L47.668 17.481L46.254 18.895ZM4.961 50.288C4.57 49.897 3.938 49.897 3.547 50.288L2.79 51.045L5.344 40.317L9.766 39.826L9.197 44.948C9.193 44.986 9.207 45.021 9.207 45.058C9.207 45.096 9.193 45.13 9.197 45.168C9.201 45.201 9.218 45.228 9.225 45.26C9.237 45.318 9.254 45.371 9.275 45.425C9.301 45.49 9.332 45.549 9.37 45.606C9.401 45.652 9.432 45.693 9.47 45.733C9.518 45.784 9.57 45.827 9.627 45.867C9.672 45.898 9.715 45.927 9.765 45.951C9.831 45.982 9.9 46 9.972 46.017C10.01 46.026 10.041 46.047 10.08 46.052C10.116 46.056 10.152 46.058 10.189 46.058H10.19H10.191H10.192H10.193C10.229 46.058 10.266 46.056 10.302 46.052L15.424 45.483L14.933 49.905L4.204 52.459L4.961 51.702C5.351 51.312 5.351 50.679 4.961 50.288ZM17.511 44.809L39.889 22.43C40.28 22.039 40.28 21.407 39.889 21.016C39.498 20.625 38.866 20.625 38.475 21.016L16.097 43.395L11.324 43.925L11.854 39.152L34.234 16.774C34.625 16.383 34.625 15.751 34.234 15.36C33.843 14.969 33.211 14.969 32.82 15.36L10.44 37.738L7.257 38.092L34.94 10.409L44.84 20.309L17.157 47.992L17.511 44.809ZM49.082 16.067L39.182 6.167L40.597 4.752L50.497 14.652L49.082 16.067Z" fill="#DF2C65"/>
                        </svg>
                        <span class="food__tooltip top">Wrong info? Click to edit!</span>
                    </div>
                    <div class="food-block__edit" v-else
                         @click="editFood()"
                    >
                        <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M52.618 2.631C49.108 -0.877 43.399 -0.877 39.889 2.631L3.827 38.693C3.81 38.71 3.8 38.731 3.785 38.749C3.764 38.773 3.746 38.799 3.727 38.825C3.674 38.899 3.633 38.978 3.602 39.064C3.593 39.09 3.58 39.113 3.573 39.139C3.57 39.149 3.564 39.159 3.561 39.169L0.026 54.019C0.01 54.086 0.006 54.154 0.004 54.221C0.004 54.234 0 54.246 0 54.259C0.001 54.373 0.026 54.484 0.065 54.591C0.074 54.616 0.084 54.638 0.095 54.662C0.144 54.769 0.205 54.872 0.291 54.958C0.386 55.053 0.498 55.126 0.619 55.176C0.74 55.226 0.869 55.251 0.998 55.251C1.075 55.251 1.153 55.242 1.229 55.224L16.079 51.689C16.106 51.683 16.13 51.668 16.156 51.659C16.19 51.648 16.222 51.635 16.255 51.62C16.327 51.587 16.394 51.546 16.456 51.497C16.48 51.478 16.505 51.464 16.528 51.443C16.536 51.435 16.546 51.431 16.554 51.423L52.617 15.36C56.127 11.85 56.127 6.14 52.618 2.631ZM51.204 4.045C53.692 6.534 53.904 10.442 51.854 13.182L42.067 3.395C44.808 1.345 48.716 1.557 51.204 4.045ZM46.254 18.895L36.354 8.995L37.768 7.581L47.668 17.481L46.254 18.895ZM4.961 50.288C4.57 49.897 3.938 49.897 3.547 50.288L2.79 51.045L5.344 40.317L9.766 39.826L9.197 44.948C9.193 44.986 9.207 45.021 9.207 45.058C9.207 45.096 9.193 45.13 9.197 45.168C9.201 45.201 9.218 45.228 9.225 45.26C9.237 45.318 9.254 45.371 9.275 45.425C9.301 45.49 9.332 45.549 9.37 45.606C9.401 45.652 9.432 45.693 9.47 45.733C9.518 45.784 9.57 45.827 9.627 45.867C9.672 45.898 9.715 45.927 9.765 45.951C9.831 45.982 9.9 46 9.972 46.017C10.01 46.026 10.041 46.047 10.08 46.052C10.116 46.056 10.152 46.058 10.189 46.058H10.19H10.191H10.192H10.193C10.229 46.058 10.266 46.056 10.302 46.052L15.424 45.483L14.933 49.905L4.204 52.459L4.961 51.702C5.351 51.312 5.351 50.679 4.961 50.288ZM17.511 44.809L39.889 22.43C40.28 22.039 40.28 21.407 39.889 21.016C39.498 20.625 38.866 20.625 38.475 21.016L16.097 43.395L11.324 43.925L11.854 39.152L34.234 16.774C34.625 16.383 34.625 15.751 34.234 15.36C33.843 14.969 33.211 14.969 32.82 15.36L10.44 37.738L7.257 38.092L34.94 10.409L44.84 20.309L17.157 47.992L17.511 44.809ZM49.082 16.067L39.182 6.167L40.597 4.752L50.497 14.652L49.082 16.067Z" fill="#DF2C65"/>
                        </svg>
                        <span class="food__tooltip top">Wrong info? Click to edit!</span>
                    </div>

                    <div class="quantity">
                        <button type="button" class="quantity-btn"
                                v-on:click="fetchUpdatePlanner(servings - 1)"
                        >
                            <span>-</span>
                        </button>
                        <span class="quantity-number">{{ servings }}</span>
                        <button type="button" class="quantity-btn"
                                v-on:click="fetchUpdatePlanner(servings + 1)"
                        >
                            <span>+</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="food-block__info food-block__info-one">
                <div class="food-block__show-group food-block__show-group--one">
                    <span class="text">{{ caloriesPerServing * servings }}</span>
                </div>
            </div>
            <label class="food-block__checked">
                <input :disabled="isCopy" type="checkbox" v-model="isChosen">
                <svg width="15" height="10" viewBox="0 0 15 10" fill="#DF2C65" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M14.7399 0.253771C14.3932 -0.0846007 13.831 -0.0845998 13.4843 0.25383L5.64331 7.90759L1.51579 3.87862C1.16908 3.54019 0.606842 3.54019 0.260075 3.87862C-0.0866917 4.21705 -0.0866917 4.76586 0.260075 5.10435L5.01542 9.74616C5.1888 9.91541 5.41605 10 5.64325 10C5.87044 10 6.09775 9.91535 6.27107 9.74616L14.7399 1.47951C15.0867 1.14108 15.0867 0.592259 14.7399 0.253771Z"/>
                </svg>
            </label>

        </div>
        <div class="food-block__show"
             v-if="showDetails"
        >
            <div class="food-block__show-group food-block__show-group--four">
                <span class="title">Store</span>
                <span class="text">{{ store }}</span>
            </div>

            <div class="food-block__show-group food-block__show-group--four" v-tooltip="package_size">
                <span class="title">Serving Size</span>
                <span class="text">{{ servingSize }}</span>
            </div>

            <div class="food-block__show-group food-block__show-group--four">
                <span class="title">Servings</span>
                <span class="text">{{ servings }}</span>
            </div>

            <div class="food-block__show-group food-block__show-group--four">
                <span class="title">Cost</span>
                <span class="text">{{ phrase.currencySm }}{{ totalToFixed(cost, 1) }}</span>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters, mapMutations} from "vuex";
import axios from "axios";
import moment from 'moment'

export default {
    props: {
        id: {
            type: Number,
            required: true,
            default: 0
        },
        meal_id: {
            type: Number,
            required: true,
            default: 0
        },
        image: {
            type: String,
            required: true,
            default: ''
        },
        title: {
            type: String,
            required: true,
            default: ''
        },
        store: {
            type: String,
            required: true,
            default: ''
        },
        date: {
            type: String,
            required: true,
            default: ''
        },
        eating: {
            type: String,
            required: true,
            default: ''
        },
        servingSize: {
            required: true,
            default: 0
        },
        servings: {
            required: true,
            default: 0
        },
        caloriesPerServing: {
            type: Number,
            required: true,
            default: 0
        },
        cost: {
            type: Number,
            required: true,
            default: 0.0
        },
        package_size: {},
        url: {},
        isCustom: {},
        storeId: {},
    },
    data() {
        return {
            showDetails: false,
            isDeleted: false,
            quantity: 1,
            selectedDayNumber: moment().format('YYYY-MM-DD')
        }
    },
    computed: {
        ...mapGetters({
            planner_id: 'planner/getPlannerId',
            isCopy: 'food/getIsCopy',
            myFood: 'food/getMyFood',
            choosedMeal: 'food/getChoosedMeal',
        }),
        isChosen: {
            get() {
                return this.choosedMeal.includes(this.meal_id);
            },
            set(value) {
                if (value) {
                    let myFood = {
                        id: this.id,
                        meal_id: this.meal_id,
                        calories: this.caloriesPerServing,
                        cost: this.cost,
                        name: this.title,
                        image: this.image,
                        eating: this.eating,
                        date: this.date,
                    };
                    this.addFood(myFood);
                    this.chooseMeal(this.meal_id);
                    this.choose(this.id)
                } else {
                    this.cancelChooseMeal(this.meal_id);
                    this.cancelChoose(this.id)
                    this.deleteFoodId(this.meal_id);
                }
            }
        }
    },
    methods: {
        ...mapMutations({
            edit: 'food/editFood',
            addFood: 'food/addFood',
            choose: 'food/choose',
            chooseMeal: 'food/chooseMeal',
            deleteFoodId: 'food/deleteFoodId',
            cancelChoose: 'food/cancelChoose',
            cancelChooseMeal: 'food/cancelChooseMeal',
        }),
        ...mapActions({
            actionsModal: 'modals/actionsModal',
        }),
        editFood() {
            const editableFood = {
                id: this.id,
                name: this.title,
                image: this.image,
                servingSize: this.servingSize,
                cost: this.cost,
                calories: this.caloriesPerServing,
                is_custom: this.isCustom,
                store_id: this.storeId
            }

            this.edit(editableFood);

            this.$store.commit('food/setProhibit', true)

            this.actionsModal({
                name: 'modalFood',
                action: 'open'
            })


        },
        totalToFixed(first, second) {
            let total = first * second;
            return total.toFixed(2)
        },
        async fetchUpdatePlanner(count) {
            if (count > 0) {
                this.isDeleted = true;
                try {
                    let res = await axios.patch('/api/meal/' + this.planner_id + '/' + this.meal_id, {
                        servings: count
                    })
                    await this.$store.dispatch('planner/fetchWeekPlan');
                    // await this.$store.dispatch('planner/validateMealPlanner');
                } catch (e) {
                }
                setTimeout(() => {
                    this.isDeleted = false;
                }, 1000)
            } else {
                this.deleteItem(this.meal_id);
            }
        },
        deleteItem(id) {
            this.$emit('delete', id);
        },
        numberChange: function (numberChange) {
            this.quantity += numberChange;
        }
    }
}
</script>

<style scoped lang="scss">
.quantity {
    display: flex;
    align-items: center;
    margin-left: 20px;
    @media screen and (min-width: 341px) and (max-width: 680px) {
        margin-left: 10px;
    }
    @media screen and (max-width: 340px) {
        margin-left: 0px;
    }
}

.quantity-btn {
    width: 26px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    cursor: pointer;
    color: #000;
    transition: .2s linear;
    -moz-user-select: none;
    -ms-user-select: none;
    -khtml-user-select: none;
    -webkit-user-select: none;

    &[disable] {
        pointer-events: none;
        opacity: .5;
    }

    span {
        font-size: 24px;
        line-height: 1;
    }

    @media screen and (min-width: 992px) {
        &:hover {
            color: var(--purple);
        }
    }
}

.quantity-number {
    font-size: 16px;
    height: 18px;
    width: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
    cursor: default;
    transition: .2s linear;
}

/*.card-product {
    display: flex;
    flex-direction: row;
    padding: 15px 20px;
    margin-top: 3px;
    margin-bottom: 20px;
    background: var(--white);
    border-radius: 8px;
    box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.2);

    @media screen and (max-width: 767px) {
        flex-direction: column;
        padding: 15px 10px;
    }

    h4 {
        font-size: 16px;
        line-height: 130%;
        max-height: 40px;
        overflow: hidden;
        -webkit-line-clamp: 2;
        display: -webkit-box;
        -webkit-box-orient: vertical;
    }


    &__image {
        width: 90px;
        min-width: 90px;
        height: 90px;
        border-radius: 8px;
        object-fit: cover;
        @media screen and (max-width: 767px) {
            width: 80px;
            min-width: 80px;
            height: 80px;
        }
    }

    &__info {
        width: calc(100% - 240px);
        display: flex;
        @media screen and (max-width: 767px) {
            width: 100%;
        }
    }

    &__total {
        width: 240px;
        display: flex;
        font-size: 14px;
        line-height: 100%;
        padding: 8px 0px;
        align-items: center;
        @media screen and (max-width: 767px) {
            width: 100%;
            padding: 15px 0px 0px 0px;
        }
    }

    &__calories, &__cost {
        width: 50%;
        text-align: right;
        padding-left: 8px;
        font-weight: 400;
        @media screen and (max-width: 767px) {
            text-align: center;
        }
    }

    &__description {
        display: flex;
        flex-direction: column;
        width: 100%;
        padding: 8px 10px;
        @media screen and (max-width: 767px) {
            padding: 8px 0 8px 10px;
        }
    }

    button.show-details {
        padding-left: 16px;

        &:after {
            border-color: #000 transparent transparent transparent;
            left: 0;
        }

        @media screen and (max-width: 480px) {
            margin-right: auto;
        }
    }

    &__actions {
        display: flex;
        flex-direction: row;
        font-size: 14px;
        line-height: 138.18%;
        color: var(--primary);
        margin-top: 3px;
        -moz-user-select: none;
        -ms-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;

        .button-food-action {
            @media screen and (max-width: 480px) {
                margin-right: auto;
            }
        }
    }

    &__button-details {
        display: flex;
        flex-direction: row;
        margin-right: 40px;
    }

    .product-details {
        font-size: 14px;
        line-height: 100%;
        font-weight: 600;

        @media screen and (max-width: 767px) {
            width: calc(100% + 90px);
            margin-left: -90px;
        }

        .product-details__params {
            @media screen and (max-width: 767px) {
                padding-left: 10px;
            }
        }

        &__divider {
            background: var(--grey-5);
            width: 100%;
            margin-bottom: 20px;
            display: block;
            height: 1px;
            margin-top: 20px;
        }

        &__header, &__row {
            display: flex;
            flex-direction: row;
            @media screen and (max-width: 767px) {
                justify-content: space-evenly;
            }
        }

        &__row {
            font-size: 14px;
        }

        &__header {
            font-size: 12px;
            line-height: 100%;
            font-weight: normal;
            margin-bottom: 8px;
        }
    }
}

.m-r-40 {
    margin-right: 40px;
}

.basis_20 {
    width: calc(100% / 3);
    padding-right: 10px;
    max-width: 120px;
}*/
</style>


