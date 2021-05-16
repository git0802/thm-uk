<template>
    <div class="container__main__slot">
        <overlay-component v-if="is_busy" />
        <div class="container__head top-head top-head--sticky">
            <app-left-drawer-burger/>
            <div class="title">
                Your Shopping List
            </div>
            <c-datepicker v-on:datepickerUpdate="selectDate" :selected_day="selected_shopping_day"/>
        </div>

        <div class="container__content unmaxed" style="width: 100%">
            <div class="container__content__table" :class="{'container__content__table--mobile': is_mobile}">
                <template v-if="(list.length === 0)  && plannerId || (is_current_week && !is_valid)">
                    <div style="display: flex; justify-content: center; margin-top: 50px; font-size: 1.5rem;">
                        Sorry, you don't have the shopping list, yet
                    </div>
                </template>


                <template v-else-if="(list.length === 0) && !plannerId">
                    <div style="display: flex; justify-content: center; margin-top: 50px; font-size: 1.5rem;">
                        Planner and shopping list for that week doesn't exists
                    </div>
                </template>

                <template v-else>
                    <ShoppingList :list="list" :planner-id="plannerId"  @update="fetchStoreList" />
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import ShoppingList from "../../components/ShoppingList";
    import OverlayComponent from "../../../js/components/overlay/OverlayComponent";
    import moment from "moment";

    export default {
        name: "PlannerShoppingList",
        components: {OverlayComponent, ShoppingList},
        props: ['title'],
        data: function() {
            return {
                wind_width: window.innerWidth,
                list: [],
                is_busy: false,
                plannerId: null,
                selected_shopping_day: null,
                is_valid: null,
            }
        },
        mounted: function() {
            this.init();
            moment.updateLocale('en', {
                week: {
                    dow: 1
                }
            });
            window.addEventListener('resize', this.widthResize)
        },
        computed: {
            is_mobile: function () {
                return this.wind_width <= 680;
            },
            is_current_week: function() {
                return moment(this.selected_shopping_day).isSame(new Date(), 'week')
            }
        },
        watch: {
            async plannerId(value) {
                await this.fetchStoreList();
            }
        },
        methods: {
            widthResize: function () {
                this.wind_width = window.innerWidth;
            },
            init() {
                this.$emit('setheadertitle', this.title);
                this.getPlannerId();
                this.selected_shopping_day = moment().format('YYYY-MM-DD')
            },
            async getPlannerId(date) {
                try {
                    let res = await this.$http.get(`/api/planner`, {
                        params: {
                            date: date
                        }
                    });
                    if(res.data.data === null) {
                        this.list = null
                        this.plannerId = null
                    }
                    this.list = []
                    this.is_valid = res.data.data.validation.is_valid
                    this.plannerId = res.data.data.id
                } catch(e) {

                }
            },
            async fetchStoreList() {
                this.is_busy = true;
                if(this.plannerId === null) {
                    this.list = [];
                    this.is_busy = false;
                } else {
                    try {
                        let response = await this.$http.get(`/api/planner/${this.plannerId}/shopping`)
                        this.list = response.data.data
                        this.is_busy = false
                        await this.$store.dispatch('plannerUi/fetchStats', this.$http)
                    } catch (e) {
                        this.is_busy = false
                    }
                }
            },
            async selectDate(date) {
                this.selected_shopping_day = moment(date).format('YYYY-MM-DD 00:00:00')
                await this.getPlannerId(this.selected_shopping_day)
            },
        }
    }
</script>

<style scoped lang="scss">
    .qty {
        margin-left: -6px;
    }

    .mobile-tr {
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.25);
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 5px;
        &:last-child {
            margin-bottom: 0;
        }
        .qty {
            margin-left: auto;
        }
    }

    .thin {
        font-family: Gilroy;
        font-weight: 900;
    }

    .totalize {
        font-family: Gilroy;
        .span {
            color: #DF2C65;
        }

    }
    .container__content__table {
        margin: 0 10px 15px 10px;
        &--mobile {
            margin: unset;
            padding: unset;
        }
    }

    .shopping-table {
        /*border-bottom: 1px solid #DFDFDF;*/
        font-family: Gilroy;
        width: 100%;

        &__mobile {
            display:flex;
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

        td {
            font-weight: 500;
        }
        th {
            white-space: nowrap;
            font-weight: 400;
            opacity: .7;
        }


        thead {
            font-size: 13px;
            text-align: justify;
            th {
                padding: 0 10px 10px 10px;
            }
        }
        tbody {
            font-size: 14px;
            td {
                padding: 10px;
            }
            tr:last-child {
                td {
                    padding: 10px 10px 18px 10px;
                }
            }
            tr:nth-child(even) {
                background: rgb(178 125 255 / .1);
            }
        }
        tfoot {
            border-top: 1px solid #DFDFDF;
            td {
                padding: 18px 10px 10px 10px;
            }
        }
    }

</style>
