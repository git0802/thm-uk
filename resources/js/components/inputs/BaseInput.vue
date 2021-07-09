<template>
    <div class="input-container" @mouseenter="focused = true" @mouseleave="focused = false">
        <div class="input-container__data"
             :class="{ 'error' : validationState === 2, 'correct': validationState === 1 }">
            <input v-if="inputMask" class="input-container__entry"
                   :type="passworded ? computedTypeViewPassword : type"
                   :ref="'input_'+_uid"
                   :placeholder="validationState === 2 && focused === false ? '' : placeHolder"
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
            <input v-else class="input-container__entry"
                   :type="passworded ? computedTypeViewPassword : type"
                   :ref="'input_'+_uid"
                   :placeholder="validationState === 2 && focused === false ? '' : placeHolder"
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
            <div class="input-container__error" v-show="validationState === 2 && focused === false ">
                <transition name="fade">
                    <p v-show="validationState === 2 && focused === false ">
                        {{ errorMessage }}
                    </p>
                </transition>
            </div>
        </div>
<!--        <div class="input-container__error">-->
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
    name: "BaseInput",
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
    @import '../../../sass/_mixins.scss';
.input-container {
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
    &__data {
        position: relative;
        background: #F9F9F9;
        padding: 9px 10px 8px 20px;
        border-radius: 7px;
        border: 1px solid #B6B6B6;

        &.correct {
            border: 1px solid var(--green);
            background: var(--green-light);
        }

        &.error {
            border: 1px solid var(--red);
            background: var(--red-light);
        }

    }

    &__error {
        color: red;
        font-size: 16px;
        min-height: 20px;
        font-weight: 400;
        position: absolute;
        left: 20px;
        top: 0;
        background-color: #fae6e6;
        height: 100%;
        width: 85%;
        align-items: center;
        display: flex;
        z-index: 5;
    }

    &__entry {
        @include base-font;
        width: 100%;
        background: transparent;
        color: var(--black);
        z-index: 5;
        position: relative;

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
