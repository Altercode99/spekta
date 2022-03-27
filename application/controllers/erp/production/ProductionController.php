<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProductionController extends Erp_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ProductionModel', 'ProdModel');
        $this->ProdModel->myConstruct('prod');
        $this->auth->isAuth();
    }

    public function getMasterProductGrid()
    {
        $params = getParam();
        $products = $this->ProdModel->getMasterProduct($params)->result();
        $xml = "";
        $no = 1;
        foreach ($products as $prod) {
            $xml .= "<row id='$prod->id'>";
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            $xml .= "<cell>" . cleanSC($prod->name) . "</cell>";
            $xml .= "<cell>" . cleanSC($prod->code) . "</cell>";
            $xml .= "<cell>" . cleanSC($prod->emp1) . "</cell>";
            $xml .= "<cell>" . cleanSC($prod->emp2) . "</cell>";
            $xml .= "<cell>" . cleanSC(toIndoDateTime($prod->created_at)) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function productForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $product = $this->Prod->getDataById('products', $params['id']);
            fetchFormData($product);
        } else {
            $post = prettyText(getPost(), ['name']);
            if (!isset($post['id'])) {
                $this->createProduct($post);
            } else {
                $this->updateProduct($post);
            }
        }
    }

    public function createProduct($post)
    {
        $checkProduct = $this->Prod->getOne('products', [
            'name' => $post['name'],
            'code' => $post['code'],
        ]);
        isExist(["Produk $post[name]" => $checkProduct]);

        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->Prod->create('products', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function updateProduct($post)
    {
        $product = $this->Prod->getDataById('products', $post['id']);
        isDelete(["Produk $post[name]" => $product]);

        if ($product->name != $post['name']) {
            $checkProduct = $this->Prod->getOne('products', [
                'name' => $post['name'],
                'code' => $post['code'],
            ]);
            isExist(["Produk $post[name]" => $checkProduct]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->Prod->updateById('products', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }

    public function productDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $product = $this->Prod->getDataById('products', $data->id);
            $this->Prod->delete('products', ['id' => $data->id]);
            if (file_exists('./assets/images/products/' . $product->filename)) {
                unlink('./assets/images/products/' . $product->filename);
            }
            $mSuccess .= "- $data->field berhasil dihapus <br>";
        }
        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    public function getSpLocGrid()
    {
        $params = getParam();
        $locs = $this->ProdModel->getSpLoc($params)->result();
        $xml = "";
        $no = 1;
        foreach ($locs as $loc) {
            $xml .= "<row id='$loc->id'>";
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            $xml .= "<cell>" . cleanSC($loc->name) . "</cell>";
            $xml .= "<cell>" . cleanSC($loc->emp1) . "</cell>";
            $xml .= "<cell>" . cleanSC($loc->emp2) . "</cell>";
            $xml .= "<cell>" . cleanSC(toIndoDateTime($loc->created_at)) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function spLocDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $this->Prod->delete('spack_locations', ['id' => $data->id]);
            $mSuccess .= "- $data->field berhasil dihapus <br>";
        }
        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    public function spLocForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $loc = $this->Prod->getDataById('spack_locations', $params['id']);
            fetchFormData($loc);
        } else {
            $post = prettyText(getPost(), ['name']);
            if (!isset($post['id'])) {
                $this->createSpLoc($post);
            } else {
                $this->updateSpLoc($post);
            }
        }
    }

    public function createSpLoc($post)
    {
        $check = $this->Prod->getOne('spack_locations', [
            'name' => $post['name'],
        ]);
        isExist(["Lokasi $post[name]" => $check]);

        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->Prod->create('spack_locations', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function updateSpLoc($post)
    {
        $loc = $this->Prod->getDataById('spack_locations', $post['id']);
        isDelete(["Lokasi $post[name]" => $loc]);

        if ($loc->name != $post['name']) {
            $check = $this->Prod->getOne('spack_locations', [
                'name' => $post['name'],
            ]);
            isExist(["Lokasi $post[name]" => $check]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->Prod->updateById('spack_locations', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }
}
