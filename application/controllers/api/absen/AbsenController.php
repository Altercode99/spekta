<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/CreatorJWT.php';

class AbsenController extends Erp_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AbsenModel', 'Absen');
        $this->Absen->myConstruct('hr');
        //Check For Token
        $this->jwt = new CreatorJWT();
        $this->jwt->checkToken($this->input->request_headers('authorization'));
    }

    public function scanQRAbsen()
    {
        $postfix = date('Ym');
        $post = fileGetContent();
        $today = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $jwtData = $this->jwt->me($this->input->request_headers('authorization'));
        $checkQr = $this->Hr->getWhereOr('gates', ['token' => $post->qrScanned, 'before_token' => $post->qrScanned])->row();
        if ($checkQr) {
            $isAbsen = $this->Hr->getOne("absen_$postfix", ['emp_id' => $jwtData['empId'], 'DATE(action_date)' => $date, 'action' => $post->action]);
            if (!$isAbsen) {
                $emp = $this->Hr->getOne('employees', ['id' => $jwtData['empId']]);
                $data = [
                    'location' => $emp->location,
                    'emp_id' => $emp->id,
                    'gate' => $checkQr->gate_name,
                    'action' => $post->action,
                    'action_date' => $today,
                    'qr_code' => $post->qrScanned,
                    'updated_by' => $emp->id,
                    'updated_at' => $today,
                ];
                $this->Hr->create("absen_$postfix", $data);
                $absen = [
                    'empId' => $emp->id,
                    'sapId' => $emp->sap_id,
                    'empName' => $emp->employee_name,
                    'gate' => $checkQr->gate_name,
                    'action' => $post->action,
                    'actionDate' => toIndoDateTime4($today),
                    'qrScanned' => $post->qrScanned,
                    'new' => $post->action == 'OUT' ? true : false,
                ];
                response(['qrScanned' => $post->qrScanned, 'absen' => $absen, 'action' => $post->action]);
            } else {
                response(['error' => 'Anda sudah absen pada tanggal ' . toIndoDateTime4($isAbsen->action_date)], 400);
            }
        } else {
            response(['error' => 'QR Code kadaluarsa, silahkan SCAN kembali QR Code yang baru!'], 404);
        }
    }

    public function getCurrentAbsen()
    {
        $postfix = date('Ym');
        $date = date('Y-m-d');
        $jwtData = $this->jwt->me($this->input->request_headers('authorization'));
        $isAbsen = $this->Hr->getOne("absen_$postfix", ['emp_id' => $jwtData['empId'], 'DATE(action_date)' => $date], '*', null, null, ['action_date' => 'DESC']);
        if ($isAbsen) {
            $emp = $this->Hr->getOne('employees', ['id' => $jwtData['empId']]);
            $actionDate = date('Y-m-d', strtotime($isAbsen->action_date));
            $absen = [
                'empId' => $emp->id,
                'sapId' => $emp->sap_id,
                'empName' => $emp->employee_name,
                'gate' => $isAbsen->gate,
                'action' => $isAbsen->action,
                'actionDate' => toIndoDateTime4($isAbsen->action_date),
                'qrScanned' => $isAbsen->qr_code,
                'new' => $actionDate == $date && $isAbsen->action == 'OUT' ? true : false,
            ];
            response(['exist' => true, 'absen' => $absen, 'action' => $isAbsen->action]);
        } else {
            response(['exist' => false]);
        }
    }

    public function getAbsens()
    {
        $params = getParam();
        $jwtData = $this->jwt->me($this->input->request_headers('authorization'));
        $postfix = $params['year_action_date'] . '' . $params['month_action_date'];
        $absens = $this->Absen->getAbsens($params, $jwtData['empId'], $postfix)->result();
        $fixAbsen = [];
        foreach ($absens as $absen) {
            $date = date('Y-m-d', strtotime($absen->action_date));
            $time = date('H:i', strtotime($absen->action_date));
            $time = str_replace(':', ".", $time);
            $time = floatval($time);

            if($absen->action == 'OUT' && $time <= 8.00) {
                $date = explode(' ', backDayToDate($date, 1))[0];
                $fixAbsen[$date]['gateOut'] = $absen->gate;
            } else if($absen->action == 'OUT'){
                $fixAbsen[$date]['gateOut'] = $absen->gate;
            }

            if($absen->action == 'IN') {
                $fixAbsen[$date]['date'] = toIndoDateDay($date);
                $fixAbsen[$date]['gateIn'] = $absen->gate;
            }

            $fixAbsen[$date][$absen->action] = toIndoDateTime5($absen->action_date);
        }

        $keys = array_column($fixAbsen, 'date');
        array_multisort($keys, SORT_ASC, $fixAbsen);

        $arrayAbsen = [];
        foreach ($fixAbsen as $absen) {
            $arrayAbsen[] = [
                'date' => $absen['date'],
                'IN' => isset($absen['IN']) ? $absen['IN'] : '-',
                'OUT' => isset($absen['OUT']) ? $absen['OUT'] : '-',
                'gateIn' => isset($absen['gateIn']) ? $absen['gateIn'] : '-',
                'gateOut' => isset($absen['gateOut']) ? $absen['gateOut'] : '-',
            ];
        }
        response(['absens' => $arrayAbsen]);
    }
}
