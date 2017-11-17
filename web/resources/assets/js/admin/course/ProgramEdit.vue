<template>
    <div id="app">
        <div class="breadcrumb">
            <a class="breadcrumb-item" href="/course/courses">课程列表</a>
            <div class="divider">/</div>
            <a class="breadcrumb-item" :href=" '/course/programs/' + formdata.course_id">题目列表</a>
            <div class="divider">/</div>
            <div class="breadcrumb-item">编辑题目</div>
        </div>
        <hr>

        <div class="ui-tab">
            <div class="tabs">
                <div @click="openPage('/course/classes/' + course_id)" class="tab">班期列表</div>
                <div class="tab active">编程题</div>
            </div>
            <div class="panel">
                <el-form :model="formdata" status-icon ref="formdata" label-width="130px" size='small' :rules='rule' class="">
                    <el-form-item label="Problem ID：">
                        {{formdata.pid_text}}
                    </el-form-item>
                    <el-form-item label="标题" prop="title">
                        <el-input v-model="formdata.title"></el-input>
                    </el-form-item>
                    <el-form-item label="时间限制" prop="time_limit">
                        <el-input v-model="formdata.time_limit"></el-input> S
                    </el-form-item>
                    <el-form-item label="内存限制" prop="memory_limit">
                        <el-input v-model="formdata.memory_limit"></el-input> MB
                    </el-form-item>
                    <el-form-item label="问题描述">
                        <quill-editor id="mDescription" v-model="formdata.description" :options="quillOptions"
                                    ref="mDescription">
                        </quill-editor>
                    </el-form-item>
                    <el-form-item label="输入描述">
                        <quill-editor id="inputDescription" v-model="formdata.input_description" :options="quillOptions" ref="input_description"></quill-editor>
                    </el-form-item>
                    <el-form-item label="输出描述">
                        <quill-editor id="outputDescription" v-model="formdata.output_description" :options="quillOptions" ref="output_description"></quill-editor>
                    </el-form-item>
                    <el-form-item label="输入样例">
                        <el-input type="textarea" v-model="formdata.sample_input" :autosize="{ minRows: 3, maxRows: 5}"></el-input>
                    </el-form-item>
                    <el-form-item label="输出样例" prop="sample_output">
                        <el-input type="textarea" v-model="formdata.sample_output" :autosize="{ minRows: 3, maxRows: 5}"></el-input>
                    </el-form-item>

                    <div class="test-case" v-for="(test_case, index) in formdata.test_cases" :key="test_case.id">
                        <el-form-item label="公开" style='width: 160px;'>
                            <el-switch v-model="test_case.public"></el-switch>
                        </el-form-item>
                        <el-form-item label="测试输入">
                            <el-input type="textarea" v-model="test_case.input" :autosize="{ minRows: 3, maxRows: 6}"></el-input>
                        </el-form-item>
                        <el-form-item label="测试输出" prop="test_output">
                            <el-input type="textarea" v-model="test_case.output" :autosize="{ minRows: 3, maxRows: 6}"></el-input>
                        </el-form-item>
                        <el-form-item style="width: 100px; margin-left: -70px">
                            <el-button type='danger' plain @click="removeTestCase(index)">删除</el-button>
                        </el-form-item>
                    </div>

                    <el-form-item>
                        <el-button type='primary' plain @click="addTestCase">新增测试用例</el-button>
                    </el-form-item>

                    <el-form-item label="提示">
                        <quill-editor id="hint" v-model="formdata.hint" :options="quillOptions" ref="hint"></quill-editor>
                    </el-form-item>
                    <el-form-item label="特殊判断">
                        <el-switch v-model="formdata.spj"></el-switch>
                    </el-form-item>
                    <el-form-item label="特殊判断代码" v-if="formdata.spj">
                        <codemirror ref="formdata.source"
                                    :code="formdata.source"
                                    :options="editorOption"
                                    @change="onEditorCodeChange">
                        </codemirror>
                    </el-form-item>
                    <el-form-item>
                        <el-button type='primary' @click="submit">保存</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </div>
    </div>
