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
    });
};

Route::domain('admin.echoecho.cn')->group($admin_route);
Route::domain('test.admin.echoecho.cn')->group($admin_route);



