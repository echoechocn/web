/**
 * Created by xiang on 17/11/1.
 */
require('../../bootstrap');
import {
  Table,
  TableColumn,
  Input,
  Button
} from 'element-ui'

Vue.use(Button);
Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Input);

import App from './Program.vue'

import 'element-ui/lib/theme-chalk/index.css'
import '../../../sass/admin/question.scss'


const app = new Vue({
  el: "#app",
  render: h => h(App)
});