/**
 * Created by xiang on 17/11/13.
 */
require('../../bootstrap');
import {
  Table,
  TableColumn,
  Input,
  Button,
  Menu,
  Submenu,
  MenuItem,
  MenuItemGroup,
  Dropdown,
  DropdownMenu,
  DropdownItem,
  Dialog,
  Tag,
  Message,
  Select,
  Option
} from 'element-ui'

Vue.use(Button);
Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Input);
Vue.use(Menu);
Vue.use(Submenu);
Vue.use(MenuItem);
Vue.use(MenuItemGroup);
Vue.use(Dropdown);
Vue.use(DropdownMenu);
Vue.use(DropdownItem);
Vue.use(Dialog);
Vue.use(Tag);
Vue.use(Select);
Vue.use(Option);

Vue.prototype.$message = Message;

import App from './Content.vue'

import 'element-ui/lib/theme-chalk/index.css'
import '../../../sass/admin/question.scss'


const app = new Vue({
  el: "#app",
  render: h => h(App)
});