<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/11/8
 * Time: 下午6:39
 */

namespace App\Http\Models;


class CourseClass extends BaseModel
{
    protected $table = 'class';
    protected $primaryKey = 'id';
    public $incrementing = false;
}