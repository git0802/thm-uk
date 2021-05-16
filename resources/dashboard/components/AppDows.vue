<template>
    <div class="day-tabs day-tabs--sticky" :ref="`parent_tabs`" :class="{mobile: this.is_mobile}" v-if="!need_to_update">
        <div class="day-tab" v-for="(day, key, index) in strings" :ref="`ref_${key}`" :key="index"
                 @click="selectDay(key)"
                 :class="{
            active: (day.full.toLocaleLowerCase() === selectedDayName.toLocaleLowerCase()),
            }">
                {{day[dayCase]}}
            </div>
        <div class="day-tab__underline" :ref="`underline`" :style="getUnderlineStyles"> </div>
    </div>
</template>

<script>

    import moment from 'moment';
    import ResizeObserver from 'resize-observer-polyfill';
    import axios from "axios";

    export default {
        // days of the week
        props: ['isEmpty', 'need_to_update'],
        name: "AppDows",
        data: function() {
            return {
                strings: {
                    monday: {
                        full: 'Monday',
                        short: 'Mon',
                    },
                    tuesday: {
                        full: 'Tuesday',
                        short: 'Tue',
                    },
                    wednesday: {
                        full: 'Wednesday',
                        short: 'Wed',
                    },
                    thursday: {
                        full: 'Thursday',
                        short: 'Thu',
                    },
                    friday: {
                        full: 'Friday',
                        short: 'Fri',
                    },
                    saturday: {
                        full: 'Saturday',
                        short: 'Sat',
                    },
                    sunday: {
                        full: 'Sunday',
                        short: 'Sun',
                    },
                },
                underline: {
                    width: 0,
                    left: 0,
                    right: 0,
                    center: 0,
                },
                resizeListener: null,
                ro: null,
            }
        },
        computed: {
            is_mobile: {
                get() {
                    return this.$store.state.plannerUi.is_mobile;
                }
            },

            current_day: {
                get() {
                    return this.$store.state.planner.current_day;
                },
                set(value) {
                    this.$store.commit('planner/setCurrentDay', value);
                }
            },
            selected_day: {
                get() {
                    return this.$store.state.planner.selected_day;
                },
                set(value) {
                    this.$store.commit('planner/setSelectedDay', value);
                }
            },
            selected_week: {
                get() {
                    return this.$store.state.planner.selected_week;
                }
            },


            currentDayName: function () {
                return moment(this.current_day, 'YYYY-MM-DD').format('dddd');
            },
            selectedDayName: function () {
                return moment(this.selected_day, 'YYYY-MM-DD').format('dddd');
            },
            dayCase: function() {
                return this.is_mobile ? 'short' : 'full';
            },
            getUnderlineStyles() {
                return [
                    {'width': `${this.underline.width}px`},
                    {'left': `${this.underline.left}px`},
                ]
            },
        },
        watch: {
            selected_day: function(newValue, oldValue) {
                let dayname = moment(newValue, 'YYYY-MM-DD').format('dddd');
                let dayAndHours = moment(newValue, 'YYYY-MM-DD').format('YYYY-MM-DD 00:00:00')
                this.calculateStyle(dayname.toLocaleLowerCase());

                // this.fetchWeekPlan(dayAndHours)
            },
            content: function () {
                this.$nextTick(() => {})
            }
        },
        mounted() {
            this.init();
        },
        beforeDestroy() {
          this.resizeListener.unobserve(this.$refs[`parent_tabs`]);
        },
        methods: {
            async fetchWeekPlan(date) {
                const response = await axios.get(`/api/planner`, {
                    params: {
                        date: date
                    }
                });
                this.$store.commit('planner/setPlannerId', response.data.data.id);
                this.$store.commit('planner/setFinishedSetup', response.data.data.finished_setup);
                return response.data;
            },
            calculateStyle(key) {
                let parent    =  this.$refs[`parent_tabs`].getBoundingClientRect();
                let day       = this.$refs[`ref_${key}`][0].getBoundingClientRect();
                let underline = this.$refs[`underline`].getBoundingClientRect();

                this.underline.width  =  day.width;
                this.underline.left   =  Math.abs(parent.left - day.left);
                this.underline.right  =  day.right;
                this.underline.center =  (this.underline.width / 2);
            },
            selectDay(key) {
                this.selected_day = this.selected_week.days[key]
                // this.$emit('test');
            },
            init() {
                if(this.selected_day) {
                    this.calculateStyle(this.currentDayName.toLowerCase());
                }

                this.resizeListener = new ResizeObserver(entries => {
                    for (const entry of entries) {
                        this.calculateStyle(this.selectedDayName.toLocaleLowerCase());
                    }
                });

                this.resizeListener.observe(this.$refs[`parent_tabs`]);
            },

        }
    }
</script>

<style scoped lang="scss">
    .day-tabs {
        display: flex;
        flex-direction: row;
        margin: 40px 20px 20px 20px;
        border-bottom: 1px solid #dfdfdf;
        position: relative;
        // todo NOT SURE
        justify-content: space-around;

        &--sticky {
            position: sticky;
            top: 101px;
            background: white;
            z-index: 5;
            margin: unset;
        }

        &.mobile {
            margin-left: unset;
            margin-right: unset;
            padding-right: 20px;
            padding-left: 20px;
        }
        .day-tab {
            font-size: 14px;
            padding: 10px;
            color: #929292;
            cursor: pointer;
            position: relative;
            transition: 0.2s linear;
            -moz-user-select: none;
            -ms-user-select: none;
            -khtml-user-select: none;
            -webkit-user-select: none;
            @media screen and (max-width: 350px) {
                padding: 7px;
            }
            &__underline {
                bottom: 0;
                position: absolute;
                left: 0;
                //border-bottom: 4px solid transparent;
                /*border-bottom: 4px solid red;*/
               // z-index: -1;
                height: 5px;
                border-top-left-radius: 5px;
                border-top-right-radius: 5px;
                background: var(--purple);
                z-index: 1;
                transition: all .35s ease-in-out;

                /*
                height: .25rem;
                margin: 0;
                background: #8e44ad;
                border-radius: 2px;
                border: none;
                transition: margin .3s cubic-bezier(0.250, 0.460, 0.450, 0.940); */
            }
            &:after {
                content: "";
                width: 100%;
                display: initial;
                height: 5px;
                background: #929292;
                position: absolute;
                bottom: 0;
                left: 0;
                border-top-left-radius: 5px;
                border-top-right-radius: 5px;
                right: 0;
                opacity: 0;
                pointer-events: none;
                transition: 0.2s linear;
            }
            &:hover {
                &:after {
                    opacity: 1;
                }
            }
            &.active {
                color: #292D34;
                &:after {
                    /*content: "";*/
                    /*width: 100%;*/
                    /*display: initial;*/
                    /*height: 5px;*/
                    /*background: var(--purple);*/
                    /*position: absolute;*/
                    /*bottom: 0;*/
                    /*left: 0;*/
                    /*border-top-left-radius: 5px;*/
                    /*border-top-right-radius: 5px;*/
                    /*right: 0;*/
                    /*transition: all 1s ease-in-out;*/

                }
            }
        }
    }
</style>
