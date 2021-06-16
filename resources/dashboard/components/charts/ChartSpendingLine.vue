<script>
    import { Line, mixins } from 'vue-chartjs'

    const { reactiveProp } = mixins

    export default {
        extends: Line,
        mixins: [reactiveProp],
        props: ['styles'],
        data() {
            let _this = this;
            return {
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
                            var tooltipEl = document.getElementById('weekly-spending-tooltip');

                            // Create element on first render
                            if (!tooltipEl) {
                                tooltipEl = document.createElement('div');
                                tooltipEl.id = 'weekly-spending-tooltip';
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
                                    innerHtml += '<tr><td>Spending: ' + span + _this.phrase.currencySm + body + '</td></tr>';
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
                }
            }
        },
        mounted () {
            this.renderChart(this.chartData, this.options);
        },
        watch: {
            chartData() {
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
