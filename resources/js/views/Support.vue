<template>
    <app-layout>
        <div class="wrapper wrapper__padded wrapper__break">
            <div class="section__caption__title m-b-21">
                Support
            </div>
            <p class="pb-5">
                For corporate inquiries, please send an initial message to <a class="a__mailto" href="mailto:hello@thehotmeal.co.uk">hello@thehotmeal.co.uk</a>, and your email will be routed from there.

            </p>
            <p class="pb-5">
                For customer support, please contact <a class="a__mailto" href="mailto:hello@thehotmeal.co.uk">hello@thehotmeal.co.uk</a>, and include your account information and query.

            </p>
            <p class="pb-5">
                For personalized support, please log in and check for your Site Advisor’s name at the top of your dashboard.

            </p>

            <p class="pb-5">
                Email support is available Monday through Friday, 9am to 6pm. We do our best to issue an immediate response and facilitate speedy processes for any specific issue to resolve.

            </p>

            <div class="contact-form">
                <div class="contact-form__caption">
                    Get in touch
                </div>

                <support-form/>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import SupportForm from "../components/support/SupportForm";
    export default {
        name: "Support",
        components: {SupportForm},
    }
</script>

<style scoped lang="scss">
    .wrapper {
        p {
            line-height: 138.18%;
            font-weight: 500;
            font-size: 16px;
            margin-bottom: 8px;
        }
    }

    .a__mailto {
        color: var(--purple);
    }

    .contact-form {
        max-width: 480px;

        &__caption {
            font-size: 16px;
            font-weight: 800;
            color: #575f6d;
            margin: 35px 0px 11px 0px;
        }
        &__inputs {
            display: flex;
            flex-direction: column;
        }
    }

    .input-style {
        width: 100%;
        background-color: transparent;
        font-family: inherit;
        font-weight: 300;
        font-style: normal;
        font-size: 15px;
        line-height: 151.19%;
        color: var(--black);
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        position: relative;
        padding: 9px 11px;
        border-radius: 7px;
        border: 1px solid var(--grey);
        background: var(--grey-light);
        &::placeholder {
            color: var(--black);
            /*Recommended write this for FireFox*/
            opacity: 1;

            .error & {
                color: var(--red);
            }
        }
    }

    .m-b-21 {
        margin-bottom: 21px;
    }
</style>
