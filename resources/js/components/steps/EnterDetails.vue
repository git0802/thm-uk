<template>
    <div class="enter-details">
        <form>
            <div class="enter-details__row-gender m-b-24">
                <select-params
                    class="enter-details__gender"
                    :name="'gender'"
                    :options="['Male', 'Female']"
                    :placeHolder="'Gender'"
                    :errorMessage="'*Gender'"
                    :disabled="false"
                    :preselect-value="params.gender"
                    @validation="validationSelectParam"
                />
                <input-user-params
                    class="enter-details__age"
                    :name="'age'"
                    :placeHolder="'Age'"
                    :minValue="12"
                    :maxValue="120"
                    :errorMessage="'*Age from 12 to 120'"
                    :init-value="params.age"
                    @validation="validationParam"
                />
            </div>
            <div class="enter-details__row">
                <input-user-params
                    class="enter-details__weight"
                    :name="'weight'"
                    :placeHolder="'Weight (' + phrase.weightSm + ')'"
                    :minValue="$phrase.minWeight"
                    :maxValue="$phrase.maxWeight"
                    :errorMessage="`*Enter weight from ${$phrase.minWeight} to ${$phrase.maxWeight}`"
                    :init-value="params.weight"
                    @validation="validationParam"
                />

                <div class="enter-details__heightCM">
                    <input-user-height
                        :init-value="params.height"
                        :class-name="'input-container__entry'"
                        @validation="validationUserHeight"
                    />
                </div>
            </div>
            <div class="enter-details__row" v-if="showNext">
                <button-next
                    :isDisabled="isDisabledNextButton"
                    :text="'CONTINUE TO FREE TRIAL'"
                    @click="nextStep"
                />
            </div>
        </form>

    </div>
</template>

<script>
    import { mapGetters, mapMutations } from "vuex";

    export default {
        props:{
            showNext: {
                type: Boolean,
                default: true
            }
        },
        data() {
            return {
                isDisabledNextButton: true,
                validationParamsForm: {
                    gender: false,
                    age: false,
                    weight: false,
                    height: false,
                }
            }
        },
        computed: {
            ...mapGetters({
                params: 'params/getUserParams',
            }),
        },
        methods: {
            ...mapMutations({
                setUserParam: 'params/setUserParam',
            }),
            validationUserHeight(validated, value) {
                this.validationParamsForm.height = validated == 1 ? true : false;
                this.height = value;

                this.setUserParam({
                    key: 'height',
                    value: value
                });

                this.validationForm();
            },
            nextStep() {
                this.$emit('next');
            },
            validationSelectParam(name, state, value) {
                if (state) {
                    this.setUserParam({
                        key: name,
                        value: value
                    });
                } else {
                    this.setUserParam({
                        key: name,
                        value: ''
                    });
                }

                this.validationParam(name, state);
            },
            validationParam(value, state) {
                for (let key in this.validationParamsForm) {
                    if (key === value) {
                        this.validationParamsForm[key] = state;
                    }
                }

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
                this.isDisabledNextButton = isValid;
                this.$emit('validation', 'details', !isValid, !isValid);
            }
        },
    }
</script>

<style lang="scss">
    .enter-details {
        display: flex;
        flex-direction: column;
        max-width: 980px;

        &__row {
            display: flex;
            flex-direction: row;
            justify-content: center;

            @media screen and (max-width: 600px) {
                flex-direction: column;
            }
        }

        &__row-gender {
            display: flex;
            flex-direction: row;
            justify-content: center;

            @media screen and (max-width: 600px) {
                flex-direction: row;
            }
        }

        &__gender {
            .v-select {
                .vs__selected, .vs__search {
                    font-weight: 300;
                }
            }
        }

        &__gender, &__weight {
            flex-basis: 50%;
            margin-right: 20px;

            @media screen and (max-width: 600px) {
                margin-right: 10px;
                margin-bottom: 20px;
            }
        }

        &__weight {
            @media screen and (max-width: 600px) {
                margin-right: 0px !important;
            }
        }

        &__age, &__heightCM {
            flex-basis: 50%;
        }
    }

    .m-b-24 {
        margin-bottom: 24px;

        @media screen and (max-width: 600px) {
            margin-bottom: 0px;
        }

    }

    .m-b-47 {
        margin-bottom: 47px;

        @media screen and (max-width: 600px) {
            margin-bottom: 0px;
        }
    }
</style>
