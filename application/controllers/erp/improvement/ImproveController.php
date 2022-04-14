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

    public function getDetCategories()
    {
        $categories = $this->Improve->getDetCategories(getParam())->result();
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

    public function detCatForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $cat = $this->General->getDataById('det_categories', $params['id'], 'id,name');
            fetchFormData($cat);
        } else {
            $post = prettyText(getPost(), ['name']);
            if (!isset($post['id'])) {
                $this->createDetCat($post);
            } else {
                $this->updateDetCat($post);
            }
        }
    }

    public function createDetCat($post)
    {
        $cat = $this->General->getWhere('det_categories', ['name' => $post['name'], 'location' => empLoc()])->row();
        isExist(["Kategori $post[name]" => $cat]);

        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');
        $insertId = $this->General->create('det_categories', $post);
        xmlResponse('inserted', $post['name']);
    }


    public function updateDetCat($post)
    {
        $cat = $this->General->getDataById('det_categories', $post['id']);
        isDelete(["Kategori $post[name]" => $cat]);

        if ($cat && $cat->name != $post['name']) {
            $checkName = $this->General->getOne('det_categories', ['name' => $post['name'], 'location' => empLoc()]);
            isExist(["Kategori $post[name]" => $checkName]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->General->updateById('det_categories', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }

    public function detCatDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $mSuccess .= "- $data->field berhasil dihapus <br>";
            $this->General->delete('det_categories', ['id' => $data->id]);
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

}