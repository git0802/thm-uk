<template>
    <section class="main-article"
        v-if="post"
    >
            <div class="main-article__container">
                <div class="main-article__left">
                    <div class="section-header__text">
                        <span>Featured article</span>
                    </div>
                    <span class="section__caption__title">
                        {{ post.title }}
                    </span>
                    <p class="section__caption__description">
                        {{ post.description }}
                    </p>
                    <router-link class="main-article__btn-wrapper" :to="{ name: 'Post', params: { slug : post.slug } }">
                        <button-base class="btn__w--240" :text="'READ MORE'"/>
                    </router-link>
                </div>
                <div class="main-article__right">
                    <img :src="post.image" alt="" class="main-article__img">
                </div>
            </div>
    </section>
</template>

<script>
    export default {
        name: "SectionOne",
        data: function() {
            return {
                post: null
            }
        },
        mounted() {
            this.fetchFeaturedPost();
        },
        methods: {
            async fetchFeaturedPost() {
                try {
                    let res = await this.$http.get('/api/post/featured')

                    this.post = res.data.featured_post;

                } catch (e) {

                }
            }
        }
    }
</script>

<style scoped lang="scss">
    .main-article {
        padding-top: 76px;
        margin-bottom: -76px;
        &__container {
            display: flex;
        }
        &__btn-wrapper {
            margin-top: 2.7em;
            width: max-content;
        }
        &__left {
            width: 580px;
            display: flex;
            flex-direction: column;
            margin-left: auto;
            justify-content: center;
            padding: 42px 0 42px 25px;
        }
        &__right {
            position: relative;
            width: calc(50% - 50px);
            background: var(--grey-3);
            margin-left: 50px;
            min-height: 535px;
        }
        &__img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    }
    .section__caption__description {
        margin-top: 1em;
    }
    .section-header__text {
        color: var(--purple);
        span:before {
            background-color: var(--purple);
        }
    }
    @media screen and (min-width:981px) and (max-width:1160px) {
        .main-article__right {
            width: 540px;
            margin-left: 40px;
        }
    }
    @media screen and (max-width:980px) {
        .main-article {
            padding-top: 59px;
            margin-bottom: -59px;
            &__container {
                flex-direction: column-reverse;
            }
            &__left {
                width: 100%;
                padding: 20px 25px 38px 25px;
            }
            &__right {
                width: 100%;
                margin-left: 0;
                min-height: 382px;
            }
        }
    }
    @media screen and (max-width: 480px) {
        .main-article__btn-wrapper {
            width: 100%;
        }
    }
</style>
