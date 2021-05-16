<template>
    <!--<router-link to="/store" class="b-link" :class="{ 'disabled' : false }">COMPLETE ORDER</router-link>-->
    <button class="button-complete-order" :class="{ 'disabled' : isDisabled }"
        :disabled="isDisabled"
        @click="$emit('click')"
    >COMPLETE ORDER
    </button>
</template>

<script>
    export default {
        name: "ButtonCompleteOrder",
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

    .button-complete-order {
        @include button-text-font;

        text-align: center;
        background: var(--primary);
        border-radius: 7px;
        padding: 13px 0px 12px;
        width: 100%;

        &:not(.disabled):hover {
            cursor: pointer;
            background: var(--primary-dark);
            transition: 400ms;
        }
    }

    .disabled {
        cursor: auto;
        pointer-events: none;
        background: var(--grey-2);
    }
</style>


