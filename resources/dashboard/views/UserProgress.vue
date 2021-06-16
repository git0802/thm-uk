<template>
    <div class="container__main__slot">
        <div class="container__head top-head top-head--sticky">
            <app-left-drawer-burger/>
            <div class="title">
                Your progress over time
            </div>
        </div>
        <div class="container__content" style="width: 100%">
            <template v-if="!$store.state.auth.user.finished_setup">
                <div style="display: flex; justify-content: center; margin-top: 50px; font-size: 1.5rem;">
                    You haven't finished your initial setup
                </div>
                <div style="display: flex; justify-content: center; margin: 1em 1em 0 1em; font-size: 1rem; font-weight: 500; text-align: center;">
                    Your meal plan & analytics cannot start until you have filled in foods correctly (i.e. earned purple checkmarks) for ALL 7 days!
                </div>
                <div style="margin-top: 25px;justify-content: center; align-items: center; display: flex;">
                    <button-base
                        :text="'Initial setup'"
                        @click="handleNewPlan"
                        style="margin-left: 10px;max-width: 262px;"
                    />
                </div>
            </template>

            <template v-else>
                <div class="progress__graph progress__graph__daily-calories">
                    <div class="progress__graph-container">
                        <div class="progress__daily-calories" :class="{'progress__daily-calories--mobile': this.is_mobile}">
                            <template v-for="(day, key, index) in this.sortedDaysByKeyName(dailyCalories.data)">
                                <chart-calories-daily :sizeCase="sizeCase" :data="day" :maxedValue="maxDailyCaloriesValue"
                                                      :date="key" :index="index"/>
                            </template>
                        </div>
                    </div>
                    <div class="progress__caption-container">
                        <div class="progress__title">
                            Weekly progress
                        </div>
                        <div class="progress__legend">
                            <div class="entry entry--squre your">
                                Your progress
                            </div>

                            <div class="entry entry--squre plan">
                                Planned progress
                            </div>
                        </div>

                    </div>
                </div>

                <div style="display: flex" class="progress__graph">
                    <div >Historical Progress</div>
                    <date-range-input :range="range" @datepickerUpdate="updateDateRange"></date-range-input>
                </div>
                <div class="progress__graph progress__graph-allweeks">
                    <div class="progress__graph-container ">
                        <chart-calories-weekly :chart-data="allWeeksProgress.data"
                                               :options="allWeeksProgress.options"
                                               :styles="allWeeksStyle"
                        />
                    </div>
                    <div class="progress__caption-container">
                        <div class="progress__title">
                            All weeks progress
                        </div>
                        <div class="progress__legend">
                            <div class="entry entry--round your">
                                Your progress
                            </div>

                            <div class="entry entry--round plan">
                                Planned progress
                            </div>
                        </div>

                    </div>
                </div>

                <div class="progress__graph progress__graph__daily-spending">
                    <div class="progress__graph-container">
                        <chart-spending-line :chart-data="allWeeksSpending.data"
                                               :styles="allWeeksStyle"
                        />
                    </div>
                    <div class="progress__caption-container">
                        <div class="progress__title">
                            Your grocery spend over time
                        </div>
                    </div>
                </div>


                <div class="progress__table progress__weekly-stats progress__table-dashboard">
                    <table class="weekly-details-table" :class="{'weekly-details-table__mobile': is_mobile}">
                        <thead>
                        <tr>
                            <th>
                                View
                            </th>
                            <th>
                                Start date
                            </th>
                            <th>
                                End date
                            </th>
                            <th>
                                Weight
                            </th>
                            <th>
                                Goal
                            </th>
                            <th>
                                Net cal
                            </th>
                            <th>
                                Result
                            </th>
                        </tr>

                        </thead>

                        <tbody>
                        <tr v-for="(item, index) in weeklyDetails" :class="{'mobile-tr': is_mobile}">
                            <week-main-td :column="'Week'"
                                          :text="item.linkText"
                                          :is_mobile="is_mobile"
                                          :is_opened="opened.includes(index)"
                                          class="progress__week-title"
                                          @click="handleAccordion(index, item.date)"
                                          style="white-space: nowrap;"/>

                            <template v-if="is_mobile ? (opened.includes(index)) : true">
                                <weekly-td :column="'Start date'" :text="item.start" :is_mobile="is_mobile"/>
                                <weekly-td :column="'End date'" :text="item.end" :is_mobile="is_mobile"/>
                                <weekly-td :column="'Weight'" :text="item.weight" :is_mobile="is_mobile"/>
                                <weekly-td :column="'Goal'" :text="calculateText(item.goal)" :is_mobile="is_mobile"/>
                                <weekly-td :column="'Net cal'" :text="item.netCal" :is_mobile="is_mobile"/>
                                <weekly-td :column="'Result'" :text="item.result" :is_mobile="is_mobile"/>
                            </template>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </template>

        </div>
        <overlay-component v-if="isLoading"/>
    </div>
