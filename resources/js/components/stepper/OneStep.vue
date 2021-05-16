<template>
    <div class="step-container" :class="{ 'last': isLast, 'not-last': !isLast }">
        <div class="step-container__content" :class="{ 'step-disabled': index >= currentStep }" @click="chooseStep">
            <div class="step-container__circle" :class="{ 'active': isCurrentStep || isChecked }">
                <span v-if="!isChecked">{{ content }}</span>
                <icon-checked v-else/>
            </div>
        </div>
        <div v-if="!isLast" class="step-container__divider" :class="{ 'checked': isChecked }">
            <hr/>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            index: {
                type: Number,
                required: true,
                default: 0
            },
            content: {
                type: String,
                required: true
            },
            isCurrentStep: {
                type: Boolean,
                required: true
            },
            isLast: {
                type: Boolean,
                required: true
            },
            isChecked: {
                type: Boolean,
                required: true
            },
            currentStep: {
                type: Number,
                required: true
            }
        },
        methods: {
            chooseStep() {
               this.$emit('choose', this.index);
            }
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../sass/_mixins.scss';

    .step-container {
        @include head-font;

        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        align-items: center;
        line-height: 151.19%;
        color: var(--white);

        &__content {
            &:hover {
                cursor: pointer;
            }
        }

        &__content, &__circle {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }

        &__circle {
            width: 61px;
            height: 61px;
            background: var(--grey-2);
            border-radius: 50%;
            border: 8px solid transparent;
            background-clip: padding-box;
        }

        &__divider {
            display: flex;
            flex-direction: row;
            width: 100%;
            min-width: 16px;

            hr {
                width: 100%;
                height: 1px;
                border: none;
                background-color: var(--grey-2);
            }
        }
    }

    .not-last {
        width: 235px;
    }

    .last {
        width: 61px;
    }

    .active {
        background: var(--primary);
        border-color: var(--primary-light-2);
    }

    .checked {
        hr {
            background-color: var(--primary-light-3);
        }
    }

    .step-disabled {
        pointer-events: none;
        cursor: auto;
    }
</style>
