<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/10/21
 * Time: 下午2:02
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Util\Salt;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    const S_K = 'IAdh8NS8Hd0K13bdm';
    const expire_time = 43200;

    public function show(Request $request){
        $builder = new CaptchaBuilder;
        $builder->build();
        $request->session()->put(self::PHRASE, $builder->getPhrase());

        return view("admin/login", ['src' => $builder->inline()]);
    }

    public function login(Request $request){
        $builder = new CaptchaBuilder($request->session()->get(self::PHRASE));
        if( ! $builder->testPhrase($request->input('captcha'))){
            return $this->loginError($request, '验证码错误', 403);
        }
        if ($request->input('name') == 'yxx' && $request->input('password') == '123'){
            // 更新验证码
            $builder = new CaptchaBuilder;
            $builder->build();
            $request->session()->put(self::PHRASE, $builder->getPhrase());

            // 使用 cookie 维持登录状态
            $name = $request->input('name');
            $now = time();
            $salt = Salt::generate();
            $sign = crypt($name . $now . self::S_K, $salt);

            return response("success")
                ->cookie('name', $name, 1440, '/')
                ->cookie('login_time', $now, 1440, '/')
                ->cookie('sa', $salt, 1440, '/')
                ->cookie('sign', $sign, 1440, '/');
        } else {
            return $this->loginError($request, '账号密码错误', 403);
        }
    }

    private function loginError(Request $request, $message, $code){
        $builder = new CaptchaBuilder;
        $builder->build();
        $request->session()->put(self::PHRASE, $builder->getPhrase());

        return response(json_encode(array(
            'message' => $message,
            'captcha' => $builder->inline()
        )), $code);
    }
}