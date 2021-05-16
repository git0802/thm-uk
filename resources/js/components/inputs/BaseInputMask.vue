<template>
    <div class="input-container">
        <div class="input-container__data"
             :class="{ 'error' : validationState === 2, 'correct': validationState === 1 }">
            <input class="input-container__entry"
                   :type="passworded ? computedTypeViewPassword : type"
                   :ref="'input_'+_uid"
                   :placeholder="placeHolder"
                   :value="value"
                   @input="$emit('input', $event.target.value); validationCallback($event)"
                   @blur="validationCallback"
                   v-mask="maskCode"
                   :name="name"
                   :required="required"
                   :minlength="min"
                   :maxlength="max"
                   :autocomplete="autocomplete"
                   :disabled="disabled"
            />
            <button-input-state v-if="passworded"
                :type="'password'"
                :state="validationState"
                @click="showPassword = !showPassword"
            />
            <button-input-state v-else
                :state="validationState"
                @clear="$refs['input_' + _uid].value=''"
            />
        </div>
        <div class="input-container__error">
            <transition name="fade">
                <p v-show="validationState === 2">
                    {{ errorMessage }}
                </p>
            </transition>
        </div>
    </div>
</template>Base

<script>
import '../../../sass/components/_vue-select.scss'
import ButtonInputState from "../buttons/ButtonInputState";
import { mask } from 'vue-the-mask'

export default {
    name: "BaseInputMask",
    components: {ButtonInputState},
    directives: { mask },
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
        value: {
            type: String
        },
        validationCallback: {
            type: Function
        },
        validationState: {
            type: Number,
        },
        maskCode: {
            type: String,
            required: false,
            default: ''
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
            inputData: ''
        }
    },
    computed: {
        computedTypeViewPassword() {
            return this.showPassword ? 'text' : 'password';
        }
    },
    mounted() {
        if (this.type === 'password') {
            this.passworded = true;
        }
    },

}
</script>

<style scoped lang="scss">
.input-container {
    display: flex;
    flex-direction: column;

    &__data {
        position: relative;
        background: #F9F9F9;
        padding: 9px 45px 8px 20px;
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
        font-size: 12px;
        min-height: 20px;
        font-weight: 400;
        margin-top: 5px;
    }

    &__entry {
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
