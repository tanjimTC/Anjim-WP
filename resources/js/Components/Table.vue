<template>
  <div>
  <div class="anjum-task-content-wrapper">
    <div v-if="tableData"  class="anjum-wp-table-wrapper">
      <el-table :data="tableData" stripe style="width: 100%">
        <el-table-column
          v-for="item in tableOptions"
          :key="item.prop"
          :prop="item.prop"
          :label="item.label"
          :width="item.width">
        </el-table-column>
      </el-table>
    </div>
  </div>

  <div class="anjum-wp-box-container anjum-wp-email-wrapper">
    <h3>Emails</h3>
    <ol class="list anjum-wp-email-list" v-if="emailAddresses">
      <li v-for="emailAddress in emailAddresses" :key="emailAddress">{{ emailAddress }}</li>
    </ol>
  </div>

  <div class="anjum-wp-box-container">
    <Footer />
  </div>

  </div>
</template>

<script>

import Footer from './Footer.vue';
export default {
  name: 'Table',

  data() {
    return {
      tableData: [],
      emailAddresses: [],
      tableOptions: [
        { prop: 'id', label: 'ID', width: 200 },
        { prop: 'url', label: 'Url', width: 350 },
        { prop: 'title', label: 'Title', width: 350 },
        { prop: 'pageviews', label: 'Pageviews', width: 200 },
        { prop: 'date', label: 'Date', width: 200 }
      ]
    }
  },
  components: {
    Footer
  },

  methods: {
    getTableData() {
      this.loading = true
      this.$adminGet({
        route: 'retrieve_data',
        type: 'table'
      }).then(response => {
        if (response && response.data) {
          this.tableData = response.data.tableData;
          this.emailAddresses = response.data.emails;
        }
      }).fail(error => {
        this.loading = false;
        this.$showError(error);
      }).always(() => {
        this.loading = false
      });
    },
  },
  mounted() {
    this.getTableData();
  }
}
</script>
