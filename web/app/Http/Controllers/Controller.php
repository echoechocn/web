<?php

namespace App\Http\Controllers;

use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    const PHRASE = "phrase";
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function captcha(Request $request){
        $captcha = new CaptchaBuilder;
        $captcha->build();
        $request->session()->put(self::PHRASE, $captcha->getPhrase());
        return $captcha->inline();
    }
}
