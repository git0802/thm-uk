<template>
    <button type="button" class="button-apply" :class="{ 'disabled-valid': isDisabled, 'disabled-success': isSuccess }"
        :disabled="isDisabled || isSuccess"
        @click="$emit('click')"
    >{{ computedButtonText }}
    </button>
</template>

<script>
    export default {
        name: "ButtonApplyCoupon",
        props: {
            isDisabled: {
                type: Boolean,
                required: true,
                default: false
            },
            isSuccess: {
                type: Boolean,
                required: true,
                default: false
            }
        },
        computed: {
            computedButtonText() {
                return this.isSuccess ? 'CONFIRMED': 'APPLY'
            }
        }
    }
</script>

<style lang="scss">
    @import '../../../sass/_mixins.scss';

    .button-apply {
        @include button-text-font;

        width: 100%;
        background: var(--primary);
        border-radius: 7px;
        padding: 9px 13px;

        &:not(.disabled-valid):not(.disabled-success):hover {
            cursor: pointer;
            background: var(--primary-dark);
            transition: 400ms;
        }

        svg {
            display: inline-block;
            margin-right: 8px;

            .icon {
                fill: var(--white);
            }
        }
    }

    .disabled-valid {
        cursor: auto;
        background: var(--grey-2);
    }

    .disabled-success {
        cursor: auto;
    }
</style>
