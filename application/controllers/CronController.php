<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CronController extends Erp_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    //@URL: http://localhost/spekta/index.php?c=AppController&m=sendEmail
    public function sendEmail()
    {
        $status = $this->Main->getDataById('email_send', 1)->status;
        if($status == 'enable') {
            $emails = $this->Main->getWhere('email', ['status' => 0, 'DATE(created_at)' => date('Y-m-d')])->result();
            foreach ($emails as $email) {
                $send = $this->sendmail->sendEmail($email->subject, $email->message, $email->email_to, $email->email_cc, $email->subject_name);
                if ($send) {
                    $data = [
                        'status' => 1,
                        'send_date' => date('Y-m-d H:i:s'),
                    ];
                    $this->Main->updateById('email', $data, $email->id);
                }
            }
        }
    }

    //@URL: http://localhost/spekta/index.php?c=AppController&m=updateStatusReservasi
    public function updateStatusReservasi()
    {
        $vehicles = $this->General->getWhere('vehicles_reservation', ['status' => 'APPROVED'])->result();
        $vhcData = [];
        foreach ($vehicles as $vhc) {
            $now = new DateTime(date('Y-m-d'));
            $exp = new DateTime(addDayToDate(date('Y-m-d', strtotime($vhc->start_date)), 1));
            if($exp < $now) {
                $vhcData[] = [
                    'id' => $vhc->id,
                    'status' => 'CLOSED'
                ];
            }
        }
        if(count($vhcData) > 0) {
            $this->General->updateMultiple('vehicles_reservation', $vhcData, 'id');
        }

        $mrooms = $this->General->getWhere('meeting_rooms_reservation', ['status' => 'APPROVED'])->result();
        $rmData = [];
        foreach ($mrooms as $mroom) {
            $now = new DateTime(date('Y-m-d'));
            $exp = new DateTime(addDayToDate(date('Y-m-d', strtotime($mroom->start_date)), 1));
            if($exp < $now) {
                $rmData[] = [
                    'id' => $mroom->id,
                    'status' => 'CLOSED'
                ];
            }
        }
        if(count($rmData) > 0) {
            $this->General->updateMultiple('meeting_rooms_reservation', $rmData, 'id');
        }
    }
}
