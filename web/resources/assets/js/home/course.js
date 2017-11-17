/**
 * Created by xiang on 17/11/13.
 */
require('../bootstrap');
import {
  Button,
  Menu,
  Submenu,
  MenuItem,
  MenuItemGroup,
  Message,
  Table,
  TableColumn,
  Select,
  Option,
} from 'element-ui'

Vue.use(Button);
Vue.use(Menu);
Vue.use(Submenu);
Vue.use(MenuItem);
Vue.use(MenuItemGroup);
Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Select);
Vue.use(Option);


Vue.prototype.$message = Message;

let VueCodeMirror = require('vue-codemirror');
// global use
Vue.use(VueCodeMirror);

import App from './Course.vue'

import 'element-ui/lib/theme-chalk/index.css'
import '../../sass/home/base.scss'


const app = new Vue({
  el: "#app",
  render: h => h(App)
});