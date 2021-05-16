<template>
    <router-view />
</template>

<script>
    import {mapGetters, mapMutations} from "vuex";

export default {
    created() {
        this.$store.dispatch('info/getPrice');
        this.$store.dispatch('loader/loading', false);
        this.$store.dispatch('auth/check');
        this.$store.dispatch('adminUi/fetchGetContent');
        this.$store.dispatch('adminUi/fetchPages', this.$http);
    },
    mounted() {
        this.onResize();
        window.addEventListener('resize', this.onResize, {passive: true})
    },
    computed: {
        ...mapGetters({
            is_mobile: "plannerUi/getMobile"
        }),
    },
    methods: {
        ...mapMutations({
            handleResize: 'plannerUi/handleResize',
        }),
        onResize() {
            this.handleResize();
        }
    }
}
</script>
