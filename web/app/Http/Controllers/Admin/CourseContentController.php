<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/11/8
 * Time: 下午5:45
 */

namespace App\Http\Controllers\Admin;


use App\Http\Models\ImageText;
use App\Http\Models\NodeInfo;
use App\Http\Models\ProgramProblem;
use Illuminate\Http\Request;
use PhpParser\Node;

class CourseContentController extends AdminController
{
    // 获取 章、节
    public function getIndexes(Request $request){
        $chapters = NodeInfo::where(['course_id' => $request->input('course_id'), 'status' => 1, 'type' => NodeInfo::TYPE_CHAPTER])->orderBy('listorder', 'asc')->get();
        $data = [];
        foreach ($chapters as $chapter){
            $item = [];
            $item['id'] = $chapter->id;
            $item['title'] = $chapter->title;
            $item['show_section'] = false;

            $sections = NodeInfo::where([
                'course_id' => $request->input('course_id'),
                'status' => 1,
                'type' => NodeInfo::TYPE_SECTION,
                'pid' => $chapter->id
            ])->orderBy('listorder', 'asc')->get();

            $children = [];
            foreach ($sections as $section){
                $children[] = array(
                    'id' => $section->id,
                    'title' => $section->title
                );
            }
            $item['children'] = $children;

            $data[] = $item;
        }
        return response(json_encode($data));
    }

    /**
     * 添加章
     */
    public function addChapter(Request $request){

        $node = new NodeInfo();
        $node->id = NodeInfo::generate();
        $node->course_id = $request->input('course_id');
        $node->type = NodeInfo::TYPE_CHAPTER;
        $node->title = $request->input('title');
        $node->content = '';
        $max_order = NodeInfo::getMaxChapterListorder($request->input('course_id'));
        $next_order = empty($max_order) ? 1: $max_order + 1;
        $node->listorder = $next_order;
        $node->status = 1;
        $node->save();

        return response(json_encode(array(
            'id' => $node->id
        )));
    }

    /**
     * 添加节
     */
    public function addSection(Request $request){

        $node = new NodeInfo();
        $node->id = NodeInfo::generate();
        $node->course_id = $request->input('course_id');
        $node->pid = $request->input('chapter_id');
        $node->type = NodeInfo::TYPE_SECTION;
        $node->title = $request->input('title');
        $node->content = '';
        $max_order = NodeInfo::getMaxSectionListorder($request->input('course_id'), $request->input('chapter_id'));
        $node->listorder = empty($max_order) ? 1: $max_order + 1;
        $node->status = 1;
        $node->save();

        return response(json_encode(array(
            'id' => $node->id
        )));
    }

    /**
     * 更新 Node 的 title
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function updateNodeInfo(Request $request){
        $node = NodeInfo::find($request->input('id'));
        $node->title = $request->input('title');
        $node->save();
        return response('');
    }

    /**
     * 添加页（页没有名称）
     */
    public function addPage(Request $request){
        $section_node = NodeInfo::find($request->input('section_id'));
        if (null == $section_node || !$section_node->isSection()){
            return response('节不存在：' . $request->input('section_id'), 403);
        }

        $node = new NodeInfo();
        $node->id = NodeInfo::generate();
        $node->course_id = $section_node->course_id;
        $node->pid = $section_node->id;
        $node->type = NodeInfo::TYPE_PAGE;
        $node->title = "";
        $node->content = json_encode([]);
        $max_order = NodeInfo::getMaxPageListOrder($section_node->id);
        $node->listorder = empty($max_order) ? 1 : $max_order + 1;
        $node->status = 1;
        $node->save();

        return response(json_encode(array(
            "id" => $node->id
        )));
    }

    /**
     * 修改章、节或者页的 listorder
     */
    public function resetListOrder(){
        return response();
    }

    public function getPageList(Request $request){
        $pages = NodeInfo::where('pid', $request->input('section_id'))->get();
        $data = [];
        foreach($pages as $page){
            $data[] = array(
                "id"=> $page->id,
                "status" => $page->status
            );
        }
        return response(json_encode($data));
    }

    public function getPageContent(Request $request, $page_id = 0){
        $page = NodeInfo::find($page_id);
        if (null == $page || !$page->isPage()){
            return response('未找到数据', 404);
        }

        $list = json_decode($page->content, true);

        if (empty($list) || $list == false){
            return response(json_encode([]));
        }

        $data = [];
        foreach ($list as $item){
            $item = array(
                "id" => $item['id'],
                "type" => $item['type']
            );

            if ($item['type'] == NodeInfo::CONTENT_IMAGE_TEXT){
                $image_text = ImageText::find($item['id']);
                if ($image_text == null){
                    continue;
                }
                $item['content'] = $image_text->getContent();
            } else if ($item['type'] == NodeInfo::CONTENT_PROGRAM){
                $program = ProgramProblem::find($item['id']);
                if ($program == null){
                    $item['title'] = '还未选择题目';
                } else{
                    $item['title'] = $program->title;
                }
            }

            $data[] = $item;
        }

        return response(json_encode($data));
    }

    /**
     * 修改页的内容
     * [{'type': 1, id: 1221}, {'type': 2, id: 121}]
     */
    public function updatePage(Request $request){
        $id = $request->input('page_id');
        $content = $request->input('content');

        $page = NodeInfo::find($id);
        $page->content = json_encode($content);
        $page->save();

        return response('');
    }

    /**
     * 更新图文内容
     */
    public function updateImageText(Request $request){
        $image_text = ImageText::find($request->input('id'));
        if (null == $image_text){
            return response('', 404);
        }
        $image_text->setContent($request->input('content'));
        $image_text->save();
        return response('');
    }

    /**
     * 新建一个图文并返回 ID
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function newImageText(){
        $image_text = new ImageText();
        $image_text->id = ImageText::generate();
        $image_text->content = '';
        $image_text->save();
        return response(json_encode(array(
            'id' => $image_text->id
        )));
    }
}