<template>
    <section class="success-section">
        <div class="wrapper success-section__wrapper">
            <img class="star1" src="assets/images/star1.svg" alt="star" />
            <img class="star2" src="assets/images/star2.svg" alt="star" />
            <img class="star3" src="assets/images/star3.svg" alt="star" />
            <a class="anchor" id="success" style="top: -100px;"></a>
            <div class="section__caption">
                <div class="section-header__text">
                        <span>
                            Client Success
                        </span>
                </div>
                <div class="section__caption__title">
                    <span>
                        You can eat what you want.
                        We just help you control how much!
                    </span>
                </div>

            </div>
            <div class="success-block">
                <div class="success-block-hero">
                    <div class="success-block-hero-image">
                        <img src="assets/images/client1.png" alt="Success Client Image" />
                    </div>
                    <div class="success-block-hero-title">
                        <span>Ayesha W.</span>
                    </div>
                    <div class="success-block-hero-quote">
                        <img class="success-block-hero-quote-top" src="assets/images/top-quote.png" alt="Top Quote Mark" />
                        <img class="success-block-hero-quote-bottom" src="assets/images/bottom-quote.png" alt="Bottom Quote Mark" />
                        <span>
                            I used to look pregnant all the time because
                            I ate all the time from stress. I quickly followed
                            The Hot Meal planner & took back control!
                        </span>
                    </div>
                </div>
                <div class="success-block-content">
                    <div>
                        <div class="success-block-content-text">
                            <span>without</span>
                            <span>Our Meal Planner</span>
                        </div>
                        <div class="success-block-content-image">
                            <img src="assets/images/client1-without-meal.png" alt="Without Meal Planner" />
                        </div>
                    </div>
                    <div>
                        <div class="success-block-content-text">
                            <span>with</span>
                            <span>Our Meal Planner</span>
                        </div>
                        <div class="success-block-content-image">
                            <img src="assets/images/client1-with-meal.png" alt="With Meal Planner" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="success-block">
                <div class="success-block-hero">
                    <div class="success-block-hero-image">
                        <img src="assets/images/client2.jpg" alt="Success Client Image" />
                    </div>
                    <div class="success-block-hero-title">
                        <span>Kate Edelman</span>
                    </div>
                    <div class="success-block-hero-quote">
                        <img class="success-block-hero-quote-top" src="assets/images/top-quote.png" alt="Top Quote Mark" />
                        <img class="success-block-hero-quote-bottom" src="assets/images/bottom-quote.png" alt="Bottom Quote Mark" />
                        <span>
                            I was unaware that I was eating 3,500 calories a day!
                            I’m supposed to be eating 1,500 calories a day to look fit.
                            This site helped me get on track & slim my waist.
                        </span>
                    </div>
                </div>
                <div class="success-block-content">
                    <div>
                        <div class="success-block-content-text">
                            <span>without</span>
                            <span>Our Meal Planner</span>
                        </div>
                        <div class="success-block-content-image">
                            <img src="assets/images/client2-without-meal.jpg" alt="Without Meal Planner" />
                        </div>
                    </div>
                    <div>
                        <div class="success-block-content-text">
                            <span>with</span>
                            <span>Our Meal Planner</span>
                        </div>
                        <div class="success-block-content-image">
                            <img src="assets/images/client2-with-meal.jpg" alt="With Meal Planner" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="success-bottom_text">
                <span>Results are your own & can vary.</span>
            </div>
        </div>
    </section>
</template>

<script>

    export default {
        name: "SectionEight",
    }

</script>

<style scoped lang="scss">
    .success-section {
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
            padding-bottom: 40px;
        }
        .section-header__text {
            margin-bottom: 29px;
        }
    }

    .success-block {
        display: grid;
        grid-template-columns: 50% 50%;
        margin-top: 20px;
        margin-bottom: 40px;

        &-hero {
            padding: 60px 100px 20px 20px;
            &-image {
                text-align: center;

                img {
                    border-radius: 50%;
                    box-shadow: 0 0 10px rgba($color: #000000, $alpha: 0.1);
                }
            }

            &-title {
                text-align: center;
                font-weight: 800;
                font-size: 26px;
                color: #B27DFF;
                margin: 12px;
            }

            &-quote {
                position: relative;
                font-weight: 800;
                font-size: 22px;
                line-height: 27px;
                color: #000000;
                opacity: 0.7;

                &-top {
                    position: absolute;
                    left: -70px;
                }

                &-bottom {
                    position: absolute;
                    right: -40px;
                    bottom: 0;
                }
            }
        }

        &-content {
            display: grid;
            grid-template-columns: 50% 50%;

            &-text {
                text-align: center;
                margin-bottom: 20px;
                span {
                    display: block;
                    font-weight: 800;
                    font-size: 14px;
                    color: #C4C4C4;
                    margin: 5px auto;
                }
                span:first-child {
                    text-transform: uppercase;
                    color: #DF2C65;
                }
            }

            &-image {
                text-align: center;

                img {
                    width: 100%;
                    max-width: 200px;
                    padding: 5px;
                }
            }
        }
    }

    .success-bottom_text {
        font-weight: 300;
        font-size: 12px;
        text-align: center;
        color: #4E4E4E;
        opacity: 0.5;
    }

    .star1, .star2, .star3 {
        position: absolute;
    }


    @media screen and (min-width: 981px) {
        .star1 {
            top: 140px;
            left: -50px;
        }

        .star2 {
            top: 200px;
            left: -30px;
        }

        .star3 {
            top: 170px;
            left: -70px;
        }
    }

    @media screen and (max-width: 980px) {
        .success-section .section__caption {
            padding-bottom: 30px;
        }
        .success-block {
            display: block;

            &-hero {
                padding: 20px;

                &-quote-top,
                &-quote-bottom {
                    display: none;
                }

                &-title {
                    font-size: 24px;
                }

                &-quote {
                    font-size: 14px;
                }
            }
        }

        .star1 {
            top: 5px;
            right: 40px;
        }

        .star2 {
            top: 30px;
            right: 20px;
        }

        .star3 {
            top: 40px;
            right: 40px;
        }

    }
</style>
