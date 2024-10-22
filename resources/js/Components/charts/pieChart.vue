<template>
  <div>
    <canvas id="anjum-wp-pie-chart-canvas"></canvas>
  </div>
</template>

<script>
import chartMixin from '../../mixins/chartMixin';

export default {
  name: 'PieChart',
  mixins: [chartMixin],

  mounted() {
    let chartOptions = {
      backgroundColor: this.graphData.colors,
      borderColor: this.graphData.colors,
      borderWidth: 1,
      responsive: true,
      maintainAspectRatio: false
    };

    this.renderChart('pie', 'anjum-wp-pie-chart-canvas', chartOptions);
  }
}
</script>
