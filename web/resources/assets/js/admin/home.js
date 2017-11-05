/**
 * Created by xiang on 17/10/29.
 */
require('../bootstrap');
import ElementUI from 'element-ui'
import App from './Home.vue'

import 'element-ui/lib/theme-chalk/index.css'
import '../../sass/admin/home.scss'

Vue.use(ElementUI);
const app = new Vue({
  el: "#app",
  render: h => h(App)
});