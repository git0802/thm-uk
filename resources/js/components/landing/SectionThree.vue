<template>
    <section class="benefits-section">
        <div class="wrapper benefits-section__wrapper">
            <a class="anchor" id="benefits" style="top: -100px;"></a>
            <div class="section__caption">
                <div class="section-header__text">
                        <span>
                            BENEFITS
                        </span>
                </div>
            </div>
            <div class="benefits-section__content">
                <div class="benefits-block benefits-block--left">
                    <img src="/assets/images/benefits-img-1.png" alt="" class="benefits-block__img">
                    <span class="benefits-block__text">Plan meals using any store</span>
                </div>

                <div class="benefits-block benefits-block--middle">
                    <img src="/assets/images/benefits-img-2.svg" alt="" class="benefits-block__img">
                    <span class="benefits-block__text">Plan for any diet (meat, vegetarian: you choose)</span>
                </div>

                <div class="benefits-block benefits-block--right">
                    <img src="/assets/images/benefits-img-3.svg" alt="" class="benefits-block__img">
                    <span class="benefits-block__text">Get a Weekly Calorie Goal calculated</span>
                </div>

                <div class="benefits-block benefits-block--left">
                    <img src="/assets/images/benefits-img-4.svg" alt="" class="benefits-block__img">
                    <span class="benefits-block__text">Set a grocery/meal budget to save {{ phrase.currencySm }}</span>
                </div>

                <div class="benefits-block benefits-block--middle">
                    <img src="/assets/images/benefits-img-5.svg" alt="" class="benefits-block__img">
                    <span class="benefits-block__text">Reuse your meal plan to avoid leftovers</span>
                </div>

                <div class="benefits-block benefits-block--right">
                    <img src="/assets/images/benefits-img-6.svg" alt="" class="benefits-block__img">
                    <span class="benefits-block__text">Get weekly notifications to view next week</span>
                </div>

                <transition name="benefits">
                    <div class="benefits-block__show"
                         v-show="benefits"
                    >
                        <div class="benefits-block benefits-block--left">
                            <img src="/assets/images/benefits-img-7.svg" alt="" class="benefits-block__img">
                            <span class="benefits-block__text">Get analytics to keep track of meals/weight</span>
                        </div>

                        <div class="benefits-block benefits-block--middle">
                            <img src="/assets/images/benefits-img-8.svg" alt="" class="benefits-block__img">
                            <span class="benefits-block__text">"Add Extra Exercise" or "Add Extra Food" calories on any day</span>
                        </div>

                        <div class="benefits-block benefits-block--right">
                            <img src="/assets/images/benefits-img-9.svg" alt="" class="benefits-block__img">
                            <span
                                class="benefits-block__text">Get access to a concierge via priority email support</span>
                        </div>
                    </div>
                </transition>
            </div>

            <div class="benefits__btn-wrapper"
                 v-on:click="benefits = !benefits"
            >
                <button-base :text="'VIEW ALL BENEFITS'"/>
            </div>


            <hooper class="benefits__slider" :infiniteScroll="true" :itemsToShow="itemsToShow">
                <slide class="benefits__slider-block">
                    <img src="/assets/images/benefits-img-1.png" alt="" class="benefits__slider-img">
                    <span class="benefits__slider-text">Plan meals using any store</span>
                </slide>
                <slide class="benefits__slider-block">
                    <img src="/assets/images/benefits-img-2.svg" alt="" class="benefits__slider-img">
                    <span class="benefits__slider-text">Plan for any diet (meat, vegetarian: you choose)</span>
                </slide>
                <slide class="benefits__slider-block">
                    <img src="/assets/images/benefits-img-3.svg" alt="" class="benefits__slider-img">
                    <span class="benefits__slider-text">Get a Weekly Calorie Goal calculated</span>
                </slide>
                <slide class="benefits__slider-block">
                    <img src="/assets/images/benefits-img-4.svg" alt="" class="benefits__slider-img">
                    <span class="benefits__slider-text">Set a grocery/meal budget to save {{ phrase.currencySm }}</span>
                </slide >
                <slide class="benefits__slider-block">
                    <img src="/assets/images/benefits-img-5.svg" alt="" class="benefits__slider-img">
                    <span class="benefits__slider-text">Reuse your meal plan to avoid leftovers</span>
                </slide>
                <slide class="benefits__slider-block">
                    <img src="/assets/images/benefits-img-6.svg" alt="" class="benefits__slider-img">
                    <span class="benefits__slider-text">Get weekly notifications to view next week</span>
                </slide>
                <slide class="benefits__slider-block">
                    <img src="/assets/images/benefits-img-7.svg" alt="" class="benefits__slider-img">
                    <span class="benefits__slider-text">Get analytics to keep track of meals/weight</span>
                </slide>
                <slide class="benefits__slider-block">
                    <img src="/assets/images/benefits-img-8.svg" alt="" class="benefits__slider-img">
                    <span class="benefits__slider-text">"Add Extra Exercise" or "Add Extra Food" calories on any day</span>
                </slide>
                <slide class="benefits__slider-block">
                    <img src="/assets/images/benefits-img-9.svg" alt="" class="benefits__slider-img">
                    <span class="benefits__slider-text">Get access to a concierge via priority email support</span>
                </slide>

                <hooper-navigation slot="hooper-addons"></hooper-navigation>
                <hooper-pagination slot="hooper-addons"></hooper-pagination>
            </hooper>

        </div>
    </section>
