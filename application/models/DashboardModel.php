<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DashboardModel extends CI_Model
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


    public function getSubOvtGrid($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.id,b.name AS sub_name,SUM(a.overtime_hour) AS ovt_hour,SUM(a.overtime_value) AS ovt_value
                       FROM employee_overtimes_detail a, sub_departments b
                       WHERE a.location = '$this->empLoc'
                       $where
                       ORDER BY SUM(a.overtime_hour) DESC";
        return $this->db->query($sql);        
    }

    public function getSummaryPersonil($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.id,a.overtime_date,SUM(a.overtime_hour) AS ovt_hour,SUM(a.overtime_value) AS ovt_value
                       FROM employee_overtimes_detail a
                       WHERE a.location = '$this->empLoc'
                       $where
                       GROUP BY a.overtime_date
                       ORDER BY SUM(a.overtime_hour) DESC";
        return $this->db->query($sql);        
    }

    public function getYearlyOvt($year, $params)
    {
        $where = advanceSearch($params);
        $sql = "SELECT a.id,a.overtime_date,SUM(a.overtime_value) AS ovt_value
                       FROM employee_overtimes_detail a
                       WHERE YEAR(a.overtime_date) = '$year'
                       $where
                       GROUP BY a.overtime_date
                       ORDER BY a.overtime_date DESC";
        return $this->db->query($sql); 
    }

    public function getSummaryMachine($get, $machine)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.id,b.sub_department_id,b.division_id,SUM(a.real_hour) AS ovt_hour, c.name AS sub_department, d.name AS division,
                       a.machine_1,a.machine_2,
                       (SELECT name FROM $this->kf_mtn.production_machines WHERE id = a.machine_1) AS machine_one,
                       (SELECT name FROM $this->kf_mtn.production_machines WHERE id = a.machine_2) AS machine_two
                       FROM employee_overtimes_detail a, $this->kf_mtn.production_machines b, sub_departments c, divisions d
                       WHERE a.$machine = b.id
                       AND b.sub_department_id = c.id
                       AND b.division_id = d.id
                       AND a.location = '$this->empLoc'
                       $where
                       ORDER BY SUM(a.overtime_hour) DESC";
        return $this->db->query($sql);        
    }
}