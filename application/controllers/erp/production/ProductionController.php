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


    public function getMasterMakloonGrid()
    {
        $params = getParam();
        $products = $this->ProdModel->getMasterMakloon($params)->result();
        $xml = "";
        $no = 1;
        foreach ($products as $prod) {
            $xml .= "<row id='$prod->id'>";
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            $xml .= "<cell>" . cleanSC($prod->name) . "</cell>";
            $xml .= "<cell>" . cleanSC($prod->emp1) . "</cell>";
            $xml .= "<cell>" . cleanSC($prod->emp2) . "</cell>";
            $xml .= "<cell>" . cleanSC(toIndoDateTime($prod->created_at)) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getMasterProductTypeGrid()
    {
        $params = getParam();
        $products = $this->ProdModel->getMasterProductType($params)->result();
        $xml = "";
        $no = 1;
        foreach ($products as $prod) {
            $xml .= "<row id='$prod->id'>";
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            $xml .= "<cell>" . cleanSC($prod->name) . "</cell>";
            $xml .= "<cell>" . cleanSC($prod->emp1) . "</cell>";
            $xml .= "<cell>" . cleanSC($prod->emp2) . "</cell>";
            $xml .= "<cell>" . cleanSC(toIndoDateTime($prod->created_at)) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
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
            $xml .= "<cell>" . cleanSC($prod->product_type ? $prod->product_type : '-') . "</cell>";
            $xml .= "<cell>" . cleanSC($prod->package_desc) . "</cell>";
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
            $product = $this->Prod->getDataById('spack_products', $params['id']);
            fetchFormData($product);
        } else {
            $post = prettyText(getPost(), ['package_desc']);
            if (!isset($post['id'])) {
                $this->createProduct($post);
            } else {
                $this->updateProduct($post);
            }
        }
    }

    public function createProduct($post)
    {
        $checkProduct = $this->Prod->getOne('spack_products', [
            'name' => $post['name'],
        ]);
        isExist(["Produk $post[name]" => $checkProduct]);
        
        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->Prod->create('spack_products', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function updateProduct($post)
    {
        $product = $this->Prod->getDataById('spack_products', $post['id']);
        isDelete(["Produk $post[name]" => $product]);

        if ($product->name != $post['name']) {
            $checkProduct = $this->Prod->getOne('spack_products', [
                'name' => $post['name'],
            ]);
            isExist(["Produk $post[name]" => $checkProduct]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->Prod->updateById('spack_products', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }

    public function productDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $checkBr = $this->Prod->getOne('spack_batch_numbers', ['product_id' => $data->id]);
            if($checkBr) {
                $mError .= "- $data->field sudah digunakan! <br>";
            } else {
                $product = $this->Prod->getDataById('spack_products', $data->id);
                $this->Prod->delete('spack_products', ['id' => $data->id]);
                if (file_exists('./assets/images/spack_products/' . $product->filename)) {
                    unlink('./assets/images/spack_products/' . $product->filename);
                }
                $mSuccess .= "- $data->field berhasil dihapus <br>";
            }
        }
        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    public function productTypeForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $type = $this->Prod->getDataById('spack_product_types', $params['id']);
            fetchFormData($type);
        } else {
            $post = prettyText(getPost(), null, ['name']);
            if (!isset($post['id'])) {
                $this->createProductType($post);
            } else {
                $this->updateProductType($post);
            }
        }
    }

    public function createProductType($post)
    {
        $checkType = $this->Prod->getOne('spack_product_types', [
            'name' => $post['name'],
        ]);
        isExist(["Tipe produk $post[name]" => $checkType]);
        
        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->Prod->create('spack_product_types', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function updateProductType($post)
    {
        $type = $this->Prod->getDataById('spack_product_types', $post['id']);
        isDelete(["Tipe produk $post[name]" => $type]);

        if ($type->name != $post['name']) {
            $checkType = $this->Prod->getOne('spack_product_types', [
                'name' => $post['name'],
            ]);
            isExist(["Produk $post[name]" => $checkType]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->Prod->updateById('spack_product_types', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }


    public function productTypeDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $checkProduct = $this->Prod->getOne('spack_products', ['product_type' => $data->id]);
            if($checkProduct) {
                $mError .= "- $data->field sudah digunakan! <br>";
            } else {
                $this->Prod->delete('spack_product_types', ['id' => $data->id]);
                $mSuccess .= "- $data->field berhasil dihapus <br>";
            }
        }
        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    public function makloonForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $makloon = $this->Prod->getDataById('spack_makloons', $params['id']);
            fetchFormData($type);
        } else {
            $post = prettyText(getPost(), null, ['name']);
            if (!isset($post['id'])) {
                $this->createMakloonType($post);
            } else {
                $this->updateMakloonType($post);
            }
        }
    }

    public function createMakloonType($post)
    {
        $checkMakloon = $this->Prod->getOne('spack_makloons', [
            'name' => $post['name'],
        ]);
        isExist(["Makloon $post[name]" => $checkMakloon]);
        
        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->Prod->create('spack_makloons', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function updateMakloonType($post)
    {
        $makloon = $this->Prod->getDataById('spack_makloons', $post['id']);
        isDelete(["Makloon $post[name]" => $makloon]);

        if ($makloon->name != $post['name']) {
            $checkMakloon = $this->Prod->getOne('spack_makloons', [
                'name' => $post['name'],
            ]);
            isExist(["Produk $post[name]" => $checkMakloon]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->Prod->updateById('spack_makloons', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }


    public function makloonDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $checkPrint = $this->Prod->getOne('spack_prints', ['makloon' => $data->id]);
            if($checkPrint) {
                $mError .= "- $data->field sudah digunakan! <br>";
            } else {
                $this->Prod->delete('spack_makloons', ['id' => $data->id]);
                $mSuccess .= "- $data->field berhasil dihapus <br>";
            }
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
            $checkPrint = $this->Prod->getOne('spack_prints', ['location_id' => $data->id]);
            if($checkPrint) {
                $mError .= "- $data->field sudah digunakan! <br>";
            } else {
                $this->Prod->delete('spack_locations', ['id' => $data->id]);
                $mSuccess .= "- $data->field berhasil dihapus <br>";
            }
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

    public function getProduct()
    {
        $params = getParam();        
        $prodList = [];
        if(isset($params['name'])) {
            $products = $this->Prod->getLike('spack_products', null, ['name' => $params['name']], 'id,name', 15)->result();
            foreach ($products as $prod) {
                $prodList['options'][] = [
                    'value' => $prod->id,
                    'text' => "$prod->name"
                ];
            }
        } else {
            $prodList['options'][] = [
                'value' => 0,
                'text' => ""
            ];
        }
        echo json_encode($prodList);
    }

    public function getProduct2()
    {
        $params = getParam();
        $products = $this->Prod->getWhere('spack_products', ['location' => empLoc()])->result();
        $prods = [];
        foreach ($products as $prod) {
            $prods['options'][] = [
                'value' => $prod->id,
                'text' => $prod->name,
                'selected' => isset($params['select']) && $params['select'] == $prod->id ? 1 : 0,
            ];
        }
        echo json_encode($prods);
    }

    public function getLocation()
    {
        $params = getParam();
        $locations = $this->Prod->getWhere('spack_locations', ['location' => empLoc()])->result();
        $locs = [];
        foreach ($locations as $loc) {
            $locs['options'][] = [
                'value' => $loc->id,
                'text' => $loc->name,
                'selected' => isset($params['select']) && $params['select'] == $prod->id ? 1 : 0,
            ];
        }
        echo json_encode($locs);
    }

    public function getProductType()
    {
        $params = getParam();
        $types = $this->Prod->getWhere('spack_product_types', ['location' => empLoc()])->result();
        $typeList = [];
        $typeList['options'][] = [
            'value' => '',
            'text' => '-Pilih Golongan Produk-',
            'selected' => 1,
        ];
        foreach ($types as $type) {
            $typeList['options'][] = [
                'value' => $type->id,
                'text' => $type->name,
                'selected' => isset($params['select']) && $params['select'] == $type->id ? 1 : 0,
            ];
        }
        echo json_encode($typeList);
    }

    public function getMakloon()
    {
        $params = getParam();
        $makloons = $this->Prod->getWhere('spack_makloons', ['location' => empLoc()])->result();
        $makloonList = [];
        $makloonList['options'][] = [
            'value' => '',
            'text' => '-Kosongkan Jika Bukan Makloon-',
            'selected' => 1,
        ];
        foreach ($makloons as $makloon) {
            $makloonList['options'][] = [
                'value' => $makloon->id,
                'text' => $makloon->name,
                'selected' => isset($params['select']) && $params['select'] == $makloon->id ? 1 : 0,
            ];
        }
        echo json_encode($makloonList);
    }

    public function spEntryForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $batch = $this->Prod->getDataById('spack_batch_numbers', $params['id']);
            fetchFormData($batch);
        } else {
            $post = prettyText(getPost(), null, ['no_batch']);
            if (!isset($post['id'])) {
                $this->createSpBatch($post);
            } else {
                $this->updateSpBatch($post);
            }
        }
    }

    public function createSpBatch($post)
    {
        $post['no_batch'] = str_replace(' ', '', strtoupper($post['no_batch']));
        $check = $this->Prod->getOne('spack_batch_numbers', [
            'no_batch' => $post['no_batch'],
        ]);
        isExist(["Nomor Batch $post[no_batch]" => $check]);

        $checkProduct = $this->Prod->getDataById('spack_products', $post['product_id']);
        if(!$checkProduct) {
            xmlResponse('error', 'Produk tidak ditemukan');
        }

        $post['location'] = empLoc();
        $post['sub_department_id'] = empSub();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->Prod->create('spack_batch_numbers', $post);
        xmlResponse('inserted', $post['no_batch']);
    }

    public function updateSpBatch($post)
    {
        $post['no_batch'] = str_replace(' ', '', strtoupper($post['no_batch']));
        $batch = $this->Prod->getDataById('spack_batch_numbers', $post['id']);
        isDelete(["Nomor batch $post[no_batch]" => $batch]);

        $checkProduct = $this->Prod->getDataById('spack_products', $post['product_id']);
        if(!$checkProduct) {
            xmlResponse('error', 'Produk tidak ditemukan');
        }

        $checkPrint = $this->Prod->getOne('spack_prints', ['no_batch' => $post['no_batch']]);
        if($checkPrint) {
            xmlResponse('error', "$post[no_batch] sudah digunakan & tidak bisa di edit, silahkan buat entry baru!");
        } 

        if ($batch->no_batch != $post['no_batch'] || $batch->product_id != $post['product_id']) {
            $check = $this->Prod->getOne('spack_batch_numbers', [
                'no_batch' => $post['no_batch'],
            ]);
            isExist(["Nomor Batch $post[no_batch]" => $check]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->Prod->updateById('spack_batch_numbers', $post, $post['id']);
        xmlResponse('updated', $post['no_batch']);
    }

    public function getSpEntryGrid()
    {
        $params = getParam();
        $batchs = $this->ProdModel->getSpEntry($params)->result();
        $xml = "";
        $no = 1;
        foreach ($batchs as $batch) {
            $xml .= "<row id='$batch->id'>";
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            $xml .= "<cell>" . cleanSC($batch->no_batch) . "</cell>";
            $xml .= "<cell>" . cleanSC($batch->product_name) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function spEntryDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $noBatch = $this->Prod->getDataById('spack_batch_numbers', $data->id)->no_batch;
            $checkPrint = $this->Prod->getOne('spack_prints', ['no_batch' => $noBatch]);
            if($checkPrint) {
                $mError .= "- $data->field sudah digunakan! <br>";
            } else {
                $this->Prod->delete('spack_batch_numbers', ['id' => $data->id]);
                $mSuccess .= "- $data->field berhasil dihapus <br>";
            }
        }
        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    public function createSpPrint()
    {
        $post = getPost();
        $checkPackingBy = $this->Hr->getOne('employees', ['nip' => $post['packing_by']]);
        $checkSpvBy = $this->Hr->getOne('employees', ['nip' => $post['spv_by']]);

        $batch = $this->Prod->getOne('spack_batch_numbers', ['no_batch' => $post['no_batch']]);
        isDelete(["Nomor Batch $post[no_batch]" => $batch]);

        if($post['packing_by'] != '') {
            if(!$checkPackingBy) {
                xmlResponse('error', 'Operator kemas tidak valid!');
            }
        }

        if($post['spv_by'] != '') {
            if(!$checkSpvBy) {
                xmlResponse('error', 'Spv tidak valid!');
            }
        }
       
        $check = $this->Prod->getOne('spack_prints', [
            'makloon' => $post['makloon'],
            'letter_date' => $post['letter_date'] ? $post['letter_date'] : NULL,
            'no_batch' => $post['no_batch'],
            'product_id' => $batch->product_id,
            'location_id' => $post['location_id'],
            'mfg_month' => $post['mfg_month'],
            'mfg_year' => $post['mfg_year'],
            'exp_month' => $post['exp_month'],
            'exp_year' => $post['exp_year'],
        ]);
        isExist(["Surat Pack" => $check]);
        unset($post['product_name']);
        $post['product_id'] = $batch->product_id;
        $post['packing_by'] = $checkPackingBy ? $checkPackingBy->id : NULL;
        $post['spv_by'] = $checkSpvBy ? $checkSpvBy->id : NULL;
        $post['created_by'] = empId();

        $insertId = $this->Prod->create('spack_prints', $post);
        xmlResponse('inserted', $post['no_batch']);
    }

    public function getSpPrint()
    {
        $params = getParam();
        $prints = $this->ProdModel->getSpPrint($params)->result();
        $xml = "";
        $no = 1;
        foreach ($prints as $print) {
            $lDate = $print->location.", ".toIndoDate($print->letter_date);
            $mfgDate = mToMonth($print->mfg_month).' '.$print->mfg_year;
            $expDate = mToMonth($print->exp_month).' '.$print->exp_year;
            $xml .= "<row id='$print->id'>";
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            $xml .= "<cell>" . cleanSC($print->no_batch) . "</cell>";
            $xml .= "<cell>" . cleanSC($print->product_name) . "</cell>";
            $xml .= "<cell>" . cleanSC($print->product_type) . "</cell>";
            $xml .= "<cell>" . cleanSC($print->package_desc) . "</cell>";
            $xml .= "<cell>" . cleanSC($print->makloon_name ? $print->makloon_name : '-') . "</cell>";
            $xml .= "<cell>" . cleanSC($lDate) . "</cell>";
            $xml .= "<cell>" . cleanSC($print->packing_by) . "</cell>";
            $xml .= "<cell>" . cleanSC($print->spv_by) . "</cell>";
            $xml .= "<cell>" . cleanSC($mfgDate) . "</cell>";
            $xml .= "<cell>" . cleanSC($expDate) . "</cell>";
            $xml .= "<cell>" . cleanSC($print->emp1) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function spPrintDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $this->Prod->delete('spack_prints', ['id' => $data->id]);
            $mSuccess .= "- $data->field berhasil dihapus <br>";
        }
        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    public function doSpPrint()
    {
        $params = getParam();
        if(isset($params['id']) && isset($params['no_batch']) && isset($params['package_desc']) && isset($params['total_print']) && isset($params['start_from'])) {
            $print = $this->ProdModel->getSpPrint(['equal_id' => $params['id']])->row();
            if($print) {
                $mfgDate = strtoupper(substr(mToMonth($print->mfg_month), 0, 3)) . ' ' . substr($print->mfg_year, 2, 4);
                $expDate = strtoupper(substr(mToMonth($print->exp_month), 0, 3)) . ' ' . substr($print->exp_year, 2, 4);
                $spackDate = $print->letter_date != '0000-00-00' ? spackDate($print->letter_date) : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                $data = [
                    'letter_date' => $print->location . ', ' .$spackDate,
                    'footer_date' => $spackDate,
                    'no_batch' => $print->no_batch,
                    'product_name' => $print->product_name,
                    'package_desc_ori' => $print->package_desc,
                    'product_type' => $print->product_type,
                    'package_desc' => $params['package_desc'],
                    'mfg_date' => $mfgDate,
                    'exp_date' => $expDate,
                    'packing_by' => $print->packing_by,
                    'spv_by' => $print->spv_by,
                    'total_print' => $params['total_print'],
                    'start_from' => $params['start_from'],
                    'makloon' => $print->makloon_name
                ];
                $this->load->view('html/surat_pack/print', $data);
            } else {
                $this->load->view('html/invalid_response', ['message' => 'Nomor Batch tidak ditemukan!']);
            }
        } else {
            $this->load->view('html/invalid_response', ['message' => 'Parameter tidak valid!']);
        }
    }
}
