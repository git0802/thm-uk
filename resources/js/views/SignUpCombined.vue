<template>
    <app-layout nav-white hide-footer>
        <div class="signup">
            <div class="signup__bg">
                <div class="signup__container">
                    <div style="padding: 10px; display: flex; flex-direction: column; align-items: center;">
                        <div class="signup__text">
                            <div class="signup__text__intro">
                                Hi Hottie, let's get trim!
                            </div>
                            <div class="signup__text__quote">
                                Plan your meals to manage your weight.
                                <br>
                                <span v-if="is_mobile">

                                </span>
                                <span v-else>
                                    Fitness begins now, wherever you are.
                                </span>
                            </div>
                            <div class="signup__text__trial">
                                Get 2 weeks free, no billing during trial.
                                <div v-if="is_mobile">
                                    Cancel any time.
                                </div>
                            </div>
                        </div>
                        <div class="signup__form">
                            <div class="signup__form__image">
                                <img src="/images/signup_female.png" alt="SignUp" v-if="userParams.gender == 'Female'">
                                <img src="/images/signup_male.png" alt="SignUp" v-else>
                            </div>
                            <form>

                            </form>
                            <div class="signup__form__row row-m-b lato-input">
                                <gender-switch
                                    class="signup__form__gender"
                                    :name="'gender'"
                                    :options="['Male', 'Female']"
                                    :placeHolder="'Choose your gender'"
                                    :errorMessage="'*Choose your gender'"
                                    :disabled="false"
                                    :preselect-value="params.gender"
                                    @validation="validationSelectParam"
                                />
                                <input-user-params
                                    class="signup__form__age lato-input"
                                    :name="'age'"
                                    :placeHolder="'Age'"
                                    :minValue="12"
                                    :maxValue="120"
                                    :errorMessage="'*Enter age from 12 to 120'"
                                    :init-value="params.age"
                                    @validation="validationParam"
                                />
                            </div>
                            <div class="signup__form__row">
                                <input-user-params
                                    class="signup__form__weight lato-input"
                                    :name="'weight'"
                                    :placeHolder="'Weight (' + phrase.weightSm + ')'"
                                    :minValue="$phrase.minWeight"
                                    :maxValue="$phrase.maxWeight"
                                    :errorMessage="`*Enter weight from ${$phrase.minWeight} to ${$phrase.maxWeight}`"
                                    :init-value="params.weight"
                                    @validation="validationParam"
                                />

                                <div class="signup__form__heightCM">
                                    <input-user-height
                                        :init-value="params.height"
                                        :class-name="'signup__form__entry lato-input'"
                                        @validation="validationUserHeight"
                                    />
                                </div>
                            </div>
                            <div class="signup__form__name">
                                <input-first-name class="signup__form__name__first"
                                                  @validation="actionsInputData"
                                />
                                <input-last-name class="signup__form__name__last"
                                                 @validation="actionsInputData"
                                />
                            </div>
                            <div class="signup__form__email">
                                <div class="signup__form__email__enter">
                                    <BaseInputUnderlined
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

                                <div class="signup__form__email__confirm">
                                    <BaseInputUnderlined
                                        v-model="form.confirm_email.value"
                                        :name="'confirm email'"
                                        :type="'email'"
                                        :placeHolder="'Confirm'"
                                        :errorMessage="form.confirm_email.validation.message"
                                        :validation-callback="form.confirm_email.validation.callback"
                                        :validation-state="form.confirm_email.validation.validated"
                                        :required="form.confirm_email.required"
                                    />
                                </div>





                            </div>
                            <div class="signup__form__password">
                                <div class="signup__form__password__enter">
                                    <BaseInputUnderlined
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


                                <div class="signup__form__password__confirm">
                                    <BaseInputUnderlined class="confirm-password"
                                               v-model="form.password_confirm.value"
                                               :name="'password'"
                                               :place-holder="'Confirm'"
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

                            <div class="signup__form__phone lato-input">
                                <VuePhoneNumberInput
                                    class="lato-input"
                                    v-model="input_phone"
                                    :required="true"
                                    :default-country-code="this.$phrase.countryShortCode"
                                    clearable
                                    @update="onUpdate"
                                />
                            </div>

                            <div class="signup__form__subhead">
                                Choose your daily calorie goal
                            </div>
                            <div class="signup__form__calculate">
                                <select-params class="signup__form__goal lato-input"
                                               :options="goalList"
                                               :placeHolder="'Choose your goal'"
                                               :errorMessage="'*Choose your goal'"
                                               :disabled="false"
                                               :preselect-value="initialGoal.selectedGoal"
                                               @validation="calculateCalories"
                                />
                                <input-disabled class="signup__form__result lato-input"
                                                :placeHolder="'Calories goal'"
                                                :value="initialGoal.calories"
                                />
                            </div>

                            <div class="signup__form__subhead">
                                My Plan
                            </div>

                            <div class="signup__form__checked-group">
                                <select-plan class="signup__form__sub"
                                               :options="price"
                                               :placeHolder="'Choose a subscription plan'"
                                               :errorMessage="'*Choose a subscription plan'"
                                               :disabled="false"
                                               :preselect-value="subscription"
                                               @input="validateSubscription($event)"
                                />
                                <label class="user-info__checked signup__form__checked lato-input">
                                    <input type="checkbox" name="signup__form__coupon" value="1"
                                           v-model.number="checked"
                                    >
                                    <svg width="15" height="10" viewBox="0 0 15 10" fill="#DF2C65">
                                        <path d="M14.7399 0.253771C14.3932 -0.0846007 13.831 -0.0845998 13.4843 0.25383L5.64331 7.90759L1.51579 3.87862C1.16908 3.54019 0.606842 3.54019 0.260075 3.87862C-0.0866917 4.21705 -0.0866917 4.76586 0.260075 5.10435L5.01542 9.74616C5.1888 9.91541 5.41605 10 5.64325 10C5.87044 10 6.09775 9.91535 6.27107 9.74616L14.7399 1.47951C15.0867 1.14108 15.0867 0.592259 14.7399 0.253771Z"/>
                                    </svg>
                                    <span class="text">Coupon Code</span>
                                </label>
                            </div>

                            <div class="signup__form__show"
                                 v-if="checked"
                            >
                                <div>
                                    <div class="signup__form__coupon">
                                        <input-coupon-number class="signup__form__coupon__number lato-input"
                                                             :isClear="isClearCouponField"
                                                             :isDisabled="computedDiscountSuccess"
                                                             @validation="validationCouponNumber"
                                                             @clear="clearComplete"
                                        />
                                        <button-apply-coupon
                                            class="button-apply lato-input"
                                            :isDisabled="isDisabledApplyButton"
                                            :isSuccess="computedDiscountSuccess"
                                            @click="applyCoupon"
                                        />
                                    </div>
                                    <div class="signup__form__message">
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

                                <div class="signup__form__trial" v-if="computedDiscountSuccess">
                                    <p class="signup__form__free">{{ couponRes }}</p>
                                </div>
                            </div>

                            <div class="flex-row-sm-col" style="justify-content: space-between">
                                <div class="signup__form__card">
                                    <input-card-number class="signup__form__card__number lato-input"
                                                       @validation="actionsInputData"
                                    />
                                </div>
                                <div class="signup__form__secure">
                                    <input-card-valid-period class="signup__form__secure__period lato-input"
                                                             @validation="actionsInputData"
                                    />
                                    <input-card-cvc class="signup__form__secure__cvc lato-input"
                                                    @validation="actionsInputData"
                                    />
                                </div>
                            </div>

                            <div class="flex signup__form__button-div">
                                <button-complete-order style="max-width: 220px"
                                    :isDisabled="!isValidUserForm || isBusy"
                                    @click="registerUser"
                                />
                            </div>
                            <div class="warning-container">
                                <template v-for="error in errorsRegister">
                                    <warning-message class="p-t-8" :message="error"/>
                                </template>
                            </div>
                            <payment-secure-info/>
                            <overlay-component v-if="isBusy" />
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

                    </div>

                </div>
            </div>

        </div>
    </app-layout>
