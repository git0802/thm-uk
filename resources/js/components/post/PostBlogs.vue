<template>
    <section class="post-blogs">
        <div class="wrapper">
            <div class="post-blogs__container">
                <div class="post-blogs__title">
                    <div class="section-header__text">
                        <span>similar topics</span>
                    </div>
                </div>

                <div class="blog-block" v-for="item in blogs">
                    <router-link class="blog-block__top" :to="{name: 'Post'}">
                        <img :src="item.img" alt="">
                    </router-link>
                    <div class="blog-block__bottom">
                        <router-link class="blog-block__title" :to="{name: 'Post'}">
                            {{ item.title }}
                        </router-link>
                        <p class="blog-block__text">{{ item.description }}</p>
                        <div class="blog-block__box">
                            <router-link :to="{name: 'Post'}">
                                <ButtonReadMore></ButtonReadMore>
                            </router-link>
                            <div class="blog-block__info">
                                <div class="info-group">
                                    <IconViews></IconViews>
                                    <span class="info-group__text">{{ item.viewers }}</span>
                                </div>
                                <div class="info-group">
                                    <IconComment></IconComment>
                                    <span class="info-group__text">{{ item.comments }}</span>
                                </div>
                                <span class="info-group__text">{{ item.time }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</template>

<script>
    export default {
        name: "PostBlogs",
        data() {
            return {
                blogs: []
            }
        }
    }
</script>

<style scoped lang="scss">
    .section-header__text {
        color: var(--purple);
        margin: 75px 0 64px 0;
        span:before {
            background-color: var(--purple);
        }
    }
    .post-blogs {
        padding-bottom: 37px;
        &__title {
            width: 100%;
            padding-left: 58px;
        }
        &__container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
    }
    .blog-block {
        display: flex;
        flex-direction: column;
        width: calc(50% - 10px);
        margin-bottom: 20px;
        &__bottom {
            display: flex;
            flex-direction: column;
            background: #fff;
            padding: 28px 31px 28px 37px;
        }
        &__top {
            height: 305px;
            background: var(--grey-3);
            img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
        }
        &__title {
            font-family: Gilroy;
            font-weight: 800;
            font-size: 24px;
            line-height: 132.19%;
            color: var(--purple);
        }
        &__text {
            font-weight: 800;
            font-size: 14px;
            line-height: 132.19%;
            margin-top: 0.5em;
            margin-bottom: 2em;
            height: 36px;
            overflow: hidden;
            -webkit-line-clamp: 2;
            display: -webkit-box;
            -webkit-box-orient: vertical;
        }
        &__box {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        &__info {
            display: flex;
        }
    }
    .info-group {
        display: flex;
        align-items: center;
        margin-right: 17px;
        svg {
            width: 16px;
            height: 10px;
            fill: #D8BEFF;
            margin-right: 5px;
        }
    }
    .info-group__text {
        font-size: 13px;
        line-height: 100%;
        font-weight: 400;
        color: #949699;
        cursor: default;
    }
    @media screen and (max-width:767px) {
        .blog-block {
            width: 100%;
            margin-bottom: 10px;
            &__top{
                height: 196px;
            }
            &__text {
                height: 54px;
                -webkit-line-clamp: 3;
            }
            &__bottom {
                padding: 22px 30px 28px 30px;
            }
        }
        .post-blogs__title {
            padding-left: 25px;
        }
    }
    @media screen and (max-width:480px) {
        .wrapper {
            padding: 0;
        }
    }
</style>
