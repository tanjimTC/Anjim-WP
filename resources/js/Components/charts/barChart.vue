<template>
  <canvas id="anjum-wp-bar-chart-canvas"></canvas>
</template>

<script>
import chartMixin from '../../mixins/chartMixin';
export default {
  name: 'BarChart',
  mixins: [chartMixin],

  mounted() {
    let chartOptions = {
      responsive: true,
      backgroundColor: this.graphData.colors,
      borderColor: this.graphData.colors,
      borderWidth: 1
    };

    this.renderChart('bar', 'anjum-wp-bar-chart-canvas', chartOptions);
  }
}
</script>
