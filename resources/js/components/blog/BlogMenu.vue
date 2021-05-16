<template>
    <div class="blog-menu">
        <div class="wrapper">
            <ul class="blog-menu__box">
                <router-link class="blog-menu__links" :style="{ width: calcWidth }"
                    :to="{ name: 'Blogs', params: { taxonomy : 'recent' } }"
                >
                    Most recent blogs
                </router-link>
                <router-link class="blog-menu__links" :style="{ width: calcWidth }"
                    :to="{ name: 'Blogs', params: { taxonomy : 'popular' } }"
                >
                    Popular
                </router-link>
                <router-link v-for="taxonomy in taxonomies" :key="taxonomy" class="blog-menu__links" :style="{ width: calcWidth }"
                    :to="{ name: 'Blogs', params: { taxonomy : taxonomy.split(' ').join('+') } }"
                >
                    {{ taxonomy }}
                </router-link>
            </ul>
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "BlogMenu",
        data() {
            return {
                taxonomies: [],
                calcWidth: '20%'
            }
        },
        created() {
            this.init();
        },
        methods: {
            init() {
                axios.get('/api/post/taxonomies')
                    .then((response) => {
                        this.taxonomies = [ ...response.data.taxonomies];

                        //We need calculation width if we change quantity taxonomies from backend
                        if (this.taxonomies.length) {
                            this.calcWidth = `calc(100% / ${this.taxonomies.length + 2})`;
                        }
                    })
            },
        }
    }
</script>

<style scoped lang="scss">
    .blog-menu {
        background: #fff;
        height: 80px;
        overflow-x: auto;
        overflow-y: hidden;
        margin-top: 76px;
        @media screen and (max-width:980px) {
            margin-top: 59px;
        }
        > div {
            height: 100%;
        }
        &__box {
            display: flex;
            height: 100%;
        }
        &__links {
            width: calc(100% / 5);
            display: flex;
            color: #000;
            align-items: center;
            justify-content: center;
            transition: .15s linear;
            &:hover {
                background: var(--purple);
                color: var(--white);
            }
        }
    }
    .blog-menu::-webkit-scrollbar { width: 0; height: 0; }
    .blog-menu { -ms-overflow-style: none; }
    .blog-menu { overflow: -moz-scrollbars-none; }
    .blog-menu {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    @media screen and (max-width:950px) {
        .wrapper {
            padding: 0;
        }
        .blog-menu {
            background: #fff;
            height: 80px;
            overflow-x: auto;
            overflow-y: hidden;
            &__box {
                width: 900px;
                margin: 0 auto;
            }
        }
    }
</style>
