<template>
  <canvas id="anjum-wp-line-chart-canvas"></canvas>
</template>

<script>
import chartMixin from '../../mixins/chartMixin';
export default {
  name: 'LineChart',
  mixins: [chartMixin],

  mounted() {
    let chartOptions = {
      responsive: true,
      fill: false,
      borderColor: '#208497',
      tension: 0.1
    };

    this.renderChart('line', 'anjum-wp-line-chart-canvas', chartOptions);
  }
}
</script>
