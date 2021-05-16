<template>
    <div class="complete-message">
        <div>{{ message }}</div>
    </div>
</template>

<script>
    export default {
        props: {
            message: {
                type: String,
                required: true,
                default: 'Success'
            },
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../sass/mixins';

    .complete-message {
        @include head-font;

        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        line-height: 12px;
        color: var(--black-dark);
    }
</style>


