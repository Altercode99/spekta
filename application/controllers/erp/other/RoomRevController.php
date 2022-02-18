<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RoomRevController extends Erp_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('HrModel');
        $this->HrModel->myConstruct('hr');
        $this->load->model('OtherModel', 'Other');
        $this->Other->myConstruct('main');
        $this->auth->isAuth();
    }

    public function getRooms()
    {
        $rooms = $this->General->getWhere('meeting_rooms', ['location' => empLoc()])->result();
        $data = [];
        $detail = [];
        foreach ($rooms as $room) {
            $data[] = [
                'key' => $room->id,
                'label' => $room->name,
            ];
            $detail[$room->id] = [
                'name' => $room->name,
                'capacity' => $room->capacity,
            ];
        }

        response(['status' => 'success', 'data' => $data, 'detail' => $detail]);
    }

    public function getEmployees()
    {
        $emps = $this->HrModel->getEmployee(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($emps as $emp) {
            $xml .= "<row id='$emp->email'>";
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            $xml .= "<cell>0</cell>";
            $xml .= "<cell>" . cleanSC($emp->employee_name) . "</cell>";
            $xml .= "<cell>" . cleanSC($emp->sub_name) . "</cell>";
            $xml .= "<cell>" . cleanSC($emp->rank_name) . "</cell>";
            $xml .= "<cell>" . cleanSC($emp->email) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getGuests()
    {
        $guests = $this->General->getAll('guest_books')->result();
        $xml = "";
        $no = 1;
        foreach ($guests as $guest) {
            $xml .= "<row id='$guest->email'>";
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            $xml .= "<cell>0</cell>";
            $xml .= "<cell>" . cleanSC($guest->name) . "</cell>";
            $xml .= "<cell>" . cleanSC($guest->company) . "</cell>";
            $xml .= "<cell>" . cleanSC($guest->email) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function addGuest()
    {
        $post = prettyText(getGridPost(), ['c3', 'c4', 'c5']);
        $data = [];
        $dataUpdate = [];
        $mError = '';
        $mSuccess = '';
        foreach ($post as $key => $value) {
            if ($value['c0'] == '') {
                if (!filter_var($value['c4'], FILTER_VALIDATE_EMAIL)) {
                    $mError .= "$value[c4] format email tidak valid \n";
                } else {
                    $checkEmail = $this->Hr->getOne('employees', ['email' => $value['c4']]);
                    if ($checkEmail) {
                        $mError .= "$value[c4] email sudah digunakan \n";
                    } else {
                        $data[] = [
                            'name' => $value['c2'],
                            'company' => $value['c3'],
                            'email' => $value['c4'],
                            'created_by' => empId(),
                            'updated_by' => empId(),
                        ];
                        $mSuccess .= "$value[c3] berhasil di tambahkan \n";
                    }
                }
            } else {
                if (!filter_var($value['c5'], FILTER_VALIDATE_EMAIL)) {
                    $mError .= "$value[c5] format email tidak valid \n";
                } else {
                    $guest = $this->General->getOne('guest_books', ['email' => $value['c5']]);
                    if ($guest->email != $value['c5']) {
                        $checkEmail = $this->Hr->getOne('employees', ['email' => $value['c5']]);
                        if ($checkEmail) {
                            $mError .= "$value[c5] email sudah digunakan \n";
                        } else {
                            $dataUpdate[] = [
                                'name' => $value['c3'],
                                'company' => $value['c4'],
                                'email' => $value['c5'],
                                'updated_by' => empId(),
                            ];
                            $mSuccess .= "$value[c3] berhasil di tambahkan \n";
                        }
                    } else {
                        $dataUpdate[] = [
                            'name' => $value['c3'],
                            'company' => $value['c4'],
                            'email' => $value['c5'],
                            'updated_by' => empId(),
                        ];
                        $mSuccess .= "$value[c3] berhasil di tambahkan \n";
                    }
                }
            }
        }

        if (count($data) > 0) {
            $this->General->createMultiple('guest_books', $data);
        }
        if (count($dataUpdate) > 0) {
            $this->General->updateMultiple('guest_books', $dataUpdate, 'email');
        }
        xmlResponse('updated', $mSuccess . ',' . $mError);
    }

    public function getEvents()
    {
        $params = getParam();
        $rooms = $this->Other->getReservasedRoom($params['min_date'], $params['max_date']);
        $data = [];
        foreach ($rooms as $room) {
            $data[] = [
                'id' => $room->id,
                'text' => $room->room_name,
                'name' => $room->name,
                'meeting_type' => $room->meeting_type,
                'description' => $room->description,
                'room' => $room->room_id,
                'start_date' => date('Y-m-d H:i', strtotime($room->start_date)),
                'end_date' => date('Y-m-d H:i', strtotime($room->end_date)),
                'participant' => $room->participants,
                'meal' => $room->meal,
                'guest' => $room->guests,
                'color' => $room->color,
            ];
        }

        header("Content-Type: application/json");
        echo json_encode($data);
    }

    public function eventHandler()
    {
        $post = fileGetContent();

        $id = $post->id;
        $action = $post->action;
        $data = $post->data;

        if (isEmpty([
            'Judul Kegiatan' => $data->name,
            'Jenis Kegiatan' => $data->meeting_type,
            'Deskripsi Kegiatan' => $data->description,
            'Perserta' => $data->participant,
            'Waktu Mulai' => $data->start_date,
            'Waktu Selesai' => $data->end_date,
        ]));

        $start = date('Y-m-d', strtotime($data->start_date));
        $end = date('Y-m-d', strtotime($data->end_date));
        $startDate = date('Y-m-d H:i:s', strtotime($data->start_date));
        $endDate = date('Y-m-d H:i:s', strtotime($data->end_date));

        if (countHour($start, $end, 'd') > 0) {
            xmlResponse('error', "Waktu mulai dan selesai meeting harus di hari yang sama!");
        }

        if (countHour($startDate, $endDate, 'h') > 6) {
            xmlResponse('error', "Waktu selesai reservasi ruang meeting maksimal adalah 6 jam!");
        }

        $bookedRooms = $this->General->getWhere('meeting_rooms_reservation', ['room_id' => $data->room, 'DATE(start_date)' => $start, 'status !=' => 'REJECTED', 'status !=' => 'CLOSED'])->result();
        $dateExist = 0;
        $dt1 = "";
        $dt2 = "";
        foreach ($bookedRooms as $booked) {
            if (checkDateExist($startDate, $booked->start_date, $booked->end_date)) {
                if ($id != $booked->id) {
                    $dateExist++;
                    $dt1 = $startDate;
                }
            }

            if (checkDateExist($endDate, $booked->start_date, $booked->end_date)) {
                if ($id != $booked->id) {
                    $dateExist++;
                    $dt2 = $endDate;
                }
            }
        }

        if ($dateExist > 0) {
            $message = "";
            if ($dt1 != '' && $dt2 != '') {
                $message = "Tanggal $dt1 dan $dt2 sudah dibooked!";
            } else if ($dt1 != '' && $dt2 == '') {
                $message = "Tanggal $dt1 sudah dibooked!";
            } else if ($dt1 == '' && $dt2 != '') {
                $message = "Tanggal $dt2 sudah dibooked!";
            }
            xmlResponse('error', $message);
        }

        $expParticipant = explode(',', $data->participant);
        $expGuest = explode(',', $data->guest);
        $totalParticipant = count($expParticipant) + count($expGuest);

        if ($action === 'inserted') {
            $event = [
                'id' => $id,
                'location' => empLoc(),
                'name' => ucwords(strtolower($data->name)),
                'meeting_type' => $data->meeting_type,
                'description' => ucwords(strtolower($data->description)),
                'room_id' => $data->room,
                'start_date' => date('Y-m-d H:i:s', strtotime($data->start_date)),
                'end_date' => date('Y-m-d H:i:s', strtotime($data->end_date)),
                'duration' => countHour($startDate, $endDate, 'h'),
                'participants' => $data->participant,
                'guests' => $data->guest,
                'meal' => $data->meal,
                'total_participant' => $totalParticipant,
                'participant_confirmed' => 0,
                'participant_rejected' => 0,
                'repeat_meet' => $data->repeat,
                'created_by' => empId(),
                'updated_by' => empId(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $this->General->create('meeting_rooms_reservation', $event);

            $participants = count($expParticipant) > 0 ? $this->Hr->getWhereIn('employees', ['email' => $expParticipant])->result_array() : [];
            $guests = count($expGuest) > 0 ? $this->General->getWhereIn('guest_books', ['email' => $expGuest])->result_array() : [];
            
            foreach ($participants as $key => $value) {
                $dataPerson[] = [
                    'meeting_id' => $id,
                    'name' => $value['employee_name'],
                    'email' => $value['email'],
                    'company' => $value['location'],
                ];
            }

            foreach ($guests as $key => $value) {
                $dataPerson[] = [
                    'meeting_id' => $id,
                    'name' => $value['name'],
                    'email' => $value['email'],
                    'company' => $value['company'],
                ];
            }
            $this->General->createMultiple('meeting_participants', $dataPerson);
           
            if ($data->repeat > 1) {
                $step = 0;
                for ($i = 1; $i <= $data->repeat - 1; $i++) {
                    $newStart = addDayToDate($start, $i + $step);
                    $newStartDate = addDayToDate($startDate, $i + $step);
                    $newEndDate = addDayToDate($endDate, $i + $step);

                    $weekend = checkWeekend2($newStart);
                    if($weekend['status']) {
                        if($weekend['day'] == 'Sat') {
                            $step += 2;
                        } else {
                            $step += 1;
                        }

                        $newStart = addDayToDate($start, $i + $step);
                        $newStartDate = addDayToDate($startDate, $i + $step);
                        $newEndDate = addDayToDate($endDate, $i + $step);
                    }

                    $bookedRooms = $this->General->getWhere('meeting_rooms_reservation', ['room_id' => $data->room, 'DATE(start_date)' => $newStart, 'status !=' => 'REJECTED', 'status !=' => 'CLOSED'])->result();
                    $dateExist = 0;
                    $dt1 = "";
                    $dt2 = "";
                    foreach ($bookedRooms as $booked) {
                        if (checkDateExist($newStartDate, $booked->start_date, $booked->end_date)) {
                            if ($id != $booked->id) {
                                $dateExist++;
                                $dt1 = $newStartDate;
                            }
                        }

                        if (checkDateExist($newEndDate, $booked->start_date, $booked->end_date)) {
                            if ($id != $booked->id) {
                                $dateExist++;
                                $dt2 = $newEndDate;
                            }
                        }
                    }

                    if ($dateExist > 0) {
                        $message = "";
                        if ($dt1 != '' && $dt2 != '') {
                            $message = "Tanggal $dt1 dan $dt2 sudah dibooked! <br>";
                        } else if ($dt1 != '' && $dt2 == '') {
                            $message = "Tanggal $dt1 sudah dibooked! <br>";
                        } else if ($dt1 == '' && $dt2 != '') {
                            $message = "Tanggal $dt2 sudah dibooked! <br>";
                        }
                    } else {
                        $event = [
                            'id' => $id.$i,
                            'ref' => $id,
                            'location' => empLoc(),
                            'name' => ucwords(strtolower($data->name)),
                            'meeting_type' => $data->meeting_type,
                            'description' => ucwords(strtolower($data->description)),
                            'room_id' => $data->room,
                            'start_date' => $newStartDate,
                            'end_date' => $newEndDate,
                            'duration' => countHour($newStartDate, $newEndDate, 'h'),
                            'participants' => $data->participant,
                            'guests' => $data->guest,
                            'meal' => $data->meal,
                            'total_participant' => $totalParticipant,
                            'participant_confirmed' => 0,
                            'participant_rejected' => 0,
                            'created_by' => empId(),
                            'updated_by' => empId(),
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
                        $this->General->create('meeting_rooms_reservation', $event);

                        foreach ($participants as $key => $value) {
                            $dataPerson[] = [
                                'meeting_id' => $id.$i,
                                'name' => $value['employee_name'],
                                'email' => $value['email'],
                                'company' => $value['location'],
                            ];
                        }
            
                        foreach ($guests as $key => $value) {
                            $dataPerson[] = [
                                'meeting_id' => $id.$i,
                                'name' => $value['name'],
                                'email' => $value['email'],
                                'company' => $value['company'],
                            ];
                        }
                        $this->General->createMultiple('meeting_participants', $dataPerson);
                    }
                }
            }

            $this->meetingNotif($id);
            xmlResponse($action, "Reservsi ruang meeting berhasil dibuat", $id);
        } else if ($action === 'updated') {
            $checkOwner = $this->General->getDataById('meeting_rooms_reservation', $id);
            if ($checkOwner->status !== 'CREATED') {
                xmlResponse('error', "Update reservasi meeting gagal, data sudah di process!");
            }

            if ($checkOwner->created_by !== empId()) {
                xmlResponse('error', "Hanya bisa diupdate oleh PIC meeting tersebut!");
            }

            $event = [
                'id' => $id,
                'name' => ucwords(strtolower($data->name)),
                'meeting_type' => $data->meeting_type,
                'description' => ucwords(strtolower($data->description)),
                'room_id' => $data->room,
                'start_date' => date('Y-m-d H:i:s', strtotime($data->start_date)),
                'end_date' => date('Y-m-d H:i:s', strtotime($data->end_date)),
                'duration' => countHour($startDate, $endDate, 'h'),
                'participants' => $data->participant,
                'guests' => $data->guest,
                'meal' => $data->meal,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            if ($data->guest != $checkOwner->guests) {
                $existingMail = [];
                $newMail = [];
                $eventMail = explode(',', $checkOwner->guests);
                $currGuest = count($eventMail) > 0 ? $this->General->getWhereIn('meeting_participants', ['email' => $eventMail, 'id' => array($id)])->result_array() : [];
                foreach ($currGuest as $key => $value) {
                    $existingMail[$value['email']] = [
                        'meeting_id' => $id,
                        'name' => $value['name'],
                        'company' => $value['company'],
                        'email' => $value['email'],
                        'status' => $value['status'],
                    ];
                }

                $guestMail = explode(',', $data->guest);
                $newGuest = count($guestMail) > 0 ? $this->General->getWhereIn('guest_books', ['email' => $guestMail])->result_array() : [];

                foreach ($newGuest as $key => $value) {
                    if (array_key_exists($value['email'], $existingMail)) {
                        $newMail[] = $existingMail[$value['email']];
                    } else {
                        $newMail[] = [
                            'meeting_id' => $id,
                            'name' => $value['name'],
                            'company' => $value['company'],
                            'email' => $value['email'],
                        ];
                    }
                }
                count($newGuest) > 0 && $this->General->deleteWhereIn('meeting_participants', ['email' => $eventMail], ['meeting_id' => $id]);
                count($newGuest) > 0 && $this->General->createMultiple('meeting_participants', $newMail);
            }

            if ($data->participant != $checkOwner->participants) {
                $existingMail = [];
                $newMail = [];
                $eventMail = explode(',', $checkOwner->participants);
                $currPart = count($eventMail) > 0 ? $this->General->getWhereIn('meeting_participants', ['email' => $eventMail, 'id' => array($id)])->result_array() : [];
                foreach ($currPart as $key => $value) {
                    $existingMail[$value['email']] = [
                        'meeting_id' => $id,
                        'name' => $value['name'],
                        'company' => $value['company'],
                        'email' => $value['email'],
                        'status' => $value['status'],
                    ];
                }

                $partMail = explode(',', $data->participant);
                $newPart = count($partMail) > 0 ? $this->Hr->getWhereIn('employees', ['email' => $partMail])->result_array() : [];

                foreach ($newPart as $key => $value) {
                    if (array_key_exists($value['email'], $existingMail)) {
                        $newMail[] = $existingMail[$value['email']];
                    } else {
                        $newMail[] = [
                            'meeting_id' => $id,
                            'name' => $value['employee_name'],
                            'company' => $value['location'],
                            'email' => $value['email'],
                        ];
                    }
                }
                count($newPart) > 0 && $this->General->deleteWhereIn('meeting_participants', ['email' => $eventMail], ['meeting_id' => $id]);
                count($newPart) > 0 && $this->General->createMultiple('meeting_participants', $newMail);
            }

            $event['participant_confirmed'] = $this->General->countWhere('meeting_participants', ['meeting_id' => $id, 'status' => 'HADIR']);
            $event['participant_rejected'] = $this->General->countWhere('meeting_participants', ['meeting_id' => $id, 'status' => 'TIDAK HADIR']);
            $event['total_participant'] = $this->General->countWhere('meeting_participants', ['meeting_id' => $id]);
            $this->General->updateById('meeting_rooms_reservation', $event, $id);
            xmlResponse($action, "Reservsi ruang meeting berhasil diupdate", $id);
        } else if ($action === 'deleted') {
            $checkOwner = $this->General->getDataById('meeting_rooms_reservation', $id);
            if ($checkOwner->created_by !== empId()) {
                xmlResponse('error', "Hanya bisa dihapus oleh PIC meeting tersebut!");
            }
            if ($checkOwner->status !== 'CREATED') {
                xmlResponse('error', "Jadwal sudah di proses!");
            }
            $this->General->deleteById('meeting_rooms_reservation', $id);
            $this->General->delete('meeting_participants', ['meeting_id' => $id]);
            xmlResponse($action, "Meeting dibatalkan!");
        }
    }

    public function meetingNotif($id)
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

        $meeting = $this->Other->getMeetingDetail($id);
        $tokenAppv = LIVE_URL . "index.php?c=PublicController&m=updateMeetRevStatus&token=" . simpleEncrypt("$id:approve");
        $tokenReject = LIVE_URL . "index.php?c=PublicController&m=updateMeetRevStatus&token=" . simpleEncrypt("$id:reject");
        $linkApprove = LIVE_URL . "index.php?c=PublicController&m=pinVerification&action=positive&token=" . simpleEncrypt($tokenAppv);
        $linkReject = LIVE_URL . "index.php?c=PublicController&m=pinVerification&action=negative&token=" . simpleEncrypt($tokenReject);
        $message = $this->load->view('html/meeting_rooms/email/general_notification', [
            'meeting' => $meeting,
            'linkApprove' => $linkApprove,
            'linkReject' => $linkReject,
        ], true);
        $dataEmail = [
            'alert_name' => 'MEETING_NOTIFICATION',
            'email_to' => $picEmail,
            'subject' => "Notifikasi Meeting Di Ruang $meeting->room_name @" . toIndoDateTime2($meeting->start_date),
            'subject_name' => "Spekta Alert: Notifikasi Meeting Di Ruang $meeting->room_name @" . toIndoDateTime2($meeting->start_date),
            'message' => $message,
        ];
        $this->Main->create('email', $dataEmail);
    }

    public function getRoomsView()
    {
        $template = $this->load->view('html/meeting_rooms/view_template', null, true);
        $rooms = $this->General->getWhere('meeting_rooms', ['location' => empLoc()])->result();

        $dataRooms = [];
        foreach ($rooms as $room) {
            $path = $room->filename ? './assets/images/meeting_rooms/' . $room->filename : './public/img/no-image.png';
            $dataRooms[] = [
                'id' => $room->id,
                'img_url' => $path,
                'name' => $room->name,
                'capacity' => $room->capacity,
                'building' => $room->building,
                'on_floor' => $room->on_floor,
                'facility' => maxStringLength($room->facility, 90),
            ];
        }

        response([
            'template' => $template,
            'data' => $dataRooms,
        ]);
    }

    public function getEventCalendar()
    {
        $post = fileGetContent();
        $table = $post->table;
        $column = $post->column;
        $id = $post->id;
        $date = explode('-', date('Y-m', strtotime($post->date)));
        $events = $this->General->getWhere($table, [
            'YEAR(start_date)' => $date[0],
            'MONTH(start_date)' => $date[1],
            $column => $id,
        ])->result();
        $dates = '';
        foreach ($events as $event) {
            if ($dates === '') {
                $dates = date('Y-m-d', strtotime($event->start_date));
            } else {
                $dates = $dates . ',' . date('Y-m-d', strtotime($event->start_date));
            }
        }
        response(['status' => 'success', 'dates' => $dates]);
    }

    public function getEventDate()
    {
        $post = fileGetContent();
        $roomId = $post->roomId;
        $date = date('Y-m-d', strtotime($post->date));
        $events = $this->Other->getEventDetail($roomId, $date);
        $template = $this->load->view('html/meeting_rooms/event_detail', ['events' => $events], true);
        $total = count($events);
        response(['status' => 'success', 'template' => $template, 'total' => $total]);
    }

    public function getEventDetail()
    {
        $params = getParam();
        $id = $params['eventId'];
        $event = $this->General->getDataById('meeting_rooms_reservation', $id);
        $email = explode(',', $event->participants);
        $emps = $this->Other->getEmployee($email);
        $emailGuest = explode(',', $event->guests);
        $guests = $this->Other->getGuest($emailGuest);
        $xml = "";
        $xml .= "<row id='name'>";
        $xml .= "<cell>Judul Kegiatan</cell>";
        $xml .= "<cell>" . cleanSC($event->name) . "</cell>";
        $xml .= "</row>";
        $xml .= "<row id='description'>";
        $xml .= "<cell>Deskripsi Kegiatan)</cell>";
        $xml .= "<cell>" . cleanSC($event->description) . "</cell>";
        $xml .= "</row>";
        $xml .= "<row id='start_date'>";
        $xml .= "<cell>Waktu Mulai)</cell>";
        $xml .= "<cell>" . cleanSC(toIndoDateTime($event->start_date)) . "</cell>";
        $xml .= "</row>";
        $xml .= "<row id='end_date'>";
        $xml .= "<cell>Waktu Selesai)</cell>";
        $xml .= "<cell>" . cleanSC(toIndoDateTime($event->end_date)) . "</cell>";
        $xml .= "</row>";
        $xml .= "<row id='duration'>";
        $xml .= "<cell>Durasi</cell>";
        $xml .= "<cell>" . cleanSC("$event->duration Jam") . "</cell>";
        $xml .= "</row>";
        $no = 1;
        foreach ($emps as $emp) {
            $xml .= "<row id='$emp->email'>";
            if ($no === 1) {
                $xml .= "<cell>Peserta</cell>";
            } else {
                $xml .= "<cell></cell>";
            }
            $xml .= "<cell>" . cleanSC("$emp->employee_name ($emp->sub_name)") . "</cell>";
            $xml .= "</row>";
            $no++;
        }

        $no1 = 1;
        foreach ($guests as $guest) {
            $xml .= "<row id='$guest->email'>";
            if ($no1 === 1) {
                $xml .= "<cell>Tamu</cell>";
            } else {
                $xml .= "<cell></cell>";
            }
            $xml .= "<cell>" . cleanSC("$guest->name ($guest->company)") . "</cell>";
            $xml .= "</row>";
            $no1++;
        }

        gridXmlHeader($xml);

    }
}
