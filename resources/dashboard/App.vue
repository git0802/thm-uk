<template>
    <div>
        <router-view />
    </div>
</template>

<script>
    import {mapGetters, mapMutations} from 'vuex';

    export default {
        data() {
            return {
            }
        },
        created() {
            this.$store.dispatch('auth/checkGuest', {route: this.$route, http: this.$http});
            this.$store.dispatch('info/getPrice');
            this.$store.dispatch('loader/loading', false)
        },
        mounted() {
            this.onResize();
            window.addEventListener('resize', this.onResize, {passive: true})
        },
        computed: {
            ...mapGetters({
                left_drawer: "plannerUi/getLeftDrawer",
                is_mobile: "plannerUi/getMobile"
            }),
        },
        methods: {
            ...mapMutations({
                handleResize: 'plannerUi/handleResize',
            }),
            onResize() {
                this.handleResize();
            }
        }
    }
</script>


<style lang="scss">
    .nowrap {
        white-space: nowrap;
    }

    :root {
        --purple: #B27DFF;
    }

    .m-auto {
        margin: auto;
    }

    .shadowbox {
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.25);
    }


    .w-max-1020 {
        max-width: 1020px;
    }

    .w-max-750 {
        max-width: 750px;
    }

    .overflow-scroll {
        overflow: scroll;
    }

    .container {
        &__wrapper {
            display: flex;
            flex-direction: row;
            max-width: 1300px;
            margin: 0 auto;
        }
        &__head {
            margin: 0 20px;
            display: flex;
            flex-direction: row;
            .title {
                font-size: 20px;
                padding-right: 8px;
            }
        }
        &__main {
            flex: 1 1 auto;
            background-color: white;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            box-shadow: 0 0 20px -20px black;
            @media screen and (min-width: 1201px) {
                width: calc(100% - 571px);
            }
            @media screen and (min-width: 1021px) and (max-width: 1200px) {
                width: calc(100% - 341px);
            }
            @media screen and (max-width: 1020px) {
                width: 100%;
            }
            &__slot {
                display: flex;
                flex-direction: column;
                height: 100%;
                position: relative;
            }
        }
        &__footer {
            position: relative;
            padding-top: 60px;
            padding-bottom: 15px;
            &--mobile {
                background-color: #f4f4f4;
                padding-bottom: 90px;
                padding-top: 25px;
            }
            &__caption {
                font-size: 14px;
                color: #4E4E4E;
                font-family: Gilroy;
                justify-content: center;
                display: flex;
                padding-top: 15px;
            }
        }
        &__left-drawer {
            // todo ask about this width
            min-width: 291px;
            width: 291px;
            padding: 0 50px 0 50px;
            background-color: #f7f7f7;
            /*height: 100%;*/
            &__mobile {
                width: 100%;
                max-width: 300px;
                height: 100%;
                overflow: scroll;
                position: fixed;
                z-index: 10;
                left: 0px;
                transition-duration: 0.3s;
                transform: translate3d(-300px, 0px, 0px);
                &--opened {
                    transform: translate3d(0vw, 0px, 0px);

                }
            }
        }
        &__content {
            padding: 10px 10px 20px 10px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            @media screen and (max-width: 1020px) {
                max-width: unset;
                padding: 10px 10px 50px 10px;
            }
            &.unmaxed {
                max-width: unset;
            }

        }
        &__right-drawer {
            max-width: 256px;
            will-change: width;
            top: 65px;
            &.showed {
                width: 240px;
            }
            &.closed {
                width: 50px
            }
            &__mobile {
                width: 100%;
                max-width: 280px;
                height: 100%;
                overflow: scroll;
                position: fixed;
                z-index: 10;
                right: 0;
                top: 0;
                transform: translate3d(280px, 0px, 0px);
                transition: transform 0.2s ease-out 0s;
                background: white;
                &--opened {
                    transform: translate3d(0, 0px, 0px);
                }
            }
        }
    }

    .top-head {
        min-width: 50px;
        z-index: 2;
        padding-top: 40px;
        align-items: baseline;
        display: flex;
        position: relative;
        &--sticky {
            position: sticky;
            top: 0;
            margin: unset;
            padding: 40px 20px;
            z-index: 6;
            background: white;
            border-bottom: 1px solid rgb(223, 223, 223);
        }
        &--borderless {
            border-bottom: unset;
        }
    }

</style>


