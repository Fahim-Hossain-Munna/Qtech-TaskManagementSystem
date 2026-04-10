import "./bootstrap";
import "../css/app.css";

import piniaPluginPersistedstate from "pinia-plugin-persistedstate";
import { createPinia } from "pinia";

import { createApp } from "vue";
import App from "./App.vue";
import router from "./routes";

const app = createApp(App);
const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);
app.use(pinia);
app.use(router);
app.mount("#app");
