<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Hautelook\Phpass\PasswordHash;
require APPPATH . '/libraries/CreatorJWT.php';

class AuthController extends Erp_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Main');
        $this->Main->myConstruct('main');
        $this->jwt = new CreatorJWT();
    }

    public function login()
    {
        $post = fileGetContent();
        $username = $post->username;
        $password = $post->password;

        $hasher = new PasswordHash(8, false);

        $user = $this->Main->getWhere('users', ['username' => $username])->row();
        if(!$user) {
            response(['error' => 'Username tidak ditemukan!'], 404);
        }

        $userPassword = $user->password;
        $byPass = $user->bypass_password;
        $role_id = $user->role_id;
        $role = $this->Main->getDataById('roles', $role_id)->name;

        $checkPassword = $hasher->CheckPassword(md5($password), $userPassword);
        $checkByPass = $hasher->CheckPassword(md5($password), $byPass);

        if($checkPassword || $checkByPass) {
            $emp = $this->HrModel->getEmpByUserId($user->id);
            $plt = $this->HrModel->getPlt($emp->id);
            $locName = $this->Main->getDataById('locations', $emp->location_id)->name;
            $picOvertime = false;
            $isPicOvertime = $this->Main->getLike('pics', ['code' => 'overtime'], ['pic_emails' => $emp->email])->row();
            if($isPicOvertime) {
                $picOvertime = true;
            } else if($emp->rank_id <= 6 || $role === "admin") {
                $picOvertime = true;
            }

            $userData = [
                'userId' => $user->id,
                'username' => $username,
                'roleId' => $role_id,
                'role' => $role,
                'empNip' => $emp->nip,
                'empId' => $emp->id,
                'empName' => $emp->employee_name,
                'deptId' => $emp->department_id,
                'department' => $emp->dept_name,
                'subId' => $emp->sub_department_id,
                'subDepartment' => $emp->sub_name,
                'rankId' => $emp->rank_id,
                'rank' => $emp->rank_name,
                'divId' => $emp->division_id,
                'division' => $emp->division_name,
                'empLoc' => $emp->location,
                'locName' => $locName,
                'picOvertime' => $picOvertime,
                'pltDepartment' => $plt ? $plt->department : null,
                'pltDeptId' => $plt ? $plt->department_id : null,
                'pltSubDepartment' => $plt ? $plt->sub_department : null,
                'pltSubId' => $plt ? $plt->sub_department_id : null,
                'pltDivision' => $plt ? $plt->division : null,
                'pltDivId' => $plt ? $plt->division_id : null,
                'pltRankId' => $plt ? $plt->rank_id : null,
            ];

            $jwtToken = $this->jwt->GenerateToken($userData);
            response(['token' => $jwtToken, 'user' => $userData]);
        } else {
            response(['error' => 'Password tidak cocok!'], 400);
        }

        response([
            'token' => 'token',
            'user' => 'user',
        ]);
    }
}