<template>
    <div class="input-container">
        <div class="input-container__data" :class="{ 'error' : computedValidationState === 2, 'correct': computedValidationState === 1 }">
            <input class="input-container__entry"
                type="tel"
                ref="input"
                :placeholder="inputPlaceHolder"
                :disabled="isDisabled"
                v-model="inputData"
                v-mask="maskCode"
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
    import { mask } from 'vue-the-mask'

    export default {
        directives: { mask },
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
            maskCode: {
                type: String,
                required: true,
                default: ''
            }
        },
        data() {
            return {
                inputData: '',
                inputPlaceHolder: '',
            }
        },
        created() {
            this.inputPlaceHolder = this.placeHolder;
        },
        methods: {
            updateValue() {
                this.$emit('validation', this.inputData);
            },
            clear() {
                this.inputData = '';
            },
        },
        computed: {
            computedValidationState() {
                if (this.validationState === 1) {
                    this.inputPlaceHolder = this.placeHolder;
                } else if (this.validationState === 2) {
                    this.inputPlaceHolder = this.errorMessage;
                }

                return this.validationState;
            }
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../sass/_mixins.scss';

    .input-container {
        @include base-font;
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

            width: 100%;
            background: transparent;

            .error & {
                color: var(--red);
            }

            &::placeholder {
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


