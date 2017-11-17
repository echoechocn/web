<?php
/**
 * Created by vscode.
 * User: xiang
 * Date: 17/11/01
 * Time: 下午11:22
 */

namespace App\Http\Controllers\Admin;

use \App\Http\Controllers\Controller;
use App\Http\Models\CourseProblemRel;
use App\Http\Models\Privilege;
use App\Http\Models\ProblemCase;
use App\Http\Models\ProgramProblem;
use App\Http\Util\DateTime;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;

class QuestionController extends Controller{

    private function listToData($problem_list = []){
        $data = [];
        foreach ($problem_list as $problem){
            $data[] = array(
                'pid' => $problem->problem_id,
                'title' => $problem->title,
                'ac_count' => $problem->accepted,
                'create_time' => $problem->in_date,
                'status' => $problem->defunct == 'Y' ? '可用' : '不可用'
            );
        }
        return $data;
    }

    public function program(Request $request){
        $problem_list = ProgramProblem::orderBy('problem_id', 'desc')->get();
        $data = $this->listToData($problem_list);
        return response($data);
    }

    public function programInCourse(Request $request, $course_id=0, $page=0){
        $rel = CourseProblemRel::where('course_id', $course_id)->orderBy('problem_id', 'desc')->forPage($page)->get();
        $list = [];
        foreach ($rel as $r) {
            $problem = ProgramProblem::find($r->problem_id);
            if (null == $problem) {
                continue;
            }
            $list[] = $problem;
        }
        return response($this->listToData($list));
    }

    /**
     * 获取问题详情
     * @param Request $request
     * @return Response
     */
    public function getProgramQuestion(Request $request){
        $pid = $request->input('pid');
        if ($pid){
            $program_problem = ProgramProblem::find($pid);

            // 所有测试用例
            $cases = ProblemCase::where('problem_id', $pid)->orderBy('id')->get();

            $data = [];
            $data['pid'] = $pid;
            $data['pid_text'] = $pid;
            $data['title'] = $program_problem->title;
            $data['time_limit'] = $program_problem->time_limit;
            $data['memory_limit'] = $program_problem->memory_limit;
            $data['description'] = $program_problem->getDescription();
            $data['input_description'] = $program_problem->getInput();
            $data['output_description'] = $program_problem->getOutput();
            $data['hint'] = $program_problem->getHint();
            $data['spj'] = $program_problem->spj == 1;
            $data['source'] = $program_problem->source;
            $data['sample_input'] = $program_problem->sample_input;
            $data['sample_output'] = $program_problem->sample_output;

            $test_cases = [];
            foreach ($cases as $case){
                $test_cases[] = array(
                    'id' => $case->id,
                    'input' => $case->input,
                    'output' => $case->output,
                    'public' => $case->public == 1
                );
            }
            $data['test_cases'] = $test_cases;
            return response(json_encode($data), 200);
        } else{
            return response("pid 不能为空：" . $pid, 403);
        }
    }

    public function addProgramQuestion(Request $request){
        // TODO 数据合法性检验

        if (empty($request->input('pid'))){
            return $this->postAddProblem($request);
        } else{
            // edit
            return $this->postEditProblem($request);
        }
    }

    /**
     * 根据请求信息，初始化 problem 数据
     * @param Request $request
     * @param ProgramProblem $problem
     * @return ProgramProblem
     */
    private function initProblem(Request $request, ProgramProblem $problem){
        $problem->title = $request->input('title');
        $problem->time_limit = $request->input('time_limit');
        $problem->memory_limit = $request->input('memory_limit');
        $problem->description = htmlentities($request->input('description'));
        $problem->input = htmlentities($request->input('input_description'));
        $problem->output = htmlentities($request->input('output_description'));
        $problem->sample_input = $request->input('sample_input');
        $problem->sample_output = $request->input('sample_output');
        $problem->hint = htmlentities($request->input('hint'));
        $problem->source = $request->input('source');
        $problem->spj = $request->input('spj') ? 1 : 0;
        $problem->in_date = DateTime::format();
        $problem->defunct = 'Y';
        return $problem;
    }

    /**
     * 删除目录下的所有文件
     * @param $basedir
     */
    private function cleanQuestionDir($basedir){
        //先删除目录下的文件：
        $dh = opendir($basedir);
        while ($file = readdir($dh)) {
            if($file != "." && $file != "..") {
                $file_path = $basedir . "/" . $file;
                if(!is_dir($file_path)) {
                    unlink($file_path);
                }
            }
        }
    }

