<template>
    <div class="create-subscription">
        <div class="create-subscription__txt">
            <div class="create-subscription__txt__capital create-subscription__txt__welcome">
                HI {{user.name}}, START YOUR FREE TRIAL!
            </div>
            <div class="create-subscription__txt__info">
                Plan your meals to manage your weight.
                <br>
                Fitness begins now, wherever you are.
            </div>
            <div class="create-subscription__txt__small">
                No gym needed. Sign up for 14 days free. Cancel any time.
            </div>
        </div>
        <div class="create-subscription__info">

            <div class="payment-info">
                <form>
                    <div class="payment-info__card">
                        <input-card-number class="payment-info__card__number"
                                           @validation="actionsInputData"
                        />
                    </div>
                    <div class="payment-info__secure">
                        <input-card-valid-period class="payment-info__secure__period"
                                                 @validation="actionsInputData"
                        />
                        <input-card-cvc class="payment-info__secure__cvc"
                                        @validation="actionsInputData"
                        />
                    </div>
                </form>
                <div class="payment-info__checked-group">
                    <label class="payment-info__checked">
                        <input type="radio" name="payment-info__coupon" value="0"
                               v-model.number="checked"
                        >
                        <svg width="15" height="10" viewBox="0 0 15 10" fill="#DF2C65">
                            <path d="M14.7399 0.253771C14.3932 -0.0846007 13.831 -0.0845998 13.4843 0.25383L5.64331 7.90759L1.51579 3.87862C1.16908 3.54019 0.606842 3.54019 0.260075 3.87862C-0.0866917 4.21705 -0.0866917 4.76586 0.260075 5.10435L5.01542 9.74616C5.1888 9.91541 5.41605 10 5.64325 10C5.87044 10 6.09775 9.91535 6.27107 9.74616L14.7399 1.47951C15.0867 1.14108 15.0867 0.592259 14.7399 0.253771Z"/>
                        </svg>
                        <span class="text">Regular subscription</span>
                    </label>

                    <label class="payment-info__checked">
                        <input type="radio" name="payment-info__coupon" value="1"
                               v-model.number="checked"
                        >
                        <svg width="15" height="10" viewBox="0 0 15 10" fill="#DF2C65">
                            <path d="M14.7399 0.253771C14.3932 -0.0846007 13.831 -0.0845998 13.4843 0.25383L5.64331 7.90759L1.51579 3.87862C1.16908 3.54019 0.606842 3.54019 0.260075 3.87862C-0.0866917 4.21705 -0.0866917 4.76586 0.260075 5.10435L5.01542 9.74616C5.1888 9.91541 5.41605 10 5.64325 10C5.87044 10 6.09775 9.91535 6.27107 9.74616L14.7399 1.47951C15.0867 1.14108 15.0867 0.592259 14.7399 0.253771Z"/>
                        </svg>
                        <span class="text">Coupon Code</span>
                    </label>
                </div>

                <div class="payment-info__show"
                     v-if="checked"
                >
                    <div>
                        <div class="payment-info__coupon">
                            <input-coupon-number class="payment-info__coupon__number"
                                                 :isClear="isClearCouponField"
                                                 :isDisabled="computedDiscountSuccess"
                                                 @validation="validationCouponNumber"
                                                 @clear="clearComplete"
                            />
                            <button-apply-coupon
                                    class="button-apply"
                                    :isDisabled="isDisabledApplyButton"
                                    :isSuccess="computedDiscountSuccess"
                                    @click="applyCoupon"
                            />
                        </div>
                        <div class="payment-info__message">
                            <div class="warning-container">
                                <warning-message v-if="computedShowWarningMessage" class="p-t-8"
                                                 :message="errors.length ? errors[0] : ''"
                                />
                                <success-message v-if="computedDiscountSuccess" class="p-t-8 p-b-16"
                                                 :message="discount.message"
                                                 @click="deleteCoupon"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="payment-info__trial" v-if="computedDiscountSuccess">
                        <p class="payment-info__free">{{ couponRes }}</p>
                    </div>
                </div>

                <button class="subscription-button" v-if="!subListOpen" @click="subListOpen = !subListOpen">
                    <div class="subscription-button__arrow-icon">
                        <svg width="16" height="9" viewBox="0 0 16 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 1L8 8L1 1" stroke="#292D34"/>
                        </svg>
                    </div>
                    <span v-if="validSubscription">Subscription : </span>
                    <span v-else>Choose a subscription plan</span>
                    <label style="padding-left: 10px"  v-if="validSubscription">
                        {{validSubscription.title}} | {{validSubscription.months}}-month | {{ phrase.currencySm }}{{validSubscription.amount}}
                    </label>

                </button>
                <div class="payment-info__subscriptions-group" v-else>
                    <label class="payment-info__subscription">
                        <span class="text-bold title">{{price[0].title}}</span>
                        <span class="text">{{price[0].months}}-month</span>
                        <label>
                            <span class="text-bold">{{ phrase.currencySm }}{{price[0].price_sale}}</span>
                            <span class="text">/mo</span>
                        </label>
                        <input type="radio" name="payment-info__subscription" :value="price[0].id"
                               v-model.number="subscription"
                               @change="subListOpen = false"
                        >
                        <svg width="15" height="10" viewBox="0 0 15 10" fill="#FFFFFF">
                            <path d="M13.795 0.199426C13.5216 -0.0664754 13.0784 -0.0664754 12.805 0.199426L4.41859 8.35645L1.19499 5.22105C0.921634 4.95515 0.478447 4.95518 0.205037 5.22105C-0.0683456 5.48693 -0.0683456 5.91799 0.205037 6.18389L3.92362 9.80066C4.19689 10.0665 4.64041 10.0664 4.91357 9.80066L13.795 1.16229C14.0684 0.896414 14.0683 0.465328 13.795 0.199426Z"/>
                        </svg>
                    </label>
                    <label class="payment-info__subscription odd" >
                        <span class="text-bold title">{{price[1].title}}</span>
                        <span class="text">{{price[1].months}}-month</span>
                        <label>
                            <span class="text-bold">{{ phrase.currencySm }}{{price[1].price_sale}}</span>
                            <span class="text">/mo. - {{ phrase.currencySm }}{{price[1].amount}}</span>
                        </label>
                        <input type="radio" name="payment-info__subscription" :value="price[1].id"
                               v-model.number="subscription"
                               @change="subListOpen = false"
                        >
                        <svg width="15" height="10" viewBox="0 0 15 10" fill="#FFFFFF">
                            <path d="M13.795 0.199426C13.5216 -0.0664754 13.0784 -0.0664754 12.805 0.199426L4.41859 8.35645L1.19499 5.22105C0.921634 4.95515 0.478447 4.95518 0.205037 5.22105C-0.0683456 5.48693 -0.0683456 5.91799 0.205037 6.18389L3.92362 9.80066C4.19689 10.0665 4.64041 10.0664 4.91357 9.80066L13.795 1.16229C14.0684 0.896414 14.0683 0.465328 13.795 0.199426Z"/>
                        </svg>
                    </label>
                    <label class="payment-info__subscription" >
                        <span class="text-bold title">{{price[2].title}}</span>
                        <span class="text">{{price[2].months}}-month</span>
                        <label>
                            <span class="text-bold">{{ phrase.currencySm }}{{price[2].price_sale}}</span>
                            <span class="text">/mo. - {{ phrase.currencySm }}{{price[2].amount}}</span>
                            <span class="red-text">33% OFF</span>
                        </label>
                        <input type="radio" name="payment-info__subscription" :value="price[2].id"
                               v-model.number="subscription"
                               @change="subListOpen = false"
                        >
                        <svg width="15" height="10" viewBox="0 0 15 10" fill="#FFFFFF">
                            <path d="M13.795 0.199426C13.5216 -0.0664754 13.0784 -0.0664754 12.805 0.199426L4.41859 8.35645L1.19499 5.22105C0.921634 4.95515 0.478447 4.95518 0.205037 5.22105C-0.0683456 5.48693 -0.0683456 5.91799 0.205037 6.18389L3.92362 9.80066C4.19689 10.0665 4.64041 10.0664 4.91357 9.80066L13.795 1.16229C14.0684 0.896414 14.0683 0.465328 13.795 0.199426Z"/>
                        </svg>
                    </label>
                    <label class="payment-info__subscription odd" >
                        <span class="text-bold year title">
                            <icon-off class="sale-off"/>Hot Year<icon-fire class="fire"/></span>
                        <span class="text">12-month</span>
                        <label>
                            <span class="text-bold">{{ phrase.currencySm }}6</span>
                            <span class="text">/mo. - {{ phrase.currencySm }}72</span>
                            <span class="red-text">60% OFF</span>
                        </label>
                        <input type="radio" name="payment-info__subscription" :value="price[3].id"
                               v-model.number="subscription"
                               @change="subListOpen = false"
                        >
                        <svg width="15" height="10" viewBox="0 0 15 10" fill="#FFFFFF">
                            <path d="M13.795 0.199426C13.5216 -0.0664754 13.0784 -0.0664754 12.805 0.199426L4.41859 8.35645L1.19499 5.22105C0.921634 4.95515 0.478447 4.95518 0.205037 5.22105C-0.0683456 5.48693 -0.0683456 5.91799 0.205037 6.18389L3.92362 9.80066C4.19689 10.0665 4.64041 10.0664 4.91357 9.80066L13.795 1.16229C14.0684 0.896414 14.0683 0.465328 13.795 0.199426Z"/>
                        </svg>
                    </label>

                </div>
            </div>
            <button-complete-order
                :isDisabled="!validSubscription || isValidUserForm || isBusy"
                @click="createSubscription"
            />
            <div class="warning-container">
                <template v-for="error in errorsRegister">
                    <warning-message class="p-t-8" :message="error"/>
                </template>
            </div>
            <payment-secure-info style="padding-top: 20px; color: white" />

        </div>

        <overlay-component v-if="isLoading || isBusy" />

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
    import { mapGetters, mapMutations, mapActions } from "vuex";
    import axios from "axios";

    export default {

        data() {
            return {
                subListOpen: false,
                checked: 0,
                isLoading: false,
                subscription: 0,
                userInfo: {
                    number: '',
                    exp_month: '',
                    exp_year: '',
                    cvc: '',
                },
                validationParamsForm: {
                    number: false,
                    exp: false,
                    cvc: false,
                },
                isDisabledApplyButton: true,
                isDisabledAfterApplyButton: false,
                isClearCouponField: false,
                defaultDiscount: {
                    success: false,
                    message: '',
                    set: {
                        type: '',
                        value: 0
                    }
                },

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
            }
        },
        watch: {
            checked(){
                this.validationForm()
                this.deleteCoupon();
                this.clearErrors()
            },
            subscription(){
                this.setSubscription(this.subscription);
                // this.getPrice();
            }
        },
        mounted() {
            window.addEventListener('message', (e) => {
                if (e.origin.indexOf('hooks.stripe.com') !== -1) {
                    this.setCheckout(null);
                    this.register({userInfo: this.userInfo, router: this.$router});
                }
            }, false);
            this.getPrice();
            this.subscription = this.subscriptionPlan.id;
        },
        computed: {
            ...mapGetters({
                user: 'auth/user',
                checkout: 'info/getCheckout',
                userData: 'info/getUserData',
                isBusy: 'info/getIsBusy',
                errors: 'info/getErrors',
                price: 'info/getPrice',
                discount: 'info/getDiscount',
                userParams: 'params/getUserParams',
                initialGoal: 'params/getInitialGoal',
                subscriptionPlan: 'info/getSubscriptionPlan',
                subscriptionStatus: 'auth/getSubscriptionStatus',
                isValidUserForm: 'info/getIsValidUserForm',
                errorsRegister: 'info/getErrorsRegister',
            }),
            computedShowWarningMessage() {
                return !!this.errors.length;
            },
            computedDiscountSuccess() {
                return this.discount.success;
            },
            couponRes() {
                if (this.discount.set.type === 'month') {
                    return 'TRIAL '+ (this.discount.set.value * this.subscriptionPlan.months + this.subscriptionPlan.trial) +' DAYS';
                } else {
                    let symbol = this.discount.set.type === 'fixed' ? 'ВЈ' : '%';

                    return 'SALE '+ this.discount.set.value + symbol;
                }
            },
            validSubscription() {
                if (this.subscription == 0) {
                    return undefined;
                }

                return this.price.filter( p => p.id == this.subscription)[0];
            }
        },
        methods: {
            ...mapMutations({
                setCheckout: 'info/setCheckout',
                setCouponNumber: 'info/setCouponNumber',
                deleteDiscount: 'info/deleteDiscount',
                setIsValidUserForm: 'info/setIsValidUserForm',
                clearErrors: 'info/clearErrors',
                setSubscription: 'info/setSubscription',
                addErrorRegister: 'info/addErrorRegister',
                setSuccessMessage: 'info/setSuccessMessage',
                clearErrorsRegister: 'info/clearErrorsRegister',
            }),
            ...mapGetters({
                getCouponNumber: 'info/getCouponNumber'
            }),
            ...mapActions({
                checkCoupon: 'info/checkCoupon',
                getPrice: 'info/getPrice',
                register: 'info/register'
            }),
            onUpdate(payload) {
                this.results = payload;
                this.isValid = payload.isValid === false;
                payload.formattedNumber ? this.phone = payload.formattedNumber : this.phone = '';
                this.actionsInputData()
            },
            actionsInputData(name, state, value) {
                this.setParam(name, value);
                this.validationParam(name, state);
                this.validationForm();
            },
            setParam(name, value) {
                for (let key in this.userInfo) {
                    if (key === name) {
                        this.userInfo[key] = value;
                    }
                }

                if (name === 'exp') {
                    const DIVIDER = '/';
                    const expValue = value.split(DIVIDER);
                    this.userInfo.exp_month = Number(expValue[0]);
                    this.userInfo.exp_year = Number(expValue[1]);
                }
            },
            validationParam(name, state) {
                for (let key in this.validationParamsForm) {
                    if (key === name) {
                        this.validationParamsForm[key] = state;
                    }
                }
            },
            validationForm() {
                const values = Object.values(this.validationParamsForm);
                let isNotValid = false;

                for (let i = 0; i < values.length; i++) {
                    if (!values[i]) {
                        isNotValid = true;
                        break;
                    }
                }
                if(!isNotValid && !this.isValid){
                    if(this.checked == 0) {
                        this.setIsValidUserForm(false);
                        this.setCouponNumber('');
                    } else {
                        if(this.computedDiscountSuccess == true) {
                            this.setIsValidUserForm(false);
                        } else {
                            this.setIsValidUserForm(true);
                        }
                    }
                } else {
                    this.setIsValidUserForm(true);
                }
            },
            async applyCoupon() {
                await this.checkCoupon();
                await this.validationForm();
            },
            deleteCoupon() {
                this.deleteDiscount(this.defaultDiscount);
                this.isClearCouponField = true;
            },
            validationCouponNumber(name, state, value) {
                if (state) {
                    this.setCouponNumber(value);
                    this.isDisabledApplyButton = false;
                } else {
                    this.setCouponNumber('');
                    this.isDisabledApplyButton = true;
                }
            },
            clearComplete() {
                this.isClearCouponField = false;
            },
            createSubscription() {
                this.isLoading = true;
                this.clearErrorsRegister()

                let finaly = true,
                    action = this.verification ? '/api/subscription/create' : '/api/subscription/create-check',
                    data = {
                        number: this.userInfo.number.replace(/ /g, ''),
                        exp_year: this.userInfo.exp_year,
                        exp_month: this.userInfo.exp_month,
                        cvc: this.userInfo.cvc,
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
                                this.createSubscription();

                                break;
                            case 'success':
                                this.verification = null;
                                this.setSuccessMessage(response.data.message);
                                this.$emit('next');

                                break;
                            case 'requires_action':
                                this.verification = response.data.id;
                                this.checkout = response.data.action.redirect_to_url.url;

                                break;
                            case 'requires_payment_method':
                                this.addErrorRegister('It\'s impossible to connect this card to your account. Please use another card and try again.', 'error');
                                break;
                        }
                    })
                    .catch((error) => {
                        this.verification = null;
                        const errors = error.response.data.errors;

                        if (errors) {
                            Object.keys(errors).forEach((k) => {
                                errors[k].forEach((e) => {
                                    this.addErrorRegister(e);
                                })
                            })
                        } else {
                            this.addErrorRegister(error.response.data.message);
                        }
                    })
                    .then(() => {
                        if (finaly) {
                            this.isLoading = false;
                        }
                    })
            }
        }
    }
