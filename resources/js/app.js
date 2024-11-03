import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { OhVueIcon, addIcons } from 'oh-vue-icons';
import piniaPluginPersistedState from 'pinia-plugin-persistedstate';
import App from './App.vue';
import router from './router/router';
import * as FaIcons from 'oh-vue-icons/icons/fa';
import * as SiIcons from 'oh-vue-icons/icons/si';
import * as BiIcons from 'oh-vue-icons/icons/bi';
import axios from 'axios';

const app = createApp(App);
const pinia = createPinia();
const Fa = Object.values({ ...FaIcons });
const Si = Object.values({ ...SiIcons });
const Bi = Object.values({ ...BiIcons });
pinia.use(piniaPluginPersistedState);
addIcons(...Fa);
addIcons(...Si);
addIcons(...Bi);

const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
console.log(token);
axios.defaults.headers.common['X-CSRF-TOKEN'] = token; 
axios.defaults.headers.post['Content-Type'] = 'application/json';

app.use(router);
app.use(pinia);
app.component('v-icon', OhVueIcon);
app.mount('#app');