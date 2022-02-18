<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OtherModel extends CI_Model
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
        $this->empLoc = empLoc();
    }

    public function getReservasedRoom($startDate, $endDate)
    {
        $startDate = date('Y-m-d', strtotime($startDate));
        $endDate = date('Y-m-d', strtotime($endDate));

        $query = "SELECT a.*,b.color,b.name AS room_name FROM $this->kf_general.meeting_rooms_reservation a, $this->kf_general.meeting_rooms b
                    WHERE a.room_id = b.id
                    AND DATE(a.start_date) BETWEEN '$startDate' AND '$endDate' 
                    AND b.location = '$this->empLoc'
                    AND a.status != 'REJECTED'
                    ORDER BY start_date ASC";
        return $this->db->query($query)->result();
    }

    public function getReservasedVehicle($startDate, $endDate)
    {
        $startDate = date('Y-m-d', strtotime($startDate));
        $endDate = date('Y-m-d', strtotime($endDate));

        $query = "SELECT a.*,b.color,b.name AS vehicle_name FROM $this->kf_general.vehicles_reservation a, $this->kf_general.vehicles b
                    WHERE a.vehicle_id = b.id
                    AND DATE(a.start_date) BETWEEN '$startDate' AND '$endDate' 
                    AND b.location = '$this->empLoc'
                    AND status != 'REJECTED'
                    ORDER BY start_date ASC";
        return $this->db->query($query)->result();
    }

    public function getEventDetail($roomId, $date)
    {
       return $this->db->select('a.*, b.employee_name AS pic')
                       ->from("$this->kf_general.meeting_rooms_reservation a")
                       ->join("$this->kf_hr.employees b", 'a.created_by = b.id')
                       ->where('DATE(a.start_date)', $date)
                       ->where('a.room_id', $roomId)
                       ->get()
                       ->result();
    }

    public function getVehicleEventDetail($vehicleId, $date)
    {
       return $this->db->select("*, 
                            (SELECT employee_name FROM $this->kf_hr.employees WHERE id = created_by) AS pic,
                            (SELECT employee_name FROM $this->kf_hr.employees WHERE email = driver) AS driver")
                       ->from("$this->kf_general.vehicles_reservation")
                       ->where('DATE(start_date)', $date)
                       ->where('vehicle_id', $vehicleId)
                       ->get()
                       ->result();
    }

    public function getEmployee($email)
    {
        return $this->db->select('a.*,b.name AS sub_name')
                ->from("$this->kf_hr.employees a")
                ->join("$this->kf_hr.sub_departments b", 'a.sub_department_id = b.id')
                ->where_in('a.email', $email)
                ->order_by('a.employee_name', 'ASC')
                ->get()
                ->result();
    }

    public function getGuest($email)
    {
        return $this->db->select('*')
                ->from("$this->kf_general.guest_books")
                ->where_in('email', $email)
                ->order_by('name', 'ASC')
                ->get()
                ->result();
    }

    public function getMeetingDetail($id)
    {
        return $this->db->select('a.*,a.id AS ticket,b.name AS room_name,b.building,c.employee_name,d.name AS sub_department')
                        ->from("$this->kf_general.meeting_rooms_reservation a")
                        ->join("$this->kf_general.meeting_rooms b", 'a.room_id = b.id')
                        ->join("$this->kf_hr.employees c", 'a.created_by = c.id')
                        ->join("$this->kf_hr.sub_departments d", 'c.sub_department_id = d.id')
                        ->where('a.id', $id)
                        ->get()
                        ->row();
    }  

    public function getMeetingDetailRef($id)
    {
        return $this->db->select('a.*,a.id AS ticket,b.name AS room_name,b.building,c.employee_name,d.name AS sub_department')
                        ->from("$this->kf_general.meeting_rooms_reservation a")
                        ->join("$this->kf_general.meeting_rooms b", 'a.room_id = b.id')
                        ->join("$this->kf_hr.employees c", 'a.created_by = c.id')
                        ->join("$this->kf_hr.sub_departments d", 'c.sub_department_id = d.id')
                        ->where('a.ref', $id)
                        ->get()
                        ->result();
    }  
    
    public function getTripDetail($id)
    {
        return $this->db->select('a.*,a.id AS ticket,b.name AS vehicle_name,b.*,c.employee_name,d.name AS sub_department')
                        ->from("$this->kf_general.vehicles_reservation a")
                        ->join("$this->kf_general.vehicles b", 'a.vehicle_id = b.id')
                        ->join("$this->kf_hr.employees c", 'a.created_by = c.id')
                        ->join("$this->kf_hr.sub_departments d", 'c.sub_department_id = d.id')
                        ->where('a.id', $id)
                        ->get()
                        ->row();
    }  
}