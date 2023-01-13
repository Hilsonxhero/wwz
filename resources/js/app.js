import "./bootstrap";

import { createApp } from "vue";
console.log("here");

import app from "./layouts/app.vue";

const ww = createApp(app);

ww.mount("#app");
