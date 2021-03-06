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

    public function getDivision()
    {
        $params = getParam();
        $divList = [];
        if(isset($params['select']) && $params['select'] == 0) {
            $divList['options'][] = [
                'value' => 0,
                'text' => "-",
                'selected' => 1,
            ];
        } else {
            $divs = $this->Hr->getWhere('divisions', ['sub_department_id' => $params['subDeptId']], "*", null, ['name' => 'ASC'])->result();
            if($divs) {
                $divList['options'][] = [
                    'value' => 0,
                    'text' => '-',
                    'selected' => isset($params['select']) ? 1 : 0,
                ]; 
                foreach ($divs as $div) {
                    $divList['options'][] = [
                        'value' => $div->id,
                        'text' => $div->name,
                        'selected' => isset($params['select']) && $params['select'] == $div->id ? 1 : 0,
                    ];
                }
            } else {
                $divList['options'][] = [
                    'value' => 0,
                    'text' => '-',
                    'selected' => 1,
                ]; 
            }
        }
        echo json_encode($divList);
    }

    public function getDepartment()
    {
        $depts = $this->Overtime->getDepartment(getParam());
        $deptList = [];
        foreach ($depts as $dept) {
            if($dept->id == empDept()) {
                $deptList['options'][] = [
                    'value' => $dept->id,
                    'text' => $dept->name,
                    'selected' => isset($params['select']) && $params['select'] == $dept->id ? 1 : 0,
                ];
            }
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
                    'text' => '- (By Pass Approval Asman)',
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
                    'text' => '- (By Pass Approval Asman)',
                    'selected' => 1,
                ];
            }
        }
        echo json_encode($subList);
    }

    public function getOTRequirement()
    {
        $params = getParam();
        $reqs = $this->Hr->getWhere("overtime_requirement", ['table_code !=' => 'makan'])->result();
        $data = [];
        $data[] = [
            'type' => 'settings',
            'position' => 'label-right',
        ];
       
        if ($params['split'] == 'teknik') {
            foreach ($reqs as $req) {
                if ($req->id > 2 && $req->id <= 11) {
                    $data[] = [
                        'type' => 'checkbox',
                        'name' => $req->table_code,
                        'value' => $req->id,
                        'label' => $req->name,
                    ];
                }
            }
        } else if ($params['split'] == 'support') {
            foreach ($reqs as $req) {
                if ($req->id == 2 || ($req->id >= 12 && $req->id <= 16)) {
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
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            $xml .= "<cell>0</cell>";
            $xml .= "<cell>" . cleanSC($machine->name) . "</cell>";
            $xml .= "<cell>" . cleanSC($machine->department) . "</cell>";
            $xml .= "<cell>" . cleanSC($machine->sub_department) . "</cell>";
            $xml .= "<cell>" . cleanSC($machine->division) . "</cell>";
            $xml .= "<cell>" . cleanSC("Gedung $machine->building Ruang $machine->room") . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function createInitialOvertime()
    {
        $currDate = new DateTime(date('Y-m-d H:i:s'));
        $limitDate = new DateTime(date('Y-m-d 21:00:00'));

        if($currDate > $limitDate) {
            if(!empRank() <= 4 && !pltRankId() <= 4) {
                return xmlResponse('error', 'Gagal membuat lemburan, lembuaran hanya bisa dibuat dibawah jam 14:00:00');
            }
        }

        $post = prettyText(getPost(), ['notes']);
        $date = $post['overtime_date'];
        $expDate = explode('-', $date);
        $lastId = $this->Overtime->lastOt('employee_overtimes', 'overtime_date', $date);
        $taskId = sprintf('%03d', $lastId + 1) . '/OT/' . empLoc() . '/' . toRomawi($expDate[1]) . '/' . $expDate[0];
        $start = genOvtDate($date, $post['start_date']);
        $end = genOvtDate($date, $post['end_date']);

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
            'jemputan' => isset($post['jemputan']) ? $post['jemputan'] : 0,
            'dust_collector' => isset($post['dust_collector']) ? $post['dust_collector'] : 0,
            'wfi' => isset($post['wfi']) ? $post['wfi'] : 0,
            'mechanic' => isset($post['mechanic']) ? $post['mechanic'] : 0,
            'electric' => isset($post['electric']) ? $post['electric'] : 0,
            'hnn' => isset($post['hnn']) ? $post['hnn'] : 0,
            'qc' => isset($post['qc']) ? $post['qc'] : 0,
            'qa' => isset($post['qa']) ? $post['qa'] : 0,
            'penandaan' => isset($post['penandaan']) ? $post['penandaan'] : 0,
            'gbk' => isset($post['gbk']) ? $post['gbk'] : 0,
            'gbk' => isset($post['gbk']) ? $post['gbk'] : 0,
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
            if(isset($post['taskIds'])) {
                $refs = explode(',', $post['taskIds']);
                $dtf = [];
                foreach ($refs as $key => $value) {
                    $dtf[] = [
                        'task_id' => $value,
                        'task_id_support' => $taskId
                    ];
                }
                if(count($dtf) > 0) {
                    $this->Hr->updateMultiple("employee_overtimes_ref", $dtf, 'task_id');
                }
            }
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
            $makan = $overtime->makan > 0 ? '???' : '-';
            $steam = $overtime->steam > 0 ? '???' : '-';
            $ahu = $overtime->ahu > 0 ? '???' : '-';
            $compressor = $overtime->compressor > 0 ? '???' : '-';
            $pw = $overtime->pw > 0 ? '???' : '-';
            $jemputan = $overtime->jemputan > 0 ? '???' : '-';
            $dust_collector = $overtime->dust_collector > 0 ? '???' : '-';
            $wfi = $overtime->wfi > 0 ? '???' : '-';
            $mechanic = $overtime->mechanic > 0 ? '???' : '-';
            $electric = $overtime->electric > 0 ? '???' : '-';
            $hnn = $overtime->hnn > 0 ? '???' : '-';
            $qc = $overtime->qc > 0 ? '???' : '-';
            $qa = $overtime->qa > 0 ? '???' : '-';
            $penandaan = $overtime->penandaan > 0 ? '???' : '-';
            $gbk = $overtime->gbk > 0 ? '???' : '-';
            $gbk = $overtime->gbk > 0 ? '???' : '-';

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
            $xml .= "<cell $color>" . cleanSC($no) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->task_id) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->department) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->sub_department) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->division) . "</cell>";
            $xml .= "<cell $color>" . cleanSC("$overtime->personil Orang") . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->status_day) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateDay($overtime->overtime_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->start_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->end_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->notes) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($makan) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($steam) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($ahu) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($compressor) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($pw) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($jemputan) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($dust_collector) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($wfi) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($mechanic) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($electric) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($hnn) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($qc) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($qa) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($penandaan) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($gbk) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($gbk) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->status) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($revisionHour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($revisionNote) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rejectionNote) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp1) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp2) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime($overtime->created_at)) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getOvertimeDetailGrid()
    {
        $params = getParam();
        $overtimes = $this->Overtime->getOvertimeDetail($params)->result();
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
            }

            $spvColor = $color;
            if($overtime->apv_spv == 'APPROVED') {
                $spvColor = "bgColor='#cedb10'";
            } else if($overtime->apv_spv == 'REJECTED') {
                $spvColor = "bgColor='#cedb10'";
            }

            $machine1 = $overtime->machine_1 ? $overtime->machine_1 : '-';
            $machine2 = $overtime->machine_2 ? $overtime->machine_2 : '-';
            $meal = $overtime->meal > 0 ? "??? ($overtime->total_meal x)" : '-';
            $xml .= "<row id='$overtime->id'>";
            $xml .= "<cell $spvColor>" . cleanSC($no) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp_task_id) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->employee_name) . "</cell>";
            if(isset($params['apv'])) {
                $xml .= "<cell $color>" . cleanSC($overtime->notes) . "</cell>";
            }
            $xml .= "<cell $color>" . cleanSC($overtime->department) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->sub_department) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->division) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($machine1) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($machine2) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->requirements) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateDay($overtime->overtime_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->start_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->end_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->status_day) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->effective_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->break_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->real_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->overtime_hour) . "</cell>";
            if(empRole() === 'admin' || empRank() <= 6 || (pltRankId() !== '-' && pltRankId() <= 6)) {
                $xml .= "<cell $color>" . cleanSC(toNumber($overtime->premi_overtime)) . "</cell>";
                $xml .= "<cell $color>" . cleanSC(toNumber($overtime->overtime_value)) . "</cell>";
            } else {
                $xml .= "<cell $color>" . cleanSC(toNumber(0)) . "</cell>";
                $xml .= "<cell $color>" . cleanSC(toNumber(0)) . "</cell>";
            }
            $xml .= "<cell $color>" . cleanSC($meal) . "</cell>";
            if(!isset($params['apv'])) {
                $xml .= "<cell $color>" . cleanSC($overtime->notes) . "</cell>";
            }
            $xml .= "<cell $color>" . cleanSC($overtime->status) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($status_updater) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->supervisor ? $overtime->apv_spv.' By '.$overtime->supervisor : '-') . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp1) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp2) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime($overtime->created_at)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->status_by) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp_id) . "</cell>";

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
        $totalPersonil = $this->Hr->countWhere('employee_overtimes_detail', ['task_id' => $post->taskId]);
        $prodOvertime = $this->Overtime->getOvertime(['equal_task_id' => $post->taskId])->row();
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
            'template' => $template,
        ]);
    }

    public function getOvertimeMachine()
    {
        $machines = $this->Overtime->getOvertimeMachine(getParam());
        $xml = "";
        $no = 1;
        foreach ($machines as $machine) {
            $xml .= "<row id='$machine->id'>";
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            $xml .= "<cell>" . cleanSC($machine->name) . "</cell>";
            $xml .= "<cell>" . cleanSC($machine->personil_ideal) . " Orang</cell>";
            $xml .= "<cell>" . cleanSC("Gedung $machine->building Ruang $machine->room") . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getEmployees()
    {
        $get = getParam();
        $emps = $this->Overtime->getEmployee($get)->result();
        $xml = "";
        $no = 1;
        foreach ($emps as $emp) {
            $realHour = $emp->real_hour ? $emp->real_hour : 0;
            $color = null;
            if ($realHour > 60) {
                $color = "bgColor='#ed9a9a'";
            }
            $xml .= "<row id='$emp->id'>";
            $xml .= "<cell $color>" . cleanSC($no) . "</cell>";
            $xml .= "<cell $color>0</cell>";
            $xml .= "<cell $color>" . cleanSC($emp->employee_name) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($realHour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($emp->dept_name) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($emp->sub_name) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($emp->division_name) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($emp->employee_status) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function createPersonilOvertime()
    {
        $post = prettyText(getPost(), ['notes']);
        $overtime = $this->Hr->getDataById('employee_overtimes', $post['overtime_id']);
        $ovtDateStart = date('Y-m-d', strtotime($overtime->start_date));
        $ovtDateEnd = date('Y-m-d', strtotime($overtime->end_date));
        $ovtClockStart = dtToFloat($overtime->start_date);
        $ovtClockEnd = dtToFloat($overtime->end_date);
        $curentClockStart = clockToFloat($post['start_date']);
        $curentClockEnd = clockToFloat($post['end_date']);

        if($ovtDateStart == $ovtDateEnd) {
            $start = genOvtDate($overtime->overtime_date, $post['start_date']);
            $end = genOvtDate($overtime->overtime_date, $post['end_date']);
            $dateStart = new DateTime($start);
            $dateEnd = new DateTime($end);
            if ($dateEnd <= $dateStart) {
                xmlResponse('invalid', 'Waktu selesai harus lebih besar dari waktu mulai!');
            }
        } else {
            if($curentClockStart < $ovtClockStart) {
                $start = addDayToDate(genOvtDate($ovtDateStart, $post['start_date']), 1);
            } else {
                $start = genOvtDate($ovtDateStart, $post['start_date']);
            }

            if($curentClockEnd > $ovtClockEnd) {
                $end = backDayToDate(genOvtDate($ovtDateEnd, $post['end_date']), 1);
            } else {
                $end = genOvtDate($ovtDateEnd, $post['end_date']);
            }

            $dateStart = new DateTime($start);
            $dateEnd = new DateTime($end);
            if ($dateEnd <= $dateStart) {
                xmlResponse('invalid', 'Waktu selesai harus lebih besar dari waktu mulai!');
            }
        }
       
        $expDate = explode('-', $overtime->overtime_date);
        $lastId = $this->Overtime->lastOt('employee_overtimes_detail', 'overtime_date', $overtime->overtime_date);

        if (countHour($start, $end, 'h') > 24) {
            xmlResponse('invalid', 'Maksimum jam lembur adalah 18 jam!');
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
        $requirements = isset($post['requirements']) && $post['requirements'] != '' ? $post['requirements'] : '-';

        $catheringPrice = $this->General->getOne('catherings', ['status' => 'ACTIVE']);
        $catPrice = $catheringPrice ? $catheringPrice->price : 0;
        $data = [];
        $no = 1;
        foreach ($personils as $key => $id) {
            $emp = $this->Hr->getDataById('employees', $id);
            $empOvt = $this->Hr->getWhere('employee_overtimes_detail', [
                'emp_id' => $emp->id, 
                'overtime_date' => date('Y-m-d', strtotime($start))
            ], '*', null, null, null, ['status' => ['CANCELED', 'REJECTED']])->result();

            $dateExist = 0;
            $dt1 = "";
            $dt2 = "";
            foreach ($empOvt as $evt) {
                if (checkDateExist($start, $evt->start_date, $evt->end_date)) {
                   $dateExist++;
                   $dt1 = $start;
                }
                if (checkDateExist($end, $evt->start_date, $evt->end_date)) {
                    $dateExist++;
                    $dt2 = $end;
                 }
            }

            if ($dateExist > 0) {
                $message = "";
                if ($dt1 != '' && $dt2 != '') {
                    $message = "Lembur $emp->employee_name Tanggal " . toIndoDateTime($dt1) . " dan " . toIndoDateTime($dt2) . " sudah dibuat!";
                } else if ($dt1 != '' && $dt2 == '') {
                    $message = "Lembur $emp->employee_name Tanggal " . toIndoDateTime($dt1) . " sudah dibuat!";
                } else if ($dt1 == '' && $dt2 != '') {
                    $message = "Lembur $emp->employee_name Tanggal " . toIndoDateTime($dt2) . " sudah dibuat!";
                }
                xmlResponse('error', $message);
            }

            $taskId = sprintf('%03d', ($lastId + $no)) . '/OT-EMP/' . empLoc() . '/' . toRomawi($expDate[1]) . '/' . $expDate[0];
            $overtimeHour = totalHour($id, $start, $end, $post['start_date'], $post['end_date']);

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
                'overtime_date' => date('Y-m-d', strtotime($start)),
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
                'status' => isset($post['status']) ? $post['status'] : 'CREATED',
                'revision_status' => isset($post['revision_status']) ? $post['revision_status'] : 'NONE',
                'status_before' => isset($post['status_before']) ? $post['status_before'] : 'CLOSED',
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
                $this->Hr->updateById('employee_overtimes_detail', ['status' => 'CANCELED', 'updated_by' => empId(), 'updated_at' => date('Y-m-d H:i:s')], $data->id);
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
        $spvEmail = null;

        $data = [
            'status' => 'PROCESS',
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
            'makan' => $makan > 0 ? 1 : 0,
        ];

        $empTaskIds = [];
        $empOvertimes = $this->Hr->getWhere('employee_overtimes_detail', ['task_id' => $overtime->task_id])->result();
        foreach ($empOvertimes as $empOvt) {
            $isHaveSpv = $this->Hr->getOne('employees', ['division_id' => $empOvt->division_id, 'division_id !=' => 0], '*', ['rank_id' => ['5', '6']]);
            $isHaveSpvPLT = $this->Hr->getOne('employee_ranks', ['division_id' => $empOvt->division_id, 'division_id !=' => 0, 'status' => 'ACTIVE'], '*', ['rank_id' => ['5', '6']]);
            if ($isHaveSpv) {
                $empTaskIds[$empOvt->division_id]['task'][] = $empOvt->emp_task_id;
                $empTaskIds[$empOvt->division_id]['email'] = $isHaveSpv->email;
            } else if ($isHaveSpvPLT) {
                $email = $this->Hr->getDataById('employees', $isHaveSpvPLT->emp_id)->email;
                $empTaskIds[$empOvt->division_id]['task'][] = $empOvt->emp_task_id;
                $empTaskIds[$empOvt->division_id]['email'] = $email;
            } else {
                $detailData = [
                    'apv_spv' => 'BY PASS',
                    'apv_spv_nip' => '-',
                    'apv_spv_date' => $overtime->start_date,
                ];
                $this->Hr->updateById('employee_overtimes_detail', $detailData, $empOvt->id);
            }
        }

        foreach ($empTaskIds as $divId => $ovtData) {
            $empTasks = implode(',', $ovtData['task']);
            $personils = $this->Overtime->getOvertimeDetailRealHour(['in_emp_task_id' => $empTasks, 'notin_status' => 'CANCELED' , 'order_by' => ['start_date' => 'ASC']])->result();
            $this->ovtlib->sendEmailAppv($ovtData['email'], 'Supervisor', 'spv', $overtime, $post->taskId, $personils, $divId);
        }

        if($overtime->sub_department_id != 5 && isMtnSupport($overtime)) {
            $this->requestOvertime($overtime, 5);
        }

        if($overtime->sub_department_id != 7 && isQaSupport($overtime)) {
            $this->requestOvertime($overtime, 7);
        }

        if($overtime->sub_department_id != 8 && isQcSupport($overtime)) {
            $this->requestOvertime($overtime, 8);
        }

        if($overtime->sub_department_id != 13 && isWhsSupport($overtime)) {
            $this->requestOvertime($overtime, 13);
        }

        $isHaveSpv = $this->Hr->getOne('employees', ['division_id' => $overtime->division_id], '*', ['rank_id' => ['5', '6']]);
        $isHaveSpvPLT = $this->Hr->getOne('employee_ranks', ['division_id' => $overtime->division_id, 'status' => 'ACTIVE'], '*', ['rank_id' => ['5', '6']]);
        if ($overtime->division_id == 0 || (!$isHaveSpv && !$isHaveSpvPLT)) {
            $data['apv_spv'] = 'BY PASS';
            $data['apv_spv_nip'] = '-';
            $data['apv_spv_date'] = $overtime->start_date;
        } else {
            if ($overtime->apv_spv_nip == '' && !$sendEmail) {
                if ($isHaveSpv) {
                    $this->ovtlib->sendEmailAppv($isHaveSpv->email, 'Supervisor', 'spv', $overtime, $post->taskId);
                } else if ($isHaveSpvPLT) {
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
            $data['apv_asman_date'] = $overtime->start_date;
        } else {
            if ($overtime->apv_asman_nip == '' && !$sendEmail) {
                if ($isHaveAsman) {
                    $this->ovtlib->sendEmailAppv($isHaveAsman->email, 'ASMAN', 'asman', $overtime, $post->taskId);
                } else if ($isHaveAsmanPLT) {
                    $email = $this->Hr->getDataById('employees', $isHaveAsmanPLT->emp_id)->email;
                    $this->ovtlib->sendEmailAppv($email, 'ASMAN', 'asman', $overtime, $post->taskId);
                }
                $sendEmail = true;
            }
        }

        $isHavePPIC = $this->Hr->getOne('employees', ['sub_department_id' => 9], '*', ['rank_id' => ['3', '4']]);
        $isHavePPICPLT = $this->Hr->getOne('employee_ranks', ['sub_department_id' => 9, 'status' => 'ACTIVE'], '*', ['rank_id' => ['3', '4']]);
        if ($overtime->sub_department_id == 0 || ($overtime->sub_department_id != 1 && $overtime->sub_department_id != 2 && $overtime->sub_department_id != 3 && $overtime->sub_department_id != 4 && $overtime->sub_department_id != 13) || (!$isHavePPIC && !$isHavePPICPLT)) {
            $data['apv_ppic'] = 'BY PASS';
            $data['apv_ppic_nip'] = '-';
            $data['apv_ppic_date'] = $overtime->start_date;
        } else {
            if ($overtime->apv_ppic_nip == '' && !$sendEmail) {
                if ($isHavePPIC) {
                    $this->ovtlib->sendEmailAppv($isHavePPIC->email, 'PPIC', 'ppic', $overtime, $post->taskId);
                } else if ($isHavePPICPLT) {
                    $email = $this->Hr->getDataById('employees', $isHavePPICPLT->emp_id)->email;
                    $this->ovtlib->sendEmailAppv($email, 'PPIC', 'ppic', $overtime, $post->taskId);
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
                $data['apv_mgr_date'] = $overtime->start_date;
            }
        } else if (!$isHaveMgr && !$isHaveMgrPLT) {
            $data['apv_mgr'] = 'BY PASS';
            $data['apv_mgr_nip'] = '-';
            $data['apv_mgr_date'] = $overtime->start_date;
        } else {
            if ($overtime->apv_mgr_nip == '' && !$sendEmail) {
                if ($isHaveAsman) {
                    $this->ovtlib->sendEmailAppv($isHaveMgr->email, 'Manager', 'mgr', $overtime, $post->taskId);
                } else if ($isHaveMgrPLT) {
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
        $this->Hr->update('employee_overtimes_ref', ['task_id_support' => ''], ['task_id_support' => $post->taskId]);
        $this->Hr->update('employee_overtimes', ['status' => 'CANCELED', 'updated_by' => empId(), 'updated_at' => date('Y-m-d H:i:s')], ['task_id' => $post->taskId]);
        $this->Hr->update('employee_overtimes_detail', ['status' => 'CANCELED', 'updated_by' => empId(), 'updated_at' => date('Y-m-d H:i:s')], ['task_id' => $post->taskId]);
        response(['status' => 'success', 'message' => 'Lemburan berhasil di batalkan']);
    }

    public function cancelOvertimeMtn()
    {
        $post = fileGetContent();
        $this->Hr->update('employee_overtimes_ref', ['task_id_support' => ''], ['task_id_support' => $post->taskId]);
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
            $makan = $overtime->makan > 0 ? '???' : '-';
            $steam = $overtime->steam > 0 ? '???' : '-';
            $ahu = $overtime->ahu > 0 ? '???' : '-';
            $compressor = $overtime->compressor > 0 ? '???' : '-';
            $pw = $overtime->pw > 0 ? '???' : '-';
            $jemputan = $overtime->jemputan > 0 ? '???' : '-';
            $dust_collector = $overtime->dust_collector > 0 ? '???' : '-';
            $wfi = $overtime->wfi > 0 ? '???' : '-';
            $mechanic = $overtime->mechanic > 0 ? '???' : '-';
            $electric = $overtime->electric > 0 ? '???' : '-';
            $hnn = $overtime->hnn > 0 ? '???' : '-';
            $qc = $overtime->qc > 0 ? '???' : '-';
            $qa = $overtime->qa > 0 ? '???' : '-';
            $penandaan = $overtime->penandaan > 0 ? '???' : '-';
            $gbk = $overtime->gbk > 0 ? '???' : '-';
            $gbk = $overtime->gbk > 0 ? '???' : '-';

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
            if ($overtime->apv_head_nip && $overtime->apv_head_nip != '-') {
                $headAppv = "$overtime->apv_head By $overtime->head @" . toIndoDateTime3($overtime->apv_head_date);
            }

            $mgrAppv = '-';
            if ($overtime->apv_mgr_nip && $overtime->apv_mgr_nip != '-') {
                $mgrAppv = "$overtime->apv_mgr By $overtime->mgr @" . toIndoDateTime3($overtime->apv_mgr_date);
            } else if ($overtime->apv_mgr_nip && $overtime->apv_mgr_nip === '-') {
                $mgrAppv = "$overtime->apv_mgr By Sistem @" . toIndoDateTime3($overtime->apv_mgr_date);

            }

            $asmanAppv = '-';
            if ($overtime->apv_asman_nip && $overtime->apv_asman_nip != '-') {
                $asmanAppv = "$overtime->apv_asman By $overtime->asman @" . toIndoDateTime3($overtime->apv_asman_date);
            } else if ($overtime->apv_asman_nip && $overtime->apv_asman_nip === '-') {
                $asmanAppv = "$overtime->apv_asman By Sistem @" . toIndoDateTime3($overtime->apv_asman_date);
            }

            $ppicAppv = '-';
            if ($overtime->apv_ppic_nip && $overtime->apv_ppic_nip != '-') {
                $ppicAppv = "$overtime->apv_ppic By $overtime->ppic @" . toIndoDateTime3($overtime->apv_ppic_date);
            } else if ($overtime->apv_ppic_nip && $overtime->apv_ppic_nip === '-') {
                $ppicAppv = "$overtime->apv_ppic By Sistem @" . toIndoDateTime3($overtime->apv_ppic_date);
            }

            $spvAppv = '-';
            if ($overtime->apv_spv_nip && $overtime->apv_spv_nip != '-') {
                $spvAppv = "$overtime->apv_spv By $overtime->spv @" . toIndoDateTime3($overtime->apv_spv_date);
            } else if ($overtime->apv_spv_nip && $overtime->apv_spv_nip === '-') {
                $spvAppv = "$overtime->apv_spv By Sistem @" . toIndoDateTime3($overtime->apv_spv_date);
            }

            $isSpvBySys = $overtime->apv_mgr_nip && $overtime->apv_mgr_nip === '-';
            $isAsmanBySys = $overtime->apv_asman_nip && $overtime->apv_asman_nip === '-';
            $isPPICBySys = $overtime->apv_ppic_nip && $overtime->apv_ppic_nip === '-';
            $isMgrBySys = $overtime->apv_mgr_nip && $overtime->apv_mgr_nip === '-';

            if ($overtime->apv_mgr_nip) {
                if (!$isMgrBySys) {
                    $appvStatus = "bgColor='#d968b1'"; //mgr
                } else {
                    if ($overtime->sub_department_id == 1 || $overtime->sub_department_id == 2 || $overtime->sub_department_id == 3 || $overtime->sub_department_id == 13) {
                        if ($overtime->apv_ppic_nip) {
                            $appvStatus = "bgColor='#d968b1'"; //mgr
                        } else if ($overtime->apv_asman_nip) {
                            $appvStatus = "bgColor='#db8a10'"; //asman
                        } else if ($overtime->apv_spv_nip) {
                            $appvStatus = "bgColor='#cedb10'"; //spv
                        }
                    } else {
                        if ($overtime->apv_asman_nip) {
                            $appvStatus = "bgColor='#d968b1'"; //asman
                        } else if ($overtime->apv_spv_nip) {
                            $appvStatus = "bgColor='#cedb10'"; //spv
                        }
                    }
                }
            } else if ($overtime->apv_ppic_nip) {
                if (!$isPPICBySys) {
                    $appvStatus = "bgColor='#1fb3a5'"; //ppic
                } else {
                    if ($overtime->apv_asman_nip) {
                        $appvStatus = "bgColor='#1fb3a5'"; //asman
                    } else if ($overtime->apv_spv_nip) {
                        $appvStatus = "bgColor='#cedb10'"; //spv
                    }
                }
            } else if ($overtime->apv_asman_nip) {
                if (!$isAsmanBySys) {
                    $appvStatus = "bgColor='#db8a10'"; //asman
                } else {
                    if ($overtime->apv_spv_nip) {
                        $appvStatus = "bgColor='#db8a10'"; //asman
                    }
                }
            } else if ($overtime->apv_spv_nip) {
                $appvStatus = "bgColor='#cedb10'"; //spv
            }

            $changeTime = $overtime->change_time != '' ? $overtime->change_time : '-';
            $rejectionNote = $overtime->rejection_note != '' ? $overtime->rejection_note : '-';

            if($overtime->apv_head_date != '0000-00-00 00:00:00') {
                if ($overtime->status_day === 'Hari Libur') {
                    $appvStatus = "bgColor='#efd898'";
                } else if ($overtime->status_day === 'Libur Nasional') {
                    $appvStatus = "bgColor='#7ecbf1'";
                } else {
                    $appvStatus = null;
                }
            }

            $xml .= "<row id='$overtime->id'>";
            $xml .= "<cell $appvStatus>" . cleanSC($no) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->task_id) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->department) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->sub_department) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->division) . "</cell>";
            $xml .= "<cell $color>" . cleanSC("$overtime->personil Orang") . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->status_day) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateDay($overtime->overtime_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->start_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->end_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->notes) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($makan) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($steam) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($ahu) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($compressor) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($pw) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($jemputan) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($dust_collector) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($wfi) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($mechanic) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($electric) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($hnn) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($qc) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($qa) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($penandaan) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($gbk) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($gbk) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->status) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($spvAppv) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($asmanAppv) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($ppicAppv) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($mgrAppv) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($headAppv) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($changeTime) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rejectionNote) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp1) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp2) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime($overtime->created_at)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->apv_spv_nip) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->apv_asman_nip) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->apv_ppic_nip) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->apv_mgr_nip) . "</cell>";
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

        $requestor = $this->Hr->getDataById('employees', empId());
        $overtime = $this->Overtime->getOvertime(['equal_task_id' => $post->taskId])->row();
        $emp = $this->Hr->getDataById('employees', $overtime->created_by);
        $message = $this->load->view('html/overtime/email/back_to_admin', [
            'overtime' => $overtime, 'revisionNote' => $post->revisionNote, 
            'emp' => $emp, 'requestor' => $requestor, 'location' => locName()], true);
        $data = [
            'alert_name' => 'OVERTIME_REVISION_REQUEST',
            'email_to' => $emp->email,
            'subject' => "Request Revisi Lembur (Task ID: $overtime->task_id) From ",
            'subject_name' => "Spekta Alert: Request Revisi Lembur (Task ID: $overtime->task_id)",
            'message' => $message,
        ];
        $insert = $this->Main->create('email', $data);

        response(['status' => 'success', 'message' => 'Lemburan berhasil di kembalikan ke Admin Lembur']);
    }

    public function rejectOvertime()
    {
        $post = fileGetContent();
        $rankId = $this->auth->rankId;
        $subId = $this->auth->subId;

        if ($this->auth->role === "admin" && $rankId > 6) {
            response(['error' => 'success', 'message' => 'Silahkan ganti privilage anda menjadi PIC lemburan!']);
        }

        $overtime = $this->Overtime->getOvertime(['equal_task_id' => $post->taskId])->row();
        if ($rankId == 5 || $rankId == 6) {
            $columnApv = 'apv_spv';
            $columnApvNip = 'apv_spv_nip';
            $columnApvDate = 'apv_spv_date';
        } else if ($rankId == 3 || $rankId == 4) {
            if ($subId == 9 && ($overtime->sub_department_id == 1 || $overtime->sub_department_id == 2 || $overtime->sub_department_id == 3 || $overtime->sub_department_id == 13)) {
                $columnApv = 'apv_ppic';
                $columnApvNip = 'apv_ppic_nip';
                $columnApvDate = 'apv_ppic_date';
            } else {
                $columnApv = 'apv_asman';
                $columnApvNip = 'apv_asman_nip';
                $columnApvDate = 'apv_asman_date';
            }
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

        if ($columnApv == 'apv_spv') {
            $this->ovtlib->sendEmailReject('Supervisor', 'spv', $overtime, $post->taskId);
        } else if ($columnApv == 'apv_asman') {
            $this->ovtlib->sendEmailReject('ASMAN', 'asman', $overtime, $post->taskId);
        } else if ($columnApv == 'apv_ppic') {
            $this->ovtlib->sendEmailReject('PPIC', 'ppic', $overtime, $post->taskId);
        } else if ($columnApv == 'apv_mgr') {
            $this->ovtlib->sendEmailReject('Manager', 'mgr', $overtime, $post->taskId);
        } else if ($columnApv == 'apv_head') {
            $this->ovtlib->sendEmailReject('Plant Manager', 'head', $overtime, $post->taskId);
        }
        response(['status' => 'success', 'message' => 'Lemburan berhasil di batalkan']);
    }

    public function approvePersonilOvertime()
    {
        $post = fileGetContent();
        $data = ['apv_spv' => 'APPROVED', 'apv_spv_nip' => empNip(), 'apv_spv_date' => date('Y-m-d H:i:s')];
        $this->Hr->update('employee_overtimes_detail', $data, ['emp_task_id' => $post->empTaskId]);
        response(['status' => 'success', 'message' => 'Lemburan berhasil di approve']);
    }

    public function rejectPersonilOvertime()
    {
        $post = fileGetContent();
        if(empRank() == 5 || empRank() == 6 || pltRankId() == 5 || pltRankId() == 6) {
            $data = ['status' => 'REJECTED', 'status_by' => empNip(), 'apv_spv' => 'REJECTED', 'apv_spv_nip' => empNip(), 'apv_spv_date' => date('Y-m-d H:i:s')];
        } else {
            $data = ['status' => 'REJECTED', 'status_by' => empNip()];
        }
        $this->Hr->update('employee_overtimes_detail', $data, ['emp_task_id' => $post->empTaskId]);
        response(['status' => 'success', 'message' => 'Lemburan berhasil di batalkan']);
    }

    public function updatePersonilNeeded()
    {
        $post = getPost();
        $totalPersonil = $this->Hr->countWhere('employee_overtimes_detail', ['task_id' => $post['task_id']], ['status' => ['CREATED', 'PROCESS']]);
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

        if(empRank() == 5 || empRank() == 6 || pltRankId() == 5 || pltRankId() == 6) {
            $data = ['status' => 'PROCESS', 'status_by' => empNip(), 'apv_spv' => 'CREATED', 'apv_spv_nip' => empNip(), 'apv_spv_date' => date('Y-m-d H:i:s')];
        } else {
            $data = ['status' => 'PROCESS', 'status_by' => empNip()];
        }

        $this->Hr->updateById('employee_overtimes_detail', $data, $emp->id);
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

        $dateStart = new DateTime($startDate);
        $dateEnd = new DateTime($endDate);
        if ($dateEnd <= $dateStart) {
            $endDate = addDayToDate($endDate, 1);
        } else if($dateEnd > $dateStart) {
            $currStart = date('Y-m-d', strtotime($startDate));
            $currEnd = date('Y-m-d', strtotime($endDate));
            if($currStart != $currEnd) {
                if(clockToFloat(getTime($endDate)) > clockToFloat(getTime($startDate))) {
                    $endDate = backDayToDate($endDate, 1);
                }
            }
        }

        $checkEnd = $this->Hr->getWhere('employee_overtimes_detail',
            ['task_id' => $overtime->task_id, 'end_date >' => $endDate],
            'task_id', null, null, null, ['status' => ['CANCELED']]
        )->row();
        if ($checkEnd) {
            xmlResponse('error', 'Ada waktu lembur karyawan yang lebih besar dari ' . toIndoDateTime($endDate) . ', silahkan cek kembali!');
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
        $startDate = genOvtDate(date('Y-m-d', strtotime(doToMysqlDate($post['labelStartDetail']))), $post['start_date']);
        $endDate = genOvtDate(date('Y-m-d', strtotime(doToMysqlDate($post['labelEndDetail']))), $post['end_date']);
        $checkStart = $this->Hr->getOne('employee_overtimes', ['task_id' => $overtime->task_id, 'start_date >' => $startDate]);
        if ($checkStart) {
            xmlResponse('error', "Waktu lembur awal karyawan lebih kecil dari waktu perintah lembur!");
        }

        $checkEnd = $this->Hr->getOne('employee_overtimes', ['task_id' => $overtime->task_id, 'end_date <' => $endDate]);
        if ($checkEnd) {
            xmlResponse('error', "Waktu lembur akhir karyawan lebih beasr dari waktu perintah lembur!");
        }
        
        if(new DateTime($endDate) < new DateTime($startDate)) {
            xmlResponse('error', "Waktu selesai harus lebih besar dari waktu mulai!");
        }

        $overtimeHour = totalHour($overtime->emp_id, $startDate, $endDate, $post['start_date'], $post['end_date']);
        $catPrice = 0;
        $catheringPrice = $this->General->getOne('catherings', ['status' => 'ACTIVE']);
        if ($catheringPrice) {
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
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
            'change_time' => 1,
            'overtime_date' => date('Y-m-d', strtotime($startDate))
        ], $post['id']);

        xmlResponse('updated', "Berhasil update jam lembur $overtime->emp_task_id");
    }

    public function updateOvertimeDetailNotes()
    {
        $post = getPost();
        $this->Hr->updateById('employee_overtimes_detail', ['notes' => $post['notes'], 'updated_by' => empId(), 'updated_at' => date('Y-m-d H:i:s')], $post['id']);
        xmlResponse('updated', "Berhasil update tugas lembur $post[empTask]");
    }

    public function approveOvertime()
    {
        $post = fileGetContent();
        $taskId = $post->taskId;
        $rankId = $this->auth->rankId;
        $subId = $this->auth->subId; // 1 Produksi
        $pltRankId = $this->auth->pltRankId;

        if ($this->auth->role === "admin" && $rankId > 6 && $pltRankId > 6) {
            response(['error' => 'success', 'message' => 'Silahkan ganti privilage anda menjadi PIC lemburan!']);
        }

        $overtime = $this->Overtime->getOvertime(['equal_task_id' => $post->taskId])->row();
        if ($rankId == 5 || $rankId == 6 || $pltRankId == 5 || $pltRankId == 6) {
            $columnApv = 'apv_spv';
            $columnApvNip = 'apv_spv_nip';
            $columnApvDate = 'apv_spv_date';
        } else if ($rankId == 3 || $rankId == 4 || $pltRankId == 3 || $pltRankId == 4) {
            if ($subId == 9 && ($overtime->sub_department_id == 1 || $overtime->sub_department_id == 2 || $overtime->sub_department_id == 3 || $overtime->sub_department_id == 4 || $overtime->sub_department_id == 13)) {
                $columnApv = 'apv_ppic';
                $columnApvNip = 'apv_ppic_nip';
                $columnApvDate = 'apv_ppic_date';
            } else {
                $columnApv = 'apv_asman';
                $columnApvNip = 'apv_asman_nip';
                $columnApvDate = 'apv_asman_date';
            }
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

        $currDate = date('Y-m-d H:i:s');
        $newCurrDate = new DateTime($currDate);
        $ovtStartDate = new DateTime($overtime->start_date);
        if($columnApv == 'apv_asman') {
            if($overtime->apv_ppic_nip == '-') {
                if($overtime->apv_mgr_nip == '-') {
                    $data['apv_ppic_date'] = $newCurrDate < $ovtStartDate ? $overtime->start_date : $currDate;
                    $data['apv_mgr_date'] = $newCurrDate < $ovtStartDate ? $overtime->start_date : $currDate;
                } else {
                    $data['apv_ppic_date'] = $newCurrDate < $ovtStartDate ? $overtime->start_date : $currDate;
                }
            }
        } else if($columnApv == 'apv_ppic') {
            if($overtime->apv_mgr_nip == '-') {
                $data['apv_mgr_date'] = $newCurrDate < $ovtStartDate ? $overtime->start_date : $currDate;
            }
        }

        $update = $this->Hr->update('employee_overtimes', $data, ['task_id' => $taskId]);
        if ($update) {
            if ($rankId == 1 || $pltRankId == 1) {
                $this->Hr->update('employee_overtimes_detail', ['status' => 'CLOSED', 'updated_by' => empId(), 'updated_at' => date('Y-m-d H:i:s')], ['task_id' => $taskId], null, ['status' => ['CANCELED', 'REJECTED']]);
            }
            if ($columnApv == 'apv_spv') {
                $isHaveAsman = $this->isHaveAsman($overtime, $post);
                if (!$isHaveAsman) {
                    $isHaveMgr = $this->isHaveMgr($overtime, $post);
                    if (!$isHaveMgr) {
                        $this->isHaveHead($overtime, $post);
                    }
                }
            } else if ($columnApv == 'apv_asman' || $columnApv == 'apv_ppic') {
                if ($columnApv == 'apv_asman') {
                    if ($overtime->sub_department_id == 1 || $overtime->sub_department_id == 2 || $overtime->sub_department_id == 3 || $overtime->sub_department_id == 4 || $overtime->sub_department_id == 13) {
                        $isHavePPIC = $this->isHavePPIC($overtime, $post);
                        if (!$isHavePPIC) {
                            $isHaveMgr = $this->isHaveMgr($overtime, $post);
                            if (!$isHaveMgr) {
                                $this->isHaveHead($overtime, $post);
                            }
                        }
                    } else {
                        $isHaveMgr = $this->isHaveMgr($overtime, $post);
                        if (!$isHaveMgr) {
                            $this->isHaveHead($overtime, $post);
                        }
                    }
                } else if ($columnApv == 'apv_ppic') {
                    $isHaveMgr = $this->isHaveMgr($overtime, $post);
                    if (!$isHaveMgr) {
                        $this->isHaveHead($overtime, $post);
                    }
                }
            } else if ($columnApv == 'apv_mgr') {
                $this->isHaveHead($overtime, $post);
            }
            response(['status' => 'success', 'message' => 'Approve lemburan berhasil']);
        } else {
            response(['error' => 'success', 'message' => 'Approve lemburan gagal']);
        }
    }

    public function requestOvertime($overtime, $subId)
    {
        $picEmails = $this->Main->getOne('pics', ['code' => 'overtime', 'sub_department_id' => $subId])->pic_emails;
        $tokenTaskId = simpleEncrypt($overtime->task_id);
        $linkAction = LIVE_URL . "index.php?c=PublicController&m=generateOvertime&token=$tokenTaskId";
        $tokenLink = simpleEncrypt($linkAction);
        $link = LIVE_URL . "index.php?c=PublicController&m=pinVerification&token=$tokenLink";
        $message = $this->load->view('html/overtime/email/generate_overtime', ['overtime' => $overtime, 'link' => $link, 'subId' => $subId], true);
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

    public function isHaveAsman($overtime, $post)
    {
        $isHaveAsman = $this->Hr->getOne('employees', ['sub_department_id' => $overtime->sub_department_id], '*', ['rank_id' => ['3', '4']]);
        $isHaveAsmanPLT = $this->Hr->getOne('employee_ranks', ['sub_department_id' => $overtime->sub_department_id, 'status' => 'ACTIVE'], '*', ['rank_id' => ['3', '4']]);
        if ($overtime->apv_asman_nip == '' && ($isHaveAsman || $isHaveAsmanPLT)) {
            if ($isHaveAsman) {
                $this->ovtlib->sendEmailAppv($isHaveAsman->email, 'ASMAN', 'asman', $overtime, $post->taskId);
                return true;
            } else if ($isHaveAsmanPLT) {
                $email = $this->Hr->getDataById('employees', $isHaveAsmanPLT->emp_id)->email;
                $this->ovtlib->sendEmailAppv($email, 'ASMAN', 'asman', $overtime, $post->taskId);
                return true;
            }
        } else {
            return false;
        }
    }

    public function isHavePPIC($overtime, $post)
    {
        $isHavePPIC = $this->Hr->getOne('employees', ['sub_department_id' => 9], '*', ['rank_id' => ['3', '4']]);
        $isHavePPICPLT = $this->Hr->getOne('employee_ranks', ['sub_department_id' => 9, 'status' => 'ACTIVE'], '*', ['rank_id' => ['3', '4']]);
        if ($overtime->apv_ppic_nip == '' && ($isHavePPIC || $isHavePPICPLT)) {
            if ($isHavePPIC) {
                $this->ovtlib->sendEmailAppv($isHavePPIC->email, 'PPIC', 'ppic', $overtime, $post->taskId);
                return true;
            } else if ($isHavePPICPLT) {
                $email = $this->Hr->getDataById('employees', $isHavePPIC->emp_id)->email;
                $this->ovtlib->sendEmailAppv($email, 'PPIC', 'ppic', $overtime, $post->taskId);
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
        if ($overtime->apv_mgr_nip == '' && ($isHaveMgr || $isHaveMgrPLT)) {
            if ($isHaveMgr) {
                $this->ovtlib->sendEmailAppv($isHaveMgr->email, 'Manager', 'mgr', $overtime, $post->taskId);
                return true;
            } else if ($isHaveMgrPLT) {
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
        if ($overtime->apv_head_nip == '' && ($isHaveHead || $isHaveHeadPLT)) {
            if ($isHaveHead) {
                $this->ovtlib->sendEmailAppv($isHaveHead->email, 'Plant Manager', 'head', $overtime, $post->taskId);
                return true;
            } else if ($isHaveHeadPLT) {
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
        if(isset($params['task_id'])) {
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
                '12' => $overtime->qc,
                '13' => $overtime->qa,
                '14' => $overtime->penandaan,
                '15' => $overtime->gbk,
                '16' => $overtime->gbb,
            ];
        }

        $mtn = ['3' => true, '4' => true, '5' => true, '6' => true, '7' => true, '8' => true, '9' => true, '10' => true, '11' => true];
        $qa = ['13' => true];
        $qc = ['12' => true];
        $whs = ['14' => true, '15' => true, '16' => true];
        
        $reqs = $this->HrModel->getRequirement();
        $xml = "";
        $no = 1;
        $subId = empSub();
        if ($subId == 5) {
            $support = $mtn;
        } else if ($subId == 7) { //@Sistem Mutu
            $support = $qa;
        } else if ($subId == 8) { //@Pengawasan Mutu
            $support = $qc;
        } else if ($subId == 13) {
            $support = $whs;
        } else {
            $support = array_merge($mtn, $qa, $qc, $whs);
        }

        foreach ($reqs as $req) {
            if (isset($reqOvt[$req->id]) && $reqOvt[$req->id] > 0) {
                if (array_key_exists($req->id, $support)) {
                    $xml .= "<row id='$req->id'>";
                    $xml .= "<cell>" . cleanSC($no) . "</cell>";
                    $xml .= "<cell>" . cleanSC($req->name) . "</cell>";
                    $xml .= "<cell>" . cleanSC($req->division_name) . "</cell>";
                    $xml .= "<cell>" . cleanSC($req->division_id) . "</cell>";
                    $xml .= "</row>";
                    $no++;
                }
            } else {
                if (array_key_exists($req->id, $support)) {
                    $xml .= "<row id='$req->id'>";
                    $xml .= "<cell>" . cleanSC($no) . "</cell>";
                    $xml .= "<cell>" . cleanSC($req->name) . "</cell>";
                    $xml .= "<cell>" . cleanSC($req->division_name) . "</cell>";
                    $xml .= "<cell>" . cleanSC($req->division_id) . "</cell>";
                    $xml .= "</row>";
                    $no++;
                }
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
            if ($overtime->payment_status == 'VERIFIED') {
                $ovtStatus = "bgColor='#c18cdd'";
            } else if ($overtime->payment_status == 'PENDING') {
                $ovtStatus = "bgColor='#c8e71c'";
            } else if ($overtime->overtime_review != '') {
                $ovtStatus = "bgColor='#75b175'";
            } else {
                if ($overtime->status_day === 'Hari Libur') {
                    $ovtStatus = "bgColor='#efd898'";
                } else if ($overtime->status_day === 'Libur Nasional') {
                    $ovtStatus = "bgColor='#7ecbf1'";
                }
            }

            $meal = $overtime->meal > 0 ? "??? ($overtime->total_meal x)" : '-';
            $machine1 = $overtime->machine_1 ? $overtime->machine_1 : '-';
            $machine2 = $overtime->machine_2 ? $overtime->machine_2 : '-';
            $xml .= "<row id='$overtime->id'>";
            $xml .= "<cell $ovtStatus>" . cleanSC($no) . "</cell>";
            if (isset($params['check'])) {
                $xml .= "<cell $color>0</cell>";
            }

            if(empRole() === 'admin' || empRank() <= 6 || (pltRankId() !== '-' && pltRankId() <= 6)) {
                $premiOvertime = $overtime->premi_overtime;
                $valueOvertime = $overtime->overtime_value;
                $mealOvertime = $overtime->meal;
            } else {
                $premiOvertime = 0;
                $valueOvertime = 0;
                $mealOvertime = 0;
            }

            $xml .= "<cell $color>" . cleanSC($overtime->emp_task_id) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->task_id) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->employee_name) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp_sub_name) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp_division) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->ovt_sub_name) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->ovt_division) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($machine1) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($machine2) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->requirements) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateDay($overtime->overtime_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->start_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->end_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->status_day) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->effective_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->break_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->real_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->overtime_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toNumber($premiOvertime)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toNumber($valueOvertime)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($meal) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toNumber($mealOvertime)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->status) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->overtime_review) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime($overtime->created_at)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->payment_status) . "</cell>";
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
                $overtimeValue = toNumber($ovt[$sub->name]['overtime_value']);
                $meal = toNumber($ovt[$sub->name]['meal']);
            } else {
                $effectiveHour = 0;
                $breakHour = 0;
                $realHour = 0;
                $overtimeHour = 0;
                $overtimeValue = 0;
                $meal = 0;
            }

            $xml .= "<row id='$sub->id'>";
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            if ($sub->name != '-') {
                $xml .= "<cell>" . cleanSC($sub->name) . "</cell>";
            } else {
                $xml .= "<cell>Direct To Sub Unit</cell>";
            }

            if ($sub->dept_name != '-') {
                $xml .= "<cell>" . cleanSC($sub->dept_name) . "</cell>";
            } else {
                $xml .= "<cell>-</cell>";
            }
            $xml .= "<cell>" . cleanSC($effectiveHour) . "</cell>";
            $xml .= "<cell>" . cleanSC($breakHour) . "</cell>";
            $xml .= "<cell>" . cleanSC($realHour) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtimeHour) . "</cell>";
            if(empRole() === 'admin' || empRank() <= 6 || (pltRankId() !== '-' && pltRankId() <= 6)) {
                $xml .= "<cell>" . cleanSC($overtimeValue) . "</cell>";
                $xml .= "<cell>" . cleanSC($meal) . "</cell>";
            } else {
                $xml .= "<cell>" . cleanSC(0) . "</cell>";
                $xml .= "<cell>" . cleanSC(0) . "</cell>";
            }
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
                $overtimeValue = toNumber($ovt[$div->name]['overtime_value']);
                $meal = toNumber($ovt[$div->name]['meal']);
            } else {
                $effectiveHour = 0;
                $breakHour = 0;
                $realHour = 0;
                $overtimeHour = 0;
                $overtimeValue = 0;
                $meal = 0;
            }

            $xml .= "<row id='$div->id'>";
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            if ($div->name != '-') {
                $xml .= "<cell>" . cleanSC($div->name) . "</cell>";
            } else {
                $xml .= "<cell>Direct To Bagian</cell>";
            }

            if ($div->sub_name != '-') {
                $xml .= "<cell>" . cleanSC($div->sub_name) . "</cell>";
            } else {
                $xml .= "<cell>-</cell>";
            }

            $xml .= "<cell>" . cleanSC($effectiveHour) . "</cell>";
            $xml .= "<cell>" . cleanSC($breakHour) . "</cell>";
            $xml .= "<cell>" . cleanSC($realHour) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtimeHour) . "</cell>";
            if(empRole() === 'admin' || empRank() <= 6 || (pltRankId() !== '-' && pltRankId() <= 6)) {
                $xml .= "<cell>" . cleanSC($overtimeValue) . "</cell>";
                $xml .= "<cell>" . cleanSC($meal) . "</cell>";
            } else {
                $xml .= "<cell>" . cleanSC(0) . "</cell>";
                $xml .= "<cell>" . cleanSC(0) . "</cell>";
            }
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

            $color = null;
            if ($overtime->real_hour > 60) {
                $color = "bgColor='#ED9377'";
            }

            $xml .= "<row id='$overtime->id'>";
            $xml .= "<cell $color>" . cleanSC($no) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp_name) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->div_name) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->sub_name) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->dept_name) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->effective_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->break_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->real_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->overtime_hour) . "</cell>";
            if(empRole() === 'admin' || empRank() <= 6 || (pltRankId() !== '-' && pltRankId() <= 6)) {
                $xml .= "<cell $color>" . cleanSC(toNumber($overtime->overtime_value)) . "</cell>";
                $xml .= "<cell $color>" . cleanSC(toNumber($overtime->meal)) . "</cell>";
            } else {
                $xml .= "<cell $color>" . cleanSC(toNumber(0)) . "</cell>";
                $xml .= "<cell $color>" . cleanSC(toNumber(0)) . "</cell>";
            }
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getReportOvertimeEmpGridRev()
    {
        $overtimes = $this->Overtime->getReportOvertimeEmpGridRev(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($overtimes as $overtime) {
            $xml .= "<row id='$overtime->id'>";
            $xml .= "<cell>" . cleanSC($no) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtime->emp_name) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtime->div_name) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtime->sub_name) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtime->dept_name) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtime->notes) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtime->effective_hour) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtime->break_hour) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtime->real_hour) . "</cell>";
            $xml .= "<cell>" . cleanSC($overtime->overtime_hour) . "</cell>";
            $xml .= "<cell>" . cleanSC(toNumber($overtime->overtime_value)) . "</cell>";
            $xml .= "<cell>" . cleanSC(toNumber($overtime->meal)) . "</cell>";
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
        $post = fileGetContent();
        $data = [];
        foreach ($post->taskIds as $key => $value) {
            $data[] = [
                'id' => $value,
                'payment_status' => 'VERIFIED',
                'payment_status_by' => empNip(),
                'updated_by' => empId(),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        $update = $this->Hr->updateMultiple('employee_overtimes_detail', $data, 'id');
        if ($update) {
            response(['status' => 'success', 'message' => 'Berhasil verifikasi lembur']);
        } else {
            response(['status' => 'error', 'message' => 'Gagal verifikasi lembur!']);
        }
    }

    public function getRequestOvertimeGrid()
    {
        $overtimes = $this->Overtime->getRequestOvertimeGrid(getParam())->result();
        $xml = "";
        $no = 1;
        foreach ($overtimes as $overtime) {
            $makan = $overtime->makan > 0 ? '???' : '-';
            $steam = $overtime->steam > 0 ? '???' : '-';
            $ahu = $overtime->ahu > 0 ? '???' : '-';
            $compressor = $overtime->compressor > 0 ? '???' : '-';
            $pw = $overtime->pw > 0 ? '???' : '-';
            $jemputan = $overtime->jemputan > 0 ? '???' : '-';
            $dust_collector = $overtime->dust_collector > 0 ? '???' : '-';
            $wfi = $overtime->wfi > 0 ? '???' : '-';
            $mechanic = $overtime->mechanic > 0 ? '???' : '-';
            $electric = $overtime->electric > 0 ? '???' : '-';
            $hnn = $overtime->hnn > 0 ? '???' : '-';
            $qc = $overtime->qc > 0 ? '???' : '-';
            $qa = $overtime->qa > 0 ? '???' : '-';
            $penandaan = $overtime->penandaan > 0 ? '???' : '-';
            $gbk = $overtime->gbk > 0 ? '???' : '-';
            $gbk = $overtime->gbk > 0 ? '???' : '-';

            $color = null;
            if ($overtime->status_day === 'Hari Libur') {
                $color = "bgColor='#efd898'";
            } else if ($overtime->status_day === 'Libur Nasional') {
                $color = "bgColor='#7ecbf1'";
            }

            $xml .= "<row id='$overtime->task_id'>";
            $xml .= "<cell $color>" . cleanSC($no) . "</cell>";
            $xml .= "<cell $color>0</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->task_id) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->task_id_support ? $overtime->task_id_support : '-') . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->department) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->sub_department) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->division) . "</cell>";
            $xml .= "<cell $color>" . cleanSC("$overtime->personil Orang") . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->status_day) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateDay($overtime->overtime_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime($overtime->start_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime($overtime->end_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->notes) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($makan) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($steam) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($ahu) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($compressor) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($pw) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($jemputan) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($dust_collector) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($wfi) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($mechanic) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($electric) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($hnn) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($qc) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($qa) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($penandaan) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($gbk) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($gbk) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp1) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime($overtime->created_at)) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
        
    }

    public function addRevisionRequest()
    {
        $post = prettyText(getPost(), ['description']);
        $date = date('Y-m-d');
        $expDate = explode('-', $date);
        $lastId = $this->Overtime->lastOt('overtime_revision_requests', 'created_at', $date);
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
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $request_id = $this->Hr->create('overtime_revision_requests', $data);

        $expTask = explode(",", $post['task_ids']);
        $dataDetail = [];
        foreach ($expTask as $taskId) {
            $dataDetail[] = [
                'task_id' => $revTaskId,
                'emp_task_id' => $taskId,
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        $reqDetail = $this->Hr->createMultiple('overtime_revision_requests_detail', $dataDetail);
        $requestor = $this->Hr->getDataById('employees', empId());
        $this->sendRevisionEmail($revTaskId, $post['task_ids'], 'OVERTIME_REVISION_REQUEST', $requestor);
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
                'task_id' => $revTaskId,
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
            if ($taskIds === '') {
                $taskIds = $rev->emp_task_id;
            } else {
                $taskIds = $taskIds . "," . $rev->emp_task_id;
            }
        }

        if ($taskIds != '') {
            $params['notin_emp_task_id'] = $taskIds;
        }

        $params['gt_overtime_date'] = backDayToDate(date('Y-m-d'), 14);

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

            $meal = $overtime->meal > 0 ? "??? ($overtime->total_meal x)" : '-';
            $machine1 = $overtime->machine_1 ? $overtime->machine_1 : '-';
            $machine2 = $overtime->machine_2 ? $overtime->machine_2 : '-';

            $xml .= "<row id='$overtime->emp_task_id'>";
            $xml .= "<cell $color>" . cleanSC($no) . "</cell>";
            $xml .= "<cell $color>0</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp_task_id) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->employee_name) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->sub_department) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->division) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($machine1) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($machine2) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->requirements) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateDay($overtime->overtime_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->start_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->end_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->status_day) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->effective_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->break_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->real_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->overtime_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toNumber($overtime->premi_overtime)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toNumber($overtime->overtime_value)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($meal) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toNumber($overtime->meal)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->notes) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->status) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime($overtime->created_at)) . "</cell>";
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
            if ($rev->status == 'PROCESS') {
                $color = "bgColor='#efd898'";
            } else if ($rev->status == 'CLOSED') {
                $color = "bgColor='#7ecb87'";
            } else if ($rev->status == 'CANCELED') {
                $color = "bgColor='#d7a878'";
            } else if ($rev->status == 'REJECTED') {
                $color = "bgColor='#c94b62'";
            }

            $xml .= "<row id='$rev->task_id'>";
            $xml .= "<cell $color>" . cleanSC($no) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->task_id) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->description) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->department) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->sub_department) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->status) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->emp1) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($rev->emp2) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime($rev->created_at)) . "</cell>";
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

            $meal = $overtime->meal > 0 ? "??? ($overtime->total_meal x)" : '-';
            $machine1 = $overtime->machine_1 ? $overtime->machine_1 : '-';
            $machine2 = $overtime->machine_2 ? $overtime->machine_2 : '-';

            $xml .= "<row id='$overtime->emp_task_id'>";
            $xml .= "<cell $color>" . cleanSC($no) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp_task_id) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->employee_name) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->sub_department) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->division) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($machine1) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($machine2) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->requirements) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateDay($overtime->overtime_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->start_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->end_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->status_day) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->effective_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->break_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->real_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->overtime_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toNumber($overtime->premi_overtime)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toNumber($overtime->overtime_value)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($meal) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toNumber($overtime->meal)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->notes) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->status) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime($overtime->created_at)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->task_start_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->task_end_date)) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function updateRevOvtDesc()
    {
        $post = prettyText(getPost(), ['description']);
        $status = $this->Hr->getOne('overtime_revision_requests', ['task_id' => $post['task_id']])->status;
        if ($status != 'CREATED') {
            xmlResponse('error', "Gagal update deskripsi, status permintaan revisi sudah $status");
        }
        $data = [
            'description' => $post['description'],
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->Hr->update('overtime_revision_requests', $data, ['task_id' => $post['task_id']]);
        xmlResponse('updated', 'Berhasil mengubah deskripsi pengajuan revisi lembur');
    }

    public function updatePrsRevOvtDesc()
    {
        $post = prettyText(getPost(), ['description']);
        $status = $this->Hr->getOne('overtime_revision_requests_personil', ['rev_task_id' => $post['rev_task_id']])->status;
        if ($status != 'CREATED') {
            xmlResponse('error', "Gagal update deskripsi, status permintaan revisi sudah $status");
        }
        $data = [
            'description' => $post['description'],
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->Hr->update('overtime_revision_requests_personil', $data, ['rev_task_id' => $post['rev_task_id']]);
        xmlResponse('updated', 'Berhasil mengubah deskripsi pengajuan revisi lembur');
    }

    public function updateRevOvtRes()
    {
        $post = prettyText(getPost(), ['response']);
        $data = [
            'response' => $post['response'],
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->Hr->update('overtime_revision_requests', $data, ['task_id' => $post['task_id']]);
        xmlResponse('updated', 'Berhasil menyimpan tanggapan pengajuan revisi lembur');
    }

    public function updateRevOvtPersonilRes()
    {
        $post = prettyText(getPost(), ['response']);
        $data = [
            'response' => $post['response'],
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->Hr->update('overtime_revision_requests_personil', $data, ['task_id' => $post['task_id']]);
        xmlResponse('updated', 'Berhasil menyimpan tanggapan pengajuan revisi lembur');
    }

    public function cancelRevOvt()
    {
        $post = fileGetContent();
        $data = [
            'status' => 'CANCELED',
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $revision = $this->Hr->getOne('overtime_revision_requests', ['task_id' => $post->taskId]);
        if ($revision->status == 'CREATED') {
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

    public function cancelRevOvtSub()
    {
        $post = fileGetContent();
        $revTaskId = $post->taskId;
        $revision = $this->Hr->getOne('overtime_revision_requests_personil', ['rev_task_id' => $revTaskId]);
        if($revision->status == 'CREATED') {
            $this->Hr->update('overtime_revision_requests_personil', [
                'status' => 'CANCELED', 
                'updated_by' => empId(), 
                'updated_at' => date('Y-m-d H:i:s')
            ], ['rev_task_id' => $revTaskId]);
            $this->Overtime->backStatusBefore($revision->task_id);
            $empOvts = $this->Hr->getWhere('employee_overtimes_detail', ['task_id' => $revision->task_id, 'status' => 'CLOSED', 'revision_status !=' => 'NONE'])->result();
            $dataHistory =[];
            foreach ($empOvts as $ovt) {
                $dataHistory[] = [
                    'rev_task_id' => $revision->rev_task_id,
                    'task_id' => $revision->task_id,
                    'emp_task_id' => $ovt->emp_task_id,
                    'status' => $ovt->status,
                    'revision_status' => $ovt->revision_status,
                    'status_before' => $ovt->status_before,
                ];
            }
            if(count($dataHistory) > 0) {
                $this->Hr->createMultiple('overtime_revision_requests_personil_history', $dataHistory);
            }
            $this->Hr->update('employee_overtimes', ['on_revision' => 0], ['task_id' => $revision->task_id]);
            $this->Hr->update('employee_overtimes_detail', ['revision_status' => 'NONE'], ['task_id' => $revision->task_id]);
            response(['status' => 'success', 'message' => 'Berhasil membatalkan pengajuan revisi lembur']);
        } else if ($revision->status == 'PROCESS') {
            response(['status' => 'error', 'message' => 'Gagal membatalkan pengajuan revisi lembur, status revisi sudah di proses oleh SDM!']);
        } else {
            response(['status' => 'error', 'message' => 'Gagal membatalkan pengajuan revisi lembur!']);
        }
    }

    public function getDescription()
    {
        $post = fileGetContent();
        $desc = $this->Hr->getOne('overtime_revision_requests', ['task_id' => $post->taskId]);
        response(['task_id' => $desc->task_id, 'description' => $desc->description]);
    }

    public function getPersonilDescription()
    {
        $post = fileGetContent();
        $desc = $this->Hr->getOne('overtime_revision_requests_personil', ['rev_task_id' => $post->taskId]);
        response(['rev_task_id' => $desc->rev_task_id, 'description' => $desc->description]);
    }

    public function getRevision()
    {
        $post = fileGetContent();
        $rev = $this->Hr->getOne('overtime_revision_requests', ['task_id' => $post->taskId]);
        response(['status' => 'success', 'revision' => $rev]);
    }

    public function getPersonilRevision()
    {
        $post = fileGetContent();
        $rev = $this->Hr->getOne('overtime_revision_requests_personil', ['rev_task_id' => $post->taskId]);
        response(['status' => 'success', 'revision' => $rev]);
    }

    public function processRevision()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $this->Hr->update('overtime_revision_requests', ['status' => 'PROCESS', 'updated_by' => empId(), 'updated_at' => date('Y-m-d H:i:s')], ['task_id' => $data->id]);
            $mSuccess .= "- $data->field berhasil diproses <br>";
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    public function processRevisionPersonil()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $this->Hr->update('overtime_revision_requests_personil', ['status' => 'PROCESS', 'updated_by' => empId(), 'updated_at' => date('Y-m-d H:i:s')], ['rev_task_id' => $data->id]);
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
            if ($revision->status == 'CREATED' || $revision->status == 'PROCESS') {
                $this->Hr->update('overtime_revision_requests', ['status' => 'REJECTED', 'updated_by' => empId(), 'updated_at' => date('Y-m-d H:i:s')], ['task_id' => $data->id]);
                $this->Hr->update('overtime_revision_requests_detail', ['status' => 'REJECTED'], ['task_id' => $data->id]);
                $mSuccess .= "- $data->field berhasil ditolak <br>";

                $empTasks = $this->Hr->getWhere('overtime_revision_requests_detail', ['task_id' => $data->id])->result();
                $empTaskIds = '';
                foreach ($empTasks as $rev) {
                    if ($empTaskIds == '') {
                        $empTaskIds = $rev->emp_task_id;
                    } else {
                        $empTaskIds = $empTaskIds . "," . $rev->emp_task_id;
                    }
                }
                $requestor = $this->Hr->getDataById('employees', $revision->created_by);
                $this->sendRevisionEmail($data->id, $empTaskIds, 'OVERTIME_REVISION_REJECTION', $requestor);
            } else {
                $mError .= "- Gagal mengubah status revisi $data->field <br>";
            }
        }

        response(['status' => 'success', 'mError' => $mError, 'mSuccess' => $mSuccess]);
    }

    public function rejectRevisionPersonil()
    {
        $post = fileGetContent();
        $mError = '';
        $mSuccess = '';
        $datas = $post->datas;
        foreach ($datas as $id => $data) {
            $revision = $this->Hr->getOne('overtime_revision_requests_personil', ['rev_task_id' => $data->id]);
            if ($revision->status == 'CREATED' || $revision->status == 'PROCESS') {
                if($revision->response != '') {
                    $this->Overtime->backStatusBefore($revision->task_id);
                    $empOvts = $this->Hr->getWhere('employee_overtimes_detail', ['task_id' => $revision->task_id, 'status' => 'CLOSED', 'revision_status !=' => 'NONE'])->result();
                    $dataHistory =[];
                    foreach ($empOvts as $ovt) {
                        $dataHistory[] = [
                            'rev_task_id' => $revision->rev_task_id,
                            'task_id' => $revision->task_id,
                            'emp_task_id' => $ovt->emp_task_id,
                            'status' => $ovt->status,
                            'revision_status' => $ovt->revision_status,
                            'status_before' => $ovt->status_before,
                        ];
                    }
                    if(count($dataHistory) > 0) {
                        $this->Hr->update('employee_overtimes', ['on_revision' => 0], ['task_id' => $revision->task_id]);
                    }
                    $this->Hr->update('overtime_revision_requests_personil', ['status' => 'REJECTED', 'updated_by' => empId(), 'updated_at' => date('Y-m-d H:i:s')], ['rev_task_id' => $data->id]);
                    $this->Hr->createMultiple('overtime_revision_requests_personil_history', $dataHistory);
                    $requestor = $this->Hr->getDataById('employees', $revision->created_by);
                    $this->sendRevisionPersonilEmail($revision, 'OVERTIME_PERSONIL_REVISION_REJECTION', $requestor);
                    $mSuccess .= "- $data->field berhasil ditolak <br>";
                } else {
                    $mError .= "- Gagal mengubah status revisi $data->field, Tanggapan SDM masih kosong! <br>";
                }
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
        $startDate = genOvtDate(date('Y-m-d', strtotime(doToMysqlDate($post['labelStartDetail']))), $post['start_date']);
        $endDate = genOvtDate(date('Y-m-d', strtotime(doToMysqlDate($post['labelEndDetail']))), $post['end_date']);

        $overtimeHour = totalHour($overtime->emp_id, $startDate, $endDate, $post['start_date'], $post['end_date']);
        $catPrice = 0;
        $catheringPrice = $this->General->getOne('catherings', ['status' => 'ACTIVE']);
        if ($catheringPrice) {
            $catPrice = $catheringPrice->price;
        }

        if ($startDate > $endDate || $startDate == $endDate) {
            xmlResponse('error', "Waktu selesai harus lebih besar dari waktu mulai!");
        }

        if (countHour($startDate, $endDate, 'h') > 24) {
            xmlResponse('error', 'Maksimum jam lembur adalah 18 jam!');
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
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
            'change_time' => 1,
            'overtime_date' => date('Y-m-d', strtotime($startDate))
        ], ['emp_task_id' => $post['task_id']]);

        xmlResponse('updated', "Berhasil update jam lembur $overtime->emp_task_id");
    }

    public function closeRevision()
    {
        $post = fileGetContent();
        $revision = $this->Hr->getOne("overtime_revision_requests", ['task_id' => $post->taskId]);
        if(!$revision->response) {
            return response(['error' => 'success', 'message' => 'Tanggapan SDM masih kosong!']);
        }
        $this->Hr->update('overtime_revision_requests', ['status' => 'CLOSED', 'updated_by' => empId(), 'updated_at' => date('Y-m-d H:i:s')], ['task_id' => $post->taskId]);
        $this->Hr->update('overtime_revision_requests_detail', ['status' => 'CLOSED'], ['task_id' => $post->taskId]);
        $empTasks = $this->Hr->getWhere('overtime_revision_requests_detail', ['task_id' => $post->taskId])->result();
        $empTaskIds = '';
        foreach ($empTasks as $rev) {
            if ($empTaskIds == '') {
                $empTaskIds = $rev->emp_task_id;
            } else {
                $empTaskIds = $empTaskIds . "," . $rev->emp_task_id;
            }
        }
        $requestor = $this->Hr->getDataById('employees', $revision->created_by);
        $this->sendRevisionEmail($post->taskId, $empTaskIds, 'OVERTIME_REVISION_CLOSED', $requestor);
        response(['status' => 'success', 'message' => 'Berhasil menutup revisi!']);
    }

    public function closeRevisionPersonil()
    {
        $post = fileGetContent();
        $revision = $this->Hr->getOne("overtime_revision_requests_personil", ['rev_task_id' => $post->taskId]);
        if(!$revision->response) {
            return response(['error' => 'success', 'message' => 'Tanggapan SDM masih kosong!']);
        }

        $empOvts = $this->Hr->getWhere('employee_overtimes_detail', ['task_id' => $revision->task_id, 'revision_status !=' => 'NONE'])->result();
        $dataHistory =[];
        foreach ($empOvts as $ovt) {
            $dataHistory[] = [
                'rev_task_id' => $revision->rev_task_id,
                'task_id' => $revision->task_id,
                'emp_task_id' => $ovt->emp_task_id,
                'status' => $ovt->status,
                'revision_status' => $ovt->revision_status,
                'status_before' => $ovt->status_before,
            ];
        }
        if(count($dataHistory) > 0) {
            $this->Hr->createMultiple('overtime_revision_requests_personil_history', $dataHistory);
        }
        $this->Hr->update('overtime_revision_requests_personil', ['status' => 'CLOSED', 'updated_by' => empId(), 'updated_at' => date('Y-m-d H:i:s')], ['rev_task_id' => $post->taskId]);
        $requestor = $this->Hr->getDataById('employees', $revision->created_by);
        $this->sendRevisionPersonilEmail($revision, 'OVERTIME_PERSONIL_REVISION_CLOSED', $requestor);
        response(['status' => 'success', 'message' => 'Berhasil menutup revisi!']);
    }


    public function sendRevisionEmail($taskId, $empTaskIds, $alertName, $requestor)
    {
        $email = $requestor->email;
        $sdms = $this->Hr->getWhere('employees', ['division_id' => 38])->result();
        foreach ($sdms as $sdm) {
            $email = $email . ',' . $sdm->email;
        }
        $overtimes = $this->Overtime->getOvertimeDetail(['in_emp_task_id' => $empTaskIds])->result();
        $revision = $this->Overtime->getRevOvtGrid(['in_task_id' => $taskId])->row();
        $message = $this->load->view('html/overtime/email/revision_overtime', [
            'requestor' => $requestor,
            'overtimes' => $overtimes,
            'revision' => $revision,
            'location' => $this->auth->locName,
            'status' => $alertName,
        ], true);

        $prefix = 'Request';
        if ($alertName == 'OVERTIME_REVISION_REJECTION') {
            $prefix = 'Rejection';
        } else if ($alertName == 'OVERTIME_REVISION_CLOSED') {
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

    public function sendRevisionPersonilEmail($revision, $alertName, $requestor)
    {
        $email = $requestor->email;
        $sdms = $this->Hr->getWhere('employees', ['division_id' => 38])->result();
        foreach ($sdms as $sdm) {
            $email = $email . ',' . $sdm->email;
        }
        
        $fullRev = $this->Overtime->getRevPersonil($revision->rev_task_id);
        $overtimes = $this->Overtime->getRevPersonilOvertime($revision->rev_task_id)->result();
        $message = $this->load->view('html/overtime/email/revision_overtime_personil_sdm', [
            'requestor' => $requestor,
            'revision' => $fullRev,
            'overtimes' => $overtimes,
            'location' => $this->auth->locName,
            'status' => $alertName,
        ], true);

        $prefix = 'Request';
        if ($alertName == 'OVERTIME_PERSONIL_REVISION_REJECTION') {
            $prefix = 'Rejection';
        } else if ($alertName == 'OVERTIME_PERSONIL_REVISION_CLOSED') {
            $prefix = 'Closed';
        }
        $data = [
            'alert_name' => $alertName,
            'email_to' => $email,
            'subject' => "$prefix Revisi Personil Lembur $fullRev->department (Task ID: $revision->rev_task_id)",
            'subject_name' => "Spekta Alert: $prefix Revisi Personil Lembur $fullRev->department (Task ID: $revision->rev_task_id)",
            'message' => $message,
        ];
        $insert = $this->Main->create('email', $data);
    }

    public function viewAttachment()
    {
        $post = fileGetContent();
        $rev = $this->Hr->getOne('overtime_revision_requests', ['task_id' => $post->taskId]);
        if ($rev->filename) {
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
            'template' => $template,
        ]);
    }

    public function getOvt7Day()
    {
        $params = getParam();
        $overtimes = $this->Overtime->getOvt7Day($params)->result();
        $xml = "";
        $no = 1;
        foreach ($overtimes as $overtime) {
            $color = null;
            if ($overtime->status_day === 'Hari Libur') {
                $color = "bgColor='#efd898'";
            } else if ($overtime->status_day === 'Libur Nasional') {
                $color = "bgColor='#7ecbf1'";
            }

            $xml .= "<row id='$overtime->task_id'>";
            $xml .= "<cell $color>" . cleanSC($no) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->task_id) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->department) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->sub_department) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->division) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->personil) . " Orang</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->status_day) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateDay($overtime->overtime_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->start_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->end_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->notes) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp1) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp2) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime($overtime->created_at)) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function getOvertimeDetailGridRev()
    {
        $params = getParam();
        $overtimes = $this->Overtime->getOvertimeDetail($params)->result();
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
            if ($overtime->change_time == 1) {
                $status_updater = 'Revisi Jam Lembur By ' . $overtime->status_updater;
            }

            if($overtime->revision_status == "CANCELED") {
                $color = "bgColor='#ed9a9a'";
            } else if($overtime->revision_status == "CLOSED") {
                $color = "bgColor='#75b175'";
            }

            $statusColor = $color;
            if(isset($params['check'])) {
               if($overtime->status == $overtime->revision_status) {
                $statusColor = "bgColor='#df6be1'";
               } else {
                $statusColor = "bgColor='#ccc'";
               }
            }

            $machine1 = $overtime->machine_1 ? $overtime->machine_1 : '-';
            $machine2 = $overtime->machine_2 ? $overtime->machine_2 : '-';
            $meal = $overtime->meal > 0 ? "??? ($overtime->total_meal x)" : '-';

            $isRevision = $this->Hr->getOne('overtime_revision_requests_detail', ['emp_task_id' => $overtime->emp_task_id], 'emp_task_id', ['status' => ['CREATED', 'PROCESS']]);
            if(!$isRevision && $overtime->payment_status != 'VERIFIED' || ($overtime->revision_status != 'NONE' && $overtime->revision_status != 'CLOSED')) {
                $xml .= "<row id='$overtime->id'>";
                $xml .= "<cell $statusColor>" . cleanSC($no) . "</cell>";
                if(isset($params['check'])) {
                    $xml .= "<cell $color>0</cell>";
                    $xml .= "<cell $color>0</cell>";
                }
                $xml .= "<cell $color>" . cleanSC($overtime->status) . "</cell>";
                $xml .= "<cell $color>" . cleanSC($overtime->revision_status) . "</cell>";
                $xml .= "<cell $color>" . cleanSC($overtime->status_before) . "</cell>";
                $xml .= "<cell $color>" . cleanSC($overtime->emp_task_id) . "</cell>";
                $xml .= "<cell $color>" . cleanSC($overtime->employee_name) . "</cell>";
                $xml .= "<cell $color>" . cleanSC($overtime->department) . "</cell>";
                $xml .= "<cell $color>" . cleanSC($overtime->sub_department) . "</cell>";
                $xml .= "<cell $color>" . cleanSC($overtime->division) . "</cell>";
                $xml .= "<cell $color>" . cleanSC($machine1) . "</cell>";
                $xml .= "<cell $color>" . cleanSC($machine2) . "</cell>";
                $xml .= "<cell $color>" . cleanSC($overtime->requirements) . "</cell>";
                $xml .= "<cell $color>" . cleanSC(toIndoDateDay($overtime->overtime_date)) . "</cell>";
                $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->start_date)) . "</cell>";
                $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->end_date)) . "</cell>";
                $xml .= "<cell $color>" . cleanSC($overtime->status_day) . "</cell>";
                $xml .= "<cell $color>" . cleanSC($overtime->effective_hour) . "</cell>";
                $xml .= "<cell $color>" . cleanSC($overtime->break_hour) . "</cell>";
                $xml .= "<cell $color>" . cleanSC($overtime->real_hour) . "</cell>";
                $xml .= "<cell $color>" . cleanSC($overtime->overtime_hour) . "</cell>";
                if(empRole() === 'admin' || empRank() <= 6 || (pltRankId() !== '-' && pltRankId() <= 6)) {
                    $xml .= "<cell $color>" . cleanSC(toNumber($overtime->premi_overtime)) . "</cell>";
                    $xml .= "<cell $color>" . cleanSC(toNumber($overtime->overtime_value)) . "</cell>";
                } else {
                    $xml .= "<cell $color>" . cleanSC(toNumber(0)) . "</cell>";
                    $xml .= "<cell $color>" . cleanSC(toNumber(0)) . "</cell>";
                }
                $xml .= "<cell $color>" . cleanSC($meal) . "</cell>";
                $xml .= "<cell $color>" . cleanSC($overtime->notes) . "</cell>";
                $xml .= "<cell $color>" . cleanSC($overtime->status) . "</cell>";
                $xml .= "<cell $color>" . cleanSC($status_updater) . "</cell>";
                $xml .= "<cell $color>" . cleanSC($overtime->emp1) . "</cell>";
                $xml .= "<cell $color>" . cleanSC($overtime->emp2) . "</cell>";
                $xml .= "<cell $color>" . cleanSC(toIndoDateTime($overtime->created_at)) . "</cell>";
                $xml .= "<cell $color>" . cleanSC($overtime->emp_id) . "</cell>";
                $xml .= "</row>";
                $no++;
            }
        }
        gridXmlHeader($xml);
    }

    public function getOvertimeDetailGridRevHistory()
    {
        $params = getParam();
        $overtimes = $this->Overtime->getOvertimeDetailHistory($params)->result();
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
            if ($overtime->change_time == 1) {
                $status_updater = 'Revisi Jam Lembur By ' . $overtime->status_updater;
            }

            if($overtime->his_rev_status == "CANCELED") {
                $color = "bgColor='#ed9a9a'";
            } else if($overtime->his_rev_status == "CLOSED") {
                $color = "bgColor='#75b175'";
            }

            $statusColor = $color;
            if(isset($params['check'])) {
               if($overtime->status == $overtime->revision_status) {
                $statusColor = "bgColor='#df6be1'";
               } else {
                $statusColor = "bgColor='#ccc'";
               }
            }

            $machine1 = $overtime->machine_1 ? $overtime->machine_1 : '-';
            $machine2 = $overtime->machine_2 ? $overtime->machine_2 : '-';
            $meal = $overtime->meal > 0 ? "??? ($overtime->total_meal x)" : '-';
            $xml .= "<row id='$overtime->id'>";
            $xml .= "<cell $statusColor>" . cleanSC($no) . "</cell>";
            if(isset($params['check'])) {
                $xml .= "<cell $color>0</cell>";
                $xml .= "<cell $color>0</cell>";
            }
            $xml .= "<cell $color>" . cleanSC($overtime->his_status) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->his_rev_status) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->his_status_before) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp_task_id) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->employee_name) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->department) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->sub_department) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->division) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($machine1) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($machine2) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->requirements) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateDay($overtime->overtime_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->start_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->end_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->status_day) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->effective_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->break_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->real_hour) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->overtime_hour) . "</cell>";
            if(empRole() === 'admin' || empRank() <= 6 || (pltRankId() !== '-' && pltRankId() <= 6)) {
                $xml .= "<cell $color>" . cleanSC(toNumber($overtime->premi_overtime)) . "</cell>";
                $xml .= "<cell $color>" . cleanSC(toNumber($overtime->overtime_value)) . "</cell>";
            } else {
                $xml .= "<cell $color>" . cleanSC(toNumber(0)) . "</cell>";
                $xml .= "<cell $color>" . cleanSC(toNumber(0)) . "</cell>";
            }
            $xml .= "<cell $color>" . cleanSC($meal) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->notes) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->status) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($status_updater) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp1) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp2) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime($overtime->created_at)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp_id) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }


    public function cancelOvtRev()
    {
        $post = fileGetContent();
        $taskId = $post->taskId;
        $data = [
            'revision_status' => 'CANCELED',
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $update = $this->Hr->update('employee_overtimes_detail', $data, ['emp_task_id' => $taskId]);
        if($update) {
            response(['status' => 'success', 'message' => 'Update personil lembur berhasil']);
        }
    }

    public function rollbackOvtRev()
    {
        $post = fileGetContent();
        $taskId = $post->taskId;
        $overtime = $this->Hr->getOne('employee_overtimes_detail', ['emp_task_id' => $taskId]);
        $data = [
            'status' => $overtime->status == 'ADD' ? 'ADD' : $overtime->status_before,
            'revision_status' => $overtime->status == 'ADD' ? 'CLOSED' : 'NONE',
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $update = $this->Hr->update('employee_overtimes_detail', $data, ['emp_task_id' => $taskId]);
        if($update) {
            response(['status' => 'success', 'message' => 'Update personil lembur berhasil']);
        }
    }

    public function createRevisionPersonil()
    {
        $post = getPost();
        $date = date('Y-m-d');
        $expDate = explode('-', $date);
        $lastId = $this->Overtime->lastOt('overtime_revision_requests_personil', 'created_at', $date);
        $revTaskId = sprintf('%03d', $lastId + 1) . '/OT-REV-P/' . empLoc() . '/' . toRomawi($expDate[1]) . '/' . $expDate[0];
        $data = [
            'location' => empLoc(),
            'rev_task_id' => $revTaskId,
            'task_id' => $post['task_id'],
            'description' => $post['description'],
            'status' => 'CREATED',
            'created_by' => empId(),
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $insert = $this->Hr->create('overtime_revision_requests_personil', $data);
        if($insert) {
            $this->Hr->update('employee_overtimes', ['on_revision' => 1], ['task_id' => $post['task_id']]);
            $email = '';
            $sdms = $this->Hr->getWhere('employees', ['division_id' => 38])->result();
            foreach ($sdms as $sdm) {
                if ($email == '') {
                    $email = $sdm->email;
                } else {
                    $email = $email . ',' . $sdm->email;
                }
            }
        
            $overtime = $this->Overtime->getOvertime(['equal_task_id' => $post['task_id']])->row();
            $overtimeDetail = $this->Overtime->getOvertimeDetail(['equal_task_id' => $post['task_id'], 'notequal_revision_status' => ''])->result();
            $message = $this->load->view('html/overtime/email/revision_overtime_personil', [
                'overtime' => $overtime, 
                'overtimeDetail' => $overtimeDetail, 
                'revision' => $data, 
                'location' => locName()], true);
            $dataEmail = [
                'alert_name' => 'OVERTIME_REVISION_PERSONIL_REQUEST',
                'email_to' => $email,
                'subject' => "Request Revisi Personil Lembur (Task ID: $overtime->task_id) From ",
                'subject_name' => "Spekta Alert: Request Revisi Personil Lembur (Task ID: $overtime->task_id)",
                'message' => $message,
            ];
            $this->Main->create('email', $dataEmail);
            xmlResponse('inserted', 'Berhasil membuat form pengajuan revisi lembur');
        }
    }

    public function getRevOvtPersonil()
    {
        $params = getParam();
        $overtimes = $this->Overtime->getRevOvtPersonil($params)->result();
        $xml = "";
        $no = 1;
        foreach ($overtimes as $overtime) {
            $color = null;
            if ($overtime->rev_status == 'PROCESS') {
                $color = "bgColor='#efd898'";
            } else if ($overtime->rev_status == 'CLOSED') {
                $color = "bgColor='#7ecb87'";
            } else if ($overtime->rev_status == 'CANCELED') {
                $color = "bgColor='#d7a878'";
            } else if ($overtime->rev_status == 'REJECTED') {
                $color = "bgColor='#c94b62'";
            }

            $xml .= "<row id='$overtime->rev_task_id'>";
            $xml .= "<cell $color>" . cleanSC($no) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->rev_task_id) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->task_id) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->department) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->sub_department) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->division) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->personil) . " Orang</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->status_day) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateDay($overtime->overtime_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->start_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime2($overtime->end_date)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->notes) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp1) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->emp2) . "</cell>";
            $xml .= "<cell $color>" . cleanSC(toIndoDateTime($overtime->created_at)) . "</cell>";
            $xml .= "<cell $color>" . cleanSC($overtime->rev_status) . "</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function confirmRevisionPersonil()
    {
        $post = getGridPost();
        $data1 = [];
        $data2 = [];
        foreach ($post as $key => $value) {
            if($value['c1'] == 1) {
                if($value['c5'] != 'ADD') {
                    $data1[] = [
                        'id' => $key,
                        'status' => $value['c4'],
                        'status_before' => $value['c3'],
                        'updated_by' => empId(),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                } else {
                    $data2[] = [
                        'id' => $key,
                        'status' => $value['c4'],
                        'updated_by' => empId(),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                }
            }
        }

        if(count($data1) > 0 || count($data2) > 0) {
            if(count($data1) > 0) {
                $this->Hr->updateMultiple('employee_overtimes_detail', $data1, 'id');
            }
            if(count($data2) > 0) {
                $this->Hr->updateMultiple('employee_overtimes_detail', $data2, 'id');
            }
            xmlResponse('updated', 'Berhasil mengubah status lembur karyawan');
        } else {
            xmlResponse('error', 'Gagal mengubah status!');
        }
    }

    public function rollbackRevisionPersonil()
    {
        $post = getGridPost();
        $data = [];
        foreach ($post as $key => $value) {
            if($value['c2'] == 1) {
                $data[] = [
                    'id' => $key,
                    'status' => $value['c5'],
                    'updated_by' => empId(),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }
        }

        if(count($data) > 0) {
            $update = $this->Hr->updateMultiple('employee_overtimes_detail', $data, 'id');
            if($update) {
                xmlResponse('updated', 'Berhasil mengubah status lembur karyawan');
            } else {
                xmlResponse('error', 'Gagal mengubah status!');
            }
        } else {
            xmlResponse('error', 'Gagal mengubah status!');
        }
    }

    public function getOvtReview()
    {
        $post = fileGetContent();
        $taskId = $post->taskId;
        $review = $this->Hr->getOne('employee_overtimes', ['task_id' => $taskId])->overtime_review;
        response(['comment' => $review]);
    }

    public function getOvtReqByTaskId()
    {
        $post = fileGetContent();
        $reqs = [];
        $empOvts = $this->Hr->getWhereIn('employee_overtimes', ['task_id' => $post->taskId])->result();
        foreach ($empOvts as $ovt) {
            if($ovt->jemputan > 0) {
                $reqs[$ovt->jemputan]= 'jemputan';
            }
            if($ovt->ahu > 0) {
                $reqs[$ovt->ahu]= 'ahu';
            }
            if($ovt->compressor > 0) {
                $reqs[$ovt->compressor]= 'compressor';
            }
            if($ovt->pw > 0) {
                $reqs[$ovt->pw]= 'pw';
            }
            if($ovt->steam > 0) {
                $reqs[$ovt->steam]= 'steam';
            }
            if($ovt->dust_collector > 0) {
                $reqs[$ovt->dust_collector]= 'dust_collector';
            }
            if($ovt->wfi > 0) {
                $reqs[$ovt->wfi]= 'wfi';
            }
            if($ovt->mechanic > 0) {
                $reqs[$ovt->mechanic]= 'mechanic';
            }
            if($ovt->electric > 0) {
                $reqs[$ovt->electric]= 'electric';
            }
            if($ovt->hnn > 0) {
                $reqs[$ovt->hnn]= 'hnn';
            }
            if($ovt->qc > 0) {
                $reqs[$ovt->qc]= 'qc';
            }
            if($ovt->qa > 0) {
                $reqs[$ovt->qa]= 'qa';
            }
            if($ovt->penandaan > 0) {
                $reqs[$ovt->penandaan]= 'penandaan';
            }
            if($ovt->gbk > 0) {
                $reqs[$ovt->gbk]= 'gbk';
            }
            if($ovt->gbb > 0) {
                $reqs[$ovt->gbb]= 'gbb';
            }
        }
        $finalReqs = [];
        foreach ($reqs as $key => $value) {
            $finalReqs[] = $value;
        }
        response(['status' => 'success', 'reqs' => $finalReqs]);
    }

    public function deleteRef()
    {
        $post = fileGetContent();
        $this->Hr->delete("employee_overtimes_ref", ['task_id' => $post->taskId]);
        response(['status' => 'success', 'message' => 'Berhasil menghapus referensi lembur']);
    }

    public function asignToOvertime()
    {
        $post = fileGetContent();
        $ovt = $this->Hr->getOne('employee_overtimes', ['task_id' => $post->taskId]);
        $data = [];
        if($ovt->jemputan > 0) {
            $data['jemputan'] = $ovt->jemputan;
        }

        if(empSub() == 5) {
            if($ovt->ahu > 0) {
                $data['ahu'] = intval($ovt->ahu);
            }
            if($ovt->compressor > 0) {
                $data['compressor'] = intval($ovt->compressor);
            }
            if($ovt->pw > 0) {
                $data['pw'] = intval($ovt->pw);
            }
            if($ovt->steam > 0) {
                $data['steam'] = intval($ovt->steam);
            }
            if($ovt->dust_collector > 0) {
                $data['dust_collector'] = intval($ovt->dust_collector);
            }
            if($ovt->wfi > 0) {
                $data['wfi'] = intval($ovt->wfi);
            }
            if($ovt->mechanic > 0) {
                $data['mechanic'] = intval($ovt->mechanic);
            }
            if($ovt->electric > 0) {
                $data['electric'] = intval($ovt->electric);
            }
            if($ovt->hnn > 0) {
                $data['hnn'] = intval($ovt->hnn);
            }
        }

        if(empSub() == 7) {
            if($ovt->qa > 0) {
                $data['qa'] = intval($ovt->qa);
            }
        }

        if(empSub() == 8) {
            if($ovt->qc > 0) {
                $data['qc'] = intval($ovt->qc);
            }
        }

        if(empSub() == 13) {
            if($ovt->penandaan > 0) {
                $data['penandaan'] = intval($ovt->penandaan);
            }
            if($ovt->gbb > 0) {
                $data['gbb'] = intval($ovt->gbb);
            }
            if($ovt->gbk > 0) {
                $data['gbk'] = intval($ovt->gbk);
            }
        }
        $this->Hr->update('employee_overtimes', $data, ['task_id' => $post->taskIdSupport]);
        $this->Hr->update('employee_overtimes_ref', ['task_id_support' => $post->taskIdSupport], ['task_id' => $post->taskId]);
        response(['status' => 'success', 'message' => 'Berhasil asign referensi lembur ke lemburan support']);
    }

    public function updatePersonilMachine()
    {
        $post = fileGetContent();
        $machines = explode(',', $post->ids);
        $total = count($machines);
        if($total > 0) {
            if($total > 2) {
                response(['status' => 'error', 'message' => '1 Orang maksimal 2 mesin!']);
            } else {
                $no = 1;
                foreach ($machines as $key => $value) {
                    $data["machine_$no"] = $value;
                    $no++;
                }
                $this->Hr->UpdateById('employee_overtimes_detail', $data, $post->id);
                response(['status' => 'success', 'message' => 'Berhasil mengubah mesin']);
            }
        } else {
            response(['status' => 'error', 'message' => 'Tidak ada mesin dipilih!']);
        }
    }

    public function updatePersonilRequest()
    {
        $post = fileGetContent();
        $ids = explode(',', $post->ids);
        $reqs = $this->Hr->getWhereIn('overtime_requirement', ['id' => $ids])->result();
        $name = '';
        foreach ($reqs as $req) {
            if($name == '') {
                $name = $req->name;
            } else {
                $name = $name.','.$req->name;
            }
        }
        $this->Hr->updateById('employee_overtimes_detail', ['requirements' => $name], $post->id);
        response(['status' => 'success', 'message' => 'Berhasil mengubah kebutuhan lembur personil']);
    }

    public function getRekapColumn()
    {
        $post = fileGetContent();
        $date1 = explode('-', $post->start);
        $date2 = explode('-', $post->end);
        $divisions = $this->Overtime->getDivision();
        
        $time1 = mktime(0, 0, 0, $date1[1], $date1[2], $date1[0]);
        $time2 = mktime(0, 0, 0, $date2[1], $date2[2], $date2[0]);

        $header = "";
        $attheader = "";
        $colsort = "";
        $colalign = "";
        $coltypes = "";
        $width = "";
        foreach ($divisions as $div) {
            $header .= ",$div->name";
            $attheader .= ",#text_filter";
            $colsort .= ",str";
            $colalign .= ",left";
            $coltypes .= ",rotxt";
            $width .= ",10";
        }

        $data = [];
        $no = 1;
        for ($start = $time1;$start <= $time2; $start += 86400) {
            $date = date('Y-m-d', $start);
            $dt = [
                $no,
                toIndoDateDay($date)
            ];

            foreach ($divisions as $div) {
                $min = $this->Overtime->getMinStartHour($date, $div->id);
                $max = $this->Overtime->getMinEndHour($date, $div->id);
                if($min && $max) {
                    $dt[] = "$min - $max";
                } else {
                    if(!$min && $max) {
                        $dt[] = "(-1 Hari) - $max";
                    } else {
                        $dt[] = "-";
                    }
                }
            }

            $data['rows'][] = [
                'id' => $date,
                'data' => $dt
            ];
            $no++;
        }

        response([
            'header' => $header,
            'attheader' => $attheader,
            'colsort' => $colsort,
            'colalign' => $colalign,
            'coltypes' => $coltypes,
            'width' => $width,
            'rows' => $data
        ]);
    }
}