<template>
    <div class="user-payment">
        <payment-info
            @click="$emit('click')"
        />
        <hr class="user-payment__divider"/>
        <payment-description/>
    </div>
</template>

<script>
    export default {
        name: 'UserPayment'
    }
</script>

<style scoped lang="scss">
    @import '../../../../../sass/mixins';

    .user-payment {
        @include base-font;

        display: flex;
        flex-direction: column;

        @media screen and (min-width: 768px) {
            width: 380px;
        }

        &__divider {
            height: 1px;
            width: 100%;
            border: none;
            background-color: var(--grey-2);
            margin-top: 35px;
            margin-bottom: 46px;
        }
    }
</style>
