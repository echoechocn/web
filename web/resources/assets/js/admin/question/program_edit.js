require('../../bootstrap');
import {
  Table,
  TableColumn,
  Input,
  Button,
  Form,
  FormItem,
  Switch,
  Message,
} from 'element-ui'

Vue.use(Button);
Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Input);
Vue.use(Form);
Vue.use(FormItem);
Vue.use(Switch);

Vue.prototype.$message = Message


let VueCodeMirror = require('vue-codemirror');
// global use
Vue.use(VueCodeMirror);

import App from './ProgramEdit.vue'

import 'element-ui/lib/theme-chalk/index.css'
import '../../../sass/admin/question.scss'


const app = new Vue({
  el: "#app",
  render: h => h(App)
});