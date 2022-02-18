<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HrModel extends CI_Model
{
    public function myConstruct($db_name = true)
    {
        parent::__construct();
        $this->db = $this->load->database($db_name, true);
        
        $this->kf_chat = $this->auth->kf_chat;
        $this->kf_general = $this->auth->kf_general;
        $this->kf_hr = $this->auth->kf_hr;
        $this->kf_main = $this->auth->kf_main;
        $this->kf_mtn = $this->auth->kf_mtn;
        $this->kf_qhse = $this->auth->kf_qhse;
        $this->empLoc = empLoc();
    }

    public function getEmployee($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.*,b.name AS division_name,c.name AS dept_name,d.name AS rank_name,e.name AS sub_name 
                    FROM employees a, divisions b, departments c, ranks d, sub_departments e 
                    WHERE a.division_id = b.id
                    AND a.department_id = c.id
                    AND a.rank_id = d.id
                    AND a.sub_department_id = e.id
                    AND a.nip != '9999'
                    $where
                    AND a.location = '$this->empLoc'";
                    
        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (
                        a.employee_name LIKE '%$get[search]%' OR 
                        a.NIP LIKE '%$get[search]%' OR
                        a.birth_place LIKE '%$get[search]%' OR
                        a.birth_date LIKE '%$get[search]%' OR
                        a.employee_status LIKE '%$get[search]%' OR
                        b.name LIKE '%$get[search]%' OR
                        c.name LIKE '%$get[search]%' OR
                        d.name LIKE '%$get[search]%'
                    )";
        } 
        $sql .= " ORDER BY a.employee_name ASC";
        return $this->db->query($sql);
    }

    public function getEmpSallary($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.*,b.basic_sallary,b.total_sallary,b.premi_overtime,c.name AS department,d.name AS sub_department, e.name AS division,f.name AS rank_name, f.grade,
                       (SELECT employee_name FROM employees WHERE id = b.created_by) AS emp1,
                       (SELECT employee_name FROM employees WHERE id = b.updated_by) AS emp2
                    FROM employees a, employee_sallary b, departments c, sub_departments d, divisions e, ranks f
                    WHERE a.id = b.emp_id
                    AND a.department_id = c.id
                    AND a.sub_department_id = d.id
                    AND a.division_id = e.id
                    AND a.rank_id = f.id
                    AND a.nip != '9999'
                    AND a.status = 'ACTIVE'
                    $where
                    AND a.location = '$this->empLoc'";
                    
        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (
                        a.employee_name LIKE '%$get[search]%' OR 
                        (SELECT employee_name FROM employees WHERE id = b.created_by) LIKE '%$get[search]%' OR
                        (SELECT employee_name FROM employees WHERE id = b.updated_by) LIKE '%$get[search]%' OR
                        b.basic_sallary LIKE '%$get[search]%' OR
                        b.premi_overtime LIKE '%$get[search]%' OR
                        c.name LIKE '%$get[search]%' OR
                        d.name LIKE '%$get[search]%' OR
                        e.name LIKE '%$get[search]%'
                    )";
        } 
        $sql .= " ORDER BY a.employee_name ASC";
        return $this->db->query($sql);
    }

    public function getRanks($empId)
    {
        return $this->db->select('a.*,b.name AS dept_name,c.name AS sub_name,d.name AS division_name, e.name AS rank_name')
                ->from('employee_ranks a')
                ->join('departments b', 'a.department_id = b.id')
                ->join('sub_departments c', 'a.sub_department_id = c.id')
                ->join('divisions d', 'a.division_id = d.id')
                ->join('ranks e', 'a.rank_id = e.id')
                ->where('a.emp_id', $empId)
                ->order_by('a.sk_date', 'DESC')
                ->get()
                ->result();
    }

    public function getEmpByUserId($userId)
    {
        return $this->db->select("a.*,b.name AS dept_name,c.name AS sub_name,d.name AS rank_name,e.name AS division_name")
                    ->from('employees a')
                    ->join('departments b', 'a.department_id = b.id')
                    ->join('sub_departments c', 'a.sub_department_id = c.id')
                    ->join('ranks d', 'a.rank_id = d.id')
                    ->join('divisions e', 'a.division_id = e.id')
                    ->where('a.user_id', $userId)
                    ->get()
                    ->row();
    }

    public function getPlt($empId)
    {
       return $this->db->select("a.*,b.name AS department,c.name AS sub_department,d.name AS division")
                       ->from('employee_ranks a')
                       ->join('departments b', 'a.department_id = b.id')
                       ->join('sub_departments c', 'a.sub_department_id = c.id')
                       ->join('divisions d', 'a.division_id = d.id')
                       ->where('a.emp_id', $empId)
                       ->where('a.status', 'ACTIVE')
                       ->get()
                       ->row();
    }

    public function getTrainings($empId)
    {
        return $this->db->select('a.*,b.name AS training_name')
                ->from('employee_trainings a')
                ->join('trainings b', 'a.training_id = b.id')
                ->where('a.emp_id', $empId)
                ->order_by('a.certificate_date', 'DESC')
                ->get()
                ->result();
    }

    public function getRequirement()
    {
        return $this->db->select('a.*,b.name AS division_name')
                        ->from('overtime_requirement a')
                        ->join('divisions b', 'a.division_id = b.id')
                        ->get()
                        ->result();
    }

    public function subWithDept()
    {
        return $this->db->select("a.*,b.name AS dept_name")
                        ->from('sub_departments a')
                        ->join('departments b', 'a.department_id = b.id')
                        ->get()
                        ->result();
    }

    public function divWithSub()
    {
        return $this->db->select("a.*,b.name AS sub_name")
                        ->from('divisions a')
                        ->join('sub_departments b', 'a.sub_department_id = b.id')
                        ->get()
                        ->result();
    }

    public function getPins()
    {
        return $this->db->select("a.pin,a.location,,b.email,b.employee_name")
                        ->from("employee_pins a")
                        ->join("employees b", "a.emp_id = b.id")
                        ->get()
                        ->result();
    }

    public function getRequestList($overtime)
    {
        $reqs = [];
        if($overtime->ahu > 0) {
            $reqs[] = $overtime->ahu;
        } 

        if($overtime->compressor > 0) {
            $reqs[] = $overtime->compressor;
        }

        if($overtime->pw > 0) {
            $reqs[] = $overtime->pw;
        }

        if($overtime->steam > 0) {
            $reqs[] = $overtime->steam;
        }

        if($overtime->dust_collector > 0) {
            $reqs[] = $overtime->dust_collector;
        }

        if($overtime->wfi > 0) {
            $reqs[] = $overtime->wfi;
        }

        if($overtime->mechanic > 0) {
            $reqs[] = $overtime->mechanic;
        }

        if($overtime->electric > 0) {
            $reqs[] = $overtime->electric;
        }

        if($overtime->hnn > 0) {
            $reqs[] = $overtime->hnn;
        }

        if($overtime->jemputan > 0) {
            $reqs[] = $overtime->jemputan;
        }

        $name = '';
        $array = [];

        if(count($reqs) > 0) {
            $requires = $this->Hr->getWhereIn('overtime_requirement', ['id' => $reqs])->result();
            foreach ($requires as $req) {
                if($name == '') {
                    $name = $req->name;
                } else {
                    $name = $name.','.$req->name;
                }
                $array[] = $req->name;
            }
        }
        
        return [
            'string' => $name !== '' ? "($name)" : null,
            'array' => $array
        ];
    }

    public function getPinGrid($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.*,b.employee_name,b.nip,c.name AS department,d.name AS sub_department,e.name AS division,f.name AS rank_name,
                       (SELECT employee_name FROM employees WHERE id = a.created_by) AS emp1,
                       (SELECT employee_name FROM employees WHERE id = a.updated_by) AS emp2
                    FROM employee_pins a, employees b, departments c, sub_departments d, divisions e, ranks f
                    WHERE a.emp_id = b.id
                    AND b.department_id = c.id
                    AND b.sub_department_id = d.id
                    AND b.division_id = e.id
                    AND b.rank_id = f.id
                    $where
                    AND a.location = '$this->empLoc'";
                    
        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (
                        b.employee_name LIKE '%$get[search]%' OR 
                        (SELECT employee_name FROM employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                        (SELECT employee_name FROM employees WHERE id = a.updated_by) LIKE '%$get[search]%' OR
                        c.name LIKE '%$get[search]%' OR
                        d.name LIKE '%$get[search]%' OR
                        e.name LIKE '%$get[search]%' OR
                        f.name LIKE '%$get[search]%'
                    )";
        } 
        $sql .= " ORDER BY b.employee_name ASC";
        return $this->db->query($sql);
    }

    public function getPinDetail($get, $id)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.*,b.employee_name,b.nip,c.name AS department,d.name AS sub_department,e.name AS division,f.name AS rank_name
                    FROM employee_pins a, employees b, departments c, sub_departments d, divisions e, ranks f
                    WHERE a.emp_id = b.id
                    AND b.department_id = c.id
                    AND b.sub_department_id = d.id
                    AND b.division_id = e.id
                    AND b.rank_id = f.id
                    AND a.id = '$id'
                    ORDER BY b.employee_name ASC";
        return $this->db->query($sql)->row();
    }
}
