<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AccessModel extends CI_Model
{
    public function myConstruct($db_name = true)
    {
        parent::__construct();
        $this->db = $this->load->database($db_name, TRUE);
        
        $this->kf_chat = $this->auth->kf_chat;
        $this->kf_general = $this->auth->kf_general;
        $this->kf_hr = $this->auth->kf_hr;
        $this->kf_main = $this->auth->kf_main;
        $this->kf_mtn = $this->auth->kf_mtn;
        $this->kf_qhse = $this->auth->kf_qhse;
    }
    
    public function getUserWithRole($get)
    {
        $sql = "SELECT a.*,b.display_name AS role_name,c.employee_name,d.name AS dept_name,e.name AS rank_name
                    FROM $this->kf_main.users a, $this->kf_main.roles b, $this->kf_hr.employees c, $this->kf_hr.departments d, $this->kf_hr.ranks e 
                    WHERE a.role_id = b.id
                    AND a.nip = c.nip
                    AND c.department_id = d.id
                    AND c.rank_id = e.id
                    AND c.nip != '9999'";
        if(isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (a.username LIKE '%$get[search]%' OR b.display_name LIKE '%$get[search]%' OR c.employee_name LIKE '%$get[search]%')";
        }
        $sql .= " ORDER BY c.employee_name";
        return $this->db->query($sql)->result();
    }

    public function setMenuActives($deptId, $rankId, $menus)
    {
        $active = $this->db->where('dept_id', $deptId)
                           ->where('rank_id', $rankId)
                           ->where_in('menu_id', $menus)
                           ->update('users_access', ['status' => 'ACTIVE']);
        $inactive = $this->db->where('dept_id', $deptId)
                           ->where('rank_id', $rankId)
                           ->where_not_in('menu_id', $menus)
                           ->update('users_access', ['status' => 'INACTIVE']);
        if($active && $inactive) {
            return true;
        } else {
            return false;
        }
    }
}