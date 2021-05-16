<template>
    <app-flexed-layout>
        <div class="wrapper__set-password">
            <div class="wrapper wrapper__padded wrapper__break layout-centered">
                <section class="set-password">
                    <h2>Update your password</h2>
                    <div class="form-contain">
                        <form @submit.prevent="updatePassword">
                            <BaseInput
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

                            <BaseInput class="confirm-password"
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
                            <div class="set-password__message">
                                {{ message }}
                            </div>
                            <button-base
                                :type="'submit'"
                                :text="'UPDATE PASSWORD'"
                                :disabled="loading"
                                :styles="{'max-width': 'unset'}"
                            />
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </app-flexed-layout>
</template>

<script>
import axios from 'axios'

export default {
    name: "SetNewPassword",
    data() {
        return {
            form: {
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
            },

            message: '',
            loading: false,
        }
    },
    methods: {
        async updatePassword() {
            this.message = '';
            this.loading = true;

            try {
                let res = await axios.post(`/api/password/reset`, {
                    password: this.form.new_password.value,
                    password_confirm: this.form.new_password_confirm.value,
                    token: this.$route.params.token
                })

                setTimeout(() => {
                    this.$router.push('/login')
                }, 1500)

            } catch (error) {
                this.message = error.response.data.errors.password_confirm[0];
            }

            this.loading = false;
        }
    }
}
</script>

<style scoped lang="scss">
    @import '../../sass/_mixins.scss';

    .wrapper__set-password {
        height: 100%;
        background: #fff;
    }

    .set-password {
        min-height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 54px 0px 0px 0px;
        position: relative;
        min-width: 380px;

        @media screen and (max-width: 600px) {
            min-width: unset;
        }

        h2 {
            @include title-font;

            margin-bottom: 25px;
        }


        &__message {
            min-height: 20px;
            text-align: center;
            padding: 2px 0px;
        }

        .form-contain {
            width: 100%;
            z-index: 2;
            max-width: 380px;

            .confirm-password {
                margin-bottom: 0px;
            }
        }
    }
</style>
