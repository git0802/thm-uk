<template>
        <div class="container__right-drawer" ref="crd" v-click-outside="vcoConfig" v-touch:swipe.right="swipeRight"
             :class="{
            'container__right-drawer__mobile': this.is_mobile,
            'container__right-drawer__mobile--opened': this.is_mobile && this.right_drawer,
        }">
            <div v-if="right_drawer" class="container__right-drawer--wrapper">
                <div class="rd-content"
                     :class="{
                }">
                    <div class="rd-content__blocks">

                        <inspire-block/>

                        <stats-block/>

                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    import { mapGetters, mapMutations } from 'vuex';
    import InspireBlock from "./InspireBlock";
    import StatsBlock from "./StatsBlock";
    export default {
        name: "AppRightDrawer",
        components: {StatsBlock, InspireBlock},
        data: function() {
            return {
                vcoConfig: {
                    handler: this.outside,
                    middleware: this.middleware,
                    events: ['dblclick', 'click'],
                    isActive: true
                }
            }
        },
        methods: {
            ...mapMutations({
                closeRightDriver: 'plannerUi/setRightDrawer',
            }),
            middleware(event) {
                let a = 'mcw-overlay hey';
                return event.target.className === a;
            },
            outside() {
                this.closeRightDriver(false);

            },
            swipeRight() {
                if(this.right_drawer) {
                    this.closeRightDriver(false);
                }
            },
        },
        computed: {
            ...mapGetters({
                right_drawer: "plannerUi/getRightDrawer",
                is_mobile: "plannerUi/getMobile"
            }),
        },
    }
</script>

<style scoped lang="scss">

    .container__right-drawer--closed {
        position: relative;
        .caption {
            height: 39px;
            width: 200px;
            left: 10px;
            top: 145px;
            position: absolute;
            transform-origin: top left;
            transform: rotateZ(-90deg);
            font-family: "Gilroy";
        }
    }

    .rd-content {
        padding: 0 20px 0 20px;
        &__blocks {
            margin-top: 30px;
        }
        &__block {
            background: red;
            border-radius: 10px;
            padding: 5px 15px 20px 15px;
            margin-bottom: 15px;
            min-width: 200px;
        }
        &.hidden {
            display: none;
        }
    }


</style>
