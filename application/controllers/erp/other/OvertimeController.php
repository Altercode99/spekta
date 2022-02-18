<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OvertimeController extends Erp_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AppMasterModel', 'AppMaster');
        $this->AppMaster->myConstruct('main');
        $this->load->model('OvertimeModel', 'Overtime');
        $this->Overtime->myConstruct('hr');
        $this->load->model('HrModel');
        $this->HrModel->myConstruct('hr');
        $this->auth->isAuth();
    }

    public function getDepartment()
    {
        $depts = $this->Overtime->getDepartment(getParam());
        $deptList = [];
        foreach ($depts as $dept) {
            $deptList['options'][] = [
                'value' => $dept->id,
                'text' => $dept->name,
                'selected' => isset($params['select']) && $params['select'] == $dept->id ? 1 : 0,
            ];
        }
        echo json_encode($deptList);
    }

    public function getSubDepartment()
    {
        $subList = [];
        if (isset($params['select']) && $params['select'] == 0) {
            $subList['options'][] = [
                'value' => 0,
                'text' => '-',
                'selected' => 1,
            ];
        } else {
            $subs = $this->Overtime->getSubDepartment(getParam());
            if ($subs) {
                $subList['options'][] = [
                    'value' => 0,
                    'text' => '-',
                ];
                foreach ($subs as $sub) {
                    $subList['options'][] = [
                        'value' => $sub->id,
                        'text' => $sub->name,
                        'selected' => isset($params['select']) && $params['select'] == $sub->id ? 1 : 0,
                    ];
                }
            } else {
                $subList['options'][] = [
                    'value' => 0,
                    'text' => '-',
                    'selected' => 1,
                ];
            }
        }
        echo json_encode($subList);
    }

    public function getOTRequirement()
    {
        $params = getParam();
        $reqs = $this->Hr->getAll("overtime_requirement")->result();
        $data = [];
        $data[] = [
            'type' => 'settings',
            'position' => 'label-right',
        ];

        if (isset($params['mtn'])) {
            foreach ($reqs as $req) {
                if ($req->id == 2) {
                    $data[] = [
                        'type' => 'checkbox',
                        'name' => $req->table_code,
                        'value' => $req->id,
                        'label' => $req->name,
                    ];
                }
            }
        } else {
            foreach ($reqs as $req) {
                if ($req->id != 1 && $req->id != 9 && $req->id != 10) {
                    $data[] = [
                        'type' => 'checkbox',
                        'name' => $req->table_code,
                        'value' => $req->id,
                        'label' => $req->name,
                    ];
                }
            }
        }
        response(['status' => 'success', 'data' => $data]);
    }

    public function getMachineGrid()
    {
        $machines = $this->AppMaster->getMachineGrid(getParam());
        $xml = "";
        $no = 1;
        foreach ($machines as $machine) {
            $xml .= "<row id='$machine->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>0</cell>";
            $xml .= "<cell>". cleanSC($machine->name) ."</cell>";
            $xml .= "<cell>". cleanSC($machine->department) ."</cell>";
            $xml .= "<cell>". cleanSC($machine->sub_department) ."</cell>";
            $xml .= "<cell>". cleanSC($machine->division) ."</cell>";
            $xml .= "<cell>". cleanSC("Gedung $machine->building Ruang $machine->room") ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function createInitialOvertime()
    {
        $params = getParam();
        $post = prettyText(getPost(), ['notes']);
        $date = $post['overtime_date'];
        $expDate = explode('-', $date);
        $lastId = $this->Overtime->lastOt('employee_overtimes', 'overtime_date', $date);
        $taskId = sprintf('%03d', $lastId + 1) . '/OT/' . empLoc() . '/' . toRomawi($expDate[1]) . '/' . $expDate[0];
        $start = genOvtDate($date, $post['start_date']);
        $end = genOvtDate($date, $post['end_date']);

        if (countHour($start, $end, 'h') > 24) {
            xmlResponse('invalid', 'Maksimum jam lembur adalah 24 jam!');
        }

        $checkOvertimes = $this->Hr->getWhere('employee_overtimes', [
            'overtime_date' => $post['overtime_date'],
            'sub_department_id' => $post['sub_department_id'],
        ], '*', null, null, null, ['CANCELED', 'REJECTED'])->result();

        $dateExist = 0;
        $dt1 = "";
        $dt2 = "";
        foreach ($checkOvertimes as $overtime) {
            if (checkDateExist($start, $overtime->start_date, $overtime->end_date)) {
                $dateExist++;
                $dt1 = $start;
            }

            if (checkDateExist($end, $overtime->start_date, $overtime->end_date)) {
                $dateExist++;
                $dt2 = $end;
            }
        }

        if ($dateExist > 0) {
            $message = "";
            if ($dt1 != '' && $dt2 != '') {
                $message = "Tanggal " . toIndoDateTime($dt1) . " dan " . toIndoDateTime($dt2) . " sudah dibuat!";
            } else if ($dt1 != '' && $dt2 == '') {
                $message = "Tanggal " . toIndoDateTime($dt1) . " sudah dibuat!";
            } else if ($dt1 == '' && $dt2 != '') {
                $message = "Tanggal " . toIndoDateTime($dt2) . " sudah dibuat!";
            }
            xmlResponse('error', $message);
        }

        $dateStart = new DateTime($start);
        $dateEnd = new DateTime($end);
        if ($dateEnd <= $dateStart) {
            $end = addDayToDate($end, 1);
        }

        $statusDay = checkStatusDay($post['overtime_date']);

        $data = [
            'location' => empLoc(),
            'task_id' => $taskId,
            'ref' => isset($params['ref']) ? $params['ref'] : '',
            'department_id' => $post['department_id'],
            'sub_department_id' => $post['sub_department_id'],
            'division_id' => $post['division_id'],
            'personil' => $post['personil'],
            'machine_ids' => isset($post['machine_id']) ? $post['machine_id'] : '',
            'overtime_date' => $post['overtime_date'],
            'start_date' => $start,
            'end_date' => $end,
            'status_day' => $statusDay,
            'notes' => $post['notes'],
            'makan' => 0,
            'steam' => isset($post['steam']) ? $post['steam'] : 0,
            'ahu' => isset($post['ahu']) ? $post['ahu'] : 0,
            'compressor' => isset($post['compressor']) ? $post['compressor'] : 0,
            'pw' => isset($post['pw']) ? $post['pw'] : 0,
            'jemputan' => $post['jemputan'],
            'dust_collector' => isset($post['dust_collector']) ? $post['dust_collector'] : 0,
            'wfi' => isset($post['wfi']) ? $post['wfi'] : 0,
            'mechanic' => isset($post['machine_id']) ? 9 : 0,
            'electric' => isset($post['machine_id']) && $post['sub_department_id'] != 5 ? 10 : 0,
            'hnn' => isset($post['hnn']) ? $post['hnn'] : 0,
            'status' => 'CREATED',
            'apv_spv' => 'CREATED',
            'apv_asman' => 'CREATED',
            'apv_mgr' => 'CREATED',
            'apv_head' => 'CREATED',
            'created_by' => empId(),
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $overtime = $this->Hr->create('employee_overtimes', $data);
        if ($overtime) {
            xmlResponse('inserted', 'Lemburan ' . toIndoDateDay($date));
        } else {
            xmlResponse('error', 'Lemburan ' . toIndoDateDay($date));
        }
    }

    public function getOvertimeGrid()
    {
        $overtimes = $this->Overtime->getOvertime(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($overtimes as $overtime) {
            $makan = $overtime->makan > 0 ? '✓' : '-';
            $steam = $overtime->steam > 0 ? '✓' : '-';
            $ahu = $overtime->ahu > 0 ? '✓' : '-';
            $compressor = $overtime->compressor > 0 ? '✓' : '-';
            $pw = $overtime->pw > 0 ? '✓' : '-';
            $jemputan = $overtime->jemputan > 0 ? '✓' : '-';
            $dust_collector = $overtime->dust_collector > 0 ? '✓' : '-';
            $mechanic = $overtime->mechanic > 0 ? '✓' : '-';
            $electric = $overtime->electric > 0 ? '✓' : '-';
            $hnn = $overtime->hnn > 0 ? '✓' : '-';

            $color = null;
            if ($overtime->status_day === 'Hari Libur') {
                $color = "bgColor='#efd898'";
            } else if ($overtime->status_day === 'Libur Nasional') {
                $color = "bgColor='#7ecbf1'";
            }

            if ($overtime->status === 'REJECTED') {
                $color = "bgColor='#ed9a9a'";
            }

            $revisionHour = $overtime->change_time != '' ? $overtime->change_time : '-';
            $revisionNote = $overtime->revision_note != '' ? $overtime->revision_note : '-';
            $rejectionNote = $overtime->rejection_note != '' ? $overtime->rejection_note : '-';

            $xml .= "<row id='$overtime->id'>";
            $xml .= "<cell $color>". cleanSC($no) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->task_id) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->department) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->sub_department) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->division) ."</cell>";
            $xml .= "<cell $color>". cleanSC("$overtime->personil Orang") ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->status_day) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateDay($overtime->overtime_date)) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateTime2($overtime->start_date)) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateTime2($overtime->end_date)) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->notes) ."</cell>";
            $xml .= "<cell $color>". cleanSC($makan) ."</cell>";
            $xml .= "<cell $color>". cleanSC($steam) ."</cell>";
            $xml .= "<cell $color>". cleanSC($ahu) ."</cell>";
            $xml .= "<cell $color>". cleanSC($compressor) ."</cell>";
            $xml .= "<cell $color>". cleanSC($pw) ."</cell>";
            $xml .= "<cell $color>". cleanSC($jemputan) ."</cell>";
            $xml .= "<cell $color>". cleanSC($dust_collector) ."</cell>";
            $xml .= "<cell $color>". cleanSC($mechanic) ."</cell>";
            $xml .= "<cell $color>". cleanSC($electric) ."</cell>";
            $xml .= "<cell $color>". cleanSC($hnn) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->status) ."</cell>";
            $xml .= "<cell $color>". cleanSC($revisionHour) ."</cell>";
            $xml .= "<cell $color>". cleanSC($revisionNote) ."</cell>";
            $xml .= "<cell $color>". cleanSC($rejectionNote) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->emp1) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->emp2) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateTime($overtime->created_at))."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getOvertimeDetailGrid()
    {
        $overtimes = $this->Overtime->getOvertimeDetail(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($overtimes as $overtime) {

            $color = null;
            if ($overtime->status_day === 'Hari Libur') {
                $color = "bgColor='#efd898'";
            } else if ($overtime->status_day === 'Libur Nasional') {
                $color = "bgColor='#7ecbf1'";
            }

            $status_updater = '-';
            if ($overtime->status === 'REJECTED') {
                $color = "bgColor='#ed9a9a'";
                $status_updater = $overtime->status . ' By ' . $overtime->status_updater;
            } else if ($overtime->change_time == 1) {
                $status_updater = 'Revisi Jam Lembur By ' . $overtime->status_updater;
            } else if ($overtime->status_by !== '') {
                $status_updater = $overtime->status . ' By ' . $overtime->status_updater;
            }

            $machine1 = $overtime->machine_1 ? $overtime->machine_1 : '-';
            $machine2 = $overtime->machine_2 ? $overtime->machine_2 : '-';
            $meal = $overtime->meal > 0 ? "✓ ($overtime->total_meal x)" : '-';
            $xml .= "<row id='$overtime->id'>";
            $xml .= "<cell $color>". cleanSC($no) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->emp_task_id) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->employee_name) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->department) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->sub_department) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->division) ."</cell>";
            $xml .= "<cell $color>". cleanSC($machine1) ."</cell>";
            $xml .= "<cell $color>". cleanSC($machine2) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->requirements) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateDay($overtime->overtime_date))."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateTime2($overtime->start_date))."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateTime2($overtime->end_date))."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->status_day) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->effective_hour) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->break_hour) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->real_hour) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->overtime_hour) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toNumber($overtime->premi_overtime))."</cell>";
            $xml .= "<cell $color>". cleanSC(toNumber($overtime->overtime_value))."</cell>";
            $xml .= "<cell $color>". cleanSC($meal) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->notes) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->status) ."</cell>";
            $xml .= "<cell $color>". cleanSC($status_updater) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->emp1) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->emp2) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateTime($overtime->created_at))."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->status_by) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->emp_id) ."</cell>";

            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getDetailOvertime()
    {
        $post = fileGetContent();
        $params = getParam();
        $params['equal_id'] = $post->id;
        $overtime = $this->Overtime->getOvertime($params)->row();
        $template = $this->load->view('html/overtime/overtime_detail', ['overtime' => $overtime], true);
        $start = date('H:i', strtotime($overtime->start_date));
        $end = date('H:i', strtotime($overtime->end_date));
        response([
            'status' => 'success',
            'template' => $template,
            'overtime' => $overtime,
            'start' => $start,
            'end' => $end,
        ]);
    }

    public function getOvertimeDetailView()
    {
        $post = fileGetContent();
        $overtime = $this->Hr->getDataById('employee_overtimes', $post->id);
        $totalPersonil = $this->Hr->countWhere('employee_overtimes_detail', ['task_id' => $overtime->ref]);
        $params['equal_task_id'] = $overtime->ref;
        $prodOvertime = $this->Overtime->getOvertime($params)->row();
        $machinesIds = explode(",", $prodOvertime->machine_ids);
        $machines = $this->Mtn->getWhereIn('production_machines', ['id' => $machinesIds])->result();
        $template = $this->load->view('html/overtime/production_overtime_detail', [
            'overtime' => $prodOvertime,
            'totalPersonil' => $totalPersonil,
            'machines' => $machines,
        ], true);
        response([
            'status' => 'success',
            'template' => $template,
            'ref' => $overtime->ref,
        ]);
    }

    public function getOvertimeDetailViewRev()
    {
        $post = fileGetContent();
        $params['equal_task_id'] = $post->taskId;
        $overtime = $this->Overtime->getOvertime($params)->row();
        $machinesIds = explode(",", $overtime->machine_ids);
        $machines = $this->Mtn->getWhereIn('production_machines', ['id' => $machinesIds])->result();
        $template = $this->load->view('html/overtime/overtime_detail_revision', [
            'overtime' => $overtime,
            'machines' => $machines,
        ], true);
        response([
            'status' => 'success',
            'template' => $template
        ]);
    }

    public function getOvertimeMachine()
    {
        $machines = $this->Overtime->getOvertimeMachine(getParam());
        $xml = "";
        $no = 1;
        foreach ($machines as $machine) {
            $xml .= "<row id='$machine->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($machine->name) ."</cell>";
            $xml .= "<cell>". cleanSC("Gedung $machine->building Ruang $machine->room") ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getEmployees()
    {
        $get = getParam();
        $emps = $this->HrModel->getEmployee($get)->result();
        $xml = "";
        $no = 1;
        foreach ($emps as $emp) {
            $xml .= "<row id='$emp->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>0</cell>";
            $xml .= "<cell>". cleanSC($emp->employee_name) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->dept_name) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->sub_name) ."</cell>";
            $xml .= "<cell>". cleanSC($emp->division_name) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function createPersonilOvertime()
    {
        $post = prettyText(getPost(), ['notes']);
        $overtime = $this->Hr->getDataById('employee_overtimes', $post['overtime_id']);

        $expDate = explode('-', $overtime->overtime_date);
        $lastId = $this->Overtime->lastOt('employee_overtimes_detail', 'overtime_date', $overtime->overtime_date);
        $start = genOvtDate($overtime->overtime_date, $post['start_date']);
        $end = genOvtDate($overtime->overtime_date, $post['end_date']);

        if (countHour($start, $end, 'h') > 24) {
            xmlResponse('invalid', 'Maksimum jam lembur adalah 24 jam!');
        }

        $dateStart = new DateTime($start);
        $dateEnd = new DateTime($end);
        if ($dateEnd <= $dateStart) {
            $end = addDayToDate($end, 1);
            $newEnd = date('Y-m-d', strtotime($end));
            $newOvtEnd = date('Y-m-d', strtotime($overtime->end_date));
            if ($newEnd != $newOvtEnd) {
                xmlResponse('invalid', 'Jam lembur tidak valid!');
            }
        } else {
            if ($dateStart >= $dateEnd) {
                $newOvtEnd = date('Y-m-d', strtotime($overtime->end_date));
                $timeStart = date('H:i:s', strtotime($dateStart));
                $start = date('Y-m-d H:i:s', strtotime("$newOvtEnd $timeStart"));
            }
        }

        $personils = explode(',', $post['personil_id']);

        if (count($personils) > $overtime->personil) {
            xmlResponse('invalid', 'Jumlah personil melebihi kebutuhan!');
        } else {
            $existPersonil = $this->Hr->countWhere('employee_overtimes_detail', ['task_id' => $overtime->task_id, 'status' => 'CREATED']);
            if (($existPersonil + count($personils)) > $overtime->personil) {
                xmlResponse('invalid', 'Jumlah personil melebihi kebutuhan!');
            }
        }

        $machine = isset($post['machine_id']) ? explode(',', $post['machine_id']) : [];
        $machine_1 = isset($machine[0]) ? $machine[0] : 0;
        $machine_2 = isset($machine[1]) ? $machine[1] : 0;
        $requirements = isset($post['requirements']) && $post['requirements'] !== ''? $post['requirements'] : '-';

        $catheringPrice = $this->General->getOne('catherings', ['status' => 'ACTIVE']);
        $catPrice = $catheringPrice ? $catheringPrice->price : 0;
        $data = [];
        $no = 1;
        foreach ($personils as $key => $id) {
            $emp = $this->Hr->getDataById('employees', $id);
            $taskId = sprintf('%03d', ($lastId + $no)) . '/OT-EMP/' . empLoc() . '/' . toRomawi($expDate[1]) . '/' . $expDate[0];
            $overtimeHour = totalHour($id, $overtime->overtime_date, $start, $end, $post['start_date'], $post['end_date']);

            $data[] = [
                'location' => empLoc(),
                'task_id' => $overtime->task_id,
                'emp_task_id' => $taskId,
                'emp_id' => $emp->id,
                'department_id' => $emp->department_id,
                'sub_department_id' => $emp->sub_department_id,
                'division_id' => $emp->division_id,
                'ovt_sub_department' => $overtime->sub_department_id,
                'ovt_division' => $overtime->division_id,
                'machine_1' => $machine_1,
                'machine_2' => $machine_2,
                'requirements' => $requirements,
                'overtime_date' => $overtime->overtime_date,
                'start_date' => $start,
                'end_date' => $end,
                'status_day' => $overtimeHour['status_day'],
                'effective_hour' => $overtimeHour['effective_hour'],
                'break_hour' => $overtimeHour['break_hour'],
                'real_hour' => $overtimeHour['real_hour'],
                'overtime_hour' => $overtimeHour['overtime_hour'],
                'premi_overtime' => $overtimeHour['premi_overtime'],
                'overtime_value' => $overtimeHour['overtime_value'],
                'meal' => $overtimeHour['total_meal'] * $catPrice,
                'total_meal' => $overtimeHour['total_meal'],
                'notes' => $post['notes'],
                'status' => 'CREATED',
                'created_by' => empId(),
                'updated_by' => empId(),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $no++;
        }

        $insert = $this->Hr->createMultiple('employee_overtimes_detail', $data);
        if ($insert) {
            xmlResponse('inserted', 'Lemburan ' . $post['personil_name']);
        } else {
            xmlResponse('error', 'Lemburan ' . $post['personil_name']);
        }
    }

    public function personilOvertimeDelete()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $overtime = $this->Hr->getDataById('employee_overtimes_detail', $data->id);
            if ($overtime->status === 'CREATED' || $overtime->status === 'PROCESS' || $overtime->status === 'REJECTED') {
                $mSuccess .= "- $data->field berhasil dibatalkan <br>";
                $this->Hr->updateById('employee_overtimes_detail', ['status' => 'CANCELED'], $data->id);
            } else {
                $mError .= "- $data->field sudah diapproved! <br>";
            }
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    public function processOvertime()
    {
        $post = fileGetContent();
        $overtime = $this->Overtime->getOvertime(['equal_task_id' => $post->taskId])->row();
        $makan = $this->Hr->countWhere('employee_overtimes_detail', ['task_id' => $post->taskId]);
        $sendEmail = false;

        $data = [
            'status' => 'PROCESS',
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
            'makan' => $makan > 0 ? 1 : 0,
        ];

        $isHaveSpv = $this->Hr->getOne('employees', ['division_id' => $overtime->division_id], '*', ['rank_id' => ['5', '6']]);
        $isHaveSpvPLT = $this->Hr->getOne('employee_ranks', ['division_id' => $overtime->division_id, 'status' => 'ACTIVE'], '*', ['rank_id' => ['5', '6']]);
        if ($overtime->division_id == 0 || (!$isHaveSpv && !$isHaveSpvPLT)) {
            $data['apv_spv'] = 'BY PASS';
            $data['apv_spv_nip'] = '-';
            $data['apv_spv_date'] = date('Y-m-d H:i:s');
        } else {
            if($overtime->apv_spv_nip == '' && !$sendEmail) {
                if($isHaveSpv) {
                    $this->ovtlib->sendEmailAppv($isHaveSpv->email, 'Supervisor', 'spv', $overtime, $post->taskId);
                } else if($isHaveSpvPLT) {
                    $email = $this->Hr->getDataById('employees', $isHaveSpvPLT->emp_id)->email;
                    $this->ovtlib->sendEmailAppv($email, 'Supervisor', 'spv', $overtime, $post->taskId);
                }
                $sendEmail = true;
            }
        }

        $isHaveAsman = $this->Hr->getOne('employees', ['sub_department_id' => $overtime->sub_department_id], '*', ['rank_id' => ['3', '4']]);
        $isHaveAsmanPLT = $this->Hr->getOne('employee_ranks', ['sub_department_id' => $overtime->sub_department_id, 'status' => 'ACTIVE'], '*', ['rank_id' => ['3', '4']]);
        if ($overtime->sub_department_id == 0 || (!$isHaveAsman && !$isHaveAsmanPLT)) {
            $data['apv_asman'] = 'BY PASS';
            $data['apv_asman_nip'] = '-';
            $data['apv_asman_date'] = date('Y-m-d H:i:s');
        } else {
            if($overtime->apv_asman_nip == '' && !$sendEmail) {
                if($isHaveAsman) {
                    $this->ovtlib->sendEmailAppv($isHaveAsman->email, 'ASMAN', 'asman', $overtime, $post->taskId);
                } else if($isHaveAsmanPLT){
                    $email = $this->Hr->getDataById('employees', $isHaveAsmanPLT->emp_id)->email;
                    $this->ovtlib->sendEmailAppv($email, 'ASMAN', 'asman', $overtime, $post->taskId);
                }
                $sendEmail = true;
            }
        }

        $isHaveMgr = $this->Hr->getOne('employees', ['department_id' => $overtime->department_id, 'rank_id' => 2]);
        $isHaveMgrPLT = $this->Hr->getOne('employee_ranks', ['department_id' => $overtime->department_id, 'rank_id' => 2, 'status' => 'ACTIVE']);
        if ($overtime->division_id == 0 && $overtime->sub_department_id == 0) {
            if ($overtime->department_id == 3) {
                $data['apv_mgr'] = 'BY PASS';
                $data['apv_mgr_nip'] = '-';
                $data['apv_mgr_date'] = date('Y-m-d H:i:s');
            }
        } else if (!$isHaveMgr && !$isHaveMgrPLT) {
            $data['apv_mgr'] = 'BY PASS';
            $data['apv_mgr_nip'] = '-';
            $data['apv_mgr_date'] = date('Y-m-d H:i:s');
        } else {
            if($overtime->apv_mgr_nip == '' && !$sendEmail) {
                if($isHaveAsman) {
                    $this->ovtlib->sendEmailAppv($isHaveMgr->email, 'Manager', 'mgr', $overtime, $post->taskId);
                } else if($isHaveMgrPLT){
                    $email = $this->Hr->getDataById('employees', $isHaveMgrPLT->emp_id)->email;
                    $this->ovtlib->sendEmailAppv($email, 'Manager', 'mgr', $overtime, $post->taskId);
                }
                $sendEmail = true;
            }
        }

        $this->Hr->update('employee_overtimes', $data, ['task_id' => $post->taskId]);

        $empData = ['status' => 'PROCESS', 'updated_by' => empId(), 'updated_at' => date('Y-m-d H:i:s')];
        $this->Hr->update('employee_overtimes_detail', $empData, ['task_id' => $post->taskId], null, ['status' => ['CANCELED', 'REJECTED']]);
        response(['status' => 'success', 'message' => 'Lemburan berhasil di proses, silahkan tunggu approval atasan terkait']);
    }

    public function cancelOvertime()
    {
        $post = fileGetContent();
        $tnpTaskId = $this->Hr->getOne('employee_overtimes', ['ref' => $post->taskId])->task_id;
        $this->Hr->update('employee_overtimes', ['status' => 'CANCELED', 'updated_by' => empId(), 'updated_at' => date('Y-m-d H:i:s')], ['task_id' => $post->taskId]);
        $this->Hr->update('employee_overtimes_detail', ['status' => 'CANCELED', 'updated_by' => empId(), 'updated_at' => date('Y-m-d H:i:s')], ['task_id' => $post->taskId]);

        $this->Hr->update('employee_overtimes', ['status' => 'CANCELED', 'updated_by' => empId(), 'updated_at' => date('Y-m-d H:i:s')], ['task_id' => $tnpTaskId]);
        $this->Hr->update('employee_overtimes_detail', ['status' => 'CANCELED', 'updated_by' => empId(), 'updated_at' => date('Y-m-d H:i:s')], ['task_id' => $tnpTaskId]);
        response(['status' => 'success', 'message' => 'Lemburan berhasil di batalkan']);
    }

    public function cancelOvertimeMtn()
    {
        $post = fileGetContent();
        $this->Hr->update('employee_overtimes', ['status' => 'CANCELED', 'updated_by' => empId(), 'updated_at' => date('Y-m-d H:i:s')], ['task_id' => $post->taskId]);
        $this->Hr->update('employee_overtimes_detail', ['status' => 'CANCELED', 'updated_by' => empId(), 'updated_at' => date('Y-m-d H:i:s')], ['task_id' => $post->taskId]);
        response(['status' => 'success', 'message' => 'Lemburan berhasil di batalkan']);
    }

    public function getAppvOvertimeGrid()
    {
        $params = getParam();
        $overtimes = $this->Overtime->getAppvOvertime($params)->result();
        $xml = "";
        $no = 1;
        foreach ($overtimes as $overtime) {
            $makan = $overtime->makan > 0 ? '✓' : '-';
            $steam = $overtime->steam > 0 ? '✓' : '-';
            $ahu = $overtime->ahu > 0 ? '✓' : '-';
            $compressor = $overtime->compressor > 0 ? '✓' : '-';
            $pw = $overtime->pw > 0 ? '✓' : '-';
            $jemputan = $overtime->jemputan > 0 ? '✓' : '-';
            $dust_collector = $overtime->dust_collector > 0 ? '✓' : '-';
            $mechanic = $overtime->mechanic > 0 ? '✓' : '-';
            $electric = $overtime->electric > 0 ? '✓' : '-';
            $hnn = $overtime->hnn > 0 ? '✓' : '-';

            $color = null;
            if ($overtime->status_day === 'Hari Libur') {
                $color = "bgColor='#efd898'";
            } else if ($overtime->status_day === 'Libur Nasional') {
                $color = "bgColor='#7ecbf1'";
            }

            if ($overtime->status === 'REJECTED') {
                $color = "bgColor='#ed9a9a'";
            }

            $appvStatus = "bgColor='#ccc'";

            $headAppv = '-';
            if ($overtime->apv_head_nip && $overtime->apv_head_nip !== '-') {
                $headAppv = "$overtime->apv_head By $overtime->head @" . toIndoDateTime3($overtime->apv_head_date);
            }

            $mgrAppv = '-';
            if ($overtime->apv_mgr_nip && $overtime->apv_mgr_nip !== '-') {
                $mgrAppv = "$overtime->apv_mgr By $overtime->mgr @" . toIndoDateTime3($overtime->apv_mgr_date);
            } else if ($overtime->apv_mgr_nip && $overtime->apv_mgr_nip === '-') {
                $mgrAppv = "$overtime->apv_mgr By Sistem @" . toIndoDateTime3($overtime->apv_mgr_date);
                
            }

            $asmanAppv = '-';
            if ($overtime->apv_asman_nip && $overtime->apv_asman_nip !== '-') {
                $asmanAppv = "$overtime->apv_asman By $overtime->asman @" . toIndoDateTime3($overtime->apv_asman_date);
            } else if ($overtime->apv_asman_nip && $overtime->apv_asman_nip === '-') {
                $asmanAppv = "$overtime->apv_asman By Sistem @" . toIndoDateTime3($overtime->apv_asman_date);
            }

            $spvAppv = '-';
            if ($overtime->apv_spv_nip && $overtime->apv_spv_nip !== '-') {
                $spvAppv = "$overtime->apv_spv By $overtime->spv @" . toIndoDateTime3($overtime->apv_spv_date);
            } else if ($overtime->apv_spv_nip && $overtime->apv_spv_nip === '-') {
                $spvAppv = "$overtime->apv_spv By Sistem @" . toIndoDateTime3($overtime->apv_spv_date);
            }

            $isSpvBySys = $overtime->apv_mgr_nip && $overtime->apv_mgr_nip === '-';
            $isAsmanBySys = $overtime->apv_asman_nip && $overtime->apv_asman_nip === '-';
            $isMgrBySys = $overtime->apv_mgr_nip && $overtime->apv_mgr_nip === '-';

            if($overtime->apv_mgr_nip) {
                if(!$isMgrBySys) {
                    $appvStatus = "bgColor='#d968b1'"; //mgr
                } else {
                    if($overtime->apv_asman_nip) {
                        $appvStatus = "bgColor='#d968b1'"; //mgr
                    } else if($overtime->apv_spv_nip){
                        $appvStatus = "bgColor='#cedb10'"; //spv
                    }
                }
            } else if($overtime->apv_asman_nip) {
                if(!$isAsmanBySys) {
                    $appvStatus = "bgColor='#db8a10'"; //asman
                } else {
                    if($overtime->apv_spv_nip) {
                        $appvStatus = "bgColor='#db8a10'"; //asman
                    }
                }
            } else if($overtime->apv_spv_nip){
                $appvStatus = "bgColor='#cedb10'"; //spv
            }
            
            $changeTime = $overtime->change_time !== '' ? $overtime->change_time : '-';
            $rejectionNote = $overtime->rejection_note !== '' ? $overtime->rejection_note : '-';

            $xml .= "<row id='$overtime->id'>";
            $xml .= "<cell $appvStatus>". cleanSC($no) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->task_id) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->department) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->sub_department) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->division) ."</cell>";
            $xml .= "<cell $color>". cleanSC("$overtime->personil Orang") ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->status_day) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateDay($overtime->overtime_date))."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateTime2($overtime->start_date))."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateTime2($overtime->end_date))."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->notes) ."</cell>";
            $xml .= "<cell $color>". cleanSC($makan) ."</cell>";
            $xml .= "<cell $color>". cleanSC($steam) ."</cell>";
            $xml .= "<cell $color>". cleanSC($ahu) ."</cell>";
            $xml .= "<cell $color>". cleanSC($compressor) ."</cell>";
            $xml .= "<cell $color>". cleanSC($pw) ."</cell>";
            $xml .= "<cell $color>". cleanSC($jemputan) ."</cell>";
            $xml .= "<cell $color>". cleanSC($dust_collector) ."</cell>";
            $xml .= "<cell $color>". cleanSC($mechanic) ."</cell>";
            $xml .= "<cell $color>". cleanSC($electric) ."</cell>";
            $xml .= "<cell $color>". cleanSC($hnn) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->status) ."</cell>";
            $xml .= "<cell $color>". cleanSC($spvAppv) ."</cell>";
            $xml .= "<cell $color>". cleanSC($asmanAppv) ."</cell>";
            $xml .= "<cell $color>". cleanSC($mgrAppv) ."</cell>";
            $xml .= "<cell $color>". cleanSC($headAppv) ."</cell>";
            $xml .= "<cell $color>". cleanSC($changeTime) ."</cell>";
            $xml .= "<cell $color>". cleanSC($rejectionNote) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->emp1) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->emp2) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateTime($overtime->created_at))."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->apv_spv_nip) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->apv_asman_nip) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->apv_mgr_nip) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function revisionOvertime()
    {
        $post = fileGetContent();
        $rankId = empRank();

        if ($rankId == 5 || $rankId == 6) {
            $columnApv = 'apv_spv';
            $columnApvNip = 'apv_spv_nip';
            $columnApvDate = 'apv_spv_date';
        } else if ($rankId == 3 || $rankId == 4) {
            $columnApv = 'apv_asman';
            $columnApvNip = 'apv_asman_nip';
            $columnApvDate = 'apv_asman_date';
        } else if ($rankId == 2) {
            $columnApv = 'apv_mgr';
            $columnApvNip = 'apv_mgr_nip';
            $columnApvDate = 'apv_mgr_date';
        } else if ($rankId == 1) {
            $columnApv = 'apv_head';
            $columnApvNip = 'apv_head_nip';
            $columnApvDate = 'apv_head_date';
        } else {
            $columnApv = 'apv_head';
            $columnApvNip = 'apv_head_nip';
            $columnApvDate = 'apv_head_date';
        }

        $data = [
            'status' => 'CREATED',
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
            'revision_note' => empName() . ": " . $post->revisionNote,
            $columnApv => 'CREATED',
            $columnApvNip => '',
            $columnApvDate => date('0000-00-00 00:00:00'),
        ];

        $this->Hr->update('employee_overtimes', $data, ['task_id' => $post->taskId]);

        $empData = ['status' => 'CREATED', 'updated_by' => empId(), 'updated_at' => date('Y-m-d H:i:s')];
        $this->Hr->update('employee_overtimes_detail', $empData, ['task_id' => $post->taskId], null, ['status' => ['CANCELED', 'REJECTED']]);
        response(['status' => 'success', 'message' => 'Lemburan berhasil di kembalikan ke Admin Lembur']);
    }

    public function rejectOvertime()
    {
        $post = fileGetContent();
        $rankId = $this->auth->rankId;

        if ($this->auth->role === "admin" && $rankId > 6) {
            response(['error' => 'success', 'message' => 'Silahkan ganti privilage anda menjadi PIC lemburan!']);
        }

        if ($rankId == 5 || $rankId == 6) {
            $columnApv = 'apv_spv';
            $columnApvNip = 'apv_spv_nip';
            $columnApvDate = 'apv_spv_date';
        } else if ($rankId == 3 || $rankId == 4) {
            $columnApv = 'apv_asman';
            $columnApvNip = 'apv_asman_nip';
            $columnApvDate = 'apv_asman_date';
        } else if ($rankId == 2) {
            $columnApv = 'apv_mgr';
            $columnApvNip = 'apv_mgr_nip';
            $columnApvDate = 'apv_mgr_date';
        } else if ($rankId == 1) {
            $columnApv = 'apv_head';
            $columnApvNip = 'apv_head_nip';
            $columnApvDate = 'apv_head_date';
        }

        $data = [
            $columnApv => 'REJECTED',
            $columnApvNip => empNip(),
            $columnApvDate => date('Y-m-d H:i:s'),
            'rejection_note' => empName() . " : " . $post->rejectionNote,
            'status' => 'REJECTED',
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $empData = [
            'status' => 'REJECTED',
            'status_by' => empNip(),
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->Hr->update('employee_overtimes', $data, ['task_id' => $post->taskId]);
        $this->Hr->update('employee_overtimes_detail', $empData, ['task_id' => $post->taskId, 'status !=' => 'CANCELED']);

        $overtime = $this->Overtime->getOvertime(['equal_task_id' => $post->taskId])->row();
        if($columnApv == 'apv_spv') {
            $isHaveAsman = $this->Hr->getOne('employees', ['sub_department_id' => $overtime->sub_department_id], '*', ['rank_id' => ['3', '4']]);
            $isHaveAsmanPLT = $this->Hr->getOne('employee_ranks', ['sub_department_id' => $overtime->sub_department_id, 'status' => 'ACTIVE'], '*', ['rank_id' => ['3', '4']]);
            if($overtime->apv_asman_nip == '' && ($isHaveAsman || $isHaveAsmanPLT)) {
                $this->ovtlib->sendEmailReject('ASMAN', 'asman', $overtime, $post->taskId);
            }
        } else if ($columnApv == 'apv_asman') {
            $isHaveMgr = $this->Hr->getOne('employees', ['department_id' => $overtime->department_id, 'rank_id' => 2]);
            $isHaveMgrPLT = $this->Hr->getOne('employee_ranks', ['department_id' => $overtime->department_id, 'rank_id' => 2, 'status' => 'ACTIVE']);
            if($overtime->apv_mgr_nip == '' && ($isHaveMgr && !$isHaveMgrPLT)) {
                $this->ovtlib->sendEmailReject('Manager', 'mgr', $overtime, $post->taskId);
            }
        } else if($columnApv == 'apv_mgr') {
            $isHaveHead = $this->Hr->getOne('employees', ['rank_id' => 1]);
            $isHaveHeadPLT = $this->Hr->getOne('employee_ranks', ['rank_id' => 1, 'status' => 'ACTIVE']);
            if($overtime->apv_head_nip == '' && ($isHaveHead || $isHaveHeadPLT)) {
                $this->ovtlib->sendEmailReject('Plant Manager', 'head', $overtime, $post->taskId);
            }
        }

        response(['status' => 'success', 'message' => 'Lemburan berhasil di batalkan']);
    }

    public function rejectPersonilOvertime()
    {
        $post = fileGetContent();
        $this->Hr->update('employee_overtimes_detail', ['status' => 'REJECTED', 'status_by' => empNip()], ['emp_task_id' => $post->empTaskId]);
        response(['status' => 'success', 'message' => 'Lemburan berhasil di batalkan']);
    }

    public function updatePersonilNeeded()
    {
        $post = getPost();
        $totalPersonil = $this->Hr->countWhere('employee_overtimes_detail', ['task_id' => $post['task_id']]);
        if ($post['personil'] < $totalPersonil) {
            xmlResponse('error', 'Jumlah personil kurang dari total personil yang sudah dilemburkan!');
        }
        $this->Hr->update('employee_overtimes', ['personil' => $post['personil']], ['task_id' => $post['task_id']]);
        xmlResponse('updated', 'Berhasil update kebutuhan orang');
    }

    public function rollbackPersonilOvertime()
    {
        $post = fileGetContent();
        $emp = $this->Hr->getOne('employee_overtimes_detail', ['emp_task_id' => $post->empTaskId]);
        $checkStart = $this->Hr->getOne('employee_overtimes', ['task_id' => $emp->task_id, 'start_date >' => $emp->start_date]);
        if ($checkStart) {
            response(['status' => 'error', 'message' => 'Waktu lembur awal karyawan lebih kecil dari waktu perintah lembur!']);
        }
        $checkEnd = $this->Hr->getOne('employee_overtimes', ['task_id' => $emp->task_id, 'end_date <' => $emp->end_date]);
        if ($checkEnd) {
            response(['status' => 'error', 'message' => 'Waktu lembur akhir karyawan lebih beasr dari waktu perintah lembur!']);
        }

        $this->Hr->updateById('employee_overtimes_detail', ['status' => 'PROCESS', 'status_by' => empNip()], $emp->id);
        response(['status' => 'success', 'message' => 'Berhasil mengembalikan karyawan ke daftar lemburan']);
    }

    public function updateOvertimeHour()
    {
        $post = getPost();
        $overtime = $this->Hr->getDataById('employee_overtimes', $post['id']);
        $startDate = genOvtDate(date('Y-m-d', strtotime($overtime->start_date)), $post['start_date']);
        $endDate = genOvtDate(date('Y-m-d', strtotime($overtime->end_date)), $post['end_date']);

        $checkStart = $this->Hr->getWhere('employee_overtimes_detail',
            ['task_id' => $overtime->task_id, 'start_date <' => $startDate],
            'task_id', null, null, null, ['status' => ['CANCELED']]
        )->row();
        if ($checkStart) {
            xmlResponse('error', 'Ada waktu lembur karyawan yang lebih kecil dari ' . toIndoDateTime($startDate) . ', silahkan cek kembali!');
        }

        $checkEnd = $this->Hr->getWhere('employee_overtimes_detail',
            ['task_id' => $overtime->task_id, 'end_date >' => $endDate],
            'task_id', null, null, null, ['status' => ['CANCELED']]
        )->row();
        if ($checkEnd) {
            xmlResponse('error', 'Ada waktu lembur karyawan yang lebih beasr dari ' . toIndoDateTime($endDate) . ', silahkan cek kembali!');
        }

        $this->Hr->updateById('employee_overtimes', [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'change_time' => "Revised By " . empName() . " @" . toIndoDateTime(date('Y-m-d H:i:s')),
        ], $post['id']);

        xmlResponse('updated', "Berhasil update jam lembur $overtime->task_id");
    }

    public function updateOvertimeDetailHour()
    {
        $post = getPost();
        $overtime = $this->Hr->getDataById('employee_overtimes_detail', $post['id']);
        $startDate = genOvtDate(date('Y-m-d', strtotime($overtime->start_date)), $post['start_date']);
        $endDate = genOvtDate(date('Y-m-d', strtotime($overtime->end_date)), $post['end_date']);

        $checkStart = $this->Hr->getOne('employee_overtimes', ['task_id' => $overtime->task_id, 'start_date >' => $overtime->start_date]);
        if ($checkStart) {
            xmlResponse('error', "Waktu lembur awal karyawan lebih kecil dari waktu perintah lembur!");
        }

        $checkEnd = $this->Hr->getOne('employee_overtimes', ['task_id' => $overtime->task_id, 'end_date <' => $overtime->end_date]);
        if ($checkEnd) {
            xmlResponse('error', "Waktu lembur akhir karyawan lebih beasr dari waktu perintah lembur!");
        }

        $overtimeHour = totalHour($overtime->emp_id, $overtime->overtime_date, $startDate, $endDate, $post['start_date'], $post['end_date']);
        $catPrice = 0;
        $catheringPrice = $this->General->getOne('catherings', ['status' => 'ACTIVE']);
        if($catheringPrice) {
            $catPrice = $catheringPrice->price;
        }

        $this->Hr->updateById('employee_overtimes_detail', [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status_day' => $overtimeHour['status_day'],
            'effective_hour' => $overtimeHour['effective_hour'],
            'break_hour' => $overtimeHour['break_hour'],
            'real_hour' => $overtimeHour['real_hour'],
            'overtime_hour' => $overtimeHour['overtime_hour'],
            'premi_overtime' => $overtimeHour['premi_overtime'],
            'overtime_value' => $overtimeHour['overtime_value'],
            'meal' => $overtimeHour['total_meal'] * $catPrice,
            'total_meal' => $overtimeHour['total_meal'],
            'status_by' => empNip(),
            'change_time' => 1,
        ], $post['id']);

        xmlResponse('updated', "Berhasil update jam lembur $overtime->emp_task_id");
    }

    public function approveOvertime()
    {
        $post = fileGetContent();
        $taskId = $post->taskId;
        $rankId = $this->auth->rankId;
        $pltRankId = $this->auth->pltRankId;

        if ($this->auth->role === "admin" && $rankId > 6 && $pltRankId > 6) {
            response(['error' => 'success', 'message' => 'Silahkan ganti privilage anda menjadi PIC lemburan!']);
        }

        if ($rankId == 5 || $rankId == 6 || $pltRankId == 5 || $pltRankId == 6) {
            $columnApv = 'apv_spv';
            $columnApvNip = 'apv_spv_nip';
            $columnApvDate = 'apv_spv_date';
        } else if ($rankId == 3 || $rankId == 4 || $pltRankId == 3 || $pltRankId == 4) {
            $columnApv = 'apv_asman';
            $columnApvNip = 'apv_asman_nip';
            $columnApvDate = 'apv_asman_date';
        } else if ($rankId == 2 || $pltRankId == 2) {
            $columnApv = 'apv_mgr';
            $columnApvNip = 'apv_mgr_nip';
            $columnApvDate = 'apv_mgr_date';
        } else if ($rankId == 1 || $pltRankId == 1) {
            $columnApv = 'apv_head';
            $columnApvNip = 'apv_head_nip';
            $columnApvDate = 'apv_head_date';
        }

        $data = [
            $columnApv => 'APPROVED',
            $columnApvNip => empNip(),
            $columnApvDate => date('Y-m-d H:i:s'),
        ];

        if ($rankId == 1 || $pltRankId == 1) {
            $data['status'] = "CLOSED";
        }

        $update = $this->Hr->update('employee_overtimes', $data, ['task_id' => $taskId]);
        if ($update) {
            if ($rankId == 1 || $pltRankId == 1) {
                $this->Hr->update('employee_overtimes_detail', ['status' => 'CLOSED'], ['task_id' => $taskId], null, ['status' => ['CANCELED', 'REJECTED']]);
            }

            $overtime = $this->Overtime->getOvertime(['equal_task_id' => $post->taskId])->row();
            if($columnApv == 'apv_spv') {
                $isHaveAsman = $this->isHaveAsman($overtime, $post);
                if(!$isHaveAsman) {
                    $isHaveMgr = $this->isHaveMgr($overtime, $post);
                    if(!$isHaveMgr) {
                        $this->isHaveHead($overtime, $post);
                    }
                }
            } else if ($columnApv == 'apv_asman') {
                if($overtime->sub_department_id != 5 && $overtime->ref == "") {
                    $picEmails = $this->Main->getOne('pics', ['code' => 'overtime', 'sub_department_id' => 5])->pic_emails;
                    $tokenTaskId = simpleEncrypt($post->taskId);
                    $linkAction = LIVE_URL . "index.php?c=PublicController&m=generateOvertime&token=$tokenTaskId";
                    $tokenLink = simpleEncrypt($linkAction);
                    $link = LIVE_URL . "index.php?c=PublicController&m=pinVerification&token=$tokenLink";
                    $message = $this->load->view('html/overtime/email/generate_overtime', ['overtime' => $overtime, 'link' => $link], true);
                    $services = $this->HrModel->getRequestList($overtime);
                    $data = [
                        'alert_name' => 'OVERTIME_REQUEST',
                        'email_to' => $picEmails,
                        'subject' => "Request Lembur (Task ID: $overtime->task_id) Untuk Support Produksi $services[string]",
                        'subject_name' => "Spekta Alert: Request Lembur (Task ID: $overtime->task_id) Untuk Support Produksi $services[string]",
                        'message' => $message,
                    ];
                    $insert = $this->Main->create('email', $data);
                }

                $isHaveMgr = $this->isHaveMgr($overtime, $post);
                if(!$isHaveMgr) {
                    $this->isHaveHead($overtime, $post);
                }
            } else if($columnApv == 'apv_mgr') {
                $this->isHaveHead($overtime, $post);
            }
            response(['status' => 'success', 'message' => 'Approve lemburan berhasil']);
        } else {
            response(['error' => 'success', 'message' => 'Approve lemburan gagal']);
        }
    }

    public function isHaveAsman($overtime, $post)
    {
        $isHaveAsman = $this->Hr->getOne('employees', ['sub_department_id' => $overtime->sub_department_id], '*', ['rank_id' => ['3', '4']]);
        $isHaveAsmanPLT = $this->Hr->getOne('employee_ranks', ['sub_department_id' => $overtime->sub_department_id, 'status' => 'ACTIVE'], '*', ['rank_id' => ['3', '4']]);
        if($overtime->apv_asman_nip == '' && ($isHaveAsman || $isHaveAsmanPLT)) {
            if($isHaveAsman) {
                $this->ovtlib->sendEmailAppv($isHaveAsman->email, 'ASMAN', 'asman', $overtime, $post->taskId);
                return true;
            } else if($isHaveAsmanPLT){
                $email = $this->Hr->getDataById('employees', $isHaveAsmanPLT->emp_id)->email;
                $this->ovtlib->sendEmailAppv($email, 'ASMAN', 'asman', $overtime, $post->taskId);
                return true;
            }
        } else {
            return false;
        }
    }

    public function isHaveMgr($overtime, $post)
    {
        $isHaveMgr = $this->Hr->getOne('employees', ['department_id' => $overtime->department_id, 'rank_id' => 2]);
        $isHaveMgrPLT = $this->Hr->getOne('employee_ranks', ['department_id' => $overtime->department_id, 'rank_id' => 2, 'status' => 'ACTIVE']);
        if($overtime->apv_mgr_nip == '' && ($isHaveMgr || $isHaveMgrPLT)) {
            if($isHaveMgr) {
                $this->ovtlib->sendEmailAppv($isHaveMgr->email, 'Manager', 'mgr', $overtime, $post->taskId);
                return true;
            } else if($isHaveMgrPLT){
                $email = $this->Hr->getDataById('employees', $isHaveMgrPLT->emp_id)->email;
                $this->ovtlib->sendEmailAppv($email, 'Manager', 'mgr', $overtime, $post->taskId);
                return true;
            }
        } else {
            return false;
        }
    }

    public function isHaveHead($overtime, $post)
    {
        $isHaveHead = $this->Hr->getOne('employees', ['rank_id' => 1]);
        $isHaveHeadPLT = $this->Hr->getOne('employees', ['rank_id' => 1, 'status' => 'ACTIVE']);
        if($overtime->apv_head_nip == '' && ($isHaveHead || $isHaveHeadPLT)) {
            if($isHaveHead) {
                $this->ovtlib->sendEmailAppv($isHaveHead->email, 'Plant Manager', 'head', $overtime, $post->taskId);
                return true;
            } else if($isHaveHeadPLT){
                $email = $this->Hr->getDataById('employees', $isHaveHeadPLT->emp_id)->email;
                $this->ovtlib->sendEmailAppv($email, 'Plant Manager', 'head', $overtime, $post->taskId);
                return true;
            }
        } else {
            return false;
        }
    }

    public function getOvertimeRequirement()
    {
        $params = getParam();
        $taskId = $params['task_id'];
        $overtime = $this->Hr->getOne("employee_overtimes", ['task_id' => $taskId]);
        $reqOvt = [
            '3' => $overtime->ahu,
            '4' => $overtime->compressor,
            '5' => $overtime->pw,
            '6' => $overtime->steam,
            '7' => $overtime->dust_collector,
            '8' => $overtime->wfi,
            '9' => $overtime->mechanic,
            '10' => $overtime->electric,
            '11' => $overtime->hnn,
        ];
        $reqs = $this->HrModel->getRequirement();
        $xml = "";
        $no = 1;
        foreach ($reqs as $req) {
            if (isset($reqOvt[$req->id]) && $reqOvt[$req->id] > 0) {
                $xml .= "<row id='$req->id'>";
                $xml .= "<cell>". cleanSC($no) ."</cell>";
                $xml .= "<cell>". cleanSC($req->name) ."</cell>";
                $xml .= "<cell>". cleanSC($req->division_name) ."</cell>";
                $xml .= "<cell>". cleanSC($req->division_id) ."</cell>";
                $xml .= "</row>";
                $no++;
            }
        }
        gridXmlHeader($xml);
    }

    public function getReportOvertimeGrid()
    {
        $params = getParam();
        $overtimes = $this->Overtime->getReportOvertime($params)->result();
        $xml = "";
        $no = 1;
        foreach ($overtimes as $overtime) {
            $color = null;
            if ($overtime->status_day === 'Hari Libur') {
                $color = "bgColor='#efd898'";
            } else if ($overtime->status_day === 'Libur Nasional') {
                $color = "bgColor='#7ecbf1'";
            }

            $ovtStatus = null;
            if($overtime->payment_status == 'VERIFIED') {
                $ovtStatus = "bgColor='#c18cdd'";
            } else if($overtime->payment_status == 'PENDING') {
                $ovtStatus = "bgColor='#c8e71c'";
            } else if($overtime->overtime_review != '') {
                $ovtStatus = "bgColor='#75b175'";
            }
            $meal = $overtime->meal > 0 ? "✓ ($overtime->total_meal x)" : '-';
            $machine1 = $overtime->machine_1 ? $overtime->machine_1 : '-';
            $machine2 = $overtime->machine_2 ? $overtime->machine_2 : '-';
            $xml .= "<row id='$overtime->id'>";
            $xml .= "<cell $ovtStatus>". cleanSC($no) ."</cell>";
            if(isset($params['check'])) {
                $xml .= "<cell $color>0</cell>";
            }
            $xml .= "<cell $color>". cleanSC($overtime->emp_task_id) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->task_id) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->employee_name) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->emp_sub_name) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->emp_division) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->ovt_sub_name) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->ovt_division) ."</cell>";
            $xml .= "<cell $color>". cleanSC($machine1) ."</cell>";
            $xml .= "<cell $color>". cleanSC($machine2) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->requirements) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateDay($overtime->overtime_date))."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateTime2($overtime->start_date))."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateTime2($overtime->end_date))."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->status_day) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->effective_hour) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->break_hour) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->real_hour) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->overtime_hour) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toNumber($overtime->premi_overtime))."</cell>";
            $xml .= "<cell $color>". cleanSC(toNumber($overtime->overtime_value))."</cell>";
            $xml .= "<cell $color>". cleanSC($meal) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toNumber($overtime->meal))."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->status) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->overtime_review) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateTime($overtime->created_at))."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getReportOvertimeSubGrid()
    {
        $overtimes = $this->Overtime->getReportOvertimeSub(getParam())->result();
        $ovt = [];
        foreach ($overtimes as $overtime) {
            $ovt[$overtime->sub_name] = [
                'effective_hour' => $overtime->effective_hour,
                'break_hour' => $overtime->break_hour,
                'real_hour' => $overtime->real_hour,
                'overtime_hour' => $overtime->overtime_hour,
                'overtime_value' => $overtime->overtime_value,
                'meal' => $overtime->meal,
            ];
        }

        $subs = $this->HrModel->subWithDept();
        $xml = "";
        $no = 1;
        foreach ($subs as $sub) {
            if (array_key_exists($sub->name, $ovt)) {
                $effectiveHour = $ovt[$sub->name]['effective_hour'];
                $breakHour = $ovt[$sub->name]['break_hour'];
                $realHour = $ovt[$sub->name]['real_hour'];
                $overtimeHour = $ovt[$sub->name]['overtime_hour'];
                $overtimeHalue = toNumber($ovt[$sub->name]['overtime_value']);
                $meal = toNumber($ovt[$sub->name]['meal']);
            } else {
                $effectiveHour = 0;
                $breakHour = 0;
                $realHour = 0;
                $overtimeHour = 0;
                $overtimeHalue = 0;
                $meal = 0;
            }

            $xml .= "<row id='$sub->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            if ($sub->name != '-') {
                $xml .= "<cell>". cleanSC($sub->name) ."</cell>";
            } else {
                $xml .= "<cell>Direct To Sub Unit</cell>";
            }

            if ($sub->dept_name != '-') {
                $xml .= "<cell>". cleanSC($sub->dept_name) ."</cell>";
            } else {
                $xml .= "<cell>-</cell>";
            }
            $xml .= "<cell>". cleanSC($effectiveHour) ."</cell>";
            $xml .= "<cell>". cleanSC($breakHour) ."</cell>";
            $xml .= "<cell>". cleanSC($realHour) ."</cell>";
            $xml .= "<cell>". cleanSC($overtimeHour) ."</cell>";
            $xml .= "<cell>". cleanSC($overtimeHalue) ."</cell>";
            $xml .= "<cell>". cleanSC($meal) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getReportOvertimeDivGrid()
    {
        $overtimes = $this->Overtime->getReportOvertimeDiv(getParam())->result();
        $ovt = [];
        foreach ($overtimes as $overtime) {
            $ovt[$overtime->div_name] = [
                'effective_hour' => $overtime->effective_hour,
                'break_hour' => $overtime->break_hour,
                'real_hour' => $overtime->real_hour,
                'overtime_hour' => $overtime->overtime_hour,
                'overtime_value' => $overtime->overtime_value,
                'meal' => $overtime->meal,
            ];
        }

        $divs = $this->HrModel->divWithSub();
        $xml = "";
        $no = 1;
        foreach ($divs as $div) {
            if (array_key_exists($div->name, $ovt)) {
                $effectiveHour = $ovt[$div->name]['effective_hour'];
                $breakHour = $ovt[$div->name]['break_hour'];
                $realHour = $ovt[$div->name]['real_hour'];
                $overtimeHour = $ovt[$div->name]['overtime_hour'];
                $overtimeHalue = toNumber($ovt[$div->name]['overtime_value']);
                $meal = toNumber($ovt[$div->name]['meal']);
            } else {
                $effectiveHour = 0;
                $breakHour = 0;
                $realHour = 0;
                $overtimeHour = 0;
                $overtimeHalue = 0;
                $meal = 0;
            }

            $xml .= "<row id='$div->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            if ($div->name != '-') {
                $xml .= "<cell>". cleanSC($div->name) ."</cell>";
            } else {
                $xml .= "<cell>Direct To Bagian</cell>";
            }

            if ($div->sub_name != '-') {
                $xml .= "<cell>". cleanSC($div->sub_name) ."</cell>";
            } else {
                $xml .= "<cell>-</cell>";
            }

            $xml .= "<cell>". cleanSC($effectiveHour) ."</cell>";
            $xml .= "<cell>". cleanSC($breakHour) ."</cell>";
            $xml .= "<cell>". cleanSC($realHour) ."</cell>";
            $xml .= "<cell>". cleanSC($overtimeHour) ."</cell>";
            $xml .= "<cell>". cleanSC($overtimeHalue) ."</cell>";
            $xml .= "<cell>". cleanSC($meal) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getReportOvertimeEmpGrid()
    {
        $overtimes = $this->Overtime->getReportOvertimeEmp(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($overtimes as $overtime) {
            $xml .= "<row id='$overtime->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($overtime->emp_name) ."</cell>";
            $xml .= "<cell>". cleanSC($overtime->div_name) ."</cell>";
            $xml .= "<cell>". cleanSC($overtime->sub_name) ."</cell>";
            $xml .= "<cell>". cleanSC($overtime->dept_name) ."</cell>";
            $xml .= "<cell>". cleanSC($overtime->effective_hour) ."</cell>";
            $xml .= "<cell>". cleanSC($overtime->break_hour) ."</cell>";
            $xml .= "<cell>". cleanSC($overtime->real_hour) ."</cell>";
            $xml .= "<cell>". cleanSC($overtime->overtime_hour) ."</cell>";
            $xml .= "<cell>". cleanSC(toNumber($overtime->overtime_value))."</cell>";
            $xml .= "<cell>". cleanSC(toNumber($overtime->meal))."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getReportOvertimeEmpGridRev()
    {
        $overtimes = $this->Overtime->getReportOvertimeEmp(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($overtimes as $overtime) {
            $xml .= "<row id='$overtime->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($overtime->emp_name) ."</cell>";
            $xml .= "<cell>". cleanSC($overtime->div_name) ."</cell>";
            $xml .= "<cell>". cleanSC($overtime->sub_name) ."</cell>";
            $xml .= "<cell>". cleanSC($overtime->dept_name) ."</cell>";
            $xml .= "<cell>". cleanSC($overtime->notes) ."</cell>";
            $xml .= "<cell>". cleanSC($overtime->effective_hour) ."</cell>";
            $xml .= "<cell>". cleanSC($overtime->break_hour) ."</cell>";
            $xml .= "<cell>". cleanSC($overtime->real_hour) ."</cell>";
            $xml .= "<cell>". cleanSC($overtime->overtime_hour) ."</cell>";
            $xml .= "<cell>". cleanSC(toNumber($overtime->overtime_value))."</cell>";
            $xml .= "<cell>". cleanSC(toNumber($overtime->meal))."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function updateOvertimeReview()
    {
        $post = prettyText(getPost(), ['overtime_review']);
        $data = [
            'overtime_review' => $post['overtime_review'],
        ];
        $this->Hr->update('employee_overtimes', $data, ['task_id' => $post['task_id']]);
        xmlResponse('updated', 'Ulasan pencapaian lembur berhasil disimpan');
    }

    public function ovtVerificationBatch()
    {
        $post = prettyText(getGridPost());
        $data = [];
        foreach ($post as $key => $value) {
            if($value['c1'] == 1) {
                $data[] = [
                    'id' => $key,
                    'payment_status' => 'VERIFIED',
                    'payment_status_by' => empNip(),
                    'updated_by' => empId(),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }
        }

        $update = $this->Hr->updateMultiple('employee_overtimes_detail', $data, 'id');
        if ($update) {
            xmlResponse('updated', 'Verifikasi lemburan berhasil');
        } else {
            xmlResponse('error', 'Verifikasi lemburan gagal!');
        }
    }
    
    public function getRequestOvertimeGrid()
    {
        $techOvertime = $this->Overtime->getTechnicOvertime();
        $taskIds = '';
        $taskId = [];
        foreach ($techOvertime as $tOvt) {
            if($tOvt->ref != '-') {
                if($taskIds == '') {
                    $taskIds = "'".$tOvt->ref."'";
                } else {
                    $taskIds = $taskIds.",'".$tOvt->ref."'";
                }
                $taskId[$tOvt->ref] = $tOvt->task_id;
            }
        }

        if($taskIds != '') {
            $overtimes = $this->Overtime->getRequestOvertimeGrid($taskIds)->result();
            $xml = "";
            $no = 1;
            foreach ($overtimes as $overtime) {
                $makan = $overtime->makan > 0 ? '✓' : '-';
                $steam = $overtime->steam > 0 ? '✓' : '-';
                $ahu = $overtime->ahu > 0 ? '✓' : '-';
                $compressor = $overtime->compressor > 0 ? '✓' : '-';
                $pw = $overtime->pw > 0 ? '✓' : '-';
                $jemputan = $overtime->jemputan > 0 ? '✓' : '-';
                $dust_collector = $overtime->dust_collector > 0 ? '✓' : '-';
                $mechanic = $overtime->mechanic > 0 ? '✓' : '-';
                $electric = $overtime->electric > 0 ? '✓' : '-';
                $hnn = $overtime->hnn > 0 ? '✓' : '-';
                $tTaskId = $taskId[$overtime->task_id];

                $color = null;
                if ($overtime->status_day === 'Hari Libur') {
                    $color = "bgColor='#efd898'";
                } else if ($overtime->status_day === 'Libur Nasional') {
                    $color = "bgColor='#7ecbf1'";
                }

                $xml .= "<row id='$overtime->id'>";
                $xml .= "<cell $color>". cleanSC($no) ."</cell>";
                $xml .= "<cell $color>". cleanSC($tTaskId) ."</cell>";
                $xml .= "<cell $color>". cleanSC($overtime->task_id) ."</cell>";
                $xml .= "<cell $color>". cleanSC($overtime->department) ."</cell>";
                $xml .= "<cell $color>". cleanSC($overtime->sub_department) ."</cell>";
                $xml .= "<cell $color>". cleanSC($overtime->division) ."</cell>";
                $xml .= "<cell $color>". cleanSC("$overtime->personil Orang") ."</cell>";
                $xml .= "<cell $color>". cleanSC($overtime->status_day) ."</cell>";
                $xml .= "<cell $color>". cleanSC(toIndoDateDay($overtime->overtime_date)) ."</cell>";
                $xml .= "<cell $color>". cleanSC(toIndoDateTime($overtime->start_date)) ."</cell>";
                $xml .= "<cell $color>". cleanSC(toIndoDateTime($overtime->end_date)) ."</cell>";
                $xml .= "<cell $color>". cleanSC($overtime->notes) ."</cell>";
                $xml .= "<cell $color>". cleanSC($makan) ."</cell>";
                $xml .= "<cell $color>". cleanSC($steam) ."</cell>";
                $xml .= "<cell $color>". cleanSC($ahu) ."</cell>";
                $xml .= "<cell $color>". cleanSC($compressor) ."</cell>";
                $xml .= "<cell $color>". cleanSC($pw) ."</cell>";
                $xml .= "<cell $color>". cleanSC($jemputan) ."</cell>";
                $xml .= "<cell $color>". cleanSC($dust_collector) ."</cell>";
                $xml .= "<cell $color>". cleanSC($mechanic) ."</cell>";
                $xml .= "<cell $color>". cleanSC($electric) ."</cell>";
                $xml .= "<cell $color>". cleanSC($hnn) ."</cell>";
                $xml .= "<cell $color>". cleanSC($overtime->emp1) ."</cell>";
                $xml .= "<cell $color>". cleanSC($overtime->emp2) ."</cell>";
                $xml .= "<cell $color>". cleanSC(toIndoDateTime($overtime->created_at))."</cell>";
                $xml .= "</row>";
                $no++;
            }
            gridXmlHeader($xml);
        } else {
            $xml = "<row></row>";
            gridXmlHeader($xml);
        }
    }

    public function addRevisionRequest()
    {
        $post = prettyText(getPost(), ['description']);
        $date = date('Y-m-d');
        $expDate = explode('-', $date);
        $lastId = $this->Overtime->lastOt('overtime_revision_requests', 'created_at' ,$date);
        $revTaskId = sprintf('%03d', $lastId + 1) . '/OT-REV/' . empLoc() . '/' . toRomawi($expDate[1]) . '/' . $expDate[0];
        $data = [
            'location' => empLoc(),
            'task_id' => $revTaskId,
            'description' => $post['description'],
            'department_id' => $post['department_id'],
            'sub_department_id' => $post['sub_department_id'],
            'filename' => $post['filename'],
            'created_by' => empId(),
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $request_id = $this->Hr->create('overtime_revision_requests', $data);
        
        $expTask = explode(",", $post['task_ids']);
        $dataDetail = [];
        foreach ($expTask as $taskId) {
            $dataDetail[] = [
                'task_id' => $revTaskId,
                'emp_task_id' => $taskId,
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        
        $reqDetail = $this->Hr->createMultiple('overtime_revision_requests_detail', $dataDetail);

        $this->sendRevisionEmail($revTaskId, $post['task_ids'], 'OVERTIME_REVISION_REQUEST');
        xmlResponse('inserted', 'Berhasil membuat form pengajuan revisi lembur');
    }

    public function addPersonRevisionRequest()
    {
        $post = fileGetContent();
        $revTaskId = $post->revTaskId;
        $data = [];
        foreach ($post->taskId as $taskId) {
            $data[] = [
                'emp_task_id' => $taskId,
                'task_id' => $revTaskId
            ];
        }
        $this->Hr->createMultiple('overtime_revision_requests_detail', $data);
        response(['status' => 'success', 'message' => 'Berhasil menambahkan data lembur']);
    }

    public function getWindowOvertimeGrid()
    {
        $params = getParam();
        $revisions = $this->Hr->getWhere('overtime_revision_requests_detail', ['status' => 'CREATED'])->result();
        $taskIds = '';
        foreach ($revisions as $rev) {
            if($taskIds === '') {
                $taskIds = $rev->emp_task_id;
            } else {
                $taskIds = $taskIds.",".$rev->emp_task_id;
            }
        }
        
        if($taskIds != '') {
            $params['notin_emp_task_id'] = $taskIds;
        }

        $params['gt_overtime_date'] = backDayToDate(date('Y-m-d'), 7);

        $overtimes = $this->Overtime->getWindowOvertimeGrid($params)->result();
        $xml = "";
        $no = 1;
        foreach ($overtimes as $overtime) {
            $color = null;
            if ($overtime->status_day === 'Hari Libur') {
                $color = "bgColor='#efd898'";
            } else if ($overtime->status_day === 'Libur Nasional') {
                $color = "bgColor='#7ecbf1'";
            }

            $meal = $overtime->meal > 0 ? "✓ ($overtime->total_meal x)" : '-';
            $machine1 = $overtime->machine_1 ? $overtime->machine_1 : '-';
            $machine2 = $overtime->machine_2 ? $overtime->machine_2 : '-';

            $xml .= "<row id='$overtime->emp_task_id'>";
            $xml .= "<cell $color>". cleanSC($no) ."</cell>";
            $xml .= "<cell $color>0</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->emp_task_id) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->employee_name) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->sub_department) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->division) ."</cell>";
            $xml .= "<cell $color>". cleanSC($machine1) ."</cell>";
            $xml .= "<cell $color>". cleanSC($machine2) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->requirements) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateDay($overtime->overtime_date)) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateTime2($overtime->start_date)) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateTime2($overtime->end_date)) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->status_day) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->effective_hour) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->break_hour) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->real_hour) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->overtime_hour) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toNumber($overtime->premi_overtime))."</cell>";
            $xml .= "<cell $color>". cleanSC(toNumber($overtime->overtime_value))."</cell>";
            $xml .= "<cell $color>". cleanSC($meal) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toNumber($overtime->meal))."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->notes) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->status) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateTime($overtime->created_at))."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getRevOvtGrid()
    {
        $revisions = $this->Overtime->getRevOvtGrid(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($revisions as $rev) {
            $color = null;
            if($rev->status == 'PROCESS') {
                $color = "bgColor='#efd898'";
            } else if($rev->status == 'CLOSED') {
                $color = "bgColor='#7ecb87'";
            } else if($rev->status == 'CANCELED') {
                $color = "bgColor='#d7a878'";
            } else if($rev->status == 'REJECTED') {
                $color = "bgColor='#c94b62'";
            }

            $xml .= "<row id='$rev->task_id'>";
            $xml .= "<cell $color>". cleanSC($no) ."</cell>";
            $xml .= "<cell $color>". cleanSC($rev->task_id) ."</cell>";
            $xml .= "<cell $color>". cleanSC($rev->description) ."</cell>";
            $xml .= "<cell $color>". cleanSC($rev->department) ."</cell>";
            $xml .= "<cell $color>". cleanSC($rev->sub_department) ."</cell>";
            $xml .= "<cell $color>". cleanSC($rev->status) ."</cell>";
            $xml .= "<cell $color>". cleanSC($rev->emp1) ."</cell>";
            $xml .= "<cell $color>". cleanSC($rev->emp2) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateTime($rev->created_at))."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getRevOvtDtlGrid()
    {
        $params = getParam();
        $taskId = $params['taskId'];
        $overtimes = $this->Overtime->getRevOvtDtlGrid($taskId);
        $xml = "";
        $no = 1;
        foreach ($overtimes as $overtime) { 
            $color = null;
            if ($overtime->status_day === 'Hari Libur') {
                $color = "bgColor='#efd898'";
            } else if ($overtime->status_day === 'Libur Nasional') {
                $color = "bgColor='#7ecbf1'";
            }

            $meal = $overtime->meal > 0 ? "✓ ($overtime->total_meal x)" : '-';
            $machine1 = $overtime->machine_1 ? $overtime->machine_1 : '-';
            $machine2 = $overtime->machine_2 ? $overtime->machine_2 : '-';

            $xml .= "<row id='$overtime->emp_task_id'>";
            $xml .= "<cell $color>". cleanSC($no) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->emp_task_id) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->employee_name) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->sub_department) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->division) ."</cell>";
            $xml .= "<cell $color>". cleanSC($machine1) ."</cell>";
            $xml .= "<cell $color>". cleanSC($machine2) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->requirements) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateDay($overtime->overtime_date)) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateTime2($overtime->start_date)) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateTime2($overtime->end_date)) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->status_day) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->effective_hour) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->break_hour) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->real_hour) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->overtime_hour) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toNumber($overtime->premi_overtime)) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toNumber($overtime->overtime_value)) ."</cell>";
            $xml .= "<cell $color>". cleanSC($meal) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toNumber($overtime->meal)) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->notes) ."</cell>";
            $xml .= "<cell $color>". cleanSC($overtime->status) ."</cell>";
            $xml .= "<cell $color>". cleanSC(toIndoDateTime($overtime->created_at)) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function updateRevOvtDesc()
    {
        $post = prettyText(getPost(), ['description']);
        $status = $this->Hr->getOne('overtime_revision_requests', ['task_id' => $post['task_id']])->status;
        if($status != 'CREATED') {
            xmlResponse('error', "Gagal update deskripsi, status permintaan revisi sudah $status");
        }
        $data = [
            'description' => $post['description'],
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->Hr->update('overtime_revision_requests', $data, ['task_id' => $post['task_id']]);
        xmlResponse('updated', 'Berhasil mengubah deskripsi pengajuan revisi lembur');
    }

    public function updateRevOvtRes()
    {
        $post = prettyText(getPost(), ['response']);
        $data = [
            'response' => $post['response'],
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->Hr->update('overtime_revision_requests', $data, ['task_id' => $post['task_id']]);
        xmlResponse('updated', 'Berhasil menyimpan tanggapan pengajuan revisi lembur');
    }

    public function cancelRevOvt()
    {
       $post = fileGetContent();
       $data = [
            'status' => 'CANCELED',
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $revision = $this->Hr->getOne('overtime_revision_requests', ['task_id' => $post->taskId]);
        if($revision->status == 'CREATED') {
            $this->Hr->update('overtime_revision_requests', $data, ['task_id' => $post->taskId]);
            $this->Hr->delete('overtime_revision_requests_detail', ['task_id' => $post->taskId]);
            if (file_exists('./assets/images/overtimes_revision_request/' . $revision->filename)) {
                unlink('./assets/images/overtimes_revision_request/' . $revision->filename);
            }
            response(['status' => 'success', 'message' => 'Berhasil membatalkan pengajuan revisi lembur']);
        } else {
            response(['status' => 'error', 'message' => 'Gagal membatalkan pengajuan revisi lembur!']);
        }
    }

    public function cancelRevOvtDetail()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $mSuccess .= "- $data->field berhasil dihapus  <br>";
            $this->Hr->delete('overtime_revision_requests_detail', ['emp_task_id' => $data->id]);
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    public function getDescription()
    {
        $post = fileGetContent();
        $desc = $this->Hr->getOne('overtime_revision_requests', ['task_id' => $post->taskId]);
        response(['task_id' => $desc->task_id, 'description' => $desc->description]);
    }

    public function getRevision()
    {
        $post = fileGetContent();
        $rev = $this->Overtime->getRevOvtGrid(['equal_task_id' => $post->taskId])->row();
        response(['status' => 'success', 'revision' => $rev]);
    }

    public function processRevision()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $this->Hr->update('overtime_revision_requests', ['status' => 'PROCESS'],['task_id' => $data->id]);
            $mSuccess .= "- $data->field berhasil diproses <br>";
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    public function rejectRevision()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $revision = $this->Hr->getOne('overtime_revision_requests', ['task_id' => $data->id]);
            if($revision->status == 'CREATED' || $revision->status == 'PROCESS') {
                $this->Hr->update('overtime_revision_requests', ['status' => 'REJECTED'],['task_id' => $data->id]);
                $this->Hr->update('overtime_revision_requests_detail', ['status' => 'REJECTED'],['task_id' => $data->id]);
                $mSuccess .= "- $data->field berhasil ditolak <br>";

                $empTasks = $this->Hr->getWhere('overtime_revision_requests_detail', ['task_id' => $data->id])->result();
                $empTaskIds = '';
                foreach ($empTasks as $rev) {
                    if($empTaskIds == '') {
                        $empTaskIds = $rev->emp_task_id;
                    } else {
                        $empTaskIds = $empTaskIds.",".$rev->emp_task_id;
                    }
                }
                $this->sendRevisionEmail($data->id, $empTaskIds, 'OVERTIME_REVISION_REJECTION');
            } else {
                $mError .= "- Gagal mengubah status revisi $data->field <br>";
            }
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    public function updateRevisionHour()
    {
        $post = getPost();
        $overtime = $this->Hr->getOne('employee_overtimes_detail', ['emp_task_id' => $post['task_id']]);
        $startDate = genOvtDate(date('Y-m-d', strtotime($overtime->start_date)), $post['start_date']);
        $endDate = genOvtDate(date('Y-m-d', strtotime($overtime->end_date)), $post['end_date']);
    
        $overtimeHour = totalHour($overtime->emp_id, $overtime->overtime_date, $startDate, $endDate, $post['start_date'], $post['end_date']);
        $catPrice = 0;
        $catheringPrice = $this->General->getOne('catherings', ['status' => 'ACTIVE']);
        if($catheringPrice) {
            $catPrice = $catheringPrice->price;
        }

        if($startDate > $endDate || $startDate == $endDate) {
            xmlResponse('error', "Waktu selesai harus lebih besar dari waktu mulai!");
        }

        $this->Hr->update('employee_overtimes_detail', [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status_day' => $overtimeHour['status_day'],
            'effective_hour' => $overtimeHour['effective_hour'],
            'break_hour' => $overtimeHour['break_hour'],
            'real_hour' => $overtimeHour['real_hour'],
            'overtime_hour' => $overtimeHour['overtime_hour'],
            'premi_overtime' => $overtimeHour['premi_overtime'],
            'overtime_value' => $overtimeHour['overtime_value'],
            'meal' => $overtimeHour['total_meal'] * $catPrice,
            'total_meal' => $overtimeHour['total_meal'],
            'status_by' => empNip(),
            'change_time' => 1,
        ], ['emp_task_id' => $post['task_id']]);

        xmlResponse('updated', "Berhasil update jam lembur $overtime->emp_task_id");
    }

    public function closeRevision()
    {
        $post = fileGetContent();
        $this->Hr->update('overtime_revision_requests', ['status' => 'CLOSED'], ['task_id' => $post->taskId]);
        $this->Hr->update('overtime_revision_requests_detail', ['status' => 'CLOSED'], ['task_id' => $post->taskId]);
        $empTasks = $this->Hr->getWhere('overtime_revision_requests_detail', ['task_id' => $post->taskId])->result();
        $empTaskIds = '';
        foreach ($empTasks as $rev) {
            if($empTaskIds == '') {
                $empTaskIds = $rev->emp_task_id;
            } else {
                $empTaskIds = $empTaskIds.",".$rev->emp_task_id;
            }
        }
        $this->sendRevisionEmail($post->taskId, $empTaskIds, 'OVERTIME_REVISION_CLOSED');
        response(['status' => 'success', 'message' => 'Berhasil menutup revisi!']);
    }

    public function sendRevisionEmail($taskId, $empTaskIds, $alertName)
    {
        $email = '';
        $sdms = $this->Hr->getWhere('employees', ['division_id' => 38])->result();
        foreach ($sdms as $sdm) {
            if($email == '') {
                $email = $sdm->email;
            } else {
                $email = $email.','.$sdm->email;
            }
        }
        $overtimes = $this->Overtime->getOvertimeDetail(['in_emp_task_id' => $empTaskIds])->result();
        $revision = $this->Overtime->getRevOvtGrid(['in_task_id' => $taskId])->row();
        $message = $this->load->view('html/overtime/email/revision_overtime', [
            'overtimes' => $overtimes, 
            'revision' => $revision, 
            'location' => $this->auth->locName,
            'status' => $alertName
        ], true);

        $prefix = 'Request';
        if($alertName == 'OVERTIME_REVISION_REJECTION') {
            $prefix = 'Rejection';
        } else if($alertName == 'OVERTIME_REVISION_CLOSED'){
            $prefix = 'Closed';
        }
        $data = [
            'alert_name' => $alertName,
            'email_to' => $email,
            'subject' => "$prefix Revisi Lembur $revision->department (Task ID: $taskId)",
            'subject_name' => "Spekta Alert: $prefix Revisi Lembur $revision->department (Task ID: $taskId)",
            'message' => $message,
        ];
        $insert = $this->Main->create('email', $data);
    }

    public function viewAttachment()
    {
        $post = fileGetContent();
        $rev = $this->Hr->getOne('overtime_revision_requests', ['task_id' => $post->taskId]);
        if($rev->filename) {
            $imgUrl = base_url('assets/images/overtimes_revision_requests/' . $rev->filename);
            $template = "<div style='width:100%;height:100%;'>
                                <img style='width:100%;height:100%;' src='$imgUrl' />
                        </div>";
        } else {
            $imgUrl = base_url('public/img/no-image.png');
            $template = "<div style='width:100%;height:100%;display:flex;flex-direction:column;justify-content:center;align-items:center'>
                                <img style='width:120px;height:100px;' src='$imgUrl' />
                        </div>";
        }
        response([
            'status' => 'success', 
            'template' => $template
        ]);
    }
}
