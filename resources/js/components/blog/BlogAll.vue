<template>
    <section class="blog-all">
        <div class="wrapper">
            <div class="blog-all__container">

                <div class="blog-block" v-for="item in blogs">
                    <router-link class="blog-block__top" :to="{ name: 'Post', params: { slug : item.slug } }">
                        <img :src="item.image" alt="Blog image">
                    </router-link>
                    <div class="blog-block__bottom">
                        <router-link class="blog-block__title" :to="{ name: 'Post', params: { slug : item.slug } }">
                            {{ item.title }}
                        </router-link>
                        <p class="blog-block__text">{{ item.description }}</p>
                        <div class="blog-block__box">
                            <router-link :to="{ name: 'Post', params: { slug : item.slug } }">
                                <button-read-more/>
                            </router-link>
                            <div class="blog-block__info">
                                <div class="info-group">
                                    <icon-views/>
                                    <span class="info-group__text">{{ item.views }}</span>
                                </div>
                                <!--Commented this because we have not logic with comments-->
                                <!--<div class="info-group">
                                    <icon-comment/>
                                    <span class="info-group__text">{{ item.comments }}</span>
                                </div>-->
                                <span class="info-group__text">{{ item.created_at }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="button-wrap">
                    <button-base
                        :disabled="offset + countDown >= all_blogs_length"
                        @click="loadMore()"
                        class="btn__w--240"
                        :text="'LOAD MORE'" />
                    <overlay-component class="blog-preloader" v-if="isLoading"/>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import axios from "axios";
    import moment from 'moment';

    export default {
        name: "BlogAll",
        data() {
            return {
                isLoading: false,
                blogs: [],
                url: '/api/post/all' ,
                default: 0,
                countDown: 10,
                all_blogs_length: null
            }
        },
        watch: {
            '$route.params': function (params) {
                const isEmpty = JSON.stringify(params) === "{}";

                this.url = isEmpty ? '/api/post/all' : `/api/post/all/${params.taxonomy.split('+').join(' ')}`;
                this.init(this.url);
                this.offset = 0;
            },
            default() {
                this.init(this.url)
            },
        },
        computed: {
            offset: {
                get () {
                    return this.default
                },
                set (newValue) {
                    this.default = newValue
                }
            }
        },
        created() {
            this.init(this.url);
        },
        methods: {
            loadMore() {
                this.offset += this.countDown;
            },
            fetchGetBlogsOffset(url){
                this.isLoading = true;
                axios.get(url + "?offset=" + this.offset)
                    .then((response) => {
                        if (response.data.success) {
                            this.blogs = [ ...response.data.post ];
                            this.all_blogs_length = response.data.total;
                        }
                    });
                setTimeout(()=> {
                    this.isLoading = false
                },200)
            },
            init(url) {
                this.fetchGetBlogsOffset(url);
            },
        }
    }
</script>

<style scoped lang="scss">
    .blog-all {
        padding: 42px 0 100px 0;
        &__container {
            position: relative;
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
            font-size: 24px;
            line-height: 132.19%;
            color: var(--purple);
            overflow: hidden;
            -webkit-line-clamp: 2;
            display: -webkit-box;
            -webkit-box-orient: vertical;
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
        color: #949699;
        font-weight: 400;
        cursor: default;
    }
    .button-wrap {
        display: flex;
        width: 100%;
        justify-content: center;
        margin-top: 40px;
        position: relative;
    }

    @media screen and (max-width:767px) {
        .blog-all {
            padding: 42px 0 37px 0;
        }
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
                padding: 22px 26px 28px 26px;
            }
        }
        .button-wrap {
            margin-top: 24px;
        }
    }

    @media screen and (max-width:480px) {
        .wrapper {
            padding: 0;
        }
        .button-wrap {
            padding: 0 25px;
        }
    }
</style>
