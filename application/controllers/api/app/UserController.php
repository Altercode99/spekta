<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/CreatorJWT.php';

class UserController extends Erp_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('HrModel');
        $this->HrModel->myConstruct('hr');
        //Check For Token
        $this->jwt = new CreatorJWT();
        $this->jwt->checkToken($this->input->request_headers('authorization'));
    }

    public function getProfile()
    {
        $jwtData = $this->jwt->me($this->input->request_headers('authorization'));
        $emp = $this->HrModel->getEmployee(['equal_id' => $jwtData['empId']])->row();
        $emp->birth_date = toIndoDate($emp->birth_date);
        $emp->os_name = $emp->os_name === '-' ? $this->Main->getDataById('locations', $emp->location_id)->name : $emp->os_name;
        $emp->address = $emp->address ? $emp->address : '-';
        $emp->email = $emp->email ? $emp->email : '-';
        $emp->mobile = $emp->mobile ? $emp->mobile : '-';
        $emp->npwp = $emp->npwp ? $emp->npwp : '-';
        $emp->parent_nik = $emp->parent_nik ? $emp->parent_nik : '-';
        $emp->sk_number = $emp->sk_number ? $emp->sk_number : '-';
        $emp->sk_date = $emp->sk_date !== '0000-00-00' ? toIndoDate($emp->sk_date) : '-';
        $emp->sk_start_date = $emp->sk_start_date !== '0000-00-00' ? toIndoDate($emp->sk_start_date) : '-';
        $emp->sk_end_date = $emp->sk_end_date === '0000-00-00' ? toIndoDate($emp->sk_end_date) : '-';
        response(['profile' => $emp]);
    }
}
