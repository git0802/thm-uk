<template>
    <!--<router-link to="/store" class="b-link" :class="{ 'disabled' : false }">COMPLETE ORDER</router-link>-->
    <button class="button-complete-order" :class="{ 'disabled' : isDisabled }"
        :disabled="isDisabled"
        @click="$emit('click')"
    >START FREE TRIAL
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
        background: linear-gradient(94.02deg, #FF369A 0.94%, #FF1D8D 92.78%);
        box-shadow: -4px -4px 9px rgba(255, 52, 153, 0.12), 12px 12px 24px rgba(227, 38, 132, 0.12);
        border-radius: 7px;
        padding: 13px 0px 12px;
        width: 100%;

        &:not(.disabled):hover {
            cursor: pointer;
            transition: 400ms;
        }
    }

    .disabled {
        cursor: auto;
        pointer-events: none;
        background: var(--primary-light);
    }
</style>


