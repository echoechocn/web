<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/10/28
 * Time: 上午11:30
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function page(Request $request){
        $path = $request->getPathInfo();
        return view('/admin/app', ['js' => 'js/admin' . $path . '.js', 'data' => []]);
    }

    public function programEdit(Request $request, $pid = 0){
        return view('/admin/app', ['js' => 'js/admin/question/program_edit.js', 'data' => array(
            'current_pid' => $pid
        )]);
    }
}