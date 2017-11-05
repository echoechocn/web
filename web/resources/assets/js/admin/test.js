require('../bootstrap');

let VueCodeMirror = require('vue-codemirror');

// global use
Vue.use(VueCodeMirror);


import App from './Test.vue'

const app = new Vue({
  el: "#app",
  render: h => h(App)
});