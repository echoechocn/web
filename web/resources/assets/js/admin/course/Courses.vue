<template>
  <div id="app">
    <div class="breadcrumb">
        <div class="breadcrumb-item">课程列表</div>
    </div>
    <hr>

    <div class="operations">
        <a href="/course/course_setting" style="position:absolute; top: 0; right: 0">
            <el-button type="primary" size="small">新增课程</el-button>
        </a>
    </div>

    <el-table
        :data="course_list"
        stripe
        border
        style="width: 100%">
            <el-table-column prop="id" label="ID" width="100"></el-table-column>
            <el-table-column label="标题">
                <template slot-scope="scope">
                    <a :href="'/course/classes/' + scope.row.id">{{ scope.row.title }}</a>
                </template>
            </el-table-column>
            <el-table-column prop="create_time" label="创建日期"></el-table-column>
            <el-table-column label="操作">
                <template slot-scope="scope">
                    <a :href=" '/course/course_setting/' + scope.row.id "><i class="el-icon-edit"></i></a>
                    <a :href=" '/course/content/' + scope.row.id " target="_blank" style="margin-left: 10px"><i class="el-icon-document"></i></a>
                </template>
            </el-table-column>
        </el-table>
    </div>
  </div>
</template>
<script>
export default {
  name: 'app',
  data(){
    return {
        course_list: [

        ]
    }
  },
  mounted(){
      let that = this;
      axios.get("/course/course_list/0")
      .then((response) => {
          that.course_list = response.data;
      }).catch((error) => {
          that.$message({
              message: error.response.data,
              type: 'error'
          });
      });
  }
}
</script>
