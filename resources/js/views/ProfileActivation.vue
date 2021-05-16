<template>
    <app-flexed-layout>
        <div class="wrapper wrapper__padded wrapper__break layout-centered">


            <div class="base-loader" v-if="loading">Loading...</div>

            <h2>
               {{ message }}
            </h2>
            <div class="confirm-bottom" v-if="success">
                <router-link class="button-wrap" :to="{name: 'Login'}">
                    <button-base :text="'Log In'"/>
                </router-link>
            </div>
        </div>
    </app-flexed-layout>
</template>

<script>
import Axios from 'axios'
import AppFlexedLayout from "../components/layouts/AppFlexedLayout";
export default {
    name: "ProfileVerification",
    components: {AppFlexedLayout},
    data: function() {
        return {
            loading: true,
            hash: this.$route.params.hash,
            message: null,
            success: false,
        }
    },
    mounted() {
        this.activateAccount()
    },
    methods: {
       async activateAccount() {
           try {
               let res = await axios.get(`/api/activate/${this.hash}`);

               this.message = res.data.message;
               this.success = res.data.success;

               if(!res.data.success) {
                    window.location = window.location.origin;
               }

               this.loading = false;

           } catch (e) {
               this.message = e.response.data.message;
               this.success = e.response.data.success
               this.loading = false;
           }
        }
    }
}
</script>

<style scoped lang="scss">
    h2 {
        text-align: center;
        font-weight: 800;
        font-size: 36px;
        line-height: 132.19%;
        color: var(--black);
    }
    .button-wrap {
        width: 240px;
        display: flex;
        @media screen and (max-width: 480px) {
            width: 100%;
        }
    }
    .confirm-bottom {
        margin-top: 40px;
        display: flex;
        width: 100%;
        justify-content: center;
    }
    button.button {
        text-transform: uppercase;
    }
</style>
