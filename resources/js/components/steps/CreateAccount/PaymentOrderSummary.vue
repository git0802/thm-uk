<template>
    <div class="order-summary">
        <div class="order-summary__title">Order summary</div>
        <div class="order-summary__amount m-b-7">
            <div>{{subscriptionPlan.months}}-month subscription</div>
            <div class="order-summary__value">{{ phrase.currencySm }}{{ amount }}</div>
        </div>
        <div class="order-summary__discount">
            <div>Discount {{ discountValue }}</div>
            <div class="order-summary__value primary-color">-{{ phrase.currencySm }}{{ discountAmount }}</div>
        </div>
        <hr class="order-summary__divider"/>

        <template>
            <div class="order-summary__total order-summary__total--small">
                <div>Total Due Now:</div>
                <div>Free</div>
            </div>

            <div class="order-summary__total order-summary__total--small">
                <div>Total Due After Free Trial:</div>
                <div>{{ phrase.currencySm }}{{ total }}</div>
            </div>
        </template>

        <div class="order-summary__description">
            <div v-if="!discountMonths.months">
                <p>Afterwards, you will be billed the current price of {{ phrase.currencySm }}{{ total | totalSum }}/{{subscriptionPlan.months}}-month.
                    By placing this order, you agree to the Terms & Conditions and auto-renewal.
                    You can cancel any time before your next billing cycle.
                </p>
            </div>
            <div v-else>
                <p>
                    You have entered a Coupon Code for {{ discountMonths.months }} month.
                    This means that you will be billed {{ phrase.currencySm }}0 for your first {{ discountMonths.days }} days.
                    After {{ discountMonths.days }} days, you will be billed the current price of {{ phrase.currencySm }}{{ total | totalSum }}/year.
                    You can cancel at any time before your next billing cycle.
                    By placing this order, you agree to the Terms & Conditions and auto-renewal.
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from "vuex";

    export default {
        name: 'PaymentOrderSummary',
        props: {
            isSuccess: {
                type: Boolean,
                required: true,
                default: false
            },
            amount: {
                required: true,
            },
            discountValue: {
                required: true,
            },
            discountAmount: {
                required: true,
            },
            total: {
                required: true,
            }
        },
        data() {
            return {
                freeMonths: {
                    months: '',
                    days: ''
                }
            }
        },
        computed: {
            ...mapGetters({
                discount: 'info/getDiscount',
                price: 'info/getPrice',
                subscription: 'info/getSubscription',
                subscriptionPlan: 'info/getSubscriptionPlan',
            }),
            discountMonths() {
                if (this.discount.set.type === 'month') {
                    this.freeMonths.months = Number(this.discount.set.value);
                    this.freeMonths.days = this.freeMonths.months * Number(this.subscriptionPlan.months) + this.subscriptionPlan.trial;

                } else {
                    this.freeMonths.months = '';
                    this.freeMonths.days = ''
                }

                return this.freeMonths;
            }
        },
        filters: {
            totalSum(value) {
                return Math.round(Number(value));
            }
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../../sass/_mixins.scss';


    .order-summary {

        &__title {
            @include head-font;

            margin-bottom: 13px;
        }

        &__amount, &__discount, &__total {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        &__total {
            @include head-24-font;
        }
        &__total--small {

        }

        &__value {
            letter-spacing: 0.085em;
        }

        &__divider {
            height: 1px;
            border: none;
            background-color: var(--grey-2);
            margin: 16px 0px;
        }

        &__description {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 33px;
        }
    }
    .order-summary__total--small {
        font-size: 18px;
    }
    .m-b-7 {
        margin-bottom: 7px;
    }

    .primary-color {
        color: var(--primary);
    }

    @media screen and (max-width: 840px) {
        .order-summary__total--small {
            font-size: 16px;
        }
    }

    @media screen and (max-width: 340px) {
        .order-summary__total--small {
            font-size: 15px;
        }
    }
</style>
