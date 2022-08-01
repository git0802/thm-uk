<template>
    <section class="how-it-works">
        <div class="wrapper">
            <a class="anchor" id="howitworks"></a>
            <div class="how-it-works__cont">
                <div class="how-it-works__left">
                    <img src="/assets/images/how-it-works-1.jpg" alt="" class="how-it-works__img--big">
                    <img src="/assets/images/how-it-works-2.png" alt="" class="how-it-works__img--small">
                    <img src="/assets/images/how-it-works-desing.svg" alt="" class="how-it-works__svg">
                </div>

                <div class="how-it-works__right" v-if="site_content">
                    <div class="section-header__text">
                        <span>
                            {{ site_content[1].block_name }}
                        </span>
                    </div>
                    <div class="section__caption__title">
                        <span>
                            {{ site_content[1].title }}
                        </span>
                    </div>
                    <div class="section__caption__description">
                        {{ site_content[1].description }}
                    </div>
                    <router-link :to="{name: 'GroceryStore'}">
                        <button-base class="btn__w--240 signup-free-btn" :text="'CREATE A FREE MEAL PLAN'"/>
                    </router-link>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: "SectionSeven",
    computed: {
        ...mapGetters({
            site_content: 'adminUi/getSiteContent'
        })
    }
}
</script>

<style scoped lang="scss">
.wrapper {
    max-width: 1110px;
}

.section-header__text {
    margin-bottom: 24px;
}

.section__caption__description {
    font-size: 18px;
    margin-top: 1.1em;
    max-width: 400px;
    white-space: pre-line;
}

.how-it-works {
    padding-top: 81px;
    padding-bottom: 116px;

    &__right {
        display: flex;
        flex-direction: column;
        width: 574px;
        padding-left: 112px;
    }

    &__left {
        display: flex;
        width: 486px;
        position: relative;
    }

    &__img--big {
        width: 400px;
        height: 309px;
        object-fit: contain;
        margin-top: 28px;
        margin-left: 13px;
        z-index: 2;
    }

    &__img--small {
        position: absolute;
        right: 0;
        top: 0;
        width: 200px;
        height: 288px;
        object-fit: contain;
        margin: 122px -32px 0 0;
        z-index: 2;
    }

    &__svg {
        position: absolute;
        right: 0;
        top: 0;
        margin: -100px -38px 0 0;
    }
}

button.button {
    margin-top: 22px;
}

.how-it-works__cont {
    display: flex;
}

@media screen and (max-width: 980px) {
    .how-it-works {
        padding-top: 40px;
        padding-bottom: 45px;

        &__right {
            margin-top: 77px;
            width: 100%;
            max-width: 600px;
            padding-left: 0;
        }

        &__cont {
            flex-direction: column;
            align-items: center;
        }
    }
}

@media screen and (max-width: 767px) {
    .how-it-works {
        &__img--small {
            margin: 18% -22px 0 0;
            width: 160px;
        }

        &__img--big {
            margin-left: 0;
            margin-top: 0;
            max-width: 400px;
            width: 100%;
        }

        &__left {
            width: 100%;
            max-width: 486px;
        }

        &__svg {
            right: 50%;
            margin: -23px 14% 0 0;
            max-width: 540px;
            transform: translateX(50%);
        }
    }
}
</style>
