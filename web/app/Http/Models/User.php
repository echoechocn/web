<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/11/17
 * Time: 上午11:16
 */

namespace App\Http\Models;


class User extends BaseModelWithoutTime
{
    protected $table = 'users';
    public $incrementing = false;
    protected $primaryKey = 'user_id';


}