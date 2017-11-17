<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/11/17
 * Time: 下午5:25
 */

namespace App\Http\Models;


class SourceCode extends BaseModelWithoutTime
{
    protected $table = 'source_code';
    protected $primaryKey = 'solution_id';
    public $incrementing = false;
}