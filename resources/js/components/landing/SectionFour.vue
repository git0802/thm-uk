<template>
    <section class="mini-signup">
        <div class="wrapper mini-signup__wrapper">
            <div class="mini-signup__content">
                <div class="section__caption">
                    <div class="section__caption__title">
                        Get started free for 14 days!
                    </div>
                    <div class="section__caption__description">
                        Cancel any time you want, or stay on and take advantage of our INCREDIBLE introductory offer that's only {{ phrase.currencySm }}6/month, ({{ phrase.currencySm }}72 billed annually) for a limited time only! <span class="text-link" @click="openModal">Or go monthly!</span>
                    </div>
<!--                    <div class="section__caption__description">-->
<!--                        *Using a voucher code-->
<!--                    </div>-->
                </div>
                <div class="button-caption">
                    <router-link :to="{name: 'GroceryStore'}">
                        <button-base class="btn__w--240 signup-free-btn" :text="'CREATE A FREE MEAL PLAN'" :style="{'z-index': 2}"/>
                    </router-link>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        name: "SectionFour",
        methods: {
            openModal() {
                this.$emit('handlemodal');
            }
        }
    }
</script>

<style scoped lang="scss">
     .section-4 {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(20em, 2fr));
         grid-gap: 2vw;
         z-index: 2;
             &:before {
                 content: "";
                 height: 500px;
                 width: 310em;
                 background-color: #FAEFFF;
                 position: absolute;
                 right: 0;
                 transform: translate(269em, -17em);
                 clip-path: ellipse(86% 155% at 89% 71%);
                 @media screen and (max-width: 980px) {
                     display: none;
                 }
             }
     }

    .button-caption {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .text-link {
        cursor: pointer;
    }

    .mini-signup {
        position: relative;
        overflow: hidden;
        background-color: #F6E3FF;
        margin: 3em 0em 7em 0em;
        padding: 5em 0;
        &__wrapper {
            position: relative
        }
        &__content {
            z-index: 2;
            display: flex;
            justify-content: space-between;
            &:before {
                content: "";
                height: 480px;
                width: 310em;
                background-color: #FAEFFF;
                position: absolute;
                right: 0;
                transform: translate(274em, -15em);
                -webkit-clip-path: ellipse(86% 155% at 89% 71%);
                clip-path: ellipse(86% 155% at 89% 71%);
            }
        }
        .button-caption {
            margin-right: 40px;
        }
        .section__caption__title {
            color: var(--purple);
            font-size: 36px;
            font-weight: 900;
        }
        .section__caption {
            z-index: 2;
            max-width: 523px;
        }
        .section__caption__description {
            font-size: 16px;
            color: #000;
            margin-top: 0.2em;
            font-weight: 800;
            padding-right: 2px;
            b {
                color: var(--primary);
            }
        }
    }
     @media screen and (max-width: 980px) {
         .mini-signup {
             margin: 46px 0 0 0;
             padding: 65px 0 70px 0;
             &__content {
                 flex-direction: column;
                 align-items: center;
                 &:before {
                     display: none;
                 }
             }
             .section__caption__description {
                 margin-top: 12px;
             }
             .button-caption {
                 margin-right: 0;
                 margin-top: 37px;
             }
         }
     }
     @media screen and (max-width: 480px) {
         .mini-signup {
             .button-caption {
                 width: 100%;
                 a {
                     width: 100%;
                 }
             }
         }
     }
</style>
