<template>
    <div class="success-message">
        <div>{{ message }}</div>
        <div>
            You can <span @click="$emit('click')">delete</span> this coupon to enter another
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            message: {
                type: String,
                required: true,
                default: 'Success message'
            },
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../sass/mixins';

    .success-message {
        @include head-font;

        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        line-height: 138.18%;
        color: var(--black-dark);

        span {
            text-decoration: underline;
            color: var(--primary);
        }

        span:hover {
            cursor: pointer;
        }
    }

    .m-r-4 {
        margin-right: 4px;
    }
</style>


