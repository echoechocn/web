<style>
.app {
    height: 100%;
}
.menu{
    display: inline-block;
    height: 100%;
    width: 200px;
    background-color: rgb(110, 120, 130);
}
.content {
    display: inline-block;
    padding: 0 10px;
    height: 100%;
    width: calc(100% - 229px);
    background-color: #fafafa;
    vertical-align: top;
    overflow: scroll;
}
.current-index {
    color: #555;
    font-size: 14px;
}
.progress {
    width: 100%;
    height: 30px;
    background-color: #e8e8e8;
    border-radius: 3px;
}
.progress-item {
    text-decoration: none;
    padding: 5px 10px;
    line-height: 30px;
    color: #555599;
    border-radius: 3px;
}
.progress-item:hover {
    background-color: #d8d8d8;
}
.progress-item.active {
    background-color: #d8d8d8;
}

.contents {
    margin-top: 10px;
    font-size: 15px;
}

.content .image-text{
    padding: 15px 30px 20px 30px;
}

.content .program {
    background-color: #eaeaea;
    padding: 15px 30px;
}

pre.sample-input, pre.sample-output, pre.ql-syntax {
    background-color: #23241f;
    color: #f8f8f2;
    overflow: visible;
    padding: 5px;
    border-radius: 3px;
}

.cell pre{
    margin: 0;
}

.el-table .error{
    background: #ddaaaa;
  }

.el-table .success {
background: #f0f9eb;
}
</style>

<template>
  <div id="app">
      <div class="menu">
          <el-menu
            background-color="#545c64"
            text-color="#fff"
            active-text-color="#ffd04b"

            :default-active="chapter_id + ''"
          >
              <el-submenu
              v-for="chapter in indexes"
              :key="chapter.id"
              :index="chapter.id"
              >
                <template slot="title">
                    <span slot="title">{{ chapter.title }}</span>
                </template>
                <el-menu-item
                        v-for="section in chapter.children"
                        :key="section.id"
                        :index="section.id">
                    {{ section.title }}
                </el-menu-item>
              </el-submenu>
          </el-menu>
      </div>
      <div class="content">
          <p class="current-index"><span>{{ current_chapter_title }}</span> > <span>{{ current_section_title }}</span></p>
          <div class="progress">
              <span
                v-for="(item, index) in pages"
                :key="item.id"
              >
                <a
                :class="'progress-item' + (page_id == item.id ? ' active': '')"
                :href="'/course/' + course_id + '/c/' + chapter_id + '/s/' + section_id + '/p/' + item.id"
                >{{ index }}</a>

                <span v-if="index < pages.length - 1"> > </span>
              </span>
              
          </div>

          <div class="contents"
            v-if="!loading"
          >
              <div
                v-for="(item, index) in contents"
                :key="item.id"
                :class="(item.type == 1 ? 'ql-snow' : '')"
              >
                <div v-if="item.type == 1" class="ql-editor image-text" v-html="item.content">
                </div>
                <div v-if="item.type == 2" class="program">
                    <h4>问题标题</h4>
                    <div class="question-title">
                        {{item.title}}
                    </div>
                    <h4>问题描述</h4>
                    <div class="description" v-html="item.description">
                    </div>
                    <h4>输入描述</h4>
                    <div class="input-description" v-html="item.input_description">
                    </div>
                    <h4>输出描述</h4>
                    <div class="output-description" v-html="item.output_description">
                    </div>
                    <h4>输入样例</h4>
                    <pre class="sample-input">{{(item.sample_input == "" || item.sample_input == null) ? "（无）" : item.sample_input }}</pre>
                    <h4>输出样例</h4>
                    <pre class="sample-output">{{item.sample_output}}</pre>
                    <h4>提示</h4>
                    <div class="hint" v-html="item.hint"></div>
                    <div>
                        <codemirror
                            v-if="item.show_editor"
                            v-model="item.user_solution.source"
                            :options="editorOption">
                        </codemirror>
                        <el-select
                        v-if="item.show_editor"
                        v-model="item.user_solution.language"
                        placeholder="请选择编程语言"
                        >
                            <el-option
                            v-for="item in languages"
                            :key='item.type'
                            :label="item.name"
                            :value="item.type"
                            >
                            </el-option>
                        </el-select>
                        <el-button @click="submitSolution(item)" v-if="item.show_editor" type="primary" size="small" style="margin-top: 10px">提交</el-button>
                        <el-button type="text" @click="item.show_editor = !item.show_editor">{{ item.show_editor ? '取消' : '提交代码' }}</el-button>
                    </div>
                    <h4>提交结果</h4>
                    <div
                    v-if="item.user_solution.result == -1">
                        未提交
                    </div>
                    <div
                    v-if="item.user_solution.result == 0">
                        获取结果中...
                    </div>
                    <div
                    v-if="item.user_solution.result > 0"
                    >
                        <el-table
                        border
                        :data="item.user_solution.cases"
                        :row-class-name="tableRowClassName"
                        >
                            <el-table-column
                            prop="case_id"
                            label="测试ID"
                            >
                            </el-table-column>

                            <el-table-column
                            label="输入"
                            >
                                <template slot-scope="scope">
                                    <pre>{{scope.row.input}}</pre>
                                </template>
                            </el-table-column>

                            <el-table-column
                            label="期望输出"
                            >
                                <template slot-scope="scope">
                                    <pre>{{scope.row.output}}</pre>
                                </template>
                            </el-table-column>

                            <el-table-column
                            label="程序输出"
                            >
                                <template slot-scope="scope">
                                    <pre>{{scope.row.user_output}}</pre>
                                </template>
                            </el-table-column>

                        </el-table>
                    </div>
                </div>
              </div>
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

