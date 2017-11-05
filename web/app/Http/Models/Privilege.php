<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/11/3
 * Time: 上午10:18
 */

namespace App\Http\Models;


class Privilege extends BaseModelWithoutTime
{
    protected $table = 'privilege';

    public $incrementing = false;
}