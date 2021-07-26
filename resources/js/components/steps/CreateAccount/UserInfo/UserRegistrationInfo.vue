<template>
    <div class="user-info">
        <form>
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


            <enter-details
                :show-next="false"
                @validation="actionsInputData"
            />
            <h3>Choose your daily calorie goal</h3>
            <calculate-calorie
                :show-next="false"
                @validation="actionsInputData"
            />
            <div class="warning-container">
                <template v-for="error in errorsRegister">
                    <warning-message class="p-t-8" :message="error"/>
                </template>
            </div>

            <div style="display: flex; flex-direction: row; justify-content: center;">
                <button-next
                        :text="'REGISTER'"
                        :isDisabled="!isValidUserForm || isBusy"
                        @click="registerUser()"
                />
            </div>
        </form>

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
        data() {
            return {
                isValid: true,
                isValidUserForm: false,
                results: null,
                input_phone: '',
                phone: null,
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
                    details: false,
                    goal: false,
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
                                    this.actionsInputData('password', true, this.form.password.value)

                                }

                                if(this.form.password_confirm.value.length > 0) {
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
                isBusy: 'info/getIsBusy',
                errors: 'info/getErrors',
                userParams: 'params/getUserParams',
                initialGoal: 'params/getInitialGoal',
                errorsRegister: 'info/getErrorsRegister',
            }),

            computedShowWarningMessage() {
                return !!this.errors.length;
            },
        },
        methods: {
            ...mapMutations({
                setCouponNumber: 'info/setCouponNumber',
                deleteDiscount: 'info/deleteDiscount',
                setIsValidUserForm: 'info/setIsValidUserForm',
                clearErrors: 'info/clearErrors',
                setVerification: 'info/setVerification',
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
                    this.isValidUserForm = true;
                } else {
                    this.isValidUserForm = false;
                }
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

                this.setVerification('trail'); // Dummy Verification code
                this.register({userInfo: this.userInfo, router: this.$router});
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
        max-width: 700px;
        padding-bottom: 30px;
        @media screen and (max-width: 991px) {
            padding-bottom: 60px;
        }
        h3 {
            @include head-24-font;

            z-index: 2;
        }
        &__row , &__email, &__password, &__phone {
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
            display: flex;
            flex-direction: row;

            @media screen and (max-width: 991px) {
                margin-bottom: 0px !important;
            }

            margin-bottom: 25px;

            &__first, &__last {
                flex-basis: 50%;
            }

            &__first {
                margin-right: 16px;

                @media screen and (max-width: 991px) {
                    margin-right: 10px;
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
            margin-bottom: 25px;

            .vue-phone-number-input {
                max-width: 330px;

                @media screen and (max-width: 1200px) {
                    max-width: unset;
                }
            }

            @media screen and (max-width: 1200px) {
                margin-bottom: 25px;
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
