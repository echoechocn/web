<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/10/22
 * Time: 下午4:05
 */

namespace App\Http\Middleware;

use App\Http\Controllers\Admin\AdminLoginController;
use Closure;
use Illuminate\Http\Request;

class AdminLoggedMiddleware
{
    public function handle(Request $request, Closure $next){
        $name = $request->cookie('name', '');
        $time = $request->cookie('login_time', '');
        $sign = $request->cookie('sign', '');
        $salt = $request->cookie('sa', '');

        if ($name == 'yxx'){
            if (crypt($name . $time . AdminLoginController::S_K, $salt) == $sign){
                if (time() - intval($time) < AdminLoginController::expire_time){
                    return redirect('/home');
                }
            }
        }

        return $next($request);
    }
}