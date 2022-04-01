<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CronController extends Erp_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('OvertimeModel', 'Overtime');
        $this->Overtime->myConstruct('hr');
    }

    //@URL: http://localhost/spekta/index.php?c=CronController&m=sendEmail
    public function sendEmail()
    {
        $status = $this->Main->getDataById('email_send', 1)->status;
        if ($status == 'enable') {
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

    //@URL: http://localhost/spekta/index.php?c=CronController&m=clearEmail
    public function clearEmail()
    {
        $last7Day = backDayToDate(date('Y-m-d'), 7);
        $this->Main->delete('email', ['created_at <' => $last7Day, 'status' => 1]);
    }

    //@URL: http://localhost/spekta/index.php?c=CronController&m=updateStatusReservasi
    public function updateStatusReservasi()
    {
        $vehicles = $this->General->getWhere('vehicles_reservation', ['status' => 'APPROVED'])->result();
        $vhcData = [];
        foreach ($vehicles as $vhc) {
            $now = new DateTime(date('Y-m-d'));
            $exp = new DateTime(addDayToDate(date('Y-m-d', strtotime($vhc->start_date)), 1));
            if ($exp < $now) {
                $vhcData[] = [
                    'id' => $vhc->id,
                    'status' => 'CLOSED',
                ];
            }
        }
        if (count($vhcData) > 0) {
            $this->General->updateMultiple('vehicles_reservation', $vhcData, 'id');
        }

        $mrooms = $this->General->getWhere('meeting_rooms_reservation', ['status' => 'APPROVED'])->result();
        $rmData = [];
        foreach ($mrooms as $mroom) {
            $now = new DateTime(date('Y-m-d'));
            $exp = new DateTime(addDayToDate(date('Y-m-d', strtotime($mroom->start_date)), 1));
            if ($exp < $now) {
                $rmData[] = [
                    'id' => $mroom->id,
                    'status' => 'CLOSED',
                ];
            }
        }
        if (count($rmData) > 0) {
            $this->General->updateMultiple('meeting_rooms_reservation', $rmData, 'id');
        }
    }

    //@URL: http://localhost/spekta/index.php?c=CronController&m=autoAppvAsman
    public function autoAppvAsman()
    {
        $date = date('Y-m-d H:i:s');
        $overtimes = $this->Overtime->getAppvAsman(backDayToDate($date, 2));
        foreach ($overtimes as $overtime) {
            $asman = $this->Hr->getOne('employees', ['sub_department_id' => $overtime->sub_department_id], '*', ['rank_id' => ['3', '4']]);
            if ($asman) {
                $empId = $asman->id;
                $empNip = $asman->nip;
                $email = $asman->email;
            } else {
                $asman = $this->Hr->getOne('employee_ranks', ['sub_department_id' => $overtime->sub_department_id, 'status' => 'ACTIVE'], '*', ['rank_id' => ['3', '4']]);
                $emp = $this->Hr->getDataById('employees', $asman->emp_id);
                $empId = $emp->id;
                $empNip = $emp->nip;
                $email = $emp->email;
            }

            $data = [
                'apv_asman' => 'APPROVED',
                'apv_asman_nip' => $empNip,
                'apv_asman_date' => date('Y-m-d H:i:s'),
            ];

            $update = $this->Hr->update('employee_overtimes', $data, ['task_id' => $overtime->task_id]);
            if ($update) {
                if ($overtime->sub_department_id == 1 || $overtime->sub_department_id == 2 || $overtime->sub_department_id == 3 || $overtime->sub_department_id == 4 || $overtime->sub_department_id == 13) {
                    $isHavePPIC = $this->isHavePPIC($overtime);
                    if (!$isHavePPIC) {
                        $isHaveMgr = $this->isHaveMgr($overtime);
                        if (!$isHaveMgr) {
                            $this->isHaveHead($overtime);
                        }
                    }
                } else {
                    $isHaveMgr = $this->isHaveMgr($overtime);
                    if (!$isHaveMgr) {
                        $this->isHaveHead($overtime);
                    }
                }
            }
        }
    }

    //@URL: http://localhost/spekta/index.php?c=CronController&m=autoAppvPPIC
    public function autoAppvPPIC()
    {
        $date = date('Y-m-d H:i:s');
        $overtimes = $this->Overtime->getAppvPPIC(backDayToDate($date, 2));
        foreach ($overtimes as $overtime) {
            $asman = $this->Hr->getOne('employees', ['sub_department_id' => 9], '*', ['rank_id' => ['3', '4']]);
            if ($asman) {
                $empId = $asman->id;
                $empNip = $asman->nip;
                $email = $asman->email;
            } else {
                $asman = $this->Hr->getOne('employee_ranks', ['sub_department_id' => 9, 'status' => 'ACTIVE'], '*', ['rank_id' => ['3', '4']]);
                $emp = $this->Hr->getDataById('employees', $asman->emp_id);
                $empId = $emp->id;
                $empNip = $emp->nip;
                $email = $emp->email;
            }

            $data = [
                'apv_ppic' => 'APPROVED',
                'apv_ppic_nip' => $empNip,
                'apv_ppic_date' => date('Y-m-d H:i:s'),
            ];
            $update = $this->Hr->update('employee_overtimes', $data, ['task_id' => $overtime->task_id]);
            if ($update) {
                $isHaveMgr = $this->isHaveMgr($overtime);
                if (!$isHaveMgr) {
                    $this->isHaveHead($overtime);
                }
            }
        }
    }

    //@URL: http://localhost/spekta/index.php?c=CronController&m=autoAppvManager
    public function autoAppvManager()
    {
        $date = date('Y-m-d H:i:s');
        $overtimes = $this->Overtime->getAppvManager(backDayToDate($date, 2));
        foreach ($overtimes as $overtime) {
            $mgr = $this->Hr->getOne('employees', ['department_id' => $overtime->department_id, 'rank_id' => 2]);
            if ($mgr) {
                $empId = $mgr->id;
                $empNip = $mgr->nip;
                $email = $mgr->email;
            } else {
                $mgr = $this->Hr->getOne('employee_ranks', ['department_id' => $overtime->department_id, 'status' => 'ACTIVE', 'rank_id' => 2]);
                $emp = $this->Hr->getDataById('employees', $mgr->emp_id);
                $empId = $emp->id;
                $empNip = $emp->nip;
                $email = $emp->email;
            }

            $data = [
                'apv_mgr' => 'APPROVED',
                'apv_mgr_nip' => $empNip,
                'apv_mgr_date' => date('Y-m-d H:i:s'),
            ];
            $update = $this->Hr->update('employee_overtimes', $data, ['task_id' => $overtime->task_id]);
            if ($update) {
                $this->isHaveHead($overtime);
            }
        }
    }

    //@URL: http://localhost/spekta/index.php?c=CronController&m=autoAppvHead
    public function autoAppvHead()
    {
        $date = date('Y-m-d H:i:s');
        $overtimes = $this->Overtime->getAppvHead(backDayToDate($date, 2));
        foreach ($overtimes as $overtime) {
            $head = $this->Hr->getOne('employees', ['rank_id' => 1]);
            if ($head) {
                $empId = $head->id;
                $empNip = $head->nip;
                $email = $head->email;
            } else {
                $head = $this->Hr->getOne('employee_ranks', ['status' => 'ACTIVE', 'rank_id' => 1]);
                $emp = $this->Hr->getDataById('employees', $head->emp_id);
                $empId = $emp->id;
                $empNip = $emp->nip;
                $email = $emp->email;
            }

            $data = [
                'apv_head' => 'APPROVED',
                'apv_head_nip' => $empNip,
                'apv_head_date' => date('Y-m-d H:i:s'),
                'status' => 'CLOSED'
            ];
            $update = $this->Hr->update('employee_overtimes', $data, ['task_id' => $overtime->task_id]);
            if ($update) {
                $this->Hr->update('employee_overtimes_detail', [
                    'status' => 'CLOSED',
                    'updated_by' => $empId,
                    'updated_at' => date('Y-m-d H:i:s')],
                    ['task_id' => $overtime->task_id],
                    null,
                    ['status' => ['CANCELED', 'REJECTED']]);
            }
        }
    }

    public function isHavePPIC($overtime)
    {
        $isHavePPIC = $this->Hr->getOne('employees', ['sub_department_id' => 9], '*', ['rank_id' => ['3', '4']]);
        $isHavePPICPLT = $this->Hr->getOne('employee_ranks', ['sub_department_id' => 9, 'status' => 'ACTIVE'], '*', ['rank_id' => ['3', '4']]);
        if ($overtime->apv_ppic_nip == '' && ($isHavePPIC || $isHavePPICPLT)) {
            if ($isHavePPIC) {
                $this->ovtlib->sendEmailAppv($isHavePPIC->email, 'PPIC', 'ppic', $overtime, $overtime->task_id);
                return true;
            } else if ($isHavePPICPLT) {
                $email = $this->Hr->getDataById('employees', $isHavePPIC->emp_id)->email;
                $this->ovtlib->sendEmailAppv($email, 'PPIC', 'ppic', $overtime, $overtime->task_id);
                return true;
            }
        } else {
            return false;
        }
    }

    public function isHaveMgr($overtime)
    {
        $isHaveMgr = $this->Hr->getOne('employees', ['department_id' => $overtime->department_id, 'rank_id' => 2]);
        $isHaveMgrPLT = $this->Hr->getOne('employee_ranks', ['department_id' => $overtime->department_id, 'rank_id' => 2, 'status' => 'ACTIVE']);
        if ($overtime->apv_mgr_nip == '' && ($isHaveMgr || $isHaveMgrPLT)) {
            if ($isHaveMgr) {
                $this->ovtlib->sendEmailAppv($isHaveMgr->email, 'Manager', 'mgr', $overtime, $overtime->task_id);
                return true;
            } else if ($isHaveMgrPLT) {
                $email = $this->Hr->getDataById('employees', $isHaveMgrPLT->emp_id)->email;
                $this->ovtlib->sendEmailAppv($email, 'Manager', 'mgr', $overtime, $overtime->task_id);
                return true;
            }
        } else {
            return false;
        }
    }

    public function isHaveHead($overtime)
    {
        $isHaveHead = $this->Hr->getOne('employees', ['rank_id' => 1]);
        $isHaveHeadPLT = $this->Hr->getOne('employees', ['rank_id' => 1, 'status' => 'ACTIVE']);
        if ($overtime->apv_head_nip == '' && ($isHaveHead || $isHaveHeadPLT)) {
            if ($isHaveHead) {
                $this->ovtlib->sendEmailAppv($isHaveHead->email, 'Plant Manager', 'head', $overtime, $overtime->task_id);
                return true;
            } else if ($isHaveHeadPLT) {
                $email = $this->Hr->getDataById('employees', $isHaveHeadPLT->emp_id)->email;
                $this->ovtlib->sendEmailAppv($email, 'Plant Manager', 'head', $overtime, $overtime->task_id);
                return true;
            }
        } else {
            return false;
        }
    }
}
