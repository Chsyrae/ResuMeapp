// import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { abilitiesPlugin } from '@casl/vue';

import moment from 'moment';
import App from './App.vue';
import router from './router';
import vuetify from './plugins/vuetify';
import ability from './services/ability';
import piniaPluginPersistedState from "pinia-plugin-persistedstate";

const app   = createApp(App);
app.config.globalProperties.$filters = {
    formattedDateTime(date) {
        return moment(date).format('Do MMM YYYY, h:mma');
    },
}
const pinia = createPinia();
pinia.use(piniaPluginPersistedState);

app.use(router);
app.use(vuetify);
app.use(pinia);
app.use(abilitiesPlugin, ability, { useGlobalProperties: true });
app.mount('#app');