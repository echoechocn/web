<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/11/3
 * Time: 下午9:40
 */

namespace App\Http\Models;


class UserCaseOutput extends BaseModelWithoutTime
{
    protected $table = 'user_case_output';

    public $incrementing = false;
}