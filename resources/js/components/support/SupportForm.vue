<template>
    <div class="support-form">
        <form @submit.prevent="handleForm">
            <div v-show="this.$route.path === '/contact'">
                <BaseInput
                    v-model="form.firstName.value"
                    :name="'firstname'"
                    :placeHolder="'First name'"
                    :errorMessage="form.firstName.validation.message"
                    :validation-callback="form.firstName.validation.callback"
                    :validation-state="form.firstName.validation.validated"
                    :required="form.firstName.required"
                    :min="form.firstName.validation.rules.min"
                    :max="form.firstName.validation.rules.max"
                />

                <BaseInput
                    v-model="form.lastName.value"
                    :name="'lastname'"
                    :placeHolder="'Last name'"
                    :errorMessage="form.lastName.validation.message"
                    :validation-callback="form.lastName.validation.callback"
                    :validation-state="form.lastName.validation.validated"
                    :required="form.lastName.required"
                    :min="form.lastName.validation.rules.min"
                    :max="form.lastName.validation.rules.max"
                />

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


                <VuePhoneNumberInput
                    :required="true"
                    @update="onUpdate"
                    default-country-code="GB"
                    clearable
                    v-model="form.phone.value"
                />

            </div>

            <BaseSelect
                :options="form.issue.options"
                v-model="form.issue.value"
                :disabled="false"
                :name="'issue'"
                :place-holder="'Issue'"
                :errorMessage="form.issue.validation.message"
                :validation-callback="form.issue.validation.callback"
                :validation-state="form.issue.validation.validated"
                :required="form.issue.required"

            />

            <BaseTextArea
                v-model="form.message.value"
                :required="true"
                :validation-state="form.message.validation.validated"
                :error-message="form.message.validation.message"
                :place-holder="'Enter your message'"
                :name="'text'"
                :validation-callback="form.message.validation.callback"
                :min="form.message.validation.rules.min"
                :max="form.message.validation.rules.max"

            />

            <button-base
                :text="'SUBMIT'"
                :type="'submit'"
                :disabled="loading"
            />

            <div class="response-message mt-5">
                {{ message }}
            </div>
        </form>
    </div>
</template>

<script>
import InputUserParams from "../inputs/InputUserParams";
import SelectParams from "../selects/SelectParams";
import BaseInput from "../inputs/BaseInput";
import InputSwitch from "../../../dashboard/components/inputs/input-switch";
import BaseSelect from "../inputs/BaseSelect";
import BaseTextArea from "../inputs/BaseTextArea";
import ButtonBase from "../buttons/ButtonBase";
import BaseInputMask from "../inputs/BaseInputMask";
import Axios from 'axios';
import DishContent from "../modals/ModalDish/DishContent";
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';

