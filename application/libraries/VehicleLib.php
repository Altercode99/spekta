<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class VehicleLib
{
    protected $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->Model('BasicModel', 'Main');
        $this->ci->Main->myConstruct('main');
        $this->ci->load->Model('BasicModel', 'Hr');
        $this->ci->Hr->myConstruct('hr');
        $this->ci->load->Model('BasicModel', 'General');
        $this->ci->General->myConstruct('general');
        $this->ci->load->Model('OtherModel', 'Other');
        $this->ci->Other->myConstruct('main');
        $this->ci->load->Model('HrModel');
        $this->ci->HrModel->myConstruct('hr');

        $this->Main = $this->ci->Main;
        $this->Hr = $this->ci->Hr;
        $this->General = $this->ci->General;
        $this->Other = $this->ci->Other;
        $this->HrModel = $this->ci->HrModel;
        $this->load = $this->ci->load;
    }

    public function approvalNotif($level, $id)
    {
        $picEmail = '';
        $pics = $this->Main->getWhere('pics', ['code' => 'vehicles'])->result();
        foreach ($pics as $pic) {
            if ($picEmail == '') {
                $picEmail = $pic->pic_emails;
            } else {
                $picEmail = $picEmail . ',' . $pic->pic_emails;
            }
        }

        if ($level == 'ASMAN') {
            $asmans = $this->Hr->getWhere('employees', ['sub_department_id' => 12], '*', null, null, ['rank_id' => ['3', '4']])->result();
            if ($asmans) {
                foreach ($asmans as $asman) {
                    if ($picEmail == '') {
                        $picEmail = $asman->email;
                    } else {
                        $picEmail = $picEmail . ',' . $asman->email;
                    }
                }
            }
        }

        if ($level == 'Supervisor') {
            $spvs = $this->Hr->getWhere('employees', ['division_id' => 34], '*', null, null, ['rank_id' => ['5', '6']])->result();
            if ($spvs) {
                foreach ($spvs as $spv) {
                    if ($picEmail == '') {
                        $picEmail = $spv->email;
                    } else {
                        $picEmail = $picEmail . ',' . $spv->email;
                    }
                }
            }
        }

        if ($picEmail != '') {
            $trip = $this->Other->getTripDetail($id);
            $passengers = $this->Other->getEmployee(explode(',', $trip->passenger));
            $driver = $this->HrModel->getEmployee(['equal_email' => $trip->driver])->row()->employee_name;
            $message = $this->load->view('html/vehicles/email/approval_notification', [
                'data' => $trip, 'driver' => $driver, 'passenger' => $passengers
            ], true);
            $dataEmail = [
                'alert_name' => 'APPROVAL_VEHICLE_REV_NOTIFICATION',
                'email_to' => $picEmail,
                'subject' => "Notifikasi Approval Reservasi Kendaraan Tujuan $trip->destination @" . toIndoDateTime2($trip->start_date),
                'subject_name' => "Spekta Alert: Notifikasi Approval Reservasi Kendaraan $trip->destination @" . toIndoDateTime2($trip->start_date),
                'message' => $message,
            ];
            $this->Main->create('email', $dataEmail);
        }
    }
    
    public function rejectionNotif($level, $id)
    {
        $picEmail = '';
        $pics = $this->Main->getWhere('pics', ['code' => 'vehicles'])->result();
        foreach ($pics as $pic) {
            if ($picEmail == '') {
                $picEmail = $pic->pic_emails;
            } else {
                $picEmail = $picEmail . ',' . $pic->pic_emails;
            }
        }

        if ($level == 'ASMAN') {
            $asmans = $this->Hr->getWhere('employees', ['sub_department_id' => 12], '*', null, null, ['rank_id' => ['3', '4']])->result();
            if ($asmans) {
                foreach ($asmans as $asman) {
                    if ($picEmail == '') {
                        $picEmail = $asman->email;
                    } else {
                        $picEmail = $picEmail . ',' . $asman->email;
                    }
                }
            }
        }

        if ($level == 'Supervisor') {
            $spvs = $this->Hr->getWhere('employees', ['division_id' => 34], '*', null, null, ['rank_id' => ['5', '6']])->result();
            if ($spvs) {
                foreach ($spvs as $spv) {
                    if ($picEmail == '') {
                        $picEmail = $spv->email;
                    } else {
                        $picEmail = $picEmail . ',' . $spv->email;
                    }
                }
            }
        }

        if ($picEmail != '') {
            $trip = $this->Other->getTripDetail($id);
            $passengers = $this->Other->getEmployee(explode(',', $trip->passenger));
            $driver = $this->HrModel->getEmployee(['equal_email' => $trip->driver])->row()->employee_name;
            $message = $this->load->view('html/vehicles/email/reject_notification', [
                'data' => $trip, 'driver' => $driver, 'passenger' => $passengers
            ], true);
            $dataEmail = [
                'alert_name' => 'REJECTION_VEHICLE_REV_NOTIFICATION',
                'email_to' => $picEmail,
                'subject' => "Notifikasi Rejection Reservasi Kendaraan Tujuan $trip->destination @" . toIndoDateTime2($trip->start_date),
                'subject_name' => "Spekta Alert: Notifikasi Rejection Reservasi Kendaraan $trip->destination @" . toIndoDateTime2($trip->start_date),
                'message' => $message,
            ];
            $this->Main->create('email', $dataEmail);
        }
    }
}
