<script>
    import { Line, mixins } from 'vue-chartjs'

    const { reactiveProp } = mixins

    export default {
        extends: Line,
        mixins: [reactiveProp],
        props: ['options', 'styles'],
        mounted () {
            this.renderChart(this.chartData, this.options);
        },
        watch: {
            chartData() {
                this.renderChart(this.chartData, this.options);
            },
            options() {
                this.renderChart(this.chartData, this.options);

            },
            styles() {
                this.renderChart(this.chartData, this.options);
            }
        },
    }
</script>

<style scoped>

</style>
