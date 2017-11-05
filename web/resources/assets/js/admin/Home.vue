<template>
    <div id="app" >
        <transition name="fade" mode="out-in" appear>
        <div v-if="show">
            <div id="left-menu">
                <el-menu class="admin-menu" @open="handleOpen" @close="handleClose" :collapse="isCollapse">
                    <el-submenu
                            v-for="item in menus"
                            :key="item.index"
                            :index="item.index">
                        <template slot="title">
                            <i class="el-icon-location"></i>
                            <span slot="title">{{ item.title }}</span>
                        </template>
                        <el-menu-item
                                v-for="c_item in item.children"
                                :key="c_item.index"
                                :index="c_item.index"
                                @click="openApp(c_item.index, c_item.title, c_item.url)">
                            {{ c_item.title }}
                        </el-menu-item>
                    </el-submenu>
                </el-menu>
            </div>
            <div id="menu-cover" @click="hideMenu">
            </div>
        </div>
        </transition>

        <div id="right-content">
            <el-button @click="toggleMenu" class="toggle-menu" icon="el-icon-menu"></el-button>

            <el-tabs v-model="topTabs" type="card" closable @tab-remove="removeTab">
                <el-tab-pane
                        v-for="(item, index) in tabs"
                        :key="item.name"
                        :label="item.title"
                        :name="item.name"
                >
                  <iframe class="base-iframe" :src="item.url" frameborder='0' :id="item.app_id"></iframe>
                </el-tab-pane>
            </el-tabs>

            <el-button @click="refresh" class="refresh" icon="el-icon-refresh"></el-button>
        </div>
    </div>
</template>
<script>
    export default{
      name: 'app',
      data(){
        return {
          topTabs: '1',
          tabs: [
            {
              title: '首页',
              name: '1',
              url: '/main',
              app_id: 'index0'
            }
          ],
          tabIndex: 1,
          isCollapse: false,
          show: false,
          width: 0,

          menus: [
            {
              index: '0',
              title: '问题管理',
              children: [
                {
                  index: '1',
                  title: '编程题列表',
                  url: '/question/program'
                },
                {
                  index: '2',
                  title: '选择题列表',
                  url: '/question/choice'
                },
              ]
            }
          ]
        }
      },
      mounted(){
        let that = this;
        document.title = '后台管理系统 - echoecho.cn';

        // 监听窗口高度改变事件
        window.onresize = function(){
          that.resizeContent();
        };

        that.resizeContent();
      },
      methods: {
        resizeContent(){
          let contents = document.getElementsByClassName('el-tabs__content');
          for(let i = 0; i < contents.length; i ++){
            contents[i].style.height = (window.innerHeight - 72) + 'px';
          }
        },
        removeTab(targetName) {
          let tabs = this.tabs;
          let activeName = this.topTabs;
          if (activeName === targetName) {
            tabs.forEach((tab, index) => {
              if (tab.name === targetName) {
                let nextTab = tabs[index + 1] || tabs[index - 1];
                if (nextTab) {
                  activeName = nextTab.name;
                }
              }
            });
          }

          this.topTabs = activeName;
          this.tabs = tabs.filter(tab => tab.name !== targetName);
        },
        openApp(index, title, url){
          let newTabName = ++this.tabIndex + '';
          this.tabs.push({
            title: title,
            name: newTabName,
            url: url,
            app_id: 'index' + index
          });
          this.topTabs = newTabName;
          this.show = !this.show;
        },
        refresh(){
          let tabs = this.tabs;
          let activeName = this.topTabs;
          let c_app_id = 0;
          tabs.forEach((tab, index) => {
            if (tab.name === activeName){
              c_app_id = tab.app_id;
            }
          });
          document.getElementById(c_app_id).contentWindow.location.reload(true);
        },
        toggleMenu(){
          this.show = !this.show;
        },
        handleOpen(key, keyPath) {
          console.log(key, keyPath);
        },
        handleClose(key, keyPath) {
          console.log(key, keyPath);
        },
        hideMenu(){
          this.show = false;
        }
      }
    }
</script>