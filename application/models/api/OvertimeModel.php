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

    public function getOvertimesDetail($location, $start, $end)
    {
        $sql = "SELECT a.*,b.name AS department,c.name AS sub_department,d.name AS division,
                       (SELECT employee_name FROM employees WHERE id = a.created_by) AS emp1,
                       (SELECT employee_name FROM employees WHERE id = a.updated_by) AS emp2,
                       e.employee_name,e.nip,e.sap_id,
                       f.apv_spv,f.apv_spv_nip,f.apv_spv_date,
                       f.apv_asman,f.apv_asman_nip,f.apv_asman_date,
                       f.apv_ppic,f.apv_ppic_nip,f.apv_ppic_date,
                       f.apv_mgr,f.apv_mgr_nip,f.apv_mgr_date,
                       f.apv_head,f.apv_head_nip,f.apv_head_date,
                       g.name AS rank_name
                       FROM employee_overtimes_detail a, departments b, sub_departments c, divisions d, employees e, employee_overtimes f, ranks g
                       WHERE a.department_id = b.id
                       AND a.sub_department_id = c.id
                       AND a.division_id = d.id
                       AND a.location = '$location'
                       AND a.emp_id = e.id
                       AND a.task_id = f.task_id
                       AND a.overtime_date BETWEEN '$start' AND '$end'
                       AND a.status = 'CLOSED'
                       AND e.rank_id = g.id
                       ORDER BY a.id DESC";
        return $this->db->query($sql);
    }
}