</template>

<script>
    import { Hooper, Slide,
        Pagination as HooperPagination,
        Navigation as HooperNavigation
    } from 'hooper';
    import 'hooper/dist/hooper.css';

    export default {
        name: "SectionThree",
        data: function () {
            return {
                benefits: false,
                //wistiaVideoId: 'iwpbme5opt',
            }
        },
        components: {
            Hooper,
            Slide,
            HooperPagination,
            HooperNavigation
        },
        computed: {
            itemsToShow() {
                return document.body.offsetWidth > 980 ? 2 : 1;
            }
        }
    }

    /*export default {
        mounted() {
            let base = document.createElement('script');
            base.setAttribute('src',`//fast.wistia.com/embed/medias/${this.wistiaVideoId}.jsonp`);
            base.setAttribute('async','async');
            document.head.appendChild(base);

            let videoScript = document.createElement('script');
            videoScript.setAttribute('src',"//fast.wistia.com/assets/external/E-v1.js");
            videoScript.setAttribute('async','async');
            document.head.appendChild(videoScript);
        }
    }*/
</script>

<style scoped lang="scss">
    .benefits-section {
        position: relative;
        overflow: hidden;
        padding-bottom: 24px;
        padding-top: 50px;

        &__wrapper {
            position: relative;
        }
        &__content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .section__caption {
            padding-left: 7px;
        }
        .section-header__text {
            margin-bottom: 29px;
        }
    }
    .benefits-block {
        display: flex;
        flex-direction: column;

        align-items: center;
        padding: 0 0px;
        text-align: center;
        margin-bottom: 60px;
        &--middle {
            width: 33%;
            padding: 0 0 0 27px;
        }
        &--left {
            width: 27%;
        }
        &--right {
            width: 29%;
        }
        &__img {
            height: 76px;
            width: max-content;
            object-fit: contain;
        }
        &__text {
            font-size: 24px;
            color: #292D34;
            margin-top: 18px;
        }
    }
    .benefits-block__show {
        display: flex;
        justify-content: space-between;
        margin-top: -31px;
        .benefits-block__text {
            margin-top: 29px;
        }
    }
    .benefits__btn-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 16px;
        button.button {
            width: 280px!important;
            max-width: 280px!important;
        }
    }

    .benefits-enter-active, .benefits-leave-active {
        transition: .25s linear;
    }
    .benefits-enter, .benefits-leave-to {
        opacity: 0;
    }

    @media screen and (min-width: 981px) {
        .benefits__slider {
            display: none;
        }
    }

    @media screen and (max-width: 980px) {
        .benefits-section__content, .benefits__btn-wrapper {
            display: none;
        }
        .benefits-section {
            .section__caption{
                padding-left: 0;
                margin-left: -33px;
            }
        }
        .wrapper {
            padding: 0 60px;
        }
        section.benefits__slider {
            height: 256px!important;
        }
        .benefits__slider-block {
            display: flex;
            flex-direction: column;
            padding: 0 14px !important;
            filter: grayscale(0);
        }
        span.benefits__slider-text {
            font-size: 24px;
            color: #292D34;
            max-width: 400px;
            text-align: center;
            line-height: 130%;
            margin-top: 26px;
        }
    }

    @media screen and (max-width: 480px) {
        span.benefits__slider-text {
            font-size: 20px;
        }
    }
</style>
