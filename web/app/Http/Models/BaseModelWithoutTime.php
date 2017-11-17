<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/11/1
 * Time: 下午4:46
 */

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class BaseModelWithoutTime extends Model
{
    use Uuid;

    public $timestamps = false;

}