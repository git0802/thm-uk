<template>
    <div class="day" v-tooltip=" {content: this.date + '<br>' + 'Planned: ' + data.plan + ' cal.' + '<br>' + 'Actual: ' + data.fact + ' cal.', trigger: 'click hover focus'}   ">
        <div class="day__entry day__entry--your"  :style="{[this.sizeCase]: (data.fact / (maxedValue / 100)) + '%' }">
            <div class="caption caption__day">
                {{ dayNumber }}
            </div>
            <div class="caption caption__calories" v-case-hide="{case: this.sizeCase, break: 30}">
                {{data.fact}}
            </div>
        </div>
        <div class="day__entry day__entry--planned"
             :style="{[this.sizeCase]: (data.plan / (maxedValue / 100)) + '%' }">
            <div class="caption caption__calories" v-case-hide="{case: this.sizeCase, break: 30}">
                {{data.plan}}
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            sizeCase: {
                type: String,
                required: true
            },
            maxedValue: {
                type: Number,
                required: true
            },
            date: {
                type: String,
                required: true
            },
            data: {
                type: Object,
                required: true,
            },
            index: {
                type: Number,
                required: true
            }

        },
        name: "ChartCaloriesDaily",
        computed: {
            dayNumber() {
                return this.index + 1;
            }
        },
        mounted() {
        }
    }
</script>

<style scoped>

</style>
