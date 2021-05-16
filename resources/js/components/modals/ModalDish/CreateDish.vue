<template>
    <div class="dish">
        <button-go-back
            @click="actionsModal({
                name: 'modalDish',
                action: 'close'
            })"
        />
        <h3>Group Products Together</h3>
        <div class="dish__group">
            <div class="dish__details">
                <input-dish-name class="m-b-25"
                                 @validation="validationParam"
                />
                <select-params class="m-b-25"
                               :options="productsList"
                               :disabled="false"
                               :placeHolder="'Products'"
                               :preselect-value="'Products'"
                               :is-lock="true"
                />
                <input-disabled class="dish__calories m-b-25"
                                :placeHolder="'Calories Per Serving:'"
                                :value="`Calories: ${dish.calories}`"
                />
                <input-disabled class="dish__cost"
                                :placeHolder="'Avg. Cost of Item:'"
                                :value="`Avg. Cost of Item: £${Math.ceil(dish.cost * 100)/100}`"
                />
                <div class="dish__message">
                    <warning-message v-if="showMessage"
                                     :message="errors.length ? errors[0] : ''"
                    />
                </div>
            </div>
            <figure class="dish__products">
                <div class="dish__product-images">
                    <img v-for="(product, index) in dish.products" :key="index" alt="Food image"
                         class="dish__product-image"
                         :src="product.image"
                         onerror="this.src='/images/no-product-image.jpg?t=0'"
                    />
                </div>
                <div class="dish__products-over">{{ dish.overProducts }}</div>
            </figure>
        </div>
        <div class="dish__saveBtn">
            <button-save
                :isDisabled="isDisabledSaveButton || isBusy"
                @click="createDish({
                    name: dishName,
                    store: selectedStore.id,
                    products: choosedFood,
                    meals: choosedMeal,
                    image: '',
                    eating: dish.eating
                })"
            />
        </div>
    </div>
</template>

<script>
import {mapGetters, mapMutations, mapActions} from "vuex";
import ButtonGoBack from "../../buttons/ButtonGoBack";
import InputDishName from "../../inputs/inputsDish/InputDishName";
import SelectParams from "../../selects/SelectParams";
import InputDisabled from "../../inputs/InputDisabled";
import WarningMessage from "../../messages/WarningMessage";
import ButtonSave from "../../buttons/ButtonSave";

export default {
    components: {
        ButtonGoBack,
        InputDishName,
        SelectParams,
        InputDisabled,
        WarningMessage,
        ButtonSave
    },
    data() {
        return {
            isDisabledSaveButton: true,
            dishName: '',
            defaultImage: '/images/default-product.png',
            dish: {
                calories: 0,
                cost: 0,
                products: [],
                overProducts: '',
                eating: '',
            },
            productsList: []
        }
    },
    computed: {
        ...mapGetters({
            isBusy: 'dish/getIsBusy',
            errors: 'dish/getErrors',
            selectedStore: 'stores/getSelectedStore',
            choosedMeal: 'food/getChoosedMeal',
            choosedFood: 'food/getChoosedFood',
            myFood: 'food/getMyFood'
        }),
        showMessage() {
            return !!this.errors.length;
        },
    },
    created() {
        this.init();
    },
    methods: {
        ...mapMutations({
            clearErrors: 'dish/clearErrors'
        }),
        ...mapActions({
            actionsModal: 'modals/actionsModal',
            createDish: 'dish/createDish'
        }),

        init() {
            this.findProducts()
            this.createProductsList();

            if (this.dish.products.length <= 4) {
                this.addDefaultImages();
            } else {
                this.dish.overProducts = `+${this.dish.products.length - 4}`;
                this.dish.products.splice(4);
            }
        },
        findProducts() {
            this.myFood.forEach((food) => {
                this.dish.calories += Number(food.calories);
                this.dish.cost += food.cost;
                this.dish.eating = food.eating;
                this.dish.products.push({
                    name: food.name,
                    image: food.image,
                });
            })
        },
        createProductsList() {
            this.dish.products.forEach((item) => {
                this.productsList.push(item.name);
            })
        },
        addDefaultImages() {
            const numberDefaultImages = 4 - this.dish.products.length;

            for (let i = 0; i < numberDefaultImages; i++) {
                this.dish.products.push({
                    name: 'Default',
                    image: this.defaultImage
                });
            }
        },
        validationParam(name, state, value) {
            if (this.errors.length) {
                this.clearErrors();
            }

            if (state) {
                this.isDisabledSaveButton = false;
                this.dishName = value;
            } else {
                this.isDisabledSaveButton = true;
                this.dishName = '';
            }
        },
    }
}
</script>

<style lang="scss">
@import '../../../../sass/_mixins.scss';

.dish {
    h3 {
        @include title-font;

        margin-bottom: 26px;
    }

    &__group {
        display: flex;
        flex-direction: row;
        margin-bottom: 2.6875rem;

        @media screen and (max-width: 1000px) {
            flex-direction: column;
        }
    }

    &__details {
        max-width: 480px;
        margin-right: 35px;

        .vs__dropdown-menu {
            .vs__dropdown-option--highlight {
                border-radius: unset !important;
                background: unset !important;
                color: unset !important;
                cursor: auto !important;
            }
        }

        @media screen and (min-width: 1200px) {
            width: 480px;
        }

        @media screen and (max-width: 600px) {
            margin-right: 0px;
        }
    }

    &__message {
        min-height: 40px;
        padding: 8px;

        .warning-message {
            justify-content: start;
        }
    }

    &__cost, &__calories {
        flex-basis: 50%;
    }

    &__products {
        display: flex;
        align-items: center;
        max-height: 120px;
    }

    &__product-images {
        display: flex;
        flex-wrap: wrap;
        max-width: 120px;
    }

    &__product-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
        margin-left: 8px;
        margin-bottom: 8px;
    }

    &__products-over {
        @include head-24-font;

        margin-left: 10px;
    }

    &__saveBtn {
        display: flex;
        flex-direction: row;
        justify-content: center;

        @media screen and (max-width: 600px) {
            flex-direction: column;
        }
    }
}

.m-b-25 {
    margin-bottom: 25px;
}

.m-r-26 {
    margin-right: 26px;
}

.m-r-15 {
    margin-right: 15px;

    @media screen and (max-width: 600px) {
        margin-right: 0px;
        margin-bottom: 25px;
    }
}
</style>
