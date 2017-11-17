<style>
.left-index {
    width: 200px;
    display: inline-block;
    height: 100%;
    padding-top: 10px;
}

.center-content {
    display: inline-block;
    width: calc(100% - 300px);
    vertical-align: top;
    border: 1px solid #e0e0e0;
    border-radius: 3px;
    min-height: 100px;
    padding: 10px;
}

.right-menu {
    position: fixed;
    width: 75px;
    right: 0;
    top: 0;
    height: 100%;
    padding: 10px 0;
}

.index{
    font-size: 15px;
    width: 100%;
    margin-top: 10px;
}

.index .chapter {    
    cursor: pointer;
    width: 100%;
    position: relative;
}

.index .action{
    position: absolute;
    display: inline-block;
    right: 2px;
    top: 5px;
}

.index .chapter .title {
    padding: 12px 9px;
    width: 160px;
}

.index .chapter .chapter-item{
    border-bottom: 1px solid #eaeaea;   
    border-bottom: 1px solid #eaeaea;
    background-color: #fafafa;
}

.index .chapter .chapter-item:hover {
    background-color: #f6f6f6;
}


.index .section{
    position: relative;
    cursor: pointer;
    font-size: 13px;
    padding: 9px 8px;
    color: #777;
    border-bottom: 1px solid #eaeaea;
    border-bottom: 1px solid #eaeaea;
    background-color: #fefefe;
}
.index .section:hover{
    background-color: #fafafa;
}
.index .section.active{
    background-color: #ddd; 
}

.section-title{
    display: inline-block;
    width: 160px;
}

.el-dropdown-link.m-option{
    display: inline-block;
    width: 30px;
    height: 30px;
    line-height: 30px;
}

.el-button.active{
    color: #409EFF;
    border-color: #c6e2ff;
    background-color: #ecf5ff;
}

.page-content {
    margin-top: 15px;
}

.content-item{
    border-top: 1px solid #e0e0e0;
    padding: 10px 0
}

