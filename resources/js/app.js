// import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import router from './router';
import vuetify from './plugins/vuetify';
import piniaPluginPersistedState from "pinia-plugin-persistedstate";

const app   = createApp(App);
const pinia = createPinia();
pinia.use(piniaPluginPersistedState);

app.use(router);
app.use(vuetify);
app.use(pinia);
app.mount('#app');