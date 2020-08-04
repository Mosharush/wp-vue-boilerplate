import Vue from "vue";
import App from "./App.vue";
import "./registerServiceWorker";
import router from "./router";
import apiFetch from "@wordpress/api-fetch";

Vue.config.productionTip = false;

const apiRootURL = "https://localhost/wp-json/";
apiFetch.use(apiFetch.createRootURLMiddleware(apiRootURL));

new Vue({
  router,
  render: (h) => h(App),
}).$mount("#app");
