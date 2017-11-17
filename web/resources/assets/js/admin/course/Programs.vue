<template>
  <div id='app'>
    <div class="breadcrumb">
        <a class="breadcrumb-item" href="/course/courses">课程列表</a>
        <div class="divider">/</div>
        <div class="breadcrumb-item">课程管理</div>
    </div>
    <hr>
    
    <div class="ui-tab">
        <div class="tabs">
            <div @click="openPage('/course/classes/' + course_id)" class="tab">班期列表</div>
            <div class="tab active">编程题</div>
        </div>
        <div class="panel">
             <div class="operations">
                <a :href="'/course/program/' + course_id" style="position:absolute; top: 0; right: 0">
                    <el-button type="primary" size="small">新增题目</el-button>
                </a>
            </div>

             <el-table
            :data="question_list"
            border
            stripe
            style="width: 100%">
                <el-table-column prop="pid" label="PID" width="100"></el-table-column>
                <el-table-column prop="title" label="标题"></el-table-column>
                <el-table-column prop="ac_count" label="通过数量"></el-table-column>
                <el-table-column prop="create_time" label="创建日期"></el-table-column>
                <el-table-column prop="status" label="状态"></el-table-column>
                <el-table-column label="操作">
                    <template slot-scope="scope">
                        <a :href=" '/course/program/' + course_id + '/' + scope.row.pid "><i class="el-icon-edit"></i></a>
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "app",
  data(){
    return {
        course_id: '0',
        question_list: [

        ]
    }
  },
  methods:{
      openPage(url){
        window.location.href = url;
      }
  },
  mounted(){
      this.course_id = document.getElementById("course_id").value;
      var that = this;
      axios.get('/question/program/course/' + this.course_id)
      .then((response) => {
        that.question_list = response.data;
      })
      .catch((error) => {
        alert(error);
      });
  }
}
</script>
