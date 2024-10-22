<template>
    <div id="anjum-wp-task_app">
        <div class="anjum-wp-task_main_nav">
            <div class="anjum-wp-navbar">
                <router-link v-for="menuItem in menus" :key="menuItem.route" active-class="ninja-tab-active" exact :class="['ninja-tab']" :to="{ name: menuItem.route }">
                    {{ menuItem.title }}
                </router-link>
            </div>

        </div>
       <router-view></router-view>
    </div>
</template>

<script>
  export default {
      name: 'AdminApp',
      data() {
          return {
              menus: []
          }
      },
      methods: {
          loadMenu() {
              this.menus = this.applyFilters('anjum_wp_task_top_level_menu', [
                  {
                      route: 'table',
                      title: 'Table'
                  },
                  {
                      route: 'graph',
                      title: 'Graph'
                  },
                  {
                      route: 'settings',
                      title: 'Settings'
                  }
              ])
          }
      },
      mounted() {
          this.loadMenu();
      }
  }
</script>