</template>

<script>
import {mapGetters, mapMutations} from 'vuex';
import ChartCaloriesDaily from "../components/charts/ChartCaloriesDaily";
import ChartCaloriesWeekly from "../components/charts/ChartCaloriesWeekly";
import ChartSpendingDaily from "../components/charts/ChartSpendingDaily";
import ButtonBase from "../../js/components/buttons/ButtonBase";
import AppLeftDrawerBurger from "../components/drawers/left/AppLeftDrawerBurger";
import WeekTd from "../components/planner/progress/weekly-td";
import moment from 'moment'
import OverlayComponent from '../../js/components/overlay/OverlayComponent'
import map from 'lodash/map'
import params from "../../store/modules/params";
import ChartSpendingLine from "../components/charts/ChartSpendingLine";


export default {
    name: "UserProgress",
    components: {
        ChartSpendingLine,
        WeekTd, ChartSpendingDaily, ChartCaloriesWeekly, ChartCaloriesDaily, OverlayComponent, ButtonBase, AppLeftDrawerBurger},
    data: function () {
        return {
            allWeeksSpending: {
                data: {
                    labels:[],
                    datasets: [
                        {
                            data: [],
                            fill: 'undefined',
                            borderColor: '#B27DFF',
                            pointBorderColor: '#CA5FBB',
                            pointHoverBorderWidth: 5,
                            pointHoverRadius: 5
                        }
                    ]
                }
            },
            allWeeksProgress: {
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    spanGaps: false,
                    elements: {
                        line: {
                            tension: 0.000001
                        },
                        point: {
                            radius: 5,
                            pointStyle: 'circle',
                            hitRadius: 5,
                            borderWidth: 5,
                            borderColor: 'rgb(202 95 187)',
                            backgroundColor: 'white',
                            z: 9999
                        }
                    },
                    tooltips: {
                        // Disable the on-canvas tooltip
                        enabled: false,

                        custom: function (tooltipModel) {
                            // Tooltip Element
                            var tooltipEl = document.getElementById('weekly-tooltip');

                            // Create element on first render
                            if (!tooltipEl) {
                                tooltipEl = document.createElement('div');
                                tooltipEl.id = 'weekly-tooltip';
                                tooltipEl.innerHTML = '<table></table>';
                                document.body.appendChild(tooltipEl);
                            }

                            // Hide if no tooltip
                            if (tooltipModel.opacity === 0) {
                                tooltipEl.style.opacity = 0;
                                return;
                            }

                            // Set caret Position
                            tooltipEl.classList.remove('above', 'below', 'no-transform');
                            if (tooltipModel.yAlign) {
                                tooltipEl.classList.add(tooltipModel.yAlign);
                            } else {
                                tooltipEl.classList.add('no-transform');
                            }

                            function getBody(bodyItem) {
                                return bodyItem.lines;
                            }

                            // Set Text
                            if (tooltipModel.body) {
                                var titleLines = tooltipModel.title || [];
                                var bodyLines = tooltipModel.body.map(getBody);

                                var innerHtml = '<thead>';

                                titleLines.forEach(function (title) {
                                    innerHtml += '<tr><th>' + title + '</th></tr>';
                                });
                                innerHtml += '</thead><tbody>';

                                bodyLines.forEach(function (body, i) {
                                    var colors = tooltipModel.labelColors[i];
                                    var style = 'background:' + colors.backgroundColor;
                                    style += '; border-color:' + colors.borderColor;
                                    style += '; border-width: 2px';
                                    var span = '<span style="' + style + '"></span>';
                                    innerHtml += '<tr><td>' + span + body + ' cal.' + '</td></tr>';
                                });
                                innerHtml += '</tbody>';

                                var tableRoot = tooltipEl.querySelector('table');
                                tableRoot.innerHTML = innerHtml;
                            }

                            // `this` will be the overall tooltip
                            var position = this._chart.canvas.getBoundingClientRect();

                            // Display, position, and set styles for fontss
                            tooltipEl.style.opacity = 1;
                            tooltipEl.style.background = tooltipModel.labelColors[0].borderColor;
                            tooltipEl.style.position = 'absolute';


                            // setting tooltip position. top centered
                            tooltipEl.style.left = position.left - (tooltipEl.offsetWidth / 2) + tooltipModel.caretX + 'px';
                            tooltipEl.style.top = position.top - (tooltipEl.offsetHeight) + tooltipModel.caretY + 'px';


                            let dataPoints = tooltipModel.dataPoints
                            let datasetIndex = this._chart.data.datasets.length - 1
                            let datasetMeta = this._chart.getDatasetMeta(datasetIndex)
                            let barr = datasetMeta.data[dataPoints[0].index]._model

                            tooltipEl.style.fontFamily = tooltipModel._bodyFontFamily;
                            tooltipEl.style.fontSize = tooltipModel.bodyFontSize + 'px';
                            tooltipEl.style.fontStyle = tooltipModel._bodyFontStyle;
                            tooltipEl.style.borderRadius = 10 + 'px';
                            tooltipEl.style.padding = tooltipModel.yPadding + 'px ' + tooltipModel.xPadding + 'px';
                            tooltipEl.style.paddingLeft = tooltipModel.xPadding + 10 + 'px';
                            tooltipEl.style.paddingRight = tooltipModel.xPadding + 10 + 'px';
                            tooltipEl.style.pointerEvents = 'none';

                        }
                    },
                    layout: {
                        padding: {
                            left: 35,
                            right: 35,
                            top: 10,
                            bottom: 10,
                        }
                    },
                    legend: {
                        display: false,
                        labels: {
                            fontColor: 'rgb(255, 99, 132)'
                        }
                    },
                    scales: {
                        yAxes: [{
                            // stacked: true
                            gridLines: {
                                display: false,
                            },
                            ticks: {
                                display: false
                            }
                        }],
                        xAxes: [{
                            // stacked: true
                            gridLines: {
                                display: false,
                            },
                            ticks: {
                                display: false
                            }
                        }]
                    },
                },
                data: {
                    labels: [],
                    datasets: [
                        {
                            data: [],
                            fill: 'undefined',
                            borderColor: '#d0b0ff',
                            pointBorderColor: '#FDBDF4',
                            pointHoverBorderWidth: 5,
                            pointHoverRadius: 5

                        },
                        {
                            data: [],
                            fill: 'undefined',
                            borderColor: '#B27DFF',
                            pointBorderColor: '#CA5FBB',
                            pointHoverBorderWidth: 5,
                            pointHoverRadius: 5
                        },
                    ],
                }
            },
            dailyCalories: {
                data: {}
            },
            dailySpending: {
                data: {}
            },
            weeklyDetails: [],
            height: 134,
            opened: [],
            isLoading: false,
            range: {
                start: moment().subtract(2, 'months').toDate(),
                end: new Date(),
            },
        }
    },
    computed: {
        ...mapGetters({
            is_mobile: 'plannerUi/getMobile',
            user: 'auth/user',

        }),


        maxDailyCaloriesValue() {
            let maxed = 0;
            map(this.dailyCalories.data, e => map(e, u => u > maxed ? maxed = u : ''));
            return maxed;
        },

        maxDailySpendingValue() {
            let maxed = 0;
            map(this.dailySpending.data, e => e.spent > maxed ? maxed = e.spent : '');
            return maxed;
        },

        sizeCase() {
            return this.is_mobile ? 'width' : 'height';
        },
        allWeeksStyle() {
            return {
                height: `${this.height}px`,
                width: `100%`,
            }
        },

    },
    async mounted() {
        await this.fetchProgress();
    },
    methods: {
        async updateDateRange(data) {
            this.range = data
            await this.fetchProgress()
        },
        handleNewPlan() {
            if (this.user.finished_setup === false) {
                window.location.href = '/store'
            } else {
                window.location = '/meal-planner/setup';
            }
        },
        sortedDaysByKeyName(unordered) {
            const ordered = {};

            let z = Object.keys(unordered).sort(function(a, b) {
                return moment(a, 'DD-MM-YYYY').diff(moment(b, 'DD-MM-YYYY'))
            }).forEach(function (key) {
                ordered[key] = unordered[key];
            });

            return ordered;
        },
        async fetchProgress() {
            this.isLoading = true;
            try {
                let res = await this.$http.get(`/api/stats/total`, {
                    params: {
                        start: moment(this.range.start).format('DD-MM-YYYY'),
                        end: moment(this.range.end).format('DD-MM-YYYY'),
                    }
                })

                this.weeklyDetails = res.data.stats.allWeeks;

                this.allWeeksProgress.data.labels = [];
                this.allWeeksSpending.data.labels = [];
                this.allWeeksProgress.data.datasets[0].data = [];
                this.allWeeksProgress.data.datasets[1].data = [];
                this.allWeeksSpending.data.datasets[0].data = [];
                for (let i = 0; i < res.data.stats.allWeeks.length; i++) {
                    this.allWeeksProgress.data.labels.push(res.data.stats.allWeeks[i].date);
                    this.allWeeksSpending.data.labels.push(res.data.stats.allWeeks[i].date);
                    this.allWeeksSpending.data.datasets[0].data.push(res.data.stats.allWeeks[i].spent);
                    this.allWeeksProgress.data.datasets[0].data.push(res.data.stats.allWeeks[i].plan);
                    this.allWeeksProgress.data.datasets[1].data.push(res.data.stats.allWeeks[i].fact);
                }
                this.dailyCalories.data = res.data.stats.currentWeek;
                let tempData = {}
                for (let index in res.data.stats.allWeeks) {
                    tempData[res.data.stats.allWeeks[index].date] = res.data.stats.allWeeks[index];
                }
                this.dailySpending.data = tempData
                this.height -= 1;
                this.height += 1;
            } catch (e) {
            }
            this.isLoading = false
        },
        ...mapMutations({
            setSelectedDay: 'planner/setSelectedDay'
        }),
        handleAccordion(index, toDate) {
            if (this.is_mobile) {
                this.opened.indexOf(index) === -1 ? this.opened.push(index) : this.opened.splice(this.opened.indexOf(index));
            } else {

                let date = moment(toDate, 'DD-MM-YYYY').format('YYYY-MM-DD');
                this.setSelectedDay(date);
                this.$router.push({name: 'dashboard.planner.meals'})
            }
        },
        calculateText(goal) {
            let rounded = function (number) {
                return +number.toFixed(2);
            }
            if (goal === 0) {
                return 'Maintain'
            } else if (goal > 0) {
                return '+' + rounded(goal) + ' ' + this.$phrase.weightSm;

            } else if (goal < 0) {
                return ' ' + rounded(goal) + ' ' + this.$phrase.weightSm;
            }
        }
    }

}
</script>

