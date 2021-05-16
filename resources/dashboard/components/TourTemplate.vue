<template>
    <v-tour name="mealsTour" :steps="steps">
        <template slot-scope="tour">
            <transition name="fade">
                <v-step
                    class="v-step"
                    v-for="(step, index) of tour.steps"
                    :key="index"
                    v-if="tour.currentStep === index"
                    :step="step"
                    :previous-step="tour.previousStep"
                    :next-step="tour.nextStep"
                    :stop="tour.stop"
                    :skip="tour.skip"
                    :is-first="tour.isFirst"
                    :is-last="tour.isLast"
                    :labels="tour.labels"
                >



                    <template>
                        <div slot="header" class="v-step__header">
                            <div v-if="step.header">
                                <div v-if="step.header.title" v-html="step.header.title"></div>
                            </div>

                            <div class="v-step__closer"  @click.prevent="tour.finish">
                                <close-x/>
                            </div>
                        </div>
                    </template>

                    <template>
                        <div slot="content" class="v-step__content">
                            <div v-if="step.content" v-html="step.content"></div>
                        </div>
                    </template>


                    <template>
                        <div slot="actions" class="v-step__actions">
                            <button v-if="!tour.isFirst" @click="tour.previousStep">
                                <right-arrow :height="14" :width="8" :fill="'white'" :styles="{transform: 'rotate(180deg)'}" />
                            </button>

                            <div class="v-step__actions__steps">
                                {{tour.currentStep + 1}} / {{tour.steps.length}}
                            </div>


                            <button v-if="!tour.isLast"  @click="tour.nextStep">
                                    <right-arrow :height="14" :width="8" :fill="'white'" />
                            </button>
                        </div>
                    </template>


                </v-step>
            </transition>
        </template>
    </v-tour>
</template>

<script>
    import CloseX from "./icons/closeX";
    import RightArrow from "./icons/RightArrow";
    export default {
        name: "TourTemplate",
        components: {RightArrow, CloseX},
        data: function() {
            return {
                name: "mealsTour",
                steps: [
                    {
                        target: '[data-v-step="1"]',
                        header: {
                            title: 'Tutorial'
                        },
                        content: 'Regenerate food that tastes different but still matches your calorie goals.',
                        params: {
                            enableScrolling: false
                        }
                    },
                    {
                        target: '[data-v-step="2"]',
                        header: {
                            title: 'Tutorial'
                        },
                        content: 'Don’t forget to scroll through the list and mark your food as eaten.',
                        params: {
                            enableScrolling: false
                        }
                    },                    {
                        target: '[data-v-step="3"]',
                        header: {
                            title: 'Tutorial'
                        },
                        content: 'Keep track of your goals and spendings on daily and weekly basis.',
                        params: {
                            enableScrolling: false
                        },
                        before: type => new Promise((resolve, reject) => {
                            this.$store.state.plannerUi.right_drawer = true;
                            resolve('foo')
                        })
                    },
                    {
                        target: '[data-v-step="4"]',
                        header: {
                            title: 'Tutorial'
                        },
                        content: 'Add or Remove extra calories if you ate more or did some exercise respectively.'
                    }
                ],
            }
        },
        watch: {
            $route(to, from) {
                if(from.name === 'dashboard.planner.meals') {
                    this.$tours[this.name].finish()
                }
            },

        },
        methods: {
            startTutorial() {
                this.$tours[this.name].start()
            }
        },
    }
</script>

<style scoped lang="scss">
        .v-step {
            background-color: #292D34 !important;
            border-radius: 8px !important;
            padding: 1.5rem !important;

            &__header {
                background-color: #292D34 !important;
                border-radius: 8px !important;
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                font-size: 20px !important;
                padding: 1rem !important;
            }

            &__content {
                text-align: initial !important;
                font-family: Gilroy !important;
                font-size: 16px;
            }

            &__closer {
                cursor: pointer;
            }

            &__actions {
                display: flex;
                justify-content: flex-end;

                &__steps {
                    padding-left: 10px;
                    padding-right: 10px;
                    font-family: Gilroy;
                    justify-content: flex-end;
                }
            }

        }
</style>
