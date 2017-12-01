const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
  module: {
    rules: [
      {
        test: /\.css$/,
        loader: "style-loader!css-loader"
      },
    ]
  }
});

mix.js('resources/assets/js/app.js', 'public/js')

  // www
  .js('resources/assets/js/home/course.js', 'public/js/home')
  .js('resources/assets/js/home/history.js', 'public/js/home')



  // admin
   .js('resources/assets/js/admin/change_captcha.js', 'public/js/admin')
   .js('resources/assets/js/admin/login.js', 'public/js/admin')
  .js('resources/assets/js/admin/home.js', 'public/js/admin')
  .js('resources/assets/js/admin/main.js', 'public/js/admin')

  .js('resources/assets/js/admin/test.js', 'public/js/admin')
  .js('resources/assets/js/admin/editor.js', 'public/js/admin')


  .js('resources/assets/js/admin/course/courses.js', 'public/js/admin/course')
  .js('resources/assets/js/admin/course/classes.js', 'public/js/admin/course')
  .js('resources/assets/js/admin/course/course_setting.js', 'public/js/admin/course')
  .js('resources/assets/js/admin/course/programs.js', 'public/js/admin/course')
  .js('resources/assets/js/admin/course/class_setting.js', 'public/js/admin/course')
  .js('resources/assets/js/admin/course/program_edit.js', 'public/js/admin/course')
  .js('resources/assets/js/admin/course/content.js', 'public/js/admin/course')

  .js('resources/assets/js/admin/question/program.js', 'public/js/admin/question')
  .js('resources/assets/js/admin/question/program_edit.js', 'public/js/admin/question');