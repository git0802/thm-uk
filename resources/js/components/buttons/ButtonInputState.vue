<template>
    <div class="input-state">
        <div v-if="type === 'password'" class="input-state__password" :class="{'correct': state === 1, 'error': state === 2 }"
             @click="$emit('click'), showPassword = !showPassword"
        >
            <icon-show-password v-if="showPassword"/>
            <icon-hide-password v-else/>
        </div>
        <div v-else class="input-state__text">
            <icon-correct v-if="state === 1"/>
            <div class="input-state__clear"
                @click="$emit('clear')"
            >
                <icon-error v-if="state === 2"/>
            </div>
        </div>
    </div>
</template>

<script>
import IconHidePassword from "../../../js/components/svg/IconHidePassword";
import IconShowPassword from "../../../js/components/svg/IconShowPassword";
import IconCorrect from "../../../js/components/svg/IconCorrect";
import IconError from "../../../js/components/svg/IconError";

    export default {
        name: "ButtonInputState",
        components: {IconHidePassword, IconShowPassword, IconCorrect, IconError},
        props: {
            type: {
                type: String,
                default: 'text'
            },
            state: {
                type: Number,
                required: true,
                default: 1
            }
        },
        data() {
            return {
                showPassword: false,
            }
        }
    }
</script>

<style lang="scss">
    .input-state {
      position: absolute;
      right: 5px;
      top: 33%;
      z-index: 5;
        .correct {
            .outside,.inside {
                fill: var(--green);
            }

            .line {
                stroke: var(--green);
            }
        }

        .error {
            .outside,.inside {
                fill: var(--red);
            }

            .line {
                stroke: var(--red);
            }
        }

        &__text, &__password {
            width: 21px;
            text-align: center;
        }

        &__clear, &__password {
            cursor: pointer;
        }
    }
</style>


