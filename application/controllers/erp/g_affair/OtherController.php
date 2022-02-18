<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OtherController extends Erp_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('GaModel');
        $this->GaModel->myConstruct('general');
        $this->load->model('OtherModel', 'Other');
        $this->Other->myConstruct('main');
        $this->load->model('HrModel');
        $this->HrModel->myConstruct('hr');

        $this->auth->isAuth();
    }

    /* ========================= CATHERING PRICE FUNCTIONS  =========================*/
    public function catheringPriceGrid()
    {
        $catherings = $this->GaModel->getCatheringPrice(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($catherings as $cat) {

            $color = $cat->status === 'ACTIVE' ? "bgColor='#efd898'" : null;

            $xml .= "<row id='$cat->id'>";
            $xml .= "<cell $color>" . cleanSC($no) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($cat->vendor_name) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($cat->price) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($cat->status) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDate($cat->expired)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($cat->emp1) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($cat->emp1) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime($cat->created_at)) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function catheringForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $cathering = $this->General->getDataById('catherings', $params['id']);
            fetchFormData($cathering);
        } else {
            $post = prettyText(getPost(), ['vendor_name']);
            if (!isset($post['id'])) {
                $this->createCathering($post);
            } else {
                $this->updateCathering($post);
            }
        }
    }

    public function createCathering($post)
    {
        $catheting = $this->General->getOne('catherings', ['vendor_name' => $post['vendor_name']]);
        isExist(["Vendor katering $post[vendor_name]" => $catheting]);

        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');
        $insertId = $this->General->create('catherings', $post);
        xmlResponse('inserted', $post['vendor_name']);
    }

    public function updateCathering($post)
    {
        $catheting = $this->General->getDataById('catherings', $post['id']);
        isDelete(["Vendor katering $post[vendor_name]" => $catheting]);

        if ($catheting && $catheting->vendor_name !== $post['vendor_name']) {
            $checkCath = $this->General->getOne('catherings', ['vendor_name' => $post['vendor_name']]);
            isExist(["Vendor katering $post[vendor_name]" => $checkCath]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->General->updateById('catherings', $post, $post['id']);
        xmlResponse('updated', $post['vendor_name']);
    }

    public function catheringDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $status = $this->General->getDataById('catherings', $data->id)->status;
            if ($status !== 'ACTIVE') {
                $mSuccess .= "- $data->field berhasil dihapus <br>";
                $this->General->delete('catherings', ['id' => $data->id]);
            } else {
                $mError .= "- $data->field sudah digunakan! <br>";
            }
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    public function setCathActive()
    {
        $post = fileGetContent();
        $this->General->update('catherings', ['status' => 'NONACTIVE'], ['status' => 'ACTIVE']);
        $update = $this->General->updateById('catherings', ['status' => 'ACTIVE'], $post->id);
        if ($update) {
            response(['status' => 'success', 'message' => 'Berhasil mengaktifkan vendor']);
        } else {
            response(['status' => 'error', 'message' => 'Gagal mengaktifkan vendor!']);
        }
    }

    /* ========================= SNACK PRICE FUNCTIONS  =========================*/
    public function getSnackGrid()
    {
        $snacks = $this->GaModel->getSnackGrid(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($snacks as $snack) {
            $xml .= "<row id='$snack->id'>";
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            $xml .= "<cell>" . cleanSC($snack->name) . "</cell>";
            $xml .= "<cell>" . cleanSC($snack->price) . "</cell>";
            $xml .= "<cell>" . cleanSC($snack->emp1) . "</cell>";
            $xml .= "<cell>" . cleanSC($snack->emp1) . "</cell>";
            $xml .= "<cell>" . cleanSC(toIndoDateTime($snack->created_at)) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function checkBeforeAddFile()
    {
        $post = fileGetContent();
        $id = $post->id;
        $isExist = false;
        if (!$id) {
            $checkSnack = $this->General->getOne('snacks', [
                'name' => $post->name,
            ]);
            if ($checkSnack) {
                $isExist = true;
            }
        } else {
            $snack = $this->General->getDataById('snacks', $id);
            if ($snack) {
                if ($snack->name !== $post->name) {
                    $checkSnack = $this->General->getOne('snacks', [
                        'name' => $post->name,
                    ]);
                    if ($checkSnack) {
                        $isExist = true;
                    }
                }
            } else {
                response(['status' => 'deleted']);
            }
        }

        if (!$isExist) {
            response(['status' => 'success']);
        } else {
            response(['status' => 'exist', 'message' => 'Data snack meeting sudah digunakan!']);
        }
    }

    public function snackForm()
    {
        $params = getParam();
        if (isset($params['id'])) {
            $snack = $this->General->getDataById('snacks', $params['id'], 'id,name,price,filename');
            fetchFormData($snack);
        } else {
            $post = prettyText(getPost(), ['name']);
            if (!isset($post['id'])) {
                $this->createSnack($post);
            } else {
                $this->updateSnack($post);
            }
        }
    }

    public function createSnack($post)
    {
        $checkSnack = $this->General->getOne('snacks', [
            'name' => $post['name'],
        ]);

        isExist(["Snack meeting $post[name]" => $checkSnack]);

        $post['location'] = empLoc();
        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $insertId = $this->General->create('snacks', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function updateSnack($post)
    {
        $snack = $this->General->getDataById('snacks', $post['id']);
        isDelete(["Meeting snack $post[name]" => $snack]);

        if ($snack->name !== $post['name']) {
            $checkSnack = $this->General->getOne('snacks', [
                'name' => $post['name'],
            ]);
            isExist(["Meeting snack $post[name]" => $checkSnack]);
        }

        $post['updated_by'] = empId();
        $post['updated_at'] = date('Y-m-d H:i:s');

        $this->General->updateById('snacks', $post, $post['id']);
        xmlResponse('updated', $post['name']);
    }

    public function snackDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $checkSnack = $this->General->getOne('meeting_rooms_reservation', ['snack_id' => $data->id]);
            if (!$checkSnack) {
                $snack = $this->General->getDataById('snacks', $data->id);
                $this->General->delete('snacks', ['id' => $data->id]);
                if (file_exists('./assets/images/meeting_snacks/' . $snack->filename)) {
                    unlink('./assets/images/meeting_snacks/' . $snack->filename);
                }
                $mSuccess .= "- $data->field berhasil dihapus <br>";
            } else {
                $mSuccess .= "- $data->field sudah digunakan! <br>";
            }
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    /* ========================= MEETING RESERVATION FUNCTIONS  =========================*/
    public function getMeetingRevGrid()
    {
        $params = getParam();
        $revisions = $this->GaModel->getMeetingRevGrid($params)->result();
        $xml = "";
        $no = 1;
        foreach ($revisions as $rev) {
            $color = "";
            if (!isset($params['report'])) {
                if ($rev->status == 'APPROVED') {
                    $color = "bgColor='#75b175'";
                } else if ($rev->status == 'REJECTED') {
                    $color = "bgColor='#c94b62'";
                } else if ($rev->status == 'CLOSED') {
                    $color = "bgColor='#dda94a'";
                }
            }
            $type = $rev->meeting_type == 'internal' ? 'Meeting Internal' : 'Meeting External';
            $meal = $rev->meal > 0 ? '✓' : '-';
            $notConfirm = $rev->total_participant - ($rev->participant_confirmed + $rev->participant_rejected);
            $reason = $rev->reason ? $rev->reason : '-';
            $xml .= "<row id='$rev->id'>";
            $xml .= "<cell $color>" . cleanSC($no) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->id) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->ref ? $rev->ref : '-') . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->name) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($type) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->room_name) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($rev->start_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($rev->end_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->duration) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($meal) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->total_participant) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->participant_confirmed) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->participant_rejected) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($notConfirm) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->snack_name) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->snack_price) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->snack_price * $rev->participant_confirmed) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->status) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($reason) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->emp1) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->emp2) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime($rev->created_at)) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getMeetingRevGroupGrid()
    {
        $params = getParam();
        $revisions = $this->GaModel->getMeetingRevGroupGrid($params)->result();
        $data = [];
        foreach ($revisions as $rev) {
            $data[$rev->room_id] = [
                'room_name' => $rev->room_name,
                'total_rev' => $rev->total_rev,
                'total_person' => $rev->total_person,
                'total_hour' => $rev->total_hour,
                'total_snack' => toNumber($rev->total_snack),
            ];
        }

        $rooms = $this->General->getAll('meeting_rooms')->result();
        $xml = "";
        $no = 1;
        foreach ($rooms as $room) {
            if(array_key_exists($room->id, $data)) {
                $xml .= "<row id='$no'>";
                $xml .= "<cell>" . cleanSC($no) . "</cell>";
                $xml .= "<cell>" . cleanSC($room->name) . "</cell>";
                $xml .= "<cell>" . cleanSC($data[$room->id]['total_rev']) . "</cell>";
                $xml .= "<cell>" . cleanSC($data[$room->id]['total_person']) . "</cell>";
                $xml .= "<cell>" . cleanSC($data[$room->id]['total_hour']) . "</cell>";
                $xml .= "<cell>" . cleanSC($data[$room->id]['total_snack']) . "</cell>";
                $xml .= "</row>";
            } else {
                $xml .= "<row id='$no'>";
                $xml .= "<cell>" . cleanSC($no) . "</cell>";
                $xml .= "<cell>" . cleanSC($room->name) . "</cell>";
                $xml .= "<cell>" . 0 . "</cell>";
                $xml .= "<cell>" . 0 . "</cell>";
                $xml .= "<cell>" . 0 . "</cell>";
                $xml .= "<cell>" . 0 . "</cell>";
                $xml .= "</row>";
            }
            
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getSnacks()
    {
        $post = fileGetContent();
        $rev = $this->General->getDataById('meeting_rooms_reservation', $post->id);
        $snacks = $this->General->getWhere('snacks', ['id !=' => 0])->result();
        $template = $this->load->view('html/meeting_rooms/snack_list', ['rev' => $rev, 'snacks' => $snacks], true);
        response(['status' => 'success', 'template' => $template, 'snack_id' => $rev->snack_id]);
    }

    public function appvReservation()
    {
        $post = fileGetContent();
        $revId = $post->id;
        $data = [
            'status' => 'APPROVED',
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if (isset($post->snackId)) {
            $data['snack_id'] = $post->snackId;
        }

        $rev = $this->General->getDataById('meeting_rooms_reservation', $revId);
        if ($rev->status == 'CREATED') {
            $this->General->updateById('meeting_rooms_reservation', $data, $revId);
            $this->General->update('meeting_rooms_reservation', $data, ['ref' => $revId]);
            $emp = $this->Hr->getDataById('employees', empId());
            $this->mroomlib->meetInvitation($emp, $revId);
            response(['status' => 'success', 'message' => 'Berhasil approve reservasi ruang']);
        } else {
            response(['status' => 'error', 'message' => 'Sudah di approve sebelumnya!']);
        }
    }

    public function changeRevSnack()
    {
        $post = fileGetContent();
        $revId = $post->id;
        $data = [
            'snack_id' => $post->snackId,
        ];

        $rev = $this->General->getDataById('meeting_rooms_reservation', $revId);
        if ($rev->status == 'APPROVED') {
            $this->General->updateById('meeting_rooms_reservation', $data, $revId);
            response(['status' => 'success', 'message' => 'Berhasil mengubah snack meeting']);
        } else {
            response(['status' => 'error', 'message' => 'Status meeting tersebut belum di approve!']);
        }
    }

    public function rejectReservation()
    {
        $post = prettyText(getPost(), ['reason']);
        $id = $post['id'];
        $data = [
            'status' => 'REJECTED',
            'reason' => $post['reason'],
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $rev = $this->General->getDataById('meeting_rooms_reservation', $id);
        if ($rev->status == 'CREATED') {
            $this->General->updateById('meeting_rooms_reservation', $data, $id);
            if (empRank() == 5 || empRank() == 6) {
                $this->mroomlib->rejectionNotif('ASMAN', $id, empName());
            } else if (empRank() == 2 || empRank() == 3) {
                $this->mroomlib->rejectionNotif('Supervisor', $id, empName());
            }
            xmlResponse('updated', 'Berhasil menolak reservasi ruang meeting');
        } else {
            xmlResponse('error', 'Sudah di reject sebelumnya!');
        }
    }

    public function updateConfirmBatch()
    {
        $post = getGridPost();
        $data = [];
        $mError = '';
        $mSuccess = '';

        foreach ($post as $key => $value) {
            if ($value['c17'] != 'REJECTED') {
                $data[] = [
                    'id' => $key,
                    'participant_confirmed' => $value['c11'],
                ];
                $mSuccess .= "$key berhasil diubah \n";
            } else {
                $mError .= "$key tidak bisa diubah! \n";
            }
        }

        count($data) > 0 ? $this->General->updateMultiple('meeting_rooms_reservation', $data, 'id') : false;
        xmlResponse('updated', $mSuccess . ',' . $mError);
    }

    public function changeRevTime()
    {
        $post = getPost();
        $meetId = $post['id'];
        $start = $post['start_date'];
        $end = $post['end_date'];

        $meeting = $this->GaModel->getMeetingRevGrid(['equal_id' => $meetId])->row();
        $date = explode(' ', $meeting->start_date)[0];
        $startDate = date('Y-m-d H:i:s', strtotime($date . ' ' . $start . ':00'));
        $endDate = date('Y-m-d H:i:s', strtotime($date . ' ' . $end . ':00'));

        if ($endDate <= $startDate) {
            xmlResponse('error', "Waktu selesai harus lebih besar dari waktu mulai!");
        }

        if (countHour($startDate, $endDate, 'h') > 6) {
            xmlResponse('error', "Waktu selesai reservasi ruang meeting maksimal adalah 6 jam!");
        }

        $bookedRooms = $this->General->getWhere('meeting_rooms_reservation',
            ['room_id' => $meeting->room_id, 'DATE(start_date)' => $date],
            '*', null, null, null, ['status' => ['CLOSED', 'REJECTED']]
        )->result();
        $dateExist = 0;
        $dt1 = "";
        $dt2 = "";
        foreach ($bookedRooms as $booked) {
            if (checkDateExist($startDate, $booked->start_date, $booked->end_date)) {
                if ($meetId != $booked->id) {
                    $dateExist++;
                    $dt1 = $startDate;
                }
            }

            if (checkDateExist($endDate, $booked->start_date, $booked->end_date)) {
                if ($meetId != $booked->id) {
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

        $duration = countHour($startDate, $endDate, 'h');
        $data = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'duration' => $duration,
        ];

        if($duration >= 2) {
            $data['meal'] = 1;
        }

        $this->General->updateById('meeting_rooms_reservation', $data, $meetId);
        xmlResponse("updated", "Berhasil mengubah waktu reservasi ruang meeting");
    }

    public function closeReservation()
    {
        $post = fileGetContent();
        $id = $post->id;
        $data = [
            'status' => 'CLOSED',
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $status = $this->General->getDataById('meeting_rooms_reservation', $id)->status;
        if ($status !== 'CLOSED') {
            $this->General->updateById('meeting_rooms_reservation', $data, $id);
            response(['status' => 'success', 'message' => 'Berhasil menutup meeting']);
        } else {
            response(['status' => 'error', 'message' => 'Meeting sudah di tutup sebelumnya!']);
        }
    }

    /* ========================= VEHICLE RESERVATION FUNCTIONS  =========================*/
    public function getVehicleRevGrid()
    {
        $params = getParam();
        $revisions = $this->GaModel->getVehicleRevGrid($params)->result();
        $xml = "";
        $no = 1;
        foreach ($revisions as $rev) {
            $color = "";
            if (!isset($params['report'])) {
                if ($rev->status == 'APPROVED') {
                    $color = "bgColor='#75b175'";
                } else if ($rev->status == 'REJECTED') {
                    $color = "bgColor='#c94b62'";
                } else if ($rev->status == 'CLOSED') {
                    $color = "bgColor='#dda94a'";
                }
            }

            $color2 = $color;
            if (!isset($params['report'])) {
                if ($rev->driver_confirmed == 'DISETUJUI') {
                    $color2 = "bgColor='#75b175'";
                } else if ($rev->driver_confirmed == 'MENOLAK') {
                    $color2 = "bgColor='#dda94a'";
                }
            }

            $type = $rev->trip_type == 'drop' ? 'Pergi Saja (Drop)' : 'Pulang Pergi';
            $reason = $rev->reason ? $rev->reason : '-';
            $xml .= "<row id='$rev->id'>";
            $xml .= "<cell $color>" . cleanSC($no) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->id) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->destination) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->vehicle) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($type) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->driver) . "</cell>";
            $xml .= "<cell $color2>" . cleanSC($rev->driver_confirmed) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->start_km) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->end_km) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->distance) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($rev->start_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($rev->end_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->duration) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->total_passenger) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->status) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($reason) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->emp1) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->emp2) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime($rev->created_at)) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getVehicleRevGroupGrid()
    {
        $params = getParam();
        $revisions = $this->GaModel->getVehicleRevGroupGrid($params)->result();
        $data = [];
        foreach ($revisions as $rev) {
            $data[$rev->vehicle_id] = [
                'vehicle_name' => $rev->vehicle_name,
                'total_rev' => $rev->total_rev,
                'total_hour' => $rev->total_hour,
                'total_km' => $rev->total_km,
            ];
        }

        $vehicles = $this->General->getAll('vehicles')->result();
        $xml = "";
        $no = 1;
        foreach ($vehicles as $vehicle) {
            if(array_key_exists($vehicle->id, $data)) {
                $xml .= "<row id='$vehicle->id'>";
                $xml .= "<cell>" . cleanSC($no) . "</cell>";
                $xml .= "<cell>" . cleanSC($vehicle->name) . "</cell>";
                $xml .= "<cell>" . cleanSC($data[$vehicle->id]['total_rev']) . "</cell>";
                $xml .= "<cell>" . cleanSC($data[$vehicle->id]['total_hour']) . "</cell>";
                $xml .= "<cell>" . cleanSC($data[$vehicle->id]['total_km']) . "</cell>";
                $xml .= "</row>";
            } else {
                $xml .= "<row id='$vehicle->id'>";
                $xml .= "<cell>" . cleanSC($no) . "</cell>";
                $xml .= "<cell>" . cleanSC($vehicle->name) . "</cell>";
                $xml .= "<cell>" . 0 . "</cell>";
                $xml .= "<cell>" . 0 . "</cell>";
                $xml .= "<cell>" . 0 . "</cell>";
                $xml .= "</row>";
            }
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function updateVehicleRevBatch()
    {
        $post = getGridPost();
        $data = [];
        $mError = '';
        $mSuccess = '';
        foreach ($post as $key => $value) {
            if ($value['c14'] != 'REJECTED') {
                if($value['c8'] > $value['c7']) {
                    $data[] = [
                        'id' => $key,
                        'start_km' => $value['c7'],
                        'end_km' => $value['c8'],
                        'distance' => $value['c8'] - $value['c7'],
                        'total_passenger' => $value['c13'],
                    ];
                    $mSuccess .= "$key Total penumpang & kilomter berhasil diubah \n";
                } else {
                    $data[] = [
                        'id' => $key,
                        'total_passenger' => $value['c10'],
                    ];
                    $mSuccess .= "$key Total penumpang berhasil diubah, inputan kilometer tidak valid (Waktu Akhir < Waktu Awal) \n";
                }
            } else {
                $mError .= "$key tidak bisa diubah! \n";
            }
        }

        count($data) > 0 ? $this->General->updateMultiple('vehicles_reservation', $data, 'id') : false;
        xmlResponse('updated', $mSuccess . ',' . $mError);
    }

    public function appvVehicleRev()
    {
        $post = fileGetContent();
        $revId = $post->id;
        $data = [
            'status' => 'APPROVED',
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $rev = $this->General->getDataById('vehicles_reservation', $revId);
        if ($rev->status == 'CREATED') {
            $this->General->updateById('vehicles_reservation', $data, $revId);
            if (empRank() == 5 || empRank() == 6) {
                $this->vehiclelib->approvalNotif('ASMAN', $revId);
            } else if (empRank() == 2 || empRank() == 3) {
                $this->vehiclelib->approvalNotif('Supervisor', $revId);
            }
            response(['status' => 'success', 'message' => 'Berhasil approve reservasi kendaraan']);
        } else {
            response(['status' => 'error', 'message' => 'Sudah di approve sebelumnya!']);
        }
    }

    public function rejectVehicleRev()
    {
        $post = prettyText(getPost(), ['reason']);
        $id = $post['id'];
        $data = [
            'status' => 'REJECTED',
            'reason' => $post['reason'],
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $rev = $this->General->getDataById('vehicles_reservation', $id);
        if ($rev->status == 'CREATED') {
            $this->General->updateById('vehicles_reservation', $data, $id);
            if (empRank() == 5 || empRank() == 6) {
                $this->vehiclelib->rejectionNotif('ASMAN', $id, empName());
            } else if (empRank() == 2 || empRank() == 3) {
                $this->vehiclelib->rejectionNotif('Supervisor', $id, empName());
            }
            xmlResponse('updated', 'Berhasil menolak reservasi kendaraan');
        } else {
            xmlResponse('error', 'Sudah di reject sebelumnya!');
        }
    }

    public function closeVehicleRev()
    {
        $post = fileGetContent();
        $id = $post->id;
        $data = [
            'status' => 'CLOSED',
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $status = $this->General->getDataById('vehicles_reservation', $id)->status;
        if ($status !== 'CLOSED') {
            $this->General->updateById('vehicles_reservation', $data, $id);
            response(['status' => 'success', 'message' => 'Berhasil menutup perjalanan']);
        } else {
            response(['status' => 'error', 'message' => 'Meeting sudah di tutup sebelumnya!']);
        }
    }

    public function changeVehicleRevTime()
    {
        $post = getPost();
        $meetId = $post['id'];
        $start = $post['start_date'];
        $end = $post['end_date'];
        $meeting = $this->GaModel->getVehicleRevGrid(['equal_id' => $meetId])->row();
        $date = explode(' ', $meeting->start_date)[0];
        $startDate = date('Y-m-d H:i:s', strtotime($date . ' ' . $start . ':00'));
        $endDate = date('Y-m-d H:i:s', strtotime($date . ' ' . $end . ':00'));

        if ($endDate <= $startDate) {
            xmlResponse('error', "Waktu selesai harus lebih besar dari waktu mulai!");
        }

        if (countHour($startDate, $endDate, 'h') > 12) {
            xmlResponse('error', "Waktu selesai reservasi ruang meeting maksimal adalah 12 jam!");
        }

        $bookedVehicles = $this->General->getWhere('vehicles_reservation',
            ['vehicle_id' => $meeting->vehicle_id, 'DATE(start_date)' => $date],
            '*', null, null, null, ['status' => ['CLOSED', 'REJECTED']]
        )->result();

        $dateExist = 0;
        $dt1 = "";
        $dt2 = "";
        foreach ($bookedVehicles as $booked) {
            if (checkDateExist($startDate, $booked->start_date, $booked->end_date)) {
                if ($meetId != $booked->id) {
                    $dateExist++;
                    $dt1 = $startDate;
                }
            }

            if (checkDateExist($endDate, $booked->start_date, $booked->end_date)) {
                if ($meetId != $booked->id) {
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

        $data = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'duration' => countHour($startDate, $endDate, 'h'),
        ];

        $this->General->updateById('vehicles_reservation', $data, $meetId);
        xmlResponse("updated", "Berhasil mengubah waktu reservasi kendaraan");
    }

    public function changeDriverList()
    {
        $params = getParam();
        $id = $params['id'];

        $trip = $this->General->getDataById('vehicles_reservation', $id);
        $avDriver = [];
        $drivers = $this->Hr->getWhere('employees', ['rank_id' => 10, 'email !=' => $trip->driver])->result();
        foreach ($drivers as $driver) {
            $checkAvailable = $this->GaModel->checkAvailableDriver($driver->email, $trip->start_date, $trip->end_date);
            if(!$checkAvailable) {
                $avDriver['options'][] = [
                    'value' => $driver->email,
                    'text' => $driver->employee_name
                ];
            }
        }
        echo json_encode($avDriver);
    }

    public function changeDriverRev()
    {
        $post = getPost();
        $id = $post['id'];
        $driverEmail = $post['driver'];
        $data = [
            'driver' => $driverEmail,
            'driver_confirmed' => 'BELUM MEMUTUSKAN',
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $update = $this->General->updateById('vehicles_reservation', $data, $id);

        if($update) {
            $trip = $this->Other->getTripDetail($id);
            $passengers = $this->Other->getEmployee(explode(',', $trip->passenger));
            $driver = $this->HrModel->getEmployee(['equal_email' => $trip->driver])->row()->employee_name;
            $date = toIndoDateDay(explode(' ', $trip->start_date)[0]);

            $linkApprove = LIVE_URL . "index.php?c=PublicController&m=driverConfirm&token=" . simpleEncrypt("$id:$trip->driver:approve");
            $linkReject = LIVE_URL . "index.php?c=PublicController&m=driverConfirm&token=" . simpleEncrypt("$id:$trip->driver:reject");
            $messageDriver = $this->load->view('html/vehicles/email/driver_notification', [
                'data' => $trip, 'driver' => $driver, 'passenger' => $passengers,
                'linkApprove' => $linkApprove, 'linkReject' => $linkReject,
            ], true);
            $dataDriver = [
                'alert_name' => 'TRIP_REQUEST_CONFIRMATION',
                'email_to' => $trip->driver,
                'subject' => "Perjalanan Dinas Ke $trip->destination Tanggal $date",
                'subject_name' => "Spekta Alert: Perjalanan Dinas Ke $trip->destination Tanggal $date",
                'message' => $messageDriver,
            ];
            $this->Main->create('email', $dataDriver);
            xmlResponse('updated', 'Berhasil mengubah driver, notif konfirmasi telah dikirim ke driver');
        } else {
            xmlResponse(['error', 'Gagal mengubah driver']);
        }
    }

    public function changeVehicleList()
    {
        $params = getParam();
        $id = $params['id'];

        $trip = $this->General->getDataById('vehicles_reservation', $id);
        $avVehicle = [];
        $vehicles = $this->General->getWhere('vehicles', ['id !=' => $trip->vehicle_id])->result();
        foreach ($vehicles as $vehicle) {
            $checkAvailable = $this->GaModel->checkAvailableVehicle($vehicle->id, $trip->start_date, $trip->end_date);
            if(!$checkAvailable) {
                $avVehicle['options'][] = [
                    'value' => $vehicle->id,
                    'text' => $vehicle->name
                ];
            }
        }
        echo json_encode($avVehicle);
    }

    public function changeVehicleRev()
    {
        $post = getPost();
        $id = $post['id'];
        $vehicleId = $post['vehicle'];
        $data = [
            'vehicle_id' => $vehicleId,
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $update = $this->General->updateById('vehicles_reservation', $data, $id);

        if($update) {
            $trip = $this->Other->getTripDetail($id);
            $passengers = $this->Other->getEmployee(explode(',', $trip->passenger));
            $driver = $this->HrModel->getEmployee(['equal_email' => $trip->driver])->row()->employee_name;
            $date = toIndoDateDay(explode(' ', $trip->start_date)[0]);

            $messageVehicle = $this->load->view('html/vehicles/email/change_vehicle_notification', [
                'data' => $trip, 'driver' => $driver, 'passenger' => $passengers
            ], true);
            $empEmail = $this->Hr->getDataById('employees', $trip->created_by)->email;
            $dataVehicle = [
                'alert_name' => 'VEHICLE_CHANGE_NOTIFICATION',
                'email_to' => $trip->driver.','.$empEmail,
                'subject' => "Penggantian Kendaraan Dinas Untuk Perjalanan Ke $trip->destination ($id) Tanggal $date",
                'subject_name' => "Spekta Alert: Penggantian Kendaraan Dinas Untuk Perjalanan Ke $trip->destination ($id) Tanggal $date",
                'message' => $messageVehicle,
            ];
            $this->Main->create('email', $dataVehicle);
            xmlResponse('updated', "Berhasil mengubah kendaraan untuk tip ($id)");
        } else {
            xmlResponse(['error', 'Gagal mengubah kendaraan']);
        }
    }
}
