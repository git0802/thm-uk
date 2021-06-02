<template>
    <div class="extra-card shadowbox" data-v-step="4">
        <div class="extra-card__head">
            <div class="icon">
                <svg width="16" height="22" viewBox="0 0 16 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.83001 7.79L5.00001 8.456V12H3.00001V7.05H3.01501L8.28301 5.132C8.52701 5.039 8.79301 4.992 9.06501 5.001C9.60851 5.01455 10.1343 5.19711 10.5693 5.52328C11.0042 5.84946 11.3267 6.30305 11.492 6.821C11.678 7.404 11.848 7.798 12.002 8.003C12.4674 8.62362 13.0711 9.12723 13.7651 9.47388C14.459 9.82052 15.2243 10.0007 16 10V12C14.9673 12.0011 13.9472 11.7733 13.0131 11.3329C12.079 10.8925 11.2542 10.2504 10.598 9.453L10.017 12.75L12 14.67V22H10V16.014L7.95001 14.027L7.00301 18.325L0.109009 17.11L0.457009 15.14L5.38101 16.008L6.83001 7.79ZM10.5 4.5C9.96958 4.5 9.46087 4.28929 9.0858 3.91421C8.71072 3.53914 8.50001 3.03043 8.50001 2.5C8.50001 1.96957 8.71072 1.46086 9.0858 1.08579C9.46087 0.710714 9.96958 0.5 10.5 0.5C11.0304 0.5 11.5391 0.710714 11.9142 1.08579C12.2893 1.46086 12.5 1.96957 12.5 2.5C12.5 3.03043 12.2893 3.53914 11.9142 3.91421C11.5391 4.28929 11.0304 4.5 10.5 4.5Z" fill="#292D34"/>
                </svg>


            </div>
            <div class="caption">
                Add Exercise
            </div>
        </div>
        <div class="extra-card__body">
            <span> I burnt </span> <input class="extra-input"  type="number" min="0" v-model="calories"> <span> calories. </span>
        </div>

        <div class="extra-card__button" :class="{loading: loading}" @click="!loading && storeExtraExercise()">
            Confirm
        </div>
        <overlay-component v-if="loading" />
    </div>
</template>

<script>
import moment from 'moment'
import OverlayComponent from "../../../../js/components/overlay/OverlayComponent";

    export default {
        name: "AppExtraExercise",
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
            async storeExtraExercise() {

                this.loading = true;
                try {
                    let res  = await this.$http.patch(` /api/planner/${this.plannerId}/correct`, {
                        type: 'burn',
                        value: this.calories,
                        day: moment(this.selected_day, 'YYYY-MM-DD').format('DD-MM-YYYY'),
                    });
                    this.$emit('change');
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
        display: flex;
        flex-direction: column;
        @media screen and (max-width: 600px) {
            width: 100%;
            flex-direction: column;
            margin-top: 15px;
        }

        &:first-child {
            margin-right: 25px;
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
            .extra-input {
                border-color: #b27dff;
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
            background: var(--purple);
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
