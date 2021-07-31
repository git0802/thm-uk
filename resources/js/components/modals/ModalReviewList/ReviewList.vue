<template>
    <div>
        <app-caption class="m-b-16"
                     :title="'Week plan'"
        />
        <div id="print" class="review-list__wrapper">
            <div class="review-list__title-print">
                <h2>Review Your Shopping List for your Meal Plan</h2>
                <div class="review-list__print-click">
                    <div @click="print">
                        <svg fill="var(--primary)" width="30" height="39" version="1.1"
                             xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             x="0px" y="0px"
                             viewBox="0 0 480 480" style="enable-background:new 0 0 480 480;" xml:space="preserve">
                        <path d="M472,160h-40v-56c0-4.418-3.582-8-8-8h-24V8c0-4.418-3.582-8-8-8H88c-4.418,0-8,3.582-8,8v88H56c-4.418,0-8,3.582-8,8v56
                            H8c-4.418,0-8,3.582-8,8v224c0,4.418,3.582,8,8,8h72v72c0,4.418,3.582,8,8,8h304c4.418,0,8-3.582,8-8v-72h72c4.418,0,8-3.582,8-8
                            V168C480,163.582,476.418,160,472,160z M400,112h16v48h-16V112z M96,16h288v144H96V16z M64,112h16v48H64V112z M80,352h-8v-24h8
                            V352z M384,464H96V328h288V464z M464,384h-64v-16h16c4.418,0,8-3.582,8-8v-40c0-4.418-3.582-8-8-8H64c-4.418,0-8,3.582-8,8v40
                            c0,4.418,3.582,8,8,8h16v16H16V176h448V384z M400,352v-24h8v24H400z"/>
                            <rect x="176" y="352" width="176" height="16"/>
                            <rect x="128" y="384" width="224" height="16"/>
                            <rect x="128" y="416" width="176" height="16"/>
                            <rect x="128" y="352" width="32" height="16"/>
                            <path d="M104,192H40c-4.418,0-8,3.582-8,8v32c0,4.418,3.582,8,8,8h64c4.418,0,8-3.582,8-8v-32C112,195.582,108.418,192,104,192z
			                  M96,224H48v-16h48V224z"/>
                            <rect x="128" y="224" width="80" height="16"/>
                            <rect x="224" y="224" width="16" height="16"/>
                    </svg>
                    </div>
                    <span>(or CTRL + I)</span>
                </div>

            </div>
            <div class="review-list__table">
                <ShoppingList :list="list" :planner-id="plannerId"  @update="fetchStoreList" />

            </div>
        </div>
        <div class="review-list__button">

            <button-base
                :text="'GET MY WEEKLY PLAN'"
                @click="saveButton"
            />
        </div>
    </div>
</template>

<script>

import { mapGetters, mapMutations } from "vuex";
import AppCaption from "../../common/AppCaption";
import WeekMainTd from "../../../../dashboard/components/planner/progress/week-main-td"
import WeeklyTd from "../../../../dashboard/components/planner/progress/weekly-td"
import TotalTd from "../../../../dashboard/components/planner/shopping/total-td"
import ButtonBase from "../../buttons/ButtonBase";
import ShoppingList from "../../../../dashboard/components/ShoppingList";

