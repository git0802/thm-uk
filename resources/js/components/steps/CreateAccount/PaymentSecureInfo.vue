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

    .secure {
        font-family: Lato;
        font-style: normal;
        font-weight: normal;
        font-size: 14px;
        line-height: 17px;

        letter-spacing: 0.02em;
        color: #272C32;
        opacity: 0.67;

        text-align: center;
        margin-top: 8px;

        &__info {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            /*For vertical alignment*/
        }

        svg {
            margin-right: 8px;
        }

        &__text {
            font-size: 12px;
            line-height: 14px;
        }
    }
</style>
