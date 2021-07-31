<template>
    <div class="user-info">
        <form>
            <input type="hidden" v-model="computedRegisterUser">
            <div class="user-info__name">
                <input-first-name class="user-info__name__first"
                                  @validation="actionsInputData"
                />
                <input-last-name class="user-info__name__last"
                                 @validation="actionsInputData"
                />
            </div>
            <div class="user-info__email">
                <div class="user-info__email__enter">
                    <BaseInput
                        v-model="form.email.value"
                        :name="'email'"
                        :type="'email'"
                        :placeHolder="'Email'"
                        :errorMessage="form.email.validation.message"
                        :validation-callback="form.email.validation.callback"
                        :validation-state="form.email.validation.validated"
                        :required="form.email.required"
                    />
                </div>

                <div class="user-info__email__confirm">
                    <BaseInput
                        v-model="form.confirm_email.value"
                        :name="'confirm email'"
                        :type="'email'"
                        :placeHolder="'Confirm email'"
                        :errorMessage="form.confirm_email.validation.message"
                        :validation-callback="form.confirm_email.validation.callback"
                        :validation-state="form.confirm_email.validation.validated"
                        :required="form.confirm_email.required"
                    />
                </div>





            </div>
            <div class="user-info__password">
                <div class="user-info__password__enter">
                    <BaseInput
                        v-model="form.password.value"
                        :name="'password'"
                        :place-holder="'Password'"
                        :type="form.password.type"
                        :required="form.password.required"
                        :error-message="form.password.validation.message"
                        :validation-callback="form.password.validation.callback"
                        :validation-state="form.password.validation.validated"
                        :min="form.password.validation.rules.min"
                        :max="form.password.validation.rules.max"
                    />
                </div>


                <div class="user-info__password__confirm">
                    <BaseInput class="confirm-password"
                               v-model="form.password_confirm.value"
                               :name="'password'"
                               :place-holder="'Password confirm'"
                               :type="form.password_confirm.type"
                               :required="form.password_confirm.required"
                               :error-message="form.password_confirm.validation.message"
                               :validation-callback="form.password_confirm.validation.callback"
                               :validation-state="form.password_confirm.validation.validated"
                               :min="form.password_confirm.validation.rules.min"
                               :max="form.password_confirm.validation.rules.max"
                    />
                </div>

            </div>
            <div class="user-info__phone">
                <VuePhoneNumberInput
                    v-model="input_phone"
                    :required="true"
                    :default-country-code="this.$phrase.countryShortCode"
                    clearable
                    @update="onUpdate"
                />
            </div>
            <div class="user-info__card">
                <input-card-number class="user-info__card__number"
                                   @validation="actionsInputData"
                />
            </div>
            <div class="user-info__secure">
                <input-card-valid-period class="user-info__secure__period"
                                         @validation="actionsInputData"
                />
                <input-card-cvc class="user-info__secure__cvc"
                                @validation="actionsInputData"
                />
            </div>
        </form>

        <div class="user-info__checked-group">
            <label class="user-info__checked">
                <input type="radio" name="user-info__coupon" value="0"
                       v-model.number="checked"
                >
                <svg width="15" height="10" viewBox="0 0 15 10" fill="#DF2C65">
                    <path d="M14.7399 0.253771C14.3932 -0.0846007 13.831 -0.0845998 13.4843 0.25383L5.64331 7.90759L1.51579 3.87862C1.16908 3.54019 0.606842 3.54019 0.260075 3.87862C-0.0866917 4.21705 -0.0866917 4.76586 0.260075 5.10435L5.01542 9.74616C5.1888 9.91541 5.41605 10 5.64325 10C5.87044 10 6.09775 9.91535 6.27107 9.74616L14.7399 1.47951C15.0867 1.14108 15.0867 0.592259 14.7399 0.253771Z"/>
                </svg>
                <span class="text">Regular subscription</span>
            </label>

            <label class="user-info__checked">
                <input type="radio" name="user-info__coupon" value="1"
                       v-model.number="checked"
                >
                <svg width="15" height="10" viewBox="0 0 15 10" fill="#DF2C65">
                    <path d="M14.7399 0.253771C14.3932 -0.0846007 13.831 -0.0845998 13.4843 0.25383L5.64331 7.90759L1.51579 3.87862C1.16908 3.54019 0.606842 3.54019 0.260075 3.87862C-0.0866917 4.21705 -0.0866917 4.76586 0.260075 5.10435L5.01542 9.74616C5.1888 9.91541 5.41605 10 5.64325 10C5.87044 10 6.09775 9.91535 6.27107 9.74616L14.7399 1.47951C15.0867 1.14108 15.0867 0.592259 14.7399 0.253771Z"/>
                </svg>
                <span class="text">Coupon Code</span>
            </label>
        </div>

        <div class="user-info__show"
             v-if="checked"
        >
            <div>
                <div class="user-info__coupon">
                    <input-coupon-number class="user-info__coupon__number"
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
                <div class="user-info__message">
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

            <div class="user-info__trial" v-if="computedDiscountSuccess">
                <p class="user-info__free">{{ couponRes }}</p>
            </div>
        </div>

        <div class="user-info__subscriptions-group">
            <label class="user-info__subscription">
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
            <label class="user-info__subscription odd">
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
            <label class="user-info__subscription">
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
            <label class="user-info__subscription odd">
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
    </div>
