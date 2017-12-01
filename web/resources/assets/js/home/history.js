/**
 * Created by xiang on 17/11/13.
 */
require('../bootstrap');


let VueCodeMirror = require('vue-codemirror');
// global use
Vue.use(VueCodeMirror);

import App from './History.vue'

import 'element-ui/lib/theme-chalk/index.css'
import '../../sass/home/base.scss'


const app = new Vue({
  el: "#app",
  render: h => h(App)
});