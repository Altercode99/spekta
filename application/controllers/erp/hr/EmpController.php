<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dhtmlx\Connector\ComboConnector;

class EmpController extends Erp_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('HrModel');
        $this->HrModel->myConstruct('hr');

        $this->auth->isAuth();
    }

    public function getEmployees()
    {
        $emps = $this->HrModel->getEmployee(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($emps as $emp) {
            $xml .= "<row id='$emp->nip'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>0</cell>";
            $xml .= "<cell>". cleanSC($emp->nip) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->employee_name) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->dept_name) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->sub_name) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->division_name) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->rank_name) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    /* ========================= EMPLOYEE FUNCTIONS  =========================*/
    public function empGrid()
    {
        $emps = $this->HrModel->getEmployee(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($emps as $emp) {
            $xml .= "<row id='$emp->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->employee_name) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->nip) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->sap_id) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->parent_nik) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->nik) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->npwp) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->birth_place) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateDay($emp->birth_date))."</cell>";
            $xml .= "<cell>". cleanSC($emp->gender) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->religion) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->age) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->employee_status) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->os_name) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->address) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->phone) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->mobile) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->email) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->sk_number) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->sk_date) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDate($emp->sk_start_date))."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDate($emp->sk_end_date))."</cell>";
            $xml .= "<cell>". cleanSC($emp->direct_spv) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->dept_name) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->sub_name) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->division_name) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->rank_name) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->status) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function empForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $emp = $this->Hr->getDataById('employees', $params['id']);
            fetchFormData($emp);
        } else {
            $post = prettyText(getPost(), ['employee_name', 'birth_place', 'address'], ['sk_number']);
            if ($post['id'] === '') {
                $this->createEmp($post);
            } else {
                $this->updateEmp($post);
            }
        }
    }

    public function createEmp($post)
    {
        $nip = $this->Hr->getOne('employees', ['nip' => $post['nip']]);
        $sap_id = $this->Hr->getOne('employees', ['sap_id' => $post['sap_id']]);
        $parent_nik = $this->Hr->getOne('employees', ['parent_nik' => $post['parent_nik']]);
        $nik = $this->Hr->getOne('employees', ['nik' => $post['nik']]);
        if ($post['npwp'] === '-') {
            $npwp = false;
        } else {
            $npwp = $this->Hr->getOne('employees', ['npwp' => $post['npwp']]);
        }
        $email = $this->Hr->getOne('employees', ['email' => $post['email']]);

        isExist([
            'NPP' => $nip,
            'ID SAP' => $sap_id,
            'Nomor Kartu Keluarga' => $sap_id,
            'NIK' => $nik,
            'NPWP' => $npwp,
            'Email' => $email,
        ]);

        $codeLoc = $this->Main->getDataById('locations', $post['location_id'], 'code')->code;

        $post['location'] = $codeLoc;
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->Hr->create('employees', $post);
        $rank = [
            'emp_id' => $insertId,
            'department_id' => $post['department_id'],
            'sub_department_id' => $post['sub_department_id'],
            'division_id' => $post['division_id'],
            'rank_id' => $post['rank_id'],
            'sk_number' => $post['sk_number'],
            'sk_date' => $post['sk_date'],
            'start_date' => $post['sk_start_date'],
            'end_date' => $post['sk_end_date'],
            'created_by' => empId(),
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $rankId = $this->Hr->create('employee_ranks', $rank);
        $sallary = [
            'emp_id' => $insertId,
            'basic_sallary' => 0,
            'premi_overtime' => 0,
            'created_by' => empId(),
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $sallId = $this->Hr->create('employee_sallary', $sallary);
        xmlResponse('inserted', $post['employee_name']);
    }

    public function updateEmp($post)
    {
        $nip = null;
        $sap_id = null;
        $parent_nik = null;
        $nik = null;
        $npwp = null;
        $email = null;

        $emp = $this->Hr->getDataById('employees', $post['id']);

        if ($emp && $emp->nip !== $post['nip']) {
            $nip = $this->Hr->getOne('employees', ['nip' => $post['nip']]);
        }
        if ($emp && $emp->sap_id !== $post['sap_id']) {
            $card = $this->Hr->getOne('employees', ['sap_id' => $post['sap_id']]);
        }
        if ($emp && $emp->parent_nik !== $post['parent_nik']) {
            $parent_nik = $this->Hr->getOne('employees', ['parent_nik' => $post['parent_nik']]);
        }
        if ($emp && $emp->nik !== $post['nik']) {
            $nik = $this->Hr->getOne('employees', ['nik' => $post['nik']]);
        }
        if ($emp && $emp->npwp !== $post['npwp']) {
            if ($npwp === '-') {
                $npwp = false;
            } else {
                $npwp = $this->Hr->getOne('employees', ['npwp' => $post['npwp']]);
            }
        }
        if ($emp && $emp->email !== $post['email']) {
            $email = $this->Hr->getOne('employees', ['email' => $post['email']]);
        }

        isExist([
            'NPP' => $nip,
            'ID SAP' => $sap_id,
            'Nomor Kartu Keluarga' => $sap_id,
            'NIK' => $nik,
            'NPWP' => $npwp,
            'Email' => $email,
        ]);

        $codeLoc = $this->Main->getDataById('locations', $post['location_id'], 'code')->code;
        $post['location'] = $codeLoc;
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->Hr->updateById('employees', $post, $post['id']);
        xmlResponse('updated', $post['employee_name']);
    }

    public function empStatus()
    {
        $post = fileGetContent();
        $params = getParam();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        $status = $params['status'];
        $message = $status == 'ACTIVE' ? 'Aktifkan' : 'Non Aktifkan';
        foreach ($datas as $id => $data) {
            $emp = $this->Hr->getDataById('employees', $data->id);
            if ($emp->status !== $status) {
                $mSuccess .= "- $data->field berhasil di $message<br />";
                $this->Hr->setStatus('employees', $data->id, $status);
            } else {
                $mError .= "- $data->field sudah $status<br />";
            }
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    public function getDepartment()
    {
        $params = getParam();
        $depts = $this->Hr->getWhere('departments', ['location' => empLoc()], "*", null, ['name' => 'ASC'])->result();
        $deptList = [];
        foreach ($depts as $dept) {
            $deptList['options'][] = [
                'value' => $dept->id,
                'text' => $dept->name,
                'selected' => isset($params['select']) && $params['select'] == $dept->id ? 1 : 0,
            ];
        }
        echo json_encode($deptList);
    }

    public function getSubDepartment()
    {
        $params = getParam();
        $subList = [];
        if(isset($params['select']) && $params['select'] == 0) {
            $subList['options'][] = [
                'value' => 0,
                'text' => '-',
                'selected' => 1,
            ];
        } else {
            $subs = $this->Hr->getWhere('sub_departments', ['department_id' => $params['deptId']], "*", null, ['name' => 'ASC'])->result();
            if($subs) {
                $subList['options'][] = [
                    'value' => 0,
                    'text' => '-',
                ]; 
                foreach ($subs as $sub) {
                    $subList['options'][] = [
                        'value' => $sub->id,
                        'text' => $sub->name,
                        'selected' => isset($params['select']) && $params['select'] == $sub->id ? 1 : 0,
                    ];
                }
            } else {
                $subList['options'][] = [
                    'value' => 0,
                    'text' => '-',
                    'selected' => 1,
                ]; 
            }
        }
        echo json_encode($subList);
    }

    public function getRank()
    {
        $params = getParam();
        $datas = $this->Hr->getWhere('ranks', ['location' => empLoc()], "*", null, ['id' => 'ASC'])->result();
        $dataList = [];
        foreach ($datas as $data) {
            $dataList['options'][] = [
                'value' => $data->id,
                'text' => "$data->name ($data->grade)",
                'selected' => isset($params['select']) && $params['select'] == $data->id ? 1 : 0,
            ];
        }
        echo json_encode($dataList);
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

    public function getDivision()
    {
        $params = getParam();
        $divList = [];
        if(isset($params['select']) && $params['select'] == 0) {
            $divList['options'][] = [
                'value' => 0,
                'text' => "-",
                'selected' => 1,
            ];
        } else {
            $divs = $this->Hr->getWhere('divisions', ['sub_department_id' => $params['subDeptId']], "*", null, ['name' => 'ASC'])->result();
            if($divs) {
                foreach ($divs as $div) {
                    $divList['options'][] = [
                        'value' => $div->id,
                        'text' => $div->name,
                        'selected' => isset($params['select']) && $params['select'] == $div->id ? 1 : 0,
                    ];
                }
            } else {
                $divList['options'][] = [
                    'value' => 0,
                    'text' => '-',
                    'selected' => 1,
                ]; 
            }
        }
        echo json_encode($divList);
    }

    public function getTraining()
    {
        $params = getParam();
        $datas = $this->Hr->getWhere('trainings', ['location' => empLoc()], "*", null, ['name' => 'ASC'])->result();
        $dataList = [];
        if($datas) {
            foreach ($datas as $data) {
                $dataList['options'][] = [
                    'value' => $data->id,
                    'text' => $data->name,
                    'selected' => isset($params['select']) && $params['select'] == $data->id ? 1 : 0,
                ];
            }
        } else {
            $dataList['options'][] = [
                'value' => '',
                'text' => '-'
            ];
        }
        echo json_encode($dataList);
    }

    /* ========================= FAMILY FUNCTIONS  =========================*/
    public function familyGrid()
    {
        $params = getParam();
        $families = $this->Hr->getWhere('employee_families', ['emp_id' => $params['empId']], '*', null, ['family_name' => 'ASC'])->result();

        $xml = "";
        $no = 1;
        foreach ($families as $family) {
            $profession = $family->profession ? $family->profession : '-';
            $xml .= "<row id='$family->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($family->family_name) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateDay($family->birth_date))."</cell>";
            $xml .= "<cell>". cleanSC($family->relation) ."</cell>";
            $xml .= "<cell>". cleanSC($family->martial_status) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateDay($family->wedding_date))."</cell>";
            $xml .= "<cell>". cleanSC($profession) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function familyForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $emp = $this->Hr->getDataById('employee_families', $params['id']);
            fetchFormData($emp);
        } else {
            $post = prettyText(getPost(), ['family_name', 'description', 'profession']);
            if ($post['id'] === '') {
                $this->createFamily($post);
            } else {
                $this->updateFamily($post);
            }
        }
    }

    public function createFamily($post)
    {
        $isExist = $this->Hr->getWhere('employee_families', ['emp_id' => $post['emp_id'], 'family_name' => $post['family_name'], 'relation' => $post['relation']])->row();
        isExist(["$post[family_name] sebagai $post[relation]" => $isExist]);

        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->Hr->create('employee_families', $post);
        xmlResponse('inserted', $post['family_name']);
    }

    public function updateFamily($post)
    {
        $family = $this->Hr->getDataById('employee_families', $post['id']);
        isDelete(["$post[family_name] sebagai $post[relation]" => $family]);

        if ($family->family_name !== $post['family_name'] || $family->relation !== $post['relation']) {
            $isExist = $this->Hr->getOne('employee_families', ['emp_id' => $post['emp_id'], 'family_name' => $post['family_name'], 'relation' => $post['relation']]);
            isExist(["$post[family_name] sebagai $post[relation]" => $isExist]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->Hr->updateById('employee_families', $post, $post['id']);
        xmlResponse('updated', $post['family_name']);
    }

    public function familyDelete(Type $var = null)
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $mSuccess .= "- $data->field berhasil dihapus<br />";
            $this->Hr->deleteById('employee_families', $data->id);
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    /* ========================= RANK FUNCTIONS  =========================*/
    public function rankGrid()
    {
        $params = getParam();
        $ranks = $this->HrModel->getRanks($params['empId']);
        $xml = "";
        $no = 1;
       
        foreach ($ranks as $rank) {
            if($rank->status == 'CURRENT') {
                $status = 'Jabatan Saat Ini';
                $color = "bgColor='#efd898'";
            } else if($rank->status == 'ACTIVE') {
                $status = 'Jabatan Pelaksana';
                $color = "bgColor='#efd898'";
            } else if($rank->status == 'NONACTIVE') {
                $status = 'Riwayat Jabatan';
                $color = '';
            }

            $xml .= "<row id='$rank->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($rank->dept_name) ."</cell>";
            $xml .= "<cell>". cleanSC($rank->sub_name) ."</cell>";
            $xml .= "<cell>". cleanSC($rank->division_name) ."</cell>";
            $xml .= "<cell>". cleanSC($rank->rank_name) ."</cell>";
            $xml .= "<cell $color>". cleanSC($status) ."</cell>";
            $xml .= "<cell>". cleanSC($rank->sk_number) ."</cell>";
            $xml .= "<cell>". cleanSC($rank->sk_date) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDate($rank->start_date))."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDate($rank->end_date))."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($rank->created_at))."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function rankStatus()
    {
        $post = fileGetContent();
        $status = $post->status;
        $id = $post->id;
        if($status == 'ACTIVE') {
            $empId = $post->empId;
            $check = $this->Hr->getOne('employee_ranks', ['status' => 'ACTIVE', 'emp_id' => $empId]);
            if($check) {
                response(['status' => 'error', 'message' => 'Nonactifkan dahulu jabatan pelaksana saat ini!']);
            }

            $data = [
                'status' => 'ACTIVE',
                'updated_by' => empId(),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $this->Hr->updateById('employee_ranks', $data, $id);
            response(['status' => 'success', 'message' => 'Berhasil mengaktifkan jabatan']);
        } else if($status == 'NONACTIVE') {

            $data = [
                'status' => 'NONACTIVE',
                'updated_by' => empId(),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $this->Hr->updateById('employee_ranks', $data, $id);
            response(['status' => 'success', 'message' => 'Berhasil menonaktifkan jabatan']);
        }
    }

    public function rankForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $emp = $this->Hr->getDataById('employee_ranks', $params['id']);
            fetchFormData($emp);
        } else {
            $post = prettyText(getPost(), null, ['sk_number']);
            if ($post['id'] === '') {
                $this->createRank($post);
            } else {
                $this->updateRank($post);
            }
        }
    }

    public function createRank($post)
    {
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $lastRank = $post['last_rank'];
        unset($post['last_rank']);
        $insertId = $this->Hr->create('employee_ranks', $post);
        $rank = $this->Hr->getDataById('ranks', $post['rank_id']);
        if ($lastRank == 1) {
            $this->Hr->updateById('employees', [
                'department_id' => $post['department_id'],
                'sub_department_id' => $post['sub_department_id'],
                'division_id' => $post['division_id'],
                'rank_id' => $post['rank_id'],
                'sk_number' => $post['sk_number'],
                'sk_date' => $post['sk_date'],
                'sk_start_date' => $post['start_date'],
                'sk_end_date' => $post['end_date'],
                'updated_by' => empId(),
                'updated_at' => date('Y-m-d H:i:s'),
            ], $post['emp_id']);
        }
        xmlResponse('inserted', $rank->name);
    }

    public function updateRank($post)
    {
        $rank = $this->Hr->getDataById('employee_ranks', $post['id']);
        isDelete(["Jabatan dengan Nomor SK: $post[sk_number]" => $rank]);

        $data['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $lastRank = $post['last_rank'];
        unset($post['last_rank']);
        $this->Hr->updateById('employee_ranks', $post, $post['id']);
        $isUsed = $this->Hr->getOne('employees', ['sk_number' => $post['sk_number']]);
        if ($lastRank == 1 || $isUsed) {
            $this->Hr->updateById('employees', [
                'department_id' => $post['department_id'],
                'sub_department_id' => $post['sub_department_id'],
                'division_id' => $post['division_id'],
                'rank_id' => $post['rank_id'],
                'sk_number' => $post['sk_number'],
                'sk_date' => $post['sk_date'],
                'sk_start_date' => $post['start_date'],
                'sk_end_date' => $post['end_date'],
                'updated_by' => empId(),
                'updated_at' => date('Y-m-d H:i:s'),
            ], $post['emp_id']);
        }
        xmlResponse('updated', $post['sk_number']);
    }

    public function rankDelete(Type $var = null)
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $skNumber = $this->Hr->getDataById('employee_ranks', $data->id)->sk_number;
            $checkRange = $this->Hr->getWhere('employees', ['sk_number' => $skNumber])->row();
            if ($checkRange) {
                $mError .= "- $data->field sedang digunakan<br />";
            } else {
                $mSuccess .= "- $data->field berhasil dihapus<br />";
                $this->Hr->deleteById('employee_ranks', $data->id);
            }

        }
        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    /* ========================= EDUCATION FUNCTIONS  =========================*/
    public function eduGrid()
    {
        $params = getParam();
        $educations = $this->Hr->getWhere('employee_educations', ['emp_id' => $params['empId']], '*', null, ['created_at' => 'DESC'])->result();

        $xml = "";
        $no = 1;
        foreach ($educations as $edu) {
            $technique = $edu->is_technique == 0 ? 'Teknik' : 'Non Teknik';
            $xml .= "<row id='$edu->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($edu->level)."</cell>";
            $xml .= "<cell>". cleanSC(fillStrip($edu->major))."</cell>";
            $xml .= "<cell>". cleanSC(fillStrip($edu->description))."</cell>";
            $xml .= "<cell>". cleanSC(fillStrip($technique))."</cell>";
            $xml .= "<cell>". cleanSC($edu->school_name) ."</cell>";
            $xml .= "<cell>". cleanSC($edu->address) ."</cell>";
            $xml .= "<cell>". cleanSC(fillStrip($edu->sttb_number))."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDate($edu->sttb_date))."</cell>";
            $xml .= "<cell>". cleanSC($edu->graduation_year) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($edu->created_at))."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function eduForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $emp = $this->Hr->getDataById('employee_educations', $params['id']);
            fetchFormData($emp);
        } else {
            $post = prettyText(getPost(), ['major', 'school_name', 'address'], ['sttb_number']);
            if ($post['id'] === '') {
                $this->createEdu($post);
            } else {
                $this->updateEdu($post);
            }
        }
    }

    public function createEdu($post)
    {
        $isExist = $this->Hr->getWhere('employee_educations', ['emp_id' => $post['emp_id'], 'level' => $post['level'], 'school_name' => $post['school_name']])->row();
        isExist(["Pendidikan $post[level] $post[school_name]" => $isExist]);

        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->Hr->create('employee_educations', $post);
        xmlResponse('inserted', $post['school_name']);
    }

    public function updateEdu($post)
    {
        $edu = $this->Hr->getDataById('employee_educations', $post['id']);
        isDelete(["Pendidikan $post[level] $post[school_name]" => $edu]);

        if ($edu->level !== $post['level'] || $edu->school_name !== $post['school_name']) {
            $isExist = $this->Hr->getOne('employee_educations', ['emp_id' => $post['emp_id'], 'level' => $post['level'], 'school_name' => $post['school_name']]);
            isExist(["Pendidikan $post[level] $post[school_name]" => $isExist]);
        }

        $data['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->Hr->updateById('employee_educations', $post, $post['id']);
        xmlResponse('updated', $post['school_name']);
    }

    public function eduDelete(Type $var = null)
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $mSuccess .= "- $data->field berhasil dihapus<br />";
            $this->Hr->deleteById('employee_educations', $data->id);
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    /* ========================= TRAINING FUNCTIONS  =========================*/
    public function trainingGrid()
    {
        $params = getParam();
        $trainings = $this->HrModel->getTrainings($params['empId']);
        $xml = "";
        $no = 1;
        foreach ($trainings as $try) {
            $xml .= "<row id='$try->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($try->training_name) ."</cell>";
            $xml .= "<cell>". cleanSC($try->location) ."</cell>";
            $xml .= "<cell>". cleanSC($try->total_hour) ."</cell>";
            $xml .= "<cell>". cleanSC(fillStrip($try->description))."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDate($try->certificate_date))."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function trainingForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $emp = $this->Hr->getDataById('employee_trainings', $params['id']);
            fetchFormData($emp);
        } else {
            $post = prettyText(getPost(), ['description', 'location'], ['sttb_number']);
            if ($post['id'] === '') {
                $this->createTraining($post);
            } else {
                $this->updateTraining($post);
            }
        }
    }

    public function createTraining($post)
    {

        $tryName = $this->Hr->getDataById('trainings', $post['training_id'], 'name')->name;
        $isExist = $this->Hr->getOne('employee_trainings', ['emp_id' => $post['emp_id'], 'training_id' => $post['training_id'], 'location' => $post['location']]);
        isExist(["Pelatihan $tryName di lokasi $post[location]" => $isExist]);

        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->Hr->create('employee_trainings', $post);
        xmlResponse('inserted', $tryName);
    }

    public function updateTraining($post)
    {
        $try = $this->Hr->getDataById('employee_trainings', $post['id']);
        $tryName = $this->Hr->getDataById('trainings', $post['training_id'], 'name')->name;
        isDelete(["Pelatihan $tryName di lokasi $post[location]" => $try]);
        
        if ($try->training_id !== $post['training_id'] || $try->location !== $post['location']) {
            $isExist = $this->Hr->getOne('employee_trainings', ['emp_id' => $post['emp_id'], 'training_id' => $post['training_id'], 'location' => $post['location']]);
            isExist(["Pelatihan $tryName di lokasi $post[location]" => $try]);
        }

        $data['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');
        $this->Hr->updateById('employee_trainings', $post, $post['id']);
        xmlResponse('updated', $tryName);
    }

    public function trainingDelete(Type $var = null)
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $mSuccess .= "- $data->field berhasil dihapus<br />";
            $this->Hr->deleteById('employee_trainings', $data->id);
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    public function pinGrid()
    {
        $params = getParam();
        $pins = $this->HrModel->getPinGrid(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($pins as $pin) {
            $xml .= "<row id='$pin->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($pin->employee_name) ."</cell>";
            $xml .= "<cell>". cleanSC($pin->nip) ."</cell>";
            $xml .= "<cell>". cleanSC($pin->pin) ."</cell>";
            $xml .= "<cell>". cleanSC($pin->rank_name) ."</cell>";
            $xml .= "<cell>". cleanSC($pin->department) ."</cell>";
            $xml .= "<cell>". cleanSC($pin->sub_department) ."</cell>";
            $xml .= "<cell>". cleanSC($pin->division) ."</cell>";
            $xml .= "<cell>". cleanSC($pin->emp1) ."</cell>";
            $xml .= "<cell>". cleanSC($pin->emp2) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($pin->created_at))."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function pinForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $emp = $this->HrModel->getPinDetail($params, $params['id']);
            fetchFormData($emp);
        } else {
            $post = getPost();
            if (!isset($post['id'])) {
                $this->createPin($post);
            } else {
                $this->updatePin($post);
            }
        }
    }

    public function createPin($post)
    {
        $emp = $this->Hr->getOne('employees', ['nip' => $post['nip']]);
        if(!$emp) {
            xmlResponse('error', 'Karyawan tidak terdaftar!');
        }

        $checkPin = $this->Hr->getOne('employee_pins', ['pin' => $post['pin']]);
        isExist(["PIN tersebut" => $checkPin]);

        $data = [
            'location' => empLoc(),
            'emp_id' => $emp->id,
            'pin' => $post['pin'],
            'created_by' => empId(),
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $insert = $this->Hr->create('employee_pins', $data);
        xmlResponse('inserted', "PIN berhasil ditambahkan");
    }

    public function updatePin($post)
    {
        $pin = $this->Hr->getDataById('employee_pins', $post['id']);
        if($pin->pin != $post['pin']) {
            $checkPin = $this->Hr->getOne('employee_pins', ['pin' => $post['pin']]);
            isExist(["PIN tersebut" => $checkPin]);
        }

        $data = [
            'pin' => $post['pin'],
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $insert = $this->Hr->updateById('employee_pins', $data, $post['id']);
        xmlResponse('updated', "PIN berhasil diubah");
    }

    public function pinDelete(Type $var = null)
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $mSuccess .= "- $data->field berhasil dihapus<br />";
            $this->Hr->deleteById('employee_pins', $data->id);
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

}
