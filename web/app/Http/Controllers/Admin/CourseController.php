<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/11/8
 * Time: 下午5:39
 */

namespace App\Http\Controllers\Admin;



use App\Http\Models\Course;
use App\Http\Models\CourseClass;
use App\Http\Models\CourseProblemRel;
use App\Http\Models\ProgramProblem;
use Illuminate\Http\Request;

class CourseController extends AdminController
{
    /**
     * 获取课程列表
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function courseList(Request $request, $page = 0){
        $courses = Course::orderBy('id', 'desc')->forPage($page)->get();
        $data = [];
        foreach ($courses as $course){
            $data[] = array(
                "id" => $course->id,
                "title" => $course->title,
                "create_time" => $course->getCreateTime()
            );
        }
        return response(json_encode($data));
    }

    /**
     * 添加 | 修改课程
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function addCourse(Request $request){
        $id = $request->input('id');
        if (empty($id)){
            $course = new Course();
            $course->id = Course::generate();
        } else{
            $course = Course::find($id);
            if (null == $course){
                return response('course 不存在', 404);
            }
        }
        $course->title = $request->input('title');
        $course->setDescription($request->input('description'));
        $course->save();
        return response("保存成功");
    }

    /**
     * 获取课程信息
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getCourseInfo(Request $request, $id = 0){
        $course = Course::find($id);
        if (null == $course){
            return response('课程不存在哦', 404);
        }
        return response(json_encode(
            array(
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->getDescription()
            )
        ));
    }

    /**
     * 获取班期详细信息
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getClassInfo(Request $request, $id = 0){
        $class = CourseClass::find($id);
        if (null == $class){
            return response("班期不存在", 400);
        } else{
            return response(json_encode(
                array(
                    "id" => $class->id,
                    "title" => $class->title,
                    "course_id" => $class->course_id
                )
            ));
        }
    }

    /**
     * 获取班期列表
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function classList(Request $request, $course_id = 0, $page = 0){
        $classes = CourseClass::where('course_id', $course_id)->orderBy('id', 'desc')->forPage($page)->get();
        $data = [];
        foreach ($classes as $class){
            $data[] = array(
                "id" => $class->id,
                "title" => $class->title,
                "course_id" => $class->course_id,
                "create_time" => $class->getCreateTime()
            );
        }
        return response(json_encode($data));
    }

    /**
     * 添加 | 修改班期信息
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function addClass(Request $request){
        $id = $request->input('id');
        if (empty($id)){
            $class = new CourseClass();
            $class->id = CourseClass::generate();
            $class->course_id = $request->input('course_id');
        } else{
            $class = CourseClass::find($request->input('id'));
            if (empty($class)){
                return response('班期不存在', 404);
            }
        }

        if (empty(Course::find($request->input('course_id')))){
            return response('对应的课程不存在', 403);
        }

        $class->title = $request->input('title');
        $class->save();
        return response('保存成功');
    }

    /**
     * 获取课程下的问题列表
     * @param Request $request
     * @param $course_id
     * @param int $page
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getProblemsInCourse(Request $request, $course_id, $page=0){
        $rel = CourseProblemRel::where('course_id', $course_id)->orderBy('problem_id', 'desc')->forPage($page)->get();
        $data = [];
        foreach ($rel as $r){
            $problem = ProgramProblem::find($r->problem_id);
            if (null == $problem){
                continue;
            }
            $data[] = array(
                'pid' => $problem->problem_id,
                'title' => $problem->title,
                'ac_count' => $problem->accepted,
                'create_time' => $problem->in_date,
                'status' => $problem->defunct == 'Y' ? '可用' : '不可用'
            );
        }
        return response(json_encode($data));
    }

}