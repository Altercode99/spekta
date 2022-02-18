<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dhtmlx\Connector\ComboConnector;

class SallaryController extends Erp_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('HrModel');
        $this->HrModel->myConstruct('hr');

        $this->auth->isAuth();
    }

    /* ========================= BASIC SALLARY FUNCTIONS  =========================*/
    public function sallaryGrid()
    {
        $emps = $this->HrModel->getEmpSallary(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($emps as $emp) {
            $grade = $emp->grade !== "0-0" ? "($emp->grade)" : null;
            $xml .= "<row id='$emp->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->employee_name) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->basic_sallary) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->total_sallary) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->premi_overtime) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->overtime == 0 ? 'Jam Berjalan' : 'Jam Tetap') ."</cell>";
            $xml .= "<cell>". cleanSC("$emp->rank_name $grade") ."</cell>";
            $xml .= "<cell>". cleanSC($emp->department) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->sub_department) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->division) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->emp1) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->emp2) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($emp->created_at))."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function sallaryForm()
    {
        $emp = $this->HrModel->getEmpSallary(getParam())->row();
        fetchFormData($emp);
    }

    public function updateSallary()
    {
        $post = getPost();
        $totalSallary = $post['basic_sallary'];
        $data = [
            'basic_sallary' => $post['basic_sallary'],
            'total_sallary' => $totalSallary,
            'premi_overtime' => $totalSallary / 173,
            'created_by' => empId(),
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->Hr->update('employee_sallary', $data, ['emp_id' => $post['id']]);
        xmlResponse('updated', $post['employee_name']);
    }

    public function updateSallaryBatch()
    {
        $post = prettyText(getGridPost());
        $data = [];
        foreach ($post as $key => $value) {
            $totalSallary = $value['c2'];
            $data[] = [
                'emp_id' => $key,
                'basic_sallary' => $value['c2'],
                'total_sallary' => $totalSallary,
                'premi_overtime' => $totalSallary / 173,
                'updated_by' => empId(),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        $update = $this->Hr->updateMultiple('employee_sallary', $data, 'emp_id');
        if ($update) {
            xmlResponse('updated', 'Update grid gaji berhasil');
        } else {
            xmlResponse('error', 'Update grid gaji gagal!');
        }
    }

    /* ========================= SUPERIOR SALLARY FUNCTIONS  =========================*/
    public function superSallaryGrid()
    {
        $emps = $this->HrModel->getEmpSallary(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($emps as $emp) {
            $grade = $emp->grade !== "0-0" ? "($emp->grade)" : null;
            $xml .= "<row id='$emp->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->employee_name) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->basic_sallary) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->total_sallary) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->premi_overtime) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->overtime == 0 ? 'Jam Berjalan' : 'Jam Tetap') ."</cell>";
            $xml .= "<cell>". cleanSC("$emp->rank_name $grade") ."</cell>";
            $xml .= "<cell>". cleanSC($emp->department) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->sub_department) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->division) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->emp1) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->emp2) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDateTime($emp->created_at))."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function superSallaryForm()
    {
        $emp = $this->HrModel->getEmpSallary(getParam())->row();
        fetchFormData($emp);
    }

    public function updateSuperSallary()
    {
        $post = getPost();
        $totalSallary = $post['basic_sallary'];
        $data = [
            'basic_sallary' => $post['basic_sallary'],
            'total_sallary' => $totalSallary,
            'premi_overtime' => $post['premi_overtime'],
            'created_by' => empId(),
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->Hr->update('employee_sallary', $data, ['emp_id' => $post['id']]);
        xmlResponse('updated', $post['employee_name']);
    }

    public function updateSuperSallaryBatch()
    {
        $post = prettyText(getGridPost());
        $data = [];
        foreach ($post as $key => $value) {
            $totalSallary = $value['c2'];
            $data[] = [
                'emp_id' => $key,
                'basic_sallary' => $value['c2'],
                'total_sallary' => $totalSallary,
                'premi_overtime' => $value['c4'],
                'updated_by' => empId(),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        $update = $this->Hr->updateMultiple('employee_sallary', $data, 'emp_id');
        if ($update) {
            xmlResponse('updated', 'Update grid gaji berhasil');
        } else {
            xmlResponse('error', 'Update grid gaji gagal!');
        }
    }
}