</script>

<style scoped lang="scss">
    .subscription-button {
        border: 1px solid var(--grey);
        border-radius: 7px;
        padding: 10px;
        background: var(--white);
        color: var(--black);
        margin-bottom: 37px;
        display: flex;
        align-items: center;
        width: 100%;

        &__arrow-icon {
            margin-right: 10px;
            border-radius: 50%;
            width: 29px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 29px;
            background: #FFFFFF;
            box-shadow: 0px 1px 6px rgba(0, 0, 0, 0.25);
        }
    }
    .create-subscription {
        font-family: Arial;
        padding: 160px 30px 120px 30px;
        // min-height: 100vh;

        @media screen and (max-width: 767px) {
            padding: 90px 20px;
        }

        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;

        @media screen and (max-width: 767px) {
            flex-direction: column;
        }


        &__txt {
            text-align: center;

            &__capital {
                text-transform: uppercase;
            }
            &__welcome {
                font-size: 28px;
                font-weight: 600;
                line-height: 132%;
                padding-bottom: 40px;
                @media screen and (max-width: 767px) {
                    font-size: 24px;
                }
            }
            &__info {
                font-size: 40px;
                font-weight: 800;
                line-height: 100%;
                padding-bottom: 20px;
                @media screen and (max-width: 767px) {
                    font-size: 30px;
                }
            }
            &__small {
                font-size: 24px;
                font-weight: 500;
                line-height: 132%;
                padding-bottom: 20px;
                @media screen and (max-width: 767px) {
                    font-size: 20px;
                }
            }
        }

        &__info {
            max-width: 480px;
            //margin-right: 120px;

            @media screen and (min-width: 768px) and (max-width: 991px) {
                //margin-right: 60px;
            }

            @media screen and (max-width: 767px) {
                //margin-right: 0px;
            }
        }
    }

    @import '../../../sass/mixins';

    @media screen and (max-width: 1201px){
        .button-apply {
            width: auto!important;
        }
    }

    input.input-container__entry {
        font-weight: 100;
    }

    .input-container__error {
        color: red;
        font-size: 16px;
        min-height: 20px;
        position: absolute;
        left: 20px;
        top: 0;
        background-color: #fae6e6;
        height: 100%;
        width: 75% !important;
        align-items: center;
        display: flex;
        z-index: 5;
        font-weight: 100 !important;
    }


    .payment-info {
        display: flex;
        flex-direction: column;
        align-items: center;

        &__row, &__name, &__email, &__password, &__phone {
            display: flex;
            flex-direction: row;

            @media screen and (max-width: 991px) {
                flex-direction: column;
            }
        }

        &__subscriptions-group{
            border: 1px solid var(--grey);
            border-radius: 7px;
            padding: 10px;
            background: var(--white);
            color: var(--black);
            margin-bottom: 37px;

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
                    margin-right: 10px;
                }
            }
        }

        &__card {
            // max-width: 330px;
            margin-bottom: 20px;

            @media screen and (max-width: 1200px) {
                max-width: 100%;
                margin-bottom: 25px;
            }

            &__number {
                flex-basis: 100%;
            }
        }

        &__secure {
            display: flex;
            flex-direction: row;
            margin-bottom: 37px;

            @media screen and (max-width: 1200px) {
                margin-bottom: 29px;
            }

            &__period {
                // max-width: 160px;
                margin-right: 20px;
            }

            &__cvc {
                max-width: 118px;
            }
        }

        &__coupon {
            display: flex;
            flex-direction: row;
            &__number {
                min-width: 200px;
                margin-right: 16px;

                @media screen and (max-width: 1200px) {
                    min-width: auto;
                    width: 100%;
                }
            }
        }

        &__description {
            @include base-font;

            text-align: center;
            margin-bottom: 25px;
        }

        &__message {
            text-align: center;

            .warning-container {
                min-height: 36px;
            }
        }

        &__trial {
            font-size: 15px;
            font-weight: 500;
            line-height: 151.19%;
            color: var(--primary);
            text-align: center;
            margin-bottom: 25px;
        }

        &__free {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 8px;
        }
    }

    .p-b-16 {
        padding-bottom: 16px;
    }

    .p-t-8 {
        padding-top: 8px;
    }

    .payment-info__checked-group {
        display: flex;
        margin-bottom: 37px;
        label.payment-info__checked:first-child {
            margin-right: 27px;
        }
    }
</style>
