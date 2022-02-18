<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ChatModel extends CI_Model
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

    public function getSpektaChatUser()
    {
        $ids = [];
        $email = [];
        $spektaUsers = $this->db->select('id,email')->from('gr_users')->get()->result();
        foreach ($spektaUsers as $user) {
            $ids[$user->email] = $user->id;
            $email[] = $user->email;
        }

        $emps = $this->db->select('id,employee_name,email,sap_id')
                    ->from("$this->kf_hr.employees")
                    ->where_in('email', $email)
                    ->get()
                    ->result();

        $users = [];
        foreach ($emps as $emp) {
            $users[] = [
                'id' => $ids[$emp->email],
                'name' => $emp->employee_name,
                'email' => $emp->email,
                'sap_id' => $emp->sap_id
            ];
        }

        return $users;
    }
}