@extends('base')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('title', '登录')

@section('top_css')
    <link href="/css/admin/login.css" rel="stylesheet" type="text/css">
@endsection

@section('body')
<div class="container" id="app">
    <div class="bottom-info">
        <h1 style="margin: 50px;"><a href="http://www.echoecho.cn" style="color: white; text-decoration: none">ECHOECHO.CN</a></h1>
    </div>
    <div class="m-form" action="" method="post">
        {{ csrf_field() }}
        <h2 class="form-title">后台登录</h2>

        <div class="form-content">
            <div class="form-field">
                <div class="input">
                    <input name="name" type="text" placeholder="用户名">
                </div>
            </div>

            <div class="form-field input">
                <input name="password" type="password" placeholder="密码">
            </div>

            <div class="form-field">
                <div class="inline input" style="width: 70px">
                    <input name="captcha" type="text" placeholder="验证码">
                </div>
                <img class="change-captcha" v-on:click="change_captcha" v-bind:src="img_src" style="width: 130px; vertical-align: bottom; margin-left: 10px;cursor: pointer">
            </div>

            <div class="form-field" style="margin-top: 10px">
                <button class="primary normal button">登录</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('bottom_js')
<script src="/js/admin/change_captcha.js"></script>
@endsection