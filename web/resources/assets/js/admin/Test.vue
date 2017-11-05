<template>
    <div id="app">
        <!--<codemirror v-model="code" :options="editorOption"></codemirror>-->
        <button @click="output">输出</button>
        <!-- or to manually control the datasynchronization（或者手动控制数据流，需要像这样手动监听changed事件） -->
        <codemirror ref="myEditor"
                    :code="code"
                    :options="editorOption"
                    @ready="onEditorReady"
                    @focus="onEditorFocus"
                    @change="onEditorCodeChange">
        </codemirror>
    </div>
</template>
<script>

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
      data(){
        return {
          code: 'const a = 10',
          editorOption: {
            tabSize: 4,
            styleActiveLine: false,
            lineNumbers: false,
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
          }
        }
      },

      methods: {
        onEditorReady(editor) {
        },
        onEditorFocus(editor) {
        },
        onEditorCodeChange(newCode) {
          this.code = newCode
        },
        output(){
          console.log(this.code)
        }
      },

      computed: {
        editor() {
          return this.$refs.myEditor.editor
        }
      },

      mounted() {
        setTimeout(() => {
          this.editorOption.lineNumbers = true;
          this.editorOption.styleActiveLine = true;
        }, 3000)
      }
    }
</script>