<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/11/8
 * Time: 下午6:37
 */

namespace App\Http\Models;


class Course extends BaseModel
{
    protected $table = 'course';
    protected $primaryKey = 'id';
    public $incrementing = false;

    public function getDescription(){
        return html_entity_decode($this->description);
    }

    public function setDescription($description){
        $this->description = htmlentities($description);
    }
}