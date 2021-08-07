<template>
    <div class="input-container">
        <div class="input-container__data" :class="{ 'error' : validationState === 2, 'correct': validationState === 1 }">
            <input class="input-container__entry"
                type="tel"
                :placeholder="inputPlaceHolder"
                v-model="inputData"
                @blur="updateValue"
            />
            <button-input-state
                :state="validationState"
                @clear="clear"
            />
        </div>
    </div>
</template>

<script>
    import { mapMutations } from "vuex";
    import ButtonInputState from "../buttons/ButtonInputState";
    export default {
        components: {ButtonInputState},
        props: {
            placeHolder: {
                type: String,
                required: true
            },
            minValue: {
                type: Number,
                required: true
            },
            maxValue: {
                type: Number,
                required: true
            },
            errorMessage: {
                type: String,
                required: true
            },
            name: {
                type: String,
                required: true,
                default: ''
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
                /*isValid for Emit*/
                isValid: false,
                validationState: 0,
                inputPlaceHolder: ''
            }
        },
        computed: {
            user(){
                return this.$store.getters['auth/user']
            }
        },
        created() {
            this.inputPlaceHolder = this.placeHolder;

            this.setInitValue(this.initValue);
        },
        mounted() {
            if(window.location.pathname === '/weekly-plan'
                || window.location.pathname === '/meal-planner/setup'
                || window.location.pathname === '/meal-planner/planner/'
                || window.location.pathname === '/store'){

                this.setInitValue(this.user.weight)
            }
        },
        methods: {
            ...mapMutations({
                setUserParam: 'params/setUserParam',
            }),

            updateValue() {
                this.isValid = this.validationData();

                if (this.isValid) {
                    this.setUserParam({
                        key: this.name,
                        value: this.inputData
                    });
                    this.validationState = 1;
                    this.inputPlaceHolder = this.placeHolder;
                } else {
                    this.setUserParam({
                        key: this.name,
                        value: ''
                    });
                    this.validationState = 2;
                    this.inputPlaceHolder = this.errorMessage;
                }

                this.$emit('validation', this.name, this.isValid, this.inputData);
            },
            validationData() {
                const symbolsInputData = String(this.inputData).split('');
                const isCorrectInputData = this.validationCorrectSymbols(symbolsInputData);

                if (isCorrectInputData) {
                    return this.inputData >= this.minValue && this.inputData <= this.maxValue;
                }

                return false;
            },
            validationCorrectSymbols(symbols) {
                let isValid = true;

                for (let i = 0; i < symbols.length; i++) {
                    if (symbols[i] < '0' || symbols[i] > '9') {
                        isValid = false;
                        break;
                    }
                }

                return isValid;
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


