<template>
    <div class="payment-options">
        <icon-stripe class="m-r-24"/>
        <icon-master-card class="m-r-24"/>
        <icon-visa/>
    </div>
</template>

<script>
import IconMasterCard from "../../../../js/components/svg/IconMasterCard";
import IconStripe from "../../../../js/components/svg/IconStripe";
import IconVisa from "../../../../js/components/svg/IconVisa";
    export default {
        name: 'PaymentOptions',
        components: {IconMasterCard, IconStripe, IconVisa}
    }
</script>

<style scoped lang="scss">
    .payment-options {
        display: flex;
        flex-direction: row;
    }

    .m-r-24 {
        margin-right: 24px;
    }
</style>
