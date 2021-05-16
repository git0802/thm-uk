<template>
    <div class="container__main__slot">
        <div class="container__head top-head top-head--sticky">
            <app-left-drawer-burger/>
            <div class="title">
                Support
            </div>
        </div>
        <div class="container__content" style="width: 100%">

            <div class="tutorial-container">

                <div class="container__content__tutorial__title"><span>TheHotMeal - Tutorial</span></div>

                <div class="container__content__tutorial__iframecontainer">

                    <iframe src="https://fast.wistia.net/embed/iframe/qtaoyqaxj8" title="TheHotMeal - Tutorial"
                        allow="autoplay; fullscreen" allowtransparency="true" frameborder="0" scrolling="no"
                        class="wistia_embed container__content__tutorial__iframe" name="wistia_embed" allowfullscreen msallowfullscreen></iframe>

                </div>
            </div>

            <div class="container__content__support">
                <div>

                    <p>
                        For corporate inquiries, please send an initial message to <a class="a__mailto"
                                                                                      href="mailto:hello@thehotmeal.co.uk">hello@thehotmeal.co.uk</a>,
                        and your email will be routed from there.
                    </p>

                    <p>
                        For customer support, please contact <a class="a__mailto" href="mailto:hello@thehotmeal.co.uk">hello@thehotmeal.co.uk</a>,
                        and include your account information and query.
                    </p>

                    <p>
                        Email support is available Monday through Friday, 9am to 6pm. We do our best to issue an
                        immediate response and facilitate speedy processes for any specific issue to resolve.
                    </p>

                </div>


                <div class="contact-form">
                    <support-form/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>


import SupportForm from "../../js/components/support/SupportForm";

export default {
    name: "Support",
    components: {
        SupportForm

    }
}
</script>

<style lang="scss" scoped>

.container__content__tutorial__title {
    margin: 20px;
}

.container__content__tutorial__iframecontainer {
    position: relative;
    width: 100%;
    overflow: hidden;
    padding-top: 56.25%;
}

iframe.wistia_embed.container__content__tutorial__iframe {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    width: 100%;
    height: 100%;
    border: none;
}

.container__content__support {
    font-size: 14px;
    padding: 0 10px;
    margin-top: 20px;

    p {
        padding: 10px 0;
        line-height: 1.35;
        font-weight: 500;
    }
}

.a__mailto {
    color: var(--purple);
}

.contact-form {
    margin-top: 30px;
    max-width: 446px;
}
</style>
