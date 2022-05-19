<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/CreatorJWT.php';

class OvertimeController extends Erp_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->jwt = new CreatorJWT();
        $this->jwt->checkToken($this->input->request_headers('authorization'));

        $this->load->model('api/OvertimeModel', 'Overtime');
        $this->Overtime->myConstruct('hr');
    }

    public function getOvertimes()
    {
        $params = getParam();
        $jwtData = $this->jwt->me($this->input->request_headers('authorization'));
        $ovts = [];
        $overtimes = $this->Overtime->getOvertimesDetail($jwtData['location'], $params['start'], $params['end'])->result();
        foreach ($overtimes as $overtime) {
            $ovts[] = [
                'id' => $overtime->id,
                'location' => $overtime->location,
                'task_id' => $overtime->task_id,
                'emp_task_id' => $overtime->emp_task_id,
                'npp' => $overtime->nip,
                'sap_id' => $overtime->sap_id,
                'employee_name' => $overtime->employee_name,
                'department' => $overtime->department,
                'sub_department' => $overtime->sub_department,
                'division' => $overtime->division,
                'rank' => $overtime->rank_name,
                'overtime_date' => $overtime->overtime_date,
                'start_date' => $overtime->start_date,
                'end_date' => $overtime->end_date,
                'status_day' => $overtime->status_day,
                'effective_hour' => $overtime->effective_hour,
                'break_hour' => $overtime->break_hour,
                'real_hour' => $overtime->real_hour,
                'overtime_hour' => $overtime->overtime_hour,
                'premi_overtime' => $overtime->premi_overtime,
                'overtime_value' => $overtime->overtime_value,
                'apv_spv' => $overtime->apv_spv,
                'apv_spv_nip' => $overtime->apv_spv_nip,
                'apv_spv_date' => $overtime->apv_spv_date,
                'apv_asman' => $overtime->apv_asman,
                'apv_asman_nip' => $overtime->apv_asman_nip,
                'apv_asman_date' => $overtime->apv_asman_date,
                'apv_ppic' => $overtime->apv_ppic,
                'apv_ppic_nip' => $overtime->apv_ppic_nip,
                'apv_ppic_date' => $overtime->apv_ppic_date,
                'apv_mgr' => $overtime->apv_mgr,
                'apv_mgr_nip' => $overtime->apv_mgr_nip,
                'apv_mgr_date' => $overtime->apv_mgr_date,
                'apv_head' => $overtime->apv_head,
                'apv_head_nip' => $overtime->apv_head_nip,
                'apv_head_date' => $overtime->apv_head_date,
            ];
        }

        response([
            'total_data' => count($ovts),
            'data' => $ovts
        ]);
    }
}