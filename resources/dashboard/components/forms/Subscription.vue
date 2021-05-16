<template>
    <div class="relative">
        <div class="subscription">
            <div class="subscription__line"><span>Subscription info</span></div>
            <span>{{ subscriptionStatus.message }}</span>
        </div>
        <div v-if="!subscriptionStatus.is_cancelled" class="subscription">
            <div class="subscription__line"><span>Subscription cancel</span></div>
            <form @submit.prevent="fetchCancelSubscriptionInfo">
                <button-base
                    :text="'Cancel subscription'"
                    :type="'submit'"
                />
            </form>
            <span v-if="success_cancel">{{ message }}</span>
        </div>
        <form @submit.prevent="fetchRenewSubscriptionInfo">
            <div>
                <div class="subscription__line"><span>Renew Subscription</span></div>
                <div class="subscription__renew">
                    <base-input
                        class="subscription__card-input"
                        v-model="form.card_number.value"
                        :name="'card_number'"
                        :placeHolder="'Card number'"
                        :errorMessage="form.card_number.validation.message"
                        :validation-callback="form.card_number.validation.callback"
                        :validation-state="form.card_number.validation.validated"
                        :required="form.card_number.required"
                        :input-mask="'XXXX XXXX XXXX XXXX'"
                    />
                    <payment-options
                        class="subscription__cards"
                    />
                </div>
                <div class="subscription__secure">
                    <base-input
                        class="subscription__secure-period"
                        v-model="form.period.value"
                        :name="'period'"
                        :placeHolder="`Exp MM/YY`"
                        :errorMessage="form.period.validation.message"
                        :validation-callback="form.period.validation.callback"
                        :validation-state="form.period.validation.validated"
                        :required="form.period.required"
                        :inputMask="'XX/XX'"
                        :max="5"
                    />
                    <base-input
                        class="subscription__secure-cvc"
                        v-model="form.cvc.value"
                        :name="'cvc'"
                        :placeHolder="'CVC'"
                        :errorMessage="form.cvc.validation.message"
                        :validation-callback="form.cvc.validation.callback"
                        :validation-state="form.cvc.validation.validated"
                        :required="form.cvc.required"
                        :min="3"
                        :max="3"
                    />
                </div>
                <div class="subscription__coupon">
                    <base-input
                        v-model="form.coupon.value"
                        :name="'coupon'"
                        :placeHolder="'Coupon'"
                        :errorMessage="form.coupon.validation.message"
                        :validation-callback="form.coupon.validation.callback"
                        :validation-state="form.coupon.validation.validated"
                        :required="form.coupon.required"
                        :min="4"
                        :max="32"
                        :disabled="discountSuccess"
                    />
                    <button-apply-coupon class="button-apply"
                        :is-disabled="form.coupon.validation.validated !== 1"
                        :is-success="discountSuccess"
                        @click="applyCoupon"
                    />
                </div>

                <div class="subscription__subscriptions-group">
                    <label class="subscription__subscription">
                        <span class="text-bold title">{{price[0].title}}</span>
                        <span class="text">{{price[0].months}}-month</span>
                        <label>
                            <span class="text-bold">{{ phrase.currencySm }}{{price[0].price_sale}}</span>
                            <span class="text">/mo</span>
                        </label>
                        <input type="radio" name="user-info__subscription" :value="price[0].id"
                               v-model.number="subscription"
                        >
                        <svg width="15" height="10" viewBox="0 0 15 10" fill="#FFFFFF">
                            <path d="M13.795 0.199426C13.5216 -0.0664754 13.0784 -0.0664754 12.805 0.199426L4.41859 8.35645L1.19499 5.22105C0.921634 4.95515 0.478447 4.95518 0.205037 5.22105C-0.0683456 5.48693 -0.0683456 5.91799 0.205037 6.18389L3.92362 9.80066C4.19689 10.0665 4.64041 10.0664 4.91357 9.80066L13.795 1.16229C14.0684 0.896414 14.0683 0.465328 13.795 0.199426Z"/>
                        </svg>
                    </label>
                    <label class="subscription__subscription odd">
                        <span class="text-bold title">{{price[1].title}}</span>
                        <span class="text">{{price[1].months}}-month</span>
                        <label>
                            <span class="text-bold">{{ phrase.currencySm }}{{price[1].price_sale}}</span>
                            <span class="text">/mo. - {{ phrase.currencySm }}{{price[1].amount}}</span>
                        </label>
                        <input type="radio" name="user-info__subscription" :value="price[1].id"
                               v-model.number="subscription"
                        >
                        <svg width="15" height="10" viewBox="0 0 15 10" fill="#FFFFFF">
                            <path d="M13.795 0.199426C13.5216 -0.0664754 13.0784 -0.0664754 12.805 0.199426L4.41859 8.35645L1.19499 5.22105C0.921634 4.95515 0.478447 4.95518 0.205037 5.22105C-0.0683456 5.48693 -0.0683456 5.91799 0.205037 6.18389L3.92362 9.80066C4.19689 10.0665 4.64041 10.0664 4.91357 9.80066L13.795 1.16229C14.0684 0.896414 14.0683 0.465328 13.795 0.199426Z"/>
                        </svg>
                    </label>
                    <label class="subscription__subscription">
                        <span class="text-bold title">{{price[2].title}}</span>
                        <span class="text">{{price[2].months}}-month</span>
                        <label>
                            <span class="text-bold">{{ phrase.currencySm }}{{price[2].price_sale}}</span>
                            <span class="text">/mo. - {{ phrase.currencySm }}{{price[2].amount}}</span>
                            <span class="red-text">33% OFF</span>
                        </label>
                        <input type="radio" name="user-info__subscription" :value="price[2].id"
                               v-model.number="subscription"
                        >
                        <svg width="15" height="10" viewBox="0 0 15 10" fill="#FFFFFF">
                            <path d="M13.795 0.199426C13.5216 -0.0664754 13.0784 -0.0664754 12.805 0.199426L4.41859 8.35645L1.19499 5.22105C0.921634 4.95515 0.478447 4.95518 0.205037 5.22105C-0.0683456 5.48693 -0.0683456 5.91799 0.205037 6.18389L3.92362 9.80066C4.19689 10.0665 4.64041 10.0664 4.91357 9.80066L13.795 1.16229C14.0684 0.896414 14.0683 0.465328 13.795 0.199426Z"/>
                        </svg>
                    </label>
                    <label class="subscription__subscription odd">
                        <span class="text-bold year title"><icon-off class="sale-off"/>Hot Year<icon-fire class="fire"/></span>
                        <span class="text">12-month</span>
                        <label>
                            <span class="text-bold">{{ phrase.currencySm }}6</span>
                            <span class="text">/mo. - {{ phrase.currencySm }}72</span>
                            <span class="red-text">60% OFF</span>
                        </label>
                        <input type="radio" name="user-info__subscription" :value="price[3].id"
                               v-model.number="subscription"
                        >
                        <svg width="15" height="10" viewBox="0 0 15 10" fill="#FFFFFF">
                            <path d="M13.795 0.199426C13.5216 -0.0664754 13.0784 -0.0664754 12.805 0.199426L4.41859 8.35645L1.19499 5.22105C0.921634 4.95515 0.478447 4.95518 0.205037 5.22105C-0.0683456 5.48693 -0.0683456 5.91799 0.205037 6.18389L3.92362 9.80066C4.19689 10.0665 4.64041 10.0664 4.91357 9.80066L13.795 1.16229C14.0684 0.896414 14.0683 0.465328 13.795 0.199426Z"/>
                        </svg>
                    </label>

                </div>

                <div class="subscription__message">
                    <div class="warning-container">
                        <warning-message v-if="showWarningMessage" class="p-t-8"
                            :message="errors.length ? errors[0] : ''"
                        />
                        <success-message v-if="discountSuccess" class="p-t-8 p-b-16"
                            :message="discount.message"
                            @click="deleteCoupon"
                        />
                    </div>
                </div>
            </div>
            <button-base class="btn-renew"
                :disabled="isValid()"
                :text="'Renew subscription'"
                :type="'submit'"
            />
        </form>
        <overlay-component v-if="is_fetch"/>

        <div class="overlay" v-if="checkout">
            <div class="modal">
                <div class="preloader-itd" style="position: absolute;width: 150px;height: 150px;z-index: 0;">
                    <svg class="preloader-itd__spinner" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                        <circle class="preloader-itd__circle" fill="none" stroke-width="4" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                    </svg>
                    <svg class="preloader-itd__spinner-bg" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                        <circle class="preloader-itd__circle-bg" fill="none" stroke-width="4" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                    </svg>
                </div>
                <iframe :src="checkout" width="600" height="500" style="z-index: 1;"></iframe>
            </div>
        </div>
    </div>
