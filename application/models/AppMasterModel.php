<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AppMasterModel extends CI_Model
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

    public function getLocationWithUser($get)
    {
        $sql = "SELECT a.*,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                    FROM $this->kf_main.locations a";

        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "WHERE (a.name LIKE '%$get[search]%' OR
                            (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                            (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$get[search]%'
                    )";
        }
        $sql .= " ORDER BY a.name";
        return $this->db->query($sql)->result();
    }

    public function getDeptWithUser($get)
    {
        $sql = "SELECT a.*,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                    FROM $this->kf_hr.departments a
                    WHERE a.location = '$this->empLoc'";

        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (a.name LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$get[search]%'
                    )";
        }
        $sql .= " ORDER BY a.name";
        return $this->db->query($sql)->result();
    }

    public function getSubDeptWithUser($get)
    {
        $sql = "SELECT a.*,b.name AS department_name,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                    FROM $this->kf_hr.sub_departments a, $this->kf_hr.departments b
                    WHERE a.department_id = b.id
                    AND a.location = '$this->empLoc'";

        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (a.name LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$get[search]%' OR
                          b.name LIKE '%$get[search]%'
                    )";
        }
        $sql .= " ORDER BY a.department_id ASC";
        return $this->db->query($sql)->result();
    }

    public function getRankWithUser($get)
    {
        $sql = "SELECT a.*,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                    FROM $this->kf_hr.ranks a
                    WHERE a.location = '$this->empLoc'";

        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (a.name LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$get[search]%'
                    )";
        }
        $sql .= " ORDER BY a.id";
        return $this->db->query($sql)->result();
    }

    public function getDivisionWithUser($get)
    {
        $sql = "SELECT a.*, b.name AS sub_department_name, c.name AS department_name,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                    FROM $this->kf_hr.divisions a, $this->kf_hr.sub_departments b, $this->kf_hr.departments c
                    WHERE a.location = '$this->empLoc'
                    AND a.sub_department_id = b.id
                    AND a.department_id = c.id";

        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (a.name LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$get[search]%' OR
                          b.name LIKE '%$get[search]%' OR
                          c.name LIKE '%$get[search]%'
                    )";
        }
        $sql .= " ORDER BY a.sub_department_id ASC";
        return $this->db->query($sql)->result();
    }

    public function getTrainingWithUser($get)
    {
        $sql = "SELECT a.*,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                    FROM $this->kf_hr.trainings a
                    WHERE a.location = '$this->empLoc'";

        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (a.name LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$get[search]%'
                    )";
        }
        $sql .= " ORDER BY a.name";
        return $this->db->query($sql)->result();
    }

    public function getBuildingWithUser($get)
    {
        $sql = "SELECT a.*,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                    FROM $this->kf_general.buildings a
                    WHERE a.location = '$this->empLoc'";

        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (a.name LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$get[search]%'
                    )";
        }
        $sql .= " ORDER BY a.name";
        return $this->db->query($sql)->result();
    }

    public function getBuildingRoomWithUser($get)
    {
        $sql = "SELECT a.*,b.name AS building,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                    FROM $this->kf_general.building_rooms a, $this->kf_general.buildings b
                    WHERE a.building_id = b.id
                    AND location = '$this->empLoc'";

        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (name LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$get[search]%'
                    )";
        }
        $sql .= " ORDER BY a.name";
        return $this->db->query($sql)->result();
    }

    public function getRoomsWithUser($get)
    {
        $sql = "SELECT a.*,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                    FROM $this->kf_general.meeting_rooms a
                    WHERE location = '$this->empLoc'";

        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (a.name LIKE '%$get[search]%' OR
                           a.capacity LIKE '%$get[search]%' OR
                           a.building LIKE '%$get[search]%' OR
                           a.on_floor LIKE '%$get[search]%' OR
                           (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                           (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$get[search]%'
                    )";
        }
        $sql .= " ORDER BY a.id";
        return $this->db->query($sql)->result();
    }

    public function getVehiclesWithUser($get)
    {
        $sql = "SELECT a.*,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                    FROM $this->kf_general.vehicles a
                    WHERE location = '$this->empLoc'";

        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (a.name LIKE '%$get[search]%' OR
                           a.passenger_capacity LIKE '%$get[search]%' OR
                           (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                           (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$get[search]%'
                    )";
        }
        $sql .= " ORDER BY id";
        return $this->db->query($sql)->result();
    }

    public function getProdMachinemWithUser($get)
    {
        $sql = "SELECT a.*,b.name AS building,c.name AS room,d.name AS department,e.name AS sub_department,f.name AS division,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                    FROM $this->kf_mtn.production_machines a, $this->kf_general.buildings b,$this->kf_general.building_rooms c,
                         $this->kf_hr.departments d, $this->kf_hr.sub_departments e, $this->kf_hr.divisions f
                    WHERE a.building_id = b.id
                    AND a.room_id = c.id
                    AND a.department_id = d.id
                    AND a.sub_department_id = e.id
                    AND a.division_id = f.id
                    AND a.location = '$this->empLoc'";

        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (a.name LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$get[search]%' OR
                          b.name LIKE '%$get[search]%' OR
                          c.name LIKE '%$get[search]%' OR
                          d.name LIKE '%$get[search]%' OR
                          e.name LIKE '%$get[search]%' OR
                          f.name LIKE '%$get[search]%'
                    )";
        }
        $sql .= " ORDER BY a.name";
        return $this->db->query($sql)->result();
    }

    public function checkLocations($post)
    {
        $this->db->where('code', $post['code']);
        $this->db->or_where('name', $post['name']);
        return $this->db->get('locations')->row();
    }

    public function getMasterOvertime($get)
    {
        $sql = "SELECT a.*,b.name AS department,c.name AS sub_department,d.name AS division,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                    FROM $this->kf_hr.overtime_requirement a, $this->kf_hr.departments b, $this->kf_hr.sub_departments c,
                         $this->kf_hr.divisions d
                    WHERE a.department_id = b.id
                    AND a.sub_department_id = c.id
                    AND a.division_id = d.id
                    AND a.location = '$this->empLoc'";

        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (a.name LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$get[search]%' OR
                          a.category LIKE '%$get[search]%' OR
                          b.name LIKE '%$get[search]%' OR
                          c.name LIKE '%$get[search]%' OR
                          d.name LIKE '%$get[search]%'
                    )";
        }
        $sql .= " ORDER BY a.name";
        return $this->db->query($sql)->result();
    }

    public function getMasterPIC()
    {
        $sql = "SELECT a.*,b.name AS sub_department,c.name AS department,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                    FROM $this->kf_main.pics a, $this->kf_hr.sub_departments b, $this->kf_hr.departments c
                    WHERE a.sub_department_id = b.id
                    AND a.department_id = c.id
                    AND a.location = '$this->empLoc'";

        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (a.name LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$get[search]%' OR
                          b.name LIKE '%$get[search]%' OR
                          c.name LIKE '%$get[search]%'
                    )";
        }
        $sql .= " ORDER BY a.name";
        return $this->db->query($sql)->result();
    }

    public function getMachineGrid($get)
    {
       $subId = $get['subId'];
       return $this->db->select('a.*,b.name AS department,c.name AS sub_department,d.name AS division,e.name AS building,f.name AS room')
                       ->from("$this->kf_mtn.production_machines a")
                       ->join("$this->kf_hr.departments b", 'a.department_id = b.id')
                       ->join("$this->kf_hr.sub_departments c", 'a.sub_department_id = c.id')
                       ->join("$this->kf_hr.divisions d", 'a.division_id = d.id')
                       ->join("$this->kf_general.buildings e", 'a.building_id = e.id')
                       ->join("$this->kf_general.building_rooms f", 'a.room_id = f.id')
                       ->where('a.sub_department_id', $subId)
                       ->get()
                       ->result();
    }

    public function getNasionalFree($get)
    {
        $where = advanceSearch($get);
        $sql = "SELECT a.*,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) AS emp1,
                    (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) AS emp2
                    FROM $this->kf_hr.national_days a
                    WHERE location = '$this->empLoc'
                    $where";

        if (isset($get['search']) && $get['search'] !== "") {
            $sql .= "AND (a.name LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.created_by) LIKE '%$get[search]%' OR
                          (SELECT employee_name FROM $this->kf_hr.employees WHERE id = a.updated_by) LIKE '%$get[search]%'
                    )";
        }
        $sql .= " ORDER BY a.date ASC";
        return $this->db->query($sql)->result();
    }

}
