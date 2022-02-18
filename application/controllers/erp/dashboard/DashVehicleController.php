<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DashVehicleController extends Erp_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DashboardModel', 'Dashboard');
        $this->Dashboard->myConstruct('hr');
        $this->load->model('GaModel');
        $this->GaModel->myConstruct('general');
        $this->auth->isAuth();
    }

    public function getTotalByColumn()
    {
        $params = getParam();
        $revs = $this->GaModel->getVehicleRevGroupGrid($params)->result_array();
        $data = [];
        $total = 0;
        foreach ($revs as $key => $value) {
            $total += $value[$params['column']];
        }
        $keys = array_column($revs, $params['column']);
        array_multisort($keys, SORT_DESC, $revs);
        $color= [];
        $no = 1;
        foreach ($revs as $key => $value) {
            if($no == 1) {
                if($value[$params['column']]) {
                    $color[] = $value['color'];
                    $data[] = [
                        'name' => $value['vehicle_name'] . ' ( '. $value[$params['column']] .' KM)',
                        'y' => $value[$params['column']] > 0 ? ($value[$params['column']] / $total) * 100 : 0,
                        'sliced' => true,
                        'selected' => true
                    ];
                }
            } else {
                if($value[$params['column']] > 0) {
                    $color[] = $value['color'];
                    $data[] = [
                        'name' => $value['vehicle_name'] . ' ( '. $value[$params['column']] .' KM )',
                        'y' => $value[$params['column']] > 0 ? ($value[$params['column']] / $total) * 100 : 0
                    ];
                }
            }
            $no++;
        }
        response(['status' => 'success', 'series' => $data, 'color' => $color]);
    }
}