</template>
<script>
  import { quillEditor } from 'vue-quill-editor'

  require('codemirror/addon/selection/active-line.js')
  // styleSelectedText
  require('codemirror/addon/selection/mark-selection.js')
  require('codemirror/addon/search/searchcursor.js')
  // hint
  require('codemirror/addon/hint/show-hint.js')
  require('codemirror/addon/hint/show-hint.css')
  require('codemirror/addon/hint/javascript-hint.js')
  require('codemirror/addon/selection/active-line.js')
  // highlightSelectionMatches
  require('codemirror/addon/scroll/annotatescrollbar.js')
  require('codemirror/addon/search/matchesonscrollbar.js')
  require('codemirror/addon/search/searchcursor.js')
  require('codemirror/addon/search/match-highlighter.js')
  // keyMap
  require('codemirror/mode/clike/clike.js')
  require('codemirror/addon/edit/matchbrackets.js')
  require('codemirror/addon/comment/comment.js')
  require('codemirror/addon/dialog/dialog.js')
  require('codemirror/addon/dialog/dialog.css')
  require('codemirror/addon/search/searchcursor.js')
  require('codemirror/addon/search/search.js')
  require('codemirror/keymap/sublime.js')
  // foldGutter
  require('codemirror/addon/fold/foldgutter.css')
  require('codemirror/addon/fold/brace-fold.js')
  require('codemirror/addon/fold/comment-fold.js')
  require('codemirror/addon/fold/foldcode.js')
  require('codemirror/addon/fold/foldgutter.js')
  require('codemirror/addon/fold/indent-fold.js')
  require('codemirror/addon/fold/markdown-fold.js')
  require('codemirror/addon/fold/xml-fold.js')

  export default{
      name: 'app',
      components: {
        quillEditor
      },
      data(){
        var validTimeLimit = (rule, value, callback) => {
            console.log('at valid time limit:' + value);
            if (!value){
                return callback(new Error('时间限制不能为空'));
            }
            var re = /^[0-9]+$/;
            if (!re.test(value)){
                return callback(new Error('请输入数字值'));
            }
            value = parseInt(value);
            if (value <= 0 || value > 60){
                return callback(new Error('时间限制只能在 0 到 60 秒之间'));
            }
            callback();
        };

        var validMemoryLimit = (rule, value, callback) => {
            if (!value){
                return callback(new Error('内存限制不能为空'));
            }
            var re = /^[0-9]+$/;
            if (!re.test(value)){
                return callback(new Error('请输入数字值'));
            }
            value = parseInt(value);
            if (value <= 0 || value > 256){
                return callback(new Error('内存限制只能在 0 到 256 之间'));
            }
            callback();
        };
        return {
          formdata: {
            course_id: 0,
            pid: 0,
            pid_text: '',
            title: '',
            time_limit: '',
            memory_limit: '',
            description: '',
            input_description: '',
            output_description: '',
            sample_input: '',
            sample_output: '',
            test_cases: [
            
            ],
            hint: '',
            spj: false,
            source: ''
          },
          rule: {
            title:[
                {required: true, message: '请输入问题标题', trigger:'blur'},
                {mini:2, max: 100, message: '长度在 2 到 100 个字符', trigger:'blur'}
            ],
            time_limit:[
                {validator: validTimeLimit, trigger: 'blur'}
            ],
            memory_limit:[
                {validator: validMemoryLimit, trigger: 'blur'}
            ],
            sample_output: [
                {required: true, message: '输出样例不能为空', trigger: 'blur'}
            ]
          },

          editorOption: {
            tabSize: 4,
            styleActiveLine: true,
            lineNumbers: true,
            line: true,
            foldGutter: true,
            styleSelectedText: true,
            gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
            highlightSelectionMatches: { showToken: /\w/, annotateScrollbar: true },
            mode: 'text/javascript',
            // hint.js options
            hintOptions:{
              // 当匹配只有一项的时候是否自动补全
              completeSingle: false
            },
            //快捷键 可提供三种模式 sublime、emacs、vim
            keyMap: "sublime",
            matchBrackets: true,
            showCursorWhenSelecting: true,
            theme: "monokai",
            extraKeys: { "Ctrl": "autocomplete" }
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
        };
      },
      mounted(){
        this.formdata.course_id = document.getElementById('course_id').value;
        let c_id = this.formdata.course_id;
        this.formdata.pid = document.getElementById('program_id').value;
        this.formdata.pid_text = this.formdata.pid == 0 ? "新增问题" : this.formdata.pid;
        // 获取基本数据
        if (this.formdata.pid != 0){
            var that = this;
            axios.get('/question/program_detail', {
                params:{
                    pid: this.formdata.pid
                }
            }).then(function(response){
                response.data.course_id = c_id;
                that.formdata = response.data
            }).catch(function(error){
                that.$message({
                    message: error.response.data,
                    type: 'error'
                });
            });
        }
      },
      // get the current quill instace object.
      computed: {
        descriptionEditor() {
          return this.$refs.mDescription.quill;
        }
      },
      methods: {
        onEditorCodeChange(newCode) {
          this.formdata.source = newCode
        },
        addTestCase(){
          this.formdata.test_cases.push({
              id: '',
              input: '',
              output: '',
              public: true
          });
        },
        removeTestCase(index){
            this.formdata.test_cases.splice(index, 1);
        },
        submit(){
          let that = this;
          this.$refs.formdata.validate((valid) => {
              if (valid){
                  axios.post('/question/program_add', this.formdata)
                    .then((response) => {
                        alert('保存成功')
                    })
                    .catch((error) => {
                        alert(error.response.data);
                        that.$message({
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
<style>
    .cm-s-monokai.CodeMirror{
        line-height: 20px;
    }

    .quill-editor{
        width: 900px;
    }

    .el-textarea {
        width: 900px;
    }

    .test-case .el-form-item {
        display: inline-block;
        width: 370px;
        vertical-align: top;
    }

    .test-case .el-form-item .el-textarea {
        width: 275px;
    }
</style>