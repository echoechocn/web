<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/10/28
 * Time: 下午10:39
 */

namespace App\Http\Util;


class Salt
{
    const m = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0',
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
        'u', 'v', 'w', 'x', 'y', 'z',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
        'U', 'V', 'W', 'X', 'Y', 'Z', ];

    public static function generate($length = 8){
        $s = sizeof(self::m);
        $string = '';
        for($i = 0; $i < $length; $i ++){
            $string .= self::m[random_int(0, $s)];
        }
        return $string;
    }
}