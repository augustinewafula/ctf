import Vue from "vue";
import VueRouter from "vue-router";
import RouterPrefetch from 'vue-router-prefetch'
import axios from 'axios'
import App from "./App";
// TIP: change to import router from "./router/starterRouter"; to start with a clean layout
import router from "./router/index";
import VueGoodTablePlugin from 'vue-good-table';
import 'vue-good-table/dist/vue-good-table.css'
import { Form, HasError, AlertError } from 'vform'

import BlackDashboard from "./plugins/blackDashboard";
import i18n from "./i18n"
import './registerServiceWorker'
import VModal from 'vue-js-modal'
Vue.use(BlackDashboard);
Vue.use(VueRouter);
Vue.use(RouterPrefetch);
Vue.use(VueGoodTablePlugin);
Vue.use(VModal)
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.headers.common['x-access-token'] = localStorage.token
// axios.defaults.baseURL = window.location.origin + '/backend/public/api'
if (process.env.NODE_ENV === 'production') {
  axios.defaults.baseURL = 'https://ctf.com'
} else {
  axios.defaults.baseURL = 'http://localhost/vue/ctf/backend/public/api/'
}

Vue.prototype.$http = axios
window.Form = Form

/* eslint-disable no-new */
new Vue({
  router,
  i18n,
  render: h => h(App)
}).$mount("#app");
