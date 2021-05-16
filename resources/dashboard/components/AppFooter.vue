<template>
    <div class="container__footer" :class="{'container__footer--mobile': is_mobile}">
        <div @click="routerPush">
          <the-hot-meal/>
        </div>
        <div class="container__footer__caption">
            The Hot Meal. All rights reserved.
        </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';

    export default {
        name: "AppFooter",
        methods: {
          routerPush() {
            this.$router.push('/meal-planner/planner').catch(()=>{});
          },
        },
        computed: {
            ...mapGetters({
               is_mobile: 'plannerUi/getMobile'
            }),
        }
    }
</script>

<style scoped>

</style>
