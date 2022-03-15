<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OvertimeModel extends CI_Model
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

    public function lastOt($table, $column, $date, $location = null)
    {
        $empLoc = $location ? $location : empLoc();
        $newDate = date('Y-m', strtotime($date));
        $query = $this->db->select("COUNT(id) AS total_id")
                        ->from($table)
                        ->where('location', $empLoc)
                        ->like($column, $newDate, 'both')
                        ->get()
                        ->row()->total_id;

        return $query;
    }

    public function getOvertime($get)
    {
        $where = advanceSearch($get);
        $location = $this->auth->isLogin() ? "AND a.location = '$this->empLoc'" : null;
        $sql = "SELECT a.*,b.name AS department,c.name AS sub_department,d.name AS division,
                       (SELECT employee_name FROM employees WHERE id = a.created_by) AS emp1,
                       (SELECT employee_name FROM employees WHERE id = a.updated_by) AS emp2
                       FROM employee_overtimes a, departments b, sub_departments c, divisions d
                       WHERE a.department_id = b.id
                       AND a.sub_department_id = c.id
                       AND a.division_id = d.id
                       $where
                       $location";
                    
        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (
                        a.task_id LIKE '%$get[search]%' OR 
                        (SELECT employee_name FROM employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                        (SELECT employee_name FROM employees WHERE id = a.updated_by) LIKE '%$get[search]%' OR
                        b.name LIKE '%$get[search]%' OR
                        c.name LIKE '%$get[search]%' OR
                        d.name LIKE '%$get[search]%'
                    )";
        } 
        $sql .= " ORDER BY a.id DESC";
        return $this->db->query($sql);
    }

    public function getAppvOvertime($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.*,b.name AS department,c.name AS sub_department,d.name AS division,
                       (SELECT employee_name FROM employees WHERE id = a.created_by) AS emp1,
                       (SELECT employee_name FROM employees WHERE id = a.updated_by) AS emp2,
                       (SELECT employee_name FROM employees WHERE nip = a.apv_spv_nip) AS spv,
                       (SELECT employee_name FROM employees WHERE nip = a.apv_asman_nip) AS asman,
                       (SELECT employee_name FROM employees WHERE nip = a.apv_ppic_nip) AS ppic,
                       (SELECT employee_name FROM employees WHERE nip = a.apv_mgr_nip) AS mgr,
                       (SELECT employee_name FROM employees WHERE nip = a.apv_head_nip) AS head
                       FROM employee_overtimes a, departments b, sub_departments c, divisions d
                       WHERE a.department_id = b.id
                       AND a.sub_department_id = c.id
                       AND a.division_id = d.id
                       $where
                       AND a.location = '$this->empLoc'";
                    
        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (
                        a.task_id LIKE '%$get[search]%' OR 
                        (SELECT employee_name FROM employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                        (SELECT employee_name FROM employees WHERE id = a.updated_by) LIKE '%$get[search]%' OR
                        b.name LIKE '%$get[search]%' OR
                        c.name LIKE '%$get[search]%' OR
                        d.name LIKE '%$get[search]%'
                    )";
        } 
        $sql .= " ORDER BY a.id DESC";
        return $this->db->query($sql);
    }

    public function getOvertimeDetail($get)
    {
        $where = advanceSearch($get);
        $location = $this->auth->isLogin() ? "AND a.location = '$this->empLoc'" : null;
        $sql = "SELECT a.*,b.name AS department,c.name AS sub_department,d.name AS division,e.employee_name,
                       (SELECT employee_name FROM employees WHERE id = a.created_by) AS emp1,
                       (SELECT employee_name FROM employees WHERE id = a.updated_by) AS emp2,
                       (SELECT employee_name FROM employees WHERE nip = a.status_by) AS status_updater,
                       (SELECT name FROM $this->kf_mtn.production_machines WHERE id = a.machine_1) AS machine_1,
                       (SELECT name FROM $this->kf_mtn.production_machines WHERE id = a.machine_2) AS machine_2
                       FROM $this->kf_hr.employee_overtimes_detail a, $this->kf_hr.departments b, $this->kf_hr.sub_departments c, 
                            $this->kf_hr.divisions d, $this->kf_hr.employees e
                       WHERE a.department_id = b.id
                       AND a.sub_department_id = c.id
                       AND a.division_id = d.id
                       AND a.emp_id = e.id
                       $location
                       $where";
                    
        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (
                        a.task_id LIKE '%$get[search]%' OR 
                        (SELECT employee_name FROM employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                        (SELECT employee_name FROM employees WHERE id = a.updated_by) LIKE '%$get[search]%' OR
                        b.name LIKE '%$get[search]%' OR
                        c.name LIKE '%$get[search]%' OR
                        d.name LIKE '%$get[search]%' OR
                        e.name LIKE '%$get[search]%' OR
                        f.employee_name LIKE '%$get[search]%'
                    )";
        } 
        $sql .= " ORDER BY a.overtime_date ASC";
        return $this->db->query($sql);
    }

    public function getReportOvertime($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.*,b.name AS emp_sub_name,c.name AS emp_division,d.name AS ovt_sub_name,e.name AS ovt_division,f.employee_name,g.overtime_review,
                       (SELECT employee_name FROM employees WHERE id = a.created_by) AS emp1,
                       (SELECT employee_name FROM employees WHERE id = a.updated_by) AS emp2,
                       (SELECT employee_name FROM employees WHERE nip = a.status_by) AS status_updater,
                       (SELECT name FROM $this->kf_mtn.production_machines WHERE id = a.machine_1) AS machine_1,
                       (SELECT name FROM $this->kf_mtn.production_machines WHERE id = a.machine_2) AS machine_2
                       FROM $this->kf_hr.employee_overtimes_detail a, $this->kf_hr.sub_departments b, $this->kf_hr.divisions c, 
                            $this->kf_hr.sub_departments d, $this->kf_hr.divisions e, $this->kf_hr.employees f, $this->kf_hr.employee_overtimes g
                       WHERE a.sub_department_id = b.id
                       AND a.division_id = c.id
                       AND a.ovt_sub_department = d.id
                       AND a.ovt_division = e.id
                       AND a.emp_id = f.id
                       AND a.task_id = g.task_id
                       AND a.location = '$this->empLoc'
                       $where";
                    
        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (
                        a.task_id LIKE '%$get[search]%' OR 
                        a.emp_task_id LIKE '%$get[search]%' OR 
                        (SELECT employee_name FROM employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                        (SELECT employee_name FROM employees WHERE id = a.updated_by) LIKE '%$get[search]%' OR
                        (SELECT employee_name FROM employees WHERE id = a.status_by) LIKE '%$get[search]%' OR
                        b.name LIKE '%$get[search]%' OR
                        c.name LIKE '%$get[search]%' OR
                        d.name LIKE '%$get[search]%' OR
                        e.name LIKE '%$get[search]%' OR
                        f.employee_name LIKE '%$get[search]%'
                    )";
        } 
        $sql .= " ORDER BY a.overtime_date DESC";
        return $this->db->query($sql);
    }

    public function getReportOvertimeSub($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.id,b.name AS sub_name,
                       SUM(a.effective_hour) AS effective_hour,SUM(a.break_hour) AS break_hour,SUM(a.real_hour) AS real_hour,
                       SUM(a.overtime_hour) AS overtime_hour,SUM(a.overtime_value) AS overtime_value,SUM(a.meal) AS meal
                       FROM employee_overtimes_detail a, sub_departments b
                       WHERE a.sub_department_id = b.id
                       AND a.location = '$this->empLoc'
                       $where";
        $sql .= " ORDER BY b.name ASC";
        return $this->db->query($sql);
    }

    public function getReportOvertimeDiv($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.id,b.name AS sub_name,c.name AS div_name,
                       SUM(a.effective_hour) AS effective_hour,SUM(a.break_hour) AS break_hour,SUM(a.real_hour) AS real_hour,
                       SUM(a.overtime_hour) AS overtime_hour,SUM(a.overtime_value) AS overtime_value,SUM(a.meal) AS meal
                       FROM employee_overtimes_detail a, sub_departments b, divisions c
                       WHERE a.sub_department_id = b.id
                       AND a.division_id = c.id
                       AND a.location = '$this->empLoc'
                       $where";
        $sql .= " ORDER BY c.name ASC";
        return $this->db->query($sql);
    }

    public function getReportOvertimeEmp($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.id,a.notes,b.employee_name AS emp_name,c.name AS dept_name,d.name AS sub_name,e.name AS div_name,
                       SUM(a.effective_hour) AS effective_hour,SUM(a.break_hour) AS break_hour,SUM(a.real_hour) AS real_hour,
                       SUM(a.overtime_hour) AS overtime_hour,SUM(a.overtime_value) AS overtime_value,SUM(a.meal) AS meal
                       FROM employee_overtimes_detail a, employees b, departments c, sub_departments d, divisions e
                       WHERE a.emp_id = b.id
                       AND a.department_id = c.id
                       AND a.sub_department_id = d.id
                       AND a.division_id = e.id
                       AND a.location = '$this->empLoc'
                       $where";
        $sql .= " ORDER BY b.employee_name ASC";
        return $this->db->query($sql);
    }

    public function getReportOvertimeEmpGridRev($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.id,a.notes,a.effective_hour,a.break_hour,a.real_hour,a.overtime_hour,a.overtime_value,a.meal,
                       b.employee_name AS emp_name,c.name AS dept_name,d.name AS sub_name,e.name AS div_name
                       FROM employee_overtimes_detail a, employees b, departments c, sub_departments d, divisions e
                       WHERE a.emp_id = b.id
                       AND a.department_id = c.id
                       AND a.sub_department_id = d.id
                       AND a.division_id = e.id
                       AND a.location = '$this->empLoc'
                       $where";
        $sql .= " ORDER BY b.employee_name ASC";
        return $this->db->query($sql);
    }

    public function getOvertimeMachine($get)
    {
        $ids = explode(',', $get['ids']);
        return $this->db->select('a.id,a.name,b.name AS building,c.name AS room')
                        ->from("$this->kf_mtn.production_machines a")
                        ->join("$this->kf_general.buildings b", 'a.building_id = b.id')
                        ->join("$this->kf_general.building_rooms c", 'a.room_id = c.id')
                        ->where_in('a.id', $ids)
                        ->get()
                        ->result();
    }

    public function getDepartment($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.* FROM departments a WHERE a.location = '$this->empLoc' $where ORDER BY a.name ASC";
        return $this->db->query($sql)->result();
    }

    public function getSubDepartment($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.* FROM sub_departments a WHERE a.location = '$this->empLoc' $where ORDER BY a.name ASC";
        return $this->db->query($sql)->result();
    }

    public function getRequestOvertimeGrid($taskIds)
    {
        $sql = "SELECT a.*,b.name AS department,c.name AS sub_department,d.name AS division,
                       (SELECT employee_name FROM employees WHERE id = a.created_by) AS emp1,
                       (SELECT employee_name FROM employees WHERE id = a.updated_by) AS emp2
                       FROM employee_overtimes a, departments b, sub_departments c, divisions d
                       WHERE a.department_id = b.id
                       AND a.sub_department_id = c.id
                       AND a.division_id = d.id
                       AND a.task_id IN($taskIds)
                       AND a.location = '$this->empLoc'
                       ORDER BY a.id DESC";
        return $this->db->query($sql);
    }

    public function getTechnicOvertime($subId)
    {
        $sql = "SELECT task_id,ref FROM employee_overtimes WHERE ref != '-' AND ref != '' AND status = 'CREATED' AND sub_department_id = '$subId'";
        return $this->db->query($sql)->result();
    }

    public function getWindowOvertimeGrid($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.*,b.name AS sub_department,c.name AS division,d.employee_name,
                (SELECT name FROM $this->kf_mtn.production_machines WHERE id = a.machine_1) AS machine_1,
                (SELECT name FROM $this->kf_mtn.production_machines WHERE id = a.machine_2) AS machine_2
                FROM employee_overtimes_detail a, sub_departments b, divisions c, employees d
                WHERE a.sub_department_id = b.id
                AND a.division_id = c.id
                AND a.emp_id = d.id
                AND a.location = '$this->empLoc'
                $where
                ORDER BY a.overtime_date ASC";
        return $this->db->query($sql);
    }

    public function getRevOvtGrid($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.*,b.name AS department,c.name AS sub_department,
                (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                FROM overtime_revision_requests a, departments b, sub_departments c
                WHERE a.department_id = b.id
                AND a.sub_department_id = c.id
                AND a.location = '$this->empLoc'
                $where
                ORDER BY a.id DESC";
        return $this->db->query($sql);
    }

    public function getRevOvtDtlGrid($taskId)
    {
        return $this->db->select("b.*,c.name AS department,d.name AS sub_department,e.name AS division,f.employee_name,g.start_date AS task_start_date,g.end_date AS task_end_date,
                                 (SELECT name FROM $this->kf_mtn.production_machines WHERE id = b.machine_1) AS machine_1,
                                 (SELECT name FROM $this->kf_mtn.production_machines WHERE id = b.machine_2) AS machine_2")
                        ->from('overtime_revision_requests_detail a')
                        ->join('employee_overtimes_detail b', 'a.emp_task_id = b.emp_task_id')
                        ->join('departments c', 'b.department_id = c.id')
                        ->join('sub_departments d', 'b.sub_department_id = d.id')
                        ->join('divisions e', 'b.division_id = e.id')
                        ->join('employees f', 'b.emp_id = f.id')
                        ->join('employee_overtimes g', 'b.task_id = b.task_id')
                        ->where('a.task_id', $taskId)
                        ->get()
                        ->result();
    }

    public function backStatusBefore($taskId)
    {
        return $this->db->query("UPDATE employee_overtimes_detail SET status = status_before WHERE task_id = '$taskId'");
    }

    public function getOvt7Day($params)
    {
        $lastWeeek = backDayToDate(date('Y-m-d'), 7);
        $where = advanceSearch($params);
        $sql = "SELECT a.*,b.name AS department,c.name AS sub_department,d.name AS division,
                       (SELECT employee_name FROM employees WHERE id = a.created_by) AS emp1,
                       (SELECT employee_name FROM employees WHERE id = a.updated_by) AS emp2
                       FROM employee_overtimes a, departments b, sub_departments c, divisions d
                       WHERE a.department_id = b.id
                       AND a.sub_department_id = c.id
                       AND a.division_id = d.id
                       AND a.location = '$this->empLoc'
                       AND DATE(a.overtime_date) > '$lastWeeek'
                       AND on_revision = 0
                       $where";
        $sql .= " ORDER BY a.overtime_date DESC";
        return $this->db->query($sql);
    }

    public function getRevOvtPersonil($params)
    {
        $where = advanceSearch($params);
        $where .= isset($params['status']) ? queryIn('e.status', $params['status']) : "";
        $sql = "SELECT a.*,b.name AS department,c.name AS sub_department,d.name AS division,e.rev_task_id,e.status AS rev_status,
                       e.description,e.response,
                       (SELECT employee_name FROM employees WHERE id = a.created_by) AS emp1,
                       (SELECT employee_name FROM employees WHERE id = a.updated_by) AS emp2
                       FROM employee_overtimes a, departments b, sub_departments c, divisions d, overtime_revision_requests_personil e
                       WHERE a.department_id = b.id
                       AND a.sub_department_id = c.id
                       AND a.division_id = d.id
                       AND a.task_id = e.task_id
                       AND a.location = '$this->empLoc'
                       $where";
        $sql .= " ORDER BY a.id DESC";
        return $this->db->query($sql);
    }

    public function getRevPersonil($taskId)
    {
        return $this->db->select("a.rev_task_id,a.description,a.response,a.status,b.task_id,b.personil,b.overtime_date,b.start_date,b.end_date,b.notes,c.name AS department,d.name AS sub_department,e.name AS division")
                        ->from('overtime_revision_requests_personil a')
                        ->join('employee_overtimes b', 'a.task_id = b.task_id')
                        ->join('departments c', 'b.department_id = c.id')
                        ->join('sub_departments d', 'b.sub_department_id = d.id')
                        ->join('divisions e', 'b.division_id = e.id')
                        ->where('a.rev_task_id', $taskId)
                        ->get()
                        ->row();
    }

    public function getRevPersonilOvertime($taskId)
    {
        return $this->db->select("a.status AS his_status,a.revision_status AS rev_his_status,a.status_before AS his_status_before,
                                  b.*,c.name AS department,d.name AS sub_department,e.name AS division,f.employee_name")
                        ->from('overtime_revision_requests_personil_history a')
                        ->join('employee_overtimes_detail b', 'a.emp_task_id = b.emp_task_id')
                        ->join('departments c', 'b.department_id = c.id')
                        ->join('sub_departments d', 'b.sub_department_id = d.id')
                        ->join('divisions e', 'b.division_id = e.id')
                        ->join('employees f', 'b.emp_id = f.id')
                        ->where('a.rev_task_id', $taskId)
                        ->get();
    }

    public function getOvertimeDetailHistory($get)
    {
        $where = advanceSearch($get);
        $location = $this->auth->isLogin() ? "AND a.location = '$this->empLoc'" : null;
        $sql = "SELECT a.*,b.name AS department,c.name AS sub_department,d.name AS division,e.employee_name,
                       f.status AS his_status, f.revision_status AS his_rev_status, f.status_before AS his_status_before,
                       (SELECT employee_name FROM employees WHERE id = a.created_by) AS emp1,
                       (SELECT employee_name FROM employees WHERE id = a.updated_by) AS emp2,
                       (SELECT employee_name FROM employees WHERE nip = a.status_by) AS status_updater,
                       (SELECT name FROM $this->kf_mtn.production_machines WHERE id = a.machine_1) AS machine_1,
                       (SELECT name FROM $this->kf_mtn.production_machines WHERE id = a.machine_2) AS machine_2
                       FROM $this->kf_hr.employee_overtimes_detail a, $this->kf_hr.departments b, $this->kf_hr.sub_departments c, 
                            $this->kf_hr.divisions d, $this->kf_hr.employees e, $this->kf_hr.overtime_revision_requests_personil_history f
                       WHERE a.department_id = b.id
                       AND a.sub_department_id = c.id
                       AND a.division_id = d.id
                       AND a.emp_id = e.id
                       AND a.emp_task_id = f.emp_task_id
                       $location
                       $where";
                    
        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (
                        a.task_id LIKE '%$get[search]%' OR 
                        (SELECT employee_name FROM employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                        (SELECT employee_name FROM employees WHERE id = a.updated_by) LIKE '%$get[search]%' OR
                        b.name LIKE '%$get[search]%' OR
                        c.name LIKE '%$get[search]%' OR
                        d.name LIKE '%$get[search]%' OR
                        e.employee_name LIKE '%$get[search]%'
                    )";
        } 
        $sql .= " ORDER BY a.overtime_date ASC";
        return $this->db->query($sql);
    }
}