<template>
    <app-layout>
        <section-one v-on:handlemodal="handleModal"/>
        <section-eight/>
        <!-- <section-featured-in /> -->
        <section-how-it-works />
        <!-- <SectionSeven></SectionSeven> -->
        <section-two/>
        <div class="not-sponsored">
           <span>
             The Hot Meal is an independent business that is not necessarily affiliated with, endorsed, or sponsored by other businesses mentioned.
           </span>
        </div>
        <section-five/>
        <section-three/>
        <section-four v-on:handlemodal="handleModal"/>
        <section-sixth v-on:handlemodal="handleModal"/>

        <div class="video-modal" :class="{'video-modal--opened': video_modal}"
             v-on:click.self="video_modal = !video_modal">
            <div class="video-modal__content" v-click-outside="clickConfig">
                <iframe src="https://fast.wistia.net/embed/iframe/asnxp5f4eu" title="7 Video"
                        allow="autoplay; fullscreen" allowtransparency="true" frameborder="0" scrolling="no"
                        class="wistia_embed" name="wistia_embed" allowfullscreen msallowfullscreen width="640"
                        height="360"></iframe>
            </div>
        </div>
    </app-layout>
</template>

<script>
import IconArrowDown from "../components/svg/IconArrowDown";
import ButtonPlay from "../components/buttons/ButtonPlay";
import SectionFeaturedIn from '../components/landing/SectionFeaturedIn.vue';
import SectionHowItWorks from '../components/landing/SectionHowItWorks.vue';

export default {
    components: { 
        'section-featured-in': SectionFeaturedIn,
        'section-how-it-works': SectionHowItWorks,
    },
    data: function () {
        return {
            video_modal: false,
            clickConfig: {
                handler: this.outside,
                middleware: this.middleware,
                events: ['dblclick', 'click'],
                isActive: true
            },
            components: {IconArrowDown, ButtonPlay}
        }
    },
    methods: {
        handleModal() {
            document.getElementsByTagName("iframe")[0].contentWindow.postMessage('{"method":"pause"}', '*');
            this.video_modal = !this.video_modal;
        },
        outside() {
            this.handleModal();
        },
        middleware(event) {
            let a = 'video-modal video-modal--opened';
            return event.target.className === a;
        }
    }
}
</script>

<style lang="scss">
a.anchor {
    display: block;
    position: relative;
    top: -250px;
    visibility: hidden;
}

button.btn__w--240 {
    width: 240px !important;
}

button.signup-free-btn {
    width: 280px !important;
    max-width: none;
}

.wrapper {
    max-width: 1030px;
    margin: 0 auto;
    padding: 0 25px;
}


.hooper {
    height: 122px !important;
    outline: none !important;

    svg.icon {
        width: 60px;
        height: 60px;
        opacity: .5;
        transition: 250ms;
    }
}

.hooper-indicator:hover, .hooper-indicator.is-active {
    background-color: #fff !important;
    box-shadow: inset 0 0 0px 4px #B27DFF;
    opacity: 1;
}

.hooper-prev, .hooper-next {
    fill: #B27DFF;
    padding: 0 !important;

    &:hover svg {
        opacity: 1;
    }
}

.hooper-prev {
    transform: translate(-100%, -50%) !important;
}

.hooper-next {
    transform: translate(100%, -50%) !important;
}

.hooper-pagination {
    transform: translate(50%, 100%) !important;
}

.hooper-slide {
    padding: 6px 10px !important;
    transition: 250ms;
    /*filter: grayscale(1);
    position: relative;
    &:after {
        content: "";
        position: absolute;
        left: 10px;
        width: calc(100% - 20px);
        height: 100%;
        background: #fff;
    }*/
    img {
        max-width: 100%;
        max-height: 100%;
        z-index: 2;
    }

    &:hover {
        filter: grayscale(0);
    }
}

.hooper-list:focus {
    outline: unset !important;
}


button.hooper-indicator {
    background: #B27DFF;
    height: 12px;
    width: 12px;
    border-radius: 50%;
    opacity: .5;
}

.hooper-slide, .is-clone {
    display: flex;
    justify-content: center;
    align-items: center;
}

.not-sponsored {
    margin-top: 1rem;
    text-align: center;
    color: #afafaf;
    font-weight: 400;
}

@media screen and (max-width: 480px) {
    button.button {
        max-width: 100% !important;
        width: 100% !important;
    }
}

</style>
