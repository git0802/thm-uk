<template>
    <div class="warning-message">
        <icon-warning/>{{ message }}
    </div>
</template>

<script>
    import IconWarning from "../svg/IconWarning";

    export default {
        name: "WarningMessage",
        components: {
            IconWarning
        },
        props: {
            message: {
                type: String,
                required: true,
                default: 'Warning message'
            },
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../sass/mixins';

    .warning-message {
        @include head-font;

        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        /*For vertical alignment*/
        line-height: 1.2;
        color: var(--black-dark);

        svg {
            min-width: 18px;
            margin-right: 10px;
        }
    }
</style>


