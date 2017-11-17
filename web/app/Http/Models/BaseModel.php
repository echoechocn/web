<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/11/3
 * Time: 下午9:40
 */

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use Uuid;

    public function getCreateTime(){
        return $this->created_at->format('Y-m-d H:i:s');
    }
}