</template>

<script>
    import {mapActions, mapGetters, mapMutations} from "vuex";
    import GenderSwitch from "../components/inputs/inputsUserInfo/GenderSwitch";
    import VuePhoneNumberInput from 'vue-phone-number-input';
    import 'vue-phone-number-input/dist/vue-phone-number-input.css';


    export default {
        name: "SignUpCombined",
        components: {GenderSwitch, VuePhoneNumberInput},
        data() {
            return {
                maintainCalories: 0,
                stepCalories: 250,
                activityFactor: 1.2,
                goalList: this.$phrase.goalList,
                isPhoneValid: false,
                results: null,
                input_phone: '',
                phone: null,
                checked: 0,
                subscription: 0,
                isDisabledNextButton: true,
                isValidUserForm: false,
                initialGoal: {
                    calories: '',
                    goal: 0,
                    selectedGoal: ''
                },
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
                    gender: false,
                    age: false,
                    weight: false,
                    height: false,
                    subscription: false,
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
        computed: {
            ...mapGetters({
                params: 'params/getUserParams',
                isBusy: 'info/getIsBusy',
                errors: 'info/getErrors',
                price: 'info/getPrice',
                discount: 'info/getDiscount',
                userParams: 'params/getUserParams',
                subscriptionPlan: 'info/getSubscriptionPlan',
                goal: 'params/getInitialGoal',
                errorsRegister: 'info/getErrorsRegister',
                checkout: 'info/getCheckout',
                is_mobile: "plannerUi/getMobile",
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
                setUserParam: 'params/setUserParam',
                setCouponNumber: 'info/setCouponNumber',
                deleteDiscount: 'info/deleteDiscount',
                clearErrors: 'info/clearErrors',
                setSubscription: 'info/setSubscription',
                setCheckout: 'info/setCheckout'
            }),
            ...mapGetters({
                getCouponNumber: 'info/getCouponNumber'
            }),
            ...mapActions({
                checkCoupon: 'info/checkCoupon',
                getPrice: 'info/getPrice',
                register: 'info/register'
            }),
            calculateMaintainCalories() {
                const weightKG = (this.$useMetricSystem ? this.userParams.weight : this.convertToKg(this.userParams.weight));
                const heightCM = this.userParams.height;
                const age = this.userParams.age;
                const genderCorrectingValue = this.userParams.gender === 'Male' ? 5 : -161;
                //Mifflin-St Jeor Equation
                this.maintainCalories = Math.round((10 * weightKG + 6.25 * heightCM - 5 * age + genderCorrectingValue) * this.activityFactor);
            },
            calculateCalories(name, state, value) {
                if (state) {
                    this.calculateMaintainCalories();
                    const MIN_CALORIES_MEN = 1200;
                    const MIN_CALORIES_WOMEN = 1000;
                    const minCalories = this.userParams.gender === 'Male' ? MIN_CALORIES_MEN : MIN_CALORIES_WOMEN;

                    this.initialGoal.selectedGoal = value;

                    const index = this.goalList.findIndex((item) => {
                        return item === value;
                    });

                    if (index !== -1) {
                        let goalListValues = this.$phrase.goalListValues;
                        if (index === 0) {
                            this.initialGoal.calories = this.maintainCalories - 1000;
                        } else if (index === 6) {
                            this.initialGoal.calories = this.maintainCalories + 1000;
                        } else {
                            this.initialGoal.calories = this.maintainCalories + (this.stepCalories * (index - 3));
                        }
                        this.initialGoal.goal = goalListValues[index];
                    }

                    if (this.initialGoal.calories < minCalories) {
                        this.initialGoal.calories = minCalories;
                    }

                    this.initialGoal.calories = String(this.initialGoal.calories);

                }
            },
            onUpdate(payload) {
                this.results = payload;
                this.isPhoneValid = payload.isValid;
                payload.formattedNumber ? this.phone = payload.formattedNumber : this.phone = '';
                this.validationForm()
            },
            actionsInputData(name, state, value) {
                this.setParam(name, value);
                this.validationParam(name, state);
                // this.validationForm();
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
            validationUserHeight(validated, value) {
                this.validationParamsForm.height = validated == 1 ? true : false;
                this.height = value;

                this.setUserParam({
                    key: 'height',
                    value: value
                });

                this.validationForm();
            },
            validateSubscription(sub) {
                if (sub && sub.id) {
                    this.subscription = sub.id
                    this.validationParam('subscription', true);
                } else {
                    this.validationParam('subscription', false);
                }
            },
            validationSelectParam(name, state, value) {
                if (state) {
                    this.setUserParam({
                        key: name,
                        value: value
                    });
                } else {
                    this.setUserParam({
                        key: name,
                        value: ''
                    });
                }

                this.validationParam(name, state);
            },
            validationParam(value, state) {
                for (let key in this.validationParamsForm) {
                    if (key === value) {
                        this.validationParamsForm[key] = state;
                    }
                }

                this.validationForm();
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

                if(!isNotValid && this.isPhoneValid){
                    if(this.checked == 0) {
                        this.isValidUserForm = true;
                        this.setCouponNumber('');
                    } else {
                        if(this.computedDiscountSuccess == true) {
                            this.isValidUserForm = true;
                        } else {
                            this.isValidUserForm = false;
                        }
                    }
                } else {
                    this.isValidUserForm = false;
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
        },
        watch: {
            userParams: {
                handler: function (val, oldVal) {
                    if (this.initialGoal.selectedGoal) {
                        this.calculateCalories('goal', true, this.initialGoal.selectedGoal)
                    }
                },
                deep: true
            }
        },
        mounted() {
            window.addEventListener('message', (e) => {
                if (e.origin.indexOf('hooks.stripe.com') !== -1) {
                    this.setCheckout(null);
                    this.register({userInfo: this.userInfo, router: this.$router});
                }
            }, false);
        },
    }
</script>

<style lang="scss" scoped>
    @import '../../sass/_mixins.scss';
    .payment-info {
        @include base-font;

        display: flex;
        flex-direction: column;

        .warning-container {
            min-height: 33px;
            text-align: center;
            padding-bottom: 20px;
        }
        button {
            cursor: pointer;
        }
    }

    .p-t-8 {
        padding-top: 8px;
    }
    .signup {
        background-image: url('/images/signup_bg.png');
        min-height: calc(100vh - 99px);
        background-size: cover;

        &__bg {
            background: linear-gradient(138.61deg, rgba(0, 0, 0, 0.21) -6.29%, rgba(0, 0, 0, 0.49) 28.27%, rgba(113, 48, 120, 0.59) 57.06%, #5A2A79 105.21%);
            min-height: calc(100vh - 99px);

            @media screen and (max-width: 991px) {
                background: linear-gradient(180deg, #C84676 0%, #993A77 43.23%, #713078 73.44%, #5A2A79 100%);
                // opacity: 0.95;
            }
        }

        &__container {
            padding-top: 110px;
            padding-bottom: 66px;
            display: flex;
            flex-direction: column;
            align-items: center;
            @media screen and (max-width: 991px) {
                padding-top: 100px;
            }
        }

        &__form {
            background: var(--white);
            margin-top: 60px;
            max-width: 622px;
            box-shadow: 20px 20px 40px rgba(0, 0, 0, 0.09);
            border-radius: 40px;
            padding: 0px 30px 50px 30px;
            font-weight: 500;

            @media screen and (max-width: 991px) {
                padding: 0px 15px 50px 15px;
                margin-top: 40px;
            }

            &__image {
                text-align: center; padding: 25px;
                @media screen and (max-width: 991px) {
                    padding: 10px;
                }

                & img {
                    @media screen and (max-width: 991px) {
                        width: 152px;
                    }
                }
            }

            &__button-div {
                justify-content: center;
                padding: 30px 0px;
                @media screen and (max-width: 991px) {
                    padding: 15px 0px;
                }
            }

            &__subhead {
                font-style: normal;
                font-weight: 600;
                font-size: 18px;
                line-height: 27px;
                padding-bottom: 16px;
                font-family: Poppins;
                @media screen and (max-width: 991px) {
                    font-size: 15px;
                    line-height: 22px;
                }
            }

            &__row{
                display: flex;
                flex-direction: row;
            }

            &__name, &__email, &__password, &__phone {
                display: flex;
                flex-direction: row;

                @media screen and (max-width: 991px) {
                    flex-direction: column;
                    font-size: 14px !important;
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
                margin-bottom: 20px;

                @media screen and (max-width: 991px) {
                    margin-bottom: 10px;
                }


                &__first, &__last {
                    flex-basis: 50%;
                }

                &__first {
                    margin-right: 16px;

                    @media screen and (max-width: 991px) {
                        margin-right: 0px;
                        margin-bottom: 10px;
                    }
                }
            }

            &__email {
                margin-bottom: 20px;

                @media screen and (max-width: 991px) {
                    margin-bottom: 10px;
                }

                &__enter, &__confirm {
                    flex-basis: 50%;
                }

                &__enter {
                    margin-right: 16px;

                    @media screen and (max-width: 991px) {
                        margin-right: 0px;
                        margin-bottom: 10px;
                    }
                }

            }

            &__password {
                margin-bottom: 20px;

                @media screen and (max-width: 991px) {
                    margin-bottom: 10px;
                }

                &__enter, &__confirm {
                    flex-basis: 50%;
                }

                &__enter {
                    margin-right: 16px;

                    @media screen and (max-width: 991px) {
                        margin-right: 0px;
                        margin-bottom: 10px;
                    }
                }
            }

            &__phone {
                margin-bottom: 20px;

                .vue-phone-number-input {
                    max-width: 330px;

                    @media screen and (max-width: 600px) {
                        max-width: unset;
                    }
                }

                @media screen and (max-width: 600px) {
                    margin-top: 16px;
                    margin-bottom: 16px;
                }
            }

            &__card {
                // max-width: 330px;
                margin-bottom: 20px;
                margin-right: 20px;
                flex-basis: 50%;

                @media screen and (max-width: 600px) {
                    max-width: 100%;
                    margin-bottom: 16px;
                    margin-right: 0px;
                }

                &__number {
                    flex-basis: 100%;
                }
            }

            &__secure {
                display: flex;
                flex-direction: row;
                margin-bottom: 20px;
                flex-basis: 50%;

                @media screen and (max-width: 1200px) {
                    margin-bottom: 29px;
                }

                &__period {
                    // max-width: 160px;
                    margin-right: 20px;
                }

                &__cvc {
                    // max-width: 118px;
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

            &__calculate {
                display: flex;
                flex-direction: row;
                justify-content: center;
                margin-bottom: 20px;

                @media screen and (max-width: 600px) {
                    flex-direction: column;
                    margin-bottom: 16px;
                }

            }

            &__goal {
                flex-basis: 50%;
                margin-right: 20px;

                .v-select {
                    .vs__selected, .vs__search {
                        font-weight: 300;
                    }
                }

                @media screen and (max-width: 600px) {
                    margin-right: 0px;
                    margin-bottom: 16px;
                }
            }
            &__result {
                flex-basis: 50%;
            }

            &__checked-group {
                display: flex;
                flex-direction: row;
                margin-bottom: 20px;

                @media screen and (max-width: 600px) {
                    flex-direction: column;
                    margin-bottom: 16px;
                }
            }

            &__checked {
                align-self: center;
                @media screen and (max-width: 600px) {
                    align-self: auto;
                }
            }

            &__sub {
                flex-basis: 50%;
                margin-right: 10px;
                padding-right: 10px;

                .v-select {
                    .vs__selected, .vs__search {
                        font-weight: 300;
                    }
                }
                @media screen and (max-width: 600px) {
                    margin-right: 0px;
                    margin-bottom: 16px;
                    padding-right: 0px;
                }
            }

            &__gender, &__weight {
                flex-basis: 50%;
                margin-right: 20px;

                @media screen and (max-width: 600px) {
                    margin-right: 10px;
                }
            }

            &__weight {
                @media screen and (max-width: 600px) {
                    margin-right: 10px;
                    flex-basis: 33%;
                }
            }

            &__age, &__heightCM {
                flex-basis: 50%;

                @media screen and (max-width: 600px) {
                    margin-bottom: 0px;
                }
            }

            &__heightCM {

                @media screen and (max-width: 600px) {
                    flex-basis: 66%;
                }
            }
        }

        &__text {
            text-align: center;
            color: var(--white);
            font-family: Poppins;
            font-style: normal;
            padding: 0px 40px;

            &__intro {
                font-weight: 500;
                font-size: 24px;
                line-height: 36px;

                @media screen and (max-width: 991px) {
                    font-size: 18px;
                    line-height: 27px;
                }
            }

            &__quote {
                font-weight: 600;
                font-size: 36px;
                line-height: 54px;

                @media screen and (max-width: 991px) {
                    font-size: 24px;
                    line-height: 31px;
                    margin-top: 12px;
                    font-weight: bold;
                }
            }

            &__trial {
                font-weight: 500;
                font-size: 20px;
                line-height: 30px;

                @media screen and (max-width: 991px) {
                    font-size: 14px;
                    line-height: 21px;
                    margin-top: 4px;
                }
            }


        }
        .row-m-b {
            margin-bottom: 20px;
            @media screen and (max-width: 600px) {
                margin-bottom: 16px;
            }
        }




    }
    .flex-row-sm-col {
        display: flex;
        flex-direction: row;
        @media screen and (max-width: 600px) {
            flex-direction: column;
        }
    }

    .lato-input {
        font-family: Lato;
        font-style: normal !important;
        font-weight: 500 !important;
        font-size: 14px !important;
        line-height: 17px !important;
        color: rgba(39, 44, 50, 0.67)!important;

        @media screen and (max-width: 600px) {
            font-size: 12px !important;
            line-height: 14px !important;
        }
    }
</style>
