<template>
    <div>
        <div class="create-account">
            <user-registration-info class="create-account__info"
                @resetIsRegister="isRegisterUser = false"
            />
        </div>
        <overlay-component v-if="isLoading" />
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
        computed: {
            ...mapGetters({
                isLoading: 'info/getIsBusy',
                userData: 'info/getUserData'
            })
        },
        methods: {
            ...mapActions({
                register: 'info/register'
            }),
        }
    }
</script>

<style scoped lang="scss">
    .create-account {
        display: flex;
        justify-content: center;

        @media screen and (max-width: 767px) {
            flex-direction: column;
        }

        &__info {
            @media screen and (min-width: 768px) and (max-width: 991px) {
                margin-right: 60px;
            }

            @media screen and (max-width: 767px) {
                margin-right: 0px;
            }
        }
    }
</style>
