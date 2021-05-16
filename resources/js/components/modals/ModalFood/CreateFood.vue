<template>
    <div class="create-food">
        <button-go-back
            @click="actionsModal({
                name: 'modalFood',
                action: 'close'
            }); clearEditFood()"
        />
        <h2>{{ title }}</h2>
        <div>
            <div class="create-food__item">
                <div class="create-food__details">
                    <input-food-name class="m-b-25"
                                     :is-disabled="isEdit"
                                     :init-value="editableFood.name"
                                     @validation="validationParam"
                    />
                    <div class="create-food__serving">
                        <input-serving-size class="create-food__size m-r-15"
                                            :init-value="editableFood.servingSize"
                                            @validation="validationParam"
                        />
                        <input-serving-calories class="create-food__calories"
                                                :init-value="editableFood.calories ? String(editableFood.calories) : ''"
                                                @validation="validationParam"
                        />
                    </div>
                    <input-food-cost class="create-food__cost"
                                     :init-value="editableFood.cost ? String(editableFood.cost) : ''"
                                     @validation="validationParam"
                    />
                    <div class="create-food__message">
                        <warning-message v-if="showMessage"
                                         :message="errors.length ? errors[0] : ''"
                        />
                    </div>
                </div>
                <div class="create-food__add">
                    <button-add-image v-if="!isEdit"
                                      :text="'Add food image'"
                                      :originalImage="editableFood.image"
                                      @saveImage="saveImage"
                    >
                        <icon-food/>
                    </button-add-image>
                    <div v-else class="create-food__box-image">
                        <div class="create-food__image">
                            <img :src="editableFood.image" alt="Food image"/>
                        </div>
                        <h3>Food image</h3>
                    </div>
                </div>
            </div>
            <div class="create-food__save">
                <button-save
                    :isDisabled="isDisabledSaveButton || isBusy"
                    @click="saveFood()"
                />
            </div>
        </div>
        <!--        <div v-else>-->
        <!--            <div class="create-food__error">-->
        <!--                <span>You cannot create a dish in this store. Create your own store.</span>-->
        <!--            </div>-->
        <!--        </div>-->
    </div>
</template>

<script>
import {mapGetters, mapMutations, mapActions} from "vuex";
import ButtonGoBack from "../../buttons/ButtonGoBack";
import InputFoodName from "../../inputs/inputsFood/InputFoodName";
import InputServingSize from "../../inputs/inputsFood/InputServingSize";
import InputServingCalories from "../../inputs/inputsFood/InputServingCalories";
import InputFoodCost from "../../inputs/inputsFood/InputFoodCost";
import WarningMessage from "../../messages/WarningMessage";
import CompleteMessage from "../../messages/CompleteMessage";
import ButtonAddImage from "../../buttons/ButtonAddImage";
import IconFood from "../../svg/IconFood";
import ButtonSave from "../../buttons/ButtonSave";

export default {
    components: {
        ButtonGoBack,
        InputFoodName,
        InputServingSize,
        InputServingCalories,
        InputFoodCost,
        WarningMessage,
        CompleteMessage,
        ButtonAddImage,
        IconFood,
        ButtonSave
    },
    data() {
        return {
            isDisabledSaveButton: true,
            validationParamsForm: {
                name: false,
                servingSize: false,
                calories: false,
                cost: false,
            },
            food: {
                name: '',
                servingSize: '',
                calories: '',
                cost: 0,
                image: '',
                storeId: ''
            },
            isEdit: false,
            title: ''
        }
    },
    computed: {
        ...mapGetters({
            selectedStore: 'stores/getSelectedStore',
            isBusy: 'food/getIsBusy',
            myFoodChoosed: 'food/getMyFoodChoosed',
            errors: 'food/getErrors',
            editableFood: 'food/getEditableFood'
        }),

        showMessage() {
            return !!this.errors.length;
        },
    },
    created() {
        this.checkEdit();
    },
    methods: {
        ...mapMutations({
            clearErrors: 'food/clearErrors',
            clearEditFood: 'food/clearEditFood'
        }),
        ...mapActions({
            actionsModal: 'modals/actionsModal',
            createFood: 'food/createFood',
        }),

        checkEdit() {
            // this.isEdit = !!Object.keys(this.editableFood).length || !this.editableFood.is_custom;
            if(this.editableFood.is_custom) {
                this.isEdit = false
            } else if (!!Object.keys(this.editableFood).length) {
                this.isEdit = !!Object.keys(this.editableFood).length
            }
            this.title = (this.isEdit || this.editableFood.is_custom ) ? 'Edit food item' : 'Add Your Own Food Item';
        },
        validationParam(name, state, value) {
            for (let key in this.validationParamsForm) {
                if (key === name) {
                    this.validationParamsForm[key] = state;
                }
            }

            this.setParam(name, state, value);
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
            this.isDisabledSaveButton = isValid;
        },
        setParam(name, state, value) {
            if (state) {
                this.food[name] = value;
            } else {
                this.food[name] = '';
            }
        },
        saveImage(file) {
            //Save file with image
            this.food.image = file;
        },
        saveFood() {
            this.food.cost = Number(this.food.cost);
            this.food.storeId = this.selectedStore.id;
            this.createFood(this.food);
        }
    }
}
</script>

<style scoped lang="scss">
@import '../../../../sass/_mixins.scss';

.create-food {
    h2 {
        @include title-font;

        margin-bottom: 26px;
    }

    &__error {
        color: red;
        font-weight: 400;
    }

    &__item {
        display: flex;
        flex-direction: row;
        margin-bottom: 112px;

        @media screen and (max-width: 1000px) {
            flex-direction: column;
        }

        @media screen and (max-width: 991px) {
            margin-bottom: 60px;
        }
    }

    &__details {
        max-width: 480px;
        margin-right: 40px;

        @media screen and (min-width: 1200px) {
            width: 480px;
        }

        @media screen and (max-width: 600px) {
            margin-right: 0px;
        }
    }

    &__serving, {
        display: flex;
        flex-direction: row;
        align-items: center;
        margin-bottom: 25px;

        @media screen and (max-width: 600px) {
            flex-direction: column;
            align-items: normal;
        }
    }

    &__cost {
        margin-bottom: 0px;

        @media screen and (max-width: 1000px) {
            margin-bottom: 25px;
        }
    }

    &__message {
        min-height: 36px;
        padding: 8px;

        .warning-message, .complete-message {
            justify-content: start;
        }
    }

    &__box-image {
        display: flex;
        align-items: center;

        h3 {
            @include head-24-font;
        }
    }

    &__image {
        width: 114px;
        min-width: 114px;
        height: 114px;
        border-radius: 50%;
        border: 2px solid var(--primary);
        margin-right: 26px;

        @media screen and (max-width: 767px) {
            width: 80px;
            min-width: 80px;
            height: 80px;
            margin-right: 20px;
        }

        img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
    }

    &__size, &__calories {
        flex-basis: 50%;
    }

    &__add {
        display: flex;
        align-items: flex-start;
    }

    &__save {
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
