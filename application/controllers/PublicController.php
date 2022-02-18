<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PublicController extends Erp_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('OvertimeModel', 'Overtime');
        $this->Overtime->myConstruct('hr');
        $this->load->model('HrModel');
        $this->HrModel->myConstruct('hr');
        $this->load->model('OtherModel', 'Other');
        $this->Other->myConstruct('main');
    }

    public function pinVerification()
    {
        $params = getParam();
        if(isset($params['token'])) {
            $this->load->view('html/pin_verification', ['token' => $params['token']]);
        } else {
            $this->load->view('html/invalid_response', ['message' => "Token tidak valid"]);
        }
    }

    public function verifyPin()
    {
        $post = fileGetContent();
        $token = $post->token;
        $pin = $post->pin;
        $checkPin = $this->Hr->getOne('employee_pins', ['pin' => $pin]);
        if ($checkPin) {
            $emp = $this->Hr->getDataById('employees', $checkPin->emp_id);
            response(['status' => 'success', 'url' => simpleEncrypt($token, 'd') . "&nip=$emp->nip&emp_id=$emp->id"]);
        } else {
            response(['status' => 'error', 'message' => 'PIN Tidak Valid']);
        }
    }

    public function generateOvertime()
    {
        $params = getParam();
        $taskId = simpleEncrypt($params['token'], 'd');
        $overtime = $this->Hr->getOne('employee_overtimes', ['task_id' => $taskId]);
        if ($overtime) {
            $checkIsGenerated = $this->Hr->getOne('employee_overtimes', ['ref' => $taskId]);
            if (!$checkIsGenerated) {
                $lastId = $this->Overtime->lastOt('employee_overtimes', 'overtime_date', $overtime->overtime_date, $overtime->location);
                $expDate = explode('-', $overtime->overtime_date);
                $newTaskId = sprintf('%03d', $lastId + 1) . '/OT/' . $overtime->location . '/' . toRomawi($expDate[1]) . '/' . $expDate[0];
                $tnpData = [
                    'location' => $overtime->location,
                    'task_id' => $newTaskId,
                    'ref' => $overtime->task_id,
                    'department_id' => 1,
                    'sub_department_id' => 5,
                    'division_id' => 0,
                    'personil' => 0,
                    'overtime_date' => $overtime->overtime_date,
                    'start_date' => $overtime->start_date,
                    'end_date' => $overtime->end_date,
                    'status_day' => $overtime->status_day,
                    'notes' => $overtime->notes,
                    'makan' => 0,
                    'steam' => $overtime->steam,
                    'ahu' => $overtime->ahu,
                    'compressor' => $overtime->compressor,
                    'pw' => $overtime->pw,
                    'jemputan' => $overtime->jemputan,
                    'dust_collector' => $overtime->dust_collector,
                    'mechanic' => $overtime->mechanic,
                    'electric' => $overtime->electric,
                    'hnn' => $overtime->hnn,
                    'status' => 'CREATED',
                    'apv_spv' => 'CREATED',
                    'apv_asman' => 'CREATED',
                    'apv_mgr' => 'CREATED',
                    'apv_head' => 'CREATED',
                    'created_by' => $params['emp_id'],
                    'updated_by' => $params['emp_id'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $newOvertime = $this->Hr->create('employee_overtimes', $tnpData);
                if ($newOvertime) {
                    $this->load->view('html/valid_response', ['message' => " <p>Berhasil membuat lembur <b>Teknik & Pemeliharaan</b> dengan Task ID: <b>$overtime->task_id</b><br/> Segera lakukan <b>pemilihan personil</b> untuk lemburan tersebut!</p>"]);
                } else {
                    $this->load->view('html/invalid_response', ['message' => "<p>Gagal membuat lembur <b>Teknik & Pemeliharaan</b><br/> Lembur dengan No. Referensi: <b>$overtime->task_id</b> sudah dibuat sebelumnya</p>"]);
                }
            } else {
                $this->load->view('html/invalid_response', ['message' => "<p>Gagal membuat lembur <b>Teknik & Pemeliharaan</b><br/> Lembur dengan No. Referensi: <b>$overtime->task_id</b> sudah dibuat sebelumnya</p>"]);
            }
        } else {
            $this->load->view('html/invalid_response', ['message' => 'Token tidak valid!']);
        }
    }

    public function approveOvertime()
    {
        $params = getParam();
        $expParam = isset($params['token']) ? explode(':', simpleEncrypt($params['token'], 'd')) : [];

        if (count($expParam) == 3) {
            $taskId = $expParam[0];
            $appvType = $expParam[1];
            $status = $expParam[2];
            $nip = $params['nip'];
            $empId = $params['emp_id'];
            $emp = $this->Hr->getOne('employees', ['nip' => $nip]);
            $overtime = $this->Overtime->getOvertime(['equal_task_id' => $taskId])->row();
            if ($appvType == 'spv') {
                $isSpvPLT = $this->Hr->getOne('employee_ranks', ['emp_id' => $empId, 'division_id' => $overtime->division_id, 'status' => 'ACTIVE'], 'rank_id,division_id', ['rank_id' => ['5', '6']]);
                if ($emp->rank_id != 5 && $emp->rank_id != 6 && !$isSpvPLT) {
                    $this->load->view('html/invalid_response', ['message' => 'Jabatan anda bukan <b>Supervisor</b>']);
                } else if ($emp->division_id != $overtime->division_id && ($isSpvPLT && $isSpvPLT->division_id != $overtime->division_id)) {
                    $this->load->view('html/invalid_response', ['message' => 'Jabatan anda tidak sesuai dengan <b>Sub Bagian Lembur</b>']);
                } else if (($emp->rank_id == 5 || $emp->rank_id == 6) && $emp->division_id == $overtime->division_id || $isSpvPLT) {
                    $this->approveAction($overtime, $emp, $taskId, $appvType, $status, $nip, $empId);
                } else {
                    $this->load->view('html/invalid_response', ['message' => 'Oops..! <b>Terjadi Kesalahan</b>']);
                }
            } else if ($appvType == 'asman') {
                $isAsmanPLT = $this->Hr->getOne('employee_ranks', ['emp_id' => $empId, 'sub_department_id' => $overtime->sub_department_id, 'status' => 'ACTIVE'], 'rank_id,sub_department_id', ['rank_id' => ['3', '4']]);
                if ($emp->rank_id != 3 && $emp->rank_id != 4 && !$isAsmanPLT) {
                    $this->load->view('html/invalid_response', ['message' => 'Jabatan anda bukan <b>ASMAN</b>']);
                } else if ($emp->sub_department_id != $overtime->sub_department_id && ($isAsmanPLT && $isAsmanPLT->sub_department_id != $overtime->sub_department_id)) {
                    $this->load->view('html/invalid_response', ['message' => 'Jabatan anda tidak sesuai dengan <b>Bagian Lembur</b>']);
                } else if (($emp->rank_id == 3 || $emp->rank_id == 4) && $emp->sub_department_id == $overtime->sub_department_id || $isAsmanPLT) {
                    $this->approveAction($overtime, $emp, $taskId, $appvType, $status, $nip, $empId);
                } else {
                    $this->load->view('html/invalid_response', ['message' => 'Oops..! <b>Terjadi Kesalahan</b>']);
                }
            } else if ($appvType == 'mgr') {
                $isMgrPLT = $this->Hr->getOne('employee_ranks', ['emp_id' => $empId, 'department_id' => $overtime->department_id, 'rank_id' => 2, 'status' => 'ACTIVE'], 'rank_id,department_id');
                if ($emp->rank_id != 2 && !$isMgrPLT) {
                    $this->load->view('html/invalid_response', ['message' => 'Jabatan anda bukan <b>Manager</b>']);
                } else if ($emp->department_id != $overtime->department_id && ($isMgrPLT && $isMgrPLT->department_id != $overtime->department_id)) {
                    $this->load->view('html/invalid_response', ['message' => 'Jabatan anda tidak sesuai dengan <b>Sub Unit Lembur</b>']);
                } else if ($emp->rank_id == 2 && $emp->department_id == $overtime->department_id || $isMgrPLT) {
                    $this->approveAction($overtime, $emp, $taskId, $appvType, $status, $nip, $empId);
                } else {
                    $this->load->view('html/invalid_response', ['message' => 'Oops..! <b>Terjadi Kesalahan</b>']);
                }
            } else if ($appvType == 'head') {
                $isHeadPLT = $this->Hr->getOne('employee_ranks', ['emp_id' => $empId, 'rank_id' => 1, 'status' => 'ACTIVE'], 'rank_id');
                if ($emp->rank_id != 1 && !$isHeadPLT) {
                    $this->load->view('html/invalid_response', ['message' => 'Jabatan anda bukan <b>Plant Manager</b>']);
                } else if ($emp->rank_id == 1 || $isAsmanPLT) {
                    $this->approveAction($overtime, $emp, $taskId, $appvType, $status, $nip, $empId);
                } else {
                    $this->load->view('html/invalid_response', ['message' => 'Oops..! <b>Terjadi Kesalahan</b>']);
                }
            }
        } else {
            $this->load->view('html/invalid_response', ['message' => 'Token tidak valid!']);
        }
    }

    public function approveAction($overtime, $emp, $taskId, $appvType, $status, $nip, $empId)
    {
        if ($appvType == 'spv') {
            $columnApv = 'apv_spv';
            $columnApvNip = 'apv_spv_nip';
            $columnApvDate = 'apv_spv_date';
        } else if ($appvType == 'asman') {
            $columnApv = 'apv_asman';
            $columnApvNip = 'apv_asman_nip';
            $columnApvDate = 'apv_asman_date';
        } else if ($appvType == 'mgr') {
            $columnApv = 'apv_mgr';
            $columnApvNip = 'apv_mgr_nip';
            $columnApvDate = 'apv_mgr_date';
        } else if ($appvType == 'head') {
            $columnApv = 'apv_head';
            $columnApvNip = 'apv_head_nip';
            $columnApvDate = 'apv_head_date';
        }

        if ($overtime->$columnApvNip == '') {
            if ($status == 'APPROVED') {
                $data = [
                    $columnApv => $status,
                    $columnApvNip => $nip,
                    $columnApvDate => date('Y-m-d H:i:s'),
                ];

                if ($emp->rank_id == 1) {
                    $data['status'] = 'CLOSED';
                    $data['updated_by'] = $empId;
                    $data['updated_at'] = date('Y-m-d H:i:s');
                }
            } else {
                $data = [
                    $columnApv => $status,
                    $columnApvNip => $nip,
                    $columnApvDate => date('Y-m-d H:i:s'),
                    'rejection_note' => $emp->employee_name . " : Rejected from email",
                    'status' => 'REJECTED',
                    'updated_by' => $empId,
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }
            $this->Hr->update('employee_overtimes', $data, ['task_id' => $taskId]);

            if($emp->rank_id == 1) {
                $dataDetail = [
                    'status' => 'CLOSED',
                    'updated_by' => $empId,
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $this->Hr->update('employee_overtimes_detail', $dataDetail, ['task_id' => $taskId]);
            }
            
            if($status == 'REJECTED') {
                $dataDetail = [
                    'status' => 'REJECTED',
                    'updated_by' => $empId,
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $this->Hr->update('employee_overtimes_detail', $dataDetail, ['task_id' => $taskId]);
            }

            if ($appvType == 'spv') {
                if ($status == 'APPROVED') {
                    $isHaveAsman = $this->isHaveAsman($overtime, $taskId);
                    if(!$isHaveAsman) {
                        $isHaveMgr = $this->isHaveMgr($overtime, $taskId);
                        if(!$isHaveMgr) {
                            $this->isHaveHead($overtime, $taskId);
                        }
                    }
                } else {
                    $this->ovtlib->sendEmailReject('Supervisor', 'spv', $overtime, $taskId);
                }
            } else if ($appvType == 'asman') {
                if ($status == 'APPROVED') {
                    $isHaveMgr = $this->isHaveMgr($overtime, $taskId);
                    if(!$isHaveMgr) {
                        $this->isHaveHead($overtime, $taskId);
                    }

                    if($overtime->sub_department_id != 5 && $overtime->ref == "") {
                        $picEmails = $this->Main->getOne('pics', ['code' => 'overtime', 'sub_department_id' => 5])->pic_emails;
                        $tokenTaskId = simpleEncrypt($overtime->task_id);
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
                } else {
                    $this->ovtlib->sendEmailReject('ASMAN', 'asman', $overtime, $taskId);
                }
            } else if ($appvType == 'mgr') {
                if ($status == 'APPROVED') {
                    $this->isHaveHead($overtime, $taskId);
                } else {
                    $this->ovtlib->sendEmailReject('Manager', 'mgr', $overtime, $taskId);
                }
            } else {
                if ($status == 'REJECTED') {
                    $this->ovtlib->sendEmailReject('Plant Manager', 'head', $overtime, $taskId);
                }
            }
            $this->load->view('html/valid_response', ['message' => "<p>Lembur <b>$overtime->task_id</b> berhasil di <b>$status</b></p>"]);
        } else {
            $employee = $overtime->$columnApvNip != '-' ? $this->Hr->getOne('employees', ['nip' => $overtime->$columnApvNip])->employee_name : null;
            $approver = $employee ? $employee : 'sistem';
            $this->load->view('html/invalid_response', ['message' => "<p>Gagal approve lembur</p><br/> Lembur dengan No. Referensi: <b>$overtime->task_id</b> sudah di $overtime->status oleh $approver </p>"]);
        }
    }

    public function isHaveAsman($overtime, $taskId)
    {
        $isHaveAsman = $this->Hr->getOne('employees', ['sub_department_id' => $overtime->sub_department_id], '*', ['rank_id' => ['3', '4']]);
        $isHaveAsmanPLT = $this->Hr->getOne('employee_ranks', ['sub_department_id' => $overtime->sub_department_id, 'status' => 'ACTIVE'], '*', ['rank_id' => ['3', '4']]);
        if ($isHaveAsman) {
            $this->ovtlib->sendEmailAppv($isHaveAsman->email, 'ASMAN', 'asman', $overtime, $taskId);
            return true;
        } else if($isHaveAsmanPLT) {
            $email = $this->Hr->getDataById('employees', $isHaveAsmanPLT->emp_id)->email;
            $this->ovtlib->sendEmailAppv($email, 'ASMAN', 'asman', $overtime, $taskId);
            return true;
        } else {
            return false;
        }
    }

    public function isHaveMgr($overtime, $taskId)
    {
        $isHaveMgr = $this->Hr->getOne('employees', ['department_id' => $overtime->department_id, 'rank_id' => 2]);
        $isHaveMgrPLT = $this->Hr->getOne('employee_ranks', ['department_id' => $overtime->department_id, 'rank_id' => 2, 'status' => 'ACTIVE']);
        if ($isHaveMgr) {
            $this->ovtlib->sendEmailAppv($isHaveMgr->email, 'Manager', 'mgr', $overtime, $taskId);
            return true;
        } else if($isHaveMgrPLT) {
            $email = $this->Hr->getDataById('employees', $isHaveMgrPLT->emp_id)->email;
            $this->ovtlib->sendEmailAppv($email, 'Manager', 'mgr', $overtime, $taskId);
            return true;
        } else {
            return false;
        }
    }

    public function isHaveHead($overtime, $taskId)
    {
        $isHaveHead = $this->Hr->getOne('employees', ['rank_id' => 1]);
        $isHaveHeadPLT = $this->Hr->getOne('employee_ranks', ['rank_id' => 1, 'status' => 'ACTIVE']);
        if ($isHaveHead) {
            $this->ovtlib->sendEmailAppv($isHaveHead->email, 'Plant Manager', 'head', $overtime, $taskId);
            return true;
        } else if($isHaveHeadPLT) {
            $email = $this->Hr->getDataById('employees', $isHaveHeadPLT->emp_id)->email;
            $this->ovtlib->sendEmailAppv($isHaveHead->email, 'Plant Manager', 'head', $overtime, $taskId);
            return true;
        } else {
            return false;
        }
    }

    public function responseMeeting()
    {
        $params = getParam();
        $token = isset($params['token']) ? explode(':', simpleEncrypt($params['token'], 'd')) : [];
       
        if(count($token) == 3) {
            $meetId = $token[0];
            $email = $token[1];
            $status = $token[2];
            $currStatus = $this->General->getOne('meeting_participants', ['email' => $email, 'meeting_id' => $meetId])->status;
            if($currStatus == 'BELUM MEMUTUSKAN') {
                if($status == 'accept') {
                    $this->General->update('meeting_participants', ['status' => 'HADIR'], ['email' => $email,  'meeting_id' => $meetId]);
                    $this->General->addValueBy('meeting_rooms_reservation', ['participant_confirmed' => 1], ['id' => $meetId]);
                    $this->load->view('html/valid_response', ['message' => "Konfirmasi diterima, anda akan HADIR di meeting tersebut"]);
                } else {
                    $this->General->update('meeting_participants', ['status' => 'TIDAK HADIR'], ['email' => $email,  'meeting_id' => $meetId]);
                    $this->General->addValueBy('meeting_rooms_reservation', ['participant_rejected' => 1], ['id' => $meetId]);
                    $this->load->view('html/valid_response', ['message' => "Konfirmasi diterima, anda memutuskan TIDAK HADIR di meeting tersebut"]);
                }
            } else {
                $this->load->view('html/invalid_response', ['message' => "Anda telah mengambil keputusan $currStatus untuk undangan ini!"]);
            }
        } else {
            $this->load->view('html/invalid_response', ['message' => 'Token tidak valid!']);
        }
    }

    public function updateMeetRevStatus()
    {
        $params = getParam();
        $expToken = isset($params['token']) ? explode(':', simpleEncrypt($params['token'], 'd')) : [];
        
        if(count($expToken) == 2) {
            $meetId = $expToken[0];
            $status = $expToken[1] == 'approve' ? 'APPROVED' : 'REJECTED';
            $nip = $params['nip'];
            $empId = $params['emp_id'];

            $data = [
                'status' => $status,
                'updated_by' => $empId,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $emp = $this->Hr->getDataById('employees', $empId);
            $rev = $this->General->getDataById('meeting_rooms_reservation', $meetId);
            if($rev->status == 'CREATED') {
                $this->General->updateById('meeting_rooms_reservation', $data, $meetId);
                $this->General->update('meeting_rooms_reservation', $data, ['ref' => $meetId]);
                if($status == 'APPROVED') {
                    $this->mroomlib->meetInvitation($emp, $meetId);
                } else if($status == 'REJECTED') {
                    if($emp->rank_id == 5 || $emp->rank_id == 6) {
                        $this->mroomlib->rejectionNotif('ASMAN', $meetId, $emp->employee_name);
                    } else if($emp->rank_id == 2 || $emp->rank_id == 3) {
                        $this->mroomlib->rejectionNotif('Supervisor', $meetId, $emp->employee_name);
                    }
                }
                $this->load->view('html/valid_response', ['message' => "Berhasil <b>$status</b> reservasi ruang meeting"]);
            } else {
                $this->load->view('html/invalid_response', ['message' => "Sudah di <b>$rev->status</b> sebelumnya!"]);
            }
        } else {
            $this->load->view('html/invalid_response', ['message' => 'Token tidak valid!']);
        }
    }

    public function driverConfirm()
    {
        $params = getParam();
        $token = isset($params['token']) ? explode(':', simpleEncrypt($params['token'], 'd')) : [];
        if(count($token) == 3) {
            $id = $token[0];
            $email = $token[1];
            $status = $token[2] == 'approve' ? 'DISETUJUI' : 'MENOLAK';
            $trip = $this->Other->getTripDetail($id);
            if($trip->driver == $email) {
                if($trip->driver_confirmed == 'BELUM MEMUTUSKAN') {
                    $data = [
                        'driver_confirmed' => $status,
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    $this->General->updateById('vehicles_reservation', $data, $id);
                    $message = $status == 'DISETUJUI' ? "Perjalanan berhasil $status" : "Anda telah berhasil $status perjalan tersebut, silahkan cek email untuk update form Perjalanan!";
                    if($status == 'DISETUJUI') {
                        $driver = $this->Hr->getOne('employees', ['email' => $trip->driver]);
                        $linkForm = LIVE_URL . "index.php?c=PublicController&m=driverTripForm&token=".simpleEncrypt("$id:$trip->driver");
                        $messageEmail = $this->load->view('html/vehicles/email/driver_trip_form_notification', [
                            'data' => $trip, 'linkForm' => $linkForm, 'driver' => $driver
                        ], true);
                        $data = [
                            'alert_name' => 'TRIP_FORM_FOR_DRIVER',
                            'email_to' => $trip->driver,
                            'subject' => "Form Trip Driver untuk perjalanan ke $trip->destination (No. Tiket: $trip->ticket)",
                            'subject_name' => "Spekta Alert: Form Trip Driver untuk perjalanan ke $trip->destination (No. Tiket: $trip->ticket)",
                            'message' => $messageEmail,
                        ];
                        $insert = $this->Main->create('email', $data);
                    }
                    $this->load->view('html/valid_response', ['message' => $message]);
                } else {
                    $this->load->view('html/invalid_response', ['message' => "Anda telah mengambil keputusan (<b>$status</b>) untuk perjalanan ($id) ini!"]);
                }
            } else {
                $this->load->view('html/invalid_response', ['message' => "Anda bukan driver yang di tunjuk untuk perjalanan ($id) ini!"]);
            }
        } else {
            $this->load->view('html/invalid_response', ['message' => "Token tidak valid"]);
        }
    }

    public function driverTripForm()
    {
        $params = getParam();
        $expToken = isset($params['token']) ? explode(':', simpleEncrypt($params['token'], 'd')) : [];

        if(count($expToken) == 2) {
            $id = $expToken[0];
            $email = $expToken[1];

            $trip = $this->Other->getTripDetail($id);
            if($trip) {
                if($trip->driver == $email) {
                    $this->load->view('html/vehicles/form_driver', ['trip' => $trip]);
                } else {
                    $this->load->view('html/invalid_response', ['message' => 'Anda bukan driver untuk perjalanan ini!']);
                }
            } else {
                $this->load->view('html/invalid_response', ['message' => 'Data perjalanan tidak ditemukan!']);
            }
        } else {
            $this->load->view('html/invalid_response', ['message' => 'Token tidak valid!']);
        }
    }

    public function updateKilometer()
    {
       $post = fileGetContent();
       $id = $post->id;

       $data = [
           'start_km' => $post->start_km,
           'end_km' => $post->end_km,
           'distance' => $post->end_km - $post->start_km
       ];

       if($post->end_km < $post->start_km && $post->end_km > 0) {
           response(['status' => 'error', 'message' => 'KM akhir harus lebih besar dari KM awal!']);
       }
       $message = 'Update kilometer berhasil';
       if($post->start_km > 0 && $post->end_km == 0) {
        $message = 'Update kilometer awal berhasil';
       } else if($post->start_km == 0 && $post->end_km > 0){
        $message = 'Update kilometer akhir berhasil';
       }
       $this->General->updateById('vehicles_reservation', $data, $id);
       response(['status' => 'success', 'message' => $message]);
    }

    public function ganttChart()
    {
        $params = getParam();
        $expToken = explode(':', simpleEncrypt($params['token'], 'd'));
        $taskId = $expToken[0];
        $subId = $expToken[1];
        $divId = $expToken[2];
        $month = $expToken[3];
        $year = $expToken[4];
        
        if($taskId == '-') {
            if($divId == '-') {
                $tasks = $this->Main->getWhere('projects_task', [
                    'sub_department_id' => $subId, 
                    'MONTH(start_date)' => $month, 
                    'YEAR(start_date)' => $year
                ])->result();
            } else {
                $tasks = $this->Main->getWhere('projects_task', [
                    'sub_department_id' => $subId, 
                    'division_id' => $divId, 
                    'MONTH(start_date)' => $month, 
                    'YEAR(start_date)' => $year
                ])->result();
            }
        } else {
            $tasks = $this->Main->getWhere('projects_task', ['task_id' => $taskId])->result();
        }

        $data = [];
        $dataLink = [];

        foreach ($tasks as $task) {
        
            $dt = [
                'id' => $task->id, 
                'text' => $task->text, 
                'start_date' => revDate($task->start_date), 
                'duration' => $task->duration, 
                'progress' => $task->progress, 
                'open' => 1
            ];
            if($task->parent > 0) {
                $dt['parent'] = $task->parent;
            }
            $data[] = $dt;
        }

        if($divId == '-') {
            $links = $this->Main->getWhere('projects_link', ['sub_department_id' => $subId])->result();
        } else {
            $links = $this->Main->getWhere('projects_link', ['sub_department_id' => $subId, 'division_id' => $divId])->result();
        }

        foreach ($links as $link) {
            $dataLink[] = [
                'id' => $link->id, 
                'source' => $link->source, 
                'target' => $link->target,
                'type' => $link->type,
            ];
        }
        $this->load->view('html/public/gantt/gantt_chart', ['data' => ['data' => $data, 'links' => $dataLink]]);
    }
}
