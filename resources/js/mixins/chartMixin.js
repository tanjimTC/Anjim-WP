import Chart from "chart.js/auto";
export default {
    props: ['graphData'],

    methods: {
        renderChart(chartType, canvasId, chartOptions) {
            let labels = this.graphData.labels;
            let values = this.graphData.values;

            let chartData = {
                labels: labels,
                datasets: [{
                    label: 'Anjum WP Task App Chart',
                    data: values,
                }],
            };

            let canvas = document.getElementById(canvasId).getContext('2d');
            this.chart = new Chart(canvas, {
                type: chartType,
                data: chartData,
                options: chartOptions
            });
        }
    }
}
