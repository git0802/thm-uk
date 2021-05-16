<template>
    <section class="support-section wrapper">
        <a class="anchor" id="support"></a>
        <div class="support-section__content">
            <div class="section__caption">
                <div class="section__caption__title">
                    Got questions?
                </div>
                <div class="section__caption__description">
                    We've got 24/7 world-class support ready to help.
                </div>
            </div>
            <div class="support-section__cards">
                <div class="support-section__cards__item">
                    <div class="icon">
                        <div class="gradient"></div>
                        <support-card-one/>
                    </div>
                    <router-link :to="'/contact'" class="support-section__cards__button">
                        <button-base :text="'START CHAT'"/>
                    </router-link>

                </div>
                <div class="support-section__cards__item">
                    <div class="icon">
                        <div class="gradient"></div>
                        <support-card-two/>
                    </div>
                    <div class="support-section__cards__button" @click="au">
                        <button-base :text="'WATCH DEMO'" />
                    </div>

                </div>
                <div class="support-section__cards__item">
                    <div class="icon">
                        <div class="gradient"></div>
                        <support-card-three/>
                    </div>
                    <router-link :to="'/contact'" class="support-section__cards__button">
                        <button-base :text="'GET IN TOUCH'"/>
                    </router-link>

                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: "SectionSixth",
    methods: {
        au() {
            this.$emit('handlemodal');
        }
    }
}
</script>

<style scoped lang="scss">
.support-section {
    margin: auto;
    margin-bottom: 68px;
    &__content {
        display: flex;
        flex-direction: row;
        @media screen and (max-width: 980px) {
            flex-direction: column;
            align-items: center;
        }
        .section__caption {
            width: 300px;
            min-width: 300px;
            padding-top: 34px;
            padding-right: 15px;
            @media screen and (max-width: 980px) {
                margin-right: unset;
                padding: 0;
            }

            @media screen and (max-width: 480px) {
                min-width: auto;
                width: 100%;
            }
        }
    }
    &__cards {
        width: 100%;
        display: flex;
        justify-content: space-between;
        .button {
            width: 100% !important;
            height: 36px;
            padding: 0;
            font-size: 16px;
        }
        &__item {
            background-color: white;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            max-width: 212px;
            width: 100%;
            height: 214px;
            padding: 22px 20px;
            &:nth-child(1) {
                .icon {
                    margin-bottom: -31px;
                    width: 190px;
                    margin-left: -8px;
                }
            }
            &:nth-child(2) {
                margin: 0 10px;
                .icon {
                    margin-bottom: -25px;
                    width: 190px;
                    margin-left: -12px;
                }
            }
            &:nth-child(3) {
                .icon {
                    margin-bottom: -15px;
                    width: 196px;
                    margin-left: -13px;
                }
            }
            @media screen and (max-width: 980px) {
                margin-top: 55px;
                &:nth-child(2) {
                    margin: unset;
                    margin-top: 55px;
                }
            }
            .icon {
                margin-top: -75px;
                display: flex;
                justify-content: center;
                position: relative;
                .gradient {
                    position: absolute;
                    top: 0;
                    left: 0;
                    bottom: 0;
                    right: 0;
                    background: linear-gradient(180deg, rgba(255, 255, 255, 0) 75%, #ffffff 100%);
                }
            }

        }
    }
}
.section__caption__title {
    font-size: 36px;
}
.section__caption__description {
    color: rgb(41 45 52 / 0.8);
    margin-top: 0;
}
@media screen and (max-width: 980px) {
    .support-section {
        padding-bottom: 25px;
        margin-bottom: 0;
        padding-top: 72px;
        &__cards {
            flex-direction: column;
            align-items: center;
        }
    }
}

@media screen and (max-width: 480px) {
    .support-section {
        background: #fff;
        &__cards__item {
            width: 100%;
            align-items: center;
            padding: 0;
        }
        &__cards__button {
            width: 100%;
        }
        .button[data-v-5bddfc54] {
            height: 42px;
        }
    }
}
</style>
