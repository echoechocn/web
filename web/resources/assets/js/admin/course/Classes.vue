<style>

</style>

<template>
  <div id="app">
    <div class="breadcrumb">
        <a class="breadcrumb-item" href="/course/courses">课程列表</a>
        <div class="divider">/</div>
        <div class="breadcrumb-item">课程管理</div>
    </div>
    <hr>
    
    <div class="ui-tab">
        <div class="tabs">
            <div class="tab active">班期列表</div>
            <div @click="openPage('/course/programs/' + course_id)" class="tab">编程题</div>
        </div>
        <div class="panel">

            <div class="operations">
                <a :href="'/course/class_setting/' + course_id" style="position:absolute; top: 0; right: 0">
                    <el-button type="primary" size="small">新增班期</el-button>
                </a>
            </div>


            <el-table
            :data="class_list"
            border
            stripe
            style="width: 100%">
                <el-table-column prop="id" label="ID" width="100"></el-table-column>
                <el-table-column label="标题">
                    <template slot-scope="scope">
                        {{ scope.row.title }}
                    </template>
                </el-table-column>
                <el-table-column prop="create_time" label="创建日期"></el-table-column>
                <el-table-column label="操作">
                    <template slot-scope="scope">
                        <a :href=" '/course/class_setting/' + scope.row.course_id + '/' + scope.row.id "><i class="el-icon-edit"></i></a>
                    </template>
                </el-table-column>
            </el-table>

        </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'app',
  data(){
    return {
        course_id: '0',
        class_list: [

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
      var vm = this;
      axios.get('/course/class_list/' + this.course_id, {})
      .then((response) => {
          vm.class_list = response.data;
      }).catch((error) => {
          vm.$message({
              message: '获取失败',
              type: 'error'
          });
      });
  }
}
</script>

