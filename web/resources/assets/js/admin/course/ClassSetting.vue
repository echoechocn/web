<template>
  <div id="app">
    <div class="breadcrumb">
        <a class="breadcrumb-item" href="/course/courses">课程列表</a>
        <div class="divider">/</div>
        <a class="breadcrumb-item" href="javascript:history.go(-1);">班期列表</a>
    </div>
    <hr>
    
    <div class="ui-tab">
        <div class="tabs">
            <div class="tab active">班期列表</div>
            <div @click="openPage('/course/programs/' + formdata.course_id)" class="tab">编程题</div>
        </div>
        <div class="panel">
            <el-form :model="formdata" status-icon ref="form" label-width='70px' size="small" :rules='rule'>
                <el-form-item label="ID">
                    {{ formdata.id > 0 ? formdata.id : "新增班期" }}
                </el-form-item>
                <el-form-item label="标题" prop="title">
                    <el-input v-model="formdata.title"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="save">保存</el-button>
                </el-form-item>
            </el-form>
        </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'app',
  data(){
    return {
        formdata:{
            course_id: '',
            id: 0,
            title: ''
        },
        rule: {
            title:[
                {required: true, message: '请输入标题', trigger:'blur'},
                {mini:2, max: 100, message: '长度在 2 到 100 个字符', trigger:'blur'}
            ]
        },

    }
  },
  methods:{
      openPage(url){
        window.location.href = url;
      },
      save(){
          let vm = this;
          vm.$refs.form.validate((valid) => {
              if (valid){
                  axios.post('/course/add_class', vm.formdata)
                  .then((response) => {
                      vm.$message({
                        message: response.data,
                        type: 'success'
                    });
                  }).catch((error) => {
                    vm.$message({
                        message: error.response.data,
                        type: 'error'
                    });
                  });
              } else{
                  return false;
              }
          });
      }
  },
  mounted(){
      this.formdata.course_id = document.getElementById("course_id").value;      
      this.formdata.id = document.getElementById("class_id").value;
      let vm = this;
      axios.get('/course/class_info/' + vm.formdata.id)
      .then((response) => {
          vm.formdata = response.data
      }).catch((error) => {
          vm.$message({
              message: error.response.data,
              type: 'error'
          });
      });
  }
}
</script>