export default {
    name: "SupportForm",
    components: {
        DishContent,
        BaseSelect,
        InputSwitch,
        BaseInput,
        SelectParams,
        InputUserParams,
        BaseTextArea,
        ButtonBase,
        BaseInputMask,
        VuePhoneNumberInput
    },
    data: function () {
        return {
            isValid: false,
            results: null,
            phone_number: null,
            form: {
                firstName: {
                    required: true,
                    value: '',
                    validation: {
                        rules: {
                            min: 2,
                            max: 120,
                        },
                        message: '',
                        validated: 0,
                        callback: (event) => {
                            if (this.form.firstName.value.length < 1) {
                                this.form.firstName.validation.validated = 2;
                                this.form.firstName.validation.message = '*Enter your firstname'
                            } else if (this.form.firstName.validation.rules.min > this.form.firstName.value.length || this.form.firstName.validation.rules.max < this.form.firstName.value.length) {
                                this.form.firstName.validation.validated = 2;
                                this.form.firstName.validation.message = '*Min 2 -  max 120 characters'
                            } else {
                                this.form.firstName.validation.validated = 1;
                            }
                        }
                    },
                },
                lastName: {
                    required: true,
                    value: '',
                    validation: {
                        rules: {
                            min: 2,
                            max: 120,
                        },
                        message: '',
                        validated: 0,
                        callback: (event) => {
                            if (this.form.lastName.value.length < 1) {
                                this.form.lastName.validation.validated = 2;
                                this.form.lastName.validation.message = '*Enter your lastname'
                            } else if (this.form.lastName.validation.rules.min > this.form.lastName.value.length || this.form.lastName.validation.rules.max < this.form.lastName.value.length) {
                                this.form.lastName.validation.validated = 2;
                                this.form.lastName.validation.message = '*Min 2 -  max 120 characters'

                            } else {
                                this.form.lastName.validation.validated = 1;
                            }
                        }
                    },
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
                            } else {
                                this.form.email.validation.validated = 2;
                                this.form.email.validation.message = '*Email is not valid!'
                            }
                        }
                    },
                },
                phone: {
                    value: '',
                },
                issue: {
                    required: true,
                    value: '',
                    options: [
                        'Account Management',
                        'Meal Plan Question',
                        'Something isn\'t working',
                        'Other'
                    ],
                    validation: {
                        rules: {},
                        message: '',
                        validated: 0,
                        callback: (event) => {
                            if (this.form.issue.value) {
                                this.form.issue.validation.validated = 1;
                            } else {
                                this.form.issue.validation.message = 'Please select issue!';
                                this.form.issue.validation.validated = 2;

                            }
                        }
                    },
                },
                message: {
                    value: '',
                    required: true,
                    validation: {
                        message: '',
                        rules: {
                            min: 10,
                            max: 4096,
                        },
                        validated: 0,
                        callback: (event) => {
                            if (this.form.message.validation.rules.min > this.form.message.value.length || this.form.message.validation.rules.max < this.form.message.value.length) {
                                this.form.message.value = '';
                                this.form.message.validation.validated = 2;
                                this.form.message.validation.message = 'Min 10 - Max 4096 characters'
                            } else {
                                this.form.message.validation.validated = 1;
                            }
                        }
                    },
                }
            },
            loading: false,
            message: '',
        }
    },
    computed: {
        authenticated: {
            get() {
                return typeof (this.$store.state.auth) != "undefined" ? this.$store.state.auth.authenticated : false;
            }
        }
    },
    mounted() {
        if (this.authenticated) {
            this.populateFieldsFromStore();
        }
    },
    methods: {
        onUpdate(payload) {
            this.results = payload;
            this.isValid = payload.isValid === false;
            payload.formattedNumber ? this.phone_number = payload.formattedNumber : this.phone_number = null
        },
        populateFieldsFromStore() {
            let user = this.$store.state.auth.user;

            this.form.firstName.value = user.name;
            this.form.firstName.validation.validated = 1;

            this.form.lastName.value = user.last_name;
            this.form.lastName.validation.validated = 1;

            this.form.email.value = user.email;
            this.form.email.validation.validated = 1;

            this.form.phone.value = user.phone;
        },
        async handleForm(event) {
            event.preventDefault()
            this.loading = true;
            try {
                let res = await axios.post(`/api/support`, {
                    first_name: this.form.firstName.value,
                    last_name: this.form.lastName.value,
                    email: this.form.email.value,
                    phone: this.form.phone.value,
                    issue: this.form.issue.value,
                    message: this.form.message.value,
                })
                this.loading = false;

                this.message = res.data.message

                this.clearForm();
            } catch (e) {
                this.loading = false;
            }
        },
        clearForm() {
            Object.keys(this.form).forEach(f => {
                this.form[f].value = ''
                this.form[f].validation.validated = 0;
            });
            if (this.authenticated) {
                this.populateFieldsFromStore();
            }

        },
    }
}
</script>

<style lang="scss">
    .support-form {
        .a__mailto {
            color: var(--purple);
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .contact-form {
            &__caption {
                font-size: 16px;
                font-weight: 800;
                color: #575f6d;
            }

            &__inputs {
                display: flex;
                flex-direction: column;
            }
        }

        .vue-phone-number-input {
            margin-bottom: 20px;

            .country-selector, .input-tel {
                height: 39px!important;;
                min-height: 39px!important;
            }
        }
    }
</style>
