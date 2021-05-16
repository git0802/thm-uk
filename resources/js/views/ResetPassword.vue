<template>
    <app-flexed-layout>
        <div class="wrapper__reset-password">
            <div class="wrapper wrapper__padded wrapper__break layout-centered">
                <section class="reset-password">
                    <h2>Reset your password</h2>
                    <div class="form-contain">
                        <form @submit.prevent="resetPassword">
                            <BaseInput
                                v-model="form.email.value"
                                :name="'email'"
                                :place-holder="'Email'"
                                :type="form.email.type"
                                :required="form.email.required"
                                :error-message="form.email.validation.message"
                                :validation-callback="form.email.validation.callback"
                                :validation-state="form.email.validation.validated"
                                :disabled="loading"
                            />
                            <div class="reset-password__message">
                                {{ message }}
                            </div>
                            <button-base
                                :type="'submit'"
                                :text="'RESET PASSWORD'"
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
    name: "ResetPassword",
    data() {
        return {
            form: {
                email: {
                    required: true,
                    value: '',
                    type: 'email',
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
                    }
                },
            },
            message: '',
            loading: false,
        }
    },

    methods: {
        async resetPassword() {
            this.message = '';
            this.loading = true;

            try {
                let res = await axios.post(`/api/password/send`, {
                    email: this.form.email.value
                })

                if (res.data.success) {
                    this.message = res.data.message;
                }

            } catch (error) {
                this.message = error.response.data.errors.email[0];
            }

            this.loading = false;
        }
    }
}
</script>

<style scoped lang="scss">
    @import '../../sass/_mixins.scss';

    .wrapper__reset-password {
        height: 100%;
        background: #fff;
    }

    .reset-password {
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
            max-width: 380px;
            z-index: 2;

            .input-container {
                margin-bottom: 0px;
            }
        }
    }
</style>
