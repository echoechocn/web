/**
 * Created by xiang on 17/11/1.
 */
require('../bootstrap');
import App from './Main.vue'

const app = new Vue({
  el: "#app",
  render: h => h(App)
});