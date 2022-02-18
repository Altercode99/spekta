<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TestController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('SimpleXLSX');
        $this->load->model('BasicModel', 'Hr');
        $this->Hr->myConstruct('hr');
        $this->load->model('BasicModel', 'Chat');
        $this->Chat->myConstruct('chat');
        $this->load->model('ChatModel');
        $this->ChatModel->myConstruct('chat');

    }

    // public function testPage()
    // {
    //     if ($xlsx = SimpleXLSX::parse('./assets/file_to_import/gaji_2.xlsx')) {
    //         $header_values = $rows = [];
    //         foreach ($xlsx->rows() as $k => $r) {
    //             if ($k === 0) {
    //                 $header_values = $r;
    //                 continue;
    //             }
    //             $rows[] = array_combine($header_values, $r);
    //         }

    //         $dataSallary = [];
    //         foreach ($rows as $key => $value) {
    //             $dataSallary[] = [
    //                 'sap_id' => $value['sap_id'],
    //                 'overtime' => $value['overtime']
    //             ];
    //         }
    //         dd($this->Hr->updateMultiple('employees', $dataSallary, 'sap_id'));
    //     } else {
    //         echo SimpleXLSX::parseError();
    //     }
    // }

    // public function testPage2()
    // {
    //     $emps = $this->Hr->getWhere('employees', ['id >' => 1])->result();
    //     $sallary = [];
    //     foreach ($emps as $emp) {
    //         $sallary[] = [
    //             'emp_id' => intval($emp->id),
    //             'sap_id' => $emp->sap_id,
    //             'basic_sallary' => 0,
    //             'premi_overtime' => 0,
    //             'created_by' => 1,
    //             'updated_by' => 1,
    //             'updated_at' => date('Y-m-d H:i:s'),
    //         ];
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
}
