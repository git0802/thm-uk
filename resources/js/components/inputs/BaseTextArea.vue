<template>
    <div class="input-container">
            <textarea
                class="input-style mb-5"  :class="{ 'error' : validationState === 2, 'correct': validationState === 1 }"
                :value="value"
                :name="name"
                :placeholder="validationState === 2 ? errorMessage : placeHolder"
                @input="$emit('input', $event.target.value); validationCallback($event)"
                @blur="validationCallback"
                :required="required"
                :minlength="min"
                :maxlength="max"
            >
            </textarea>
            <button-input-state class="input-container__state"
                                :state="validationState"
            />

<!--        <div class="input-container__error">-->
<!--            <transition name="fade">-->
<!--                <p v-show="validationState === 2">-->
<!--                    {{ errorMessage }}-->
<!--                </p>-->
<!--            </transition>-->
<!--        </div>-->
    </div>
</template>

<script>
import '../../../sass/components/_vue-select.scss'
import ButtonInputState from "../buttons/ButtonInputState";

export default {
    name: "BaseTextArea",
    components: {ButtonInputState},
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
            required: false,
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
        }
    },
}
</script>

<style scoped lang="scss">
.input-container {
    display: flex;
    flex-direction: column;
    position: relative;
    margin-bottom: 20px;
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

    :focus {
        outline: none;
    }
}

.input-style {
    width: 100%;
    background-color: transparent;
    font-family: inherit;
    font-weight: 500;
    font-style: normal;
    font-size: 15px;
    line-height: 151.19%;
    color: var(--black);
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    position: relative;
    padding: 9px 45px 8px 20px;
    border-radius: 7px;
    border: 1px solid var(--grey);
    background: var(--grey-light);

    &.correct {
        border: 1px solid var(--green);
        background: var(--green-light);
    }

    &.error {
        border: 1px solid var(--red);
        background: var(--red-light);
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
</style>
