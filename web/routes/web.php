<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$www_route = function(){
    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('test', function(){
        return "www hha";
    });

    Route::get('/create_user', 'Home\UserController@createUser');
    Route::get('/login_from_backdoor', 'Home\UserController@login');


    Route::group(['middleware' => ['home_logged']], function(){
        // 课程页面大纲等
        Route::get('/course/{course_id}', 'Home\PageController@courseNew')->where(['course_id' => '[0-9]+']);
        Route::get('/course/{course_id}/c/{chapter_id}/s/{section_id}', 'Home\PageController@courseSection')->where(['course_id' => '[0-9]+']);
        Route::get('/course/{course_id}/c/{chapter_id}/s/{section_id}/p/{page_id}', 'Home\PageController@course')->where(['course_id' => '[0-9]+', 'chapter_id' => '[0-9]+', 'section_id' => '[0-9]+', 'page_id' => '[0-9]+']);
        // 课程页，及内容
        Route::get('/course/{course_id}/indexes', 'Home\CourseController@getIndexes')->where(['course_id' => '[0-9]+']);
        Route::get('/course/{section_id}/pages', 'Home\CourseController@getPages')->where(['section_id' => '[0-9]+']);
        Route::get('/course/{page_id}/content', 'Home\CourseController@getContent')->where(['page_id' => '[0-9]+']);
        Route::post('/course/solution', 'Home\CourseController@postSolution');
        Route::get('/course/user_problem', 'Home\CourseController@requestUserProblemStatus');
        Route::get('/course/history/{problem_id}', 'Home\PageController@history');
        Route::get('/course/h/{problem_id}', 'Home\CourseController@getHistory');



    });
};


Route::domain('www.echoecho.cn')->group($www_route);

Route::domain('test.www.echoecho.cn')->group($www_route);


$admin_route = function(){
    Route::get('/', function(){
        return redirect('/login');
    });
    Route::post('/login', 'Admin\AdminLoginController@login');

    Route::get('/question/question_list', 'Admin\PageController@page');

    Route::get('/captcha', 'Controller@captcha');

    Route::group(['middleware' => ['admin_logged']], function() {
        Route::get('/login', 'Admin\PageController@page');
    });

    Route::get('/test', 'Admin\PageController@page');
    Route::get('/editor', 'Admin\PageController@page');


    // 需要登录的权限
    Route::group(['middleware' => ['admin_login']], function(){
        Route::get('/home', 'Admin\PageController@page');
        Route::get('/main', 'Admin\PageController@page');
    });

    // 需要登录 且 判断接口页面的权限
    Route::group(['middleware' => ['admin_login', 'admin_auth']], function(){
        Route::get('/question/program', 'Admin\PageController@page');
        Route::get('/question/program_edit/{pid?}', 'Admin\PageController@programEdit')->where('pid', '[0-9]+');
        Route::post('/question/program_add', 'Admin\QuestionController@addProgramQuestion');
        Route::get('/question/program_detail', 'Admin\QuestionController@getProgramQuestion');
        Route::get('/question/program/list', 'Admin\QuestionController@program');
        Route::get('/question/program/course/{course_id}/{page?}', 'Admin\QuestionController@programInCourse')->where(['course_id' => '[0-9]+']);


        // course 相关
        // 页面
        Route::get('/course/courses', 'Admin\PageController@page');
        Route::get('/course/course_setting/{id?}', 'Admin\PageController@courseSetting')->where('id', "[0-9]+");  // 课程设置页面
        Route::get('/course/class_setting/{course_id}/{class_id?}', 'Admin\PageController@classSetting')->where(['course_id' => "[0-9]+", 'class_id' => '[0-9]+']);  // 课程设置页面
        Route::get('/course/classes/{course_id}', 'Admin\PageController@classes')->where('course_id', "[0-9]+");
        Route::get('/course/programs/{course_id}', 'Admin\PageController@coursePrograms')->where('course_id' ,"[0-9]+");
        Route::get('/course/program/{course_id}/{program_id?}', 'Admin\PageController@courseProgram')->where(['course_id' => "[0-9]+", 'program_id' => '[0-9]+']);
        Route::get('/course/content/{course_id}', 'Admin\PageController@courseContent')->where('course_id' ,"[0-9]+");

        // 数据
        Route::get('/course/course_list/{page?}', 'Admin\CourseController@courseList')->where('page', "[0-9]+");
        Route::post('/course/add_course', 'Admin\CourseController@addCourse');
        Route::get('/course/course_info/{id}', 'Admin\CourseController@getCourseInfo')->where('id', '[0-9]+');
        Route::get('/course/class_info/{id}', 'Admin\CourseController@getClassInfo')->where('id', '[0-9]+');
        Route::get('/course/class_list/{course_id}/{page?}', 'Admin\CourseController@classList')->where(['course_id' => "[0-9]+", 'page' => '[0-9]+']);
        Route::post('/course/add_class', 'Admin\CourseController@addClass');

        // 课程内容的接口
        Route::get('/course/content/indexes', 'Admin\CourseContentController@getIndexes');
        Route::post('/course/content/add_chapter', 'Admin\CourseContentController@addChapter');
        Route::post('/course/content/add_section', 'Admin\CourseContentController@addSection');
        Route::post('/course/content/update_node', 'Admin\CourseContentController@updateNodeInfo');
        Route::post('/course/content/add_page', 'Admin\CourseContentController@addPage');
        Route::get('/course/content/page_list', 'Admin\CourseContentController@getPageList');
        Route::post('/course/content/update_page', 'Admin\CourseContentController@updatePage');
        Route::post('/course/content/update_image_text', 'Admin\CourseContentController@updateImageText');
        Route::post('/course/content/add_image_text', 'Admin\CourseContentController@newImageText');
        Route::get('/course/content/page_content/{page_id}', 'Admin\CourseContentController@getPageContent')->where(['page_id' => "[0-9]+"]);


    });
};

Route::domain('admin.echoecho.cn')->group($admin_route);
Route::domain('test.admin.echoecho.cn')->group($admin_route);



