<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/10/22
 * Time: 下午3:44
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;

class MainController extends Controller
{
    public function home(){
        return view('/admin/home');
    }
}