<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AbsenModel extends CI_Model
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
    }

    public function getAbsens($params, $empId, $postfix)
    {
        $where = advanceSearch($params);
        $sql = "SELECT * FROM absen_$postfix a WHERE a.emp_id = $empId $where ORDER BY a.action_date DESC";
        return $this->db->query($sql);
    }
}