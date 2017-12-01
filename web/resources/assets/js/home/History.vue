<style>
#app {
    padding: 10px 50px;
}
.info {
    font-size: 14px;
}
pre {
    background-color: #333;
    color: #cdd;
    padding: 10px;
    border-radius: 3px;
}
</style>

<template>
  <div id="app">
      <h3>问题ID：{{problem_id}} {{problem.title}}</h3>

      <div
      v-for="item in problem.history"
      :key="item.solution_id"
      >
      <div class="info">
        <span>ID：{{item.solution_id}}</span>
        <span>语言：{{item.language}}</span>
        <span>状态：{{item.status}}</span>
        <span>提交时间：{{item.create_time}}</span>
      </div>
        
        <pre>{{item.code}}</pre>
      </div>
  </div>
</template>
<script>
export default {
  name: 'app',
  data() {
      return {
          problem_id: 0,

          problem:{
              title: '',
              history:[
                //   {
                //       solution_id: 12,
                //       code: "12\n1212\n1212\n1212\n1212\n121",
                //       language: 1,
                //       status: 4,
                //       create_time: '20170'
                //   }
              ]
          }
      }
  },
  mounted(){
      this.problem_id = document.getElementById("problem_id").value;
      let vm = this;
      // 获取数据
      axios.get("/course/h/" + this.problem_id)
      .then((response) => {
          vm.problem = response.data;
      })
      .catch((error) => {
          alert("error");
          console.log(error);
      });
  }
}
</script>

