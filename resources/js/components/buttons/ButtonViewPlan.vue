<template>
    <button class="button-view-plan"
        @click="$emit('click')"
    >VIEW YOUR WEEKLY PLAN
    </button>
</template>

<script>
    export default {
        name: "ButtonViewPlan"
    }
</script>

<style scoped lang="scss">
    @import '../../../sass/_mixins.scss';

    .button-view-plan {
        @include button-text-font;

        background: var(--primary);
        border-radius: 7px;
        padding: 13px 51px 12px;

        &:not(.disabled):hover {
            cursor: pointer;
            background: var(--primary-dark);
            transition: 400ms;
        }

        @media screen and (max-width: 480px) {
            padding: 13px 12px 12px;
        }
    }
</style>


