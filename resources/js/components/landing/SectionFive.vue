<template>
    <section class="works-section">
        <a class="anchor" id="whyitworks"></a>
        <div class="works-section__content wrapper">
            <div class="section__caption" v-if="site_content">
                <div class="section-header__text">
                    <span>
                        {{ site_content[2].block_name }}
                    </span>
                </div>
                <div class="section__caption__title">
                    {{ site_content[2].title }}
                </div>
                <div class="section__caption__description">
                    {{ site_content[2].description }}
                </div>
                <div class="works-section__content__button">
                    <router-link :to="{name: 'SignUp'}">
                        <button-base class="btn__w--240" :text="'GET STARTED FREE'"/>
                    </router-link>
                </div>
            </div>
            <div class="works-section__right">
                <img src="/assets/images/works-section__img.png" alt="" class="works-section__img">
                <img src="/assets/images/works-section-desing.svg" alt="" class="works-section__img-desing">
            </div>
        </div>
    </section>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: "SectionFive",
    computed: {
        ...mapGetters({
            site_content: 'adminUi/getSiteContent'
        })
    }
}
</script>

<style scoped lang="scss">
    .wrapper {
        max-width: 1038px;
    }

    .works-section {
        padding-top: 134px;
        padding-bottom: 104px;
        position: relative;
        overflow: hidden;

        .section-header__text {
            margin-bottom: 32px;
            padding-left: 53px;
        }

        .section__caption__title {
            line-height: 109%;
        }

        .section__caption__description {
            margin-top: 1.1em;
        }
        @media screen and (max-width: 980px) {
            padding-top: 70px;
            margin-bottom: 0;
            overflow: hidden;
            padding-bottom: 0;
        }

        &__content {
            display: flex;
            position: relative;

            @media screen and (max-width: 980px) {
                flex-direction: column-reverse;
                align-items: center;
                .section__caption {
                    width: 100% !important;
                    max-width: 600px;
                    z-index: 2;
                }
            }

            .section__caption {
                width: 477px;
                @media screen and (min-width: 981px) {
                    min-height: 360px;
                }
            }

            &__button {
                margin-top: 22px;
            }
        }

        &__right {
            position: relative;
            width: 505px;
        }

        &__img {
            position: absolute;
            top: -34px;
            left: 36px;
            width: 109%;
            z-index: 2;
        }

        &__img-desing {
            position: absolute;
            top: -111px;
            left: 169px;
        }
    }
    @media screen and (max-width: 1100px) {
        .works-section {
            &__img {
                width: 100%;
            }
        }
    }

    @media screen and (max-width: 980px) {
        .works-section {
            &__img {
                position: relative;
                max-width: 520px;
                left: 0;
            }
            &__right {
                width: 100%;
                display: flex;
                justify-content: center;
            }
            &__img-desing {
                left: 34%;
            }
        }
    }

    @media screen and (max-width: 767px) {
        .works-section__img-desing {
            top: -96px;
            width: 134%;
            max-width: 650px;
        }
    }
</style>
