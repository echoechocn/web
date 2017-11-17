<style>
.quill-editor{
    width: 900px;
}
.el-input {
    width: 450px;
}
</style>

<template>
  <div class="app">
    <div class="breadcrumb">
        <a class="breadcrumb-item" href="/course/courses">课程列表</a>
        <div class="divider">/</div>
        <div class="breadcrumb-item">编辑课程</div>
    </div>
    <hr>

    <el-form :model="formdata" status-icon ref="formdata" label-width="130px" size="small" :rules="rule">
        <el-form-item label="ID">
            {{ formdata.id ? formdata.id : "新增课程" }}
        </el-form-item>
        <el-form-item label="标题" prop="title">
            <el-input v-model="formdata.title"></el-input>
        </el-form-item>
        <el-form-item label="描述">
            <quill-editor id="description" v-model="formdata.description" :options="quillOptions" ref="description"></quill-editor>
        </el-form-item>
        <el-form-item>
            <el-button type="primary" @click="save()">保存</el-button>
        </el-form-item>
    </el-form>
  </div>
</template>
<script>
import { quillEditor } from 'vue-quill-editor'

export default {
  name: 'app',
  components: {
    quillEditor
  },
  data(){
    return {
        formdata: {
            id: '',
            title: '',
            description: ''
        },
        rule: {
            title:[
                {required: true, message: '请输入标题', trigger:'blur'},
                {mini:2, max: 100, message: '长度在 2 到 100 个字符', trigger:'blur'}
            ]
        },

        quillOptions: {
            placeholder: '输入内容...',
            modules: {
              toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{'list': 'ordered'}, {'list': 'bullet'}],
                [{'align': ['', 'center', 'right', 'justify']}],
                [{'header': [1, 2, 3, 4, 5, false]}],
                [{'script': 'sub'}, {'script': 'sup'}],
                [{'color': []}, {'background': []}],
                [{'font':[]}],
                ['link', 'image'],
                [{'indent': '+1'}, {'indent': '-1'}]
              ]
            }
        }
    }
  },
  mounted(){
      var id = document.getElementById('id').value;
      if (id > 0){
        let that = this;
        axios.get('/course/course_info/' + id)
        .then((response)=>{
            that.formdata = response.data
        }).catch((error) => {
            that.$message({
                message: error.response.data,
                type: 'error'
            });
        });
      }
  },
  methods: {
      save(){
          let vm = this;
          vm.$refs.formdata.validate((valid) => {
              if (valid){
                  axios.post('/course/add_course', vm.formdata)
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
  }
}
</script>

