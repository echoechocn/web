<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/11/17
 * Time: 下午12:16
 */

namespace App\Http\Middleware;


use Illuminate\Http\Request;

class InCourseMiddleware
{
    public function handle(Request $request, \Closure $next){
        if ($request->session()->has('logged')){
            return $next($request);
        } else {
            return response("未登录");
        }
    }
}