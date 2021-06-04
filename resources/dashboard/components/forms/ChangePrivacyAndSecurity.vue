<template>
    <div>
        <form @submit.prevent="handleForm">
            <h3 class="mb-1 color-6 input-title">
                Old password
            </h3>


            <base-input
                v-model="form.old_password.value"
                :name="'password'"
                :place-holder="'Old password'"
                :type="form.old_password.type"
                :required="form.old_password.required"
                :error-message="form.old_password.validation.message"
                :validation-callback="form.old_password.validation.callback"
                :validation-state="form.old_password.validation.validated"
                :disabled="loading"
                :min="form.old_password.validation.rules.min"
                :max="form.old_password.validation.rules.max"
            />
            <h3 class="mb-1 color-6 input-title">
                New password
            </h3>

            <base-input
                v-model="form.new_password.value"
                :name="'password'"
                :place-holder="'New password'"
                :type="form.new_password.type"
                :required="form.new_password.required"
                :error-message="form.new_password.validation.message"
                :validation-callback="form.new_password.validation.callback"
                :validation-state="form.new_password.validation.validated"
                :disabled="loading"
                :min="form.new_password.validation.rules.min"
                :max="form.new_password.validation.rules.max"
            />
            <h3 class="mb-1 color-6 input-title">
                New password confirm
            </h3>

            <base-input
                v-model="form.new_password_confirm.value"
                :name="'password'"
                :place-holder="'New password confirm'"
                :type="form.new_password_confirm.type"
                :required="form.new_password_confirm.required"
                :error-message="form.new_password_confirm.validation.message"
                :validation-callback="form.new_password_confirm.validation.callback"
                :validation-state="form.new_password_confirm.validation.validated"
                :disabled="loading"
                :min="form.new_password_confirm.validation.rules.min"
                :max="form.new_password_confirm.validation.rules.max"
            />

            <button-base
                :text="'Update password'"
                :type="'submit'"
                :disabled="loading || !validationForms()"
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
    name: "ChangePrivacyAndSecurity",
    components: {ButtonBase, BaseInput, OverlayComponent},
    data: function () {
        return {
            loading: false,
            message: '',
            form: {
                old_password: {
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
                            if (this.form.old_password.validation.rules.min > this.form.old_password.value.length || this.form.old_password.validation.rules.max < this.form.old_password.value.length) {
                                this.form.old_password.validation.validated = 2;
                                this.form.old_password.validation.message = `*Min ${this.form.old_password.validation.rules.min} -  max ${this.form.old_password.validation.rules.max} characters`

                            } else {
                                this.form.old_password.validation.validated = 1;
                            }
                        }
                    }
                },
                new_password: {
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
                            if (this.form.new_password.validation.rules.min > this.form.new_password.value.length || this.form.new_password.validation.rules.max < this.form.new_password.value.length) {
                                this.form.new_password.validation.validated = 2;
                                this.form.new_password.validation.message = `*Min ${this.form.new_password.validation.rules.min} -  max ${this.form.new_password.validation.rules.max} characters`

                            } else {
                                this.form.new_password.validation.validated = 1;
                            }
                        }
                    }
                },
                new_password_confirm: {
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
                            let isValid = this.form.new_password.value === this.form.new_password_confirm.value;

                            if (this.form.new_password_confirm.validation.rules.min > this.form.new_password_confirm.value.length || this.form.new_password_confirm.validation.rules.max < this.form.new_password_confirm.value.length) {
                                this.form.new_password_confirm.validation.validated = 2;
                                this.form.new_password_confirm.validation.message = `*Min ${this.form.new_password_confirm.validation.rules.min} -  max ${this.form.new_password_confirm.validation.rules.max} characters`
                            } else if (!isValid) {
                                this.form.new_password_confirm.validation.validated = 2;
                                this.form.new_password_confirm.validation.message = '*Confirm password is not valid!'
                            } else {
                                this.form.new_password_confirm.validation.validated = 1;
                            }
                        }
                    }
                },

            }
        }

    },
    mounted() {
    },
    methods: {
        validationForms() {
            let form = this.form
            return form.old_password.validation.validated == 1 && form.new_password.validation.validated == 1 && form.new_password_confirm.validation.validated == 1;
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
        populateFieldsFromStore() {
            let user = this.$store.state.auth.user;

            this.form.email.value = user.email;
            this.form.email.validation.validated = 1;
        },
        async handleForm(event) {
            event.preventDefault()
            this.loading = true;
            try {
                let res = await axios.post(`/api/user/settings/change-password`, {
                    old_password: this.form.old_password.value,
                    new_password: this.form.new_password.value,
                    new_password_confirm: this.form.new_password_confirm.value,
                })
                this.loading = false;
                this.$store.commit('auth/userInfo', res.data.user)
                this.message = res.data.message
                this.$notify({
                    group: 'planner',
                    title: `Change password`,
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
