<template>
    <div class="input-container">
        <div class="input-container__data" :class="{ 'error' : validationState === 2, 'correct': validationState === 1 }">
            <input class="input-container__entry"
                :type="computedTypeViewPassword"
                :placeholder="inputPlaceHolder"
                v-model="inputData"
                @blur="updateValue"
                @input="updateValue"
            />
            <button-input-state
                :type="'password'"
                :state="validationState"
                @click="showPassword = !showPassword"
            />
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                name: 'password',
                inputData: '',
                inputPlaceHolder: 'Password',
                validationState: 0,
                errorMessage: '*Password',
                showPassword: false,
            }
        },
        computed: {
            computedTypeViewPassword() {
                return this.showPassword ? 'text' : 'password';
            }
        },
        methods: {
            updateValue() {
                this.validationData(this.inputData);
            },
            validationData(value) {
                const MIN_CHAR_NUMBER = 8;
                const MAX_CHAR_NUMBER = 32;
                let isValid = false;

                if (value.length >= MIN_CHAR_NUMBER && value.length <= MAX_CHAR_NUMBER) {
                    this.validationState = 1;
                    this.inputPlaceHolder = 'Password';
                    isValid = true;

                } else {
                    this.validationState = 2;
                    this.inputPlaceHolder = this.errorMessage;
                    isValid = false;
                }

                this.$emit('validation', this.name, isValid, value);
            },
            clear() {
                this.inputData = '';
            }
        }
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

