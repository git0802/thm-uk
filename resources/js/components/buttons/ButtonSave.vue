<template>
    <button class="button-save" :class="{ 'is-disabled': isDisabled }"
        :disabled="isDisabled"
        @click="$emit('click')"
    >SAVE
    </button>
</template>

<script>
    export default {
        name: "ButtonSave",

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

    .button-save {
        @include button-text-font;

        background: var(--primary);
        border-radius: 7px;
        padding: 13px 78px 12px;

        &:not(.is-disabled):hover {
            cursor: pointer;
            background: var(--primary-dark);
            transition: 400ms;
        }
    }

    .is-disabled {
        cursor: auto;
        background: var(--grey-2);
    }
</style>





