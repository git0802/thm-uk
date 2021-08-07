<template>
    <div>
        <form @submit.prevent="handleForm">
            <h3 class="mb-1 color-6 input-title">
                Email
            </h3>
            <base-input
                v-model="form.email.value"
                :name="'email'"
                :type="'email'"
                :placeHolder="'Email'"
                :errorMessage="form.email.validation.message"
                :validation-callback="form.email.validation.callback"
                :validation-state="form.email.validation.validated"
                :required="form.email.required"
            />
            <h3 class="mb-1 color-6 input-title">
                Confirm email
            </h3>
            <base-input
                v-model="form.confirm_email.value"
                :name="'confirm email'"
                :type="'email'"
                :placeHolder="'Confirm email'"
                :errorMessage="form.confirm_email.validation.message"
                :validation-callback="form.confirm_email.validation.callback"
                :validation-state="form.confirm_email.validation.validated"
                :required="form.confirm_email.required"
            />

            <button-base
                :text="'Update email'"
                :type="'submit'"
                :disabled="loading || !validationForm()"
                :classes="'button--purple'"
            />
            <overlay-component v-if="loading" />

        </form>

    </div>

</template>

<script>
import BaseInput from "../../../js/components/inputs/BaseInput";
import ButtonBase from "../../../js/components/buttons/ButtonBase";
import OverlayComponent from "../../../js/components/overlay/OverlayComponent";

export default {
    name: "ChangeEmail",
    components: {ButtonBase, BaseInput, OverlayComponent},
    data: function () {
        return {
            loading: false,
            isDisabledSaveButton: false,
            message: '',
            form: {
                email: {
                    required: true,
                    value: this.$store.state.auth.user.email,
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
                confirm_email: {
                    required: true,
                    value: '',
                    validation: {
                        message: '',
                        validated: 0,
                        callback: (event) => {
                            let isValid = this.form.email.value === this.form.confirm_email.value;
                            if (isValid) {
                                this.form.confirm_email.validation.validated = 1;
                            } else {
                                this.form.confirm_email.validation.validated = 2;
                                this.form.confirm_email.validation.message = '*Confirm Email is not valid!'
                            }
                        }
                    },
                },
            }
        }

    },
    mounted() {
    },
    methods: {
        validationForm() {
            return this.form.confirm_email.validation.validated == 1  && this.form.email.validation.validated == 1;
        },
        clearForm() {
            this.form.confirm_email.value = ''
            this.form.confirm_email.validation.validated = 0;
            if (this.authenticated) {
                this.populateFieldsFromStore();
            }

        },
        populateFieldsFromStore() {
            let user = this.$store.state.auth.user;

            this.form.email.value = user.email;
            this.form.email.validation.validated = 1;
        },
        async handleForm(event) {
            event.preventDefault()
            this.loading = true;
            try {
                let res = await axios.post(`/api/user/settings/change-email`, {
                    email: this.form.confirm_email.value
                })
                this.loading = false;
                this.$store.commit('auth/userInfo', res.data.user)
                this.message = res.data.message
                this.$notify({
                    group: 'planner',
                    title: `Change Email`,
                    type: 'success',
                    text: this.message,
                });
                this.clearForm();
            } catch (e) {
                let errResponse = e.response;
                Object.values(errResponse.data.errors).forEach(val => {
                    val.forEach(nVal => {
                        this.$notify({
                            group: 'planner',
                            title: 'Error!',
                            type: 'error',
                            text: nVal,
                        });
                    })
                });
                this.loading = false;
            }
        },
    }
}
</script>

<style scoped>
form {
    position: relative;
}
</style>
