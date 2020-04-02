import Vue from "vue";
import axios from "axios";
import G from "./global";

const $http = axios.create({
  timeout: 20000,
  baseURL: G.url.base
});

$http.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
Vue.prototype.$axios = axios;
Vue.prototype.$http = $http;

export default $http;
