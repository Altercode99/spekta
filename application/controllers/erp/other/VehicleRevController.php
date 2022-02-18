<?php
defined('BASEPATH') or exit('No direct script access allowed');

class VehicleRevController extends Erp_Controller
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

    public function getVehicles()
    {
        $vehicles = $this->General->getWhere('vehicles', ['location' => empLoc()])->result();
        $data = [];
        $detail = [];
        foreach ($vehicles as $vehicle) {
            $data[] = [
                'key' => $vehicle->id,
                'label' => $vehicle->name . " - ($vehicle->brand $vehicle->type)",
            ];
            $detail[$vehicle->id] = [
                'name' => $vehicle->name,
                'passenger_capacity' => $vehicle->passenger_capacity
            ];
        }

        response(['status' => 'success', 'data' => $data, 'detail' => $detail]);
    }

    public function getEvents()
    {
        $params = getParam();
        $vehicles = $this->Other->getReservasedVehicle($params['min_date'], $params['max_date']);
        $data = [];
        foreach ($vehicles as $vehicle) {
            $data[] = [
                'id' => $vehicle->id,
                'text' => $vehicle->vehicle_name,
                'destination' => $vehicle->destination,
                'trip_type' => $vehicle->trip_type,
                'description' => $vehicle->description,
                'vehicle' => $vehicle->vehicle_id,
                'start_date' => date('Y-m-d H:i', strtotime($vehicle->start_date)),
                'end_date' => date('Y-m-d H:i', strtotime($vehicle->end_date)),
                'driver' => $vehicle->driver,
                'passenger' => $vehicle->passenger,
                'color' => $vehicle->color,
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
            'Tujuan' => $data->destination,
            'Deskripsi Kegiatan' => $data->description,
            'Driver' => $data->driver,
            'Penumpang' => $data->passenger,
            'Waktu Mulai' => $data->start_date,
            'Waktu Selesai' => $data->end_date,
        ]));

        $start = date('Y-m-d', strtotime($data->start_date));
        $end = date('Y-m-d', strtotime($data->end_date));
        $startDate = date('Y-m-d H:i:s', strtotime($data->start_date));
        $endDate = date('Y-m-d H:i:s', strtotime($data->end_date));

        if (countHour($start, $end, 'd') > 0) {
            xmlResponse('error', "Waktu mulai dan selesai perjalanan harus di hari yang sama!");
        }

        if (countHour($startDate, $endDate, 'h') > 12) {
            xmlResponse('error', "Waktu selesai reservasi kendaraan maksimal adalah 12 jam!");
        }

        $bookedVehicles = $this->General->getWhere('vehicles_reservation', ['driver' => $data->driver, 'vehicle_id' => $data->vehicle, 'DATE(start_date)' => $start, 'status !=' => 'REJECTED', 'status !=' => 'CLOSED'])->result();
        $dateExist = 0;
        $dt1 = "";
        $dt2 = "";
        foreach ($bookedVehicles as $booked) {
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

        if ($action === 'inserted') {
            $event = [
                'id' => $id,
                'location' => empLoc(),
                'destination' => ucwords(strtolower($data->destination)),
                'trip_type' => $data->trip_type,
                'description' => ucwords(strtolower($data->description)),
                'vehicle_id' => $data->vehicle,
                'start_date' => date('Y-m-d H:i:s', strtotime($data->start_date)),
                'end_date' => date('Y-m-d H:i:s', strtotime($data->end_date)),
                'duration' => countHour($startDate, $endDate, 'h'),
                'driver' => $data->driver,
                'passenger' => $data->passenger,
                'total_passenger' => count(explode(',', $data->passenger)),
                'created_by' => empId(),
                'updated_by' => empId(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $this->General->create('vehicles_reservation', $event);
            $this->revNotif($id);
            xmlResponse($action, "Reservsi kendaraan berhasil dibuat", $id);
        } else if ($action === 'updated') {

            $checkOwner = $this->General->getDataById('vehicles_reservation', $id)->created_by;
            if ($checkOwner !== empId()) {
                xmlResponse('error', "Hanya bisa diupdate oleh PIC reservasi tersebut!");
            }

            $event = [
                'id' => $id,
                'location' => empLoc(),
                'destination' => ucwords(strtolower($data->destination)),
                'trip_type' => $data->trip_type,
                'description' => ucwords(strtolower($data->description)),
                'vehicle_id' => $data->vehicle,
                'start_date' => date('Y-m-d H:i:s', strtotime($data->start_date)),
                'end_date' => date('Y-m-d H:i:s', strtotime($data->end_date)),
                'duration' => countHour($startDate, $endDate, 'h'),
                'driver' => $data->driver,
                'passenger' => $data->passenger,
                'total_passenger' => count(explode(',', $data->passenger)),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $this->General->updateById('vehicles_reservation', $event, $id);
            xmlResponse($action, "Reservsi kendaraan berhasil diupdate", $id);
        } else if ($action === 'deleted') {
            $checkOwner = $this->General->getDataById('vehicles_reservation', $id);
            if ($checkOwner->created_by !== empId()) {
                xmlResponse('error', "Hanya bisa dihapus oleh PIC reservasi tersebut!");
            }
            if($checkOwner->status !== 'CREATED') {
                xmlResponse('error', "Jadwal sudah di proses!");
            }
            $this->General->deleteById('vehicles_reservation', $id);
            xmlResponse($action, "Reservasi kendaraan dibatalkan!");
        }
    }

    public function getVehiclesView()
    {
        $template = $this->load->view('html/vehicles/view_template', null, true);
        $vehicles = $this->General->getWhere('vehicles', ['location' => empLoc()])->result();

        $dataVehicles = [];
        foreach ($vehicles as $vehicle) {
            $path = $vehicle->filename ? './assets/images/vehicles/' . $vehicle->filename : './public/img/no-image.png';
            $dataVehicles[] = [
                'id' => $vehicle->id,
                'img_url' => $path,
                'name' => $vehicle->name,
                'police_no' => $vehicle->police_no,
                'brand' => $vehicle->brand,
                'type' => $vehicle->type,
                'machine_capacity' => $vehicle->machine_capacity,
                'last_km' => $vehicle->last_km,
                'passenger_capacity' => $vehicle->passenger_capacity,
            ];
        }

        response([
            'template' => $template,
            'data' => $dataVehicles,
        ]);
    }

    public function getEventDate()
    {
        $post = fileGetContent();
        $vehicleId = $post->vehicleId;
        $date = date('Y-m-d', strtotime($post->date));
        $events = $this->Other->getVehicleEventDetail($vehicleId, $date);
        $template = $this->load->view('html/vehicles/event_detail', ['events' => $events], true);
        $total = count($events);
        response(['status' => 'success', 'template' => $template, 'total' => $total]);
    }

    public function getEventDetail()
    {
        $params = getParam();
        $id = $params['eventId'];
        $event = $this->General->getDataById('vehicles_reservation', $id);
        $drivers = $this->Other->getEmployee(explode(',', $event->driver));
        $passengers = $this->Other->getEmployee(explode(',', $event->passenger));
        $xml = "";
        $xml .= "<row id='destination'>";
        $xml .= "<cell>Tujuan</cell>";
        $xml .= "<cell>". cleanSC($event->destination) ."</cell>";
        $xml .= "</row>";
        $xml .= "<row id='description'>";
        $xml .= "<cell>Deskripsi Kegiatan</cell>";
        $xml .= "<cell>". cleanSC($event->description) ."</cell>";
        $xml .= "</row>";
        $xml .= "<row id='start_date'>";
        $xml .= "<cell>Waktu Mulai</cell>";
        $xml .= "<cell>". cleanSC(toIndoDateTime($event->start_date))."</cell>";
        $xml .= "</row>";
        $xml .= "<row id='end_date'>";
        $xml .= "<cell>Waktu Selesai</cell>";
        $xml .= "<cell>". cleanSC(toIndoDateTime($event->end_date))."</cell>";
        $xml .= "</row>";
        $xml .= "<row id='duration'>";
        $xml .= "<cell>Durasi</cell>";
        $xml .= "<cell>". cleanSC("$event->duration Jam") ."</cell>";
        $xml .= "</row>";
        $no = 1;
        foreach ($drivers as $driver) {
            $xml .= "<row id='$driver->email'>";
            if ($no === 1) {
                $xml .= "<cell>Driver</cell>";
            } else {
                $xml .= "<cell></cell>";
            }
            $xml .= "<cell>". cleanSC("$driver->employee_name ($driver->sub_name)") ."</cell>";
            $xml .= "</row>";
            $no++;
        }

        $no = 1;
        foreach ($passengers as $passenger) {
            $xml .= "<row id='$passenger->email'>";
            if ($no === 1) {
                $xml .= "<cell>Penumpang</cell>";
            } else {
                $xml .= "<cell></cell>";
            }
            $xml .= "<cell>". cleanSC("$passenger->employee_name ($passenger->sub_name)") ."</cell>";
            $xml .= "</row>";
            $no++;
        }

        gridXmlHeader($xml);
    }

    public function revNotif($id)
    {
        $trip = $this->Other->getTripDetail($id);
        $driver = $this->HrModel->getEmployee(['equal_email' => $trip->driver])->row()->employee_name;
        $passengers = $this->Other->getEmployee(explode(',', $trip->passenger));
        $date = toIndoDateDay(explode(' ', $trip->start_date)[0]);

        $picEmail = '';
        $pics = $this->Main->getWhere('pics', ['code' => 'vehicles'])->result();
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

        $messagePic = $this->load->view('html/vehicles/email/pic_notification', [
            'data' => $trip, 'driver' => $driver, 'passenger' => $passengers,
        ], true);
        $dataPic = [
            'alert_name' => 'TRIP_REQUEST_NOTIFICATION',
            'email_to' => $picEmail,
            'subject' => "Perjalanan Dinas Ke $trip->destination Tanggal $date",
            'subject_name' => "Spekta Alert: Perjalanan Dinas Ke $trip->destination Tanggal $date",
            'message' => $messagePic,
        ];
        $this->Main->create('email', $dataPic);

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
    }
}
