<template>
  <footer class="anjum-wp-footer">
    <div class="anjum-wp-container">
      <div class="anjum-wp-social-links">
        <ul>
          <li><a href="https://www.facebook.com/tanjim.chowdhurymac/" target="_blank"><span class="dashicons dashicons-facebook"></span></a></li>
          <li><a href="https://www.instagram.com/tanjim_chowdhury74/" target="_blank"><span class="dashicons dashicons-instagram"></span></a></li>
          <li><a href="https://www.linkedin.com/in/its-tanjim-chowdhury/" target="_blank"><span class="dashicons dashicons-linkedin"></span></a></li>
          <li><a href="https://profiles.wordpress.org/tanjimtc71/" target="_blank"><span class="dashicons dashicons-wordpress"></span></a></li>
        </ul>
      </div>
      <div class="anjum-wp-menu">
        <router-link to="/">Table</router-link>
        <router-link to="/graph">Graph</router-link>
        <router-link to="/settings">Settings</router-link>
      </div>
    </div>
    <div class="anjum-wp-copyright">
      <p>&copy;Musarrat Anjum Chowdhuy</p>
    </div>
  </footer>
</template>

<script>

export default {
  name: 'Footer'
}
</script>