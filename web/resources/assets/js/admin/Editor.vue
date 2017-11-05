<template>
    <div id="app">
        <button @click="output">输出</button>
        <quill-editor id="inputDescription" v-model="formdata.input_description" :options="quillOptions" ref="input_description"></quill-editor>

        <!-- <quill-editor v-model="content"
                      ref="myQuillEditor"
                      :options="editorOption"
                      @blur="onEditorBlur($event)"
                      @focus="onEditorFocus($event)"
                      @ready="onEditorReady($event)">
        </quill-editor> -->
    </div>
</template>
<script>
  // mount with component(can't work in Nuxt.js/SSR)
  import { quillEditor } from 'vue-quill-editor'

  export default{
      name: 'app',
      components: {
        quillEditor
      },
      data(){
        return {
          content: '<h1>Html For Editor</h1>',
          formdata: {
            input_description: '<h1>lala</h1>'
          },
          editorOption: {
            // some quill options
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
      methods: {
        onEditorBlur(editor) {
          console.log('editor blur!', editor)
        },
        onEditorFocus(editor) {
          console.log('editor focus!', editor)
        },
        onEditorReady(editor) {
          console.log('editor ready!', editor)
        },
        output(){
          console.log(this.formdata.input_description);
        }
      },
      // get the current quill instace object.
      computed: {
        editor() {
          return this.$refs.input_description.quill
        }
      },
      mounted() {
        // you can use current editor object to do something(quill methods)
        console.log('this is current quill instance object', this.editor)
      }
    }
</script>