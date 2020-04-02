import Vue from "vue";
import VueMoment from "vue-moment";
import VueClipboard from "vue-clipboard2";

VueClipboard.config.autoSetContainer = true; // add this line
Vue.use(VueClipboard);
Vue.use(VueMoment);
