<template>
    <div class="input-container">
        <div class="input-container__data" :class="{ 'error' : computedValidationState === 2, 'correct': computedValidationState === 1 }">
            <input class="input-container__entry"
                type="text"
                :placeholder="inputPlaceHolder"
                :disabled="isDisabled"
                v-model="inputData"
                @blur="updateValue"
                @input="updateValue"
                @focus="clearErrors"
            />
            <input type="hidden" v-model="computedIsClearField">
            <button-input-state class="input-container__state"
                :state="computedValidationState"
                @clear="clear"
            />
        </div>
    </div>
</template>

<script>
    import { mapMutations } from "vuex";

    export default {
        props: {
            isDisabled: {
                type: Boolean,
                required: true,
                default: false
            },
            isClear: {
                type: Boolean,
                required: true,
                default: false
            }
        },
        data() {
            return {
                inputData: '',
                inputPlaceHolder: 'Enter the code',
                errorMessage: '*Enter the code',
                validationState: 0,
                name: 'coupon'
            }
        },
        watch: {
            errors(val){
                if(val.length > 0){
                    this.validationState = 2
                } else {
                    this.validationState = 1
                }
            }
        },
        computed: {
            errors(){
                return this.$store.getters['info/getErrors']
            },
            computedValidationState() {
                if (this.validationState === 1) {
                    this.inputPlaceHolder = 'Enter the code';
                } else if (this.validationState === 2) {
                    this.inputPlaceHolder = this.errorMessage;
                }

                return this.validationState;
            },
            computedIsClearField() {
                if (this.isClear) {
                    this.clear();
                    this.validationData('');
                    this.validationState = 0;
                    this.$emit('clear');
                }
            }
        },
        methods: {
            ...mapMutations({
                clearErrors: 'info/clearErrors'
            }),

            updateValue() {
                this.validationData(this.inputData);
            },
            clear() {
                this.inputData = '';
            },
            validationData(value) {
                const MIN_CHAR_NUMBER = 4;
                const MAX_CHAR_NUMBER = 32;
                let isValid = false;

                if (value.length >= MIN_CHAR_NUMBER && value.length <= MAX_CHAR_NUMBER) {
                    this.validationState = 1;
                    isValid = true;
                } else {
                    this.validationState = 2;
                    isValid = false;
                }

                this.$emit('validation', this.name, isValid, value);
            },
        },
    }
</script>

<style scoped lang="scss">
    @import '../../../../../sass/_mixins.scss';

    .input-container {
        display: flex;
        flex-direction: column;

        &__data {
            position: relative;
            background: #F9F9F9;
            padding: 9px 45px 8px 20px;
            border-radius: 7px;
            border: 1px solid #B6B6B6;

            &.error {
                border: 1px solid var(--red);
                background: var(--red-light);
                transition: 400ms;
            }

            &.correct {
                border: 1px solid var(--green);
                background: var(--green-light);
                transition: 400ms;
            }
        }

        &__entry {
            @include base-font;

            width: 100%;
            background: transparent;
            color: var(--black);

            .error & {
                color: var(--red);
            }

            &::placeholder {
                color: var(--black);
                /*Recommended write this for FireFox*/
                opacity: 1;

                .error & {
                    color: var(--red);
                }
            }
        }

        :focus {
            outline: none;
        }
    }
</style>

