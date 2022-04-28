<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ImproveController extends Erp_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ImproveModel', 'Improve');
        $this->Improve->myConstruct('general');
        $this->auth->isAuth();
    }

    public function getImproveCategories()
    {
        $categories = $this->Improve->getImproveCategories(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($categories as $cat) {
            $xml .= "<row id='$cat->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($cat->name) ."</cell>";
            $xml .= "<cell>". cleanSC($cat->emp1) ."</cell>";
            $xml .= "<cell>". cleanSC($cat->emp2) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($cat->created_at)) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function improveCatForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $cat = $this->General->getDataById('improve_categories', $params['id'], 'id,name');
            fetchFormData($cat);
        } else {
            $post = prettyText(getPost(), ['name']);
            if (!isset($post['id'])) {
                $this->createImproveCat($post);
            } else {
                $this->updateImproveCat($post);
            }
        }
    }

    public function createImproveCat($post)
    {
        $cat = $this->General->getWhere('improve_categories', ['name' => $post['name'], 'location' => empLoc()])->row();
        isExist(["Kategori $post[name]" => $cat]);

        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');
        $insertId = $this->General->create('improve_categories', $post);
        xmlResponse('inserted', $post['name']);
    }


    public function updateImproveCat($post)
    {
        $cat = $this->General->getDataById('improve_categories', $post['id']);
        isDelete(["Kategori $post[name]" => $cat]);

        if ($cat && $cat->name != $post['name']) {
            $checkName = $this->General->getOne('improve_categories', ['name' => $post['name'], 'location' => empLoc()]);
            isExist(["Kategori $post[name]" => $checkName]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->General->updateById('improve_categories', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }

    public function improveCatDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $mSuccess .= "- $data->field berhasil dihapus <br>";
            $this->General->delete('improve_categories', ['id' => $data->id]);
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    public function getImproveLevels()
    {
        $levels = $this->Improve->getImproveLevels(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($levels as $level) {
            $xml .= "<row id='$level->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($level->name) ."</cell>";
            $xml .= "<cell>". cleanSC($level->stand_for) ."</cell>";
            $xml .= "<cell>". cleanSC($level->emp1) ."</cell>";
            $xml .= "<cell>". cleanSC($level->emp2) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($level->created_at)) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function improveLevelForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $level = $this->General->getDataById('improve_levels', $params['id'], 'id,name');
            fetchFormData($level);
        } else {
            $post = prettyText(getPost(), ['name']);
            if (!isset($post['id'])) {
                $this->createImproveLevel($post);
            } else {
                $this->updateImproveLevel($post);
            }
        }
    }

    public function createImproveLevel($post)
    {
        $level = $this->General->getWhere('improve_levels', ['name' => $post['name'], 'location' => empLoc()])->row();
        isExist(["Tingkatan $post[name]" => $level]);

        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');
        $insertId = $this->General->create('improve_levels', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function updateImproveLevel($post)
    {
        $level = $this->General->getDataById('improve_levels', $post['id']);
        isDelete(["Kategori $post[name]" => $level]);

        if ($level && $level->name != $post['name']) {
            $checkName = $this->General->getOne('improve_levels', ['name' => $post['name'], 'location' => empLoc()]);
            isExist(["Tingkatan $post[name]" => $checkName]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->General->updateById('improve_levels', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }

    public function improveLevelDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $mSuccess .= "- $data->field berhasil dihapus <br>";
            $this->General->delete('improve_levels', ['id' => $data->id]);
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    public function checkBeforeAddFileDetForm()
    {
        $post = fileGetContent();
        $isExist = false;
        if (!isset($post->id)) {
            $check = $this->General->getOne('improve_ideas', ['title' => $post->title, 'location' => empLoc()]);
            if ($check) {
                $isExist = true;
            }
        } else {
            $idea = $this->General->getDataById('improve_ideas', $post->id);
            if ($idea) {
                if ($idea->title != $post->title) {
                    $check = $this->General->getOne('improve_ideas', ['title' => $post->title, 'location' => empLoc()]);
                    if ($check) {
                        $isExist = true;
                    }
                }
            } else {
                response(['status' => 'deleted']);
            }
        }

        if (!$isExist) {
            response(['status' => 'success']);
        } else {
            response(['status' => 'exist', 'message' => 'Judul improvement sudah digunakan!']);
        }
    }

    public function detectiveForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $data = $this->General->getDataById('improve_ideas', $params['id']);
            fetchFormData($data);
        } else {
            $post = prettyText(getPost(), ['title']);
            if (!isset($post['id'])) {
                $this->createDetIdea($post);
            } else {
                $this->updateDetIdea($post);
            }
        }
    }

    public function createDetIdea($post)
    {
        $title = $this->General->getOne('improve_ideas', ['title' => $post['title'], 'location' => empLoc()]);
        isExist(['Judul Improvement' => $title]);

        $data = [
            'location' => empLoc(),
            'sub_department_id' => empSub(),
            'emp_id' => empId(),
            'title' => $post['title'],
            'current_condition' => $post['current_condition'],
            'expected_condition' => $post['expected_condition'],
            'planning' => $post['planning'],
            'before_filename' => $post['before_filename'],
            'superior_nip' => $post['superior_nip'],
            'level_id' => 2,
            'created_by' => empId(),
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $insertId = $this->General->create('improve_ideas', $data);
        xmlResponse('inserted', $post['title']);
    }

    public function getDetIdeas(Type $var = null)
    {
        $params = getParam();
        $ideas = $this->Improve->getDetIdeas(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($ideas as $ide) {
            $xml .= "<row id='$ide->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($ide->employee_name) ."</cell>";
            $xml .= "<cell>". cleanSC($ide->category ? $ide->category : '-') ."</cell>";
            $xml .= "<cell>". cleanSC($ide->sub_department) ."</cell>";
            $xml .= "<cell>". cleanSC($ide->title) ."</cell>";
            $xml .= "<cell>". cleanSC($ide->current_condition) ."</cell>";
            $xml .= "<cell>". cleanSC($ide->expected_condition) ."</cell>";
            $xml .= "<cell>". cleanSC($ide->planning) ."</cell>";
            $xml .= "<cell>". cleanSC($ide->superior_name) ."</cell>";
            $xml .= "<cell>". cleanSC($ide->superior_approval) ."</cell>";
            $xml .= "<cell>". cleanSC($ide->det_approval) ."</cell>";
            $xml .= "<cell>". cleanSC($ide->status) ."</cell>";
            $xml .= "<cell>". cleanSC($ide->emp1) ."</cell>";
            $xml .= "<cell>". cleanSC($ide->emp2) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($ide->created_at)) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

}