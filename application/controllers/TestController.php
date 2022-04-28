<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TestController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('SimpleXLSX');
        // $this->load->model('BasicModel', 'Hr');
        // $this->Hr->myConstruct('hr');
        // $this->load->model('BasicModel', 'Chat');
        // $this->Chat->myConstruct('chat');
        // $this->load->model('ChatModel');
        // $this->ChatModel->myConstruct('chat');
        $this->load->model('BasicModel', 'Mtn');
        $this->Mtn->myConstruct('mtn');
        $this->load->model('OvertimeModel', 'Overtime');
        $this->Overtime->myConstruct('hr');

    }

    // public function updateDirecSpv()
    // {
    //     $emps = $this->Hr->getWhere('employees', ['direct_spv !=' => ''])->result();
    //     foreach ($emps as $emp) {
    //         $spvNip = $this->Hr->getOne('employees', ['sap_id' => $emp->direct_spv]);
    //         if($spvNip) {
    //             $data = [
    //                 'direct_spv' => $spvNip->nip
    //             ];
    //             $this->Hr->updateById('employees', $data, $emp->id);
    //         }
    //     }
    //     echo "Oke";
    // }

    // public function testPage()
    // {
    //     if ($xlsx = SimpleXLSX::parse('./assets/file_to_import/expdate.xlsx')) {
    //         $header_values = $rows = [];
    //         foreach ($xlsx->rows() as $k => $r) {
    //             if ($k === 0) {
    //                 $header_values = $r;
    //                 continue;
    //             }
    //             $rows[] = array_combine($header_values, $r);
    //         }

    //         $data = [];
    //         foreach ($rows as $key => $value) {
    //             $exist = $this->Hr->getOne('employees', ['nip' => $value['nip']]);
    //             if($exist) {
    //                 $data[] = [
    //                     'nip' => $value['nip'],
    //                     'sk_number' => '-',
    //                     'sk_date' => $value['sk_start_date'],
    //                     'sk_start_date' => $value['sk_start_date'],
    //                     'sk_end_date' => $value['sk_end_date'],
    //                 ];
    //             }
    //         }
    //         $this->Hr->updateMultiple('employees', $data, 'nip');
    //         echo "Oke";
    //     } else {
    //         echo SimpleXLSX::parseError();
    //     }
    // }

    // public function testPage()
    // {
    //     if ($xlsx = SimpleXLSX::parse('./assets/file_to_import/mesin_3.xlsx')) {
    //         $header_values = $rows = [];
    //         foreach ($xlsx->rows() as $k => $r) {
    //             if ($k === 0) {
    //                 $header_values = $r;
    //                 continue;
    //             }
    //             $rows[] = array_combine($header_values, $r);
    //         }

    //         $data = [];
    //         foreach ($rows as $key => $value) {
    //             $exist = $this->Mtn->getOne('production_machines', ['name' => $value['name']]);
    //             if($exist) {
    //                 if($value['personil_ideal'] > 0) {
    //                     $data[] = [
    //                         'name' => $value['name'],
    //                         'personil_ideal' => $value['personil_ideal'],
    //                     ];
    //                 }
    //             }
    //         }
    //         $this->Mtn->updateMultiple('production_machines', $data, 'name');
    //         echo "Oke";
    //     } else {
    //         echo SimpleXLSX::parseError();
    //     }
    // }

    // public function testPage()
    // {
    //     if ($xlsx = SimpleXLSX::parse('./assets/file_to_import/employee.xlsx')) {
    //         $header_values = $rows = [];
    //         foreach ($xlsx->rows() as $k => $r) {
    //             if ($k === 0) {
    //                 $header_values = $r;
    //                 continue;
    //             }
    //             $rows[] = array_combine($header_values, $r);
    //         }

    //         $dataInsert = [];
    //         $dataUpdate = [];
    //         foreach ($rows as $key => $value) {
    //             $exist = $this->Hr->getOne('employees', ['sap_id' => $value['sap_id']]);
    //             if($exist) {
    //                 $dataUpdate[] = [
    //                     'sap_id' => $value['sap_id'],
    //                     'email' => $value['email'],
    //                 ];
    //             } else {
    //                 $dataInsert[] = [
    //                     'npp' => $value['npp'],
    //                     'sap_id' => $value['sap_id'],
    //                     'nik' => $value['nik'],
    //                     'employee_name' => $value['employee_name'],
    //                     'birth_date' => date('Y-m-d', strtotime($value['birth_date'])),
    //                     'gender' => $value['gender'],
    //                     'religion' => $value['religion'],
    //                     'age' => intval($value['age']),
    //                     'employee_status' => $value['employee_status'],
    //                     'os_name' => $value['os_name'],
    //                     'email' => $value['email'],
    //                     'department_id' => $value['department_id'],
    //                     'sub_department_id' => $value['sub_department_id'],
    //                     'division_id' => $value['division_id'],
    //                     'rank_id' => $value['rank_id'],
    //                     'overtime' => $value['overtime'],
    //                 ];
    //             }
    //         }
    //         if(count($dataInsert) > 0) {
    //             $this->Hr->createMultiple('employees', $dataInsert);
    //         }
    //         if(count($dataUpdate) > 0) {
    //             $this->Hr->updateMultiple('employees', $dataUpdate, 'sap_id');
    //         }
    //         echo "Oke";
    //     } else {
    //         echo SimpleXLSX::parseError();
    //     }
    // }

    // public function testPage2()
    // {
    //     $emps = $this->Hr->getWhere('employees', ['id >' => 1])->result();
    //     $sallary = [];
    //     foreach ($emps as $emp) {
    //         $isEmp = $this->Hr->getOne('employee_sallary', ['emp_id' => $emp->id]);
    //         if(!$isEmp) {
    //             $sallary[] = [
    //                 'emp_id' => intval($emp->id),
    //                 'sap_id' => $emp->sap_id,
    //                 'basic_sallary' => 4641854,
    //                 'premi_overtime' => 4641854 / 173,
    //                 'created_by' => 1,
    //                 'updated_by' => 1,
    //                 'updated_at' => date('Y-m-d H:i:s'),
    //             ];
    //         }
    //     }
    //     $create = $this->Hr->createMultiple('employee_sallary', $sallary);
    //     dd($create);
    // }

    // public function testPage3()
    // {
    //     if ($xlsx = SimpleXLSX::parse('./assets/file_to_import/gaji2.xlsx')) {
    //         $header_values = $rows = [];
    //         foreach ($xlsx->rows() as $k => $r) {
    //             if ($k === 0) {
    //                 $header_values = str_replace('.', '', $r);
    //                 continue;
    //             }
    //             $rows[] = array_combine($header_values, $r != '-' ? $r : 0);
    //         }
    //         // dd($rows);
    //         $create = $this->Hr->updateMultiple('employee_sallary', $rows, 'sap_id');
    //         dd($create);
    //     } else {
    //         echo SimpleXLSX::parseError();
    //     }
    // }

    // public function testPage4()
    // {
    //     $users = $this->ChatModel->getSpektaChatUser();
        
    //     foreach ($users as $user) {
    //         $message = $this->load->view('html/spektachat_notification', ['data' => $user], true);

    //         $dataEmail = [
    //             'alert_name' => 'SPEKTA_ACCOUNT_NOTIFICATION',
    //             'email_to' => $user['email'],
    //             'subject' => "Akun Aplikasi S.P.E.K.T.A Chat",
    //             'subject_name' => "Spekta Alert: Akun Aplikasi S.P.E.K.T.A Chat",
    //             'message' => $message,
    //         ];

    //         $this->Main->create('email', $dataEmail);
    //     }

    // }

    // public function testPage5()
    // {
    //     $emps = $this->Hr->getWhere('employees', ['rank_id <=' => 6])->result();
    //     $data = [];
    //     foreach ($emps as $emp) {
    //         $data[] = [
    //             'location' => 'KF-JKT',
    //             'emp_id' => $emp->id,
    //             'pin' => $this->generatePIN(6),
    //             'status' => 'ACTIVE',
    //             'created_by' => empId(),
    //             'updated_by' => empId(),
    //             'updated_at' => date('Y-m-d H:i:s'),
    //         ];
    //     }
    //     $this->Hr->createMultiple('employee_pins', $data);
    // }

    // public function testPage6()
    // {
    //     $pins = $this->HrModel->getPins();
    //     foreach ($pins as $pin) {
    //         $dataEmail = [
    //             'alert_name' => 'PIN_NOTIFICATION',
    //             'email_to' => $pin->email,
    //             'subject' => "PIN Approval Aplikasi S.P.E.K.T.A",
    //             'subject_name' => "Spekta Alert: PIN Approval Aplikasi S.P.E.K.T.A",
    //             'message' => $this->load->view("html/pin_notification", ['data' => $pin], true),
    //         ];
    //         $this->Main->create('email', $dataEmail);
    //     }
    // }

    // public function generatePIN($digits = 6)
    // {
    //     $i = 0;
    //     $pin = "";
    //     while ($i < $digits) {
    //         $pin .= mt_rand(0, 9);
    //         $i++;
    //     }
    //     return $pin;
    // }

    // public function test()
    // {
    //     $overtime = $this->Overtime->getOvertime(['equal_task_id' => '028/OT/KF-JKT/III/2022'])->row();
    //     $this->load->view('html/overtime/email/approve_overtime', [
    //         'overtime' => $overtime,
    //         'rank' => 'Asman',
    //         'level' => 'Asman',
    //         'linkApprove' => '',
    //         'linkReject' => '',
    //     ]);
    // }
}
