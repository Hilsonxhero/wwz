import "./bootstrap";

import { createApp } from "vue";

import app from "./layouts/app.vue";

const init = createApp(app);

init.mount("#app");
