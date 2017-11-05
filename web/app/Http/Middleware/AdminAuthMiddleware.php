<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/10/22
 * Time: 下午4:06
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuthMiddleware
{
    public function handle(Request $request, Closure $next){
        return $next($request);
    }
}