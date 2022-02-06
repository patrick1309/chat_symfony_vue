import Vue from "vue";
import App from "./components/App.vue";

import "./../styles/app.scss";
import "bootstrap";
import "@fortawesome/fontawesome-free";

const app = new Vue({
	el: "#app",
	render: (h) => h(App),
});
