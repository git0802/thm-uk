<template>
    <div class="guest-banner">
        <span v-if="user">ALERT: This is a DUMMY version of the site based on a {{ user.height_in_feet }} male who’s {{ user.weight }} pounds. To customize for YOUR own weight goals, please
        <router-link :to="{name: 'SignUp'}" @click.native="close()" exact>sign up FREE</router-link>.</span>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "GuestBanner",
        computed: {
            ...mapGetters({
                user: 'auth/user',
            }),
        },
    }
</script>

<style scoped lang="scss">
    @import '../../../sass/mixins';

    .guest-banner {
      background-color: black;
      width: 100%;
      color: white;
      text-align: center;
      padding: 8px 10px;
      font-size: 14px;
      z-index: 11;
      font-weight: normal;
      flex: 0 1 100%;
      a {
        color: #B47EFB;
      }
    }

    @media screen and (min-width: 1021px) {
      .guest-banner  {
        display: none;
      }
    }

</style>
