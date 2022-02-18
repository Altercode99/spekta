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

    //@URL: http://localhost/spekta/index.php?c=AppController&m=updateStatusReservasi
    public function updateStatusReservasi()
    {
        $updateVehicle = $this->General->update('vehicles_reservation', [
            'status' => 'CLOSED',
        ], ['status' => 'APPROVED', 'DATE(start_date) <' => date('2022-02-05')]);

        $updatMRoom = $this->General->update('meeting_rooms_reservation', [
            'status' => 'CLOSED',
        ], ['status' => 'APPROVED', 'DATE(start_date) <' => date('2022-02-05')]);
    }
}
