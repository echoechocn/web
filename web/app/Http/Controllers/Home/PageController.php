<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/11/15
 * Time: 下午6:18
 */

namespace App\Http\Controllers\Home;


use App\Http\Controllers\Controller;
use App\Http\Models\Course;
use App\Http\Models\NodeInfo;
use Illuminate\Http\Request;

class PageController extends Controller
{

    public function courseNew(Request $request, $course_id){
        // 找到第一章第一节第一页，然后重定向
        $chapters = NodeInfo::getChapters($course_id);
        if (empty($chapters)){
            return $this->emptyCourse();
        }

        foreach ($chapters as $chapter){
            $sections = NodeInfo::getSections($chapter->id);
            if (empty($sections)){
                continue;
            }

            foreach ($sections as $section){
                $pages = NodeInfo::getPages($section->id);
                if (empty($pages)){
                    continue;
                }
                return redirect('/course/' . $course_id . '/c/' . $chapter->id . '/s/' . $section->id . '/p/' . $pages[0]->id);
            }
        }

        return $this->emptyCourse();
    }

    public function courseSection(Request $request, $course_id, $chapter_id, $section_id){
        $pages = NodeInfo::getPages($section_id);
        if (empty($pages)){
            return $this->emptyCourse();
        }
        return redirect('/course/' . $course_id . '/c/' . $chapter_id . '/s/' . $section_id . '/p/' . $pages[0]->id);
    }

    public function course(Request $request, $course_id, $chapter_id, $section_id, $page_id){

        // 验证课程章节页是否存在
        $course = Course::find($course_id);
        if (empty($course)){
            return $this->emptyCourse();
        }

        $node_ids = [$chapter_id, $section_id, $page_id];
        foreach ($node_ids as $node_id){
            $node = NodeInfo::find($node_id);
            if (empty($node)){
                return $this->emptyCourse();
            }
        }

        $data = array(
            'js' => 'js/home/course.js',
            'data' => array(
                'course_id' => $course_id,
                'chapter_id' => $chapter_id,
                'section_id' => $section_id,
                'page_id' => $page_id
            )
        );
        return $this->app($data);
    }

    public function app($data){
        return view('/home/app', $data);
    }

    public function emptyCourse(){
        return view('/home/empty_course', []);
    }
}