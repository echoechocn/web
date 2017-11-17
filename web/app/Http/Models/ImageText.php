<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/11/8
 * Time: 下午6:41
 */

namespace App\Http\Models;


class ImageText extends BaseModel
{
    protected $table = 'image_text';
    protected $primaryKey = 'id';
    public $incrementing = false;

    public function setContent($content){
        $this->content = htmlentities($content);
    }

    public function getContent(){
        return html_entity_decode($this->content);
    }
}