export default {
  name: 'app',
  data(){
      return {
          languages:[
              {
                  type: 7,
                  name: 'php'
              },
              {
                  type: 6,
                  name: 'python3'
              }
          ],

          loading: true,
          indexes: [
          ],
          title: '',
          course_id: 0,
          chapter_id: 0,
          section_id: 0,
          page_id: 0,

          current_chapter_title: '',
          current_section_title: '',
          pages: [
          ],
          contents: [
              {
                  id: 121,
                  type: 1,
                  content: '1212'
              },
              {
                  id: 121,
                  type: 2,
                  title: '1212',

              }
          ],

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
      }
  },
  mounted(){
      this.course_id = document.getElementById('course_id').value;
      this.chapter_id = document.getElementById('chapter_id').value;
      this.section_id = document.getElementById('section_id').value;
      this.page_id = document.getElementById('page_id').value;
      let vm = this;
      // 获取章节信息
      axios.get('/course/' + this.course_id + '/indexes')
      .then((response) => {
          vm.indexes = response.data;
          for(let i = 0; i < vm.indexes.length; i ++){
              if (vm.indexes[i].id == vm.chapter_id){
                  vm.current_chapter_title = vm.indexes[i].title;
                  for (let j = 0; j < vm.indexes[i].children.length; j++) {
                      if (vm.indexes[i].children[j].id == vm.section_id){
                          vm.current_section_title = vm.indexes[i].children[j].title;
                          return;
                      }
                  }
              }
          }
      }).catch((error) => {
          vm.$message({
              message: error.response.data,
              type: 'error'
          });
      });

      // 获取页信息及详细内容
      axios.get('/course/' + this.section_id + '/pages')
      .then((response) => {
          vm.pages = response.data;
      }).catch(() => {
          vm.$message({
              message: error.response.data,
              type: 'error'
          });
      });

      // 获取页内容
      axios.get('/course/' + this.page_id + '/content')
      .then((response) => {
          vm.loading = false;
          vm.contents = response.data;

      }).catch((error) => {
          vm.$message({
              message: error.response.data,
              type: 'error'
          });
      });

    // 循环获取未获取到结果的 code
    window.setTimeout(() => {
        vm.getSolutionResult();
    }, 2500);
  },
  methods: {
      getSolutionResult(){
        let vm = this;

        for(var i = 0; i < vm.contents.length; i ++){
            // 如果是编程题
            if (vm.contents[i].type == 2){
                let item = vm.contents[i];
                if (item.user_solution.result == 0){
                    axios.get("/course/user_problem?problem_id=" + item.id)
                    .then((response) => {
                        item.user_solution = response.data
                    })
                    .catch((error) => {
                        console.log(error);
                        // vm.$mesage({
                        //     message: error.response,
                        //     type: 'error'
                        // });
                    });
                }
            }
        }
        window.setTimeout(() => {
            vm.getSolutionResult();
        }, 2500);
      },

      tableRowClassName({row, rouwIndex}){
          if (row['result'] == 4){
              return 'success';
          } else {
              return 'error';
          }
      },
      submitSolution(item){
          var data = {
                problem_id: item.id,
                language: item.user_solution.language,
                source: item.user_solution.source
          }
          let vm = this;
          axios.post('/course/solution', data)
          .then((respose) => {
              vm.$message({
                  message: '提交成功',
                  type: 'success'
              });
              item.show_editor = false;
              item.user_solution.result = 0;
          })
          .catch((error) => {
              vm.$message({
                  message: error.response.data,
                  type: 'error'
              });
          });
      }
  }

}
</script>

