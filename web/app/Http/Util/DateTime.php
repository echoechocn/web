<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/11/2
 * Time: 下午10:06
 */

namespace App\Http\Util;


class DateTime
{
    protected static $now = null;

    public static function getTimestamp($time = null)
    {
        if(static::$now === null) {
            static::$now = time();
        }

        return $time === null ? static::$now : (is_numeric($time) ? intval($time) : strtotime($time));
    }

    public static function format($time=null, $format = 'Y-m-d H:i:s')
    {
        $ts = static::getTimestamp($time);
        return date($format, $ts);
    }

    public static function formatCn($time=null, $format = 'Y年m月n日 H:i:s')
    {
        return static::format($time, $format);
    }

}