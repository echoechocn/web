<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/11/8
 * Time: 下午6:41
 */

namespace App\Http\Models;


class NodeInfo extends BaseModel
{
    protected $table = 'node_info';
    protected $primaryKey = 'id';
    public $incrementing = false;

    const TYPE_CHAPTER = 1;
    const TYPE_SECTION = 2;
    const TYPE_PAGE = 3;

    const CONTENT_IMAGE_TEXT = 1;
    const CONTENT_PROGRAM = 2;

    public static function getMaxChapterListorder($course_id){
        return self::where('course_id', $course_id)->max('listorder');
    }

    public static function getMaxSectionListorder($course_id, $chapter_id){
        return self::where(array(
            'course_id' => $course_id,
            'pid' => $chapter_id
        ))->max('listorder');
    }

    public static function getMaxPageListOrder($section_id){
        return self::where(array(
            'pid' => $section_id
        ))->max('listorder');
    }

    public function isSection(){
        return $this->type == self::TYPE_SECTION;
    }

    public function isChapter(){
        return $this->type == self::TYPE_CHAPTER;
    }

    public function isPage(){
        return $this->type == self::TYPE_PAGE;
    }

    public static function getChapters($course_id){
        return self::where(['course_id' => $course_id, 'type' => self::TYPE_CHAPTER])->orderBy('listorder', 'asc')->get();
    }

    public static function getSections($chapter_id){
        return self::getByPid($chapter_id);
    }

    public static function getPages($section_id){
        return self::getByPid($section_id);
    }

    public static function getByPid($pid){
        return self::where('pid', $pid)->orderBy('listorder', 'asc')->get();
    }
}