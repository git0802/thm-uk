<template>
    <button class="button-next" :class="{ 'disabled': isDisabled }"
        :disabled="isDisabled"
        @click="$emit('click')"
    >{{ text }}
    </button>
</template>

<script>
    export default {
        name: "ButtonNext",

        props: {
            isDisabled: {
                type: Boolean,
                required: true,
                default: false
            },
            text: {
                type: String,
                required: false,
                default: "NEXT STEP"
            }
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../sass/_mixins.scss';

    .button-next {
        @include button-text-font;

        background: var(--primary);
        border-radius: 7px;
        padding: 13px 78px 12px;

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





