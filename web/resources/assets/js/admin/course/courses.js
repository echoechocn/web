/**
 * Created by xiang on 17/11/8.
 */
require('../../bootstrap');
import {
    Table,
    TableColumn,
    Input,
    Button,
    Message
} from 'element-ui'

Vue.use(Button);
Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Input);

import App from './Courses.vue'
Vue.prototype.$message = Message;


import 'element-ui/lib/theme-chalk/index.css'

const app = new Vue({
    el: "#app",
    render: h => h(App)
});