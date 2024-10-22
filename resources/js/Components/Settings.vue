<template>
  <div>
  <div class="anjum-task-content-wrapper anjum-task-setting-container" v-loading="loading">
    <h2 class="anjum-wp-task-title anjum-task-text-center">Settings</h2>
    <el-form v-if="settings">
      <el-form-item class="anjum-wp-input-wrapper" >
        <div class="anjum-wp-input-fields">
          <label class="anjum-wp-label">
            Rows to display
          </label>
          <el-input class="anjum-wp-input" type="number" v-model="settings.rowsToShow" min="1" max="5" />
        </div>
      </el-form-item>

      <el-form-item class="anjum-wp-input-wrapper" >
        <div class="anjum-wp-input-fields">
          <label class="anjum-wp-label">
            Date Format
          </label>
          <el-radio-group v-model="settings.dateFormat">
              <el-radio label="human_readable">Human Readable</el-radio>
              <el-radio label="unix">Unix</el-radio>
          </el-radio-group>
        </div>
      </el-form-item>

      <el-form-item class="">
        <div class="anjum-wp-input-fields">
          <label class="anjum-wp-label">Email Addresses:</label>
          <div class="anjum-wp-emails">
            <div class="anjum-wp-email-box" v-for="(email, index) in settings.emails" :key="index">
              <el-input type="text" v-model="settings.emails[index]" />
              <el-button type="danger" icon="el-icon-delete" circle @click="removeEmail(index)" :disabled="settings.emails.length <= 1"></el-button>
            </div>
            <el-button type="primary" @click="addEmail" :disabled="settings.emails.length >= 5">Add Email</el-button>
          </div>
        </div>
      </el-form-item>

      <el-button type="primary" @click="saveSettings">Save Settings</el-button>
      <el-button type="danger" @click="clearCache">Clear Cache</el-button>

    </el-form>
  </div>

  <div class="anjum-wp-box-container">
    <Footer />
  </div>
  </div>
</template>

<script>
import Footer from './Footer.vue';
export default {
  name: 'Settings',
  data() {
    return {
      loading: false,
      settings: false,
    };
  },
  components: {
    Footer,
  },
  methods: {
    addEmail() {
      if (this.settings.emails.length < 5) {
        this.settings.emails.push('');
      }
    },
    removeEmail(index) {
      this.settings.emails.splice(index, 1);
    },
    getSettings() {
      this.loading = true;
      this.$adminPost({
        route: 'retrieve_settings',
      })
          .then((response) => {
            if (response && response.success) {
              this.settings = response.data.settings;
            }
          })
          .fail((error) => {
            this.loading = false;
            if (error.responseJSON && error.responseJSON.data) {
              let errorMessage = error.responseJSON.data.message;
              this.$notify({
                message: errorMessage,
                type: 'error',
                offset: 100,
              });
            } else {
              console.log(error);
              this.$notify(error);
            }
          })
          .always(() => {
            this.loading = false;
          });
    },

    saveSettings() {
      this.$adminPost({
        route: 'update_settings',
        settings: JSON.stringify(this.settings),
      })
          .then((response) => {
            if (response && response.success) {
              this.$notify({
                message: response.data.message,
                type: 'success',
                offset: 100,
              });
              this.getSettings();
            }
          })
          .fail((error) => {
            this.loading = false;
            if (error.responseJSON && error.responseJSON.data) {
              let errorMessage = error.responseJSON.data.message;
              this.$notify({
                message: errorMessage,
                type: 'error',
                offset: 100,
              });
            } else {
              console.log(error);
              this.$notify(error);
            }
          })
          .always(() => {
            this.loading = false;
          });
    },

    clearCache() {
      this.$adminPost({
        route: 'clear_cache',
      })
          .then((response) => {
            if (response && response.success) {
              this.$notify({
                message: response.data.message,
                type: 'success',
                offset: 100,
              });
            }
          })
          .fail((error) => {
            this.loading = false;
            if (error.responseJSON && error.responseJSON.data) {
              let errorMessage = error.responseJSON.data.message;
              this.$notify({
                message: errorMessage,
                type: 'error',
                offset: 100,
              });
            } else {
              console.log(error);
              this.$notify(error);
            }
          })
          .always(() => {
            this.loading = false;
          });
    },
  },
  mounted() {
    this.getSettings();
  },
};
</script>
