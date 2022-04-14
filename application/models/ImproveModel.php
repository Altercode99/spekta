<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ImproveModel extends CI_Model
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

    public function getDetCategories($params)
    {
        $where = advanceSearch($params);
        $sql = "SELECT a.*,
                       (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                       (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                       FROM det_categories a
                       WHERE a.location = '$this->empLoc'
                       $where";
                    
        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (
                        a.name LIKE '%$get[search]%' OR 
                        (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                        (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$get[search]%'
                    )";
        } 
        $sql .= " ORDER BY a.id DESC";
        return $this->db->query($sql);
    }
}