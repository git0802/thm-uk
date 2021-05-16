<template>
    <div class="extra-card shadowbox">
        <div class="extra-card__head">
            <div class="icon">
                <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 0V20H16V12H13V5C13 3.67392 13.5268 2.40215 14.4645 1.46447C15.4021 0.526784 16.6739 0 18 0ZM6 11.9V20H4V11.9C2.87081 11.6691 1.85599 11.0554 1.12714 10.1625C0.398297 9.2697 0.000141065 8.15255 0 7V1H2V8H4V1H6V8H8V1H10V7C9.99986 8.15255 9.6017 9.2697 8.87286 10.1625C8.14401 11.0554 7.12919 11.6691 6 11.9Z" fill="#292D34"/>
                </svg>

            </div>
            <div class="caption">
                Extra Food Consumed
            </div>
        </div>
        <div class="extra-card__body">
            <span> I ate </span> <input class="extra-input"  type="number" min="0" v-model="calories">  <span> more calories. </span>
        </div>

        <div class="extra-card__button" :class="{loading: loading}" @click="!loading && storeExtraCalories()">
            Confirm
        </div>
        <overlay-component v-if="loading" />
    </div>
</template>

<script>
import OverlayComponent from '../../../../js/components/overlay/OverlayComponent'
import moment from 'moment'

    export default {
        name: "AppExtraConsumed",
        components: {OverlayComponent},
        data: function () {
            return {
                calories: 0,
                loading: false,
            }
        },
        computed: {
            plannerId: {
                get() {
                    return this.$store.state.planner.planner_id;
                }
            },
            selected_day() {
                return this.$store.state.planner.selected_day;
            }
        },
        methods: {
            async storeExtraCalories() {

                this.loading = true;
                try {
                    let res  = await this.$http.patch(` /api/planner/${this.plannerId}/correct`, {
                        type: 'ate',
                        value: this.calories,
                        day: moment(this.selected_day, 'YYYY-MM-DD').format('DD-MM-YYYY'),
                    });

                    this.$notify({
                        group: 'planner',
                        title: 'Yay!',
                        type: 'success',
                        text: res.data.message,
                    });

                    await this.$store.dispatch('plannerUi/fetchStats', this.$http);

                    this.loading  = false;
                    this.calories = 0;

                } catch (e) {
                    let errResponse = e.response;
                    Object.values(errResponse.data.errors).forEach(val => {
                        val.forEach(nVal => {
                            this.$notify({
                                group: 'planner',
                                title: 'Error!',
                                type: 'error',
                                text: nVal,
                            });
                        })
                    });
                    this.loading = false;
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    .extra-card {
        position: relative;
        border-radius: 10px;
        width: 50%;
        height: 100%;
        display: flex;
        flex-direction: column;
        @media screen and (max-width: 600px) {
            width: 100%;
            flex-direction: column;
        }

        &:first-child {
            margin-right: 20px;
            @media screen and (max-width: 600px) {
                margin-right: unset;
            }
        }
        &__head{
            padding: 1.26rem 1.26rem 0 1.26rem;
            display: flex;
            flex-direction: row;
            align-items: center;
            position: relative;

            .icon {
                position: absolute;
                padding-right: 0;
            }
            .caption {
                font-size: 20px;
                padding-left: 34px;
            }
        }
        &__body {
            padding: 1.875rem 1.26rem 1.875rem 1.26rem;
            font-size: 16px;
            height: 100%;
            display: flex;
            align-items: center;
            span {
                font-weight: 400;
            }
        }
        &__footer {
            .button {

            }
        }
        &__button {
            width: 100%;
            padding: 10px 6px;
            min-height: 34px;
            background: #929292;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            display: flex;
            justify-content: center;
            color: white;
            font-size: 14px;
            cursor: pointer;
            user-select: none;
            &.loading {
                cursor: unset;
                color: #c5c5c5;
            }
        }
    }

</style>
