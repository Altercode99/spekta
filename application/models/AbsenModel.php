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
        $isExist = $this->checkTableExist("absen_$postfix");
        if($isExist->num_rows() > 0) {
            $where = advanceSearch($params);
            $sql = "SELECT *,DATE(action_date) AS date FROM absen_$postfix a WHERE a.emp_id = $empId $where ORDER BY a.action_date DESC";
            return $this->db->query($sql);
        } else {
            return $isExist;
        }
    }

    public function checkTableExist($table)
    {
        return $this->db->query("SHOW TABLES LIKE '%$table%'");
    }

    public function getLastIn($empId, $postfix)
    {
       return $this->db->select('*, DATE(action_date) AS date')
                 ->from("absen_$postfix")
                 ->where('emp_id', $empId)
                 ->where('action', 'IN')
                 ->order_by('action_date', 'DESC')
                 ->limit(1)
                 ->get()
                 ->row();
    }
}