export default {
    name: "ModalReviewList",
    components: {
        ShoppingList,
        AppCaption,
        WeekMainTd,
        WeeklyTd,
        TotalTd,
        ButtonBase
    },
    data() {
        return {
            keysPressed: {},
            list: [],
            is_busy: false,
        }
    },
    computed: {
        ...mapGetters({
            is_mobile: 'plannerUi/getMobile',
            selected_day: 'planner/getSelectedDay',
            current_day: 'planner/getCurrentDay',
            plannerId: 'planner/getPlannerId'
        })
    },
    mounted() {
        document.querySelector('body').addEventListener('keydown', e => {
            this.keysPressed[e.key] = true;
            if (this.keysPressed['Control'] && e.key == 'i') {
                this.print()
            }
        })
        document.querySelector('body').addEventListener('keyup', (e) => {
            this.keysPressed = {};
        });
    },
    async created() {
        await this.fetchStoreList();
    },
    methods: {
        ...mapMutations({
            setSelectedDay: 'planner/setSelectedDay'
        }),
        print() {
            const prtHtml = document.getElementById('print').innerHTML;
            let stylesHtml = '';
            for (const node of [...document.querySelectorAll('link[rel="stylesheet"], style')]) {
                stylesHtml += node.outerHTML;
            }
            const WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
            WinPrint.document.write(`<!DOCTYPE html>
                          <html>
                              <head>
                                ${stylesHtml}
                              </head>
                              <body>
                                ${prtHtml}
                              </body>
                            </html>`);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
        },
        saveButton() {
            window.location.href = '/meal-planner/planner/'
        },
        async fetchStoreList() {
            this.is_busy = true;
            try {
                let response = await this.$http.get(`/api/planner/${this.plannerId}/shopping`)
                this.list = response.data.data;
                this.is_busy = false;
                await this.$store.dispatch('plannerUi/fetchStats', this.$http);
            } catch (e) {
                this.is_busy = false;
            }
        },
    },
}
</script>

<style lang="scss">
@import '../../../../sass/_mixins.scss';

.review-list {
    position: relative;
    padding: 140px 30px;
    background: var(--white);
    min-height: 100vh;

    &__title-print {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    &__print-click {
        text-align: center;
        align-items: center;

        svg {
            cursor: pointer;
        }

        span {
            white-space: nowrap;
            margin-left: 1rem;
            color: #7b7b7b;
            font-weight: 500;
            font-size: 13px;
        }
    }

    &__main {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
    }

    &__wrapper {
        width: 100%;
        max-width: 980px;
        margin: 0 auto;

        h2 {
            @include title-font;
            @media screen and (max-width: 1020px) {
                @include head-24-font;
                margin-bottom: 20px;
            }
        }
    }

    &__message {
        display: flex;
        justify-content: center;
        margin-bottom: 35px;
    }

    &__button {
        margin-top: 4rem;
        display: flex;
        justify-content: center;

        @media screen and (max-width: 600px) {
            flex-direction: column;
        }
    }

    &__table {
        margin-top: 17px;
        width: 100%;
        margin-bottom: 30px;

        table {
            width: 100%;
            text-align: left;

            thead {
                font-size: 13px;
                color: #747e8f;
                font-family: Gilroy;
            }

            tbody {
                font-size: 14px;
                //tr:nth-child(odd) {
                //    background-color: #f7f2ff;
                //}
                tr {
                    border-top: 1px solid #f7f2ff;
                    @media screen and (max-width: 1020px) {
                        background: unset;
                    }
                    td {
                        padding: 10px;


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
}

.weekly-details-table {
    margin-bottom: 30px;
    width: 100%;
    text-align: left;
    border: 1px solid #7F8185;
    border-radius: 8px;
    padding: 9px 10px;
    border-collapse: separate;

    &__mobile {
        display: flex;
        flex-direction: column;

        thead {
            display: none
        }

        tr {
            display: flex;
            flex-direction: column;
            border: unset !important;
        }

        tbody {
            tr:nth-child(odd) td {
                background-color: #f7f2ff;
            }
        }

        td {
            display: flex;
            width: 100% !important;
        }
    }

    th, td {
        padding: 10px;
        width: 10%;

        .bold {
            font-weight: 500;
        }

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
        th, td {
            &:nth-child(1), &:nth-child(2) {
                width: 25%;
            }
        }
    }

    tfoot {
        border-top: 1px solid #DFDFDF;
    }

    span.table-day {
        display: flex;
        font-size: 26px;
    }

    span.eating-time {
        font-size: 18px;
        text-transform: capitalize;
        margin-bottom: 10px;
        margin-top: 20px;
        display: flex;
    }
}

.total {
    color: var(--purple) !important;
}

.table-title {
    color: #7b7b7b;
    font-weight: 500;
    white-space: nowrap;
    @media screen and (max-width: 1020px) {
        display: none!important;
    }

    span {
        width: 10%;
        color: #7F8185;
        font-weight: 400;
        font-size: 14px;

        &:nth-child(1) {
            width: 25%;
        }

        &:nth-child(2) {
            width: 25%;
        }
    }
}


</style>
