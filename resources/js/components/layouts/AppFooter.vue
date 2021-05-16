<template>
    <div class="footer">
        <div class="footer__top">
            <div class="footer__wrap">
                <div class="brand" @click="routerPush">
                    <the-hot-meal/>
                </div>
                <div class="footer__links">
                    <router-link class="footer__links-item" :to="{'name': 'Landing'}">
                        Home
                    </router-link>
                    <router-link class="footer__links-item" v-for="page in pages" :key="page.slug" :to="{ name: 'SlugPage', params: { slug: page.slug } }">
                        {{page.title}}
                    </router-link>
                </div>
                <div class="footer__social">
                    <template v-for="link in links">
                        <a :href="link.url" class="footer__links-social" target="_blank">
                            {{ link.name }}
                        </a>
                    </template>
                </div>
            </div>
        </div>
        <div class="footer__bottom">
            The Hot Meal. All rights reserved.
        </div>
    </div>
</template>

<script>
    import { mapGetters } from "vuex";

    export default {
        name: "LandFooter",
        data: function() {
            return {
                links: [],
            }
        },
        computed: {
            ...mapGetters({
                pages: 'adminUi/getPages',
            }),
        },
        mounted() {
          this.fetchSocial()
        },
        methods: {
            routerPush() {
                this.$router.push('/').catch(()=>{});
            },
            async fetchSocial() {
                try {
                    let res = await this.$http.get('/api/content/links')
                    if(res.data.success) {
                        this.links = res.data.links
                    }
                } catch(e) {

                }
            }
        },
    }
</script>

<style scoped lang="scss">
    .footer {
        display: flex;
        flex-direction: column;
        font-weight: 300;
        color: #4E4E4E;
        margin-top: auto;
        &__wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 25px;
        }
        .brand {
            cursor: pointer;
            width: 256px;
        }
        &__links {
            width: 660px;
            font-size: 0;
            text-align: center;
        }
        &__social {
            max-width: 256px;
        }
        &__links-social {
            font-size: 13px;
            color: #ff7082;
            font-weight: 800;
            transition: 250ms;
            padding: 0 10px;
            display: inline-block;
            vertical-align: top;
            &:hover {
                color: #23040e;
                opacity: 0.5;
            }
        }
        &__links-item {
            font-size: 13px;
            color: #4E4E4E;
            font-weight: 800;
            transition: 250ms;
            width: calc(100% / 7);
            padding: 0 10px;
            display: inline-block;
            vertical-align: top;
            &.social {
              color: #ff7082;
                &:hover {
                    color: #23040e;
                    opacity: 0.5;
                }
            }
            &:hover {
                color: var(--primary);
                opacity: 0.5;
            }
        }
        &__top {
            background: rgba(246, 227, 255, 0.3);
            padding: 30px 0 20px 0;
            position: relative;
        }
        &__bottom {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 26px;
            font-size: 12px;
        }
        @media screen and (max-width: 980px) {
            .footer {
                &__wrap {
                    flex-direction: column;
                }
                &__links {
                    margin: 20px 0;
                    max-width: 70%;
                }
                &__links-item {
                    width: 50%;
                    margin: 10px 0;
                }
            }
        }
        @media screen and (max-width: 600px) {
            .footer {
                &__links {
                    max-width: 90%;
                }
            }
        }
    }
</style>
