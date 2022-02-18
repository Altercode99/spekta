<?php
defined('BASEPATH') or exit('No direct script access allowed');

class GaModel extends CI_Model
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

    public function getCatheringPrice($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.*,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                    FROM catherings a
                    WHERE location = '$this->empLoc'
                    $where";

        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (a.vendor_name LIKE '%$get[search]%' OR
                        (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                        (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$get[search]%' 
                    )";
        }
        $sql .= " ORDER BY a.vendor_name";
        return $this->db->query($sql);
    }
    
    public function getSnackGrid($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.*,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                    FROM snacks a
                    WHERE location = '$this->empLoc'
                    AND id > 0
                    $where";

        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (a.name LIKE '%$get[search]%' OR
                        (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                        (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$get[search]%' 
                    )";
        }
        $sql .= " ORDER BY a.name";
        return $this->db->query($sql);
    }

    public function getMeetingRevGrid($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.*,b.name AS room_name,c.name AS snack_name,c.price AS snack_price,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                    FROM meeting_rooms_reservation a, meeting_rooms b, snacks c
                    WHERE a.room_id = b.id
                    AND a.snack_id = c.id
                    AND a.location = '$this->empLoc'
                    $where";

        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (a.name LIKE '%$get[search]%' OR
                        (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                        (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$get[search]%' OR
                        b.name LIKE '%$get[search]%' OR
                        c.name LIKE '%$get[search]%'
                    )";
        }
        $sql .= " ORDER BY a.start_date,a.id ASC";
        return $this->db->query($sql);
    }

    public function getVehicleRevGrid($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.*,b.name AS vehicle,c.employee_name AS driver,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                    FROM vehicles_reservation a, vehicles b, $this->kf_hr.employees c
                    WHERE a.vehicle_id = b.id
                    AND a.driver = c.email
                    AND a.location = '$this->empLoc'
                    $where";

        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (a.name LIKE '%$get[search]%' OR
                        (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                        (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$get[search]%' OR
                        b.name LIKE '%$get[search]%' OR
                        c.employee_name LIKE '%$get[search]%'
                    )";
        }
        $sql .= " ORDER BY a.start_date ASC";
        return $this->db->query($sql);
    }

    public function getMeetingRevGroupGrid($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.room_id,COUNT(a.id) AS total_rev, SUM(a.participant_confirmed) AS total_person, SUM(a.duration) AS total_hour,
                    SUM(c.price * a.participant_confirmed) AS total_snack, b.name AS room_name,b.color
                    FROM meeting_rooms_reservation a, meeting_rooms b, snacks c
                    WHERE a.room_id = b.id
                    AND a.snack_id = c.id
                    AND a.location = '$this->empLoc'
                    $where
                    GROUP BY a.room_id
                    ORDER BY b.name ASC";     
        return $this->db->query($sql);
    }

    public function getVehicleRevGroupGrid($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.vehicle_id,COUNT(a.id) AS total_rev, SUM(a.duration) AS total_hour,SUM(a.distance) AS total_km, 
                    b.name AS vehicle_name, b.color
                    FROM vehicles_reservation a, vehicles b
                    WHERE a.vehicle_id = b.id
                    AND a.location = '$this->empLoc'
                    $where
                    GROUP BY a.vehicle_id
                    ORDER BY b.name ASC";     
        return $this->db->query($sql);
    }

    public function checkAvailableDriver($driver, $start, $end)
    {
        $sql = "SELECT driver FROM vehicles_reservation WHERE (start_date BETWEEN '$start' AND '$end' OR end_date BETWEEN '$start' AND '$end') AND driver = '$driver'";
        return $this->db->query($sql)->row();
    }

    public function checkAvailableVehicle($vehicleId, $start, $end)
    {
        $sql = "SELECT vehicle_id FROM vehicles_reservation WHERE (start_date BETWEEN '$start' AND '$end' OR end_date BETWEEN '$start' AND '$end') AND vehicle_id = '$vehicleId'";
        return $this->db->query($sql)->row();
    }
}
