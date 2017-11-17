<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/10/28
 * Time: 上午11:30
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function page(Request $request){
        $path = $request->getPathInfo();
        return view('/admin/app', ['js' => 'js/admin' . $path . '.js', 'data' => []]);
    }

    public function programEdit(Request $request, $pid = 0){
        return view('/admin/app', ['js' => 'js/admin/question/program_edit.js', 'data' => array(
            'current_pid' => $pid
        )]);
    }

    public function classes($course_id = 0){
        $data = array(
            'js' => 'js/admin/course/classes.js',
            'data' => array(
                'course_id' => $course_id
            )
        );
        return $this->app($data);
    }

    public function coursePrograms($course_id = 0){
        $data = array(
            'js' => 'js/admin/course/programs.js',
            'data' => array(
                'course_id' => $course_id
            )
        );
        return $this->app($data);
    }

    public function courseProgram($course_id = 0, $program_id = 0){
        $data = array(
            'js' => 'js/admin/course/program_edit.js',
            'data' => array(
                'course_id' => $course_id,
                'program_id' => $program_id
            )
        );
        return $this->app($data);
    }

    public function courseSetting($id = 0){
        $data = array(
            'js' => 'js/admin/course/course_setting.js',
            'data' => array(
                'id' => $id
            )
        );
        return $this->app($data);
    }

    public function classSetting($course_id, $class_id = 0){
        $data = array(
            'js' => 'js/admin/course/class_setting.js',
            'data' => array(
                'course_id' => $course_id,
                'class_id' => $class_id
            )
        );
        return $this->app($data);
    }

    public function courseContent($course_id){
        $data = array(
            'js' => 'js/admin/course/content.js',
            'data' => array(
                'course_id' => $course_id
            )
        );
        return $this->app($data);
    }

    private function app($data){
        return view('/admin/app', $data);
    }
}