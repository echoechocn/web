<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/11/9
 * Time: 上午10:13
 */

namespace App\Http\Models;


use Illuminate\Support\Facades\Redis;

trait Uuid
{
    public static function generate($offset=null, $key=null)
    {
        $key = $key === null ? '__uuid__' : strval($key);
        $id = Redis::incr($key);
        return intval($id) + ($offset === null ? 0 : intval($offset));
    }
}