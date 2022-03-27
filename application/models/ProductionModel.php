<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProductionModel extends CI_Model
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
        $this->kf_prod = $this->auth->kf_prod;
        $this->empLoc = empLoc();
    }

    public function getMasterProduct($params)
    {
        $where = advanceSearch($params);
        $sql = "SELECT a.*,
                       (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                       (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                       FROM $this->kf_prod.products a
                       WHERE a.location = '$this->empLoc'
                       $where";
                    
        if (isset($params['search']) && $params['search'] !== "") {
            $sql .= "AND (
                        a.name LIKE '%$params[search]%' OR 
                        (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$params[search]%' OR
                        (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$params[search]%'
                    )";
        } 
        $sql .= " ORDER BY a.name DESC";
        return $this->db->query($sql);
    }

    public function getSpLoc($params)
    {
        $where = advanceSearch($params);
        $sql = "SELECT a.*,
                       (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                       (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                       FROM $this->kf_prod.spack_locations a
                       WHERE a.location = '$this->empLoc'
                       $where";
                    
        if (isset($params['search']) && $params['search'] !== "") {
            $sql .= "AND (
                        a.name LIKE '%$params[search]%' OR 
                        (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$params[search]%' OR
                        (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$params[search]%'
                    )";
        } 
        $sql .= " ORDER BY a.name DESC";
        return $this->db->query($sql);
    }
}