<template>
    <div class="day">
        <div class="day__entry" v-tooltip="{content: this.date + '<br>' + 'Spending: ' + '£' +  data.spent, trigger: 'click hover focus'}" :style="{[this.sizeCase]: (data.spent / (maxedValue / 100)) + '%' }">
            <div class="caption caption__spend" v-case-hide="{case: this.sizeCase, break: 30}">
                £{{data.spent.toFixed(1)}}
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ChartSpendingDaily",
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
        }
    }
</script>

<style scoped>

</style>
