<template>
    <div>
        <div class="create-account">
            <user-info class="create-account__info"
                :isRegisterUser="isRegisterUser"
                @resetIsRegister="isRegisterUser = false"
            />
            <user-payment
                @click="registerUser"
            />
            <overlay-component v-if="isLoading" />

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
        <section-testimonials />
    </div>
</template>

<script>
    import { mapGetters, mapMutations, mapActions } from "vuex";

    export default {
        data() {
            return {
                isRegisterUser: false
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
        computed: {
            ...mapGetters({
                isLoading: 'info/getIsBusy',
                checkout: 'info/getCheckout',
                userData: 'info/getUserData'
            })
        },
        methods: {
            ...mapMutations({
                setCheckout: 'info/setCheckout'
            }),
            ...mapActions({
                register: 'info/register'
            }),
            registerUser() {
                this.isRegisterUser = true;
            }
        }
    }
</script>

<style scoped lang="scss">
    .create-account {
        display: flex;
        flex-direction: row;
        max-width: 980px;

        @media screen and (max-width: 767px) {
            flex-direction: column;
        }

        &__info {
            margin-right: 120px;

            @media screen and (min-width: 768px) and (max-width: 991px) {
                margin-right: 60px;
            }

            @media screen and (max-width: 767px) {
                margin-right: 0px;
            }
        }
    }
</style>
