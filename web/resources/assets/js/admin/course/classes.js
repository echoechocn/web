require('../../bootstrap');
import {
  Table,
  TableColumn,
  Input,
  Button,
  Form,
  FormItem,
  Tabs,
  TabPane,
  Message,
} from 'element-ui'

Vue.use(Button);
Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Tabs);
Vue.use(TabPane);
Vue.use(Input);
Vue.use(Form);
Vue.use(FormItem);

Vue.prototype.$message = Message

import App from './Classes.vue'

import 'element-ui/lib/theme-chalk/index.css'
import '../../../sass/admin/course.scss'

const app = new Vue({
  el: "#app",
  render: h => h(App)
});