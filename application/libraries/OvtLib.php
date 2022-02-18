<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class OvtLib
{
    protected $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->Model('BasicModel', 'Main');
        $this->ci->Main->myConstruct('main');
        $this->ci->load->Model('BasicModel', 'Hr');
        $this->ci->Hr->myConstruct('hr');

        $this->Main = $this->ci->Main;
        $this->Hr = $this->ci->Hr;
        $this->load = $this->ci->load;
    }

    public function sendEmailAppv($email, $rank, $rankLevel, $overtime, $taskId)
    {
        $tokenApprove = simpleEncrypt($taskId . ":$rankLevel:APPROVED");
        $linkApprove = LIVE_URL . "index.php?c=PublicController&m=approveOvertime&token=$tokenApprove";
        $tokenReject = simpleEncrypt($taskId . ":$rankLevel:REJECTED");
        $linkReject = LIVE_URL . "index.php?c=PublicController&m=approveOvertime&token=$tokenReject";

        $linkApprove = LIVE_URL . "index.php?c=PublicController&m=pinVerification&action=positive&token=" . simpleEncrypt($linkApprove);
        $linkReject = LIVE_URL . "index.php?c=PublicController&m=pinVerification&action=negative&token=" . simpleEncrypt($linkReject);
        $level = " ";
        $sublevel = "";
        if ($overtime->division != '-') {
            $level = " <b>Sub Bagian $overtime->division</b> ";
            $sublevel = " Sub Bagian $overtime->division";
        } else if ($overtime->sub_department != '-') {
            $level = " <b>Bagian $overtime->sub_department</b> ";
            $sublevel = "Bagian $overtime->sub_department";
        } else if ($overtime->department != '-') {
            $level = " <b>Sub Unit $overtime->department</b> ";
            $sublevel = "Sub Unit $overtime->department";
        }
        $message = $this->load->view('html/overtime/email/approve_overtime', [
            'overtime' => $overtime,
            'rank' => $rank,
            'level' => $level,
            'linkApprove' => $linkApprove,
            'linkReject' => $linkReject,
        ], true);
        $dataEmail = [
            'alert_name' => 'OVERTIME_APPROVEMENT',
            'email_to' => $email,
            'subject' => "Approval ($rank) Lembur $sublevel ($overtime->overtime_date) dengan Task ID: $taskId",
            'subject_name' => "Spekta Alert: Approval ($rank) Lembur $sublevel ($overtime->overtime_date) dengan Task ID: $taskId",
            'message' => $message,
        ];
        $insert = $this->Main->create('email', $dataEmail);
    }

    public function sendEmailReject($rank, $rankLevel, $overtime, $taskId)
    {
        $level = ' ';
        $sublevel = ' ';
        if ($overtime->division != '-') {
            $level = " <b>Sub Bagian $overtime->division</b> ";
            $sublevel = "Sub Bagian $overtime->division";
        } else if ($overtime->sub_department != '-') {
            $level = " <b>Bagian $overtime->sub_department</b> ";
            $sublevel = "Bagian $overtime->sub_department";
        } else if ($overtime->department != '-') {
            $level = " <b>Sub Unit $overtime->department</b> ";
            $sublevel = "Sub Unit $overtime->department";
        }

        $message = $this->load->view('html/overtime/email/reject_overtime', [
            'overtime' => $overtime,
            'rank' => $rank,
            'level' => $level,
        ], true);

        if ($rankLevel == 'spv') {
            $email = $this->getAdminEmail($overtime->sub_department_id);
        } else if ($rankLevel == 'asman') {
            $email = $this->getAdminEmail($overtime->sub_department_id);
            $email = $this->getSpvEmail($email, $overtime->division_id);
        } else if ($rankLevel == 'mgr') {
            $email = $this->getAdminEmail($overtime->sub_department_id);
            $email = $this->getSpvEmail($email, $overtime->division_id);
            $email = $this->getAsmanEmail($email, $overtime->sub_department_id);
        } else if ($rankLevel == 'head') {
            $email = $this->getAdminEmail($overtime->sub_department_id);
            $email = $this->getSpvEmail($email, $overtime->division_id);
            $email = $this->getAsmanEmail($email, $overtime->sub_department_id);
            $email = $this->getMgrEmail($email, $overtime->department_id);
        }

        $emailList = '';
        foreach ($email as $value) {
            if ($emailList == '') {
                $emailList = $value;
            } else {
                $emailList = $emailList . "," . $value;
            }
        }

        $dataEmail = [
            'alert_name' => 'OVERTIME_REJECTION',
            'email_to' => $emailList,
            'subject' => "Rejection ($rank) Lembur $sublevel ($overtime->overtime_date) dengan Task ID: $taskId",
            'subject_name' => "Spekta Alert: Rejection ($rank) Lembur $sublevel ($overtime->overtime_date) dengan Task ID: $taskId",
            'message' => $message,
        ];
        $insert = $this->Main->create('email', $dataEmail);
    }

    public function getAdminEmail($subId)
    {
        $email = [];
        $admin = $this->Main->getOne('pics', ['code' => 'overtime', 'sub_department_id' => $subId]);
        if ($admin && $admin->pic_emails != '') {
            $aEmail = explode(',', $admin->pic_emails);
            foreach ($aEmail as $value) {
                $email[] = $value;
            }
        }
        return $email;
    }

    public function getSpvEmail($email, $divId)
    {
        $isHaveSpv = $this->Hr->getOne('employees', ['division_id' => $divId], '*', ['rank_id' => ['5', '6']]);
        $isHaveSpvPLT = $this->Hr->getOne('employee_ranks', ['division_id' => $divId, 'status' => 'ACTIVE'], '*', ['rank_id' => ['5', '6']]);
        if ($isHaveSpv) {
            $email[] = $isHaveSpv->email;
        } else if($isHaveSpvPLT){
            $spvMail = $this->Hr->getDataById('employees', $isHaveSpvPLT->emp_id)->email;
            $email[] = $spvMail;
        }
        return $email;
    }

    public function getAsmanEmail($email, $subId)
    {
        $isHaveAsman = $this->Hr->getOne('employees', ['sub_department_id' => $subId], '*', ['rank_id' => ['3', '4']]);
        $isHaveAsmanPLT = $this->Hr->getOne('employee_ranks', ['sub_department_id' => $subId, 'status' => 'ACTIVE'], '*', ['rank_id' => ['3', '4']]);
        if ($isHaveAsman) {
            $email[] = $isHaveAsman->email;
        } else if($isHaveAsmanPLT){
            $asmanMail = $this->Hr->getDataById('employees', $isHaveAsmanPLT->emp_id)->email;
            $email[] = $asmanMail;
        }
        return $email;
    }

    public function getMgrEmail($email, $divId)
    {
        $isHaveMgr = $this->Hr->getOne('employees', ['department_id' => $divId, 'rank_id' => 2]);
        $isHaveMgrPLT = $this->Hr->getOne('employees', ['department_id' => $divId, 'rank_id' => 2, 'status' => 'ACTIVE']);
        if ($isHaveMgr) {
            $email[] = $isHaveMgr->email;
        } else if($isHaveMgrPLT){
            $mgrMail = $this->Hr->getDataById('employees', $isHaveMgrPLT->emp_id)->email;
            $email[] = $mgrMail;
        }
        return $email;
    }
}
