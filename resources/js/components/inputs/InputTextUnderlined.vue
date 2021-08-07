<template>
    <div class="underlined_input-container">
        <div style="flex: none">{{inputPlaceHolder}}</div>
        <div class="underlined_input-container__data" :class="{ 'error' : computedValidationState === 2, 'correct': computedValidationState === 1 }">
            <input class="underlined_input-container__entry"
                   type="text"
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
        name: "InputTextUnderlined",
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

    .underlined_input-container {
        display: flex;
        flex-direction: row;
        align-items: flex-end;
        font-family: Lato;
        font-style: normal;
        font-weight: 500;
        font-size: 18px;
        line-height: 22px;
        color: #272C32;

        @media screen and (max-width: 600px) {
            font-size: 14px !important;
            line-height: 17px !important;
        }

        &__data {
            margin-left: 10px;
            position: relative;
            padding: 9px 25px 1px 0px;
            border-bottom: 1px solid #B6B6B6;
            width: 100%;

            &.error {
                border-bottom: 1px solid var(--red);
                transition: 400ms;
            }

            &.correct {
                border-bottom: 1px solid var(--green);
                transition: 400ms;
            }
        }

        &__entry {
            width: 100%;
            background: transparent;

            .error & {
                color: var(--red);
            }

            &::placeholder {
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


