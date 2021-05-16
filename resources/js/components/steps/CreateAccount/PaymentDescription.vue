<template>
    <div class="payment-description">
        <div class="payment-description__title">Is my information secure?</div>
        <div class="m-b-20">
                The Hot Meal uses Stripe for payment processing.
                Stripe has been audited by a PCI-certified auditor and is certified to PCI Service Provider Level 1.
                This is the most stringent level of certification in the payments industry.
        </div>
        <div>
            <div class="m-b-6">Payment processing provided by:</div>
            <payment-options/>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'PaymentDescription'
    }
</script>

<style scoped lang="scss">
    @import '../../../../sass/_mixins.scss';

    .payment-description {
        @include base-font;

        display: flex;
        flex-direction: column;

        &__title {
            @include head-font;

            line-height: 138.18%;
            margin-bottom: 14px;
        }
        margin-bottom: 45px;
    }

    .m-b-6 {
        margin-bottom: 6px;
    }

    .m-b-20 {
        margin-bottom: 20px;
    }
</style>
