import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import '@mdi/font/css/materialdesignicons.css';
import axios from 'axios';

window.axios = axios;
window.axios.defaults.baseURL = '/api';

const vuetify = createVuetify({
  components,
  directives,
});

createApp(App).use(vuetify).use(router).mount('#app');