</style>
<template>
  <div id="app">
      <div class="left-index">
        <el-button @click="new_chapter_visible=true">添加章</el-button>
        <div class="index">
            <div
             v-for="item in indexes"
             :key="item.id"
             class="chapter">
                <div class="chapter-item">
                    <div class="title" @click="toggleSection(item.id)">
                        {{ item.title }}
                    </div>
                    <div class="action">
                        <el-dropdown
                        @command="handleChapterMenu"
                        >
                            <span class="el-dropdown-link m-option">
                                <i class="el-icon-more el-icon--right"></i>
                            </span>
                            <el-dropdown-menu slot="dropdown">
                                <el-dropdown-item :command="{'op': 'add', 'chapter_id': item.id, 'title': '测试title'}">添加节</el-dropdown-item>
                                <el-dropdown-item :command="{'op': 'rename', 'chapter_id': item.id}">重命名</el-dropdown-item>
                                <el-dropdown-item :command="{'op': 'up', 'chapter_id': item.id}">上移章</el-dropdown-item>
                                <el-dropdown-item>删除章</el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>
                    </div>
                </div>

                <div v-show="item.show_section">
                    <div
                    v-for="sec in item.children"
                    :key="sec.id"
                    @click="openSection(item.id, sec.id)"
                    :class="'section' + (sec.id == current_section_id ? ' active' : '') ">

                        <div class="section-item">
                            <div class="section-title">
                                {{sec.title}}
                            </div>

                            <div class="action">
                                <el-dropdown @command="handleSectionMenu">
                                <span class="el-dropdown-link m-option">
                                    <i class="el-icon-more-outline el-icon--right"></i>
                                </span>
                                <el-dropdown-menu slot="dropdown">
                                    <el-dropdown-item :command="{'op': 'up', 'chapter_id': item.id, 'section_id': sec.id}">上移节</el-dropdown-item>
                                    <el-dropdown-item :command="{'op': 'rename', 'chapter_id': item.id, 'section_id': sec.id}">重命名</el-dropdown-item>
                                    <el-dropdown-item>删除节</el-dropdown-item>
                                </el-dropdown-menu>
                                </el-dropdown>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>

      <!-- 编辑区域 -->
      <div class="center-content">
          <div class="pages">
              <span style="color: #777">页码：</span>
              <el-button
                size="small"
                v-for="(item, index) in pages"
                :key="item.id"
                :class="current_page_id == item.id ? 'active' : ''"
                @click="openPage(item.id)"
                >{{ index }}</el-button>

              <el-button size="small" @click="addPage()">新建页</el-button>
          </div>
          <div class="page-content">
              <div class="content-item"
              v-for="(item, index) in current_page"
              :key="item.index"
              >
                <div v-if="item.type==1">
                    <quill-editor :id="'editor' + item.id" v-model="item.content" :options="quillOptions"
                        @change="onContentChange(item.id)"
                        ref="'editor' + item.id">
                    </quill-editor>
                    <el-button :type="item.changed == true ? 'primary': ''" size="small" @click="saveImageText(item.id)" style="margin-top: 5px;">保存（ID：{{ item.id }}）</el-button>
                </div>
                <div v-if="item.type==2">
                    编程题：
                    <el-select
                        v-model="item.id" placeholder="选择问题"
                        @change="updatePage()"
                    >
                        <el-option
                            v-for="item in programs"
                            :key="item.pid"
                            :label="item.title"
                            :value="item.pid"
                        >
                        </el-option>

                    </el-select>
                </div>
              </div>
          </div>
      </div>

      <!-- 操作区域 -->
      <div class="right-menu"
        v-show="current_page_id > 0">
        <div>
            <el-button size="small" @click="addImageText()">图文</el-button>
        </div>
        <div style="margin-top: 10px;">
            <el-button size="small" @click="addProgram()">编程题</el-button>
        </div>
      </div>

    <!-- 新增 -->
    <el-dialog
        title="新建章"
        :visible.sync="new_chapter_visible"
        width="350px"
        >
            <el-input v-model="new_chapter_name" placeholder="章名" ></el-input>
            <span slot="footer" class="dialog-footer">
                <el-button @click="new_chapter_visible=false">取消</el-button>
                <el-button @click="newChapter">确定</el-button>
            </span>
    </el-dialog>
    <el-dialog
        title="新建节"
        :visible.sync="new_section_visible"
        width="350px"
        >
            <el-input v-model="new_section_name" placeholder="节名" ></el-input>
            <span slot="footer" class="dialog-footer">
                <el-button @click="new_section_visible=false">取消</el-button>
                <el-button @click="addSection">确定</el-button>
            </span>
    </el-dialog>

    <!-- 重命名 -->
    <el-dialog
     title="重命名章"
     :visible.sync="rename_chapter_visible"
     width="350px"
     >
        <el-input v-model="rename_chapter_name" placeholder="章名" ></el-input>
        <span slot="footer" class="dialog-footer">
            <el-button @click="rename_chapter_visible=false">取消</el-button>
            <el-button @click="renameChapter">确定</el-button>
        </span>
    </el-dialog>

    <el-dialog
     title="重命名节"
     :visible.sync="rename_section_visible"
     width="350px"
     >
        <el-input v-model="rename_section_name" placeholder="节名" ></el-input>
        <span slot="footer" class="dialog-footer">
            <el-button @click="rename_section_visible=false">取消</el-button>
            <el-button @click="renameSection">确定</el-button>
        </span>
    </el-dialog>

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
          course_id: 0,
          current_chapter_id: 0,
          current_section_id: 0,
          pages:[
               
          ],
          current_page_id: 0,
          current_page:[
            //   {
            //       id: 121,
            //       type: '1',   // 图文
            //       content: 'asdas'
            //   },
            //   {
            //       id: 122,
            //       type: '2',    // 编程题
            //       title: '题目标题'
            //   }
          ],

          // 该课程下面的编程题列表
          programs:[

          ],

          // dialogs
          new_chapter_visible: false,
          new_chapter_name: '',

          new_section_visible: false,
          new_section_chapter_id: 0,
          new_section_name: '',

          rename_chapter_visible: false,
          rename_chapter_name: '',
          rename_chapter_id: 0,

          rename_section_visible: false,
          rename_section_chapter_id: 0,
          rename_section_name: '',
          rename_section_id: 0,


          indexes: [
            // {
            //   id: '0',
            //   title: '问题管理问题管理问题管理',
            //   show_section: true,
            //   children: [
            //     {
            //       id: '1',
            //       title: '编程题列表编程题列表编程'
            //     },
            //   ]
            // }
          ],
          count: 2,

          // 编辑器配置
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
  methods: {
      /* 定义的一些方法 */
      getChapter(chapter_id){
          for(var i = 0; i < this.indexes.length; i ++){
              if (this.indexes[i].id == chapter_id){
                  return this.indexes[i];
              }
          }
          console.warn('未找到章：' + chapter_id);
          return null;
      },

      getSection(chapter_id , section_id){
          var chapter = this.getChapter(chapter_id);
          if (null == chapter){
              return null;
          }
          for(var i = 0; i < chapter.children.length; i ++){
              if(section_id == chapter.children[i].id){
                  return chapter.children[i];
              }
          }
          return null;
      },

      /* 以下是章、节的操作 */
      toggleSection(id){
          for(var i = 0; i < this.indexes.length; i ++){
              if (id == this.indexes[i]['id']){
                  this.indexes[i]['show_section'] = !this.indexes[i]['show_section'];
                  break;
              }
          }
      },
      openSection(chapter_id, section_id){
          if (this.current_chapter_id == chapter_id && this.current_section_id == section_id){
              return true;
          }
          let vm = this;
          axios.get('/course/content/page_list?section_id=' + section_id)
          .then((response) => {
              vm.pages = response.data;
              this.current_chapter_id = chapter_id;
              this.current_section_id = section_id;
              this.current_page_id = 0;
              this.current_page = [];
          }).catch((error) => {
              vm.$message({
                  'message': error.response.data,
                  'type': 'error'
              });
          });
      },
      upSection(chapter_id, section_id){
        // TODO reorder node
        var chapter = this.getChapter(chapter_id);
        if (null == chapter){
            return;
        }
        var children = chapter.children;
        var index = 0;
        for(; index < children.length; index ++){
            if (section_id == children[index].id){
                break;
            }
        }

        if (index == 0 || index == children.length){
            return;
        }

        var l = children.splice(0, index - 1);
        var r = children.splice(index + 1);
        var result = l.concat(children.reverse()).concat(r);

        chapter.children = result;
      },
      upChapter(chapter_id){
        // TODO reoder node
        var index = 0; 
        for(; index < this.indexes.length; index ++){
            if (chapter_id == this.indexes[index].id){
                break;
            }
        }
        if(index == 0 || index == this.indexes.length){
            return;
        }
        var l = this.indexes.splice(0, index - 1);
        var r = this.indexes.splice(index + 1);
        this.indexes = l.concat(this.indexes.reverse()).concat(r);
      },
      newChapter(){
          let vm = this;
          axios.post('/course/content/add_chapter', {
              'course_id': vm.course_id,
              'title': this.new_chapter_name,
          }).then((response) => {
            vm.indexes.push({
              id: response.data.id,
              title: vm.new_chapter_name,
              show_section: 'false',
              children: []
            });

            this.new_chapter_visible = false;
            this.new_chapter_name = '';
          }).catch((error) => {
              vm.$message({
                  message: error.response.data,
                  type: 'error'
              });
          });
      },
      handleChapterMenu(message){
          switch(message.op){
            // 新建节
            case 'add': 
                this.new_section_visible = true;
                this.new_section_chapter_id = message.chapter_id;
                break;
            // 重命名章
            case 'rename':
                var chapter = this.getChapter(message.chapter_id);
                this.rename_chapter_visible = true;
                this.rename_chapter_name = chapter.title;
                this.rename_chapter_id = message.chapter_id;
                break;
            case 'up':
                this.upChapter(message.chapter_id);
                break;
          }
      },
      handleSectionMenu(message){
          switch(message.op){
              // 节重命名
              case 'rename':
                var section = this.getSection(message.chapter_id, message.section_id);
                this.rename_section_name = section.title;
                this.rename_section_chapter_id = message.chapter_id;
                this.rename_section_id = message.section_id
                this.rename_section_visible = true;
                break;
            case 'up':
                this.upSection(message.chapter_id, message.section_id);
                break;
          }
      },
      addSection(){
          // TODO
          let vm = this;
          axios.post('/course/content/add_section', {
              'course_id': vm.course_id,
              'chapter_id': vm.new_section_chapter_id,
              'title': vm.new_section_name
          }).then((response) => {
            var chapter = vm.getChapter(vm.new_section_chapter_id);
            if (null == chapter){
                return false;
            }
            chapter.children.push({
                id: response.id,
                title: vm.new_section_name
            });
            vm.new_section_visible = false;
            vm.new_section_name = '';
          }).catch((error) => {
              vm.$message({
                  message: error.response.data,
                  type: 'error'
              });
          });
      },
      updateNode(id, title, callback){
          var vm = this;
          axios.post('/course/content/update_node', {
              id: id,
              title: title
          }).then((response) => {
              callback();
          }).catch((error) => {
              vm.$message({
                  message: error.response.data,
                  type: 'error'
              });
          });
      },
      renameChapter(){
          var vm = this;
          this.updateNode(this.rename_chapter_id, this.rename_chapter_name, () => {
            var chapter = vm.getChapter(vm.rename_chapter_id);
            chapter.title = vm.rename_chapter_name;
            vm.rename_chapter_visible = false;
          });
      },
      renameSection(section_id, title){
          var vm = this;
          this.updateNode(this.rename_section_id, this.rename_section_name, () => {
            var section = vm.getSection(vm.rename_section_chapter_id, vm.rename_section_id);
            section.title = vm.rename_section_name;
             vm.rename_section_visible = false;
          });
      },
      removeChapter(chapter_id){

      },
      removeSection(){
          
      },

      /* 以下是页与内容的操作 */
      addPage(){
          let vm = this;
          axios.post('/course/content/add_page', {
              "section_id": vm.current_section_id
          }).then((response) => {
              vm.pages.push({
                  'id': response.data.id,
                  'status': 1
              });
          }).catch((error) => {
              vm.$message({
                  message: error.response.data,
                  type: "error"
              });
          });
      },
      onContentChange(content_id){
          for(let i = 0; i < this.current_page.length; i ++){
              if (this.current_page[i].id == content_id){
                  this.current_page[i].changed = true;
              }
          }
      },
      removePage(page_id){

      },
      upPage(page_id){

      },
      openPage(page_id){
          this.current_page_id = page_id;
          var vm = this;
          axios.get('/course/content/page_content/' + page_id)
          .then((response) => {
              let data = response.data;
              for (let i = 0; i < data.length; i++) {
                  data[i].changed = false;
              }
              vm.current_page = data;
          }).catch((error) => {
              vm.$message({
                  message: error.response.data,
                  type: 'error'
              });
          });
      },
      addImageText(){
          var vm = this;
          axios.post('/course/content/add_image_text', {
              "content": ""
          }).then((response) => {
              vm.current_page.push({
                  id: response.data.id,
                  type: 1,
                  content: ''
              });
              // 保存该 page 的结构
              vm.updatePage();
          }).catch((error) => {
              vm.$message({
                  message: error.response.data,
                  type: 'error'
              });
          });
      },
      addProgram(){
          this.current_page.push({
              id: '',
              type: 2,
              title: '未选择题目'
          });
          this.updatePage();
      },
      saveImageText(id){
          // 找到对应图文内容，保存
          let vm = this;
          for(let i = 0; i < vm.current_page.length; i ++){
              if (vm.current_page[i].id == id){
                  axios.post('/course/content/update_image_text', {
                      id : id,
                      content: vm.current_page[i].content
                  }).then((response) => {
                      vm.current_page[i].changed = false;
                      vm.$message({
                          message: '保存成功',
                          type: 'success'
                      });
                  }).catch((error) => {
                      vm.$message({
                          message: error.response.data,
                          type: 'error'
                      });
                  });
                  break;
              }
          }
      },

      updatePage(){
          var vm = this;
          var content = [];
          for(let i = 0; i < vm.current_page.length; i ++){
              content.push({
                  id: vm.current_page[i].id,
                  type: vm.current_page[i].type
              });
          }
          axios.post('/course/content/update_page', {
              'page_id' : vm.current_page_id,
              'content': content
          }).then((response) => {

          })
          .catch((error) => {
              vm.$message({
                  message: error.response.data,
                  type: 'error'
              });
          });
      }
  },
  mounted(){
      this.course_id = document.getElementById("course_id").value;
      let vm = this;
      axios.get('/course/content/indexes?course_id=' + this.course_id)
      .then((response) => {
          vm.indexes = response.data
      }).catch((error) => {
          vm.$message({
              message: error.response.data,
              type: 'error'
          });
      });

      axios.get('/question/program/course/' + this.course_id)
      .then((response) => {
        vm.programs = response.data;
      })
      .catch((error) => {
        alert(error);
      });
  }
}
</script>
