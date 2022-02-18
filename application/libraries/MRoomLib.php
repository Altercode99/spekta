<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class MRoomLib
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

        $this->Main = $this->ci->Main;
        $this->Hr = $this->ci->Hr;
        $this->General = $this->ci->General;
        $this->Other = $this->ci->Other;
        $this->load = $this->ci->load;
    }

    public function meetInvitation($emp, $id)
    {
        $meeting = $this->Other->getMeetingDetail($id);
        $participants = explode(',', $meeting->participants);
        $guests = explode(',', $meeting->guests);

        $locName = $this->Main->getOne('locations', ['code' => $emp->location])->name;

        if(count($participants) > 0) {
            foreach ($participants as $value) {
                if($value != '') {
                    $participant = $this->Hr->getOne('employees', ['email' => $value]);
                    $tokenAccept = simpleEncrypt($id . ':' . $value . ':accept');
                    $tokenReject = simpleEncrypt($id . ':' . $value . ':reject');
                    $urlAccept = LIVE_URL . "index.php?c=PublicController&m=responseMeeting&token=$tokenAccept";
                    $urlReject = LIVE_URL . "index.php?c=PublicController&m=responseMeeting&token=$tokenReject";
                    $type = $meeting->meeting_type == 'internal' ? 'Meeting Internal' : 'Meeting External';
                    $message = $this->load->view('html/meeting_rooms/email/invitation_participant', [
                        'linkA' => $urlAccept,
                        'linkR' => $urlReject,
                        'meeting' => $meeting,
                        'emp' => $participant,
                        'location' => $locName,
                        'type' => $type,
                    ], true);
        
                    $data[] = [
                        'alert_name' => 'MEETING_INVITATION',
                        'email_to' => $value,
                        'subject' => "Undangan Meeting Dari $meeting->employee_name ($meeting->sub_department) Di Ruang $meeting->room_name @" . toIndoDateTime2($meeting->start_date),
                        'subject_name' => "Spekta Alert: Undangan Meeting Dari $meeting->employee_name ($meeting->sub_department) Di Ruang $meeting->room_name @" . toIndoDateTime2($meeting->start_date),
                        'message' => $message,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
            }
        }

        if(count($guests) > 0) {
            foreach ($guests as $value) {
                if($value != '') {
                    $guest = $this->General->getOne('guest_books', ['email' => $value]);
                    $tokenAccept = simpleEncrypt($id . ':' . $value . ':accept');
                    $tokenReject = simpleEncrypt($id . ':' . $value . ':reject');
                    $urlAccept = LIVE_URL . "index.php?c=PublicController&m=responseMeeting&token=$tokenAccept";
                    $urlReject = LIVE_URL . "index.php?c=PublicController&m=responseMeeting&token=$tokenReject";
                    $type = 'Meeting External';
                    $message = $this->load->view('html/meeting_rooms/email/invitation_guest', [
                        'linkA' => $urlAccept,
                        'linkR' => $urlReject,
                        'meeting' => $meeting,
                        'guest' => $guest,
                        'location' => $locName,
                        'type' => $type,
                    ], true);
                    $data[] = [
                        'alert_name' => 'MEETING_INVITATION',
                        'email_to' => $value,
                        'subject' => "Undangan Meeting Dari $locName (Ruang $meeting->room_name) @" . toIndoDateTime2($meeting->start_date),
                        'subject_name' => "Spekta Alert: Undangan Meeting $locName (Ruang $meeting->room_name) @" . toIndoDateTime2($meeting->start_date),
                        'message' => $message,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
            }
        }
        
        $refs = $this->Other->getMeetingDetailRef($id);
        $no = 2;
        foreach ($refs as $ref) {
            $participants = explode(',', $ref->participants);
            $guests = explode(',', $ref->guests);

            if(count($participants) > 0) {
                foreach ($participants as $value) {
                    if($value != '') {
                        $participant = $this->Hr->getOne('employees', ['email' => $value]);
                        $tokenAccept = simpleEncrypt($ref->id . ':' . $value . ':accept');
                        $tokenReject = simpleEncrypt($ref->id . ':' . $value . ':reject');
                        $urlAccept = LIVE_URL . "index.php?c=PublicController&m=responseMeeting&token=$tokenAccept";
                        $urlReject = LIVE_URL . "index.php?c=PublicController&m=responseMeeting&token=$tokenReject";
                        $type = $ref->meeting_type == 'internal' ? 'Meeting Internal' : 'Meeting External';
                        $message = $this->load->view('html/meeting_rooms/email/invitation_participant', [
                            'linkA' => $urlAccept,
                            'linkR' => $urlReject,
                            'meeting' => $ref,
                            'emp' => $participant,
                            'location' => $locName,
                            'type' => $type,
                        ], true);
        
                        $data[] = [
                            'alert_name' => 'MEETING_INVITATION',
                            'email_to' => $value,
                            'subject' => "Undangan Meeting Sesi #$no Dari $ref->employee_name ($ref->sub_department) Di Ruang $ref->room_name @" . toIndoDateTime2($ref->start_date),
                            'subject_name' => "Spekta Alert: Undangan Meeting Sesi #$no Dari $ref->employee_name ($ref->sub_department) Di Ruang $ref->room_name @" . toIndoDateTime2($ref->start_date),
                            'message' => $message,
                            'created_at' => $ref->start_date,
                        ];
                    }
                }
            }
        
            if(count($guests) > 0) {
                foreach ($guests as $value) {
                    if($value != '') {
                        $guest = $this->General->getOne('guest_books', ['email' => $value]);
                        $tokenAccept = simpleEncrypt($ref->id . ':' . $value . ':accept');
                        $tokenReject = simpleEncrypt($ref->id . ':' . $value . ':reject');
                        $urlAccept = LIVE_URL . "index.php?c=PublicController&m=responseMeeting&token=$tokenAccept";
                        $urlReject = LIVE_URL . "index.php?c=PublicController&m=responseMeeting&token=$tokenReject";
                        $type = 'Meeting External';
                        $message = $this->load->view('html/meeting_rooms/email/invitation_guest', [
                            'linkA' => $urlAccept,
                            'linkR' => $urlReject,
                            'meeting' => $ref,
                            'guest' => $guest,
                            'location' => $locName,
                            'type' => $type,
                        ], true);
                        $data[] = [
                            'alert_name' => 'MEETING_INVITATION',
                            'email_to' => $value,
                            'subject' => "Undangan Meeting Sesi #$no Dari $locName (Ruang $ref->room_name) @" . toIndoDateTime2($ref->start_date),
                            'subject_name' => "Spekta Alert: Undangan Meeting Sesi #$no $locName (Ruang $ref->room_name) @" . toIndoDateTime2($ref->start_date),
                            'message' => $message,
                            'created_at' => $ref->start_date,
                        ];
                    }
                }
            }
            $no++;
        }

        $this->Main->createMultiple('email', $data);
        if ($emp->rank_id == 5 || $emp->rank_id == 6) {
            $this->approvalNotif('ASMAN', $id, $emp);
        } else if ($emp->rank_id == 2 || $emp->rank_id == 3) {
            $this->approvalNotif('Supervisor', $id, $emp);
        }
    }

    public function approvalNotif($level, $id, $emp)
    {
        $picEmail = '';
        $pics = $this->Main->getWhere('pics', ['code' => 'meeting_rooms'])->result();
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
            $meeting = $this->Other->getMeetingDetail($id);
            $message = $this->load->view('html/meeting_rooms/email/approval_notification', ['meeting' => $meeting, 'empName' => $emp->employee_name], true);
            $dataEmail = [
                'alert_name' => 'APPROVAL_MEETING_NOTIFICATION',
                'email_to' => $picEmail,
                'subject' => "Notifikasi Approval Meeting Di Ruang $meeting->room_name @" . toIndoDateTime2($meeting->start_date),
                'subject_name' => "Spekta Alert: Notifikasi Approval Meeting Di Ruang $meeting->room_name @" . toIndoDateTime2($meeting->start_date),
                'message' => $message,
            ];
            $this->Main->create('email', $dataEmail);
        }
    }

    public function rejectionNotif($level, $id, $empName)
    {
        $meeting = $this->Other->getMeetingDetail($id);
        $picEmail = '';
        $req = $this->Hr->getOne('employees', ['id' => $meeting->created_by]);
        $picEmail = $req->email;

        $pics = $this->Main->getWhere('pics', ['code' => 'meeting_rooms'])->result();
        foreach ($pics as $pic) {
            $picEmail = $picEmail . ',' . $pic->pic_emails;
        }

        if ($level == 'ASMAN') {
            $asmans = $this->Hr->getWhere('employees', ['sub_department_id' => 12], '*', null, null, ['rank_id' => ['3', '4']])->result();
            if ($asmans) {
                foreach ($asmans as $asman) {
                    $picEmail = $picEmail . ',' . $asman->email;
                }
            }
        }

        if ($level == 'Supervisor') {
            $spvs = $this->Hr->getWhere('employees', ['division_id' => 34], '*', null, null, ['rank_id' => ['5', '6']])->result();
            if ($spvs) {
                foreach ($spvs as $spv) {
                    $picEmail = $picEmail . ',' . $spv->email;
                }
            }
        }

        if ($picEmail != '') {
            $message = $this->load->view('html/meeting_rooms/email/reject_notification', ['meeting' => $meeting, 'empName' => $empName], true);
            $dataEmail = [
                'alert_name' => 'REJECTION_MEETING_NOTIFICATION',
                'email_to' => $picEmail,
                'subject' => "Notifikasi Rejection Meeting Di Ruang $meeting->room_name @" . toIndoDateTime2($meeting->start_date),
                'subject_name' => "Spekta Alert: Notifikasi Rejection Meeting Di Ruang $meeting->room_name @" . toIndoDateTime2($meeting->start_date),
                'message' => $message,
            ];
            $this->Main->create('email', $dataEmail);
        }
    }
}
