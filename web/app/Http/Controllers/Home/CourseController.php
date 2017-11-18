<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/11/15
 * Time: 下午6:04
 */

namespace App\Http\Controllers\Home;


use App\Http\Controllers\Controller;
use App\Http\Models\ImageText;
use App\Http\Models\NodeInfo;
use App\Http\Models\ProblemCase;
use App\Http\Models\ProgramProblem;
use App\Http\Models\Solution;
use App\Http\Models\SourceCode;
use App\Http\Models\UserCaseOutput;
use App\Http\Util\DateTime;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function studyPage(){

    }

    /**
     * 获取章节的结构
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getIndexes(Request $request, $course_id){
        $chapters = NodeInfo::getChapters($course_id);
        $data = [];

        foreach ($chapters as $chapter){
            $cpt = array(
                'id' => $chapter->id,
                'title' => $chapter->title
            );

            $children = [];
            $sections = NodeInfo::getSections($chapter->id);
            foreach ($sections as $section){
                $sec = array(
                    'id' => $section->id,
                    'title' => $section->title
                );
                $children[] = $sec;
            }

            $cpt['children'] = $children;
            $data[] = $cpt;
        }

        return response(json_encode($data));
    }

    public function getPages(Request $request, $section_id){
        $pages = NodeInfo::getPages($section_id);
        $data = [];
        foreach ($pages as $page){
            $data[] = array(
                "id" => $page->id
            );
        }
        return response(json_encode($data));
    }

    public function getContent(Request $request, $page_id){
        $page = NodeInfo::find($page_id);
        if (null == $page){
            return response('未找到对应页内容', 404);
        }

        $contents = json_decode($page->content, true);

        if (empty($contents)){
            $contents = [];
        }

        $data = [];
        foreach ($contents as $content){
            $item = [];
            switch ($content['type']){
                case NodeInfo::CONTENT_IMAGE_TEXT:
                    $item = array(
                        'id' => $content['id'],
                        'content' => $this->getImageTextContent($content['id']),
                        'type' => NodeInfo::CONTENT_IMAGE_TEXT
                    );
                    break;
                case NodeInfo::CONTENT_PROGRAM:
                    $program = ProgramProblem::find($content['id']);
                    if (null == $program){
                        continue;
                    }

                    $item = array(
                        "id" => $program->problem_id,
                        "title" => $program->title,
                        "description" => $program->getDescription(),
                        "input_description" => $program->getInput(),
                        "output_description" => $program->getOutput(),
                        "hint" => $program->getHint(),
                        "sample_input" => $program->sample_input,
                        "sample_output" => $program->sample_output,
                        "time_limit" => $program->time_limit,
                        "memory_limit" => $program->memory_limit,
                        'type' => NodeInfo::CONTENT_PROGRAM,
                        'show_editor' => false,
                    );

                    $user_id = $request->session()->get('user_id');
                    $item['user_solution'] = $this->getUserProblemStatus($user_id, $program->problem_id);
                    break;
            }
            $data[] = $item;
        }
        return response(json_encode($data));
    }

    // 获取图文内容
    private function getImageTextContent($id){
        $image_text = ImageText::find($id);
        if (null == $image_text){
            return '';
        } else{
            return $image_text->getContent();
        }
    }

    public function requestUserProblemStatus(Request $request){
        return response(json_encode(
            $this->getUserProblemStatus($request->session()->get('user_id'), $request->input('problem_id'))
        ));
    }

    private function getUserProblemStatus($user_id, $problem_id){
        $solutions = Solution::where(['user_id' => $user_id, 'problem_id' => $problem_id])
            ->orderBy('solution_id', 'desc')->take(1)->get();

        foreach ($solutions as $solution){
            return $this->getSolutionStatus($solution);
        }

        return array(
            'result' => '-1',  // 未提交
            'source' => '',
            'language' => 7,   // 默认使用php
            'cases' => []
        );
    }

    private function getSolutionStatus($solution){
        $data = array(
            'result' => $solution->result,
            'language' => $solution->language,
            'source' => ''
        );

        $case_data = [];

        $case_outputs = UserCaseOutput::where(['solution_id' => $solution->solution_id])->orderBy('case_id', 'asc')->get();
        foreach ($case_outputs as $case_output){
            $case = ProblemCase::find($case_output->case_id);
            if (null == $case){
                $case_data[] = array(
                    'case_id' => 0,
                    'input' => '丢失',
                    'output' => '丢失',
                    'result' => 0,
                    'public' => 1
                );
                continue;
            }
            $item = array(
                'case_id' => $case->id
            );
            if ($case->public){
                $item['input'] = $case->input;
                $item['output'] = $case->output;
                $item['user_output'] = $case_output->output;
            } else{
                $item['input'] = '保密';
                $item['output'] = '保密';
                $item['user_output'] = '保密';
            }
            $item['result'] = $case_output->result;
            $case_data[] = $item;
        }

        $data['cases'] = $case_data;
        return $data;
    }

    public function postSolution(Request $request){
        $solution = new Solution();
        $solution->problem_id = $request->input('problem_id');
        $solution->user_id = $request->session()->get('user_id');
        $solution->in_date = DateTime::format();
        $solution->language = $request->input('language');
        $solution->ip = $request->ip();
        $solution->code_length = strlen($request->input('source'));
        $solution->save();

        $source_code = new SourceCode();
        $source_code->solution_id = $solution->solution_id;
        $source_code->source = $request->input('source');
        $source_code->save();

        return response(json_encode(array(
            'solution_id' => $solution->solution_id
        )));
    }

}