<template>
    <button class="button-sign-in" :class="{ 'disabled': isDisabled }"
        :disabled="isDisabled"
        @click="$emit('click')"
    >SIGN IN
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
            }
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../sass/_mixins.scss';

    .button-sign-in {
        @include button-text-font;

        background: var(--primary);
        border-radius: 7px;
        padding: 13px 78px 12px;

        &:not(.disabled):hover {
            background: var(--primary-dark);
            transition: 400ms;
        }
    }

    .disabled {
        cursor: auto;
        background: var(--grey-2);
    }
</style>





