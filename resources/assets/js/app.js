
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

if (document.getElementById('vue-app')) {
  require('./bootstrap');

  window.Vue = require('vue');

  /**
   * Next, we will create a fresh Vue application instance and attach it to
   * the body of the page. From here, you may begin adding components to
   * the application, or feel free to tweak this setup for your needs.
   */

  Vue.component('permissions-component', require('./components/PermissionsComponent.vue'));

  const app = new Vue({
      el: '#vue-app'
  });
}