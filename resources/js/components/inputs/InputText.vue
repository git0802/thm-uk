<template>
    <div class="input-container">
        <div class="input-container__data" :class="{ 'error' : computedValidationState === 2, 'correct': computedValidationState === 1 }">
            <input class="input-container__entry"
                type="text"
                :placeholder="inputPlaceHolder"
                :disabled="isDisabled"
                v-model="inputData"
                @blur="updateValue"
            />
            <button-input-state
                :state="computedValidationState"
                @clear="clear"
            />
        </div>
    </div>
</template>

<script>
    import ButtonInputState from "../buttons/ButtonInputState"

    export default {
        name: "InputText",
        components: {
            ButtonInputState
        },
        props: {
            placeHolder: {
                type: String,
                required: true,
                default: ''
            },
            errorMessage: {
                type: String,
                required: true,
                default: ''
            },
            isDisabled: {
                type: Boolean,
                default: false
            },
            validationState: {
                type: Number,
                required: true,
                default: 0
            },
            initValue: {
                type: String,
                required: false,
                default: ''
            }
        },
        data() {
            return {
                inputData: '',
                inputPlaceHolder: ''
            }
        },
        created() {
            this.inputPlaceHolder = this.placeHolder;

            this.setInitValue(this.initValue);
        },
        methods: {
            updateValue() {
                this.$emit('validation', this.inputData);
            },
            clear() {
                this.inputData = '';
            },
            setInitValue(value) {
                if (value) {
                    this.inputData = value;
                    this.updateValue();
                }
            }
        },
        computed: {
            computedValidationState() {
                if (this.validationState === 1) {
                    this.inputPlaceHolder = this.placeHolder;
                } else if (this.validationState === 2) {
                    this.inputPlaceHolder = this.errorMessage;
                }

                return this.validationState;
            },
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../sass/_mixins.scss';

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


