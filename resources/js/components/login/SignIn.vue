<template>
    <div class="wrapper__login-page">
        <div class="wrapper wrapper__padded wrapper__break layout-centered">
            <section class="signIn-container">
                <loader-component :loading="loading"/>

                <h2>Sign in to your account</h2>

                <div class="form-contain">
                    <form @submit.prevent="handleLogin">
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

                        <BaseInput
                            v-model="form.password.value"
                            :name="'password'"
                            :place-holder="'Password'"
                            :type="form.password.type"
                            :required="form.password.required"
                            :error-message="form.password.validation.message"
                            :validation-callback="form.password.validation.callback"
                            :validation-state="form.password.validation.validated"
                            :disabled="loading"
                            :min="form.password.validation.rules.min"
                            :max="form.password.validation.rules.max"
                        />

                        <button-base
                            :type="'submit'"
                            :text="'SIGN IN'"
                            :disabled="loading"
                            :styles="{'max-width': 'unset'}"
                        />
                        <div class="reset-password">
                            <router-link class="reset-password__link" :to="{ name: 'ResetPassword'}">Forgot your password?</router-link>
                        </div>
                        <div class="response-message" v-html="message"/>
                    </form>
                </div>
            </section>
        </div>
    </div>
</template>

<script>

export default {
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
                password: {
                    required: true,
                    value: '',
                    type: 'password',
                    validation: {
                        rules: {
                            min: 6,
                            max: 128,
                        },
                        message: '',
                        validated: 0,
                        callback: (event) => {
                            if (this.form.password.validation.rules.min > this.form.password.value.length || this.form.password.validation.rules.max < this.form.password.value.length) {
                                this.form.password.validation.validated = 2;
                                this.form.password.validation.message = `*Min ${this.form.password.validation.rules.min} -  max ${this.form.password.validation.rules.max} characters`

                            } else {
                                this.form.password.validation.validated = 1;
                            }
                        }
                    }
                }
            },
            message: '',
            loading: false,
        }
    },
    beforeCreate() {
        if(this.$store.state.auth.authenticated && !this.$store.state.auth.guest) {
            window.location = '/'
        }
    },
    methods: {
        async handleLogin(event) {
            event.preventDefault();

            this.message = '';
            this.loading = true;
            try {
                let res = await this.$store.dispatch('auth/authenticate',
                    {
                        data: {
                            email: this.form.email.value,
                            password: this.form.password.value},
                        http: this.$http});

                this.loading = false;

                if(!res.data.success) {
                    this.message += res.data.message + '<br/>'
                } else {
                    let wait = await this.$store.dispatch('auth/signIn', {
                        http: this.$http,
                        token: res.data.token,
                        user: res.data.user
                    });


                    if(wait) {
                        if(res.data.user.finished_setup) {
                            window.location = '/meal-planner/planner/';
                        } else {
                            window.location = '/store';
                        }
                    }
                }
            } catch (e) {

                if(e.response.data.errors) {
                    Object.keys(e.response.data.errors).forEach(i => {
                        e.response.data.errors[i].forEach(z => {
                            this.form[i].validation.message = z;
                            this.form[i].validation.validated = 2;
                        })
                    });



                } else {
                    this.message += e.response.data.message + '<br/>'
                }
            }
                this.loading = false;
        },
    }
}
</script>

<style scoped lang="scss">
    @import '../../../sass/_mixins.scss';

    .wrapper__login-page {
        height: 100%;
        background: #fff;
    }

    .response-message {
        margin-top: 10px;
        font-weight: 500;
        min-height: 20px;
    }

    .form-contain {
        width: 100%;
        max-width: 380px;
        z-index: 2;
    }

    .signIn-container {
        min-height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 54px 0px 0px 0px;
        position: relative;


        @media screen and (max-width: 600px) {
            align-items: flex-start;
        }

        h2 {
            @include title-font;

            margin-top: 25px;
            z-index: 2;
        }

        &__content {
            display: flex;
            flex-direction: column;
            align-items: center;

            .input-container, button {
                max-width: 380px;
                width: 100%
            }

            .input-container__data {
                background: var(--grey-light);
            }
        }

        .reset-password {
            text-align: right;
            margin-top: 8px;

            &__link {
                color: #0000008c;

                &:hover {
                    color: var(--primary);
                    opacity: .5;
                }
            }
        }
    }

    .m-b-29, h2 {
        margin-bottom: 29px;

        @media screen and (max-width: 600px) {
            margin-bottom: 25px;
        }
    }
</style>