</template>

<script>
import { mapGetters, mapMutations, mapActions } from "vuex";
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';
import IconOff from "../../../../../js/components/svg/IconOff";
import IconFire from "../../../../../js/components/svg/IconFire";

export default {
    components: {VuePhoneNumberInput, IconOff, IconFire},
    props: {
        isRegisterUser: {
            type: Boolean,
            required: true,
            default: false
        }
    },
    data() {
        return {
            isValid: false,
            results: null,
            input_phone: '',
            phone: null,
            checked: 0,
            subscription: 0,
            userInfo: {
                name: '',
                last_name: '',
                email: '',
                confirm_email: '',
                password: '',
                confirm_password: '',
                number: '',
                exp_month: '',
                exp_year: '',
                cvc: '',
                gender: '',
                age: '',
                weight: '',
                height: '',
            },
            validationParamsForm: {
                name: false,
                last_name: false,
                email: false,
                confirm_email: false,
                password: false,
                confirm_password: false,
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
                password: {
                    required: true,
                    value: '',
                    type: 'password',
                    validation: {
                        rules: {
                            min: 8,
                            max: 32,
                        },
                        message: '',
                        validated: 0,
                        callback: (event) => {
                            if (this.form.password.validation.rules.min > this.form.password.value.length || this.form.password.validation.rules.max < this.form.password.value.length) {
                                this.form.password.validation.validated = 2;
                                this.form.password.validation.message = `*Min ${this.form.password.validation.rules.min} -  max ${this.form.password.validation.rules.max}`
                                this.actionsInputData('password', false, this.form.password.value)
                            } else {
                                this.form.password.validation.validated = 1;
                                console.log('you whaet')
                                this.actionsInputData('password', true, this.form.password.value)

                            }

                            if(this.form.password_confirm.value.length > 0) {
                                console.log('need to check confirm')
                                this.form.password_confirm.validation.callback()
                            }
                        }
                    }
                },
                password_confirm: {
                    required: true,
                    value: '',
                    type: 'password',
                    validation: {
                        rules: {
                            min: 8,
                            max: 32,
                        },
                        message: '',
                        validated: 0,
                        callback: (event) => {
                            let isValid = this.form.password.value === this.form.password_confirm.value;

                            if (this.form.password_confirm.validation.rules.min > this.form.password_confirm.value.length || this.form.password_confirm.validation.rules.max < this.form.password_confirm.value.length) {
                                this.form.password_confirm.validation.validated = 2;
                                this.form.password_confirm.validation.message = `*Min ${this.form.password_confirm.validation.rules.min} -  max ${this.form.password_confirm.validation.rules.max}`
                                this.actionsInputData('confirm_password', false, this.form.password_confirm.value)


                            } else if (!isValid) {
                                this.form.password_confirm.validation.validated = 2;
                                this.form.password_confirm.validation.message = '*Confirmation is invalid'
                                this.actionsInputData('confirm_password', false, this.form.password_confirm.value)


                            } else {
                                this.form.password_confirm.validation.validated = 1;
                                this.actionsInputData('confirm_password', true, this.form.password_confirm.value)


                            }
                        }
                    }
                },
                email: {
                    required: true,
                    value: '',
                    validation: {
                        message: '',
                        validated: 0,
                        callback: (event) => {
                            let re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                            let isValid = re.test(this.form.email.value);
                            if (isValid) {
                                this.form.email.validation.validated = 1;
                                this.actionsInputData('email', true, this.form.email.value)
                            } else {
                                this.form.email.validation.validated = 2;
                                this.form.email.validation.message = '*Email is invalid'
                                this.actionsInputData('email', false, this.form.email.value)

                            }

                            if(this.form.confirm_email.value.length > 0) {
                                this.form.confirm_email.validation.callback()
                            }

                        }
                    },
                },
                confirm_email: {
                    required: true,
                    value: '',
                    validation: {
                        message: '',
                        validated: 0,
                        callback: (event) => {
                            let isValid = this.form.email.value === this.form.confirm_email.value;
                            let re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                            let regex = re.test(this.form.email.value);
                            if (isValid && regex) {
                                this.form.confirm_email.validation.validated = 1;
                                this.actionsInputData('confirm_email', true, this.form.email.value)

                            } else {
                                this.form.confirm_email.validation.validated = 2;
                                this.form.confirm_email.validation.message = '*Confirmation is invalid'
                                this.actionsInputData('confirm_email', false, this.form.email.value)

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
        this.getPrice();
        this.subscription = this.subscriptionPlan.id;
    },
    computed: {
        ...mapGetters({
            isBusy: 'info/getIsBusy',
            errors: 'info/getErrors',
            price: 'info/getPrice',
            discount: 'info/getDiscount',
            userParams: 'params/getUserParams',
            initialGoal: 'params/getInitialGoal',
            subscriptionPlan: 'info/getSubscriptionPlan',
        }),

        computedShowWarningMessage() {
            return !!this.errors.length;
        },
        computedDiscountSuccess() {
            return this.discount.success;
        },
        computedRegisterUser() {
            if (this.isRegisterUser) {
                this.registerUser();
            }
        },
        couponRes() {
            if (this.discount.set.type === 'month') {
                return 'TRIAL '+ (this.discount.set.value * this.subscriptionPlan.months + this.subscriptionPlan.trial) +' DAYS';
            } else {
                let symbol = this.discount.set.type === 'fixed' ? 'ВЈ' : '%';

                return 'SALE '+ this.discount.set.value + symbol;
            }
        }
    },
    methods: {
        ...mapMutations({
            setCouponNumber: 'info/setCouponNumber',
            deleteDiscount: 'info/deleteDiscount',
            setIsValidUserForm: 'info/setIsValidUserForm',
            clearErrors: 'info/clearErrors',
            setSubscription: 'info/setSubscription',
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
        registerUser() {
            //Add userParams from store for register
            this.userInfo.gender = this.userParams.gender.toLowerCase();
            this.userInfo.age = Number(this.userParams.age);
            this.userInfo.height = this.userParams.height;
            this.userInfo.weight = this.userParams.weight;
            this.userInfo.phone = this.phone;
            this.userInfo.number = this.userInfo.number.split(' ').join('');
            this.userInfo.initial_goal = this.initialGoal.goal;
            this.userInfo.initial_calories_goal = this.initialGoal.calories;
            this.userInfo.coupon = this.getCouponNumber();
            this.userInfo.subscription = this.subscription;

            this.register({userInfo: this.userInfo, router: this.$router});
            this.$emit('resetIsRegister');
        }
    }
}
</script>

<style lang="scss">
@import '../../../../../sass/mixins';

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


.user-info {
    display: flex;
    flex-direction: column;

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

    &__name {
        margin-bottom: 25px;

        &__first, &__last {
            flex-basis: 50%;
        }

        &__first {
            margin-right: 16px;

            @media screen and (max-width: 991px) {
                margin-right: 0px;
                margin-bottom: 25px;
            }
        }
    }

    &__email {
        //margin-bottom: 25px;

        &__enter, &__confirm {
            flex-basis: 50%;
        }

        &__enter {
            margin-right: 16px;

            @media screen and (max-width: 991px) {
                margin-right: 0px;
                //margin-bottom: 25px;
            }
        }

    }

    &__password {
        //margin-bottom: 25px;

        &__enter, &__confirm {
            flex-basis: 50%;
        }

        &__enter {
            margin-right: 16px;

            @media screen and (max-width: 991px) {
                margin-right: 0px;
                //margin-bottom: 25px;
            }
        }
    }

    &__phone {
        margin-bottom: 37px;

        .vue-phone-number-input {
            max-width: 330px;

            @media screen and (max-width: 1200px) {
                max-width: unset;
            }
        }

        @media screen and (max-width: 1200px) {
            margin-bottom: 57px;
        }
    }

    &__card {
        max-width: 330px;
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
            max-width: 160px;
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
            min-width: 330px;
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

.user-info__checked-group {
    display: flex;
    margin-bottom: 37px;
    label.user-info__checked:first-child {
        margin-right: 27px;
    }
}
</style>
