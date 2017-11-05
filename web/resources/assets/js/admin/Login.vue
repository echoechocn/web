<template>
    <div id="app">
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>Admin 登录</span>
            </div>
            <el-form :model="login" status-icon :rules="rule" ref="login" label-width="80px" class="">
                <el-form-item label="账号：" prop="username">
                    <el-input type="text" v-model="login.username" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="密码：" prop="pass">
                    <el-input type="password" v-model="login.pass" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="验证码：" prop="captcha">
                    <el-input type="text" v-model="login.captcha" auto-complete="off" class="captcha"></el-input>
                    <img class="change-captcha" v-on:click="change_captcha" v-bind:src="img_src" style="width: 130px; vertical-align: bottom; margin-left: 10px;cursor: pointer">
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="submit('login')">登录</el-button>
                </el-form-item>
            </el-form>
        </el-card>
    </div>
</template>
<script>
    export default{
      name: 'app',
      data(){
        var checkPassword = (rule, value, callback) => {
          if (!value){
            return callback(new Error('密码不能为空'));
          }
          callback();
        };
        var checkUserName = (rule, value, callback) => {
          if (!value){
            return callback(new Error('账号不能为空'));
          }
          callback();
        };
        var checkCaptcha = (rule, value, callback) => {
          if (!value){
            return callback(new Error('验证码不能为空'));
          }
          callback();
        };
        return {
          img_src: '',
          login: {
            pass: '',
            username: '',
            captcha: '',
          },
          rule: {
            pass: [{
              validator: checkPassword, trigger: 'blur'
            }],
            username:[{
              validator: checkUserName, trigger: 'blur'
            }],
            captcha: [{
              validator: checkCaptcha, trigger: 'blur'
            }]
          }
        };
      },
      mounted() {
        document.title = '登录';
        this.change_captcha();
      },
      methods: {
        submit(formName){
          this.$refs[formName].validate((valid) => {
            if (valid){
              var that = this;
              axios.post('/login',{
                'name': that.login.username,
                'password': that.login.pass,
                'captcha': that.login.captcha
              }).then(function(response){
                window.location.href='/home';
              }).catch(function(error){
                console.log(error.response);
                that.img_src = error.response.data.captcha;
                that.$message({
                  message: error.response.data.message,
                  type: 'error'
                });
              })
            } else{
              return false;
            }
          })
        },
        change_captcha(){
          var v = this;
          axios.get("/captcha")
            .then(function(response){
              v.img_src = response.data;
            })
            .catch(function(error){
              alert(error);
            });
        }
      },
    }
</script>