</template>

<script>
import PaymentOptions from "../../../js/components/steps/CreateAccount/PaymentOptions";
import BaseInput from "../../../js/components/inputs/BaseInput";
import ButtonBase from "../../../js/components/buttons/ButtonBase";
import ButtonApplyCoupon from "../../../js/components/buttons/ButtonApplyCoupon";
import OverlayComponent from "../../../js/components/overlay/OverlayComponent";
import WarningMessage from "../../../js/components/messages/WarningMessage";
import SuccessMessage from "../../../js/components/messages/SuccessMessage";
import IconOff from "../../../js/components/svg/IconOff";
import IconFire from "../../../js/components/svg/IconFire";
import axios from "axios";
import { mapGetters, mapMutations, mapActions, } from "vuex";
import router from "../../../js/router";

export default {
    name: "Subscription",
    components: {
        ButtonBase,
        BaseInput,
        ButtonApplyCoupon,
        PaymentOptions,
        OverlayComponent,
        WarningMessage,
        SuccessMessage,
        IconOff, IconFire
    },
    data() {
        return {
            defaultPaymentOptions: {
                amount: null,
                text: 'HOT NEW DEAL!!!',
                freeDays: '',
                trial: 0
            },
            subscription: 0,
            options: [
                {text: 'Billed annually'},
                {text: ''},
            ],
            form: {
                card_number: {
                    required: true,
                    value: '',
                    validation: {
                        rules: {
                            long: 19,
                        },
                        message: '',
                        validated: 0,
                        callback: (event) => {
                            if (this.form.card_number.value.length < 1) {
                                this.form.card_number.validation.validated = 2;
                                this.form.card_number.validation.message = '*Enter your card number'
                            } else if (this.form.card_number.validation.rules.long !== this.form.card_number.value.length) {
                                this.form.card_number.validation.validated = 2;
                                this.form.card_number.validation.message = '*Your card number must me 16 characters long'
                            } else {
                                this.form.card_number.validation.validated = 1;
                            }
                        }
                    },
                },
                period: {
                    required: true,
                    value: '',
                    validation: {
                        rules: {
                            long: 5
                        },
                        message: '',
                        validated: 0,
                        callback: (event) => {
                            if (this.form.period.value.length < 1) {
                                this.form.period.validation.validated = 2;
                                this.form.period.validation.message = '*Enter your period'
                            } else if (this.form.period.validation.rules.long !== this.form.period.value.length) {
                                this.form.period.validation.validated = 2;
                                this.form.period.validation.message = '*Date must be like - MM/YY'
                            } else {
                                this.form.period.validation.validated = 1;
                            }
                            this.validationData(this.form.period.value)
                        }
                    },
                },
                cvc: {
                    required: true,
                    value: '',
                    validation: {
                        rules: {
                            max: 3,
                        },
                        message: '',
                        validated: 0,
                        callback: (event) => {
                            if (this.form.cvc.value.length < 1) {
                                this.form.cvc.validation.validated = 2;
                                this.form.cvc.validation.message = '*Enter your cvc'
                            } else if (this.form.cvc.validation.rules.max !== this.form.cvc.value.length) {
                                this.form.cvc.validation.validated = 2;
                                this.form.cvc.validation.message = '*Must be 3 characters'
                            } else {
                                this.form.cvc.validation.validated = 1;
                            }
                        }
                    },
                },
                coupon: {
                    required: false,
                    value: '',
                    validation: {
                        rules: {
                            min: 4,
                            max: 32
                        },
                        message: '',
                        validated: 0,
                        callback: (event) => {
                            if (this.form.coupon.value.length) {
                                if ( this.form.coupon.validation.rules.min > this.form.coupon.value.length || this.form.coupon.validation.rules.max < this.form.coupon.value.length ) {
                                    this.form.coupon.validation.validated = 2;
                                    this.form.coupon.validation.message = '*Min 4 characters -  max 32 characters';
                                    this.setCouponNumber('');
                                } else {
                                    this.form.coupon.validation.validated = 1;
                                    this.setCouponNumber(this.form.coupon.value);
                                }
                            }
                        }
                    },
                },
            },
            is_renew_disabled: false,
            success_cancel: false,
            is_fetch: false,
            message: '',
            defaultDiscount: {
                success: false,
                message: '',
                set: {
                    type: '',
                    value: 0
                }
            },
            verification: null,
            checkout: null
        }
    },
    mounted() {
        window.addEventListener('message', (e) => {
            if (e.origin.indexOf('hooks.stripe.com') !== -1) {
                this.checkout = null;
                this.fetchRenewSubscriptionInfo();
            }
        }, false);
        this.subscription = this.oldSubscriptionPlan.id;

    },
    computed: {
        ...mapGetters({
            discount: 'info/getDiscount',
            errors: 'info/getErrors',
            getCouponNumber: 'info/getCouponNumber',
            price: 'info/getPrice',
            selectedSubscriptionPlan: 'info/getSubscriptionPlan',
            oldSubscriptionPlan: 'auth/getSubscriptionPlan',
            subscriptionStatus: 'auth/getSubscriptionStatus',
        }),
        showWarningMessage() {
            return !!this.errors.length;
        },
        discountSuccess() {
            if (this.discount.success) {
                this.fetchGetPrice();
            }

            return this.discount.success;
        },
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
    watch: {
        subscription(){
            this.setSubscription(this.subscription);
        },

    },
    created() {
        this.fetchGetPrice();
        // this.fetchGetSubscriptionInfo();
        this.setDefaultPaymentOptions();
    },
    methods: {
        ...mapMutations({
            setCouponNumber: 'info/setCouponNumber',
            deleteDiscount: 'info/deleteDiscount',
            setSubscription: 'info/setSubscription',
        }),
        ...mapActions({
            checkCoupon: 'info/checkCoupon',
            checkSubscriptionStatus: 'auth/checkSubscriptionStatus',
        }),
        isValid() {
            return !(this.form.period.validation.validated == 1 && this.form.cvc.validation.validated == 1 && this.form.card_number.validation.validated == 1);
        },
        validationData(value) {
            const CHAR_NUMBER = 5;
            let cardExp = '';
            if (value.length > CHAR_NUMBER) {
                cardExp = value.slice(0, -1);
            } else {
                cardExp = value;
            }

            if (cardExp.length === CHAR_NUMBER) {
                const DIVIDER = '/';
                const expValue = cardExp.split(DIVIDER);
                const MM = expValue[0];
                const YY = expValue[1];
                const MIN_MM = 1;
                const MAX_MM = 12;
                const MIN_YY = 20;
                const MAX_YY = 99;

                const formatMM = this.formatValueWithLeadZero(MM);

                if (formatMM >= MIN_MM && formatMM <= MAX_MM && YY >= MIN_YY && YY <= MAX_YY) {
                    this.form.period.validation.validated = 1;
                } else {
                    this.form.period.validation.validated = 2;
                    this.form.period.validation.message = '*Enter active card'

                }
                this.month = MM;
                this.year = YY
            } else {
                this.form.period.validation.validated = 2;
            }

        },
        notify(text, status) {
            this.$notify({
                group: 'planner',
                title: 'Yay!',
                type: status,
                text: text
            });
        },
        calculateDiscount() {
            switch (this.discount.set.type) {
                case 'fixed':
                    this.paymentOptions.discountValue = `${this.discount.set.value}$`;
                    this.paymentOptions.discountAmount = this.discount.set.value.toFixed(2);
                    break;
                case 'percent':
                    this.paymentOptions.discountValue = `${this.discount.set.value}%`;
                    this.paymentOptions.discountAmount = (((this.selectedSubscriptionPlan.price * this.selectedSubscriptionPlan.months) / 100) * this.discount.set.value).toFixed(2);
                    break;
                case 'month':
                    this.paymentOptions.discountValue = this.selectedSubscriptionPlan.sale + '%';
                    this.paymentOptions.discountAmount = (((this.selectedSubscriptionPlan.price * this.selectedSubscriptionPlan.months) / 100) * this.price.data.sale).toFixed(2);
                    break;
            }

            this.paymentOptions.total = (this.paymentOptions.amount - this.paymentOptions.discountAmount).toFixed(2);
        },
        setDefaultPaymentOptions() {
            let amount = this.selectedSubscriptionPlan.price * this.selectedSubscriptionPlan.months;
            this.paymentOptions = {
                amount: amount,
                discountValue: this.selectedSubscriptionPlan.sale + '%',
                discountAmount: this.selectedSubscriptionPlan.price * this.selectedSubscriptionPlan.months - this.selectedSubscriptionPlan.amount,
                total: this.selectedSubscriptionPlan.amount,
            };
        },
        clearForm() {
            Object.keys(this.form).forEach(f => {
                this.form[f].value = ''
                this.form[f].validation.validated = 0;
            });
        },
        formatValueWithLeadZero(value) {
            if (value[0] === '0') {
                return value.substr(1);
            } else {
                return value;
            }
        },
        // async fetchGetSubscriptionInfo() {
        //     try {
        //         const res = await axios.get(`/api/subscription/status`);
        //         this.subscription_info = res.data.data.message;
        //         this.is_cancelled = res.data.data.is_cancelled;
        //     } catch (error) {
        //         console.log(error);
        //     }
        // },
        async fetchGetPrice() {
            try {
                const res = await axios.get(`/api/subscription/price`, {
                    params: {
                        coupon: this.getCouponNumber
                    }
                });
                this.defaultPaymentOptions.amount = res.data.data.price.cost;
                this.defaultPaymentOptions.trial = res.data.data.trial;

                if (this.defaultPaymentOptions.trial) {
                    this.defaultPaymentOptions.freeDays = `Free for ${ this.defaultPaymentOptions.trial } days`;
                    this.options[1].text = `${phrase.currencySm} 0.00 For ${ this.defaultPaymentOptions.trial } days (Cancel any time! We will never charge you for the first ${ this.defaultPaymentOptions.trial } days).`;
                } else {
                    this.defaultPaymentOptions.freeDays = '';
                    this.options[1].text = 'Cancel any time';
                }
            } catch (error) {
                console.log(error);
            }
        },
        async fetchCancelSubscriptionInfo() {
            this.is_fetch = true;
            try {
                const res = await axios.post(`/api/subscription/cancel`);
                await this.checkSubscriptionStatus();
                this.notify(res.data.message, 'success');
                this.message = res.data.data.message;
                this.success_cancel = res.data.data.success;
                this.is_fetch = false;
            } catch (error) {
                await this.checkSubscriptionStatus();
                this.is_fetch = false;
            }
        },
        fetchRenewSubscriptionInfo() {
            this.is_fetch = true;

            if ( !this.discountSuccess ) {
                this.setCouponNumber('');

                this.form.coupon.value = '';
                this.form.coupon.validation.validated = 0;
                this.form.coupon.validation.message = '';
            }

            let finaly = true,
                action = this.verification ? '/api/subscription/renew' : '/api/subscription/renew-check',
                data = {
                    number: this.form.card_number.value.replace(/ /g, ''),
                    exp_year: this.year,
                    exp_month: this.month,
                    cvc: this.form.cvc.value,
                    coupon: this.getCouponNumber,
                    verification: this.verification,
                    subscription: this.subscription,
                };

            axios.post(action, data)
                .then((response) => {
                    switch (response.data.result) {
                        case 'succeeded':
                            finaly = false;
                            this.verification = response.data.id;
                            this.fetchRenewSubscriptionInfo();

                            break;
                        case 'success':
                            this.verification = null;
                            this.notify(response.data.message, 'success');
                            this.clearForm();
                            this.checkSubscriptionStatus();

                            break;
                        case 'requires_action':
                            this.verification = response.data.id;
                            this.checkout = response.data.action.redirect_to_url.url;

                            break;
                        case 'requires_payment_method':
                            this.notify('It\'s impossible to connect this card to your account. Please use another card and try again.', 'error');
                            break;
                    }
                })
                .catch((error) => {
                    this.verification = null;
                    const errors = error.response.data.errors;

                    if (errors) {
                        Object.keys(errors).forEach((k) => {
                            errors[k].forEach((e) => {
                                this.notify(e, 'error');
                            })
                        })
                    } else {
                        this.notify(error.response.data.message, 'error');
                    }
                })
                .then(() => {
                    if (finaly) {
                        this.is_fetch = false;
                    }
                })
        },
        async applyCoupon() {
            await this.checkCoupon();

            if ( !this.discountSuccess ) {
                this.form.coupon.validation.validated = 2;
                this.form.coupon.validation.message = this.form.coupon.value;
                this.setCouponNumber('');
            }else{
                this.form.coupon.validation.validated = 1;
            }
        },
        deleteCoupon() {
            this.deleteDiscount(this.defaultDiscount);
            //Clear field and store after delete coupon
            this.form.coupon.value = '';
            this.form.coupon.validation.validated = 0;
            this.setCouponNumber('');

            this.fetchGetPrice();
        }
    },
}
</script>

<style lang="scss" scoped>

.relative {
    position: relative;
}

.subscription {
    margin: 4.25rem 0;
    font-family: Gilroy;
    font-style: normal;
    font-weight: 300;
    font-size: 18px;
    color: #000;


    &__renew {
        display: flex;
        justify-content: space-between;

        @media screen and (max-width: 1200px) {
            flex-direction: column;
            margin-bottom: 25px;
        }

    }

    &__cards {
        width: 100%;
        max-width: 125px;
        margin-left: 25px;
        @media screen and (max-width: 1200px) {
            max-width: 180px;
            margin: 0 auto;
        }
    }

    &__card-input {
        width: 100%;
        max-width: 330px;
        @media screen and (max-width: 1200px) {
            max-width: 100%;
        }
    }

    &__secure {
        display: flex;
        margin-bottom: 20px;
    }

    &__secure-period {
        max-width: 160px;
        margin-right: 20px;
    }

    &__secure-cvc {
        max-width: 118px;
    }

    &__coupon {
        display: flex;

        @media screen and (max-width: 1200px) {
            flex-direction: column;
        }

        .input-container {
            min-width: 330px;
            margin-right: 16px;
            margin-bottom: 0px;

            @media screen and (max-width: 1200px) {
                min-width: unset;
                margin-right: 0px;
                margin-bottom: 20px;
            }
        }

        .button-apply {
            max-height: 39px;
        }
    }

    &__message {
        text-align: center;

        .warning-container {
            min-height: 36px;
        }
    }

    &__block {
        text-align: center;
        display: inline-block;
        width: 100%;
        margin-bottom: 2.5rem;
    }

    &__up {
        color: #fff;
        background: #DF2C65;
        border-top-left-radius: 7px;
        border-top-right-radius: 7px;
        padding: 2rem 0;
        font-family: Gilroy;
        font-style: normal;
        font-weight: 300;
        font-size: 15px;

        &__month {
            font-weight: 800;
            font-size: 45px;
        }
    }

    &__down {
        text-align: left;
        background: #FFFFFF;
        border: 1px solid #B6B6B6;
        box-sizing: border-box;
        border-bottom-left-radius: 7px;
        border-bottom-right-radius: 7px;
        padding: 1.875rem 0 1.875rem 1.875rem;

        ul li {
            margin: 1.25rem 0;
            font-family: Gilroy;
            font-style: normal;
            font-weight: 300;
            font-size: 15px;
            line-height: 151.19%;
            color: #292D34;
        }
    }

    &__profile {

        font-weight: 800;
        line-height: 138.18%;
        color: var(--black);

        h1 {
            line-height: 138.18%;
            margin-left: 2.0625rem;
        }
    }

    &__line {
        margin-top: 4.3125rem;
        font-weight: 300;
        font-size: 15px;
        line-height: 151.19%;
        position: relative;
        padding-left: 50px;
        margin-bottom: 14px;
        color: var(--primary);
        opacity: 0.6;

        span {
            font-size: 18px;
            line-height: 151.19%;
        }
    }

    &__line:before {
        content: "";
        display: block;
        position: absolute;
        vertical-align: middle;
        width: 35px;
        height: 1px;
        left: 0px;
        top: 50%;
        background-color: var(--primary-light);
    }

    button.button {
        max-width: 30rem;
    }

    &__subscriptions-group{
        border: 1px solid var(--grey);
        border-radius: 7px;
        padding: 10px;
        margin-top: 40px;
        margin-bottom: 40px;
    }

    &__subscription {
        display: flex;
        flex-direction: row;
        cursor: pointer;
        padding: 10px;
        & input{
            display: none;
            pointer-events: none;
        }
        & > svg{
            width: 26px;
            height: 26px;
            margin-left: 5px;
            background: var(--white);
            color: var(--white);
            border: 2px solid var(--purple);
            padding: 3px;
            border-radius: 2px;
            opacity: 1;
            pointer-events: none;
            transition: 0.15s ease;
        }
        & input:checked + svg{
            background: var(--purple);
            color: var(--white);
        }
        & span{
            flex-basis: 25%;
        }
        & label{
            flex-basis: 40%;
            pointer-events: none;
        }
        & .red-text{
            font-family: Gilroy,serif;
            font-size: 9px;
            font-style: normal;
            font-weight: 400;
            line-height: 12px;
            letter-spacing: 0em;
            text-align: left;
            color: var(--primary);
        }
        & .text{
            font-family: Gilroy;
            font-size: 14px;
            font-style: normal;
            font-weight: 300;
            line-height: 19px;
            letter-spacing: 0em;
            text-align: left;

        }

        & .text-bold{
            font-family: Gilroy;
            font-size: 14px;
            font-style: normal;
            font-weight: 800;
            line-height: 19px;
            letter-spacing: 0em;
            text-align: left;
            &.title{
                margin-left: 15px;
            }
        }

        & input:checked ~ .text-bold{
            color: var(--purple);
        }

        &.odd{
            background: #EDEDED;
        }
        & .year{
            display: flex;
            & .sale-off{
                margin-top: -20px;
                margin-left: -35px;
                margin-right: -20px;
            }
            & .fire{
                margin-left: 10px;
            }
        }
    }

}

.p-b-16 {
    padding-bottom: 16px;
}

.p-t-8 {
    padding-top: 8px;
}
.btn-renew{
    margin: auto;
    display: block;
}

</style>
