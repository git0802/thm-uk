<template>
    <app-layout>
        <div class="wrapper wrapper__padded wrapper__break" v-if="page">
            <h1 class="section__caption__title">
                {{ page.title }}
            </h1>
            <div v-html="page.body" class="slug-page"></div>
            <template v-if="route_path === '/contact'">
                <div class="slug-page__form" style="margin-top: 2em">
                    <support-form/>
                </div>
            </template>
        </div>
    </app-layout>
</template>

<script>
export default {
    name: "Advertising",
    data() {
        return {
            page: null
        }
    },
    computed: {
        route_path() {
            return this.$route.path
        }
    },
    watch: {
        route_path() {
            this.fetchPage();
        }
    },
    mounted() {
        this.fetchPage();
    },
    methods: {
        async fetchPage() {
            try {
                let res = await this.$http.get(`/api/page` + this.route_path)

                this.page = res.data.post
            } catch (e) {
               window.location = '/'
            }
        },
    }
}
</script>

<style scoped lang="scss">
    .section__caption__title {
        margin: 20px 0;
    }
    h2 {
        margin: 30px 0 14px 0;
        font-size: 20px;
    }
    .wrapper {
        width: 100%;
        min-height: calc(100vh - 126px);
    }
    .slug-page__form {
        max-width: 480px;
        width: 100%;
        margin-top: 2em;
    }
</style>