<style lang="scss">

.container__main__slot {
    position: relative;
}

.mobile-tr {
    //padding: 10px;

    &:not(:last-child) {
        /*border-bottom: 5px solid #b27dfe5c;*/
    }

    &:last-child {
        margin-bottom: 0;
    }

    &:nth-child(2n) {
        /*background: #f7f1ff;*/

    }
}


.weekly-details-table {
    width: 100%;

    &__mobile {
        display: flex;
        flex-direction: column;

        thead {
            display: none
        }

        tr {
            display: flex;
            flex-direction: column;
        }

        td {
            display: flex;
        }
    }

    th, td {
        padding: 10px;
        @media screen and (min-width: 1021px) {
            &:first-child {
                padding-left: 18px;
            }
            &:last-child {
                padding-right: 18px;
            }
        }
    }

    th {
        white-space: nowrap;
        font-weight: 500;
        color: rgb(41 45 52 / 0.6);
    }


    thead {
        font-size: 13px;
        text-align: justify;
        font-family: Gilroy;
    }

    tbody {
        font-size: 14px;
        font-family: Gilroy;
    }

    tfoot {
        border-top: 1px solid #DFDFDF;
    }
}


.progress {
    &__week-title {
        cursor: pointer;

        span {
            transition: 0.15s linear;
        }

        :hover {
            color: #df2c65e6;
        }
    }

    &__daily {
        &-calories {
            height: 204px;
            display: flex;
            justify-content: space-around;
            align-items: flex-end;


            .day {
                min-width: 10%;
                height: 100%;
                margin: 0 5px;
                display: flex;
                align-items: flex-end;

                &__entry {
                    border-top-left-radius: 10px;
                    border-top-right-radius: 10px;
                    padding: 5px;
                    min-height: 22px;
                    min-width: 70%;
                    position: relative;
                    transition: height 1s ease-out, width 1s ease-out;

                    .caption {
                        position: absolute;
                        left: 50%;

                        &__day {
                            bottom: 0;
                            transform: translate(-50%, 100%);
                            font-size: 14px;
                            margin-bottom: -5px;
                            color: #292D34;
                        }

                        &__calories {
                            top: 0;
                            font-size: 12px;
                            margin-top: 6px;
                            color: white;
                            white-space: nowrap;
                            transform: translate(-50%, 30%);
                        }
                    }

                    &--planned {
                        background: linear-gradient(199.51deg, #b27dff7d -31.95%, #df2c6582 145.89%);
                        left: -35%;
                        z-index: 1;
                    }

                    &--your {
                        background: linear-gradient(201.34deg, #b27dffe6 -31.95%, #df2c65e6 145.89%);
                        z-index: 4;
                    }
                }
            }

            &--mobile {
                height: unset;
                align-items: flex-start;
                flex-direction: column;

                .day {
                    min-width: 100%;
                    height: 60px;
                    margin: 10px 0;
                    flex-direction: column;
                    align-items: flex-start;
                    position: relative;

                    &:first-child {
                        margin-top: unset;
                    }


                    &__entry {
                        border-top-left-radius: unset;
                        border-bottom-right-radius: 10px;
                        min-width: 42px;
                        min-height: 55%;

                        .caption {
                            position: absolute;
                            font-size: 14px;

                            &__day {
                                left: 0;
                                transform: translate(-100%, 100%);
                                margin-bottom: 6px;
                                margin-left: -6px;
                            }

                            &__calories {
                                right: 0;
                                transform: translate(-50%, 50%);
                                left: unset;
                                margin-top: 3px;
                            }
                        }


                        &--planned {
                            left: unset;
                            bottom: 0;
                            position: absolute;
                            z-index: 1;
                        }

                        &--your {
                            z-index: 2;
                        }

                    }

                }

            }

        }

        &-spending {
            height: 120px;
            display: flex;
            justify-content: space-around;
            align-items: flex-end;


            .day {
                min-width: 7%;
                height: 100%;
                margin: 0 5px;
                display: flex;
                align-items: flex-end;

                &__entry {
                    border-top-left-radius: 10px;
                    border-top-right-radius: 10px;
                    padding: 5px;
                    min-height: 22px;
                    width: 100%;
                    position: relative;
                    background: linear-gradient(207.8deg, #B27DFF -31.95%, #8F23D0 145.89%);
                    transition: height 1s ease-out, width 1s ease-out;

                    .caption {
                        position: absolute;
                        color: white;
                        font-size: 14px;
                        left: 50%;

                        &__spend {
                            bottom: 0;
                            transform: translate(-50%, 0);
                            width: 100%;
                            justify-content: center;
                            display: flex;
                            font-size: 12px;
                            margin-bottom: 5px;
                        }
                    }
                }
            }

            &--mobile {
                height: unset;
                flex-direction: column;
                align-items: flex-start;

                .day {
                    min-width: unset;
                    height: 50px;
                    width: 100%;
                    margin: 10px 0;

                    &:first-child {
                        margin-top: unset;
                    }

                    &__entry {
                        border-top-left-radius: unset;
                        border-bottom-right-radius: 10px;
                        border-top-right-radius: 10px;
                        min-width: 42px;
                        height: 100%;

                        .caption {
                            left: unset;
                            right: 0;

                            &__spend {
                                bottom: 50%;
                                transform: translate(-50%, 50%);
                                width: unset;
                                margin-bottom: 0;
                                margin-right: 3px;
                            }
                        }

                    }
                }

            }
        }

    }

    &__graph {
        margin-top: 40px;
        padding: 0 10px;

        &__daily-calories {
            margin-top: 30px;

            .your {
                &:before {
                    background: linear-gradient(188.57deg, rgb(178 125 255 / 0.9) -31.95%, rgb(223 44 101 / 0.9) 145.89%);
                }
            }

            .plan {
                &:before {
                    background: linear-gradient(188.57deg, rgb(178 125 255 / 0.5) -31.95%, rgb(223 44 101 / 0.5) 145.89%);
                }
            }
        }

        &-allweeks {
            canvas {
                width: 100% !important;
            }

            @media screen and (max-width: 1020px) {
                .progress__graph-container {
                    position: relative;
                    overflow-y: hidden;
                    overflow-x: auto;
                    border-right: 1px solid #C4C4C4;

                    > div {
                        min-width: 500px;
                    }
                }
            }

            .your {
                &:before {
                    background: #CA5FBB;
                }
            }

            .plan {
                &:before {
                    background: #FDBDF4;
                }
            }
        }

        &__daily-spending {
            .progress__caption-container {
                margin-top: 12px;
            }
        }

        &-container {
            border-bottom: 1px solid #C4C4C4;
            @media screen and (max-width: 1020px) {
                border-bottom: unset;
                border-left: 1px solid #C4C4C4;


            }
        }
    }

    &__table {
        margin-top: 64px;
        width: 100%;
        padding: 0 10px;

        table {
            width: 100%;
            text-align: left;

            thead {
                font-size: 12px;
                color: #747e8f;
                font-family: Gilroy;
            }

            tbody {
                font-size: 12px;

                tr {
                    background-color: #f7f2ff;
                    @media screen and (max-width: 1020px) {
                        background: unset;
                        &:nth-child(odd) {
                            td {
                                background-color: #f7f2ff;
                            }
                        }
                    }

                    td {
                        padding: 10px;

                        &:nth-child(1), &:nth-child(4), &:nth-child(6) {
                            color: var(--purple);
                        }

                        @media screen and (max-width: 1020px) {
                            &:nth-child(1) {
                                padding-left: 10px;
                            }
                            padding: 20px 10px 20px 18px;
                        }
                    }
                }
            }

        }

    }


    &__caption-container {
        margin-top: 22px;
        display: flex;
        height: 20px;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }

    &__title {
        font-size: 12px;
        font-weight: 900;
    }

    &__legend {
        display: flex;
        flex-direction: row;
        /*font-size: 12px;*/

        .entry {
            padding-left: 30px;
            margin-left: 16px;
            position: relative;
            font-size: 12px;
            margin-bottom: 0;
            margin-top: 0;

            &--round {
                &:before {
                    border-radius: 50%;
                }
            }

            &--squre {
                &:before {
                    border-radius: 8px;
                }
            }

            &.your {
                &:before {
                    top: 50%;
                    content: "";
                    height: 20px;
                    width: 20px;
                    position: absolute;
                    left: 0;
                    transform: translateY(-50%);
                }
            }

            &.plan {
                &:before {
                    top: 50%;
                    content: "";
                    height: 20px;
                    width: 20px;
                    position: absolute;
                    left: 0;
                    transform: translateY(-50%);
                }
            }
        }


    }

    &__graph-allweeks {

    }
}

</style>
