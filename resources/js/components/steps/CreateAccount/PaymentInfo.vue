<template>
    <div class="payment-info">
        <payment-order-summary
            :isSuccess="computedDiscountSuccess"
            :amount="paymentOptions.amount"
            :discountValue="paymentOptions.discountValue"
            :discountAmount="paymentOptions.discountAmount"
            :total="paymentOptions.total"
        />
        <button-complete-order
            :isDisabled="isValidUserForm || isBusy"
            @click="$emit('click')"
        />
        <div class="warning-container">
            <template v-for="error in errorsRegister">
                <warning-message class="p-t-8" :message="error"/>
            </template>
        </div>
        <payment-secure-info/>
    </div>
</template>

<script>
    import { mapGetters } from "vuex";

    export default {
        data() {
            return {
                paymentOptions: {
                    amount: '',
                    discountValue: '',
                    discountAmount: '',
                    total: ''
                }
            }
        },
        computed: {
            ...mapGetters({
                price: 'info/getPrice',
                subscription: 'info/getSubscription',
                discount: 'info/getDiscount',
                isBusy: 'info/getIsBusy',
                errorsRegister: 'info/getErrorsRegister',
                isValidUserForm: 'info/getIsValidUserForm',
                subscriptionPlan: 'info/getSubscriptionPlan',
            }),

            computedDiscountSuccess() {
                if (this.discount.success) {
                    this.calculateDiscount();
                } else {
                    this.setDefaultPaymentOptions();
                }
                return this.discount.success;
            },
            computedShowWarningMessage() {
                return !!this.errorsRegister.length;
            },
        },
        created() {
            this.setDefaultPaymentOptions();
        },
        methods: {
            calculateDiscount() {
                switch (this.discount.set.type) {
                    case 'fixed':
                        this.paymentOptions.discountValue = `${this.discount.set.value}$`;
                        this.paymentOptions.discountAmount = this.discount.set.value.toFixed(2);
                        break;
                    case 'percent':
                        this.paymentOptions.discountValue = `${this.discount.set.value}%`;
                        this.paymentOptions.discountAmount = (((this.subscriptionPlan.price * this.subscriptionPlan.months) / 100) * this.discount.set.value).toFixed(2);
                        break;
                    case 'month':
                        this.paymentOptions.discountValue = this.subscriptionPlan.sale + '%';
                        this.paymentOptions.discountAmount = (((this.subscriptionPlan.price * this.subscriptionPlan.months) / 100) * this.price.data.sale).toFixed(2);
                        break;
                }

                this.paymentOptions.total = (this.paymentOptions.amount - this.paymentOptions.discountAmount).toFixed(2);
            },
            setDefaultPaymentOptions() {
                let amount = this.subscriptionPlan.price * this.subscriptionPlan.months;
                this.paymentOptions = {
                    amount: amount,
                    discountValue: this.subscriptionPlan.sale + '%',
                    discountAmount: this.subscriptionPlan.price * this.subscriptionPlan.months - this.subscriptionPlan.amount,
                    total: this.subscriptionPlan.amount,
                };
            }
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../../sass/_mixins.scss';

    .payment-info {
        @include base-font;

        display: flex;
        flex-direction: column;

        .warning-container {
            min-height: 33px;
            text-align: center;
        }
        button {
            cursor: pointer;
        }
    }

    .p-t-8 {
        padding-top: 8px;
    }
</style>
