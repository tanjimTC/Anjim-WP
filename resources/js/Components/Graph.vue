<template>
  <div v-loading="loading">
    <div class="anjum-wp-chart-options">
      <label for="chartType" class="anjum-wp-chart-options-label">Select Graph Type</label>
      <el-select id="chartType" v-model="selectedChartType">
        <el-option v-for="option in chartOptions" :key="option.value" :label="option.label" :value="option.value"></el-option>
      </el-select>
    </div>

    <div class="anjum-wp-charts" v-if="renderGraph">
      <div class="anjum-wp-chart-box">
        <h2>{{ selectedChartType.label }}</h2>
        <component :is="selectedChartComponent" :graphData="graphData"></component>
      </div>
    </div>

    <div class="anjum-wp-box-container">
      <Footer />
    </div>

  </div>
</template>

<script>
import LineChart from './charts/lineChart.vue';
import BarChart from './charts/barChart.vue';
import PieChart from './charts/pieChart.vue';
import DoughnutChart from './charts/doughnutChart.vue';
import Footer from "./Footer.vue";


export default {
  name: 'Graph',

  data() {
    return {
      loading: false,
      graphData: [],
      renderGraph: false,
      selectedChartType: 'pie',
      chartOptions: [
        { value: 'pie', label: 'Pie Chart' },
        { value: 'bar', label: 'Bar Chart' },
        { value: 'line', label: 'Line Chart' },
        { value: 'doughnut', label: 'Doughnut Chart' }
      ]
    }
  },

  components: {
    Footer,
    LineChart,
    BarChart,
    PieChart,
    DoughnutChart
  },

  methods: {
    getGraphData() {
      this.loading = true;
      this.$adminGet({
        route: 'retrieve_data',
        type: 'graph'
      }).then(response => {
        if(response && response.data) {
          this.graphData = response.data.graphData;
          this.renderGraph = true;
        }
      }).fail(error => {
        this.loading = false;
        this.$showError(error);
      }).always(() => {
        this.loading = false;
      });
    },
  },

  computed: {
    selectedChartComponent() {
      switch (this.selectedChartType) {
        case 'line':
          return LineChart;
        case 'bar':
          return BarChart;
        case 'pie':
          return PieChart;
        case 'doughnut':
          return DoughnutChart;
        default:
          return null;
      }
    }
  },

  mounted() {
    this.getGraphData();
  }
}
</script>
