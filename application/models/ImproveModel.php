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

    public function getImproveCategories($params)
    {
        $where = advanceSearch($params);
        $sql = "SELECT a.*,
                       (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                       (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                       FROM improve_categories a
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

    public function getImproveLevels($params)
    {
        $where = advanceSearch($params);
        $sql = "SELECT a.*,
                       (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                       (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                       FROM improve_levels a
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

    public function getDetIdeas($params)
    {
        $where = advanceSearch($params);
        $sql = "SELECT a.*,b.employee_name,c.employee_name AS superior_name,d.name AS sub_department,
                       (SELECT name FROM improve_categories WHERE id = a.category_id) AS category,
                       (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                       (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                       FROM improve_ideas a, $this->kf_hr.employees b, $this->kf_hr.employees c, $this->kf_hr.sub_departments d 
                       WHERE a.emp_id = b.id
                       AND a.superior_nip = c.nip 
                       AND a.sub_department_id = d.id 
                       AND a.location = '$this->empLoc'
                       $where";
                    
        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (
                        a.name LIKE '%$get[search]%' OR 
                        (SELECT name FROM improve_categories WHERE id = a.category_id) AS category OR
                        (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                        (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$get[search]%'
                    )";
        } 
        $sql .= " ORDER BY a.id DESC";
        return $this->db->query($sql);
    }
}