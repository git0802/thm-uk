<template>
    <div class="underlined_input-container" @mouseenter="focused = true" @mouseleave="focused = false">
        <div style="flex: none">{{placeHolder}}</div>
        <div class="underlined_input-container__data"
             :class="{ 'error' : validationState === 2, 'correct': validationState === 1 }">
            <input v-if="inputMask" class="underlined_input-container__entry"
                   :type="passworded ? computedTypeViewPassword : type"
                   :ref="'input_'+_uid"
                   :value="value"
                   @input="$emit('input', $event.target.value)"
                   @focus="$emit('focus', $event)"
                   @blur="validationCallback"
                   :name="name"
                   v-mask="inputMask"
                   :required="required"
                   :minlength="min"
                   :maxlength="max"
                   :autocomplete="autocomplete"
                   :disabled="disabled"
                   :style="{color: validationState === 2 && focused === false ? 'transparent' : ''}"
            />
            <input v-else class="underlined_input-container__entry"
                   :type="passworded ? computedTypeViewPassword : type"
                   :ref="'input_'+_uid"
                   :value="value"
                   @input="$emit('input', $event.target.value)"
                   @focus="$emit('focus', $event)"
                   @blur="validationCallback"
                   :name="name"
                   :required="required"
                   :minlength="min"
                   :maxlength="max"
                   :autocomplete="autocomplete"
                   :disabled="disabled"
                   :style="{color: validationState === 2 && focused === false ? 'transparent' : ''}"
            />
            <button-input-state v-if="passworded"
                                :type="'password'"
                                :state="validationState"
                                @click="showPassword = !showPassword"
            />
            <button-input-state v-else
                                :state="validationState"
                                @clear="$emit('input', '')"
            />
            <div class="underlined_input-container__error" v-show="validationState === 2 && focused === false ">
                <transition name="fade">
                    <p v-show="validationState === 2 && focused === false ">
                        {{ errorMessage }}
                    </p>
                </transition>
            </div>
        </div>
        <!--        <div class="underlined_input-container__error">-->
        <!--            <transition name="fade">-->
        <!--                <p v-show="validationState === 2">-->
        <!--                    {{ errorMessage }}-->
        <!--                </p>-->
        <!--            </transition>-->
        <!--        </div>-->
    </div>
</template>Base

<script>
    import '../../../sass/components/_vue-select.scss'
    // import IMask from "imask";
    import { mask } from 'vue-the-mask'

    import ButtonInputState from "../buttons/ButtonInputState";

    export default {
        name: "BaseInputUnderlined",
        directives: { mask },
        components: {
            ButtonInputState,
        },
        props: {
            placeHolder: {
                type: String,
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
            type: {
                type: String,
                required: false,
                default: ''
            },
            value: {},
            inputMask: {},
            validationCallback: {
                type: Function
            },
            validationState: {
                type: Number,
            },
            required: {
                type: Boolean,
                default: false,
            },
            min: {
                type: Number,
                default: null,
            },
            max: {
                type: Number,
                default: null,
            },
            autocomplete: {
                type: String,
                default: null,
            },
            disabled: {
                type: Boolean,
                default: false,
            },
        },
        data() {
            return {
                masker: null,
                passworded: false,
                showPassword: false,
                inputData: '',
                focused: false,
            }
        },
        computed: {
            computedTypeViewPassword() {
                return this.showPassword ? 'text' : 'password';
            }
        },
        mounted() {
            // if (this.mask) {
            //     this.masker = IMask(
            //         this.$refs['input_' + this._uid], {
            //             mask: this.mask,
            //             padFractionalZeros: false,
            //         });
            // }
            if (this.type === 'password') {
                this.passworded = true;
            }
        },

        methods: {
            test() {}
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

            &.correct {
                border-bottom: 1px solid var(--green);
                transition: 400ms;
            }

            &.error {
                border-bottom: 1px solid var(--red);
                transition: 400ms;
            }

        }

        &__error {
            color: red;
            font-size: 16px;
            min-height: 20px;
            font-weight: 400;
            position: absolute;
            left: 0px;
            top: 0;
            background-color: transparent !important;
            height: 100%;
            width: 85%;
            align-items: center;
            display: flex;
            z-index: 5;
        }

        &__entry {
            width: 100%;
            background: transparent;
            z-index: 5;
            position: relative;

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

        &__icon {
            position: absolute;
            top: 27%;
            right: 18px;
            z-index: 1;
        }

        &__state {
            position: absolute;
            right: 5%;
            top: 25%;
            z-index: 0;
        }

        :focus {
            outline: none;
        }
    }
</style>
