<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AppMaster1Controller extends Erp_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('AppMasterModel', 'AppMaster');
        $this->AppMaster->myConstruct('main');

        $this->auth->isAuth();
    }

    public function getLocation()
    {
        $params = getParam();
        $datas = $this->Main->getWhere('locations', ['code' => empLoc()], "*", null, ['name' => 'ASC'])->result();
        $dataList = [];
        foreach ($datas as $data) {
            $dataList['options'][] = [
                'value' => $data->id,
                'text' => $data->name,
                'selected' => isset($params['select']) && $params['select'] == $data->id ? 1 : 0,
            ];
        }
        echo json_encode($dataList);
    }

    public function getBuilding()
    {
        $params = getParam();
        $datas = $this->General->getWhere('buildings', ['location' => empLoc()], "*", null, ['name' => 'ASC'])->result();
        $dataList = [];
        foreach ($datas as $data) {
            $dataList['options'][] = [
                'value' => $data->id,
                'text' => $data->name,
                'selected' => isset($params['select']) && $params['select'] == $data->id ? 1 : 0,
            ];
        }
        echo json_encode($dataList);
    }

    public function getBuildingRoom()
    {
        $params = getParam();
        $datas = $this->General->getWhere('building_rooms', ['building_id' => $params['buildId']], "*", null, ['name' => 'ASC'])->result();
        $dataList = [];
        foreach ($datas as $data) {
            $dataList['options'][] = [
                'value' => $data->id,
                'text' => $data->name,
                'selected' => isset($params['select']) && $params['select'] == $data->id ? 1 : 0,
            ];
        }
        echo json_encode($dataList);
    }

    public function getMachine()
    {
        $params = getParam();
        $datas = $this->Mtn->getWhere('production_machines', ['sub_department_id' => $params['subDeptId']], "*", null, ['name' => 'ASC'])->result();
        $dataList = [];
        foreach ($datas as $data) {
            $dataList['options'][] = [
                'value' => $data->id,
                'text' => $data->name,
                'selected' => isset($params['select']) && $params['select'] == $data->id ? 1 : 0,
            ];
        }
        echo json_encode($dataList);
    }

    /* ========================= LOCATION FUNCTIONS  =========================*/
    public function locGrid()
    {
        $locations = $this->AppMaster->getLocationWithUser(getParam());
        $xml = "";
        $no = 1;
        foreach ($locations as $loc) {
            $xml .= "<row id='$loc->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($loc->code) ."</cell>";
            $xml .= "<cell>". cleanSC($loc->name) ."</cell>";
            $xml .= "<cell>". cleanSC($loc->emp1) ."</cell>";
            $xml .= "<cell>". cleanSC($loc->emp2) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($loc->created_at)) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function locForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $location = $this->Main->getDataById('locations', $params['id'], 'id,code,name');
            fetchFormData($location);
        } else {
            $post = prettyText(getPost(), ['name'], ['code']);
            if (!isset($post['id'])) {
                $this->createLocation($post);
            } else {
                $this->updateLocation($post);
            }
        }
    }

    public function createLocation($post)
    {
        $location = $this->AppMaster->checkLocations($post);
        isExist(["Lokasi $post[name]" => $location]);

        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');
        $insertId = $this->Main->create('locations', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function updateLocation($post)
    {
        $location = $this->Main->getDataById('locations', $post['id']);
        isDelete(["Lokasi $post[name]" => $location]);

        $checkLoc = $this->AppMaster->checkLocations($post);
        if ($checkLoc && $checkLoc->name !== $post['name']) {
            $checkName = $this->Main->getWhere('locations', ['name' => $post['name']])->row();
            isExist(["Lokasi $post[name]" => $checkName]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->Main->updateById('locations', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }

    public function locDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $codeLoc = $this->Main->getDataById('locations', $data->id)->code;
            $isUsed = $this->Hr->getWhere('employees', ['location' => $codeLoc], 'location', 1)->row();
            if (!$isUsed) {
                $mSuccess .= "- $data->field berhasil dihapus <br>";
                $this->Main->delete('locations', ['id' => $data->id]);
            } else {
                $mError .= "- $data->field sudah digunakan! <br>";
            }
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    /* ========================= DEPARTMENTS FUNCTIONS  =========================*/
    public function deptGrid()
    {
        $departments = $this->AppMaster->getDeptWithUser(getParam());
        $xml = "";
        $no = 1;
        foreach ($departments as $dept) {
            $xml .= "<row id='$dept->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($dept->name) ."</cell>";
            $xml .= "<cell>". cleanSC($dept->emp1) ."</cell>";
            $xml .= "<cell>". cleanSC($dept->emp2) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($dept->created_at)) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function deptForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $department = $this->Hr->getDataById('departments', $params['id'], 'id,location,name');
            fetchFormData($department);
        } else {
            $post = prettyText(getPost(), ['name']);
            if (!isset($post['id'])) {
                $this->createDept($post);
            } else {
                $this->updateDept($post);
            }
        }
    }

    public function createDept($post)
    {
        $department = $this->Hr->getWhere('departments', ['name' => $post['name'], 'location' => empLoc()])->row();
        isExist(["Departemen $post[name]" => $department]);

        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->Hr->create('departments', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function updateDept($post)
    {
        $department = $this->Hr->getDataById('departments', $post['id']);
        isDelete(["Departemen $post[name]" => $department]);

        if ($department->name !== $post['name']) {
            $checkDept = $this->Hr->getWhere('departments', ['name' => $post['name'], 'location' => empLoc()])->row();
            isExist(["Departemen $post[name]" => $checkDept]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->Hr->updateById('departments', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }

    public function deptDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $isEmp = $this->Hr->getWhere('employees', ['department_id' => $data->id], 'department_id', 1)->row();
            $isSub = $this->Hr->getWhere('sub_departments', ['department_id' => $data->id], 'department_id', 1)->row();
            $isDiv = $this->Hr->getWhere('divisions', ['department_id' => $data->id], 'department_id', 1)->row();
            if (!$isEmp && !$isSub && !$isDiv) {
                $mSuccess .= "- $data->field berhasil dihapus <br>";
                $this->Hr->delete('departments', ['id' => $data->id]);
            } else {
                $message = "";
                if ($isEmp) {
                    $message .= $message == "" ? 'Data Kepegawaian' : ', Data Kepegawaian';
                }
                if ($isSub) {
                    $message .= $message == "" ? 'Data Sub Departemen' : ', Data Sub Departemen';
                }
                if ($isDiv) {
                    $message .= $message == "" ? 'Dokumen Divisi' : ', Dokumen Divisi';
                }
                $mError .= "- $data->field sudah digunakan pada ($message)! <br>";
            }
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    /* ========================= DEPARTMENTS FUNCTIONS  =========================*/
    public function subDeptGrid()
    {
        $subs = $this->AppMaster->getSubDeptWithUser(getParam());
        $xml = "";
        $no = 1;
        foreach ($subs as $sub) {
            $xml .= "<row id='$sub->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($sub->name) ."</cell>";
            $xml .= "<cell>". cleanSC($sub->department_name) ."</cell>";
            $xml .= "<cell>". cleanSC($sub->emp1) ."</cell>";
            $xml .= "<cell>". cleanSC($sub->emp2) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($sub->created_at)) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function subDeptForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $subDepartment = $this->Hr->getDataById('sub_departments', $params['id'], 'id,location,department_id,name,(file_limit / 1000000) as file_limit');
            fetchFormData($subDepartment);
        } else {
            $post = prettyText(getPost(), ['name']);
            if (!isset($post['id'])) {
                $this->createSubDept($post);
            } else {
                $this->updateSubDept($post);
            }
        }
    }

    public function createSubDept($post)
    {
        $subDepartment = $this->Hr->getWhere('sub_departments', ['name' => $post['name'], 'department_id' => $post['department_id']])->row();
        isExist(["Sub Departemen $post[name]" => $subDepartment]);

        $post['file_limit'] = $post['file_limit'] * 1000000;
        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->Hr->create('sub_departments', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function updateSubDept($post)
    {
        $subDepartment = $this->Hr->getDataById('sub_departments', $post['id']);
        isDelete(["Sub Departemen $post[name]" => $subDepartment]);

        if ($subDepartment->name !== $post['name']) {
            $checkSubDept = $this->Hr->getWhere('sub_departments', ['name' => $post['name'], 'department_id' => $post['department_id']])->row();
            isExist(["Sub Departemen $post[name]" => $checkSubDept]);
        }

        $post['file_limit'] = $post['file_limit'] * 1000000;
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->Hr->updateById('sub_departments', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }

    public function subDeptDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $isEmp = $this->Hr->getWhere('employees', ['sub_department_id' => $data->id], 'sub_department_id', 1)->row();
            $isDiv = $this->Hr->getWhere('divisions', ['sub_department_id' => $data->id], 'sub_department_id', 1)->row();
            $isFile = $this->Qhse->getWhere('main_folders', ['sub_department_id' => $data->id], 'sub_department_id', 1)->row();
            $isAccess = $this->Main->getWhere('users_menu', ['sub_id' => $data->id], 'sub_id', 1)->row();

            if (!$isEmp && !$isDiv && !$isFile && !$isAccess && $data->id != 11 && $data->id != 5) {
                $mSuccess .= "- $data->field berhasil dihapus <br>";
                $this->Hr->delete('sub_departments', ['id' => $data->id]);
            } else {
                $message = "";
                if ($isEmp) {
                    $message .= $message == "" ? 'Data Kepegawaian' : ', Data Kepegawaian';
                }
                if ($isDiv) {
                    $message .= $message == "" ? 'Data Divisi' : ', Data Sub Divisi';
                }
                if ($isFile) {
                    $message .= $message == "" ? 'Dokumen Kontrol' : ', Dokumen Kontrol';
                }
                if ($isAccess || $isAccessDept) {
                    $message .= $message == "" ? 'Konfigurasi Akses' : ', Konfigurasi Akses';
                }
                $mError .= "- $data->field sudah digunakan pada ($message)! <br>";
            }
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    /* ========================= RANK FUNCTIONS  =========================*/
    public function rankGrid()
    {
        $ranks = $this->AppMaster->getRankWithUser(getParam());
        $xml = "";
        $no = 1;
        foreach ($ranks as $rank) {
            $name = $rank->grade !== '0-0' ? "$rank->name ($rank->grade)" : $rank->name;
            $xml .= "<row id='$rank->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($name) ."</cell>";
            $xml .= "<cell>". cleanSC($rank->emp1) ."</cell>";
            $xml .= "<cell>". cleanSC($rank->emp2) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($rank->created_at)) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function rankForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $rank = $this->Hr->getDataById('ranks', $params['id'], 'id,grade,name');
            fetchFormData($rank);
        } else {
            $post = prettyText(getPost(), ['name']);
            if (!isset($post['id'])) {
                $this->createRank($post);
            } else {
                $this->updateRank($post);
            }
        }
    }

    public function createRank($post)
    {
        $this->checkGrade($post['grade']);
        $rank = $this->Hr->getWhere('ranks', ['name' => $post['name'], 'grade' => $post['grade'], 'location' => empLoc()])->row();
        isExist(["Jabatan $post[name]" => $rank]);

        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->Hr->create('ranks', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function updateRank($post)
    {
        $this->checkGrade($post['grade']);
        $rank = $this->Hr->getDataById('ranks', $post['id']);
        isDelete(["Jabatan $post[name]" => $rank]);

        if ($rank->name !== $post['name'] || $rank->grade !== $post['grade']) {
            $checkRank = $this->Hr->getWhere('ranks', ['name' => $post['name'], 'grade' => $post['grade'], 'location' => empLoc()])->row();
            isExist(["Jabatan $post[name]" => $checkRank]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->Hr->updateById('ranks', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }

    public function checkGrade($rankGrade)
    {
        $grade = explode('-', $rankGrade);
        if (count($grade) < 2) {
            xmlResponse('error', 'Format grade adalah contoh: 8-10!');
        } else {
            foreach ($grade as $key => $value) {
                if (!is_numeric($value)) {
                    xmlResponse('error', 'Masukan angka saja dalam form grade!');
                }
            }
        }
    }

    public function rankDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $isEmp = $this->Hr->getWhere('employees', ['rank_id' => $data->id], 'rank_id', 1)->row();
            $isAccess = $this->Main->getWhere('users_menu', ['rank_id' => $data->id], 'rank_id', 1)->row();
            if (!$isEmp && !$isAccess) {
                $mSuccess .= "- $data->field berhasil dihapus <br>";
                $this->Hr->delete('ranks', ['id' => $data->id]);
            } else {
                $message = "";
                if ($isEmp) {
                    $message .= $message == "" ? 'Data Kepegawaian' : ', Data Kepegawaian';
                }
                if ($isAccess) {
                    $message .= $message == "" ? 'Konfigurasi Akses' : ', Konfigurasi Akses';
                }
                $mError .= "- $data->field sudah digunakan pada ($message)! <br>";
            }

        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    /* ========================= DIVISION FUNCTIONS  =========================*/
    public function divGrid()
    {
        $divs = $this->AppMaster->getDivisionWithUser(getParam());
        $xml = "";
        $no = 1;
        foreach ($divs as $div) {
            $xml .= "<row id='$div->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($div->name) ."</cell>";
            $xml .= "<cell>". cleanSC($div->sub_department_name) ."</cell>";
            $xml .= "<cell>". cleanSC($div->department_name) ."</cell>";
            $xml .= "<cell>". cleanSC($div->emp1) ."</cell>";
            $xml .= "<cell>". cleanSC($div->emp2) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($div->created_at)) ."</cell>";
            $xml .= "</row>";
            $no++;
        }

        gridXmlHeader($xml);
    }

    public function divForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $div = $this->Hr->getDataById('divisions', $params['id'], 'id,sub_department_id,department_id,name');
            fetchFormData($div);
        } else {
            $post = prettyText(getPost(), ['name']);
            if (!isset($post['id'])) {
                $this->createDivision($post);
            } else {
                $this->updateDivision($post);
            }
        }
    }

    public function createDivision($post)
    {
        $div = $this->Hr->getWhere('divisions', ['name' => $post['name'], 'sub_department_id' => $post['sub_department_id']])->row();
        isExist(["Divisi $post[name]" => $div]);

        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->Hr->create('divisions', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function updateDivision($post)
    {
        $div = $this->Hr->getDataById('divisions', $post['id']);
        isDelete(["Divisi $post[name]" => $div]);

        if ($div->name !== $post['name'] || $div->sub_department_id !== $post['sub_department_id']) {
            $checkdiv = $this->Hr->getWhere('divisions', ['name' => $post['name'], 'sub_department_id' => $post['sub_department_id']])->row();
            isExist(["Divisi $post[name]" => $checkdiv]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->Hr->updateById('divisions', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }

    public function divDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $isUsed = $this->Hr->getWhere('employees', ['division_id' => $data->id], 'division_id', 1)->row();
            if (!$isUsed) {
                $mSuccess .= "- $data->field berhasil dihapus <br>";
                $this->Hr->delete('divisions', ['id' => $data->id]);
            } else {
                $mError .= "- $data->field sudah digunakan! <br>";
            }
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    /* ========================= TRAINING FUNCTIONS  =========================*/
    public function trainingGrid()
    {
        $trainings = $this->AppMaster->getTrainingWithUser(getParam());
        $xml = "";
        $no = 1;
        foreach ($trainings as $training) {
            $xml .= "<row id='$training->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($training->name) ."</cell>";
            $xml .= "<cell>". cleanSC($training->emp1) ."</cell>";
            $xml .= "<cell>". cleanSC($training->emp2) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($training->created_at)) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function trainingForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $div = $this->Hr->getDataById('trainings', $params['id'], 'id,name');
            fetchFormData($div);
        } else {
            $post = prettyText(getPost(), ['name']);
            if (!isset($post['id'])) {
                $this->createTraining($post);
            } else {
                $this->updateTraining($post);
            }
        }
    }

    public function createTraining($post)
    {
        $div = $this->Hr->getWhere('trainings', ['name' => $post['name'], 'location' => empLoc()])->row();
        isExist(["Pelatihan $post[name]" => $div]);

        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->Hr->create('trainings', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function updateTraining($post)
    {
        $div = $this->Hr->getDataById('trainings', $post['id']);
        isDelete(["Pelatihan $post[name]" => $div]);

        if ($div->name !== $post['name']) {
            $checkdiv = $this->Hr->getWhere('trainings', ['name' => $post['name'], 'location' => empLoc()])->row();
            isExist(["Pelatihan $post[name]" => $checkdiv]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->Hr->updateById('trainings', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }

    public function trainingDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $isUsed = $this->Hr->getWhere('employee_trainings', ['training_id' => $data->id], 'training_id', 1)->row();
            if (!$isUsed) {
                $mSuccess .= "- $data->field berhasil dihapus  <br>";
                $this->Hr->delete('trainings', ['id' => $data->id]);
            } else {
                $mError .= "- $data->field sudah digunakan!  <br>";
            }
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    /* ========================= MEETING ROOM FUNCTIONS  =========================*/
    public function roomGrid()
    {
        $rooms = $this->AppMaster->getRoomsWithUser(getParam());
        $xml = "";
        $no = 1;
        foreach ($rooms as $room) {
            $xml .= "<row id='$room->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($room->name) ."</cell>";
            $xml .= "<cell>". cleanSC($room->color) ."</cell>";
            $xml .= "<cell>". cleanSC($room->color) ."</cell>";
            $xml .= "<cell>". cleanSC($room->capacity) ."</cell>";
            $xml .= "<cell>". cleanSC($room->building) ."</cell>";
            $xml .= "<cell>". cleanSC($room->on_floor) ."</cell>";
            $xml .= "<cell>". cleanSC($room->emp1) ."</cell>";
            $xml .= "<cell>". cleanSC($room->emp2) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($room->created_at)) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function checkBeforeAddFile()
    {
        $post = fileGetContent();
        $id = $post->id;
        $isExist = false;
        if (!$id) {
            $checkRoom = $this->General->getOne('meeting_rooms', ['name' => $post->name, 'location' => empLoc()]);
            if ($checkRoom) {
                $isExist = true;
            }
        } else {
            $room = $this->General->getDataById('meeting_rooms', $id);
            if ($room) {
                if ($room->name !== $post->name) {
                    $checkRoom = $this->General->getOne('meeting_rooms', ['name' => $post->name, 'location' => empLoc()]);
                    if ($checkRoom) {
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
            response(['status' => 'exist', 'message' => 'Nama ruang meeting sudah digunakan!']);
        }
    }

    public function updateAfterUpload()
    {
        $post = fileGetContent();
        $id = $post->id;
        $filename = $post->filename;
        $oldFile = $post->oldFile;
        $folder = $post->folder;

        $data = $this->Main->getDataById($folder, $id);

        if (!$data) {
            response(['status' => 'deleted', 'message' => 'Gagal update foto!']);
        }
        $fexp = explode(".", $folder)[1];
        $this->Main->updateById($folder, ['filename' => $filename], $id);
        if ($oldFile !== '' && file_exists('./assets/images/' . $fexp . '/' . $oldFile)) {
            unlink('./assets/images/' . $fexp . '/' . $oldFile);
        }

        response(['status' => 'success', 'message' => 'Berhasil update foto']);
    }

    public function fileUpload()
    {
        $params = getParam();
        $save = isset($params['save']) ? $params['save'] : true;
        $this->uploadTempImage('images/' . $params['folder'], null, $save);
    }

    public function roomForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $room = $this->General->getDataById('meeting_rooms', $params['id']);
            fetchFormData($room);
        } else {
            $post = prettyText(getPost(), ['name', 'building', 'facility']);
            if (!isset($post['id'])) {
                $this->createRoom($post);
            } else {
                $this->updateRoom($post);
            }
        }
    }

    public function createRoom($post)
    {
        $room = $this->General->getOne('meeting_rooms', ['name' => $post['name'], 'location' => empLoc()]);
        isExist(["Ruang meeting $post[name]" => $room]);

        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->General->create('meeting_rooms', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function updateRoom($post)
    {
        $room = $this->General->getDataById('meeting_rooms', $post['id']);
        isDelete(["Ruang meeting $post[name]" => $room]);

        if ($room->name !== $post['name']) {
            $room = $this->General->getWhere('meeting_rooms', ['name' => $post['name'], 'location' => empLoc()])->row();
            isExist(["Ruang meeting $post[name]" => $room]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->General->updateById('meeting_rooms', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }

    public function updateRoomBatch()
    {
        $post = prettyText(getGridPost(), ['c5']);
        $data = [];
        foreach ($post as $key => $value) {
            $data[] = [
                'id' => $key,
                'color' => $value['c2'],
                'capacity' => $value['c4'],
                'building' => $value['c5'],
                'on_floor' => $value['c6'],
                'updated_by' => empId(),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        $update = $this->General->updateMultiple('meeting_rooms', $data, 'id');
        if ($update) {
            xmlResponse('updated', 'Update grid ruang meeting berhasil');
        } else {
            xmlResponse('error', 'Update grid ruang meeting gagal!');
        }
    }

    public function roomDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $isUsed = $this->General->getWhere('meeting_rooms_reservation', ['room_id' => $data->id], 'room_id', 1)->row();
            if (!$isUsed) {
                $room = $this->General->getDataById('meeting_rooms', $data->id);
                $this->General->delete('meeting_rooms', ['id' => $data->id]);
                if (file_exists('./assets/images/meeting_rooms/' . $room->filename)) {
                    unlink('./assets/images/meeting_rooms/' . $room->filename);
                }
                $mSuccess .= "- $data->field berhasil dihapus  <br>";
            } else {
                $mError .= "- $data->field sudah digunakan! <br>";
            }
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    /* ========================= MEETING ROOM FUNCTIONS  =========================*/
    public function vehicleGrid()
    {
        $rooms = $this->AppMaster->getVehiclesWithUser(getParam());
        $xml = "";
        $no = 1;
        foreach ($rooms as $room) {
            $xml .= "<row id='$room->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($room->name) ."</cell>";
            $xml .= "<cell>". cleanSC($room->brand) ."</cell>";
            $xml .= "<cell>". cleanSC($room->type) ."</cell>";
            $xml .= "<cell>". cleanSC($room->color) ."</cell>";
            $xml .= "<cell>". cleanSC($room->color) ."</cell>";
            $xml .= "<cell>". cleanSC($room->police_no) ."</cell>";
            $xml .= "<cell>". cleanSC($room->passenger_capacity) ."</cell>";
            $xml .= "<cell>". cleanSC($room->bpkb_no) ."</cell>";
            $xml .= "<cell>". cleanSC($room->stnk_no) ."</cell>";
            $xml .= "<cell>". cleanSC($room->machine_no) ."</cell>";
            $xml .= "<cell>". cleanSC($room->machine_capacity) ."</cell>";
            $xml .= "<cell>". cleanSC($room->last_km) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoSlash($room->last_service_date)) ."</cell>";
            $xml .= "<cell>". cleanSC($room->emp1) ."</cell>";
            $xml .= "<cell>". cleanSC($room->emp2) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($room->created_at)) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function checkBeforeAddFile2()
    {
        $post = fileGetContent();
        $id = $post->id;
        $isExist = false;
        if (!$id) {
            $checkVehicle = $this->General->getOne('vehicles', ['police_no' => $post->police_no, 'location' => empLoc()]);
            if ($checkVehicle) {
                $isExist = true;
            }
        } else {
            $vehicle = $this->General->getDataById('vehicles', $id);
            if ($vehicle) {
                if ($vehicle->police_no !== $post->police_no) {
                    $checkVehicle = $this->General->getOne('vehicles', ['police_no' => $post->police_no, 'location' => empLoc()]);
                    if ($checkVehicle) {
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
            response(['status' => 'exist', 'message' => 'Nomor polisi kendaraan sudah digunakan!']);
        }
    }

    public function vehicleForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $vehicle = $this->General->getDataById('vehicles', $params['id']);
            fetchFormData($vehicle);
        } else {
            $post = prettyText(getPost(), ['name', 'brand', 'type'], ['police_no', 'bpkb_no', 'stnk_no', 'machine_no']);
            if (!isset($post['id'])) {
                $this->createVehicle($post);
            } else {
                $this->updateVehicle($post);
            }
        }
    }

    public function createVehicle($post)
    {
        $name = $this->General->getOne('vehicles', ['name' => $post['name'], 'location' => empLoc()]);
        $police_no = $this->General->getOne('vehicles', ['police_no' => $post['police_no']]);
        $bpkb_no = $this->General->getOne('vehicles', ['bpkb_no' => $post['bpkb_no']]);
        $stnk_no = $this->General->getOne('vehicles', ['stnk_no' => $post['stnk_no']]);
        $machine_no = $this->General->getOne('vehicles', ['machine_no' => $post['machine_no']]);

        isExist([
            'Nama' => $name,
            'Nomor Polisi' => $police_no,
            'Nomor BPKB' => $bpkb_no,
            'Nomor STNK' => $stnk_no,
            'Nomor Mesin' => $machine_no,
        ]);

        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->General->create('vehicles', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function updateVehicle($post)
    {
        $room = $this->General->getDataById('vehicles', $post['id']);
        isDelete(["Kendaraan $post[name]" => $room]);

        $name = null;
        $police_no = null;
        $bpkb_no = null;
        $stnk_no = null;
        $machine_no = null;

        if ($room && $room->name !== $post['name']) {
            $name = $this->General->getOne('vehicles', ['name' => $post['name'], 'location' => empLoc()]);
        }
        if ($room && $room->police_no !== $post['police_no']) {
            $police_no = $this->General->getOne('vehicles', ['police_no' => $post['police_no']]);
        }
        if ($room && $room->bpkb_no !== $post['bpkb_no']) {
            $bpkb_no = $this->General->getOne('vehicles', ['bpkb_no' => $post['bpkb_no']]);
        }
        if ($room && $room->stnk_no !== $post['stnk_no']) {
            $stnk_no = $this->General->getOne('vehicles', ['stnk_no' => $post['stnk_no']]);
        }
        if ($room && $room->machine_no !== $post['machine_no']) {
            $machine_no = $this->General->getOne('vehicles', ['machine_no' => $post['machine_no']]);
        }

        isExist([
            'Nama' => $name,
            'Nomor Polisi ' => $police_no,
            'Nomor BPKB' => $bpkb_no,
            'Nomor STNK' => $stnk_no,
            'Nomor Mesin' => $machine_no,
        ]);

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->General->updateById('vehicles', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }

    public function updateVehicleBatch()
    {
        $post = prettyText(getGridPost(), ['c5']);
        $data = [];
        foreach ($post as $key => $value) {
            $data[] = [
                'id' => $key,
                'brand' => $value['c2'],
                'type' => $value['c3'],
                'color' => $value['c4'],
                'passenger_capacity' => $value['c7'],
                'machine_capacity' => $value['c11'],
                'last_km' => $value['c12'],
                'last_service_date' => substrDate($value['c13']),
                'updated_by' => empId(),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        $update = $this->General->updateMultiple('vehicles', $data, 'id');
        if ($update) {
            xmlResponse('updated', 'Update grid kendaraan berhasil');
        } else {
            xmlResponse('error', 'Update grid kendaraan gagal!');
        }
    }

    public function vehicleDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $isUsed = $this->General->getOne('vehicles_reservation', ['vehicle_id' => $data->id]);
            if(!$isUsed) {
                $vehicles = $this->General->getDataById('vehicles', $data->id);
                $this->General->delete('vehicles', ['id' => $data->id]);
                if (file_exists('./assets/images/vehicles/' . $vehicles->filename)) {
                    unlink('./assets/images/vehicles/' . $vehicles->filename);
                }
                $mSuccess .= "- $data->field berhasil dihapus <br>";
            } else {
                $mError .= "- $data->field sudah digunakan! <br>";
            }
            
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    /* ========================= BUILDING FUNCTIONS  =========================*/
    public function buildingGrid()
    {
        $buildings = $this->AppMaster->getBuildingWithUser(getParam());
        $xml = "";
        $no = 1;
        foreach ($buildings as $building) {
            $xml .= "<row id='$building->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($building->name) ."</cell>";
            $xml .= "<cell>". cleanSC($building->emp1) ."</cell>";
            $xml .= "<cell>". cleanSC($building->emp2) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($building->created_at)) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function buildingForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $div = $this->General->getDataById('buildings', $params['id'], 'id,name');
            fetchFormData($div);
        } else {
            $post = prettyText(getPost(), ['name']);
            if (!isset($post['id'])) {
                $this->createBuilding($post);
            } else {
                $this->updateBuilding($post);
            }
        }
    }

    public function createBuilding($post)
    {
        $div = $this->General->getWhere('buildings', ['name' => $post['name'], 'location' => empLoc()])->row();
        isExist(["Gedung $post[name]" => $div]);

        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->General->create('buildings', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function updateBuilding($post)
    {
        $div = $this->General->getDataById('buildings', $post['id']);
        isDelete(["Gedung $post[name]" => $div]);

        if ($div->name !== $post['name']) {
            $checkdiv = $this->General->getWhere('buildings', ['name' => $post['name'], 'location' => empLoc()])->row();
            isExist(["Gedung $post[name]" => $checkdiv]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->General->updateById('buildings', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }

    public function buildingDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $isBuildingRoom = $this->General->getWhere('building_rooms', ['building_id' => $data->id], 'building_id', 1)->row();
            $isUsed = $this->Mtn->getWhere('production_machines', ['building_id' => $data->id], 'building_id', 1)->row();
            if (!$isUsed && !$isBuildingRoom) {
                $mSuccess .= "- $data->field berhasil dihapus  <br>";
                $this->General->delete('buildings', ['id' => $data->id]);
            } else {
                $mError .= "- $data->field sudah digunakan!  <br>";
            }
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    /* ========================= BUILDING ROOM FUNCTIONS  =========================*/
    public function buildRoomGrid()
    {
        $buildings = $this->AppMaster->getBuildingRoomWithUser(getParam());
        $xml = "";
        $no = 1;
        foreach ($buildings as $building) {
            $xml .= "<row id='$building->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($building->name) ."</cell>";
            $xml .= "<cell>". cleanSC($building->building) ."</cell>";
            $xml .= "<cell>". cleanSC($building->emp1) ."</cell>";
            $xml .= "<cell>". cleanSC($building->emp2) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($building->created_at)) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function buildRoomForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $div = $this->General->getDataById('building_rooms', $params['id'], 'id,building_id,name');
            fetchFormData($div);
        } else {
            $post = prettyText(getPost(), ['name']);
            if (!isset($post['id'])) {
                $this->createBuildRoom($post);
            } else {
                $this->updateBuildRoom($post);
            }
        }
    }

    public function createBuildRoom($post)
    {
        $div = $this->General->getWhere('building_rooms', ['name' => $post['name'], 'building_id' => $post['building_id']])->row();
        isExist(["Gedung $post[name]" => $div]);

        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->General->create('building_rooms', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function updateBuildRoom($post)
    {
        $div = $this->General->getDataById('building_rooms', $post['id']);
        isDelete(["Gedung $post[name]" => $div]);

        if ($div->name !== $post['name'] || $div->building_id !== $post['building_id']) {
            $checkdiv = $this->General->getWhere('building_rooms', ['name' => $post['name'], 'building_id' => $post['building_id']])->row();
            isExist(["Gedung $post[name]" => $checkdiv]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->General->updateById('building_rooms', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }

    public function buildRoomDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $isUsed = $this->Mtn->getWhere('production_machines', ['room_id' => $data->id], 'room_id', 1)->row();
            if (!$isUsed) {
                $mSuccess .= "- $data->field berhasil dihapus  <br>";
                $this->General->delete('building_rooms', ['id' => $data->id]);
            } else {
                $mError .= "- $data->field sudah digunakan!  <br>";
            }
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    /* ========================= BUILDING ROOM FUNCTIONS  =========================*/
    public function prodMachineGrid()
    {
        $machines = $this->AppMaster->getProdMachinemWithUser(getParam());
        $xml = "";
        $no = 1;
        foreach ($machines as $machine) {
            $xml .= "<row id='$machine->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($machine->name) ."</cell>";
            $xml .= "<cell>". cleanSC($machine->building) ."</cell>";
            $xml .= "<cell>". cleanSC($machine->room) ."</cell>";
            $xml .= "<cell>". cleanSC($machine->department) ."</cell>";
            $xml .= "<cell>". cleanSC($machine->sub_department) ."</cell>";
            $xml .= "<cell>". cleanSC($machine->division) ."</cell>";
            $xml .= "<cell>". cleanSC($machine->dimension) ."</cell>";
            $xml .= "<cell>". cleanSC($machine->emp1) ."</cell>";
            $xml .= "<cell>". cleanSC($machine->emp2) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($machine->created_at)) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function checkBeforeAddFile3()
    {
        $post = fileGetContent();
        $id = $post->id;
        $isExist = false;
        if (!$id) {
            $checkMachine = $this->Mtn->getOne('production_machines', [
                'name' => $post->name,
                'building_id' => $post->building_id,
                'room_id' => $post->room_id,
                'department_id' => $post->department_id,
                'sub_department_id' => $post->sub_department_id,
                'division_id' => $post->division_id,
            ]);
            if ($checkMachine) {
                $isExist = true;
            }
        } else {
            $machine = $this->Mtn->getDataById('production_machines', $id);
            if ($machine) {
                if ($machine->name !== $post->name || $machine->building_id !== $post->building_id || $machine->room_id !== $post->room_id ||
                    $machine->department_id !== $post->department_id || $machine->sub_department_id !== $post->sub_department_id || $machine->division_id !== $post->division_id) {
                    $checkMachine = $this->Mtn->getOne('production_machines', [
                        'name' => $post->name,
                        'building_id' => $post->building_id,
                        'room_id' => $post->room_id,
                        'department_id' => $post->department_id,
                        'sub_department_id' => $post->sub_department_id,
                        'division_id' => $post->division_id,
                    ]);
                    if ($checkMachine) {
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
            response(['status' => 'exist', 'message' => 'Data mesin produksi sudah digunakan!']);
        }
    }

    public function prodMachineForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $div = $this->Mtn->getDataById('production_machines', $params['id'], 'id,name,room_id,building_id,division_id,sub_department_id,department_id,dimension,filename');
            fetchFormData($div);
        } else {
            $post = prettyText(getPost(), ['name']);
            if (!isset($post['id'])) {
                $this->createProdMachine($post);
            } else {
                $this->updateProdMachine($post);
            }
        }
    }

    public function createProdMachine($post)
    {
        $checkMachine = $this->Mtn->getOne('production_machines', [
            'name' => $post['name'],
            'building_id' => $post['building_id'],
            'room_id' => $post['room_id'],
            'department_id' => $post['department_id'],
            'sub_department_id' => $post['sub_department_id'],
            'division_id' => $post['division_id'],
        ]);

        isExist(["Mesin produksi $post[name]" => $checkMachine]);

        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->Mtn->create('production_machines', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function updateProdMachine($post)
    {
        $machine = $this->Mtn->getDataById('production_machines', $post['id']);
        isDelete(["Mesin produksi $post[name]" => $machine]);

        if ($machine->name !== $post['name'] || $machine->building_id !== $post['building_id'] || $machine->room_id !== $post['room_id'] ||
            $machine->department_id !== $post['department_id'] || $machine->sub_department_id !== $post['sub_department_id'] || $machine->division_id !== $post['division_id']) {
            $checkMachine = $this->Mtn->getOne('production_machines', [
                'name' => $post['name'],
                'building_id' => $post['building_id'],
                'room_id' => $post['room_id'],
                'department_id' => $post['department_id'],
                'sub_department_id' => $post['sub_department_id'],
                'division_id' => $post['division_id'],
            ]);
            isExist(["Mesin produksi $post[name]" => $checkMachine]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->Mtn->updateById('production_machines', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }

    public function prodMachineDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $checkMachine1 = $this->Hr->getOne('employee_overtimes_detail', ['machine_1' => $data->id]);
            $checkMachine2 = $this->Hr->getOne('employee_overtimes_detail', ['machine_2' => $data->id]);
            if(!$checkMachine1 && !$checkMachine2) {
                $machine = $this->Mtn->getDataById('production_machines', $data->id);
                $this->Mtn->delete('production_machines', ['id' => $data->id]);
                if (file_exists('./assets/images/production_machines/' . $machine->filename)) {
                    unlink('./assets/images/production_machines/' . $machine->filename);
                }
                $mSuccess .= "- $data->field berhasil dihapus <br>";
            } else {
                $mError .= "- $data->field sudah digunakan! <br>";
            }
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }
}
