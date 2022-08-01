<template>
    <section class="hero-section">
        <div class="wrapper">
            <div class="hero-section__content">
                <div class="section__caption" v-if="site_content">
                    <div class="section__caption__title">
                        {{ site_content[0].title }}
                    </div>
                    <div class="section__caption__description">
                        {{ site_content[0].description }}
                    </div>
                    <div class="hero-section__content__button">
                        <router-link :to="{name: 'GroceryStore'}">
                            <button-base class="btn__w--240 signup-free-btn" :text="'CREATE A FREE MEAL PLAN'"/>
                        </router-link>
                    </div>


                    <a class="arrow-down" @click="anchor({to: '/', hash: '#howitworks'})">
                        <IconArrowDown></IconArrowDown>
                    </a>
                </div>
                <div class="hero-section__content__video">
                    <template v-if="lottieJson">
                        <lottie-player
                            autoplay
                            loop
                            mode="normal"
                            class="lottie"
                            :src="JSON.stringify(lottieJson)"
                        >
                        </lottie-player>
                    </template>

                    <div class="play-wrapper" @click="au">
                        <ButtonPlay></ButtonPlay>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import "@lottiefiles/lottie-player";
import {mapGetters} from "vuex";

export default {
    name: "SectionOne",
    data() {
        return {
            lottieJson: null,
        }
    },
    computed: {
        ...mapGetters({
            site_content: 'adminUi/getSiteContent'
        })
    },
    mounted() {
        this.fetchJson();
    },
    methods: {
        fetchJson() {
            axios.get('/images/girl.json').then(res => {
                this.lottieJson = res.data;
            })
        },
        au() {
            this.$emit('handlemodal');
        },
        anchor(path){
            window.location.hash = '';
            this.$router.push(path)
        }
    }
}
</script>

<style scoped lang="scss">
.lottie {
    width: 640px;
    height: 660px;
    position: absolute;
    left: -14px;
    top: -30px;
    @media screen and (max-width: 980px) {
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
        position: relative;
    }

}

.play-wrapper {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 98px;
    height: 98px;
    margin: 31px 0 0 -12px;
}


.section__caption {
    display: flex;
    flex-direction: column;
    justify-content: center;
    max-width: 410px;
    padding-right: 15px;
    padding-bottom: 36px;
    position: relative;

    .arrow-down {
        cursor: pointer;
        position: absolute;
        bottom: 0;
        left: 3px;
        padding: 12px;
        width: 62px;
        margin: 0 0 -12px -12px;

        &:hover svg {
            fill: var(--primary);
        }

        svg {
            width: 100%;
            transition: 250ms;
        }
    }

    @media screen and (max-width: 980px) {
        order: 2;
        max-width: 100%;
        align-items: center;
        margin-top: -26px;
        padding-right: 0;
        text-align: center;
    }
}

.hero-section {
    padding-top: 75px;
    padding-bottom: 50px;
    overflow: hidden;
    position: relative;
    @media screen and (max-width: 980px) {
        margin-top: unset;
        padding-top: 59px;
        padding-bottom: 0;
    }

    &__content {
        z-index: 2;
        display: flex;
        flex-direction: row;
        min-height: 568px;
        @media screen and (max-width: 980px) {
            margin-top: -20px;
            flex-direction: column;
        }

        &:before {
            content: "";
            display: block;
            position: absolute;
            background: url(/assets/images/fleck3.svg) no-repeat top center/1180px;
            width: 100%;
            height: 200%;
            transform: translate(-10%, -48%);
            left: 50%;
            top: 50%;
            max-width: 1180px;

            @media screen and (max-width: 980px) {
                display: none;
            }
        }

        &__video {
            position: relative;
            width: 570px;
            @media screen and (max-width: 980px) {
                order: 1;
                width: 100%;
            }
        }

        &__button {
            margin-top: 16px;
            @media screen and (max-width: 480px) {
                width: 100%;
            }
        }
    }
}

@media screen and (max-width: 980px) {
    a.arrow-down {
        display: none;
    }
}

</style>
