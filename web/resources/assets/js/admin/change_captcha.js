/**
 * Created by xiang on 17/10/21.
 */
require("../bootstrap");

const change_captcha = new Vue({
  el: ".change-captcha",
  data: {
    'img_src' : ""
  },
  created: function(){
    this.change_captcha();
  },
  methods: {
    change_captcha: function(event){
      var v = this;
      axios.get("/captcha")
        .then(function(response){
          v.img_src = response.data;
        })
        .catch(function(error){
          alert(error);
        });
    }
  }
});