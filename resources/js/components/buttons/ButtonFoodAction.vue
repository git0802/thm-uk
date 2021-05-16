<template>
    <div class="button-food-action" :class="{'is-disabled': isDisabled}"
        @click="$emit('click')"
    >
        <slot/>{{ text }}
    </div>
</template>

<script>
    export default {
        props: {
            text: {
                type: String,
                required: true,
                default: 'Food action'
            },
            isDisabled: {
                type: Boolean,
                required: true,
                default: false
            }
        }
    }
</script>

<style lang="scss">
    .button-food-action {
        font-size: 14px;
        line-height: 138.18%;
        font-weight: 300;
        display: flex;
        flex-direction: row;

        &:not(.is-disabled) {
            color: var(--primary);
            transition: 400ms;

            .icon {
                fill:var(--primary);
                transition: 400ms;
            }
        }

        svg {
            margin-right: 8px;
        }

        &:not(.is-disabled):hover {
            cursor: pointer;
            color: var(--primary-dark);
            transition: 400ms;

            .icon {
                fill: var(--primary-dark);
                transition: 400ms;
            }
        }
    }

    .is-disabled {
        color: var(--grey);
        pointer-events: none;
        transition: 400ms;

        .icon {
            fill: var(--grey);
            transition: 400ms;
        }
    }
</style>
