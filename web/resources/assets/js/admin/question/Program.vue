<template>
    <div id="app">
        <div class="breadcrumb">
            <div class="breadcrumb-item">问题列表</div>
        </div>
        <hr>

        <div class="operations">
            <a href="/question/program_edit" style="position:absolute; top: 0; right: 0">
                <el-button type="primary" size="small">新增问题</el-button>
            </a>
        </div>

        <el-table
        :data="question_list"
        stripe
        style="width: 100%">
            <el-table-column prop="pid" label="PID" width="100"></el-table-column>
            <el-table-column prop="title" label="标题"></el-table-column>
            <el-table-column prop="ac_count" label="通过数量"></el-table-column>
            <el-table-column prop="create_time" label="创建日期"></el-table-column>
            <el-table-column prop="status" label="状态"></el-table-column>
            <el-table-column label="操作">
                <template slot-scope="scope">
                    <a :href=" '/question/program_edit/' + scope.row.pid "><i class="el-icon-edit"></i></a>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>
<script>
    export default{
      name: 'app',
      data(){
        return {
          question_list: [
          ]
        }
      },
      mounted(){
        var that = this;
        axios.get('/question/program/list')
          .then((response) => {
            that.question_list = response.data;
          })
          .catch((error) => {
            alert(error);
          });
      }
    }
</script>