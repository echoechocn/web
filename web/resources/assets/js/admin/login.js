/**
 * Created by xiang on 17/10/27.
 */
require('../bootstrap');


import App from './Login.vue';
import ElementUI from 'element-ui';

import '../../sass/admin/login.scss'
import 'element-ui/lib/theme-chalk/index.css'

Vue.use(ElementUI);

const app = new Vue({
  el: '#app',
  render: h => h(App)
});