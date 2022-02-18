<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Hautelook\Phpass\PasswordHash;

class AuthController extends Erp_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('HrModel');
        $this->HrModel->myConstruct('hr');
    }

    public function login()
    {
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        ob_start();
        session_start();

        if ($this->session->userdata('failed') >= 5) {
            print_r("<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>");
            print_r("<script> try { parent.submitCallback('failedbanned'); } catch(e) {};</script>");
        } else {
            $username = $this->input->post("username");
            $password = $this->input->post("password");
            $hasher = new PasswordHash(8, false);

            $user = $this->Main->getWhere('users', ['username' => $username])->row();
            $encrypt_password = $user->password;
            $encrypt_bypass = $user->bypass_password;
            $role_id = $user->role_id;
            $role = $this->Main->getDataById('roles', $role_id)->name;

            $expired_date = new DateTime($password_expired);
            $current_date = new DateTime(date("Y-m-d"));
            $check_password = $hasher->CheckPassword(md5($password), $encrypt_password);
            $check_bypass = $hasher->CheckPassword(md5($password), $encrypt_bypass);
            if ($check_password || $check_bypass) {
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

                $sessionData = [
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

                $this->session->set_userdata(SESSION_KEY, $sessionData);
                $jsonData = json_encode($sessionData);
                print_r("<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>");
                print_r("<script> try { parent.submitCallback('success', '$jsonData'); } catch(e) {};</script>");
            } else {
                print_r("<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>");
                print_r("<script> try { parent.submitCallback('failed', null); } catch(e) {};</script>");
                if (!$this->session->has_userdata('failed')) {
                    $this->session->set_userdata('failed', 1);
                } elseif ($this->session->has_userdata('failed')) {
                    $this->session->set_userdata('failed', $this->session->userdata('failed') + 1);
                }
            }
        }
    }

    public function exitapp()
    {
        session_destroy();
    }
}
