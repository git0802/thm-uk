<template>
    <app-layout>
        <main class="check-email">
            <h2>{{ message }}</h2>
        </main>
    </app-layout>
</template>

<script>
    import { mapGetters } from "vuex";

    export default {
        computed: {
            ...mapGetters({
                message: 'info/getSuccessMessage',
            }),
        },
    }
</script>

<style scoped lang="scss">
    @import '../../sass/_mixins.scss';

    .check-email {
        @include title-font;

        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 100px);
        text-align: center;
        padding: 76px 20px 0px 20px;
        @media screen and (max-width: 980px) {
            padding: 59px 20px 0px 20px;
        }
    }
</style>
