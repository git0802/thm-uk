<template>
    <section class="how-it-works-section">
        <div class="wrapper how-it-works-section__wrapper">
            <img class="star1" src="assets/images/star1.svg" alt="star" />
            <img class="star2" src="assets/images/star2.svg" alt="star" />
            <a class="anchor" id="success" style="top: -100px;"></a>
            <div class="section__caption">
                <div class="section-header__text">
                    <span>
                        How it works
                    </span>
                </div>
                <div class="section__caption__title">
                    <span>
                        Plan all your weekly groceries & calories in 5 minutes or less!
                    </span>
                </div>
            </div>
            <div class="steps-block">
                <div class="step-block">
                    <div class="step-block-image step1">
                        <img src="assets/images/howitworks-step-1.svg" alt="Step1 Image" />
                    </div>
                    <div class="step-block-title">
                        <span>1</span>
                    </div>
                    <div class="step-block-quote">
                        <span>
                            Drag & drop foods to eat weekly from any grocery store
                        </span>
                    </div>
                </div>
                <div class="step-block">
                    <div class="step-block-image has-border step2">
                        <img src="assets/images/howitworks-step-2.svg" alt="Step2 Image" />
                    </div>
                    <div class="step-block-title">
                        <span>2</span>
                    </div>
                    <div class="step-block-quote">
                        <span>
                            Set a calorie limit to manage your foods & your weight
                        </span>
                    </div>
                </div>
                <div class="step-block">
                    <div class="step-block-image has-border step3">
                        <img src="assets/images/howitworks-step-3.svg" alt="Step3 Image" />
                    </div>
                    <div class="step-block-title">
                        <span>3</span>
                    </div>
                    <div class="step-block-quote">
                        <span>
                            Follow the meal plan generated & achieve healthy goals!
                        </span>
                    </div>
                </div>
            </div>
            <div class="success-bottom_text">
                <router-link :to="{name: 'GroceryStore'}">
                    <button-base class="btn__w--240 signup-free-btn" :text="'CREATE A FREE MEAL PLAN'"/>
                </router-link>
            </div>
        </div>
    </section>
</template>

<script>

    export default {
        name: "SectionHowItWorks",
    }

</script>

<style scoped lang="scss">
    .how-it-works-section {
        background: url(/assets/images/how-it-works-bg.svg);
        background-position: center;
        position: relative;
        overflow: hidden;
        padding-bottom: 50px;
        padding-top: 60px;
        background-repeat: no-repeat;
        background-size: 100% auto;

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
            padding-bottom: 40px;
        }
        .section-header__text {
            margin-bottom: 29px;
        }
        .section__caption__title {
            font-family: Gilroy;
            font-style: normal;
            font-weight: 800;
            font-size: 30px;
            line-height: 50px;
            color: #292D34;
            white-space: nowrap;
        }
        @media (max-width: 1200px) {
            .section__caption__title {
                font-size: 30px;
            }
        }
        @media (max-width: 1024px) {
            .section__caption__title {
                font-size: 30px;
                white-space: unset;
            }
        }
        @media (max-width: 768px) {
            background: none;
            .section__caption__title {
                font-size: 28px;
                line-height: 132.19%;
                padding-left: 20px;
            }
            .section-header__text {
                font-size: 18px;
            }
        }
    }

    .steps-block {
        display: flex;
        justify-content: space-between;
        background: #FFF;
        position: relative;
        padding: 68px 38px 48px;

        &:before {
            content: '';
            display: block;
            width: 100%;
            height: 100%;
            transform: translate(-16px, -5.5px);
            background: #000000;
            opacity: 0.05;
            filter: blur(24px);
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
        }
        .step-block {
            width: 280px;
            text-align: center;
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            flex-direction: column;
        }
        .step-block-image {
            height: 130px;
            position: relative;
            img {
                height: 100%;
            }
            &.step2 {
                padding-top: 6px;
                padding-bottom: 3px;
            }
            &.step3 {
                padding-top: 10px;
                padding-bottom: 3px;
            }
            &.has-border:before {
                content: "";
                display: block;
                bottom: -4px;
                left: 50%;
                transform: translateX(-50%);
                position: absolute;
                border-bottom: 1.2px solid #3f3d562e;
                padding-bottom: 0;
                width: 128px;
            }
        }

        .step-block-title {
            background: #E0CFE9;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-family: Gilroy;
            font-style: normal;
            font-weight: 800;
            font-size: 40px;
            color: #fff;
            margin: 14px 0 10px;
        }
        .step-block-quote {
            font-family: Gilroy;
            font-style: normal;
            font-weight: normal;
            font-size: 15px;
            line-height: 151.19%;
            text-align: center;
            color: #000000;
        }

        @media (max-width: 1024px) {
            justify-content: space-around;
            .step-block {
                width: 240px;
            }
        }

        @media (max-width: 912px) {
            padding: 68px 28px 48px;
            .step-block {
                width: 195px;
            }
        }
        @media (max-width: 768px) {
            background: transparent;
            flex-direction: column;
            align-items: center;
            padding: 0 28px;
            .step-block {
                width: 240px;
                margin-bottom: 30px;
            }
            .step-block-title {
                font-size: 30px;
                width: 44px;
                height: 44px;
            }
            .step-block-quote {
                font-size: 14px;
            }
        }
    }

    .success-bottom_text {
        text-align: center;
        margin-top: 2em;
    }

    // Stars
    .star1, .star2 {
        position: absolute;
    }
    @media screen and (min-width: 981px) {
        .star1 {
            top: 200px;
            left: -60px;
        }
        .star2 {
            top: 250px;
            left: -110px;
        }
    }
    @media screen and (max-width: 980px) {
        .star1 {
            top: 115px;
            right: 40px;
        }
        .star2 {
            top: 140px;
            right: 20px;
        }
    }
</style>
