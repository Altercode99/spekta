<?php
defined('BASEPATH') or exit('No direct script access allowed');

class QhseModel extends CI_Model
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

    public function getFiles($column, $ids)
    {

        if ($column == 'sub_id') {
            return $this->db->select("a.*, 
                            (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                            (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2,
                            b.parent_id AS main_id")
                            ->from('files a')
                            ->join('sub_folders b', 'a.sub_id = b.id')
                            ->where_in('a.' . $column, $ids)
                            ->order_by('a.name', 'ASC')
                            ->get()
                            ->result();
        } else {
            return $this->db->select("a.*,
                            (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                            (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2")
                            ->from('files a')
                            ->where_in($column, $ids)
                            ->order_by('a.name', 'ASC')
                            ->get()
                            ->result();
        }
    }

    public function getFolders($table, $whereIn)
    {
        $this->db->select("a.*, 
                            (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                            (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2");
        $this->db->from("$table a");
        foreach ($whereIn as $key => $value) {
            $this->db->where_in("a.$key", $value);
        }
        $this->db->order_by('a.name', 'ASC');
        return $this->db->get()->result();
    }

    public function getRevisions($fileId)
    {
        return $this->db->select('a.*,b.employee_name AS revision_by')
                        ->from('file_revisions a')
                        ->join("$this->kf_hr.employees b", 'a.revised_by = b.id')
                        ->where('a.file_id', $fileId)
                        ->order_by('a.revision', 'DESC')
                        ->get()->result();
    }

    public function getFreeSpace($subId)
    {
        $mFile = $this->db->select('SUM(size) AS total_size')->from('file_revisions')->where('sub_id', $subId)->get()->row()->total_size;
        $limit = $this->db->select('file_limit')->from("$this->kf_hr.sub_departments")->where('id', $subId)->get()->row()->file_limit;
        if($mFile >= $limit) {
            return true;
        } else {
            return false;
        }
    }
}
