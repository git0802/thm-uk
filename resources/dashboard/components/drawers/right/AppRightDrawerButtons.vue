<template>

    <div v-if="this.is_mobile">
        <div class="top-head top-head__mobile" :class="{

            'top-head__mobile--opened': this.is_mobile && this.right_drawer

        }"  ref="tophead">
            <template>
                <div class="rd-closer">
                        <div class="rd-closer__circle mobile shadowbox"
                             @click="handleRightDrawer">
                            <div class="rd-closer__circle__svg">
                                <template v-if="this.right_drawer">
                                    <svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5.76506 7.00046L0.411721 11.7588L1.94094 13.1181L8.82349 7.00046L1.94094 0.882812L0.411721 2.24208L5.76506 7.00046Z"/>
                                    </svg>
                                </template>
                                <template v-else>
                                    <svg width="10" height="13" viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3.85335 6.38235L9.20669 1.62397L7.67747 0.264706L0.794922 6.38235L7.67747 12.5L9.20669 11.1407L3.85335 6.38235Z"/>
                                    </svg>
                                </template>
                            </div>
                        </div>
                </div>
            </template>
        </div>
    </div>
    <div v-else>
        <div class="top-head" ref="tophead">
            <template v-if="right_drawer">
                <div class="rd-closer__circle shadowbox"
                     @click="handleRightDrawer">
                    <div class="rd-closer__circle__svg">
                        <svg style="margin-left: 2px;" width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.76506 7.00046L0.411721 11.7588L1.94094 13.1181L8.82349 7.00046L1.94094 0.882812L0.411721 2.24208L5.76506 7.00046Z"/>
                        </svg>
                    </div>
                </div>
                <div class="rd-closer__caption">
                    Hide Side Panel
                </div>
            </template>
            <template v-else>
                <div class="rd-closer__circle shadowbox"
                     style=" position: absolute;
                             top: 160px;
                             left: 14px;
                             margin-left: 0;"
                     @click="handleRightDrawer">
                    <svg style="margin-right: 2px;" width="10" height="13" viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.85335 6.38235L9.20669 1.62397L7.67747 0.264706L0.794922 6.38235L7.67747 12.5L9.20669 11.1407L3.85335 6.38235Z"/>
                    </svg>
                </div>
                <div class="rd-closer__caption rotated">
                    Show Side Panel
                </div>
            </template>
        </div>
    </div>

</template>

<script>
    import { mapGetters, mapMutations } from 'vuex';

    export default {
        name: "AppRightDrawerButtons",
        data: function() {
            return {
                transform: {
                    progress: 0
                },
            }
        },
        methods: {
            ...mapMutations({
                handleRightDrawer: 'plannerUi/handleRightDrawer',
            }),
        },
        computed: {
            ...mapGetters({
                right_drawer: "plannerUi/getRightDrawer",
                is_mobile: "plannerUi/getMobile"
            }),
        },
        watch: {
            right_drawer: function (newValue, oldValue) {
                if(newValue) {
                    // if(this.is_mobile) {
                    //     this.$refs.tophead.style.transform = `translate3d(${this.transform.opened}, 0px, 0px)`;
                    //     this.$refs.tophead.style.position = `fixed`;
                    //     this.$refs.tophead.classList.add('opened')
                    // }
                    this.transform.progress = 100;
                }
                if(oldValue) {
                    // if(this.is_mobile) {
                    //     this.$refs.tophead.style.transform = `translate3d(${this.transform.closed}, 0px, 0px)`;
                    //     this.$refs.tophead.style.position = `fixed`;
                    //     this.$refs.tophead.classList.remove('opened')
                    // }
                    this.transform.progress = 0;
                }
            },
        }
    }
</script>

<style scoped lang="scss">
    .top-head__mobile {
        position: fixed;
        right: 0;
        top: calc(50vh - 50px);
        transform: translate3d(40px, 0px, 0px);
        transition: transform 0.2s ease-out 0s;
        &--opened {
            transform: translate3d(calc(-280px + 40px), 0px, 0px);
            z-index: 5;
        }
    }

    .mobile .rd-closer__circle__svg {
        margin-left: -20px;
    }
    .rd-closer {
        position: relative;
        display: flex;
        flex-direction: row;
        margin-left: 5px;

        &__circle {
            height: 25px;
            width: 25px;
            border-radius: 50%;
            cursor: pointer;
            justify-content: center;
            display: flex;
            color: #292D34;
            align-items: center;
            margin-right: 10px;
            margin-left: 20px;
            z-index: 2;
            background: white;
            svg {
                fill: currentColor;
            }
            @media screen and (min-width: 991px) {
                transition: color .15s linear, background .15s linear;
                &:hover {
                    background: var(--purple);
                    color: #fff;
                    svg {
                        fill: currentColor;
                    }
                }
            }

            &.mobile {
                height: 53px;
                width: 53px;
                .opened {

                }
            }
            &__svg {
                height: 100%;
                width: 100%;
                display: flex;
                justify-content: center;
                align-items: center;
            }
        }

        &__caption {
            font-family: "Gilroy";
            font-weight: 400;

            &.rotated {
                height: 24px;
                width: 200px;
                left: 16px;
                top: 145px;
                position: absolute;
                transform-origin: top left;
                transform: rotateZ(-90deg);
            }

        }
    }
</style>
