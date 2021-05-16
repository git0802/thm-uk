<template>
    <div class="secure">
        <div class="secure__info">
            <icon-secure/>Secure credit card payment
        </div>
        <div class="secure__text">This is a secure 128-bit SSL encrypted payment</div>
    </div>
</template>

<script>
    export default {
        name: 'PaymentSecureInfo'
    }
</script>

<style scoped lang="scss">
    @import '../../../../sass/_mixins.scss';

    .secure {
        @include head-font;
        text-align: center;
        margin-top: 8px;

        &__info {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            /*For vertical alignment*/
            line-height: 12px;
        }

        svg {
            margin-right: 8px;
        }

        &__text {
            @include base-font;

            margin-top: 3px;
        }
    }
</style>