    /**
     * 修改题目
     * @param $request
     * @return Response
     */
    private function postEditProblem($request){
        $pid = $request->input('pid');
        $problem = ProgramProblem::find($pid);
        if (null == $problem){
            return response('未找到问题', 404);
        }
        $problem = $this->initProblem($request, $problem);
        $problem->save();

        $OJ_DATA = env('OJ_DATA', '/home/judge/data');
        $basedir = "$OJ_DATA/$pid";
        $this->cleanQuestionDir($basedir);

        try{
            mkdir ( $basedir );
        } catch (\ErrorException $e){
        }

        if(strlen($problem->sample_output) && !strlen($problem->sample_input)){
            $sample_input="0";
        } else {
            $sample_input = $problem->sample_input;
        }

        if(strlen($sample_input)){
            $this->mkdata($pid, "sample.in", $sample_input, $OJ_DATA);
        }
        if(strlen($problem->sample_output)){
            $this->mkdata($pid, "sample.out", $problem->sample_output, $OJ_DATA);
        }

        $this->addTestCase($request, $problem, $OJ_DATA);

        return response('add success, pid:' . $problem->problem_id);
    }

    /**
     * 添加(修改)测试用例
     * @param $request
     * @param $problem
     * @param $OJ_DATA
     */
    private function addTestCase($request, $problem, $OJ_DATA){
        $pid = $problem->problem_id;
        $test_cases = $request->input('test_cases', []);

        $exist_cases = ProblemCase::where('problem_id', $pid)->get();

        $case_ids = [];

        foreach ($test_cases as $test_case){
            if (empty($test_case['id'])){
                $problem_case = new ProblemCase();
            } else{
                $problem_case = ProblemCase::find($test_case['id']);
                if (null == $problem_case){
                    continue;
                }
            }

            $case_ids[] = $test_case['id'];

            $problem_case->problem_id = $problem->problem_id;
            $problem_case->input = strlen($test_case['input']) ? $test_case['input'] : '0';
            $problem_case->output = $test_case['output'];
            $problem_case->public = $test_case['public'] ? 1 : 0;
            $problem_case->save();

            $this->mkdata($pid, $problem_case->id . ".in", $problem_case->input, $OJ_DATA);
            $this->mkdata($pid, $problem_case->id . ".out", $problem_case->output, $OJ_DATA);
        }

        // 查询需要删除的 case
        foreach ($exist_cases as $case){
            if (!in_array($case->id, $case_ids)){
                $case->delete();
            }
        }
    }

    /**
     * @param $request Request
     * @return Response
     */
    private function postAddProblem($request){
        // create problem
        $problem = new ProgramProblem();
        $problem = $this->initProblem($request, $problem);
        $problem->save();

        // insert privilege
        $privilege = new Privilege();
        $privilege->user_id = $request->cookie('name', 'y');
        $privilege->rightstr = "p" . $problem->problem_id;
        $privilege->save();

        // 自增ID，保存完之后会自动设置
        $pid = $problem->problem_id;

        $OJ_DATA = env('OJ_DATA', '/home/judge/data');
        $basedir = "$OJ_DATA/$pid";
        mkdir ( $basedir );

        if(strlen($problem->sample_output) && !strlen($problem->sample_input)){
            $sample_input="0";
        } else {
            $sample_input = $problem->sample_input;
        }

        if(strlen($sample_input)){
            $this->mkdata($pid, "sample.in", $sample_input, $OJ_DATA);
        }
        if(strlen($problem->sample_output)){
            $this->mkdata($pid, "sample.out", $problem->sample_output, $OJ_DATA);
        }

        $this->addTestCase($request, $problem, $OJ_DATA);

        // 如果有关联的课程，添加课程管理数据
        if ($request->input('course_id')){
            $rel = new CourseProblemRel();
            $rel->course_id = $request->input('course_id');
            $rel->problem_id = $pid;
            $rel->save();
        }

        return response('add success, pid:' . $problem->problem_id);
    }

    private function mkdata($pid, $filename, $input, $OJ_DATA){
        $basedir = "$OJ_DATA/$pid";
        $fp = @fopen ( $basedir . "/$filename", "w" );
        if($fp){
            fputs ( $fp, preg_replace ( "(\r\n)", "\n", $input ) );
            fclose ( $fp );
        }else{
            echo "Error while opening".$basedir . "/$filename ,try [chgrp -R www-data $OJ_DATA] and [chmod -R 771 $OJ_DATA ] ";
        }
    }
}