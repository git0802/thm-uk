<template>
    <button class="button-weekly-plan" :class="{ 'disabled': isDisabled }"
        :disabled="isDisabled"
        @click="$emit('click')"
    >GET MY WEEKLY PLAN
    </button>
</template>

<script>
    export default {
        name: "ButtonPlan",
        props: {
            isDisabled: {
                type: Boolean,
                required: true,
                default: false
            }
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../sass/_mixins.scss';

    .button-weekly-plan {
        @include button-text-font;

        background: var(--primary);
        border-radius: 7px;
        padding: 13px 51px 12px;

        &:not(.disabled):hover {
            cursor: pointer;
            background: var(--primary-dark);
            transition: 400ms;
        }
    }

    .disabled {
        cursor: auto;
        background: var(--grey-2);
    }
</style>


