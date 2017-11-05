<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/11/1
 * Time: 下午4:47
 */

namespace App\Http\Models;


class ProgramProblem extends BaseModelWithoutTime
{
    protected $table = 'problem';
    protected $primaryKey = 'problem_id';


    public function getDescription(){
        return html_entity_decode($this->description);
    }

    public function getInput(){
        return html_entity_decode($this->input);
    }

    public function getOutput(){
        return html_entity_decode($this->output);
    }

    public function getHint(){
        return html_entity_decode($this->hint);
    }
}