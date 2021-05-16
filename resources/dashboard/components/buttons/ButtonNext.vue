<template>
    <button @click="nextStep()" :disabled="isDisabled" class="button" :class="{ 'disabled': isDisabled }">
        <span class="button__text">NEXT STEP</span>
    </button>
</template>
<script>
    export default {
        props: {
            isDisabled: {
                type: Boolean,
                required: true
            }
        },
        methods: {
            nextStep() {
                this.$emit('next');
            }
        }
    }
</script>
<style scoped lang="scss">
    .button {
        background: var(--primary);
        border-radius: 7px;
        padding: 13px 78px 12px;

        &__text {
            font-weight: 800;
            font-size: 17.4545px;
            font-style: normal;
            line-height: 132.19%;
            color: var(--white);
        }

        &:focus {
            outline: none;
        }

        &:not(.disabled):hover {
            background: var(--primary-dark);
        }
    }
</style>


